<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Infrastructure\Repositories;

use App\Contexts\Geography\Domain\Repositories\FuzzyMatchingRepositoryInterface;
use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use Illuminate\Support\Facades\DB;

/**
 * EloquentFuzzyMatchingRepository
 *
 * PostgreSQL-based implementation using trigram similarity and custom functions.
 */
class EloquentFuzzyMatchingRepository implements FuzzyMatchingRepositoryInterface
{
    private const NEPAL_VARIATIONS = [
        'roshyara' => 'rosyara',
        'kathmandu' => 'kathmandu',
        'pokhara' => 'pokhara',
        'biratnagar' => 'birat nagar',
        'birgunj' => 'birgunj',
        'dharan' => 'dharan',
        'bharatpur' => 'bharat pur',
        'butwal' => 'butwal',
        'hetauda' => 'hetauda',
        'nepalgunj' => 'nepalgunj',
        'birgunj' => 'bir gunj',
    ];

    public function findSimilarNames(
        string $candidateName,
        CountryCode $countryCode,
        int $level,
        ?GeoUnitId $parentId = null,
        float $similarityThreshold = 0.3,
        int $limit = 10
    ): array {
        $normalizedName = $this->normalizeNepaliName($candidateName);

        $query = DB::table('geo_administrative_units')
            ->select([
                'id',
                'name_local',
                'admin_level',
                'parent_id',
                'country_code',
                DB::raw("similarity(name_local->>'np', ?) as similarity_score"),
                DB::raw("'postgres_trgm' as match_type")
            ])
            ->addBinding($normalizedName)
            ->where('country_code', $countryCode->toString())
            ->where('admin_level', $level)
            ->where(DB::raw("similarity(name_local->>'np', ?)"), '>=', $similarityThreshold)
            ->addBinding($normalizedName)
            ->orderBy('similarity_score', 'desc')
            ->limit($limit);

        if ($parentId !== null) {
            $query->where('parent_id', $parentId->toInt());
        }

        return $query->get()->all();
    }

    public function suggestCorrection(
        string $candidateName,
        CountryCode $countryCode,
        float $similarityThreshold = 0.7
    ): ?string {
        $normalizedName = $this->normalizeNepaliName($candidateName);

        $result = DB::table('geo_administrative_units')
            ->select('name_local')
            ->where('country_code', $countryCode->toString())
            ->whereRaw("similarity(name_local->>'np', ?) > ?", [$normalizedName, $similarityThreshold])
            ->orderByRaw("similarity(name_local->>'np', ?) DESC", [$normalizedName])
            ->first();

        return $result ? json_decode($result->name_local, true)['np'] ?? null : null;
    }

    public function normalizeNepaliName(string $name): string
    {
        $name = mb_strtolower(trim($name), 'UTF-8');

        // Remove common prefixes/suffixes
        $name = preg_replace('/^(municipality|rural municipality|metropolitan|sub-metropolitan)\s+/i', '', $name);
        $name = preg_replace('/\s+(municipality|nagar|gaunpalika|nagarpaalika|city|metropolitan city|sub-metropolitan city|rural municipality)$/i', '', $name);

        // Handle Nepal-specific variations
        foreach (self::NEPAL_VARIATIONS as $variant => $standard) {
            if (str_contains($name, $variant)) {
                $name = str_replace($variant, $standard, $name);
            }
        }

        // Remove extra spaces and special characters
        $name = preg_replace('/\s+/', ' ', $name);
        $name = preg_replace('/[^\p{L}\p{N}\s]/u', '', $name);

        return $name;
    }

    public function getNepalVariations(): array
    {
        return self::NEPAL_VARIATIONS;
    }
}