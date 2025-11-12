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
        Schema::create('annex_control_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('kpi_id')->nullable();
            $table->integer('client_id');
            $table->integer('client_id');
            $table->integer('client_id');
            $table->integer('client_id');
            $table->integer('client_id');
            $table->integer('client_id');
            $table->integer('client_id');
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
        Schema::dropIfExists('annex_control_assessments');
    }
};
