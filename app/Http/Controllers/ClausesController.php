<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Clause;
use App\Models\Client;
use App\Models\DocumentTemplate;
use App\Models\Question;
use App\Models\Standard;
use App\Models\Upload;
use App\Models\Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ClausesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->standard_id) && $request->standard_id !== '') {
            $clauses = Clause::with('standard', 'templates')->where('standard_id', $request->standard_id)->orderBy('sort_by')->paginate($request->limit);
        } else {

            $clauses = Clause::with('standard', 'templates')->orderBy('sort_by')->paginate($request->limit);
        }
        return response()->json(compact('clauses'), 200);
    }
    public function fetchClausesWithQuestions(Request $request)
    {
        $client_id = $request->client_id;
        $standard_id = $request->standard_id;
        $project_id = $request->project_id;
        $clauses = Clause::with([
            'questions.answer' => function ($q) use ($client_id, $standard_id, $project_id) {
                $q->where(['standard_id' => $standard_id, 'client_id' => $client_id, 'project_id' => $project_id]);
            },
            'questions.answer.evidences'
        ])->where(['standard_id' => $standard_id])->where('will_have_audit_questions', 1)->orderBy('sort_by')->get();
        return response()->json(compact('clauses'), 200);
    }
    public function fetchClausesWithDocuments(Request $request)
    {
        $client_id = $request->client_id;
        $standard_id = $request->standard_id;
        $project_id = $request->project_id;
        $clauses = Clause::with([
            'uploads' => function ($q) use ($client_id, $standard_id, $project_id) {
                $q->where(['standard_id' => $standard_id, 'client_id' => $client_id, 'project_id' => $project_id]);
            }
        ])->where(['standard_id' => $standard_id])->where('requires_document_upload', 1)->orderBy('sort_by')->get();
        return response()->json(compact('clauses'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $names_string = $request->names;
        $names_array = explode('|', $names_string);
        foreach ($names_array as $name) {
            Clause::firstOrCreate([
                'name' => trim($name),
                'standard_id' => $request->standard_id,
                'will_have_audit_questions' => $request->will_have_audit_questions,
                'requires_document_upload' => $request->requires_document_upload,
            ]);
        }
        return response()->json(['message' => 'Successful'], 200);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Clause  $clause
     * @return \Illuminate\Http\Response
     */
    public function show(Clause $clause)
    {
        //
        $clause = $clause->with('standard')->find($clause->id);
        return response()->json(compact('clause'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Clause  $clause
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Clause $clause)
    {
        //
        $clause->name = $request->name;
        $clause->standard_id = $request->standard_id;
        // $clause->description = $request->description;
        $clause->will_have_audit_questions = $request->will_have_audit_questions;
        $clause->requires_document_upload = $request->requires_document_upload;
        $clause->save();
        return response()->json(['message' => 'Successful'], 200);
    }

    public function setSortValue(Request $request, Clause $clause)
    {
        //
        $clause->sort_by = $request->value;
        $clause->save();
        return response()->json(['message' => 'Successful'], 200);
    }

    public function fetchExceptions(Request $request)
    {
        // $client = $this->getClient();
        $client_id = $request->client_id;
        $project_id = $request->project_id;
        $exceptions = Exception::with('clause', 'answer.question', 'upload')->where(['client_id' => $client_id, 'project_id' => $project_id])->paginate(10);
        return response()->json(compact('exceptions'), 200);
    }
    public function createException(Request $request)
    {
        $user = $this->getUser();
        $client = $this->getClient();
        $type = $request->type;
        $clause_id = $request->clause_id;
        $project_id = $request->project_id;
        $reason = $request->reason;

        if ($type == 'answer') {
            $answer_id = $request->answer_id;
            $exception = Exception::where(['client_id' => $client->id, 'answer_id' => $answer_id])->first();
            if (!$exception) {
                $exception = new Exception();
                $exception->client_id = $client->id;
                $exception->clause_id = $clause_id;
                $exception->project_id = $project_id;
                $exception->answer_id = $answer_id;
                $exception->created_by = $user->id;
                $exception->reason = $reason;
                $exception->save();
            }
            $answer = Answer::find($answer_id);
            $answer->is_exception = 1;
            $answer->save();
            return $exception;
        }
        if ($type == 'upload') {
            $upload_id = $request->upload_id;
            $exception = Exception::where(['client_id' => $client->id, 'upload_id' => $upload_id])->first();
            if (!$exception) {
                $exception = new Exception();
                $exception->client_id = $client->id;
                $exception->clause_id = $clause_id;
                $exception->upload_id = $upload_id;
                $exception->project_id = $project_id;
                $exception->created_by = $user->id;
                $exception->reason = $reason;
                $exception->save();
            }
            $upload = Upload::find($upload_id);
            $upload->is_exception = 1;
            $upload->save();
            return $exception;
        }
        return response()->json([], 204);
    }
    public function reverseException(Exception $exception)
    {
        $answer_id = $exception->answer_id;
        if ($answer_id !== NULL) {

            $answer = Answer::find($answer_id);
            $answer->is_exception = 0;
            $answer->save();
        }
        $upload_id = $exception->upload_id;
        if ($upload_id !== NULL) {
            $upload = Upload::find($upload_id);
            $upload->is_exception = 0;
            $upload->save();
        }
        $exception->delete();
        return response()->json([], 204);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clause  $clause
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clause $clause)
    {
        $clause->delete();
        return response()->json([], 204);
    }

    public function destroyTemplate(DocumentTemplate $template)
    {
        Storage::disk('public')->delete($template->link);
        $template->delete();
        return response()->json([], 204);
    }
    public function remarkOnUpload(Request $request, Upload $upload)
    {
        $value = $request->value;
        $field = $request->field;
        $upload->$field = $value;
        $upload->save();

        $clause = Clause::find($upload->clause_id);
        $client = Client::find($upload->client_id);
        $title = "Remark on uploaded document";
        //log this event
        $description = "Remark was made on document title: $clause->template_title, clause: $clause->name uploaded by $client->name";
        $this->auditTrailEvent($title, $description);
    }
}
