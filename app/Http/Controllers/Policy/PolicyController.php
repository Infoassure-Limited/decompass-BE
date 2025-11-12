<?php

namespace App\Http\Controllers\Policy;

use App\Http\Controllers\Controller;
use App\Models\Policy\Policy;
use App\Models\Policy\PolicyAudit;
use App\Models\Policy\PolicyVersion;
use App\Http\Requests\PolicyStoreRequest;
use App\Http\Requests\PolicyUpdateRequest;
use App\Http\Resources\PolicyResource;
use App\Http\Resources\PolicyCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PolicyController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    //     $this->authorizeResource(Policy::class, 'policy');
    // }

    /**
     * Display a listing of policies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\PolicyCollection
     */
    public function index(Request $request)
    {
        $client_id = $this->getClient()->id;
        $query = Policy::with(['category', 'owner'])->where('client_id', $client_id);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Sort options
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSortFields = ['title', 'document_number', 'status', 'created_at', 'effective_date', 'review_date'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $policies = $query->paginate($request->input('per_page', 15));

        return new PolicyCollection($policies);
    }

    /**
     * Store a newly created policy.
     *
     * @param  \App\Http\Requests\PolicyStoreRequest  $request
     * @return \App\Http\Resources\PolicyResource
     */
    public function store(PolicyStoreRequest $request)
    {
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $policy = new Policy($request->validated());
        $policy->owner_id = $user_id;
        $policy->client_id = $client_id;
        $policy->status = 'draft';
        $policy->save();

        $policy->document_number = 'POL-' . $policy->id . randomNumber(5);
        $policy->save();
        // Create initial version
        $policyVersion = new PolicyVersion([
            'policy_id' => $policy->id,
            'version_number' => '1.0',
            'content' => $policy->content,
            'created_by' => $user_id,
        ]);
        $policyVersion->save();

        // Create audit record
        $this->createPolicyAudit($policy->id, 'created', 'Policy drafted');

        return new PolicyResource($policy->load(['category', 'owner']));
    }
    private function createPolicyAudit($policyId, $action, $details)
    {
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        PolicyAudit::create([
            'client_id' => $client_id,
            'policy_id' => $policyId,
            'user_id' => $user_id,
            'action' => $action,
            'details' => $details
        ]);
    }
    /**
     * Display the specified policy.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return \App\Http\Resources\PolicyResource
     */
    public function show(Policy $policy)
    {
        $policy->load([
            'category',
            'owner',
            'approver',
            'versions' => function ($q) {
                $q->orderBy('id', 'DESC');
            }
        ]);
        return new PolicyResource($policy);
    }

    /**
     * Update the specified policy.
     *
     * @param  \App\Http\Requests\PolicyUpdateRequest  $request
     * @param  \App\Models\Policy\Policy  $policy
     */
    public function update(PolicyUpdateRequest $request, Policy $policy)
    {
        $old_content = $policy->content;
        $user_id = $this->getUser()->id;
        $client_id = $this->getClient()->id;
        $oldStatus = $policy->status;
        $policy->update($request->validated());
        // If content was updated, create a new version
        if ($request->has('content') && strcmp($old_content, $policy->content) !== 0) {
            $latestVersion = $policy->versions()->latest()->first();
            $versionNumber = $latestVersion ?
                $this->incrementVersionNumber($latestVersion->version_number) :
                '1.0';

            $policyVersion = new PolicyVersion([
                'policy_id' => $policy->id,
                'version_number' => $versionNumber,
                'content' => $policy->content,
                'change_summary' => $request->change_summary ?? 'Content updated',
                'created_by' => $user_id,
            ]);
            $policyVersion->save();
        }

        // Create audit record
        $details = $oldStatus != $policy->status ?
            "Status changed from {$oldStatus} to {$policy->status}" :
            'Policy details updated';
        $this->createPolicyAudit($policy->id, 'updated', $details);
        return new PolicyResource($policy->load(['category', 'owner']));
    }

    /**
     * Remove the specified policy.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function destroy(Policy $policy)
    {
        $details = 'Policy deleted';
        $this->createPolicyAudit($policy->id, 'deleted', $details);

        $policy->delete();

        return response()->json(['message' => 'Policy deleted successfully']);
    }

    /**
     * Submit a policy for review.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return \App\Http\Resources\PolicyResource
     */
    public function updateFields(Request $request, Policy $policy)
    {
        // $this->authorize('submitForReview', $policy);
        $field = $request->field;
        $value = $request->value;

        $policy->$field = $value;
        $policy->save();

        // $details = 'Policy submitted for review';
        // $this->createPolicyAudit($policy->id, $details);

        return new PolicyResource($policy->load(['category', 'owner']));
    }
    /**
     * Submit a policy for review.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return \App\Http\Resources\PolicyResource
     */
    public function submitForReview(Policy $policy)
    {
        // $this->authorize('submitForReview', $policy);

        if ($policy->status !== 'draft') {
            return response()->json([
                'message' => 'Only draft policies can be submitted for review'
            ], 422);
        }

        $policy->status = 'review';
        $policy->save();

        $details = 'Policy submitted for review';
        $this->createPolicyAudit($policy->id, 'submitted', $details);

        return new PolicyResource($policy->load(['category', 'owner']));
    }

    /**
     * Approve a policy.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return \App\Http\Resources\PolicyResource
     */
    public function approve(Policy $policy)
    {
        // $this->authorize('approve', $policy);

        if ($policy->status !== 'review') {
            return response()->json([
                'message' => 'Only policies in review can be approved'
            ], 422);
        }

        $policy->status = 'approved';
        $policy->approved_by = $this->getUser()->id;
        $policy->approved_at = now();
        // $policy->review_date = date('Y-m-d', strtotime('now'));
        $policy->save();
        $this->calculateReviewDate($policy, $policy->review_interval);
        $details = 'Policy review approved';
        $this->createPolicyAudit($policy->id, 'approved', $details);

        return new PolicyResource($policy->load(['category', 'owner', 'approver']));
    }
    private function calculateReviewDate($policy, $interval)
    {
        switch ($interval) {
            case 'Quarterly':
                $review_date = date('Y-m-d', strtotime('now +3months'));
                break;
            case 'Biannually':
                $review_date = date('Y-m-d', strtotime('now +6months'));
                break;
            case 'Anually':
                $review_date = date('Y-m-d', strtotime('now +12months'));
                break;
            default:
                $review_date = date('Y-m-d', strtotime('now +3months'));
                break;
        }
        $policy->review_date = $review_date;
        $policy->save();
    }

    /**
     * Publish a policy.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\PolicyResource
     */
    public function publish(Policy $policy, Request $request)
    {
        // $this->authorize('publish', $policy);

        if ($policy->status !== 'approved') {
            return response()->json([
                'message' => 'Only approved policies can be published'
            ], 422);
        }

        // $request->validate([
        //     'effective_date' => 'required|date|after_or_equal:today',
        //     'review_date' => 'required|date|after:effective_date',
        //     'expiry_date' => 'nullable|date|after:effective_date',
        // ]);

        $policy->status = 'published';
        $policy->published_at = now();
        // $policy->effective_date = $request->effective_date;
        // $policy->review_date = $request->review_date;
        // $policy->expiry_date = $request->expiry_date;
        $policy->save();


        $details = 'Policy published';
        $this->createPolicyAudit($policy->id, 'published', $details);

        return new PolicyResource($policy->load(['category', 'owner', 'approver']));
    }

    /**
     * Archive a policy.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return \App\Http\Resources\PolicyResource
     */
    public function archive(Policy $policy)
    {
        // $this->authorize('archive', $policy);

        if ($policy->status !== 'published') {
            return response()->json([
                'message' => 'Only published policies can be archived'
            ], 422);
        }

        $policy->status = 'archived';
        $policy->save();


        $details = 'Policy archived';
        $this->createPolicyAudit($policy->id, 'archived', $details);

        return new PolicyResource($policy->load(['category', 'owner']));
    }

    /**
     * Get policy versions.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function versions(Policy $policy)
    {
        // $this->authorize('viewVersions', $policy);

        $versions = $policy->versions()
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $versions
        ]);
    }

    /**
     * Get the audit trail for a policy.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function auditTrail(Policy $policy)
    {
        $this->authorize('viewAuditTrail', $policy);

        $audits = $policy->audits()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $audits
        ]);
    }

    /**
     * Mark a policy as read by the current user.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function markAsRead(Policy $policy)
    {
        $this->authorize('read', $policy);

        $policy->users()->syncWithoutDetaching([
            $this->getUser()->id => [
                'read_at' => now(),
            ]
        ]);

        return response()->json([
            'message' => 'Policy marked as read'
        ]);
    }

    /**
     * Acknowledge a policy by the current user.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function acknowledge(Policy $policy)
    {
        $this->authorize('acknowledge', $policy);

        $policy->users()->syncWithoutDetaching([
            $this->getUser()->id => [
                'read_at' => now(),
                'acknowledged_at' => now(),
            ]
        ]);

        return response()->json([
            'message' => 'Policy acknowledged'
        ]);
    }

    /**
     * Get the list of users who have read/acknowledged a policy.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function acknowledgements(Policy $policy)
    {
        $this->authorize('viewAcknowledgements', $policy);

        $users = $policy->users()
            ->withPivot(['read_at', 'acknowledged_at'])
            ->get();

        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Get a summary of policy compliance.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function complianceSummary(Policy $policy)
    {
        $this->authorize('viewComplianceSummary', $policy);

        $totalUsers = \App\Models\User::count();
        $readCount = $policy->users()->whereNotNull('read_at')->count();
        $acknowledgedCount = $policy->users()->whereNotNull('acknowledged_at')->count();

        return response()->json([
            'data' => [
                'total_users' => $totalUsers,
                'read_count' => $readCount,
                'read_percentage' => $totalUsers > 0 ? round(($readCount / $totalUsers) * 100, 2) : 0,
                'acknowledged_count' => $acknowledgedCount,
                'acknowledged_percentage' => $totalUsers > 0 ? round(($acknowledgedCount / $totalUsers) * 100, 2) : 0,
            ]
        ]);
    }

    /**
     * Increment version number (e.g., 1.0 -> 1.1, 1.9 -> 2.0)
     *
     * @param  string  $versionNumber
     * @return string
     */
    private function incrementVersionNumber($versionNumber)
    {
        list($major, $minor) = explode('.', $versionNumber);

        $minor++;
        if ($minor >= 10) {
            $major++;
            $minor = 0;
        }

        return "{$major}.{$minor}";
    }
}
