<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\BusinessUnit;
use App\Models\KeyRiskIndicatorAssessment;
use App\Models\Risk;
use App\Models\RiskAssessment;
use App\Models\RiskCategory;
use App\Models\RiskImpact;
use App\Models\RiskImpactArea;
use App\Models\RiskImpactOnArea;
use App\Models\RiskLikelihood;
use App\Models\RiskMatrix;
use App\Models\RiskRegister;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Constraint\Operator;

class RiskAssessmentsController extends Controller
{

    public function fetchRiskAppetite(Request $request)
    {
        $impacts = [];
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        $risk_appetite = null;
        if ($risk_matrix) {

            $risk_appetite = $risk_matrix->risk_appetite;
        }

        return response()->json(compact('risk_appetite'), 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchAssetTypes(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $asset_types = AssetType::with([
            'assets' => function ($q) use ($client_id) {
                $q->where('client_id', $client_id);
            }
        ])
            ->orderBy('asset_types.name')
            ->select('asset_types.*')
            ->get()
            ->makeHidden('asset_samples');
        return response()->json(compact('asset_types'), 200);
    }
    public function fetchAssetTypesWithAssetAssessments(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $asset_types = AssetType::with([
            'assets.riskAssessments' => function ($q) use ($client_id) {
                $q->where('client_id', $client_id);
            }
        ])->where('client_id', $client_id)->orderBy('name')->get();
        return response()->json(compact('asset_types'), 200);
    }

    public function fetchBusinessUnits(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $business_units = BusinessUnit::with([
            'businessProcesses' => function ($q) use ($client_id) {
                $q->where('client_id', $client_id);
            }
        ])->where('client_id', $client_id)
            ->orderBy('unit_name')
            ->get();
        return response()->json(compact('business_units'), 200);
    }


    public function fetchAssets(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $asset_type_id = $request->asset_type_id;
        $assets = Asset::where(['client_id' => $client_id, 'asset_type_id' => $asset_type_id])->orderBy('name')->get();
        return response()->json(compact('assets'), 200);
    }
    public function fetchImpacts(Request $request)
    {
        $impacts = [];
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        if ($risk_matrix) {
            $matrix = $risk_matrix->current_matrix;
            $impacts = RiskImpact::orderBy('value')->where('client_id', $client_id)->where('matrix', $matrix)->get();
        }

        return response()->json(compact('impacts'), 200);
    }
    public function fetchCategories(Request $request)
    {
        if (isset($request->client_id) && $request->client_id != '') {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $categories = RiskCategory::where('client_id', $client_id)->orderBy('name')->get();
        return response()->json(compact('categories'), 200);
    }
    public function generateRiskCategories(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $filename = portalPulicPath('risk_categories.json');
        $file_content = file_get_contents($filename);
        $risk_categories = json_decode($file_content);
        foreach ($risk_categories as $data) {
            $category = $data->category;
            $risk_category = RiskCategory::firstOrCreate([
                'client_id' => $client_id,
                'name' => $data->category
            ], [
                'sub_categories' => $data->sub_categories
            ]);
        }
        $categories = RiskCategory::where('client_id', $client_id)->orderBy('name')->get();
        return response()->json(compact('categories'), 200);
    }

    public function fetchLikelihoods(Request $request)
    {
        $likelihoods = [];
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        if ($risk_matrix) {
            $matrix = $risk_matrix->current_matrix;
            $likelihoods = RiskLikelihood::orderBy('value')->where('client_id', $client_id)->where('matrix', $matrix)->get();
        }
        return response()->json(compact('likelihoods'), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function saveImpacts(Request $request)
    // {
    //     $names_array = $request->names;
    //     foreach ($names_array as $name) {
    //         RiskImpact::firstOrCreate([
    //             'name' => trim($name)
    //         ]);
    //     }
    //     return response()->json(['message' => 'Successful'], 200);
    // }
    // public function saveAssetTypes(Request $request)
    // {
    //     if (isset($request->client_id)) {
    //         $client_id = $request->client_id;
    //     } else {
    //         $client_id = $this->getClient()->id;
    //     }
    //     $names_array = $request->names;
    //     foreach ($names_array as $name) {
    //         AssetType::firstOrCreate([
    //             'client_id' => $client_id,
    //             'name' => trim($name)
    //         ]);
    //     }
    //     return response()->json(['message' => 'Successful'], 200);
    // }

    // public function saveAssets(Request $request)
    // {
    //     if (isset($request->client_id)) {
    //         $client_id = $request->client_id;
    //     } else {
    //         $client_id = $this->getClient()->id;
    //     }
    //     $asset_type_id = $request->asset_type_id;
    //     $names_array = $request->names;
    //     foreach ($names_array as $name) {
    //         Asset::firstOrCreate([
    //             'client_id' => $client_id,
    //             'asset_type_id' => $asset_type_id,
    //             'name' => trim($name)
    //         ]);
    //     }
    //     return response()->json(['message' => 'Successful'], 200);
    // }
    // public function updateAssetType(Request $request, AssetType $asset_type)
    // {
    //     $asset_type->name = $request->name;
    //     $asset_type->save();
    //     return response()->json(['message' => 'Successful'], 200);
    // }
    // public function updateAsset(Request $request, Asset $asset)
    // {
    //     $asset->name = $request->name;
    //     // $asset->asset_type_id = $request->asset_type_id;
    //     $asset->save();
    //     return response()->json(['message' => 'Successful'], 200);
    // }
    public function saveCategories(Request $request)
    {
        $client_id = $request->client_id;
        $name = $request->name;
        $sub_categories = [];
        foreach ($request->sub_categories as $sub_category) {
            $sub_categories[] = ['name' => $sub_category];
        }
        RiskCategory::firstOrCreate([
            'client_id' => $client_id,
            'name' => trim($name),
            'sub_categories' => $sub_categories,
        ]);
        return response()->json(['message' => 'Successful'], 200);
    }
    public function updateCategory(Request $request, RiskCategory $riskCategory)
    {
        // $sub_categories = [];
        // foreach ($request->sub_categories as $sub_category) {
        //     $sub_categories[] = ['name' => $sub_category];
        // }
        $riskCategory->name = $request->name;
        $riskCategory->sub_categories = $request->sub_categories;
        $riskCategory->save();
        return response()->json(['message' => 'Successful'], 200);
    }
    // public function saveLikelihoods(Request $request)
    // {
    //     $names_array = $request->names;
    //     foreach ($names_array as $name) {
    //         RiskLikelihood::firstOrCreate([
    //             'name' => trim($name)
    //         ]);
    //     }
    //     return response()->json(['message' => 'Successful'], 200);
    // }

    public function deleteImpact(RiskImpact $value)
    {
        $value->delete();
        return response()->json([], 204);
    }
    public function deleteCategory(RiskCategory $value)
    {
        $value->delete();
        return response()->json([], 204);
    }
    public function deleteLikelihood(RiskLikelihood $value)
    {
        $value->delete();
        return response()->json([], 204);
    }


    public function fetchRiskAssessmentsOld(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $this->store($client_id);
        // $standard_id = 0;
        // if (isset($request->standard_id)) {
        //     $standard_id = $request->standard_id;
        // }
        $module = $request->module;
        $modules = ['isms'];
        if ($module == 'bcms' || $module == 'ndpa' || $module == 'rcsa') {
            $modules = ['bcms', 'ndpa', 'rcsa'];
        }
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        $risk_appetite = null;
        if ($risk_matrix) {

            $risk_appetite = $risk_matrix->risk_appetite;
        }

        $risk_assessment_query = RiskAssessment::join('risk_registers', 'risk_registers.id', 'risk_assessments.risk_register_id')
            ->leftJoin('business_units', 'business_units.id', 'risk_registers.business_unit_id')
            ->leftJoin('business_processes', 'business_processes.id', 'risk_registers.business_process_id')
            ->leftJoin('assets', 'assets.id', 'risk_registers.asset_id')
            ->leftJoin('asset_types', 'asset_types.id', 'risk_registers.asset_type_id')
            ->where(['risk_assessments.client_id' => $client_id/*, 'risk_assessments.module' => $module*/])
            ->select('risk_assessments.*', 'risk_registers.*', 'risk_assessments.id as id', 'risk_registers.asset_name as asset_name', 'risk_registers.asset_type_name as asset_type_name', 'business_units.unit_name as business_unit', \DB::raw("CONCAT_WS(' ',business_processes.generated_process_id, business_processes.name) as business_process"))
            ->orderBy('risk_id', 'ASC')
            ->get();

        $asset_types = $risk_assessment_query->where('asset_id', '!=', NULL)->groupBy(['asset_type_name', 'asset_name']);


        $business_units = $risk_assessment_query->where('business_process', '!=', NULL)->groupBy('business_process');
        // for tabular view
        $risk_assessments = RiskAssessment::join('risk_registers', 'risk_registers.id', 'risk_assessments.risk_register_id')
            ->leftJoin('business_units', 'business_units.id', 'risk_registers.business_unit_id')
            ->leftJoin('business_processes', 'business_processes.id', 'risk_registers.business_process_id')
            ->leftJoin('assets', 'assets.id', 'risk_registers.asset_id')
            ->leftJoin('asset_types', 'asset_types.id', 'risk_registers.asset_type_id')
            ->where(['risk_assessments.client_id' => $client_id])
            ->whereIn('risk_assessments.module', $modules)
            ->select('risk_assessments.*', 'risk_registers.*', 'risk_assessments.id as id', 'risk_registers.asset_name as asset_name', 'risk_registers.asset_type_name as asset_type_name', 'business_processes.name as business_process', 'business_units.unit_name as business_unit')
            ->orderBy('risk_id', 'ASC')
            ->get();

        return response()->json(compact('risk_assessments', 'asset_types', 'business_units', 'risk_appetite'), 200);
    }

    public function fetchRiskAssessments(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $this->store($client_id);
        // $standard_id = 0;
        // if (isset($request->standard_id)) {
        //     $standard_id = $request->standard_id;
        // }
        $type = $request->type;
        $asset_id = $request->asset_id;
        $business_process_id = $request->business_process_id;
        $modules = ['isms'];

        $module = $request->module;
        if ($module == 'bcms' || $module == 'ndpa' || $module == 'rcsa') {
            $modules = ['bcms', 'ndpa', 'rcsa'];
        }
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        $risk_appetite = null;
        if ($risk_matrix) {

            $risk_appetite = $risk_matrix->risk_appetite;
        }

        $asset_risk_assessments = [];
        $business_risk_assessments = [];
        if ($asset_id !== null) {
            $asset_risk_assessment_query = RiskAssessment::join('risk_registers', 'risk_registers.id', 'risk_assessments.risk_register_id')
                ->leftJoin('business_units', 'business_units.id', 'risk_registers.business_unit_id')
                ->leftJoin('business_processes', 'business_processes.id', 'risk_registers.business_process_id')
                ->leftJoin('assets', 'assets.id', 'risk_registers.asset_id')
                ->leftJoin('asset_types', 'asset_types.id', 'risk_registers.asset_type_id')
                ->where(['risk_assessments.client_id' => $client_id/*, 'risk_assessments.module' => $module*/])
                ->select('risk_assessments.*', 'risk_registers.*', 'risk_assessments.id as id', 'risk_registers.asset_name as asset_name', 'risk_registers.asset_type_name as asset_type_name', 'business_units.unit_name as business_unit', \DB::raw("CONCAT_WS(' ',business_processes.generated_process_id, business_processes.name) as business_process"))
                ->orderBy('risk_id', 'ASC');

            $asset_risk_assessments = $asset_risk_assessment_query->where('risk_assessments.asset_id', $asset_id)->get();
        }

        if ($business_process_id !== null) {
            $business_risk_assessment_query = RiskAssessment::join('risk_registers', 'risk_registers.id', 'risk_assessments.risk_register_id')
                ->leftJoin('business_units', 'business_units.id', 'risk_registers.business_unit_id')
                ->leftJoin('business_processes', 'business_processes.id', 'risk_registers.business_process_id')
                ->leftJoin('assets', 'assets.id', 'risk_registers.asset_id')
                ->leftJoin('asset_types', 'asset_types.id', 'risk_registers.asset_type_id')
                ->where(['risk_assessments.client_id' => $client_id/*, 'risk_assessments.module' => $module*/])
                ->select('risk_assessments.*', 'risk_registers.*', 'risk_assessments.id as id', 'risk_registers.asset_name as asset_name', 'risk_registers.asset_type_name as asset_type_name', 'business_units.unit_name as business_unit', \DB::raw("CONCAT_WS(' ',business_processes.generated_process_id, business_processes.name) as business_process"))
                ->orderBy('risk_id', 'ASC');

            $business_risk_assessments = $business_risk_assessment_query->where('risk_assessments.business_process_id', $business_process_id)->get();
        }

        // for tabular view
        // $risk_assessments = RiskAssessment::join('risk_registers', 'risk_registers.id', 'risk_assessments.risk_register_id')
        //     ->leftJoin('business_units', 'business_units.id', 'risk_registers.business_unit_id')
        //     ->leftJoin('business_processes', 'business_processes.id', 'risk_registers.business_process_id')
        //     ->leftJoin('assets', 'assets.id', 'risk_registers.asset_id')
        //     ->leftJoin('asset_types', 'asset_types.id', 'risk_registers.asset_type_id')
        //     ->where(['risk_assessments.client_id' => $client_id])
        //     ->whereIn('risk_assessments.module', $modules)
        //     ->select('risk_assessments.*', 'risk_registers.*', 'risk_assessments.id as id', 'risk_registers.asset_name as asset_name', 'risk_registers.asset_type_name as asset_type_name', 'business_processes.name as business_process', 'business_units.unit_name as business_unit')
        //     ->orderBy('risk_id', 'ASC')
        //     ->paginate(10);

        return response()->json(compact('asset_risk_assessments', 'business_risk_assessments', 'risk_appetite'), 200);
    }

    public function fetchAllRiskAssessments(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $this->store($client_id);
        // $standard_id = 0;
        // if (isset($request->standard_id)) {
        //     $standard_id = $request->standard_id;
        // }
        $asset_id = $request->asset_id;
        $business_process_id = $request->business_process_id;
        $modules = ['isms'];

        $module = $request->module;
        if ($module == 'bcms' || $module == 'ndpa' || $module == 'rcsa') {
            $modules = ['bcms', 'ndpa', 'rcsa'];
        }
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        $risk_appetite = null;
        if ($risk_matrix) {

            $risk_appetite = $risk_matrix->risk_appetite;
        }

        // for tabular view
        $risk_assessments = RiskAssessment::join('risk_registers', 'risk_registers.id', 'risk_assessments.risk_register_id')
            ->leftJoin('business_units', 'business_units.id', 'risk_registers.business_unit_id')
            ->leftJoin('business_processes', 'business_processes.id', 'risk_registers.business_process_id')
            ->leftJoin('assets', 'assets.id', 'risk_registers.asset_id')
            ->leftJoin('asset_types', 'asset_types.id', 'risk_registers.asset_type_id')
            ->where(['risk_assessments.client_id' => $client_id])
            ->whereIn('risk_assessments.module', $modules)
            ->where('risk_score', '!=', NULL)
            ->select('risk_assessments.*', 'risk_registers.*', 'risk_assessments.id as id', 'risk_registers.asset_name as asset_name', 'risk_registers.asset_type_name as asset_type_name', 'business_processes.name as business_process', 'business_units.unit_name as business_unit')
            ->orderBy('risk_id', 'ASC')
            ->get();
        ini_set('memory_limit', '128M');
        return response()->json(compact('risk_assessments', 'risk_appetite'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    private function store($client_id)
    {
        $risk_registers = RiskRegister::where(['client_id' => $client_id])->where('vulnerability_description', '!=', null)->get();

        $impact_fields = [
            ['name' => 'Confidentiality', 'slug' => 'C', 'impact_value' => '', 'meaning' => ''],
            ['name' => 'Integrity', 'slug' => 'I', 'impact_value' => '', 'meaning' => ''],
            ['name' => 'Availability', 'slug' => 'A', 'impact_value' => '', 'meaning' => ''],
            ['name' => 'Privacy', 'slug' => 'P', 'impact_value' => '', 'meaning' => ''],
        ];
        // if ($module == 'rcsa') {
        // $impact_fields = [];
        $impact_on_areas = [];
        $impact_areas = RiskImpactArea::where(['client_id' => $client_id])->orderBy('area')->get();
        foreach ($impact_areas as $impact_area) {
            $impact_on_areas[] = [
                'id' => $impact_area->id,
                'name' => $impact_area->area,
                'slug' => $impact_area->area,
                'impact_value' => '',
                'meaning' => ''
            ];
        }
        // }

        foreach ($risk_registers as $risk_register) {
            RiskAssessment::firstOrCreate(
                [
                    'client_id' => $risk_register->client_id,
                    'risk_register_id' => $risk_register->id,
                    // 'project_id' => $request->project_id,
                ],
                [
                    'module' => $risk_register->module,
                    'asset_id' => $risk_register->asset_id,
                    'asset_type_id' => $risk_register->asset_type_id,
                    'business_unit_id' => ($risk_register->business_unit_id != 0) ? $risk_register->business_unit_id : NULL,
                    'business_process_id' => ($risk_register->business_process_id != 0) ? $risk_register->business_process_id : NULL,
                    'impact_data' => $impact_fields,
                    'revised_impact_data' => $impact_fields,
                    'impact_on_areas' => $impact_on_areas,
                    'revised_impact_on_areas' => $impact_on_areas,
                ]
            );

            // if (!$riskAssessment) {
            //     $riskAssessment = new RiskAssessment();
            // }
            // $riskAssessment->client_id = $client_id;
            // $riskAssessment->risk_register_id = $risk_register->id;
            // $riskAssessment->asset_id = $risk_register->asset_id;
            // $riskAssessment->asset_type_id = $risk_register->asset_type_id;
            // $riskAssessment->business_unit_id = ($risk_register->business_unit_id != 0) ? $risk_register->business_unit_id : NULL;
            // $riskAssessment->business_process_id = ($risk_register->business_process_id != 0) ? $risk_register->business_process_id : NULL;
            // $riskAssessment->impact_data = $impact_fields;
            // $riskAssessment->revised_impact_data = $impact_fields;
            // $riskAssessment->save();
        }
        return 'success';
    }
    private function resetImpactOnArea(RiskAssessment $riskAssessment)
    {
        $client_id = $this->getClient()->id;
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        $matrix = $risk_matrix->current_matrix;

        $new_impact_on_areas = [];
        $new_revised_impact_on_areas = [];
        $impact_on_areas = $riskAssessment->impact_on_areas;
        $revised_impact_on_areas = $riskAssessment->revised_impact_on_areas;
        $impact_areas = RiskImpactArea::where(['client_id' => $client_id])->orderBy('area')->get();
        foreach ($impact_areas as $impact_area) {
            if (!in_array($impact_area->id, array_column($impact_on_areas, 'id'))) {

                $new_impact_on_areas[] = [
                    'id' => $impact_area->id,
                    'name' => $impact_area->area,
                    'slug' => $impact_area->area,
                    'impact_value' => '',
                    'meaning' => ''
                ];
                $new_revised_impact_on_areas[] = [
                    'id' => $impact_area->id,
                    'name' => $impact_area->area,
                    'slug' => $impact_area->area,
                    'impact_value' => '',
                    'meaning' => ''
                ];
            } else {
                foreach ($impact_on_areas as $impact_on_area) {
                    if ($impact_on_area['id'] == $impact_area->id) {
                        $new_impact_on_areas[] = [
                            'id' => $impact_area->id,
                            'name' => $impact_area->area,
                            'slug' => $impact_area->area,
                            'impact_value' => $impact_on_area['impact_value'],
                            'meaning' => $impact_on_area['meaning']
                        ];
                        // $new_impact_on_areas[] = $impact_on_area;
                    }
                }
                foreach ($revised_impact_on_areas as $impact_on_area) {
                    if ($impact_on_area['id'] == $impact_area->id) {
                        // $new_revised_impact_on_areas[] = $impact_on_area;
                        $new_revised_impact_on_areas[] = [
                            'id' => $impact_area->id,
                            'name' => $impact_area->area,
                            'slug' => $impact_area->area,
                            'impact_value' => $impact_on_area['impact_value'],
                            'meaning' => $impact_on_area['meaning']
                        ];
                    }
                }
            }
        }
        $riskAssessment->impact_on_areas = $new_impact_on_areas;
        $riskAssessment->revised_impact_on_areas = $new_revised_impact_on_areas;
        $riskAssessment->save();
        $this->updateLikelihoodRationale($riskAssessment, $matrix);
        $this->updateRevisedLikelihoodRationale($riskAssessment, $matrix);
        $this->updateRiskCategory($riskAssessment, $matrix);
        $this->updateImpactRationale($riskAssessment, $matrix);
        $this->updateReversedRiskCategory($riskAssessment, $matrix);
        $this->updateRevisedImpactRationale($riskAssessment, $matrix);
    }
    public function show(Request $request, RiskAssessment $riskAssessment)
    {
        $this->resetImpactOnArea($riskAssessment);
        $riskAssessment = $riskAssessment->with('riskRegister')
            ->join('risk_registers', 'risk_registers.id', 'risk_assessments.risk_register_id')
            ->leftJoin('business_units', 'business_units.id', 'risk_registers.business_unit_id')
            ->leftJoin('business_processes', 'business_processes.id', 'risk_registers.business_process_id')
            ->leftJoin('assets', 'assets.id', 'risk_registers.asset_id')
            ->leftJoin('asset_types', 'asset_types.id', 'risk_registers.asset_type_id')
            ->select('risk_assessments.*', 'risk_registers.*', 'risk_assessments.id as id', 'risk_registers.asset_name as asset_name', 'risk_registers.asset_type_name as asset_type_name', 'business_units.unit_name as business_unit', \DB::raw("CONCAT_WS(' ',business_processes.generated_process_id, business_processes.name) as business_process"))->find($riskAssessment->id);

        return response()->json(['risk_assessment' => $riskAssessment], 200);
    }
    public function deleteRiskImpactArea(Request $request, RiskImpactArea $riskImpactArea)
    {
        RiskImpactOnArea::where(['risk_impact_area_id' => $riskImpactArea->id])->delete();
        $riskImpactArea->delete();

        RiskAssessment::chunkById(100, function (Collection $riskAssessments) {
            foreach ($riskAssessments as $riskAssessment) {

                $this->resetImpactOnArea($riskAssessment);
            }
        }, $column = 'id');
        return response()->json(['message' => 'Successful'], 200);
    }
    private function maxValue($arrayNums)
    {
        $max = 0;
        foreach ($arrayNums as $num) {
            if ($num > $max) {
                $max = $num;
            }
        }
        return $max;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RiskAssessment  $riskAssessment
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRiskAssessmentFields(Request $request, RiskAssessment $riskAssessment)
    {
        $client_id = $riskAssessment->client_id;
        $sub_field = $request->sub_field;
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        $matrix = $risk_matrix->current_matrix;
        $field = $request->field;
        $value = $request->value;
        $new_data = [];
        if ($sub_field != '') {
            $risk_impact = RiskImpact::where([
                'client_id' => $client_id,
                'matrix' => $matrix,
                'value' => $value,
            ])->first();
            $impact_data = $riskAssessment->$field;
            foreach ($impact_data as $data) {
                if ($sub_field == $data['slug']) {
                    $data['impact_value'] = $value;
                    $data['meaning'] = $value . '-' . $risk_impact->name;
                }
                $new_data[] = $data;
            }
            $value = $new_data;
        }


        $riskAssessment->$field = $value;
        $riskAssessment->save();
        $this->updateLikelihoodRationale($riskAssessment, $matrix);
        $this->updateRevisedLikelihoodRationale($riskAssessment, $matrix);
        $this->updateRiskCategory($riskAssessment, $matrix);
        $this->updateImpactRationale($riskAssessment, $matrix);
        $this->updateReversedRiskCategory($riskAssessment, $matrix);
        $this->updateRevisedImpactRationale($riskAssessment, $matrix);
        return response()->json(['data' => $riskAssessment], 200);
    }
    private function updateLikelihoodRationale($riskAssessment, $matrix)
    {
        $likelihood = $riskAssessment->likelihood_of_occurence;
        $risk_likelihood = RiskLikelihood::where([
            'value' => (int) $likelihood,
            'matrix' => $matrix,
            'client_id' => $riskAssessment->client_id
        ])->first();
        if ($risk_likelihood) {
            $riskAssessment->likelihood_rationale = $risk_likelihood->summary;
            $riskAssessment->save();
        }


    }
    private function updateRevisedLikelihoodRationale($riskAssessment, $matrix)
    {
        $likelihood = $riskAssessment->revised_likelihood_of_occurence;
        if ($riskAssessment->treatment_option != 'Mitigate') {
            $likelihood = $riskAssessment->likelihood_of_occurence;
        }
        $risk_likelihood = RiskLikelihood::where([
            'value' => (int) $likelihood,
            'matrix' => $matrix,
            'client_id' => $riskAssessment->client_id
        ])->first();
        if ($risk_likelihood) {
            $riskAssessment->revised_likelihood_rationale = $risk_likelihood->summary;
            $riskAssessment->save();
        }


    }
    private function updateImpactRationale($riskAssessment, $matrix)
    {
        $impact_on_areas = $riskAssessment->impact_on_areas;
        $rationale = '';
        foreach ($impact_on_areas as $impact_on_area) {
            $impactOnArea = RiskImpactOnArea::with('impactArea')->where([
                'risk_impact_area_id' => (int) $impact_on_area['id'],
                'impact_value' => (int) $impact_on_area['impact_value'],
                'matrix' => $matrix,
                'client_id' => $riskAssessment->client_id
            ])->first();
            if ($impactOnArea) {

                $area = $impactOnArea->impactArea->area;
                $impact_level = ($impactOnArea->impact_level) ? $impactOnArea->impact_level : 'Not specified';
                $rationale .= "<li><strong>$area:</strong> $impact_level</li>";
            }
        }
        $riskAssessment->impact_rationale = '<ul>' . $rationale . '</ul>';
        $riskAssessment->save();
    }
    private function updateRevisedImpactRationale($riskAssessment, $matrix)
    {
        $impact_on_areas = $riskAssessment->revised_impact_on_areas;
        if ($riskAssessment->treatment_option != 'Mitigate') {
            $impact_on_areas = $riskAssessment->impact_on_areas;
        }
        $rationale = '';
        foreach ($impact_on_areas as $impact_on_area) {
            $impactOnArea = RiskImpactOnArea::with('impactArea')->where([
                'risk_impact_area_id' => (int) $impact_on_area['id'],
                'impact_value' => (int) $impact_on_area['impact_value'],
                'matrix' => $matrix,
                'client_id' => $riskAssessment->client_id
            ])->first();
            if ($impactOnArea) {

                $area = $impactOnArea->impactArea->area;
                $impact_level = ($impactOnArea->impact_level) ? $impactOnArea->impact_level : 'Not specified';
                $rationale .= "<li><strong>$area:</strong> $impact_level</li>";
            }
        }
        $riskAssessment->revised_impact_rationale = '<ul>' . $rationale . '</ul>';
        $riskAssessment->save();
    }
    private function updateRiskCategory($riskAssessment, $matrix)
    {

        $valuesArray = [];
        $impact_on_areas = ($riskAssessment->impact_on_areas) ? $riskAssessment->impact_on_areas : [];
        $impact_data = ($riskAssessment->impact_data) ? $riskAssessment->impact_data : [];
        $imapact_data_array = array_merge($impact_on_areas, $impact_data);
        foreach ($imapact_data_array as $data) {
            $valuesArray[] = ($data['impact_value'] != '') ? $data['impact_value'] : 0;
        }

        $impact_val = max($valuesArray);
        $risk_score = $riskAssessment->likelihood_of_occurence * $impact_val;
        list($risk_level, $color) = analyzeRiskCategory($risk_score, $matrix);

        $riskAssessment->impact_of_occurence = ($impact_val > 0) ? $impact_val : NULL;
        $riskAssessment->risk_score = ($risk_score > 0) ? $risk_score : NULL;
        $riskAssessment->risk_level = $risk_level;
        $riskAssessment->risk_level_color = $color;
        $riskAssessment->save();
    }
    private function updateReversedRiskCategory($riskAssessment, $matrix)
    {
        $valuesArray = [];
        $impact_on_areas = ($riskAssessment->revised_impact_on_areas) ? $riskAssessment->revised_impact_on_areas : [];
        $impact_data = ($riskAssessment->revised_impact_data) ? $riskAssessment->revised_impact_data : [];
        $imapact_data_array = array_merge($impact_on_areas, $impact_data);
        $likelihood = $riskAssessment->revised_likelihood_of_occurence;
        if ($riskAssessment->treatment_option != 'Mitigate') {
            $impact_on_areas = ($riskAssessment->impact_on_areas) ? $riskAssessment->impact_on_areas : [];
            $impact_data = ($riskAssessment->impact_data) ? $riskAssessment->impact_data : [];
            $imapact_data_array = array_merge($impact_on_areas, $impact_data);

            $likelihood = $riskAssessment->likelihood_of_occurence;
        }
        foreach ($imapact_data_array as $data) {
            $valuesArray[] = ($data['impact_value'] != '') ? $data['impact_value'] : 0;
        }

        $impact_val = max($valuesArray);
        $risk_score = $likelihood * $impact_val;
        list($risk_level, $color) = analyzeRiskCategory($risk_score, $matrix);

        $riskAssessment->revised_impact_of_occurence = $impact_val;
        $riskAssessment->revised_risk_score = $risk_score;
        $riskAssessment->revised_risk_level = $risk_level;
        $riskAssessment->revised_risk_level_color = $color;
        $riskAssessment->save();
    }

    public function updateRiskFields(Request $request, Risk $risk)
    {
        //
        $field = $request->field;
        $value = $request->value;
        $risk->$field = $value;
        $risk->save();
        return $risk;
    }
    public function saveRiskAssessmentTreatmentDetails(Request $request, RiskAssessment $riskAssessment)
    {
        $riskAssessment->update([
            'treatment_option' => $request->treatment_option,
            'treatment_option_details' => $request->treatment_option_details,
        ]);
        return $riskAssessment;
    }
    public function saveResidualRiskAssessmentTreatmentDetails(Request $request, RiskAssessment $riskAssessment)
    {
        $riskAssessment->update([
            'residual_risk_treatment_option' => $request->residual_risk_treatment_option,
            'residual_treatment_option_details' => $request->residual_treatment_option_details,
        ]);
        return $riskAssessment;
    }

}
