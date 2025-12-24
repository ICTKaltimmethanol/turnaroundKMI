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

    $kode = $request->kode;

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

            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'gate' => $gate,
            ], 200);
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
