<?php

namespace App\Http\Controllers;

use App\Models\RecordOfProcessingActivity;
use Illuminate\Http\Request;

class RoPAController extends Controller
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
        $condition = [];
        if (isset($request->business_unit_id) && $request->business_unit_id != 'all') {
            $condition['business_unit_id'] = $request->business_unit_id;
        }

        $ropas = RecordOfProcessingActivity::join('business_units', 'business_units.id', 'record_of_processing_activities.business_unit_id')
            ->where('record_of_processing_activities.client_id', $client_id)
            ->where($condition)
            ->select('record_of_processing_activities.*', 'business_units.unit_name as business_unit')
            ->get();
        return response()->json(compact('ropas'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        RecordOfProcessingActivity::firstOrCreate([
            'client_id' => $request->client_id,
            'business_unit_id' => $request->business_unit_id,
            'controller_name' => $request->controller_name,
            'processing_purpose' => $request->processing_purpose,
            'controller_contact_details' => $request->controller_contact_details,
            'joint_controller_name' => $request->joint_controller_name,
            'joint_controller_contact_details' => $request->joint_controller_contact_details,
            'controller_rep_name' => $request->controller_rep_name,
            'controller_rep_contact_details' => $request->controller_rep_contact_details,
            'dpo_name' => $request->dpo_name,
            'dpo_details' => $request->dpo_details,
            'data_subject_categories' => $request->data_subject_categories,
            'personal_data_categories' => $request->personal_data_categories,
            'data_recipients_categories' => $request->data_recipients_categories,
            'security_measures_applied' => $request->security_measures_applied,
            'international_transfer_destination' => $request->international_transfer_destination,
            'erasure_time_limit' => $request->erasure_time_limit,
            'comments' => $request->comments,
        ]);
        return response()->json('success');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RecordOfProcessingActivity  $ropa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RecordOfProcessingActivity $ropa)
    {
        $field = $request->field;
        $value = $request->value;
        $ropa->$field = $value;
        $ropa->save();
        return response()->json(compact('ropa'), 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RecordOfProcessingActivity  $ropa
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecordOfProcessingActivity $ropa)
    {
        $ropa->delete();
        return response()->json([], 204);
    }
}
