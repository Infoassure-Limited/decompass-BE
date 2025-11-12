<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use App\Models\VendorDueDiligence\Vendor;
use Illuminate\Http\Request;
use App\Models\VendorDueDiligence\VendorAudit;
use App\Models\VendorDueDiligence\AuditResponse;
use Illuminate\Support\Facades\Validator;
class VendorAuditsController extends Controller
{
    public function index(Request $request)
    {
        $query = VendorAudit::with(['vendor', 'template']);

        if ($request->has('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $audits = $query->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $audits], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'audit_template_id' => 'required|exists:audit_templates,id',
            'audit_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:audit_date',
            'status' => 'in:draft,in_progress,completed,expired,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['auditor_id'] = Auth::id();

        $audit = VendorAudit::create($data);
        return response()->json(['data' => $audit, 'message' => 'Vendor audit created successfully'], 201);
    }

    public function show($id)
    {
        $audit = VendorAudit::with(['vendor', 'template', 'responses.question', 'auditor'])->findOrFail($id);
        return response()->json(['data' => $audit], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'audit_date' => 'sometimes|required|date',
            'due_date' => 'nullable|date|after_or_equal:audit_date',
            'status' => 'in:draft,in_progress,completed,expired,canceled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $audit = VendorAudit::findOrFail($id);
        $audit->update($request->all());

        return response()->json(['data' => $audit, 'message' => 'Vendor audit updated successfully'], 200);
    }

    public function destroy($id)
    {
        $audit = VendorAudit::findOrFail($id);
        $audit->delete();
        return response()->json(['message' => 'Vendor audit deleted successfully'], 200);
    }

    public function submitResponses(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'responses' => 'required|array',
            'responses.*.audit_question_id' => 'required|exists:audit_questions,id',
            'responses.*.response' => 'required|string',
            'responses.*.comment' => 'nullable|string',
            'responses.*.score' => 'nullable|numeric',
            'responses.*.attachments' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $audit = VendorAudit::findOrFail($id);

        foreach ($request->responses as $responseData) {
            $responseData['vendor_audit_id'] = $id;

            AuditResponse::updateOrCreate(
                [
                    'vendor_audit_id' => $id,
                    'audit_question_id' => $responseData['audit_question_id']
                ],
                $responseData
            );
        }

        // Update audit status if needed
        if ($audit->status === 'draft') {
            $audit->status = 'in_progress';
            $audit->save();
        }

        // Calculate the score
        $audit->calculateScore();

        return response()->json([
            'message' => 'Responses submitted successfully',
            'data' => $audit->fresh()->load('responses')
        ], 200);
    }

    public function complete(Request $request, $id)
    {
        $audit = VendorAudit::findOrFail($id);

        // Check if all required questions have responses
        $template = $audit->template;
        $requiredQuestions = $template->questions()->where('is_required', true)->count();
        $answeredRequiredQuestions = $audit->responses()
            ->whereIn('audit_question_id', $template->questions()->where('is_required', true)->pluck('id'))
            ->count();

        if ($requiredQuestions > $answeredRequiredQuestions) {
            return response()->json([
                'message' => 'Cannot complete audit. Not all required questions have been answered.',
                'required' => $requiredQuestions,
                'answered' => $answeredRequiredQuestions
            ], 422);
        }

        // Calculate final score
        $audit->calculateScore();

        // Update to completed
        $audit->status = 'completed';
        $audit->save();

        return response()->json([
            'message' => 'Audit completed successfully',
            'data' => $audit->fresh()
        ], 200);
    }
    public function getAudits($id)
    {
        $vendor = Vendor::findOrFail($id);
        $audits = $vendor->audits()->with(['template', 'responses'])->get();
        return response()->json(['data' => $audits], 200);
    }

    public function getRiskAssessments($id)
    {
        $vendor = Vendor::findOrFail($id);
        $assessments = $vendor->riskAssessments()->with(['remediationPlans'])->get();
        return response()->json(['data' => $assessments], 200);
    }
}
