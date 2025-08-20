<x-app-layout>
    <div class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <h1 class="text-3xl font-semibold">Edit Metadata Dokumen</h1>
        </div>
    </div>

    <div class="py-6">
    <div class="max-w-3xl mx-auto px-6">
        <a href="{{ route('documents.index') }}" class="inline-block mb-4 px-4 py-2 rounded-lg border">← Kembali</a>

        <form action="{{ route('documents.update',$doc) }}" method="POST" class="bg-white p-6 rounded-xl shadow space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium">Judul / Perihal *</label>
                <input name="title" value="{{ old('title',$doc->title) }}" class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Nomor Surat</label>
                    <input name="letter_number" value="{{ old('letter_number',$doc->letter_number) }}" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Tanggal Dokumen</label>
                    <input type="date" name="document_date" value="{{ old('document_date',$doc->document_date?->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Kategori</label>
                    <input name="category" value="{{ old('category',$doc->category) }}" list="category-list" class="w-full border rounded-lg px-3 py-2">
                    <datalist id="category-list">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-sm font-medium">Tahun</label>
                    <input name="year" value="{{ old('year',$doc->year) }}" type="number" class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('description',$doc->description) }}</textarea>
            </div>

            <div class="flex gap-3">
                <button class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Update</button>
                <a href="{{ route('documents.index') }}" class="px-5 py-2 rounded-lg border">Batal</a>
            </div>
        </form>
    </div>
    </div>
</x-app-layout>
