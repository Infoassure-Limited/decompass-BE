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
        Schema::create('review_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('agenda')->nullable();
            $table->dateTime('scheduled_at');
            $table->dateTime('ended_at')->nullable();
            $table->integer('duration_minutes')->default(60);
            $table->string('location')->nullable();
            $table->string('meeting_link')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled, postponed
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('meeting_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_meeting_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('role')->nullable();
            $table->boolean('is_external')->default(false);
            $table->boolean('confirmed')->default(false);
            $table->timestamps();
        });
        Schema::create('meeting_action_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_meeting_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->date('due_date')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, completed
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_meetings');
        Schema::dropIfExists('meeting_attendees');
        Schema::dropIfExists('meeting_action_items');
    }
};
