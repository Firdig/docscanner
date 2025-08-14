<x-app-layout>
    <div class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <h1 class="text-3xl font-semibold">Unggah Dokumen</h1>
        </div>
    </div>

    <div class="py-6">
    <div class="max-w-3xl mx-auto px-6">
        <a href="{{ route('documents.index') }}" class="inline-block mb-4 px-4 py-2 rounded-lg border">← Kembali</a>

        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Judul / Perihal *</label>
                <input name="title" value="{{ old('title') }}" class="w-full border rounded-lg px-3 py-2" required>
                @error('title')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Nomor Surat</label>
                    <input name="letter_number" value="{{ old('letter_number') }}" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Tanggal Dokumen</label>
                    <input type="date" name="document_date" value="{{ old('document_date') }}" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Kategori</label>
                    <input name="category" value="{{ old('category') }}" list="category-list" class="w-full border rounded-lg px-3 py-2">
                    <datalist id="category-list">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-sm font-medium">Tahun</label>
                    <input name="year" value="{{ old('year') }}" type="number" min="1900" max="2100" class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium">File (PDF/JPG/PNG) *</label>
                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="w-full border rounded-lg px-3 py-2" required>
                @error('file')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Simpan</button>
                <a href="{{ route('documents.index') }}" class="px-5 py-2 rounded-lg border">Batal</a>
            </div>
        </form>
    </div>
    </div>
</x-app-layout>
