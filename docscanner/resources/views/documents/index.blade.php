documents/index.blade.php :
<x-app-layout>
 <x-slot name="header">Storage</x-slot>

 {{-- FIX: kelas tombol supaya tidak ketimpa reset CSS --}}
 <style>
  .btn{display:inline-flex;align-items:center;justify-content:center;gap:.375rem;
    padding:.375rem .5rem;border-radius:.5rem;font-size:.875rem;font-weight:600;
    text-decoration:none;white-space:nowrap;line-height:1.25}

  /* ukuran konsisten kedua tombol kanan */
  .btn-cta{height:44px;width:130px;padding:.5rem 1rem;box-sizing:border-box;}

  /* DITUKAR: Scan = biru, Unggah = hijau */
  .btn-scan{background:#0284c7;color:#fff}   .btn-scan:hover{background:#0369a1}
  .btn-upload{background:#059669;color:#fff} .btn-upload:hover{background:#047857}

  /* table actions (tetap) */
  .btn-preview{background:#e5e7eb;color:#111827} .btn-preview:hover{background:#d1d5db}
  .btn-download{background:#38bdf8;color:#fff}   .btn-download:hover{background:#0ea5e9}
  .btn-edit{background:#f97316;color:#fff}       .btn-edit:hover{background:#ea580c}
  .btn-delete{background:#e11d48;color:#fff}     .btn-delete:hover{background:#be123c}
  
  /* ukuran seragam untuk tombol Aksi (samakan dengan Delete) */
  .btn-action{
  height: 30px;        /* tinggi seragam */
  width: 75px;        /* lebar seragam; ubah ke 100–120 sesuai selera */
  padding: 0 .75rem;   /* tinggi dikontrol oleh height */
  box-sizing: border-box;}
</style>
    

 <div class="py-6">
  <div class="max-w-7xl mx-auto px-6 space-y-4">

    {{-- FILTER BAR --}}
    <form method="GET" action="{{ route('documents.index') }}" class="bg-white p-4 rounded-xl shadow space-y-3">

    {{-- Baris 1: Search (kiri) + Scan (kanan) --}}
    <div class="flex flex-col md:flex-row gap-3 items-start">
        <div class="flex-1">
        <input type="text" name="q" value="{{ $q }}" placeholder="cari judul dokumen / perihal / nomor surat"
                class="w-full border rounded-lg px-3 py-2" />
        </div>

        {{-- Scan dokumen (kanan atas) --}}
        <div class="w-full md:w-auto">
        <a href="{{ route('scan.index') }}" class="btn btn-cta btn-scan">Scan Dokumen</a>
        </div>
    </div>

    {{-- Baris 2: Kategori (kiri) + Unggah (kanan, sejajar & rata kanan) --}}
    <div class="flex flex-col md:flex-row items-center gap-3 justify-between -mt-1">
        <div class="w-full md:w-60">
        <select name="category" class="w-full border rounded-lg px-3 py-2">
            <option value="">Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}" @selected($category===$cat)>{{ $cat }}</option>
            @endforeach
        </select>
        </div>

        <div class="w-full md:w-auto md:ml-auto">
        <a href="{{ route('documents.create') }}" class="btn btn-cta btn-upload">Unggah Dokumen</a>
        </div>
    </div>

    {{-- Baris 3: Tanggal (rapat di bawah kategori) --}}
    <div class="pt-0 -mt-2">
        <p class="text-sm font-semibold mb-1">berdasarkan tanggal</p>
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
            <th class="px-3 py-2 text-center w-56">Aksi</th>
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
                {{-- PENTING: struktur tetap! hanya tambah kelas warna agar tidak hilang --}}
                <a href="{{ route('documents.show',$doc) }}"
                   class="btn btn-action btn-preview">Preview</a>

                <a href="{{ route('documents.download',$doc) }}"
                   class="btn btn-action btn-download">Download</a>

                <a href="{{ route('documents.edit',$doc) }}"
                   class="btn btn-action btn-edit">Edit</a>

                <form action="{{ route('documents.destroy',$doc) }}" method="POST"
                      onsubmit="return confirm('Hapus dokumen ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-action btn-delete">Delete</button>
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