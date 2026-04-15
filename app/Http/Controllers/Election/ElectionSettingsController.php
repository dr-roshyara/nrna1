<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Services\ElectionAuditService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ElectionSettingsController extends Controller
{
    /**
     * Display the election settings page.
     */
    public function edit(Election $election): Response
    {
        $this->authorize('manageSettings', $election);

        // Check for votes using direct query to avoid relationship type issues
        $hasVotes = $election->isDemo()
            ? \App\Models\DemoVote::where('election_id', $election->id)->exists()
            : \App\Models\Vote::where('election_id', $election->id)->exists();

        return Inertia::render('Elections/Settings/Index', [
            'election'     => $election->load('settingsUpdatedBy:id,name', 'organisation:id,slug,name'),
            'organisation' => $election->organisation,
            'hasVotes'     => $hasVotes,
        ]);
    }

    /**
     * Update the election settings.
     */
    public function update(Request $request, Election $election): RedirectResponse
    {
        $this->authorize('manageSettings', $election);

        // Optimistic locking — reject stale writes
        if ((int) $request->input('settings_version') !== $election->settings_version) {
            return back()->withErrors([
                'settings_version' => 'Settings were modified by another user. Please reload and try again.',
            ]);
        }

        // Active election guard — require confirmation when votes exist
        $hasVotes = $election->isDemo()
            ? \App\Models\DemoVote::where('election_id', $election->id)->exists()
            : \App\Models\Vote::where('election_id', $election->id)->exists();

        if ($election->isCurrentlyActive() && $hasVotes) {
            if (!$request->boolean('confirmed_active_changes')) {
                return back()->with('warning',
                    'This election is active and has votes. Changes take effect immediately. Re-submit to confirm.');
            }
        }

        $validated = $request->validate([
            'ip_restriction_enabled'    => ['boolean'],
            'ip_restriction_max_per_ip' => ['integer', 'min:1', 'max:50'],
            'ip_whitelist'              => ['nullable', 'array'],
            'ip_whitelist.*'            => ['string'],
            'no_vote_option_enabled'    => ['boolean'],
            'no_vote_option_label'      => ['string', 'max:100'],
            'selection_constraint_type' => ['required', 'in:any,exact,range,minimum,maximum'],
            'selection_constraint_min'  => ['nullable', 'integer', 'min:0'],
            'selection_constraint_max'  => ['nullable', 'integer', 'min:1'],
            'settings_version'          => ['required', 'integer'],
            'confirmed_active_changes'  => ['boolean'],
        ]);

        // Build change diff for audit trail
        $settingsKeys = array_diff(array_keys($validated), ['settings_version', 'confirmed_active_changes']);
        $changes = [];
        foreach ($settingsKeys as $key) {
            if ($election->$key != ($validated[$key] ?? null)) {
                $changes[$key] = ['from' => $election->$key, 'to' => $validated[$key] ?? null];
            }
        }

        $updateData = array_merge(
            collect($validated)->except(['settings_version', 'confirmed_active_changes'])->toArray(),
            [
                'settings_version'    => $election->settings_version + 1,
                'settings_updated_by' => $request->user()->id,
                'settings_updated_at' => now(),
                'settings_changes'    => $changes,
            ]
        );

        \Illuminate\Support\Facades\Log::debug('🔵 [ElectionSettingsController] Saving settings', [
            'election_id' => $election->id,
            'election_name' => $election->name,
            'update_data' => $updateData,
            'validated_no_vote_enabled' => $validated['no_vote_option_enabled'] ?? 'NOT IN VALIDATED',
        ]);

        $election->update($updateData);

        \Illuminate\Support\Facades\Log::debug('🔵 [ElectionSettingsController] Settings saved', [
            'election_id' => $election->id,
            'no_vote_option_enabled_after_save' => $election->no_vote_option_enabled,
            'fresh_from_db' => \App\Models\Election::find($election->id)?->no_vote_option_enabled,
        ]);

        // Log settings_changed event
        if (!empty($changes)) {
            app(ElectionAuditService::class)->log(
                election: $election,
                event: 'settings_changed',
                user: $request->user(),
                category: 'committee',
                ip: $request->ip(),
                metadata: ['changes' => $changes]
            );
        }

        return back()->with('success', 'Settings saved.');
    }
}
