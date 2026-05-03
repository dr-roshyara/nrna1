<?php

namespace App\Contexts\Geography\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Administrative Unit API Resource
 *
 * Transforms GeoAdministrativeUnit model to JSON:API format.
 * Includes hierarchical relationships and metadata.
 */
class AdministrativeUnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $language = $request->header('Accept-Language', 'en');

        return [
            'type' => 'administrative_unit',
            'id' => $this->id,
            'attributes' => [
                'code' => $this->code,
                'local_code' => $this->local_code,
                'country_code' => $this->country_code,
                'admin_level' => $this->admin_level,
                'admin_type' => $this->admin_type,
                'name' => $this->getName($language),
                'name_local' => $this->name_local,
                'metadata' => $this->metadata,
                'is_active' => $this->is_active,
                'path' => $this->path,
                'full_path' => $this->getFullPath($language),
            ],
            'relationships' => [
                'parent' => [
                    'data' => $this->parent_id ? [
                        'type' => 'administrative_unit',
                        'id' => $this->parent_id,
                    ] : null,
                ],
                'country' => [
                    'data' => [
                        'type' => 'country',
                        'id' => $this->country_code,
                    ],
                ],
            ],
            'meta' => [
                'created_at' => $this->created_at?->toIso8601String(),
                'updated_at' => $this->updated_at?->toIso8601String(),
            ],
        ];
    }

    /**
     * Get additional data for JSON:API envelope.
     *
     * @param Request $request
     * @return array
     */
    public function with(Request $request): array
    {
        return [
            'jsonapi' => [
                'version' => '1.0',
            ],
        ];
    }
}
