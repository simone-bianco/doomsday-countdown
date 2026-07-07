<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_agent_revisions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('agent_id')->constrained('ai_agents')->cascadeOnDelete();
            $table->unsignedInteger('revision_number');
            $table->json('snapshot');
            $table->string('note')->nullable();
            $table->string('author_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unique(['agent_id', 'revision_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_agent_revisions');
    }
};
