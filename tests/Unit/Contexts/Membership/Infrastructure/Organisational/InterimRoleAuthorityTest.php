<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\AuthorityDecision;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityRequest;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Domain\Organisational\AuthorityOutcome;
use App\Contexts\Membership\Domain\Organisational\CompetenceKind;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
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

    /** S2-3a (D-238): the request shape; subject and period do not matter to this adapter */
    private static function ask(array $roles, MembershipAct $act): AuthorityDecision
    {
        return self::authority($roles)->check(TenantId::fromString('O1'),
            new AuthorityRequest($act, 'user-7', PersonRef::fromString('P1'), null, TimePoint::of(20_000), null, 'req-1',
                $act === MembershipAct::RecordDeath ? 'death-certificate:test' : null));   // S2-3b: a death cites its evidence
    }

    /** @return array<string, array{string}> */
    public static function admittingRoles(): array
    {
        return ['owner' => ['owner'], 'admin' => ['admin']];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('admittingRoles')]
    public function test_owner_and_admin_may_admit_and_the_evidence_is_recorded(string $role): void
    {
        $decision = self::ask(['O1' => ['user-7' => $role]], MembershipAct::Admit);

        $this->assertTrue($decision->isAuthorised());
        $e = $decision->evidence();
        $this->assertSame(CompetenceKind::InterimRole, $e->basis()->competenceKind(), 'S2-3a: a technical role check, said so');
        $this->assertSame(20_000, $e->basis()->evaluatedFor()->ordinal());
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
        $decision = self::ask($roles, MembershipAct::Admit);

        $this->assertFalse($decision->isAuthorised());
        $this->assertSame(AuthorityOutcome::NotAuthorised, $decision->evidence()->basis()->outcome(), 'a refusal, never Undetermined');
        $this->assertNotNull($decision->evidence()->basis()->reason());
        $this->assertFalse($decision->evidence()->result());
        $this->assertSame('interim:legacy-MembershipPolicy:approveApplication', $decision->evidence()->ruleRef());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('admittingRoles')]
    public function test_every_ending_is_denied_until_governance_decides_g1(string $role): void
    {
        foreach ([MembershipAct::RecordResignation, MembershipAct::RecordDeath] as $act) {   // S2-3a: End asks by kind
            $decision = self::ask(['O1' => ['user-7' => $role]], $act);

            $this->assertFalse($decision->isAuthorised(), 'no ending authority is inferred (G-1 open): ' . $act->value);
            $this->assertSame('interim:unresolved:G-1', $decision->evidence()->ruleRef(), $act->value);
            $this->assertFalse($decision->evidence()->result(), $act->value);
        }
    }

    /**
     * PBDIGIT-354 S2-1 / S2-2: no legacy rule exists for renewing, suspending or restoring either, so, as for End,
     * every actor is denied with recorded evidence (never a crash, never an inferred grant). This adapter stays
     * unbound for every release-1 organisation (blueprint G-3); Governance S3 supplies the real authority.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('admittingRoles')]
    public function test_renewal_suspension_and_restoration_are_denied_until_governance_decides(string $role): void
    {
        foreach ([MembershipAct::Renew, MembershipAct::Suspend, MembershipAct::Restore] as $act) {
            $decision = self::ask(['O1' => ['user-7' => $role]], $act);

            $this->assertFalse($decision->isAuthorised(), $act->value);
            $this->assertSame('interim:unresolved:authority-owner', $decision->evidence()->ruleRef(), $act->value);
            $this->assertFalse($decision->evidence()->result(), $act->value);
        }
    }

    public function test_every_act_kind_has_an_answer(): void
    {
        foreach (MembershipAct::cases() as $act) {
            $this->assertInstanceOf(AuthorityDecision::class, self::ask(['O1' => ['user-7' => 'owner']], $act), $act->value);
        }
    }
}
