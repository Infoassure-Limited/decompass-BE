<?php

namespace App\Http\Controllers;

use App\Models\GeneralRiskLibrary;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
class GeneralRiskLibrariesController extends Controller
{
    // public function __construct(Request $httpRequest)
    // {
    //     parent::__construct($httpRequest);
    //     $this->middleware(function ($request, $next) {

    //         $this->autoGenerateAndSaveRiskLibrary();
    //         return $next($request);
    //     });


    // }
    private function autoGenerateAndSaveRiskLibrary()
    {
        // if (isset($request->client_id)) {
        //     $client_id = $request->client_id;
        // } else {
        //     $client_id = $this->getClient()->id;
        // }
        $asset_type_count = GeneralRiskLibrary::count();
        if ($asset_type_count < 1) {
            $names_array = $this->generateAssetTypes(); // $request->names;
            if ($names_array !== null && count($names_array) > 0) {

                foreach ($names_array as $name) {
                    GeneralRiskLibrary::firstOrCreate([
                        // 'client_id' => $client_id,
                        'name' => trim(ucwords($name))
                    ]);
                }
            }
        }

    }

    public function generativeThreatIntelligence()
    {
        //
        // $message = "What are the cyber security threat intelligence feeds each from reputable sources. ";
        // $question = "Provide at least 10 responses each for the following standards: ### ISO 27001, ISO 27017, ISO 27018 and PCI DSS ###";
        // // $answer = "Response: ### $ans, $details ###";
        // // $evidence = "Evidence: ### $evid ###";
        // $instruction = "
        //     Let all affected assets by the threats, be listed in a string array format. Also provide the vulnerabilities for each of the threats and the solution to each of the vulnerabilities in a array formats
        //     Provide the responses as an array of objects in json format for easy extraction in the format below:

        //     threat: <threat>
        //     affected_assets: <affected_assets>
        //     vulnerabilities: <vulnerabilities>
        //     source: <source>
        //     solutions: <solutions>";

        // $content = $message . $question . $instruction;

        // $result = OpenAI::chat()->create([
        //     'model' => 'gpt-3.5-turbo',
        //     'messages' => [
        //         ['role' => 'user', 'content' => $content],
        //     ],
        // ]);

        // response is score and justification
        $filename = portalPulicPath('cybersecurity_threat_intel.json');
        $file_content = file_get_contents($filename);
        $feeds = json_decode($file_content);
        foreach ($feeds as $feed) {
            $threat = $feed->threat;
            $threat_library = GeneralRiskLibrary::where('threats', 'LIKE', $threat)->first();
            if (!$threat_library) {
                $threat_library = new GeneralRiskLibrary();
            }
            $threat_library->asset_types = $feed->asset_type;
            $threat_library->items = $feed->affected_assets;
            $threat_library->threats = $threat;
            $threat_library->vulnerabilities = $feed->vulnerabilities;
            // $threat_library->source = $feed->source;
            $threat_library->solutions = $feed->solutions;
            $threat_library->save();
        }
        //print_r($result);
    }
    private function generateAssetTypes()
    {
        //
        $message = "As an ISMS manager list all possible Threats, Vulnerabilites and Control measure or solution for all possible asset an organization can have. ";
        $instruction = "Provide the response in a string array format";

        $content = $message . $instruction;

        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $content],
            ],
        ]);

        // response is score and justification
        $ai_response = json_decode($result->choices[0]->message->content);
        return $ai_response;
        // print_r($result);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $risk_libraries = GeneralRiskLibrary::orderBy('threats')->paginate(10);
        return response()->json(compact('risk_libraries'), 200);
    }
    public function fetchThreats(Request $request)
    {
        $query = GeneralRiskLibrary::query();
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $query->where('client_id', $client_id)->orWhere('client_id', NULL);
        if (isset($request->asset_type) && $request->asset_type != '') {
            $asset_type = $request->asset_type;
            $query->where('asset_types', 'LIKE', "%$asset_type%");
        }
        $threats = $query->orderBy('threats')->get();
        return response()->json(compact('threats'), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        if (isset($request->client_id)) {
            $client_id = $request->client_id;
        } else {
            $client_id = $this->getClient()->id;
        }
        $formData = $request->validate(
            [
                'data' => 'required|array',
                'asset_type' => 'required|string',
            ]
        );
        $entries = json_decode(json_encode($formData['data']));
        foreach ($entries as $entry) {
            GeneralRiskLibrary::firstOrCreate([
                'client_id' => $client_id,
                'threats' => $entry->threat
            ], [
                'asset_types' => $formData['asset_type'],
                'vulnerabilities' => explode(',', $entry->vulnerabilities)
            ]);
        }

    }

    public function storeBulk(Request $request)
    {
        $bulk_data = json_decode(json_encode($request->bulk_data));
        foreach ($bulk_data as $csvRow) {
            //try {
            $threats = trim($csvRow->THREATS);
            $vulnerabilities = isset($csvRow->VULNERABILITIES) ? trim($csvRow->VULNERABILITIES) : NULL;
            $solutions = isset($csvRow->SOLUTIONS) ? trim($csvRow->SOLUTIONS) : NULL;

            $risk_library = GeneralRiskLibrary::where('threats', $threats)->first();
            if ($risk_library) {
                $risk_library->vulnerabilities .= ' ' . $vulnerabilities;
                $risk_library->solutions .= ' ' . $solutions;
                $risk_library->save();
            } else {
                GeneralRiskLibrary::create([
                    'threats' => $threats,
                    'vulnerabilities' => $vulnerabilities,
                    'solutions' => $solutions
                ]);
            }
        }
        return 'success';

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GeneralRiskLibrary  $generalRiskLibrary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GeneralRiskLibrary $generalRiskLibrary)
    {
        //
        $field = $request->field;
        $generalRiskLibrary->$field = $request->value;
        $generalRiskLibrary->save();
        return response()->json([], 204);
    }
}
