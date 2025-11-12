<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CurriculumLevelGroup;

class RolesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        if ($user->login_as !== 'super') {
            $client = $this->getClient();
            $roles = Role::whereNotIn('name', ['super', 'partner', 'short-term-su', 'vendor'])
                ->where(function ($q) use ($client) {
                    $q->where('client_id', $client->id)
                        ->orWhere('client_id', null);

                })
                ->with('permissions')
                ->get();
        } else {

            $roles = Role::where('name', '!=', 'client')
                ->where('client_id', null)
                ->with('permissions')
                ->get();
        }
        return $this->render(compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * This method handles the creation and persistence of a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $name = $validated['name'];
        $user = $this->getUser();
        if ($user->login_as !== 'super') {
            $client = $this->getClient();
            $role = Role::where('name', $name)
                ->where(function ($q) use ($client) {
                    $q->where('client_id', $client->id)
                        ->orWhere('client_id', null);
                })
                ->first();
            if (!$role) {
                $role = new Role();
                $role->name = $name;
                $role->client_id = $client->id;
                $role->display_name = $name;
                $role->description = $validated['description'] ?? null;
                $role->save();
            }

            $userIds = $client->users()->pluck('id');
            $userIds = $userIds->toArray();
            $title = "New Role Created under " . $client->name;
            //log this event
            $description = "$user->name created a new role titled: $name";
            $this->sendNotification($title, $description, $userIds);
        } else {
            $role = Role::where('name', $name)->first();
            if (!$role) {
                $role = new Role();
                $role->name = $name;
                $role->display_name = ucwords(str_replace('-', ' ', $name));
                $role->description = $validated['description'] ?? null;
                $role->save();

                $userIds = [$user->id];
                $title = "New Role Created";
                //log this event
                $description = "$user->name created a new role titled: $name";
                $this->sendNotification($title, $description, $userIds);
            }
        }
        return $this->index($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $name = $validated['name'];
        $role->name = $name;
        $role->display_name = ucwords(str_replace('-', ' ', $name));
        $role->description = $validated['description'] ?? null;
        $role->save();
        return $this->index($request);
    }

    public function assignRoles(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'roles' => 'required|array',
            'roles.*' => 'integer|exists:roles,id',
        ]);

        $user = User::find($validated['user_id']);
        $user->syncRoles($validated['roles']);
        $user->flushCache();
        $roles = $user->roles()->with('permissions')->get();
        $permissions = [];
        foreach ($roles as $role) {
            $permissions = array_merge($permissions, $role->permissions->toArray());
        }
        return response()->json(compact('roles', 'permissions'), 200);
    }
    // public function removeAssignedRole(Request $request)
    // {
    //     $user = User::find($request->user_id);
    //     $user->detachRole($request->roles);
    // }
}
