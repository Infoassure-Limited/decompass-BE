<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use App\Models\VendorDueDiligence\Contract;
use App\Models\VendorDueDiligence\SlaConfig;
use App\Models\User;
use App\Models\VendorDueDiligence\Vendor;
use App\Models\VendorDueDiligence\VendorPerformanceMetric;
use App\Models\VendorDueDiligence\VendorPerformanceScorecard;
use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;
class ContractsAndSLAController extends Controller
{
    //
    public function fetchContracts(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $contracts = Contract::with('sla.performanceMetrics', 'vendor', 'client')
            ->where([
                'vendor_id' => $vendor_id,
                'client_id' => $client_id,
            ])
            ->get();
        return response()->json(compact('contracts'), 200);
    }

    public function fetchSLA(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $contract_id = $request->contract_id;
        $slas = SlaConfig::with('performanceMetrics')->where([
            'vendor_id' => $vendor_id,
            'client_id' => $client_id,
            'contract_id' => $contract_id
        ])->get();
        return response()->json(compact('slas'), 200);
    }

    public function showSLA(Request $request, SlaConfig $sla)
    {
        $sla = $sla->with('performanceMetrics', 'scoreCards.kpiMetrics')->find($sla->id);
        return response()->json(compact('sla'), 200);
    }

    public function uploadContract(Request $request)
    {

        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $title = $request->title;
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $expiry_date = date('Y-m-d', strtotime($request->expiry_date));
        $file = $request->file('file_uploaded');
        if ($file != null && $file->isValid()) {

            $formated_name = str_replace(' ', '_', ucwords($title));
            $file_name = $formated_name . '_' . $vendor_id . "." . $file->guessClientExtension();

            $link = $file->storeAs('vendors/' . $vendor_id . '/documents', $file_name, 'public');
            if (isset($request->id) && $request->id !== 'undefined' && $request->id !== null) {
                Contract::updateOrCreate([
                    'id' => $request->id,
                    'vendor_id' => $vendor_id,
                    'client_id' => $client_id,
                ], ['title' => $title, 'file_link' => $link, 'start_date' => $start_date, 'expiry_date' => $expiry_date]);
            } else {
                $ticket = Contract::orderBy('id', 'DESC')->first();
                $prepend_id = ($ticket != null) ? $ticket->id + 1 : 1;
                $contract_no = 'CT-' . $prepend_id . randomNumber();
                $contract = Contract::updateOrCreate([
                    'vendor_id' => $vendor_id,
                    'client_id' => $client_id,
                    'title' => $title
                ], ['contract_no' => $contract_no, 'file_link' => $link, 'start_date' => $start_date, 'expiry_date' => $expiry_date]);

                $vendorUserIds = User::where('vendor_id', $vendor_id)->pluck('id')->toArray();

                $title = "New Contract Created";
                //log this event
                $description = "New contract with number $contract->contract_no has been created";
                //log this event
                $this->sendVendorNotification($title, $description, $vendorUserIds);
                // Log this action

                $userIds = $this->getVendorClientUserIds($vendor_id);
                $this->sendNotification($title, $description, $userIds);
            }


            // Log this action
            // $userIds = $this->getVendorClientUserIds($vendor_id);
            // $token = $request->bearerToken();
            // $user = User::where('api_token', $token)->first();
            // $name = $user->name;// . ' (' . $user->email . ')';
            // $title = "Contract Uploaded";
            // $userIds = $this->getVendorClientUserIds($vendor_id);
            // //log this event
            // $description = "The vendor profile for $vendor->name was updated by $name. <br>" . $extra_message;
            // $this->sendNotification($title, $description, $userIds);
        } else {
            if (isset($request->id) && $request->id !== 'undefined' && $request->id !== null) {
                Contract::updateOrCreate([
                    'id' => $request->id,
                    'vendor_id' => $vendor_id,
                    'client_id' => $client_id,
                ], ['title' => $title, 'start_date' => $start_date, 'expiry_date' => $expiry_date]);
            }
        }

    }

    public function saveSLAConfig(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $contract_id = $request->contract_id;
        $contract = Contract::find($contract_id);
        $performance_metrics = $request->performance_metrics;
        $data = $request->toArray();
        $sla = SlaConfig::updateOrCreate([
            'vendor_id' => $vendor_id,
            'client_id' => $client_id,
            'contract_id' => $contract_id
        ], $data);
        $this->saveVendorPerformanceMetrics($vendor_id, $client_id, $contract_id, $sla->id, $performance_metrics);
        // Log this action

        $vendorUserIds = User::where('vendor_id', $vendor_id)->pluck('id')->toArray();

        $title = "Contract SLA Created";
        //log this event
        $description = "SLA for contract with number $contract->contract_no has been created";
        //log this event
        $this->sendVendorNotification($title, $description, $vendorUserIds);
        // Log this action

        $userIds = $this->getVendorClientUserIds($vendor_id);
        $this->sendNotification($title, $description, $userIds);
    }

    private function saveVendorPerformanceMetrics($vendor_id, $client_id, $contract_id, $sla_id, $performance_metrics)
    {
        foreach ($performance_metrics as $data) {
            $metrics = $data['metrics'];
            if ($metrics != '') {
                $metric = VendorPerformanceMetric::updateOrCreate([
                    'vendor_id' => $vendor_id,
                    'client_id' => $client_id,
                    'contract_id' => $contract_id,
                    'sla_config_id' => $sla_id,
                    'metrics' => $metrics,
                ], $data);
                $this->setKPIScores($metric);
            }

        }
    }
    private function setKPIScores(VendorPerformanceMetric $metric)
    {
        //  we want to make it 52 weeks a year, giving 4 weeks per month
        $value = [];
        for ($i = 1; $i <= 52; $i++) {
            # code...
            $value[$i] = [NULL, '#f0f0f0']; // this is the assessment value and color code
        }
        VendorPerformanceScorecard::firstOrCreate([
            'vendor_id' => $metric->vendor_id,
            'client_id' => $metric->client_id,
            'contract_id' => $metric->contract_id,
            'sla_config_id' => $metric->sla_config_id,
            'vendor_performance_metric_id' => $metric->id,
        ], ['scores' => $value]);
    }
    public function updateKPIScores(Request $request, VendorPerformanceScorecard $score)
    {
        $new_data = [];
        $key = $request->key;
        $value = $request->value;
        $performance_metrics = VendorPerformanceMetric::find($score->vendor_performance_metric_id);
        $target = $performance_metrics->target;
        $unit = $performance_metrics->unit;
        if ($value != NULL) {
            $assessments_data = $score->scores;
            foreach ($assessments_data as $k => $val) {
                if ($k == $key) {
                    $val[0] = $value;
                    $val[1] = $this->getColourIndicatorFormValue($value, $target, $unit);
                }
                $new_data[$k] = $val;
            }

            $score->scores = $new_data;
            $score->save();
        }

        return response()->json(['message' => 'Success'], 200);
    }
    private function getColourIndicatorFormValue($value, $target, $unit)
    {
        switch ($unit) {
            case '%':
                if ((int) $value >= (int) $target) {
                    return '#67c23a';
                } else {
                    return '#e06666';
                }

            case 'Hrs':
                if ((int) $value <= (int) $target) {
                    return '#67c23a';
                } else {
                    return '#e06666';
                }
            case 'Days':
                if ((int) $value <= (int) $target) {
                    return '#67c23a';
                } else {
                    return '#e06666';
                }
        }
    }
    public function saveVendorPerformanceScore(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $contract_id = $request->contract_id;
        $sla_config_id = $request->sla_config_id;
        $data = $request->toArray();
        VendorPerformanceScorecard::updateOrCreate([
            'vendor_id' => $vendor_id,
            'client_id' => $client_id,
            'contract_id' => $contract_id,
            'sla_config_id' => $sla_config_id
        ], $data);

        // Log this action


    }

    public function contractRenewal(Request $request, Contract $contract)
    {
        $contract->status = $request->status;
        $contract->renewal_details = $request->renewal_details;
        $contract->save();

        $details = $contract->renewal_details->notes;

        $vendorUserIds = User::where('vendor_id', $contract->vendor_id)->pluck('id')->toArray();

        $title = "Contract $contract->status";
        //log this event
        $description = "Contract with number $contract->contract_no has been $contract->status. You can see details below <br>" .
            $details;
        //log this event
        $this->sendVendorNotification($title, $description, $vendorUserIds);
        // Log this action

        $userIds = $this->getVendorClientUserIds($contract->vendor_id);
        //log this event
        $description = "Contract with number $contract->contract_no has been $contract->status";
        $this->sendNotification($title, $description, $userIds);
    }
    public function destroyMetrics(Request $request, VendorPerformanceMetric $metrics)
    {
        $metrics->delete();

        // you can log notification
    }

    public function pdfToText(Request $request)
    {
        $file = $request->file('file_uploaded');
        $file_text = Pdf::getText(
            $file,
            'C:\xampp\htdocs\3core-projects\infoassure-backend\xpdf-tools-win-4.05\bin64\pdftotext.exe'
        );
        return explode(' ', $file_text);
    }
}
