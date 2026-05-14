<?php

declare(strict_types=1);

namespace App\Http\Requests\Committee;

use Illuminate\Foundation\Http\FormRequest;

final class CreateCommitteeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'governanceLevel' => ['required', 'integer', 'min:0', 'max:10'],
            'geoUnitId' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Committee name is required.',
            'name.string' => 'Committee name must be a string.',
            'governanceLevel.required' => 'Governance level is required.',
            'governanceLevel.integer' => 'Governance level must be an integer.',
            'geoUnitId.required' => 'Geographic unit is required.',
            'geoUnitId.integer' => 'Geographic unit must be a valid ID.',
        ];
    }
}
