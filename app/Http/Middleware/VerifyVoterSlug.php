<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Election;
use App\Models\VoterSlug;
use App\Models\DemoVoterSlug;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Voting\ElectionMismatchException;
use App\Exceptions\Voting\ElectionNotFoundException;
use App\Exceptions\Voting\ExpiredVoterSlugException;
use App\Exceptions\Voting\InvalidVoterSlugException;
use App\Exceptions\Voting\OrganisationMismatchException;
use App\Exceptions\Voting\SlugOwnershipException;
use App\Exceptions\Voting\VotingException;

class VerifyVoterSlug
{
    /**
     * Server-side voter-slug verification for every {vslug} voting route (PBDIGIT-273; plan
     * docs/plans/20261011-1318-verifyvoterslug-hardening-plan.md). Checks, in order:
     *
     * V-1 an authenticated user, before any slug lookup;
     * V-2 the slug type matches the route: demo routes (marked by the `election.demo` middleware) take a
     *     DemoVoterSlug, all other routes a VoterSlug; a string parameter is looked up in that model only;
     * V-3 the slug belongs to the authenticated user;
     * V-5 the slug is not revoked: not soft-deleted, active, status not `expired`;
     * V-4 the slug is not expired: now >= expires_at, or now >= created_at + voting.time_in_minutes (fixed
 *     lifetime from creation), or a missing timestamp, is expired;
     * V-6 the slug's election exists, belongs to the slug's organisation and has the route's type.
     *
     * The verified slug is stored on the request only after every check passed. Logs and exception
     * context carry the slug's database id, never the slug string (the exception renderer logs context).
     * Step order (V-7) and voting entitlement (V-8) are enforced by later middleware on the same routes.
     */
    public function handle(Request $request, Closure $next)
    {
        // V-1
        if (auth()->id() === null) {
            throw new AuthenticationException('Unauthenticated.');
        }

        $isDemoRoute = $this->isDemoRoute($request);

        // V-2
        $voterSlug = $this->resolveSlug($request->route('vslug'), $isDemoRoute);
        if ($voterSlug === null) {
            $this->reject(new InvalidVoterSlugException('Voting session not found', ['demo_route' => $isDemoRoute]));
        }

        $context = ['slug_id' => $voterSlug->id, 'demo_route' => $isDemoRoute];

        // V-3
        if ((string) $voterSlug->user_id !== (string) auth()->id()) {
            $this->reject(new SlugOwnershipException('This voting session does not belong to you', $context));
        }

        // V-5
        if ((method_exists($voterSlug, 'trashed') && $voterSlug->trashed())
            || !$voterSlug->is_active
            || $voterSlug->status === 'expired') {
            $this->reject(new InvalidVoterSlugException('This voting session is no longer active', $context));
        }

        // V-4
        if ($this->isExpired($voterSlug)) {
            $this->reject(new ExpiredVoterSlugException('This voting session has expired', $context));
        }

        // V-6
        $election = $voterSlug->relationLoaded('election')
            ? $voterSlug->getRelation('election')
            : Election::withoutGlobalScopes()->find($voterSlug->election_id);
        if ($election === null || $election->trashed()) {
            $this->reject(new ElectionNotFoundException('The voting session has no valid election', $context));
        }
        if (empty($voterSlug->organisation_id)
            || (string) $election->organisation_id !== (string) $voterSlug->organisation_id) {
            $this->reject(new OrganisationMismatchException('Voting session and election belong to different organisations', $context));
        }
        if ($election->isDemo() !== $isDemoRoute) {
            $this->reject(new ElectionMismatchException('Election type does not match the voting route', $context));
        }

        $request->attributes->set('voter_slug', $voterSlug);

        Log::debug('[VerifyVoterSlug] verification passed', $context + ['election_id' => $election->id]);

        return $next($request);
    }

    /**
     * Fixed lifetime (Human PO 2026-10-11): a slug is valid only while now < min(expires_at, created_at +
     * voting.time_in_minutes). The cap on created_at means no in-place extension of expires_at can lengthen
     * a slug's life; a missing expires_at or created_at counts as expired.
     */
    private function isExpired(Model $voterSlug): bool
    {
        if ($voterSlug->expires_at === null || $voterSlug->created_at === null) {
            return true;
        }
        $lifetimeEnd = $voterSlug->created_at->copy()->addMinutes((int) config('voting.time_in_minutes', 30));

        return now()->greaterThanOrEqualTo($voterSlug->expires_at) || now()->greaterThanOrEqualTo($lifetimeEnd);
    }

    /** The demo slug routes are the ones carrying the `election.demo` middleware (routes/election/electionRoutes.php). */
    private function isDemoRoute(Request $request): bool
    {
        return in_array('election.demo', $request->route()?->gatherMiddleware() ?? [], true);
    }

    /** A bound model must be of the route's slug type; a string is looked up in that model only (no fallback). */
    private function resolveSlug(mixed $slugParam, bool $isDemoRoute): ?Model
    {
        $expected = $isDemoRoute ? DemoVoterSlug::class : VoterSlug::class;

        if ($slugParam instanceof Model) {
            return $slugParam instanceof $expected ? $slugParam : null;
        }
        if (!is_string($slugParam) || $slugParam === '') {
            return null;
        }

        return $expected::withoutGlobalScopes()->where('slug', $slugParam)->first();
    }

    /** @return never */
    private function reject(VotingException $exception): void
    {
        Log::warning('[VerifyVoterSlug] ' . $exception->getMessage(), $exception->getContext());

        throw $exception;
    }
}
