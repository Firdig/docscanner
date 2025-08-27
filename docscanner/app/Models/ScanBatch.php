<?php
// app/Models/ScanBatch.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ScanBatch extends Model {
    protected $fillable = ['user_id','status','source_name','dpi','color_mode'];
    public function pages(){ return $this->hasMany(ScanPage::class)->orderBy('order'); }
}

// app/Models/ScanPage.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ScanPage extends Model {
    protected $fillable=['scan_batch_id','disk','path','order'];
    protected $appends=['url'];
    public function getUrlAttribute(){ return \Storage::disk($this->disk)->url($this->path); }
    public function batch(){ return $this->belongsTo(ScanBatch::class); }
}
