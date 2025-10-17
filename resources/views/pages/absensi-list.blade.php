
@extends('welcome')

@section('title', 'Absensi')

@section('content')
        
    <div class="mb-12 max-w-md mx-auto">
        <div class="flex justify-between items-center pt-2">
            <p class="font-bold text-lg text-white">List Kehadiran </p>
            <div class="flex  items-center gap-2 py-2 pb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                </svg>
                <div class=" rounded-full border border-gray-200 p-1 px-2 text-gray-200 text-sm hover:bg-gray-800 ">Pilih Tanggal</div>
                 
            </div>
        </div>

         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6">
                @forelse ($presences as $presence)
                    @php
                        \Carbon\Carbon::setLocale('id');
                        $date = \Carbon\Carbon::parse($presence->created_at)->translatedFormat('l, d F Y');
                        $timeIn = optional($presence->presenceIn)->presence_time ?? '--:--:--';
                        $timeOut = optional($presence->presenceOut)->presence_time ?? '--:--:--';

                        $minutes = $presence->total_time ;
                        $statusColor = 'yellow';

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
                            <div class="bg-{{ $statusColor }}-100 rounded-full border border-{{ $statusColor }}-300 p-1 px-2 text-{{ $statusColor }}-800 text-xs">{{ $totalTime }}</div>
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

@endsection