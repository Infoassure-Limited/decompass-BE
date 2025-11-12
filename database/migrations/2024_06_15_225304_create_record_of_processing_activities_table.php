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
        Schema::create('record_of_processing_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('business_unit_id');
            $table->string('controller_name')->nullable();
            $table->string('controller_contact_details')->nullable();
            $table->string('joint_controller_name')->nullable();
            $table->string('joint_controller_contact_details')->nullable();
            $table->string('controller_rep_name')->nullable();
            $table->string('controller_rep_contact_details')->nullable();
            $table->string('dpo_name')->nullable();
            $table->string('dpo_details')->nullable();
            $table->string('processing_purpose')->nullable();
            $table->string('data_subject_categories')->nullable();
            $table->string('personal_data_categories')->nullable();
            $table->string('data_recipients_categories')->nullable();
            $table->string('international_transfer_destination')->nullable();
            $table->string('erasure_time_limit')->nullable();
            $table->string('security_measures_applied')->nullable();
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
        Schema::dropIfExists('record_of_processing_activities');
    }
};
