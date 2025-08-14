<x-app-layout>
    <div class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <h1 class="text-3xl font-semibold">Preview Dokumen</h1>
        </div>
    </div>

    <div class="py-6">
    <div class="max-w-6xl mx-auto px-6 space-y-4">
        <a href="{{ route('documents.index') }}" class="inline-block px-4 py-2 rounded-lg border">← Kembali</a>

        <div class="bg-white p-4 rounded-xl shadow">
            <div class="mb-4">
                <div class="font-semibold text-lg">{{ $doc->title }}</div>
                <div class="text-sm text-gray-600">
                    No: {{ $doc->letter_number ?? '-' }} ·
                    Kategori: {{ $doc->category ?? '-' }} ·
                    Tanggal: {{ optional($doc->document_date)->format('d/m/Y') ?? '-' }}
                </div>
            </div>

            @if(str_contains($doc->mime,'pdf'))
                <iframe src="{{ $doc->file_url }}" class="w-full h-[80vh] rounded-lg border"></iframe>
            @else
                <img src="{{ $doc->file_url }}" class="max-h-[80vh] rounded-lg border mx-auto">
            @endif
        </div>
    </div>
    </div>
</x-app-layout>
