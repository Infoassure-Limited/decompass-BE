<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('info_need_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['name']);
        });
        Schema::create('info_needs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('info_need_category_id')->constrained('info_need_categories')->cascadeOnDelete();
            $table->string('name')->index();
            $table->string('code')->nullable(); // optional identifier
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['info_need_category_id','name']);
        });

        Schema::create('measures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('info_need_id')->constrained('info_needs')->cascadeOnDelete();
            $table->string('title')->index();
            $table->text('measurement_need')->nullable();
            $table->json('metadata')->nullable(); // advanced fields
            $table->timestamps();

            $table->unique(['info_need_id', 'title']);
        });

        Schema::create('applicable_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id');
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->decimal('weight', 5, 2)->default(0);
            $table->json('metadata')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['client_id', 'title']);
            $table->index(['client_id','weight']);
        });

        Schema::create('performance_monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id');
            $table->foreignId('info_need_id')->constrained('info_needs')->cascadeOnDelete();
            $table->foreignId('measure_id')->constrained('measures')->cascadeOnDelete();
            $table->foreignId('applicable_objective_id')->nullable()->constrained('applicable_objectives')->cascadeOnDelete();
            $table->string('frequency')->default('Annually');
            $table->string('unit')->nullable();
            $table->text('formula')->nullable();
            $table->string('target')->nullable();
            $table->enum('is_achieved', ['No', 'Yes'])->default('No');
            $table->text('not_achieved_reasons')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->json('settings')->nullable(); // e.g., thresholds, refresh_period
            $table->timestamps();

            $table->index(['client_id']);
            $table->index(['measure_id']);
            $table->index(['applicable_objective_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_need_categories');
        Schema::dropIfExists('info_needs');
        Schema::dropIfExists('applicable_objectives');
        Schema::dropIfExists('measures');
        Schema::dropIfExists('performance_monitorings');
    }
};
