<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->string('module')->index();
            $table->integer('risk_register_id')->index();
            $table->integer('client_id')->index();
            $table->integer('business_unit_id')->nullable()->index();
            $table->integer('business_process_id')->nullable()->index();
            $table->integer('standard_id')->nullable();
            $table->integer('asset_type_id')->nullable();
            $table->string('asset')->nullable();

            $table->json('impact_data')->nullable();

            $table->integer('likelihood_of_occurence')->nullable();
            $table->string('likelihood_rationale')->nullable();
            $table->integer('impact_of_occurence')->nullable();
            $table->string('impact_rationale')->nullable();
            $table->integer('risk_score')->nullable();
            $table->string('risk_level')->nullable();
            $table->string('risk_level_color')->nullable();
            $table->string('treatment_option')->nullable();
            $table->string('treatment_option_details')->nullable();
            $table->text('recommended_control')->nullable();
            $table->string('control_effectiveness_level')->nullable();

            $table->json('revised_impact_data')->nullable();

            $table->integer('revised_likelihood_of_occurence')->nullable();
            $table->string('revised_likelihood_rationale')->nullable();
            $table->integer('revised_impact_of_occurence')->nullable();
            $table->string('revised_impact_rationale')->nullable();
            $table->integer('revised_risk_score')->nullable();
            $table->string('revised_risk_level')->nullable();
            $table->string('revised_risk_level_color')->nullable();
            $table->string('residual_risk_treatment_option')->nullable();
            $table->string('residual_treatment_option_details')->nullable();
            $table->string('status')->nullable();
            $table->date('target_closure_date')->nullable();
            $table->string('key_risk_indicator')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('risk_assessments');
    }
};
