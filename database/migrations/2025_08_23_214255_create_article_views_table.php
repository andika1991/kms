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
        Schema::create('article_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artikel_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();

            $table->foreign('artikel_id')->references('id')->on('artikelpengetahuan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('pengguna')->onDelete('cascade');

            // 1 user dihitung sekali per artikel
            $table->unique(['artikel_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_views');
    }
};
