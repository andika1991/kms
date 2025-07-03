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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen')->nullable();
            $table->string('path_dokumen')->nullable();
            $table->string('encrypted_key')->nullable();
            $table->text('deskripsi')->nullable();
             $table->foreignId('kategori_dokumen_id')->nullable()->constrained('kategori_dokumen')->onDelete('cascade');
                         $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->onDelete('cascade');
             $table->timestamps();
             $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
