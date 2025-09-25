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
          </div>
        </div>

        <!-- Kualitas Scan -->
        <div class="space-y-2">
          <div class="text-xs uppercase tracking-wide text-gray-500">Kualitas Scan</div>
          <select id="scanQuality" class="w-full px-3 py-2 border rounded text-sm">
            <option value="draft">Draft (150 DPI) - File kecil</option>
            <option value="normal" selected>Normal (300 DPI) - Seimbang</option>
            <option value="high">High (600 DPI) - File besar</option>
            <option value="custom">Custom...</option>
          </select>
          
          <div id="customSettings" class="grid grid-cols-2 gap-2 hidden">
            <input id="customDpi" type="number" placeholder="DPI" class="px-2 py-1 border rounded text-sm" value="300" min="75" max="1200">
            <input id="customQuality" type="number" placeholder="Quality %" class="px-2 py-1 border rounded text-sm" value="85" min="10" max="100">
          </div>
          
          <div class="text-xs text-gray-500" id="qualityInfo">
            Estimasi ukuran per halaman: ~500KB
          </div>
        </div>

        <!-- Aksi -->
        <div class="grid grid-cols-2 gap-2">
          <button id="btnScan" data-requires-sdk
            class="inline-flex items-center justify-center rounded-xl text-sm font-semibold px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
            Scan
          </button>

          <button id="btnDeletePage" data-requires-page
            class="inline-flex items-center justify-center rounded-xl text-sm font-semibold px-4 py-2 border hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
            Hapus Halaman
          </button>

          <button id="btnClear" data-requires-sdk
            class="inline-flex items-center justify-center rounded-xl text-sm font-semibold px-4 py-2 border hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
            Clear Semua
          </button>

          <button id="btnLoadFile" class="inline-flex items-center justify-center rounded-xl text-sm font-semibold px-4 py-2 border hover:bg-gray-50">
            Muat dari File
          </button>
        </div>

        <!-- Status -->
        <div class="text-xs text-gray-600" role="status" aria-live="polite">
          <div id="status" class="inline-flex items-center gap-2">
            <span id="statusDot" class="inline-block w-2.5 h-2.5 rounded-full bg-amber-500"></span>
            <span id="statusText">Status: menunggu WebTWAIN…</span>
          </div>
        </div>

        <!-- File Size Warning -->
        <div id="sizeWarning" class="hidden p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-800">
          <div class="flex items-center justify-between">
            <span>⚠️ File besar terdeteksi</span>
            <button onclick="this.parentElement.parentElement.classList.add('hidden')" class="text-yellow-600">✕</button>
          </div>
          <div class="mt-1 text-xs">Preview mungkin lambat</div>
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

            <!-- <button id="btnDownloadPdf" data-requires-page
              class="px-3 py-1 text-sm border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" title="Unduh sebagai PDF">
              Download PDF
            </button> -->
          </div>
        </div>

        <div id="dwtcontrolContainer" class="w-full h-[520px] border rounded bg-white overflow-hidden">
          <!-- WebTWAIN container -->
        </div>
      </div>
    </div>

    <div class="flex justify-end">
      <button id="btnGoReview"
        class="px-5 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed"
        disabled>
        Lanjut ke Review (Step 2)
      </button>
    </div>
  </div>

  <!-- Progress Modal -->
  <div id="progressModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
      <h3 class="font-semibold mb-3">Memproses Dokumen...</h3>
      <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%" id="progressBar"></div>
      </div>
      <p id="progressText" class="text-sm text-gray-600">Memulai...</p>
      <p class="text-xs text-gray-500 mt-1">Mohon tunggu...</p>
    </div>
  </div>

  <!-- WEBTWAIN -->
  <script src="https://unpkg.com/dwt/dist/dynamsoft.webtwain.min.js"></script>
  <script>
    // ========== KONFIGURASI ==========
    const SCAN_CONFIG = {
      DPI_PRESETS: {
        'draft': { dpi: 150, quality: 70, name: 'Draft (150 DPI)', estimatedKB: 200 },
        'normal': { dpi: 300, quality: 85, name: 'Normal (300 DPI)', estimatedKB: 500 },
        'high': { dpi: 600, quality: 90, name: 'High Quality (600 DPI)', estimatedKB: 2000 },
        'custom': { dpi: 300, quality: 85, name: 'Custom', estimatedKB: 500 }
      },
      MAX_FILE_SIZE: 50 * 1024 * 1024, // 50MB
      COMPRESSION_THRESHOLD: 5 * 1024 * 1024, // 5MB
      WARNING_THRESHOLD: 10 * 1024 * 1024 // 10MB
    };

    // ========== WEBTWAIN CONFIGURATION ==========
    Dynamsoft.DWT.ProductKey = "t01938AUAABIPFNIR6bNfLqnn3kMLYRRzeUIvxmZGjjQ16xA7fV9v743vRFeNbs2j4RE+5ZwisKodz8M/QZbI8wABlOq7EFXjyskLnDLfKTTfiQVOvuUkcmlqc7ZV2hc3cABeCZDjORwAaiCfpQHeel+9MhQAjwAZADI6A0bA6S188HUdvJiMPqb/JfTMyQucMt9ZF8gcJxY4+ZbTF4ixpDZ/2y0XCOqXUwA8AuQUwO8j6wqEWoBHgHSANrDKkXwBZcYrnA==;t01908AUAAKmXXgjmfCgNUpvM5h/NhQT4ACotaPdgy3Lsk96PUy1UF1obVTkBVV7LlRYmFDrlUSxWYyQEO71o2ef4HmPRvZu4cvICp8x3Cs13YoGTbzmJXOrafNoqzYsTOACvBMhxHQ4ANZDX0gBvvY9eGQqAR4AMABmtASPgdBf9z2dqA1J//edAo5MXOGW+sw7IHCcWOPmW0wfEWFKb3+2WA4L65hQAjwA5BfB7yLqAUAvwCJAO0AZWOZIvQ/4rhw==";
    Dynamsoft.DWT.ResourcesPath = "https://unpkg.com/dwt/dist";
    Dynamsoft.DWT.ServiceInstallerLocation = "https://demo.dynamsoft.com/DWT/Resources/dist/";
    Dynamsoft.DWT.UseLocalService = true;
    Dynamsoft.DWT.IfAlwaysFocusOnPopupWindow = true;
    Dynamsoft.DWT.AutoLoad = false;

    Dynamsoft.DWT.Containers = [{
      ContainerId: "dwtcontrolContainer",
      Width: "100%",
      Height: "520px"
    }];

    // ========== GLOBAL VARIABLES ==========
    let DWTObject = null;
    let deviceNames = [];

    // ========== UTILITY FUNCTIONS ==========
    const setStatus = (text, type = 'info') => {
      const statusText = document.getElementById('statusText');
      const statusDot = document.getElementById('statusDot');
      
      if (statusText) statusText.textContent = 'Status: ' + text;
      
      if (statusDot) {
        const colors = {
          info: 'bg-blue-500',
          success: 'bg-green-500',
          warning: 'bg-yellow-500',
          error: 'bg-red-500'
        };
        statusDot.className = `inline-block w-2.5 h-2.5 rounded-full ${colors[type] || colors.info}`;
      }
    };

    const showProgress = (show = true, text = 'Memproses...', progress = 0) => {
      const modal = document.getElementById('progressModal');
      const progressText = document.getElementById('progressText');
      const progressBar = document.getElementById('progressBar');
      
      if (show) {
        modal.classList.remove('hidden');
        if (progressText) progressText.textContent = text;
        if (progressBar) progressBar.style.width = progress + '%';
      } else {
        modal.classList.add('hidden');
      }
    };

    const updateQualityInfo = () => {
      const quality = document.getElementById('scanQuality').value;
      let estimatedKB;
      
      if (quality === 'custom') {
        const dpi = parseInt(document.getElementById('customDpi').value) || 300;
        estimatedKB = Math.round((dpi / 300) * 500);
      } else {
        estimatedKB = SCAN_CONFIG.DPI_PRESETS[quality].estimatedKB;
      }
      
      const info = estimatedKB > 1024 ? 
        `~${(estimatedKB/1024).toFixed(1)}MB per halaman` : 
        `~${estimatedKB}KB per halaman`;
        
      document.getElementById('qualityInfo').textContent = `Estimasi ukuran per halaman: ${info}`;
    };

    const updateButtonStates = () => {
      const hasPages = DWTObject && DWTObject.HowManyImagesInBuffer > 0;
      const buttons = document.querySelectorAll('[data-requires-page]');
      
      buttons.forEach(btn => {
        btn.disabled = !hasPages;
      });
      
      const btnGoReview = document.getElementById('btnGoReview');
      if (btnGoReview) {
        btnGoReview.disabled = !hasPages;
        if (hasPages) {
          const totalPages = DWTObject.HowManyImagesInBuffer;
          const quality = document.getElementById('scanQuality').value;
          const estimatedSize = totalPages * getEstimatedPageSize(quality);
          const sizeMB = (estimatedSize / 1024 / 1024).toFixed(1);
          
          btnGoReview.innerHTML = `Lanjut ke Review<br><small class="text-xs opacity-75">${totalPages} hal, ~${sizeMB}MB</small>`;
          
          // Show warning for large files
          if (estimatedSize > SCAN_CONFIG.WARNING_THRESHOLD) {
            document.getElementById('sizeWarning').classList.remove('hidden');
          }
        } else {
          btnGoReview.textContent = 'Lanjut ke Review (Step 2)';
        }
      }
    };

    const refreshPageInfo = () => {
      if (!DWTObject) {
        document.getElementById('pageInfo').textContent = '—';
        return;
      }
      
      const total = DWTObject.HowManyImagesInBuffer || 0;
      const idx = (DWTObject.CurrentImageIndexInBuffer ?? -1);
      document.getElementById('pageInfo').textContent = total > 0 ? `Halaman ${idx + 1} / ${total}` : '—';
      
      updateButtonStates();
    };

    const getEstimatedPageSize = (quality) => {
      if (quality === 'custom') {
        const dpi = parseInt(document.getElementById('customDpi').value) || 300;
        return Math.round((dpi / 300) * 500 * 1024);
      }
      return SCAN_CONFIG.DPI_PRESETS[quality].estimatedKB * 1024;
    };

    const applyQualitySettings = async () => {
      const qualityMode = document.getElementById('scanQuality').value;
      let settings;
      
      if (qualityMode === 'custom') {
        settings = {
          dpi: parseInt(document.getElementById('customDpi').value) || 300,
          quality: parseInt(document.getElementById('customQuality').value) || 85
        };
      } else {
        settings = SCAN_CONFIG.DPI_PRESETS[qualityMode];
      }
      
      try {
        if (DWTObject.Resolution !== undefined) {
          DWTObject.Resolution = settings.dpi;
        }
        if (DWTObject.JPEGQuality !== undefined) {
          DWTObject.JPEGQuality = settings.quality;
        }
        
        console.log(`Applied scan settings: ${settings.dpi} DPI, ${settings.quality}% quality`);
      } catch (e) {
        console.warn('Could not apply all scan settings:', e);
      }
    };

    const b64ToBlob = (base64, mime) => {
      const byteChars = atob(base64);
      const byteArr = new Uint8Array(byteChars.length);
      for (let i = 0; i < byteChars.length; i++) {
        byteArr[i] = byteChars.charCodeAt(i);
      }
      return new Blob([byteArr], { type: mime });
    };

    // ========== EVENT HANDLERS ==========
    
    // Error handler
    Dynamsoft.DWT.RegisterEvent("OnWebTwainError", function (err) {
      const msg = (err && (err.message || err)) + "";
      console.warn("[DWT] Error:", msg);
      if (/not installed|fail to connect|service/i.test(msg)) {
        setStatus("Tidak dapat terhubung ke layanan scanner", 'error');
      }
    });

    // Ready handler
    Dynamsoft.DWT.RegisterEvent("OnWebTwainReady", async function () {
      DWTObject = Dynamsoft.DWT.GetWebTwain("dwtcontrolContainer");
      window.DWTObject = DWTObject;
      
      if (!DWTObject) {
        setStatus("Gagal inisialisasi", 'error');
        alert("WebTWAIN belum siap");
        return;
      }

      try {
        deviceNames = await DWTObject.GetSourceNamesAsync();
        console.log("Available scanners:", deviceNames);
      } catch (e) {
        console.warn("GetSourceNamesAsync error:", e);
        deviceNames = [];
      }

      setStatus("Siap untuk scan", 'success');
      refreshPageInfo();
    });

    // ========== BUTTON EVENTS ==========

    // Quality selector
    document.getElementById('scanQuality').addEventListener('change', function() {
      const isCustom = this.value === 'custom';
      document.getElementById('customSettings').classList.toggle('hidden', !isCustom);
      updateQualityInfo();
    });

    document.getElementById('customDpi').addEventListener('input', updateQualityInfo);
    document.getElementById('customQuality').addEventListener('input', updateQualityInfo);

    // Scan button
    document.getElementById("btnScan").addEventListener("click", async (e) => {
      e.preventDefault();
      
      if (!DWTObject) {
        setStatus("Menghubungkan layanan scanner…", 'warning');
        if (Dynamsoft?.DWT?.ConnectToTheService) {
          Dynamsoft.DWT.ConnectToTheService();
        } else {
          window.open(Dynamsoft.DWT.ServiceInstallerLocation, "_blank");
        }
        return;
      }

      try {
        await applyQualitySettings();
        
        setStatus("Memulai scan...", 'info');
        await DWTObject.SelectSourceAsync();
        await DWTObject.AcquireImageAsync({ 
          IfCloseSourceAfterAcquire: true,
          IfShowUI: true
        });
        
        setStatus("Scan berhasil", 'success');
        setTimeout(refreshPageInfo, 500);
        
      } catch (exp) {
        console.error('Scan error:', exp);
        setStatus("Scan gagal", 'error');
        alert(exp.message || exp);
      }
    });

    // Navigation buttons
    document.getElementById("btnPrev").addEventListener("click", () => {
      if (DWTObject && DWTObject.CurrentImageIndexInBuffer > 0) {
        DWTObject.CurrentImageIndexInBuffer = DWTObject.CurrentImageIndexInBuffer - 1;
      }
    });

    document.getElementById("btnNextPage").addEventListener("click", () => {
      if (DWTObject) {
        const current = DWTObject.CurrentImageIndexInBuffer;
        const total = DWTObject.HowManyImagesInBuffer;
        if (current < total - 1) {
          DWTObject.CurrentImageIndexInBuffer = current + 1;
        }
      }
    });

    // Rotation buttons
    document.getElementById("rotateLeft").addEventListener("click", () => {
      if (DWTObject) {
        const i = DWTObject.CurrentImageIndexInBuffer;
        if (i >= 0) DWTObject.RotateLeft(i);
      }
    });

    document.getElementById("rotateRight").addEventListener("click", () => {
      if (DWTObject) {
        const i = DWTObject.CurrentImageIndexInBuffer;
        if (i >= 0) DWTObject.RotateRight(i);
      }
    });

    // Delete and clear buttons
    document.getElementById("btnDeletePage").addEventListener("click", function () {
      if (DWTObject) {
        const i = DWTObject.CurrentImageIndexInBuffer;
        if (i >= 0) {
          DWTObject.RemoveImage(i);
          setTimeout(refreshPageInfo, 200);
        }
      }
    });

    document.getElementById("btnClear").addEventListener("click", function () {
      if (DWTObject && confirm('Hapus semua halaman?')) {
        DWTObject.RemoveAllImages();
        setTimeout(refreshPageInfo, 200);
      }
    });

    // Load file button
    document.getElementById("btnLoadFile").addEventListener("click", () => {
      if (!DWTObject) return;
      DWTObject.LoadImageEx("", 5,
        () => {
          setStatus("File berhasil dimuat", 'success');
          setTimeout(refreshPageInfo, 500);
        },
        (code, msg) => {
          setStatus("Gagal muat file", 'error');
          alert("Gagal muat file: " + msg);
        }
      );
    });

    // Download PDF button
    // document.getElementById("btnDownloadPdf").addEventListener("click", () => {
    //   if (!DWTObject || DWTObject.HowManyImagesInBuffer === 0) {
    //     alert("Belum ada halaman.");
    //     return;
    //   }

    //   const totalPages = DWTObject.HowManyImagesInBuffer;
    //   const estimatedSize = totalPages * getEstimatedPageSize(document.getElementById('scanQuality').value);
      
    //   if (estimatedSize > 10 * 1024 * 1024) {
    //     if (!confirm(`File berukuran besar (~${(estimatedSize/1024/1024).toFixed(1)}MB). Lanjutkan download?`)) {
    //       return;
    //     }
    //   }

    //   setStatus("Membuat PDF...", 'info');
    //   showProgress(true, 'Generating PDF...', 0);

    //   const indices = [];
    //   for (let i = 0; i < totalPages; i++) {
    //     indices.push(i);
    //   }

    //   DWTObject.ConvertToBase64(
    //     indices,
    //     Dynamsoft.DWT.EnumDWT_ImageType.IT_PDF,
    //     (base64) => {
    //       showProgress(false);
    //       const blob = b64ToBlob(base64, "application/pdf");
    //       const sizeMB = (blob.size / 1024 / 1024).toFixed(1);
          
    //       const url = URL.createObjectURL(blob);
    //       const a = document.createElement('a');
    //       a.href = url;
    //       a.download = `scan_${new Date().toISOString().slice(0,10)}_${sizeMB}MB.pdf`;
    //       a.click();
    //       URL.revokeObjectURL(url);
          
    //       setStatus(`PDF berhasil diunduh (${sizeMB}MB)`, 'success');
    //     },
    //     (code, msg) => {
    //       showProgress(false);
    //       setStatus("Export gagal", 'error');
    //       alert("Export gagal: " + msg);
    //     }
    //   );
    // });

    // Go to Review button
    document.getElementById("btnGoReview").addEventListener("click", async () => {
      if (!DWTObject || DWTObject.HowManyImagesInBuffer === 0) {
        alert("Belum ada halaman yang di-scan.");
        return;
      }
      
      const totalImages = DWTObject.HowManyImagesInBuffer;
      const estimatedSize = totalImages * getEstimatedPageSize(document.getElementById('scanQuality').value);
      
      // Check size limit
      if (estimatedSize > SCAN_CONFIG.MAX_FILE_SIZE) {
        alert(`File terlalu besar (~${(estimatedSize/1024/1024).toFixed(1)}MB). Maksimal 50MB. Silakan kurangi kualitas atau jumlah halaman.`);
        return;
      }

      // Show progress for large files
      if (estimatedSize > SCAN_CONFIG.COMPRESSION_THRESHOLD) {
        showProgress(true, 'Mengkonversi PDF...', 0);
        
        // Simulate progress
        let progress = 0;
        const progressInterval = setInterval(() => {
          progress += Math.random() * 15;
          if (progress > 90) progress = 90;
          showProgress(true, `Memproses ${totalImages} halaman...`, progress);
        }, 500);
        
        setTimeout(() => clearInterval(progressInterval), 10000);
      }

      try {
        const indices = [];
        for (let i = 0; i < totalImages; i++) {
          indices.push(i);
        }
        
        console.log("Converting pages:", indices);
        setStatus("Mengkonversi ke PDF...", 'info');
        
        DWTObject.ConvertToBase64(
          indices,
          Dynamsoft.DWT.EnumDWT_ImageType.IT_PDF,
          function (result) {
            showProgress(false);
            const b64 = result.getData(0, result.getLength());
            const actualSize = Math.round((b64.length * 3/4));
            const sizeMB = (actualSize / 1024 / 1024).toFixed(1);
            
            console.log(`Actual PDF size: ${sizeMB}MB, base64 length: ${b64.length}`);
            
            // Final size check
            if (actualSize > SCAN_CONFIG.MAX_FILE_SIZE) {
              alert(`File terlalu besar (${sizeMB}MB). Maksimal 50MB.`);
              return;
            }
            
            sessionStorage.setItem("scanPDF", b64);
            setStatus("Berhasil! Mengarahkan ke review...", 'success');
            
            setTimeout(() => {
              window.location.href = "/scan/step2";
            }, 1000);
          },
          function (code, msg) {
            showProgress(false);
            console.error("Export error:", code, msg);
            setStatus("Export gagal", 'error');
            alert("Export gagal: " + msg); 
          }
        );
        
      } catch (error) {
        showProgress(false);
        console.error('Go to review error:', error);
        setStatus("Error", 'error');
        alert('Error: ' + error.message);
      }
    });

    // ========== INITIALIZATION ==========
    
    // Initialize quality info
    updateQualityInfo();
    
    // Start page info refresh interval
    setInterval(refreshPageInfo, 1000);
    
    // Load WebTWAIN
    Dynamsoft.DWT.Load().catch(err => {
      console.error("[DWT] Load error:", err);
      setStatus("Gagal load WebTWAIN", 'error');
    });
  </script>
</x-app-layout>