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
        Schema::create('key_risk_indicator_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('business_unit_id');
            $table->integer('risk_assessment_id');
            $table->string('frequency_of_assessment')->nullable();
            $table->string('unit_of_measurement')->nullable();
            $table->text('risk_trigger_threshold')->nullable();
            $table->json('assessments')->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('key_risk_indicator_assessments');
    }
};
