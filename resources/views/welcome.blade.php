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
                    <div class="fixed inset-x-0 bottom-0 left-0 z-50 w-full h-16 mx-auto overflow-hidden bg-white border-t border-gray-200 sm:bottom-5 sm:shadow-lg sm:shadow-base-500/30 hover:shadow-md duration-300 sm:border sm:max-w-md sm:rounded-xl border-base-50">
                        <div class="h-full mx-auto font-sm grid grid-cols-5">
                            <a href="{{route('home')}}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-base-50 group gap-1 hover:text-blue-500 text-base-500">
                                <svg
                                    class="size-5"
                                    xmlns="https://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                >
                                    <path
                                    d="M21 20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V9.48907C3 9.18048 3.14247 8.88917 3.38606 8.69972L11.3861 2.47749C11.7472 2.19663 12.2528 2.19663 12.6139 2.47749L20.6139 8.69972C20.8575 8.88917 21 9.18048 21 9.48907V20ZM19 19V9.97815L12 4.53371L5 9.97815V19H19Z"
                                    ></path>
                                </svg>
                                <span class="text-xs">Home</span>
                            </a>
                            <a href="{{route('absensi')}}" class="inline-flex flex-col items-center justify-center  hover:bg-base-50 group gap-1 hover:text-blue-500 text-base-500" >                
                                <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                                </svg>
                                <span class="text-xs">Absensi</span>
                            </a>
                            <a href="{{route('cuti')}}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-base-50 group gap-1 hover:text-orange-500 text-base-500"
                                >
                                    <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>

                                <span class="text-xs">Cuti</span>
                            </a>
                            <a href="" class="inline-flex flex-col items-center justify-center px-5 hover:bg-base-50 group gap-1 hover:text-orange-500 text-base-500">
                                <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                </svg>

                                <span class="text-xs">info</span>
                            </a>
                            <a href="{{route('profile')}}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-base-50 group gap-1 hover:text-orange-500 text-base-500">
                                <svg
                                    class="size-5"
                                    xmlns="https://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                >
                                    <path
                                    d="M4 22C4 17.5817 7.58172 14 12 14C16.4183 14 20 17.5817 20 22H18C18 18.6863 15.3137 16 12 16C8.68629 16 6 18.6863 6 22H4ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11Z"
                                    ></path>
                                </svg>
                                <span class="text-xs">Badge</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="h-full p-6 md:p-4 sm:p-8">
                    @yield('content')                
                </div>
            </div>
        </div>
    </body>
</html>
