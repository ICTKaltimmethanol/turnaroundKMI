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


    <!-- Header -->
    <header class="w-full border-b border-white/5 bg-black/30 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

            <!-- BRAND -->
            <div class="flex flex-col leading-tight">
                <span class="text-lg font-semibold tracking-wide text-white">
                    ABSENSI TA
                </span>
                <span class="text-[10px] tracking-widest uppercase">
                    Turn <span class=" text-red-300 uppercase">Around <span class="text-blue-400 font-bold">System</span></span>
                </span>
            </div>

            <!-- STATUS BAR -->
            <div class="flex items-center gap-5">

                <!-- POS -->
                <div class="flex flex-col items-end leading-tight">
                    <span class="text-[10px] tracking-widest text-white/40 uppercase">
                        Active Post
                    </span>
                    <span class="text-sm font-medium text-emerald-400">
                        {{ $gate }}
                    </span>
                </div>

                <!-- Divider -->
                <div class="h-8 w-px bg-white/10"></div>

                <!-- Time -->
                <div id="liveTime"
                    class="text-sm font-mono tracking-wide text-white/70 min-w-[70px] text-right">
                </div>

                <!-- Divider -->
                <div class="h-8 w-px bg-white/10"></div>

                <!-- End Button -->
                <form action="{{ route('akses.logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="px-3 py-1.5 text-[10px] tracking-widest uppercase
                            text-white/60 border border-white/20 rounded-md
                            hover:text-red-400 hover:border-red-400/40
                            hover:bg-red-500/5 transition">
                        End Session
                    </button>
                </form>

            </div>
        </div>
    </header>



    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-10">
        <div class="grid grid-cols-2 gap-6 mb-6">

            <div class="rounded-xl bg-emerald-500/20 border border-emerald-400/40 p-5 text-center">
                <p class="text-sm text-emerald-200">Absensi Masuk Hari Ini</p>
                <p id="alreadyInCount" class="text-3xl font-bold text-emerald-300">0</p>
            </div>

            <div class="rounded-xl bg-amber-500/20 border border-amber-400/40 p-5 text-center">
                <p class="text-sm text-amber-200">Absensi Keluar Hari Ini</p>
                <p id="notOutCount" class="text-3xl font-bold text-amber-300">0</p>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
            <!-- Scanner Section -->
            <div class="lg:col-span-1">
                <div class="relative rounded-2xl border border-white/20 bg-white/5 p-6 shadow-lg">
                    <!-- Hidden Input -->
                    <input
                    type="text"
                    id="barcodeInput"
                    class="absolute inset-0 opacity-0"
                    autocomplete="off"
                    spellcheck="false"
                    onkeydown="if(event.key === 'Enter') handleScan()"
                    />


                    <img src="{{ asset('images/engineer.png') }}" alt="Scanner" class="mx-auto h-56 opacity-90" />


                    <div class="mt-6 text-center">
                        <p class="text-lg font-semibold text-white">Scan Barcode Anda</p>
                        <p class="text-sm text-white/60">Pastikan scanner aktif dan mengarah ke QR Code</p>
                    </div>

                    <!-- Loading -->
                    <div id="loadingIndicator"
                        class="hidden absolute inset-0 z-10
                                bg-black/70 backdrop-blur-sm
                                rounded-2xl
                                flex flex-col items-center justify-center
                                transition-opacity duration-300">

                        <!-- Loader -->
                        <div class="relative w-14 h-14 mb-4">
                            <div class="absolute inset-0 rounded-full border-2 border-white/10"></div>
                            <div class="absolute inset-0 rounded-full border-2 border-blue-400
                                        border-t-transparent animate-spin"></div>
                        </div>

                        <!-- Text -->
                        <p class="text-sm tracking-widest uppercase text-white/80">
                            Processing
                        </p>
                        <p class="text-xs text-white/40 mt-1">
                            Verifying attendance data
                        </p>
                    </div>

                </div>
            </div>


            <!-- Result Section -->
            <div class="lg:col-span-2">
                <div id="employeeData" class="space-y-4">
                    <div class="rounded-2xl border border-dashed border-white/20 p-10 text-center text-white/50">
                        <p class="text-lg">Data karyawan akan tampil di sini</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- SESSION EXPIRED MODAL -->
    <div id="sessionModal"
        class="fixed inset-0 z-50 hidden items-center justify-center
                bg-black/70 backdrop-blur-sm">

        <div class="w-full max-w-sm bg-slate-900 border border-white/10
                    rounded-2xl p-6 text-center shadow-2xl">

            <div class="text-4xl mb-3 text-red-400">⚠</div>

            <h2 class="text-lg font-semibold tracking-wide mb-1">
                SESSION BERAKHIR
            </h2>

            <p class="text-sm text-white/60">
                Sesi absensi telah berakhir.<br>
                Silakan masuk kembali untuk melanjutkan.
            </p>
        </div>
    </div>

    {{--<!-- Logo perusahaan -->
        <div class="flex justify-center items-center gap-8 mt-10">
            <img src="{{ asset('images/sojitz.png') }}" alt="Sojitz" class="h-12" />
            <img src="{{ asset('images/daicel.png') }}" alt="Daicel" class="h-12" />
            <img src="{{ asset('images/humpus.png') }}" alt="Humpus" class="h-12" />
            <img src="{{ asset('images/engineer2.png') }}" alt="Engineer 2" class="h-12" />
        </div>--}}

    <script>
        // Update jam realtime
        function updateLiveTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            document.getElementById('liveTime').innerText = timeString;
        }

        updateLiveTime();
        setInterval(updateLiveTime, 1000);

        const input = document.getElementById('barcodeInput');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        window.addEventListener('load', () => input.focus());
        document.addEventListener('click', () => input.focus());

        // Proses scan
        function handleScan() {
            const code = input.value.trim();
            if (code.length < 3) return;

            const loader = document.getElementById('loadingIndicator');
                loader.classList.remove('hidden');
                loader.style.opacity = '0';

                requestAnimationFrame(() => {
                    loader.style.opacity = '1';
                });

            input.disabled = true;

            fetch(`/absensi/scan`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ barcode: code }),
            })
            
            .then(response => {
                if ([401, 419].includes(response.status)) {
                    showSessionExpired();
                    throw new Error('Session expired');
                }

                return response.json();
            })
            .then(data => {
              
               const employee = data.employee ?? null;

                // Pilih warna sesuai status
                let bgColor = '';
                if (data.status === 'masuk') {
                    bgColor = 'bg-green-100 border-green-400 text-green-700';
                } else if (data.status === 'keluar') {
                    bgColor = 'bg-yellow-100 border-yellow-400 text-yellow-700';
                } else {
                    bgColor = 'bg-gray-100 border-gray-400 text-gray-700';
                }

                
                const infoBox = `
                    <div class="${bgColor} border px-4 py-3 rounded mb-4">
                        <strong>${data.status.toUpperCase()}</strong> - ${data.message}
                    </div>

                    <div class=" p-4 bg-center bg-cover bg-no-repeat bg-[url('https://kaltimmethanol.com/themes/methanol/images/slider2_.jpg')] bg-gray-700 bg-blend-multiply rounded-xl shadow-lg overflow-hidden border border-gray-200">
                        <div class="p-4 px-2 pt-2 rounded-lg text-center space-y-2">

                            <div class="rounded-lg text-white text-center py-2 pt-4">
                                <h2 class="text-xl font-bold italic uppercase tracking-wide text-blue-400">Turn <span class="text-red-400">Around</span> <span class="text-white">2025</span></h2>
                                <p class="text-sm">PT. Kaltim Methanol Industri</p>
                            </div>

                            <!-- Info Karyawan -->
                            <div class="text-gray-800 py-2">
                                <h3 class="text-xl font-bold text-gray-100">${employee.full_name}</h3>
                                <p class="text-md text-gray-200 italic">${employee.company_name} - ${employee.position_name}</p>
                                <p class="text-sm text-gray-400">${employee.employee_code}</p>
                                ${data.total_minutes !== undefined ? `<p class="mt-1 text-sm">Total Waktu: ${data.total_minutes} menit</p>` : ''}
                            </div>

                        </div>
                    </div>
                `;



                document.getElementById('employeeData').innerHTML = infoBox;
                loadDailyCounter();
            })
            .catch(() => {
                document.getElementById('employeeData').innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        Karyawan tidak ditemukan atau terjadi kesalahan saat scan.
                    </div>
                `;
            })
            .finally(() => {
                loader.style.opacity = '0';
                setTimeout(() => loader.classList.add('hidden'), 200);
                input.value = '';
                input.disabled = false;
                input.focus();
            });
        }
        function loadDailyCounter() {
            fetch('/absensi/counter-harian')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('alreadyInCount').innerText = data.already_in;
                    document.getElementById('notOutCount').innerText = data.not_out_yet;
                });
        }

        /* ===============================
        SESSION EXPIRED HANDLER
        ================================ */
        function showSessionExpired() {
            const modal = document.getElementById('sessionModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // redirect otomatis
            setTimeout(() => {
                window.location.href = "{{ route('akses.index') }}";
            }, 2000);
        }


    </script>
</body>
</html>
