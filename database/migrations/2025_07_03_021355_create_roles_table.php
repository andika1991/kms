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
        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->string('nama_role');
              $table->integer('parent_id')->nullable();
   $table->foreignId('bidang_id')->constrained('bidang')->onDelete('cascade');
            $table->foreignId('subbidang_id')->nullable()->constrained('subbidang')->onDelete('cascade');
   $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
