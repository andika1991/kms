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
        Schema::create('dokumen_strategis', function (Blueprint $table) {
            $table->id('id_dokumen_strategis');
            $table->string('status_publikasi')->nullable();
            $table->string('target_publikasi')->nullable();
            $table->text('alasan_publikasi')->nullable();
            $table->boolean('perlu_persetujuankadis')->default(false);
            $table->boolean('perlu_persetujuankabid')->default(false);

            $table->string('tanggal_persetujuan_kabid')->nullable();
            $table->string('tanggal_persetujuan_kadis')->nullable();

            $table->string('status_persetujuan_kabid')->nullable();
            $table->string('status_persetujuan_kadis')->nullable();

            // relasi ke pengguna
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumen')->onDelete('cascade');

            // relasi ke kepala bidang
            $table->foreignId('kabid_id')->nullable()->constrained('pengguna')->onDelete('cascade');

            // relasi ke kepala dinas
            $table->foreignId('kadis_id')->nullable()->constrained('pengguna')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_strategis');
    }
};
