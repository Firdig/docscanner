<?php
use App\Http\Controllers\ScanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Placeholder menu scan & dokumen
    // Route::view('/scan', 'scan.index')->name('scan.index');
    Route::view('/documents', 'documents.index')->name('documents.index');
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{document}/stream', [DocumentController::class, 'stream'])
        ->name('documents.stream');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
   
    Route::view('/scan', 'scan.index')->name('scan.index');     // <- INDEX (wizard)
    Route::view('/scan/step1', 'scan.step1')->name('scan.step1');
    Route::view('/scan/step2', 'scan.step2')->name('scan.step2');
    //Route::view('/scan/step3', 'scan.step3')->name('scan.step3');

    // API routes for scan - fix the route path
    Route::post('/api/scan/upload', [ScanController::class, 'upload'])->name('scan.upload');
    Route::post('/api/scan/start-batch', [ScanController::class, 'startBatch'])->name('scan.start-batch');
    Route::post('/api/scan/cancel-batch', [ScanController::class, 'cancelBatch'])->name('scan.cancel-batch');

    // Activity Logs
    Route::get('/activity-logs', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [App\Http\Controllers\ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::get('/activity-logs/export', [App\Http\Controllers\ActivityLogController::class, 'export'])->name('activity-logs.export');
    
    // API routes untuk dashboard
    Route::get('/api/activity-logs/recent', [App\Http\Controllers\ActivityLogController::class, 'recent']);

    // Route::post('/scan/upload', [ScanController::class, 'upload'])->name('scan.upload');
    // Route::post('/api/scan/upload', [\App\Http\Controllers\ScanController::class,'upload'])
    //     ->name('scan.upload');
        
});


// //Route::middleware(['auth'])->prefix('scan')->name('scan.')->group(function(){
//     Route::get('/',                 [ScanController::class,'devices'])->name('index');     // step 1
//     Route::post('/start',           [ScanController::class,'start'])->name('start');       // pilih device/preset
//     Route::get('/capture/{batch}',  [ScanController::class,'capture'])->name('capture');   // step 2
//     Route::post('/capture/{batch}/upload', [ScanController::class,'uploadPage'])->name('upload'); // terima halaman dari SDK (blob->file)
//     Route::delete('/capture/{batch}/page/{page}', [ScanController::class,'deletePage'])->name('page.delete');
//     Route::post('/capture/{batch}/page/{page}/replace', [ScanController::class,'replacePage'])->name('page.replace');

//     Route::get('/review/{batch}',   [ScanController::class,'review'])->name('review');     // step 3
//     Route::post('/review/{batch}/reorder', [ScanController::class,'reorder'])->name('reorder');

//     Route::get('/metadata/{batch}', [ScanController::class,'metadata'])->name('metadata'); // step 4
//     Route::post('/save/{batch}',    [ScanController::class,'save'])->name('save');
//     Route::post('/cancel/{batch}',  [ScanController::class,'cancel'])->name('cancel');
// });

require __DIR__.'/auth.php';
