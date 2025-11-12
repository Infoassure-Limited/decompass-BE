<?php

namespace App\Http\Controllers;

use App\Models\Standard;
use Illuminate\Http\Request;

class StandardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $standards = Standard::with('consulting')->orderBy('id', 'DESC')->get();
        return response()->json(compact('standards'), 200);
    }
    public function fetchWithClauses()
    {
        $standards = Standard::with(['clauses' => function ($q) {
            $q->where('will_have_audit_questions', 1);
        }])->get();
        return response()->json(compact('standards'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $names_string = $request->names;
        $names_array = explode('|', $names_string);
        $assessment_activities = implode('|', $request->assessment_activities);
        foreach ($names_array as $name) {
            Standard::firstOrCreate([
                'name' => trim($name),
                'consulting_id' => $request->consulting_id,
                'assessment_activities' => $assessment_activities
            ]);
        }
        return response()->json(['message' => 'Successful'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Standard  $standard
     * @return \Illuminate\Http\Response
     */
    public function show(Standard $standard)
    {
        //
        $standard = $standard->with('clauses', 'questions')->find($standard->id);
        return response()->json(compact('standard'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Standard  $standard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Standard $standard)
    {
        //
        $assessment_activities = implode('|', $request->assessment_activities);
        $standard->name = $request->name;
        $standard->consulting_id = $request->consulting_id;
        $standard->description = $request->description;
        $standard->assessment_activities = $assessment_activities;
        $standard->save();
        return response()->json(['message' => 'Successful'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Standard  $standard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Standard $standard)
    {
        $standard->delete();
        return response()->json([], 204);
    }
}
