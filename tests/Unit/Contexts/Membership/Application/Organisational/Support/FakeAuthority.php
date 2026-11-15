<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational\Support;

use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityDecision;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityRequest;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Test double for the consumer-side authority port (no Governance implementation; DS-4 open).
 * It records NO basis on purpose (S2-3a, D-238): the many tests using it keep exercising the "basis not recorded"
 * path, the shape of every historical row. AuthorityContractTest covers answers with a basis.
 */
final class FakeAuthority implements AuthorityCheckPort
{
    public int $calls = 0;
    /** @var list<AuthorityRequest> */
    public array $requests = [];

    /** @param int $grantFirst grant the first N calls, deny afterwards (PHP_INT_MAX = always grant) */
    public function __construct(private readonly int $grantFirst = PHP_INT_MAX)
    {
    }

    public function check(TenantId $tenant, AuthorityRequest $request): AuthorityDecision
    {
        $this->calls++;
        $this->requests[] = $request;
        $granted = $this->calls <= $this->grantFirst;

        return AuthorityDecision::of(AuthorityEvidence::of($request->actor, 'test-role', 'test:fake-authority', 'v1', $granted, Instant::of($this->calls), false));
    }
}
