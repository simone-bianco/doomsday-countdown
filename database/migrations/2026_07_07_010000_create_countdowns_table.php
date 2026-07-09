<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countdowns', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('summary');
            $table->json('description')->nullable();
            $table->json('causes')->nullable();
            $table->json('consequences')->nullable();
            $table->json('recommended_actions')->nullable();
            $table->string('severity')->default('high');
            $table->string('status')->default('active');
            $table->timestamp('target_date')->nullable();
            $table->string('image_path');
            $table->string('accent_color')->default('#ff2a23');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countdowns');
    }
};
