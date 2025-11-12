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
        Schema::create('business_processes', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('business_unit_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('roles_responsible')->nullable();
            $table->integer('no_of_people_involved')->nullable();
            $table->integer('minimum_no_of_people_involved')->nullable();
            $table->string('product_or_service_delivered')->nullable();
            $table->string('regulatory_obligations')->nullable();
            $table->string('applications_used')->nullable();
            $table->string('business_units_depended_on')->nullable();
            $table->string('processes_depended_on')->nullable();
            $table->string('key_vendors_or_external_dependencies')->nullable();
            $table->string('vital_non_electronic_records')->nullable();
            $table->string('vital_electronic_records')->nullable();
            $table->string('alternative_workaround_during_system_failure')->nullable();
            $table->string('key_individuals_process_depends_on')->nullable();
            $table->string('peak_periods')->nullable();
            $table->string('remote_working')->nullable();
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
        Schema::dropIfExists('business_processes');
    }
};
