<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visualizations', function (Blueprint $table): void {
            $table->id();
            $table->morphs('visualizable');
            $table->string('key');
            $table->string('type')->default('line');
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('sources');
            $table->json('reasoning');
            $table->json('payload');
            $table->unsignedInteger('schema_version')->default(1);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visualizations');
    }
};
