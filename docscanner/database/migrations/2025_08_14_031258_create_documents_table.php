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
    Schema::create('documents', function (Blueprint $table) {
        $table->id();
        $table->string('title');                 // Judul/Perihal
        $table->string('letter_number')->nullable(); // Nomor Surat
        $table->date('document_date')->nullable();   // Tanggal Dokumen
        $table->string('category')->nullable();      // Kategori (string dulu)
        $table->year('year')->nullable();            // Tahun
        $table->text('description')->nullable();     // Deskripsi
        $table->string('disk')->default('public');   // disk penyimpanan
        $table->string('path');                      // path file relatif pada disk
        $table->string('mime')->nullable();          // tipe mime (pdf/jpg/png)
        $table->unsignedBigInteger('size')->nullable(); // ukuran byte
        $table->timestamps();
        $table->index(['title', 'letter_number', 'category']);
        $table->index('document_date');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
