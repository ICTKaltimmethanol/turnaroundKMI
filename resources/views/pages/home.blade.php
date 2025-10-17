
@extends('welcome')

@section('title', 'Welcome')

@section('content')
    <div class="max-w-md mx-auto">
    <div class="">
        @php
            use Carbon\Carbon;
            Carbon::setLocale('id');
            $employeeName = auth()->user()->full_name ?? 'Nama Employee';
            $employeePosition = auth()->user()->position->name ?? 'Posisi';
            $employeeCompany = auth()->user()->company->name ?? 'Perusahaan';
            $dateToday = Carbon::now()->translatedFormat('l, d M Y'); 
            $totalKehadiran = auth()->user()->presences->count() ?? '-:-';
            $totalIzin = 3;
            $totalJamKerja = abs(auth()->user()->presences->sum('total_time')) ?? '-:-' ; 
             $hours = floor($totalJamKerja / 60);
                $mins = $totalJamKerja % 60;
                    $totalJam = ($hours > 0 ? $hours . ' jam ' : '') . $mins . ' menit';
                        
        @endphp

<div class="flex justify-between pt-4 items-center text-white">
    <div>
        <p class="text-xl">Selamat Pagi,</p>
        <p class="text-xl font-bold">{{ $employeeName }}</p>
    </div>
    <div class="rounded-full bg-gray-100 p-3">
        <!-- Heroicon User SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-700">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.25a8.25 8.25 0 0115 0" />
        </svg>
    </div>
</div>

<p class="text-md py-4 text-white">{{$employeePosition}} - {{$employeeCompany}}</p>

<div class="bg-white rounded-xl p-4 shadow-md mb-4">
    <div class="flex justify-between items-center">
        <p class="font-semibold text-gray-800">Working Schedule</p>
        <p class="text-red-300 font-medium">{{ $dateToday }}</p>
    </div>
    <div class="text-3xl text-center py-6 text-gray-900">
        07:00 - 18:00
    </div>
    <a href="#" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold text-center py-2 px-8 rounded-full block">
        Absen
    </a>
</div>

<div class="flex justify-center items-center bg-white rounded-xl shadow-md grid grid-cols-3 gap-2 py-8 mb-4">
    <div class="flex flex-col items-center justify-center text-center ">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-green-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
        </svg>
        <div>
            <p class="font-bold text-xs">Total Absensi</p>
            <p class="font-md text-xs">{{ $totalKehadiran }} Kali</p>
        </div>     
    </div>
    <div class="flex flex-col items-center justify-center text-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-yellow-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        </svg>
        <div>
            <p class="font-bold text-xs">Total Izin</p>
            <p class="font-md text-xs">{{ $totalIzin }} Kali</p>
        </div>                                
    </div>
    <div class="flex flex-col items-center justify-center text-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-red-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <div>
            <p class="font-bold text-xs">Total Jam Kerja</p>
            <p class="font-md text-xs">{{ $totalJam }}</p>
        </div>                                
    </div>
</div>

    </div>

        {{--
        <section class="bg-center bg-cover bg-no-repeat bg-[url('https://kaltimmethanol.com/themes/methanol/images/slider2_.jpg')] bg-gray-700 bg-blend-multiply">
            <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-56">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">PT. Kaltim Methanol Industri<br><span class="text-blue-400">Turn Around</span> <span class="text-red-400">2025</span> </h1>
                <p class="mb-8 text-lg font-normal text-gray-300 lg:text-xl sm:px-16 lg:px-48">Dengan Disiplin dan Kepedulian, Kita Wujudkan Turn Around yang Aman dan Efisien.</p>
            </div>
        </section>
        --}}
        
        <div class="mb-12">
            <div class="flex justify-between items-center py-2">
                <p class="font-bold text-lg">Kehadiran</p>
                <a href="{{ route('absensi') }}" class="text-sm text-blue-700">Tampilkan Semua</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6">
                @forelse ($presences as $presence)
                    @php
                    
                        \Carbon\Carbon::setLocale('id');
                        $date = \Carbon\Carbon::parse($presence->created_at)->translatedFormat('l, d F Y');
                        $timeIn = optional($presence->presenceIn)->presence_time ?? '--:--:--';
                        $timeOut = optional($presence->presenceOut)->presence_time ?? '--:--:--';

                        $minutes = $presence->total_time;

                        if ($minutes === 0 || $minutes === null) {
                            $totalTime = '--:--';
                        } else {
                            // Paksa jadi positif
                            $minutes = abs($minutes);

                            $hours = floor($minutes / 60);
                            $mins = $minutes % 60;

                            $totalTime = 
                                ($hours > 0 ? $hours . ' jam ' : '') . 
                                $mins . ' menit';
                        }
                    @endphp




                    <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 w-full mx-auto space-y-4">
                        <div class="flex justify-between items-center">
                            <p class="font-semibold text-md ">{{ $date }}</p>
                            <div class="bg-yellow-100 rounded-full border border-yellow-300 p-1 px-2 text-yellow-800 text-xs">{{ $totalTime }}</div>
                        </div>
                        <hr class="border border-gray-300">
                        <div class="flex justify-between items-center space-x-4">
                            <div class="flex items-center bg-green-100 py-2 pr-8 pl-4 rounded-xl space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-green-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                                    </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Time In</p>
                                    <p class="text-md font-semibold text-green-600">{{ $timeIn }}</p>
                                </div>
                            </div>
                            <div class="flex items-center bg-pink-100 py-2 pl-4 pr-8 rounded-xl space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-pink-900">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                <div>
                                    <p class="text-sm text-gray-600">Time Out</p>
                                    <p class="text-md font-semibold text-pink-900">{{ $timeOut }}</p>
                                </div>
                            </div>
                        </div>

                    
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada data kehadiran.</p>
                @endforelse
            </div>
        </div>

    </div>
@endsection