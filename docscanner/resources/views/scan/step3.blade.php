<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Step 3: Metadata & Simpan</h2></x-slot>

  <div class="max-w-4xl mx-auto p-6 bg-white border rounded space-y-4">
    <iframe id="preview" class="w-full h-[400px] border"></iframe>

    <form id="metaForm" class="grid gap-3">
      <input name="title" placeholder="Judul/Perihal" class="border rounded p-2" required>
      <input name="letter_number" placeholder="Nomor Surat" class="border rounded p-2">
      <input type="date" name="document_date" class="border rounded p-2">
      <input name="category" placeholder="Kategori" class="border rounded p-2">
      <input name="year" placeholder="Tahun" class="border rounded p-2">
      <textarea name="description" placeholder="Deskripsi" class="border rounded p-2"></textarea>

      <button id="btnSave" type="button" class="bg-green-600 text-white py-2 rounded">Simpan ke Storage</button>
      <p id="status" class="text-sm text-gray-500"></p>
    </form>
  </div>

  <script>
    const base64=sessionStorage.getItem("scanPDF");
    if(base64){
      document.getElementById('preview').src="data:application/pdf;base64,"+base64;
    } else {
      alert("Tidak ada hasil scan."); window.location.href="{{ route('scan.step1') }}";
    }

    document.getElementById('btnSave').onclick=async()=>{
      const fd=new FormData(document.getElementById('metaForm'));

      // konversi base64 ke Blob
      const byteChars=atob(base64);
      const byteNums=new Array(byteChars.length);
      for(let i=0;i<byteChars.length;i++) byteNums[i]=byteChars.charCodeAt(i);
      const blob=new Blob([new Uint8Array(byteNums)],{type:"application/pdf"});
      fd.append("file", blob, "scan.pdf");

      const resp=await fetch("{{ route('scan.upload') }}",{
        method:"POST",
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:fd
      });
      if(!resp.ok){ alert("Upload gagal"); return; }
      const data=await resp.json();
      document.getElementById('status').textContent="✅ Tersimpan, cek di Storage.";
      // bersihkan session
      sessionStorage.removeItem("scanPDF");
    };
  </script>
</x-app-layout>
