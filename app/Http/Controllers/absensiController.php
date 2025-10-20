<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\QrCode;
use App\Models\PresenceIn;
use App\Models\PresenceOut;
use App\Models\Presences;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;



class AbsensiController extends Controller
{
    public function index() {
        return view('pages.absensi');
    }

    /*public function scan(Request $request)
    {
        try {
            $request->validate([
                'barcode' => 'required|string',
            ]);

            $barcode = $request->input('barcode');
            \Log::info('Scan barcode: ' . $barcode);

            $qrCode = QrCode::with('employee.position')
                ->where('code', $barcode)
                ->first();

            \Log::info('QR Code result:', ['qrCode' => $qrCode]);

            if (!$qrCode || !$qrCode->employee) {
                \Log::warning('Employee not found for barcode: ' . $barcode);
                return response()->json(['message' => 'Employee not found'], 404);
            }

            $employee = $qrCode->employee;

            return response()->json([
                'img_path' => $employee->profile_img_path ? asset('storage/' . $employee->profile_img_path) : asset('default-image.png'),
                'full_name' => $employee->full_name,
                'email' => $employee->email,
                'company_name' => $employee->company ? $employee->company->name : '-',
                'position_name' => $employee->position ? $employee->position->name : '-',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in scan method: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
        
    }*/

public function scan(Request $request)
{
    $request->validate([
        'barcode' => 'required|string',
    ]);

    DB::beginTransaction();

    try {
        $barcode = $request->input('barcode');
        Log::info('Scan barcode: ' . $barcode);

        $qrCode = QrCode::with('employee.position', 'employee.company')
            ->where('code', $barcode)
            ->first();

        if (!$qrCode || !$qrCode->employee) {
            return response()->json(['message' => 'QR Code tidak valid, tidak ada karyawan terhubung'], 404);
        }

        $employee = $qrCode->employee;
        $employeeId = $employee->id;
        $now = now();
        $today = $now->toDateString();

        // Ambil latitude & longitude (fallback jika kosong atau null)
        $latitude = $request->filled('latitude') ? $request->input('latitude') : '-6.200000';
        $longitude = $request->filled('longitude') ? $request->input('longitude') : '106.816666';

        // Ambil presensi masuk terakhir
        $lastIn = PresenceIn::where('employees_id', $employeeId)
            ->latest('presence_time')
            ->first();

        // Cek apakah presensi terakhir belum punya pasangan (belum presensi keluar)
        $presenceRecord = lastIn 
            ? !Presence::where('presenceIn_id', $lastIn->id)->first()
            : null;
        $hasUnpairedIn = $presenceRecord && is_null ($presenceRecord->presenceOut_id);

        // PRESENSI MASUK
        if (!$lastIn || !$hasUnpairedIn) {
            $presenceIn = PresenceIn::create([
                'presence_in_status' => 'on_time', // Optional: bisa dihitung telat jika ada jam kerja
                'latitude_in' => $latitude,
                'longitude_in' => $longitude,
                'presence_time' => $now,
                'presence_date' => $today,
                'employees_id' => $employeeId,
            ]);

            // Buat entry awal di tabel presences
            Presences::create([
                'presenceIn_id' => $presenceIn->id,
                'employees_id' => $employeeId,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'masuk',
                'message' => 'Presensi masuk berhasil dicatat.',
                'employee' => [
                    'full_name' => $employee->full_name ?? '-',
                    'employee_code' => $employee->employees_code ?? '-',
                    'company_name' => $employee->company?->name ?? '-',
                    'position_name' => $employee->position?->name ?? '-',
                ]
            ]);
        }

        // 🔸 PRESENSI KELUAR
        $presenceOut = PresenceOut::create([
            'latitude_out' => $latitude,
            'longitude_out' => $longitude,
            'presence_time' => $now,
            'presence_date' => $today,
            'employees_id' => $employeeId,
        ]);

        $inTime = Carbon::parse($lastIn->presence_time);
        $outTime = Carbon::parse($presenceOut->presence_time);
        $totalTime = $outTime->gt($inTime) ? $outTime->diffInMinutes($inTime) : 0;

        // Ambil record presences, jika tidak ada, buat baru (fail-safe)
        $presences = Presences::firstOrCreate(
            ['presenceIn_id' => $lastIn->id],
            ['employees_id' => $employeeId]
        );

        $presences->update([
            'presenceOut_id' => $presenceOut->id,
            'total_time' => $totalTime,
        ]);

        DB::commit();

        return response()->json([
            'status' => 'keluar',
            'message' => 'Presensi keluar berhasil dicatat.',
            'total_minutes' => $totalTime,
            'employee' => [
                'full_name' => $employee->full_name,
                'email' => $employee->email,
                'employee_code' => $employee->employees_code,
                'company_name' => $employee->company?->name ?? '-',
                'position_name' => $employee->position?->name ?? '-',
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error in scan method: ' . $e->getMessage());

        return response()->json([
            'error' => 'Internal Server Error',
            'message' => $e->getMessage(),
        ], 500);
    }
}





}
