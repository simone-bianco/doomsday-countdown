<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_agent_tool_bindings', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('agent_id')->constrained('ai_agents')->cascadeOnDelete();
            $table->foreignUuid('tool_id')->constrained('ai_agent_tools')->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->string('alias')->nullable();
            $table->string('sub_agent_history_mode')->default('stateless');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['agent_id', 'tool_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_agent_tool_bindings');
    }
};
