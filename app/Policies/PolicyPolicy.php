<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Policy\Policy;
use Illuminate\Auth\Access\HandlesAuthorization;

class PolicyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any policies.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return true; // All authenticated users can view policies list
    }

    /**
     * Determine whether the user can view the policy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function view(User $user, Policy $policy)
    {
        // Only published policies are visible to everyone
        // Drafts, reviews, and approved policies are only visible to owners and admins
        if ($policy->status === 'published' || $policy->status === 'archived') {
            return true;
        }

        return $user->id === $policy->owner_id ||
            $user->hasRole(['admin', 'policy_manager', 'compliance_manager']);
    }

    /**
     * Determine whether the user can create policies.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole(['admin', 'policy_manager', 'compliance_manager']);
    }

    /**
     * Determine whether the user can update the policy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function update(User $user, Policy $policy)
    {
        // Only draft policies can be updated by their owners
        // Admins can update any policy that's not published or archived
        if ($policy->status === 'published' || $policy->status === 'archived') {
            return $user->hasRole(['admin', 'policy_manager']);
        }

        return $user->id === $policy->owner_id ||
            $user->hasRole(['admin', 'policy_manager']);
    }

    /**
     * Determine whether the user can delete the policy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function delete(User $user, Policy $policy)
    {
        // Only drafts can be deleted, and only by admins or the owner
        if ($policy->status !== 'draft') {
            return false;
        }

        return $user->id === $policy->owner_id ||
            $user->hasRole(['admin', 'policy_manager']);
    }

    /**
     * Determine whether the user can submit the policy for review.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function submitForReview(User $user, Policy $policy)
    {
        // Only drafts can be submitted for review, and only by the owner or admins
        if ($policy->status !== 'draft') {
            return false;
        }

        return $user->id === $policy->owner_id ||
            $user->hasRole(['admin', 'policy_manager']);
    }

    /**
     * Determine whether the user can approve the policy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function approve(User $user, Policy $policy)
    {
        // Only policies in review can be approved, and only by admins or compliance managers
        // The owner cannot approve their own policy
        if ($policy->status !== 'review' || $user->id === $policy->owner_id) {
            return false;
        }

        return $user->hasRole(['admin', 'compliance_manager']);
    }

    /**
     * Determine whether the user can publish the policy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function publish(User $user, Policy $policy)
    {
        // Only approved policies can be published, and only by admins or policy managers
        if ($policy->status !== 'approved') {
            return false;
        }

        return $user->hasRole(['admin', 'policy_manager']);
    }

    /**
     * Determine whether the user can archive the policy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function archive(User $user, Policy $policy)
    {
        // Only published policies can be archived, and only by admins or policy managers
        if ($policy->status !== 'published') {
            return false;
        }

        return $user->hasRole(['admin', 'policy_manager']);
    }

    /**
     * Determine whether the user can view policy versions.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function viewVersions(User $user, Policy $policy)
    {
        // The policy owner and admins can view versions of any policy
        // For published policies, anyone can view versions
        if ($policy->status === 'published' || $policy->status === 'archived') {
            return true;
        }

        return $user->id === $policy->owner_id ||
            $user->hasRole(['admin', 'policy_manager', 'compliance_manager']);
    }

    /**
     * Determine whether the user can view the audit trail.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function viewAuditTrail(User $user, Policy $policy)
    {
        return $user->id === $policy->owner_id ||
            $user->hasRole(['admin', 'policy_manager', 'compliance_manager', 'auditor']);
    }

    /**
     * Determine whether the user can mark the policy as read.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function read(User $user, Policy $policy)
    {
        // Only published policies can be marked as read
        return $policy->status === 'published';
    }

    /**
     * Determine whether the user can acknowledge the policy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function acknowledge(User $user, Policy $policy)
    {
        // Only published policies can be acknowledged
        return $policy->status === 'published';
    }

    /**
     * Determine whether the user can view policy acknowledgements.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function viewAcknowledgements(User $user, Policy $policy)
    {
        return $user->id === $policy->owner_id ||
            $user->hasRole(['admin', 'policy_manager', 'compliance_manager', 'hr_manager']);
    }

    /**
     * Determine whether the user can view policy compliance summary.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Policy\Policy  $policy
     * @return bool
     */
    public function viewComplianceSummary(User $user, Policy $policy)
    {
        return $user->id === $policy->owner_id ||
            $user->hasRole(['admin', 'policy_manager', 'compliance_manager', 'hr_manager']);
    }

    /**
     * Determine whether the user can view policy dashboard.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewDashboard(User $user)
    {
        return $user->hasRole(['admin', 'policy_manager', 'compliance_manager']);
    }
}
