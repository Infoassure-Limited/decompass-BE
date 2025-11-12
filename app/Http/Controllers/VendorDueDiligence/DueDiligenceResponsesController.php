<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use App\Models\AvailableModule;
use App\Models\Client;
use App\Models\Project;
use App\Models\RiskImpact;
use App\Models\RiskImpactArea;
use App\Models\RiskImpactOnArea;
use App\Models\RiskLikelihood;
use App\Models\RiskMatrix;
use App\Models\VendorDueDiligence\DueDiligenceEvidence;
use App\Models\DueDiligenceQuestion;
use App\Models\VendorDueDiligence\DueDiligenceResponse;
use App\Models\VendorDueDiligence\RiskAssessment;
use App\Models\User;
use App\Models\VendorDueDiligence\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;
use Spatie\PdfToText\Pdf;

class DueDiligenceResponsesController extends Controller
{
    protected $module;
    public function __construct(Request $httpRequest)
    {
        parent::__construct($httpRequest);
        $this->middleware(function ($request, $next) {

            $this->module = AvailableModule::where('slug', 'vdd')->first();
            return $next($request);
        });


    }
    public function fetchResponses(Request $request)
    {
        // $client_id = $request->client_id;
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $answers = DueDiligenceResponse::with('evidences', 'riskAssessment')
            ->join('due_diligence_questions', 'due_diligence_questions.id', 'due_diligence_responses.due_diligence_question_id')
            ->where(['due_diligence_responses.client_id' => $client_id, 'due_diligence_responses.vendor_id' => $vendor_id])
            ->select('*', 'due_diligence_responses.id as id')
            ->get()
            ->groupBy('domain');
        return response()->json(compact('answers'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $user = $this->getUser();
        $client_id = $request->client_id;
        $vendor_id = $request->vendor_id;
        $questions = json_decode(json_encode($request->questions));
        foreach ($questions as $question) {
            // create answer
            $data = [
                'client_id' => $client_id,
                'vendor_id' => $vendor_id,
                'due_diligence_question_id' => $question->id
            ];
            DueDiligenceResponse::firstOrCreate($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DueDiligenceResponse  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(DueDiligenceResponse $answer)
    {
        //
    }

    private function analyzeWithOpenAI($quest, $ans, $details, $evid)
    {
        //
        $message = "Please assess the following vendor due diligence response the the question below:";
        $question = "Question: ### $quest ###";
        $answer = "Response: ### $ans, $details ###";
        if ($evid != '') {
            $evidence = "Evidence: ### $evid ###";
        }
        $instruction = "
                Based on the provided information, generate:
                1. A risk score between 1 and 3 reflecting the risk associated with the response. High score means high risk.
                2. The various associated vulnerabilities based on the risk score. 
                3. A brief observation for the assigned score.
                4. A good recommendation based on the assigned risk score.
                Provide the response in a json format for easy extraction in the format below;

                score: {range of 1 to 3}
                observation: <brief justification in 30 to 50 words>
                recommendations: <good recommendation in 50 words>
                vulnerabilities: <state the vulnerabilities in 50 words>";

        $content = $message . $question . $answer . $instruction;
        if ($evid != '') {
            $content = $message . $question . $answer . $evidence . $instruction;
        }

        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $content],
            ],
        ]);

        // response is score and justification
        $ai_response = json_decode($result->choices[0]->message->content);
        return $ai_response;
        // print_r($result);
    }
    // private function fetchClientImpactsSetup($client_id)
    // {
    //     $impacts = [];
    //     $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
    //     if ($risk_matrix) {
    //         $matrix = $risk_matrix->current_matrix;
    //         $impacts = RiskImpact::orderBy('value')
    //             ->where('client_id', $client_id)
    //             ->where('matrix', $matrix)->get();
    //     }
    //     return $impacts;
    // }
    // private function fetchClientLikelihoodsSetup($client_id)
    // {
    //     $likelihoods = [];
    //     $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
    //     if ($risk_matrix) {
    //         $matrix = $risk_matrix->current_matrix;
    //         $likelihoods = RiskLikelihood::orderBy('value')
    //             ->where('client_id', $client_id)
    //             ->where('matrix', $matrix)->get();
    //     }
    //     return $likelihoods;
    // }
    private function createRiskAssessment($data)
    {
        $client_id = $data->client_id;
        $vendor_id = $data->vendor_id;
        $due_diligence_response_id = $data->id;
        $due_diligence_question_id = $data->due_diligence_question_id;
        // $impact_fields = [
        //     ['name' => 'Confidentiality', 'slug' => 'C', 'impact_value' => '', 'meaning' => ''],
        //     ['name' => 'Integrity', 'slug' => 'I', 'impact_value' => '', 'meaning' => ''],
        //     ['name' => 'Availability', 'slug' => 'A', 'impact_value' => '', 'meaning' => ''],
        //     ['name' => 'Privacy', 'slug' => 'P', 'impact_value' => '', 'meaning' => ''],
        // ];
        // if ($module == 'rcsa') {
        // $impact_fields = [];
        $impact_on_areas = [];
        $impact_areas = RiskImpactArea::where('client_id', $client_id)->orderBy('area')->get();
        foreach ($impact_areas as $impact_area) {
            $impact_on_areas[] = [
                'id' => $impact_area->id,
                'name' => $impact_area->area,
                'slug' => $impact_area->area,
                'impact_value' => '',
                'meaning' => ''
            ];
        }
        // }
        RiskAssessment::updateOrCreate(
            [
                'client_id' => $client_id,
                'vendor_id' => $vendor_id,
                'due_diligence_response_id' => $due_diligence_response_id,
                'due_diligence_question_id' => $due_diligence_question_id,
            ],
            [
                // 'impact_data' => $impact_fields,
                // 'revised_impact_data' => $impact_fields,
                'impact_on_areas' => $impact_on_areas,
                'revised_impact_on_areas' => $impact_on_areas,
                // 'impact_on_areas' => $impact_on_areas,
                // 'revised_impact_on_areas' => $impact_on_areas,
            ]
        );

        return 'success';
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DueDiligenceResponse  $answer
     * @return void
     */
    public function submitDueDiligenceResponses(Request $request)
    {
        $value = $request->value;
        $answer_ids = json_decode(json_encode($request->answer_ids));
        DueDiligenceResponse::with('question')->whereIn('id', $answer_ids)->chunkById(10, function ($answers) {
            foreach ($answers as $answer) {
                $ans = $answer->answer;
                if ($ans != NULL) {

                    $details = $answer->detailed_explanation;
                    $quest = $answer->question->question;
                    $template_ids_array = $answer->question->expected_document_template_ids;
                    $uploaded_documents = DueDiligenceEvidence::where('due_diligence_response_id', $answer->id)
                        // ->where('is_exception', 0)
                        ->where('link', '!=', NULL)
                        ->get()
                        ->pluck('link')
                        // ->pluck('full_document_link')
                        ->toArray();
                    // $evidences = $uploaded_documents;
                    $evidence_links_array = $uploaded_documents;
                    $file_text = '';
                    $evidence_link = (count($evidence_links_array) > 0) ? implode(',', $evidence_links_array) : '';
                    if ($evidence_link != '') {
                        // $file_text = Pdf::getText(
                        //     portalPulicPath($evidence_link),
                        //     'C:\xampp\htdocs\3core-projects\infoassure-backend\xpdf-tools-win-4.05\bin64\pdftotext.exe'
                        // );
                    }


                    try {
                        $ai_response = $this->analyzeWithOpenAI($quest, $ans, $details, $file_text);
                        $answer->risk_score = $ai_response->score;
                        $answer->observation = $ai_response->observation;
                        $answer->impact = $ai_response->vulnerabilities;
                        $answer->recommendations = $ai_response->recommendations;
                    } catch (\Throwable $th) {
                        //throw $th;
                        $answer->risk_score = 3; // the high risk by default
                        $answer->observation = NULL;
                        $answer->impact = NULL;
                    }

                }
                $answer->is_submitted = 1;
                $answer->save();
                $this->createRiskAssessment($answer);
            }
        }, $column = 'id');

        //send notification
        $answer = DueDiligenceResponse::with('question')->find($answer_ids[0]);
        $question = $answer->question;
        $vendor = Vendor::find($answer->vendor_id);

        // $project = Project::with('users')->where('available_module_id', $this->module->id)->first();
        // $userIds = $project->users()->pluck('id')->toArray();
        $userIds = $this->getVendorClientUserIds($vendor->id);
        $token = $request->bearerToken();
        $user = User::where('api_token', $token)->first();
        $name = $user->name;// . ' (' . $user->email . ')';

        // if ($value === 1) {

        //     $title = "Answers Submitted";
        //     //log this event
        //     $description = "$name submitted response to vendor due diligence assessment for $vendor->name";
        // } else {

        //     $title = "Response modification enabled";
        //     //log this event
        //     $description = "$name enabled response modification on due diligence";
        // }

        $title = "Answers Submitted";
        //log this event
        $description = "$name submitted response to vendor due diligence assessment under $question->domain for $vendor->name";
        //log this event
        // $description = "The vendor profile for $vendor->name was updated by $name. <br>" . $extra_message;
        $this->sendNotification($title, $description, $userIds);

        // $this->auditTrailEvent($title, $description, $users);
    }
    public function enableModification(Request $request)
    {
        $user = $this->getUser();
        $value = $request->value;
        $answer_ids = json_decode(json_encode($request->answer_ids));
        DueDiligenceResponse::whereIn('id', $answer_ids)->update(['is_submitted' => 0]);

        //send notification
        $answer = DueDiligenceResponse::with('question')->find($answer_ids[0]);
        $question = $answer->question;
        $vendorUserIds = User::where('vendor_id', $answer->vendor_id)->pluck('id')->toArray();
        // if ($value === 1) {

        //     $title = "Answers Submitted";
        //     //log this event
        //     $description = "$name submitted response to vendor due diligence assessment for $vendor->name";
        // } else {

        //     $title = "Response modification enabled";
        //     //log this event
        //     $description = "$name enabled response modification on due diligence";
        // }

        $title = "Response modification enabled";
        //log this event
        $description = "$user->name enabled response modification on due diligence assessment under $question->domain";
        //log this event
        // $description = "The vendor profile for $vendor->name was updated by $name. <br>" . $extra_message;
        $this->sendVendorNotification($title, $description, $vendorUserIds);

        // $this->auditTrailEvent($title, $description, $users);
    }
    public function changeStatus(Request $request)
    {
        $user = $this->getUser();
        $value = $request->value;
        $answer_ids = json_decode(json_encode($request->answer_ids));
        DueDiligenceResponse::whereIn('id', $answer_ids)->update(['status' => $value]);

        //send notification
        $answer = DueDiligenceResponse::with('question')->find($answer_ids[0]);
        $question = $answer->question;
        $vendorUserIds = User::where('vendor_id', $answer->vendor_id)->pluck('id')->toArray();


        $title = "Assessment Status Changed";
        //log this event
        $description = "$user->name changed the due diligence assessment status to $value under $question->domain";
        //log this event
        // $description = "The vendor profile for $vendor->name was updated by $name. <br>" . $extra_message;
        $this->sendVendorNotification($title, $description, $vendorUserIds);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DueDiligenceResponse  $answer
     * @return void
     */
    public function update(Request $request, DueDiligenceResponse $answer)
    {
        $field = $request->field;
        $answer->$field = $request->answer;
        $answer->save();
        // $user = $this->getUser();
        // $field = $request->field;
        // $answer->$field = $request->answer;
        // $client = Client::with('users', 'partner.users')->find($answer->client_id);
        // $partner = $client->partner;
        // $users = $partner->users;
        // $users = $users->merge($client->users);
        // $answer->save();
        // $title = "Response to Due Diligence Assessment made";
        // $details = "<br> <strong>Details:</strong> <br> Domain: " . $answer->question->domain . " <br> Question: " . $answer->question->question;
        // //log this event
        // $description = "$user->name gave a response on due diligence assessment.  $details";
        // $this->auditTrailEvent($title, $description, $users);
    }

    public function uploadDueDiligenceEvidence(Request $request)
    {
        $evidence = new DueDiligenceEvidence();
        $title = $request->title;
        $answer_id = $request->answer_id;
        $answer = DueDiligenceResponse::find($answer_id);
        $client_id = $answer->client_id;
        $client = Client::find($client_id);
        // $folder_key = $client_id;
        $folder_key = str_replace(' ', '_', ucwords($client->name));
        if ($request->file('file_uploaded') != null && $request->file('file_uploaded')->isValid()) {
            $file = $request->file('file_uploaded');
            $name = $file->hashName();
            // $file_name = $name . "." . $request->file('file_uploaded')->extension();
            // $link = $file->storeAs('clients/' . $folder_key . '/due-diligence-evidence', $name, 'public');
            $link = $file->storeAs('vendors/' . $answer->vendor_id . '/documents', $name, 'public');
            $evidence->client_id = $client_id;
            $evidence->due_diligence_response_id = $answer_id;
            $evidence->link = $link;
            $evidence->evidence_title = $title;
            $evidence->save();
        }
    }
    public function destroyDueDiligenceEvidence(DueDiligenceEvidence $evidence)
    {
        Storage::disk('public')->delete($evidence->link);
        $evidence->delete();
        return response()->json([], 204);
    }
}
