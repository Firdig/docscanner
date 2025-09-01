<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800">Step 1: Scan Dokumen</h2>
    <p class="text-sm text-gray-500">Gunakan mode Simple (UI driver) bila advanced tidak didukung oleh driver.</p>
  </x-slot>

  <div class="max-w-7xl mx-auto p-6 space-y-6">

    <div class="grid lg:grid-cols-3 gap-5">
      <!-- KIRI: Panel kontrol -->
      <div class="p-4 bg-white border rounded-2xl space-y-4">
        <div class="space-y-2">
          <div class="text-xs uppercase tracking-wide text-gray-500">Mode</div>
          <div class="flex items-center gap-4">
            <label class="inline-flex items-center gap-2">
              <input type="radio" name="scanMode" value="simple" checked>
              <span>Simple (UI Driver)</span>
            </label>
            <!-- <label class="inline-flex items-center gap-2">
              <input type="radio" name="scanMode" value="advanced">
              <span>Advanced (Silent)</span>
            </label> -->
          </div>
        </div>

        <!-- <div id="advancedFields" class="space-y-3 hidden">
          <div>
            <label class="text-sm">Pilih Device</label>
            <select id="deviceSelect" class="w-full border rounded p-2">
              <option value="">(Memuat...)</option>
            </select>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="text-sm">DPI</label>
              <select id="dpi" class="w-full border rounded p-2">
                <option>200</option>
                <option selected>300</option>
                <option>600</option>
              </select>
            </div>
            <div>
              <label class="text-sm">Mode Warna</label>
              <select id="pixelType" class="w-full border rounded p-2">
                <option value="2" selected>Color</option>
                <option value="1">Gray</option>
                <option value="0">B/W</option>
              </select>
            </div>
          </div>
          <p class="text-xs text-gray-500">Catatan: beberapa driver (WIA) menolak B/W atau DPI tertentu.</p>
        </div> -->

        <div class="grid grid-cols-2 gap-2">
          <button id="btnScan" class="py-2 rounded-xl bg-blue-600 text-white">Scan</button>
          <!-- <button id="btnSelectSource" class="py-2 rounded-xl border">Pilih Sumber…</button> -->
          <button id="btnDeletePage" class="py-2 rounded-xl border">Hapus Halaman</button>
          <button id="btnClear" class="py-2 rounded-xl border">Clear Semua</button>
          <button id="btnLoadFile" class="py-2 rounded-xl border col-span-2">Muat dari File (uji alur)</button>
        </div>

        <div class="text-xs text-gray-500">
          <div id="status">Status: menunggu WebTWAIN…</div>
        </div>
      </div>

      <!-- KANAN: Preview + toolbar -->
      <div class="lg:col-span-2 p-4 bg-white border rounded-2xl">
        <div class="flex items-center justify-between mb-2">
          <div class="font-semibold">Preview</div>
          <div class="flex items-center gap-2">
            <button id="btnPrev" class="px-3 py-1 border rounded">Prev</button>
            <button id="btnNextPage" class="px-3 py-1 border rounded">Next</button>
            <span id="pageInfo" class="text-sm text-gray-600">—</span>
            <span class="mx-2 w-px h-5 bg-gray-300"></span>
            <!-- <button id="zoomOut" class="px-3 py-1 border rounded">−</button>
            <button id="zoomIn"  class="px-3 py-1 border rounded">+</button> -->
            <button id="rotateLeft"  class="px-3 py-1 border rounded">⟲</button>
            <button id="rotateRight" class="px-3 py-1 border rounded">⟳</button>
            <button id="btnDownloadPdf" class="px-3 py-1 border rounded">Download PDF</button>
          </div>
        </div>
        <div id="dwtcontrolContainer" class="w-full h-[520px] border rounded bg-white"></div>
      </div>
    </div>

    <div class="flex justify-end">
      <button id="btnGoReview" class="px-5 py-2 rounded-xl bg-emerald-600 text-white">
        Lanjut ke Review (Step 2)
      </button>
    </div>
  </div>

  <!-- WEBTWAIN -->
  <script src="https://unpkg.com/dwt/dist/dynamsoft.webtwain.min.js"></script>
  <script>
    // ======== KONFIGURASI POKOK (ikuti sample resmi) ========
    Dynamsoft.DWT.ProductKey    = "t01938AUAABIPFNIR6bNfLqnn3kMLYRRzeUIvxmZGjjQ16xA7fV9v743vRFeNbs2j4RE+5ZwisKodz8M/QZbI8wABlOq7EFXjyskLnDLfKTTfiQVOvuUkcmlqc7ZV2hc3cABeCZDjORwAaiCfpQHeel+9MhQAjwAZADI6A0bA6S188HUdvJiMPqb/JfTMyQucMt9ZF8gcJxY4+ZbTF4ixpDZ/2y0XCOqXUwA8AuQUwO8j6wqEWoBHgHSANrDKkXwBZcYrnA==;t01908AUAAKmXXgjmfCgNUpvM5h/NhQT4ACotaPdgy3Lsk96PUy1UF1obVTkBVV7LlRYmFDrlUSxWYyQEO71o2ef4HmPRvZu4cvICp8x3Cs13YoGTbzmJXOrafNoqzYsTOACvBMhxHQ4ANZDX0gBvvY9eGQqAR4AMABmtASPgdBf9z2dqA1J//edAo5MXOGW+sw7IHCcWOPmW0wfEWFKb3+2WA4L65hQAjwA5BfB7yLqAUAvwCJAO0AZWOZIvQ/4rhw==";     // <-- ganti dengan key kamu
    Dynamsoft.DWT.ResourcesPath = "https://unpkg.com/dwt/dist";
     // Rekomendasi: host sendiri folder Resources/dist. Sementara bisa pakai demo resmi:
    Dynamsoft.DWT.ServiceInstallerLocation = "https://demo.dynamsoft.com/DWT/Resources/dist/"; // :contentReference[oaicite:0]{index=0}
  // [ADD] (opsional) pastikan menggunakan Local Service mode & pop-up fokus
  Dynamsoft.DWT.UseLocalService = true;
  Dynamsoft.DWT.IfAlwaysFocusOnPopupWindow = true; 
     Dynamsoft.DWT.AutoLoad = true;
    Dynamsoft.DWT.Containers = [{
    ContainerId: "dwtcontrolContainer",
    Width: "100%",
    Height: "520px"
  }];

 // [ADD] Update status jika ada error awal (mis. service belum terpasang)
  Dynamsoft.DWT.RegisterEvent("OnWebTwainError", function (err) {       // :contentReference[oaicite:1]{index=1}
    try {
      const msg = (err && (err.message || err)) + "";
      if (/not installed|fail to connect|service/i.test(msg)) {
        setStatus("Memerlukan instalasi komponen scanner — menampilkan dialog installer…");
      }
    } catch {}
  });

Dynamsoft.DWT.Load().catch(err => {
    console.error("[DWT] Load error:", err);
     });
    // Variabel global (konsisten)
    let DWTObject = null;
    let deviceNames = [];

    // Toggle Advanced panel
    const advPanel = document.getElementById('advancedFields');
    document.querySelectorAll('input[name="scanMode"]').forEach(r => {
      r.addEventListener('change', () => {
        const isAdv = document.querySelector('input[name="scanMode"][value="advanced"]').checked;
        advPanel.classList.toggle('hidden', !isAdv);
      });
    });

    // Util status
    const setStatus = (t) => document.getElementById('status').textContent = 'Status: ' + t;

    // Ketika WebTWAIN siap
    Dynamsoft.DWT.RegisterEvent("OnWebTwainReady", async function () {
      DWTObject = Dynamsoft.DWT.GetWebTwain("dwtcontrolContainer");
      if (!DWTObject) { setStatus("gagal inisialisasi"); alert("WebTWAIN belum siap"); return; }

      // Bind viewer supaya preview tampil
      function bindViewer() {
    if (!DWTObject) return;
    try {
      DWTObject.Viewer.bind("dwtcontrolContainer");
      DWTObject.Viewer.setViewMode(1, 1);
      DWTObject.Viewer.showPageNumber = true;
      DWTObject.Viewer.width  = "100%";
      DWTObject.Viewer.height = "100%";
      DWTObject.Viewer.refresh();  // force redraw
    } catch (e) {
      console.warn('[DWT] bindViewer warn:', e);
    }
  }
      // Muat daftar device (untuk Advanced)
      try {
        deviceNames = await DWTObject.GetSourceNamesAsync();
        const sel = document.getElementById('deviceSelect');
        sel.innerHTML = "";
        if (deviceNames && deviceNames.length) {
          deviceNames.forEach((nm, i) => {
            const opt = document.createElement('option');
            opt.value = String(i);
            opt.textContent = nm;
            sel.appendChild(opt);
          });
        } else {
          const opt = document.createElement('option');
          opt.value = "";
          opt.textContent = "(Tidak ada scanner)";
          document.getElementById('deviceSelect').appendChild(opt);
        }
      } catch (e) {
        console.warn("GetSourceNamesAsync error:", e);
      }

      setStatus("siap");
      refreshPageInfo();
    });

    // Info halaman (otomatis update tiap 400ms)
    function refreshPageInfo() {
      if (!DWTObject) { document.getElementById('pageInfo').textContent = '—'; return; }
      const total = DWTObject.HowManyImagesInBuffer || 0;
      const idx   = DWTObject.CurrentImageIndexInBuffer ?? -1;
      document.getElementById('pageInfo').textContent =
        total > 0 ? `Halaman ${idx + 1} / ${total}` : '—';
    }
    setInterval(refreshPageInfo, 400);

    // ======== AKSI TOMBOL ========

    // 1) Scan
    document.getElementById("btnScan").addEventListener("click", async (e) => {
      e.preventDefault();
      if (!DWTObject) {
      setStatus("Menghubungkan layanan scanner…");
      // Meminta DWT mencoba konek; jika tidak ada service, DWT akan munculkan dialog installer
      if (Dynamsoft?.DWT?.ConnectToTheService) {      // tersedia di API global DWT v18+  :contentReference[oaicite:2]{index=2}
        Dynamsoft.DWT.ConnectToTheService();
      } else {
        // fallback: buka halaman installer (kalau pop-up diblokir, user tinggal klik ulang tombol Scan)
        window.open(Dynamsoft.DWT.ServiceInstallerLocation, "_blank");
      }
      return;
    }

      const isSimple = document.querySelector('input[name="scanMode"][value="simple"]').checked;
    try {
      if (isSimple) {
        setStatus("menunggu dialog driver…");
        await DWTObject.SelectSourceAsync();
        await DWTObject.AcquireImageAsync({ IfCloseSourceAfterAcquire: true });
      } else {
          // Mode ADVANCED: pilih device & atur minimal (Color + DPI)
          const idxStr = document.getElementById('deviceSelect').value;
          const idx    = parseInt(idxStr, 10);
          if (isNaN(idx) || !deviceNames[idx]) {
            alert("Pilih device terlebih dahulu (Advanced).");
            return;
          }
          setStatus("membuka sumber…");
          await DWTObject.SelectSourceAsync(deviceNames[idx]);

          // set parameter dengan try/catch supaya gagal silent (driver beda-beda)
          DWTObject.IfShowUI = false;
          try { DWTObject.PixelType = parseInt(document.getElementById('pixelType').value, 10); } catch {}
          try { DWTObject.Resolution = parseInt(document.getElementById('dpi').value, 10); } catch {}

          setStatus("memindai…");
          await DWTObject.AcquireImageAsync({ IfCloseSourceAfterAcquire: true });
        }
        setStatus("selesai scan");
      } catch (exp) {
        console.error(exp);
        setStatus("gagal");
        alert(exp.message || exp);
      }
    });

    // 2) Pilih Sumber (manual)
    // document.getElementById("btnSelectSource").addEventListener("click", async () => {
    //   if (!DWTObject) return;
    //   try {
    //     await DWTObject.SelectSourceAsync(); // tampilkan dialog sumber
    //   } catch (e) {
    //     alert(e.message || e);
    //   }
    // });

    // 3) Hapus halaman aktif
    document.getElementById("btnDeletePage").addEventListener("click", function () {
      if (!DWTObject) return;
      const i = DWTObject.CurrentImageIndexInBuffer;
      if (i >= 0) DWTObject.RemoveImage(i);
    });

    // 4) Clear semua
    document.getElementById("btnClear").addEventListener("click", function () {
      DWTObject?.RemoveAllImages?.();
    });

    // 5) Navigasi halaman
    document.getElementById("btnPrev").addEventListener("click", () => {
      if (!DWTObject) return;
      const i = DWTObject.CurrentImageIndexInBuffer;
      if (i > 0) DWTObject.CurrentImageIndexInBuffer = i - 1;
    });
    document.getElementById("btnNextPage").addEventListener("click", () => {
      if (!DWTObject) return;
      const i = DWTObject.CurrentImageIndexInBuffer;
      const total = DWTObject.HowManyImagesInBuffer;
      if (i < total - 1) DWTObject.CurrentImageIndexInBuffer = i + 1;
    });

    // 6) Zoom & Rotate
    // document.getElementById("zoomIn").addEventListener("click", () => {
    //   if (DWTObject) DWTObject.Viewer.zoom = (DWTObject.Viewer.zoom || 1) + 0.1;
    // });
    // document.getElementById("zoomOut").addEventListener("click", () => {
    //   if (DWTObject) DWTObject.Viewer.zoom = Math.max(0.1, (DWTObject.Viewer.zoom || 1) - 0.1);
    // });
    document.getElementById("rotateLeft").addEventListener("click", () => {
      if (!DWTObject) return;
      const i = DWTObject.CurrentImageIndexInBuffer;
      if (i >= 0) DWTObject.RotateLeft(i);
    });
    document.getElementById("rotateRight").addEventListener("click", () => {
      if (!DWTObject) return;
      const i = DWTObject.CurrentImageIndexInBuffer;
      if (i >= 0) DWTObject.RotateRight(i);
    });

    // 7) Muat dari file (uji alur tanpa scanner)
    document.getElementById("btnLoadFile").addEventListener("click", () => {
      if (!DWTObject) return;
      // "" + 5 => buka dialog & dukung multi-format
      DWTObject.LoadImageEx("", 5,
        () => setStatus("file dimuat"),
        (code, msg) => alert("Gagal muat file: " + msg)
      );
    });

    // 8) Download PDF lokal (tanpa backend, untuk uji)
    document.getElementById("btnDownloadPdf").addEventListener("click", () => {
      if (!DWTObject || DWTObject.HowManyImagesInBuffer === 0) { alert("Belum ada halaman."); return; }
      DWTObject.ConvertToBase64(
        [0, DWTObject.HowManyImagesInBuffer - 1],
        Dynamsoft.DWT.EnumDWT_ImageType.IT_PDF,
        (base64) => {
          const blob = b64ToBlob(base64, "application/pdf");
          const url  = URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = url; a.download = 'scan.pdf'; a.click();
          URL.revokeObjectURL(url);
        },
        (c, m) => alert("Export gagal: " + m)
      );
    });

    // 9) Lanjut Step 2 (simpan ke sessionStorage)
    document.getElementById("btnGoReview").addEventListener("click", () => {
      if (!DWTObject || DWTObject.HowManyImagesInBuffer === 0) {
        alert("Belum ada halaman yang di-scan.");
        return;
      }
      DWTObject.ConvertToBase64(
  [0, DWTObject.HowManyImagesInBuffer - 1],
  Dynamsoft.DWT.EnumDWT_ImageType.IT_PDF,
  function (result /* Base64Result */) {
    const b64 = result.getData(0, result.getLength()); // ✅ ambil string base64 murni
    sessionStorage.setItem("scanPDF", b64);
    window.location.href = "{{ route('scan.step2') }}";
  },
  function (code, msg) { alert("Export gagal: " + msg); }
);
    });

    // Util: base64 -> Blob
    function b64ToBlob(base64, mime) {
      const byteChars = atob(base64);
      const byteArr = new Uint8Array(byteChars.length);
      for (let i = 0; i < byteChars.length; i++) byteArr[i] = byteChars.charCodeAt(i);
      return new Blob([byteArr], { type: mime });
    }
  </script>
</x-app-layout>
