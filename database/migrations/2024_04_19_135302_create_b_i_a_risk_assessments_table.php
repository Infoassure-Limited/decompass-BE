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
        Schema::create('b_i_a_risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('ra_id');
            $table->integer('client_id');
            $table->integer('business_unit_id');
            $table->integer('business_processes_id');
            // $table->integer('asset_type_id');
            // $table->string('asset');
            $table->string('risk_owner')->nullable();
            $table->string('risk_description')->nullable();
            $table->string('existing_treatment')->nullable();
            $table->integer('likelihood')->nullable();
            $table->string('likelihood_rationale')->nullable();
            $table->integer('impact')->nullable();
            $table->string('impact_rationale')->nullable();
            $table->integer('risk_score')->nullable();
            $table->string('risk_level')->nullable();
            $table->string('treatment_option')->nullable();
            $table->string('outage_scenerio')->nullable();
            $table->string('treatment')->nullable();
            $table->string('responsible')->nullable();
            $table->string('status')->nullable();
            $table->date('target_date_for_closure')->nullable();
            $table->integer('post_treatment_likelihood')->nullable();
            $table->string('post_treatment_likelihood_rationale')->nullable();
            $table->integer('post_treatment_impact')->nullable();
            $table->string('post_treatment_impact_rationale')->nullable();
            $table->integer('post_treatment_risk_score')->nullable();
            $table->string('post_treatment_risk_level')->nullable();
            $table->string('residual_risk')->nullable();
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
        Schema::dropIfExists('b_i_a_risk_assessments');
    }
};
