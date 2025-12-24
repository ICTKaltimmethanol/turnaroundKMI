<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Absensi TA 2025 - PT. Kaltim Methanol Industri</title>

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
            ACCESS CONTROL SYSTEM
        </p>
    </div>

    <!-- FORM -->
    <form onsubmit="return cekAkses()">
        <div class="relative mb-6">
            <input
                id="kode"
                type="password"
                placeholder="MASUKKAN KODE AKSES"
                autofocus
                class="w-full rounded-xl bg-black/40 border border-white/20
                       px-4 py-3 pr-12 text-center tracking-widest
                       focus:outline-none focus:ring-2 focus:ring-blue-400">

            <button type="button"
                    onclick="toggleVisibility()"
                    class="absolute inset-y-0 right-3 flex items-center
                           text-white/40 hover:text-white/70">
                <span id="eyeOpen">👁</span>
                <span id="eyeClosed" class="hidden">🙈</span>
            </button>
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-gradient-to-r
                   from-blue-500 to-indigo-600
                   hover:from-blue-400 hover:to-indigo-500
                   py-3 text-sm font-semibold tracking-widest">
            BUKA AKSES
        </button>
    </form>

    <p class="mt-6 text-center text-xs text-white/40 tracking-widest">
        AUTHORIZED PERSONNEL ONLY
    </p>
</div>

<!-- MODAL -->
<div id="modal" class="fixed inset-0 hidden items-center justify-center bg-black/60">
    <div class="bg-slate-900 rounded-xl p-6 text-center max-w-sm w-full">
        <div id="modalIcon" class="text-4xl mb-3"></div>
        <h2 id="modalTitle" class="font-semibold"></h2>
        <p id="modalMessage" class="text-sm text-white/60 mt-1"></p>

        <button onclick="closeModal()"
                class="mt-5 w-full rounded-xl bg-white/10 py-2 text-xs">
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
   CEK AKSES (FINAL FIX)
================================ */
async function cekAkses() {
    const kode = document.getElementById('kode').value.trim();

    if (!kode) {
        showModal('error', 'INPUT REQUIRED', 'Kode akses tidak boleh kosong');
        return false;
    }

    try {
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

        // 🔑 SINKRON DENGAN BACKEND
        if (!data.success) {
            showModal('error', 'ACCESS DENIED', data.message);
            return false;
        }

        // ✅ REDIRECT AMAN
        window.location.replace("{{ route('absensi.index') }}");

    } catch (e) {
        showModal('error', 'SERVER ERROR', 'Tidak dapat terhubung ke server');
    }

    return false;
}

/* ===============================
   MODAL
================================ */
function showModal(type, title, message) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    modalTitle.innerText = title;
    modalMessage.innerText = message;

    modalIcon.innerText = type === 'error' ? '✕' : '✓';
}

function closeModal() {
    modal.classList.add('hidden');
    document.getElementById('kode').value = '';
    document.getElementById('kode').focus();
}
</script>

</body>
</html>
