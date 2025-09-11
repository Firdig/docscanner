<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800">Step 2: Metadata & Simpan</h2>
    <p class="text-sm text-gray-500">Isi detail dokumen, lalu simpan ke storage.</p>
  </x-slot>

  <div class="max-w-6xl mx-auto p-6 space-y-6">
    <div class="grid lg:grid-cols-2 gap-6">
      <!-- Kiri: Preview yang dioptimasi untuk file besar -->
      <div class="p-4 bg-white border rounded-2xl">
        <div class="flex items-center justify-between mb-2">
          <h3 class="font-semibold">Preview Dokumen</h3>
          <div class="flex items-center gap-2">
            <button id="btnKembaliScan" class="px-3 py-1 border rounded text-sm">← Kembali ke Scan</button>
            <button id="btnTogglePreview" class="px-3 py-1 border rounded text-sm">🔄 Toggle Mode</button>
          </div>
        </div>

        <!-- Preview Container -->
        <div id="previewContainer" class="w-full h-[520px] border rounded bg-gray-50 relative overflow-hidden">
          <!-- Mode 1: Thumbnail Grid untuk file besar -->
          <div id="thumbnailMode" class="w-full h-full p-4 overflow-y-auto" style="display: none;">
            <div class="text-center mb-4">
              <h4 class="font-semibold text-lg mb-2">📄 Mode Thumbnail</h4>
              <p class="text-sm text-gray-600">Klik thumbnail untuk preview halaman</p>
            </div>
            <div id="thumbnailGrid" class="grid grid-cols-2 gap-3">
              <!-- Thumbnails akan di-generate di sini -->
            </div>
          </div>

          <!-- Mode 2: Single Page Viewer -->
          <div id="singlePageMode" class="w-full h-full flex flex-col" style="display: none;">
            <div class="flex items-center justify-between p-2 bg-gray-100 border-b">
              <span id="pageCounter" class="text-sm font-medium">Halaman 1 dari 1</span>
              <div class="flex items-center gap-1">
                <button id="prevPage" class="px-2 py-1 border rounded text-xs">‹ Prev</button>
                <button id="nextPage" class="px-2 py-1 border rounded text-xs">Next ›</button>
                <select id="pageSelector" class="ml-2 px-2 py-1 border rounded text-xs"></select>
              </div>
            </div>
            <div id="pageViewer" class="flex-1 overflow-auto bg-white">
              <canvas id="pageCanvas" class="w-full h-auto"></canvas>
            </div>
          </div>

          <!-- Mode 3: Fallback Info -->
          <div id="fallbackMode" class="w-full h-full flex flex-col items-center justify-center p-6 text-center">
            <div class="text-6xl mb-4">📄</div>
            <h4 class="text-lg font-semibold mb-2">Dokumen Siap untuk Disimpan</h4>
            <p class="text-sm text-gray-600 mb-2">Ukuran: <span id="fileSize"></span></p>
            <p class="text-sm text-gray-600 mb-4">Estimasi halaman: <span id="pageCount"></span></p>
            
            <div class="space-y-2 w-full max-w-xs">
              <button id="btnDownloadPreview" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                📥 Download untuk Preview
              </button>
              <button id="btnOpenNewTab" class="w-full px-4 py-2 border rounded hover:bg-gray-50">
                🔗 Buka di Tab Baru
              </button>
              <button id="btnGenerateThumbnails" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                🖼️ Generate Thumbnails
              </button>
            </div>
          </div>

          <!-- Loading state -->
          <div id="previewLoading" class="w-full h-full flex items-center justify-center">
            <div class="text-center">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
              <p class="text-sm text-gray-600">Memproses dokumen...</p>
              <p id="loadingProgress" class="text-xs text-gray-500 mt-1"></p>
            </div>
          </div>
        </div>

        <!-- File Info -->
        <div id="fileInfo" class="mt-3 p-3 bg-gray-50 rounded text-sm">
          <div class="grid grid-cols-2 gap-2 text-xs">
            <div>Ukuran: <span id="displaySize" class="font-mono">-</span></div>
            <div>Halaman: <span id="displayPages" class="font-mono">-</span></div>
            <div>Mode: <span id="displayMode" class="font-mono">Auto</span></div>
            <div>Status: <span id="displayStatus" class="font-mono">Loading...</span></div>
          </div>
        </div>
      </div>

      <!-- Kanan: Form metadata yang sama -->
      <div class="p-4 bg-white border rounded-2xl">
        <h3 class="font-semibold mb-3">Metadata Dokumen</h3>

        <form id="metaForm" class="grid gap-3">
          <input name="title" placeholder="Judul/Perihal *" class="border rounded p-2" required>
          <input name="letter_number" placeholder="Nomor Surat" class="border rounded p-2">
          <div class="grid md:grid-cols-2 gap-3">
            <input type="date" name="document_date" class="border rounded p-2" placeholder="Tanggal Dokumen">
            <input name="category" placeholder="Kategori" class="border rounded p-2">
          </div>
          <div class="grid md:grid-cols-2 gap-3">
            <input name="year" placeholder="Tahun" class="border rounded p-2">
            <input name="description" placeholder="Deskripsi (opsional)" class="border rounded p-2">
          </div>

          <!-- Progress bar untuk upload -->
          <div id="uploadProgress" class="hidden">
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div id="uploadBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p id="uploadText" class="text-sm text-gray-600 mt-1">Mengunggah...</p>
          </div>

          <div class="flex items-center gap-3 pt-2">
            <button id="btnSave" type="button" class="flex-1 px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">
              💾 Simpan ke Storage
            </button>
            <button id="btnDownloadLocal" type="button" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
              📥 Download
            </button>
          </div>
          
          <div id="status" class="text-sm text-center p-2 rounded"></div>
        </form>

        <hr class="my-4">

        <div class="flex items-center gap-2">
          <a href="/documents" class="px-3 py-2 rounded border text-sm">📁 Buka Storage</a>
          <button id="btnResetFlow" class="px-3 py-2 rounded border text-sm">🔄 Mulai Ulang</button>
          <button id="btnCompressFile" class="px-3 py-2 rounded border text-sm">🗜️ Kompres</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Include PDF.js untuk thumbnail generation -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
  
  <script>
    // ========== KONFIGURASI ==========
    const CONFIG = {
      SMALL_FILE_LIMIT: 2 * 1024 * 1024,    // 2MB
      MEDIUM_FILE_LIMIT: 10 * 1024 * 1024,  // 10MB
      THUMBNAIL_SIZE: 200,
      MAX_UPLOAD_SIZE: 50 * 1024 * 1024,    // 50MB
      CHUNK_SIZE: 1024 * 1024               // 1MB chunks
    };

    // ========== GLOBAL VARIABLES ==========
    let base64Data = null;
    let pdfDoc = null;
    let currentPage = 1;
    let totalPages = 0;
    let previewMode = 'auto';
    let fileSize = 0;

    // ========== INITIALIZATION ==========
    document.addEventListener('DOMContentLoaded', function() {
      initializeApp();
    });

    async function initializeApp() {
      try {
        base64Data = sessionStorage.getItem("scanPDF");
        
        if (!base64Data || base64Data.includes("[object Object]") || base64Data.length < 100) {
          throw new Error("Data scan tidak valid");
        }

        fileSize = Math.round((base64Data.length * 3/4));
        updateFileInfo();
        
        // Auto-determine preview mode
        await determinePreviewMode();
        
      } catch (error) {
        console.error('Initialization error:', error);
        alert("Data scan tidak valid. Silakan ulangi dari Step 1.");
        window.location.href = "/scan/step1";
      }
    }

    function updateFileInfo() {
      const sizeKB = Math.round(fileSize / 1024);
      const sizeMB = (fileSize / (1024 * 1024)).toFixed(2);
      const displaySize = sizeKB > 1024 ? `${sizeMB} MB` : `${sizeKB} KB`;
      
      document.getElementById('displaySize').textContent = displaySize;
      document.getElementById('fileSize').textContent = displaySize;
    }

    async function determinePreviewMode() {
      setStatus('Menganalisis dokumen...', 'info');
      
      try {
        if (fileSize <= CONFIG.SMALL_FILE_LIMIT) {
          // File kecil: gunakan iframe
          await showIframePreview();
          previewMode = 'iframe';
        } else if (fileSize <= CONFIG.MEDIUM_FILE_LIMIT) {
          // File medium: gunakan thumbnail mode
          await initializeThumbnailMode();
          previewMode = 'thumbnail';
        } else {
          // File besar: gunakan fallback
          showFallbackMode();
          previewMode = 'fallback';
        }
        
        document.getElementById('displayMode').textContent = previewMode;
        setStatus('Siap untuk disimpan', 'success');
        
      } catch (error) {
        console.error('Preview mode error:', error);
        showFallbackMode();
        previewMode = 'fallback';
      }
    }

    async function showIframePreview() {
      const container = document.getElementById('previewContainer');
      container.innerHTML = `<iframe src="data:application/pdf;base64,${base64Data}" class="w-full h-full"></iframe>`;
    }

    async function initializeThumbnailMode() {
      setStatus('Generating thumbnails...', 'info');
      
      try {
        // Load PDF with PDF.js
        const pdfData = base64ToUint8Array(base64Data);
        pdfDoc = await pdfjsLib.getDocument({data: pdfData}).promise;
        totalPages = pdfDoc.numPages;
        
        document.getElementById('displayPages').textContent = totalPages;
        
        // Generate thumbnails
        await generateThumbnails();
        
        // Show thumbnail mode
        document.getElementById('thumbnailMode').style.display = 'block';
        document.getElementById('previewLoading').style.display = 'none';
        
      } catch (error) {
        console.error('Thumbnail generation error:', error);
        showFallbackMode();
      }
    }

    async function generateThumbnails() {
      const grid = document.getElementById('thumbnailGrid');
      grid.innerHTML = '';
      
      for (let pageNum = 1; pageNum <= totalPages; pageNum++) {
        try {
          const page = await pdfDoc.getPage(pageNum);
          const scale = CONFIG.THUMBNAIL_SIZE / Math.max(page.getViewport({scale: 1}).width, page.getViewport({scale: 1}).height);
          const viewport = page.getViewport({scale});
          
          const canvas = document.createElement('canvas');
          const context = canvas.getContext('2d');
          canvas.width = viewport.width;
          canvas.height = viewport.height;
          
          await page.render({canvasContext: context, viewport}).promise;
          
          // Create thumbnail container
          const thumbContainer = document.createElement('div');
          thumbContainer.className = 'border rounded p-2 hover:bg-gray-50 cursor-pointer text-center';
          thumbContainer.innerHTML = `
            <div class="mb-1">${canvas.outerHTML}</div>
            <p class="text-xs text-gray-600">Hal. ${pageNum}</p>
          `;
          
          thumbContainer.onclick = () => showSinglePage(pageNum);
          grid.appendChild(thumbContainer);
          
          // Update progress
          document.getElementById('loadingProgress').textContent = `Generating thumbnail ${pageNum}/${totalPages}`;
          
        } catch (error) {
          console.error(`Error generating thumbnail for page ${pageNum}:`, error);
        }
      }
    }

    async function showSinglePage(pageNum) {
      currentPage = pageNum;
      
      // Switch to single page mode
      document.getElementById('thumbnailMode').style.display = 'none';
      document.getElementById('singlePageMode').style.display = 'flex';
      
      // Setup page selector
      const selector = document.getElementById('pageSelector');
      selector.innerHTML = '';
      for (let i = 1; i <= totalPages; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = `Halaman ${i}`;
        if (i === pageNum) option.selected = true;
        selector.appendChild(option);
      }
      
      await renderSinglePage(pageNum);
      updatePageControls();
    }

    async function renderSinglePage(pageNum) {
      try {
        const page = await pdfDoc.getPage(pageNum);
        const canvas = document.getElementById('pageCanvas');
        const context = canvas.getContext('2d');
        
        // Calculate scale to fit container
        const container = document.getElementById('pageViewer');
        const containerWidth = container.clientWidth - 20; // padding
        const scale = containerWidth / page.getViewport({scale: 1}).width;
        const viewport = page.getViewport({scale});
        
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        
        await page.render({canvasContext: context, viewport}).promise;
        
      } catch (error) {
        console.error('Single page render error:', error);
      }
    }

    function updatePageControls() {
      document.getElementById('pageCounter').textContent = `Halaman ${currentPage} dari ${totalPages}`;
      document.getElementById('prevPage').disabled = currentPage <= 1;
      document.getElementById('nextPage').disabled = currentPage >= totalPages;
    }

    function showFallbackMode() {
      document.getElementById('fallbackMode').style.display = 'flex';
      document.getElementById('previewLoading').style.display = 'none';
      
      // Estimate page count based on file size
      const estimatedPages = Math.ceil(fileSize / (200 * 1024)); // Assume ~200KB per page
      document.getElementById('pageCount').textContent = `~${estimatedPages}`;
      document.getElementById('displayPages').textContent = `~${estimatedPages}`;
    }

    // ========== EVENT LISTENERS ==========
    
    // Toggle preview mode
    document.getElementById('btnTogglePreview').onclick = async () => {
      if (previewMode === 'thumbnail') {
        document.getElementById('thumbnailMode').style.display = 'none';
        document.getElementById('singlePageMode').style.display = 'none';
        showFallbackMode();
        previewMode = 'fallback';
      } else if (previewMode === 'fallback') {
        await initializeThumbnailMode();
        previewMode = 'thumbnail';
      }
    };

    // Page navigation
    document.getElementById('prevPage').onclick = () => {
      if (currentPage > 1) {
        showSinglePage(currentPage - 1);
      }
    };

    document.getElementById('nextPage').onclick = () => {
      if (currentPage < totalPages) {
        showSinglePage(currentPage + 1);
      }
    };

    document.getElementById('pageSelector').onchange = (e) => {
      showSinglePage(parseInt(e.target.value));
    };

    // Back to thumbnail mode
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && document.getElementById('singlePageMode').style.display === 'flex') {
        document.getElementById('singlePageMode').style.display = 'none';
        document.getElementById('thumbnailMode').style.display = 'block';
      }
    });

    // Generate thumbnails button
    document.getElementById('btnGenerateThumbnails').onclick = async () => {
      document.getElementById('fallbackMode').style.display = 'none';
      document.getElementById('previewLoading').style.display = 'flex';
      await initializeThumbnailMode();
    };

    // Download buttons
    document.getElementById('btnDownloadPreview').onclick = downloadFile;
    document.getElementById('btnDownloadLocal').onclick = downloadFile;

    document.getElementById('btnOpenNewTab').onclick = () => {
      try {
        const blob = b64ToBlob(base64Data, "application/pdf");
        const url = URL.createObjectURL(blob);
        const newWindow = window.open(url, '_blank');
        if (!newWindow) {
          setStatus('Popup diblokir. Mengunduh file...', 'warning');
          downloadFile();
        } else {
          setTimeout(() => URL.revokeObjectURL(url), 10000);
        }
      } catch (error) {
        setStatus('Error: ' + error.message, 'error');
      }
    };

    // File compression
    document.getElementById('btnCompressFile').onclick = async () => {
      setStatus('Fitur kompresi akan segera tersedia...', 'info');
      // TODO: Implement PDF compression
    };

    // Navigation
    document.getElementById('btnKembaliScan').onclick = () => {
      window.location.href = "/scan/step1";
    };

    document.getElementById('btnResetFlow').onclick = () => {
      if (confirm('Yakin ingin mengulang? Data scan akan hilang.')) {
        sessionStorage.removeItem('scanPDF');
        window.location.href = "/scan/step1";
      }
    };

    // ========== SAVE FUNCTIONALITY ==========
    document.getElementById('btnSave').addEventListener('click', async () => {
      const form = document.getElementById('metaForm');
      if (!form.reportValidity()) {
        setStatus('Mohon lengkapi form yang wajib diisi', 'warning');
        return;
      }

      if (fileSize > CONFIG.MAX_UPLOAD_SIZE) {
        setStatus(`File terlalu besar (${(fileSize/1024/1024).toFixed(1)}MB). Maksimal 50MB.`, 'error');
        return;
      }

      await uploadWithProgress();
    });

    async function uploadWithProgress() {
      const form = document.getElementById('metaForm');
      const progressContainer = document.getElementById('uploadProgress');
      const progressBar = document.getElementById('uploadBar');
      const progressText = document.getElementById('uploadText');
      const saveBtn = document.getElementById('btnSave');
      
try {
  saveBtn.disabled = true;
  progressContainer.classList.remove('hidden');
  
  const fd = new FormData(form);
  const blob = b64ToBlob(base64Data, "application/pdf");
  fd.append('file', blob, `scan_${Date.now()}.pdf`);
  
  // Get CSRF token from meta tag
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  
  // Simulate progress for large files
  let progress = 0;
  const progressInterval = setInterval(() => {
    progress += Math.random() * 10;
    if (progress > 90) progress = 90;
    progressBar.style.width = progress + '%';
    progressText.textContent = `Mengunggah... ${Math.round(progress)}%`;
  }, 200);
  const UPLOAD_URL = "{{ route('scan.upload') }}";

  const resp = await fetch(UPLOAD_URL, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken || '',
      'Accept': 'application/json'
    },
    body: fd
  });
  
  clearInterval(progressInterval);
  progressBar.style.width = '100%';
  progressText.textContent = 'Upload selesai!';
  window.location.href = "/documents";
  if (!resp.ok) {
    let errorText;
    try {
      const err = await resp.json();
      errorText = err.message || JSON.stringify(err);
      // tambahan: tampilkan pesan field "file" kalau ada
      if (err.errors && err.errors.file && err.errors.file.length) {
        errorText += ` — ${err.errors.file.join(', ')}`;
      }
    } catch {
      errorText = await resp.text();
    }
    throw new Error(`Server error (${resp.status}): ${errorText}`);
  }
} catch (error) {
  setStatus('Error upload: ' + error.message, 'error');
} finally {
  saveBtn.disabled = false;
}
    }

    // ========== UTILITY FUNCTIONS ==========
    
    function downloadFile() {
      try {
        const blob = b64ToBlob(base64Data, "application/pdf");
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `scan_${new Date().toISOString().slice(0,10)}.pdf`;
        a.click();
        URL.revokeObjectURL(url);
        setStatus('File berhasil diunduh', 'success');
      } catch (error) {
        setStatus('Error download: ' + error.message, 'error');
      }
    }

    function b64ToBlob(base64, mime) {
      const byteChars = atob(base64);
      const byteArr = new Uint8Array(byteChars.length);
      for (let i = 0; i < byteChars.length; i++) {
        byteArr[i] = byteChars.charCodeAt(i);
      }
      return new Blob([byteArr], { type: mime });
    }

    function base64ToUint8Array(base64) {
      const binary = atob(base64);
      const bytes = new Uint8Array(binary.length);
      for (let i = 0; i < binary.length; i++) {
        bytes[i] = binary.charCodeAt(i);
      }
      return bytes;
    }

    function setStatus(message, type = 'info') {
      const statusEl = document.getElementById('status');
      const colors = {
        success: 'bg-green-100 text-green-800',
        error: 'bg-red-100 text-red-800',
        warning: 'bg-yellow-100 text-yellow-800',
        info: 'bg-blue-100 text-blue-800'
      };
      
      statusEl.className = `text-sm text-center p-2 rounded ${colors[type]}`;
      statusEl.textContent = message;
      
      document.getElementById('displayStatus').textContent = message;
      
      if (type === 'success' || type === 'error') {
        setTimeout(() => {
          statusEl.textContent = '';
          statusEl.className = 'text-sm text-center p-2 rounded';
        }, 5000);
      }
    }

    // Configure PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
  </script>

  <!-- Meta tag CSRF Token (pastikan ini ada di layout) -->
  @push('meta')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @endpush
</x-app-layout>