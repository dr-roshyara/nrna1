<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\PublishedLanguage\MembershipCoverageResolved;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEventProcessor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\Support\ScriptedLedgerClockReader;

/**
 * PBDIGIT-343 task 7 (plan test 14; D-6): the C1 row written by the append goes through the EXISTING relay
 * (OutboxEventProcessor) unchanged: hydrated by Membership's registered hydrator, dispatched, marked completed,
 * not dead-lettered. No consumer exists yet (Organisation is slice 3); that is harmless (plan R-9).
 */
final class PostgresC1RelayRoundTripTest extends MembershipPostgresTestCase
{
    public function test_the_relay_hydrates_dispatches_and_completes_a_c1_row(): void
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'c1-relay', 'slug' => 'c1-relay-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $t = TenantId::fromString($id);
        $repo = new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => 1_760_000_000_000_123));
        $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('person-M1'))->member();
        $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k1'), 'fp1');
        $row = OutboxEvent::query()->where('organisation_id', $id)->where('event_type', 'MembershipCoverageResolved')->sole();
        $dispatched = [];
        Event::listen(MembershipCoverageResolved::class, static function (MembershipCoverageResolved $e) use (&$dispatched): void {
            $dispatched[] = $e;
        });

        $this->app->make(OutboxEventProcessor::class)->processEvent($row);

        $row->refresh();
        $this->assertSame('completed', $row->status, 'processed, not dead-lettered or failed');
        $this->assertNotNull($row->processed_at);
        $this->assertCount(1, $dispatched);
        $this->assertSame('M1', $dispatched[0]->membershipId);
        $this->assertSame($id, $dispatched[0]->organisationId);
        $this->assertSame(1, $dispatched[0]->seq);
        $this->assertSame('2024-10-04', $dispatched[0]->coverage[0]->from);
        $this->assertNull($dispatched[0]->coverage[0]->to);
    }
}
