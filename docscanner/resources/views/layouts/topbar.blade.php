{{-- Topbar --}}
<header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b">
    <div class="h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between">

        {{-- Kiri: tombol buka sidebar (mobile) + judul halaman --}}
        <div class="flex items-center gap-3">
            <button class="lg:hidden inline-flex items-center justify-center rounded-md p-2 border"
                    @click="sidebarOpen = !sidebarOpen" aria-label="Toggle sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="leading-tight">
                {{-- Judul otomatis dari slot header jika ada, fallback nama route --}}
                @isset($header)
                    <h1 class="font-semibold text-lg">
                        {{ $header }}
                    </h1>
                @else
                    <h1 class="font-semibold text-lg capitalize">
                        {{ str_replace('.', ' › ', request()->route()->getName()) }}
                    </h1>
                @endisset
                <p class="text-xs text-gray-500 hidden sm:block">Sistem pemindaian & arsip dokumen</p>
            </div>
        </div>

        {{-- Kanan: user menu sederhana --}}
        <div class="flex items-center gap-3">
            <span class="hidden sm:block text-sm text-gray-600">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
