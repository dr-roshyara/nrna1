<?php

declare(strict_types=1);

namespace App\Http\Requests\Committee;

use Illuminate\Foundation\Http\FormRequest;

final class StoreCommitteeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:100',
                'unique:committees,code,NULL,id,organisation_id,' . $this->route('organisation')->id,
            ],
            'governanceLevel' => 'required|integer|between:0,100',
            'geoUnitId' => 'required|integer|exists:geo_administrative_units,id',
        ];
    }
}
