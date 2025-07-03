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
        Schema::create('agenda_pimpinan', function (Blueprint $table) {
            $table->id(); // PK
            $table->string('nama_agenda');
            $table->date('date_agenda');
            $table->time('waktu_agenda');
            $table->foreignId('id_pengguna')->constrained('pengguna')->onDelete('cascade');
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_pimpinan');
    }
};
