<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Election;
use App\Models\VoterSlug;
use App\Models\Organisation;

class CacheService
{
    /**
     * Cache TTL for different data types (in seconds)
     */
    private const CACHE_TTL = [
        'election' => 3600,        // 1 hour
        'voter_slug' => 300,       // 5 minutes
        'organisation' => 3600,    // 1 hour
        'active_election' => 300,  // 5 minutes
    ];

    // ============ ELECTION CACHING ============

    /**
     * Get an election from cache, or fetch and cache it
     */
    public function getElection(int $id): ?Election
    {
        return Cache::remember("election.{$id}", self::CACHE_TTL['election'], function() use ($id) {
            return Election::withoutGlobalScopes()
                ->withEssentialRelations()
                ->find($id);
        });
    }

    /**
     * Get election by slug
     */
    public function getElectionBySlug(string $slug): ?Election
    {
        return Cache::remember("election.slug.{$slug}", self::CACHE_TTL['election'], function() use ($slug) {
            return Election::withoutGlobalScopes()
                ->withEssentialRelations()
                ->where('slug', $slug)
                ->first();
        });
    }

    /**
     * Clear election cache
     */
    public function clearElection(int $id): void
    {
        Cache::forget("election.{$id}");
        Cache::forget("election.slug.{$id}");

        // Also clear organisation election lists
        $election = Election::find($id);
        if ($election) {
            Cache::forget("elections.org.{$election->organisation_id}");
            Cache::forget("elections.active.org.{$election->organisation_id}");
        }
    }

    /**
     * Get all elections for an organisation
     */
    public function getElectionsForOrganisation(int $orgId, bool $includePlatform = true): array
    {
        $cacheKey = "elections.org.{$orgId}." . ($includePlatform ? 'with_platform' : 'org_only');

        return Cache::remember($cacheKey, self::CACHE_TTL['election'], function() use ($orgId, $includePlatform) {
            $query = Election::withoutGlobalScopes()
                ->where('organisation_id', $orgId);

            if ($includePlatform) {
                $query->orWhere('organisation_id', 0);
            }

            return $query->orderBy('status')
                ->orderBy('start_date', 'desc')
                ->get()
                ->toArray();
        });
    }

    /**
     * Get active election for an organisation
     */
    public function getActiveElectionForOrganisation(int $orgId): ?Election
    {
        return Cache::remember("elections.active.org.{$orgId}", self::CACHE_TTL['active_election'], function() use ($orgId) {
            return Election::withoutGlobalScopes()
                ->withEssentialRelations()
                ->where('status', 'active')
                ->where(function($q) use ($orgId) {
                    $q->where('organisation_id', $orgId)
                      ->orWhere('organisation_id', 0);
                })
                ->first();
        });
    }

    // ============ VOTER SLUG CACHING ============

    /**
     * Get voter slug from cache with all relationships
     */
    public function getVoterSlug(string $slug): ?VoterSlug
    {
        return Cache::remember("voter_slug.{$slug}", self::CACHE_TTL['voter_slug'], function() use ($slug) {
            return VoterSlug::withEssentialRelations()
                ->where('slug', $slug)
                ->first();
        });
    }

    /**
     * Get voter slug with all relationships (full version)
     */
    public function getVoterSlugFull(string $slug): ?VoterSlug
    {
        return Cache::remember("voter_slug.full.{$slug}", self::CACHE_TTL['voter_slug'], function() use ($slug) {
            return VoterSlug::withAllRelations()
                ->where('slug', $slug)
                ->first();
        });
    }

    /**
     * Clear voter slug cache
     */
    public function clearVoterSlug(string $slug): void
    {
        Cache::forget("voter_slug.{$slug}");
        Cache::forget("voter_slug.full.{$slug}");
    }

    /**
     * Clear voter slug cache by ID
     */
    public function clearVoterSlugById(int $id): void
    {
        $slug = VoterSlug::find($id)?->slug;
        if ($slug) {
            $this->clearVoterSlug($slug);
        }
    }

    // ============ ORGANISATION CACHING ============

    /**
     * Get organisation from cache
     */
    public function getOrganisation(int $id): ?Organisation
    {
        return Cache::remember("organisation.{$id}", self::CACHE_TTL['organisation'], function() use ($id) {
            return Organisation::find($id);
        });
    }

    /**
     * Clear organisation cache
     */
    public function clearOrganisation(int $id): void
    {
        Cache::forget("organisation.{$id}");
    }

    // ============ BATCH PRELOADING ============

    /**
     * Preload all elections for an organisation into cache
     */
    public function preloadElectionsForOrganisation(int $orgId): void
    {
        $elections = Election::withoutGlobalScopes()
            ->where('organisation_id', $orgId)
            ->orWhere('organisation_id', 0)
            ->get();

        foreach ($elections as $election) {
            Cache::put("election.{$election->id}", $election, self::CACHE_TTL['election']);
        }

        Cache::put("elections.org.{$orgId}.with_platform", $elections->toArray(), self::CACHE_TTL['election']);
    }

    /**
     * Preload voter slugs for a user
     */
    public function preloadVoterSlugsForUser(int $userId): void
    {
        $slugs = VoterSlug::where('user_id', $userId)
            ->where('is_active', true)
            ->get();

        foreach ($slugs as $slug) {
            Cache::put("voter_slug.{$slug->slug}", $slug, self::CACHE_TTL['voter_slug']);
        }
    }

    // ============ CACHE INVALIDATION ============

    /**
     * Flush all voting-related caches
     */
    public function flushAllVotingCaches(): void
    {
        Cache::tags(['voting'])->flush();
    }

    /**
     * Flush caches for a specific organisation
     */
    public function flushOrganisationCaches(int $orgId): void
    {
        Cache::forget("elections.org.{$orgId}.*");
        Cache::forget("elections.active.org.{$orgId}");
    }

    /**
     * Flush caches for a specific election
     */
    public function flushElectionCaches(int $electionId): void
    {
        $election = Election::find($electionId);
        if ($election) {
            $this->clearElection($electionId);
            $this->flushOrganisationCaches($election->organisation_id);
        }
    }
}
