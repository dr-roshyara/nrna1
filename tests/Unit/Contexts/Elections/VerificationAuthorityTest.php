<?php

namespace Tests\Unit\Contexts\Elections;

use App\Contexts\Elections\Domain\Policies\VerificationAuthority;
use App\Contexts\Elections\Domain\ValueObjects\ElectionLeadershipRole;
use App\Contexts\Elections\Domain\ValueObjects\VerifierRole;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-213 C3.2 — VerificationAuthority: WHO may create human identity evidence.
 *
 * ADR_20261004_1549 D2 / §3: "Identity verification may be performed by the Chief or
 * Deputy Chief of the election." The leadership role is supplied (election-scoped)
 * by the VerificationAuthoritySource port; anything else grants nothing. Pure.
 */
final class VerificationAuthorityTest extends TestCase
{
    private VerificationAuthority $authority;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authority = new VerificationAuthority();
    }

    public function test_the_chief_of_the_election_may_verify_as_election_chief(): void
    {
        $this->assertSame(VerifierRole::ElectionChief, $this->authority->roleFor(ElectionLeadershipRole::Chief));
    }

    public function test_the_deputy_chief_of_the_election_may_verify_as_election_deputy_chief(): void
    {
        $this->assertSame(VerifierRole::ElectionDeputyChief, $this->authority->roleFor(ElectionLeadershipRole::DeputyChief));
    }

    public function test_no_election_leadership_role_grants_no_authority(): void
    {
        $this->assertNull($this->authority->roleFor(null));
    }

    public function test_verifier_roles_have_stable_evidence_codes(): void
    {
        $this->assertSame('ELECTION_CHIEF', VerifierRole::ElectionChief->value);
        $this->assertSame('ELECTION_DEPUTY_CHIEF', VerifierRole::ElectionDeputyChief->value);
    }
}
