<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    // STORAGE (INDEX) — list + search + filter
    public function index(Request $req)
    {
        $q        = $req->input('q');                    // judul/perihal/nomor
        $category = $req->input('category');
        $from     = $req->input('from');
        $to       = $req->input('to');

        $docs = Document::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('title', 'like', "%$q%")
                       ->orWhere('letter_number', 'like', "%$q%");
                });
            })
            ->when($category, fn($qr) => $qr->where('category', $category))
            ->when($from, fn($qr) => $qr->whereDate('document_date', '>=', $from))
            ->when($to,   fn($qr) => $qr->whereDate('document_date', '<=', $to))
            ->orderByDesc('document_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // daftar kategori (sementara hardcode, nanti bisa tabel sendiri)
        $categories = Document::query()
            ->select('category')->whereNotNull('category')
            ->distinct()->orderBy('category')->pluck('category');

        return view('documents.index', compact('docs','categories','q','category','from','to'));
    }

    // FORM UNGGAH SEDERHANA
    public function create()
    {
        $categories = Document::query()
            ->select('category')->whereNotNull('category')
            ->distinct()->orderBy('category')->pluck('category');

        return view('documents.create', compact('categories'));
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'title'         => ['required','string','max:255'],
            'letter_number' => ['nullable','string','max:255'],
            'document_date' => ['nullable','date'],
            'category'      => ['nullable','string','max:100'],
            'year'          => ['nullable','integer','min:1900','max:2100'],
            'description'   => ['nullable','string'],
            'file'          => ['required','file', 'mimes:pdf,jpg,jpeg,png','max:20480'], // 20MB
        ]);

        $file = $req->file('file');
        $storedPath = $file->store('documents/'.date('Y/m'), 'public');

        $doc = Document::create([
            ...$data,
            'disk' => 'public',
            'path' => $storedPath,
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'year' => $data['year'] ?? ($data['document_date'] ? date('Y', strtotime($data['document_date'])) : null),
        ]);

        return redirect()->route('documents.index')->with('ok', 'Dokumen berhasil diunggah.');
    }
    public function edit(Document $document)
{
    $categories = Document::query()
        ->select('category')->whereNotNull('category')
        ->distinct()->orderBy('category')->pluck('category');

    return view('documents.edit', ['doc' => $document, 'categories' => $categories]);
}

public function update(Request $req, Document $document)
{
    $data = $req->validate([
        'title'         => ['required','string','max:255'],
        'letter_number' => ['nullable','string','max:255'],
        'document_date' => ['nullable','date'],
        'category'      => ['nullable','string','max:100'],
        'year'          => ['nullable','integer','min:1900','max:2100'],
        'description'   => ['nullable','string'],
    ]);

    $document->update($data);

    return redirect()->route('documents.index')->with('ok','Metadata dokumen berhasil diperbarui.');
}

    public function show(Document $document)
{
    if (!Storage::disk($document->disk)->exists($document->path)) {
        return redirect()->route('documents.index')
            ->withErrors('File tidak ditemukan di storage.'); // tampilkan flash error
    }
    return view('documents.show', ['doc' => $document]);
}
public function stream(Document $document)
{
    if (!Storage::disk($document->disk)->exists($document->path)) {
        abort(404, 'File tidak ditemukan di storage.');
    }

    $mime = $document->mime ?: Storage::mimeType($document->path);
    $fullPath = Storage::disk($document->disk)->path($document->path);

    // Mengirim langsung konten file ke browser (PDF/Gambar bisa di-embed)
    return response()->file($fullPath, [
        'Content-Type' => $mime,
        'Cache-Control' => 'private, max-age=0, no-cache',
    ]);
}

    public function download(Document $document)
    {
        return Storage::disk($document->disk)->download($document->path, ($document->title ?: 'document').'.'.pathinfo($document->path, PATHINFO_EXTENSION));
    }

    public function destroy(Document $document)
    {
        Storage::disk($document->disk)->delete($document->path);
        $document->delete();
        return back()->with('ok', 'Dokumen dihapus.');
    }
}
