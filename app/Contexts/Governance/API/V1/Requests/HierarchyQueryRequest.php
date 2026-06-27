<?php

declare(strict_types=1);

namespace App\Contexts\Governance\API\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HierarchyQueryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'depth' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'root' => ['sometimes', 'string', 'max:36'],
            'search' => ['sometimes', 'string', 'max:255'],
            'state' => ['sometimes', 'string', 'max:50'],
            'cursor' => ['sometimes', 'string', 'max:100'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function getDepth(): ?int
    {
        return $this->has('depth') ? (int) $this->get('depth') : null;
    }

    public function getRoot(): ?string
    {
        return $this->get('root');
    }

    public function getSearch(): ?string
    {
        return $this->get('search');
    }

    public function getState(): ?string
    {
        return $this->get('state');
    }

    public function getCursor(): ?string
    {
        return $this->get('cursor');
    }
}
