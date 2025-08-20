@props(['title' => 'Login • ScanDocs'])

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-900 antialiased flex flex-col min-h-screen">

  {{-- BAR BIRU ATAS --}}
  <div class="h-16 w-full bg-[#497AB8]"></div>

  {{-- PANEL LOGIN DITENGAH --}}
  <main class="flex-1 flex items-center justify-center px-4">
    <div class="w-full max-w-lg bg-[#D7E4F1] rounded-lg shadow px-8 py-10">
      {{ $slot }}
    </div>
  </main>

</body>
</html>
