<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use App\Http\Requests\TaskRequest;

class TasksController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $tasks = $user->assignedTasks()->latest()->paginate(10);
        return TaskResource::collection($tasks);
    }

    public function store(TaskRequest $request)
    {
        $user = $this->getUser();
        $client_id = $this->getClient()->id;
        $validated = $request->validated();
        $validated['client_id'] = $client_id;
        $validated['assigned_by'] = $user->id;

        $task = $request->user()->assignedTasks()->create($validated);
        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        $this->authorizeTask($task);
        return new TaskResource($task);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $validated = $request->validated();

        $task->update($validated);
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $this->authorizeTask($task);
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }

    protected function authorizeTask(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }
}