<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Role;
use App\Models\School;
use App\Models\Staff;
use App\Models\State;
use App\Models\Student;
use App\Models\StudentsInClass;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laracasts\Flash\Flash;

class UsersController extends Controller
{

    public function fetchClientUsers()
    {
        $users = new Collection();
        $partner = $this->getPartner();
        if ($partner) {
            $partner_id = $partner->id;
            $clients = Client::with('users')->where('partner_id', $partner_id)->get();
            foreach ($clients as $client) {
                $users = $users->merge($client->users);
            }
        }
        return response()->json(compact('users'), 200);
    }
    public function fetchPartnerUsers()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'partner');
        })->orWhere('role', 'partner')->get();
        return response()->json(compact('users'), 200);
    }
    public function fetchStaff()
    {
        $user = $this->getUser();
        $staff = [];
        if ($user->haRole('partner')) {
            $partner = $this->getPartner();
            $staff = $partner->users()->with('roles', 'permissions')->get();
        }
        if ($user->isSuperAdmin()) {

            $staff = User::with('roles', 'permissions')->where('role', 'staff')->get();
        }
        return response()->json(compact('staff'), 200);
    }
    public function userNotifications(Request $request)
    {
        $user = $this->getUser();
        // $school = $this->getSchool();
        // $sess_id = $this->getSession()->id;
        $notifications = $user->notifications()->orderBy('created_at', 'DESC')->take(5)->get();
        $unread_notifications = $user->unreadNotifications()->count();
        return response()->json(compact('notifications', 'unread_notifications'), 200);
    }
    public function markNotificationAsRead(Request $request)
    {
        $user = $this->getUser();
        $user->unreadNotifications->markAsRead();
        $notifications = $user->notifications()->orderBy('created_at', 'DESC')->paginate($request->limit);
        $unread_notifications = $user->unreadNotifications()->count();
        return response()->json(compact('notifications', 'unread_notifications'), 200);
    }
    public function changePassword()
    {
        $user = $this->getUser();
        $user->password_status = 'default';
        $user->save();
        return redirect()->route('dashboard');
    }
    public function adminResetUserPassword(Request $request)
    {
        $user = User::find($request->user_id);
        $user->password = 'password';
        $user->password_status = 'default';
        $user->save();
    }
    public function resetPassword(Request $request, User $user)
    {
        $confirm_password = $request->confirm_password;
        $new_password = $request->new_password;

        if ($new_password === $confirm_password) {
            $user->password = $new_password;
            $user->password_status = 'custom';

            if ($user->save()) {
                return response()->json(['message' => 'success'], 200);
            }
        }
        return response()->json([
            'message' => 'Password does not match'
        ], 401);
    }

    public function approveUser(Request $request, User $user)
    {
        $user->is_confirmed = '1';
        $user->save();
        return response()->json(['message' => 'success'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->password = $request->email;
        $request->role = 'vendor';
        $user_obj = new User();
        $user = $user_obj->createUser($request);
        $user->roles()->sync([7]); // role id 7 is vendor

        // send confirmation email to user
        //email will be sent later containing login credentials
        // SendQueuedConfirmationEmailJob::dispatch($user);
        return response()->json('success', 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePhoto(Request $request)
    {

        $folder_key = 'photo';
        $user = User::find($request->user_id);
        if ($request->file('photo') != null && $request->file('photo')->isValid()) {
            $mime = $request->file('photo')->getClientMimeType();

            if ($mime == 'image/png' || $mime == 'image/jpeg' || $mime == 'image/jpg' || $mime == 'image/gif') {
                $name = 'profile_photo_user' . $user->id . '.' . $request->file('photo')->guessClientExtension();
                $photo_name = $user->uploadFile($request, $name, $folder_key);
                $user->photo = $photo_name;
                $user->save();
            }
        }
        return $user->photo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request, User $user)
    {
        //
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return response()->json([], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // $user->delete();
        // return response()->json([], 204);
    }
}
