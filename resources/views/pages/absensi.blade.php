<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Absent Page</title>

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite('resources/css/app.css')

    <!-- CSRF Token untuk fetch -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body class="font-roboto antialiased dark:bg-black dark:text-white/50">
    <div class="h-screen flex justify-center items-center text-black">
        <div>
            <center>
                <h1 class="text-6xl font-bold text-red-800 text-center">
                    <span class="text-blue-900">ABSENSI</span> TURN AROUND
                </h1>

                <div class="text-8xl py-2 font-bold">
                    <div id="liveTime"></div>
                </div>

                <div class="w-full mb-4 rounded-xl border-2 border-gray-500 relative">
        
                    <input
                        type="text"
                        id="barcodeInput"
                        class="opacity-0 absolute top-0 left-0 w-full h-full"
                        autocomplete="off"
                        spellcheck="false"
                        onkeydown="if(event.key === 'Enter') handleScan()"
                    />


                    <!-- Tempat menampilkan data employee -->
                    <div
                        id="employeeData"
                        class="mt-4 text-xl font-semibold text-gray-700 dark:text-white"
                    ></div>

                    <img src="{{ asset('images/engineer.png') }}" alt="Engineer" class="h-70 mx-auto" />
                    <div
                        class=" py-2 px-6 mx-auto mt-3"
                    >
                        <p class="text-lg font-semibold text-gray-500">
                            Arahkan barcode anda ke scanner
                        </p>
                    </div>
                </div>

                <div class="flex justify-center items-center gap-8 py-2">
                    <img src="{{ asset('images/sojitz.png') }}" alt="Sojitz" class="h-15" />
                    <img src="{{ asset('images/daicel.png') }}" alt="Daicel" class="h-15" />
                    <img src="{{ asset('images/humpus.png') }}" alt="Humpus" class="h-15" />
                    <img
                        src="{{ asset('images/engineer2.png') }}"
                        alt="Engineer 2"
                        class="h-15"
                    />
                </div>
                
            </center>
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

        // Fokuskan input barcode saat halaman siap
        const input = document.getElementById('barcodeInput');
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');

        window.addEventListener('load', () => {
            input.focus();
        });

        // Handle scan barcode
        function handleScan() {
            const code = input.value.trim();
            if (code.length < 3) return; 

            fetch('{{ route('absensi.scan') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ barcode: code }),
            })
            .then((response) => {
                if (!response.ok) throw new Error('<div class="w-70 bg-red-200 py-2 px-4  text-lg rounded-md">Data tidak ditemukan</div>');
                return response.json();
            })
            .then((data) => {
                document.getElementById('employeeData').innerHTML = `
                   
                    <div class="w-70 bg-green-200 py-2 px-4 font-semibold text-lg rounded-md">
                        Berhasil melakukan absensi
                    </div>
                `;
            })
            .catch(() => {
                document.getElementById('employeeData').innerHTML = `<p class="w-70 text-gray-800 bg-red-200 rounded-md py-2 px-4">Karyawan tidak ditemukan</p>`;
            });

            input.value = '';  
            input.focus();    
        }

        document.addEventListener('click', () => input.focus());
    </script>
</body>
</html>
