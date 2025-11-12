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
        Schema::create('email_lists', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->timestamps();
        });
        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('message');
            $table->string('sender');
            $table->text('recipients');
            $table->text('read_by')->nullable();
            $table->string('recipient_delete')->nullable();
            $table->timestamps();
        });
        Schema::create('email_replies', function (Blueprint $table) {
            $table->id();
            $table->integer('email_message_id');
            $table->text('message');
            $table->string('sender');
            $table->text('read_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_lists');
        Schema::dropIfExists('email_messages');
        Schema::dropIfExists('email_replies');
    }
};
