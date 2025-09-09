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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            
            // Activity details
            $table->string('activity_type', 50); // 'document', 'scan', 'auth'
            $table->string('action', 50);        // 'create', 'read', 'update', 'delete', 'login', 'logout'
            $table->string('description');       // Deskripsi aktivitas
            
            // Subject (object yang diakses)
            $table->string('subject_type')->nullable(); // 'Document', 'ScanBatch'
            $table->unsignedBigInteger('subject_id')->nullable();
            
            // Properties (data tambahan dalam JSON)
            $table->json('properties')->nullable(); // old_values, new_values, metadata
            
            // Request details
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('url', 500)->nullable();
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index(['activity_type', 'action']);
            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};