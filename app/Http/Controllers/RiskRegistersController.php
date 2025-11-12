<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\BusinessProcess;
use App\Models\BusinessUnit;
use App\Models\Client;
use App\Models\GeneralRiskLibrary;
use App\Models\NDPA\PersonalDataAssessment;
use App\Models\RiskAssessment;
use App\Models\RiskControlSelfAssessment;
use App\Models\RiskImpact;
use App\Models\RiskImpactArea;
use App\Models\RiskImpactOnArea;
use App\Models\RiskLikelihood;
use App\Models\RiskMatrix;
use App\Models\RiskRegister;
use Artisan;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class RiskRegistersController extends Controller
{
    // public function __construct(Request $httpRequest)
    // {
    //     parent::__construct($httpRequest);
    //     $this->middleware(function ($request, $next) {
    //         try {
    //             Artisan::call('ai:generated-threats-library');
    //         } catch (\Throwable $th) {
    //             //throw $th;
    //         }
    //         return $next($request);
    //     });
    // }
    private function loadAutoRiskRegisterData($generatedThreats, $asset, $asset_type, $client)
    {
        foreach ($generatedThreats as $generated_threat) {
            $threat = $generated_threat->threat;
            $vulnerabilities = $generated_threat->vulnerabilities;
            $riskRegister = RiskRegister::firstOrCreate([
                'client_id' => $asset->client_id,
                'module' => 'isms',
                'business_unit_id' => null,
                'business_process_id' => null,
                'asset_type_id' => $asset->asset_type_id,
                'asset_type_name' => $asset_type->name,
                'asset_id' => $asset->id,
                'asset_name' => $asset->name,
                'sub_unit' => '',
                'type' => 'General',
                'threat' => $threat,
                'vulnerability_description' => implode(', ', $vulnerabilities),

            ], [

            ]);
            if ($riskRegister->risk_id == null) {
                $riskRegister->risk_id = 'RSK' . generateNumber($client->next_general_risk_id);

                $riskRegister->control_no = 'CTRL' . generateNumber($client->next_general_risk_id);
                $riskRegister->save();
                $client->next_general_risk_id += 1;
                $client->save();
            }

        }
    }
    public function autoGenerateAndSaveAssetRiskRegisters(Request $request)
    {
        $client = $this->getClient();
        $client_id = $client->id;
        if (isset($request->id) && $request->id != '') {
            $id = $request->id;
            $asset = Asset::with('assetType')->find($id);
            $risk_register_count = RiskRegister::where('asset_id', $asset->id)->count();
            if ($risk_register_count < 1) {
                $asset_type = $asset->assetType;
                $description = $asset->description;
                $generated_threats = $this->generativeThreatIntelligence($asset->name, $asset_type->name, $description);
                if ($generated_threats !== null && count($generated_threats) > 0) {
                    $this->loadAutoRiskRegisterData($generated_threats, $asset, $asset_type, $client);
                }


            }
        } else {
            $assets = Asset::with('assetType')->where('client_id', $client_id)->get();
            foreach ($assets as $asset) {

                $risk_register_count = RiskRegister::where('asset_id', $asset->id)->count();
                if ($risk_register_count < 1) {
                    $asset_type = $asset->assetType;
                    $description = $asset->description;
                    $generated_threats = $this->generativeThreatIntelligence($asset->name, $asset_type->name, $description);
                    if ($generated_threats !== null && count($generated_threats) > 0) {
                        $this->loadAutoRiskRegisterData($generated_threats, $asset, $asset_type, $client);
                    }


                }
            }
        }

    }
    // }
    public function autoGenerateAndSaveProcessRiskRegisters(Request $request)
    {
        $client = $this->getClient();
        $client_id = $client->id;
        if (isset($request->id) && $request->id != '') {
            $id = $request->id;
            $business_process = BusinessProcess::with('businessUnit')->find($id);
            $risk_register_count = RiskRegister::where('business_process_id', $business_process->id)->count();
            if ($risk_register_count < 1) {
                $business_unit = $business_process->businessUnit;
                $description = $business_process->description;
                $generated_threats = $this->generativeThreatIntelligence($business_process->name, $business_unit->name, $description);
                if ($generated_threats !== null && count($generated_threats) > 0) {
                    $this->loadAutoRiskRegisterData($generated_threats, $business_process, $business_unit, $client);
                }


            }
        } else {
            $business_processes = BusinessProcess::with('businessUnit')->where('client_id', $client_id)->get();
            foreach ($business_processes as $business_process) {

                $risk_register_count = RiskRegister::where('business_process_id', $business_process->id)->count();
                if ($risk_register_count < 1) {
                    $business_unit = $business_process->businessUnit;
                    $description = $business_process->description;
                    $generated_threats = $this->generativeThreatIntelligence($business_process->name, $business_unit->name, $description);
                    if ($generated_threats !== null && count($generated_threats) > 0) {
                        $this->loadAutoRiskRegisterData($generated_threats, $business_process, $business_unit, $client);
                    }


                }
            }
        }

    }
    private function generativeThreatIntelligence($item, $category, $description)
    {
        if ($description !== NULL) {
            $message = "Generate at least 5 cyber security threats associated with $item under $category described as $description.";
        } else {
            $message = "Generate at least 5 cyber security threats associated with $item under $category.";
        }
        $instruction = "
            Also provide the vulnerabilities and recommended controls for each of the threats in an array format.
            Format the responses as an array of objects in json format for easy extraction in the format below:
            
            threat: <threat>
            vulnerabilities: <vulnerabilities>
            recommended_controls: <recommended_controls>";

        $content = $message . $instruction;

        return $this->callOpenAISearch($content);
    }
    public function fetchDefaultRiskImpactArea()
    {
        $impact_areas = defaultImpactCriteria();
        return response()->json(compact('impact_areas'));
    }

    public function setupRiskMatrices(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $impact_matrices = riskImpactMatrix();
        $likelihood_matrices = riskLikelihoodMatrix();
        foreach ($impact_matrices as $matrix => $matrix_array) {
            foreach ($matrix_array as $content) {
                RiskImpact::firstOrCreate(
                    [
                        'client_id' => $client_id,
                        'matrix' => $matrix,
                        'value' => $content['value'],
                    ],
                    [
                        'name' => $content['name'],
                    ]
                );
            }

        }
        foreach ($likelihood_matrices as $matrix => $matrix_array) {
            foreach ($matrix_array as $content) {
                RiskLikelihood::firstOrCreate(
                    [
                        'client_id' => $client_id,
                        'matrix' => $matrix,
                        'value' => $content['value'],
                    ],
                    [
                        'name' => $content['name'],
                        'summary' => $content['summary'],
                    ]
                );
            }

        }
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        if (!$risk_matrix) {
            $risk_matrix = new RiskMatrix();
            $risk_matrix->client_id = $client_id;
            $risk_matrix->current_matrix = '3x3';
            $risk_matrix->save();
        }
        $this->setupImpactOnAreas($client_id);
        $risk_matrix = RiskMatrix::with('creator', 'approver')->where('client_id', $client_id)->first();
        $active_matrix = $risk_matrix->current_matrix;

        $impact_matrices = RiskImpact::with([
            'impactOnAreas' => function ($q) use ($client_id) {
                $q->where('client_id', $client_id);
            },
            'impactOnAreas.impactArea'
        ])->where(['client_id' => $client_id, 'matrix' => $active_matrix])->orderBy('value')->get()->groupBy('matrix');

        $likelihood_matrices = RiskLikelihood::where(['client_id' => $client_id, 'matrix' => $active_matrix])->orderBy('value')->get()->groupBy('matrix');
        $matrices = ['3x3', '5x5'];
        return response()->json(compact('impact_matrices', 'likelihood_matrices', 'risk_matrix', 'matrices'), 200);
    }

    private function setupImpactOnAreas($client_id)
    {
        $risk_impacts = RiskImpact::where('client_id', $client_id)->get();
        $impact_area_ids = RiskImpactArea::where('client_id', $client_id)->pluck('id');
        foreach ($risk_impacts as $risk_impact) {
            foreach ($impact_area_ids as $impact_area_id) {
                RiskImpactOnArea::firstOrCreate(
                    [
                        'client_id' => $client_id,
                        'risk_impact_id' => $risk_impact->id,
                        'impact_value' => $risk_impact->value,
                        'risk_impact_area_id' => $impact_area_id,
                        'matrix' => $risk_impact->matrix,
                    ]
                );
            }

        }
    }
    public function fetchAssetRiskRegisters(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }

        $asset_types = Asset::with([
            'riskRegisters' => function ($q) use ($client_id) {
                $q->where('client_id', $client_id);
            }
        ])
            ->join('asset_types', 'asset_types.id', '=', 'assets.asset_type_id')
            ->where('assets.client_id', $client_id)
            ->select('assets.*', 'assets.id as id', 'assets.name as name', 'asset_types.name as asset_type_name')
            ->get()
            ->groupBy('asset_type_name');
        return response()->json(compact('asset_types'), 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchRiskMatrix(Request $request)
    {
        $risk_matrix = RiskMatrix::with('creator', 'approver')
            ->where('client_id', $request->client_id)
            ->first();
        return response()->json(compact($risk_matrix), 200);
    }


    /**
     * Display the specified resource in storage.
     *
     * @param  \App\Models\RiskMatrix  $riskMatrix
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(RiskMatrix $riskMatrix)
    {
        $risk_matrix = $riskMatrix->with('creator', 'approver')->find($riskMatrix->id);
        return response()->json(compact('risk_matrix'), 200);
    }
    /**
     * Propose a specific matrix
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function proposeMatrix(Request $request)
    {
        $client_id = $request->client_id;
        $user_id = $this->getUser()->id;
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        if (!$risk_matrix) {
            $risk_matrix = new RiskMatrix();
        }
        $risk_matrix->client_id = $client_id;
        $risk_matrix->proposed_matrix = $request->proposed_matrix;
        $risk_matrix->created_by = $user_id;
        $risk_matrix->save();
        return $this->show($risk_matrix);
    }

    /**
     * Approve a proposed matrix
     *
     * @param  \App\Models\RiskMatrix  $riskMatrix
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveMatrix(RiskMatrix $riskMatrix)
    {
        $user_id = $this->getUser()->id;
        $riskMatrix->current_matrix = $riskMatrix->proposed_matrix;
        $riskMatrix->proposed_matrix = NULL;
        $riskMatrix->approved_by = $user_id;
        $riskMatrix->save();
        return $this->show($riskMatrix);
    }
    /**
     * Set the Client Risk Appetite
     *
     * @param  \App\Models\RiskMatrix  $riskMatrix
     * @return \Illuminate\Http\JsonResponse
     */
    public function setRiskAppetite(Request $request, RiskMatrix $riskMatrix)
    {
        // $user_id = $this->getUser()->id;
        $riskMatrix->risk_appetite = $request->risk_appetite;
        $riskMatrix->save();
        return $this->show($riskMatrix);
    }

    public function customizeRiskMatrix(Request $request)
    {
        $id = $request->id;
        $table = $request->table;
        if ($table == 'impact') {
            $matrix = RiskImpact::find($id);
        } else {
            $matrix = RiskLikelihood::find($id);
        }
        $field = $request->field;
        $value = $request->value;
        $matrix->$field = $value;
        $matrix->save();
        return response()->json([]);
    }
    public function fetchModuleRiskRegisters(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $module = $request->module;
        $modules = ['isms'];
        if ($module == 'bcms' || $module == 'ndpa' || $module == 'rcsa') {
            $modules = ['bcms', 'ndpa', 'rcsa'];
        }
        $risk_registers = RiskRegister::leftJoin('business_units', 'risk_registers.business_unit_id', 'business_units.id')
            ->leftJoin('business_processes', 'risk_registers.business_process_id', 'business_processes.id')
            ->where(['risk_registers.client_id' => $client_id])
            ->whereIn('module', $modules)
            ->where('submit_mode', 'final')
            ->select('risk_registers.*', 'business_units.group_name as l1', 'business_units.unit_name as l2', 'business_units.unit_name as business_unit', 'business_units.teams as teams', 'business_processes.name as business_process', 'business_processes.objective as business_process_objective', 'business_processes.generated_process_id as generated_process_id')
            ->get();
        return response()->json(compact('risk_registers'), 200);
    }
    /////////////////// RIKS REGISTERS ///////////////
    public function fetchRiskRegisters(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $type = $request->type;
        $asset_id = $request->asset_id;
        $business_process_id = $request->business_process_id;
        // $risk_registers = RiskRegister::with('businessUnit', 'businessProcess')->where(['client_id' => $client_id, 'business_unit_id' => $business_unit_id])->get();
        // $unsubmitted_risk_registers = RiskRegister::leftJoin('business_units', 'risk_registers.business_unit_id', 'business_units.id')
        //     ->leftJoin('business_processes', 'risk_registers.business_process_id', 'business_processes.id')
        //     ->where($condition)
        //     ->where('submit_mode', 'temporal')
        //     ->select('risk_registers.*', 'business_units.group_name as l1', 'business_units.unit_name as l2', 'business_units.unit_name as business_unit', 'business_units.teams as teams', 'business_processes.name as business_process', 'business_processes.objective as business_process_objective', 'business_processes.generated_process_id as generated_process_id', 'business_processes.name as business_process')
        //     ->get();




        $asset_risk_registers = [];
        $business_risk_registers = [];
        if ($asset_id !== null) {
            $asset_risk_registers_query = RiskRegister::leftJoin('business_units', 'risk_registers.business_unit_id', 'business_units.id')
                ->leftJoin('business_processes', 'risk_registers.business_process_id', 'business_processes.id')
                ->where(['risk_registers.client_id' => $client_id])
                // ->where('submit_mode', 'final')
                ->where('risk_registers.asset_id', $asset_id)
                ->select('risk_registers.*', 'business_units.group_name as l1', 'business_units.unit_name as l2', 'business_units.unit_name as business_unit', 'business_units.teams as teams', 'business_processes.name as business_process', 'business_processes.objective as business_process_objective', 'business_processes.generated_process_id as generated_process_id', 'business_processes.name as business_process');
            $asset_risk_registers = $asset_risk_registers_query->where('risk_registers.asset_id', $asset_id)->get();
        }

        if ($business_process_id !== null) {
            $business_risk_registers_query = RiskRegister::leftJoin('business_units', 'risk_registers.business_unit_id', 'business_units.id')
                ->leftJoin('business_processes', 'risk_registers.business_process_id', 'business_processes.id')
                ->where(['risk_registers.client_id' => $client_id])
                // ->where('submit_mode', 'final')
                ->select('risk_registers.*', 'business_units.group_name as l1', 'business_units.unit_name as l2', 'business_units.unit_name as business_unit', 'business_units.teams as teams', 'business_processes.name as business_process', 'business_processes.objective as business_process_objective', 'business_processes.generated_process_id as generated_process_id', 'business_processes.name as business_process');
            $business_risk_registers = $business_risk_registers_query->where('risk_registers.business_process_id', $business_process_id)->get();
        }
        return response()->json(compact('asset_risk_registers', 'business_risk_registers'), 200);
        // $business_unit_id = $request->business_unit_id;
        // if (isset($request->client_id)) {
        //     $client_id = $request->client_id;
        // } else {
        //     $client_id = $this->getClient()->id;
        // }
        // $risk_registers = RiskRegister::join('business_units', 'risk_registers.business_unit_id', 'business_units.id')
        //     ->where(['risk_registers.client_id' => $client_id, 'business_unit_id' => $business_unit_id])
        //     ->select('risk_registers.*', 'business_units.unit_name', \DB::raw('CONCAT(prepend_risk_no_value,risk_id) as risk_id'))
        //     ->get();
        // return response()->json(compact('risk_registers'), 200);
    }

    public function fetchBusinessUnitsWithRiskRegisters(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        // $this->loadPDAToRiskRegister($client_id);
        $business_units = BusinessUnit::with([
            'businessProcesses.riskRegisters' => function ($q) use ($client_id) {
                $q->where('client_id', $client_id);
            }
        ])->where('client_id', $client_id)->orderBy('id')->get();
        return response()->json(compact('business_units'), 200);
    }
    private function loadPDAToRiskRegister($client_id)
    {
        $module = 'ndpa';
        $type = 'Personal Data Asset';
        $pdas = PersonalDataAssessment::where('client_id', $client_id)->get();
        foreach ($pdas as $pda) {
            $asset_name = implode(',', $pda->personal_data_item); //$pda->personal_data_item; // implode(',', $pda->personal_data_item);
            $riskRegister = RiskRegister::where([
                'client_id' => $client_id,
                'module' => $module,
                'business_unit_id' => $pda->business_unit_id,
                'business_process_id' => $pda->business_process_id,
                'asset_name' => $asset_name,
                'type' => $type,
            ])->first();

            if (!$riskRegister) {
                $riskRegister = new RiskRegister();
            }
            $riskRegister->module = $module;
            $riskRegister->client_id = $client_id;
            $riskRegister->business_unit_id = $pda->business_unit_id;
            $riskRegister->business_process_id = $pda->business_process_id;
            $riskRegister->asset_type_name = $type;
            $riskRegister->type = $type;
            $riskRegister->asset_name = $asset_name;
            $riskRegister->submit_mode = 'final';

            $business_unit = BusinessUnit::find($pda->business_unit_id);
            $riskRegister->risk_id = $business_unit->prepend_risk_no_value . generateNumber($business_unit->next_risk_id);

            $riskRegister->control_no = 'CTRL' . generateNumber($business_unit->next_risk_id);
            $riskRegister->save();
            $business_unit->next_risk_id += 1;
            $business_unit->save();
        }
    }
    public function fetchPendingRiskRegister(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $riskRegister = RiskRegister::firstOrCreate([
            'client_id' => $client_id,
            'submit_mode' => 'pending'
        ])->first();
        return response()->json(compact('riskRegister'), 200);
    }
    private function saveNewRiskRegister(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        if (isset($request->id) && $request->id != '') {
            $riskRegister = RiskRegister::find($request->id);

        } else {
            $riskRegister = RiskRegister::where([
                'client_id' => $client_id,
                'business_unit_id' => $request->business_unit_id,
                'business_process_id' => $request->business_process_id,
                'asset_type_id' => $request->asset_type_id,
                'asset_id' => $request->asset_id,
                'sub_unit' => $request->sub_unit,
                'type' => $request->type,
                'threat' => $request->threat
            ])->first();
        }
        if (!$riskRegister) {
            $riskRegister = new RiskRegister();
        }
        $riskRegister->module = $request->module;
        $riskRegister->client_id = $client_id;
        $riskRegister->business_unit_id = $request->business_unit_id;
        $riskRegister->business_process_id = $request->business_process_id;
        $riskRegister->asset_type_id = $request->asset_type_id;
        $riskRegister->asset_type_name = $request->asset_type_name;

        $riskRegister->asset_id = $request->asset_id;
        $riskRegister->asset_name = $request->asset_name;
        $riskRegister->sub_unit = $request->sub_unit;
        $riskRegister->type = $request->type;
        $riskRegister->sub_type = $request->sub_type;
        $riskRegister->threat = $request->threat;
        $this->saveNewThreat($request->threat);
        $riskRegister->vulnerability_description = $request->vulnerability_description;
        $riskRegister->outcome = $request->outcome;
        $riskRegister->risk_owner = $request->risk_owner;
        $riskRegister->control_location = $request->control_location;
        $riskRegister->control_description = $request->control_description;
        $riskRegister->control_frequency = $request->control_frequency;
        $riskRegister->control_owner = $request->control_owner;
        $riskRegister->control_type = $request->control_type;
        $riskRegister->nature_of_control = $request->nature_of_control;
        $riskRegister->application_used_for_control = $request->application_used_for_control;
        $riskRegister->compensating_control = $request->compensating_control;
        $riskRegister->test_procedures = $request->test_procedures;
        if ($request->sample_size != null && $request->sample_size != '' && $request->sample_size != 'null') {
            $riskRegister->sample_size = $request->sample_size;
        }

        $riskRegister->data_required = $request->data_required;
        $riskRegister->link_to_evidence = $this->uploadRiskEvidenceDocument($request);
        $riskRegister->test_conclusion = $request->test_conclusion;
        $riskRegister->gap_description = $request->gap_description;
        $riskRegister->tod_improvement_opportunity = $request->tod_improvement_opportunity;
        $riskRegister->recommendation = $request->recommendation;
        $riskRegister->responsibility = $request->responsibility;
        $riskRegister->timeline = $request->timeline;
        $riskRegister->tod_gap_status = $request->tod_gap_status;
        $riskRegister->submit_mode = $request->submit_mode;
        $riskRegister->save();
        if ($riskRegister->risk_id == NULL && $request->submit_mode == 'final') {

            $client = Client::find($client_id);
            $riskRegister->risk_id = 'RSK' . generateNumber($client->next_general_risk_id);

            $riskRegister->control_no = 'CTRL' . generateNumber($client->next_general_risk_id);
            $riskRegister->save();
            $client->next_general_risk_id += 1;
            $client->save();

            // if ($request->business_unit_id != null) {
            //     $business_unit = BusinessUnit::find($request->business_unit_id);
            //     $riskRegister->risk_id = $business_unit->prepend_risk_no_value . generateNumber($business_unit->next_risk_id);

            //     $riskRegister->control_no = 'CTRL' . generateNumber($business_unit->next_risk_id);
            //     $riskRegister->save();
            //     $business_unit->next_risk_id += 1;
            //     $business_unit->save();
            // } else {
            //     $client = Client::find($client_id);
            //     $riskRegister->risk_id = 'RSK' . generateNumber($client->next_general_risk_id);

            //     $riskRegister->control_no = 'CTRL' . generateNumber($client->next_general_risk_id);
            //     $riskRegister->save();
            //     $client->next_general_risk_id += 1;
            //     $client->save();
            // }

        }
        return response()->json(['id' => $riskRegister->id], 200);
    }
    public function storeRiskRegister(Request $request)
    {
        $formData = $request->validate(
            [
                'threats' => 'required|string'
            ]
        );
        $threats = json_decode($request->threats);
        if (count($threats) < 1) {
            return response()->json(['message' => 'No threats provided'], 400);
        }
        foreach ($threats as $threat) {
            $request->threat = $threat->threat;
            $request->vulnerability_description = implode(',', $threat->vulnerabilities);
            $request->control_description = implode(', ', $threat->control_descriptions);
            $this->saveNewRiskRegister($request);
        }

    }
    private function saveNewThreat($threat)
    {
        GeneralRiskLibrary::firstOrCreate([
            'threats' => $threat
        ]);
    }
    private function uploadRiskEvidenceDocument(Request $request)
    {
        $client = Client::find($request->client_id);
        // $folder_key = $request->client_id;
        $folder_key = str_replace(' ', '_', ucwords($client->name));
        $file = $request->file('link_to_evidence');
        if ($file != null && $file->isValid()) {

            $name = $file->getClientOriginalName();
            // $name = $request->file('file_uploaded')->hashName();
            // $file_name = $name . "." . $request->file('file_uploaded')->extension();
            $link = $file->storeAs('clients/' . $folder_key . '/risk-evidence', $name, 'public');

            return $link;
        }
        return NULL;
    }
    public function updateRiskRegister(Request $request, RiskRegister $riskRegister)
    {
        $field = $request->field;
        $value = $request->value;
        $riskRegister->$field = $value;
        $riskRegister->save();
        // $riskRegister->risk_type = $request->risk_type;
        // $riskRegister->vulnerability_description = $request->vulnerability_description;
        // $riskRegister->threat_impact_description = $request->threat_impact_description;
        // $riskRegister->existing_controls = $request->existing_controls;
        // $riskRegister->risk_owner = $request->risk_owner;
        // $riskRegister->save();
        return response()->json(['message' => 'Successful'], 200);
    }
    public function deleteRiskRegister(Request $request, RiskRegister $riskRegister)
    {
        $riskRegister->delete();
        return response()->json(['message' => 'Successful'], 200);
    }
    /////////////////// END OF RIKS REGISTERS ///////////////
    /////////////////// RIKS IMPACT AREAS ///////////////
    public function fetchRiskImpactArea(Request $request)
    {
        // $business_unit_id = $request->business_unit_id;
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $risk_impact_areas = RiskImpactArea::where([
            'client_id' => $client_id,
            // 'business_unit_id' => $business_unit_id
        ])
            ->orderBy('area')
            ->get();
        return response()->json(compact('risk_impact_areas'), 200);
    }
    public function storeRiskImpactArea(Request $request)
    {
        $client_id = $request->client_id;
        // $business_unit_id = $request->business_unit_id;
        $impact_areas = $request->areas;
        foreach ($impact_areas as $area) {
            RiskImpactArea::firstOrCreate([
                'client_id' => $client_id,
                // 'business_unit_id' => $business_unit_id,
                'area' => trim($area)
            ]);
        }
        $this->setupImpactOnAreas($client_id);
        return response()->json(['message' => 'Successful'], 200);
    }
    public function updateRiskImpactArea(Request $request, RiskImpactArea $riskImpactArea)
    {
        $riskImpactArea->area = $request->area;
        $riskImpactArea->save();
        return response()->json(['message' => 'Successful'], 200);
    }
    public function deleteRiskImpactArea(Request $request, RiskImpactArea $riskImpactArea)
    {
        RiskImpactOnArea::where(['risk_impact_area_id' => $riskImpactArea->id])->delete();
        $riskImpactArea->delete();
        return response()->json(['message' => 'Successful'], 200);
    }
    /////////////////// END OF RIKS IMPACT AREAS///////////////
    /////////////////// RIKS IMPACT ON AREAS///////////////
    // public function fetchRiskImpactOnArea(Request $request)
    // {
    //     if (isset($request->client_id)) {
    //         $client_id = $request->client_id;
    //     } else {
    //         $client_id = $this->getClient()->id;
    //     }
    //     $risk_impact_area = RiskImpactOnArea::where(['client_id' => $client_id])->get();
    //     return response()->json(compact('risk_impact_area'), 200);
    // }
    // public function storeRiskImpactOnArea(Request $request)
    // {
    //     $impact_areas = $request->areas;
    //     foreach ($impact_areas as $area) {
    //         RiskImpactOnArea::firstOrCreate(
    //             [
    //                 'client_id' => $request->client_id,
    //                 'risk_impact_id' => $area->risk_impact_id,
    //                 'risk_impact_area_id' => $area->risk_impact_area_id,
    //                 'matrix' => $area->matrix,
    //             ],
    //             ['impact_level' => $area->impact_level]
    //         );
    //     }
    //     return response()->json(['message' => 'Successful'], 200);
    // }
    public function updateRiskImpactOnArea(Request $request, RiskImpactOnArea $riskImpactOnArea)
    {
        $riskImpactOnArea->impact_level = $request->impact_level;
        $riskImpactOnArea->save();
        return response()->json(['message' => 'Successful'], 200);
    }
    public function deleteRiskImpactOnArea(Request $request, RiskImpactOnArea $riskImpactOnArea)
    {
        $riskImpactOnArea->delete();
        return response()->json(['message' => 'Successful'], 200);
    }
    /////////////////// END OF RIKS IMPACT ON AREAS///////////////
}
