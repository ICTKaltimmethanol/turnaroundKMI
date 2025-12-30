<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AccessController extends Controller
{
    public function index()
    {
        return view('pages.access');
    }

    public function cek(Request $request): JsonResponse
    {
        $kode = trim($request->input('kode'));

        if ($kode === '') {
            return response()->json([
                'success' => false,
                'message' => 'Kode tidak boleh kosong'
            ], 422);
        }

        $gates = [
            'Gate 1' => 'GATE1TA2025',
            'Gate 2' => 'GATE2TA2025',
            'Gate 3' => 'GATE3TA2025',
        ];

        foreach ($gates as $gate => $code) {
            if (hash_equals($code, $kode)) {

                return response()->json([
                    'success' => true,
                    'gate' => $gate,
                ])->withCookie(
                    cookie(
                        'gate',
                        $gate,
                        120,    // menit
                        null,
                        null,
                        true,   // secure (HTTPS)
                        true    // httpOnly
                    )
                );
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Kode tidak valid'
        ], 401);
    }

    public function logout()
    {
        return redirect()
            ->route('akses.index')
            ->withCookie(cookie()->forget('gate'));
    }
}
