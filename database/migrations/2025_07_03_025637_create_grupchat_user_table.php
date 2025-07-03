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
        Schema::create('grupchat_user', function (Blueprint $table) {
            $table->id();
              $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->onDelete('cascade');
                $table->foreignId('grupchat_id')->nullable()->constrained('grupchat')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupchat_user');
    }
};
