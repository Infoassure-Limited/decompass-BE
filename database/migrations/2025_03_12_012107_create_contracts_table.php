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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('vendor_id');
            $table->integer('title');
            $table->string('file_link');
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sla_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('contract_id');
            $table->integer('client_id');
            $table->integer('vendor_id');
            $table->string('service_name');
            $table->text('service_description');
            $table->double('uptime_guarantee')->nullable();
            $table->integer('response_time')->nullable();
            $table->integer('resolution_time')->nullable();
            $table->mediumText('vendor_responsibilities')->nullable();
            $table->mediumText('client_responsibilities')->nullable();
            $table->string('report_frequency')->nullable();
            $table->string('performance_monitoring_method')->nullable();
            $table->string('penalty_type')->nullable();
            $table->integer('penalty_amount')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('renewal_terms')->nullable();
            $table->string('approval_workflow')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vendor_performance_scorecards', function (Blueprint $table) {
            $table->id();
            $table->integer('contract_id');
            $table->integer('client_id');
            $table->integer('vendor_id');
            $table->integer('sla_config_id');
            $table->double('delivery_timeliness')->nullable();
            $table->integer('service_quality_rating')->nullable();
            $table->integer('uptime_performance')->nullable();
            $table->integer('issue_resolution_time')->nullable();
            $table->string('sla_compliance_status')->nullable();
            $table->integer('overall_performance_score')->nullable();
            $table->string('action_required')->nullable();
            $table->text('comments')->nullable();
            $table->string('approval_status')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('sla_configs');
        Schema::dropIfExists('vendor_performance_scorecards');
    }
};
