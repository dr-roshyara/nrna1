<?php

namespace App\Http\Controllers;

use App\Contexts\Elections\Application\Commands\AssignVoterCommand;
use App\Contexts\Elections\Application\Commands\BulkAssignVotersCommand;
use App\Contexts\Elections\Application\Handlers\AssignVoterHandler;
use App\Contexts\Elections\Application\Handlers\BulkAssignVotersHandler;
use App\Contexts\Elections\Application\Commands\RevokeIdentityVerificationCommand;
use App\Contexts\Elections\Application\Handlers\RevokeIdentityVerificationHandler;
use App\Contexts\Elections\Domain\ValueObjects\RevocationReason;
use App\Contexts\Elections\Application\Ports\VoterRegistrationQuery;
use App\Contexts\Elections\Domain\ValueObjects\AdmissionRequirement;
use App\Http\Controllers\Election\VoterAdmissionPolicyController;
use App\Contexts\Elections\Domain\Exceptions\DuplicateVoterException;
use App\Contexts\Elections\Domain\Exceptions\VoterNotEligibleException;
use App\Domain\Election\Enum\VoterSourceStrategy;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\VoterIpVerification;
use App\Models\VoterVerification;
use App\ReadModels\VoterVerificationStates;
use App\Services\VoterEligibilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * ElectionVoterController
 *
 * Manages voter assignments (ElectionMembership) for real elections.
 * Demo elections return 404 — voter management is not applicable to them.
 *
 * Authorization: ElectionPolicy (view = org member, manage = commission/admin)
 * Middleware: auth + verified + ensure.organisation  (via routes/organisations.php group)
 */
class ElectionVoterController extends Controller
{
    public function __construct(
        private VoterEligibilityService $eligibilityService,
        private AssignVoterHandler $assignVoterHandler,
        private BulkAssignVotersHandler $bulkAssignVotersHandler,
    ) {}

    // =========================================================================
    // index — list voters assigned to the election
    // =========================================================================

    public function index(Organisation $organisation, string $election)
    {
        $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($election->organisation_id !== $organisation->id, 404);
        abort_if($election->type === 'demo', 404, 'Voter management is not available for demo elections.');

        $this->authorize('view', $election);

        $search   = request('search');
        $status   = request('status');
        $perPage  = in_array((int) request('per_page'), [25, 50, 100]) ? (int) request('per_page') : 50;

        $voters = $election->memberships()
            ->with('user:id,name,email,current_ip,updated_at')
            ->where('role', 'voter')
            ->when($search, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest('assigned_at')
            ->paginate($perPage)
            ->withQueryString();

        // Org members not yet assigned to this election — for the assign dropdown
        $assignedUserIds = $election->memberships()
            ->where('role', 'voter')
            ->pluck('user_id')
            ->toArray();

        $unassignedMembers = $this->eligibilityService
            ->unassignedEligibleQuery($organisation, $assignedUserIds, VoterSourceStrategy::fromElection($election))
            ->get();

        // Load active verifications for this election, keyed by user_id
        $verifications = VoterVerification::where('election_id', $election->id)
            ->where('status', 'active')
            ->get(['id', 'user_id', 'verified_ip', 'verified_device_fingerprint_hash', 'verified_at', 'notes'])
            ->keyBy('user_id');

        return Inertia::render('Elections/Voters/Index', [
            'election'             => $election->only('id', 'slug', 'name', 'type', 'status'),
            'organisation'         => $organisation->only('id', 'slug', 'name'),
            'voters'               => $voters,
            'stats'                => $election->voter_stats,
            'unassignedMembers'    => $unassignedMembers,
            'filters'              => ['search' => $search, 'status' => $status, 'per_page' => $perPage],
            'verifications'        => $verifications,
            'admission'            => $this->admissionProps($election),
            'identityVerification' => $this->identityVerificationStates($election, $voters->pluck('user_id')->all()),
            // PBDIGIT-222 Capture IP: the IP Address column follows the "Voter's IP Verification" setting
            // (PO 2026-10-06).
            'captureIp'            => ['active' => $election->isIpVerificationEnabled()],
            // Current IP capture per voter (PBDIGIT-222 target UI: the IP column's state comes only from the
            // IP record). Display / evidence only — never read for admission or entitlement.
            'ipVerification'       => $this->ipCaptures($election, $voters->pluck('user_id')->all()),
            // IP Binding (PBDIGIT-247): display only — enforced by the Security Verdict Authority, never by this page.
            'ipBinding'            => ['active' => $election->isIpBindingEnabled()],
        ]);
    }

    /**
     * Current IP capture per voter on this page (PBDIGIT-222), keyed by user_id — a DISPLAY read-model
     * from voter_ip_verifications only; it never touches identity evidence or entitlement.
     *
     * @param  list<string>  $userIds
     * @return array<string, array{ip_address: string, captured_at: string, notes: ?string}>
     */
    private function ipCaptures(Election $election, array $userIds): array
    {
        if (! $election->isIpVerificationEnabled() || $userIds === []) {
            return [];
        }

        return VoterIpVerification::where('election_id', $election->id)
            ->whereIn('user_id', $userIds)
            ->get(['user_id', 'ip_address', 'captured_at', 'notes'])
            ->mapWithKeys(fn ($row) => [$row->user_id => [
                'ip_address'  => $row->ip_address,
                'captured_at' => $row->captured_at?->toIso8601String(),
                'notes'       => $row->notes,
            ]])
            ->all();
    }

    /**
     * Voter Admission state for the management page (PBDIGIT-213 C4-B).
     * bulkVerifiableCount = the "Verify All" set (C4-D1), from the same port the
     * bulk handler uses, so the count the officer confirms is the server's own.
     */
    private function admissionProps(Election $election): array
    {
        return [
            'required'            => $election->admissionPolicy()->requires(AdmissionRequirement::OfficerIdentityVerification),
            'available'           => VoterAdmissionPolicyController::isAvailableFor($election),
            'bulkVerifiableCount' => count(app(VoterRegistrationQuery::class)->bulkVerifiableVoterIds($election->id)),
        ];
    }

    /**
     * Per-voter identity-verification EVIDENCE state (PBDIGIT-213 C4-B) — a DISPLAY
     * read-model, never an authorisation source. Independent of the policy: the policy
     * says whether verification is REQUIRED (admissionProps 'required'); this says whether
     * it HAS BEEN DONE (PO, 2026-10-04). Shared with the read-only voter list (PBDIGIT-250).
     *
     * @param  list<string>  $userIds
     * @return array<string, string>
     */
    private function identityVerificationStates(Election $election, array $userIds): array
    {
        return app(VoterVerificationStates::class)->identity($election, $userIds);
    }

    // =========================================================================
    // store — assign a single voter to the election
    // =========================================================================

    public function store(Request $request, Organisation $organisation, string $election)
    {
        $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($election->organisation_id !== $organisation->id, 404);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manageVoters', $election);

        $request->validate([
            'user_id' => [
                'required',
                'uuid',
                // Must be eligible voter (checked against election's constitutional snapshot)
                function ($attribute, $value, $fail) use ($organisation, $election) {
                    $user = \App\Models\User::find($value);
                    $mode = VoterSourceStrategy::fromElection($election);
                    if (! $user || ! $this->eligibilityService->isEligibleVoter($organisation, $user, $mode)) {
                        $fail('The selected user does not meet the voter eligibility requirements for this election.');
                    }
                },
            ],
        ]);

        try {
            $this->assignVoterHandler->handle(new AssignVoterCommand(
                userId: $request->user_id,
                electionId: $election->id,
                organisationId: $organisation->id,
                mode: VoterSourceStrategy::fromElection($election),
                assignedBy: auth()->id(),
            ));
        } catch (VoterNotEligibleException | DuplicateVoterException $e) {
            return back()->withErrors(['user_id' => $e->getMessage()]);
        }

        return back()->with('success', 'Voter assigned successfully.');
    }

    // =========================================================================
    // bulkStore — assign multiple voters in one request
    // =========================================================================

    public function bulkStore(Request $request, Organisation $organisation, string $election)
    {
        $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($election->organisation_id !== $organisation->id, 404);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manageVoters', $election);

        $request->validate([
            'user_ids'   => 'required|array|max:1000',
            'user_ids.*' => 'uuid',
        ]);

        $result = $this->bulkAssignVotersHandler->handle(new BulkAssignVotersCommand(
            userIds:        $request->user_ids,
            electionId:     $election->id,
            organisationId: $organisation->id,
            mode:           VoterSourceStrategy::fromElection($election),
            assignedBy:     auth()->id(),
        ));

        return redirect()->route('organisations.elections.voters', [
            'organisation' => $organisation->slug,
            'election' => $election->slug
        ])->with('bulk_result', $result);
    }

    // =========================================================================
    // destroy — remove (soft) a voter from the election
    // =========================================================================

    public function destroy(Organisation $organisation, string $election, ElectionMembership $membership)
    {
        $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($election->organisation_id !== $organisation->id, 404);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manageVoters', $election);

        // Row lock prevents race condition: if voter's VoteController::store() is in a
        // live transaction, lockForUpdate() blocks until one side commits.
        DB::transaction(function () use ($membership) {
            $locked = ElectionMembership::where('id', $membership->id)
                ->lockForUpdate()
                ->firstOrFail();

            $locked->remove('Removed by ' . auth()->user()->name, auth()->user());
        });

        return back()->with('success', 'Voter removed successfully.');
    }

    // =========================================================================
    // approve — set a voter's status to active
    // =========================================================================

    public function approve(Organisation $organisation, string $election, ElectionMembership $membership): RedirectResponse
    {
        $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($election->organisation_id !== $organisation->id, 404);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manageVoters', $election);

        if ($membership->election_id !== $election->id) {
            abort(404);
        }

        if ($membership->status === 'active') {
            return back()->with('error', 'Voter is already approved.');
        }

        $membership->update(['status' => 'active', 'assigned_at' => now()]);
        Cache::forget("election.{$election->id}.voter_stats");

        return back()->with('success', "Voter {$membership->user->name} approved.");
    }

    // =========================================================================
    // suspend — set a voter's status to inactive
    // =========================================================================

    public function suspend(Organisation $organisation, string $election, ElectionMembership $membership): RedirectResponse
    {
        $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($election->organisation_id !== $organisation->id, 404);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manageVoters', $election);

        if ($membership->election_id !== $election->id) {
            abort(404);
        }

        if ($membership->status === 'inactive') {
            return back()->with('error', 'Voter is already suspended.');
        }

        $membership->update(['status' => 'inactive']);
        Cache::forget("election.{$election->id}.voter_stats");

        return back()->with('success', "Voter {$membership->user->name} suspended.");
    }

    // =========================================================================
    // export — stream a CSV of assigned voters
    // =========================================================================

    public function export(Organisation $organisation, string $election)
    {
        $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($election->organisation_id !== $organisation->id, 404);
        abort_if($election->type === 'demo', 404);

        $this->authorize('view', $election);

        $voters = $election->memberships()
            ->with('user:id,name,email')
            ->where('role', 'voter')
            ->get();

        $filename = 'voters-' . $election->id . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($voters) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($handle, ['Name', 'Email', 'Status', 'Assigned At']);
            foreach ($voters as $membership) {
                fputcsv($handle, [
                    $membership->user->name  ?? '',
                    $membership->user->email ?? '',
                    $membership->status,
                    $membership->assigned_at?->toDateTimeString(),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // =========================================================================
    // proposeSuspension — first committee member proposes
    // =========================================================================

    public function proposeSuspension(Organisation $organisation, string $election, ElectionMembership $membership): RedirectResponse
    {
        $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($election->organisation_id !== $organisation->id, 404);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manageVoters', $election);

        if ($membership->election_id !== $election->id) {
            abort(404);
        }

        if ($membership->status !== 'active') {
            return back()->with('error', 'Only active voters can have a suspension proposed.');
        }

        if ($membership->has_voted) {
            return back()->with('error', 'Cannot suspend a voter who has already voted.');
        }

        if ($membership->isSuspensionProposed()) {
            return back()->with('error', 'A suspension is already pending for this voter.');
        }

        $membership->proposeSuspension(auth()->user());

        Log::channel('voting_security')->info('Suspension proposed', [
            'proposer_id'   => auth()->id(),
            'proposer_name' => auth()->user()->name,
            'voter_id'      => $membership->user_id,
            'election_id'   => $election->id,
        ]);

        return back()->with('success', "Suspension proposed for {$membership->user->name}. A second committee member must confirm.");
    }

    // =========================================================================
    // confirmSuspension — second committee member confirms
    // =========================================================================

    public function confirmSuspension(Organisation $organisation, string $election, ElectionMembership $membership): RedirectResponse
    {
        $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($election->organisation_id !== $organisation->id, 404);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manageVoters', $election);

        if ($membership->election_id !== $election->id) {
            abort(404);
        }

        if (! $membership->canConfirmSuspension(auth()->user())) {
            return back()->with('error', 'You cannot confirm a suspension you proposed, or no suspension is pending.');
        }

        $proposedBy = $membership->suspension_proposed_by;

        // PBDIGIT-213 C3.4 (ADR D5): a confirmed suspension keeps the voter registered
        // and ends the CURRENT admission effect of their identity evidence, so
        // re-verification is required. The historical fact is kept. Atomic with the
        // confirmation.
        DB::transaction(function () use ($membership, $election) {
            $membership->confirmSuspension(auth()->user());

            app(RevokeIdentityVerificationHandler::class)->handle(new RevokeIdentityVerificationCommand(
                electionId: $election->id,
                voterId: $membership->user_id,
                revokedBy: auth()->id(),
                reason: RevocationReason::SuspensionConfirmed,
            ));
        });
        Cache::forget("election.{$election->id}.voter_stats");

        Log::channel('voting_security')->info('Suspension confirmed', [
            'confirmer_id'   => auth()->id(),
            'confirmer_name' => auth()->user()->name,
            'proposer_name'  => $proposedBy,
            'voter_id'       => $membership->user_id,
            'election_id'    => $election->id,
        ]);

        return back()->with('success', "Voter {$membership->user->name} suspended. Proposed by {$proposedBy}, confirmed by " . auth()->user()->name . '.');
    }

    // =========================================================================
    // cancelProposal — proposer or admin cancels pending proposal
    // =========================================================================

    public function cancelProposal(Organisation $organisation, string $election, ElectionMembership $membership): RedirectResponse
    {
        $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
        abort_if($election->organisation_id !== $organisation->id, 404);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manageVoters', $election);

        if ($membership->election_id !== $election->id) {
            abort(404);
        }

        if (! $membership->isSuspensionProposed()) {
            return back()->with('error', 'No pending suspension proposal to cancel.');
        }

        $isProposer = $membership->suspension_proposed_by === auth()->user()->name;
        $isAdmin    = auth()->user()->hasRole('admin');

        if (! $isProposer && ! $isAdmin) {
            abort(403, 'Only the proposer or an admin can cancel the suspension proposal.');
        }

        $membership->cancelSuspensionProposal();

        return back()->with('success', "Suspension proposal for {$membership->user->name} cancelled.");
    }

}
