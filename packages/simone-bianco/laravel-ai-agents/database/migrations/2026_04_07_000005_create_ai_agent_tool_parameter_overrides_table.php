<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_agent_tool_parameter_overrides', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('tool_binding_id')->constrained('ai_agent_tool_bindings')->cascadeOnDelete();
            $table->string('parameter_path');
            $table->string('source');
            $table->json('literal_value')->nullable();
            $table->string('variable_expression')->nullable();
            $table->timestamps();
            $table->unique(['tool_binding_id', 'parameter_path']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_agent_tool_parameter_overrides');
    }
};
