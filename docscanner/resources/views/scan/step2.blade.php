<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Step 2: Review Hasil Scan</h2></x-slot>

  <div class="max-w-3xl mx-auto p-6 bg-white rounded border">
    <p class="mb-4">Preview PDF hasil scan:</p>
    <iframe id="preview" class="w-full h-[600px] border"></iframe>

    <div class="flex justify-between mt-4">
      <a href="{{ route('scan.step1') }}" class="px-4 py-2 border rounded">← Kembali</a>
      <a href="{{ route('scan.step3') }}" class="px-4 py-2 bg-green-600 text-white rounded">Lanjut ke Metadata →</a>
    </div>
  </div>

  <script>
    // ambil base64 dari sessionStorage dan tampilkan
    const base64=sessionStorage.getItem("scanPDF");
    if(base64){
      const pdfUrl="data:application/pdf;base64,"+base64;
      document.getElementById('preview').src=pdfUrl;
    } else {
      alert("Tidak ada hasil scan."); window.location.href="{{ route('scan.step1') }}";
    }
  </script>
</x-app-layout>
