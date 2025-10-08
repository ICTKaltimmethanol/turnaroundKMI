<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

          @vite('resources/css/app.css')
</head>
<body>       
    <div class="p-8">
        <img src="{{asset('images/engineer.png')}}" alt="Login Illustration" class="mx-auto mb-4  object-cover ">
            <!-- Tambahkan gambar di sini -->
            <h1 class="py-8 font-md text-2xl text-center">TA 2025 <br>PT. Kaltim Methanol Industri</h1>
        <div class=""></div>
        <form class="space-y-4 md:space-y-6" action="#">
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="">
            </div>
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
            </div>
            
                <button class="w-full rounded-full px-4 py-2 bg-blue-600 hover:bg-blue700 text-white mb-2"><a href="{{route('home')}}">Log In</a></button>
       
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                Belum memiliki akun? <a href="#" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Daftar</a>
            </p>
        </form>
    </div>
</body>
</html>