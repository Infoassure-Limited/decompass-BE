<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerformanceMonitoringRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'client_id' => 'required|exists:mysql.clients,id',
            'info_need_id' => 'required|exists:pam.info_needs,id',
            'applicable_objective_id' => 'required|exists:pam.applicable_objectives,id',
            'frequency' => 'required|string|max:100',
            'measures' => 'required|array',
            'measures.*.measure_id' => 'required|exists:measures,id',
            'unit' => 'nullable|string|max:100',
            'formula' => 'nullable|string',
            'target' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
        ];
    }
}
