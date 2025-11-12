<?php

use App\Http\Controllers\AnswersController;
use App\Http\Controllers\AppMailingsController;
use App\Http\Controllers\AssetsController;
use App\Http\Controllers\BulkUploadFileController;
use App\Http\Controllers\DPIAController;
use App\Http\Controllers\GeneralRiskLibrariesController;
use App\Http\Controllers\PDAController;
use App\Http\Controllers\RCSAController;
use App\Http\Controllers\RiskRegistersController;
use App\Http\Controllers\RoPAController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UploadsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BIAController;
use App\Http\Controllers\BusinessUnitsController;
use App\Http\Controllers\ClausesController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ConsultingsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\DueDiligenceQuestionsController;
use App\Http\Controllers\DueDiligenceResponsesController;
use App\Http\Controllers\EvidenceController;
use App\Http\Controllers\FormFieldsController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ProjectPlanController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RiskAssessmentsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SOAController;
use App\Http\Controllers\StandardsController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('set-admin-role', [Controller::class, 'setAdminRole']);
Route::get('countries', [Controller::class, 'fetchCountries']);
Route::get('fetch-available-modules', [Controller::class, 'fetchAvailableModules']);

Route::get('generate-captcha', [Controller::class, 'fetchCaptcha']);
Route::post('spreadsheet/export-excel', [DocumentsController::class, 'exportExcel']);


// Route::get('clause-report', [ReportsController::class, 'clientProjectManagementClauseReport']);
// Route::get('completion-report', [ReportsController::class, 'clientProjectRequirementCompletionReport']);
// Route::get('summary-report', [ReportsController::class, 'clientProjectAssessmentSummaryReport']);
Route::group(['prefix' => 'auth'], function () {
    Route::post('register-client', [ClientsController::class, 'registerClient']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('confirm-registration', [AuthController::class, 'confirmRegistration']);
    Route::post('recover-password', [AuthController::class, 'recoverPassword']);
    Route::get('confirm-password-reset-token/{token}', [AuthController::class, 'confirmPasswordResetToken']);

    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('other-user-login', [AuthController::class, 'otherUserLogin']);
    Route::put('sent-2fa-code/{user}', [AuthController::class, 'send2FACode']);
    Route::put('confirm-2fa-code/{user}', [AuthController::class, 'confirm2FACode']);

    // Route::post('register', [AuthController::class, 'register'])->middleware('permission:create-users');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('login-as', [AuthController::class, 'loginAs']);
        Route::get('user', [AuthController::class, 'fetchUser']); //->middleware('permission:read-users');
    });
});


//////////////////////////////// APP APIS //////////////////////////////////////////////
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'risk-matrix'], function () {

        Route::get('fetch-risk-matrix', [RiskRegistersController::class, 'fetchRiskMatrix']);
    });
    Route::group(['prefix' => 'risk-assessment'], function () {
        Route::get('fetch-risks', [RiskAssessmentsController::class, 'fetchRisks']);
        Route::get('fetch-impacts', [RiskAssessmentsController::class, 'fetchImpacts']);
        Route::get('fetch-categories', [RiskAssessmentsController::class, 'fetchCategories']);
        Route::post('generate-risk-categories', [RiskAssessmentsController::class, 'generateRiskCategories']);

        Route::get('fetch-likelihoods', [RiskAssessmentsController::class, 'fetchLikelihoods']);
    });
    Route::group(['prefix' => 'business-units'], function () {

        Route::get('fetch-other-users', [BusinessUnitsController::class, 'fetchOtherUsers']);
        Route::post('save-other-users', [BusinessUnitsController::class, 'saveOtherUser']);
        Route::put('update-other-users/{user}', [BusinessUnitsController::class, 'updateOtherUser']);

        Route::get('fetch-business-units', [BusinessUnitsController::class, 'fetchBusinessUnits']);

        Route::get('fetch-business-processes', [BusinessUnitsController::class, 'fetchBusinessProcesses']);

        Route::post('save-business-units', [BusinessUnitsController::class, 'saveBusinessUnits']);
        Route::post('save-business-processes', [BusinessUnitsController::class, 'saveBusinessProcesses']);

        Route::put('update-business-unit/{unit}', [BusinessUnitsController::class, 'updateBusinessUnit']);
        Route::put('update-business-process/{process}', [BusinessUnitsController::class, 'updateBusinessProcess']);
        Route::put('refresh-access-code/{business_unit}', [BusinessUnitsController::class, 'refreshAccessCode']);

        Route::get('get-bia-time-recovery-requirement', [BusinessUnitsController::class, 'getBiaTimeRecoveryRequirement']);
        Route::post('save-bia-time-recovery-requirement', [BusinessUnitsController::class, 'saveBiaTimeRecoveryRequirement']);
        Route::put('update-bia-time-recovery-requirement/{criteria}', [BusinessUnitsController::class, 'updateBiaTimeRecoveryRequirement']);
        Route::delete('delete-bia-time-recovery-requirement/{criteria}', [BusinessUnitsController::class, 'deleteBiaTimeRecoveryRequirement']);

        Route::post('upload-process-flow', [BusinessUnitsController::class, 'uploadProcessFlow']);
        Route::put('change-process-status/{process}', [BusinessUnitsController::class, 'changeProcessStatus']);
        Route::delete('destroy/{business_unit}', [BusinessUnitsController::class, 'deleteBusinessUnit']);


    });
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

    Route::post('become-a-client', [ClientsController::class, 'becomeAClient']);

    Route::get('search-email-list', [Controller::class, 'searchEmailList']);

    Route::get('fetch-client-activated-projects', [ProjectsController::class, 'fetchClientActivatedProjects']);

    Route::group(['prefix' => 'messages'], function () {

        Route::get('/', [AppMailingsController::class, 'inbox']);
        Route::get('/inbox', [AppMailingsController::class, 'inbox']);
        Route::get('/sent', [AppMailingsController::class, 'sent']);
        Route::post('send-message', [AppMailingsController::class, 'compose']);
        Route::delete('delete/{message}', [AppMailingsController::class, 'delete']);
        Route::put('reply/{message}', [AppMailingsController::class, 'reply']);
        Route::get('/details/{message}', [AppMailingsController::class, 'messageDetails']);
    });
    Route::group(['prefix' => 'risk-library'], function () {
        Route::get('/', [GeneralRiskLibrariesController::class, 'index']);
        Route::get('fetch-threats', [GeneralRiskLibrariesController::class, 'fetchThreats']);

        Route::post('store', [GeneralRiskLibrariesController::class, 'store']);
        Route::put('update/{generalRiskLibrary}', [GeneralRiskLibrariesController::class, 'update']);
        Route::post('store-bulk', [GeneralRiskLibrariesController::class, 'storeBulk']);

        // Route::delete('destroy/{form_field}', [GeneralRiskLibrariesController::class, 'destroy']);
    });

    Route::group(['prefix' => 'rcsa'], function () {

        Route::get('fetch', [RCSAController::class, 'fetchRCSA']);
        Route::post('create-rcsa-from-rcm', [RCSAController::class, 'createRCSAFromRCM']);
        Route::put('update-fields/{rcsa}', [RCSAController::class, 'updateFields']);
        Route::post('store', [RCSAController::class, 'store']);
        Route::post('create-new-category', [RCSAController::class, 'createNewCategory']);
        Route::post('update-overall-control-rating', [RCSAController::class, 'updateOverallControlRating']);

        Route::get('fetch-risk-assessments', [RCSAController::class, 'fetchRCSARiskAssessments']);
        Route::post('store-risk-assessment', [RCSAController::class, 'storeRiskAssessment']);

        Route::put('update-risk-assessment-fields/{riskAssessment}', [RCSAController::class, 'updateRCSARiskAssessmentFields']);


        Route::get('fetch-risk-indicator-assessments', [RCSAController::class, 'fetchRiskIndicatorAssessments']);
        Route::post('save-kri-threshold', [RCSAController::class, 'saveKRIThreshold']);
        Route::put('update-risk-indicator-assessment/{assessment}', [RCSAController::class, 'updateRiskIndicatorAssessment']);
        Route::put('update-kri-assessment-value/{kriAssessment}', [RCSAController::class, 'updateKRIAssessmentValues']);
        Route::get('calculate-enterprise-risk-register', [RCSAController::class, 'calculateEnterpriseRiskScore']);


    });

    Route::get('fetch-default-risk-impact-areas', [RiskRegistersController::class, 'fetchDefaultRiskImpactArea']);
    Route::post('propose-matrix', [RiskRegistersController::class, 'proposeMatrix']);
    Route::put('approve-matrix/{riskMatrix}', [RiskRegistersController::class, 'approveMatrix']);
    Route::put('set-risk-appetite/{riskMatrix}', [RiskRegistersController::class, 'setRiskAppetite']);

    Route::get('setup-risk-matrices', [RiskRegistersController::class, 'setupRiskMatrices']);
    Route::post('customize-risk-matrix', [RiskRegistersController::class, 'customizeRiskMatrix']);

    Route::get('fetch-module-risk-registers', [RiskRegistersController::class, 'fetchModuleRiskRegisters']);
    Route::get('fetch-business-units-with-risk-registers', [RiskRegistersController::class, 'fetchBusinessUnitsWithRiskRegisters']);

    Route::get('fetch-pending-registers', [RiskRegistersController::class, 'fetchPendingRiskRegister']);
    Route::get('fetch-risk-registers', [RiskRegistersController::class, 'fetchRiskRegisters']);
    Route::post('store-risk-registers', [RiskRegistersController::class, 'storeRiskRegister']);
    Route::put('update-risk-register/{riskRegister}', [RiskRegistersController::class, 'updateRiskRegister']);
    Route::delete('delete-risk-registers/{riskRegister}', [RiskRegistersController::class, 'deleteRiskRegister']);

    Route::get('fetch-risk-impact-area', [RiskRegistersController::class, 'fetchRiskImpactArea']);
    Route::post('store-risk-impact-area', [RiskRegistersController::class, 'storeRiskImpactArea']);
    Route::put('update-risk-impact-area/{riskImpactArea}', [RiskRegistersController::class, 'updateRiskImpactArea']);
    // Route::delete('delete-risk-impact-area/{riskImpactArea}', [RiskRegistersController::class, 'deleteRiskImpactArea']);
    Route::delete('delete-risk-impact-area/{riskImpactArea}', [RiskAssessmentsController::class, 'deleteRiskImpactArea']);

    Route::get('fetch-risk-impact-on-area', [RiskRegistersController::class, 'fetchRiskImpactOnArea']);
    Route::post('store-risk-impact-on-area', [RiskRegistersController::class, 'storeRiskImpactOnArea']);
    Route::put('update-risk-impact-on-area/{riskImpactOnArea}', [RiskRegistersController::class, 'updateRiskImpactOnArea']);
    Route::delete('delete-risk-impact-on-area/{riskImpactOnArea}', [RiskRegistersController::class, 'deleteRiskImpactOnArea']);

    Route::post('generate-asset-auto-risk-registers', [RiskRegistersController::class, 'autoGenerateAndSaveAssetRiskRegisters']);
    Route::post('generate-process-auto-risk-registers', [RiskRegistersController::class, 'autoGenerateAndSaveProcessRiskRegisters']);
    Route::get('fetch-asset-risk-registers', [RiskRegistersController::class, 'fetchAssetRiskRegisters']);


    Route::get('format-doc-to-sfdt', [DocumentsController::class, 'formatDocToSFDT']);
    Route::post('save-doc-template', [DocumentsController::class, 'saveDocTemplate']);
    Route::post('save-excel-doc-template', [DocumentsController::class, 'saveExcelDocTemplate']);
    Route::post('upload-default-templates', [DocumentsController::class, 'uploadDefaultTemplates']);


    Route::get('generate-threat-intelligence', [GeneralRiskLibrariesController::class, 'generativeThreatIntelligence']);




    Route::post('save-client-copy', [DocumentsController::class, 'saveClientCopy']);
    Route::post('fetch-excel-doc', [DocumentsController::class, 'fetchExcelDocument']);
    Route::post('fetch-json-formatted-excel-doc', [DocumentsController::class, 'fetchJsonFormattedExcelDocument']);


    Route::get('user-notifications', [UsersController::class, 'userNotifications']);
    Route::get('notification/mark-as-read', [UsersController::class, 'markNotificationAsRead']);

    Route::group(['prefix' => 'users'], function () {

        Route::get('fetch-partner-users', [UsersController::class, 'fetchPartnerUsers']);
        Route::get('fetch-client-users', [UsersController::class, 'fetchClientUsers']);
        Route::get('fetch-staff', [UsersController::class, 'fetchStaff']);
        Route::post('register', [UsersController::class, 'store']);
        Route::put('update-profile/{user}', [UsersController::class, 'updateProfile']);
        Route::post('upload-photo', [UsersController::class, 'updatePhoto']);
        Route::get('show/{user}', [UsersController::class, 'show']);

    });

    Route::group(['prefix' => 'custom-fields'], function () {
        Route::get('/', [FormFieldsController::class, 'index']);
        Route::post('store', [FormFieldsController::class, 'store']);
        Route::put('update/{form_field}', [FormFieldsController::class, 'update']);
        Route::delete('destroy/{form_field}', [FormFieldsController::class, 'destroy']);
    });

    Route::group(['prefix' => 'clients'], function () {
        Route::get('/', [ClientsController::class, 'index']);
        Route::get('users', [ClientsController::class, 'fetchClientUsers']);

        Route::get('fetch-user-clients', [ClientsController::class, 'fetchUserClients']);
        Route::post('register', [ClientsController::class, 'store']);
        Route::post('register-client-user', [ClientsController::class, 'registerClientUser']);

        Route::post('update', [ClientsController::class, 'update']);
        Route::put('update-client-user/{user}', [ClientsController::class, 'updateClientUser']);
        Route::put('update-theme/{client}', [ClientsController::class, 'updateTheme']);
        Route::put('attach-client-user/{client}', [ClientsController::class, 'attachClientUser']);
        Route::delete('delete-client-user/{user}', [ClientsController::class, 'deleteClientUser']);


        // Route::delete('delete-client-user/{user}', [ClientsController::class, 'removeClientUser']);

        Route::put('send-login-credentials/{user}', [ClientsController::class, 'sendLoginCredentials']);
        Route::put('toggle-client-suspension/{client}', [ClientsController::class, 'toggleClientSuspension']);
        Route::put('assign-user-as-client-admin/{client}', [ClientsController::class, 'assignUserAsClientAdmin']);

    });
    Route::group(['prefix' => 'partners'], function () {
        Route::get('/', [PartnersController::class, 'index']);
        Route::get('fetch-user-partners', [PartnersController::class, 'fetchUserPartners']);
        Route::post('register', [PartnersController::class, 'store']);
        Route::post('register-partner-user', [PartnersController::class, 'registerPartnerUser']);

        Route::post('update', [PartnersController::class, 'update']);
        Route::put('update-partner-user/{user}', [PartnersController::class, 'updatePartnerUser']);

        Route::put('attach-partner-user/{partner}', [PartnersController::class, 'attachPartnerUser']);
        Route::put('delete-partner-user/{partner}', [PartnersController::class, 'removePartnerUser']);

        Route::put('send-login-credentials/{user}', [PartnersController::class, 'sendLoginCredentials']);
        Route::put('toggle-partner-suspension/{partner}', [PartnersController::class, 'togglePartnerSuspension']);
    });

    Route::group(['prefix' => 'projects'], function () {
        Route::get('/', [ProjectsController::class, 'index']);
        Route::get('client-projects', [ProjectsController::class, 'clientProjects']);

        Route::get('show/{project}', [ProjectsController::class, 'show']);

        Route::post('save', [ProjectsController::class, 'store']);
        Route::put('assign-to-user/{project}', [ProjectsController::class, 'assignProjectToClientStaff']);
        Route::put('unassign-user-from-project/{project}', [ProjectsController::class, 'unassignProjectFromClientStaff']);

        Route::put('set-dates/{project}', [ProjectsController::class, 'setDates']);
        Route::put('update-random-fields/{project}', [ProjectsController::class, 'updateRandomFields']);

        Route::delete('destroy/{project}', [ProjectsController::class, 'destroy']);
        Route::post('upload-certificate', [ProjectsController::class, 'uploadProjectCertificate']);
        Route::get('client-project-certificates', [ProjectsController::class, 'clientProjectCertificates']);
        Route::get('client-project-feedback', [ProjectsController::class, 'clientProjectFeedback']);
        Route::post('save-client-feedback', [ProjectsController::class, 'saveClientFeedback']);

        Route::post('assign-projects-to-consultant', [ProjectsController::class, 'assignProjectsToConsultant']);
        Route::post('unassign-project-from-consultant', [ProjectsController::class, 'unassignProjectFromConsultant']);
    });
    Route::group(['prefix' => 'project-plans'], function () {
        Route::get('/fetch-project-phases', [ProjectPlanController::class, 'fetchProjectPhases']);
        Route::get('/fetch-client-project-plan', [ProjectPlanController::class, 'fetchClientProjectPlan']);

        Route::post('/store-project-phases', [ProjectPlanController::class, 'storeProjectPhases']);
        Route::put('/update-project-phases/{project_phase}', [ProjectPlanController::class, 'updateProjectPhases']);
        Route::delete('/destroy-project-phases/{project_phase}', [ProjectPlanController::class, 'destroyProjectPhases']);

        Route::get('/fetch-gen-project-plans', [ProjectPlanController::class, 'fetchGeneralProjectPlan']);
        Route::post('/store-gen-project-plans', [ProjectPlanController::class, 'storeGeneralProjectPlans']);
        Route::put('/update-project-plan/{project_plan}', [ProjectPlanController::class, 'updateProjectPlan']);
        Route::delete('/destroy-project-plans/{project_plan}', [ProjectPlanController::class, 'destroyProjectPlan']);
        Route::put('/unlink-standard-from-project-plan/{project_plan}', [ProjectPlanController::class, 'unlinkStandardFromProjectPlan']);
        Route::put('/link-standards-to-project-plan/{project_plan}', [ProjectPlanController::class, 'linkStandardtoProjectPlan']);


        Route::post('/store-client-project-plan', [ProjectPlanController::class, 'storeClientProjectPlan']);
        Route::put('/update-client-project-plan-fields/{client_project_plan}', [ProjectPlanController::class, 'updateClientProjectPlanFields']);
    });


    Route::group(['prefix' => 'consultings'], function () {
        Route::get('/', [ConsultingsController::class, 'index']);
        Route::get('with-clauses', [ConsultingsController::class, 'fetchWithClauses']);

        Route::post('save', [ConsultingsController::class, 'store']);
        Route::put('update/{consulting}', [ConsultingsController::class, 'update']);
        Route::delete('destroy/{consulting}', [ConsultingsController::class, 'destroy']);
    });


    Route::group(['prefix' => 'standards'], function () {
        Route::get('/', [StandardsController::class, 'index']);
        Route::get('with-clauses', [StandardsController::class, 'fetchWithClauses']);

        Route::post('save', [StandardsController::class, 'store']);
        Route::put('update/{standard}', [StandardsController::class, 'update']);
        Route::delete('destroy/{standard}', [StandardsController::class, 'destroy']);
    });


    Route::group(['prefix' => 'clauses'], function () {
        Route::get('/', [ClausesController::class, 'index']);
        Route::get('fetch-clauses-with-questions', [ClausesController::class, 'fetchClausesWithQuestions']);

        Route::get('fetch-clauses-with-documents', [ClausesController::class, 'fetchClausesWithDocuments']);
        Route::post('save', [ClausesController::class, 'store']);

        Route::put('update/{clause}', [ClausesController::class, 'update']);
        Route::put('set-sort-value/{clause}', [ClausesController::class, 'setSortValue']);
        Route::delete('destroy/{clause}', [ClausesController::class, 'destroy']);
    });

    Route::group(['prefix' => 'document-templates'], function () {

        Route::get('fetch', [DocumentsController::class, 'fetchDocumentTemplates']);
        Route::post('upload', [DocumentsController::class, 'uploadDocumentTemplate']);
        Route::post('update', [DocumentsController::class, 'updateDocumentTemplate']);
        Route::delete('delete/{document}', [DocumentsController::class, 'destroy']);

        // Route::post('upload-document-template', [UploadsController::class, 'uploadDocumentTemplate']);
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
        Route::post('upload-gap-assessment-evidence', [AnswersController::class, 'uploadGapAssessmentEvidence']);
        Route::delete('destroy-gap-assessment-evidence/{gap_assessment_evidence}', [AnswersController::class, 'destroyGapAssessmentEvidenceEvidence']);
    });

    Route::group(['prefix' => 'reports'], function () {
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



    Route::group(['prefix' => 'evidence'], function () {

        Route::get('/', [EvidenceController::class, 'index']);
        Route::get('fetch-client-evidence', [EvidenceController::class, 'fetchClientEvidence']);

        Route::post('store', [EvidenceController::class, 'store']);
        Route::put('update/{evidence}', [EvidenceController::class, 'update']);
        Route::post('create-client-evidence', [EvidenceController::class, 'createClientEvidence']);
        Route::delete('destroy/{evidence}', [EvidenceController::class, 'destroy']);
        Route::delete('destroy-client-evidence/{client_evidence}', [EvidenceController::class, 'destroyClientEvidence']);
    });
    Route::group(['prefix' => 'uploads'], function () {
        Route::get('fetch-uploads', [UploadsController::class, 'fetchUploads']);
        Route::post('fetch-uploaded-document-with-template-ids', [UploadsController::class, 'fetchUploadedDocumentWithTemplateIds']);
        Route::post('save', [UploadsController::class, 'createUploads']);
        Route::post('upload-file', [UploadsController::class, 'uploadEvidenceFile']);
        Route::delete('destroy-template/{template}', [UploadsController::class, 'destroyTemplate']);
        Route::put('remark-on-upload/{upload}', [UploadsController::class, 'remarkOnUpload']);

    });
    ///////////////////////////////////RISK MANAGEMENT////////////////////////////////////////////////
    Route::group(['prefix' => 'assets'], function () {
        Route::get('fetch-asset-types', [AssetsController::class, 'fetchAssetTypes']);

        Route::get('fetch-assets', [AssetsController::class, 'fetchAssets']);

        Route::post('save-asset-types', [AssetsController::class, 'saveAssetTypes']);
        Route::post('save-assets', [AssetsController::class, 'saveAssets']);
        Route::post('upload-bulk-assets', [AssetsController::class, 'uploadBulkAssets']);


        Route::put('update-asset-type/{asset_type}', [AssetsController::class, 'updateAssetType']);
        Route::put('update-asset/{asset}', [AssetsController::class, 'updateAsset']);

        Route::put('set-asset-owner/{asset}', [AssetsController::class, 'setAssetOwner']);

        Route::delete('delete-asset-type/{assetType}', [AssetsController::class, 'deleteAssetType']);
        Route::delete('delete-asset/{asset}', [AssetsController::class, 'deleteAsset']);
    });
    ///////////////////////////////////ASSESSMENT MANAGEMENT////////////////////////////////////////////////


    ///////////////////////////////////RISK ASSESSMENT////////////////////////////////////////////////
    Route::group(['prefix' => 'risk-assessment'], function () {
        Route::get('fetch-asset-types', [RiskAssessmentsController::class, 'fetchAssetTypes']);
        Route::get('fetch-asset-types-with-asset-assessment', [RiskAssessmentsController::class, 'fetchAssetTypesWithAssetAssessments']);
        Route::get('fetch-business-units', [RiskAssessmentsController::class, 'fetchBusinessUnits']);

        Route::get('fetch-assets', [RiskAssessmentsController::class, 'fetchAssets']);


        Route::get('fetch-risk-appetite', [RiskAssessmentsController::class, 'fetchRiskAppetite']);

        Route::post('save-risk', [RiskAssessmentsController::class, 'saveRisk']);
        Route::put('update-risk/{risk}', [RiskAssessmentsController::class, 'updateRisk']);

        // Route::post('save-impacts', [RiskAssessmentsController::class, 'saveImpacts']);
        // Route::post('save-asset-types', [RiskAssessmentsController::class, 'saveAssetTypes']);
        // Route::post('save-assets', [RiskAssessmentsController::class, 'saveAssets']);
        Route::post('save-categories', [RiskAssessmentsController::class, 'saveCategories']);
        Route::put('update-category/{riskCategory}', [RiskAssessmentsController::class, 'updateCategory']);

        // Route::post('save-likelihoods', [RiskAssessmentsController::class, 'saveLikelihoods']);

        // Route::put('update-asset-type/{asset_type}', [RiskAssessmentsController::class, 'updateAssetType']);
        // Route::put('update-asset/{asset}', [RiskAssessmentsController::class, 'updateAsset']);


        Route::delete('delete-impact/{value}', [RiskAssessmentsController::class, 'deleteImpact']);
        // Route::delete('delete-asset-type/{value}', [RiskAssessmentsController::class, 'deleteAssetType']);
        Route::delete('delete-category{value}', [RiskAssessmentsController::class, 'deleteCategory']);
        Route::delete('delete-likelihood/{value}', [RiskAssessmentsController::class, 'deleteLikelihood']);


        Route::get('fetch-risk-assessments', [RiskAssessmentsController::class, 'fetchRiskAssessments']);
        Route::get('fetch-all-risk-assessments', [RiskAssessmentsController::class, 'fetchAllRiskAssessments']);

        Route::post('store-risk-assessment', [RiskAssessmentsController::class, 'store']);

        Route::put('update-fields/{riskAssessment}', [RiskAssessmentsController::class, 'updateRiskAssessmentFields']);
        Route::put('update-risk-fields/{risk}', [RiskAssessmentsController::class, 'updateRiskFields']);
        Route::get('details/{riskAssessment}', [RiskAssessmentsController::class, 'show']);

        Route::put('save-risk-assessment-treatment-details/{riskAssessment}', [RiskAssessmentsController::class, 'saveRiskAssessmentTreatmentDetails']);
        Route::put('save-residual-risk-assessment-treatment-details/{riskAssessment}', [RiskAssessmentsController::class, 'saveResidualRiskAssessmentTreatmentDetails']);

    });
    ///////////////////////////////////RISK ASSESSMENT////////////////////////////////////////////////

    ///////////////////////////////////STATEMENT OF AVAILABILITY//////////////////////////////////////
    Route::group(['prefix' => 'soa'], function () {
        Route::get('fetch-areas', [SOAController::class, 'fetchAreas']);
        Route::get('fetch-controls', [SOAController::class, 'fetchControls']);

        Route::post('save-areas', [SOAController::class, 'saveAreas']);
        Route::post('save-controls', [SOAController::class, 'saveControl']);

        Route::delete('delete-area/{value}', [SOAController::class, 'deleteArea']);
        Route::delete('delete-control{value}', [SOAController::class, 'deleteControl']);


        Route::get('fetch-soa', [SOAController::class, 'fetchSOA']);
        Route::get('details/{soa}', [SOAController::class, 'show']);
        Route::put('update-soa/{soa}', [SOAController::class, 'update']);

        Route::put('update-fields/{riskAssessment}', [SOAController::class, 'updateFields']);
    });
    ///////////////////////////STATEMENT OF AVAILABILITY/////////////////////////////////////
    Route::group(['prefix' => 'due-diligence'], function () {
        Route::group(['prefix' => 'questions'], function () {
            Route::get('/', [DueDiligenceQuestionsController::class, 'index']);
            Route::get('/fetch-questions-with-response', [DueDiligenceQuestionsController::class, 'fetchQuestionWithResponse']);

            Route::post('save', [DueDiligenceQuestionsController::class, 'store']);
            Route::post('upload-bulk', [DueDiligenceQuestionsController::class, 'uploadBulk']);

            Route::put('update/{question}', [DueDiligenceQuestionsController::class, 'update']);
            Route::delete('destroy/{question}', [DueDiligenceQuestionsController::class, 'destroy']);
        });
        Route::group(['prefix' => 'answers'], function () {
            Route::get('/fetch-responses', [DueDiligenceResponsesController::class, 'fetchResponses']);
            Route::post('save', [DueDiligenceResponsesController::class, 'store']);
            Route::put('update/{answer}', [DueDiligenceResponsesController::class, 'update']);
            // Route::delete('destroy/{answer}', [DueDiligenceResponsesController::class, 'destroy']);

            Route::post('submit', [DueDiligenceResponsesController::class, 'submitDueDiligenceResponses']);

            Route::post('upload-due-diligence-evidence', [DueDiligenceResponsesController::class, 'uploadDueDiligenceEvidence']);

            Route::delete('destroy-evidence/{evidence}', [DueDiligenceResponsesController::class, 'destroyDueDiligenceEvidence']);
        });
    });
    Route::group(['prefix' => 'pda'], function () {

        Route::get('/', [PDAController::class, 'index']);
        Route::get('fetch-personal-data-item', [PDAController::class, 'fetchPersonalDataItems']);
        Route::post('store', [PDAController::class, 'store']);


        Route::put('update/{pda}', [PDAController::class, 'update']);
        Route::delete('destroy/{pda}', [PDAController::class, 'destroy']);
    });

    Route::group(['prefix' => 'dpia'], function () {

        Route::get('/', [DPIAController::class, 'index']);
        Route::get('fetch-risk-assessments', [DPIAController::class, 'fetchRiskAssessments']);
        // Route::post('store', [DPIAController::class, 'store']);

        Route::put('update/{dpia}', [DPIAController::class, 'update']);
        // Route::delete('destroy/{dpia}', [DPIAController::class, 'destroy']);
    });
    Route::group(['prefix' => 'packages'], function () {

        Route::get('fetch-packages', [PackagesController::class, 'fetchPackages']);
        Route::post('store', [PackagesController::class, 'storePackage']);
        Route::put('update/{package}', [PackagesController::class, 'updatePackage']);

        Route::delete('destroy/{package}', [PackagesController::class, 'deletePackage']);

        Route::get('fetch-modules', [PackagesController::class, 'fetchModules']);
        Route::get('fetch-activated-modules', [PackagesController::class, 'fetchActivatedModules']);

        Route::post('activate-partners-module', [PackagesController::class, 'activatePartnersModule']);
        Route::delete('deactivate-partners-module/{activated_module}', [PackagesController::class, 'deactivatePartnersModule']);
        Route::put('activate-clients-module/{activated_module}', [PackagesController::class, 'activateClientsModule']);
        Route::put('deactivate-client-module/{activated_module}', [PackagesController::class, 'deactivateClientModule']);
    });

    Route::group(['prefix' => 'subscriptions'], function () {

        Route::get('/', [SubscriptionsController::class, 'index']);
        Route::get('fetch-subscription-details', [SubscriptionsController::class, 'fetchSubscriptionDetails']);
        Route::post('store', [SubscriptionsController::class, 'store']);
        Route::post('payment', [SubscriptionsController::class, 'paymentForSubscription']);
        Route::post('successful-payment-status', [SubscriptionsController::class, 'sucessfulPaymentStatus']);

    });
    // Access Control Roles & Permission
    Route::group(['prefix' => 'acl'], function () {
        Route::get('roles/index', [RolesController::class, 'index']);
        Route::post('roles/save', [RolesController::class, 'store']);
        Route::put('roles/update/{role}', [RolesController::class, 'update']);
        Route::post('roles/assign', [RolesController::class, 'assignRoles']);


        Route::get('permissions/index', [PermissionsController::class, 'index']);
        Route::post('permissions/assign-user', [PermissionsController::class, 'assignUserPermissions']);
        Route::post('permissions/assign-role', [PermissionsController::class, 'assignRolePermissions']);
    });

    Route::group(['prefix' => 'tasks'], function () {

        Route::get('/', [TasksController::class, 'index']);
        Route::post('store', [TasksController::class, 'store']);
        Route::put('update/{task}', [TasksController::class, 'update']);
        Route::delete('destroy/{task}', [TasksController::class, 'destroy']);


    });
    Route::group(['prefix' => 'files'], function () {

        Route::post('/upload', [BulkUploadFileController::class, 'upload']);
        Route::get('/', [BulkUploadFileController::class, 'index']);
        Route::get('show/{file}', [BulkUploadFileController::class, 'show']);
        Route::put('update/{file}', [BulkUploadFileController::class, 'update']);
        Route::get('{file}/export', [BulkUploadFileController::class, 'export']);


    });


});
