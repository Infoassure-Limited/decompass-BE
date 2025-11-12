<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->integer('client_id');
            $table->string('subject');
            $table->text('message')->nullable();
            $table->string('priority')->default('Low');
            $table->string('status')->default('Open');
            $table->boolean('is_resolved')->default(false);
            $table->string('assigned_to')->nullable();
            $table->timestamps();
        });
        Schema::create('ticket_responses', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->integer('client_id');
            $table->string('ticket_id');
            $table->text('message')->nullable();
            $table->timestamps();
        });
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('ticket_responses');
        Schema::dropIfExists('ticket_categories');
    }
};
