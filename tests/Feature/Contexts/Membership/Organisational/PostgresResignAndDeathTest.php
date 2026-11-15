<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\RecordDeath;
use App\Contexts\Membership\Application\Organisational\RecordDeathHandler;
use App\Contexts\Membership\Application\Organisational\ResignMembership;
use App\Contexts\Membership\Application\Organisational\ResignMembershipHandler;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ScriptedLedgerClockReader;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * PBDIGIT-354 S2-3b on PostgreSQL: resign now and record a death are stored, reloaded and published (C1: coverage ends
 * at the effective date, C-6). The death's evidence reference is kept in the provenance jsonb. No migration.
 * Day ordinals: 20000 = 2024-10-04, 20040 = 2024-11-13, 20045 = 2024-11-18.
 */
final class PostgresResignAndDeathTest extends MembershipPostgresTestCase
{
    private int $clock = 1_760_000_000_000_000;

    private function repository(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(fn (): int => $this->clock += 1_000));
    }

    private function organisationWithMember(): string
    {
        $org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $org, 'name' => 's2-3b', 'slug' => 's23b-' . $org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $this->assertTrue((new AdmitMemberHandler($this->repository(), new FakeAuthority()))->handle(
            new AdmitMember($org, 'P1', 'M1', 'A1', 20_000, 20_000, 20_365, false, 'registrar-1', 'decl-fixture', null, 'k-admit'),
        )->isAppended());

        return $org;
    }

    /** @return list<object> C1 payloads by their own seq */
    private static function c1(string $org): array
    {
        $events = array_map(static fn ($r) => json_decode($r->payload), DB::select("SELECT payload FROM outbox_events WHERE organisation_id = ? AND event_type = 'MembershipCoverageResolved'", [$org]));
        usort($events, static fn (object $x, object $y): int => $x->seq <=> $y->seq);

        return $events;
    }

    public function test_a_resignation_is_stored_reloaded_and_ends_the_published_coverage(): void
    {
        $org = $this->organisationWithMember();

        $this->assertTrue((new ResignMembershipHandler($this->repository(), new FakeAuthority()))->handle(new ResignMembership($org, 'M1', 'E1', 20_040, 'P1', null, 'k1'))->isAppended());

        $end = $this->repository()->load(TenantId::fromString($org), MemberId::fromString('M1'))->log()[1]->fact();
        $this->assertSame(['resignation', 20_040, 'A1'], [$end->kind()->value(), $end->effectiveAt()->ordinal(), $end->period()->value()]);
        $this->assertSame([['2024-10-04', '2024-11-13']], array_map(static fn ($i) => [$i->from, $i->to], self::c1($org)[1]->coverage), 'C-6: coverage ends at the effective date');
    }

    public function test_a_death_is_stored_with_its_evidence_reference_and_ends_the_published_coverage(): void
    {
        $org = $this->organisationWithMember();

        $this->assertTrue((new RecordDeathHandler($this->repository(), new FakeAuthority()))->handle(
            new RecordDeath($org, 'M1', 'E1', 20_040, 20_045, 'death-certificate:2024/77', 'registrar-1', null, 'k1'),
        )->isAppended());

        $member = $this->repository()->load(TenantId::fromString($org), MemberId::fromString('M1'));
        $end = $member->log()[1]->fact();
        $this->assertSame(['death', 20_040, 'death-certificate:2024/77'], [$end->kind()->value(), $end->effectiveAt()->ordinal(), $end->provenance()->declarationRef()]);
        $this->assertFalse($member->resolve()->isMember(TimePoint::of(20_040)));
        $stored = json_decode(DB::selectOne("SELECT provenance::text AS p FROM org_membership_facts WHERE organisation_id = ? AND fact_id = 'E1'", [$org])->p, true);
        $this->assertSame('death-certificate:2024/77', $stored['declarationRef'], 'in the jsonb');
        $this->assertSame([['2024-10-04', '2024-11-13']], array_map(static fn ($i) => [$i->from, $i->to], self::c1($org)[1]->coverage));
    }

    public function test_a_refused_death_writes_nothing(): void
    {
        $org = $this->organisationWithMember();

        $outcome = (new RecordDeathHandler($this->repository(), new FakeAuthority()))->handle(new RecordDeath($org, 'M1', 'E1', 20_040, 20_045, null, 'registrar-1', null, 'k1'));

        $this->assertSame('death_evidence_required', $outcome->domainRefusal()?->value);
        $this->assertSame(1, (int) DB::selectOne('SELECT count(*) AS n FROM org_membership_facts WHERE organisation_id = ?', [$org])->n);
        $this->assertCount(1, self::c1($org));
    }
}
