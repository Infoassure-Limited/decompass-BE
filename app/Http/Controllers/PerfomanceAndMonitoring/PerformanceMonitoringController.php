<?php

namespace App\Http\Controllers\PerfomanceAndMonitoring;

use App\Http\Controllers\Controller;
use App\Http\Requests\PerformanceMonitoringRequest;
use App\Models\PerfomanceAndMonitoring\PerformanceMonitoring;
use Illuminate\Support\Facades\DB;

class PerformanceMonitoringController extends Controller
{
    public function assignMeasures(PerformanceMonitoringRequest $req)
    {
        $clientId = $this->getClient()->id;

        DB::beginTransaction();

        try {
            $results = [];

            foreach ($req->measures as $m) {
                $record = PerformanceMonitoring::updateOrCreate(
                    [
                        'client_id' => $clientId,
                        'info_need_id' => $m['info_need_id'],
                        'applicable_objective_id' => $req->applicable_objective_id,
                        'measure_id' => $m['measure_id']
                    ],
                    [
                        'unit' => $m['unit'] ?? null,
                        'formula' => $m['formula'] ?? null,
                        'target' => $m['target'] ?? null,
                        'settings' => $m['settings'] ?? null,
                    ]
                );

                $results[] = $record->load('measure.infoNeed');
            }

            DB::commit();

            return response()->json($results, 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => 'error', 'detail' => $e->getMessage()], 500);
        }
    }

    public function getOrgMeasures($organizationId)
    {
        return PerformanceMonitoring::with('measure.infoNeed')
            ->where('client_id', $organizationId)
            ->get();
    }
}
