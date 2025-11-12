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
        Schema::create('business_impact_analyses', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('business_unit_id');
            $table->integer('business_process_id');
            $table->string('priority')->nullable();
            $table->integer('minimum_service_level')->nullable();
            $table->string('maximum_allowable_outage')->nullable();
            $table->string('recovery_time_objective')->nullable();
            $table->string('recovery_point_objective')->nullable();
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
        Schema::dropIfExists('business_impact_analyses');
    }
};
