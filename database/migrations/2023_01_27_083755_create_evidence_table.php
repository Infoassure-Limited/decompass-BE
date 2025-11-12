<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvidenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evidence', function (Blueprint $table) {
            $table->id();
            $table->integer('consulting_id');
            $table->string('title');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('client_evidence', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('evidence_id');
            $table->integer('clause_id');
            $table->string('evidence_title')->nullable();
            $table->string('link')->nullable();
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
        Schema::dropIfExists('evidence');
        Schema::dropIfExists('client_evidence');
    }
}
