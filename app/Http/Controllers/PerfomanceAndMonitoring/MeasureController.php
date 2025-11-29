<?php

namespace App\Http\Controllers\PerfomanceAndMonitoring;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMeasureRequest;
use App\Models\PerfomanceAndMonitoring\Measure;
use Illuminate\Http\Request;

class MeasureController extends Controller
{
    public function index(Request $request)
    {
        $measures =  Measure::with('infoNeed')->where('info_need_id', $request->info_need_id)->get();
        return response()->json(compact('measures'), 200);
    }

    public function store(StoreMeasureRequest $req)
    {
        $measures = $req->input('measures');
        $info_need_id = $req->input('info_need_id');
        foreach ($measures as $measure) {
            
            $measure = Measure::firstOrCreate(
                ['info_need_id' => $info_need_id, 'title' => $measure['title']]
                ,$measure);
        }
        return 'success';
    }

    public function update(StoreMeasureRequest $req, Measure $measure)
    {
        $measure->update($req->validated());
        return response()->json($measure);
    }

    public function destroy(Measure $measure)
    {
        $measure->delete();
        return response()->json(['message' => 'deleted']);
    }
}