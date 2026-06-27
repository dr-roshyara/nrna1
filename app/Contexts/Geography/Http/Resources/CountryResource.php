<?php

namespace App\Contexts\Geography\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Country API Resource
 *
 * Transforms Country model to JSON:API format.
 * Includes admin hierarchy configuration and metadata.
 */
class CountryResource extends JsonResource
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
            'type' => 'country',
            'id' => $this->code,
            'attributes' => [
                'code' => $this->code,
                'code_alpha3' => $this->code_alpha3,
                'code_numeric' => $this->code_numeric,
                'name' => $this->getName($language),
                'name_local' => $this->name_local,
                'name_en' => $this->name_en,
                'phone_code' => $this->phone_code,
                'currency_code' => $this->currency_code,
                'capital' => $this->capital_en,
                'is_active' => $this->is_active,
                'is_supported' => $this->is_supported,
                'admin_levels' => $this->formatAdminLevels($language),
                'validation_rules' => [
                    'id_documents' => $this->id_validation_rules,
                    'phone' => $this->phone_validation_rules,
                ],
            ],
            'meta' => [
                'created_at' => $this->created_at?->toIso8601String(),
                'updated_at' => $this->updated_at?->toIso8601String(),
            ],
        ];
    }

    /**
     * Format admin levels for API response.
     *
     * @param string $language
     * @return array
     */
    protected function formatAdminLevels(string $language): array
    {
        if (!$this->admin_levels) {
            return [];
        }

        $formatted = [];
        foreach ($this->admin_levels as $level => $config) {
            $formatted[$level] = [
                'level' => (int) $level,
                'name' => $config['name'] ?? null,
                'local_name' => $config['local_name'] ?? null,
                'expected_count' => $config['count'] ?? null,
            ];
        }

        return $formatted;
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
