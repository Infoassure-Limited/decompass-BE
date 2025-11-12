<?php

namespace App\Http\Controllers;

use App\Models\ClientEvidence;
use App\Models\Evidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if (isset($request->standard_id) && $request->standard_id !== '') {
            $evidence = Evidence::with('standard')->where('standard_id', $request->standard_id)->get();
        } else {

            $evidence = Evidence::with('standard')->get();
        }
        return response()->json(compact('evidence'), 200);
    }
    public function fetchClientEvidence(Request $request)
    {
        $client_id = $request->client_id;
        $standard_id = $request->standard_id;
        $consulting_id = $request->consulting_id;
        $evidence = Evidence::with([
            'clientEvidences' => function ($q) use ($client_id, $standard_id) {
                $q->where(['standard_id' => $standard_id, 'client_id' => $client_id]);
            }
        ])->where(['consulting_id' => $consulting_id])->orderBy('title')->get();
        return response()->json(compact('evidence'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $evidence = Evidence::where([
            'standard_id' => $request->standard_id,
            'consulting_id' => $request->consulting_id,
            'title' => $request->title
        ])->first();
        if (!$evidence) {
            $evidence = new Evidence();
        }

        $evidence->standard_id = $request->standard_id;
        $evidence->consulting_id = $request->consulting_id;
        $evidence->title = $request->title;
        $evidence->upload_type = $request->upload_type;
        $evidence->sub_document_titles = ($request->upload_type === 'multiple') ? $request->sub_document_titles : NULL;
        $evidence->save();

        return response()->json(['message' => 'Successful'], 200);
    }

    public function createClientEvidence(Request $request)
    {
        $evidence_id = $request->evidence_id;
        $project_id = $request->project_id;
        $client_id = $request->client_id;
        $standard_id = $request->standard_id;
        $mode = $request->mode;

        if ($mode === 'single') {

            $client_evidence = ClientEvidence::where([
                'client_id' => $client_id,
                'evidence_id' => $evidence_id,
                'standard_id' => $standard_id,
                'project_id' => $project_id,
            ])->first();
            if (!$client_evidence) {
                $client_evidence = new ClientEvidence();
            } else {
                // remove previous upload
                Storage::disk('public')->delete($client_evidence->link);
            }
        } else {
            $client_evidence = new ClientEvidence();
        }
        return $this->uploadClientEvidenceFile($request, $client_evidence);
    }
    private function uploadClientEvidenceFile(Request $request, ClientEvidence $client_evidence)
    {
        $evidence_id = $request->evidence_id;
        // $clause_id = $request->clause_id;
        $project_id = $request->project_id;
        // $consulting_id = $request->consulting_id;
        $client_id = $request->client_id;
        $standard_id = $request->standard_id;
        $client = $this->getClient();
        // $folder_key =  $client->id;
        $folder_key = str_replace(' ', '_', ucwords($client->name));
        if ($request->file('file_uploaded') != null && $request->file('file_uploaded')->isValid()) {

            $client_evidence->client_id = $client_id;
            $client_evidence->evidence_id = $evidence_id;
            $client_evidence->project_id = $project_id;
            $client_evidence->standard_id = $standard_id;
            if ($client_evidence->save()) {

                $document_name = $request->file('file_uploaded')->getClientOriginalName();
                $name = $request->file('file_uploaded')->hashName();
                $file_name = $name . "." . $request->file('file_uploaded')->extension();
                $link = $request->file('file_uploaded')->storeAs('clients/' . $folder_key . '/evidence', $file_name, 'public');
                $client_evidence->link = $link;
                $client_evidence->document_name = $document_name;
                $client_evidence->save();
            }
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Evidence  $evidence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evidence $evidence)
    {
        $evidence->title = $request->title;
        $evidence->consulting_id = $request->consulting_id;
        $evidence->standard_id = $request->standard_id;
        $evidence->upload_type = $request->upload_type;
        $evidence->sub_document_titles = ($request->upload_type === 'multiple') ? $request->sub_document_titles : NULL;
        $evidence->save();
        return response()->json(['message' => 'Successful'], 200);
    }
    public function destroyClientEvidence(ClientEvidence $client_evidence)
    {
        Storage::disk('public')->delete($client_evidence->link);
        $client_evidence->delete();
        return response()->json([], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Evidence  $evidence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evidence $evidence)
    {
        $evidence->delete();
        return response()->json([], 204);
    }
}
