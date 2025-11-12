<?php

namespace App\Http\Controllers\ISMS;

use App\Http\Controllers\Controller;
use App\Http\Resources\IncidentTypeResource;
use App\Models\ISMS\IncidentType;
use Illuminate\Http\Request;

class IncidentTypeController extends Controller
{
    public function index()
    {

        return IncidentTypeResource::collection(IncidentType::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);
        $incidentType = IncidentType::create($validated);

        return new IncidentTypeResource($incidentType);
    }

    public function show(IncidentType $incidentType)
    {
        return new IncidentTypeResource($incidentType);
    }

    public function update(Request $request, IncidentType $incidentType)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);

        $incidentType->update($validated);

        return new IncidentTypeResource($incidentType);
    }

    public function destroy(IncidentType $incidentType)
    {
        $incidentType->delete();

        return response()->json(['message' => 'Incident type deleted successfully']);
    }
}
