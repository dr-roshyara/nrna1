<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Membership\Infrastructure\Organisational\StoredMemberLogInvalid;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * COR-4 step 5 (PBDIGIT-329; Q-6, AC-2): stored rows that pass the database's CHECKs but are not a history the kernel
 * could have decided, or cannot be decoded, make the Member fail to load with StoredMemberLogInvalid: an
 * infrastructure failure, fail closed (V-1). Rows are inserted directly; runs only on the PBDIGIT-324 throwaway cluster.
 */
final class PostgresCorrectionDecodeTest extends MembershipPostgresTestCase
{
    private function repo(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), fn (): int => 1_760_000_000_000_000);
    }

    /** @param list<array<string, mixed>> $rows inserted in the given order (seq may be out of order) */
    private function stored(array $rows): TenantId
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'cor4', 'slug' => 'cor4-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $t = TenantId::fromString($id);
        $this->repo()->create($t, MemberId::fromString('M1'), PersonRef::fromString('P1'));
        foreach ($rows as $row) {
            DB::table('org_membership_facts')->insert($row + [
                'organisation_id' => $id, 'membership_id' => 'M1', 'recorded_at' => '2026-01-01 00:00:00+00',
                'provenance' => '{"kind":"decision","actorOrSource":"registrar","declarationRef":"decl-fixture","ruleRef":null,"ruleVersion":null,"importer":null,"note":null,"authorityEvidence":null}',
            ]);
        }
        DB::table('org_memberships')->where('organisation_id', $id)->update(['version' => count($rows)]);

        return $t;
    }

    private const ADMIT = ['seq' => 1, 'fact_id' => 'A1', 'kind' => 'admit', 'effective_at' => '2026-01-01', 'initial_term' => 'none'];

    /** @return array<string, array{list<array<string, mixed>>}> */
    public static function invalidStoredHistories(): array
    {
        $correct = ['seq' => 2, 'fact_id' => 'C2', 'kind' => 'correct', 'effective_at' => '2025-12-01', 'target_fact_id' => 'A1', 'consequences' => '[]', 'g2_version' => 1];

        return [
            'an unknown g2_version' => [[self::ADMIT, ['g2_version' => 2] + $correct]],
            'consequences that are not trims' => [[self::ADMIT, ['consequences' => '[{"period":5}]'] + $correct]],
            'a trim with a malformed date' => [[self::ADMIT, ['consequences' => '[{"period":"A1","removed":[["2026-13-45",null]]}]'] + $correct]],
            'a trim that removes nothing' => [[self::ADMIT, ['consequences' => '[{"period":"A1","removed":[]}]'] + $correct]],
            'an Annul of a Void (N-3)' => [[self::ADMIT,
                ['seq' => 2, 'fact_id' => 'V2', 'kind' => 'void', 'effective_at' => null, 'target_fact_id' => 'A1'],
                ['seq' => 3, 'fact_id' => 'X3', 'kind' => 'annul', 'effective_at' => null, 'target_fact_id' => 'V2']]],
            // Review condition C-1 (F-1): damaged provenance must fail as StoredMemberLogInvalid, not ErrorException / TypeError.
            'provenance without its actor' => [[['provenance' => '{"kind":"decision"}'] + self::ADMIT]],
            'provenance whose actor is a number' => [[['provenance' => '{"kind":"decision","actorOrSource":5,"declarationRef":null,"ruleRef":null,"ruleVersion":null,"importer":null,"note":null,"authorityEvidence":null}'] + self::ADMIT]],
            'provenance that is a list' => [[['provenance' => '[1,2]'] + self::ADMIT]],
            'a reversal of a later fact (stored out of order)' => [[self::ADMIT,
                ['seq' => 3, 'fact_id' => 'C3', 'kind' => 'correct', 'effective_at' => '2025-12-01', 'target_fact_id' => 'A1', 'consequences' => '[]', 'g2_version' => 1],
                ['seq' => 2, 'fact_id' => 'X2', 'kind' => 'annul', 'effective_at' => null, 'target_fact_id' => 'C3']]],
        ];
    }

    /** @param list<array<string, mixed>> $rows */
    #[DataProvider('invalidStoredHistories')]
    public function test_an_invalid_stored_history_fails_to_load_as_stored_member_log_invalid(array $rows): void
    {
        $t = $this->stored($rows);

        $this->expectException(StoredMemberLogInvalid::class);
        $this->repo()->load($t, MemberId::fromString('M1'));
    }

    // Review F-7 (PO 2026-11-03, option A): an open-ended (∞) trim bound is stored as null and reloads as ∞, exactly.
    public function test_an_open_ended_trim_round_trips_through_the_database(): void
    {
        $t = $this->stored([
            ['seq' => 1, 'fact_id' => 'A0', 'kind' => 'admit', 'effective_at' => '2025-01-01', 'initial_term' => 'none'],
            ['seq' => 2, 'fact_id' => 'E1', 'kind' => 'end', 'effective_at' => '2025-06-01', 'target_fact_id' => 'A0', 'end_kind' => 'resignation'],
            ['seq' => 3, 'fact_id' => 'A1', 'kind' => 'admit', 'effective_at' => '2025-06-01', 'initial_term' => 'none'],
            ['seq' => 4, 'fact_id' => 'C4', 'kind' => 'correct', 'effective_at' => '2025-12-01', 'target_fact_id' => 'E1',
                'consequences' => '[{"period":"A1","removed":[["2025-06-01",null]]}]', 'g2_version' => 1],
        ]);

        $correct = $this->repo()->load($t, MemberId::fromString('M1'))->log()[3]->fact();

        $this->assertInstanceOf(\App\Contexts\Membership\Domain\Organisational\Correct::class, $correct);
        $removed = $correct->consequences()[0]->removed();
        $this->assertTrue($removed->equals(\App\Contexts\Membership\Domain\Organisational\IntervalSet::of(
            \App\Contexts\Membership\Domain\Organisational\Interval::from(\App\Contexts\Membership\Infrastructure\Organisational\DateOrdinal::fromDate('2025-06-01')),
        )), 'removed = [2025-06-01, ∞)');
        $this->assertTrue($removed->intervals()[0]->isOpenEnded());
    }

    public function test_a_valid_stored_history_with_every_new_kind_loads(): void
    {
        $t = $this->stored([
            self::ADMIT,
            ['seq' => 2, 'fact_id' => 'C2', 'kind' => 'correct', 'effective_at' => '2025-12-01', 'target_fact_id' => 'A1', 'consequences' => '[]', 'g2_version' => 1],
            ['seq' => 3, 'fact_id' => 'X3', 'kind' => 'annul', 'effective_at' => null, 'target_fact_id' => 'C2', 'decided_at' => '2026-01-02 00:00:00+00'],
            ['seq' => 4, 'fact_id' => 'V4', 'kind' => 'void', 'effective_at' => null, 'target_fact_id' => 'X3'],
        ]);

        $this->assertSame(4, $this->repo()->load($t, MemberId::fromString('M1'))->version());
    }
}
