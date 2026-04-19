<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Organisation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;
use Inertia\Response;

class OrganisationSettingsController extends Controller
{
    public function index(Organisation $organisation): Response
    {
        $this->authorize('manageMembership', $organisation);

        $memberCount = Cache::remember(
            "organisation.{$organisation->id}.member_count",
            3600,
            fn() => Member::where('organisation_id', $organisation->id)->count()
        );

        return Inertia::render('Organisations/Settings/Index', [
            'organisation' => $organisation,
            'memberCount' => $memberCount,
        ]);
    }

    public function updateMembershipMode(Request $request, Organisation $organisation): RedirectResponse
    {
        $this->authorize('manageMembership', $organisation);

        // Rate limit: 5 attempts per hour per organisation
        $rateLimitKey = "membership-mode-change:{$organisation->id}";
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return back()->withErrors([
                'rate' => __('organisations.errors.membership_mode_rate_limit'),
            ]);
        }
        RateLimiter::hit($rateLimitKey, 3600);

        $validated = $this->validateMembershipModeChange($request);

        $memberCount = Member::where('organisation_id', $organisation->id)->count();

        // Validate mode change constraints
        $this->validateModeChangeConstraints($organisation, $validated, $memberCount);

        // Atomic transaction with audit logging
        DB::transaction(function () use ($organisation, $validated, $memberCount) {
            $oldMode = $organisation->uses_full_membership ? 'full' : 'election_only';
            $newMode = $validated['uses_full_membership'] ? 'full' : 'election_only';

            $organisation->update(['uses_full_membership' => $validated['uses_full_membership']]);

            // Invalidate cache after mode change
            Cache::forget("organisation.{$organisation->id}.member_count");

            Log::info('Organisation membership mode changed', [
                'organisation_id' => $organisation->id,
                'from' => $oldMode,
                'to' => $newMode,
                'user_id' => auth()->id(),
                'member_count' => $memberCount,
            ]);
        });

        return back()->with('success', __('organisations.messages.membership_mode_updated'));
    }

    /**
     * Validate membership mode change request
     */
    private function validateMembershipModeChange(Request $request): array
    {
        return $request->validate([
            'uses_full_membership' => 'required|boolean',
            'confirm_mode_change' => 'required_if:uses_full_membership,false|boolean',
        ]);
    }

    /**
     * Validate constraints for membership mode change
     */
    private function validateModeChangeConstraints(Organisation $organisation, array $validated, int $memberCount): void
    {
        // Only apply constraints when switching FROM full TO election-only
        if (!$organisation->uses_full_membership || $validated['uses_full_membership']) {
            return;
        }

        // Check for members with pending fees before switching to election-only
        $pendingFeeMembers = Member::where('organisation_id', $organisation->id)
            ->where('fees_status', 'pending')
            ->count();

        if ($pendingFeeMembers > 0) {
            throw new \Illuminate\Validation\ValidationException(
                \Illuminate\Validation\Validator::make([], [
                    'membership_mode' => "required",
                ], [
                    'membership_mode.required' => __('organisations.errors.pending_fees_block', ['count' => $pendingFeeMembers]),
                ])
            );
        }

        // Require explicit confirmation when members exist
        if ($memberCount > 0 && !$validated['confirm_mode_change']) {
            throw new \Illuminate\Validation\ValidationException(
                \Illuminate\Validation\Validator::make([], [
                    'confirm_mode_change' => "required",
                ], [
                    'confirm_mode_change.required' => __('organisations.errors.confirmation_required'),
                ])
            );
        }
    }
}
