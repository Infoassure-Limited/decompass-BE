<?php

namespace App\Http\Controllers\NDPA;

use App\Http\Controllers\Controller;
use App\Models\NDPA\PersonalDataAssessment;
use App\Models\NDPA\PersonalDataItem;
use Illuminate\Http\Request;

class PDAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
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
            $condition['personal_data_assessments.business_unit_id'] = $request->business_unit_id;
        }
        // if (isset($request->business_process_id) && $request->business_process_id != 'all') {
        //     $condition['business_process_id'] = $request->business_process_id;
        // }
        $pdas = PersonalDataAssessment::join(getDatabaseName('mysql') . 'business_processes as business_processes', 'business_processes.id', 'personal_data_assessments.business_process_id')
            ->join(getDatabaseName('mysql') . 'business_units as business_units', 'business_units.id', 'personal_data_assessments.business_unit_id')
            ->where('personal_data_assessments.client_id', $client_id)
            ->where($condition)
            ->select('personal_data_assessments.*', 'business_units.unit_name as business_unit', 'business_processes.name as business_process')
            ->get();
        // Group By Business unit
        $grouped_pdas = $pdas->groupBy('business_unit');
        return response()->json(compact('pdas', 'grouped_pdas'), 200);
    }

    public function fetchPersonalDataItems(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $personal_data_items = PersonalDataItem::where(function ($q) use ($client_id) {
            $q->whereNull('client_id')
                ->orWhere('client_id', $client_id);

        })->orderBy('item')->get();
        return response()->json(compact('personal_data_items'), 200);
    }
    private function saveNewPersonalDataItem(Request $request, $items)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        if ($items != NULL) {

            foreach ($items as $item) {
                PersonalDataItem::firstOrCreate([
                    'item' => $item,
                    'client_id' => $client_id
                ]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        PersonalDataAssessment::firstOrCreate([
            'client_id' => $request->client_id,
            'standard_id' => $request->standard_id,
            'business_unit_id' => $request->business_unit_id,
            'business_process_id' => $request->business_process_id,
            'personal_data_item' => $request->personal_data_item,
            'description' => $request->description,
            'sensitive_personal_data' => $request->sensitive_personal_data,
            'exception_used_personal_data' => $request->exception_used_personal_data,
            'obtained_from_data_source' => $request->obtained_from_data_source,
            'owner' => $request->owner,
            'processing_purpose' => $request->processing_purpose,
            'lawful_basis_of_processing' => $request->lawful_basis_of_processing,
            'how_is_consent_obtained' => $request->how_is_consent_obtained,
            'automated_decision_making' => $request->automated_decision_making,
            'level_of_data_subject_access' => $request->level_of_data_subject_access,
            'location_stored' => $request->location_stored,
            'country_stored_in' => $request->country_stored_in,
            'retention_period' => $request->retention_period,
            'encryption_level' => $request->encryption_level,
            'access_control' => $request->access_control,
            'third_parties_shared_with' => $request->third_parties_shared_with,
            'comments' => $request->comments
        ]);
        $this->saveNewPersonalDataItem($request->personal_data_item);
        return response()->json('success');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PersonalDataAssessment  $pda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PersonalDataAssessment $pda)
    {
        $field = $request->field;
        $value = $request->value;
        $pda->$field = $value;
        $pda->save();
        if ($field == 'personal_data_item') {

            $this->saveNewPersonalDataItem($value);
        }
        return response()->json(compact('pda'), 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PersonalDataAssessment  $pda
     * @return \Illuminate\Http\Response
     */
    public function destroy(PersonalDataAssessment $pda)
    {
        //
        $pda->delete();
        return response()->json([], 204);
    }
}
