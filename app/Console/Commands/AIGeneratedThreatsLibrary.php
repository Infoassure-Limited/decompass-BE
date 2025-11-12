<?php

namespace App\Console\Commands;

use App\Models\Asset;
use App\Models\BusinessProcess;
use App\Models\NDPA\PersonalDataAssessment;
use App\Models\RiskRegister;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use OpenAI\Laravel\Facades\OpenAI;

class AIGeneratedThreatsLibrary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:generated-threats-library';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform ai generated threats library';


    public function autoGenerateAndSaveAssetRiskRegisters()
    {
        Asset::with('client', 'assetType')->where('threat_generated', 0)
            ->chunkById(100, function (Collection $assets) {
                foreach ($assets as $asset) {
                    $client = $asset->client;
                    $asset_type = $asset->assetType;
                    $description = $asset->description;
                    $generated_threats = $this->generativeThreatIntelligence($asset->name, $asset_type->name, $description);
                    if ($generated_threats !== null && count($generated_threats) > 0) {
                        $this->loadAutoRiskRegisterData($generated_threats, 'isms', 'General', null, null, $asset->id, $asset->name, $asset_type->id, $asset_type->name, $client);
                        $asset->threat_generated = 1;
                        $asset->save();
                    }



                }
            }, $column = 'id');
    }

    public function autoGenerateAndSaveProcessRiskRegisters()
    {
        BusinessProcess::with('client', 'businessUnit')->where('threat_generated', 0)
            ->chunkById(100, function (Collection $business_processes) {
                foreach ($business_processes as $business_process) {
                    $client = $business_process->client;
                    $business_unit = $business_process->businessUnit;
                    $description = $business_process->description;
                    $generated_threats = $this->generativeThreatIntelligence($business_process->name, $business_unit->name, $description);
                    if ($generated_threats !== null && count($generated_threats) > 0) {
                        $this->loadAutoRiskRegisterData($generated_threats, 'bcms', 'Strategic Risks', $business_unit->id, $business_process->id, null, null, null, null, $client);
                        $business_process->threat_generated = 1;
                        $business_process->save();
                    }



                }
            }, $column = 'id');
    }

    public function autoToadPDAToRiskRegister()
    {

        PersonalDataAssessment::with('client', 'businessUnit', 'businessProcess')->where('threat_generated', 0)
            ->chunkById(100, function (Collection $pdas) {
                foreach ($pdas as $pda) {
                    $type = 'Personal Data Asset';
                    $client = $pda->client;
                    $business_unit_id = $pda->business_unit_id;
                    $business_process_id = $pda->business_process_id;
                    $asset_name = implode(',', $pda->personal_data_item);
                    $description = $pda->description;

                    $generated_threats = $this->generativeThreatIntelligence($asset_name, $type, $description);
                    if ($generated_threats !== null && count($generated_threats) > 0) {
                        $this->loadAutoRiskRegisterData($generated_threats, 'ndpa', $type, $business_unit_id, $business_process_id, null, $asset_name, null, $type, $client);
                        $pda->threat_generated = 1;
                        $pda->save();
                    }



                }
            }, $column = 'id');
    }
    private function generativeThreatIntelligence($item, $category, $description)
    {
        if ($description !== NULL) {
            $message = "Generate at least 5 cyber security threats associated with ###$item### under ###$category### described as ###$description###.";
        } else {
            $message = "Generate at least 5 cyber security threats associated with ###$item### under ###$category###.";
        }
        $instruction = "
            Also provide the vulnerabilities for each of the threats in an array format.
            Format the responses as an array of objects in json format for easy extraction in the format below:
            
            threat: <threat>
            vulnerabilities: <vulnerabilities>";

        $content = $message . $instruction;

        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            //'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $content],
            ],
        ]);
        $ai_response = json_decode($result->choices[0]->message->content);
        return $ai_response;
    }

    private function loadAutoRiskRegisterData($generatedThreats, $module, $type, $business_unit_id, $business_process_id, $asset_id, $asset_name, $asset_type_id, $asset_type_name, $client)
    {
        foreach ($generatedThreats as $generated_threat) {
            $threat = $generated_threat->threat;
            $vulnerabilities = $generated_threat->vulnerabilities;
            $riskRegister = RiskRegister::firstOrCreate([
                'client_id' => $client->id,
                'module' => $module,
                'business_unit_id' => $business_unit_id,
                'business_process_id' => $business_process_id,
                'asset_type_id' => $asset_type_id,
                'asset_type_name' => $asset_type_name,
                'asset_id' => $asset_id,
                'asset_name' => $asset_name,
                'sub_unit' => '',
                'type' => $type,
                'threat' => $threat,
                'vulnerability_description' => implode(', ', $vulnerabilities),

            ]);
            if ($riskRegister->risk_id == null) {
                $riskRegister->risk_id = 'RSK-' . generateNumber($client->next_general_risk_id);

                $riskRegister->control_no = 'CTRL-' . generateNumber($client->next_general_risk_id);
                $riskRegister->save();
                $client->next_general_risk_id += 1;
                $client->save();
            }

        }
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->autoGenerateAndSaveAssetRiskRegisters();
        $this->autoGenerateAndSaveProcessRiskRegisters();
        $this->autoToadPDAToRiskRegister();


    }
}
