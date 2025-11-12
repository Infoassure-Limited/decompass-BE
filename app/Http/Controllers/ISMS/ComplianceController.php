<?php

namespace App\Http\Controllers\ISMS;

use App\Http\Controllers\Controller;
use App\Models\ISMS\Clause;
use App\Models\ISMS\ComplianceQuestion;
use App\Models\ISMS\ComplianceResponse;
use App\Models\ISMS\ComplianceResponseMonitor;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ComplianceController extends Controller
{
    //
    public function fetchClauses()
    {
        $clauses = Clause::with('questions')->orderBy('sort_by')->get();
        return response()->json(compact('clauses'), 200);
    }
    public function fetchQuestions()
    {
        $questions = ComplianceQuestion::join('clauses', 'clauses.id', '=', 'compliance_questions.clause_id')
            ->get()
            ->groupBy('clauses.name');
        return response()->json(compact('questions'), 200);
    }
    public function fetchResponseMonitors()
    {
        $client_id = $this->getClient()->id;
        $monitors = ComplianceResponseMonitor::with('responses.question')
            ->join('clauses', 'clauses.id', '=', 'compliance_response_monitors.clause_id')
            ->select('*', 'clauses.description as clause')
            ->get()
            ->groupBy('clause');
        return response()->json(compact('monitors'), 200);
    }
    public function saveQuestions(Request $request)
    {
        $data = $request->toArray();
        $clause_id = $request->clause_id;
        ComplianceQuestion::firstOrCreate(
            ['clause_id' => $clause_id, 'question' => $request->question],
            $data
        );
    }
    public function updateQuestion(Request $request, ComplianceQuestion $complianceQuestion)
    {
        $data = $request->toArray();
        $complianceQuestion::update($data);
        return $this->fetchQuestions();
    }
    public function createComplianceMonitor(Request $request)
    {
        $client_id = $this->getClient()->id;
        $project_id = $request->project_id;
        $clauses = Clause::orderBy('sort_by')->get();
        foreach ($clauses as $clause) {
            $monitor = ComplianceResponseMonitor::firstOrCreate([
                'client_id' => $client_id,
                'project_id' => $project_id,
                'clause_id' => $clause->id
            ]);


            $this->setComplianceResponse($monitor);
        }
        return $this->fetchResponseMonitors();
    }
    private function setComplianceResponse(ComplianceResponseMonitor $monitor)
    {
        $questions = ComplianceQuestion::where('clause_id', $monitor->clause_id)->get();
        foreach ($questions as $question) {
            ComplianceResponse::updateOrCreate(
                [
                    'client_id' => $monitor->client_id,
                    'compliance_response_monitor_id' => $monitor->id,
                    'clause_id' => $monitor->clause_id,
                    'compliance_question_id' => $question->id,
                ],
                [
                    'assignee_tasks' => $question->possible_tasks
                ]
            );
        }

    }
    public function updateComplianceResponse(Request $request, ComplianceResponse $answer)
    {
        $field = $request->field;
        $answer->$field = $request->value;
        $answer->requires_ai_analysis = 1;
        $answer->save();
    }
    public function submitResponses(Request $request, ComplianceResponseMonitor $monitor)
    {

        $monitor->is_submitted = 1;
        $monitor->date_submitted = date('Y-m-d', strtotime('now'));
        $monitor->submitted_by = $this->getUser()->id;
        $monitor->save();


        // let AI analyze the responses
        ComplianceResponse::with('question')
            ->where('compliance_response_monitor_id', $monitor->id)
            ->where('requires_ai_analysis', 1)
            ->chunkById(10, function ($answers) {
                foreach ($answers as $answer) {
                    $ans = ($answer->response != null) ? $answer->response : implode(',', $answer->response_array);
                    $details = $answer->details;
                    $response = $ans . '. Details: ' . $details;
                    if ($ans != NULL) {
                        $quest = $answer->question->question;
                        $ai_response = $this->analyzeWithOpenAI($quest, $response);
                        if ($ai_response->status != null && $ai_response->status != '') {

                            $answer->status = $ai_response->status;
                            $answer->justification = $ai_response->justification;
                            $answer->recommendation = $ai_response->recommendation;
                            $answer->requires_ai_analysis = 0;
                            $answer->save();
                        }

                    }
                }
            }, $column = 'id');
        return $this->fetchResponseMonitors();
    }

    private function analyzeWithOpenAI($quest, $ans)
    {
        //
        $message = "Please assess the following compliance response for the question below:";
        $question = "Question: ### $quest ###";
        $answer = "Response: ### $ans ###";
        $instruction = "
               Based on the ISO 27001 (Information Security Management System) standard, analyze and generate:
                1. The status of the response as Compliant, Opportunity for Improvement, or Non-Compliant, ignoring the absence of an uploaded evidence.
                3. A brief justification for the status.
                4. A good recommendation based on the status.
                Provide the response in a json format for easy extraction in the format below:

                status: <status>
                justification: <brief justification in 30 to 50 words>
                recommendation: <good recommendation in 30 words>";

        $content = $message . $question . $answer . $instruction;

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
}
