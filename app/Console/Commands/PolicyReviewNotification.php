<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Policy\Policy;
use App\Notifications\PolicyReviewDue;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PolicyReviewNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'isms:policy-review-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for policies that need review';

    public function updateDueReviewsBackToDraft()
    {
        $today = date('Y-m-d', strtotime('now'));
        Policy::where('status', 'published')
            ->where('review_date', '<=', $today)
            ->chunkById(100, function (Collection $policies) {
                foreach ($policies as $policy) {
                    $policy->update(['status' => 'review']);
                }
            }, $column = 'id');
        return 0;
    }
    public function sendDueReviewNotification()
    {
        $dueSoonDate = Carbon::now()->addDays(30);

        $policies = Policy::where('status', 'published')
            ->where('review_date', '<=', $dueSoonDate)
            ->with(['owner'])
            ->get();

        $count = 0;

        foreach ($policies as $policy) {
            $owner = $policy->owner;
            if ($owner) {
                $owner->notify(new PolicyReviewDue($policy));
                $count++;
            }
        }

        $this->info("Sent {$count} policy review notifications.");

        return 0;
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->sendDueReviewNotification();
        $this->updateDueReviewsBackToDraft();
    }
}
