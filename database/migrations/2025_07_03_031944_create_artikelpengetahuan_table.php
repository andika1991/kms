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
        Schema::create('artikelpengetahuan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('thumbnail');
            $table->string('filedok');
            $table->text('isi');
            $table->foreignId('kategori_pengetahuan_id')->nullable()->constrained('kategori_pengetahuan')->onDelete('cascade');
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->onDelete('cascade');                              
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikelpengetahuan');
    }
};
