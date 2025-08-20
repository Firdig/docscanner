{{-- Sidebar --}}
<div
    class="fixed inset-y-0 left-0 z-40 w-64 bg-blue-900 text-white shadow-xl lg:translate-x-0 transform transition-transform"
    :class="{'-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen, 'lg:translate-x-0': true}">

    {{-- Brand / Logo --}}
    <div class="flex items-center gap-3 px-5 h-16 border-b border-blue-800">
        <img src="/logo-dinas-pendidikan-batu.png" class="h-10 w-10 rounded-md bg-white object-cover" alt="Logo">
        <div class="leading-tight">
            <div class="font-semibold">Dinas Pendidikan Kota Batu</div>
            <div class="text-xs text-blue-200">DocScanner</div>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="px-3 py-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">

        {{-- helper kelas aktif --}}
        @php
            $link = fn ($is) => 'flex items-center gap-3 px-3 py-2 rounded-lg transition '.
                ($is ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white');
            $icon = 'h-5 w-5';
        @endphp

        <a href="{{ route('dashboard') }}" class="{{ $link(request()->routeIs('dashboard')) }}">
            {{-- home icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $icon }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 10.5 12 3l9 7.5M4.5 9.75V21h15V9.75" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('scan.index') }}" class="{{ $link(request()->routeIs('scan.*')) }}">
            {{-- printer/scanner icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $icon }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M6 9V4h12v5M6 14h12m-9 6h6M4 14a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2" />
            </svg>
            <span>Scanner</span>
        </a>

        <a href="{{ route('documents.index') }}" class="{{ $link(request()->routeIs('documents.*')) }}">
            {{-- folder icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $icon }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z" />
            </svg>
            <span>Storage</span>
        </a>

        {{-- (opsional) menu lain: Kategori, Pengaturan, dll. --}}
        {{-- <a href="#" class="{{ $link(false) }}">…</a> --}}
    </nav>
</div>
