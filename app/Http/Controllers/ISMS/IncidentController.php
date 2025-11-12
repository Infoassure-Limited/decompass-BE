<?php

namespace App\Http\Controllers\ISMS;

use App\Http\Controllers\Controller;
use App\Http\Resources\IncidentResource;
use App\Models\ISMS\ImmediateResolutionAction;
use App\Models\ISMS\Incident;
use App\Models\ISMS\IncidentActivityLog;
use App\Models\ISMS\IncidentRootCauseAnalysis;
use App\Models\ISMS\IncidentTask;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $client_id = $this->getClient()->id;
        $query = Incident::query()->with('incidentType')->where('client_id', $client_id);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by incident type
        if ($request->has('incident_type_id')) {
            $query->where('incident_type_id', $request->incident_type_id);
        }

        // Filter by assigned user
        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Filter by reported by
        if ($request->has('reported_by')) {
            $query->where('reported_by', $request->reported_by);
        }

        // Sort results
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Paginate results
        $perPage = $request->input('per_page', 15);
        $incidents = $query->paginate($perPage);

        return IncidentResource::collection($incidents);
    }

    public function store(Request $request)
    {
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $request->validate([
            'file' => 'nullable|file|max:10240',
        ]);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'incident_type_id' => 'required|exists:isms.incident_types,id',
            'assigned_to' => 'nullable|exists:users,id',
            'occurred_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'affected_assets' => 'required|array'
        ]);

        $validated['client_id'] = $client_id;
        $validated['reported_by'] = $user_id;


        $incident = Incident::firstOrCreate($validated);

        $incident->incident_no = 'INC-' . $incident->id . randomNumber(5);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('client/' . $client_id . '/isms-incident-evidence/' . $incident->id, $fileName, 'public');
        $incident->evidence_link = $filePath;
        $incident->save();

        // Log activity
        IncidentActivityLog::create([
            'user_id' => $user_id,
            'client_id' => $client_id,
            'incident_id' => $incident->id,
            'action' => 'created',
            'changes' => $validated,
        ]);

        return new IncidentResource($incident);
    }

    public function fetchResolutionActions(Incident $incident)
    {
        $client_id = $this->getClient()->id;
        $resolution_actions = ImmediateResolutionAction::where('incident_id', $incident->id)
            ->where('client_id', $client_id)
            ->get();
        return response()->json(compact('resolution_actions'), 200);
    }

    public function storeResolutionAction(Request $request)
    {
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $incident_id = $request->incident_id;
        $validated = $request->validate([
            'immediate_action_taken' => 'required|string',
            'is_escalated' => 'required|string|in:Yes,No',
            'escalation_details' => 'nullable|string',
            'deadline' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        ImmediateResolutionAction::create([
            'incident_id' => $incident_id,
            'client_id' => $client_id,
            'immediate_action_taken' => $validated['immediate_action_taken'],
            'is_escalated' => $validated['is_escalated'],
            'escalation_details' => $validated['escalation_details'],
            'deadline' => $validated['deadline'],
            'assigned_to' => $validated['assigned_to'],
        ]);
        // $incident->update($validated);

        // Log activity
        IncidentActivityLog::create([
            'user_id' => $user_id,
            'client_id' => $client_id,
            'incident_id' => $incident_id,
            'action' => 'resolution_action_added',
            'changes' => $validated,
        ]);

        // return new IncidentResource($incident);
    }
    public function show(Incident $incident)
    {
        return new IncidentResource($incident->load(['incidentType', 'reporter', 'reviewer', 'assignee']));
    }

    public function assignUser(Request $request, Incident $incident)
    {
        $oldAssignee = $incident->assigned_to;
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $incident->update($validated);

        // Log activity
        IncidentActivityLog::create([
            'user_id' => $user_id,
            'client_id' => $client_id,
            'incident_id' => $incident->id,
            'action' => 'assigned',
            'changes' => [
                'old' => ['assigned_to' => $oldAssignee],
                'new' => ['assigned_to' => $validated['assigned_to']],
            ],
        ]);

        return new IncidentResource($incident);
    }

    public function update(Request $request, Incident $incident)
    {
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $oldData = $incident->toArray();

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'incident_type_id' => 'sometimes|required|exists:incident_types,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'nullable|in:open,investigating,resolved,closed,reopened',
            'priority' => 'nullable|in:low,medium,high,critical',
            'occurred_at' => 'sometimes|required|date',
            'location' => 'nullable|string|max:255'
        ]);

        $incident->update($validated);

        // Log activity
        IncidentActivityLog::create([
            'user_id' => $user_id,
            'client_id' => $client_id,
            'incident_id' => $incident->id,
            'action' => 'updated',
            'changes' => [
                'old' => $oldData,
                'new' => $incident->toArray(),
            ],
        ]);

        return new IncidentResource($incident);
    }
    public function updateFields(Request $request, Incident $incident)
    {
        // $this->authorize('submitForReview', $policy);
        $field = $request->field;
        $value = $request->value;

        $incident->$field = $value;
        $incident->save();

        // $details = 'Policy submitted for review';
        // $this->createPolicyAudit($policy->id, $details);

        return new IncidentResource($incident);
    }
    public function closeIncident(Request $request, Incident $incident)
    {
        $incident->status = 'Closed';
        $incident->closure_date = date('Y-m-d H:i:s', strtotime('now'));
        $incident->save();

        // $details = 'Policy submitted for review';
        // $this->createPolicyAudit($policy->id, $details);

        return new IncidentResource($incident);
    }
    public function destroy(Incident $incident)
    {
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $incident->delete();

        // Log activity
        IncidentActivityLog::create([
            'user_id' => $user_id,
            'client_id' => $client_id,
            'incident_id' => $incident->id,
            'action' => 'deleted',
            'changes' => $incident->toArray(),
        ]);

        return response()->json(['message' => 'Incident deleted successfully']);
    }

    public function uploadTaskEvidence(Request $request)
    {
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $validated = $request->validate([
            'task_id' => 'required|exists:isms.incident_tasks,id',
            'incident_id' => 'required|exists:isms.incidents,id',
            'file' => 'required|file|max:10240', // 10MB max
        ]);
        $task = IncidentTask::find($validated['task_id']);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('client/' . $client_id . '/isms-incident-task-evidence/' . $task->id, $fileName, 'public');

        $evidence = $task->evidences()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'comments' => $request->comments,
            'user_id' => $user_id,
            'client_id' => $client_id,
            'incident_id' => $validated['incident_id']
        ]);

        return response()->json(compact('evidence'), 200);
    }

    public function storeIncidentTask(Request $request, Incident $incident)
    {
        $data = $request->toArray();
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $data['client_id'] = $client_id;
        $task = $incident->tasks()->create([
            $data
        ]);

        return response()->json(compact('task'), 200);
    }


    public function fetchTasks(Request $request)
    {
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $incident_id = $request->incident_id;
        // Log activity
        $tasks = IncidentTask::where([
            'client_id' => $client_id,
            'incident_id' => $incident_id
        ])->get();

        return response()->json(compact('tasks'), 200);
    }

    public function storeTask(Request $request)
    {
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $data = $request->toArray();
        $data['client_id'] = $client_id;
        // Log activity
        $task = IncidentTask::create($data)->get();

        return response()->json(compact('task'), 200);
    }
    public function showTask(IncidentTask $task)
    {
        $task->load('incident', 'assignee', 'evidences')->find($task->id);
        return response()->json(compact('task'), 200);
    }


    public function assignUserToTask(Request $request, IncidentTask $task)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $task->update($validated);
    }
    public function updateTaskFields(Request $request, IncidentTask $task)
    {
        // $this->authorize('submitForReview', $policy);
        $field = $request->field;
        $value = $request->value;

        $task->$field = $value;
        $task->save();
    }

    public function fetchRCA(Request $request)
    {
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $incident_id = $request->incident_id;
        // Log activity
        $rcas = IncidentRootCauseAnalysis::where([
            'client_id' => $client_id,
            'incident_id' => $incident_id
        ])->get();

        return response()->json(compact('rcas'), 200);
    }

    public function storeRCA(Request $request)
    {
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $incident_id = $request->incident_id;
        $validated = $request->validate([
            'description' => 'required|string',
            'impact_of_the_incident' => 'required|string',
            'preventive_measures' => 'required|string',
            'follow_up_required' => 'required',
            'method' => 'required'
        ]);
        $validated['client_id'] = $client_id;
        $validated['incident_id'] = $incident_id;
        $validated['created_by'] = $user_id;
        // Log activity
        $rcas = IncidentRootCauseAnalysis::create($validated);

        return response()->json(compact('rcas'), 200);
    }
}
