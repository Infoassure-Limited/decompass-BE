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

        Schema::create('audit_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['security', 'compliance', 'performance', 'financial', 'general'])->default('general');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('audit_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->foreignId('audit_template_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->enum('question_type', ['yes_no', 'multiple_choice', 'text', 'numeric', 'date'])->default('yes_no');
            $table->json('options')->nullable(); // For multiple_choice questions
            $table->boolean('is_required')->default(true);
            $table->integer('weight')->default(1); // For weighted scoring
            $table->string('category')->nullable(); // To group questions
            $table->integer('order')->default(0); // For ordering questions
            $table->timestamps();
        });
        Schema::create('vendor_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->integer('client_id');
            $table->foreignId('audit_template_id')->constrained();
            $table->foreignId('auditor_id')->nullable(); // User who performed the audit
            $table->date('audit_date');
            $table->date('due_date')->nullable();
            $table->enum('status', ['draft', 'in_progress', 'completed', 'expired', 'canceled'])->default('draft');
            $table->decimal('score', 5, 2)->nullable(); // Overall score
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('audit_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_audit_id')->constrained()->onDelete('cascade');
            $table->foreignId('audit_question_id')->constrained()->onDelete('cascade');
            $table->text('response');
            $table->text('comment')->nullable();
            $table->decimal('score', 5, 2)->nullable(); // Score for this particular response
            $table->json('attachments')->nullable(); // File attachments as evidence
            $table->timestamps();
        });
        Schema::create('audit_risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_audit_id')->nullable()->constrained(); // Can be based on an audit
            $table->foreignId('assessor_id')->nullable(); // User who performed the assessment
            $table->date('assessment_date');
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->decimal('risk_score', 5, 2)->nullable();
            $table->text('findings')->nullable();
            $table->text('recommendations')->nullable();
            $table->date('next_assessment_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('audit_remediation_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_risk_assessment_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('assigned_to'); // email of the User responsible
            $table->date('due_date');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'overdue'])->default('pending');
            $table->date('completed_date')->nullable();
            $table->text('completion_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_templates');
        Schema::dropIfExists('audit_questions');
        Schema::dropIfExists('vendor_audits');
        Schema::dropIfExists('audit_responses');
        Schema::dropIfExists('audit_risk_assessments');
        Schema::dropIfExists('audit_remediation_plans');
    }
};
