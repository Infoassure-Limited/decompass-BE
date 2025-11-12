<?php

namespace App\Http\Controllers;

use App\Models\DueDiligenceQuestion;
use Illuminate\Http\Request;

class DueDiligenceQuestionsController extends Controller
{
    public function index(Request $request)
    {
        $questions = DueDiligenceQuestion::paginate($request->limit);
        return response()->json(compact('questions'), 200);
    }
    public function fetchQuestionWithResponse(Request $request)
    {
        $client_id = $request->client_id;
        $domains = DueDiligenceQuestion::with(['response' => function ($q) use ($client_id) {
            $q->where('client_id', $client_id);
        }, 'response.evidences'])->get()->groupBy('domain');
        return response()->json(compact('domains'), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DueDiligenceQuestion::firstOrCreate([
            'question' => $request->question,
            'key' => $request->key,
            'domain' => $request->domain,
        ]);
        return response()->json(['message' => 'Successful'], 200);
    }
    public function uploadBulk(Request $request)
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
                //store the entry for this student
                $this->store($request);
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
}
