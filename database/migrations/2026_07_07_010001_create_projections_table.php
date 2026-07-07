<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('countdown_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('neutral');
            $table->timestamp('target_date')->nullable();
            $table->json('title');
            $table->json('summary')->nullable();
            $table->unsignedTinyInteger('confidence_score')->default(50);
            $table->unsignedTinyInteger('probability_score')->default(50);
            $table->string('trend')->default('stable');
            $table->json('methodology')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['countdown_id', 'type', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projections');
    }
};
