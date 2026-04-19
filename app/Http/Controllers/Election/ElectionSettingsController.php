<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Http\Requests\Election\UpdateElectionSettingsRequest;
use App\Models\DemoVote;
use App\Models\Election;
use App\Models\Vote;
use App\Services\ElectionAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class ElectionSettingsController extends Controller
{
    /**
     * Display the election settings page.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Election $election): Response
    {
        $this->authorize('manageSettings', $election);

        return Inertia::render('Elections/Settings/Index', [
            'election'     => $election->load('settingsUpdatedBy:id,name', 'organisation:id,slug,name'),
            'organisation' => $election->organisation,
            'hasVotes'     => $this->hasVotes($election),
        ]);
    }

    /**
     * Update the election settings with optimistic locking and audit trail.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UpdateElectionSettingsRequest $request, Election $election): RedirectResponse
    {
        // Authorization handled in FormRequest

        // Optimistic locking — reject stale writes
        $this->checkOptimisticLock($request, $election);

        // Active election guard — require confirmation when votes exist
        if ($this->shouldConfirmActiveChanges($election, $request)) {
            return back()->with('warning',
                'This election is active and has votes. Changes take effect immediately. Re-submit to confirm.');
        }

        $validated = $request->validated();

        // Detect changes for audit trail
        $excludeKeys = ['settings_version', 'confirmed_active_changes', 'agreed_to_settings'];
        $changes = $this->detectChanges($election, $validated, $excludeKeys);

        // Build update data with audit metadata
        $updateData = $this->buildUpdateData($validated, $changes, $request->user()->id, $excludeKeys);

        // Update election
        $election->update($updateData);

        // Invalidate settings cache
        $this->invalidateSettingsCache($election);

        // Log successful update only if changes occurred
        if (!empty($changes)) {
            $this->logSettingsChange($election, $changes);
        }

        return back()->with('success', 'Election settings updated successfully.');
    }

    /**
     * Check if election has votes (demo or real).
     */
    private function hasVotes(Election $election): bool
    {
        $model = $election->isDemo() ? DemoVote::class : Vote::class;
        return $model::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->exists();
    }

    /**
     * Validate optimistic locking version.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function checkOptimisticLock(UpdateElectionSettingsRequest $request, Election $election): void
    {
        if ((int) $request->input('settings_version') !== $election->settings_version) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'settings_version' => 'Settings were modified by another user. Please reload and try again.',
            ]);
        }
    }

    /**
     * Determine if active election requires confirmation before saving.
     */
    private function shouldConfirmActiveChanges(Election $election, UpdateElectionSettingsRequest $request): bool
    {
        return $election->isCurrentlyActive()
            && $this->hasVotes($election)
            && !$request->boolean('confirmed_active_changes');
    }

    /**
     * Detect actual value changes between old and new settings.
     * Handles arrays by JSON serialization.
     */
    private function detectChanges(Election $election, array $validated, array $excludeKeys): array
    {
        $settingsKeys = array_diff(array_keys($validated), $excludeKeys);
        $changes = [];

        foreach ($settingsKeys as $key) {
            $oldValue = $election->$key;
            $newValue = $validated[$key] ?? null;

            // Serialize arrays for comparison to avoid type coercion issues
            $oldSerialized = is_array($oldValue) ? json_encode($oldValue) : (string) $oldValue;
            $newSerialized = is_array($newValue) ? json_encode($newValue) : (string) $newValue;

            if ($oldSerialized !== $newSerialized) {
                $changes[$key] = ['from' => $oldValue, 'to' => $newValue];
            }
        }

        return $changes;
    }

    /**
     * Build the update data array with audit metadata.
     */
    private function buildUpdateData(array $validated, array $changes, string $userId, array $excludeKeys): array
    {
        return array_merge(
            collect($validated)->except($excludeKeys)->toArray(),
            [
                'settings_version'    => $validated['settings_version'] + 1,
                'settings_updated_by' => $userId,
                'settings_updated_at' => now(),
                'settings_changes'    => $changes,
            ]
        );
    }

    /**
     * Invalidate all caches related to election settings.
     */
    private function invalidateSettingsCache(Election $election): void
    {
        Cache::forget("election-settings-{$election->id}");
        Cache::forget("election.{$election->id}");
        Cache::forget("election.{$election->id}.voter_count");
    }

    /**
     * Log settings change to debug log (only in debug mode).
     */
    private function logSettingsChange(Election $election, array $changes): void
    {
        // Audit trail
        app(ElectionAuditService::class)->log(
            election: $election,
            event: 'settings_changed',
            user: auth()->user(),
            category: 'committee',
            metadata: ['changes' => $changes]
        );

        // Debug log
        if (config('app.debug')) {
            \Illuminate\Support\Facades\Log::debug('⚙️ [ElectionSettings] Updated', [
                'election_id'   => $election->id,
                'election_name' => $election->name,
                'changes'       => $changes,
                'version'       => $election->settings_version,
            ]);
        }
    }
}
