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
                    <p class="mt-1 text-2xl font-semibold">{{ \App\Models\Document::count() }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-sm text-slate-500">Terakhir Upload</p>
                    <p class="mt-1 text-base">
                        @php
                            $lastDoc = \App\Models\Document::latest()->first();
                        @endphp
                        {{ $lastDoc ? $lastDoc->created_at->diffForHumans() : 'Belum ada' }}
                    </p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-sm text-slate-500">Status Scanner</p>
                    <span class="mt-2 inline-flex items-center gap-2 rounded-full bg-yellow-100 text-yellow-800 px-3 py-1 text-sm">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span> Belum terhubung
                    </span>
                </div>
            </div>

            {{-- Riwayat Aktivitas Real --}}
            <div class="mt-8 rounded-2xl border border-slate-200 bg-white">
                <div class="p-5 border-b border-slate-200 flex items-center justify-between">
                    <h4 class="font-semibold text-gray-800">Riwayat Aktivitas Terbaru</h4>
                    <a href="{{ route('activity-logs.index') }}" 
                       class="text-sm text-indigo-600 hover:text-indigo-800">
                        Lihat Semua
                    </a>
                </div>
                
                <div id="recent-activities" class="divide-y divide-slate-100">
                    {{-- Activities akan dimuat via JavaScript --}}
                    <div class="p-5 text-center text-slate-500">
                        <div class="animate-pulse">Memuat aktivitas...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk load recent activities --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadRecentActivities();
        });

        function loadRecentActivities() {
            fetch('/api/activity-logs/recent?limit=5')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('recent-activities');
                    
                    if (data.activities && data.activities.length > 0) {
                        container.innerHTML = '';
                        
                        data.activities.forEach(activity => {
                            const activityHtml = `
                                <div class="p-4 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <span class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-full ${activity.color_class} text-sm">
                                            ${activity.icon}
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900">${activity.description}</p>
                                            <p class="text-xs text-slate-500 mt-1">${activity.created_at_human}</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                            container.insertAdjacentHTML('beforeend', activityHtml);
                        });
                    } else {
                        container.innerHTML = `
                            <div class="p-5 text-center text-slate-500">
                                <p class="text-sm">Belum ada aktivitas</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading activities:', error);
                    document.getElementById('recent-activities').innerHTML = `
                        <div class="p-5 text-center text-red-500">
                            <p class="text-sm">Gagal memuat aktivitas</p>
                        </div>
                    `;
                });
        }

        // Auto refresh setiap 30 detik
        setInterval(loadRecentActivities, 30000);
    </script>
</x-app-layout>