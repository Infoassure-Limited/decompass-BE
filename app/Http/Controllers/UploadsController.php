<?php

namespace App\Http\Controllers;

use App\Models\Clause;
use App\Models\Client;
use App\Models\DocumentTemplate;
use App\Models\ISMS\AssignedTask;
use App\Models\NDPA\AssignedTask as NDPAAssignedTask;
use App\Models\Upload;
use Illuminate\Http\Request;

class UploadsController extends Controller
{
    public function __construct(Request $httpRequest)
    {
        parent::__construct($httpRequest);
        $this->middleware(function ($request, $next) {

            $this->setExpectedUploadsFromISMSAssignedTasks();
            $this->setExpectedUploadsFromNDPAAssignedTasks();
            return $next($request);
        });


    }
    public function fetchUploads(Request $request)
    {

        $client_id = $this->getClient()->id;
        $year = $this->getYear();
        if (isset($request->year) && $request->year != '') {
            $year = $request->year;
        }
        if (isset($request->title) && $request->title != '') {

            $uploads = Upload::join('document_templates', 'uploads.template_id', '=', 'document_templates.id')
                ->where('uploads.client_id', $client_id)
                ->where('title', 'LIKE', "%$request->title%")
                ->where('uploads.created_at', 'LIKE', '%' . $year . '%')
                ->orderBy('title')
                ->select('uploads.*', 'document_templates.first_letter', 'document_templates.title')
                ->get()
                ->groupBy('first_letter');
        } else {

            $uploads = Upload::join('document_templates', 'uploads.template_id', '=', 'document_templates.id')
                ->where('uploads.client_id', $client_id)
                ->where('uploads.created_at', 'LIKE', '%' . $year . '%')
                ->orderBy('title')
                ->select('uploads.*', 'document_templates.first_letter', 'document_templates.title')
                ->get()
                ->groupBy('first_letter');
        }

        return response()->json(compact('uploads'), 200);
    }
    private function setExpectedUploadsFromISMSAssignedTasks()
    {
        $client = $this->getClient();
        $task = AssignedTask::join('module_activity_tasks', 'assigned_tasks.module_activity_task_id', '=', 'module_activity_tasks.id')
            ->where('assigned_tasks.client_id', $client->id)
            ->where('evidences', '!=', NULL)
            ->select(\DB::raw("GROUP_CONCAT(DISTINCT evidences SEPARATOR ', ') as task_evidences"))
            ->first();
        if ($task) {
            $templated_ids_array = [];
            $task_evidences_array = explode(', ', $task->task_evidences);
            foreach ($ids_array as $id) {
                $id_array = json_decode($id);
                if ($id_array != null && count($id_array) > 0 && is_array($id_array)) {
                    $templated_ids_array = array_unique(array_merge($templated_ids_array, $id_array));
                }

            }

            $templates = DocumentTemplate::whereIn('id', $templated_ids_array)->get();
            if ($templates->count() > 0) {

                $this->uploadTemplates($client->id, $templates);
            }
        }
    }
    private function setExpectedUploadsFromNDPAAssignedTasks()
    {
        $client = $this->getClient();
        $template_ids = NDPAAssignedTask::join('module_activity_tasks', 'assigned_tasks.module_activity_task_id', '=', 'module_activity_tasks.id')
            ->where('assigned_tasks.client_id', $client->id)
            ->where('document_template_ids', '!=', NULL)
            ->select(\DB::raw("GROUP_CONCAT(DISTINCT document_template_ids SEPARATOR ', ') as ids"))
            ->first();
        if ($template_ids) {
            $templated_ids_array = [];
            $ids_array = explode(', ', $template_ids->ids);
            foreach ($ids_array as $id) {
                $id_array = json_decode($id);
                if ($id_array != null && count($id_array) > 0 && is_array($id_array)) {
                    $templated_ids_array = array_unique(array_merge($templated_ids_array, $id_array));
                }

            }

            $templates = DocumentTemplate::whereIn('id', $templated_ids_array)->get();
            if ($templates->count() > 0) {
                $this->uploadTemplates($client->id, $templates);
            }
        }
    }

    private function uploadTemplates($client_id, $templates)
    {
        foreach ($templates as $template) {
            Upload::updateOrCreate([
                'client_id' => $client_id,
                'template_id' => $template->id,
            ], [
                'template_link' => $template->link
            ]);
        }

    }
    /**
     * Display a listing of the resource.
     */
    // public function createUploads(Request $request)
    // {
    //     $last_year = $this->getYear() - 1;
    //     $client_id = $request->client_id;
    //     $templates = DocumentTemplate::orderBy('title')->get();
    //     // create answer
    //     if (!empty($templates)) {
    //         foreach ($templates as $template) {
    //             $upload = Upload::where([
    //                 'client_id' => $client_id
    //             ])->where('created_at', 'LIKE', '%' . $last_year . '%')->first();
    //             if (!$upload) {
    //                 $this->uploadTemplate($request, $template);
    //             } else {
    //                 $this->uploadTemplate($request, $upload);
    //             }
    //         }
    //     }
    // }
    // private function uploadTemplate($request, $template)
    // {
    //     $user = $this->getUser();
    //     $client_id = $request->client_id;
    //     $upload = Upload::where([
    //         'client_id' => $client_id,
    //         'title' => $template->title,
    //     ])->first();
    //     if (!$upload) {
    //         $upload = new Upload();
    //     }
    //     $upload->client_id = $client_id;
    //     $upload->created_by = $user->id;
    //     $upload->template_id = $template->id;
    //     $upload->title = $template->title;
    //     $upload->template_link = $template->link;
    //     $upload->save();
    // }
    public function uploadEvidenceFile(Request $request)
    {
        $client = $this->getClient();
        $upload = Upload::with('template')->find($request->upload_id);
        $doc_title = $upload->template->title;
        // $folder_key = $client->id;
        $folder_key = str_replace(' ', '_', ucwords($client->name));
        if ($request->file('file_uploaded') != null && $request->file('file_uploaded')->isValid()) {
            $formated_name = str_replace(' ', '_', ucwords($doc_title));
            $file_name = 'evidence_for_' . $formated_name . '_' . $client->id . '_template' . $upload->template_id . "." . $request->file('file_uploaded')->guessClientExtension();
            $link = $request->file('file_uploaded')->storeAs('clients/' . $folder_key . '/document', $file_name, 'public');
            $upload->link = $link;
            $upload->save();

            $user = $this->getUser();
            $users = $client->users;
            $userIds = $client->users()->pluck('id');
            $userIds = $userIds->toArray();

            $name = $user->name;// . ' (' . $user->email . ')';
            $title = "Document Uploaded";
            //log this event
            $description = "$name uploaded a document titled: $doc_title";
            $this->sendNotification($title, $description, $userIds);
            // $this->auditTrailEvent($title, $description, $users);

            return $link;
        }
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
        $description = "Remark was made on document title: $clause->title, clause: $clause->name uploaded by $client->name";
        $this->auditTrailEvent($title, $description);
    }

    public function fetchUploadedDocumentWithTemplateIds(Request $request)
    {
        $client_id = $this->getClient()->id;
        $year = $this->getYear();
        if (isset($request->year) && $request->year != '') {
            $year = $request->year;
        }
        $template_ids = json_decode(json_encode($request->template_ids), 1);
        $uploads = Upload::with('template')
            ->join('document_templates', 'uploads.template_id', '=', 'document_templates.id')
            ->where('uploads.client_id', $client_id)
            ->whereIn('template_id', $template_ids)
            ->where('uploads.created_at', 'LIKE', '%' . $year . '%')
            ->orderBy('title')
            ->select('uploads.*', 'document_templates.first_letter', 'document_templates.title', 'document_templates.link as template_link')
            ->get();
        return response()->json(compact('uploads'), 200);
    }
}
