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
        Schema::create('incident_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->string('title');
            $table->text('description');
            $table->foreignId('incident_type_id')->constrained('incident_types');
            $table->foreignId('reported_by');
            $table->foreignId('assigned_to')->nullable();
            $table->enum('status', ['Open', 'In Progress', 'Resolved', 'Closed', 'Reopened', 'Escalated'])->default('open');
            $table->enum('severity', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->dateTime('occurred_at')->nullable();
            $table->text('affected_assets')->nullable();
            $table->string('location')->nullable();

            $table->enum('review_status', ['Approved', 'Rejected', 'Pending'])->default('Pending');
            $table->text('reviewer_comment')->nullable();
            $table->dateTime('closure_date')->nullable();
            $table->timestamps();
        });
        Schema::create('immediate_resolution_actions', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->foreignId('incident_id')->constrained('incidents');
            $table->text('immediate_action_taken')->nullable();
            $table->enum('is_escalated', ['No', 'Yes'])->default('No');
            $table->text('escalation_details')->nullable();
            $table->date('deadline')->nullable();
            $table->timestamps();
        });
        Schema::create('incident_evidences', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->foreignId('incident_id')->constrained('incidents');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size');
            $table->integer('user_id');
            $table->text('comments');
            $table->timestamps();
        });

        Schema::create('incident_tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->string('title');
            $table->text('description');
            $table->foreignId('incident_id')->constrained('incidents');
            $table->foreignId('assigned_to')->nullable();
            $table->enum('status', ['Open', 'In Progress', 'Resolved', 'Closed', 'Reopened'])->default('Open');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->date('deadline')->nullable();
            $table->string('evidence_of_task_completion')->nullable();
            $table->enum('approval_status', ['Approved', 'Rejected', 'Pending'])->default('Pending');
            $table->text('additional_noted')->nullable();
            $table->timestamps();
        });
        Schema::create('incident_root_cause_analyses', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->text('description');
            $table->foreignId('incident_id')->constrained('incidents');
            $table->text('impact_of_the_incident')->nullable();
            $table->text('preventive_measures')->nullable();
            $table->enum('follow_up_required', ['Yes', 'No'])->default('No');
            $table->timestamps();
        });
        Schema::create('incident_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('incident_id');
            $table->foreignId('user_id')->nullable();
            $table->string('action');
            $table->json('changes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_types');
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('immediate_resolution_actions');
        Schema::dropIfExists('incident_evidences');
        Schema::dropIfExists('incident_tasks');
        Schema::dropIfExists('incident_root_cause_analyses');
        Schema::dropIfExists('incident_activity_logs');
    }
};
