<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DueDiligenceEvidence;
use App\Models\DueDiligenceQuestion;
use App\Models\DueDiligenceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DueDiligenceResponsesController extends Controller
{
    public function fetchResponses(Request $request)
    {
        $client_id = $request->client_id;
        $answers = DueDiligenceResponse::with('question', 'evidences')->where('client_id', $client_id)->get();
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
        $fetch_questions = DueDiligenceQuestion::get();
        foreach ($fetch_questions as $fetch_question) {
            // create answer
            $data = [
                'client_id' => $client_id,
                'due_diligence_question_id' => $fetch_question->id
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DueDiligenceResponse  $answer
     * @return \Illuminate\Http\Response
     */
    public function submitDueDiligenceResponses(Request $request)
    {

        $user = $this->getUser();
        $value = $request->value;
        $answer_ids = json_decode(json_encode($request->answer_ids));
        DueDiligenceResponse::whereIn('id', $answer_ids)->update(['is_submitted' => $value]);

        //send notification
        $answer = DueDiligenceResponse::with('client.users', 'client.partner.users')->find($answer_ids[0]);
        $name = $user->name;
        $users = $answer->client->users;
        $partner = $answer->client->partner;
        $users = $users->merge($partner->users);
        if ($value === 1) {

            $title = "Answers Submitted";
            //log this event
            $description = "$name submitted response to due diligence";
        } else {

            $title = "Response modification enabled";
            //log this event
            $description = "$name enabled response modification on due diligence";
        }
        $this->auditTrailEvent($title, $description, $users);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DueDiligenceResponse  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DueDiligenceResponse $answer)
    {
        $user = $this->getUser();
        $field = $request->field;
        $answer->$field = $request->answer;
        $client = Client::with('users', 'partner.users')->find($answer->client_id);
        $partner = $client->partner;
        $users = $partner->users;
        $users = $users->merge($client->users);
        $answer->save();
        $title = "Response to Due Diligence Assessment made";
        $details = "<br> <strong>Details:</strong> <br> Domain: " . $answer->question->domain . " <br> Question: " . $answer->question->question;
        //log this event
        $description = "$user->name gave a response on due diligence assessment.  $details";
        $this->auditTrailEvent($title, $description, $users);
    }

    public function uploadDueDiligenceEvidence(Request $request)
    {
        $client = $this->getClient();
        $evidence = new DueDiligenceEvidence();
        $title = $request->title;
        $answer_id = $request->answer_id;
        $client_id = $client->id;
        $folder_key = str_replace(' ', '_', ucwords($client->name));
        if ($request->file('file_uploaded') != null && $request->file('file_uploaded')->isValid()) {

            $name = $request->file('file_uploaded')->hashName();
            // $file_name = $name . "." . $request->file('file_uploaded')->extension();
            $link = $request->file('file_uploaded')->storeAs('clients/' . $folder_key . '/due-diligence-evidence', $name, 'public');

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
