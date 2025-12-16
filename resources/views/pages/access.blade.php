<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- SEO Meta -->
    <title>Absensi TA 2025 - PT. Kaltim Methanol Industri</title>
    <meta name="description" content="Sistem Absensi Online Turn Around 2025 PT. Kaltim Methanol Industri. Digunakan untuk pencatatan kehadiran pekerja perbantuan kegiatan TA 2025 di Bontang.">
    <meta name="keywords" content="Sistem Absensi, Absensi Online, TA 2025, PT Kaltim Methanol Industri, KMI Bontang, Absensi Karyawan, Absensi Kontraktor, Turn Around KMI, KMI, TA, 2025">

    <!-- Open Graph (untuk preview di media sosial) -->
    <meta property="og:title" content="Sistem Absensi TA 2025 - PT. Kaltim Methanol Industri" />
    <meta property="og:description" content="Aplikasi absensi online untuk kegiatan Turn Around 2025 PT. KMI Bontang. Sistem pencatatan kehadiran personel selama kegiatan turn around berlangsung." />
    <meta property="og:image" content="https://kaltimmethanol.com/themes/methanol/images/slider2_.jpg" />
    <meta property="og:url" content="https://turnaround-kmi.com/" />
    <meta property="og:type" content="website" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{asset('images/engineer2.png')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <title>Akses Absensi</title>
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
            ACCESS CONTROL SYSTEM
        </p>
    </div>

    <!-- FORM -->
    <form onsubmit="return cekAkses()">

        <!-- PASSWORD -->
        <div class="relative mb-6">
            <input
                id="kode"
                type="password"
                placeholder="MASUKKAN KODE AKSES"
                autofocus
                class="w-full rounded-xl bg-black/40 border border-white/20
                       px-4 py-3 pr-12 text-center tracking-widest
                       focus:outline-none focus:ring-2 focus:ring-blue-400">

            <!-- EYE -->
            <button type="button"
                    onclick="toggleVisibility()"
                    tabindex="-1"
                    class="absolute inset-y-0 right-3 flex items-center
                           text-white/40 hover:text-white/70 transition">
                <span id="eyeOpen">👁</span>
                <span id="eyeClosed" class="hidden">🙈</span>
            </button>
        </div>

        <!-- BUTTON -->
        <button
            type="submit"
            class="w-full rounded-xl bg-gradient-to-r
                   from-blue-500 to-indigo-600
                   hover:from-blue-400 hover:to-indigo-500
                   transition-all duration-200
                   py-3 text-sm font-semibold tracking-widest
                   shadow-lg shadow-blue-500/30">
            BUKA AKSES
        </button>
    </form>

    <p class="mt-6 text-center text-xs text-white/40 tracking-widest">
        AUTHORIZED PERSONNEL ONLY
    </p>
</div>

<!-- MODAL -->
<div id="modal"
     class="fixed inset-0 z-50 hidden items-center justify-center
            bg-black/60 backdrop-blur-sm">

    <div class="w-full max-w-sm bg-slate-900 border border-white/10
                rounded-2xl p-6 text-center">

        <div id="modalIcon" class="text-4xl mb-3"></div>
        <h2 id="modalTitle" class="font-semibold mb-1"></h2>
        <p id="modalMessage" class="text-sm text-white/60"></p>

        <!-- BUTTON (HANYA ERROR) -->
        <button id="modalButton"
                onclick="closeModal()"
                class="mt-5 w-full rounded-xl bg-white/10
                       hover:bg-white/15 border border-white/20
                       py-2 text-xs tracking-widest transition hidden">
            OK
        </button>
    </div>
</div>

<script>
/* ===============================
   TOGGLE PASSWORD
================================ */
function toggleVisibility() {
    const input = document.getElementById('kode');
    eyeOpen.classList.toggle('hidden');
    eyeClosed.classList.toggle('hidden');
    input.type = input.type === 'password' ? 'text' : 'password';
}

/* ===============================
   CEK AKSES
================================ */
async function cekAkses() {
    const kode = document.getElementById('kode').value.trim();

    const res = await fetch("{{ route('akses.cek') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({ kode })
    });

    const data = await res.json();

    if (!res.ok) {
        showModal('error', 'ACCESS DENIED', data.message);
        return false;
    }

    showModal('success', 'ACCESS GRANTED', `Masuk ${data.gate}`);

    setTimeout(() => {
        window.location.href = "{{ route('absensi.index') }}";
    }, 1200);

    return false;
}

/* ===============================
   MODAL HANDLER
================================ */
function showModal(type, title, message) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    modalTitle.innerText = title;
    modalMessage.innerText = message;

    if (type === 'success') {
        modalIcon.innerText = '✓';
        modalIcon.className = 'text-4xl mb-3 text-emerald-400';
        modalButton.classList.add('hidden');
    } else {
        modalIcon.innerText = '✕';
        modalIcon.className = 'text-4xl mb-3 text-red-400';
        modalButton.classList.remove('hidden');
    }
}

function closeModal() {
    modal.classList.add('hidden');
    document.getElementById('kode').value = '';
    document.getElementById('kode').focus();
}
</script>

</body>
</html>
