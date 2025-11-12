<?php

namespace App\Http\Controllers\Policy;

use App\Http\Controllers\Controller;
use App\Models\Policy\Policy;
use App\Models\Policy\PolicyAudit;
use App\Models\Policy\PolicyCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolicyDashboardController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    /**
     * Get dashboard statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('viewDashboard', Policy::class);
        $client_id = $this->getClient()->id;
        $stats = [
            'total_policies' => Policy::where('client_id', $client_id)->count(),
            'published_policies' => Policy::where('client_id', $client_id)->where('status', 'published')->count(),
            'draft_policies' => Policy::where('client_id', $client_id)->where('status', 'draft')->count(),
            'review_policies' => Policy::where('client_id', $client_id)->where('status', 'review')->orWhere('status', 'approved')->count(),
            'archived_policies' => Policy::where('client_id', $client_id)->where('status', 'archived')->count(),
            'expiring_soon' => Policy::where('client_id', $client_id)->where('status', 'published')
                ->where('expiry_date', '<=', now()->addMonths(3))
                ->where('expiry_date', '>=', now())
                ->count(),
            'review_needed' => Policy::where('client_id', $client_id)->where('status', 'published')
                ->where('review_date', '<=', now())
                ->count(),
            'policies_by_category' => PolicyCategory::where('client_id', $client_id)->withCount('policies')
                ->get()
                ->map(function ($category) {
                    return [
                        'name' => $category->name,
                        'count' => $category->policies_count
                    ];
                }),
            'recent_activity' => PolicyAudit::where('client_id', $client_id)->with(['policy', 'user'])
                ->orderBy('created_at', 'desc')
                ->limit(value: 10)
                ->get(),
            'monthly_stats' => $this->getMonthlyStats()
        ];

        return response()->json(['data' => $stats]);
    }

    /**
     * Get monthly statistics for policies.
     *
     * @return array
     */
    private function getMonthlyStats()
    {
        $months = 12;
        $result = [];

        for ($i = 0; $i < $months; $i++) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');

            $result[] = [
                'month' => $month,
                'created' => Policy::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'published' => Policy::whereYear('published_at', $date->year)
                    ->whereMonth('published_at', $date->month)
                    ->count(),
            ];
        }

        return array_reverse($result);
    }
}