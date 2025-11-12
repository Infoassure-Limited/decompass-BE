<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use App\Models\VendorDueDiligence\DefaultQuestion;
use App\Models\VendorDueDiligence\DueDiligenceQuestion;
use Illuminate\Http\Request;

class DueDiligenceQuestionsController extends Controller
{
    public function fetchDefaultQuestions(Request $request)
    {
        $questions = DefaultQuestion::orderBy('domain')
            ->orderBy('question')
            ->get()
            ->groupBy('domain');
        return response()->json(compact('questions'), 200);
    }
    public function index(Request $request)
    {
        $client_id = $request->client_id;
        $questions = DueDiligenceQuestion::where('client_id', $client_id)
            ->orderBy('domain')
            ->orderBy('question')
            ->get()
            ->groupBy('domain');
        return response()->json(compact('questions'), 200);
    }
    public function fetchQuestionWithResponse(Request $request)
    {
        $client_id = $request->client_id;
        $domains = DueDiligenceQuestion::with([
            'response' => function ($q) use ($client_id) {
                $q->where('client_id', $client_id);
            },
            'response.evidences'
        ])->get()->groupBy('domain');
        return response()->json(compact('domains'), 200);
    }
    public function saveImportedQuestions(Request $request)
    {

        $client_id = $this->getClient()->id;
        $questions = json_decode(json_encode($request->questions));
        foreach ($questions as $question) {
            $this->store($client_id, $question);
        }
        return response()->json(['message' => 'Successful'], 200);
    }
    public function saveQuestions(Request $request)
    {
        $client_id = $this->getClient()->id;
        $details = json_decode(json_encode($request->details));
        foreach ($details as $data) {

            $this->store($client_id, $data);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  $data
     * @param  $client_id
     * @return void
     */
    private function store($client_id, $data)
    {
        DueDiligenceQuestion::updateOrCreate(
            [
                'client_id' => $client_id,
                'question' => $data->question
            ],
            [
                'key' => $data->key,
                'domain' => $data->domain,
                'answer_type' => $data->answer_type,
                'upload_evidence' => $data->upload_evidence
            ]
        );

    }

    public function saveDefaultQuestion(Request $request)
    {
        DefaultQuestion::updateOrCreate([
            'question' => $request->question,

        ], [
            'key' => $request->key,
            'domain' => $request->domain,
            'answer_type' => $request->answer_type,
            'upload_evidence' => $request->upload_evidence,
        ]);
    }
    public function uploadBulkDefaultQuestions(Request $request)
    {
        set_time_limit(0);
        $bulk_data = json_decode(json_encode($request->bulk_data));
        $unsaved_data = [];
        $error = [];
        foreach ($bulk_data as $csvRow) {
            try {
                $request->question = trim($csvRow->QUESTION);
                $request->key = (isset($csvRow->KEY)) ? trim($csvRow->KEY) : NULL;
                $request->domain = (isset($csvRow->DOMAIN)) ? trim($csvRow->DOMAIN) : NULL;
                $request->answer_type = (isset($csvRow->ANSWER_TYPE)) ? trim(strtolower($csvRow->ANSWER_TYPE)) : 'both';
                $upload_evidence = (isset($csvRow->REQUIRES_EVIDENCE)) ? trim(strtolower($csvRow->REQUIRES_EVIDENCE)) : 'yes';

                $request->upload_evidence = ($upload_evidence == 'yes') ? 1 : 0;
                $this->saveDefaultQuestion($request);
            } catch (\Throwable $th) {
                $unsaved_data[] = $csvRow;
                $error[] = $th;
                // return response()->json($th);
            }
        }
        return response()->json(compact('unsaved_data', 'error'), 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DueDiligenceQuestion  $question
     * @return \Illuminate\Http\Response
     */
    public function show(DueDiligenceQuestion $question)
    {
        //
        $question = $question->find($question->id);
        return response()->json(compact('question'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DueDiligenceQuestion  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DueDiligenceQuestion $question)
    {
        //
        $question->question = $request->question;
        $question->key = $request->key;
        $question->domain = $request->domain;
        $question->answer_type = $request->answer_type;
        $question->upload_evidence = $request->upload_evidence;
        $question->save();
        return response()->json(['message' => 'Successful'], 200);
    }

    public function updateDefaultQuestion(Request $request, DefaultQuestion $question)
    {
        //
        $question->question = $request->question;
        $question->key = $request->key;
        $question->domain = $request->domain;
        $question->answer_type = $request->answer_type;
        $question->upload_evidence = $request->upload_evidence;
        $question->save();
        return response()->json(['message' => 'Successful'], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DueDiligenceQuestion  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(DueDiligenceQuestion $question)
    {
        $question->delete();
        return response()->json([], 204);
    }


    public function destroyDefaultQuestion(DefaultQuestion $question)
    {
        $question->delete();
        return response()->json([], 204);
    }


}
