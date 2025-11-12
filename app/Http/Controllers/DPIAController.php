<?php

namespace App\Http\Controllers;

use App\Models\BusinessProcess;
use App\Models\DPIAssessment;
use App\Models\PersonalDataAssessment;
use App\Models\RiskImpactOnArea;
use App\Models\RiskLikelihood;
use App\Models\RiskMatrix;
use Illuminate\Http\Request;

class DPIAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $pdas = PersonalDataAssessment::where('client_id', $client_id)->get();
        foreach ($pdas as $pda) {
            $business_process = BusinessProcess::find($pda->business_process_id);
            DPIAssessment::firstOrCreate([
                'client_id' => $client_id,
                'business_unit_id' => $pda->business_unit_id,
                'business_process_id' => $pda->business_process_id,
                'personal_data_asset' => $pda->personal_data_item,
                'risk_owner' => $business_process->process_owner
            ]);
        }
        $dpias = DPIAssessment::join('business_units', 'business_units.id', 'd_p_i_assessments.business_unit_id')
            ->join('business_processes', 'business_processes.id', 'd_p_i_assessments.business_process_id')
            ->where('d_p_i_assessments.client_id', $client_id)
            ->select('d_p_i_assessments.*', 'business_units.unit_name as business_unit', 'business_processes.name as business_process', 'business_processes.name as label')
            ->get()->groupBy('business_unit');
        $dpia_data = [];
        foreach ($dpias as $key => $value) {
            $dpia_data[] = ['label' => $key, 'children' => $value];
        }
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        $risk_appetite = null;
        if ($risk_matrix) {

            $risk_appetite = $risk_matrix->risk_appetite;
        }
        return response()->json(compact('dpia_data', 'risk_appetite'), 200);
    }

    public function fetchRiskAssessments(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $risk_assessments = DPIAssessment::join('business_units', 'business_units.id', 'd_p_i_assessments.business_unit_id')
            ->join('business_processes', 'business_processes.id', 'd_p_i_assessments.business_process_id')
            ->where('d_p_i_assessments.client_id', $client_id)
            ->select('d_p_i_assessments.*', 'business_units.unit_name as business_unit', 'business_processes.name as business_process')
            ->get();
        return response()->json(compact('risk_assessments'), 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DPIAssessment  $dpia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DPIAssessment $dpia)
    {
        $client_id = $dpia->client_id;
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        $matrix = $risk_matrix->current_matrix;
        $field = $request->field;
        $value = $request->value;
        $dpia->$field = $value;
        $dpia->save();
        $impactOnAreas = RiskImpactOnArea::with('impactArea')->where([
            'impact_value' => $dpia->impact,
            'matrix' => $matrix,
            'client_id' => $dpia->client_id
        ])
            ->whereRaw('impact_level IS NOT NULL')
            ->get();
        $likelihood = RiskLikelihood::where([
            'value' => $dpia->likelihood,
            'matrix' => $matrix,
            'client_id' => $dpia->client_id
        ])->first();
        $dpia = $this->updateRiskCategory($dpia, $matrix);
        $dpia = $this->updateImpactAndLikelihoodRationale($dpia, $impactOnAreas, $likelihood);
        $dpia = $this->updateReversedRiskCategory($dpia, $matrix);
        $dpia = $this->updateRevisedImpactAndLikelihoodRationale($dpia, $impactOnAreas, $likelihood);
        return response()->json(compact('dpia'), 200);

    }
    private function updateImpactAndLikelihoodRationale($assessment, $impactOnAreas, $likelihood)
    {

        $rationale = '';
        foreach ($impactOnAreas as $impactOnArea) {
            $area = $impactOnArea->impactArea->area;
            $impact_level = $impactOnArea->impact_level;
            $rationale .= "<li><strong>$area:</strong> $impact_level</li>";
        }
        $assessment->impact_rationale = '<ul>' . $rationale . '</ul>';
        $assessment->likelihood_rationale = $likelihood->summary;
        $assessment->save();
        return $assessment;
    }
    private function updateRevisedImpactAndLikelihoodRationale($assessment, $impactOnAreas, $likelihood)
    {
        $rationale = '';
        foreach ($impactOnAreas as $impactOnArea) {
            $area = $impactOnArea->impactArea->area;
            $impact_level = $impactOnArea->impact_level;
            $rationale .= "<li><strong>$area:</strong> $impact_level</li>";
        }
        $assessment->revised_impact_rationale = '<ul>' . $rationale . '</ul>';
        $assessment->revised_likelihood_rationale = $likelihood->summary;
        $assessment->save();
        return $assessment;
    }
    private function updateRiskCategory($assessment, $matrix)
    {

        $impact_val = $assessment->impact;
        $risk_value = $assessment->likelihood * $impact_val;
        $risk_category = $this->analyzeRiskCategory($risk_value, $matrix);

        $assessment->impact = ($impact_val > 0) ? $impact_val : NULL;
        $assessment->risk_score = ($risk_value > 0) ? $risk_value : NULL;
        $assessment->risk_level = $risk_category;
        $assessment->save();
        return $assessment;
    }
    private function updateReversedRiskCategory($assessment, $matrix)
    {
        $impact_val = $assessment->revised_impact;
        $risk_value = $assessment->revised_likelihood * $impact_val;
        $risk_category = $this->analyzeRiskCategory($risk_value, $matrix);

        $assessment->revised_impact = ($impact_val > 0) ? $impact_val : NULL;
        $assessment->revised_risk_score = ($risk_value > 0) ? $risk_value : NULL;
        $assessment->revised_risk_level = $risk_category;
        $assessment->save();
        return $assessment;
    }
    private function analyzeRiskCategory($riskValue, $matrix = '3x3')
    {
        $category = NULL;
        switch ($matrix) {
            case '5x5':
                if ($riskValue >= 12) {
                    $category = 'High';
                }
                if ($riskValue >= 5 && $riskValue <= 11) {
                    $category = 'Medium';
                }
                if ($riskValue >= 1 && $riskValue <= 4) {
                    $category = 'Low';
                }
                break;

            default:
                if ($riskValue >= 6) {
                    $category = 'High';
                }
                if ($riskValue >= 3 && $riskValue <= 5) {
                    $category = 'Medium';
                }
                if ($riskValue >= 1 && $riskValue <= 2) {
                    $category = 'Low';
                }
                break;
        }
        return $category;
    }
    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\DPIAssessment  $dPIAssessment
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(DPIAssessment $dpia)
    // {
    //     //
    // }
}
