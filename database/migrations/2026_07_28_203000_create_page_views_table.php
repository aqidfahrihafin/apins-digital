<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('path', 500);
            $table->string('route_name', 120)->nullable();
            $table->string('page_title', 200)->nullable();
            $table->string('visitor_hash', 64)->index();
            $table->string('session_hash', 64)->index();
            $table->string('device', 20)->default('desktop');
            $table->string('referrer_host', 150)->nullable();
            $table->unsignedInteger('response_ms')->default(0);
            $table->timestamp('viewed_at')->index();
            $table->timestamps();

            $table->index(['viewed_at', 'path']);
            $table->index(['viewed_at', 'device']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
