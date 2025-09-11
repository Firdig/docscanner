<x-guest-layout title="Login • ScanDocs">

  {{-- JUDUL BESAR --}}
  <h1 class="text-center text-4xl font-semibold text-gray-600 mb-8">Login</h1>

  {{-- KOTAK FORM KECIL DI TENGAH PANEL --}}
  <div class="max-w-md mx-auto">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
      @csrf

      {{-- Username (pakai email di backend, placeholder tetap "Username") --}}
      <div class="mb-4">
        <x-text-input
          id="email"
          name="email"
          type="email"
          :value="old('email')"
          required
          autofocus
          autocomplete="username"
          placeholder="Username"
          class="block w-full bg-white rounded-md border border-slate-200 px-4 py-2 shadow-[0_2px_0_rgba(0,0,0,0.2)] focus:outline-none focus:ring-2 focus:ring-[#497AB8]/40"
        />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div>

      {{-- Password --}}
      <div class="mb-6">
        <x-text-input
          id="password"
          name="password"
          type="password"
          required
          autocomplete="current-password"
          placeholder="Password"
          class="block w-full bg-white rounded-md border border-slate-200 px-4 py-2 shadow-[0_2px_0_rgba(0,0,0,0.2)] focus:outline-none focus:ring-2 focus:ring-[#497AB8]/40"
        />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
      </div>

      {{-- Tombol hijau "MASUK" --}}
      <button
        type="submit"
        class="w-40 mx-auto block rounded-full bg-[#2ECC71] text-white font-semibold tracking-wide py-2 shadow-[0_3px_0_rgba(0,0,0,0.25)] hover:brightness-95 active:translate-y-[1px]"
      >
        MASUK
      </button>

      {{-- (Opsional) Remember & Forgot untuk kebutuhan UX, tapi disembunyikan dari fokus visual --}}
      <div class="mt-4 flex items-center justify-center gap-4 text-sm">
        <label for="remember_me" class="inline-flex items-center text-gray-600">
          <input id="remember_me" type="checkbox" name="remember"
           class="rounded border-slate-300 text-[#497AB8] focus:ring-[#497AB8]/40">
          <span class="ms-2">Remember me</span>
        </label>

      @if (Route::has('password.request'))
        <!-- <a class="underline text-gray-600 hover:text-gray-900"
          href="{{ route('password.request') }}">
          Forgot your password?
        </a> -->
      @endif

      {{-- Tambahan: Link ke Register (tanpa ubah style keseluruhan) --}}
      @if (Route::has('register'))
        <a class="underline text-gray-600 hover:text-gray-900"
          href="{{ route('register') }}">
          Register
        </a>
      @endif
      </div>

    </form>
  </div>
</x-guest-layout>
