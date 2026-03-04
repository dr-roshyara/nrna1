<?php

namespace App\Observers;

use App\Models\UserOrganisationRole;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * UserOrganisationObserver
 *
 * Responsible for invalidating caches when user-organisation relationships change.
 * Ensures dashboard routing stays fresh after role/permission changes.
 *
 * Pattern: When users gain/lose organisation roles, their cached dashboard
 * resolution must be invalidated so they're routed correctly on next login.
 */
class UserOrganisationObserver
{
    /**
     * Handle the UserOrganisationRole "created" event.
     * Triggers when a user is assigned to an organisation.
     *
     * @param UserOrganisationRole $pivot
     * @return void
     */
    public function created(UserOrganisationRole $pivot): void
    {
        $this->invalidateUserCaches($pivot->user_id);

        Log::channel(config('login-routing.analytics.channel', 'login'))
            ->info('User assigned to organisation - caches invalidated', [
                'user_id' => $pivot->user_id,
                'organisation_id' => $pivot->organisation_id,
                'role' => $pivot->role,
                'timestamp' => now()->toIso8601String(),
            ]);
    }

    /**
     * Handle the UserOrganisationRole "updated" event.
     * Triggers when a user's role within an organisation changes.
     *
     * @param UserOrganisationRole $pivot
     * @return void
     */
    public function updated(UserOrganisationRole $pivot): void
    {
        $this->invalidateUserCaches($pivot->user_id);

        Log::channel(config('login-routing.analytics.channel', 'login'))
            ->info('User organisation role changed - caches invalidated', [
                'user_id' => $pivot->user_id,
                'organisation_id' => $pivot->organisation_id,
                'old_role' => $pivot->getOriginal('role'),
                'new_role' => $pivot->role,
                'timestamp' => now()->toIso8601String(),
            ]);
    }

    /**
     * Handle the UserOrganisationRole "deleted" event.
     * Triggers when a user is removed from an organisation.
     *
     * @param UserOrganisationRole $pivot
     * @return void
     */
    public function deleted(UserOrganisationRole $pivot): void
    {
        $this->invalidateUserCaches($pivot->user_id);

        Log::channel(config('login-routing.analytics.channel', 'login'))
            ->info('User removed from organisation - caches invalidated', [
                'user_id' => $pivot->user_id,
                'organisation_id' => $pivot->organisation_id,
                'role' => $pivot->role,
                'timestamp' => now()->toIso8601String(),
            ]);
    }

    /**
     * Handle the UserOrganisationRole "restored" event.
     * Triggers when a soft-deleted organisation assignment is restored.
     *
     * @param UserOrganisationRole $pivot
     * @return void
     */
    public function restored(UserOrganisationRole $pivot): void
    {
        $this->invalidateUserCaches($pivot->user_id);

        Log::channel(config('login-routing.analytics.channel', 'login'))
            ->info('User organisation role restored - caches invalidated', [
                'user_id' => $pivot->user_id,
                'organisation_id' => $pivot->organisation_id,
                'role' => $pivot->role,
                'timestamp' => now()->toIso8601String(),
            ]);
    }

    /**
     * Handle the UserOrganisationRole "force deleted" event.
     * Triggers when a user-organisation relationship is permanently deleted.
     *
     * @param UserOrganisationRole $pivot
     * @return void
     */
    public function forceDeleted(UserOrganisationRole $pivot): void
    {
        $this->invalidateUserCaches($pivot->user_id);

        Log::channel(config('login-routing.analytics.channel', 'login'))
            ->warning('User organisation role force deleted - caches invalidated', [
                'user_id' => $pivot->user_id,
                'organisation_id' => $pivot->organisation_id,
                'role' => $pivot->role,
                'timestamp' => now()->toIso8601String(),
            ]);
    }

    /**
     * Invalidate all caches for a specific user
     *
     * Called when user's roles/organisations change to ensure next login
     * re-resolves dashboard rather than using stale cached routing.
     *
     * Cache keys invalidated:
     * - dashboard_resolution:{user_id} - Main routing cache
     * - user_orgs_with_roles:{user_id} - Organisation relationships
     * - user_active_vote:{user_id} - Active voting session
     * - user_dashboard_preferences:{user_id} - User preferences
     *
     * @param int $userId
     * @return void
     */
    protected function invalidateUserCaches(int $userId): void
    {
        $cacheKeys = [
            // Login routing caches
            config('login-routing.cache.cache_key_prefix') . $userId,
            'dashboard_resolution:' . $userId,

            // Organisation-related caches
            'user_orgs_with_roles:' . $userId,
            'user_org_list:' . $userId,
            'user_organisation_roles:' . $userId,

            // Voting-related caches
            'user_active_vote:' . $userId,
            'user_voting_sessions:' . $userId,

            // Preference caches
            'user_dashboard_preferences:' . $userId,
            'user_last_organisation:' . $userId,
        ];

        $invalidatedCount = 0;
        foreach ($cacheKeys as $key) {
            if (Cache::forget($key)) {
                $invalidatedCount++;
            }
        }

        if (config('login-routing.debug.log_cache', false)) {
            Log::debug('User caches invalidated', [
                'user_id' => $userId,
                'keys_targeted' => count($cacheKeys),
                'keys_found_and_deleted' => $invalidatedCount,
            ]);
        }
    }
}
