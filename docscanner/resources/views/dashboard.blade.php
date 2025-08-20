<x-app-layout>
    {{-- HEADER DASHBOARD SEDERHANA --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
           {{-- Kiri: Logo + Judul --}}
            <div class="flex items-center gap-3">
                {{-- Logo --}}
                <div class="w-10 h-10">
                    <img src="{{ asset('logo-dinas-pendidikan-batu.png') }}" 
                        alt="Logo" 
                        class="w-10 h-10 rounded-lg object-cover">
                </div>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dinas Pendidikan Kota Batu</h2>
                <p class="text-sm text-gray-500">Menu Utama</p>
            </div>
        </div>

        </div>
    </x-slot>

    {{-- BODY --}}
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-6">
                <a href="{{ route('scan.index') }}"
                   class="group block rounded-2xl bg-white border border-slate-200 p-6 shadow-sm
                          hover:shadow-md hover:-translate-y-0.5
                          transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-200
                          hover:bg-gray-100 active:bg-gray-200">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-xl bg-[#E8F1FF] grid place-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#4A79BD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <path d="M3 7h18M5 11h14a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2Z"/>
                                <path d="M8 15h8M7 7l2-3h6l2 3"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Scan Dokumen</h3>
                            <p class="text-gray-500 mt-1">Lakukan pemindaian dan kelola halaman sebelum disimpan.</p>
                            <span class="inline-flex items-center gap-1 mt-3 text-[#4A79BD] group-hover:gap-2 transition-all">
                                Mulai scan
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg>
                            </span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('documents.index') }}"
                   class="group block rounded-2xl bg-white border border-slate-200 p-6 shadow-sm
                          hover:shadow-md hover:-translate-y-0.5
                          transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-200
                          hover:bg-gray-100 active:bg-gray-200">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-xl bg-[#E9FBF3] grid place-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <path d="M3 7a2 2 0 0 1 2-2h4l2 2h6a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Dokumen</h3>
                            <p class="text-gray-500 mt-1">Lihat, cari, filter, dan kelola dokumen tersimpan.</p>
                            <span class="inline-flex items-center gap-1 mt-3 text-emerald-700 group-hover:gap-2 transition-all">
                                Buka storage
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="mt-8 grid sm:grid-cols-3 gap-4">
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-sm text-slate-500">Total Dokumen</p>
                    <p class="mt-1 text-2xl font-semibold">—</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-sm text-slate-500">Terakhir Upload</p>
                    <p class="mt-1 text-base">—</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-sm text-slate-500">Status Scanner</p>
                    <span class="mt-2 inline-flex items-center gap-2 rounded-full bg-yellow-100 text-yellow-800 px-3 py-1 text-sm">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span> Belum terhubung
                    </span>
                </div>
            </div>

            <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5">
                <h4 class="font-semibold text-gray-800 mb-3">Riwayat Aktivitas</h4>
                <ul class="text-sm text-slate-600 space-y-2">
                    <li>— UI only</li>
                    <li>— UI only</li>
                    <li>— UI only</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
