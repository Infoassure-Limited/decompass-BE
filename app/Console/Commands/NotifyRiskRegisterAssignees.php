<?php

namespace App\Console\Commands;

use App\Models\RiskRegister;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class NotifyRiskRegisterAssignees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notify-risk-register-assignees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification to risk register assignees';


    public function checkForUnsentAssetAssignedNotifications()
    {
        RiskRegister::groupBy('asset_id')->where('assignee_ids', '!=' , NULL)
        ->where('asset_id', '!=', NULL)
        ->where('notification_sent', 0)
            ->chunkById(100, function (Collection $risk_registers) {
                foreach ($risk_registers as $risk_register) {
                    $title = "Task Assigned";
                    $url = '<a href="'.env('FRONTEND_URL').'/modules/isms-index#risk-management" target="_blank">Click Here</a>';
                    $message = "You have been assigned to provide existing controls to threats associated with the following asset: ". strtoupper($risk_register->asset_name)."<br> To do so, <ul>".
                    "<li>Sign in to your account</li>".
                    "<li>Then come back to this message and $url</li>";

                    $recipients = User::whereIn('id', $risk_register->assignee_ids)->get();
                    foreach ($recipients as $recipient) {

                        Mail::to($recipient)->send(new SendMail($title, $message, $recipient));
                    }
                    RiskRegister::where('asset_id', $risk_register->asset_id)->update(['notification_sent' => 1]);


                }
            }, $column = 'id');
    }
    
    public function checkForUnsentBusinessProcessAssignedNotifications()
    {
        RiskRegister::groupBy('business_process_id')
        ->with('businessProcess')
        ->where('business_process_id', '!=', NULL)
        ->where('assignee_ids', '!=' , NULL)
        ->where('notification_sent', 0)
            ->chunkById(100, function (Collection $risk_registers) {
                foreach ($risk_registers as $risk_register) {
                    $business_process = $risk_register->businessProcess->name;
                    $url = '<a href="'.env('FRONTEND_URL').'/modules/bcms-index#risk-management" target="_blank">Click Here</a>';
                    $title = "Task Assigned";
                    $message = "You have been assigned to provide existing controls to threats associated with the following business process: ". strtoupper($business_process)."<br> To do so, <ul>".
                    "<li>Sign in to your account</li>".
                    "<li>Then come back to this message and $url</li>";
                    $recipients = User::whereIn('id', $risk_register->assignee_ids)->get();
                    foreach ($recipients as $recipient) {

                        Mail::to($recipient)->send(new SendMail($title, $message, $recipient));
                    }
                    RiskRegister::where('business_process_id', $risk_register->business_process_id)->update(['notification_sent' => 1]);


                }
            }, $column = 'id');
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->checkForUnsentAssetAssignedNotifications();
        $this->checkForUnsentBusinessProcessAssignedNotifications();

    }
}
