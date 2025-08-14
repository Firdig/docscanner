<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
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
}
