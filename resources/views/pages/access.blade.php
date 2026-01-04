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
        <p class="text-xs text-white/50 tracking-widest mt-2 mb-8">
            TA 2025 · PT. Kaltim Methanol Industri
        </p>
    </div>

  

    <!-- GATE OPTIONS -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">

    <!-- GATE 1 -->
    <a href="{{ url('/absensi/1') }}"
       class="group h-50 py-8 sm:h-32
              rounded-xl
              border border-blue-400/20
              bg-white/5
              flex flex-col items-center justify-center
              transition-all duration-300
              hover:border-blue-400
              hover:bg-blue-500/10
              hover:shadow-[0_0_25px_-8px_rgba(59,130,246,0.45)]
              focus:outline-none focus:ring-2 focus:ring-blue-400">
        <span class="text-xl sm:text-2xl font-semibold tracking-widest text-blue-400">
            GATE 1
        </span>
    </a>

    <!-- GATE 2 -->
    <a href="{{ url('/absensi/2') }}"
       class="group h-50 py-8 sm:h-32
              rounded-xl
              border border-indigo-400/20
              bg-white/5
              flex flex-col items-center justify-center
              transition-all duration-300
              hover:border-indigo-400
              hover:bg-indigo-500/10
              hover:shadow-[0_0_25px_-8px_rgba(99,102,241,0.45)]
              focus:outline-none focus:ring-2 focus:ring-indigo-400">
        <span class="text-xl sm:text-2xl font-semibold tracking-widest text-indigo-400">
            GATE 2
        </span>
    </a>

    <!-- GATE 3 -->
    <a href="{{ url('/absensi/3') }}"
       class="group h-50 py-8 sm:h-32
              rounded-xl
              border border-purple-400/20
              bg-white/5
              flex flex-col items-center justify-center
              transition-all duration-300
              hover:border-purple-400
              hover:bg-purple-500/10
              hover:shadow-[0_0_25px_-8px_rgba(168,85,247,0.45)]
              focus:outline-none focus:ring-2 focus:ring-purple-400">
        <span class="text-xl sm:text-2xl font-semibold tracking-widest text-purple-400">
            GATE 3
        </span>
    </a>

</div>




    <!-- FOOTER -->
    <p class="text-center text-[10px] text-white/40 tracking-widest">
        AUTHORIZED PERSONNEL ONLY
    </p>

</div>

</body>
</html>
