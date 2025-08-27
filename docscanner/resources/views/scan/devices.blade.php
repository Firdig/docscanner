<x-app-layout>
    <x-slot name="header">Scanner — Pilih Perangkat</x-slot>

    <div class="max-w-3xl mx-auto">
        
    </div>

    {{-- Placeholder integrasi SDK: muat daftar sumber TWAIN --}}
    <script>
    // Contoh: jika menggunakan Dynamsoft WebTWAIN
    // window.DWObject.GetSourceNames().then(names => {
    //   const sel = document.getElementById('twain-source');
    //   names.forEach(n => { const o=document.createElement('option'); o.value=o.textContent=n; sel.appendChild(o); });
    // });
    </script>
</x-app-layout>
