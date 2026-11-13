<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\BoundaryNotClosed;
use App\Contexts\ReferenceGeography\Application\Ports\GeographyReadSource;
use App\Contexts\ReferenceGeography\Application\Ports\TimeBoundary;
use App\Contexts\ReferenceGeography\Application\Ports\PublicationRecord;
use App\Contexts\ReferenceGeography\Application\Published\Knowledge;
use App\Contexts\ReferenceGeography\Application\Published\KnowledgeNotAvailable;
use App\Contexts\ReferenceGeography\Application\PublishedGeography;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-5a (design §2, §2.1): the published read model, over fakes.
 * - knowledge: a position up to the committed one, or a time τ closed through by the ledger and mapped to the highest
 *   position recorded at or before τ; anything beyond is refused (KnowledgeNotAvailable), never answered partially;
 * - every query re-checks its knowledge, so a fabricated position beyond the boundary is refused;
 * - answers are strings and DTOs (the published language), built from domain belief.
 */
final class PublishedGeographyTest extends TestCase
{
    private const NEPAL = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const BAGMATI = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const KOSHI = '6c5d4e3f-2a1b-4c9d-8e7f-6a5b4c3d2e1f';
    private const OLD_ID = '1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d';

    private static int $n = 0;

    private static function claim(SlotKey $slot, ?string $value, string $from = '2015-09-20', ?string $to = null): Claim
    {
        self::$n++;

        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), $slot,
            $value === null ? ClaimValue::none() : ClaimValue::of($value),
            new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to)), new Basis(BasisKind::Instrument, 'act'));
    }

    /** A fake source: slot histories, recorded times per position, a committed position. */
    private static function source(int $committed = 4): GeographyReadSource
    {
        $u = static fn (string $id): ReferenceUnitId => ReferenceUnitId::fromString($id);
        $slots = [];
        $add = static function (SlotKey $slot, Claim $c, int $p) use (&$slots): void {
            $slots[$slot->encoded()] = ($slots[$slot->encoded()] ?? SlotHistory::for($slot))->with($c, $p);
        };
        $add(SlotKey::existence($u(self::NEPAL)), self::claim(SlotKey::existence($u(self::NEPAL)), 'exists', '1768-01-01'), 1);
        $add(SlotKey::existence($u(self::BAGMATI)), self::claim(SlotKey::existence($u(self::BAGMATI)), 'exists'), 2);
        $add(SlotKey::containment($u(self::BAGMATI), 'admin'), self::claim(SlotKey::containment($u(self::BAGMATI), 'admin'), self::NEPAL), 2);
        $add(SlotKey::existence($u(self::KOSHI)), self::claim(SlotKey::existence($u(self::KOSHI)), 'exists'), 3);
        $add(SlotKey::containment($u(self::KOSHI), 'admin'), self::claim(SlotKey::containment($u(self::KOSHI), 'admin'), self::NEPAL), 3);
        $add(SlotKey::reconciliation($u(self::OLD_ID)), self::claim(SlotKey::reconciliation($u(self::OLD_ID)), self::BAGMATI, '1900-01-01'), 4);

        return new class ($slots, $committed) implements GeographyReadSource {
            /** @param array<string, SlotHistory> $slots */
            public function __construct(private readonly array $slots, private readonly int $committed)
            {
            }

            public function committedPosition(): int
            {
                return $this->committed;
            }

            public function positionAtTime(string $tau): int
            {
                return ['2026-01-01 00:00:00+00' => 0, '2026-03-01 00:00:00+00' => 2][$tau] ?? $this->committed;
            }

            public function publication(int $position): ?PublicationRecord
            {
                return $position < 1 ? null : new PublicationRecord($position, "2026-02-0{$position} 10:00:00+00", "revision-{$position}");
            }

            public function unitsWithExistence(int $p): array
            {
                $units = [];
                foreach ($this->slots as $h) {
                    if ($h->slot()->kind()->value === 'EXISTENCE' && $h->latest($p) !== null) {
                        $units[] = $h->slot()->subject();
                    }
                }

                return $units;
            }

            public function slotHistory(SlotKey $slot, int $p): SlotHistory
            {
                return $this->slots[$slot->encoded()] ?? SlotHistory::for($slot);
            }

            public function subjectSlots(ReferenceUnitId $unit, int $p): array
            {
                return array_values(array_filter($this->slots, static fn (SlotHistory $h): bool => $h->slot()->subject()?->equals($unit) === true && $h->latest($p) !== null));
            }

            public function disputesForSubject(ReferenceUnitId $unit, int $p): array
            {
                return [];
            }

            public function containedIn(ReferenceUnitId $parent, string $scheme, int $p): array
            {
                $out = [];
                foreach ($this->slots as $h) {
                    if ($h->slot()->kind()->value === 'CONTAINMENT' && $h->slot()->component('scheme') === $scheme && $h->latest($p)?->value->value() === $parent->value()) {
                        $out[] = $h->slot()->subject();
                    }
                }

                return $out;
            }
        };
    }

    /** Only the time boundary: the read side cannot even reach the publication lock (PublicationLedger::withLock). */
    private static function ledger(?string $refusal = null): TimeBoundary
    {
        return new class ($refusal) implements TimeBoundary {
            public function __construct(private readonly ?string $refusal)
            {
            }

            public function closeThrough(?string $tau): string
            {
                if ($this->refusal !== null) {
                    throw new BoundaryNotClosed($this->refusal);
                }

                return (string) $tau;
            }
        };
    }

    private static function geography(int $committed = 4, ?string $refusal = null): PublishedGeography
    {
        return new PublishedGeography(self::source($committed), self::ledger($refusal));
    }

    public function test_knowledge_at_a_committed_position_is_given_and_beyond_it_refused(): void
    {
        $this->assertSame(3, self::geography()->knowledgeAt(3)->position);
        $this->assertSame(0, self::geography()->knowledgeAt(0)->position, 'nothing published yet is a complete answer');

        $this->expectException(KnowledgeNotAvailable::class);
        self::geography()->knowledgeAt(5);
    }

    public function test_a_negative_position_is_refused(): void
    {
        $this->expectException(KnowledgeNotAvailable::class);
        self::geography()->knowledgeAt(-1);
    }

    public function test_knowledge_at_a_time_closes_through_it_then_maps_it_to_a_position(): void
    {
        $k = self::geography()->knowledgeAtTime('2026-03-01 00:00:00+00');

        $this->assertSame(2, $k->position);
        $this->assertSame('2026-03-01 00:00:00+00', $k->asOfTime);
    }

    public function test_a_time_that_cannot_be_closed_is_refused_not_answered(): void
    {
        $this->expectException(KnowledgeNotAvailable::class);
        $this->expectExceptionMessage('τ is in the future');
        self::geography(refusal: 'τ is in the future')->knowledgeAtTime('2099-01-01 00:00:00+00');
    }

    public function test_a_fabricated_position_beyond_the_boundary_is_refused_by_every_query(): void
    {
        $forged = new Knowledge(9);
        foreach ([
            static fn (PublishedGeography $g) => $g->placesAt('2020-01-01', $forged),
            static fn (PublishedGeography $g) => $g->place(self::NEPAL, '2020-01-01', $forged),
            static fn (PublishedGeography $g) => $g->childrenOf(self::NEPAL, 'admin', '2020-01-01', $forged),
            static fn (PublishedGeography $g) => $g->resolve(self::OLD_ID, $forged),
        ] as $i => $query) {
            try {
                $query(self::geography());
                $this->fail("query {$i} answered beyond the boundary");
            } catch (KnowledgeNotAvailable) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_places_at_t_are_those_believed_to_exist_as_known_at_p(): void
    {
        $g = self::geography();

        $this->assertSame([self::NEPAL, self::BAGMATI], $g->placesAt('2020-01-01', $g->knowledgeAt(2)));
        $this->assertSame([self::NEPAL, self::KOSHI, self::BAGMATI], $g->placesAt('2020-01-01', $g->knowledgeAt(3)), 'sorted by id');
        $this->assertSame([self::NEPAL], $g->placesAt('2000-01-01', $g->knowledgeAt(3)), 'provinces did not exist in 2000');
    }

    public function test_a_place_profile_is_published_language_only(): void
    {
        $g = self::geography();
        $place = $g->place(self::BAGMATI, '2020-01-01', $g->knowledgeAt(3));

        $this->assertNotNull($place);
        $this->assertSame(self::BAGMATI, $place->unitId);
        $this->assertSame('2020-01-01', $place->validAt);
        $this->assertSame(3, $place->position);
        $this->assertTrue($place->exists);
        $this->assertSame(['admin' => self::NEPAL], $place->parents);
        $this->assertNull($place->mergedInto);
        $this->assertNull($g->place(self::KOSHI, '2020-01-01', $g->knowledgeAt(2)), 'not yet published at p = 2');
    }

    public function test_children_are_the_places_whose_parent_in_the_scheme_is_believed_to_be_the_place(): void
    {
        $g = self::geography();

        $this->assertSame([self::KOSHI, self::BAGMATI], $g->childrenOf(self::NEPAL, 'admin', '2020-01-01', $g->knowledgeAt(4)));
        $this->assertSame([], $g->childrenOf(self::NEPAL, 'admin', '2000-01-01', $g->knowledgeAt(4)), 'no containment claim covers 2000');
        $this->assertSame([], $g->childrenOf(self::NEPAL, 'electoral', '2020-01-01', $g->knowledgeAt(4)));
    }

    public function test_resolve_follows_one_merge_and_says_when_it_became_known(): void
    {
        $g = self::geography();

        $merged = $g->resolve(self::OLD_ID, $g->knowledgeAt(4));
        $this->assertTrue($merged->merged);
        $this->assertSame(self::OLD_ID, $merged->requested);
        $this->assertSame(self::BAGMATI, $merged->canonical);
        $this->assertSame(4, $merged->knownAtPosition);
        $this->assertSame('2026-02-04 10:00:00+00', $merged->recordedAt);
        $this->assertSame('revision-4', $merged->revisionId);

        $before = $g->resolve(self::OLD_ID, $g->knowledgeAt(3));
        $this->assertFalse($before->merged);
        $this->assertSame(self::OLD_ID, $before->canonical, 'unmerged: the identity resolves to itself');
    }
}
