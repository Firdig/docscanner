<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Scanner Wizard</h2>
  </x-slot>

  <div class="max-w-6xl mx-auto p-6 space-y-6">
    <!-- Progress Ringkas -->
    <div id="progress" class="flex items-center gap-3 text-sm">
      <div id="p1" class="px-3 py-1 rounded-full border">Step 1</div>
      <span>—</span>
      <div id="p2" class="px-3 py-1 rounded-full border">Step 2</div>
      <span>—</span>
      <div id="p3" class="px-3 py-1 rounded-full border">Step 3</div>
    </div>

    <!-- Kartu Langkah -->
    <div class="grid md:grid-cols-3 gap-4">
      <!-- Step 1 -->
      <div class="p-5 rounded-2xl border bg-white flex flex-col">
        <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Step 1</div>
        <h3 class="text-lg font-semibold">Scan & Preview</h3>
        <p class="text-sm text-gray-600 mt-1">Pilih device, atur DPI/warna, lakukan pemindaian.</p>
        <div class="mt-auto pt-4 flex items-center justify-between">
          <span id="s1badge" class="text-xs px-2 py-1 rounded bg-gray-100">Belum mulai</span>
          <a href="{{ route('scan.step1') }}" class="px-4 py-2 rounded-xl bg-blue-600 text-white">Mulai</a>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="p-5 rounded-2xl border bg-white flex flex-col">
        <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Step 2</div>
        <h3 class="text-lg font-semibold">Review Hasil</h3>
        <p class="text-sm text-gray-600 mt-1">Tinjau semua halaman & pastikan sudah benar.</p>
        <div class="mt-auto pt-4 flex items-center justify-between">
          <span id="s2badge" class="text-xs px-2 py-1 rounded bg-gray-100">Terkunci</span>
          <a id="goStep2" href="{{ route('scan.step2') }}"
             class="px-4 py-2 rounded-xl bg-green-600 text-white opacity-40 pointer-events-none">Lanjut</a>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="p-5 rounded-2xl border bg-white flex flex-col">
        <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Step 3</div>
        <h3 class="text-lg font-semibold">Metadata & Simpan</h3>
        <p class="text-sm text-gray-600 mt-1">Isi judul, nomor, tanggal, kategori, tahun, deskripsi.</p>
        <div class="mt-auto pt-4 flex items-center justify-between">
          <span id="s3badge" class="text-xs px-2 py-1 rounded bg-gray-100">Terkunci</span>
          <a id="goStep3" href="{{ route('scan.step3') }}"
             class="px-4 py-2 rounded-xl bg-emerald-600 text-white opacity-40 pointer-events-none">Simpan</a>
        </div>
      </div>
    </div>

    <!-- Aksi tambahan -->
    <div class="flex items-center justify-between">
      <div class="text-sm text-gray-500">
        Tip: Pastikan <b>Dynamsoft Service</b> terpasang di komputer untuk menjalankan pemindaian.
      </div>
      <button id="resetFlow" class="text-sm px-3 py-2 rounded border">Mulai Ulang Alur</button>
    </div>
  </div>

  <script>
    // Logika aktivasi Step 2/3 berdasarkan adanya hasil scan sementara di sessionStorage
    const pdfBase64 = sessionStorage.getItem('scanPDF');

    // Progress badge styling helper
    function mark(el, type){
      // type: 'done' | 'active' | 'locked' | 'idle'
      el.classList.remove('bg-gray-100','bg-blue-100','bg-green-100','bg-red-100');
      if(type==='done'){ el.classList.add('bg-green-100'); }
      else if(type==='active'){ el.classList.add('bg-blue-100'); }
      else if(type==='locked'){ el.classList.add('bg-red-100'); }
      else { el.classList.add('bg-gray-100'); }
    }

    const s1badge = document.getElementById('s1badge');
    const s2badge = document.getElementById('s2badge');
    const s3badge = document.getElementById('s3badge');

    const p1 = document.getElementById('p1');
    const p2 = document.getElementById('p2');
    const p3 = document.getElementById('p3');

    const goStep2 = document.getElementById('goStep2');
    const goStep3 = document.getElementById('goStep3');

    if (pdfBase64) {
      // Sudah ada hasil scan sementara → Step 2 & 3 aktif
      s1badge.textContent = 'Selesai (sementara)';
      s2badge.textContent = 'Siap ditinjau';
      s3badge.textContent = 'Siap disimpan';

      goStep2.classList.remove('opacity-40','pointer-events-none');
      goStep3.classList.remove('opacity-40','pointer-events-none');

      mark(p1, 'done'); mark(p2, 'active'); mark(p3, 'idle');
    } else {
      // Belum ada hasil → Step 2 & 3 terkunci
      s1badge.textContent = 'Belum mulai';
      s2badge.textContent = 'Terkunci';
      s3badge.textContent = 'Terkunci';

      mark(p1, 'active'); mark(p2, 'locked'); mark(p3, 'locked');
    }

    // Mulai ulang alur: hapus hasil sementara
    document.getElementById('resetFlow').addEventListener('click', () => {
      sessionStorage.removeItem('scanPDF');
      // Refresh agar state UI kembali ke awal
      window.location.reload();
    });
  </script>
</x-app-layout>
