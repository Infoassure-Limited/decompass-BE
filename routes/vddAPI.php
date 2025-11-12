<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\VendorDueDiligence\AppMailingsController;
use App\Http\Controllers\VendorDueDiligence\AuditQuestionController;
use App\Http\Controllers\VendorDueDiligence\AuditRiskAssessmentController;
use App\Http\Controllers\VendorDueDiligence\AuditTemplateController;
use App\Http\Controllers\VendorDueDiligence\AuthController;
use App\Http\Controllers\VendorDueDiligence\ContractsAndSLAController;
use App\Http\Controllers\VendorDueDiligence\DueDiligenceQuestionsController;
use App\Http\Controllers\VendorDueDiligence\DueDiligenceReportsController;
use App\Http\Controllers\VendorDueDiligence\DueDiligenceResponsesController;
use App\Http\Controllers\VendorDueDiligence\InvoicesController;
use App\Http\Controllers\VendorDueDiligence\MeetingActionItemController;
use App\Http\Controllers\VendorDueDiligence\MeetingAttendeeController;
use App\Http\Controllers\VendorDueDiligence\RemediationPlanController;
use App\Http\Controllers\VendorDueDiligence\ReportsController;
use App\Http\Controllers\VendorDueDiligence\ReviewMeetingController;
use App\Http\Controllers\VendorDueDiligence\RiskAssessmentsController;
use App\Http\Controllers\VendorDueDiligence\TicketsController;
use App\Http\Controllers\VendorDueDiligence\VendorAuditsController;
use App\Http\Controllers\VendorDueDiligence\VendorsController;

// Route::group(['prefix' => 'vdd/auth'], function () {
//     Route::post('login', [AuthController::class, 'login']);

// });



Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'vdd'], function () {

        //VENDORS ENDPOINTS///
        Route::post('pdf-to-text', [ContractsAndSLAController::class, 'pdfToText']);
        Route::get('search-email-list', [Controller::class, 'searchEmailList']);
        Route::get('show-vendor/{vendor}', [VendorsController::class, 'showVendor']);
        Route::post('update-vendor', [VendorsController::class, 'updateVendor']);
        Route::delete('delete-uploaded-document/{document}', [VendorsController::class, 'deleteUploadedDocument']);

        Route::group(['prefix' => 'answers'], function () {
            Route::get('fetch', [DueDiligenceResponsesController::class, 'fetchResponses']);
            Route::put('update/{answer}', [DueDiligenceResponsesController::class, 'update']);
            // Route::delete('destroy/{answer}', [DueDiligenceResponsesController::class, 'destroy']);

            Route::post('submit', [DueDiligenceResponsesController::class, 'submitDueDiligenceResponses']);

            Route::post('upload-due-diligence-evidence', [DueDiligenceResponsesController::class, 'uploadDueDiligenceEvidence']);
            Route::delete('destroy-evidence/{evidence}', [DueDiligenceResponsesController::class, 'destroyDueDiligenceEvidence']);
        });

        Route::group(['prefix' => 'messages'], function () {

            Route::get('/', [AppMailingsController::class, 'inbox']);
            Route::get('/inbox', [AppMailingsController::class, 'inbox']);
            Route::get('/sent', [AppMailingsController::class, 'sent']);
            Route::post('send-message', [AppMailingsController::class, 'compose']);
            Route::delete('delete/{message}', [AppMailingsController::class, 'delete']);
            Route::put('reply/{message}', [AppMailingsController::class, 'reply']);
            Route::get('/details/{message}', [AppMailingsController::class, 'messageDetails']);
        });

        Route::group(['prefix' => 'invoices'], function () {

            Route::get('/', [InvoicesController::class, 'index']);
            Route::post('store', [InvoicesController::class, 'store']);
            Route::post('upload-invoice', [InvoicesController::class, 'uploadInvoice']);

            Route::put('update/{invoice}', [InvoicesController::class, 'update']);
            Route::delete('destroy/{invoice}', [InvoicesController::class, 'destroy']);
            Route::delete('destroy-invoice-item/{invoice_item}', [InvoicesController::class, 'destroyInvoiceItem']);

            Route::put('confirm-payment/{invoice}', [InvoicesController::class, 'confirmPayment']);

        });
        Route::group(['prefix' => 'vendor-reports'], function () {
            Route::get('vendor-invoices-analysis', [ReportsController::class, 'vendorInvoicesAnalysis']);
            Route::get('vendor-tickets-analysis', [ReportsController::class, 'vendorTicketsAnalysis']);

        });
        Route::group(['prefix' => 'vendor-contracts'], function () {

            Route::get('fetch', [ContractsAndSLAController::class, 'fetchContracts']);
            Route::get('show-sla/{sla}', [ContractsAndSLAController::class, 'showSLA']);
            Route::post('upload-contract', [ContractsAndSLAController::class, 'uploadContract']);
            Route::post('save-sla', [ContractsAndSLAController::class, 'saveSLAConfig']);
            Route::delete('destroy-metrics/{metrics}', [ContractsAndSLAController::class, 'destroyMetrics']);

        });

        Route::group(['prefix' => 'vendor-tickets'], function () {

            Route::get('/', [TicketsController::class, 'index']);
            Route::get('show/{ticket}', [TicketsController::class, 'show']);
            Route::post('store', [TicketsController::class, 'store']);
            Route::post('save-response', [TicketsController::class, 'saveTicketResponse']);
            Route::put('update/{ticket}', [TicketsController::class, 'updateField']);


        });

        Route::group(['prefix' => 'vendor-review-meetings'], function () {

            Route::apiResource('/', ReviewMeetingController::class);
            Route::put('update-status/{reviewMeeting}', [ReviewMeetingController::class, 'updateStatus']);
            Route::get('get-vendor-meetings/{vendor}', [ReviewMeetingController::class, 'getVendorMeetings']);

            // Meeting Attendee Routes
            Route::apiResource('attendees', MeetingAttendeeController::class);
            Route::put('review-meetings/{reviewMeeting}/attendees/{attendee}/confirmation', [MeetingAttendeeController::class, 'updateConfirmation']);

            // Meeting Action Item Routes
            Route::apiResource('action-items', MeetingActionItemController::class);
            Route::put('review-meetings/{reviewMeeting}/action-items/{actionItem}/status', [MeetingActionItemController::class, 'updateStatus']);

        });
        //VENDORS ENDPOINTS ENDS////


        ///CLIENT ENDPOINTS////

        Route::get('vendor-invoices', [InvoicesController::class, 'index']);
        Route::put('invoices/make-payment/{invoice}', [InvoicesController::class, 'makePayment']);
        Route::post('invoices/upload-payment-evidence', [InvoicesController::class, 'uploadPaymentEvidence']);
        Route::put('approve-invoice/{invoice}', [InvoicesController::class, 'approvalAction']);

        Route::get('fetch-vendors', [VendorsController::class, 'index']);
        Route::delete('delete-vendor-user/{user}', [VendorsController::class, 'deleteVendorUser']);

        Route::get('fetch-vendor-categories', [VendorsController::class, 'fetchVendorCategories']);
        Route::post('save-vendor-category', [VendorsController::class, 'saveVendorCategory']);
        Route::put('update-vendor-category/{category}', [VendorsController::class, 'updateVendorCategory']);
        Route::delete('delete-vendor-category/{category}', [VendorsController::class, 'deleteVendorCategory']);


        Route::put('assign-users-as-vendor-admin/{vendor}', [VendorsController::class, 'assignUserAsVendorAdmin']);

        Route::get('fetch-approved-vendors', [VendorsController::class, 'fetchApprovedVendors']);
        Route::get('fetch-client-users', [VendorsController::class, 'fetchClientUsers']);

        Route::post('register-vendor', [VendorsController::class, 'store']);
        Route::post('register-vendor-user', [VendorsController::class, 'registerVendorUser']);
        Route::put('update-vendor-user/{user}', [VendorsController::class, 'updateVendorUser']);
        Route::put('send-login-credentials/{user}', [VendorsController::class, 'sendLoginCredentials']);
        Route::put('approve-vendor/{vendor}', [VendorsController::class, 'approvalAction']);
        Route::put('categorize-vendor/{vendor}', [VendorsController::class, 'categorizeVendor']);


        Route::group(['prefix' => 'questions'], function () {
            Route::get('/', [DueDiligenceQuestionsController::class, 'index']);
            Route::get('fetch-default-questions', [DueDiligenceQuestionsController::class, 'fetchDefaultQuestions']);
            Route::post('upload-default-questions', [DueDiligenceQuestionsController::class, 'uploadBulkDefaultQuestions']);
            Route::post('save-default-question', [DueDiligenceQuestionsController::class, 'saveDefaultQuestion']);
            Route::put('update-default-question/{question}', [DueDiligenceQuestionsController::class, 'updateDefaultQuestion']);
            Route::delete('destroy-default-question/{question}', [DueDiligenceQuestionsController::class, 'destroyDefaultQuestion']);


            Route::post('save-imported-questions', [DueDiligenceQuestionsController::class, 'saveImportedQuestions']);

            Route::post('save', [DueDiligenceQuestionsController::class, 'saveQuestions']);
            Route::put('update/{question}', [DueDiligenceQuestionsController::class, 'update']);
            Route::delete('destroy/{question}', [DueDiligenceQuestionsController::class, 'destroy']);

        });
        Route::group(['prefix' => 'responses'], function () {
            Route::get('fetch', [DueDiligenceResponsesController::class, 'fetchResponses']);

            Route::post('store', [DueDiligenceResponsesController::class, 'store']);
            Route::put('update/{answer}', [DueDiligenceResponsesController::class, 'update']);

            Route::post('enable-modification', [DueDiligenceResponsesController::class, 'enableModification']);
            Route::post('change-status', [DueDiligenceResponsesController::class, 'changeStatus']);

        });
        Route::group(['prefix' => 'reports'], function () {
            Route::get('fetch', [DueDiligenceReportsController::class, 'index']);
            Route::get('vendor-onboarding-count', [ReportsController::class, 'vendorOnboardingCount']);
            Route::get('vendor-onboarding-report', [ReportsController::class, 'vendorOnboardingReport']);
            Route::get('vendor-invoices-analysis', [ReportsController::class, 'vendorInvoicesAnalysis']);
            Route::get('vendor-contracts-analysis', [ReportsController::class, 'vendorContractsAnalysis']);
            Route::get('vendor-tickets-analysis', [ReportsController::class, 'vendorTicketsAnalysis']);
            Route::get('vendor-risk-assessment-analysis', [ReportsController::class, 'vendorRiskAssessmentAnalysis']);
            Route::get('enterprise-risk-score', [ReportsController::class, 'calculateEnterpriseRiskScore']);


        });

        Route::group(['prefix' => 'client-contracts'], function () {

            Route::get('fetch', [ContractsAndSLAController::class, 'fetchContracts']);
            Route::get('show-sla/{sla}', [ContractsAndSLAController::class, 'showSLA']);
            Route::post('save-performance-score', [ContractsAndSLAController::class, 'saveVendorPerformanceScore']);
            Route::post('upload-contract', [ContractsAndSLAController::class, 'uploadContract']);
            // Route::post('save-sla', [ContractsAndSLAController::class, 'saveSLAConfig']);
            Route::put('renewal/{contract}', [ContractsAndSLAController::class, 'contractRenewal']);
            Route::put('update-kpi-scores/{score}', [ContractsAndSLAController::class, 'updateKPIScores']);


        });

        Route::group(['prefix' => 'risk-assessment'], function () {

            // Route::post('save-risk', [RiskAssessmentsController::class, 'saveRisk']);
            Route::put('update-risk/{risk}', [RiskAssessmentsController::class, 'updateRisk']);

            Route::post('save-categories', [RiskAssessmentsController::class, 'saveCategories']);
            Route::put('update-category/{riskCategory}', [RiskAssessmentsController::class, 'updateCategory']);

            // Route::post('save-likelihoods', [RiskAssessmentsController::class, 'saveLikelihoods']);



            Route::delete('delete-impact/{value}', [RiskAssessmentsController::class, 'deleteImpact']);
            Route::delete('delete-category{value}', [RiskAssessmentsController::class, 'deleteCategory']);
            Route::delete('delete-likelihood/{value}', [RiskAssessmentsController::class, 'deleteLikelihood']);


            Route::post('store-risk-assessment', [RiskAssessmentsController::class, 'store']);

            Route::put('update-fields/{riskAssessment}', [RiskAssessmentsController::class, 'updateRiskAssessmentFields']);
            Route::put('update-risk-fields/{risk}', [RiskAssessmentsController::class, 'updateRiskFields']);
            Route::get('details/{riskAssessment}', [RiskAssessmentsController::class, 'show']);

            Route::put('save-risk-assessment-treatment-details/{riskAssessment}', [RiskAssessmentsController::class, 'saveRiskAssessmentTreatmentDetails']);

        });

        Route::group(['prefix' => 'client-tickets'], function () {

            Route::get('/', [TicketsController::class, 'index']);
            Route::get('show/{ticket}', [TicketsController::class, 'show']);
            Route::post('store', [TicketsController::class, 'store']);
            Route::post('save-response', [TicketsController::class, 'saveTicketResponse']);
            Route::put('update/{ticket}', [TicketsController::class, 'updateField']);

        });

        Route::group(['prefix' => 'client-review-meetings'], function () {
            // Route::apiResource('/', ReviewMeetingController::class);
            Route::get('/', [ReviewMeetingController::class, 'index']);
            Route::post('store', [ReviewMeetingController::class, 'store']);
            Route::put('update/{reviewMeeting}', [ReviewMeetingController::class, 'update']);
            Route::put('update-status/{reviewMeeting}', [ReviewMeetingController::class, 'updateStatus']);
            Route::get('get-vendor-meetings/{vendor}', [ReviewMeetingController::class, 'getVendorMeetings']);
            Route::delete('destroy/{reviewMeeting}', [ReviewMeetingController::class, 'destroy']);

            // Meeting Attendee Routes
            Route::apiResource('attendees', MeetingAttendeeController::class);
            Route::put('review-meetings/{reviewMeeting}/attendees/{attendee}/confirmation', [MeetingAttendeeController::class, 'updateConfirmation']);

            // Meeting Action Item Routes
            Route::apiResource('action-items', MeetingActionItemController::class);
            Route::put('review-meetings/{reviewMeeting}/action-items/{actionItem}/status', [MeetingActionItemController::class, 'updateStatus']);

        });

        Route::group(['prefix' => 'client-vendor-audit'], function () {
            // Route::apiResource('vendors', VendorController::class);
            // Route::get('vendors/{id}/audits', [VendorController::class, 'getAudits']);
            // Route::get('vendors/{id}/risk-assessments', [VendorController::class, 'getRiskAssessments']);

            // Audit Templates
            // Route::apiResource('audit-templates', AuditTemplateController::class);

            // // Audit Questions
            // Route::apiResource('audit-questions', AuditQuestionController::class);
            // Route::post('audit-questions/reorder', [AuditQuestionController::class, 'reorder']);

            // // Vendor Audits
            // Route::apiResource('vendor-audits', VendorAuditsController::class);
            // Route::post('vendor-audits/{id}/submit-responses', [VendorAuditsController::class, 'submitResponses']);
            // Route::post('vendor-audits/{id}/complete', [VendorAuditsController::class, 'complete']);
            // Route::get('vendors/{id}/audits', [VendorAuditsController::class, 'getAudits']);
            // Route::get('vendors/{id}/risk-assessments', [VendorAuditsController::class, 'getRiskAssessments']);

            // // Risk Assessments
            // Route::apiResource('risk-assessments', AuditRiskAssessmentController::class);
            // Route::post('risk-assessments/{id}/remediation-plan', [AuditRiskAssessmentController::class, 'addRemediationPlan']);

            // // Remediation Plans
            // Route::apiResource('remediation-plans', RemediationPlanController::class);
            // Route::post('remediation-plans/{id}/complete', [RemediationPlanController::class, 'markAsComplete']);

            // Dashboard
            // Route::get('dashboard/overview', [DashboardController::class, 'getOverview']);
            // Route::get('dashboard/risk-summary', [DashboardController::class, 'getRiskSummary']);
            // Route::get('dashboard/audit-summary', [DashboardController::class, 'getAuditSummary']);

        });

        ///CLIENT ENDPOINTS////

    });

});