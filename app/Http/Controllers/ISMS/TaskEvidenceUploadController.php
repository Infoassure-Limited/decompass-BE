<?php

namespace App\Http\Controllers\ISMS;

use App\Http\Controllers\Controller;
use App\Models\ISMS\AssignedTask;
use App\Models\ISMS\TaskEvidenceUpload;
use Illuminate\Http\Request;

class TaskEvidenceUploadController extends Controller
{
    // public function __construct(Request $httpRequest)
    // {
    //     parent::__construct($httpRequest);
    //     $this->middleware(function ($request, $next) {

    //         $this->setExpectedEvidencesFromTasks();
    //         return $next($request);
    //     });


    // }
    // private function setExpectedEvidencesFromTasks()
    // {
    //     $client = $this->getClient();
    //     $task = AssignedTask::join('module_activity_tasks', 'assigned_tasks.module_activity_task_id', '=', 'module_activity_tasks.id')
    //         ->where('assigned_tasks.client_id', $client->id)
    //         ->where('evidences', '!=', NULL)
    //         ->select('assigned_tasks.id as id', \DB::raw("GROUP_CONCAT(DISTINCT evidences SEPARATOR ', ') as task_evidences"))
    //         ->first();
    //     if ($task) {
    //         $task_evidences_array = explode(', ', $task->task_evidences);
    //         foreach ($task_evidences_array as $task_evidence) {
    //             TaskEvidenceUpload::firstOrCreate([
    //                 'client_id' => $client->id,
    //                 'assigned_task_id' => $task->id,
    //                 'title' => $task_evidence
    //             ]);

    //         }
    //     }
    // }
    /**
     * Display a listing of the resource.
     */
    public function uploadEvidenceFile(Request $request)
    {
        // Validate request
        $request->validate([
            'upload_id' => 'required|exists:isms.task_evidence_uploads,id',
            'file_uploaded' => [
                'required',
                'file',
                'max:10240', // 10MB max file size
                'mimes:pdf,doc,docx,xls,xlsx,txt,jpg,jpeg,png', // Allowed file types
            ]
        ]);

        $client = $this->getClient();
        $upload = TaskEvidenceUpload::find($request->upload_id);

        // Authorization check
        if ($upload->client_id != $client->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$request->hasFile('file_uploaded') || !$request->file('file_uploaded')->isValid()) {
            return response()->json(['message' => 'Invalid file upload'], 422);
        }

        try {
            $uploadedFile = $request->file('file_uploaded');
            $hash_title = hash('sha256', $upload->title . '-' . time());
            $folder_key = str_replace(' ', '_', ucwords($client->name));

            // Sanitize filename
            $formatedName = $this->sanitizeFileName($upload->title);
            $extension = $uploadedFile->guessClientExtension();
            $fileName = "{$formatedName}_{$hash_title}.{$extension}";

            // Store file
            $link = $uploadedFile->storeAs(
                "clients/{$folder_key}/evidences/ISMS",
                $fileName,
                'public'
            );

            // Update database
            $upload->update([
                'link' => $link,
                'file_type' => $extension,
                'file_size' => $uploadedFile->getSize(),
                'original_name' => $uploadedFile->getClientOriginalName()
            ]);

            // Send notification
            $this->notifyFileUpload($client, $upload);

            return response()->json([
                'message' => 'File uploaded successfully',
                'link' => $link
            ], 200);

        } catch (\Exception $e) {
            \Log::error('File upload failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'File upload failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sanitize filename to prevent security issues
     */
    private function sanitizeFileName(string $filename): string
    {
        $filename = str_replace(' ', '_', ucwords($filename));
        $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $filename);
        return preg_replace('/[^A-Za-z0-9\-_.]/', '', $filename);
    }

    /**
     * Send notification about file upload
     */
    private function notifyFileUpload($client, $upload)
    {
        $user = $this->getUser();
        $userIds = $client->users()->pluck('id')->toArray();

        $title = "Evidence Uploaded";
        $description = "{$user->name} uploaded a required evidence titled: {$upload->title}";

        $this->sendNotification($title, $description, $userIds);
    }
}
