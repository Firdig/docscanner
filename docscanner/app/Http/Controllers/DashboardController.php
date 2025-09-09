<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalDocs = Document::count();

        // Ambil waktu upload terakhir (pakai uploaded_at jika ada; fallback ke created_at)
        $last = Document::query()
            ->select(['id', 'judul', 'title', 'nama_berkas', 'uploaded_at', 'created_at'])
            ->orderByRaw('COALESCE(uploaded_at, created_at) DESC')
            ->first();

        $lastUploadedAt = $last ? ($last->uploaded_at ?? $last->created_at) : null;

        // 10 aktivitas terakhir
        $activities = ActivityLog::latest()->limit(10)->get();

        return view('dashboard', [
            'totalDocs'      => $totalDocs,
            'lastUploadedAt' => $lastUploadedAt,
            'activities'     => $activities,
        ]);
    }
}
