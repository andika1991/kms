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
        Schema::create('aksesdokumenpengguna', function (Blueprint $table) {
            $table->id();
                         $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->onDelete('cascade');
             $table->foreignId('dokumen_id')->nullable()->constrained('dokumen')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aksesdokumenpengguna');
    }
};
