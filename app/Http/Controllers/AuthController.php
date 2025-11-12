<?php

namespace App\Http\Controllers;

use App\Rules\ReCaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Jobs\SendQueuedPasswordResetEmailJob;
use App\Models\TwoFactorAuthentication;
use App\Jobs\SendQueued2FACode;
use Illuminate\Validation\Rules\Password;
use App\Mail\PassKey;
use App\Mail\ResetPassword;
use App\Models\Client;
use App\Models\OtherUnitsUser;
use App\Models\Partner;
use App\Models\UserPassword;
use App\Models\BusinessUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    protected $username;
    protected $macAddr;
    protected $todayDate;
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->macAddr = request()->ip();
        $this->todayDate = date('Y-m-d', strtotime('now'));
        // $this->middleware('guest')->except('logout');
        // $this->username = $this->findUsername();
    }
    public function findUsername()
    {
        $login = request()->input('username');

        $user = User::where('phone', $login)->first();

        if ($user) {
            $fieldType = 'phone';
        } else {
            $fieldType = 'email';
        }


        request()->merge([$fieldType => $login]);


        return $fieldType;
    }
    public function username()
    {
        return $this->username;
    }
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|unique:users',
            'phone' => 'required|string|unique:users',
            'email' => 'required|string|unique:users',
            'password' => 'required|string',
            'c_password' => 'required|same:password',
            'recaptcha' => ['required', new ReCaptcha]
        ]);

        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($user->save()) {
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;

            return response()->json([
                'message' => 'Successfully created user!',
                'accessToken' => $token,
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }
    public function otherUserLogin(Request $request)
    {
        $email = $request->email;
        $access_code = $request->access_code;
        $other_user = OtherUnitsUser::where('email', $email)->first();
        if ($other_user) {

            $business_unit_id = $other_user->business_unit_id;
            $business_unit = BusinessUnit::find($business_unit_id);
            if ($business_unit->access_code == $access_code) {
                $token = randomNumber();
                return response(compact('token', 'other_user'), 200);
            }
        }
        return response(['message' => 'Invalid Credentials'], 401);
    }
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     */
    public function loginNo2FA(Request $request)
    {
        $this->username = $this->findUsername();

        $credentials = $request->only($this->username(), 'password');
        $request->validate([
            // 'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        // $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $user = $request->user();
        // $this->setupTheme($user);

        // if ($user->email_verified_at === NULL) {
        //     return response()->json(['message' => 'Account Activation Needed'], 403);
        // }
        return $this->generateAuthorizationKey($user);
    }
    public function login(Request $request)
    {
        $request->validate([
            'recaptcha' => ['required', new ReCaptcha]
        ]);
        $this->username = $this->findUsername();

        $credentials = $request->only($this->username(), 'password');
        $request->validate([
            // 'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        // $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $user = $request->user();

        // $this->setupTheme($user);
        $lastLoginDate = date('Y-m-d', strtotime($user->last_login));

        if ($user->email_verified_at === NULL) {
            return response()->json(['message' => 'Account Activation Needed'], 403);
        }
        if ($user->password_status === 'default') {
            $message = 'change_password';
            return response()->json(compact('message', 'user'), 200);
        }
        $clients = $user->clients;
        if (!empty($clients) && isset($clients[0])) {
            $client = $clients[0];
            if ($client->is_active === 0) {
                return response()->json(['message' => 'Your account has been suspended. Kindly contact the administrator'], 403);
            }
        }
        if ($user->system_mac_address !== NULL && $user->system_mac_address === $this->macAddr && $this->todayDate === $lastLoginDate) {
            return $this->generateAuthorizationKey($user);
        }



        return $this->send2FACode($user);
    }

    public function send2FACode(User $user)
    {
        $token = randomNumber();
        $_2fa = TwoFactorAuthentication::where('user_id', $user->id)->first();
        if ($_2fa) {
            $resend_time = strtotime('now');
            if ($resend_time - $_2fa->timestamp <= 90) {
                return response()->json(['message' => 'Please wait a little before sending another OTP'], 500);
            }
        }
        if (!$_2fa) {

            $_2fa = new TwoFactorAuthentication();
        }
        $_2fa->user_id = $user->id;
        $_2fa->token = hash('sha256', $token);
        $_2fa->timestamp = strtotime('now');
        if ($_2fa->save()) {
            // send token to email
            // SendQueued2FACode::dispatch($user, $token);
            Mail::to($user)->send(new PassKey($user, $token));
        }
        $message = 'OTP';
        return response()->json(compact('message', 'user'), 200);
    }
    public function confirm2FACode(Request $request, User $user)
    {
        $request->validate([
            'recaptcha' => ['required', new ReCaptcha]
        ]);
        $login_time = strtotime('now');
        $token = hash('sha256', $request->token);
        $_2fa = TwoFactorAuthentication::where(['user_id' => $user->id, 'token' => $token])->first();
        if ($_2fa) {
            if ($login_time - $_2fa->timestamp <= 300) {
                $_2fa->delete();
                // this means the time of applying the passcode is within 300seconds (5mins)


                return $this->generateAuthorizationKey($user);
            }
            $_2fa->delete();
        }
        return response()->json(['message' => 'Invalid or Expired Token. Please Resend'], 500);
    }
    private function generateAuthorizationKey($user, $saveToken = true)
    {
        $name = $user->name . ' (' . $user->email . ')';
        $title = "Log in action";
        //log this event
        $description = "$name logged in to the portal";
        $this->auditTrailEvent($title, $description);

        $user_resource = new UserResource($user);
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        if ($saveToken) {

            $user->api_token = $token;
            $user->system_mac_address = $this->macAddr;
            $user->last_login = date('Y-m-d H:i:s', strtotime('now'));
            $user->save();
        }
        // return response()->json([
        //     'user_data' => $user_resource
        // ])->header('Authorization', $token);
        return response()->json(['data' => $user_resource, 'tk' => $token], 200)->header('Authorization', $token);
    }
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function fetchUser(Request $request)
    {
        $this->setupTheme($request->user());
        return new UserResource(Auth::user());
        // return response()->json($request->user());
    }
    public function loginAs(Request $request)
    {
        $user = $request->user();
        $user->login_as = $request->role;
        $user->client_id = $request->client_id;
        $user->partner_id = $request->partner_id;
        $user->vendor_id = $request->vendor_id;
        $user->save();
        return $this->fetchUser($request);
    }
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    // public function logout(Request $request)
    // {
    //     $request->user()->tokens()->delete();

    //     return response()->json([
    //         'message' => 'Successfully logged out'
    //     ]);
    // }
    public function logout(Request $request)
    {
        // return $request;
        // $this->guard()->logout();

        // $request->session()->invalidate();
        // $request->user()->tokens()->delete();

        // log this event
        $name = $request->user()->name . ' (' . $request->user()->email . ')';
        $title = "Log out action";
        //log this event
        $description = "$name logged out of the portal";
        $this->auditTrailEvent($title, $description);

        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function confirmRegistration(Request $request)
    {
        $hash = $request->code;
        if (isset($request->user_id) && $hash === 'admin_confirmation') {
            $user = User::find($request->user_id);
            $message = 'Cannot Activate User';
            if ($user) {        //hash is confirmed and valid
                if ($user->email_verified_at === NULL) {
                    $user->email_verified_at = date('Y-m-d H:i:s', strtotime('now'));
                    $user->last_login = date('Y-m-d H:i:s', strtotime('now'));
                    $user->system_mac_address = $this->macAddr;
                    $user->save();
                    $message = 'Account Activated Successfully';
                } else {
                    $message = 'Account Already Activated';
                }
            }
        } else {
            $confirm_hash = User::where(['confirm_hash' => $hash])->first();
            $message = 'Invalid Activation Link';
            if ($confirm_hash) {        //hash is confirmed and valid
                if ($confirm_hash->email_verified_at === NULL) {
                    $confirm_hash->email_verified_at = date('Y-m-d H:i:s', strtotime('now'));
                    $confirm_hash->last_login = date('Y-m-d H:i:s', strtotime('now'));
                    $confirm_hash->system_mac_address = $this->macAddr;
                    $confirm_hash->save();
                    $message = 'Account Activated Successfully';
                } else {
                    $message = 'Account Already Activated';
                }
                //return view('auth.registration_confirmed', compact('message'));

            }
        }



        return $message;
    }
    public function recoverPassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'recaptcha' => ['required', new ReCaptcha]
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = hash('sha256', time() . $user->email);
            DB::table('password_resets')->updateOrInsert(
                ['email' => $user->email, 'token' => $token]
            );

            // SendQueuedPasswordResetEmailJob::dispatch($user, $token);
            Mail::to($user)->send(new ResetPassword($user, $token));
            return response()->json(['message' => 'A password reset link has been sent to your email'], 200);
        }

        return response()->json(['message' => 'Email Not Found'], 500);
    }
    public function confirmPasswordResetToken($token)
    {
        $user_token = DB::table('password_resets')->where('token', $token)->first();
        if ($user_token) {
            return response()->json(['email' => $user_token->email], 200);
        }
        return response()->json(['message' => 'Invalid Reset Link'], 500);
    }
    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => [
                'required',
                Password::min(12) // Minimum length of 8 characters
                    ->letters() // Must contain at least one letter
                    ->mixedCase() // Must contain both uppercase and lowercase letters
                    ->numbers() // Must contain at least one number
                    ->symbols() // Must contain at least one symbol
                    ->uncompromised()
            ],
            'recaptcha' => ['required', new ReCaptcha]
        ]);

        if (isset($request->include_old_password)) {


            $credentials = ['email' => $request->email, 'password' => $request->old_password]; // $request->only('email', 'password');
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'You need to remember your old password'
                ], 401);
            }
        }


        $user = User::where('email', $data['email'])->first();
        if ($user) {

            $hashed_password = hash('sha256', $data['password']);
            if (isset($request->message) && $request->message === 'password_due_for_change') {

                $user_password = UserPassword::where(['user_id' => $user->id, 'password' => $hashed_password])->first();
                if ($user_password) {
                    return response()->json([
                        'message' => 'You have used this password in recent times. Kindly change it.'
                    ], 401);
                }
            }
            $user->password = $data['password'];
            $user->password_status = 'custom';
            $user->password_expires_at = date('Y-m-d H:i:s', strtotime($this->todayDate . ' +90 days'));
            if ($user->save()) {
                DB::table('password_resets')->where('email', $data['email'])->delete();
                $user_password_count = UserPassword::where('user_id', $user->id)->count();
                if ($user_password_count < 3) {
                    $user_password = new UserPassword();
                    $user_password->user_id = $user->id;
                    $user_password->password = $hashed_password;
                    $user_password->save();
                } else {
                    $user_password = UserPassword::where('user_id', $user->id)->orderBy('updated_at')->first();
                    $user_password->password = $hashed_password;
                    $user_password->save();
                }
            }
        }

        return 'success';
    }
    private function setupTheme($user)
    {
        $logo = 'partner-logos/default-logo.png';
        $navbar_bg = 'rgb(11, 23, 61)';
        $sidebar_bg = 'rgb(210, 162, 4)';
        if ($user->hasRole('client')) {
            $client_id = $user->client_id;
            $client = Client::find($client_id);
            if ($client) {
                $logo = $client->logo;
                $navbar_bg = $client->navbar_bg;
                $sidebar_bg = $client->sidebar_bg;
            }
        }
        if ($user->hasRole('partner')) {
            $partner_id = $user->partner_id;
            $partner = Partner::find($partner_id);
            if ($partner) {
                $logo = $partner->logo;
                $navbar_bg = $partner->navbar_bg;
                $sidebar_bg = $partner->sidebar_bg;
            }
        }
        $user->logo = $logo;
        $user->navbar_bg = $navbar_bg;
        $user->sidebar_bg = $sidebar_bg;
        $user->save();
    }

    private function fetchUserRoles($user)
    {
        return array_map(
            function ($role) {
                return $role['name'];
            },
            $user->roles->toArray()
        );
    }
}
