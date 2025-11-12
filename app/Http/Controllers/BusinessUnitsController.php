<?php

namespace App\Http\Controllers;

use App\Models\BusinessProcess;
use App\Models\BusinessUnit;
use App\Models\BCMS\BiaTimeRecoveryRequirement;
use App\Models\Client;
use App\Models\OtherUnitsUser;
use App\Models\RiskImpactArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessUnitsController extends Controller
{
    //
    public function fetchBusinessUnits(Request $request)
    {
        $client_id = $this->getClient()->id;
        // $data = $request->validate([
        //     'client_id' => 'required|integer|exists:clients,id',
        // ]);

        $this->saveDefaultBiaTimeRecoveryRequirement($client_id);

        $business_units = BusinessUnit::with('teamMembers')
            ->withCount('businessProcesses')
            ->where('client_id', $client_id)
            ->get();
        return response()->json(compact('business_units'), 200);
    }

    public function fetchBusinessProcesses(Request $request)
    {
        $client = $this->getClient();
        // $business_unit_id = $request->business_unit_id;
        $data = $request->validate([
            'business_unit_id' => 'required|integer|exists:business_units,id'
        ]);
        $business_unit = BusinessUnit::findOrFail($data['business_unit_id']);

        // Optional ownership check: ensure business_unit belongs to provided client if client_id provided in request
        if ($business_unit->client_id != $client->id) {
            return response()->json(['message' => 'Unauthorized business unit'], 403);
        }
        $business_processes = BusinessProcess::where('business_unit_id', $business_unit->id)->get();
        return response()->json(compact('business_processes'), 200);
    }
    public function saveBusinessUnits(Request $request)
    {
        $client_id = $this->getClient()->id;
        $data = $request->validate([
            'business_units' => 'required|array|min:1',
            'business_units.*.group_name' => 'required|string|max:255',
            'business_units.*.unit_name' => 'required|string|max:255',
            'business_units.*.function_performed' => 'nullable|string|max:2000',
            'business_units.*.contact_phone' => 'nullable|string|max:50',
        ]);

        $this->saveDefaultBiaTimeRecoveryRequirement($client_id);

        $business_units = $data['business_units'];
        foreach ($business_units as $bu) {
            BusinessUnit::firstOrCreate([
                'client_id' => $client_id,
                'group_name' => $bu['group_name'],
                'unit_name' => $bu['unit_name'],
            ], [
                // 'teams' => $bu['teams'] ?? null,
                'function_performed' => $bu['function_performed'] ?? null,
                'contact_phone' => $bu['contact_phone'] ?? null,
                'access_code' => randomcode(), // helper: app/Http/helpers.php
                'prepend_risk_no_value' => acronym($bu['unit_name']), // helper
            ]);
        }
        return response()->json(['message' => 'Successful'], 200);
    }
    public function saveBusinessProcesses(Request $request)
    {
        $client = $this->getClient();
        $data = $request->validate([
            'business_unit_id' => 'required|integer|exists:business_units,id',
            'name' => 'required|string|max:1000',
            'process_owner' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'roles_responsible' => 'nullable|string',
            'no_of_people_involved' => 'nullable|integer|min:0',
            'minimum_no_of_people_involved' => 'nullable|integer|min:0',
            'product_or_service_delivered' => 'nullable|string',
            'regulatory_obligations' => 'nullable|string',
            'applications_used' => 'nullable|string',
            'teams' => 'nullable|string',
            'business_units_depended_on' => 'nullable|array',
            'processes_depended_on' => 'nullable|string',
            'key_vendors_or_external_dependencies' => 'nullable|string',
            'vital_non_electronic_records' => 'nullable|string',
            'vital_electronic_records' => 'nullable|string',
            'alternative_workaround_during_system_failure' => 'nullable|string',
            'key_individuals_process_depends_on' => 'nullable|string',
            'peak_periods' => 'nullable|string',
            'remote_working' => 'nullable|boolean',
        ]);

        $business_unit = BusinessUnit::findOrFail($data['business_unit_id']);

        // Optional ownership check: ensure business_unit belongs to provided client if client_id provided in request
        if ($business_unit->client_id != $client->id) {
            return response()->json(['message' => 'Unauthorized business unit'], 403);
        }

        BusinessProcess::firstOrCreate([
            'generated_process_id' => $business_unit->id . '.' . $business_unit->next_process_id,
            'client_id' => $business_unit->client_id,
            'business_unit_id' => $business_unit->id,
            'name' => $data['name'],
            'process_owner' => $data['process_owner'] ?? null,
            'objective' => $data['name'],
            'description' => $data['description'] ?? null,
            'roles_responsible' => $data['roles_responsible'] ?? null,
            'no_of_people_involved' => $data['no_of_people_involved'] ?? null,
            'minimum_no_of_people_involved' => $data['minimum_no_of_people_involved'] ?? null,
            'product_or_service_delivered' => $data['product_or_service_delivered'] ?? null,
            'regulatory_obligations' => $data['regulatory_obligations'] ?? null,
            'applications_used' => $data['applications_used'] ?? null,
            'teams' => $data['teams'] ?? null,
            'business_units_depended_on' => isset($data['business_units_depended_on']) ? implode(',', $data['business_units_depended_on']) : null,
            'processes_depended_on' => $data['processes_depended_on'] ?? null,
            'key_vendors_or_external_dependencies' => $data['key_vendors_or_external_dependencies'] ?? null,
            'vital_non_electronic_records' => $data['vital_non_electronic_records'] ?? null,
            'vital_electronic_records' => $data['vital_electronic_records'] ?? null,
            'alternative_workaround_during_system_failure' => $data['alternative_workaround_during_system_failure'] ?? null,
            'key_individuals_process_depends_on' => $data['key_individuals_process_depends_on'] ?? null,
            'peak_periods' => $data['peak_periods'] ?? null,
            'remote_working' => $data['remote_working'] ?? null,
        ]);

        $business_unit->increment('next_process_id');
        return response()->json(['message' => 'Successful'], 200);
    }

    public function updateBusinessUnit(Request $request, BusinessUnit $unit)
    {
        $client = $this->getClient();
        // Ownership check: ensure business_unit belongs to provided client if client_id provided in request
        if ($unit->client_id != $client->id) {
            return response()->json(['message' => 'Unauthorized action on business unit'], 403);
        }
        $unit->group_name = $request->group_name;
        $unit->unit_name = $request->unit_name;
        $unit->function_performed = $request->function_performed;
        $unit->teams = $request->teams;
        $unit->contact_phone = $request->contact_phone;
        $unit->save();
        return response()->json(compact('unit'), 200);
    }
    public function deleteBusinessUnit(Request $request, BusinessUnit $unit)
    {
        $client = $this->getClient();
        // Ownership check: ensure business_unit belongs to provided client if client_id provided in request
        if ($unit->client_id != $client->id) {
            return response()->json(['message' => 'Unauthorized action on business unit'], 403);
        }
        // prevent deletion if there are assets referencing this type
        $dependentCount = BusinessProcess::where('business_unit_id', $unit->id)->count();
        if ($dependentCount > 0) {
            abort(409, 'Business unit has dependent processes; remove or reassign them first');
        }
        $actor = $this->getUser();
        $title = "Business Unit Deletion";
        $description = "$actor->name deleted $unit->unit_name from the list of business units.";
        $this->auditTrailEvent($title, $description);
        $unit->delete();
        return response()->json([], 204);
    }

    public function updateBusinessProcess(Request $request, BusinessProcess $process)
    {
        $client = $this->getClient();
        // Ownership check: ensure business_unit belongs to provided client if client_id provided in request
        if ($process->client_id != $client->id) {
            return response()->json(['message' => 'Unauthorized action on business process'], 403);
        }
        $process->update([
            'name' => $request->name,
            'process_owner' => $request->process_owner,
            'objective' => $request->name,
            'description' => $request->description,
            'roles_responsible' => $request->roles_responsible,
            'no_of_people_involved' => $request->no_of_people_involved,

            'minimum_no_of_people_involved' => $request->minimum_no_of_people_involved,

            'product_or_service_delivered' => $request->product_or_service_delivered,
            'regulatory_obligations' => $request->regulatory_obligations,
            'applications_used' => $request->applications_used,
            'teams' => $request->teams,
            'business_units_depended_on' => implode(',', $request->business_units_depended_on),

            'processes_depended_on' => $request->processes_depended_on,

            'key_vendors_or_external_dependencies' => $request->key_vendors_or_external_dependencies,

            'vital_non_electronic_records' => $request->vital_non_electronic_records,
            'vital_electronic_records' => $request->vital_electronic_records,

            'alternative_workaround_during_system_failure' => $request->alternative_workaround_during_system_failure,

            'key_individuals_process_depends_on' => $request->key_individuals_process_depends_on,

            'peak_periods' => $request->peak_periods,
            'remote_working' => $request->remote_working,

        ]);
        // $process->name = $request->name;
        // $process->description = $request->description;
        // $process->roles_responsible = $request->roles_responsible;
        // $process->no_of_people_involved = $request->no_of_people_involved;
        // $process->minimum_no_of_people_involved = $request->minimum_no_of_people_involved;
        // $process->save();
        return response()->json(compact('process'), 200);
    }
    public function refreshAccessCode(Request $request, BusinessUnit $business_unit)
    {
        $client = $this->getClient();
        // Ownership check: ensure business_unit belongs to provided client if client_id provided in request
        if ($business_unit->client_id != $client->id) {
            return response()->json(['message' => 'Unauthorized action on business unit'], 403);
        }
        $business_unit->access_code = randomcode();
        $business_unit->save();
        return response()->json(compact('business_unit'), 200);

    }
    public function fetchOtherUsers(Request $request)
    {
        $users = OtherUnitsUser::with('businessUnit')->where('business_unit_id', $request->business_unit_id)->get();
        return response()->json(compact('users'), 200);
    }
    public function saveOtherUser(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'business_unit_id' => 'required|integer|exists:business_units,id',
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
        ]);

        OtherUnitsUser::firstOrCreate([
            'client_id' => $data['client_id'],
            'business_unit_id' => $data['business_unit_id'],
            'email' => $data['email'],
        ], [
            'name' => $data['name'],
        ]);

        return response()->json('success', 200);
    }

    public function updateOtherUser(Request $request, OtherUnitsUser $user)
    {
        $data = $request->validate([
            'business_unit_id' => 'required|integer|exists:business_units,id',
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
        ]);

        $user->business_unit_id = $data['business_unit_id'];
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->save();
        return response()->json('success', 200);
    }


    public function getBiaTimeRecoveryRequirement(Request $request)
    {
        $client_id = $this->getClient()->id;
        $this->saveDefaultBiaTimeRecoveryRequirement($client_id);
        $time_recovery_requirements = BiaTimeRecoveryRequirement::where([
            'client_id' => $client_id,
        ])->get();
        return response()->json(compact('time_recovery_requirements'), 200);
    }

    private function saveDefaultBiaTimeRecoveryRequirement($client_id)
    {
        $impact_criteria = BiaTimeRecoveryRequirement::where([
            'client_id' => $client_id
        ])->count();
        if ($impact_criteria < 1) {

            $default_time_requirements = defaultBiaTimeRecoveryRequirement();
            foreach ($default_time_requirements as $default_time_requirement) {
                BiaTimeRecoveryRequirement::firstOrCreate([
                    'client_id' => $client_id,
                    'name' => $default_time_requirement['name'],
                    'time_in_minutes' => $default_time_requirement['time_in_minutes'],
                ]);
            }
        }
    }
    public function saveBiaTimeRecoveryRequirement(Request $request)
    {
        $client_id = $request->client_id;
        BiaTimeRecoveryRequirement::firstOrCreate([
            'client_id' => $client_id,
            'name' => $request->name,
            'time_in_minutes' => $request->time_in_minutes,
        ]);
        return response()->json(['message' => 'Successful'], 200);
    }

    public function updateBiaTimeRecoveryRequirement(Request $request, BiaTimeRecoveryRequirement $criteria)
    {
        $client = $this->getClient();
        // Ownership check: ensure business_unit belongs to provided client if client_id provided in request
        if ($criteria->client_id != $client->id) {
            return response()->json(['message' => 'Unauthorized action on resource'], 403);
        }
        $field = $request->field;
        $value = $request->value;
        $criteria->$field = $value;
        $criteria->save();
        return response()->json(compact('criteria'), 200);
    }
    public function deleteBiaTimeRecoveryRequirement(BiaTimeRecoveryRequirement $criteria)
    {
        $client = $this->getClient();
        // Ownership check: ensure business_unit belongs to provided client if client_id provided in request
        if ($criteria->client_id != $client->id) {
            return response()->json(['message' => 'Unauthorized action on resource'], 403);
        }
        $criteria->delete();
        return response()->json([], 204);
    }
    public function uploadProcessFlow(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:business_processes,id',
            'file_uploaded' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $business_process = BusinessProcess::findOrFail($data['id']);
        $client = Client::findOrFail($business_process->client_id);
        $folder_key = str_replace(' ', '_', ucwords($client->name));

        if ($request->file('file_uploaded') != null && $request->file('file_uploaded')->isValid()) {
            // safe deletion: check existence and ensure stored path is inside expected folder
            if ($business_process->flow_chart_diagram !== null && Storage::disk('public')->exists($business_process->flow_chart_diagram)) {
                Storage::disk('public')->delete($business_process->flow_chart_diagram);
            }

            // use store() with folder to avoid trusting client filenames
            $link = $request->file('file_uploaded')->store('clients/' . $folder_key . '/business-process-flow', 'public');
            $business_process->flow_chart_diagram = $link;
            $business_process->save();
        }

        return response()->json(['message' => 'Uploaded'], 200);
    }
    public function changeProcessStatus(Request $request, BusinessProcess $process)
    {
        $client = $this->getClient();
        // Ownership check: ensure business_unit belongs to provided client if client_id provided in request
        if ($process->client_id != $client->id) {
            return response()->json(['message' => 'Unauthorized action on resource'], 403);
        }
        $data = $request->validate([
            'status' => 'required|string|in:active,inactive,archived,pending',
        ]);

        $process->status = $data['status'];
        $process->save();
        return response()->json([], 204);
    }
}
