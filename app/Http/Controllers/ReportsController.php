<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\AvailableModule;
use App\Models\BCMS\BusinessImpactAnalysis;
use App\Models\BusinessProcess;
use App\Models\BusinessUnit;
use App\Models\Client;
use App\Models\RiskMatrix;
use App\Models\RiskRegister;
use App\Models\Upload;
use App\Models\Exception;
use App\Models\Project;
use App\Models\RiskAssessment;
use App\Models\SOAArea;
use App\Models\Standard;
use App\Models\StatementOfApplicability;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function clientDashboardStatistics(Request $request)
    {
        // $year = date('Y', strtotime('now'));
        $client = $this->getClient();
        $my_projects = $this->getMyProjects($client->id);
        $all_projects_count = $my_projects->count();
        $completed_projects = $my_projects->where('is_completed', 1)
            // ->where('created_at', 'LIKE', '%' . $year . '%')
            ->count();

        $in_progress = $all_projects_count - $completed_projects;
        $all_projects = $my_projects;
        // foreach ($all_projects as $project) {
        //     $project->watchProjectProgress($project);
        // }
        return response()->json(compact('client', 'all_projects', 'all_projects_count', 'completed_projects', 'in_progress'), 200);
    }
    public function clientDataAnalysisDashbord(Request $request)
    {
        // $year = date('Y', strtotime('now'));
        $client = $this->getClient();
        $my_projects = $this->getMyProjects($client->id);
        // $uploaded_documents = Upload::where(['client_id' => $client->id, 'is_exception' => 0])->where('link', '!=', NULL)->count();
        // $expected_documents = Upload::where(['client_id' => $client->id])->count();
        // $answered_questions = Answer::where(['client_id' => $client->id, 'is_exception' => 0])->where('is_submitted', 1)->count();
        // $all_questions = Answer::where(['client_id' => $client->id])->count();
        // $exceptions = Exception::where('client_id', $client->id)->count();
        $all_projects_count = $my_projects->count();
        $completed_projects = $my_projects->where('is_completed', 1)
            // ->where('created_at', 'LIKE', '%' . $year . '%')
            ->count();

        $in_progress = $all_projects_count - $completed_projects;
        $all_projects = $my_projects;
        // foreach ($all_projects as $project) {
        //     $project->watchProjectProgress($project);
        // }
        return response()->json(compact('client', 'all_projects', 'all_projects_count', 'completed_projects', 'in_progress'), 200);
    }

    /**
     * Validate client/project scope and enforce ownership.
     * Throws Illuminate\Validation\ValidationException for invalid input.
     * Returns array with validated keys.
     */
    private function validateClientScope(Request $request, array $rules = [])
    {
        // Basic validation first
        $data = $request->validate($rules);

        // Enforce that if client_id is supplied it matches current client's id
        if (isset($data['client_id'])) {
            $currentClient = $this->getClient();
            if (!$currentClient || (int) $data['client_id'] !== (int) $currentClient->id) {
                abort(403, 'Forbidden client scope');
            }
        }

        // If project_id provided, ensure project exists and belongs to client
        if (isset($data['project_id'])) {
            $project = Project::findOrFail($data['project_id']);
            $clientIdToCheck = $data['client_id'] ?? $this->getClient()->id;
            if ((int) $project->client_id !== (int) $clientIdToCheck) {
                abort(403, 'Project does not belong to client');
            }
        }

        return $data;
    }

    public function clientProjectDataAnalysis(Request $request)
    {
        $data = $this->validateClientScope($request, [
            'client_id' => 'nullable|integer|exists:clients,id',
            'project_id' => 'required',
        ]);

        // allow 'all' or integer id for project_id
        if ($request->project_id !== 'all') {
            $request->validate(['project_id' => 'required|integer|exists:projects,id']);
        }

        // guard against returning very large result sets
        $maxAllowed = 5000;
        $projectIds = $this->getMyProjects($this->getClient()->id)->pluck('id');
        if ($projectIds->count() > $maxAllowed) {
            abort(413, 'Request would return too many projects; narrow the scope');
        }

        // ...existing logic follows (unchanged)...
        $client_id = $request->client_id;
        $project_id = $request->project_id;
        if ($project_id === 'all') {
            $expectedDocumentProjectIds = $this->getMyProjects($client_id)->where('allow_document_uploads', 1)->pluck('id');
            $projectIds = $this->getMyProjects($client_id)->pluck('id');
            $condition = ['client_id' => $client_id];

            $uploaded_documents = Upload::where($condition)->whereIn('project_id', $expectedDocumentProjectIds)
                // ->where('is_exception', 0)
                ->where('link', '!=', NULL)
                ->count();

            $expected_documents = Upload::where($condition)->whereIn('project_id', $expectedDocumentProjectIds)->count();
            $answered_questions = Answer::where($condition)
                ->whereIn('project_id', $projectIds)
                ->where('is_exception', 0)
                ->where(function ($q) {
                    $q->where('yes_or_no', '!=', NULL);
                    $q->orWhere('open_ended_answer', '!=', NULL);
                })
                // ->where('status', 'Closed')
                ->count();
            $all_questions = Answer::where($condition)->whereIn('project_id', $projectIds)->count();
            $exceptions = Exception::where($condition)->whereIn('project_id', $projectIds)->count();

            $my_projects = $this->getUser()->projects()->where($condition)->groupBy('client_id')->select(\DB::raw('AVG(progress) as project_progress'))->first();
            $project_progress = $my_projects->project_progress;
        } else {
            $project = Project::find($project_id);
            $project_progress = $project->progress;
            $expected_documents = 0;
            $condition = ['client_id' => $client_id];
            $uploaded_documents = Upload::where($condition)
                // ->where('is_exception', 0)
                ->where('link', '!=', NULL)
                ->count();
            if ($project->allow_document_uploads == 1) {
                $expected_documents = Upload::where($condition)->count();
            }

            $answered_questions = Answer::where($condition)
                ->where('is_exception', 0)
                ->where(function ($q) {
                    $q->where('yes_or_no', '!=', NULL);
                    $q->orWhere('open_ended_answer', '!=', NULL);
                })
                // ->where('status', 'Closed')
                ->count();
            $all_questions = Answer::where($condition)->count();
            $exceptions = Exception::where($condition)->count();
        }


        return response()->json(compact('uploaded_documents', 'expected_documents', 'answered_questions', 'all_questions', 'exceptions', 'project_progress'), 200);
    }

    public function clientProjectAssessmentSummaryReport(Request $request)
    {
        $data = $this->validateClientScope($request, [
            'client_id' => 'required|integer|exists:clients,id',
            'project_id' => 'required|integer|exists:projects,id',
        ]);

        // safe query using validated data
        $client_id = $data['client_id'];
        $project_id = $data['project_id'];

        $reports = Answer::join('clauses', 'clauses.id', '=', 'answers.clause_id')
            ->where(['client_id' => $client_id, 'project_id' => $project_id])
            ->orderBy('clauses.sort_by')
            ->select(\DB::raw('COUNT(CASE WHEN consultant_grade = "Conformity" THEN answers.id END ) as conformity'), \DB::raw('COUNT(CASE WHEN consultant_grade = "Non-Conformity" THEN answers.id END ) as non_conformity'), \DB::raw('COUNT(CASE WHEN consultant_grade = "Not Applicable" THEN answers.id END ) as not_applicable'), \DB::raw('COUNT(CASE WHEN consultant_grade = "Opportunity For Improvement" THEN answers.id END ) as open_for_imporvement'))
            ->first();

        return response()->json(compact('reports'), 200);
    }

    public function clientProjectManagementClauseReport(Request $request)
    {
        $data = $this->validateClientScope($request, [
            'client_id' => 'required|integer|exists:clients,id',
            'project_id' => 'required|integer|exists:projects,id',
        ]);

        $client_id = $data['client_id'];
        $project_id = $data['project_id'];

        $reports = Answer::join('clauses', 'clauses.id', '=', 'answers.clause_id')
            ->groupBy('clause_id')
            ->where(['client_id' => $client_id, 'project_id' => $project_id])
            ->orderBy('clauses.sort_by')
            ->select('clauses.name', \DB::raw('COUNT(*) as total'), \DB::raw('COUNT(CASE WHEN consultant_grade = "Conformity" THEN answers.id END ) as conformity'), \DB::raw('COUNT(CASE WHEN consultant_grade = "Non-Conformity" THEN answers.id END ) as non_conformity'), \DB::raw('COUNT(CASE WHEN consultant_grade = "Not Applicable" THEN answers.id END ) as not_applicable'), \DB::raw('COUNT(CASE WHEN consultant_grade = "Opportunity For Improvement" THEN answers.id END ) as open_for_imporvement'))
            ->get();

        $categories = [];
        $conformity = [];
        $non_conformity = [];
        $not_applicable = [];
        $open_for_imporvement = [];
        foreach ($reports as $report) {
            $total = $report->total;
            $categories[] = $report->name;
            $conformity[] = [
                'name' => $report->name,
                'y' => ($report->conformity > 0) ? $report->conformity : 0
            ];
            $non_conformity[] = [
                'name' => $report->name,
                'y' => ($report->non_conformity > 0) ? $report->non_conformity : 0
            ];

            $not_applicable[] = [
                'name' => $report->name,
                'y' => ($report->not_applicable > 0) ? $report->not_applicable : 0
            ];

            $open_for_imporvement[] = [
                'name' => $report->name,
                'y' => ($report->open_for_imporvement > 0) ? $report->open_for_imporvement : 0
            ];
        }
        $series = [
            [
                'name' => 'Conformity',
                'data' => $conformity, //array format
                'color' => '#00A65A',
                'stack' => 'Management Clause',
                'dataLabels' => [
                    'enabled' => true,
                ],
            ],
            [
                'name' => 'Non Conformity',
                'data' => $non_conformity, //array format
                'color' => '#F00C12',
                'stack' => 'Management Clause',
                'dataLabels' => [
                    'enabled' => true,
                ],
            ],
            [
                'name' => 'Opportunity For Improvement',
                'data' => $open_for_imporvement, //array format
                'color' => '#FFA500',
                'stack' => 'Management Clause',
                'dataLabels' => [
                    'enabled' => true,
                ],
            ],
            [
                'name' => 'N/A',
                'data' => $not_applicable, //array format
                'color' => '#cccccc',
                'stack' => 'Management Clause',
                'dataLabels' => [
                    'enabled' => true,
                ],
            ],
        ];
        $project = Project::with('standard')->find($project_id);
        $subtitle = ''; // $project->standard->name;
        return response()->json(compact('categories', 'series', 'subtitle'), 200);
    }
    // public function clientAssessmentSummaryCombinedChart(Request $request)
    // {
    //     $client_id = $request->client_id;
    //     $project_id = $request->project_id;
    //     $reports = Answer::join('clauses', 'clauses.id', '=', 'answers.clause_id')
    //         ->groupBy('clause_id')
    //         ->where(['client_id' => $client_id, 'project_id' => $project_id])
    //         // ->where('is_submitted', 1)
    //         ->orderBy('clauses.sort_by')
    //         ->select('clauses.name', \DB::raw('COUNT(*) as total'), \DB::raw('COUNT(CASE WHEN consultant_grade = "Conformity" THEN answers.id END ) as conformity'), \DB::raw('COUNT(CASE WHEN consultant_grade = "Non-Conformity" THEN answers.id END ) as non_conformity'), \DB::raw('COUNT(CASE WHEN consultant_grade = "Not Applicable" THEN answers.id END ) as not_applicable'), \DB::raw('COUNT(CASE WHEN consultant_grade = "Opportunity For Improvement" THEN answers.id END ) as open_for_imporvement'), \DB::raw('COUNT(CASE WHEN status = "Closed" AND is_exception = 0 THEN answers.id END ) as answered_questions'), \DB::raw('COUNT(*) as all_questions'))
    //         ->get();
    //     $categories = [];
    //     $conformity_count = 0;
    //     $non_conformity_count = 0;
    //     $not_applicable_count = 0;
    //     $open_for_imporvement_count = 0;
    //     $conformity = [];
    //     $non_conformity = [];
    //     $not_applicable = [];
    //     $open_for_imporvement = [];
    //     $completion_data = [];
    //     foreach ($reports as $report) {
    //         $total = $report->total;

    //         $conformity_count += $report->conformity;
    //         $non_conformity_count += $report->non_conformity;
    //         $not_applicable_count += $report->not_applicable;
    //         $open_for_imporvement_count += $report->open_for_imporvement;

    //         $categories[] = $report->name;
    //         $conformity[] = [
    //             'name' => $report->name,
    //             'y' => ($report->conformity > 0) ? $report->conformity : 0
    //         ];
    //         $non_conformity[] = [
    //             'name' => $report->name,
    //             'y' => ($report->non_conformity > 0) ? $report->non_conformity : 0
    //         ];

    //         $not_applicable[] = [
    //             'name' => $report->name,
    //             'y' => ($report->not_applicable > 0) ? $report->not_applicable : 0
    //         ];

    //         $open_for_imporvement[] = [
    //             'name' => $report->name,
    //             'y' => ($report->open_for_imporvement > 0) ? $report->open_for_imporvement : 0
    //         ];

    //         $total_response = $report->answered_questions;
    //         $total_task = $report->all_questions;

    //         $percentage_progress = 0;
    //         if ($total_task > 0) {
    //             $percentage_progress = $total_response / $total_task * 100;
    //         }
    //         $completion_data[] = [
    //             $report->name,
    //             // (float) sprintf('%0.1f', $percentage_progress)
    //             (int) $percentage_progress
    //         ];
    //     }
    //     $series = [
    //         [
    //             'type' => 'column',
    //             'name' => 'Conformity',
    //             'data' => $conformity, //array format
    //             'color' => '#00a65a',
    //             'stack' => 'Management Clause',
    //             'dataLabels' => [
    //                 'enabled' => true,
    //             ],
    //         ],
    //         [
    //             'type' => 'column',
    //             'name' => 'Non Conformity',
    //             'data' => $non_conformity, //array format
    //             'color' => '#f00c12',
    //             'stack' => 'Management Clause',
    //             'dataLabels' => [
    //                 'enabled' => true,
    //             ],
    //         ],
    //         [
    //             'type' => 'column',
    //             'name' => 'Opportunity For Improvement',
    //             'data' => $open_for_imporvement, //array format
    //             'color' => '#FFA500',
    //             'stack' => 'Management Clause',
    //             'dataLabels' => [
    //                 'enabled' => true,
    //             ],
    //         ],
    //         [
    //             'type' => 'column',
    //             'name' => 'N/A',
    //             'data' => $not_applicable, //array format
    //             'color' => '#cccccc',
    //             'stack' => 'Management Clause',
    //             'dataLabels' => [
    //                 'enabled' => true,
    //             ],
    //         ],
    //         [
    //             'type' => 'pie',
    //             'name' => 'Total Count',
    //             'colorByPoint' => true,
    //             'dataLabels' => [
    //                 'enabled' => false,
    //             ],
    //             'center' => [100, 100],
    //             'size' => 100,
    //             'innerSize' => '50%',
    //             'showInLegend' => false,
    //             'data' => [
    //                 [
    //                     'name' => 'Conformity',
    //                     'y' => $conformity_count,
    //                     'color' => '#00a65a',
    //                 },
    //                 [
    //                     'name' => 'Non Conformity',
    //                     'y' => $non_conformity_count,
    //                     'color' => '#f00c12',
    //                 },
    //                 [
    //                     'name' => 'Open For Improvement',
    //                     'y' => $not_applicable_count,
    //                     'color' => '#FFA500',
    //                 },
    //                 [
    //                     'name' => 'N/A',
    //                     'y' => $open_for_imporvement_count,
    //                     'color' => '#cccccc',
    //                 ]
    //             ],
    //         ],
    //     ];
    //     $project = Project::with('standard')->find($project_id);
    //     $subtitle = $project->standard->name;
    //     return response()->json(compact('categories', 'series', 'subtitle'), 200);
    // }
    public function clientProjectRequirementCompletionReport(Request $request)
    {
        $data = $this->validateClientScope($request, [
            'client_id' => 'required|integer|exists:clients,id',
            'project_id' => 'required|integer|exists:projects,id',
        ]);

        $client_id = $data['client_id'];
        $project_id = $data['project_id'];

        $clauses = Answer::groupBy('clause_id')
            ->join('clauses', 'clauses.id', '=', 'answers.clause_id')
            ->where(['client_id' => $client_id, 'project_id' => $project_id])
            ->orderBy('clauses.sort_by')
            ->select('clauses.name', 'clauses.id', \DB::raw('COUNT(CASE WHEN status = "Closed" AND is_exception = 0 THEN answers.id END ) as answered_questions'), \DB::raw('COUNT(*) as all_questions'), )
            ->get();

        $data = [];
        $cumulative_task = 0;
        $cumulative_response = 0;
        $total_progress = 0;
        foreach ($clauses as $clause) {
            $clause_id = $clause->id;

            $total_response = $clause->answered_questions;
            $total_task = $clause->all_questions;

            $cumulative_task += $total_task;
            $cumulative_response += $total_response;
            $percentage_progress = 0;
            if ($total_task > 0) {
                $percentage_progress = ($total_response / $total_task) * 100;
            }
            $data[] = [
                $clause->name,
                // (float) sprintf('%0.1f', $percentage_progress)
                (int) $percentage_progress
            ];
            // $clause->progress = (float) sprintf('%0.1f', $percentage_progress);
        }
        if ($cumulative_task > 0) {
            $total_progress = ($cumulative_response / $cumulative_task) * 100;
        }
        $total_progress = (int) $total_progress;
        $project = Project::with('standard')->find($project_id);
        $subtitle = ''; //$project->standard->name;
        return response()->json(compact('data', 'subtitle', 'total_progress'), 200);
    }

    public function partnerDataAnalysisDashbord()
    {
        $partner_id = $this->getPartner()->id;
        $clients = Client::where('partner_id', $partner_id)->count();
        $projects = Project::where('partner_id', $partner_id)->count();
        // $uploads = Upload::where('is_exception', 0)->where('link', '!=', NULL)->count();
        return response()->json(compact('clients', 'projects'), 200);
    }
    public function adminDataAnalysisDashbord()
    {
        $client = $this->getClient();
        // $uploaded_documents = Upload::where('is_exception', 0)->where('link', '!=', NULL)->count();
        // $expected_documents = Upload::count();
        // $answered_questions = Answer::where('is_exception', 0)->where('is_submitted', 1)->count();
        // $all_questions = Answer::count();
        // $exceptions = Exception::count();
        // return response()->json(compact('uploaded_documents', 'expected_documents', 'answered_questions', 'all_questions', 'exceptions', 'clients', 'projects', 'standards'), 200);
        $users = $client->users()->count();
        $projects = Project::where('client_id', $client->id)->count();
        $uploads = Upload::where('client_id', $client->id)
            ->where('is_exception', 0)
            ->where('link', '!=', NULL)->count();
        $project_completion = 0;
        $assets = Asset::where('client_id', $client->id)->count();
        $business_units = BusinessUnit::where('client_id', $client->id)->count();
        $busness_processes = BusinessProcess::where('client_id', $client->id)->count();
        $available_modules = AvailableModule::where('status', 'Ready')->orderBy('name')->get();
        return response()->json(
            compact(
                'users',
                'projects',
                'uploads',
                'project_completion',
                'available_modules',
                'assets',
                'business_units',
                'busness_processes'
            ),
            200
        );
    }

    public function soaSummary(Request $request)
    {
        $client_id = $this->getClient()->id;

        $reports = SOAArea::with('controls')->orderBy('name')->get();
        $controls = [];
        $categories = [];
        $applicable = [];
        $implemented = [];
        $tabular_presentations = [];
        foreach ($reports as $report) {
            $iso_controls = count($report->controls);
            $categories[] = $report->name;
            $applicable_control = StatementOfApplicability::where([
                'client_id' => $client_id,
                // 'standard_id' => $standard_id,
                's_o_a_area_id' => $report->id
            ])->where('applicable', 'Yes')->count();
            $implemented_control = StatementOfApplicability::where([
                'client_id' => $client_id,
                // 'standard_id' => $standard_id,
                's_o_a_area_id' => $report->id
            ])->where('applicable', 'Yes')->whereIn('implemented', ['Fully Implemented'/*, 'Partially Implemented'*/])->count();

            $controls[] = [
                'name' => $report->name,
                'y' => $iso_controls,
            ];
            $applicable[] = [
                'name' => $report->name,
                'y' => $applicable_control,
            ];
            $implemented[] = [
                'name' => $report->name,
                'y' => $implemented_control,
            ];
            $percent_control_applicable = ($iso_controls > 0) ? $applicable_control / $iso_controls * 100 : 0;
            $percent_control_implemented = ($applicable_control > 0) ? $implemented_control / $applicable_control * 100 : 0;
            $report->no_of_controls = $iso_controls;
            $report->applicable_controls = $applicable_control;
            $report->implemented_controls = $implemented_control;
            $report->percent_control_applicable = (float) sprintf('%0.1f', $percent_control_applicable);
            $report->percent_control_implemented = (float) sprintf('%0.1f', $percent_control_implemented);
            $tabular_presentations[] = $report;
        }
        $series = [
            [
                'name' => 'Number of ISO/IEC 27001 Controls',
                'data' => $controls, //array format
                // 'color' => '#00a65a',
            ],
            [
                'name' => 'Number of Applicable Controls',
                'data' => $applicable, //array format
                // 'color' => '#00a65a',
            ],
            [
                'name' => 'Number of Applicable Controls Implemented',
                'data' => $implemented, //array format
                // 'color' => '#f00c12',
            ],
        ];
        $subtitle = '';
        return response()->json(compact('categories', 'series', 'subtitle', 'tabular_presentations'), 200);
    }

    public function riskAssessmentSummary(Request $request)
    {
        $data = $this->validateClientScope($request, [
            'client_id' => 'required|integer|exists:clients,id',
            'standard_id' => 'required|integer|exists:standards,id'
        ]);
        $client_id = $data['client_id'];
        $standard_id = $data['standard_id'];

        $summary = RiskAssessment::join('asset_types', 'asset_types.id', '=', 'risk_assessments.asset_type_id')
            ->join('risk_registers', 'risk_registers.id', 'risk_assessments.risk_register_id')
            ->groupBy('asset')
            ->where(['risk_assessments.client_id' => $client_id, 'risk_assessments.standard_id' => $standard_id])
            ->select('asset_types.name as asset_type', 'risk_owner', 'asset', \DB::raw('COUNT(*) as no_of_threats'), \DB::raw('COUNT(CASE WHEN risk_category = "Low" THEN risk_assessments.id END ) as lows'), \DB::raw('COUNT(CASE WHEN risk_category = "Medium" THEN risk_assessments.id END ) as mediums'), \DB::raw('COUNT(CASE WHEN risk_category = "High" THEN risk_assessments.id END ) as highs'))
            ->get();

        return response()->json(compact('summary'), 200);
    }

    public function fetchProjectAnswers(Request $request)
    {
        $data = $this->validateClientScope($request, [
            'project_id' => 'required|integer|exists:projects,id',
            'standard_id' => 'nullable|integer|exists:standards,id',
        ]);

        $project_id = $data['project_id'];

        $assessment_answers = Answer::with(['client', 'clause', 'standard', 'question'])
            ->join('clauses', 'clauses.id', '=', 'answers.clause_id')
            ->where(['project_id' => $project_id])
            ->orderBy('clauses.sort_by')
            ->select('answers.*')
            ->get();

        return response()->json(compact('assessment_answers'), 200);
    }

    public function assetRiskAnalysis(Request $request)
    {
        $data = $this->validateClientScope($request, [
            'client_id' => 'required|integer|exists:clients,id',
        ]);
        $client_id = $data['client_id'];

        // guard: if many grouped items would be returned, ask caller to paginate/filter
        $groupCount = RiskRegister::where(['client_id' => $client_id, 'module' => 'isms'])->whereNotNull('asset_type_id')->count();
        if ($groupCount > 5000) {
            abort(413, 'Too many assets to analyze at once');
        }

        $categories = [];
        $total_low = 0;
        $total_medium = 0;
        $total_high = 0;
        $low = [];
        $medium = [];
        $high = [];
        $dataLabels = [
            'enabled' => true,
            // 'rotation' => 0,
            'color' => '#FFFFFF',
            'align' => 'center',
            //format: '{point.y:.1f}', // one decimal
            // 'y' => 25, // 10 pixels down from the top
            'style' => [
                'fontSize' => '10px',
                'fontFamily' => 'Verdana, sans-serif'
            ]
        ];

        $drilldown_low_series = [];
        $drilldown_medium_series = [];
        $drilldown_high_series = [];
        $total_risk_scores = 0;
        $count_risk_score = 0;
        // $asset_types = AssetType::with('assets')->where('client_id', $client_id)->get();
        $grouped_risk_registers = RiskRegister::with('assetType', 'asset')->where(['client_id' => $client_id, 'module' => 'isms'])
            ->where('asset_type_id', '!=', NULL)->get()->groupBy('asset_type_id');
        foreach ($grouped_risk_registers as $asset_type_id => $risk_registers):
            $asset_type = $risk_registers[0]->assetType;
            $categories[] = $asset_type->name;
            $risk_assessment = RiskAssessment::groupBy('asset_type_id')
                ->where(['client_id' => $client_id, 'asset_type_id' => $asset_type_id, 'module' => 'isms'])
                ->select(\DB::raw('COUNT(CASE WHEN risk_level = "Low" THEN risk_assessments.id END ) as low'), \DB::raw('COUNT(CASE WHEN risk_level = "Medium" THEN risk_assessments.id END ) as medium'), \DB::raw('COUNT(CASE WHEN risk_level = "High" THEN risk_assessments.id END ) as high'), \DB::raw('SUM(risk_score) as total_risk_score'))
                ->first();
            if ($risk_assessment) {
                $total_low += $risk_assessment->low;
                $total_medium += $risk_assessment->medium;
                $total_high += $risk_assessment->high;
                $current_risk_score = (int) $risk_assessment->total_risk_score;
                if ($current_risk_score > 0) {

                    $total_risk_scores += $current_risk_score;
                    $count_risk_score++;
                }
                $low[] = [
                    'name' => $asset_type->name,
                    'y' => (int) $risk_assessment->low,
                    'drilldown' => $asset_type_id . '_low',

                ];

                $medium[] = [
                    'name' => $asset_type->name,
                    'y' => (int) $risk_assessment->medium,
                    'drilldown' => $asset_type_id . '_medium',

                ];

                $high[] = [
                    'name' => $asset_type->name,
                    'y' => (int) $risk_assessment->high,
                    'drilldown' => $asset_type_id . '_high',

                ];

            }
            $drilldown_series_low = [];
            $drilldown_series_medium = [];
            $drilldown_series_high = [];
            $unique_asset_ids = [];
            foreach ($risk_registers as $risk_register) {
                $asset = $risk_register->asset;
                if ($asset) {
                    if (!in_array($asset->id, $unique_asset_ids)) {
                        $asset_risk_assessment = RiskAssessment::groupBy('asset_id')
                            ->where(['client_id' => $client_id, 'asset_id' => $asset->id, 'module' => 'isms'])
                            ->where('asset_type_id', '!=', NULL)
                            ->select(\DB::raw('COUNT(CASE WHEN risk_level = "Low" THEN risk_assessments.id END ) as low'), \DB::raw('COUNT(CASE WHEN risk_level = "Medium" THEN risk_assessments.id END ) as medium'), \DB::raw('COUNT(CASE WHEN risk_level = "High" THEN risk_assessments.id END ) as high'))
                            ->first();
                        if ($asset_risk_assessment) {
                            $drilldown_series_low[] = [$asset->name, (int) $asset_risk_assessment->low];
                            $drilldown_series_medium[] = [$asset->name, (int) $asset_risk_assessment->medium];
                            $drilldown_series_high[] = [$asset->name, (int) $asset_risk_assessment->high];
                        }

                        $unique_asset_ids[] = $asset->id;
                    }
                }


            }
            $drilldown_low_series[] = [
                'name' => 'Low',
                "id" => $asset_type_id . '_low',
                "data" => $drilldown_series_low,
                //'dataLabels' => $dataLabels
            ];
            $drilldown_medium_series[] = [
                'name' => 'Medium',
                "id" => $asset_type_id . '_medium',
                "data" => $drilldown_series_medium,
                //'dataLabels' => $dataLabels
            ];
            $drilldown_high_series[] = [
                'name' => 'High',
                "id" => $asset_type_id . '_high',
                "data" => $drilldown_series_high,
                // 'dataLabels' => $dataLabels
            ];

        endforeach;
        $risk_score = 0;
        $risk_level = '';
        $risk_matrix = RiskMatrix::firstOrCreate(['client_id' => $client_id]);
        $matrix = $risk_matrix->current_matrix;
        if ($count_risk_score > 0) {
            $risk_score = (int) ($total_risk_scores / $count_risk_score);
            list($risk_level, $color) = analyzeRiskCategory($risk_score, $matrix);
        }
        $drilldown_series = array_merge($drilldown_low_series, $drilldown_medium_series, $drilldown_high_series);
        $series = [
            [
                'name' => 'Low',
                'data' => $low, //array format
                'color' => '#3BD135',
                // 'dataLabels' => $dataLabels
            ],
            [
                'name' => 'Medium',
                'data' => $medium, //array format
                'color' => '#FFFF00',
                // 'dataLabels' => $dataLabels
            ],
            [
                'name' => 'High',
                'data' => $high, //array format
                'color' => '#DD2C2C',
                // 'dataLabels' => $dataLabels
            ],
        ];
        // $school = School::find($school_id);
        return response()->json([

            'categories' => $categories,
            'series' => $series,
            'title' => 'Asset Risk Analysis',
            'subtitle' => 'Drilldown to Assets by clicking the columns',
            'drilldown_series' => $drilldown_series,
            'total_low' => $total_low,
            'total_medium' => $total_medium,
            'total_high' => $total_high,
            'risk_level' => $risk_level,
            'risk_score' => $risk_score,
            'matrix' => $matrix,
        ], 200);
    }
    public function processRiskAnalysis(Request $request)
    {
        $data = $this->validateClientScope($request, [
            'client_id' => 'required|integer|exists:clients,id',
            'module' => 'nullable|string',
        ]);
        $module_condition = [];
        if (isset($request->module) && $request->module != 'all') {
            $module_condition = ['module' => $request->module];
        }
        $client_id = $data['client_id'];

        // restrict module values if provided
        if ($request->filled('module') && !in_array($request->module, ['isms', 'bcms', 'ndpa', 'rcsa', 'all'])) {
            abort(422, 'Invalid module');
        }

        // guard large datasets
        $groupCount = RiskRegister::where(['client_id' => $client_id])->whereNotNull('business_unit_id')->count();
        if ($groupCount > 5000) {
            abort(413, 'Too many business units to analyze at once');
        }

        $categories = [];
        $total_low = 0;
        $total_medium = 0;
        $total_high = 0;

        $total_effective = 0;
        $total_ineffective = 0;
        $total_sub_optimal = 0;

        $low = [];
        $medium = [];
        $high = [];
        $dataLabels = [
            'enabled' => true,
            // 'rotation' => 0,
            'color' => '#FFFFFF',
            'align' => 'center',
            //format: '{point.y:.1f}', // one decimal
            // 'y' => 25, // 10 pixels down from the top
            'style' => [
                'fontSize' => '10px',
                'fontFamily' => 'Verdana, sans-serif'
            ]
        ];

        $drilldown_low_series = [];
        $drilldown_medium_series = [];
        $drilldown_high_series = [];
        $total_risk_scores = 0;
        $count_risk_score = 0;
        $grouped_risk_registers = RiskRegister::with('businessUnit', 'businessProcess')
            ->where(['client_id' => $client_id])
            ->where($module_condition)
            ->where('business_unit_id', '!=', NULL)
            ->where('business_unit_id', '!=', 0)
            ->get()
            ->groupBy('business_unit_id');
        foreach ($grouped_risk_registers as $business_unit_id => $risk_registers):
            $business_name = $risk_registers[0]->businessUnit->unit_name;
            $categories[] = $business_name;
            $risk_assessment = RiskAssessment::where(['client_id' => $client_id, 'business_unit_id' => $business_unit_id])->where($module_condition)
                ->select(
                    \DB::raw('COUNT(CASE WHEN revised_risk_level = "Low" THEN risk_assessments.id END ) as low'),
                    \DB::raw('COUNT(CASE WHEN revised_risk_level = "Medium" THEN risk_assessments.id END ) as medium'),
                    \DB::raw('COUNT(CASE WHEN revised_risk_level = "High" THEN risk_assessments.id END ) as high'),
                    \DB::raw('SUM(revised_risk_score) as total_risk_score'),
                    \DB::raw('COUNT(CASE WHEN control_effectiveness_level = "Effective" THEN risk_assessments.id END ) as effective'),
                    \DB::raw('COUNT(CASE WHEN control_effectiveness_level = "Ineffective" THEN risk_assessments.id END ) as ineffective'),
                    \DB::raw('COUNT(CASE WHEN control_effectiveness_level = "Sub-optimal" THEN risk_assessments.id END ) as sub_optimal')
                )
                ->first();

            $total_low += $risk_assessment->low;
            $total_medium += $risk_assessment->medium;
            $total_high += $risk_assessment->high;

            $total_effective += $risk_assessment->effective;
            $total_ineffective += $risk_assessment->ineffective;
            $total_sub_optimal += $risk_assessment->sub_optimal;


            $current_risk_score = (int) $risk_assessment->total_risk_score;
            if ($current_risk_score > 0) {

                $total_risk_scores += $current_risk_score;
                $count_risk_score++;
            }
            $low[] = [
                'name' => $business_name,
                'y' => (int) $risk_assessment->low,
                'drilldown' => $business_unit_id . '_low',

            ];

            $medium[] = [
                'name' => $business_name,
                'y' => (int) $risk_assessment->medium,
                'drilldown' => $business_unit_id . '_medium',

            ];

            $high[] = [
                'name' => $business_name,
                'y' => (int) $risk_assessment->high,
                'drilldown' => $business_unit_id . '_high',

            ];
            $drilldown_series_low = [];
            $drilldown_series_medium = [];
            $drilldown_series_high = [];
            $unique_business_process_ids = [];
            foreach ($risk_registers as $each_risk_register) {
                if (!in_array($each_risk_register->business_process_id, $unique_business_process_ids)) {
                    $business_process_name = $each_risk_register->businessProcess->name;
                    $process_risk_assessment = RiskAssessment::where(['client_id' => $client_id, 'business_process_id' => $each_risk_register->business_process_id])
                        ->where($module_condition)
                        ->select(\DB::raw('COUNT(CASE WHEN revised_risk_level = "Low" THEN risk_assessments.id END ) as low'), \DB::raw('COUNT(CASE WHEN revised_risk_level = "Medium" THEN risk_assessments.id END ) as medium'), \DB::raw('COUNT(CASE WHEN revised_risk_level = "High" THEN risk_assessments.id END ) as high'))
                        ->first();

                    $drilldown_series_low[] = [$business_process_name, (int) $process_risk_assessment->low];
                    $drilldown_series_medium[] = [$business_process_name, (int) $process_risk_assessment->medium];
                    $drilldown_series_high[] = [$business_process_name, (int) $process_risk_assessment->high];

                    $unique_business_process_ids[] = $each_risk_register->business_process_id;
                }
            }
            $drilldown_low_series[] = [
                'name' => 'Low',
                "id" => $business_unit_id . '_low',
                "data" => $drilldown_series_low,
                //'dataLabels' => $dataLabels
            ];
            $drilldown_medium_series[] = [
                'name' => 'Medium',
                "id" => $business_unit_id . '_medium',
                "data" => $drilldown_series_medium,
                //'dataLabels' => $dataLabels
            ];
            $drilldown_high_series[] = [
                'name' => 'High',
                "id" => $business_unit_id . '_high',
                "data" => $drilldown_series_high,
                // 'dataLabels' => $dataLabels
            ];
        endforeach;
        $risk_score = 0;
        $risk_level = '';
        $risk_matrix = RiskMatrix::firstOrCreate(['client_id' => $client_id]);
        $matrix = $risk_matrix->current_matrix;
        if ($count_risk_score > 0) {
            $risk_score = (int) ($total_risk_scores / $count_risk_score);
            list($risk_level, $color) = analyzeRiskCategory($risk_score, $matrix);
        }
        $drilldown_series = array_merge($drilldown_low_series, $drilldown_medium_series, $drilldown_high_series);
        $series = [
            [
                'name' => 'Low',
                'data' => $low, //array format
                'color' => '#3BD135',
                // 'dataLabels' => $dataLabels
            ],
            [
                'name' => 'Medium',
                'data' => $medium, //array format
                'color' => '#FFFF00',
                // 'dataLabels' => $dataLabels
            ],
            [
                'name' => 'High',
                'data' => $high, //array format
                'color' => '#DD2C2C',
                // 'dataLabels' => $dataLabels
            ],
        ];

        $severity_series = [
            [
                'name' => 'Risk Severity',
                'data' => [
                    ['name' => 'Low', 'y' => $total_low, 'color' => '#00a65a'],
                    ['name' => 'Medium', 'y' => $total_medium, 'color' => '#FFFF00'],
                    ['name' => 'High', 'y' => $total_high, 'color' => '#DD2C2C'],
                ],
            ],
        ];
        $effectiveness_series = [
            [
                'name' => 'Control Effectiveness',
                'data' => [
                    ['name' => 'Effective', 'y' => $total_effective],
                    ['name' => 'Ineffective', 'y' => $total_ineffective],
                    ['name' => 'Sub-optimal', 'y' => $total_sub_optimal],
                ],
            ],
        ];
        // $school = School::find($school_id);
        return response()->json([

            'categories' => $categories,
            'series' => $series,
            'title' => 'DPIA Risk Analysis',
            'subtitle' => 'Drilldown to Business Process Analysis by clicking the columns',
            'drilldown_series' => $drilldown_series,
            'total_low' => $total_low,
            'total_medium' => $total_medium,
            'total_high' => $total_high,
            'risk_level' => $risk_level,
            'risk_score' => $risk_score,
            'effectiveness_series' => $effectiveness_series,
            'severity_series' => $severity_series,
            'matrix' => $matrix,
        ], 200);
    }
    public function dataAnalysisBIA(Request $request)
    {
        $data = $this->validateClientScope($request, [
            'business_unit_id' => 'required|integer|exists:business_units,id',
        ]);
        $client_id = $this->getClient()->id;
        $business_unit_id = $data['business_unit_id'];

        $categories = [];
        $impact_areas = [];
        $total_critical = 0;
        $total_monitor = 0;
        $total_non_critical = 0;

        $drilldown_series = [];

        $bias = BusinessImpactAnalysis::with('businessUnit', 'businessProcess', 'impacts')
            ->where(['client_id' => $client_id, 'business_unit_id' => $business_unit_id])
            ->get();

        foreach ($bias as $bia):
            $business_process_name = $bia->businessProcess->name;
            $categories[] = $business_process_name;

            $priority = $bia->priority;
            $critical = 0;
            $monitor = 0;
            $non_critical = 0;
            switch ($priority) {
                case 'Critical':
                    $total_critical++;
                    $critical++;
                    break;
                case 'Monitor':
                    $total_monitor++;
                    $monitor++;
                    break;
                case 'Non-Critical':
                    $total_non_critical++;
                    $non_critical++;
                    break;
            }
            $impacts = $bia->impacts;

            $d_i_drilldown = [];
            foreach ($impacts as $impact) {
                $criteria = $impact->criteria;
                $process_disruption_impacts = $impact->process_disruption_impact;
                $impact_areas[$criteria][] = [
                    'name' => $business_process_name,
                    'y' => (int) $impact->impact_score,
                    'drilldown' => $criteria . '_' . $impact->business_impact_analysis_id,

                ];
                foreach ($process_disruption_impacts as $process_disruption_impact) {
                    $d_i_name = $process_disruption_impact['name'];
                    $d_i_value = $process_disruption_impact['value'];
                    $d_i_drilldown[$criteria . '_' . $impact->business_impact_analysis_id][] = [$d_i_name, (int) $d_i_value];
                }
            }



            // $count_pie = 1;
            // $count_drill_down_data = count($d_i_drilldown);
            foreach ($d_i_drilldown as $key => $data) {
                $drilldown_series[] = [
                    'type' => 'column',
                    'name' => $key,
                    "id" => $key,
                    'data' => $data,
                ];


            }
        endforeach;
        $series = [];
        foreach ($impact_areas as $key => $data) {
            $series[] = [
                'type' => 'column',
                'name' => $key,
                'data' => $data,
            ];
        }
        $pie_chart_series = [
            [
                'name' => 'Priority Count',
                'colorByPoint' => true,
                'data' => [
                    [
                        'name' => 'Critical',
                        'y' => $total_critical,
                        'color' => '#D14C42',
                        'drilldown' => 'Critical',
                    ],
                    [
                        'name' => 'Monitor',
                        'y' => $total_monitor,
                        'color' => '#FFFF00',
                        'drilldown' => 'Monitor',
                    ],
                    [
                        'name' => 'Non-Critical',
                        'y' => $total_non_critical,
                        'color' => '#00a65a',
                        'drilldown' => 'Non-Critical',
                    ]
                ],
            ],
        ];
        return response()->json([

            'categories' => $categories,
            'series' => $series,
            'title' => 'BIA Data Analysis',
            'subtitle' => 'Drilldown to Business Process Analysis by clicking the columns',
            'pie_chart_series' => $pie_chart_series,
            'drilldown_series' => $drilldown_series,
        ], 200);
    }
}
