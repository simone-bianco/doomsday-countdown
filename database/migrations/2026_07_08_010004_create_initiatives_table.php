<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('initiatives', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('countdown_id')->constrained()->cascadeOnDelete();
            $table->string('locale')->default('all');
            $table->string('type')->default('other');
            $table->string('title');
            $table->text('excerpt');
            $table->text('body')->nullable();
            $table->string('organization')->nullable();
            $table->string('url');
            $table->string('image_path')->nullable();
            $table->string('cta_label')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['countdown_id', 'locale', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('initiatives');
    }
};
