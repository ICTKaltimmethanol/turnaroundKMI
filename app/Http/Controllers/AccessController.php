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
        $request->validate([
            'kode' => 'required|string'
        ]);

        $gates = [
            'Gate 1' => ['GATE1TA2025'],
            'Gate 2' => ['GATE2TA2025'],
            'Gate 3' => ['GATE3TA2025'],
        ];

        foreach ($gates as $gate => $codes) {
            if (in_array($request->kode, $codes)) {

                $request->session()->regenerate();

                session([
                    'gate' => $gate,
                    'gate_login_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'gate' => $gate,
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Kode tidak valid'
        ], 401);
    }



    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('akses.index');
    }

}
