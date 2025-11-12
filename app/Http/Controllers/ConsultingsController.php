<?php

namespace App\Http\Controllers;

use App\Models\Consulting;
use Illuminate\Http\Request;

class ConsultingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consultings = Consulting::with('standards')->orderBy('id', 'DESC')->get();
        return response()->json(compact('consultings'), 200);
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
        foreach ($names_array as $name) {
            Consulting::firstOrCreate([
                'name' => trim($name)
            ]);
        }
        return response()->json(['message' => 'Successful'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Consulting  $consulting
     * @return \Illuminate\Http\Response
     */
    public function show(Consulting $consulting)
    {
        //
        $consulting = $consulting->with('standards')->find($consulting->id);
        return response()->json(compact('consulting'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consulting  $consulting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Consulting $consulting)
    {
        //
        $consulting->name = $request->name;
        $consulting->description = $request->description;
        $consulting->save();
        return response()->json(['message' => 'Successful'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Consulting  $consulting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Consulting $consulting)
    {
        $consulting->delete();
        return response()->json([], 204);
    }
}
