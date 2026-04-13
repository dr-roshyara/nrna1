<?php

namespace App\Services;

use App\Models\Election;
use Illuminate\Support\Facades\Cache;

class ElectionSettingsService
{
    /**
     * Get a setting value with three-tier fallback:
     * 1. Election database column
     * 2. Environment variable
     * 3. Default value
     */
    public function get(Election $election, string $key, $default = null)
    {
        $cacheKey = "election:{$election->id}:setting:{$key}";

        return Cache::remember($cacheKey, 300, function () use ($election, $key, $default) {
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
            default => null,
        };
    }

    /**
     * Get setting from environment variables
     */
    protected function getEnvSetting(string $key): mixed
    {
        return match ($key) {
            'max_use_ip_address'  => env('MAX_USE_IP_ADDRESS'),
            'control_ip_address'  => env('CONTROL_IP_ADDRESS'),
            'voting_ip_mode'      => env('VOTING_IP_MODE'),
            'ip_mismatch_action'  => env('IP_MISMATCH_ACTION'),
            'select_all_required' => env('SELECT_ALL_REQUIRED'),
            default => null,
        };
    }

    /**
     * Map election selection_constraint_type to legacy 'select_all_required' format
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
