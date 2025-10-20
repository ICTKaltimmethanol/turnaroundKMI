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
<body class="font-roboto antialiased dark:bg-black dark:text-white/50">
    <div class="h-screen flex justify-center items-center text-black">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-red-800 mb-6">
            <span class="text-blue-900">ABSENSI</span> TURN AROUND
        </h1>

        <div class="text-8xl py-2 font-bold">
            <div id="liveTime"></div>
        </div>

        <!-- Kontainer Flex: Gambar & Data Karyawan -->
        <div class="flex items-start justify-center gap-10 mt-8">
            <!-- Gambar + Scanner -->
            <div class="w-full max-w-md border-2 border-gray-500 rounded-xl p-4 relative">
                <!-- Input Barcode (hidden tapi tetap aktif) -->
                <input
                    type="text"
                    id="barcodeInput"
                    class="absolute opacity-0 top-0 left-0 w-full h-full"
                    autocomplete="off"
                    spellcheck="false"
                    onkeydown="if(event.key === 'Enter') handleScan()"
                />

                <img src="{{ asset('images/engineer.png') }}" alt="Engineer" class="h-70 mx-auto" />

                <div class="mt-4">
                    <p class="text-lg font-semibold text-gray-500">
                        Arahkan barcode anda ke scanner
                    </p>
                </div>
            </div>

            <!-- Data Karyawan di Samping -->
            <div id="employeeData" class="w-full max-w-md text-xl font-semibold text-gray-700 dark:text-white">
                <!-- Data akan muncul di sini -->
            </div>
        </div>

        {{--<!-- Logo perusahaan -->
        <div class="flex justify-center items-center gap-8 mt-10">
            <img src="{{ asset('images/sojitz.png') }}" alt="Sojitz" class="h-12" />
            <img src="{{ asset('images/daicel.png') }}" alt="Daicel" class="h-12" />
            <img src="{{ asset('images/humpus.png') }}" alt="Humpus" class="h-12" />
            <img src="{{ asset('images/engineer2.png') }}" alt="Engineer 2" class="h-12" />
        </div>--}}
    </div>
</div>


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

            fetch(`{{ route('absensi.scan') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ barcode: code }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Scan gagal');
                }
                return response.json();
            })
           .then(data => {
                const employee = data.employee;

                const infoBox = `
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <strong>${data.status.toUpperCase()}</strong> - ${data.message}
                    </div>

                    <div class="max-w-md p-4 bg-center bg-cover bg-no-repeat bg-[url('https://kaltimmethanol.com/themes/methanol/images/slider2_.jpg')] bg-gray-700 bg-blend-multiply rounded-xl shadow-lg overflow-hidden border border-gray-200">
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

                            {{--<!-- Icons -->
                            <div class="flex justify-center items-center gap-6 py-2 bg-white rounded-lg border border-gray-200">
                                <img src="/images/sojitz.png" alt="Sojitz" class="h-5" />
                                <img src="/images/daicel.png" alt="Daicel" class="h-5" />
                                <img src="/images/humpus.png" alt="Humpus" class="h-5" />
                                <img src="/images/engineer2.png" alt="Engineer 2" class="h-5" />
                            </div> --}}
                        </div>
                    </div>
                `;

                document.getElementById('employeeData').innerHTML = infoBox;
            })

            .catch(() => {
                document.getElementById('employeeData').innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        Karyawan tidak ditemukan atau terjadi kesalahan saat scan.
                    </div>
                `;
            });

            input.value = '';
            input.focus();
        }
    </script>
</body>
</html>
