<?php

namespace App\Http\Controllers\Election;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Contexts\Elections\Application\Ports\AdmissionImpactQuery;
use App\Contexts\Elections\Domain\Exceptions\SecurityPolicyLocked;
use App\Contexts\Elections\Domain\ValueObjects\AdmissionRequirement;
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
            'admission'    => $this->admissionProps($election),
        ]);
    }

    /**
     * Voter Admission state for the settings page (PBDIGIT-213 C2; the card is C4).
     * affectedVoters: registered voters whose entitlement flips under the
     * none ↔ identity-verification transition (ADR_20261004_1549 R3-2).
     */
    private function admissionProps(Election $election): array
    {
        return [
            'identityVerificationRequired' => $election->admissionPolicy()->requires(AdmissionRequirement::OfficerIdentityVerification),
            'isLocked'                     => ElectionLifecycle::of($election)->isLocked(),
            'available'                    => VoterAdmissionPolicyController::isAvailableFor($election),
            'affectedVoters'               => app(AdmissionImpactQuery::class)->registeredVotersWithoutIdentityEvidence($election->id),
        ];
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

        // PBDIGIT-244 I9: the security policy is immutable from ReadyForVoting. A real change
        // refuses the whole PATCH (nothing written); the Election model guard enforces the same rule.
        $securityPolicyLocked = ElectionLifecycle::of($election)->isLocked();
        try {
            $this->assertSecurityPolicyMutable($election, $request->validated(), $securityPolicyLocked);
        } catch (SecurityPolicyLocked) {
            return back()->withErrors([
                $this->changedSecurityPolicyFields($election, $request->validated())[0]
                    => 'The security policy cannot be changed once the election is ready for voting.',
            ]);
        }

        // Active election guard — require confirmation when votes exist
        if ($this->shouldConfirmActiveChanges($election, $request)) {
            return back()->with('warning',
                'This election is active and has votes. Changes take effect immediately. Re-submit to confirm.');
        }

        $validated = $request->validated();

        // Locked election, security policy unchanged: never write those fields, so a merely
        // re-typed value (e.g. ip_whitelist null re-sent as []) cannot trip the model guard.
        if ($securityPolicyLocked) {
            $validated = array_diff_key($validated, array_flip(Election::SECURITY_POLICY_FIELDS));
        }

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
     * PBDIGIT-244 I9: refuse an effective security-policy change on a locked election.
     *
     * @throws SecurityPolicyLocked
     */
    private function assertSecurityPolicyMutable(Election $election, array $validated, bool $locked): void
    {
        if ($locked && $this->changedSecurityPolicyFields($election, $validated) !== []) {
            throw SecurityPolicyLocked::forElection($election->id);
        }
    }

    /**
     * Security-policy fields whose submitted value differs from the stored one, compared on
     * normalised values: booleans and integers by value, ip_whitelist null == [] (PO 2026-10-05).
     *
     * @return list<string>
     */
    private function changedSecurityPolicyFields(Election $election, array $validated): array
    {
        $normalise = fn (string $field, mixed $value): string => match ($field) {
            'ip_restriction_enabled',
            'ip_binding_enabled'        => (bool) $value ? '1' : '0',
            'ip_restriction_max_per_ip' => $value === null ? '' : (string) (int) $value,
            'ip_whitelist'              => json_encode(array_values((array) ($value ?? []))),
            default                     => (string) $value,
        };

        $changed = [];
        foreach (Election::SECURITY_POLICY_FIELDS as $field) {
            if (array_key_exists($field, $validated)
                && $normalise($field, $election->$field) !== $normalise($field, $validated[$field])) {
                $changed[] = $field;
            }
        }

        return $changed;
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
