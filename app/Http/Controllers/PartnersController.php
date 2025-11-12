<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use App\Jobs\SendQueuedConfirmationEmailJob;
use App\Mail\ConfirmNewRegistration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PartnersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->option) && $request->option === 'all') {
            $partners = Partner::orderBy('name')->get();
        } else {

            $partners = Partner::with('users')->orderBy('name')->paginate($request->limit);
        }
        return response()->json(compact('partners'), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchUserPartners()
    {
        $user_id = $this->getUser()->id;
        $user = User::find($user_id);
        $partners = $user->partners;
        return response()->json(compact('partners'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $contact_email = $request->contact_email;
        $partner = Partner::where('contact_email', $contact_email)->first();
        if (!$partner) {
            $partner = new Partner();
            $partner->name = $request->organization_name;
            $partner->contact_email = $request->contact_email;
            $partner->contact_phone = $request->contact_phone;
            $partner->contact_address = $request->contact_address;
            if ($partner->save()) {
                $actor = $this->getUser();
                $title = "New Partner Registered";
                //log this event
                $description = "$partner->name was registered by $actor->name";
                $this->auditTrailEvent($title, $description);


                return response()->json(compact('partner'), 200);
            }
            return response()->json(['message' => 'Unable to register'], 500);
        }
        return response()->json(['message' => 'Company already exists'], 401);
    }
    public function registerPartnerUser(Request $request)
    {
        $partner = Partner::find($request->partner_id);
        $request->name = $request->admin_first_name . ' ' . $request->admin_last_name;
        $request->email = $request->admin_email;
        $request->password = $request->admin_email;
        $request->phone = $request->admin_phone;
        $request->role = 'partner';
        $user_obj = new User();
        $user = $user_obj->createUser($request);
        // sync user to client
        $partner->users()->syncWithoutDetaching($user->id);
        $role = Role::where('name', 'partner')->first();
        $user->roles()->sync($role->id); // role id 6 is partner

        return response()->json('success', 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function sendLoginCredentials(User $user)
    {
        $password = $user->email; // randomPassword();
        $user->password = $password;
        $user->save();
        //email will be sent later containing login credentials
        // SendQueuedConfirmationEmailJob::dispatch($partner, $password);
        Mail::to($user)->send(new ConfirmNewRegistration($user, $password));
        // \Illuminate\Support\Facades\Artisan::call('queue:work --queue=high,default');
        return response()->json([], 204);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $partner = Partner::find($request->id);
        $partner->name = $request->name;
        $partner->contact_email = $request->contact_email;
        $partner->contact_phone = $request->contact_phone;
        $partner->contact_address = $request->contact_address;
        $partner->navbar_bg = $request->navbar_bg;
        $partner->sidebar_bg = $request->sidebar_bg;
        $partner->save();
        $this->changePartnerLogo($request, $partner);
    }
    private function changePartnerLogo($data, $partner)
    {
        if ($data->file('logo') != null && $data->file('logo')->isValid()) {

            $name = time() . '_' . $data->file('logo')->hashName();
            // $file_name = $name . "." . $request->file('file_uploaded')->extension();
            $link = $data->file('logo')->storeAs('partner-logos', $name, 'public');
            $partner->logo = $link;
            $partner->save();
        }
    }
    public function updatePartnerUser(Request $request, User $user)
    {

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->designation = $request->designation;
        $user->save();

        // $partner->partners()->sync($partner->id);
        // $role = Role::where('name', 'partner')->first();
        // $partner->roles()->sync($role->id); // role id 3 is partner

    }
    public function attachPartnerUser(Request $request, Partner $partner)
    {
        $actor = $this->getUser();
        $user = User::find($request->user_id);
        $title = "Partner User Attached";
        //log this event
        $description = "$user->name was attached to $partner->name by $actor->name";
        $this->auditTrailEvent($title, $description);

        $partner->users()->syncWithoutDetaching($user->id);
        // $user->delete();
        return response()->json([], 204);
        // $role = Role::where('name', 'partner')->first();
        // $partner->roles()->sync($role->id); // role id 3 is partner

    }
    public function removePartnerUser(Request $request, Partner $partner)
    {
        $actor = $this->getUser();
        $user = User::find($request->user_id);
        $title = "Partner User Deletion";
        //log this event
        $description = "$user->name was removed from $partner->name by $actor->name";
        $this->auditTrailEvent($title, $description);

        $partner->users()->detach($user->id);
        // $user->delete();
        return response()->json([], 204);
        // $role = Role::where('name', 'partner')->first();
        // $partner->roles()->sync($role->id); // role id 3 is partner

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function togglePartnerSuspension(Request $request, Partner $partner)
    {
        $value = $request->value;
        $partner->is_active = $value;
        $partner->save();
    }
}
