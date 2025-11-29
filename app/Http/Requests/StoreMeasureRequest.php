<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeasureRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'info_need_id' => 'required|exists:pam.info_needs,id',
            'measures' => 'required|array|min:1',
            'measures.*.title' => 'required|string|max:255',
            'measures.*.measurement_need' => 'nullable|string|max:255',
            'measures.*.metadata' => 'nullable|array',
        ];
    }
}
