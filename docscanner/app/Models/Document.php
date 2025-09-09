<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\Loggable;

class Document extends Model
{
    use Loggable;

    protected $fillable = [
        'title','letter_number','document_date','category','year',
        'description','disk','path','mime','size',
    ];

    protected $casts = [
        'document_date' => 'date',
        'year' => 'integer',
    ];

    // Akses URL publik untuk preview/download
    public function getFileUrlAttribute(): string
    {
        if ($this->disk === 'public') {
            return asset('storage/' . $this->path);
        }
        return Storage::disk($this->disk)->url($this->path);
    }

    // Loggable trait overrides
    public function getActivityType(): string
    {
        return 'document';
    }

    public function getCreateDescription(): string
    {
        return "mengunggah dokumen '{$this->title}'";
    }

    public function getUpdateDescription(): string
    {
        return "memperbarui dokumen '{$this->title}'";
    }

    public function getDeleteDescription(): string
    {
        return "menghapus dokumen '{$this->title}'";
    }

    // Custom activity logging methods
    public function logViewed(): void
    {
        $this->logActivity('read', "melihat dokumen '{$this->title}'");
    }

    public function logDownloaded(): void
    {
        $this->logActivity('download', "mengunduh dokumen '{$this->title}'");
    }

    public function logStreamed(): void
    {
        $this->logActivity('stream', "membuka preview dokumen '{$this->title}'");
    }
}