<?php

namespace App\Http\Controllers;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Models\Election;
use App\Models\User;
use App\Exceptions\Voting\VoterSlugIssuanceConflict;
use App\Services\ElectionAuditService;
use App\Services\Voting\VoterSlugIssuance;
use App\Services\Voting\VoterSlugIssuer;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;

class ElectionVotingController extends Controller
{
    /**
     * Show the election page.
     *
     * GET /elections/{slug}
     *
     * Determines the user's voting eligibility and renders the civic election dashboard.
     * Uses withoutGlobalScopes() to bypass BelongsToTenant — slug is authoritative context.
     */
    public function show(string $slug): Response
    {
        $election = Election::withoutGlobalScopes()
            ->with('organisation:id,name,slug,logo')
            ->where('slug', $slug)
            ->where('type', 'real')
            ->firstOrFail();

        $user = auth()->user();

        // ONE authoritative entitlement decision (PBDIGIT-213 C1). Scope is derived
        // from the election, never from ambient tenant context (PBDIGIT-65/69).
        //  - isEligible = the voter's STANDING (registered voter, election's
        //    organisation, active) — independent of the lifecycle, so the page can
        //    say "registered, not yet open" / "period ended".
        //  - canVote    = the entitlement DECISION (standing + lifecycle, composed
        //    once in the domain) and not already voted.
        //  - admissionPending = registered and active, but the election's admission
        //    requirement (identity verification) is not yet satisfied (PBDIGIT-213 C3).
        $entitlement      = app(VotingEntitlementQuery::class);
        $standing         = $entitlement->standingOf($user->id, $election->id);
        $isEligible       = $standing->isEntitled();
        $admissionPending = $standing === VotingEntitlementReason::NotAdmitted;
        $hasVoted         = $this->membershipHasVoted($user, $election);
        $canVote          = $entitlement->decide($user->id, $election->id)->isEntitled() && ! $hasVoted;

        // Lifecycle SSOT — display props and audit log only; not a second decision.
        $lifecycle = ElectionLifecycle::of($election);

        if (!$lifecycle->canVote()) {
            Log::channel('voter_audit')->info('Vote creation blocked by election lifecycle', [
                'election_id' => $election->id,
                'user_id' => $user->id,
                'election_state' => $lifecycle->state()->value,
                'blocked_reason' => $lifecycle->blockedReason(),
            ]);
        }

        $org = $election->organisation;

        // Evaluate IP restriction status
        $ip      = request()->ip();
        $ipBlock = $this->resolveIpBlock($election, $ip);

        return Inertia::render('Election/Show', [
            'election'         => $election,
            'hasVoted'         => $hasVoted,
            'canVote'          => $canVote,
            'isEligible'       => $isEligible,
            'admissionPending' => $admissionPending,
            'lifecycle'        => [
                'state'           => $lifecycle->state()->value,
                'canVote'         => $lifecycle->canVote(),
                'blockedReason'   => $lifecycle->blockedReason(),
                'isTerminal'      => $lifecycle->isTerminal(),
                'allowedActions'  => $lifecycle->allowedActions(),
            ],
            'ipAddress'        => $ip,
            'ipBlocked'        => $ipBlock['blocked'],
            'ipBlockMessage'   => $ipBlock['message'],
            'remainingVotes'   => $ipBlock['remainingVotes'] ?? null,
            'organisation'     => $org,
            'organisationLogo' => $org?->logo ? \Storage::disk('public')->url($org->logo) : null,
            'organisationName' => $org?->name,
        ]);
    }

    /**
     * Start the voting flow.
     *
     * POST /elections/{slug}/start
     *
     * Validates eligibility, creates (or reuses) a VoterSlug,
     * then redirects into the existing voting workflow at slug.code.create.
     */
    public function start(Request $request, string $slug): RedirectResponse
    {
        $election = Election::withoutGlobalScopes()
            ->where('slug', $slug)
            ->where('type', 'real')
            ->firstOrFail();

        $user = auth()->user();

        // ONE authoritative standing decision (PBDIGIT-213 C1): registered voter,
        // election's organisation, active membership — scope from the election.
        $standing = app(VotingEntitlementQuery::class)->standingOf($user->id, $election->id);

        if (! $standing->isEntitled()) {
            return redirect()->route('elections.show', $slug)
                ->with('error', match ($standing) {
                    VotingEntitlementReason::NotActive   => 'Your voter registration for this election is not active.',
                    VotingEntitlementReason::NotAdmitted => 'Your identity must be verified by the Chief or Deputy Chief of the election before you can vote.',
                    default                              => 'You are not registered as a voter for this election.',
                });
        }

        if ($this->membershipHasVoted($user, $election)) {
            return redirect()->route('elections.show', $slug)
                ->with('info', 'You have already voted.');
        }

        // Validate election lifecycle state (SSOT check)
        $lifecycle = ElectionLifecycle::of($election);
        if (!$lifecycle->canVote()) {
            Log::channel('voter_audit')->warning('Voting start blocked by election lifecycle', [
                'election_id' => $election->id,
                'user_id' => $user->id,
                'election_state' => $lifecycle->state()->value,
                'blocked_reason' => $lifecycle->blockedReason(),
            ]);

            return redirect()->route('elections.show', $slug)
                ->with('error', 'Voting is not currently allowed: ' . $lifecycle->blockedReason());
        }

        // IP restriction check (replaces bare abort() with friendly redirect)
        $ipBlock = $this->resolveIpBlock($election, $request->ip());
        if ($ipBlock['blocked']) {
            // The admins' record, with the limit that applied (PBDIGIT-288 item 5)
            \App\Services\Voting\IpVoteLimit::recordRefusal($election, $user, 'start', (string) $request->ip());

            return redirect()->route('elections.show', $slug)
                ->with('error', $ipBlock['message']);
        }

        // PBDIGIT-274 S4: one current, valid link per voter, through the issuer. A valid link is reused unchanged;
        // an expired or deactivated one is replaced by a NEW row (fresh creation time, so the guard accepts it)
        // and kept as history. The old code rewrote the existing row in place and kept its old created_at, which
        // locked the voter out once the 30 minutes had passed.
        try {
            $issuance = VoterSlugIssuer::fresh()->issue($user, $election);
        } catch (VoterSlugIssuanceConflict $e) {
            return redirect()->route('elections.show', $slug)->with('error', $e->getUserMessage());
        }

        if ($issuance->outcome === VoterSlugIssuance::ALREADY_VOTED) {
            return redirect()->route('elections.show', $slug)->with('info', 'You have already voted.');
        }
        if (! $issuance->hasLink()) {
            return redirect()->route('elections.show', $slug)
                ->with('error', match ($issuance->refusal) {
                    VotingEntitlementReason::NotActive          => 'Your voter registration for this election is not active.',
                    VotingEntitlementReason::NotAdmitted        => 'Your identity must be verified by the Chief or Deputy Chief of the election before you can vote.',
                    VotingEntitlementReason::ElectionNotVotable => 'Voting is not currently allowed.',
                    default                                     => 'You are not registered as a voter for this election.',
                });
        }
        $voterSlug = $issuance->slug;

        // Log voting_started event
        app(ElectionAuditService::class)->log(
            election: $election,
            event: 'voting_started',
            user: $user,
            category: 'voters',
            ip: $request->ip()
        );

        return redirect()->route('slug.code.create', ['vslug' => $voterSlug->slug]);
    }

    /**
     * Ballot-side fact, deliberately kept OUTSIDE the entitlement decision
     * (ADR_20261004_1549 §2.2 inv. 8). Scope from the election, not ambient context.
     */
    private function membershipHasVoted(User $user, Election $election): bool
    {
        return (bool) $user->electionMemberships()
            ->withoutGlobalScope('tenant')
            ->where('election_id', $election->id)
            ->value('has_voted');
    }

    /**
     * Evaluate IP restriction status through the one shared rule (PBDIGIT-278 item 8, IpVoteLimit): whitelist → no
     * limit; an enabled election restriction wins; else MAX_USE_IP_ADDRESS; else 10. The count is completed votes
     * from this IP in this election (codes.client_ip); before, it read voter_slugs.step_1_ip, which nothing writes
     * for real elections, so the limit was never enforced here.
     */
    private function resolveIpBlock(Election $election, string $ip): array
    {
        $max = \App\Services\Voting\IpVoteLimit::limitFor($election, $ip);
        if ($max === null) {
            return ['blocked' => false, 'message' => null];
        }

        return $this->evaluateIpCount($election, $ip, $max);
    }

    /**
     * Count completed votes from an IP and determine if blocked.
     */
    private function evaluateIpCount(Election $election, string $ip, int $max): array
    {
        $votedCount = \App\Services\Voting\IpVoteLimit::votesFrom($election, $ip);

        if ($votedCount >= $max) {
            return [
                'blocked'        => true,
                'message'        => \App\Services\Voting\IpVoteLimit::refusalMessage($max),
                'remainingVotes' => 0,
            ];
        }

        return [
            'blocked'        => false,
            'message'        => null,
            'remainingVotes' => $max - $votedCount,
        ];
    }
}
