<?php

namespace App\Observers;
use App\Models\Policy\Policy;
use App\Models\Policy\PolicyAudit;
use Illuminate\Support\Facades\Auth;

class PolicyObserver
{
    /**
     * Handle the Policy "created" event.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return void
     */
    public function created(Policy $policy)
    {
        $this->createAuditRecord($policy, 'created', 'Policy created');
    }

    /**
     * Handle the Policy "updated" event.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return void
     */
    public function updated(Policy $policy)
    {
        $changes = [];

        foreach ($policy->getChanges() as $field => $value) {
            if (!in_array($field, ['updated_at'])) {
                $original = $policy->getOriginal($field);
                $changes[] = "$field changed from '$original' to '$value'";
            }
        }

        if (!empty($changes)) {
            $details = implode(', ', $changes);
            $this->createAuditRecord($policy, 'updated', $details);
        }
    }

    /**
     * Handle the Policy "deleted" event.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return void
     */
    public function deleted(Policy $policy)
    {
        $this->createAuditRecord($policy, 'deleted', 'Policy deleted');
    }

    /**
     * Handle the Policy "restored" event.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return void
     */
    public function restored(Policy $policy)
    {
        $this->createAuditRecord($policy, 'restored', 'Policy restored from deletion');
    }

    /**
     * Handle the Policy "force deleted" event.
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @return void
     */
    public function forceDeleted(Policy $policy)
    {
        $this->createAuditRecord($policy, 'force_deleted', 'Policy permanently deleted');
    }

    /**
     * Create an audit record for policy actions
     *
     * @param  \App\Models\Policy\Policy  $policy
     * @param  string  $action
     * @param  string  $details
     * @return void
     */
    private function createAuditRecord(Policy $policy, $action, $details)
    {
        PolicyAudit::create([
            'policy_id' => $policy->id,
            'user_id' => Auth::id() ?? 1, // Default to admin if no authenticated user
            'action' => $action,
            'details' => $details
        ]);
    }
}