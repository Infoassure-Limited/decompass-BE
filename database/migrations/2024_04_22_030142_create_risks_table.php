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
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('business_unit_id');
            $table->integer('business_process_id');
            $table->string('risk_unique_id');
            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->string('outcome')->nullable();
            $table->string('risk_owner')->nullable();
            $table->string('control_no')->nullable();
            $table->string('control_location')->nullable();
            $table->string('control_description')->nullable();
            $table->string('control_frequency')->nullable();
            $table->string('control_owner')->nullable();
            $table->string('control_type')->nullable();
            $table->string('nature_of_control')->nullable();
            $table->string('application_used_for_control')->nullable();
            $table->string('compensating_control')->nullable();
            $table->string('test_procedures')->nullable();
            $table->integer('sample_size')->nullable();
            $table->string('data_required')->nullable();
            $table->string('link_to_evidence')->nullable();
            $table->string('test_conclusion')->nullable();
            $table->string('gap_description')->nullable();
            $table->string('tod_improvement_opportunity')->nullable();
            $table->integer('recommendation')->nullable();
            $table->string('responsibility')->nullable();
            $table->string('timeline')->nullable();
            $table->string('tod_gap_status')->nullable();
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
        Schema::dropIfExists('risks');
    }
};
