<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TA KMI</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

          @vite('resources/css/app.css')
    </head>
    <body class="font-roboto antialiased dark:bg-black dark:text-white/50">
        <div class="relative overflow-hidden min-h-screen">
            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-900 rounded-full z-[-10]"></div> 
            <div class="h-full mx-auto grid grid-cols-5">
                <div class="fixed inset-x-0 bottom-0 left-0 z-50 w-full h-16 mx-auto overflow-hidden bg-white border-t sm:bottom-5 sm:shadow-lg sm:shadow-base-500/30 hover:shadow-md duration-300 sm:border sm:max-w-md sm:rounded-xl border-base-50">
                    <div class="h-full mx-auto font-medium grid grid-cols-5">
                        <button
                        type="button"
                        class="inline-flex flex-col items-center justify-center px-5 hover:bg-base-50 group gap-1 hover:text-orange-500 text-base-500"
                        >
                        <svg
                            class="size-5"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                        >
                            <path
                            d="M21 20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V9.48907C3 9.18048 3.14247 8.88917 3.38606 8.69972L11.3861 2.47749C11.7472 2.19663 12.2528 2.19663 12.6139 2.47749L20.6139 8.69972C20.8575 8.88917 21 9.18048 21 9.48907V20ZM19 19V9.97815L12 4.53371L5 9.97815V19H19Z"
                            ></path>
                        </svg>
                        <span class="text-xs">Home</span>
                        </button>
                        <button
                        type="button"
                        class="inline-flex flex-col items-center justify-center px-5 hover:bg-base-50 group gap-1 hover:text-orange-500 text-base-500"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                            </svg>

                        <span class="text-xs">Absensi</span>
                        </button>
                        <button
                        type="button"
                        class="inline-flex flex-col items-center justify-center px-5 hover:bg-base-50 group gap-1 hover:text-orange-500 text-base-500"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>

                        <span class="text-xs">Cuti</span>
                        </button>
                        <button
                        type="button"
                        class="inline-flex flex-col items-center justify-center px-5 hover:bg-base-50 group gap-1 hover:text-orange-500 text-base-500"
                        >
                        <svg
                            class="size-5"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                        >
                            <path
                            d="M5 18H19V11.0314C19 7.14806 15.866 4 12 4C8.13401 4 5 7.14806 5 11.0314V18ZM12 2C16.9706 2 21 6.04348 21 11.0314V20H3V11.0314C3 6.04348 7.02944 2 12 2ZM9.5 21H14.5C14.5 22.3807 13.3807 23.5 12 23.5C10.6193 23.5 9.5 22.3807 9.5 21Z"
                            ></path>
                        </svg>
                        <span class="text-xs">Pemberitahuan</span>
                        </button>
                        <button
                        type="button"
                        class="inline-flex flex-col items-center justify-center px-5 hover:bg-base-50 group gap-1 hover:text-orange-500 text-base-500"
                        >
                        <svg
                            class="size-5"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                        >
                            <path
                            d="M4 22C4 17.5817 7.58172 14 12 14C16.4183 14 20 17.5817 20 22H18C18 18.6863 15.3137 16 12 16C8.68629 16 6 18.6863 6 22H4ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11Z"
                            ></path>
                        </svg>
                        <span class="text-xs">Profile</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="h-full p-6 md:p-4 sm:p-8">
                <div class="">
                    <div class="flex justify-between pt-4 items-center text-white">
                        <div>
                            <p class="text-xl ">Selamat Pagi,</p>
                            <p class="text-xl font-bold">User.TA.KaltimMethanol</p>
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
                    
                    <p class="text-md py-4 text-white">Helper - Kaltim Nusa Etika</p>

                    <div class="bg-white rounded-xl p-4 shadow-md mb-4">
                        <div class="flex justify-between items-center">
                            <p class="font-semibold text-gray-800">Working Schedule</p>
                            <p class="text-red-300 font-medium">Senin, 25 Des 2025</p>
                        </div>
                        <div class="text-3xl text-center py-6 text-gray-900">
                            07:00 - 18:00
                        </div>
                        <a href="#" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold text-center py-2 px-8 rounded-full block">
                            Absen
                        </a>
                    </div>

                    <div class=" flex justify-center items-center bg-white rounded-xl shadow-md grid grid-cols-3 gap-2 py-8 mb-4">
                        <div class="flex flex-col items-center justify-center text-center ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                            </svg>
                            <div>
                                <p class="font-bold text-xs">Total Kehadiran</p>
                                <p class="font-md text-xs">15 Hari</p>
                            </div>     
                        </div>
                        <div class="flex flex-col items-center justify-center text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-yellow-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            <div>
                                <p class="font-bold text-xs">Total Izin</p>
                                <p class="font-md text-xs">15 Hari</p>
                            </div>
                            
                        </div>
                        <div class="flex flex-col items-center justify-center text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <div>
                                <p class="font-bold text-xs">Keterlambatan</p>
                                <p class="font-md text-xs">15 Hari</p>
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
                        <a href="" class="text-sm text-blue-700"> Tampilkan Semua</a>
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
            </div>
        </div>
    </body>
</html>
