<?php

namespace App\Http\Controllers;

use App\Models\ActivatedModule;
use App\Models\Client;
use App\Models\Partner;
use App\Models\User;
use App\Models\VendorDueDiligence\Vendor;
use Illuminate\Http\Request;
use App\Jobs\SendQueuedConfirmationEmailJob;
use App\Mail\ConfirmNewRegistration;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Rules\ReCaptcha;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        $condition = [];
        $partner_with_clients = [];
        if ($user->haRole('user') || $user->haRole('admin')) {
            $id = $this->getClient()->id;
            $condition = ['id' => $id];
        }
        if ($user->haRole('partner')) {
            $partner_id = $this->getPartner()->id;
            $condition = ['partner_id' => $partner_id];
        }

        if ($user->haRole('super')) {
            $partner_with_clients = Partner::with('clients.matrix')->get();
        }

        if (isset($request->option) && $request->option === 'all') {
            $clients = Client::with('matrix')->where($condition)->orderBy('name')->get();

        } else {

            $clients = Client::with('users', 'matrix')->where($condition)->orderBy('name')->paginate($request->limit);
            return response()->json(compact('clients'), 200);
        }
        return response()->json(compact('clients', 'partner_with_clients'), 200);
    }


    public function fetchUserClients(Request $request)
    {

        $user_id = $this->getUser()->id;
        $user = User::find($user_id);
        $clients = $user->clients;
        if (isset($request->role) && $request->role !== null) {
            foreach ($clients as $client) {

                $role = Role::where(['name' => $request->role, 'client_id' => $client->id])->first();
                if (!$role) {
                    $clients = $clients->except($client->id);
                }
            }
        }
        return response()->json(compact('clients'), 200);
    }

    public function fetchClientUsers()
    {
        $client = $this->getClient();
        $users = $client->users;
        return response()->json(compact('users'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $this->getUser();
        if (!$user->hasPermission('create-clients')) {
            return response()->json(['message' => 'You need permission for this action'], 403);
        }
        // if (!$user->haRole('partner')) {
        //     return response()->json(['message' => 'Clients registration is restricted to Partners only'], 500);
        // }
        $partner_id = $this->getPartner()->id;

        $contact_email = $request->contact_email;
        $client = Client::where('contact_email', $contact_email)->first();
        if (!$client) {
            $client = new Client();
            $client->partner_id = $partner_id;
            $client->name = $request->organization_name;
            $client->contact_email = $request->contact_email;
            $client->contact_phone = $request->contact_phone;
            $client->contact_address = $request->contact_address;
            if ($client->save()) {
                $actor = $this->getUser();
                $title = "New Client Registered";
                //log this event
                $description = "$client->name was registered by $actor->name";
                $this->auditTrailEvent($title, $description);


                return response()->json(compact('client'), 200);
            }
            return response()->json(['message' => 'Unable to register'], 500);
        }
        return response()->json(['message' => 'Company already exists'], 401);
    }

    public function registerClient(Request $request)
    {
        $request->validate([
            'organization_name' => 'required|string',
            'admin_first_name' => 'required|string',
            'phone' => 'required|string|unique:users',
            'email' => 'required|string|unique:users',
            'recaptcha' => ['required', new ReCaptcha]
        ]);
        $contact_email = $request->contact_email;
        $client = Client::where('contact_email', $contact_email)->first();
        if (!$client) {
            $client = new Client();
            $client->name = $request->organization_name;
            $client->contact_email = $request->contact_email;
            $client->contact_phone = $request->contact_phone;
            $client->contact_address = $request->contact_address;
            if ($client->save()) {
                $request->client_id = $client->id;
                $request->role = 'user';
                $request->roles = ['admin', 'user'];
                $request->login_as = 'admin';
                $this->registerClientUser($request);
                $title = "New Client Registered";
                //log this event
                $description = "$client->name was registered";
                $this->auditTrailEvent($title, $description);

                return response()->json(compact('client'), 200);
            }
            return response()->json(['message' => 'Unable to register'], 500);
        }
        return response()->json(['message' => 'Company already exists'], 401);
    }


    public function registerClientUser(Request $request)
    {
        $client = Client::find($request->client_id);
        $request->name = $request->admin_first_name . ' ' . $request->admin_last_name;
        // $request->email = $request->admin_email;
        $request->password = $request->email;
        // $request->phone = $request->admin_phone;
        $user_obj = new User();
        $user = $user_obj->createUser($request);
        // make this user the client admin
        $roles = [$request->role];
        if (isset($request->roles)) {
            if (in_array('admin', $request->roles)) {

                $client->admin_user_id = $user->id;
                $client->save();
            }
            $roles = $request->roles;
        }
        // sync user to client
        $client->users()->syncWithoutDetaching($user->id);
        $roleIds = Role::whereIn('name', $roles)->pluck('id');
        $user->roles()->sync($roleIds); // role id 3 is client

        $this->sendLoginCredentials($user);
        return response()->json('success', 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendLoginCredentials(User $user)
    {
        $password = randomPassword();
        $user->password = $password;
        $user->password_status = 'default';
        $user->save();
        //email will be sent later containing login credentials
        // SendQueuedConfirmationEmailJob::dispatch($user, $password);
        Mail::to($user)->send(new ConfirmNewRegistration($user, $password));
        // \Illuminate\Support\Facades\Artisan::call('queue:work --queue=high,default');
        return response()->json([], 204);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return mixed
     */
    public function update(Request $request)
    {
        $user = $this->getUser();
        if (!$user->hasPermission('update-clients')) {
            return response()->json(['message' => 'You need permission for this action'], 403);
        }
        try {
            $client = Client::find($request->id);
            $client->name = $request->name;
            $client->contact_email = $request->contact_email;
            $client->contact_phone = $request->contact_phone;
            $client->contact_address = $request->contact_address;
            $client->navbar_bg = $request->navbar_bg;
            $client->sidebar_bg = $request->sidebar_bg;
            $client->save();
            $this->changeClientLogo($request, $client);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return response()->json(['message' => $errorMessage], 500);
        }

    }

    public function becomeAClient(Request $request)
    {
        $data = json_decode(json_encode($request->data));
        $vendorId = $data->id;
        $vendor = Vendor::find($vendorId);
        $vendor->is_a_client = 1;
        $vendor->save();


        $name = $data->name;
        $client = Client::where('name', $name)->first();
        if (!$client) {
            $client = new Client();
            $client->name = $name;
            $client->contact_email = $data->company_email;
            $client->contact_phone = $data->company_phone;
            $client->contact_address = $data->contact_address;
            if ($client->save()) {
                $dataUsers = $data->users;
                $count = 1;
                foreach ($dataUsers as $dataUser) {
                    $user_id = $dataUser->id;
                    $user = User::find($user_id);
                    $user->role = 'user';
                    $user->client_id = $client->id;
                    $roles = ['user'];
                    $user->login_as = 'user';
                    if ($count == 1) {
                        $roles = ['admin', 'user'];
                        $user->login_as = 'admin';
                        $client->admin_user_id = $user->id;
                        $client->save();
                    }
                    $user->save();
                    $client->users()->syncWithoutDetaching($user->id);
                    $roleIds = Role::whereIn('name', $roles)->pluck('id');
                    $user->roles()->sync($roleIds);
                    $count++;
                }
                $title = "Vendor registrated as client";
                //log this event
                $description = "$client->name has become a client";
                $this->auditTrailEvent($title, $description);
            }
        }
    }
    public function updateTheme(Request $request, Client $client)
    {
        $field = $request->field;
        $value = $request->value;
        $client->$field = $value;
        $client->save();
    }
    private function changeClientLogo($data, $client)
    {
        if ($data->file('logo') != null && $data->file('logo')->isValid()) {

            $name = time() . '_' . $data->file('logo')->hashName();
            // $file_name = $name . "." . $request->file('file_uploaded')->extension();
            $link = $data->file('logo')->storeAs('client-logos', $name, 'public');
            $client->logo = $link;
            $client->save();
        }
    }
    public function updateClientUser(Request $request, User $user)
    {

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->designation = $request->designation;
        $user->save();

        // $client->users()->sync($user->id);
        // $role = Role::where('name', 'client')->first();
        // $user->roles()->sync($role->id); // role id 3 is client

    }
    public function attachClientUser(Request $request, Client $client)
    {
        $actor = $this->getUser();
        $user = User::find($request->user_id);
        $title = "Client User Attached";
        //log this event
        $description = "$user->name was attached to $client->name by $actor->name";
        $this->auditTrailEvent($title, $description);

        $client->users()->syncWithoutDetaching($user->id);
        // $user->delete();
        return response()->json([], 204);
        // $role = Role::where('name', 'partner')->first();
        // $partner->roles()->sync($role->id); // role id 3 is partner

    }

    public function assignUserAsClientAdmin(Request $request, Client $client)
    {
        $client->admin_user_id = $request->user_id;
        $client->save();
        $client = $client->with('users')->find($client->id);
        return response()->json(compact('client'), 200);
    }

    public function removeClientUser(Request $request, Client $client)
    {
        $actor = $this->getUser();
        if (!$actor->haRole('partner')) {
            return response()->json(['message' => 'Clients are managed by Partners only'], 500);
        }
        $user = User::find($request->user_id);
        $title = "Client User Deletion";
        //log this event
        $description = "$user->name was removed from $client->name by $actor->name";
        $this->auditTrailEvent($title, $description);

        $client->users()->detach($user->id);
        // $user->delete();
        return response()->json([], 204);
        // $role = Role::where('name', 'partner')->first();
        // $partner->roles()->sync($role->id); // role id 3 is partner

    }
    public function deleteClientUser(Request $request, User $user)
    {
        $client = $this->getClient();
        $checkIfClientUser = $client->users()->find($user->id);
        if (!$checkIfClientUser) {
            return response()->json(['message' => 'You cannot delete this user'], 403);
        }
        $actor = $this->getUser();
        if (!$actor->hasPermission('delete-client-user')) {
            return response()->json(['message' => 'You need permission for this critical action'], 403);
        }
        $title = "User Deletion";
        //log this event
        $description = "$user->name was deleted by $actor->name";
        $this->auditTrailEvent($title, $description);
        $user->forceDelete();
        return response()->json([], 204);
        // $client->users()->sync($user->id);
        // $role = Role::where('name', 'client')->first();
        // $user->roles()->sync($role->id); // role id 3 is client

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return mixed
     */
    public function toggleClientSuspension(Request $request, Client $client)
    {
        $actor = $this->getUser();
        if (!$actor->haRole(['partner', 'super'])) {
            return response()->json(['message' => 'Permission Denied'], 403);
        }
        $value = $request->value;
        $client->is_active = $value;
        $client->save();
    }
}
