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
        Schema::create('risk_registers', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('business_unit_id');
            $table->string('risk_id');
            $table->string('risk_type')->nullable();
            $table->string('vunerability_description')->nullable();
            $table->string('threat_impact_description')->nullable();
            $table->string('existing_controls')->nullable();
            $table->string('risk_owner')->nullable();
            $table->timestamps();
        });
        Schema::create('risk_impact_areas', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->string('area');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::create('risk_impact_on_areas', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('risk_impact_id');
            $table->integer('risk_impact_area_id');
            $table->string('impact_level');
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
        Schema::dropIfExists('risk_registers')->nullable();
        Schema::dropIfExists('risk_impact_areas')->nullable();
        Schema::dropIfExists('risk_impact_on_areas')->nullable();
    }
};
