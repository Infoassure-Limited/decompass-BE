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
        Schema::create('statement_of_availabilities', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('s_o_a_area_id');
            $table->integer('s_o_a_control_id');
            $table->string('applicable')->nullable();
            $table->string('implemented')->nullable();
            $table->string('legal_requirement')->nullable();
            $table->string('business_requirement')->nullable();
            $table->string('result_of_risk_assessment')->nullable();
            $table->text('justification_of_exclusion')->nullable();
            $table->text('assets')->nullable();
            $table->text('risk')->nullable();
            $table->text('issue')->nullable();
            $table->text('addition_control_required')->nullable();
            $table->string('r')->nullable();
            $table->string('a')->nullable();
            $table->string('c')->nullable();
            $table->string('i')->nullable();
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
        Schema::dropIfExists('statement_of_availabilities');
    }
};
