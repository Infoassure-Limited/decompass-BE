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
        Schema::create('assigned_task_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('module_activity_task_id');
            $table->text('comment')->nullable();
            $table->integer('comment_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_task_comments');
    }
};
