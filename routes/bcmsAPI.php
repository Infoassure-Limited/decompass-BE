<?php

use App\Http\Controllers\BCMS\BIAController;
use App\Http\Controllers\BCMS\CalendarController;
use App\Http\Controllers\BCMS\ReportsController;
use App\Http\Controllers\BCMS\TaskEvidenceUploadController;
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'bcms'], function () {
        Route::group(['prefix' => 'bia'], function () {

            Route::get('fetch-bia', [BIAController::class, 'fetchBIA']);
            Route::put('update-bia/{bia}', [BIAController::class, 'updateBIA']);

            Route::post('store', [BIAController::class, 'store']);
            Route::put('update-disruption-impact/{impact}', [BIAController::class, 'updateDisruptionImpact']);

            Route::get('fetch-risk-assessment', [BIAController::class, 'fetchRiskAssessments']);
            Route::post('store-risk-assessment', [BIAController::class, 'storeRiskAssessment']);
            Route::put('update-risk-assessment-field/{assessment}', [BIAController::class, 'updateRiskAssessmentFields']);
            Route::get('risk-assessment-summary', [BIAController::class, 'riskAssessmentSummary']);


        });
        Route::group(['prefix' => 'reports'], function () {
            Route::get('compliance-status', [ReportsController::class, 'complianceStatus']);

            Route::get('client-dashboard-statistics', [ReportsController::class, 'clientDashboardStatistics']);
            Route::get('client-data-analysis-dashboard', [ReportsController::class, 'clientDataAnalysisDashbord']);
            Route::get('client-project-data-analysis', [ReportsController::class, 'clientProjectDataAnalysis']);
            Route::get('admin-data-analysis-dashboard', [ReportsController::class, 'adminDataAnalysisDashbord']);
            Route::get('partner-data-analysis-dashboard', [ReportsController::class, 'partnerDataAnalysisDashbord']);

            Route::get('clause-report', [ReportsController::class, 'clientProjectManagementClauseReport']);
            Route::get('completion-report', [ReportsController::class, 'clientProjectRequirementCompletionReport']);
            Route::get('summary-report', [ReportsController::class, 'clientProjectAssessmentSummaryReport']);
            Route::get('soa-summary', [ReportsController::class, 'soaSummary']);
            Route::get('risk-assessment-summary', [ReportsController::class, 'riskAssessmentSummary']);
            Route::get('fetch-project-answers', [ReportsController::class, 'fetchProjectAnswers']);
            Route::get('asset-risk-analysis', [ReportsController::class, 'assetRiskAnalysis']);
            Route::get('process-risk-analysis', [ReportsController::class, 'processRiskAnalysis']);
            Route::get('bia-data-analysis', [ReportsController::class, 'dataAnalysisBIA']);



        });

        Route::group(['prefix' => 'calendar'], function () {
            // Incident Types

            Route::get('fetch-all-tasks', [CalendarController::class, 'fetchAllTasks']);
            Route::get('show-task/{task}', [CalendarController::class, 'showTask']);
            Route::get('fetch-task-logs', [CalendarController::class, 'fetchTaskLogs']);

            Route::get('fetch-task-by-clause', [CalendarController::class, 'fetchModuleTaskByClause']);
            Route::get('fetch-client-assigned-tasks', [CalendarController::class, 'fetchClientAssignedTasks']);
            Route::get('fetch-user-assigned-tasks', [CalendarController::class, 'fetchUserAssignedTasks']);

            Route::post('store-clause-activities', [CalendarController::class, 'storeClauseActivities']);
            Route::put('update-clause-activity/{moduleActivity}', [CalendarController::class, 'updateClauseActivity']);
            Route::post('store-clause-activity-tasks', [CalendarController::class, 'storeClauseActivityTasks']);
            Route::put('update-clause-activity-task/{moduleActivityTask}', [CalendarController::class, 'updateClauseActivityTask']);
            Route::post('assign-task-to-user', [CalendarController::class, 'assignTaskToUser']);
            Route::get('fetch-my-calendar-data', [CalendarController::class, 'fetchMyCalendarData']);
            Route::get('fetch-project-calendar-data', [CalendarController::class, 'fetchProjectCalendarData']);

            Route::put('mark-task-as-done/{task}', [CalendarController::class, 'markTaskAsDone']);
            Route::put('mark-task-as-completed/{taskLog}', [CalendarController::class, 'markTaskAsCompleted']);

            Route::get('set-expected-uploads', [CalendarController::class, 'setExpectedUploadsFromAssignedTasks']);
            Route::put('save-assigned-task-note/{taskLog}', [CalendarController::class, 'saveAssignedTaskNote']);
            Route::delete('undo-task-assignment/{assignedTask}', [CalendarController::class, 'undoTaskAssignment']);


            Route::group(['prefix' => 'comments'], function () {
                // Incident Types

                Route::get('fetch-task-comments', [CalendarController::class, 'fetchTaskComments']);
                Route::post('post-task-comment', [CalendarController::class, 'postTaskcomment']);
                Route::put('update-task-comment/{comment}', [CalendarController::class, 'updateTaskComment']);
                Route::delete('delete-comment/{comment}', [CalendarController::class, 'deleteComment']);


            });
        });
        Route::group(['prefix' => 'uploads'], function () {
            Route::get('fetch-uploads', [TaskEvidenceUploadController::class, 'fetchUploads']);
            Route::post('fetch-uploaded-document-with-template-ids', [TaskEvidenceUploadController::class, 'fetchUploadedDocumentWithTemplateIds']);
            Route::post('save', [TaskEvidenceUploadController::class, 'createUploads']);
            Route::post('upload-file', [TaskEvidenceUploadController::class, 'uploadEvidenceFile']);
            Route::delete('destroy-template/{template}', [TaskEvidenceUploadController::class, 'destroyTemplate']);
            Route::put('remark-on-upload/{upload}', [TaskEvidenceUploadController::class, 'remarkOnUpload']);

        });
    });
});