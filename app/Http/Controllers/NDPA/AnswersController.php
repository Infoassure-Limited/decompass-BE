<?php

namespace App\Http\Controllers\NDPA;

use App\Http\Controllers\Controller;
use App\Models\NDPA\Answer;
use App\Models\NDPA\Clause;
use App\Models\Client;
use App\Models\NDPA\GapAssessmentEvidence;
use App\Models\Project;
use App\Models\NDPA\Question;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

class AnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $this->getUser();
        $project_id = $request->project_id;
        $consulting_id = $request->consulting_id;
        $client_id = $request->client_id;
        $client = Client::find($client_id);
        $fetch_questions = Question::get();
        foreach ($fetch_questions as $fetch_question) {
            // create answer
            $data = [
                'client_id' => $client_id,
                'section_id' => $fetch_question->section_id,
                'project_id' => $project_id,

                'question_id' => $fetch_question->id,
                'clause_id' => $fetch_question->clause_id,
                // 'created_by' => $user->id,
            ];
            if ($fetch_question->question != NULL) {
                Answer::firstOrCreate($data/*, ['assignee_id' => $client->admin_user_id]*/);
                // $answer_obj = new Answer();
                // $answer_obj->createProjectAnswer($data, );
            }
        }
        Answer::where([
            'client_id' => $client_id,
            'project_id' => $project_id,
            'assignee_id' => NULL,
        ])->chunkById(100, function (Collection $answers) use ($client) {
            foreach ($answers as $answer) {
                $answer->assignee_id = $client->admin_user_id;
                $answer->save();
            }
        }, 'id');
    }
    public function assignUserToRespond(Request $request)
    {
        //
        $answers = json_decode(json_encode($request->answers));
        $date = date('Y-m-d H:i:s', strtotime('now'));
        $user = $this->getUser();
        foreach ($answers as $answer) {
            $answerObject = Answer::with('clause', 'section', 'question')->find($answer->id);
            $former_assignee_id = $answerObject->assignee_id;
            $assignee_id = $answer->assignee_id;
            if ($former_assignee_id != $assignee_id) {
                $answerObject->start_date = date('Y-m-d', strtotime('now'));
                $answerObject->end_date = date('Y-m-d', strtotime($answer->end_date));
                $answerObject->assignee_id = $assignee_id;
                $answerObject->save();


                $clause = $answerObject->clause;
                $section = $answerObject->section;
                $question = $answerObject->question;

                $title1 = 'Task Assigned';
                $message1 = "You have been <strong>assigned</strong> to a task on the NDPA Module ($clause->name) with the details below:<br>" .
                    ucwords($section->name . ' - ' . $section->description) . "<br>" .
                    "Question No.:  #$question->id <br>" .
                    "Assigned by:  $user->name<br>" .
                    "Date assigned: $answerObject->start_date<br>" .
                    "Deadline:  $answerObject->end_date<br>" .
                    "Extra Comment:  $answer->comment<br>";


                $title2 = 'Task Unassigned';
                $message2 = "You have been <strong>unassigned</strong> from a task on the NDPA Module ($clause->name) with the details below:<br>" .
                    ucwords($section->name . ' - ' . $section->description) . "<br>" .
                    "Question No.:  #$question->id <br>" .
                    "Unassigned by:  $user->name<br>" .
                    "Date: $date<br>";

                // send task assignment notification to the assignee and and reassignment to the previous assignee if available
                $this->sendNotification($title1, $message1, [$answer->assignee_id]);

                if ($former_assignee_id != NULL) {
                    $this->sendNotification($title2, $message2, [$former_assignee_id]);
                }
            }
        }


    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \JsonSerializable
     */
    private function analyzeWithOpenAI($quest, $ans, $details, $evid)
    {
        //
        $message = "Please assess the following compliance response for the question below:";
        $question = "Question: ### $quest ###";
        $answer = "Response: ### $ans, $details ###";
        $evidence = "Evidence: ### $evid ###";
        $instruction = "
                Based on the provided information, generate:
                1. A score from 1 to 10 reflecting the accuracy and completeness of the response and evidence.
                2. An assessment grade which is either Conformity, Opportunity for Improvement, or Non-Conformity for the assigned score. For a Conformity, the score must be above 7
                3. A brief justification for the assigned score.
                4. A good recommendation based on the assigned score.
                5. the associated risks if the assessment grade is not Conformity.
                Provide the response in a json format for easy extraction in the format below;

                score: {range of 1 to 10}
                grade: <grade>
                justification: <brief justification in 30 to 50 words>
                recommendation: <good recommendation in 20 words>
                associated_risks: <state the associated_risks in 10 words>";

        $content = $message . $question . $answer . $evidence . $instruction;

        return $this->callOpenAISearch($content);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function submitAnswers(Request $request)
    {

        $user = $this->getUser();
        $value = $request->value;
        $answer_ids = json_decode(json_encode($request->answer_ids));
        if ($value === 1) {
            Answer::with('question')->whereIn('id', $answer_ids)->chunkById(10, function ($answers) {
                foreach ($answers as $answer) {
                    $ans = $answer->yes_or_no;
                    if ($ans != NULL) {

                        $details = $answer->open_ended_answer;
                        $quest = $answer->question->question;
                        $template_ids_array = $answer->question->expected_document_template_ids;
                        $uploaded_documents = Upload::where('client_id', $answer->client_id)
                            ->whereIn('template_id', $template_ids_array)
                            // ->where('is_exception', 0)
                            ->where('link', '!=', NULL)
                            ->get()
                            ->pluck('full_document_link')->toArray();
                        // $evidences = $uploaded_documents;
                        $evidence_links_array = $uploaded_documents;
                        // foreach ($evidences as $evidence) {
                        //     $evidence_links_array[] = env('APP_URL') . '/storage/' . $evidence->link;
                        // }
                        $evidence_link = implode(',', $evidence_links_array);
                        if ($ans == 'NO') {

                            $ai_response = $this->analyzeWithOpenAI($quest, $ans, $details, $evidence_link);
                            $answer->score = $ai_response->score;
                            $answer->findings = $ai_response->justification;
                            $answer->consultant_grade = $ai_response->grade;
                            $answer->recommendations = $ai_response->recommendation;
                            $answer->associated_risks = $ai_response->associated_risks;
                        } else if (count($evidence_links_array) > 0) {

                            $ai_response = $this->analyzeWithOpenAI($quest, $ans, $details, $evidence_link);
                            $answer->score = $ai_response->score;
                            $answer->findings = $ai_response->justification;
                            $answer->consultant_grade = $ai_response->grade;
                            $answer->recommendations = $ai_response->recommendation;
                            $answer->associated_risks = $ai_response->associated_risks;
                            if ($ai_response->grade == 'Conformity') {
                                $answer->is_risk_resolved = 1;
                            }
                        } else {

                            $answer->score = 1;
                            $answer->findings = "The response says 'Yes' but lacks evidence to backup the claim.";
                            $answer->consultant_grade = 'Non-Conformity';
                            $answer->recommendations = 'Kindly provide detailed evidence to ascertain compliance.';

                        }


                    }
                    $answer->is_submitted = 1;
                    $answer->save();

                }
            }, $column = 'id');
        }
        //send notification
        $answer = Answer::with('clause', 'section', 'project.users')->find($answer_ids[0]);
        $clause = $answer->clause;
        $section = $answer->section;
        $question = $answer->question;
        $name = $user->name;
        $userIds = $answer->project->users()->pluck('id');
        $userIds = $userIds->toArray();
        if ($value === 1) {

            $title = "Response Submitted and Analyzed";
            //log this event
            $description = "Response on compliance assessment for the NDPA ($clause->name) has been submitted by $name and analyzed for compliance. Check details below: <br><br>" .
                ucwords($section->name . ' - ' . $section->description) . "<br>" .
                "Question No.:  #$answer->question_id <br><br>";

        } else {
            Answer::whereIn('id', $answer_ids)->update(['is_submitted' => 0]);
            $title = "Response modification enabled";
            //log this event
            $description = "$name has enabled the ability to modify responses made on compliance assessment for the NDPA ($clause->name). Check details below: <br><br>" .
                ucwords($section->name . ' - ' . $section->description) . "<br>" .
                "Question No.:  #$answer->question_id <br><br>";
        }
        $this->sendNotification($title, $description, $userIds);
        $question = Question::with('answer.assignee')->find($answer->question_id);
        return response()->json(compact('question'), 200);
        // $this->auditTrailEvent($title, $description, $users);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        $field = $request->field;
        $answer->$field = $request->answer;
        $answer->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function remarkOnAnswer(Request $request, Answer $answer)
    {
        $user = $this->getUser();
        $remark = $request->remark;
        $answer->remark = $remark;
        $answer->save();

        $clause = Clause::with('standard')->find($answer->clause_id);
        $standard = $clause->standard;
        $client = Client::with('users')->find($answer->client_id);
        $title = "Remark on gap assessment";
        //log this event
        $description = "$user->name made a remark on gap assessment for $standard->name, $clause->name";
        $this->auditTrailEvent($title, $description, $client->users);
    }
    public function uploadGapAssessmentEvidence(Request $request)
    {
        $client = $this->getClient();
        $gap_assessment_evidence = new GapAssessmentEvidence();
        $title = $request->title;
        $answer_id = $request->answer_id;
        $answer = Answer::find($answer_id);
        $project_id = $answer->project_id;
        $client_id = $client->id;
        $folder_key = $client_id;
        if ($request->file('file_uploaded') != null && $request->file('file_uploaded')->isValid()) {

            $name = $request->file('file_uploaded')->getClientOriginalName();
            // $name = $request->file('file_uploaded')->hashName();
            // $file_name = $name . "." . $request->file('file_uploaded')->extension();
            $link = $request->file('file_uploaded')->storeAs('clients/' . $folder_key . '/gap-assessment-evidence', $name, 'public');

            $gap_assessment_evidence->client_id = $client_id;
            $gap_assessment_evidence->project_id = $project_id;
            $gap_assessment_evidence->answer_id = $answer_id;
            $gap_assessment_evidence->link = $link;
            $gap_assessment_evidence->evidence_title = $title;
            $gap_assessment_evidence->save();
        }
    }
    public function destroyGapAssessmentEvidenceEvidence(GapAssessmentEvidence $gap_assessment_evidence)
    {
        Storage::disk('public')->delete($gap_assessment_evidence->link);
        $gap_assessment_evidence->delete();
        return response()->json([], 204);
    }


}
