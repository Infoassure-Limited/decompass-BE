<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use App\Models\KeyRiskIndicatorAssessment;
use App\Models\Risk;
use App\Models\VendorDueDiligence\RiskAssessment;
use App\Models\RiskCategory;
use App\Models\RiskImpact;
use App\Models\RiskImpactArea;
use App\Models\RiskImpactOnArea;
use App\Models\RiskLikelihood;
use App\Models\RiskMatrix;
use Illuminate\Http\Request;

class RiskAssessmentsController extends Controller
{


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
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
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
        $sub_categories = [];
        foreach ($request->sub_categories as $sub_category) {
            $sub_categories[] = ['name' => $sub_category];
        }
        $riskCategory->name = $request->name;
        $riskCategory->sub_categories = $sub_categories;
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
        $module = $request->module;
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
            ->leftJoin('business_units', 'business_units.id', 'risk_assessments.business_unit_id')
            ->leftJoin('business_processes', 'business_processes.id', 'risk_assessments.business_process_id')
            ->where(['risk_assessments.client_id' => $client_id/*, 'risk_assessments.module' => $module*/])
            ->select('risk_assessments.*', 'risk_registers.*', 'risk_assessments.id as id', 'business_units.unit_name as business_unit', \DB::raw("CONCAT_WS(' ',business_processes.generated_process_id, business_processes.name) as business_process"))
            ->orderBy('risk_id', 'ASC')
            ->get();

        $asset_types = $risk_assessment_query->where('asset_id', '!=', NULL)->groupBy('asset_name');


        $business_units = $risk_assessment_query->where('business_process', '!=', NULL)->groupBy('business_process');
        // for tabular view
        $risk_assessments = RiskAssessment::join('risk_registers', 'risk_registers.id', 'risk_assessments.risk_register_id')
            ->leftJoin('business_units', 'business_units.id', 'risk_assessments.business_unit_id')
            ->leftJoin('business_processes', 'business_processes.id', 'risk_assessments.business_process_id')
            ->where(['risk_assessments.client_id' => $client_id])
            ->whereIn('risk_assessments.module', $modules)
            ->select('risk_assessments.*', 'risk_registers.*', 'risk_assessments.id as id', 'business_processes.name as business_process', 'business_units.unit_name as business_unit')
            ->orderBy('risk_id', 'ASC')
            ->get();

        return response()->json(compact('risk_assessments', 'asset_types', 'business_units', 'risk_appetite'), 200);
    }

    public function show(Request $request, RiskAssessment $riskAssessment)
    {
        return response()->json(['risk_assessment' => $riskAssessment], 200);
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
        return response()->json(['risk_assessment' => $riskAssessment], 200);
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

}
