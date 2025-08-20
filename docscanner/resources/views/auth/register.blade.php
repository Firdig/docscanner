<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register • ScanDocs</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-900 antialiased">

  <!-- Bar biru atas -->
  <div class="h-16 bg-[#4A79BD]"></div>

  <!-- Card form -->
  <main class="min-h-[calc(100vh-4rem)] flex items-start justify-center">
    <div class="w-full max-w-xl mx-auto mt-16 p-10 bg-[#d8e4ef] rounded-xl shadow-md">

      <h1 class="text-3xl font-semibold text-center text-gray-600 mb-8">Register</h1>

      <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
          <label class="block text-gray-700 mb-2">Name</label>
          <input type="text" name="name" value="{{ old('name') }}"
                 class="w-full rounded-md border border-gray-300 px-4 py-3 text-gray-700 placeholder-gray-400
                        focus:outline-none focus:ring-2 focus:ring-[#4A79BD]" placeholder="Nama lengkap">
          @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div>
          <label class="block text-gray-700 mb-2">Email</label>
          <input type="email" name="email" value="{{ old('email') }}"
                 class="w-full rounded-md border border-gray-300 px-4 py-3 text-gray-700 placeholder-gray-400
                        focus:outline-none focus:ring-2 focus:ring-[#4A79BD]" placeholder="nama@email.com">
          @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div>
          <label class="block text-gray-700 mb-2">Password</label>
          <div class="relative">
            <input id="pwd" type="password" name="password"
                   class="w-full rounded-md border border-gray-300 px-4 py-3 pr-11 text-gray-700
                          focus:outline-none focus:ring-2 focus:ring-[#4A79BD]" placeholder="Password">
            <button type="button" onclick="togglePwd('pwd')"
                    class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700"></button>
          </div>
          @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="block text-gray-700 mb-2">Confirm Password</label>
          <div class="relative">
            <input id="pwdc" type="password" name="password_confirmation"
                   class="w-full rounded-md border border-gray-300 px-4 py-3 pr-11 text-gray-700
                          focus:outline-none focus:ring-2 focus:ring-[#4A79BD]" placeholder="Ulangi Password">
            <button type="button" onclick="togglePwd('pwdc')"
                    class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700"></button>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between">
          <a href="{{ route('login') }}" class="text-sm text-[#4A79BD] hover:underline">
            Already registered?
          </a>
          <button type="submit"
                  class="bg-[#4A79BD] text-white px-6 py-3 rounded-md font-semibold tracking-wide
                         hover:bg-[#3c64a0] transition">
            REGISTER
          </button>
        </div>
      </form>
    </div>
  </main>

  <script>
    function togglePwd(id){
      const el = document.getElementById(id);
      el.type = el.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
