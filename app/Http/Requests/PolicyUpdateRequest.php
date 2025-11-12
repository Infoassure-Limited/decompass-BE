<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PolicyUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;  // Authorization handled by policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'document_number' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('sec_policies.policies')->ignore($this->policy->id)
            ],
            'category_id' => 'sometimes|required|exists:policy_categories,id',
            'status' => 'sometimes|required|in:draft,review,approved,published,archived',
            'effective_date' => 'nullable|date',
            'review_date' => 'nullable|date|after_or_equal:effective_date',
            'expiry_date' => 'nullable|date|after_or_equal:effective_date',
            'change_summary' => 'sometimes|nullable|string',
        ];
    }
}
