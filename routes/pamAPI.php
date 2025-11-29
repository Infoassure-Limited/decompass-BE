<?php

use App\Http\Controllers\PerfomanceAndMonitoring\InfoNeedsController;
use App\Http\Controllers\PerfomanceAndMonitoring\MeasureController;
use App\Http\Controllers\PerfomanceAndMonitoring\PerformanceMonitoringController;
// Measures (admin-level)
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'pam'], function () {
        Route::get('fetch-infoneed-categories', [InfoNeedsController::class, 'fetchInfoNeedsCategories']);
        Route::post('store-infoneed-categories', [InfoNeedsController::class, 'storeInfoNeedsCategories']);
        Route::put('update-infoneed-category/{category}', [InfoNeedsController::class, 'updateInfoNeedsCategory']);
        Route::delete('destroy-infoneed-category/{category}', [InfoNeedsController::class, 'destroyInfoNeedsCategory']);

        Route::get('fetch-infoneeds', [InfoNeedsController::class, 'fetchInfoNeeds']);
        Route::post('store-infoneeds', [InfoNeedsController::class, 'storeInfoNeeds']);
        Route::put('update-infoneed/{infoNeed}', [InfoNeedsController::class, 'updateInfoNeed']);
        Route::delete('destroy-infoneed/{infoNeed}', [InfoNeedsController::class, 'destroyInfoNeed']);

        Route::get('measures', [MeasureController::class, 'index']);
        Route::post('measures/store', [MeasureController::class, 'store']);
        Route::put('measures/update/{measure}', [MeasureController::class, 'update']);
        Route::delete('measures/destroy/{measure}', [MeasureController::class, 'destroy']);

        // Assign measures to organization
        Route::post('organizations/assign-measures', [PerformanceMonitoringController::class, 'assignMeasures']);
        Route::get('organizations/{org}/measures', [PerformanceMonitoringController::class, 'getOrgMeasures']);
    });
});