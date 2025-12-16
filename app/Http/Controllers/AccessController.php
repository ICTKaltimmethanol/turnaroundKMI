<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessController extends Controller
{
    
    public function index()
    {
        return view('pages.access');
    }

    public function cek(Request $request)
    {
        $kode = $request->input('kode');

        $gates = [
            'Gate 1' => ['GATE1TA2025'],
            'Gate 2' => ['GATE2TA2025'],
            'Gate 3' => ['GATE3TA2025'],
        ];

        foreach ($gates as $gate => $codes) {
            if (in_array($kode, $codes)) {

                session([
                    'gate' => $gate,
                    'gate_login_at' => now(),
                ]);

                return response()->json([
                    'status' => 'success',
                    'gate' => $gate,
                ]);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Kode tidak valid'
        ], 401);
    }


    public function logout()
    {
        session()->forget(['gate', 'gate_login_at']);
        return redirect()->route('akses.index');
    }
}
