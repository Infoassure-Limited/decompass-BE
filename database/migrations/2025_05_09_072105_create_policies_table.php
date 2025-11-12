<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('policy_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('document_number')->unique();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('owner_id');
            $table->enum('status', ['draft', 'review', 'approved', 'published', 'archived'])->default('draft');
            $table->date('published_at')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('review_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('policy_categories');
        });
        Schema::create('policy_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('policy_id');
            $table->string('version_number');
            $table->text('content');
            $table->text('change_summary')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('policy_id')->references('id')->on('policies')->onDelete('cascade');
        });
        Schema::create('policy_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('policy_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();

            $table->foreign('policy_id')->references('id')->on('policies')->onDelete('cascade');

            $table->unique(['policy_id', 'user_id']);
        });
        Schema::create('policy_audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('policy_id');
            $table->unsignedBigInteger('user_id');
            $table->string('action'); // created, updated, published, archived, etc.
            $table->text('details')->nullable();
            $table->timestamps();

            $table->foreign('policy_id')->references('id')->on('policies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('policy_categories');
        Schema::dropIfExists('policies');
        Schema::dropIfExists('policy_versions');
        Schema::dropIfExists('policy_user');
        Schema::dropIfExists('policy_audits');
    }
};
