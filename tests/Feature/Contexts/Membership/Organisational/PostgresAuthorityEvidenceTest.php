<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\EndMembership;
use App\Contexts\Membership\Application\Organisational\EndMembershipHandler;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityDecision;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityRequest;
use App\Contexts\Membership\Domain\Organisational\AuthorityBasis;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\AuthorityOutcome;
use App\Contexts\Membership\Domain\Organisational\CompetenceKind;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ScriptedLedgerClockReader;

/**
 * PBDIGIT-354 S2-3a (D-238) on PostgreSQL: the answer's basis is stored on the fact in jsonb and read back; evidence in
 * the historical shape (no basis) still loads, as "not recorded"; a pull exclusion writes nothing.
 */
final class PostgresAuthorityEvidenceTest extends MembershipPostgresTestCase
{
    private string $org;
    private int $clock = 1_760_000_000_000_000;
    /** @var object{kind: CompetenceKind}&AuthorityCheckPort */
    private AuthorityCheckPort $authority;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $this->org, 'name' => 's2-3a', 'slug' => 's23a-' . $this->org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $this->authority = new class implements AuthorityCheckPort {
            public CompetenceKind $kind = CompetenceKind::WorkedOut;

            public function check(TenantId $tenant, AuthorityRequest $request): AuthorityDecision
            {
                return AuthorityDecision::of(AuthorityEvidence::of($request->actor, 'test-capacity', 'test:pg', 'v1', true, Instant::of(1), true,
                    AuthorityBasis::of(AuthorityOutcome::Authorised, null, $request->at, $this->kind, 'resolution:3/part:1', ['sha256:pg'])));
            }
        };
        $this->assertTrue((new AdmitMemberHandler($this->repository(), $this->authority))->handle(
            new AdmitMember($this->org, 'P1', 'M1', 'A1', 20_000, 20_000, 20_365, false, 'registrar-1', 'decl-fixture', null, 'k-admit'),
        )->isAppended());
    }

    private function repository(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(fn (): int => $this->clock += 1_000));
    }

    private function end(string $kind, string $actor, string $key)
    {
        return (new EndMembershipHandler($this->repository(), $this->authority))->handle(new EndMembership($this->org, 'M1', 'E1', 'A1', 20_050, 20_050, $kind, $actor, null, $key));
    }

    public function test_the_basis_is_stored_and_read_back(): void
    {
        $this->authority->kind = CompetenceKind::OwnAct;
        $this->assertTrue($this->end('resignation', 'P1', 'k1')->isAppended(), 'the member resigning: actor = subject');

        $basis = $this->repository()->load(TenantId::fromString($this->org), MemberId::fromString('M1'))->log()[1]->fact()->provenance()->authorityEvidence()->basis();
        $this->assertSame([AuthorityOutcome::Authorised, CompetenceKind::OwnAct, 20_050, 'resolution:3/part:1', ['sha256:pg']],
            [$basis->outcome(), $basis->competenceKind(), $basis->evaluatedFor()->ordinal(), $basis->decisionRef(), $basis->evidence()]);
        $stored = json_decode(DB::selectOne("SELECT provenance::text AS p FROM org_membership_facts WHERE organisation_id = ? AND fact_id = 'E1'", [$this->org])->p, true);
        $this->assertSame('own-act', $stored['authorityEvidence']['basis']['competenceKind'], 'in the jsonb');
    }

    public function test_historical_evidence_without_a_basis_still_loads_as_not_recorded(): void
    {
        // the admission row, rewritten HERE (test setup only) into the shape stored before S2-3a
        DB::update("UPDATE org_membership_facts SET provenance = provenance #- '{authorityEvidence,basis}' WHERE organisation_id = ? AND fact_id = 'A1'", [$this->org]);
        // jsonb_exists() rather than the `?` operator, which PDO would read as a placeholder
        $this->assertFalse((bool) DB::selectOne("SELECT jsonb_exists(provenance->'authorityEvidence', 'basis') AS b FROM org_membership_facts WHERE organisation_id = ? AND fact_id = 'A1'", [$this->org])->b);

        $member = $this->repository()->load(TenantId::fromString($this->org), MemberId::fromString('M1'));

        $this->assertNull($member->log()[0]->fact()->provenance()->authorityEvidence()->basis(), 'not recorded; nothing inferred');
        $this->assertTrue($member->resolve()->isMember(TimePoint::of(20_100)));
    }

    public function test_a_pull_exclusion_writes_nothing(): void
    {
        $this->assertSame(RefusalReason::ExclusionIsPushOnly, $this->end('exclusion', 'board-1', 'k1')->domainRefusal());

        $this->assertSame(1, (int) DB::selectOne('SELECT count(*) AS n FROM org_membership_facts WHERE organisation_id = ?', [$this->org])->n);
        $this->assertSame(0, (int) DB::selectOne('SELECT count(*) AS n FROM org_membership_commands WHERE organisation_id = ? AND idempotency_key = ?', [$this->org, 'k1'])->n);
    }
}
