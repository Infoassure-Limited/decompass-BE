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
        Schema::create('project_phases', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('general_project_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('project_phase_id');
            $table->integer('standard_id');
            $table->string('task');
            $table->string('responsibility');
            $table->string('resource');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('client_project_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('project_id');
            $table->integer('general_project_plan_id');
            $table->integer('progress')->default(0);
            $table->string('status')->default('pending');
            $table->text('pending_items')->nullable();
            $table->text('risk')->nullable();
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
        Schema::dropIfExists('general_project_plans');
        Schema::dropIfExists('project_phases');
        Schema::dropIfExists('client_project_plans');
    }
};
