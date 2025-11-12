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
        Schema::create('package_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->double('amount');
            $table->double('discount')->nullable();
            $table->double('total')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('package_subscription_details', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('subscription_id');
            $table->integer('module_package_id');
            $table->double('amount')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('package_subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->integer('subscription_id');
            $table->double('amount')->nullable();
            $table->string('txn_ref')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_subscriptions');
        Schema::dropIfExists('package_subscription_details');
        Schema::dropIfExists('package_subscription_payments');
    }
};
