<?php

namespace App\Http\Controllers\Membership;

use App\Events\Membership\MembershipApplicationApproved;
use App\Events\Membership\MembershipApplicationRejected;
use App\Exceptions\ApplicationAlreadyProcessedException;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Policies\MembershipPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class MembershipApplicationController extends Controller
{
    // ── create ────────────────────────────────────────────────────────────────

    public function create(Organisation $organisation): Response
    {
        $types = MembershipType::where('organisation_id', $organisation->id)
            ->active()
            ->orderBy('sort_order')
            ->get(['id', 'name', 'fee_amount', 'fee_currency', 'duration_months', 'description']);

        return Inertia::render('Organisations/Membership/Apply', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'types'        => $types,
        ]);
    }

    // ── store ─────────────────────────────────────────────────────────────────

    public function store(Request $request, Organisation $organisation): RedirectResponse
    {
        $validated = $request->validate([
            'membership_type_id'  => ['required', 'uuid'],
            'application_data'    => ['nullable', 'array'],
        ]);

        $user = $request->user();

        // Guard: user is already an active member of this organisation.
        // Both OrganisationUser and Member use BelongsToTenant, so we bypass
        // global scopes here — the /apply route runs without ensure.organisation
        // middleware, meaning current_organisation_id is not yet in session.
        $alreadyMember = OrganisationUser::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->whereHas('member', fn ($q) =>
                $q->withoutGlobalScopes()
                  ->where('organisation_id', $organisation->id)
                  ->where('status', 'active')
            )
            ->exists();

        if ($alreadyMember) {
            return back()->withErrors(['error' => 'You are already an active member of this organisation.']);
        }

        // Guard: user already has a pending application
        $hasPending = MembershipApplication::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['draft', 'submitted', 'under_review'])
            ->exists();

        if ($hasPending) {
            return back()->withErrors(['error' => 'You already have a pending application for this organisation.']);
        }

        // Verify type belongs to this organisation and is active
        $type = MembershipType::where('id', $validated['membership_type_id'])
            ->where('organisation_id', $organisation->id)
            ->where('is_active', true)
            ->first();

        if (!$type) {
            return back()->withErrors(['membership_type_id' => 'The selected membership type is not available.']);
        }

        MembershipApplication::create([
            'id'                  => (string) Str::uuid(),
            'organisation_id'     => $organisation->id,
            'user_id'             => $user->id,
            'membership_type_id'  => $type->id,
            'status'              => 'submitted',
            'application_data'    => $validated['application_data'] ?? null,
            'expires_at'          => now()->addDays(config('membership.application_expiry_days', 30)),
            'submitted_at'        => now(),
        ]);

        return redirect()->route('organisations.voter-hub', $organisation->slug)
            ->with('success', 'Your membership application has been submitted.');
    }

    // ── index ─────────────────────────────────────────────────────────────────

    public function index(Request $request, Organisation $organisation): Response|RedirectResponse
    {
        $this->authorizeForOrg($request->user(), $organisation, 'viewApplications');

        $applications = MembershipApplication::with(['user', 'membershipType'])
            ->where('organisation_id', $organisation->id)
            ->latest()
            ->paginate(20);

        return Inertia::render('Organisations/Membership/Applications/Index', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'applications' => $applications,
        ]);
    }

    // ── show ──────────────────────────────────────────────────────────────────

    public function show(Request $request, Organisation $organisation, MembershipApplication $application): Response|RedirectResponse
    {
        $this->authorizeForOrg($request->user(), $organisation, 'viewApplications');

        abort_if($application->organisation_id !== $organisation->id, 404);

        $application->load(['user', 'membershipType', 'reviewer']);

        // For public applications awaiting type assignment, pass available types
        $types = $application->isPublicApplication()
            ? MembershipType::where('organisation_id', $organisation->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'fee_amount', 'fee_currency', 'duration_months'])
            : collect();

        return Inertia::render('Organisations/Membership/Applications/Show', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'application'  => $application,
            'types'        => $types,
        ]);
    }

    // ── approve ───────────────────────────────────────────────────────────────

    public function approve(Request $request, Organisation $organisation, MembershipApplication $application): RedirectResponse
    {
        $this->authorizeForOrg($request->user(), $organisation, 'approveApplication');

        abort_if($application->organisation_id !== $organisation->id, 404);

        if (!$application->isPending()) {
            return back()->withErrors(['error' => 'This application has already been processed.']);
        }

        // Public applications: admin must select a membership type at approval
        if ($application->isPublicApplication()) {
            $request->validate([
                'membership_type_id' => ['required', 'uuid'],
            ]);

            $selectedType = MembershipType::where('id', $request->membership_type_id)
                ->where('organisation_id', $organisation->id)
                ->where('is_active', true)
                ->first();

            if (! $selectedType) {
                return back()->withErrors(['membership_type_id' => 'The selected membership type is not available.']);
            }

            $application->update(['membership_type_id' => $selectedType->id]);
            $application->refresh();
        }

        try {
            DB::transaction(function () use ($application, $request, $organisation) {
                // For public applications, create the user account first
                if ($application->isPublicApplication()) {
                    $data = $application->application_data ?? [];
                    $user = User::create([
                        'id'              => (string) Str::uuid(),
                        'organisation_id' => $organisation->id,
                        'name'            => trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')),
                        'first_name'      => $data['first_name'] ?? null,
                        'last_name'       => $data['last_name'] ?? null,
                        'email'           => $application->applicant_email,
                        'telephone'       => $data['telephone_number'] ?? null,
                        'city'            => $data['city'] ?? null,
                        'country'         => $data['country'] ?? null,
                        'education_level' => $data['education_level'] ?? null,
                        'profession'      => $data['profession'] ?? null,
                        'password'        => Hash::make(Str::random(32)),
                    ]);
                    $application->update(['user_id' => $user->id]);
                    $application->refresh();

                    // Send password-set invitation link
                    Password::sendResetLink(['email' => $user->email]);
                }

                $application->approve($request->user()->id);

                $type = $application->membershipType;

                // Create OrganisationUser
                $orgUser = OrganisationUser::firstOrCreate(
                    [
                        'organisation_id' => $organisation->id,
                        'user_id'         => $application->user_id,
                    ],
                    [
                        'id'     => (string) Str::uuid(),
                        'role'   => 'member',
                        'status' => 'active',
                    ]
                );

                // Create UserOrganisationRole
                UserOrganisationRole::firstOrCreate(
                    [
                        'organisation_id' => $organisation->id,
                        'user_id'         => $application->user_id,
                    ],
                    [
                        'id'   => (string) Str::uuid(),
                        'role' => 'member',
                    ]
                );

                // Create Member
                $expiresAt = $type->duration_months
                    ? now()->addMonths($type->duration_months)
                    : null;

                $member = Member::create([
                    'id'                    => (string) Str::uuid(),
                    'organisation_id'       => $organisation->id,
                    'organisation_user_id'  => $orgUser->id,
                    'membership_type_id'    => $type->id,
                    'membership_number'     => 'M' . strtoupper(Str::random(8)),
                    'status'                => 'active',
                    'fees_status'           => 'unpaid',
                    'joined_at'             => now(),
                    'membership_expires_at' => $expiresAt,
                    'created_by'            => $request->user()->id,
                ]);

                // Create pending fee (fee snapshot)
                MembershipFee::create([
                    'id'                  => (string) Str::uuid(),
                    'organisation_id'     => $organisation->id,
                    'member_id'           => $member->id,
                    'membership_type_id'  => $type->id,
                    'amount'              => $type->fee_amount,
                    'currency'            => $type->fee_currency,
                    'fee_amount_at_time'  => $type->fee_amount,
                    'currency_at_time'    => $type->fee_currency,
                    'status'              => 'pending',
                    'recorded_by'         => $request->user()->id,
                ]);

                event(new MembershipApplicationApproved($application));
            });
        } catch (ApplicationAlreadyProcessedException) {
            return back()->withErrors(['error' => 'This application was already processed by another administrator.']);
        }

        return redirect()->route('organisations.membership.applications.index', $organisation->slug)
            ->with('success', 'Application approved successfully.');
    }

    // ── reject ────────────────────────────────────────────────────────────────

    public function reject(Request $request, Organisation $organisation, MembershipApplication $application): RedirectResponse
    {
        $this->authorizeForOrg($request->user(), $organisation, 'rejectApplication');

        abort_if($application->organisation_id !== $organisation->id, 404);

        if (!$application->isPending()) {
            return back()->withErrors(['error' => 'This application has already been processed.']);
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $application->reject($request->user()->id, $validated['rejection_reason']);
            event(new MembershipApplicationRejected($application));
        } catch (ApplicationAlreadyProcessedException) {
            return back()->withErrors(['error' => 'This application was already processed by another administrator.']);
        }

        return redirect()->route('organisations.membership.applications.index', $organisation->slug)
            ->with('success', 'Application rejected.');
    }

    // ── helpers ───────────────────────────────────────────────────────────────

    private function authorizeForOrg($user, Organisation $organisation, string $ability): void
    {
        $policy = new MembershipPolicy();

        $allowed = match ($ability) {
            'viewApplications'  => $policy->viewApplications($user, $organisation),
            'approveApplication'=> $policy->approveApplication($user, $organisation),
            'rejectApplication' => $policy->rejectApplication($user, $organisation),
            default             => false,
        };

        abort_if(!$allowed, 403);
    }
}
