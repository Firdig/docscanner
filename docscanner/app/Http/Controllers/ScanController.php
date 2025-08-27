<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;

class ScanController extends Controller
{
    public function upload(Request $request)
    {
        $data = $request->validate([
            'file'          => ['required','file','max:102400'], // <=100MB
            'title'         => ['required','string','max:255'],
            'letter_number' => ['nullable','string','max:255'],
            'document_date' => ['nullable','date'],
            'category'      => ['nullable','string','max:255'],
            'year'          => ['nullable','digits:4'],
            'description'   => ['nullable','string'],
        ]);

        $disk = 'public'; // sesuai default di tabelmu
        $file = $data['file'];
        $ext  = $file->getClientOriginalExtension() ?: 'pdf';

        $dir  = 'documents/'.date('Y').'/'.date('m');
        $name = Str::slug($data['title']).'-'.Str::random(6).'.'.$ext;

        // simpan ke storage/app/public/...
        $path = $file->storeAs($dir, $name, $disk);

        // simpan record
        $doc = Document::create([
            'title'         => $data['title'],
            'letter_number' => $data['letter_number'] ?? null,
            'document_date' => $data['document_date'] ?? null,
            'category'      => $data['category'] ?? null,
            'year'          => $data['year'] ?? null,
            'description'   => $data['description'] ?? null,
            'disk'          => $disk,
            'path'          => $path,
            'mime'          => $file->getMimeType(),
            'size'          => $file->getSize(),
        ]);

        return response()->json([
            'message'      => 'Uploaded',
            'document_id'  => $doc->id,
            'url'          => $doc->url, // dari accessor
        ]);
    }
}
