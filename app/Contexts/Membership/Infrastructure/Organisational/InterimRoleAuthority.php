<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityDecision;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * INTERIM authority adapter (D-4 = A1, PO / ARB 2026-10-11; PBDIGIT-271). It introduces no policy of its own:
 *
 * - Admit mirrors the legacy MembershipPolicy::approveApplication rule (owner / admin).
 * - End is DENIED for every actor. No legacy ending rule exists to mirror (plan F9), and which authority
 *   may end a membership is an open decision of the authority owner (G-1). Nothing is inferred.
 *
 * Every check records AuthorityEvidence. The adapter is replaced by the owning context's implementation
 * once the agreed replacement criterion (DS-4) is met. Not bound in the container in Slice 2a (2b, held).
 */
final class InterimRoleAuthority implements AuthorityCheckPort
{
    private const ADMIT_RULE = 'interim:legacy-MembershipPolicy:approveApplication';
    private const END_RULE = 'interim:unresolved:G-1';
    private const POLICY_VERSION = 'interim-1';
    private const ADMITTING_ROLES = ['owner', 'admin'];

    /** @param \Closure(): int $clock the check instant (microseconds since the Unix epoch) */
    public function __construct(private readonly OrganisationRoleReader $roles, private readonly \Closure $clock)
    {
    }

    public function check(TenantId $tenant, string $actor, MembershipAct $act): AuthorityDecision
    {
        $role = $this->roles->roleOf($tenant, $actor);
        [$ruleRef, $granted] = match ($act) {
            MembershipAct::Admit => [self::ADMIT_RULE, in_array($role, self::ADMITTING_ROLES, true)],
            MembershipAct::End => [self::END_RULE, false],
        };
        $evidence = AuthorityEvidence::of(
            $actor,
            'organisation_role:' . ($role ?? 'none'),
            $ruleRef,
            self::POLICY_VERSION,
            $granted,
            Instant::of(($this->clock)()),
            false,
        );

        return $granted ? AuthorityDecision::granted($evidence) : AuthorityDecision::denied($evidence);
    }
}
