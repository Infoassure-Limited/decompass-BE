<?php

namespace App\Http\Controllers\Policy;

use App\Http\Controllers\Controller;
use App\Models\ISMS\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidentDashboardController extends Controller
{
    public function getStats()
    {
        // Incident statistics
        $incidentStats = [
            'total' => Incident::count(),
            'by_status' => Incident::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray(),
            'by_severity' => Incident::select('severity', DB::raw('count(*) as count'))
                ->groupBy('severity')
                ->get()
                ->pluck('count', 'severity')
                ->toArray(),
            'recent' => Incident::orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($incident) {
                    return [
                        'id' => $incident->id,
                        'title' => $incident->title,
                        'status' => $incident->status,
                        'severity' => $incident->severity,
                        'created_at' => $incident->created_at,
                    ];
                }),
        ];

        return response()->json([
            'incidents' => $incidentStats
        ]);
    }
}