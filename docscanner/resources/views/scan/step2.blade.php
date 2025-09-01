<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800">Step 2: Metadata & Simpan</h2>
    <p class="text-sm text-gray-500">Isi detail dokumen, lalu simpan ke storage.</p>
  </x-slot>

  <div class="max-w-6xl mx-auto p-6 space-y-6">
    <div class="grid lg:grid-cols-2 gap-6">
      <!-- Kiri: Preview ringkas (ambil dari sessionStorage) -->
      <div class="p-4 bg-white border rounded-2xl">
        <div class="flex items-center justify-between mb-2">
          <h3 class="font-semibold">Preview</h3>
          <button id="btnKembaliScan" class="px-3 py-1 border rounded">← Kembali ke Scan</button>
        </div>
        <iframe id="preview" class="w-full h-[520px] border rounded"></iframe>
        <p id="previewInfo" class="text-xs text-gray-500 mt-2"></p>
      </div>

      <!-- Kanan: Form metadata -->
      <div class="p-4 bg-white border rounded-2xl">
        <h3 class="font-semibold mb-3">Metadata Dokumen</h3>

        <form id="metaForm" class="grid gap-3">
          <input name="title" placeholder="Judul/Perihal" class="border rounded p-2" required>
          <input name="letter_number" placeholder="Nomor Surat" class="border rounded p-2">
          <div class="grid md:grid-cols-2 gap-3">
            <input type="date" name="document_date" class="border rounded p-2" placeholder="Tanggal Dokumen">
            <input name="category" placeholder="Kategori" class="border rounded p-2">
          </div>
          <div class="grid md:grid-cols-2 gap-3">
            <input name="year" placeholder="Tahun" class="border rounded p-2">
            <input name="description" placeholder="Deskripsi (opsional)" class="border rounded p-2">
          </div>

          <div class="flex items-center gap-3 pt-2">
            <button id="btnSave" type="button" class="px-4 py-2 rounded-xl bg-emerald-600 text-white">
              Simpan ke Storage
            </button>
            <button id="btnDownloadLocal" type="button" class="px-4 py-2 rounded-xl border">
              Download PDF (lokal)
            </button>
            <span id="status" class="text-sm text-gray-500"></span>
          </div>
        </form>

        <hr class="my-4">

        <div class="flex items-center gap-2">
          <a href="/documents" class="px-3 py-2 rounded border">Buka Storage</a>
          <button id="btnResetFlow" class="px-3 py-2 rounded border">Mulai Ulang Alur</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Ambil PDF base64 dari Step 1
    const base64 = sessionStorage.getItem("scanPDF");
    const statusEl = document.getElementById('status');
    const setStatus = t => statusEl.textContent = t || '';

    // Guard: bersihkan sisa cache lama yang berisi "[object Object]"
if (base64 && base64.includes("[object Object]")) {
  sessionStorage.removeItem("scanPDF");
  alert("Cache hasil scan lama tidak valid. Silakan ulangi dari Step 1.");
  window.location.href = "{{ route('scan.step1') }}";
}

// Tampilkan preview
if (base64) {
  document.getElementById('preview').src = "data:application/pdf;base64," + base64;
} else {
  alert("Belum ada hasil scan."); window.location.href = "{{ route('scan.step1') }}";
}

    document.getElementById('btnKembaliScan').onclick = () => {
      window.location.href = "{{ route('scan.step1') }}";
    };

    // Download lokal (opsional)
    document.getElementById('btnDownloadLocal').onclick = () => {
      const blob = b64ToBlob(base64, "application/pdf");
      const url  = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url; a.download = 'scan.pdf'; a.click();
      URL.revokeObjectURL(url);
    };

    // Reset flow
    document.getElementById('btnResetFlow').onclick = () => {
      sessionStorage.removeItem('scanPDF');
      window.location.href = "{{ route('scan.step1') }}";
    };

    // SIMPAN ke Storage (upload ke Laravel)
    document.getElementById('btnSave').addEventListener('click', async () => {
      const form = document.getElementById('metaForm');
      if (!form.reportValidity()) return;

      setStatus('Menyiapkan & mengunggah...');
      try {
        const fd = new FormData(form);
        const blob = b64ToBlob(base64, "application/pdf");
        fd.append('file', blob, 'scan.pdf');

        const resp = await fetch("{{ route('scan.upload') }}", {
          method: 'POST',
          headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
          body: fd
        });

        if (!resp.ok) {
          const t = await resp.text();
          setStatus('');
          alert('Upload gagal: ' + t);
          return;
        }

        const data = await resp.json();
        setStatus('Tersimpan ✅');
        // Bersihkan buffer sementara agar alur segar
        sessionStorage.removeItem('scanPDF');

        // Arahkan ke Storage atau detail dokumen
        // window.location.href = /documents/${data.document_id}; // kalau ada halaman detail
        // default: ke daftar storage
        window.location.href = "/documents";

      } catch (e) {
        console.error(e);
        setStatus('');
        alert('Terjadi error saat upload: ' + (e.message || e));
      }
    });

    // Util: base64 → Blob
    function b64ToBlob(base64, mime) {
      const byteChars = atob(base64);
      const byteArr = new Uint8Array(byteChars.length);
      for (let i = 0; i < byteChars.length; i++) byteArr[i] = byteChars.charCodeAt(i);
      return new Blob([byteArr], { type: mime });
    }
  </script>
</x-app-layout>