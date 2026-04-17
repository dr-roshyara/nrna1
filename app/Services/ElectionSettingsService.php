<?php

namespace App\Services;

use App\Models\Election;
use Illuminate\Support\Facades\Cache;

class ElectionSettingsService
{
    private int $cacheTtl;

    public function __construct()
    {
        $this->cacheTtl = config('election.settings_cache_ttl', 300);
    }

    /**
     * Get a setting value with three-tier fallback:
     * 1. Election database column
     * 2. Environment variable
     * 3. Default value
     */
    public function get(Election $election, string $key, $default = null)
    {
        $cacheKey = "election:{$election->id}:setting:{$key}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($election, $key, $default) {
            return $this->getElectionSetting($election, $key)
                ?? $this->getEnvSetting($key)
                ?? $default;
        });
    }

    /**
     * Get setting from election database columns
     */
    protected function getElectionSetting(Election $election, string $key): mixed
    {
        return match ($key) {
            'max_use_ip_address'  => $election->ip_restriction_max_per_ip ?? null,
            'control_ip_address'  => $election->ip_restriction_enabled ?? null,
            'select_all_required' => $this->mapSelectionConstraint($election),
            'ip_mismatch_action'  => $election->ip_mismatch_action ?? null,
            'voting_ip_mode'      => $election->voting_ip_mode ?? null,
            'ip_whitelist'        => $election->ip_whitelist ?? null,
            'no_vote_enabled'     => $election->no_vote_option_enabled ?? null,
            'no_vote_label'       => $election->no_vote_option_label ?? null,
            default => null,
        };
    }

    /**
     * Get setting from environment variables with fallback defaults
     */
    protected function getEnvSetting(string $key): mixed
    {
        return match ($key) {
            'max_use_ip_address'  => env('MAX_USE_IP_ADDRESS', 4),
            'control_ip_address'  => env('CONTROL_IP_ADDRESS', false),
            'voting_ip_mode'      => env('VOTING_IP_MODE', 'strict'),
            'ip_mismatch_action'  => env('IP_MISMATCH_ACTION', 'block'),
            'select_all_required' => env('SELECT_ALL_REQUIRED', 'no'),
            default => null,
        };
    }

    /**
     * Map election selection_constraint_type to legacy 'select_all_required' format
     * Constrained types (exact, range, minimum) map to 'yes'
     * Flexible types (any, maximum) map to 'no'
     */
    protected function mapSelectionConstraint(Election $election): ?string
    {
        if (!$election->selection_constraint_type) {
            return null;
        }

        return match ($election->selection_constraint_type) {
            'exact', 'range', 'minimum' => 'yes',
            'any', 'maximum'            => 'no',
            default => null,
        };
    }
}
