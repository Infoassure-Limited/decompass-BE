<?php

namespace App\Http\Controllers;

use App\Models\ActivatedModule;
use App\Models\AvailableModule;
use App\Models\Client;
use App\Models\ClientProjectPlan;
use App\Models\ConsultantProject;
use App\Models\FeedBack;
use App\Models\GeneralProjectPlan;
use App\Models\ModuleFeature;
use App\Models\Project;
use App\Models\ProjectCertificate;
use App\Models\Standard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function fetchClientActivatedProjects(Request $request, Client $client)
    {
        $client = $this->getClient();
        $partner_id = $client->partner_id;
        $activated_modules = AvailableModule::join('activated_modules', 'available_modules.id', 'activated_modules.available_module_id')
            ->where('partner_id', $partner_id)
            ->select('available_modules.*')->with('standards')
            ->get();
        $all_modules = AvailableModule::with('standards')->orderBy('name')->get();
        $projects = $this->getMyProjects($client->id);
        foreach ($projects as $project) {
            $package = $project->package;
            $featureIds = $package->features;

            $module_slug = ModuleFeature::whereIn('id', $featureIds)->pluck('slug');
            $project->feature_slug = $module_slug;
        }
        // Project::with('availableModule', 'standard')
        //     ->where(['client_id' => $client->id, 'year' => $this->getYear()])
        //     ->orderBy('id', 'DESC')->get();
        return response()->json(compact('projects', 'activated_modules', 'all_modules'), 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $client = $this->getClient();
        // $consulting_id = $request->consulting_id;
        // $projects = Project::with('availableModule', 'certificate', 'standard')->where(['client_id' => $client->id/*, 'year' => $this->getYear()*/])->orderBy('id', 'DESC')->get(); //->paginate(10);
        $projects = $this->getMyProjects($client->id);
        return response()->json(compact('projects'), 200);
    }

    public function clientProjects(Request $request)
    {
        $user = $this->getUser();
        $year = $this->getYear();
        if (isset($request->year) && $request->year != '') {
            $year = (int) $request->year;
        }
        $condition = [];
        if ($user->haRole('partner') && !$user->haRole('super')) {
            $partner_id = $this->getPartner()->id;
            $condition = ['partner_id' => $partner_id];
        }
        if (isset($request->client_id) && $request->client_id != '') {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $client = Client::with('users')->find($client_id);
        $users = ($client) ? $client->users : [];
        // $consulting_id = $request->consulting_id;
        $projects = Project::with([
            'client',
            'certificate',
            'availableModule',
            'standard',
            'users',
            'consultants'
        ])->where($condition)->where(['client_id' => $client_id, 'year' => $year])->orderBy('id', 'DESC')->get(); //->paginate(10);
        return response()->json(compact('projects', 'users'), 200);
    }
    public function clientProjectCertificates(Request $request)
    {
        $client_id = $request->client_id;
        // $consulting_id = $request->consulting_id;
        $certificates = ProjectCertificate::with('project.standard')->where(['client_id' => $client_id])->orderBy('id', 'DESC')->get(); //->paginate(10);
        return response()->json(compact('certificates'), 200);
    }

    public function clientProjectFeedback(Request $request)
    {
        $client_id = $request->client_id;
        $project_id = $request->project_id;
        $feedbacks = FeedBack::where(['client_id' => $client_id, 'project_id' => $project_id])->get(); //->paginate(10);
        return response()->json(compact('feedbacks'), 200);
    }

    public function uploadProjectCertificate(Request $request)
    {
        $projectCert = ProjectCertificate::find($request->project_certificate_id);
        $cert_type = $request->cert_type;
        if ($request->file('file_uploaded') != null && $request->file('file_uploaded')->isValid()) {
            $file_name = $cert_type . '_project_' . $projectCert->project_id . "." . $request->file('file_uploaded')->guessClientExtension();
            $link = $request->file('file_uploaded')->storeAs('clients/' . $projectCert->client_id . '/certificate', $file_name, 'public');
            // $media = $request->file('file_uploaded');
            // $file_name = 'file_for_clause_' . $upload->id . '_' . time() . "." . $request->file('file_uploaded')->guessClientExtension();
            // $link = $this->uploadFile($media, $file_name, $folder_key);
            $projectCert->$cert_type = $link;
            $projectCert->save();
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $client_id = $request->client_id;
        $client = Client::find($client_id);

        $partner_id = $client->partner_id;
        // $user = $this->getUser();
        $available_module_id = $request->available_module_id;
        $standards = json_decode(json_encode($request->standards));
        if (count($standards) > 0) {

            foreach ($standards as $standard_id) {
                $standard = Standard::find($standard_id);
                $project = Project::firstOrCreate([
                    'title' => $standard->name,
                    'partner_id' => $partner_id,
                    'client_id' => $client_id,
                    'available_module_id' => $available_module_id,
                    'standard_id' => $standard_id,
                    'year' => $this->getYear(),
                ]);

                // $this->storeClientProjectPlan($client_id, $project->id, $standard_id);
                $this->assignProjectToClientStaff($request, $project);

                $this->createProjectCertificate($client_id, $project->id);
            }
        } else {
            $available_module = AvailableModule::find($available_module_id);
            $project = Project::firstOrCreate([
                'title' => $available_module->name,
                'partner_id' => $partner_id,
                'client_id' => $client_id,
                'available_module_id' => $available_module_id,
                'standard_id' => NULL,
                'year' => $this->getYear(),
            ]);
            $this->assignProjectToClientStaff($request, $project);
            $this->createProjectCertificate($client_id, $project->id);
        }
        $actor = $this->getUser();
        $title = "New Project Created for Client";
        //log this event
        $description = "New project was created for $client->name by $actor->name";
        $this->auditTrailEvent($title, $description);
        return response()->json(['message' => 'Successful'], 200);
    }
    private function createProjectCertificate($client_id, $project_id)
    {
        ProjectCertificate::firstOrCreate([
            'client_id' => $client_id,
            'project_id' => $project_id,
        ]);
    }
    // public function storeClientProjectPlan($client_id, $project_id, $standard_id)
    // {
    //     $standard = Standard::with('generalProjectPlans')->find($standard_id);
    //     $general_project_plans = $standard->generalProjectPlans;

    //     foreach ($general_project_plans as $general_project_plan) {
    //         ClientProjectPlan::firstOrCreate([
    //             'client_id' => $client_id,
    //             'project_id' => $project_id,
    //             'general_project_plan_id' => $general_project_plan->id,
    //         ]);
    //     }
    // }
    public function assignProjectToClientStaff(Request $request, Project $project)
    {
        $user = $this->getUser();
        // $users = $client->users()->where('role', 'admin')->get();
        $user_ids = (isset($request->user_ids)) ? $request->user_ids : [];
        // foreach ($users as $user) {
        //     $user_ids[] = $user->id;
        // }

        $project->users()->syncWithoutDetaching($user_ids); //->paginate(10);

        $title = "The $project->title Project Engagement";
        $message = "You have been engaged on the $project->title Project by $user->name. Stay connected for upcoming tasks.";
        $this->sendNotification($title, $message, $user_ids);
        return response()->json([], 204);
    }
    public function unassignProjectFromClientStaff(Request $request, Project $project)
    {
        $user = $this->getUser();
        $user_id = $request->user_id;
        $project->users()->detach([$user_id]);
        $title = 'Project Disengagement';
        $message = "You have been <strong>disengaged</strong> from the $project->title Project by $user->name.";
        $this->sendNotification($title, $message, [$user_id]);
        return response()->json([], 204);
    }

    public function assignProjectsToConsultant(Request $request)
    {
        $user_ids = $request->user_ids;
        $project_ids = $request->project_ids;
        foreach ($project_ids as $project_id) {
            $project = Project::find($project_id);
            $project->consultants()->sync($user_ids);
        }
        return response()->json([], 204);
    }
    public function unassignProjectFromConsultant(Request $request)
    {
        $user_id = $request->user_id;
        $project_id = $request->project_id;
        ConsultantProject::where(['project_id' => $project_id, 'user_id' => $user_id])->delete();
        return response()->json([], 204);
    }

    public function saveClientFeedback(Request $request)
    {
        $client_id = $request->client_id;
        $project_id = $request->project_id;
        if (isset($request->extra_details)) {
            $extra_details = json_decode(json_encode($request->extra_details));
            foreach ($extra_details as $detail) {
                $label = $detail->label;
                $value = $detail->value;
                $feedback = FeedBack::where(['client_id' => $client_id, 'project_id' => $project_id, 'label' => $label])->first();
                if (!$feedback) {

                    $feedback = new FeedBack();
                }
                $feedback->client_id = $client_id;
                $feedback->project_id = $project_id;
                $feedback->label = $label;
                $feedback->value = $value;
                $feedback->save();
            }
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Project $project)
    {
        //
        $project = $project->with('standard.clauses')->find($project->id);
        return response()->json(compact('project'), 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function setDates(Request $request, Project $project)
    {
        //
        $deadline = date('Y-m-d', strtotime('+1 year'));
        $value = $request->date;
        $project->start_date = date('Y-m-d', strtotime($value));
        $project->deadline = $deadline;
        $project->year = date('Y', strtotime($value));
        $project->save();
        return response()->json(['message' => 'Successful'], 200);
    }

    public function updateRandomFields(Request $request, Project $project)
    {
        $field = $request->field;
        $value = $request->value;
        $project->$field = $value;
        $project->save();
        return response()->json(['message' => 'Successful'], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Project $project)
    {
        $client_id = $project->client_id;
        $client = Client::find($client_id);
        $actor = $this->getUser();
        $title = "Project deleted";
        //log this event
        $description = "Project with id $project->id was deleted for $client->name by $actor->name";
        $this->auditTrailEvent($title, $description);

        $project->delete();
        return response()->json([], 204);
    }
}
