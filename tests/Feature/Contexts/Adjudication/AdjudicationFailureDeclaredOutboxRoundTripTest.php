<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Adjudication;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Events\AdjudicationFailureDeclared;
use App\Contexts\Adjudication\Infrastructure\Outbox\AdjudicationFailureDeclaredHydrator;
use App\Contexts\Adjudication\Infrastructure\Outbox\OutboxEventAdapter;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent;
use App\Models\Organisation;
use App\Services\TenantContext;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * WP-4C-1 — the writer↔hydrator round trip. **This is the test that answers O-1.**
 *
 * K2 and K3 assert a payload shape, but a hydrator checked only against a fixture **agrees with
 * itself**: it proves nothing about what will actually be in the outbox. The two halves of the
 * serialization pair are only a *contract* once one produces what the other consumes.
 *
 * So this test writes through `OutboxEventAdapter` — the real writer, with real persistence —
 * reads the stored payload back, and hydrates it. **If the writer and hydrator ever disagree on a
 * field name, a format or the schema version, this fails and K2/K3 do not.**
 *
 * **It asserts nothing about publication semantics.** The provenance handed in is arbitrary and
 * the test makes no claim that it is the *correct* provenance for a real declaration — whether the
 * publication site should begin a conversation or continue one is the open question **§6/E2**, and
 * it is untouched here.
 *
 * **FIXTURE NOTE, learned by failing:** `outbox_events.aggregate_id` is a **UUID** column, so a
 * `ChallengeRef` reaching the outbox must be UUID-shaped. `ChallengeRef::fromString()` does not
 * enforce that, and the unit-level fixtures (`ch-4c1-1`) pass happily without a database. **The
 * first run of this test failed on `invalid input syntax for type uuid` — precisely the class of
 * defect a fixture-only hydrator test cannot surface.** Recorded, not repaired: whether
 * `ChallengeRef` should validate its own shape is a value-object question outside this slice.
 *
 * Traceability: **R-88 · R-89** · plan §6/E2 · §17 (O-1, O-2) · ADR-T5 (v1-only window) ·
 * ADR-T11 (references only) · the WP-3A rule (publication **and** registration).
 */
final class AdjudicationFailureDeclaredOutboxRoundTripTest extends TestCase
{
    use RefreshDatabase;

    private string $tenantId;

    protected function setUp(): void
    {
        parent::setUp();

        $org = Organisation::create([
            'name' => 'WP-4C-1 Round Trip Org',
            'slug' => 'wp4c1-round-trip-org',
            'type' => 'tenant',
            'is_default' => false,
        ]);
        $this->tenantId = (string) $org->id;
        TenantContext::set($this->tenantId);
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    private function event(): AdjudicationFailureDeclared
    {
        return new AdjudicationFailureDeclared(
            ChallengeRef::fromString('44444444-4444-4444-8444-444444444444'),
            Reason::fromString('The admitted evidence does not establish the contested tally.'),
            IssuedByAuthority::fromString('constitutional-council'),
            new DateTimeImmutable('2026-08-04T11:30:00+00:00'),
        );
    }

    public function test_the_written_payload_hydrates_back_to_an_equal_event(): void
    {
        $this->app->make(OutboxEventAdapter::class)->enqueue(
            EventProvenance::start('11111111-1111-4111-8111-111111111111'),
            $this->event(),
        );

        $row = OutboxEvent::query()->where('event_type', 'AdjudicationFailureDeclared')->firstOrFail();

        // The dispatch arm is exercised here too: without it, enqueue() throws LogicException
        // ("No outbox mapping for event …") and no row exists at all.
        $this->assertSame('AdjudicationProcess', $row->aggregate_type);
        $this->assertSame('44444444-4444-4444-8444-444444444444', $row->aggregate_id);
        $this->assertSame($this->tenantId, (string) $row->organisation_id);

        $payload = is_array($row->payload) ? $row->payload : json_decode((string) $row->payload, true);

        $hydrated = (new AdjudicationFailureDeclaredHydrator())->hydrate($payload);

        $this->assertInstanceOf(AdjudicationFailureDeclared::class, $hydrated);
        $this->assertSame('44444444-4444-4444-8444-444444444444', $hydrated->challengeRef->toString());
        $this->assertSame(
            'The admitted evidence does not establish the contested tally.',
            $hydrated->reason->toString(),
        );
        $this->assertSame('constitutional-council', $hydrated->declaredByAuthority->toString());
        $this->assertSame(
            '2026-08-04T11:30:00+00:00',
            $hydrated->declaredAt->setTimezone(new \DateTimeZone('UTC'))->format('c'),
        );
    }

    /**
     * The v1-only window holds against the REAL written payload, not only a fixture: the writer
     * must stamp `schema_version = 1`, and anything else must be refused (ADR-T5).
     */
    public function test_the_writer_stamps_the_version_the_hydrator_accepts(): void
    {
        $this->app->make(OutboxEventAdapter::class)->enqueue(
            EventProvenance::start('22222222-2222-4222-8222-222222222222'),
            $this->event(),
        );

        $row = OutboxEvent::query()->where('event_type', 'AdjudicationFailureDeclared')->firstOrFail();
        $payload = is_array($row->payload) ? $row->payload : json_decode((string) $row->payload, true);

        $this->assertSame(1, $payload['schema_version']);

        // And the pair genuinely disagrees when the version does — proving the window is enforced
        // against the writer's own shape, not merely against a hand-built fixture.
        $payload['schema_version'] = 2;
        $this->expectException(\InvalidArgumentException::class);
        (new AdjudicationFailureDeclaredHydrator())->hydrate($payload);
    }
}
