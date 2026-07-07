<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_agent_tools', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('key')->unique();
            $table->string('kind')->default('registered_class');
            $table->string('class')->nullable();
            $table->foreignUuid('sub_agent_id')->nullable()->constrained('ai_agents')->nullOnDelete();
            $table->json('dynamic_definition')->nullable();
            $table->string('label');
            $table->text('description')->nullable();
            $table->json('default_parameters')->nullable();
            $table->json('parameter_manifest')->nullable();
            $table->json('allowed_sub_agent_types')->nullable();
            $table->json('allowed_hosts')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_agent_tools');
    }
};
