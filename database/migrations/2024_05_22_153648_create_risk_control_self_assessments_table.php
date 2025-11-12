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
        Schema::create('risk_control_self_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('business_unit_id');
            $table->integer('business_process_id');
            $table->integer('rcm_id');
            $table->string('category');
            $table->string('key_process');
            $table->string('control_activities');
            $table->string('control_owner');
            $table->string('source');
            $table->string('control_type');
            $table->string('risk_description');
            $table->integer('risk_rating');
            $table->string('self_assessment_control')->nullable();
            $table->integer('self_assessment_score')->nullable();
            $table->string('comment_on_status')->nullable();
            $table->string('rm_rating_of_control')->nullable();
            $table->integer('validation')->nullable();
            $table->string('basis_of_rm_rating')->nullable();
            $table->integer('self_assessment_of_process_level_risk')->nullable();
            $table->string('rm_validated_process_level_risk')->nullable();
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
        Schema::dropIfExists('risk_control_self_assessments');
    }
};
