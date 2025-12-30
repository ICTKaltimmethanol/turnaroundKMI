<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekGate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $gate = $request->cookie('gate');

        if (!in_array($gate, ['Gate 1', 'Gate 2', 'Gate 3'], true)) {
            return redirect()->route('akses.index');
        }

        return $next($request);
    }

}
