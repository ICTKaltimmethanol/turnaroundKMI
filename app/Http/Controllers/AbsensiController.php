<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\QrCode;
use App\Models\PresenceIn;
use App\Models\PresenceOut;
use App\Models\Presences;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;



class AbsensiController extends Controller
{
    
    public function index($gate)
    {
        if (!in_array($gate, [1, 2, 3])) {
            return redirect()->route('akses.index');
        }

        return view('pages.absensi', [
            'gate' => $gate
        ]);
    }

    public function counterMasukDanBelumKeluar()
    {
        $start = Carbon::today()->startOfDay();
        $end   = Carbon::today()->endOfDay();

        $alreadyIn = Presences::whereBetween('created_at', [$start, $end])
            ->whereNotNull('presenceIn_id')
            ->count();

        $notOutYet = Presences::whereBetween('created_at', [$start, $end])
            ->whereNotNull('presenceIn_id')
            ->whereNull('presenceOut_id')
            ->count();

        $allOverPresence = Presences::Count();

        return response()->json([
            'already_in' => $alreadyIn,
            'not_out_yet' => $notOutYet,
            'allOverPresence' => $allOverPresence,
        ]);
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

    public function scan(Request $request) {
        $request->validate([
            'barcode' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {
            $barcode = $request->input('barcode');

            // Ambil QR Code + relasi employee, position, company
            $qrCode = QrCode::with('employee.position', 'employee.company')
                ->where('code', $barcode)
                ->first();

            if (!$qrCode || !$qrCode->employee) {
                return response()->json([
                    'message' => 'QR Code tidak valid, tidak ada karyawan terhubung'
                ], 404);
            }

            $employee = $qrCode->employee;
            $employeeId = $employee->id;
            $now = now();

            $latitude = $request->filled('latitude') ? $request->input('latitude') : '-6.200000';
            $longitude = $request->filled('longitude') ? $request->input('longitude') : '106.816666';

            // Ambil presensi terakhir yang belum memiliki out
            $lastPresence = Presences::where('employees_id', $employeeId)
                ->whereNull('presenceOut_id')
                ->latest('id')
                ->lockForUpdate()
                ->first();

            /**
             * =========================
             * PRESENSI MASUK
             * =========================
             */
            if (!$lastPresence) {
                // Record PresenceIn
                $presenceIn = PresenceIn::create([
                    'presence_in_status' => 'on_time',
                    'latitude_in' => $latitude,
                    'longitude_in' => $longitude,
                    'presence_time' => $now->toTimeString(),
                    'presence_date' => $now->toDateString(),
                    'employees_id' => $employeeId,

                 
                ]);

                // Buat record Presences
                Presences::create([
                    'presenceIn_id' => $presenceIn->id,
                    'employees_id' => $employeeId,
                    'employees_company_id' => $employee->company_id,
                    'employees_position_id' => $employee->position_id,
                       // Snapshot data
                    'employee_name'  => $employee->full_name,
                    'employee_code'  => $employee->employees_code,
                    'company_name'   => $employee->company?->name,
                    'position_name'  => $employee->position?->name,
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

            /**
             * =========================
             * PRESENSI KELUAR
             * =========================
             */
            $lastIn = PresenceIn::find($lastPresence->presenceIn_id);

            // Record PresenceOut
            $presenceOut = PresenceOut::create([
                'latitude_out' => $latitude,
                'longitude_out' => $longitude,
                'presence_time' => $now->toTimeString(),
                'presence_date' => $now->toDateString(),
                'employees_id' => $employeeId,
            ]);

            // Selisih waktu masuk-keluar (support lintas hari)
            $inDateTime = Carbon::parse($lastIn->presence_date . ' ' . $lastIn->presence_time);
            $outDateTime = Carbon::parse($presenceOut->presence_date . ' ' . $presenceOut->presence_time);

            if ($outDateTime->lessThan($inDateTime)) {
                $outDateTime->addDay();
            }

            // Total menit
            $totalMinutes = $outDateTime->diffInMinutes($inDateTime);

            // Total jam penuh (depannya saja)
            // $totalHoursOnly = floor($totalMinutes / 60);

            // Format jam:menit untuk readability
            // $remainingMinutes = $totalMinutes % 60;
            // $totalReadable = sprintf('%02d jam %02d menit', $totalHoursOnly, $remainingMinutes);

            // Update Presences dengan out dan total waktu
            $lastPresence->update([
                'presenceOut_id' => $presenceOut->id,
                'total_time' => $totalMinutes,
                //'total_time' => $totalHoursOnly, // simpan hanya jam penuh
            ]);

            DB::commit();

            return response()->json([
                'status' => 'keluar',
                'message' => 'Presensi keluar berhasil dicatat.',
                'total_minutes' => $totalMinutes,
                //'total_hours' => $totalHoursOnly, // jam penuh
                //'total_time_readable' => $totalReadable,
                'employee' => [
                    'full_name' => $employee->full_name,
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
