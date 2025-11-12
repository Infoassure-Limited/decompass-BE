<?php

namespace App\Http\Controllers\NDPA;

use App\Http\Controllers\Controller;

use App\Models\NDPA\Answer;
use App\Models\NDPA\Clause;
use App\Models\Client;
use App\Models\NDPA\ClauseSection;
use App\Models\DocumentTemplate;
use App\Models\NDPA\Question;
use App\Models\Standard;
use App\Models\Upload;
use App\Models\NDPA\Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ClausesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $clauses = Clause::with('sections')->orderBy('sort_by')->get();
        return response()->json(compact('clauses'), 200);
    }
    public function fetchClausesWithQuestions(Request $request)
    {
        $client_id = $request->client_id;
        $project_id = $request->project_id;
        $clauses = Clause::with([
            'sections.questions.answer' => function ($q) use ($client_id, $project_id) {
                $q->where(['client_id' => $client_id, 'project_id' => $project_id]);
            },
            'sections.questions.answer.assignee',
            // 'questions.section'
        ])->where('will_have_audit_questions', 1)->orderBy('sort_by')->get();

        $answer_completion_status = Answer::groupBy('clause_id')->where(['client_id' => $client_id, 'project_id' => $project_id])

            ->select('clause_id', \DB::raw('COUNT(CASE WHEN status IS NULL OR status != "Closed" AND is_exception = 0 THEN answers.id END ) as pending'))
            ->get()->groupBy('clause_id');

        $template_ids = Question::where('expected_document_template_ids', '!=', NULL)->pluck('expected_document_template_ids');
        $template_ids_array = $template_ids->flatten()->unique()->sort()->values();
        $uploads = Upload::where('client_id', $client_id)->whereIn('template_id', $template_ids_array)->get()->groupBy('template_id');
        return response()->json(compact('clauses', 'uploads', 'answer_completion_status'), 200);
    }
    public function fetchClausesWithDocuments(Request $request)
    {
        $client_id = $request->client_id;
        $standard_id = $request->standard_id;
        $project_id = $request->project_id;
        $clauses = Clause::with([
            'uploads' => function ($q) use ($client_id, $project_id) {
                $q->where(['client_id' => $client_id, 'project_id' => $project_id]);
            }
        ])->where('requires_document_upload', 1)->orderBy('sort_by')->get();
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
            ], ['description' => $request->description]);
        }
        return response()->json(['message' => 'Successful'], 200);
    }
    // public function createUploads(Request $request)
    // {
    //     $last_year = $this->getYear() - 1;
    //     $consulting_id = $request->consulting_id;
    //     $client_id = $request->client_id;
    //     $section_id = $request->section_id;
    //     $clauses = Clause::with('templates')->where(['section_id' => $section_id, 'requires_document_upload' => 1])->get();
    //     foreach ($clauses as $clause) {
    //         // create answer
    //         if (!empty($clause->templates)) {
    //             foreach ($clause->templates as $template) {
    //                 $upload = Upload::where([
    //                     'client_id' => $client_id,
    //                     'section_id' => $section_id,
    //                     'consulting_id' => $consulting_id,
    //                     'clause_id' => $clause->id,
    //                     'is_exception' => 0,
    //                 ])->where('created_at', 'LIKE', '%' . $last_year . '%')->first();
    //                 if (!$upload) {
    //                     $this->createNewUploads($request, $clause, $template);
    //                 } else {
    //                     $this->createUploadsForOldClients($request, $clause, $upload);
    //                 }
    //             }
    //         }
    //     }
    // }
    // private function createUploadsForOldClients($request, $clause, $old_upload)
    // {
    //     $user = $this->getUser();
    //     $project_id = $request->project_id;
    //     $consulting_id = $request->consulting_id;
    //     $client_id = $request->client_id;
    //     $standard_id = $request->standard_id;
    //     $section_id = $request->section_id;
    //     $upload = Upload::where([
    //         'client_id' => $client_id,
    //         'standard_id' => $standard_id,
    //         'section_id' => $section_id,
    //         'project_id' => $project_id,
    //         'consulting_id' => $consulting_id,
    //         'clause_id' => $old_upload->clause_id,
    //         'template_id' => $old_upload->template_id,
    //     ])->first();
    //     if (!$upload) {
    //         $upload = new Upload();
    //     }
    //     $upload->client_id = $client_id;
    //     $upload->standard_id = $standard_id;
    //     $upload->section_id = $section_id;
    //     $upload->project_id = $project_id;
    //     $upload->consulting_id = $consulting_id;
    //     $upload->clause_id = $clause->id;
    //     $upload->created_by = $user->id;
    //     $upload->template_id = $old_upload->template_id;
    //     $upload->template_title = $old_upload->template_title;
    //     $upload->template_link = $old_upload->template_link;
    //     $upload->save();
    // }
    // private function createNewUploads($request, $clause, $template)
    // {
    //     $user = $this->getUser();
    //     $project_id = $request->project_id;
    //     $consulting_id = $request->consulting_id;
    //     $client_id = $request->client_id;
    //     $standard_id = $request->standard_id;
    //     $section_id = $request->section_id;
    //     $upload = Upload::where([
    //         'client_id' => $client_id,
    //         'standard_id' => $standard_id,
    //         'section_id' => $section_id,
    //         'project_id' => $project_id,
    //         'consulting_id' => $consulting_id,
    //         'clause_id' => $clause->id,
    //         'template_id' => $template->id,
    //     ])->first();
    //     if (!$upload) {
    //         $upload = new Upload();
    //     }
    //     $upload->client_id = $client_id;
    //     $upload->standard_id = $standard_id;
    //     $upload->section_id = $section_id;
    //     $upload->project_id = $project_id;
    //     $upload->consulting_id = $consulting_id;
    //     $upload->clause_id = $clause->id;
    //     $upload->created_by = $user->id;
    //     $upload->template_id = $template->id;
    //     $upload->template_title = $template->title;
    //     $upload->template_link = $template->link;
    //     $upload->save();
    // }
    // public function uploadClauseFile(Request $request)
    // {
    //     $client = $this->getClient();
    //     $upload = Upload::find($request->upload_id);
    //     $folder_key = $client->id;
    //     if ($request->file('file_uploaded') != null && $request->file('file_uploaded')->isValid()) {
    //         $file_name = 'file_for_clause' . $upload->clause_id . '_template' . $upload->template_id . "." . $request->file('file_uploaded')->guessClientExtension();
    //         $link = $request->file('file_uploaded')->storeAs('clients/' . $folder_key . '/document', $file_name, 'public');
    //         // $media = $request->file('file_uploaded');
    //         // $file_name = 'file_for_clause_' . $upload->id . '_' . time() . "." . $request->file('file_uploaded')->guessClientExtension();
    //         // $link = $this->uploadFile($media, $file_name, $folder_key);
    //         $upload->link = $link;
    //         $upload->save();

    //         $user = $this->getUser();
    //         $clause = Clause::find($upload->clause_id);
    //         $name = $user->name . ' (' . $user->email . ')';
    //         $title = "Document Uploaded";
    //         //log this event
    //         $description = "$name uploaded document for clause: $clause->name";
    //         $this->auditTrailEvent($title, $description, $user);

    //         return $link;
    //     }
    // }
    // public function uploadDocumentTemplate(Request $request)
    // {
    //     $clause_id = $request->clause_id;
    //     $section_id = $request->section_id;
    //     $title = $request->title;
    //     $template = new DocumentTemplate();
    //     if ($request->file('file_uploaded') != null && $request->file('file_uploaded')->isValid()) {
    //         $formated_name = str_replace(' ', '_', ucwords($title));
    //         $file_name = $formated_name . '_template_' . time() . "." . $request->file('file_uploaded')->guessClientExtension();
    //         $link = $request->file('file_uploaded')->storeAs('document_template', $file_name, 'public');

    //         DocumentTemplate::firstOrCreate([
    //             'title' => $title,
    //             'clause_id' => $clause_id,
    //             'section_id' => $section_id,

    //         ], ['link' => $link]);
    //         $template->save();
    //     }
    // }


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

    public function viewClauseSections(Request $request)
    {
        $sections = ClauseSection::where('clause_id', $request->clause_id)->get();
        return response()->json(compact('sections'), 200);
    }

    public function saveSection(Request $request)
    {
        $clause_id = $request->clause_id;
        $section_details = json_decode(json_encode($request->section_details));
        foreach ($section_details as $detail) {
            ClauseSection::firstOrCreate([
                'name' => $detail->name,
                'clause_id' => $clause_id,
            ], ['description' => $detail->description]);

        }
        return response()->json(['message' => 'Successful'], 200);
    }

    public function updateSection(Request $request, ClauseSection $section)
    {
        $section->name = $request->name;
        $section->clause_id = $request->clause_id;
        $section->description = $request->description;
        $section->save();
        return response()->json(compact('sections'), 200);
    }

    public function destroySection(ClauseSection $section)
    {
        $section->delete();
        return response()->json([], 204);
    }
}
