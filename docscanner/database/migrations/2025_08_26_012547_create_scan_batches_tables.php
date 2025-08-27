<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('scan_batches', function (Blueprint $t) {
        $t->id();
        $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        $t->string('status')->default('draft'); // draft|saved|cancelled
        $t->string('source_name')->nullable(); // nama device TWAIN yang dipilih
        $t->integer('dpi')->nullable();        // preset dpi
        $t->string('color_mode')->nullable();  // 'color'|'grayscale'
        $t->timestamps();
    });

    Schema::create('scan_pages', function (Blueprint $t) {
        $t->id();
        $t->foreignId('scan_batch_id')->constrained()->cascadeOnDelete();
        $t->string('disk')->default('public');
        $t->string('path');       // path gambar sementara (jpg/png)
        $t->integer('order')->default(0);
        $t->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_batches_tables');
    }
};
