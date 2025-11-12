<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if (isset($request->clause_id) && $request->clause_id !== '') {
            $questions = Question::with('standard', 'clause')->where('clause_id', $request->clause_id)->paginate($request->limit);
        } else {

            $questions = Question::with('standard', 'clause')->orderBy('id', 'DESC')->paginate($request->limit);
        }
        return response()->json(compact('questions'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Question::firstOrCreate([
            'standard_id' => $request->standard_id,
            'clause_id' => $request->clause_id,
            'question' => $request->question,
            'upload_evidence' => $request->upload_evidence,
            'can_have_exception' => $request->can_have_exception,
            'answer_type' => $request->answer_type
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
                $request->answer_type = trim($csvRow->ANSWER_TYPE);
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
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
        $question = $question->with('standard', 'clause')->find($question->id);
        return response()->json(compact('question'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
        $question->standard_id = $request->standard_id;
        $question->clause_id = $request->clause_id;
        $question->question = $request->question;
        $question->question_type = $request->question_type;
        $question->upload_evidence = $request->upload_evidence;
        $question->can_have_exception = $request->can_have_exception;
        $question->answer_type = $request->answer_type;
        $question->save();
        return response()->json(['message' => 'Successful'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json([], 204);
    }
}
