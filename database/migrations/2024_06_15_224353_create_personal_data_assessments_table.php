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
        Schema::create('personal_data_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('business_unit_id');
            $table->integer('business_process_id');
            $table->text('personal_data_item')->nullable();
            $table->text('description')->nullable();
            $table->string('sensitive_personal_data')->nullable();
            $table->string('exception_used_personal_data')->nullable();
            $table->string('obtained_from_data_source')->nullable();
            $table->string('owner')->nullable();
            $table->string('processing_purpose')->nullable();
            $table->string('lawful_basis_of_processing')->nullable();
            $table->string('how_is_consent_obtained')->nullable();
            $table->string('automated_decision_making')->nullable();
            $table->string('level_of_data_subject_access')->nullable();
            $table->string('location_stored')->nullable();
            $table->string('country_stored_in')->nullable();
            $table->string('retention_period')->nullable();
            $table->string('encryption_level')->nullable();
            $table->string('access_control')->nullable();
            $table->string('third_parties_shared_with')->nullable();
            $table->string('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_data_assessments');
    }
};
