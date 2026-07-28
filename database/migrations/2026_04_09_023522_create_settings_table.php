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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('headline', 255);
            $table->text('subheadline')->nullable();
            $table->text('description')->nullable();
            $table->string('hero_title', 100)->nullable();
            $table->json('services')->nullable();
            $table->string('portfolio_button_text', 100)->nullable();
            $table->string('portfolio_url', 500)->nullable();
            $table->string('consultation_button_text', 100)->nullable();
            $table->string('consultation_url', 500)->nullable();

            // 1 kolom image (bisa untuk hero background atau banner)
            $table->string('image')->nullable(); // menyimpan path file gambar

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
