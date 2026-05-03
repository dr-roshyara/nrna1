<?php

namespace App\Contexts\Geography\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Geography Hierarchy API Resource
 *
 * Transforms complete country hierarchy to JSON:API format.
 * Used for full hierarchy responses from GeographyService.
 */
class GeographyHierarchyResource extends JsonResource
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
            'type' => 'geography_hierarchy',
            'id' => $this->resource['country']['code'] ?? null,
            'attributes' => [
                'country' => [
                    'code' => $this->resource['country']['code'] ?? null,
                    'name' => $this->getLocalizedName($this->resource['country']['name_local'] ?? [], $language),
                    'name_en' => $this->resource['country']['name'] ?? null,
                ],
                'levels' => $this->formatLevels($language),
            ],
            'meta' => [
                'total_levels' => count($this->resource['levels'] ?? []),
                'total_units' => $this->calculateTotalUnits(),
                'language' => $language,
            ],
        ];
    }

    /**
     * Format hierarchy levels for API response.
     *
     * @param string $language
     * @return array
     */
    protected function formatLevels(string $language): array
    {
        if (!isset($this->resource['levels'])) {
            return [];
        }

        $formatted = [];
        foreach ($this->resource['levels'] as $level => $data) {
            $formatted[] = [
                'level' => (int) $level,
                'name' => $data['name'] ?? null,
                'local_name' => $data['local_name'] ?? null,
                'expected_count' => $data['expected_count'] ?? null,
                'actual_count' => $data['actual_count'] ?? 0,
                'completion_percentage' => $this->calculateCompletion($data),
                'units' => $this->formatUnits($data['units'] ?? [], $language),
            ];
        }

        return $formatted;
    }

    /**
     * Format units for API response.
     *
     * @param array $units
     * @param string $language
     * @return array
     */
    protected function formatUnits(array $units, string $language): array
    {
        return array_map(function ($unit) use ($language) {
            return [
                'id' => $unit['id'] ?? null,
                'code' => $unit['code'] ?? null,
                'name' => $this->getLocalizedName($unit['name_local'] ?? [], $language),
                'admin_type' => $unit['admin_type'] ?? null,
                'parent_id' => $unit['parent_id'] ?? null,
            ];
        }, $units);
    }

    /**
     * Get localized name from name_local array.
     *
     * @param array $nameLocal
     * @param string $language
     * @return string|null
     */
    protected function getLocalizedName(array $nameLocal, string $language): ?string
    {
        return $nameLocal[$language] ?? $nameLocal['en'] ?? null;
    }

    /**
     * Calculate completion percentage for a level.
     *
     * @param array $data
     * @return float
     */
    protected function calculateCompletion(array $data): float
    {
        $expected = $data['expected_count'] ?? 0;
        $actual = $data['actual_count'] ?? 0;

        if ($expected === 0) {
            return 0.0;
        }

        return round(($actual / $expected) * 100, 2);
    }

    /**
     * Calculate total units across all levels.
     *
     * @return int
     */
    protected function calculateTotalUnits(): int
    {
        $total = 0;
        foreach ($this->resource['levels'] ?? [] as $data) {
            $total += $data['actual_count'] ?? 0;
        }
        return $total;
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
