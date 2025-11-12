<?php

namespace App\Http\Controllers;

use App\Models\SOAArea;
use App\Models\SOAControl;
use App\Models\StatementOfApplicability;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SOAController extends Controller
{
    public function fetchAreas()
    {
        $areas = SOAArea::with('controls')->orderBy('name')->get();
        return response()->json(compact('areas'), 200);
    }
    public function fetchControls()
    {
        $controls = SOAControl::with('area')->orderBy('name')->get();
        return response()->json(compact('controls'), 200);
    }
    public function saveAreas(Request $request)
    {
        $names_array = $request->names;
        foreach ($names_array as $name) {
            SOAArea::firstOrCreate([
                'name' => trim($name)
            ]);
        }
        return response()->json(['message' => 'Successful'], 200);
    }
    public function saveControl(Request $request)
    {
        $name = $request->name;
        $area_id = $request->area_id;
        $description = $request->description;
        $control = SOAControl::where(['s_o_a_area_id' => $area_id, 'name' => $name])->first();
        if (!$control) {
            $control = new SOAControl();
        }
        $control->name = $name;
        $control->s_o_a_area_id = $area_id;
        $control->description = $description;
        $control->save();
        return response()->json(['message' => 'Successful'], 200);
    }

    public function deleteArea(SOAArea $value)
    {
        $value->controls()->delete();
        $value->delete();
        return response()->json([], 204);
    }
    public function deleteControl(SOAControl $value)
    {
        $value->delete();
        return response()->json([], 204);
    }

    public function fetchSOA(Request $request)
    {
        $client_id = $this->getClient()->id;

        // if (isset($request->client_id)) {
        //     $client_id = $request->client_id;
        // } else {

        //     $client_id = $this->getClient()->id;
        // }
        $this->setupSOAForClient($client_id);
        $soas = SOAArea::with([
            'controls.soa' => function ($q) use ($client_id, ) {
                $q->where(['client_id' => $client_id]);
            }
        ])->orderBy('name')->get();
        return response()->json(compact('soas'), 200);
    }
    public function show(Request $request, StatementOfApplicability $soa)
    {
        $client_id = $this->getClient()->id;
        if ($client_id != $soa->client_id) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }
        return response()->json(compact('soa'), 200);
    }
    private function setupSOAForClient($client_id)
    {
        // SOAControl::chunkById(50, function (Collection $soa_controls) use ($client_id, $standard_id) {
        //     foreach ($soa_controls as $soa_control) {
        //         StatementOfApplicability::firstOrCreate([
        //             'client_id' => $client_id,
        //             'standard_id' => $standard_id,
        //             's_o_a_area_id' => $soa_control->s_o_a_area_id,
        //             's_o_a_control_id' => $soa_control->id,
        //         ]);
        //     }
        // });
        $soa_controls = SOAControl::get();
        foreach ($soa_controls as $soa_control) {
            StatementOfApplicability::firstOrCreate([
                'client_id' => $client_id,
                // 'standard_id' => $standard_id,
                's_o_a_area_id' => $soa_control->s_o_a_area_id,
                's_o_a_control_id' => $soa_control->id,
            ]);
        }
    }
    public function update(Request $request, StatementOfApplicability $soa)
    {
        $field = $request->field;
        $value = $request->value;

        $soa->$field = $value;
        $soa->save();
    }
}
