<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational\Support;

use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityDecision;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/** Test double for the consumer-side authority port (no Governance implementation; DS-4 open). */
final class FakeAuthority implements AuthorityCheckPort
{
    public int $calls = 0;

    /** @param int $grantFirst grant the first N calls, deny afterwards (PHP_INT_MAX = always grant) */
    public function __construct(private readonly int $grantFirst = PHP_INT_MAX)
    {
    }

    public function check(TenantId $tenant, string $actor, MembershipAct $act): AuthorityDecision
    {
        $this->calls++;
        $granted = $this->calls <= $this->grantFirst;
        $evidence = AuthorityEvidence::of($actor, 'test-role', 'test:fake-authority', 'v1', $granted, Instant::of($this->calls), false);

        return $granted ? AuthorityDecision::granted($evidence) : AuthorityDecision::denied($evidence);
    }
}
