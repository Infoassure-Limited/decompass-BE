<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_disruption_impacts', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('business_impact_analysis_id');
            $table->string('disaster');
            $table->string('one_hr');
            $table->string('three_hrs');
            $table->string('one_day');
            $table->string('three_days');
            $table->string('one_week');
            $table->string('two_weeks');
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
        Schema::dropIfExists('process_disruption_impacts');
    }
};
