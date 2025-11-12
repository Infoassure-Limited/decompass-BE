<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use App\Models\AvailableModule;
use App\Models\Client;
use App\Models\Project;
use App\Models\VendorDueDiligence\DueDiligenceEvidence;
use App\Models\DueDiligenceQuestion;
use App\Models\VendorDueDiligence\DueDiligenceResponse;
use App\Models\User;
use App\Models\VendorDueDiligence\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

class DueDiligenceReportsController extends Controller
{
    protected $module;
    public function __construct()
    {
        //->paginate(10);
        $this->module = AvailableModule::where('slug', 'vdd')->first();
    }
    public function index(Request $request)
    {
        // $client_id = $request->client_id;
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $answers = DueDiligenceResponse::with('evidences')
            ->join('due_diligence_questions', 'due_diligence_questions.id', 'due_diligence_responses.due_diligence_question_id')
            ->where(['due_diligence_responses.client_id' => $client_id, 'due_diligence_responses.vendor_id' => $vendor_id])
            ->select('*', 'due_diligence_responses.id as id')
            ->get()
            ->groupBy('domain');
        return response()->json(compact('answers'), 200);
    }
}
