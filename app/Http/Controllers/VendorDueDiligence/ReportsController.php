<?php

namespace App\Http\Controllers\VendorDueDiligence;

use App\Http\Controllers\Controller;
use App\Models\RiskMatrix;
use App\Models\VendorDueDiligence\Category;
use App\Models\VendorDueDiligence\Contract;
use App\Models\VendorDueDiligence\DueDiligenceResponse;
use App\Models\VendorDueDiligence\Invoice;
use App\Models\VendorDueDiligence\RiskAssessment;
use App\Models\VendorDueDiligence\Ticket;
use App\Models\VendorDueDiligence\Vendor;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    //
    public function vendorOnboardingCount()
    {
        $client_id = $this->getClient()->id;
        $vendor_count = Vendor::where('client_id', $client_id)->count();
        $approved_vendors = Vendor::where(['client_id' => $client_id])
            ->where('second_approval', '!=', NULL)
            ->where('second_approval', 'LIKE', '%"Approve"%')
            ->count();
        $rejected_vendors = Vendor::where(['client_id' => $client_id])
            ->where('second_approval', '!=', NULL)
            ->where('second_approval', 'LIKE', '%"Reject"%')
            ->count();
        $pending_approval = Vendor::where(['client_id' => $client_id])
            ->where('second_approval', '=', NULL)
            ->count();
        // }
        return response()->json(compact('vendor_count', 'approved_vendors', 'rejected_vendors', 'pending_approval'), 200);
    }
    public function vendorOnboardingReportOld(Request $request)
    {
        $client_id = $this->getClient()->id;
        $chart_type = $request->chart_type;
        switch ($chart_type) {
            case 'ratings':
                $this->fetchVendorByIRR($client_id);
                break;
            case 'category':
                $this->fetchVendorByCategory($client_id);
                break;
            default:
                # code...
                break;
        }
        $vendor_count = Vendor::where('client_id', $client_id)->count();
        $approved_vendors = Vendor::where(['client_id' => $client_id])
            ->where('second_approval', '!=', NULL)
            ->where('second_approval', 'LIKE', '%"Approve"%')
            ->count();
        $rejected_vendors = Vendor::where(['client_id' => $client_id])
            ->where('second_approval', '!=', NULL)
            ->where('second_approval', 'LIKE', '%"Reject"%')
            ->count();
        $pending_approval = Vendor::where(['client_id' => $client_id])
            ->where('second_approval', '=', NULL)
            ->count();

        $ratings_data = Vendor::where(['client_id' => $client_id])
            ->select(\DB::raw('COUNT(CASE WHEN inherent_risk_rating = 1 THEN vendors.id END ) as lows'), \DB::raw('COUNT(CASE WHEN inherent_risk_rating = 2 THEN vendors.id END ) as mediums'), \DB::raw('COUNT(CASE WHEN inherent_risk_rating = 3 THEN vendors.id END ) as highs'))
            ->get();
        $vendor_ratings_series = collect($ratings_data->toArray())->flatten()->all();

        $categories = Category::orderBy('slug')->get();
        $vendor_categories = [];
        $data = [];
        $vendor_grouped_categories = Vendor::leftJoin('categories', 'categories.id', '=', 'vendors.category_id')
            ->where(['client_id' => $client_id])
            ->select('vendors.id', 'category_id', 'slug')
            ->get()
            ->groupBy('slug');

        foreach ($vendor_grouped_categories as $key => $value) {
            $vendor_categories[] = ($key !== '') ? $key : 'Uncategorized';
            $data[] = count($value);
        }
        $vendor_categories_series = [
            [
                'name' => 'Count',
                'data' => $data
            ]
        ];
        return response()->json(compact('vendor_count', 'approved_vendors', 'rejected_vendors', 'pending_approval', 'vendor_categories_series', 'vendor_ratings_series', 'vendor_categories', 'categories'), 200);
    }
    public function vendorOnboardingReport(Request $request)
    {
        $client_id = $this->getClient()->id;
        $chart_type = $request->chart_type;
        switch ($chart_type) {
            case 'ratings':
                return $this->fetchVendorByIRR($client_id);
            case 'category':
                return $this->fetchVendorByCategory($client_id);
            case 'approval':
                return $this->fetchVendorByApprovalStatus($client_id);

            default:
                # code...
                break;
        }
    }
    private function fetchVendorByIRR($client_id)
    {

        $ratings_data = Vendor::where(['client_id' => $client_id])
            ->select(\DB::raw('COUNT(CASE WHEN inherent_risk_rating = 1 THEN vendors.id END ) as low'), \DB::raw('COUNT(CASE WHEN inherent_risk_rating = 2 THEN vendors.id END ) as medium'), \DB::raw('COUNT(CASE WHEN inherent_risk_rating = 3 THEN vendors.id END ) as high'))
            ->get();
        // $vendor_ratings_series = collect($ratings_data->toArray())->flatten()->all();
        $ratings = $ratings_data[0];
        $data = [
            [
                'name' => 'Low',
                'y' => $ratings->low,
                'selected' => true
            ],
            [
                'name' => 'Medium',
                'y' => $ratings->medium
            ],
            [
                'name' => 'High',
                'y' => $ratings->high
            ]
        ];
        $series = [
            [
                'name' => 'Ratings',
                'colorByPoint' => true,
                'data' => $data,
            ]
        ];
        // data: [{
//             name: 'Low',
//             y: 23.9
//         }, {
//             name: 'Medium',
//             y: 12.6
//         }, {
//             name: 'High',
//             y: 26.4
//         }]

        return response()->json(compact('series'), 200);
    }
    private function fetchVendorByApprovalStatus($client_id)
    {

        $approved_vendors = Vendor::where(['client_id' => $client_id])
            ->where('second_approval', '!=', NULL)
            ->where('second_approval', 'LIKE', '%"Approve"%')
            ->count();
        $rejected_vendors = Vendor::where(['client_id' => $client_id])
            ->where('second_approval', '!=', NULL)
            ->where('second_approval', 'LIKE', '%"Reject"%')
            ->count();
        $pending_approval = Vendor::where(['client_id' => $client_id])
            ->where('second_approval', '=', NULL)
            ->count();

        // $series = [$pending_approval, $rejected_vendors, $approved_vendors];
        // $ratings = $ratings_data[0];
        $data = [
            [
                'name' => 'Pending',
                'y' => $pending_approval
            ],
            [
                'name' => 'Rejected',
                'y' => $rejected_vendors
            ],
            [
                'name' => 'Approved',
                'y' => $approved_vendors,
                'selected' => true
            ]
        ];
        $series = [
            [
                'name' => 'Approval',
                'colorByPoint' => true,
                'data' => $data,
            ]
        ];
        return response()->json(compact('series'), 200);
    }

    private function fetchVendorByCategory($client_id)
    {

        $vendor_categories = [];
        $data = [];
        $vendor_grouped_categories = Vendor::leftJoin('categories', 'categories.id', '=', 'vendors.category_id')
            ->where(['vendors.client_id' => $client_id])
            ->select('vendors.id', 'category_id', 'slug')
            ->get()
            ->groupBy('slug');

        foreach ($vendor_grouped_categories as $key => $value) {
            $vendor_categories[] = ($key !== '') ? $key : 'Uncategorized';
            $data[] = count($value);
        }
        $vendor_categories_series = [
            [
                'name' => 'Count',
                'colorByPoint' => false,
                'data' => $data
            ]
        ];

        return response()->json(compact('vendor_categories_series', 'vendor_categories'), 200);
    }
    public function vendorInvoicesAnalysis(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $total_invoices = Invoice::where(['client_id' => $client_id, 'vendor_id' => $vendor_id])->count();
        $pending_invoices = Invoice::where(['client_id' => $client_id, 'vendor_id' => $vendor_id, 'status' => 'Pending'])->count();
        $overdue_invoices = Invoice::where(['client_id' => $client_id, 'vendor_id' => $vendor_id, 'status' => 'Overdue'])->count();
        $paid_invoices = Invoice::where(['client_id' => $client_id, 'vendor_id' => $vendor_id, 'status' => 'Paid'])->count();
        return response()->json(compact('total_invoices', 'pending_invoices', 'overdue_invoices', 'paid_invoices'), 200);
    }
    public function vendorTicketsAnalysis(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $total_tickets = Ticket::where(['client_id' => $client_id, 'vendor_id' => $vendor_id])->count();
        $open_tickets = Ticket::where(['client_id' => $client_id, 'vendor_id' => $vendor_id, 'status' => 'Open'])->count();
        $in_progress_tickets = Ticket::where(['client_id' => $client_id, 'vendor_id' => $vendor_id, 'status' => 'In Progress'])->count();
        $resolved_tickets = Ticket::where(['client_id' => $client_id, 'vendor_id' => $vendor_id, 'status' => 'Resolved'])->count();
        return response()->json(compact('total_tickets', 'open_tickets', 'in_progress_tickets', 'resolved_tickets'), 200);
    }

    public function vendorRiskAssessmentAnalysis(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $groupedResponses = RiskAssessment::join('due_diligence_questions', 'due_diligence_questions.id', '=', 'risk_assessments.due_diligence_question_id')
            ->groupBy('due_diligence_questions.domain')
            ->where(['risk_assessments.vendor_id' => $vendor_id, 'risk_assessments.client_id' => $client_id])
            ->where('revised_risk_score', '>', 0)
            ->select('domain', \DB::raw('COUNT(CASE WHEN revised_risk_level = "Very High" THEN risk_assessments.id END ) as very_high'), \DB::raw('COUNT(CASE WHEN revised_risk_level = "High" THEN risk_assessments.id END ) as high'), \DB::raw('COUNT(CASE WHEN revised_risk_level = "Medium" THEN risk_assessments.id END ) as medium'), \DB::raw('COUNT(CASE WHEN revised_risk_level = "Low" THEN risk_assessments.id END ) as low'))
            ->get()
            ->groupBy('domain');
        $risk_categories = [];
        $lows = [];
        $mediums = [];
        $highs = [];
        $very_highs = [];
        foreach ($groupedResponses as $domain => $riskScores) {
            $risk_categories[] = $domain;
            $very_highs[] = $riskScores[0]->very_high;
            $highs[] = $riskScores[0]->high;
            $mediums[] = $riskScores[0]->medium;
            $lows[] = $riskScores[0]->low;
        }
        $series = [
            [
                'name' => 'Low',
                'data' => $lows
            ],
            [
                'name' => 'Medium',
                'data' => $mediums
            ],
            [
                'name' => 'High',
                'data' => $highs
            ],
        ];
        return response()->json(compact('risk_categories', 'series'), 200);
    }
    public function calculateEnterpriseRiskScore(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $condition = ['client_id' => $client_id, 'vendor_id' => $vendor_id];
        $risk_assessments = RiskAssessment::where($condition)
            ->where('revised_risk_score', '>', 0)->get();

        $impact_rating_count = 0;
        $likelihood_rating_count = 0;
        $risk_score_count = 0;
        $count = count($risk_assessments);
        $overall_impact_rating = 0;
        $overall_likelihood_rating = 0;
        $average_risk_score = 0;
        if ($count > 0) {
            foreach ($risk_assessments as $risk_assessment) {
                $impact_rating_count += $risk_assessment->revised_impact_of_occurence;
                $likelihood_rating_count += $risk_assessment->revised_likelihood_of_occurence;
                $risk_score_count += $risk_assessment->revised_risk_score;
                $average_risk_score = ($risk_assessment->revised_risk_score > $average_risk_score) ? $risk_assessment->revised_risk_score : $average_risk_score;
            }
            $overall_impact_rating = sprintf("%.1f", $impact_rating_count / $count);
            $overall_likelihood_rating = sprintf("%.1f", $likelihood_rating_count / $count);
            // $average_risk_score = sprintf("%.1f", $risk_score_count / $count);
        }
        $severity_distribution = RiskAssessment::where(['client_id' => $client_id])
            ->where($condition)
            ->where('revised_risk_score', '>', 0)
            ->select(\DB::raw('COUNT(CASE WHEN revised_risk_level = "Very High" THEN risk_assessments.id END ) as very_high'), \DB::raw('COUNT(CASE WHEN revised_risk_level = "High" THEN risk_assessments.id END ) as high'), \DB::raw('COUNT(CASE WHEN revised_risk_level = "Medium" THEN risk_assessments.id END ) as medium'), \DB::raw('COUNT(CASE WHEN revised_risk_level = "Low" THEN risk_assessments.id END ) as low'))
            ->first();
        $effectiveness_level = RiskAssessment::where(['client_id' => $client_id])
            ->where($condition)
            ->select(\DB::raw('COUNT(CASE WHEN control_effectiveness_level = "Effective" THEN risk_assessments.id END ) as effective'), \DB::raw('COUNT(CASE WHEN control_effectiveness_level = "Ineffective" THEN risk_assessments.id END ) as ineffective'), \DB::raw('COUNT(CASE WHEN control_effectiveness_level = "Sub-optimal" THEN risk_assessments.id END ) as sub_optimal'))
            ->first();
        $risk_severity_series = [
            [
                'name' => 'Risk Severity',
                'colors' => ['green', 'yellow', /*'#FFFF00',*/ 'red'],
                'data' => [
                    // ['Very High', $severity_distribution->very_high],

                    ['Low', $severity_distribution->low],
                    ['Medium', $severity_distribution->medium],
                    ['High', $severity_distribution->high],
                ], //array format
                'colorByPoint' => true,
                'groupPadding' => 0,
            ],
        ];
        $effectiveness_series = [
            [
                'name' => 'Control Effectiveness',
                'data' => [
                    ['name' => 'Effective', 'y' => $effectiveness_level->effective, 'selected' => true],
                    ['name' => 'Ineffective', 'y' => $effectiveness_level->ineffective],
                    ['name' => 'Sub-optimal', 'y' => $effectiveness_level->sub_optimal],
                ],
            ],
        ];
        $risk_matrix = RiskMatrix::where('client_id', $client_id)->first();
        $matrix = $risk_matrix->current_matrix;
        $overall_risk_level = analyzeRiskCategory($average_risk_score, $matrix);
        return response()->json(compact('risk_assessments', 'overall_impact_rating', 'overall_likelihood_rating', 'average_risk_score', 'risk_severity_series', 'effectiveness_series', 'overall_risk_level'), 200);
    }
    public function vendorContractsAnalysis(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::find($vendor_id);
        $client_id = $vendor->client_id;
        $total_contracts = Contract::where(['client_id' => $client_id, 'vendor_id' => $vendor_id])->count();
        $active_contracts = Contract::where(['client_id' => $client_id, 'vendor_id' => $vendor_id, 'status' => 'Active'])->count();
        $renewed_contracts = Contract::where(['client_id' => $client_id, 'vendor_id' => $vendor_id, 'status' => 'Renewed'])->count();
        $expired_contracts = Contract::where(['client_id' => $client_id, 'vendor_id' => $vendor_id, 'status' => 'Expired'])->count();
        return response()->json(compact('total_contracts', 'active_contracts', 'renewed_contracts', 'expired_contracts'), 200);
    }
}
