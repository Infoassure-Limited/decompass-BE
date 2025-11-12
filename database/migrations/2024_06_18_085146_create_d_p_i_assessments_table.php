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
        Schema::create('d_p_i_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('business_unit_id')->nullable();
            $table->integer('business_process_id')->nullable();
            $table->text('personal_data_asset')->nullable();
            $table->text('risk_scenerio')->nullable();
            $table->string('risk_owner')->nullable();
            $table->text('existing_controls')->nullable();
            $table->integer('likelihood')->nullable();
            $table->string('likelihood_rationale')->nullable();
            $table->integer('impact')->nullable();
            $table->string('impact_rationale')->nullable();
            $table->integer('risk_score')->nullable();
            $table->string('risk_level')->nullable();
            $table->string('treatment_option')->nullable();
            $table->text('treatment_actions')->nullable();
            $table->integer('revised_likelihood')->nullable();
            $table->string('revised_likelihood_rationale')->nullable();
            $table->integer('revised_impact')->nullable();
            $table->string('rivised_impact_rationale')->nullable();
            $table->integer('revised_risk_score')->nullable();
            $table->string('revised_risk_level')->nullable();
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
        Schema::dropIfExists('d_p_i_assessments');
    }
};
