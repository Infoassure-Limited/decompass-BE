<?php

namespace App\Http\Resources;

use App\Models\ActivatedModule;
use App\Models\AvailableModule;
use App\Models\Client;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Role;
use App\Models\VendorDueDiligence\Vendor;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this_year = (int) date('Y', strtotime('now'));
        $permissions = [];
        $login_as = $this->login_as;
        if ($login_as === 'client') {
            $login_as = 'user';
        }
        $roles = [$login_as];
        $all_roles = array_map(
            function ($role) {
                return $role['name'];
            },
            $this->roles->toArray()
        );
        if ($this->vendor_id !== NULL) {

            $roles[] = 'vendor';
            $all_roles[] = 'vendor';
        }
        if ($login_as !== NULL) {

            $role = Role::with('permissions')->where('name', $this->login_as)->first();
            $permissions = $role->permissions()->pluck('name');
        }
        $modules = [];
        $partner = '';
        if ($this->haRole('user')) {
            $client_id = $this->client_id;
            $client = Client::find($client_id);
            $partner_id = $client->partner_id;

            // $projects = Project::with('availableModule')
            //     ->where(['client_id' => $client_id, 'year' => $this_year])
            //     ->orderBy('id', 'DESC')
            //     ->get();
            $projects = Project::with('availableModule')
                ->where(['client_id' => $client_id, 'year' => $this_year])
                ->orderBy('id', 'DESC')
                ->get();
            // $projects = $this->projects()
            //     ->with('availableModule')
            //     ->where(['client_id' => $client_id, 'year' => $this_year])
            //     ->orderBy('id', 'DESC')
            //     ->get();
            foreach ($projects as $project) {

                $modules[] = $project->availableModule->slug;
            }
            $partner = Partner::find($partner_id);
        }
        if ($this->isAdmin()) {
            $client_id = $this->client_id;
            $client = Client::find($client_id);
            if ($client->admin_user_id == $this->id) {
                $roles[] = 'admin';
            }
            $partner_id = $client->partner_id;

            $projects = Project::with('availableModule')
                ->where(['client_id' => $client_id, 'year' => $this_year])
                ->orderBy('id', 'DESC')
                ->get();
            foreach ($projects as $project) {

                $modules[] = $project->availableModule->slug;
            }
            $partner = Partner::find($partner_id);
        }
        if ($this->haRole('partner')) {
            $partner_id = $this->partner_id;
            $partner = Partner::with('activatedModules')->find($partner_id);
            $activated_modules = $partner->activatedModules;
            foreach ($activated_modules as $activated_module) {

                $modules[] = $activated_module->availableModule->slug;
            }
        }
        if ($this->haRole('super')) {
            $modules = AvailableModule::pluck('slug')->toArray();
        }
        if (in_array('admin', $roles)) {

            $all_roles[] = 'admin';
        }
        sort($modules);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password_status' => $this->password_status,
            'notifications' => $this->notifications()->orderBy('created_at', 'DESC')->take(5)->get(),
            'login_as' => $login_as,
            'partner_id' => $this->partner_id,
            'client_id' => $this->client_id,
            'vendor_id' => $this->vendor_id,
            'modules' => $modules,
            // 'activity_logs' => $this->notifications()->orderBy('created_at', 'DESC')->get(),
            'roles' => array_unique($roles),
            'all_roles' => array_unique($all_roles),
            // 'role' => 'admin',
            // 'permissions' => $permissions,
            'permissions' => array_map(
                function ($permission) {
                    return $permission['name'];
                },
                $this->allPermissions()->toArray()
            ),
            // 'role' => 'admin',
            'all_permissions' => array_map(
                function ($permission) {
                    return $permission['name'];
                },
                $this->allPermissions()->toArray()
            ),
            'photo' => $this->photo,
            'logo' => $this->logo,
            'navbar_bg' => $this->navbar_bg,
            'sidebar_bg' => $this->sidebar_bg,

        ];
    }
}
