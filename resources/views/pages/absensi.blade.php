<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Absent Page</title>

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite('resources/css/app.css')

    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body class="min-h-screen font-[Figtree] bg-gradient-to-br from-slate-900 via-slate-800 to-black text-white">

<!-- ================= HEADER ================= -->
<header class="w-full border-b border-white/5 bg-black/30 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

        <div class="flex flex-col leading-tight">
            <span class="text-lg font-semibold tracking-wide">ABSENSI TA</span>
            <span class="text-[10px] tracking-widest uppercase">
                Turn <span class="text-red-300">Around <span class="text-blue-400 font-bold">System</span></span>
            </span>
        </div>

        <div class="flex items-center gap-5">
            <div class="flex flex-col items-end leading-tight">
                <span class="text-[10px] tracking-widest text-white/40 uppercase">Gate in use</span>
                <span class="text-sm font-medium text-emerald-400">Gate {{ $gate }}</span>
            </div>

            <div class="h-8 w-px bg-white/10"></div>

            <div id="liveTime" class="text-sm font-mono tracking-wide text-white/70 min-w-[70px] text-right"></div>

            <div class="h-8 w-px bg-white/10"></div>

            <form action="{{ route('akses.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-3 py-1.5 text-[10px] tracking-widest uppercase
                    text-white/60 border border-white/20 rounded-md
                    hover:text-red-400 hover:border-red-400/40 hover:bg-red-500/5 transition">
                    End Session
                </button>
            </form>
        </div>
    </div>
</header>

<!-- ================= MAIN ================= -->
<main class="max-w-7xl mx-auto px-6 py-10">

    <div class="grid grid-cols-3 gap-6 mb-6">

        <div class="rounded-xl bg-emerald-500/20 border border-emerald-400/40 p-5 text-center">
            <p class="text-sm text-emerald-200">Absensi Masuk Hari Ini</p>
            <p id="alreadyInCount" class="text-3xl font-bold text-emerald-300">0</p>
        </div>

        <div class="rounded-xl bg-amber-500/20 border border-amber-400/40 p-5 text-center">
            <p class="text-sm text-amber-200">Belum Absensi Keluar Hari Ini</p>
            <p id="notOutCount" class="text-3xl font-bold text-amber-300">0</p>
        </div>

        <div class="rounded-xl bg-blue-500/20 border border-blue-400/40 p-5 text-center">
            <p class="text-sm text-blue-200">Total Seluruh Presensi</p>
            <p id="allOverPresence" class="text-3xl font-bold text-blue-300">0</p>
        </div>

    </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- SCANNER -->
        <div>
            <div class="relative rounded-2xl border border-white/20 bg-white/5 p-6">

                <input type="text" id="barcodeInput"
                    class="absolute inset-0 opacity-0"
                    autocomplete="off"
                    onkeydown="if(event.key==='Enter')handleScan()" />

                <img src="{{ asset('images/engineer.png') }}" class="mx-auto h-56" />

                <div class="mt-6 text-center">
                    <p class="text-lg font-semibold">Scan Barcode Anda</p>
                    <p class="text-sm text-white/60">Pastikan scanner aktif</p>
                </div>

                <!-- LOADER -->
                <div id="loadingIndicator"
                    class="hidden absolute inset-0 bg-black/70 backdrop-blur-sm
                    flex flex-col items-center justify-center rounded-2xl">

                    <div class="w-14 h-14 mb-4 relative">
                        <div class="absolute inset-0 rounded-full border border-white/10"></div>
                        <div class="absolute inset-0 rounded-full border-2 border-blue-400 border-t-transparent animate-spin"></div>
                    </div>

                    <p class="text-xs tracking-widest uppercase text-white/70">Processing</p>
                </div>
            </div>
        </div>

        <!-- RESULT -->
        <div class="lg:col-span-2">
            <div id="employeeData"
                class="rounded-2xl border border-dashed border-white/20 p-10 text-center text-white/50">
                Data pekerja akan tampil di sini
            </div>
             <div class="p-4 bg-center bg-cover bg-no-repeat 
                bg-[url('https://kaltimmethanol.com/themes/methanol/images/slider2_.jpg')]
                bg-gray-700 bg-blend-multiply rounded-xl shadow-lg overflow-hidden border border-gray-200">

                <div class="p-4 px-2 pt-2 rounded-lg text-center space-y-2">

                    <!-- Header -->
                    <div class="rounded-lg text-white text-center py-2 pt-4">
                        <h2 class="text-xl font-bold italic uppercase tracking-wide text-blue-400">
                            Turn <span class="text-red-400">Around</span> <span class="text-white">2025</span>
                        </h2>
                        <p class="text-sm">PT. Kaltim Methanol Industri</p>
                    </div>

                    <!-- Info Karyawan -->
                    <div class="py-2">
                        <h3 class="text-4xl font-black text-White-100">
                            ${data.employee.full_name}
                        </h3>

                        <p class="text-l text-gray-200 italic">
                            ${data.employee.company_name} - ${data.employee.position_name}
                        </p>

                        <p class="text-md text-gray-400">
                            ${data.employee.employee_code}
                        </p>

                     
                
                    </div>

                </div>
            </div>
            <div class="rounded-2xl  border border-dashed border-white/20 p-10 text-center text-white/50 mt-8">
                <h1 class="text-8xl">Cukup 1 kali scan untuk tiap orang, jangan lakukan secara memaksa untuk tiap id pekerja</h1>
            </div>
        </div>

    </div>
</main>

<!-- ================= SESSION MODAL ================= -->
<div id="sessionModal"
    class="fixed inset-0 hidden z-50 bg-black/70 backdrop-blur-sm items-center justify-center">
    <div class="bg-slate-900 border border-white/10 rounded-xl p-6 text-center">
        <div class="text-4xl text-red-400 mb-2">⚠</div>
        <p class="text-sm text-white/70">Session berakhir</p>
    </div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
/* ================= TIME ================= */
function updateLiveTime() {
    document.getElementById('liveTime').innerText = new Date().toLocaleTimeString();
}
setInterval(updateLiveTime, 1000); updateLiveTime();

/* ================= COOLDOWN CONFIG ================= */
const SCAN_COOLDOWN_MS = 60 * 1000;
const STORAGE_KEY = 'employee_scan_cooldown';

function getCooldownData() {
    return JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
}

function setCooldown(code) {
    const data = getCooldownData();
    data[code] = Date.now();
    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
}

function remainingCooldown(code) {
    const data = getCooldownData();
    if (!data[code]) return 0;
    return Math.max(0, SCAN_COOLDOWN_MS - (Date.now() - data[code]));
}

/* ================= SCAN ================= */
const input = document.getElementById('barcodeInput');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

window.onload = () => input.focus();
document.onclick = () => input.focus();

function handleScan() {
    const code = input.value.trim();
    if (code.length < 3) return;

    const remaining = remainingCooldown(code);
    if (remaining > 0) {
        document.getElementById('employeeData').innerHTML = `
            <div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 rounded">
                Barcode sudah discan.<br>
                Tunggu <strong>${Math.ceil(remaining / 1000)} detik</strong>.
            </div>`;
        input.value = '';
        return;
    }

    document.getElementById('loadingIndicator').classList.remove('hidden');
    input.disabled = true;

    fetch('/absensi/scan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ barcode: code })
    })
    .then(res => res.json())
    .then(data => {
        if (!data || !data.employee) throw 'error';

       setCooldown(data.employee.employee_code);

                let bgColor = '';
                if (data.status === 'masuk') {
                    bgColor = 'bg-green-100 border-green-400 text-green-700';
                } else if (data.status === 'keluar') {
                    bgColor = 'bg-yellow-100 border-yellow-400 text-yellow-700';
                } else {
                    bgColor = 'bg-gray-100 border-gray-400 text-gray-700';
                }
        document.getElementById('employeeData').innerHTML = `
            <div class="${bgColor} border px-4 py-3 rounded mb-4">
                <strong class="text-4xl">${data.status.toUpperCase()}</strong> - ${data.message ?? ''}
            </div>

            <div class="p-4 bg-center bg-cover bg-no-repeat 
                bg-[url('https://kaltimmethanol.com/themes/methanol/images/slider2_.jpg')]
                bg-gray-700 bg-blend-multiply rounded-xl shadow-lg overflow-hidden border border-gray-200">

                <div class="p-4 px-2 pt-2 rounded-lg text-center space-y-2">

                    <!-- Header -->
                    <div class="rounded-lg text-white text-center py-2 pt-4">
                        <h2 class="text-xl font-bold italic uppercase tracking-wide text-blue-400">
                            Turn <span class="text-red-400">Around</span> <span class="text-white">2025</span>
                        </h2>
                        <p class="text-sm">PT. Kaltim Methanol Industri</p>
                    </div>

                    <!-- Info Karyawan -->
                    <div class="py-2">
                        <h3 class="text-4xl font-black text-White-100">
                            ${data.employee.full_name}
                        </h3>

                        <p class="text-l text-gray-200 italic">
                            ${data.employee.company_name} - ${data.employee.position_name}
                        </p>

                        <p class="text-md text-gray-400">
                            ${data.employee.employee_code}
                        </p>

                     
                
                    </div>

                </div>
            </div>
        `;

        loadDailyCounter();

    })
    .catch(() => {
        document.getElementById('employeeData').innerHTML =
            `<div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded">
                Scan gagal atau data tidak ditemukan
            </div>`;
    })
    .finally(() => {
        input.value = '';
        input.disabled = false;
        document.getElementById('loadingIndicator').classList.add('hidden');
        input.focus();
    });
}

/* ================= COUNTER ================= */
function loadDailyCounter() {
    fetch('/absensi/counter-harian')
        .then(res => res.json())
        .then(d => {
            alreadyInCount.innerText = d.already_in ?? 0;
            notOutCount.innerText = d.not_out_yet ?? 0;
            allOverPresence.innerText = d.allOverPresence ?? 0;
        });

}

document.addEventListener('DOMContentLoaded', () => {
    loadDailyCounter(); 
});

setInterval(() => {
    if (!document.hidden) {
        loadDailyCounter();
    }
}, 5000);
</script>

</body>
</html>
