<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_sources', function (Blueprint $table): void {
            $table->id();
            $table->string('type');
            $table->string('provider')->nullable();
            $table->string('name');
            $table->string('external_id')->nullable();
            $table->text('source_url')->nullable();
            $table->text('feed_url')->nullable();
            $table->json('topics')->nullable();
            $table->json('keywords')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedInteger('weight')->default(100);
            $table->boolean('is_global')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type', 'provider', 'is_active']);
            $table->unique(['type', 'provider', 'external_id']);
        });

        Schema::create('content_source_countdown', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('content_source_id')->constrained()->cascadeOnDelete();
            $table->foreignId('countdown_id')->constrained()->cascadeOnDelete();
            $table->json('keywords')->nullable();
            $table->json('excluded_keywords')->nullable();
            $table->unsignedInteger('weight')->default(100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['content_source_id', 'countdown_id']);
            $table->index(['countdown_id', 'is_active']);
        });

        Schema::create('news', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('countdown_id')->constrained()->cascadeOnDelete();
            $table->string('locale')->default('all');
            $table->string('title');
            $table->text('excerpt');
            $table->string('content_type')->default('article');
            $table->string('source_name')->nullable();
            $table->text('source_url')->nullable();
            $table->text('canonical_source_url')->nullable();
            $table->string('canonical_source_hash', 64)->nullable();
            $table->string('external_provider')->nullable();
            $table->string('external_id')->nullable();
            $table->text('embed_url')->nullable();
            $table->text('preview_image_url')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['countdown_id', 'locale', 'published_at']);
            $table->index(['content_type', 'published_at']);
            $table->unique(['countdown_id', 'canonical_source_hash']);
            $table->unique(['countdown_id', 'external_provider', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
        Schema::dropIfExists('content_source_countdown');
        Schema::dropIfExists('content_sources');
    }
};
