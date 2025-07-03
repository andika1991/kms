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
        Schema::create('grupchat', function (Blueprint $table) {
            $table->id(); // buat nama kolom id spesifik
            $table->string('nama_grup');
            $table->text('deskripsi')->nullable();
            $table->string('grup_role')->nullable(); // role seperti: bidang, forum, privat, dll.
            $table->boolean('is_private')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('bidang_id')->nullable()->constrained('bidang')->onDelete('cascade');
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupchat');
    }
};
