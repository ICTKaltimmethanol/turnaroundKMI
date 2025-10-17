<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net" />
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
  @vite('resources/css/app.css')

  <style>
    .password-wrapper {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      top: 70%;
      right: 0.75rem;
      transform: translateY(-50%);
      cursor: pointer;
      color: #4a5568;
      user-select: none;
    }

    .toggle-password:hover {
      color: #2b6cb0;
    }
  </style>
</head>
<body>
  <div class="p-8 max-w-md mx-auto">
    <img src="{{ asset('images/engineer.png') }}" alt="Login Illustration" class="mx-auto mb-4 object-cover">
    <h1 class="py-8 font-md text-2xl text-center">TA 2025 <br>PT. Kaltim Methanol Industri</h1>

    <!-- ✅ ALERT ERROR -->
    @if (session('error'))
      <div class="mb-4 p-3 text-sm text-red-800 bg-red-100 border border-red-300 rounded-md">
        {{ session('error') }}
      </div>
    @endif

    <form class="space-y-4 md:space-y-6" action="{{ route('login.post') }}" method="POST">
      @csrf

      <div>
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="name@company.com" required>
        @error('email')
          <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="password-wrapper">
        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
        <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required />
        <span class="toggle-password" onclick="togglePasswordVisibility()" aria-label="Toggle password visibility" role="button" tabindex="0">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
          </svg>
        </span>
        @error('password')
          <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <button type="submit" class="w-full rounded-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white mb-2">Log In</button>

      <p class="text-sm font-light text-gray-500 dark:text-gray-400">
        Belum memiliki akun? <a href="#" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Daftar</a>
      </p>
    </form>
  </div>

  <script>
    function togglePasswordVisibility() {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
    }

    document.querySelector('.toggle-password').addEventListener('keydown', function (e) {
      if (e.key === ' ' || e.key === 'Enter') {
        e.preventDefault();
        togglePasswordVisibility();
      }
    });
  </script>
</body>
</html>
