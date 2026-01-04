<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Welcome - Absensi TA 2025</title>

    <link rel="icon" type="image/png" href="{{ asset('images/engineer2.png') }}" />

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black
             text-white flex items-center justify-center">

<div class="w-full max-w-md bg-white/5 backdrop-blur
            border border-white/10 rounded-xl p-8 shadow-xl">

    <!-- HEADER -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold tracking-wide">
            <span class="text-blue-400">ABSENSI</span>
            <span class="text-red-400">TURN AROUND</span>
        </h1>
        <p class="text-xs text-white/50 tracking-widest mt-2">
            TA 2025 · PT. Kaltim Methanol Industri
        </p>
    </div>

    <!-- WELCOME TEXT -->
    <div class="text-center space-y-4 mb-8">
        <p class="text-lg font-semibold">
            Selamat Datang 👋
        </p>
        <p class="text-sm text-white/60 leading-relaxed">
            Sistem Absensi Turn Around digunakan untuk pencatatan
            kehadiran masuk dan keluar selama kegiatan TA berlangsung.
        </p>
    </div>

    <!-- ACTION -->
    <div class="space-y-4">
        <!-- Contoh langsung ke Gate 1 -->
        <a href="{{ url('/absensi/1') }}"
           class="block text-center rounded-xl bg-gradient-to-r
                  from-blue-500 to-indigo-600
                  hover:from-blue-400 hover:to-indigo-500
                  py-3 text-sm font-semibold tracking-widest transition">
            MASUK ABSENSI
        </a>

        <p class="text-center text-xs text-white/40 tracking-widest">
            AUTHORIZED PERSONNEL ONLY
        </p>
    </div>

</div>

</body>
</html>
