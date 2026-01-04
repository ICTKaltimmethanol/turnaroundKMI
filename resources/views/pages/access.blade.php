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

<div class=" max-w-lg bg-white/5 backdrop-blur
            border border-white/10 rounded-2xl p-8 shadow-2xl">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold tracking-wide">
            <span class="text-blue-400">ABSENSI</span>
            <span class="text-red-400">TURN AROUND</span>
        </h1>
        <p class="text-xs text-white/50 tracking-widest mt-2">
            TA 2025 · PT. Kaltim Methanol Industri
        </p>
    </div>

    <!-- INTRO -->
    <div class="text-center mb-8">
        <p class="text-lg font-semibold">
            Selamat Datang
        </p>
        <p class="text-sm text-white/60 leading-relaxed mt-2">
            Silakan pilih lokasi gate untuk melanjutkan
            proses absensi masuk dan keluar.
        </p>
    </div>

    <!-- GATE OPTIONS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        <a href="{{ url('/absensi/1') }}"
        class="w-full h-28 sm:h-32 rounded-xl
                border border-white/10
                bg-white/5 hover:bg-blue-500/10
                flex items-center justify-center
                transition hover:-translate-y-0.5">

            <p class="text-lg sm:text-xl font-bold tracking-widest text-blue-400">
                GATE 1
            </p>
        </a>

        <a href="{{ url('/absensi/2') }}"
        class="w-full h-28 sm:h-32 rounded-xl
                border border-white/10
                bg-white/5 hover:bg-indigo-500/10
                flex items-center justify-center
                transition hover:-translate-y-0.5">

            <p class="text-lg sm:text-xl font-bold tracking-widest text-indigo-400">
                GATE 2
            </p>
        </a>

        <a href="{{ url('/absensi/3') }}"
        class="w-full h-28 sm:h-32 rounded-xl
                border border-white/10
                bg-white/5 hover:bg-purple-500/10
                flex items-center justify-center
                transition hover:-translate-y-0.5">

            <p class="text-lg sm:text-xl font-bold tracking-widest text-purple-400">
                GATE 3
            </p>
        </a>

    </div>



    <!-- FOOTER -->
    <p class="text-center text-[10px] text-white/40 tracking-widest">
        AUTHORIZED PERSONNEL ONLY
    </p>

</div>

</body>
</html>
