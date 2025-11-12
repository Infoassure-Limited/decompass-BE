<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('module_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('clause_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::create('module_activity_tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('clause_id');
            $table->integer('module_activity_id');
            $table->integer('dependency')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('hint')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('occurence', ['weekly', 'monthly', 'biannually', 'anually'])->default('monthly');
            $table->timestamps();
        });

        Schema::create('assigned_tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('clause_id');
            $table->integer('module_activity_id');
            $table->integer('module_activity_task_id');
            $table->integer('assignee_id')->nullable();
            $table->json('days')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->double('progress')->default('0.00');
            $table->enum('status', ['pending', 'in_progress', 'overdue', 'completed'])->default('pending');
            $table->timestamps();
        });

        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->foreignId('assigned_task_id');
            $table->foreignId('triggered_by')->nullable();
            $table->timestamp('executed_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_activities');
        Schema::dropIfExists('module_activity_tasks');
        Schema::dropIfExists('assigned_tasks');
        Schema::dropIfExists('task_logs');
    }
};
