<?php

use App\Http\Controllers\NDPA\CalendarController;
use App\Http\Controllers\NDPA\ClausesController;
use App\Http\Controllers\NDPA\PDAController;
use App\Http\Controllers\NDPA\QuestionsController;
use App\Http\Controllers\NDPA\AnswersController;
use App\Http\Controllers\NDPA\ReportsController;
use App\Http\Controllers\NDPA\RoPAController;
use App\Http\Controllers\NDPA\TaskEvidenceUploadController;
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'ndpa'], function () {
        Route::group(['prefix' => 'clauses'], function () {
            Route::get('/', [ClausesController::class, 'index']);
            Route::get('fetch-clauses-with-questions', [ClausesController::class, 'fetchClausesWithQuestions']);

            Route::get('fetch-clauses-with-documents', [ClausesController::class, 'fetchClausesWithDocuments']);
            Route::post('save', [ClausesController::class, 'store']);

            Route::put('update/{clause}', [ClausesController::class, 'update']);
            Route::put('set-sort-value/{clause}', [ClausesController::class, 'setSortValue']);
            Route::delete('destroy/{clause}', [ClausesController::class, 'destroy']);

            // Route::post('uploads/save', [ClausesController::class, 'createUploads']);
            // Route::post('upload-file', [ClausesController::class, 'uploadClauseFile']);
            // Route::post('upload-document-template', [ClausesController::class, 'uploadDocumentTemplate']);
            // Route::delete('destroy-template/{template}', [ClausesController::class, 'destroyTemplate']);
            // Route::put('remark-on-upload/{upload}', [ClausesController::class, 'remarkOnUpload']);

            Route::get('fetch-clauses-sections', [ClausesController::class, 'viewClauseSections']);
            Route::post('save-section', [ClausesController::class, 'saveSection']);
            Route::put('update-section/{section}', [ClausesController::class, 'updateSection']);
            Route::delete('destroy-section/{section}', [ClausesController::class, 'destroySection']);
        });
        Route::group(['prefix' => 'questions'], function () {
            Route::get('/', [QuestionsController::class, 'index']);
            Route::post('save', [QuestionsController::class, 'store']);
            Route::post('upload-bulk', [QuestionsController::class, 'uploadBulk']);

            Route::put('update/{question}', [QuestionsController::class, 'update']);
            Route::delete('destroy/{question}', [QuestionsController::class, 'destroy']);
        });
        Route::group(['prefix' => 'answers'], function () {
            Route::get('/', [AnswersController::class, 'index']);
            Route::post('save', [AnswersController::class, 'store']);
            Route::put('update/{answer}', [AnswersController::class, 'update']);
            Route::delete('destroy/{answer}', [AnswersController::class, 'destroy']);

            Route::post('submit', [AnswersController::class, 'submitAnswers']);
            Route::put('remark-on-answer/{answer}', [AnswersController::class, 'remarkOnAnswer']);
            Route::post('assign-user-to-respond', [AnswersController::class, 'assignUserToRespond']);

            // Route::post('upload-gap-assessment-evidence', [AnswersController::class, 'uploadGapAssessmentEvidence']);
            // Route::delete('destroy-gap-assessment-evidence/{gap_assessment_evidence}', [AnswersController::class, 'destroyGapAssessmentEvidenceEvidence']);
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

        Route::group(['prefix' => 'exceptions'], function () {

            Route::get('fetch', [ClausesController::class, 'fetchExceptions']);
            Route::post('create', [ClausesController::class, 'createException']);
            Route::delete('reverse/{exception}', [ClausesController::class, 'reverseException']);
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

        Route::group(['prefix' => 'pda'], function () {

            Route::get('/', [PDAController::class, 'index']);
            Route::get('fetch-personal-data-item', [PDAController::class, 'fetchPersonalDataItems']);
            Route::post('store', [PDAController::class, 'store']);


            Route::put('update/{pda}', [PDAController::class, 'update']);
            Route::delete('destroy/{pda}', [PDAController::class, 'destroy']);
        });
        Route::group(['prefix' => 'ropa'], function () {

            Route::get('/', [RoPAController::class, 'index']);
            Route::post('store', [RoPAController::class, 'store']);

            Route::put('update/{ropa}', [RoPAController::class, 'update']);
            Route::delete('destroy/{ropa}', [RoPAController::class, 'destroy']);
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