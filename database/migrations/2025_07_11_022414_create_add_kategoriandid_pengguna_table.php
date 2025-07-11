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
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->enum('kategori_kegiatan', ['publik', 'internal'])
                  ->default('publik')
                  ->after('deskripsi_kegiatan');
                    $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropColumn('kategori_kegiatan');
              $table->dropColumn('pengguna_id');
        });
    }
};
