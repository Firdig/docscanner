<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800">Step 1: Scan Dokumen</h2>
    <p class="text-sm text-gray-500">Pilih cara pemindaian, lakukan scan, dan tinjau hasil di panel preview.</p>
  </x-slot>

  <div class="max-w-6xl mx-auto p-6 space-y-6">
    <div class="grid md:grid-cols-3 gap-5">
      <!-- Panel kiri: Controls -->
      <div class="p-4 bg-white border rounded-2xl space-y-4">
        <div>
          <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Mode</div>
          <div class="flex items-center gap-2">
            <label class="inline-flex items-center gap-2">
              <input type="radio" name="mode" value="simple" checked>
              <span>Simple (UI Driver)</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input type="radio" name="mode" value="advanced">
              <span>Advanced (Silent)</span>
            </label>
          </div>
        </div>

        <div id="advancedPanel" class="space-y-3 hidden">
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
          <p class="text-xs text-gray-500">Catatan: kalau driver menolak pengaturan ini, kembali ke mode Simple ya.</p>
        </div>

        <div class="pt-2">
          <button id="btnScan" class="w-full py-2 rounded-xl bg-blue-600 text-white">Scan</button>
          <div class="mt-2 grid grid-cols-2 gap-2">
            <button id="btnDeletePage" class="py-2 rounded border">Hapus Halaman</button>
            <button id="btnClear" class="py-2 rounded border">Clear Semua</button>
          </div>
        </div>

        <div class="text-xs text-gray-500">
          Tips: kalau gagal, coba mode <b>Simple</b> dulu. Setelah berhasil, baru coba mode <b>Advanced</b>.
        </div>
      </div>

      <!-- Panel kanan: Preview & toolbar -->
      <div class="md:col-span-2 p-4 bg-white border rounded-2xl">
        <div class="flex items-center justify-between mb-2">
          <h3 class="font-semibold">Preview</h3>
          <div class="flex items-center gap-2">
            <button id="zoomOut" class="px-3 py-1 border rounded">−</button>
            <button id="zoomIn"  class="px-3 py-1 border rounded">+</button>
            <span id="pageInfo" class="text-sm text-gray-600"></span>
          </div>
        </div>
        <div id="dwtcontrolContainer" class="w-full h-[440px] border rounded"></div>
      </div>
    </div>

    <div class="flex justify-end">
      <button id="btnNext" class="px-5 py-2 rounded-xl bg-emerald-600 text-white">
        Lanjut ke Review
      </button>
    </div>
  </div>


  <!-- Ganti script lama di step1 dengan ini (bagian <script>) -->
<script src="https://unpkg.com/dwt/dist/dynamsoft.webtwain.min.js"></script>
<script>
  let DWObject = null;
  let deviceNames = [];

  // 1) Product key & resources dari CDN (bukan /dwt lokal)
  Dynamsoft.DWT.ProductKey = "t01938AUAABIPFNIR6bNfLqnn3kMLYRRzeUIvxmZGjjQ16xA7fV9v743vRFeNbs2j4RE+5ZwisKodz8M/QZbI8wABlOq7EFXjyskLnDLfKTTfiQVOvuUkcmlqc7ZV2hc3cABeCZDjORwAaiCfpQHeel+9MhQAjwAZADI6A0bA6S188HUdvJiMPqb/JfTMyQucMt9ZF8gcJxY4+ZbTF4ixpDZ/2y0XCOqXUwA8AuQUwO8j6wqEWoBHgHSANrDKkXwBZcYrnA==;t01908AUAAKmXXgjmfCgNUpvM5h/NhQT4ACotaPdgy3Lsk96PUy1UF1obVTkBVV7LlRYmFDrlUSxWYyQEO71o2ef4HmPRvZu4cvICp8x3Cs13YoGTbzmJXOrafNoqzYsTOACvBMhxHQ4ANZDX0gBvvY9eGQqAR4AMABmtASPgdBf9z2dqA1J//edAo5MXOGW+sw7IHCcWOPmW0wfEWFKb3+2WA4L65hQAjwA5BfB7yLqAUAvwCJAO0AZWOZIvQ/4rhw==";
  Dynamsoft.DWT.ResourcesPath = "https://unpkg.com/dwt/dist";

   // 2) VAR GLOBAL (konsisten: DWTObject)
  let DWTObject = null;

  // 3) Siapkan UI setelah kontrol siap
  Dynamsoft.DWT.RegisterEvent("OnWebTwainReady", function () {
    DWTObject = Dynamsoft.DWT.GetWebTwain("dwtcontrolContainer");
    if (!DWTObject) { alert("WebTWAIN belum siap"); return; }

    // Wajib agar preview muncul:
    DWTObject.Viewer.bind("dwtcontrolContainer");
    DWTObject.Viewer.setViewMode(1, 1);       // 1 kolom x 1 baris
    DWTObject.Viewer.showPageNumber = true;
    DWTObject.Viewer.width  = "100%";
    DWTObject.Viewer.height = "100%";
  });

  // 4) Scan (gaya sample → paling kompatibel)
  document.getElementById("btnScan").addEventListener("click", function (e) {
    e.preventDefault();
    if (!DWTObject) { alert("Scanner belum siap."); return; }

    DWTObject.SelectSourceAsync()
      .then(function () {
        return DWTObject.AcquireImageAsync({
          IfCloseSourceAfterAcquire: true
        });
      })
      .catch(function (exp) {
        alert(exp.message || exp);
      });
  });

  // 5) Hapus halaman aktif
  document.getElementById("btnDeletePage").addEventListener("click", function () {
    if (!DWTObject) return;
    const i = DWTObject.CurrentImageIndexInBuffer;
    if (i >= 0) DWTObject.RemoveImage(i);
  });

  // 6) Clear semua
  document.getElementById("btnClear").addEventListener("click", function () {
    DWTObject?.RemoveAllImages?.();
  });

  // 7) Zoom
  document.getElementById("zoomIn").addEventListener("click", () => {
    if (DWTObject) DWTObject.Viewer.zoom = (DWTObject.Viewer.zoom || 1) + 0.1;
  });
  document.getElementById("zoomOut").addEventListener("click", () => {
    if (DWTObject) DWTObject.Viewer.zoom = Math.max(0.1, (DWTObject.Viewer.zoom || 1) - 0.1);
  });

  // 8) Next → export ke base64 PDF → simpan → Step 2
  document.getElementById("btnNext").addEventListener("click", function () {
    if (!DWTObject || DWTObject.HowManyImagesInBuffer === 0) {
      alert("Belum ada halaman yang di-scan.");
      return;
    }
    DWTObject.ConvertToBase64(
      [0, DWTObject.HowManyImagesInBuffer - 1],
      Dynamsoft.DWT.EnumDWT_ImageType.IT_PDF,
      function (base64) {
        sessionStorage.setItem("scanPDF", base64);
        window.location.href = "{{ route('scan.step2') }}";
      },
      function (code, msg) {
        alert("Export gagal: " + msg);
      }
    );
  });
</script>


</x-app-layout>
