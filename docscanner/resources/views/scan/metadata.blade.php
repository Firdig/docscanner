<x-app-layout>
    <x-slot name="header">Scanner — Metadata & Simpan</x-slot>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-4 rounded-xl shadow">
            <h3 class="font-semibold mb-3">Preview</h3>
            @if($batch->pages->count())
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-[70vh] overflow-auto">
                @foreach($batch->pages as $p)
                    <img src="{{ $p->url }}" class="w-full h-40 object-cover rounded border">
                @endforeach
            </div>
            @else
            <div class="text-gray-500">Belum ada halaman.</div>
            @endif
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <form action="{{ route('scan.save',$batch) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Judul / Perihal *</label>
                    <input name="title" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Nomor Surat</label>
                    <input name="letter_number" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Tanggal Dokumen</label>
                    <input type="date" name="document_date" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium">Kategori</label>
                        <input name="category" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Tahun</label>
                        <input type="number" name="year" class="w-full border rounded-lg px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium">Simpan sebagai</label>
                    <select name="output" class="w-full border rounded-lg px-3 py-2">
                        <option value="pdf" selected>PDF (multi-halaman)</option>
                        <option value="jpg">Gambar (halaman tunggal)</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('scan.review',$batch) }}" class="px-4 py-2 rounded border">Kembali</a>
                    <button class="px-5 py-2 rounded bg-green-600 text-white">Simpan ke Storage</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
