<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width= , initial-scale=1.0">
    <title>Absent Page</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite('resources/css/app.css')
</head>
<body class="font-roboto antialiased dark:bg-black dark:text-white/50">
    <div class="h-screen flex justify-center items-center text-black">
        <div>
            <center>
                <h1 class=" text-6xl font-bold text-red-800 text-center"><span class="text-blue-900">ABSENSI</span> TURN AROUND </h1>
                <div class="text-8xl py-2 font-bold">
                    <div id="liveTime"></div>
                </div>
                <div class="h-80 w-full mb-4 rounded-xl border border-2 border-gray-500">
                    <img src="{{asset('images/engineer.png')}}" alt="" class=" h-70">
                    <div class="w-60 bg-white rounded-xl border border-2 border-gray-600 py-2 px-6">   
                        <p class="text-lg font-semibold ">Arahkan barcode anda ke scanner</p>
                    </div>
                    
                </div>
          
                
                  <div class="flex justify-center items-center gap-8 py-12">
                    <img src="{{asset('images/sojitz.png')}}" alt="" class=" h-15">
                    <img src="{{asset('images/daicel.png')}}" alt="" class=" h-15">
                    <img src="{{asset('images/humpus.png')}}" alt="" class=" h-15">
                    <img src="{{asset('images/engineer2.png')}}" alt="" class=" h-15">
                </div>
                <script>
                    function updateLiveTime() {
                        const now = new Date();
                        const timeString = now.toLocaleTimeString(); 
                        document.getElementById('liveTime').innerText = timeString;
                    }

                    updateLiveTime();
                    setInterval(updateLiveTime, 1000);
                </script>

            </center>
        </div>
    </div>
</body>
</html>