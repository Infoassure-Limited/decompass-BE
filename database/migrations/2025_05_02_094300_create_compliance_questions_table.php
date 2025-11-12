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
        Schema::create('clauses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
        Schema::create('compliance_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('clause_id');
            $table->text('question');
            $table->longText('possible_tasks')->nullable();
            $table->enum('input_type', ['text', 'text_area', 'dropdown', 'number', 'date'])->default('text');
            $table->string('select_options')->nullable();
            $table->boolean('is_multiple_select')->default(0);
            $table->boolean('requires_evidence')->default(0);
            $table->timestamps();
        });
        Schema::create('compliance_response_monitors', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('project_id');
            $table->integer('clause_id');
            $table->boolean('is_submitted')->default(0);
            $table->date('date_submitted')->nullable();
            $table->integer('submitted_by')->nullable();
            $table->timestamps();
        });
        Schema::create('compliance_responses', function (Blueprint $table) {
            $table->id();
            $table->integer('assignee_id');
            $table->integer('client_id');
            $table->integer('compliance_response_monitor_id');
            $table->integer('clause_id');
            $table->integer('compliance_question_id');
            $table->text('question');
            $table->text('response')->nullable();
            $table->boolean('is_exception');
            $table->string('status')->nullable();
            $table->text('evidences')->nullable();
            $table->longText('assignee_tasks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clauses');
        Schema::dropIfExists('compliance_questions');
        Schema::dropIfExists('compliance_response_monitors');
        Schema::dropIfExists('compliance_responses');
    }
};
