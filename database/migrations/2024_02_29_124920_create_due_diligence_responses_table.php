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
        Schema::create('due_diligence_responses', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('due_diligence_question_id');
            $table->string('answer')->nullable();
            $table->text('detailed_explanation')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('due_diligence_responses');
    }
};
