<?php

namespace App\Http\Controllers;

use App\Models\BIARiskAssessment;
use App\Models\BusinessImpactAnalysis;
use App\Models\BusinessProcess;
use App\Models\BiaTimeRecoveryRequirement;
use App\Models\ProcessDisruptionImpact;
use App\Models\RiskImpact;
use App\Models\RiskImpactArea;
use App\Models\RiskMatrix;
use Illuminate\Http\Request;

class BIAController extends Controller
{

    public function fetchBIA(Request $request)
    {
        $client_id = $request->client_id;
        $this->store($client_id);
        $bias = BusinessImpactAnalysis::with('impacts')
            ->join('business_units', 'business_units.id', 'business_impact_analyses.business_unit_id')
            ->join('business_processes', 'business_processes.id', 'business_impact_analyses.business_process_id')
            ->where(['business_impact_analyses.client_id' => $client_id])
            ->select('business_impact_analyses.*', 'business_impact_analyses.id as id', 'business_units.unit_name as business_unit', 'business_processes.description', 'business_processes.roles_responsible', 'business_processes.no_of_people_involved', 'business_processes.minimum_no_of_people_involved', 'business_processes.product_or_service_delivered', 'business_processes.regulatory_obligations', 'business_processes.name as business_process')
            ->get();
        $business_impact_analyses = $bias->groupBy(['business_unit']);
        $disruption_impact = ProcessDisruptionImpact::where('client_id', $client_id)->first();
        $process_disruption_impact = $disruption_impact->process_disruption_impact;
        return response()->json(compact('business_impact_analyses', 'bias', 'process_disruption_impact'), 200);
    }
    private function store($client_id)
    {
        //check if there is impact criteria set for this Business Unit
        $time_recovery_requirements = BiaTimeRecoveryRequirement::where([
            'client_id' => $client_id
        ])->orderBy('time_in_minutes')->get();

        if ($time_recovery_requirements->count() < 1) {
            return response()->json(['message' => 'Time Recovery Requirement is not set. Kindly inform your Consultant'], 500);
        }

        $business_processes = BusinessProcess::where(['client_id' => $client_id])->get();

        $impact_areas = RiskImpactArea::where(['client_id' => $client_id])->orderBy('area')->get();

        $process_disruption_impact = [];
        foreach ($time_recovery_requirements as $time_recovery_requirement) {
            $process_disruption_impact[] = [
                'id' => $time_recovery_requirement->id,
                'name' => $time_recovery_requirement->name,
                'time_in_minutes' => $time_recovery_requirement->time_in_minutes,
                'value' => '',
                'meaning' => ''
            ];
        }
        foreach ($business_processes as $process) {
            $minimum_service_level = $this->calculateMinimumServiceLevel($process->minimum_no_of_people_involved, $process->no_of_people_involved);
            $business_impact_analysis = BusinessImpactAnalysis::firstOrCreate(
                [
                    'client_id' => $process->client_id,
                    'business_unit_id' => $process->business_unit_id,
                    'business_process_id' => $process->id,
                    'minimum_service_level' => $minimum_service_level,
                ],
            );

            $this->createProcessDisruptionImpact($business_impact_analysis, $impact_areas, $process_disruption_impact);

        }


        // $request->business_impact_analysis_id = $new_entry->id;
        // $this->createProcessDisruptionImpact($request);
    }
    private function calculateMinimumServiceLevel($numerator, $denominator)
    {
        $minimumServiceLevel = 0;
        if ($numerator > 0 && $denominator > 0) {
            $minimumServiceLevel = 100 * ($numerator / $denominator);
        }
        return (int) $minimumServiceLevel;
    }
    private function createProcessDisruptionImpact($business_impact_analysis, $impact_areas, $process_disruption_impact)
    {
        foreach ($impact_areas as $impact) {
            ProcessDisruptionImpact::firstOrCreate([
                'client_id' => $business_impact_analysis->client_id,
                'business_impact_analysis_id' => $business_impact_analysis->id,
                'criteria' => $impact->area,
            ], ['process_disruption_impact' => $process_disruption_impact]);
        }
    }
    public function updateBIA(Request $request, BusinessImpactAnalysis $bia)
    {
        $business_process = BusinessProcess::find($bia->business_process_id);
        $minimum_service_level = $this->calculateMinimumServiceLevel($business_process->minimum_no_of_people_involved, $business_process->no_of_people_involved);
        $field = $request->field;
        $value = $request->value;

        $bia->$field = $value;
        $bia->minimum_service_level = $minimum_service_level;
        $bia->save();
        return $bia;
    }
    public function updateDisruptionImpact(Request $request, ProcessDisruptionImpact $impact)
    {
        $id = $request->id;
        $value = $request->value;

        $client_id = $impact->client_id;
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        $matrix = $risk_matrix->current_matrix;
        $risk_impact = RiskImpact::where([
            'client_id' => $client_id,
            'matrix' => $matrix,
            'value' => $value,
        ])->first();
        $impact_max_value = RiskImpact::where([
            'client_id' => $client_id,
            'matrix' => $matrix
        ])->orderBy('value', 'DESC')->pluck('value')->first();
        $process_disruption_impacts = $impact->process_disruption_impact;
        $new_data = [];
        foreach ($process_disruption_impacts as $data) {
            if ($data['id'] == $id) {
                $data['value'] = $value;
                $data['meaning'] = $value . '-' . $risk_impact->name;
            }
            $new_data[] = $data;
        }
        $impact->process_disruption_impact = $new_data;
        $impact->save();
        $this->calculateBIAPriority($impact->business_impact_analysis_id, $impact_max_value);
        $business_impact_analysis = BusinessImpactAnalysis::find($impact->business_impact_analysis_id);
        return response()->json(compact('business_impact_analysis'), 200);
    }

    private function calculateBIAPriority($biaId, $impact_max_value)
    {
        $business_impact_analysis = BusinessImpactAnalysis::find($biaId);
        $impacts = ProcessDisruptionImpact::where('business_impact_analysis_id', $biaId)->get();
        $no_of_entries = count($impacts);
        $priroities = ['Critical' => 0, 'Monitor' => 0, 'Non-Critical' => 0];
        $priority_value = 'Non-Critical';
        $priority_color = '5FD142';
        foreach ($impacts as $impact) {
            $process_disruption_impacts = $impact->process_disruption_impact;
            // $each_impact_score = 0;
            $each_impact_score = [];
            foreach ($process_disruption_impacts as $process_disruption_impact) {
                $time_in_minutes = $process_disruption_impact['time_in_minutes'];
                $value = $process_disruption_impact['value'];
                // $each_impact_score += (int) $value;

                $each_impact_score[] = (int) $value;
                if ($time_in_minutes <= 540 && $value == $impact_max_value) {
                    $priroities['Critical'] += 1;
                } else if (($time_in_minutes > 540 && $time_in_minutes <= 2700) && $value == $impact_max_value) {
                    $priroities['Monitor'] += 1;
                } else {
                    $priroities['Non-Critical'] += 1;
                }
            }
            // $impact_score = (int) $each_impact_score / $no_of_entries;
            $impact_score = max($each_impact_score);
            $impact->impact_score = $impact_score;
            $impact->save();
        }
        if ($priroities['Critical'] > 0) {
            $priority_value = 'Critical';
            $priority_color = 'D14C42';
        } else if ($priroities['Monitor'] > 0) {
            $priority_value = 'Monitor';
            $priority_color = 'FFFF00';
        }
        $business_impact_analysis->priority = $priority_value;
        $business_impact_analysis->priority_color = $priority_color;
        $business_impact_analysis->save();
    }
    public function fetchRiskAssessments(Request $request)
    {
        $client_id = $request->client_id;
        $business_unit_id = $request->business_unit_id;
        $risk_assessments = BIARiskAssessment::with('businessProcess')->where(['client_id' => $client_id, 'business_unit_id' => $business_unit_id])->orderBy('id', 'DESC')->get(); //->paginate(10);
        return response()->json(compact('risk_assessments'), 200);
    }
    public function storeRiskAssessment(Request $request)
    {
        //
        $client_id = $request->client_id;
        $business_unit_id = $request->business_unit_id;
        // return $request;
        $assessments = json_decode(json_encode($request->assessments));
        $count = BIARiskAssessment::where(['client_id' => $client_id, 'business_unit_id' => $business_unit_id])->orderBy('ra_id', 'DESC')->select('ra_id')->first();
        if ($count) {

            $ra_id = $count->ra_id + 1;
        } else {
            $ra_id = 1;
        }
        foreach ($assessments as $assessment) {
            $business_process_id = $assessment->business_process_id;
            $risk_description = $assessment->risk_description;
            $risk_owner = $assessment->risk_owner;

            $new_entry = new BIARiskAssessment();

            $check_for_same_entry = BIARiskAssessment::where([
                'client_id' => $client_id,
                'business_unit_id' => $business_unit_id,
                'business_process_id' => $business_process_id,
            ])->first();
            if ($check_for_same_entry) {
                $new_entry->ra_id = $check_for_same_entry->ra_id;
            } else {
                $new_entry->ra_id = $ra_id;
                $ra_id++;
            }
            $new_entry->client_id = $client_id;
            $new_entry->business_unit_id = $business_unit_id;
            $new_entry->business_process_id = $business_process_id;
            $new_entry->risk_description = $risk_description;
            $new_entry->risk_owner = $risk_owner;
            $new_entry->risk_description = $assessment->risk_description;
            $new_entry->existing_treatment = $assessment->existing_treatment;
            $new_entry->likelihood_rationale = $assessment->likelihood_rationale;
            $new_entry->likelihood = $assessment->likelihood;
            $new_entry->impact = $assessment->impact;
            $new_entry->impact_rationale = $assessment->impact_rationale;
            // $new_entry->risk_score = $assessment->risk_score;
            // $new_entry->risk_level = $assessment->risk_level;
            $risk_score = $new_entry->likelihood * $new_entry->impact;
            if ($risk_score > 0) {
                $risk_level = $this->analyzeRiskLevel($risk_score);

                $new_entry->risk_score = $risk_score;
                $new_entry->risk_level = $risk_level;
            }
            $new_entry->save();
        }
    }
    public function updateRiskAssessmentFields(Request $request, BIARiskAssessment $assessment)
    {
        //
        $matrix = $request->matrix;
        $field = $request->field;
        $value = $request->value;
        $assessment->$field = $value;
        $assessment->save();

        $this->updateRiskCategory($assessment, $matrix);
        $this->updateReversedRiskCategory($assessment, $matrix);
        return $assessment;
    }
    private function updateRiskCategory($riskAssessment, $matrix)
    {
        $risk_score = $riskAssessment->likelihood * $riskAssessment->impact;
        if ($risk_score > 0) {

            $risk_level = $this->analyzeRiskLevel($risk_score);

            $riskAssessment->risk_score = $risk_score;
            $riskAssessment->risk_level = $risk_level;
            $riskAssessment->save();
        }
    }
    private function updateReversedRiskCategory($riskAssessment, $matrix)
    {
        $risk_score = $riskAssessment->post_treatment_likelihood * $riskAssessment->post_treatment_impact;
        if ($risk_score > 0) {
            $risk_level = $this->analyzeRiskLevel($risk_score);

            $riskAssessment->post_treatment_risk_score = $risk_score;
            $riskAssessment->post_treatment_risk_level = $risk_level;
            $riskAssessment->save();
        }
    }
    private function analyzeRiskLevel($riskValue, $matrix = '3x3')
    {
        $category = 'Low';
        if ($riskValue >= 6) {
            $category = 'High';
        }
        if ($riskValue >= 3 && $riskValue <= 5) {
            $category = 'Medium';
        }
        // switch ($matrix) {
        //     case '5x5':
        //         if ($riskValue >= 12) {
        //             $category = 'High';
        //         }
        //         if ($riskValue >= 5) {
        //             $category = 'Medium';
        //         }
        //         break;

        //     default:
        //         if ($riskValue >= 6) {
        //             $category = 'High';
        //         }
        //         if ($riskValue >= 3) {
        //             $category = 'Medium';
        //         }
        //         break;
        // }
        return $category;
    }
    public function riskAssessmentSummary(Request $request)
    {
        $client_id = $request->client_id;
        $business_unit_id = $request->business_unit_id;
        $summary = BIARiskAssessment::join('business_processes', 'business_processes.id', '=', 'b_i_a_risk_assessments.business_process_id')
            ->groupBy('business_process_id')
            ->where(['b_i_a_risk_assessments.client_id' => $client_id, 'b_i_a_risk_assessments.business_unit_id' => $business_unit_id])
            ->select('business_processes.name as business_process_name', 'risk_owner', \DB::raw('COUNT(*) as no_of_threats'), \DB::raw('COUNT(CASE WHEN risk_level = "Low" THEN b_i_a_risk_assessments.id END ) as lows'), \DB::raw('COUNT(CASE WHEN risk_level = "Medium" THEN b_i_a_risk_assessments.id END ) as mediums'), \DB::raw('COUNT(CASE WHEN risk_level = "High" THEN b_i_a_risk_assessments.id END ) as highs'))
            ->get();
        return response()->json(compact('summary'), 200);
    }
}
