<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Infrastructure\Organisational\InterimRoleAuthority;
use App\Contexts\Membership\Infrastructure\Organisational\OrganisationRoleReader;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

/**
 * D-4 = A1 (PO / ARB, 2026-10-11): an INTERIM authority adapter. Admission mirrors the legacy
 * MembershipPolicy::approveApplication rule (owner / admin) and records AuthorityEvidence. Ending is
 * DENIED for everyone until Governance decides G-1 (no legacy ending rule exists to mirror).
 * Replaceable by the Governance-owned implementation (DS-4). PBDIGIT-271, Slice 2a step 6.
 */
final class InterimRoleAuthorityTest extends TestCase
{
    private static function authority(array $roles): InterimRoleAuthority
    {
        $reader = new class ($roles) implements OrganisationRoleReader {
            /** @param array<string, array<string, string>> $roles tenant => actor => role */
            public function __construct(private readonly array $roles)
            {
            }

            public function roleOf(TenantId $tenant, string $actor): ?string
            {
                return $this->roles[$tenant->value()][$actor] ?? null;
            }
        };

        return new InterimRoleAuthority($reader, static fn (): int => 1_760_000_000_000_000);
    }

    /** @return array<string, array{string}> */
    public static function admittingRoles(): array
    {
        return ['owner' => ['owner'], 'admin' => ['admin']];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('admittingRoles')]
    public function test_owner_and_admin_may_admit_and_the_evidence_is_recorded(string $role): void
    {
        $decision = self::authority(['O1' => ['user-7' => $role]])->check(TenantId::fromString('O1'), 'user-7', MembershipAct::Admit);

        $this->assertTrue($decision->isGranted());
        $e = $decision->evidence();
        $this->assertSame('user-7', $e->actor());
        $this->assertSame("organisation_role:$role", $e->capacity());
        $this->assertSame('interim:legacy-MembershipPolicy:approveApplication', $e->ruleRef());
        $this->assertSame('interim-1', $e->policyVersion());
        $this->assertTrue($e->result());
        $this->assertSame(1_760_000_000_000_000, $e->checkedAt()->ordinal());
        $this->assertFalse($e->competenceVerified());
    }

    /** @return array<string, array{array<string, array<string, string>>}> */
    public static function nonAdmittingActors(): array
    {
        return [
            'member' => [['O1' => ['user-7' => 'member']]],
            'commission' => [['O1' => ['user-7' => 'commission']]],
            'no role' => [[]],
            'owner of another organisation' => [['O2' => ['user-7' => 'owner']]],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('nonAdmittingActors')]
    public function test_other_actors_may_not_admit(array $roles): void
    {
        $decision = self::authority($roles)->check(TenantId::fromString('O1'), 'user-7', MembershipAct::Admit);

        $this->assertFalse($decision->isGranted());
        $this->assertFalse($decision->evidence()->result());
        $this->assertSame('interim:legacy-MembershipPolicy:approveApplication', $decision->evidence()->ruleRef());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('admittingRoles')]
    public function test_every_ending_is_denied_until_governance_decides_g1(string $role): void
    {
        $decision = self::authority(['O1' => ['user-7' => $role]])->check(TenantId::fromString('O1'), 'user-7', MembershipAct::End);

        $this->assertFalse($decision->isGranted(), 'no ending authority is inferred (G-1 open)');
        $this->assertSame('interim:unresolved:G-1', $decision->evidence()->ruleRef());
        $this->assertFalse($decision->evidence()->result());
    }
}
