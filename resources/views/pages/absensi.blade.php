<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Absensi TA</title>

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite('resources/css/app.css')

    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body class="min-h-screen font-[Figtree] bg-gray-100 text-gray-800">

<!-- ================= HEADER ================= -->
<header class="w-full border-b border-gray-200 bg-white">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

        <div>
            <div class="text-lg font-semibold tracking-wide">ABSENSI TA</div>
            <div class="text-[10px] tracking-widest uppercase text-gray-500">
                Turn <span class="text-red-500">Around</span>
                <span class="text-blue-600 font-bold">System</span>
            </div>
        </div>

        <div class="flex items-center gap-5">
            <div class="text-right">
                <div class="text-[10px] uppercase tracking-widest text-gray-400">Gate in use</div>
                <div class="text-sm font-medium text-emerald-600">Gate {{ $gate }}</div>
            </div>

            <div class="h-6 w-px bg-gray-300"></div>

            <div id="liveTime" class="text-sm font-mono text-gray-600 min-w-[70px] text-right"></div>

            <div class="h-6 w-px bg-gray-300"></div>

            <form action="{{ route('akses.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-3 py-1.5 text-[10px] uppercase tracking-widest
                    text-gray-600 border border-gray-300 rounded
                    hover:text-red-600 hover:border-red-400 hover:bg-red-50 transition">
                    End Session
                </button>
            </form>
        </div>
    </div>
</header>

<!-- ================= MAIN ================= -->
<main class="max-w-7xl mx-auto px-6 py-10">

    <!-- COUNTER -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="rounded-xl bg-green-50 border border-green-300 p-5 text-center">
            <p class="text-sm text-green-700">Absensi Masuk Hari Ini</p>
            <p id="alreadyInCount" class="text-3xl font-bold text-green-700">0</p>
        </div>

        <div class="rounded-xl bg-yellow-50 border border-yellow-300 p-5 text-center">
            <p class="text-sm text-yellow-700">Belum Absensi Keluar Hari Ini</p>
            <p id="notOutCount" class="text-3xl font-bold text-yellow-700">0</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- SCANNER -->
        <div>
            <div class="relative rounded-2xl border border-gray-300 bg-white p-6 shadow">

                <input type="text" id="barcodeInput"
                    class="absolute inset-0 opacity-0"
                    autocomplete="off"
                    onkeydown="if(event.key==='Enter')handleScan()" />

                <img src="{{ asset('images/engineer.png') }}" class="mx-auto h-56" />

                <div class="mt-6 text-center">
                    <p class="text-lg font-semibold">Scan Barcode Anda</p>
                    <p class="text-sm text-gray-500">Pastikan scanner aktif</p>
                </div>

                <!-- LOADER -->
                <div id="loadingIndicator"
                    class="hidden absolute inset-0 bg-white/80 backdrop-blur-sm
                    flex flex-col items-center justify-center rounded-2xl">

                    <div class="w-14 h-14 mb-4 relative">
                        <div class="absolute inset-0 rounded-full border border-gray-300"></div>
                        <div class="absolute inset-0 rounded-full border-2 border-blue-500 border-t-transparent animate-spin"></div>
                    </div>

                    <p class="text-xs uppercase tracking-widest text-gray-500">Processing</p>
                </div>
            </div>
        </div>

        <!-- RESULT -->
        <div class="lg:col-span-2">
            <div id="employeeData"
                class="rounded-2xl border border-gray-300 bg-white p-10 text-center text-gray-400 shadow">
                Data karyawan akan tampil di sini
            </div>
        </div>

    </div>
</main>

<!-- ================= SCRIPT ================= -->
<script>
/* ===== TIME ===== */
function updateLiveTime() {
    liveTime.innerText = new Date().toLocaleTimeString();
}
setInterval(updateLiveTime, 1000); updateLiveTime();

/* ===== COOLDOWN ===== */
const SCAN_COOLDOWN_MS = 60000;
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

/* ===== SCAN ===== */
const input = document.getElementById('barcodeInput');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

window.onload = () => input.focus();
document.onclick = () => input.focus();

function handleScan() {
    const code = input.value.trim();
    if (code.length < 3) return;

    const remaining = remainingCooldown(code);
    if (remaining > 0) {
        employeeData.innerHTML = `
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded">
                Barcode sudah discan.<br>
                Tunggu <strong>${Math.ceil(remaining / 1000)} detik</strong>.
            </div>`;
        input.value = '';
        return;
    }

    loadingIndicator.classList.remove('hidden');
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

        let color = 'bg-red-100 border-red-400 text-red-700';
        let label = 'ERROR';

        if (data.status === 'success') {
            if (data.type === 'in') {
                color = 'bg-green-100 border-green-400 text-green-700';
                label = 'ABSENSI MASUK';
            } else if (data.type === 'out') {
                color = 'bg-yellow-100 border-yellow-400 text-yellow-800';
                label = 'ABSENSI KELUAR';
            }
        }

        employeeData.innerHTML = `
            <div class="${color} border px-4 py-3 rounded mb-4">
                <strong>${label}</strong><br>${data.message ?? ''}
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow">
                <h2 class="text-xl font-bold text-blue-600 mb-1">
                    Turn <span class="text-red-500">Around</span> 2025
                </h2>
                <p class="text-sm text-gray-500 mb-4">PT. Kaltim Methanol Industri</p>

                <h3 class="text-xl font-bold">${data.employee.full_name}</h3>
                <p class="italic text-gray-600">
                    ${data.employee.company_name} - ${data.employee.position_name}
                </p>
                <p class="text-sm text-gray-500">${data.employee.employee_code}</p>
            </div>
        `;

        loadDailyCounter();
    })
    .catch(() => {
        employeeData.innerHTML = `
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                Scan gagal atau data tidak ditemukan
            </div>`;
    })
    .finally(() => {
        input.value = '';
        input.disabled = false;
        loadingIndicator.classList.add('hidden');
        input.focus();
    });
}

/* ===== COUNTER ===== */
function loadDailyCounter() {
    fetch('/absensi/counter-harian')
        .then(res => res.json())
        .then(d => {
            alreadyInCount.innerText = d.already_in ?? 0;
            notOutCount.innerText = d.not_out_yet ?? 0;
        });
}
</script>

</body>
</html>
