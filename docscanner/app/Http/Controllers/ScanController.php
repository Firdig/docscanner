<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Services\ActivityLogger;

class ScanController extends Controller
{
    protected ActivityLogger $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    public function index()
    {
        // Log activity - akses halaman scan
        $this->activityLogger->logScan('access_scan_page');
        
        return view('scan.index');
    }

    public function step1()
    {
        // Log activity - akses step 1 scan
        $this->activityLogger->logScan('access_step1');
        
        return view('scan.step1');
    }

    public function step2()
    {
        // Log activity - akses step 2 scan
        $this->activityLogger->logScan('access_step2');
        
        return view('scan.step2');
    }

    public function step3()
    {
        // Log activity - akses step 3 scan
        $this->activityLogger->logScan('access_step3');
        
        return view('scan.step3');
    }

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

        // Log activity - upload scan result
        $this->activityLogger->logScan('upload', null, [
            'document_id' => $doc->id,
            'document_title' => $doc->title,
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'original_name' => $file->getClientOriginalName(),
        ]);

        return response()->json([
            'message'      => 'Uploaded',
            'document_id'  => $doc->id,
            'url'          => $doc->file_url, // dari accessor
        ]);
    }

    // Method tambahan untuk batch scanning (jika diperlukan)
    public function startBatch(Request $request)
    {
        // Logic untuk memulai batch scan
        $this->activityLogger->logScan('start', null, [
            'device' => $request->input('device'),
            'dpi' => $request->input('dpi'),
            'color_mode' => $request->input('color_mode'),
        ]);

        return response()->json(['message' => 'Batch scan started']);
    }

    public function cancelBatch(Request $request)
    {
        // Logic untuk cancel batch scan
        $this->activityLogger->logScan('cancel', null, [
            'batch_id' => $request->input('batch_id'),
            'reason' => 'User cancelled',
        ]);

        return response()->json(['message' => 'Batch scan cancelled']);
    }
}