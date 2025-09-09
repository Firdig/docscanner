<x-app-layout>
  <x-slot name="header">
  <h2 class="font-semibold text-xl text-gray-800">Step 1: Scan Dokumen</h2>
  
</x-slot>

<div class="max-w-7xl mx-auto p-6 space-y-6">

  <div class="grid lg:grid-cols-3 gap-5">
    <!-- KIRI: Panel kontrol -->
    <div class="p-4 bg-white border rounded-2xl space-y-5">
      <!-- Mode -->
      <div class="space-y-2">
        <div class="text-xs uppercase tracking-wide text-gray-500">Mode</div>
        <div class="flex items-center gap-4">
          <label class="inline-flex items-center gap-2">
            <input type="radio" name="scanMode" value="simple" checked>
            <span class="text-sm text-gray-800">Simple (UI Driver)</span>
          </label>
          {{-- Opsi advanced disembunyikan sementara --}}
          {{-- 
          <label class="inline-flex items-center gap-2">
            <input type="radio" name="scanMode" value="advanced">
            <span class="text-sm text-gray-800">Advanced (Silent)</span>
          </label>
          --}}
        </div>
      </div>

      <!-- Aksi -->
      <div class="grid grid-cols-2 gap-2">
        <button id="btnScan" data-requires-sdk
          class="inline-flex items-center justify-center rounded-xl text-sm font-semibold px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
          Scan
        </button>

        {{-- <button id="btnSelectSource" class="inline-flex items-center justify-center rounded-xl text-sm font-semibold px-4 py-2 border hover:bg-gray-50">Pilih Sumber…</button> --}}

        <button id="btnDeletePage" data-requires-page
          class="inline-flex items-center justify-center rounded-xl text-sm font-semibold px-4 py-2 border hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
          Hapus Halaman
        </button>

        <button id="btnClear" data-requires-sdk
          class="inline-flex items-center justify-center rounded-xl text-sm font-semibold px-4 py-2 border hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
          Clear Semua
        </button>

        <button id="btnLoadFile" class="col-span-2 inline-flex items-center justify-center rounded-xl text-sm font-semibold px-4 py-2 border hover:bg-gray-50">
          Muat dari File (uji alur)
        </button>
      </div>

      <!-- Status -->
      <div class="text-xs text-gray-600" role="status" aria-live="polite">
        <div id="status" class="inline-flex items-center gap-2">
          <span id="statusDot" class="inline-block w-2.5 h-2.5 rounded-full bg-amber-500"></span>
          <span id="statusText">Status: menunggu WebTWAIN…</span>
        </div>
      </div>
    </div>

    <!-- KANAN: Preview + toolbar -->
    <div class="lg:col-span-2 p-4 bg-white border rounded-2xl">
      <div class="flex items-center justify-between mb-3">
        <div class="font-semibold">Preview</div>
        <div class="flex items-center gap-2">
          <button id="btnPrev" data-requires-page
            class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" title="Halaman sebelumnya">
            Prev
          </button>
          <button id="btnNextPage" data-requires-page
            class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" title="Halaman berikutnya">
            Next
          </button>
          <span id="pageInfo" class="text-sm text-gray-600 select-none">—</span>

          <span class="mx-2 w-px h-5 bg-gray-300"></span>

          <button id="rotateLeft" data-requires-page
            class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" title="Putar kiri (⟲)">
            ⟲
          </button>
          <button id="rotateRight" data-requires-page
            class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" title="Putar kanan (⟳)">
            ⟳
          </button>

          <button id="btnDownloadPdf" data-requires-page
            class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" title="Unduh sebagai PDF">
            Download PDF
          </button>
        </div>
      </div>

      <div id="dwtcontrolContainer"
           class="w-full h-[520px] border rounded bg-white overflow-hidden">
        {{-- Kontainer WebTWAIN akan render canvas/preview di sini --}}
      </div>
    </div>
  </div>

  <div class="flex justify-end">
    <button id="btnGoReview"
      class="px-5 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed"
      enabled>
      Lanjut ke Review (Step 2)
    </button>
  </div>
</div>



  <!-- WEBTWAIN -->
<script src="https://unpkg.com/dwt/dist/dynamsoft.webtwain.min.js"></script>
<script>
  // [ADD] Helper status didefinisikan paling awal agar aman dipakai di mana saja
  const setStatus = (t) => {
    const el = document.getElementById('status');
    if (el) el.textContent = 'Status: ' + t;
  };

  // ======== KONFIGURASI POKOK (tetap mengacu ke code kamu) ========
  Dynamsoft.DWT.ProductKey    = "t01938AUAABIPFNIR6bNfLqnn3kMLYRRzeUIvxmZGjjQ16xA7fV9v743vRFeNbs2j4RE+5ZwisKodz8M/QZbI8wABlOq7EFXjyskLnDLfKTTfiQVOvuUkcmlqc7ZV2hc3cABeCZDjORwAaiCfpQHeel+9MhQAjwAZADI6A0bA6S188HUdvJiMPqb/JfTMyQucMt9ZF8gcJxY4+ZbTF4ixpDZ/2y0XCOqXUwA8AuQUwO8j6wqEWoBHgHSANrDKkXwBZcYrnA==;t01908AUAAKmXXgjmfCgNUpvM5h/NhQT4ACotaPdgy3Lsk96PUy1UF1obVTkBVV7LlRYmFDrlUSxWYyQEO71o2ef4HmPRvZu4cvICp8x3Cs13YoGTbzmJXOrafNoqzYsTOACvBMhxHQ4ANZDX0gBvvY9eGQqAR4AMABmtASPgdBf9z2dqA1J//edAo5MXOGW+sw7IHCcWOPmW0wfEWFKb3+2WA4L65hQAjwA5BfB7yLqAUAvwCJAO0AZWOZIvQ/4rhw==";
  Dynamsoft.DWT.ResourcesPath = "https://unpkg.com/dwt/dist";
  Dynamsoft.DWT.ServiceInstallerLocation = "https://demo.dynamsoft.com/DWT/Resources/dist/";
  Dynamsoft.DWT.UseLocalService = true;
  Dynamsoft.DWT.IfAlwaysFocusOnPopupWindow = true;

  // [CHANGE] Pastikan event terdaftar dulu, baru load → matikan autoload otomatis
  Dynamsoft.DWT.AutoLoad = false;

  Dynamsoft.DWT.Containers = [{
    ContainerId: "dwtcontrolContainer",
    Width: "100%",
    Height: "520px"
  }];

  // [ADD] Log error awal (agar tahu kalau service/port/CSP terblokir)
  Dynamsoft.DWT.RegisterEvent("OnWebTwainError", function (err) {
    const msg = (err && (err.message || err)) + "";
    console.warn("[DWT] Error:", msg);
    if (/not installed|fail to connect|service/i.test(msg)) {
      setStatus("Tidak dapat terhubung ke layanan scanner (cek service/port/CSP/PNA).");
    }
  });

  // Variabel global (tetap)
  let DWTObject = null;
  let deviceNames = [];

  // [SAFEGUARD] Advanced panel mungkin kamu komentari — lindungi aksesnya
  const advPanel = document.getElementById('advancedFields');
  const scanModeRadios = document.querySelectorAll('input[name="scanMode"]');
  if (advPanel && scanModeRadios.length) {
    scanModeRadios.forEach(r => {
      r.addEventListener('change', () => {
        const isAdv = document.querySelector('input[name="scanMode"][value="advanced"]')?.checked;
        advPanel.classList.toggle('hidden', !isAdv);
      });
    });
  }

  // Info halaman (otomatis update tiap 400ms) — tetap
  // function refreshPageInfo() {
  //   if (!DWTObject) {
  //     const el = document.getElementById('pageInfo');
  //     if (el) el.textContent = '—';
  //     return;
  //   }
  //   const total = DWTObject.HowManyImagesInBuffer || 0;
  //   const idx   = (DWTObject.CurrentImageIndexInBuffer ?? -1);
  //   const el = document.getElementById('pageInfo');
  //   if (el) el.textContent = total > 0 ? Halaman ${idx + 1} / ${total} : '—';
  // }
  // setInterval(refreshPageInfo, 400);

  // [CHANGE] Daftarkan handler siap lebih awal, sebelum Load()
  Dynamsoft.DWT.RegisterEvent("OnWebTwainReady", async function () {
    DWTObject = Dynamsoft.DWT.GetWebTwain("dwtcontrolContainer");
    window.DWTObject = DWTObject; // [ADD] memudahkan debug di Console
    if (!DWTObject) { setStatus("gagal inisialisasi"); alert("WebTWAIN belum siap"); return; }

    // Muat daftar device (hanya bila elemen ada)
    try {
      deviceNames = await DWTObject.GetSourceNamesAsync();
      const sel = document.getElementById('deviceSelect'); // bisa tidak ada (kamu komentari)
      if (sel) {
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
          sel.appendChild(opt);
        }
      }
    } catch (e) {
      console.warn("GetSourceNamesAsync error:", e);
    }

    setStatus("siap");
    refreshPageInfo();
  });

  // [CHANGE] Load setelah semua event terdaftar → mencegah OnWebTwainReady “terlewat”
  Dynamsoft.DWT.Load().catch(err => {
    console.error("[DWT] Load error:", err);
  });

  // ======== AKSI TOMBOL (tetap seperti punyamu) ========

  // 1) Scan
  document.getElementById("btnScan").addEventListener("click", async (e) => {
    e.preventDefault();
    if (!DWTObject) {
      setStatus("Menghubungkan layanan scanner…");
      if (Dynamsoft?.DWT?.ConnectToTheService) {
        Dynamsoft.DWT.ConnectToTheService(); // [KEEP] biarkan DWT memicu installer jika perlu
      } else {
        window.open(Dynamsoft.DWT.ServiceInstallerLocation, "_blank");
      }
      return;
    }

    const isSimple = document.querySelector('input[name="scanMode"][value="simple"]')?.checked;
    try {
      if (isSimple) {
        setStatus("menunggu dialog driver…");
        await DWTObject.SelectSourceAsync();
        await DWTObject.AcquireImageAsync({ IfCloseSourceAfterAcquire: true });
      } else {
        const idxStr = document.getElementById('deviceSelect')?.value;
        const idx    = parseInt(idxStr, 10);
        if (isNaN(idx) || !deviceNames[idx]) { alert("Pilih device terlebih dahulu (Advanced)."); return; }

        setStatus("membuka sumber…");
        await DWTObject.SelectSourceAsync(deviceNames[idx]);

        DWTObject.IfShowUI = false;
        try { DWTObject.PixelType = parseInt(document.getElementById('pixelType')?.value, 10); } catch {}
        try { DWTObject.Resolution = parseInt(document.getElementById('dpi')?.value, 10); } catch {}

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

  // 6) Rotate
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

  // 7) Muat dari file (uji alur)
  document.getElementById("btnLoadFile").addEventListener("click", () => {
    if (!DWTObject) return;
    DWTObject.LoadImageEx("", 5,
      () => setStatus("file dimuat"),
      (code, msg) => alert("Gagal muat file: " + msg)
    );
  });

  // 8) Download PDF lokal
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
      function (result) {
        const b64 = result.getData(0, result.getLength()); // ✅ ambil string base64 murni
        sessionStorage.setItem("scanPDF", b64);
        window.location.href = "{{ route('scan.step2') }}";
      },
      function (code, msg) { alert("Export gagal: " + msg); }
    );
  });

  // Util: base64 -> Blob (tetap)
  function b64ToBlob(base64, mime) {
    const byteChars = atob(base64);
    const byteArr = new Uint8Array(byteChars.length);
    for (let i = 0; i < byteChars.length; i++) byteArr[i] = byteChars.charCodeAt(i);
    return new Blob([byteArr], { type: mime });
  }
</script>

</x-app-layout>