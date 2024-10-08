<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPreferenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'preferred_categories' => 'nullable|array',
            'preferred_categories.*' => 'nullable|integer|exists:categories,id|distinct',
            //
            'preferred_sources' => 'nullable|array',
            'preferred_sources.*' => 'nullable|integer|exists:news_sources,id|distinct',
            //
            'preferred_authors' => 'nullable|array',
            'preferred_authors.*' => 'nullable|integer|exists:authors,id|distinct',
        ];
    }
}
