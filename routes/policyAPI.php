<?php

use App\Http\Controllers\Policy\PolicyController;
use App\Http\Controllers\Policy\PolicyCategoryController;
use App\Http\Controllers\Policy\PolicyDashboardController;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'policy'], function () {
        Route::group(['prefix' => 'policy-categories'], function () {
            Route::get('/', [PolicyCategoryController::class, 'index']);
            Route::get('show/{category}', [PolicyCategoryController::class, 'show']);
            Route::post('store', [PolicyCategoryController::class, 'store']);
            Route::put('update/{category}', [PolicyCategoryController::class, 'update']);
            Route::delete('destroy/{category}', [PolicyCategoryController::class, 'destroy']);
        });

        Route::group(['prefix' => 'policies'], function () {
            Route::get('/', [PolicyController::class, 'index']);
            Route::get('show/{policy}', [PolicyController::class, 'show']);
            Route::post('store', [PolicyController::class, 'store']);
            Route::put('update/{policy}', [PolicyController::class, 'update']);
            Route::delete('destroy/{policy}', [PolicyController::class, 'destroy']);
        });

        // Policy workflow actions
        Route::put('policies/submit-for-review/{policy}', [PolicyController::class, 'submitForReview']);
        Route::put('policies/approve/{policy}', [PolicyController::class, 'approve']);
        Route::put('policies/publish/{policy}', [PolicyController::class, 'publish']);
        Route::put('policies/archive/{policy}', [PolicyController::class, 'archive']);
        Route::put('policies/update-fields/{policy}', [PolicyController::class, 'updateFields']);


        // Policy versions
        Route::get('policies/versions/{policy}', [PolicyController::class, 'versions']);

        // Audit trail
        Route::get('policies/audit-trail/{policy}', [PolicyController::class, 'auditTrail']);

        // User acknowledgments
        Route::post('policies/mark-as-read/{policy}', [PolicyController::class, 'markAsRead']);
        Route::post('policies/acknowledge/{policy}', [PolicyController::class, 'acknowledge']);
        Route::get('policies/acknowledgements/{policy}', [PolicyController::class, 'acknowledgements']);
        Route::get('policies/compliance-summary/{policy}', [PolicyController::class, 'complianceSummary']);

        // Dashboard
        Route::get('dashboard', [PolicyDashboardController::class, 'index']);
    });
});