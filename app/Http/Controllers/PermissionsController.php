<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Project;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = $this->getUser();
        $this_year = (int) date('Y', strtotime('now'));
        if ($user->login_as == 'admin') {
            $client = $this->getClient();
            $projects = Project::with('availableModule')
                ->where(['client_id' => $client->id, 'year' => $this_year])
                ->orderBy('id', 'DESC')
                ->get();
            $modules = [];
            foreach ($projects as $project) {

                $modules[] = $project->availableModule->slug;
            }
            // $roles = Role::where('client_id', $client->id)->with('permissions')->get();
            $permissions = Permission::orderBy('name')
                ->where('module', '!=', 'SYSTEM')
                ->where(function ($q) use ($modules) {
                    $q->where('module', 'GENERAL')
                        ->orWhereIn('module_slug', $modules);
                })
                ->get()
                ->groupBy('module');
            return $this->render(compact('permissions'));
        }
        if ($user->login_as !== 'super') {
            $client = $this->getClient();
            // $roles = Role::where('client_id', $client->id)->with('permissions')->get();
            $permissions = Permission::orderBy('name')->where('module', '!=', 'SYSTEM')->get()->groupBy('module');
        } else {


            $permissions = Permission::orderBy('name')->get()->groupBy('module');
        }
        return $this->render(compact('permissions'));
    }



    public function assignUserPermissions(Request $request)
    {
        $user = User::find($request->user_id);
        $user->syncPermissions($request->permissions);
        $permissions = $user->allPermissions();
        $user->flushCache();
        return response()->json(compact('permissions'), 200);
    }
    public function assignRolePermissions(Request $request)
    {
        $role = Role::find($request->role_id);
        $role->syncPermissions($request->permissions);
        $permissions = $role->permissions;
        $role->flushCache();
        return response()->json(compact('permissions'), 200);
    }
    // public function removeAssignedPermission(Request $request)
    // {
    //     $user = User::find($request->user_id);
    //     $user->detachPermissions($request->permissions);
    // }
}
