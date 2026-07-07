<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_agents', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('scope')->nullable();
            $table->string('provider')->default('openai');
            $table->string('model');
            $table->longText('system_prompt')->nullable();
            $table->float('temperature')->nullable();
            $table->float('top_p')->nullable();
            $table->unsignedInteger('max_completion_tokens')->nullable();
            $table->boolean('parallel_tool_calls')->default(true);
            $table->boolean('developer_role_for_instructions')->default(false);
            $table->string('response_format')->default('json_schema');
            $table->json('response_schema')->nullable();
            $table->string('streaming_mode')->default('sync');
            $table->string('history_driver')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->unsignedInteger('active_runs_count')->default(0);
            $table->json('metadata')->nullable();
            $table->uuid('current_revision_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_agents');
    }
};
