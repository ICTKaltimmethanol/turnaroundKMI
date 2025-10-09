
@extends('welcome')

@section('title', 'Absensi')

@section('content')
        
        <div class="mb-12">
            <div class="flex justify-between items-center pt-2">
                <p class="font-bold text-lg text-white">List Kehadiran </p>
            </div>
            <div class=" flex justify-between items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                </svg>
          
                <div class="flex  items-center gap-2 py-2 pb-4">
                    <div class=" rounded-full border border-gray-200 p-1 px-2 text-gray-200 text-sm hover:bg-gray-800 ">Pilih Tanggal</div>
                    
                    <div class=" rounded-full border border-gray-200 p-1 px-2 text-gray-200 text-sm hover:bg-gray-800 ">Terlambat</div>
                    
                    <div class=" rounded-full border border-gray-200 p-1 px-2 text-gray-200 text-sm hover:bg-gray-800 ">On Time</div>
                    
                </div>
              </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">        
                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 w-full mx-auto space-y-4">
                    <div class="flex justify-between items-center">
                        <p class="font-semibold text-md ">Senin, 10 Desember 2025</p>
                        <div class="bg-green-100 rounded-full border border-green-300 p-1 px-2 text-green-800 text-xs">On Time</div>
                    </div>
                    <hr class="border  border-gray-300">
                    <div class="flex justify-between items-center space-x-4">
                        <div class="flex items-center  bg-green-100 py-2 pr-8 pl-4 rounded-xl space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-green-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                            </svg>
                            <div class="">
                                <p class="text-sm font-md text-gray-500">Time In</p>
                                <p class="text-md font-semibold text-green-600">07:10:00</p>
                            </div>
                        </div>
                        <div class="flex items-center  bg-pink-100 py-2 pl-4 pr-8 rounded-xl space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-pink-900">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            <div>
                                <p class="text-sm font-md text-gray-600">Time Out</p>
                                <p class="text-MD font-semibold text-pink-900">--:--:--</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 w-full mx-auto space-y-4">
                    <div class="flex justify-between items-center">
                        <p class="font-semibold text-md ">Senin, 9 Desember 2025</p>
                        <div class="bg-orange-100 rounded-full border border-orange-300 p-1 px-2 text-orange-800 text-xs">Terlambat</div>
                    </div>
                    <hr class="border  border-gray-300">
                    <div class="flex justify-between items-center space-x-4">
                        <div class="flex items-center  bg-green-100 py-2 pr-8 pl-4 rounded-xl space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-green-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                            </svg>
                            <div class="">
                                <p class="text-sm font-md text-gray-500">Time In</p>
                                <p class="text-md font-semibold text-green-600">07:00:00</p>
                            </div>
                        </div>
                        <div class="flex items-center  bg-pink-100 py-2 pl-4 pr-8 rounded-xl space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-pink-900">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            <div>
                                <p class="text-sm font-md text-gray-600">Time Out</p>
                                <p class="text-MD font-semibold text-pink-900">17:00:00</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center  bg-red-100 py-2 pl-4 pr-8 rounded-xl space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-red-900">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div>
                            <p class="text-sm font-md text-gray-600">Terlambat</p>
                            <p class="text-MD font-semibold text-red-900">30 min</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 w-full mx-auto space-y-4">
                    <div class="flex justify-between items-center">
                        <p class="font-semibold text-md ">Senin, 8 Desember 2025</p>
                        <div class="bg-green-100 rounded-full border border-green-300 p-1 px-2 text-green-800 text-xs">On Time</div>
                    </div>
                    <hr class="border  border-gray-300">
                    <div class="flex justify-between items-center space-x-4">
                        <div class="flex items-center  bg-green-100 py-2 pr-8 pl-4 rounded-xl space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-green-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                            </svg>
                            <div class="">
                                <p class="text-sm font-md text-gray-500">Time In</p>
                                <p class="text-md font-semibold text-green-600">07:00:00</p>
                            </div>
                        </div>
                        <div class="flex items-center  bg-pink-100 py-2 pl-4 pr-8 rounded-xl space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-pink-900">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            <div>
                                <p class="text-sm font-md text-gray-600">Time Out</p>
                                <p class="text-MD font-semibold text-pink-900">18:00:00</p>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>

@endsection