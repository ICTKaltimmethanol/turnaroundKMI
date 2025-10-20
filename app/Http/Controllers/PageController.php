<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presences;
use App\Models\TimeOff;

class PageController extends Controller
{
    
    public function home()
    {
        $employee = Auth::guard('employee')->user(); //  Ambil employee yang login

        $presences = Presences::with(['presenceIn', 'presenceOut'])
                        ->where('employees_id', $employee->id)
                        ->latest()
                        ->take(3) 
                        ->get();

        return view('pages.home', compact('presences'));
    }
    
    public function absent()
    {
        $employee = Auth::guard('employee')->user(); 
        
        // Ambil semua presences milik employee, dengan eager load presenceIn dan presenceOut
        $presences = Presences::with(['presenceIn', 'presenceOut'])
                    ->where('employees_id', $employee->id)
                    ->latest()
                    ->get();

        return view('pages.absensi-list', compact('presences'));
    }

    public function cuti()
    {
        
        $employee = Auth::guard('employee')->user(); 

        $timeOffs = TimeOff::where('employees_id', $employee->id)
                    ->latest()
                    ->get();

        return view('pages.cuti',compact('timeOffs'));
    }
    
    public function cutiStore(Request $request)
    {
        $request->validate([
            'time_off_status' => 'required|string|max:255',
            'time_off_action' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Ambil employee yang sedang login
        $employee = auth()->user();

        $employee->timeOffs()->create([
            'time_off_status' => $request->time_off_status,
            'time_off_action' => $request->time_off_action,
            'description' => $request->description,
        ]);

        return redirect()->route('cuti')->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    public function info()
    {
        return view('pages.info');
    }

    public function profile()
    {
        $employee = Auth::guard('employee')->user(); // Ambil employee yang login
        return view('pages.profile', compact('employee'));
    }       

   

}
