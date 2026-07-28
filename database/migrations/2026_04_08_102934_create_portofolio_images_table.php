<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portofolios_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portofolios_id')->constrained()->cascadeOnDelete();
            $table->string('image_url', 255);
            $table->string('caption', 255)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portofolios_images');
    }
};
