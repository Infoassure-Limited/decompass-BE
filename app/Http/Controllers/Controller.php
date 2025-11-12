<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\ActivatedModule;
use App\Models\AvailableModule;
use App\Models\EmailList;
use App\Models\Project;
use App\Models\VendorDueDiligence\Vendor;
use App\Notifications\AuditTrail;
use App\Models\Client;
use App\Models\Partner;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\VendorDueDiligence\User as VendorUser;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use OpenAI\Laravel\Facades\OpenAI;
use Notification;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;
    protected $client;

    protected $partner;
    protected $myProjects;
    protected $role;
    protected $roles = [];
    protected $data = [];
    protected $currency = '₦'; //'&#x20A6;';
    protected $this_year;

    public function __construct(Request $httpRequest)
    {

        //->paginate(10);
        $this->middleware(function ($request, $next) {

            $this->setUser();
            $this->setClient();
            return $next($request);
        });
    }
    public function render($data = [])
    {
        $this->data = array_merge($this->data, $data);
        $this->data['currency'] = '₦';

        //print_r($data['class']->class->name);exit;*/
        return response()->json($this->data, 200);
    }

    public function setYear()
    {

        $this->this_year = (int) date('Y', strtotime('now'));
    }
    public function getYear()
    {
        $this->setYear();
        return $this->this_year;
    }
    public function getRoles()
    {
        $this->setRoles();
        return $this->roles;
    }
    public function getPermissions()
    {
        $permissions = Permission::orderBy('name')->get();
        return $permissions;
    }
    public function getSoftwareName()
    {
        return env("APP_NAME");
    }

    public function setUser()
    {
        $this->user = Auth::user(); //User::find(Auth::user()->id);
    }

    public function getUser()
    {
        $this->setUser();

        return $this->user;
    }
    public function setMyProjects($client_id = NULL)
    {
        $user = $this->getUser();
        if ($client_id == NULL) {

            $client_id = $user->client_id;
        }

        $this->myProjects = Project::with('client', 'availableModule', 'package')
            ->where(['client_id' => $client_id, 'year' => $this->getYear()])
            ->orderBy('id', 'DESC')
            ->get();
        // if ($user->haRole('user') || $user->haRole('admin') || $user->haRole('super')) {
        //     $this->myProjects = $user->projects()
        //         ->with('client', 'availableModule', 'package')
        //         ->where(['client_id' => $client_id, 'year' => $this->getYear()])
        //         ->orderBy('id', 'DESC')
        //         ->get();
        // } else {

        //     $this->myProjects = Project::with('client', 'availableModule', 'package')
        //         ->where(['client_id' => $client_id, 'year' => $this->getYear()])
        //         ->orderBy('id', 'DESC')
        //         ->get();
        // }
    }

    public function getMyProjects($client_id)
    {
        $this->setMyProjects($client_id);

        return $this->myProjects;
    }
    public function setClient()
    {
        $user = $this->getUser();
        // $client_user = DB::table('client_user')->where('user_id', $user->id)->first();
        // $client_id = $client_user->client_id;
        if ($user) {
            $client = Client::find($user->client_id);
            $this->client = $client;
        }

    }

    public function getClient()
    {
        $this->setClient();

        return $this->client;
    }
    public function setPartner()
    {
        $user = $this->getUser();
        // $partner_user = DB::table('partner_user')->where('user_id', $user->id)->first();
        // $partner_id = $partner_user->partner_id;
        $this->partner = Partner::find($user->partner_id);
    }

    public function getPartner()
    {
        $this->setPartner();

        return $this->partner;
    }
    public function getCurrency()
    {
        return $this->currency;
    }

    public function fetchCountries()
    {
        $countries = DB::table('countries')->orderBy('country_name')->pluck('country_name');
        return response()->json(compact('countries'), 200);
    }
    public function uploadFile($media, $file_name, $folder_key)
    {
        $folder = "clients/" . $folder_key;

        $media->storeAs($folder, $file_name, 'public');

        return $folder . '/' . $file_name;
    }
    public function auditTrailEvent($title, $action, $clients = null)
    {

        // $user = $this->getUser();
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'super');
            // ->orWhere('name', '=', 'admin'); // this is the role id inside of this callback
        })->get();
        if ($clients != null) {
            $users = $users->merge($clients);
        }
        $notification = new AuditTrail($title, $action);
        return Notification::send($users->unique(), $notification);
    }

    public function sendNotification($title, $message, array $userIds)
    {
        // $client = $this->getClient();
        // $notification_channels = ($client->notification_channels) ? $client->notification_channels : ['email', 'in_app'];
        try {
            $notification_channels = ['email', 'in_app'];
            $recipients = User::whereIn('id', $userIds)->get();

            if (in_array('in_app', $notification_channels)) {
                $notification = new AuditTrail($title, $message);
                Notification::send($recipients, $notification);
            }

            if (in_array('email', $notification_channels)) {
                foreach ($recipients as $recipient) {

                    Mail::to($recipient)->send(new SendMail($title, $message, $recipient));
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
    public function sendVendorNotification($title, $message, array $userIds)
    {
        // $client = $this->getClient();
        // $notification_channels = ($client->notification_channels) ? $client->notification_channels : ['email', 'in_app'];
        $notification_channels = ['email', 'in_app'];
        try {

            $recipients = VendorUser::whereIn('id', $userIds)->get();

            foreach ($recipients as $recipient) {

                Mail::to($recipient)->send(new SendMail($title, $message, $recipient));
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function fetchAvailableModules()
    {
        $modules = AvailableModule::orderBy('name')->get();
        return response()->json(compact('modules'));
    }
    public function searchEmailList(Request $request)
    {
        $string = $request->email_string;
        $emails = EmailList::where('email', 'LIKE', '%' . $string . '%')
            ->orWhere('name', 'LIKE', '%' . $string . '%')
            ->get();
        return response()->json(compact('emails'), 200);
    }
    // public function fetchClientActivatedModules(Request $request, Client $client)
    // {
    //     $partner_id = $client->partner_id;
    //     $activated_modules = ActivatedModule::with('availableModule')->where('partner_id', $partner_id)->where('client_ids', '!=', NULL)->get();
    //     $modules = [];
    //     foreach ($activated_modules as $activated_module) {
    //         $client_ids_array = explode('~', $activated_module->client_ids);
    //         if (in_array($client->id, $client_ids_array)) {
    //             $modules[] = $activated_module->availableModule;
    //         }
    //     }

    //     return response()->json(compact('modules'));
    // }

    public function getVendorClientUserIds($vendorId)
    {
        $vendor = Vendor::find($vendorId);
        $ids = [];
        try {

            foreach ($vendor->client_users as $value) {
                $ids[] = $value->id;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $ids;
    }

    public function fetchCaptcha(Request $request)
    {
        $ip = request()->ip();
        $im = imagecreatetruecolor(150, 75);

        $bg = imagecolorallocate($im, 220, 220, 220);
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        $font = dirname(__FILE__) . '/fonts/arial.ttf';
        // set background colour.
        imagefilledrectangle($im, 0, 0, 150, 75, $bg);

        // output text.
        imagettftext($im, 35, 0, 10, 55, $black, $font, 'ABCD');

        for ($i = 0; $i < 50; $i++) {
            //imagefilledrectangle($im, $i + $i2, 5, $i + $i3, 70, $black);
            imagesetthickness($im, rand(1, 5));
            imagearc(
                $im,
                rand(1, 300), // x-coordinate of the center.
                rand(1, 300), // y-coordinate of the center.
                rand(1, 300), // The arc width.
                rand(1, 300), // The arc height.
                rand(1, 300), // The arc start angle, in degrees.
                rand(1, 300), // The arc end angle, in degrees.
                (rand(0, 1) ? $black : $white) // A color identifier.
            );
        }

        header('Content-type: image/png');
        imagepng($im);
        imagedestroy($im);
    }
    public function industryCertifications()
    {
        return ['ISO 27001', 'SOC 2', 'GDPR', 'PCI-DSS'];
    }

    public function callOpenAISearch($query)
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-5',
            //'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $query],
            ],
        ]);
        $ai_response = json_decode($result->choices[0]->message->content);
        return $ai_response;
    }
}
