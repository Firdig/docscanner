<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Menu Utama
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('scan.index') }}" class="block p-6 bg-white shadow rounded-xl hover:shadow-lg transition">
                <h3 class="text-lg font-semibold mb-2">Scan Dokumen</h3>
                <p class="text-sm text-gray-600">Lakukan pemindaian dan kelola halaman sebelum disimpan.</p>
            </a>
            <a href="{{ route('documents.index') }}" class="block p-6 bg-white shadow rounded-xl hover:shadow-lg transition">
                <h3 class="text-lg font-semibold mb-2">Dokumen</h3>
                <p class="text-sm text-gray-600">Lihat, cari, filter, dan kelola dokumen tersimpan.</p>
            </a>
        </div>
    </div>
</x-app-layout>
