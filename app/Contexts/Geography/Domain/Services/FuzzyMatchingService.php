<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Services;

use App\Contexts\Geography\Domain\Repositories\FuzzyMatchingRepositoryInterface;
use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Geography\Domain\ValueObjects\PotentialMatches;
use App\Contexts\Geography\Domain\ValueObjects\MatchResult;
use App\Contexts\Geography\Domain\ValueObjects\SimilarityScore;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

/**
 * FuzzyMatchingService
 *
 * Service for finding potential matches for geographic names using fuzzy matching.
 * Combines multiple matching strategies and caches results.
 */
class FuzzyMatchingService
{
    private const CACHE_TTL = 3600; // 1 hour
    private const CACHE_PREFIX = 'fuzzy:match:';

    public function __construct(
        private FuzzyMatchingRepositoryInterface $repository,
        private CacheRepository $cache
    ) {}

    /**
     * Find potential matches for a candidate geography name
     *
     * @param string $candidateName The name to match
     * @param CountryCode $countryCode Limit to specific country
     * @param int $level Administrative level (1-8)
     * @param GeoUnitId|null $parentId Limit to children of specific parent
     * @return PotentialMatches Categorized matches
     */
    public function findPotentialMatches(
        string $candidateName,
        CountryCode $countryCode,
        int $level,
        ?GeoUnitId $parentId = null
    ): PotentialMatches {
        $cacheKey = $this->generateCacheKey($candidateName, $countryCode, $level, $parentId);

        // Try cache first
        $cached = $this->cache->get($cacheKey);
        if ($cached instanceof PotentialMatches) {
            return $cached;
        }

        // Get matches from repository
        $rawMatches = $this->repository->findSimilarNames(
            $candidateName,
            $countryCode,
            $level,
            $parentId
        );

        // Convert to MatchResult objects
        $matchResults = array_map(
            fn($row) => MatchResult::fromDatabaseRow($row),
            $rawMatches
        );

        // Create PotentialMatches value object
        $potentialMatches = PotentialMatches::fromMatchResults($matchResults);

        // Cache the result
        $this->cache->put($cacheKey, $potentialMatches, self::CACHE_TTL);

        return $potentialMatches;
    }

    /**
     * Suggest correction for a potentially misspelled name
     *
     * @param string $candidateName The potentially misspelled name
     * @param CountryCode $countryCode Limit to specific country
     * @return string|null Suggested correction, or null if no good match
     */
    public function suggestCorrection(
        string $candidateName,
        CountryCode $countryCode
    ): ?string {
        $cacheKey = $this->generateSuggestionCacheKey($candidateName, $countryCode);

        $cached = $this->cache->get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        $suggestion = $this->repository->suggestCorrection($candidateName, $countryCode);

        // Cache even null results (negative caching)
        $this->cache->put($cacheKey, $suggestion, self::CACHE_TTL);

        return $suggestion;
    }

    /**
     * Normalize Nepal-specific geography name
     *
     * @param string $name Raw geography name
     * @return string Normalized name
     */
    public function normalizeNepaliName(string $name): string
    {
        return $this->repository->normalizeNepaliName($name);
    }

    /**
     * Get Nepal-specific name variations
     *
     * @return array<string, string> Map of variation => standard form
     */
    public function getNepalVariations(): array
    {
        return $this->repository->getNepalVariations();
    }

    /**
     * Clear cache for specific search
     */
    public function clearCache(
        string $candidateName,
        CountryCode $countryCode,
        int $level,
        ?GeoUnitId $parentId = null
    ): void {
        $cacheKey = $this->generateCacheKey($candidateName, $countryCode, $level, $parentId);
        $this->cache->forget($cacheKey);

        $suggestionKey = $this->generateSuggestionCacheKey($candidateName, $countryCode);
        $this->cache->forget($suggestionKey);
    }

    /**
     * Clear all fuzzy matching caches
     */
    public function clearAllCache(): void
    {
        $keys = $this->cache->get(self::CACHE_PREFIX . 'keys', []);
        foreach ($keys as $key) {
            $this->cache->forget($key);
        }
        $this->cache->forget(self::CACHE_PREFIX . 'keys');
    }

    /**
     * Generate cache key for match search
     */
    private function generateCacheKey(
        string $candidateName,
        CountryCode $countryCode,
        int $level,
        ?GeoUnitId $parentId
    ): string {
        $keyData = [
            'name' => $candidateName,
            'country' => $countryCode->toString(),
            'level' => $level,
            'parent' => $parentId?->toInt(),
        ];

        $key = self::CACHE_PREFIX . 'match:' . md5(serialize($keyData));

        // Track key for bulk clearance
        $this->trackCacheKey($key);

        return $key;
    }

    /**
     * Generate cache key for suggestion
     */
    private function generateSuggestionCacheKey(string $candidateName, CountryCode $countryCode): string
    {
        $keyData = [
            'name' => $candidateName,
            'country' => $countryCode->toString(),
            'type' => 'suggestion',
        ];

        $key = self::CACHE_PREFIX . 'suggestion:' . md5(serialize($keyData));

        // Track key for bulk clearance
        $this->trackCacheKey($key);

        return $key;
    }

    /**
     * Track cache key for bulk clearance
     */
    private function trackCacheKey(string $key): void
    {
        $keys = $this->cache->get(self::CACHE_PREFIX . 'keys', []);
        if (!in_array($key, $keys, true)) {
            $keys[] = $key;
            $this->cache->put(self::CACHE_PREFIX . 'keys', $keys, self::CACHE_TTL * 2);
        }
    }
}