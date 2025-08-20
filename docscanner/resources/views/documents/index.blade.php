<x-app-layout>
    {{-- HEADER BIRU --}}
    <div class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <h1 class="text-3xl font-semibold">Storage</h1>
        </div>
    </div>

    <div class="py-6">
    <div class="max-w-7xl mx-auto px-6 space-y-4">

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('documents.index') }}" class="bg-white p-4 rounded-xl shadow space-y-3">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="q" value="{{ $q }}" placeholder="cari judul dokumen / perihal / nomor surat"
                           class="w-full border rounded-lg px-3 py-2" />
                </div>
                <div class="w-full md:w-60">
                    <select name="category" class="w-full border rounded-lg px-3 py-2">
                        <option value="">Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" @selected($category===$cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <a href="{{ route('documents.create') }}"
                       class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600">
                        Unggah Dokumen
                    </a>
                    <a href="{{ route('scan.index') }}"
                       class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                        Scan Dokumen
                    </a>
                </div>
            </div>

            <div class="pt-2">
                <p class="text-sm font-semibold mb-2">berdasarkan tanggal</p>
                <div class="flex flex-col md:flex-row items-center gap-3">
                    <input type="date" name="from" value="{{ $from }}" class="border rounded-lg px-3 py-2">
                    <span class="text-gray-500">s.d.</span>
                    <input type="date" name="to" value="{{ $to }}" class="border rounded-lg px-3 py-2">
                    <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Terapkan</button>
                    @if(request()->hasAny(['q','category','from','to']))
                        <a href="{{ route('documents.index') }}" class="px-4 py-2 rounded-lg border">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- FLASH --}}
        @if(session('ok'))
            <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded border border-emerald-200">
                {{ session('ok') }}
            </div>
        @endif

        {{-- TABEL --}}
        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-cyan-600 text-white">
                        <th class="px-3 py-2 text-left w-16">NO</th>
                        <th class="px-3 py-2 text-left">Judul Dokumen/Perihal</th>
                        <th class="px-3 py-2 text-left">Nomor Surat</th>
                        <th class="px-3 py-2 text-left">Kategori</th>
                        <th class="px-3 py-2 text-left">Tanggal Dokumen</th>
                        <th class="px-3 py-2 text-left w-56">aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($docs as $i => $doc)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-3 py-2">{{ ($docs->currentPage()-1)*$docs->perPage() + $i + 1 }}</td>
                        <td class="px-3 py-2 font-semibold">{{ $doc->title }}</td>
                        <td class="px-3 py-2">{{ $doc->letter_number ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $doc->category ?? '-' }}</td>
                        <td class="px-3 py-2">{{ optional($doc->document_date)->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-3 py-2">
                            <div class="flex gap-3">
                                <a href="{{ route('documents.show',$doc) }}" class="px-2 py-1 text-sm rounded bg-sky-500 text-white">Preview</a>
                                <a href="{{ route('documents.download',$doc) }}" class="px-2 py-1 text-sm rounded bg-amber-500 text-white">Download</a>
                                <a href="{{ route('documents.edit',$doc) }}" class="px-2 py-1 text-sm rounded bg-sky-500 text-white">Edit</a>
                                {{-- Edit metadata kita tambahkan di tahap berikut --}}
                                <form action="{{ route('documents.destroy',$doc) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?')">
                                    @csrf @method('DELETE')
                                    <button class="px-2 py-1 text-sm rounded bg-rose-600 text-white">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-3 py-6 text-center text-gray-500">Belum ada dokumen</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $docs->links() }}
        </div>
    </div>
    </div>
</x-app-layout>
