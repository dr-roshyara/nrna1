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
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Application\ApplicationId;
use App\Contexts\Membership\Application\Application\UseCases\SubmitMembershipApplication;
use App\Contexts\Membership\Application\Application\UseCases\RejectMembershipApplication;
use App\Contexts\Membership\Application\Application\UseCases\ApproveMembershipApplication;
use App\Contexts\Membership\Application\Application\DTOs\SubmitMembershipApplicationCommand;
use App\Contexts\Membership\Application\Application\DTOs\ApproveMembershipApplicationCommand;
use Carbon\CarbonImmutable;
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

    public function store(
        Request $request,
        Organisation $organisation,
        SubmitMembershipApplication $submitMembershipApplication
    ): RedirectResponse {
        $validated = $request->validate([
            'membership_type_id'  => ['required', 'uuid'],
            'application_data'    => ['nullable', 'array'],
        ]);

        $user = $request->user();

        // Guard: user is already an active member of this organisation.
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
            ->whereIn('status', ['draft', 'submitted', 'under_review'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('applicant_email', $user->email);
            })
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

        try {
            $command = SubmitMembershipApplicationCommand::fromRequest(
                $organisation->id,
                $user->id,
                $validated['membership_type_id'],
                $validated['application_data'] ?? null
            );

            $submitMembershipApplication->execute($command);

            return redirect()->route('organisations.voter-hub', $organisation->slug)
                ->with('success', 'Your membership application has been submitted.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to submit application: ' . $e->getMessage()]);
        }
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

    public function approve(
        Request $request,
        Organisation $organisation,
        string $application,
        ApproveMembershipApplication $approveMembershipApplication
    ): RedirectResponse {
        $this->authorizeForOrg($request->user(), $organisation, 'approveApplication');

        $application = MembershipApplication::where('id', $application)
            ->where('organisation_id', $organisation->id)
            ->firstOrFail();

        abort_if($application->organisation_id !== $organisation->id, 404);

        if (!$application->isPending()) {
            return back()->withErrors(['error' => 'This application has already been processed.']);
        }

        // Public applications: admin must select a membership type and committee at approval
        if ($application->isPublicApplication()) {
            $request->validate([
                'membership_type_id' => ['required', 'uuid'],
                'committee_id'       => ['nullable', 'uuid'],
            ]);

            $selectedType = MembershipType::where('id', $request->membership_type_id)
                ->where('organisation_id', $organisation->id)
                ->where('is_active', true)
                ->first();

            if (!$selectedType) {
                return back()->withErrors(['membership_type_id' => 'The selected membership type is not available.']);
            }

            $application->update(['membership_type_id' => $selectedType->id]);
            $application->refresh();
        }

        try {
            \Log::info('MembershipApplicationController::approve - starting approval', [
                'application_id' => $application->id,
                'organisation_id' => $organisation->id,
            ]);

            DB::transaction(function () use ($application, $request, $organisation, $approveMembershipApplication) {
                $user = null;

                // For public applications, link or create the user account
                if ($application->isPublicApplication()) {
                    $data = $application->application_data ?? [];

                    // Re-use existing account if the email is already registered
                    $user = User::where('email', $application->applicant_email)->first();

                    if (!$user) {
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

                        // Send password-set invitation link only for brand-new accounts
                        Password::sendResetLink(['email' => $user->email]);
                    }

                    $application->update(['user_id' => $user->id]);
                    $application->refresh();
                } else {
                    // For regular applications, fetch the applicant user
                    $user = User::findOrFail($application->user_id);
                }

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
                UserOrganisationRole::withoutGlobalScopes()->firstOrCreate(
                    [
                        'organisation_id' => $organisation->id,
                        'user_id'         => $application->user_id,
                    ],
                    [
                        'id'   => (string) Str::uuid(),
                        'role' => 'member',
                    ]
                );

                // DDD: Execute use case to create Member and Fee aggregates
                $committeeId = $request->filled('committee_id')
                    ? CommitteeId::fromString($request->committee_id)
                    : CommitteeId::generate();

                $command = new ApproveMembershipApplicationCommand(
                    applicationId: ApplicationId::fromString($application->id),
                    tenantId: TenantId::fromOrganisationId($organisation->id),
                    userId: $application->user_id,
                    organisationUserId: $orgUser->id,
                    name: $user->name,
                    email: $user->email,
                    phone: $user->telephone,
                    committeeId: $committeeId,
                    membershipTypeId: MembershipTypeId::fromString($type->id),
                    membershipFeeAmount: (float) $type->fee_amount,
                    feeDueDate: CarbonImmutable::now()->addMonths($type->duration_months ?? 12)
                );

                \Log::info('MembershipApplicationController::approve - executing use case', [
                    'application_id' => $application->id,
                ]);

                $approveMembershipApplication->execute($command);

                \Log::info('MembershipApplicationController::approve - use case executed successfully', [
                    'application_id' => $application->id,
                ]);

                event(new MembershipApplicationApproved($application->fresh()));
            });
        } catch (ApplicationAlreadyProcessedException) {
            \Log::warning('MembershipApplicationController::approve - already processed', [
                'application_id' => $application->id,
            ]);
            return back()->withErrors(['error' => 'This application was already processed by another administrator.']);
        } catch (\Exception $e) {
            \Log::error('MembershipApplicationController::approve - exception', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);
            return back()->withErrors(['error' => 'Failed to approve application: ' . $e->getMessage()]);
        }

        return redirect()->route('organisations.membership.applications.index', $organisation->slug)
            ->with('success', 'Application approved successfully.');
    }

    // ── reject ────────────────────────────────────────────────────────────────

    public function reject(
        Request $request,
        Organisation $organisation,
        string $application,
        RejectMembershipApplication $rejectMembershipApplication
    ): RedirectResponse {
        $this->authorizeForOrg($request->user(), $organisation, 'rejectApplication');

        $application = MembershipApplication::where('id', $application)
            ->where('organisation_id', $organisation->id)
            ->firstOrFail();

        abort_if($application->organisation_id !== $organisation->id, 404);

        if (!$application->isPending()) {
            return back()->withErrors(['error' => 'This application has already been processed.']);
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $applicationId = \App\Contexts\Membership\Domain\Application\ApplicationId::fromString($application->id);
            $tenantId = TenantId::fromOrganisationId($organisation->id);

            $rejectMembershipApplication->execute(
                $applicationId,
                $tenantId,
                $validated['rejection_reason']
            );

            event(new MembershipApplicationRejected($application->fresh()));

            return redirect()->route('organisations.membership.applications.index', $organisation->slug)
                ->with('success', 'Application rejected.');
        } catch (ApplicationAlreadyProcessedException) {
            return back()->withErrors(['error' => 'This application was already processed by another administrator.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to reject application: ' . $e->getMessage()]);
        }
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
