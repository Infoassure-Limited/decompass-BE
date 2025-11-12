<?php

namespace App\Http\Controllers;

use App\Models\KeyRiskIndicatorAssessment;
use App\Models\RCSARiskAssessment;
use App\Models\Risk;
use App\Models\RiskAssessment;
use App\Models\RiskControlSelfAssessment;
use App\Models\RiskImpactArea;
use App\Models\RiskImpactOnArea;
use App\Models\RiskMatrix;
use App\Models\RiskRegister;
use Illuminate\Http\Request;

class RCSAController extends Controller
{


    public function fetchRCSA(Request $request)
    {
        $rcsa_data = RiskControlSelfAssessment::join('risk_registers', 'risk_registers.id', 'risk_control_self_assessments.rcm_id')->where([
            'risk_control_self_assessments.client_id' => $request->client_id,
            'risk_control_self_assessments.business_unit_id' => $request->business_unit_id
        ])->select('*', 'risk_control_self_assessments.id as id')->orderBy('risk_control_self_assessments.created_at', 'DESC')->get()->groupBy('category');


        $category_details = RiskControlSelfAssessment::groupBy('category')->where([
            'client_id' => $request->client_id,
            'business_unit_id' => $request->business_unit_id
        ])->select('category', 'overall_process_control_rating', \DB::raw('ROUND(AVG((self_assessment_score) * 10 ), 1) as percent_self_assessment_score'), \DB::raw('ROUND(AVG((validation) * 10 ), 1) as percent_validation_score'))->get();

        $total_scores = RiskControlSelfAssessment::where([
            'client_id' => $request->client_id,
            'business_unit_id' => $request->business_unit_id
        ])->select(\DB::raw('SUM(self_assessment_score) as total_self_assessment_score'), \DB::raw('SUM(validation) as total_validation_score'), \DB::raw('(COUNT(*) * 10) as potential_max_score'))->first();
        $total_scores->self_assessment_percentage_rating = 0;
        $total_scores->validation_percentage_rating = 0;
        if ($total_scores->potential_max_score > 0) {

            $total_scores->self_assessment_percentage_rating = sprintf('%0.2f', ($total_scores->total_self_assessment_score / $total_scores->potential_max_score) * 100);

            $total_scores->validation_percentage_rating = sprintf('%0.2f', ($total_scores->total_validation_score / $total_scores->potential_max_score) * 100);
        }



        return response()->json(compact('rcsa_data', 'category_details', 'total_scores'), 200);
    }
    public function createRCSAFromRCM(Request $request)
    {
        $risks = RiskRegister::with('businessUnit', 'businessProcess')->where([
            'client_id' => $request->client_id,
            'business_unit_id' => $request->business_unit_id,
            'module' => 'rcsa',
        ])->get();

        foreach ($risks as $risk) {
            RiskControlSelfAssessment::firstOrCreate(
                ['rcm_id' => $risk->id],
                [
                    'client_id' => $risk->client_id,
                    'business_unit_id' => $risk->business_unit_id,
                    'business_process_id' => $risk->business_process_id,

                    'category' => strtoupper($risk->businessProcess->name),
                    'key_process' => ucwords($risk->businessProcess->name),
                    'control_owner' => $risk->control_owner,
                    'control_activities' => $risk->control_description,
                    'control_type' => $risk->control_type,
                    'risk_description' => $risk->vulnerability_description,
                ]
            );
        }

        // $rcsas = RiskControlSelfAssessment::where([
        //     'client_id' => $request->client_id,
        //     'business_unit_id' => $request->business_unit_id
        // ])->get()->groupBy('category');
        return $this->fetchRCSA($request);
    }
    /**
     * Save tnew record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RCSARiskAssessment  $riskAssessment
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        RiskControlSelfAssessment::firstOrCreate([
            'client_id' => $request->client_id,
            'business_unit_id' => $request->business_unit_id,
            'business_process_id' => $request->business_process_id,
            'rcm_id' => $request->rcm_id,
            'category' => $request->category,
            'key_process' => $request->key_process,
            'control_owner' => $request->control_owner,
            'source' => $request->source,
            'control_type' => $request->control_type,
            'risk_description' => $request->risk_description,
            'self_assessment_control' => $request->self_assessment_control,
            'self_assessment_score' => $request->self_assessment_score,
            'comment_on_status' => $request->comment_on_status,
            'rm_rating_of_control' => $request->rm_rating_of_control,
            'validation' => $request->validation,
            'basis_of_rm_rating' => $request->basis_of_rm_rating,
            'self_assessment_of_process_level_risk' => $request->self_assessment_of_process_level_risk,
            'rm_validated_process_level_risk' => $request->rm_validated_process_level_risk,
        ]);
        return response()->json('success');
    }
    public function createNewCategory(Request $request)
    {
        $category = $request->category;
        $control_data = json_decode(json_encode($request->control_data));
        foreach ($control_data as $control) {
            RiskControlSelfAssessment::firstOrCreate(
                [
                    'client_id' => $request->client_id,
                    'business_unit_id' => $request->business_unit_id,
                    'category' => strtoupper($category),
                    'key_process' => ucwords($control->key_process),
                    'control_activities' => $control->control_activities
                ],
                [

                    'control_owner' => $control->control_owner,
                    'control_type' => $control->control_type,
                    'risk_description' => $control->risk_description,
                ]
            );
        }
        return response()->json('success');
    }
    public function updateOverallControlRating(Request $request)
    {
        RiskControlSelfAssessment::where([
            'client_id' => $request->client_id,
            'business_unit_id' => $request->business_unit_id,
            'category' => $request->category
        ])->update(['overall_process_control_rating' => $request->value]);
        return $this->fetchRCSA($request);
    }
    public function updateFields(Request $request, RiskControlSelfAssessment $rcsa)
    {
        //
        $field = $request->field;
        $value = $request->value;
        $rcsa->$field = $value;
        if ($field == 'self_assessment_control') {
            $rcsa->self_assessment_score = $this->calculateScore($value);
            $rcsa->self_assessment_of_process_level_risk = $this->calculateLevelRisk($rcsa->self_assessment_score);
        }
        if ($field == 'rm_rating_of_control') {
            $rcsa->validation = $this->calculateScore($value);
            $rcsa->rm_validated_process_level_risk = $this->calculateLevelRisk($rcsa->validation);
        }
        $rcsa->save();
        return response()->json(compact('rcsa')); // $this->fetchRCSA($request);
        // $rcsas = RiskControlSelfAssessment::where([
        //     'client_id' => $rcsa->client_id,
        //     'business_unit_id' => $rcsa->business_unit_id
        // ])->get()->groupBy('category');
        // return response()->json(compact('rcsas'), 200);
    }
    private function calculateScore($value)
    {
        $score = 0;
        switch ($value) {
            case 'Level 1':
                $score = 0;
                break;
            case 'Level 2':
                $score = 4;
                break;
            case 'Level 3':
                $score = 7;
                break;
            case 'Level 4':
                $score = 10;
                break;

            default:
                $score = 0;
                break;
        }
        return $score;
    }
    private function calculateLevelRisk($value)
    {
        $risk = 'High';
        if ($value <= 3 && $value >= 0) {
            $risk = 'High';
        } else if ($value <= 6 && $value >= 4) {
            $risk = 'Medium';
        } else {
            $risk = 'Low';
        }
        return $risk;
    }
    public function fetchRCSARiskAssessments(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $standard_id = 0;
        if (isset($request->standard_id)) {
            $standard_id = $request->standard_id;
        }
        $module = $request->module;
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        $risk_appetite = null;
        if ($risk_matrix) {

            $risk_appetite = $risk_matrix->risk_appetite;
        }
        $this->storeRiskAssessment($request);
        $risk_assessments = RCSARiskAssessment::leftJoin('risk_registers', 'risk_registers.id', 'rcsa_risk_assessments.risk_register_id')
            ->leftJoin('business_units', 'business_units.id', 'rcsa_risk_assessments.business_unit_id')
            ->leftJoin('business_processes', 'business_processes.id', 'rcsa_risk_assessments.business_process_id')
            ->leftJoin('asset_types', 'asset_types.id', 'rcsa_risk_assessments.asset_type_id')
            ->where(['rcsa_risk_assessments.client_id' => $client_id, 'rcsa_risk_assessments.module' => $module])
            ->select('rcsa_risk_assessments.*', 'risk_registers.*', 'rcsa_risk_assessments.id as id', \DB::raw('CONCAT(prepend_risk_no_value,risk_id) as risk_id'), 'business_processes.name as business_process', 'business_units.unit_name as business_unit', 'asset_types.name as asset_type')
            ->orderBy('risk_id', 'ASC')
            ->get();
        $grouped_risk_assessments = $risk_assessments->groupBy('business_unit');

        return response()->json(compact('grouped_risk_assessments', 'risk_assessments', 'risk_appetite'), 200);
    }
    public function storeRiskAssessment(Request $request)
    {
        $module = $request->module;
        //
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $standard_id = $request->standard_id;
        $business_unit_id = $request->business_unit_id;
        // return $request;
        // $assessments = json_decode(json_encode($request->assessments));
        $assessments = RiskControlSelfAssessment::where([
            'client_id' => $request->client_id,
            // 'business_unit_id' => $request->business_unit_id
        ])->where('validation', '!=', NULL)->get();

        // $impact_fields = [
        //     ['name' => 'Confidentiality', 'slug' => 'C', 'impact_value' => ''],
        //     ['name' => 'Integrity', 'slug' => 'I', 'impact_value' => ''],
        //     ['name' => 'Availability', 'slug' => 'A', 'impact_value' => ''],
        //     ['name' => 'Privacy', 'slug' => 'P', 'impact_value' => ''],
        // ];
        // if ($module == 'rcsa') {
        $impact_fields = [];

        $impact_areas = RiskImpactArea::where(['client_id' => $client_id])->orderBy('area')->get();
        foreach ($impact_areas as $impact_area) {
            $impact_fields[] = [
                'name' => $impact_area->area,
                'slug' => $impact_area->area,
                'impact_value' => ''
            ];
        }
        //}
        foreach ($assessments as $assessment) {

            RCSARiskAssessment::firstOrCreate(
                [
                    'module' => $module,
                    'client_id' => $client_id,
                    'standard_id' => $standard_id,
                    'risk_register_id' => $assessment->rcm_id,
                    'business_unit_id' => $assessment->business_unit_id,
                    'business_process_id' => $assessment->business_process_id,
                ],
                ['impact_data' => $impact_fields, 'revised_impact_data' => $impact_fields]
            );
        }
        // return response()->json(['message' => 'Success'], 200);
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
     * @param  \App\Models\RCSARiskAssessment  $riskAssessment
     * @return \App\Models\RCSARiskAssessment  $riskAssessment
     */
    public function updateRCSARiskAssessmentFields(Request $request, RCSARiskAssessment $riskAssessment)
    {
        $client_id = $riskAssessment->client_id;
        $sub_field = $request->sub_field;
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        $matrix = $risk_matrix->current_matrix;
        $field = $request->field;
        $value = $request->value;
        $new_data = [];
        if ($sub_field != '') {
            $impact_data = $riskAssessment->$field;
            foreach ($impact_data as $data) {
                if ($sub_field == $data['slug']) {
                    $data['impact_value'] = $value;
                }
                $new_data[] = $data;
            }
            $value = $new_data;
        }


        $riskAssessment->$field = $value;
        $riskAssessment->save();
        $this->updateRiskCategory($riskAssessment, $matrix);
        $this->updateImpactRationale($riskAssessment, $matrix);
        $this->updateReversedRiskCategory($riskAssessment, $matrix);
        $this->updateRevisedImpactRationale($riskAssessment, $matrix);
        return $riskAssessment;
    }
    private function updateImpactRationale($riskAssessment, $matrix)
    {

        $impactOnAreas = RiskImpactOnArea::with('impactArea')->where([
            'impact_value' => $riskAssessment->impact_of_occurence,
            'matrix' => $matrix,
            'client_id' => $riskAssessment->client_id
        ])
            ->whereRaw('impact_level IS NOT NULL')
            ->get();
        $rationale = '';
        foreach ($impactOnAreas as $impactOnArea) {
            $area = $impactOnArea->impactArea->area;
            $impact_level = $impactOnArea->impact_level;
            $rationale .= "<li><strong>$area:</strong> $impact_level</li>";
        }
        $riskAssessment->impact_rationale = '<ul>' . $rationale . '</ul>';
        $riskAssessment->save();
    }
    private function updateRevisedImpactRationale($riskAssessment, $matrix)
    {

        $impactOnAreas = RiskImpactOnArea::with('impactArea')->where([
            'impact_value' => $riskAssessment->revised_impact_of_occurence,
            'matrix' => $matrix,
            'client_id' => $riskAssessment->client_id
        ])
            ->whereRaw('impact_level IS NOT NULL')
            ->get();
        $rationale = '';
        foreach ($impactOnAreas as $impactOnArea) {
            $area = $impactOnArea->impactArea->area;
            $impact_level = $impactOnArea->impact_level;
            $rationale .= "<li><strong>$area:</strong> $impact_level</li>";
        }
        $riskAssessment->revised_impact_rationale = '<ul>' . $rationale . '</ul>';
        $riskAssessment->save();
    }
    private function updateRiskCategory($riskAssessment, $matrix)
    {

        $valuesArray = [];
        $impact_data = $riskAssessment->impact_data;
        foreach ($impact_data as $data) {
            $valuesArray[] = ($data['impact_value'] != '') ? $data['impact_value'] : 0;
        }

        $impact_val = $this->maxValue($valuesArray);
        $risk_value = $riskAssessment->likelihood_of_occurence * $impact_val;
        // $risk_category = $this->analyzeRiskCategory($risk_value, $matrix);
        list($risk_category, $color) = analyzeRiskCategory($risk_value, $matrix);

        $riskAssessment->impact_of_occurence = ($impact_val > 0) ? $impact_val : NULL;
        $riskAssessment->overall_risk_rating = ($risk_value > 0) ? $risk_value : NULL;
        $riskAssessment->risk_category = $risk_category;
        $riskAssessment->level_color = $color;
        $riskAssessment->save();
    }
    private function updateReversedRiskCategory($riskAssessment, $matrix)
    {
        $valuesArray = [];
        $impact_data = $riskAssessment->revised_impact_data;
        foreach ($impact_data as $data) {
            $valuesArray[] = ($data['impact_value'] != '') ? $data['impact_value'] : 0;
        }

        $impact_val = $this->maxValue($valuesArray);
        $risk_value = $riskAssessment->revised_likelihood_of_occurence * $impact_val;
        list($risk_category, $color) = analyzeRiskCategory($risk_value, $matrix);

        $riskAssessment->revised_impact_of_occurence = $impact_val;
        $riskAssessment->revised_overall_risk_rating = $risk_value;
        $riskAssessment->revised_risk_category = $risk_category;
        $riskAssessment->revised_level_color = $color;
        $riskAssessment->save();
    }


    public function fetchRiskIndicatorAssessments(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $standard_id = 0;
        $module = $request->module;
        $risk_assessments = RiskAssessment::where(['client_id' => $client_id, 'module' => $module])->where('key_risk_indicator', '!=', NULL)
            ->select('id', 'business_unit_id')
            ->get();
        $business_unit_ids = [];
        foreach ($risk_assessments as $risk_assessment) {
            $business_unit_ids[] = $risk_assessment->business_unit_id;
            $assessment = KeyRiskIndicatorAssessment::firstOrCreate(
                [
                    'client_id' => $client_id,
                    'business_unit_id' => $risk_assessment->business_unit_id,
                    'risk_assessment_id' => $risk_assessment->id,
                ]
            );
            $this->setKRIAssessmentValues($assessment);
        }
        $assessments = KeyRiskIndicatorAssessment::join('risk_assessments', 'risk_assessments.id', 'key_risk_indicator_assessments.risk_assessment_id')
            ->where('key_risk_indicator_assessments.client_id', $client_id)
            ->whereIn('key_risk_indicator_assessments.business_unit_id', $business_unit_ids)
            ->select('key_risk_indicator_assessments.*', 'risk_assessments.key_risk_indicator as kri')->get();
        return response()->json(compact('assessments'), 200);
    }


    private function setKRIAssessmentValues(KeyRiskIndicatorAssessment $assessment)
    {
        if ($assessment->assessments == NULL) {
            //  we want to make it 48 weeks a year, giving 4 weeks per month
            $value = [];
            for ($i = 1; $i <= 48; $i++) {
                # code...
                $value[$i] = [NULL, '#f0f0f0']; // this is the assessment value and color code
            }


            $assessment->assessments = $value;
            $assessment->save();
        }
    }
    public function saveKRIThreshold(Request $request)
    {
        $assessment = KeyRiskIndicatorAssessment::find($request->id);
        $assessment->risk_trigger_threshold = $request->threshold;
        $assessment->save();

        return response()->json(['message' => 'Success'], 200);
    }
    public function updateRiskIndicatorAssessment(Request $request, KeyRiskIndicatorAssessment $assessment)
    {
        $field = $request->field;
        $value = $request->value;
        $assessment->$field = $value;
        $assessment->save();

        return response()->json(['message' => 'Success'], 200);
    }
    public function updateKRIAssessmentValues(Request $request, KeyRiskIndicatorAssessment $kriAssessment)
    {
        $new_data = [];
        $risk_trigger_threshold = $kriAssessment->risk_trigger_threshold;
        $key = $request->key;
        $value = $request->value;
        if ($value != NULL) {
            $assessments_data = $kriAssessment->assessments;
            foreach ($assessments_data as $k => $val) {
                if ($k == $key) {
                    $val[0] = $value;
                    $val[1] = $this->getColourIndicatorFormValue($value, $risk_trigger_threshold);
                }
                $new_data[$k] = $val;
            }

            $kriAssessment->assessments = $new_data;
            $kriAssessment->save();
        }

        return response()->json(['message' => 'Success'], 200);
    }
    private function getColourIndicatorFormValue($value, $risk_trigger_threshold)
    {
        foreach ($risk_trigger_threshold as $threshold) {
            $color = $threshold['color'];
            $val = $threshold['value'];
            $val2 = $threshold['value2'];
            $operator = $threshold['operator'];
            if ($operator == '-') {
                if ($value >= $val && $value <= $val2) {
                    return $color;
                }
            } else {
                switch ($operator) {
                    case '==':
                        if ($value == $val) {
                            return $color;
                        }
                        break;

                    case '<':
                        if ($value < $val) {
                            return $color;
                        }
                        break;
                    case '<=':
                        if ($value <= $val) {
                            return $color;
                        }
                        break;
                    case '>':
                        if ($value > $val) {
                            return $color;
                        }
                        break;
                    case '>=':
                        if ($value >= $val) {
                            return $color;
                        }
                        break;
                }
            }
        }
    }
    public function calculateEnterpriseRiskScore(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $condition = [];
        if (isset($request->business_unit_id) && $request->business_unit_id != '') {
            $condition['risk_assessments.business_unit_id'] = $request->business_unit_id;
        }
        if (isset($request->type) && $request->type != '') {
            $condition['risk_registers.type'] = $request->type;
        }
        if (isset($request->sub_type) && $request->sub_type != '') {
            $condition['risk_registers.sub_type'] = $request->sub_type;
        }
        if (isset($request->risk_id) && $request->risk_id != '') {
            $condition['risk_registers.risk_id'] = $request->risk_id;
        }
        if (isset($request->business_unit_id) && $request->business_unit_id != '') {
            $condition['risk_assessments.business_unit_id'] = $request->business_unit_id;
        }
        $risk_assessments = RiskAssessment::join('risk_registers', 'risk_registers.id', 'risk_assessments.risk_register_id')
            ->join('business_units', 'business_units.id', 'risk_assessments.business_unit_id')
            ->join('business_processes', 'business_processes.id', 'risk_assessments.business_process_id')
            ->where(['risk_assessments.client_id' => $client_id])
            ->where('risk_assessments.module', $request->module)
            ->where($condition)
            ->select('risk_assessments.*', 'risk_registers.*', 'risk_assessments.id as id', 'business_processes.name as business_process', 'business_units.unit_name as business_unit')
            ->orderBy('risk_id', 'ASC')
            ->get();

        $impact_rating_count = 0;
        $likelihood_rating_count = 0;
        $risk_score_count = 0;
        $count = count($risk_assessments);
        $overall_impact_rating = 0;
        $overall_likelihood_rating = 0;
        $average_risk_score = 0;
        if ($count > 0) {
            foreach ($risk_assessments as $risk_assessment) {
                $impact_rating_count += $risk_assessment->revised_impact_of_occurence;
                $likelihood_rating_count += $risk_assessment->revised_likelihood_of_occurence;
                $risk_score_count += $risk_assessment->revised_risk_score;
            }
            $overall_impact_rating = sprintf("%.1f", $impact_rating_count / $count);
            $overall_likelihood_rating = sprintf("%.1f", $likelihood_rating_count / $count);
            $average_risk_score = sprintf("%.1f", $risk_score_count / $count);
        }
        $severity_distribution = RiskAssessment::where(['client_id' => $client_id])
            ->where($condition)
            ->select(\DB::raw('COUNT(CASE WHEN revised_risk_level = "Very High" THEN risk_assessments.id END ) as very_high'), \DB::raw('COUNT(CASE WHEN revised_risk_level = "High" THEN risk_assessments.id END ) as high'), \DB::raw('COUNT(CASE WHEN revised_risk_level = "Medium" THEN risk_assessments.id END ) as medium'), \DB::raw('COUNT(CASE WHEN revised_risk_level = "Low" THEN risk_assessments.id END ) as low'))
            ->first();
        $effectiveness_level = RiskAssessment::where(['client_id' => $client_id])
            ->where($condition)
            ->select(\DB::raw('COUNT(CASE WHEN control_effectiveness_level = "Effective" THEN risk_assessments.id END ) as effective'), \DB::raw('COUNT(CASE WHEN control_effectiveness_level = "Ineffective" THEN risk_assessments.id END ) as ineffective'), \DB::raw('COUNT(CASE WHEN control_effectiveness_level = "Sub-optimal" THEN risk_assessments.id END ) as sub_optimal'))
            ->first();
        $column_series = [
            [
                'name' => 'Risk Severity',
                'colors' => ['#DD2C2C', '#FFFF00', /*'#FFFF00',*/ '#3BD135'],
                'data' => [
                    // ['Very High', $severity_distribution->very_high],
                    ['High', $severity_distribution->high],
                    ['Medium', $severity_distribution->medium],
                    ['Low', $severity_distribution->low],
                ], //array format
                'colorByPoint' => true,
                'groupPadding' => 0,
            ],
        ];
        $pie_series = [
            [
                'name' => 'Control Effectiveness',
                'data' => [
                    ['name' => 'Effective', 'y' => $effectiveness_level->effective],
                    ['name' => 'Ineffective', 'y' => $effectiveness_level->ineffective],
                    ['name' => 'Sub-optimal', 'y' => $effectiveness_level->sub_optimal],
                ],
            ],
        ];
        return response()->json(compact('risk_assessments', 'overall_impact_rating', 'overall_likelihood_rating', 'average_risk_score', 'column_series', 'pie_series'), 200);
    }
}
