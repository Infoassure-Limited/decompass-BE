<?php

namespace App\Http\Controllers;

use App\Models\AvailableModule;
use App\Models\ClientProjectPlan;
use App\Models\GeneralProjectPlan;
use App\Models\Project;
use App\Models\ProjectPhase;
use App\Models\Standard;
use Illuminate\Http\Request;

class ProjectPlanController extends Controller
{
    //
    public function fetchProjectPhases(Request $request)
    {
        // $partner_id = $this->getPartner()->id;
        $module_id = $request->module_id;
        $project_phases = ProjectPhase::with('module')->where(['module_id' => $module_id])->get();
        return response()->json(compact('project_phases'), 200);
    }
    public function fetchClientProjectPlan(Request $request)
    {
        $client_id = $request->client_id;
        $project_id = $request->project_id;
        // $partner_id = $this->getPartner()->id;
        $project_phases = ClientProjectPlan::with('generalProjectPlan')
            ->join('project_phases', 'project_phases.id', 'client_project_plans.project_phase_id')
            ->where([/*'client_project_plans.partner_id' => $partner_id,*/ 'client_id' => $client_id, 'project_id' => $project_id])
            ->select('*', 'client_project_plans.id as id')
            ->get()
            ->groupBy('title');
        return response()->json(compact('project_phases'), 200);
    }
    public function storeProjectPhases(Request $request)
    {
        $partner_id = $this->getPartner()->id;
        $titles = $request->titles;
        $standard_ids = $request->standard_ids;
        $titles_array = explode('|', $titles);
        foreach ($standard_ids as $standard_id) {

            foreach ($titles_array as $title) {
                ProjectPhase::firstOrCreate([
                    'standard_id' => $standard_id,
                    'partner_id' => $partner_id,
                    'title' => trim($title)
                ]);
            }
        }
        return response()->json(['message' => 'Successful'], 200);
    }
    public function updateProjectPhases(Request $request, ProjectPhase $project_phase)
    {
        //
        $project_phase->title = $request->title;
        $project_phase->save();
        return response()->json(['message' => 'Successful'], 200);
    }
    public function destroyProjectPhases(Request $request, ProjectPhase $project_phase)
    {
        //
        $project_phase->delete();
        return response()->json([], 204);
    }

    //////////////////////Project General plan///////////////////////////////
    public function fetchGeneralProjectPlan(Request $request)
    {
        $module_id = $request->module_id;
        $module = AvailableModule::with('generalProjectPlans.projectPhase')->find($module_id);
        // $project_phase_id = $request->project_phase_id;
        $general_project_plans = $module->generalProjectPlans;
        return response()->json(compact('general_project_plans'), 200);
    }
    public function storeGeneralProjectPlans(Request $request)
    {
        $project_phase_id = $request->project_phase_id;
        $module_id = $request->module_id;


        $details = json_decode(json_encode($request->details));
        foreach ($details as $detail) {
            $plan = GeneralProjectPlan::firstOrCreate([
                'task' => $detail->task,
                'responsibility' => $detail->responsibility,
                'resource' => $detail->resource,
                'project_phase_id' => $project_phase_id,
            ]);
            $plan->availableModules()->sync($module_id);
        }
        return response()->json(['message' => 'Successful'], 200);
    }
    public function linkStandardtoProjectPlan(Request $request, GeneralProjectPlan $project_plan)
    {
        $project_plan->standards()->syncWithoutDetaching($request->standard_ids);
        return response()->json([], 204);
    }
    public function unlinkStandardFromProjectPlan(Request $request, GeneralProjectPlan $project_plan)
    {
        $project_plan->standards()->detach($request->standard_id);
        return response()->json([], 204);
    }

    public function updateProjectPlan(Request $request, GeneralProjectPlan $project_plan)
    {
        //
        $project_plan->task = $request->task;
        $project_plan->responsibility = $request->responsibility;
        $project_plan->resource = $request->resource;
        $project_plan->save();
        return response()->json(['message' => 'Successful'], 200);
    }

    public function destroyProjectPlan(Request $request, GeneralProjectPlan $project_plan)
    {
        //
        $project_plan->delete();
        return response()->json([], 204);
    }

    //////////////////////Client Project plan///////////////////////////////

    public function storeClientProjectPlan(Request $request)
    {
        $partner_id = $this->getPartner()->id;
        $client_id = $request->client_id;
        $project_id = $request->project_id;
        $standard_id = $request->standard_id;
        $standard = Standard::with('generalProjectPlans')->find($standard_id);
        $general_project_plans = $standard->generalProjectPlans;

        foreach ($general_project_plans as $general_project_plan) {
            ClientProjectPlan::firstOrCreate([
                'partner_id' => $partner_id,
                'client_id' => $client_id,
                'project_id' => $project_id,
                'project_phase_id' => $general_project_plan->project_phase_id,
                'general_project_plan_id' => $general_project_plan->id,
            ]);
        }
    }
    public function updateClientProjectPlanFields(Request $request, ClientProjectPlan $client_project_plan)
    {
        $field = $request->field;
        $value = $request->value;
        $client_project_plan->$field = $value;
        $client_project_plan->save();
        $this->calculateProjectProgress($client_project_plan->project_id);
        return 'success';
    }
    private function calculateProjectProgress($projectId)
    {
        $project = Project::with('clientProjectPlans')->find($projectId);
        $no_of_plans = $project->clientProjectPlans()->count();
        $total_progress = 0;
        foreach ($project->clientProjectPlans as $clientProjectPlan) {
            $total_progress += $clientProjectPlan->progress;
        }
        $percent_completion = ($no_of_plans > 0) ? $total_progress / $no_of_plans : 0;
        $project->progress = $percent_completion;
        $project->save();
    }
}
