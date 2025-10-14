<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\QrCode;
use App\Models\PresenceIn;
use App\Models\PresenceOut;
use App\Models\Presences;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;


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
    try {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcode = $request->input('barcode');
        \Log::info('Scan barcode: ' . $barcode);

        $qrCode = QrCode::with('employee.position', 'employee.company')
            ->where('code', $barcode)
            ->first();

        if (!$qrCode || !$qrCode->employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $employee = $qrCode->employee;
        $employeeId = $employee->id;
        $now = now();
        $today = $now->toDateString();

        // Cek pasangan PresenceIn dengan PresenceOut di Presences
        $lastIn = PresenceIn::where('employees_id', $employeeId)
            ->latest('presence_time')
            ->first();

        $alreadyPaired = $lastIn
            ? Presences::where('presenceIn_id', $lastIn->id)->exists()
            : true;

        // 🔹 PRESENSI MASUK
        if (!$lastIn || $alreadyPaired) {
            $presenceIn = PresenceIn::create([
                'presence_in_status' => 'on_time',
                'latitude_in' => $request->input('latitude') ?? '-6.200000',
                'longitude_in' => $request->input('longitude') ?? '106.816666',
                'presence_time' => $now,
                'presence_date' => $today,
                'employees_id' => $employeeId,
            ]);

            return response()->json([
                'status' => 'masuk',
                'message' => 'Presensi masuk berhasil dicatat.',
                'employee' => [
                    'img_path' => $employee->profile_img_path ? asset('storage/' . $employee->profile_img_path) : asset('default-image.png'),
                    'full_name' => $employee->full_name,
                    'email' => $employee->email,
                    'company_name' => $employee->company?->name ?? '-',
                    'position_name' => $employee->position?->name ?? '-',
                ]
            ]);
        }

        // 🔸 PRESENSI KELUAR
        $presenceOut = PresenceOut::create([
            'latitude_out' => $request->input('latitude') ?? '-6.200000',
            'longitude_out' => $request->input('longitude') ?? '106.816666',
            'presence_time' => $now,
            'presence_date' => $today,
            'employees_id' => $employeeId,
        ]);

        $inTime = Carbon::parse($lastIn->presence_time);
        $outTime = Carbon::parse($presenceOut->presence_time);
        $totalTime = $outTime->greaterThan($inTime) ? $outTime->diffInMinutes($inTime) : 0;

        Presences::create([
            'total_time' => $totalTime,
            'presenceIn_id' => $lastIn->id,
            'presenceOut_id' => $presenceOut->id,
            'employees_id' => $employeeId,
        ]);

        return response()->json([
            'status' => 'keluar',
            'message' => 'Presensi keluar berhasil dicatat.',
            'total_minutes' => $totalTime,
            'employee' => [
                'img_path' => $employee->profile_img_path ? asset('storage/' . $employee->profile_img_path) : asset('default-image.png'),
                'full_name' => $employee->full_name,
                'email' => $employee->email,
                'company_name' => $employee->company?->name ?? '-',
                'position_name' => $employee->position?->name ?? '-',
            ]
        ]);
    } catch (\Exception $e) {
        \Log::error('Error in scan method: ' . $e->getMessage());
        return response()->json([
            'error' => 'Internal Server Error',
            'message' => $e->getMessage(),
        ], 500);
    }
}




}
