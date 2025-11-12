<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorDueDiligence\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        // $credentials = request(['email', 'password']);
        if (!Auth::guard('vendor')->attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $user = Auth::guard('vendor')->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        $user->api_token = $token;
        $user->system_mac_address = $this->macAddr;
        $user->last_login = date('Y-m-d H:i:s', strtotime('now'));
        $user->save();

        return response(compact('token', 'user'), 200)->header('Authorization', $token);
    }
}
