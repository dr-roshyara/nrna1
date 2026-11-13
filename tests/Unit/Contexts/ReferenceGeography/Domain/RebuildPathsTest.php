<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Approval;
use App\Contexts\ReferenceGeography\Domain\Assessment;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\JudgmentReason;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PriorValue;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionSnapshot;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\RosterEntry;
use App\Contexts\ReferenceGeography\Domain\RosterEntryKind;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * PBDIGIT-346 G-4b (G-4 interpretation §3): persistence needs exact rebuild paths, and a rebuild must never accept a
 * state the domain could not have produced.
 * - SlotKey::decode: rebuilt through the shape's own factory; refused unless it re-encodes to exactly the input;
 * - BeforeImage::fromSegments: contiguous, ascending segments only;
 * - StewardRoster::fromEntries: versions 1..n without gaps, replayed through the domain's own writes;
 * - Revision snapshot / rebuild: round trip; a submitted or stale revision must reproduce its stored digest.
 */
final class RebuildPathsTest extends TestCase
{
    private const U = '3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c';

    private static function u(): ReferenceUnitId
    {
        return ReferenceUnitId::fromString(self::U);
    }

    private static function p(string $label): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $label));
    }

    /** @return list<SlotKey> one key of every shape */
    private static function everyShape(): array
    {
        $u = self::u();

        return [
            SlotKey::existence($u), SlotKey::containment($u, 'admin'), SlotKey::classification($u, 'admin'), SlotKey::boundary($u, 'survey|dept=7:'),
            SlotKey::succession($u, 'division:' . self::U), SlotKey::identifier($u, 'iso-3166-1'), SlotKey::grouping($u, 'm49'), SlotKey::admission($u),
            SlotKey::sourceName($u, 'gazette', 'ne', 'official'), SlotKey::sourceNameValue($u, 'gazette', 'ne', 'alternative', 'कोशी'), SlotKey::cldrName($u, 'de', 'standard'),
            SlotKey::cldrInheritance('46'), SlotKey::issuer('npl-govt'), SlotKey::mandate('constitution-2015'), SlotKey::competence('npl-govt', 'territorial'),
            SlotKey::precedence('constitution-over-act'), SlotKey::dispute(ClaimId::fromString('00000000-0000-4000-8000-000000000001')), SlotKey::reconciliation($u),
            SlotKey::profileScheme($u), SlotKey::profileTier($u, 'admin', 4), SlotKey::profileClass($u, 'admin', 'district'), SlotKey::platformDefaultLocale(),
        ];
    }

    public function test_every_slot_key_shape_round_trips_through_decode(): void
    {
        foreach (self::everyShape() as $key) {
            $this->assertTrue(SlotKey::decode($key->encoded())->equals($key), $key->encoded());
        }
    }

    public function test_a_tampered_or_impossible_encoding_is_refused(): void
    {
        $u = self::U;
        foreach ([
            'NOPE|unit=36:' . $u . '|' => 'unknown kind',
            'EXISTENCE|unit=35:' . $u . '|' => 'wrong length',
            'EXISTENCE|unit=36:' . $u => 'unterminated',
            'EXISTENCE|unit=36:' . $u . '|extra' => 'trailing data',
            'EXISTENCE|entry=36:' . $u . '|' => 'a label the shape does not have',
            'CONTAINMENT|unit=36:' . $u . '|' => 'a missing component',
            'PROFILE_TIER|country_entry=36:' . $u . '|scheme=5:admin|position=2:04|' => 'a non-canonical position',
            'EXISTENCE|unit=3:abc|' => 'not a unit id',
        ] as $encoded => $why) {
            try {
                SlotKey::decode($encoded);
                $this->fail("must be refused: {$why}");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_a_before_image_round_trips_and_only_contiguous_segments_are_accepted(): void
    {
        $iv = static fn (string $f, ?string $t = null): ValidInterval => new ValidInterval(ValidDate::fromString($f), $t === null ? null : ValidDate::fromString($t));
        $segments = [[$iv('2016-01-01', '2018-01-01'), PriorValue::of('district')], [$iv('2018-01-01'), PriorValue::absent()]];
        $image = BeforeImage::fromSegments($segments);

        $this->assertEquals($segments, $image->segments());
        $this->assertTrue($image->hadValue('district', $iv('2017-01-01', '2017-02-01')));
        foreach ([[[$iv('2018-01-01'), PriorValue::none()], [$iv('2016-01-01', '2018-01-01'), PriorValue::none()]], [[$iv('2016-01-01', '2017-01-01'), PriorValue::none()], [$iv('2018-01-01'), PriorValue::none()]], []] as $bad) {
            try {
                BeforeImage::fromSegments($bad);
                $this->fail('must be refused');
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_a_roster_rebuilt_from_its_entries_answers_exactly_as_the_original(): void
    {
        $owner = self::p('owner');
        $r = StewardRoster::empty();
        $r->appointSteward(self::p('a'), $owner);
        $r->appointMatterReviewer(self::p('r'), RevisionId::fromString('9d9d9d9d-9d9d-4d9d-8d9d-9d9d9d9d9d9d'), $owner);
        $r->appointSteward(self::p('b'), $owner);
        $r->recordMerge('m-1', [self::p('a'), self::p('x')]);
        $r->declareConflict(self::p('b'), RevisionId::fromString('9d9d9d9d-9d9d-4d9d-8d9d-9d9d9d9d9d9d'), true);
        $r->endSteward(self::p('b'), $owner);
        $r->recordReversal('m-1');

        $rebuilt = StewardRoster::fromEntries($r->entries());

        $this->assertEquals($r->entries(), $rebuilt->entries());
        $this->assertSame($r->thresholdActivatedAt(), $rebuilt->thresholdActivatedAt());
        for ($v = 0; $v <= $r->version(); $v++) {
            $this->assertSame($r->isStewardAt(self::p('b'), $v), $rebuilt->isStewardAt(self::p('b'), $v));
            $this->assertSame($r->knownSameAt(self::p('a'), self::p('x'), $v), $rebuilt->knownSameAt(self::p('a'), self::p('x'), $v));
        }
    }

    public function test_a_roster_history_with_a_gap_or_an_impossible_write_is_refused(): void
    {
        $owner = self::p('owner');
        foreach ([
            'gap' => [new RosterEntry(1, RosterEntryKind::StewardAppointed, self::p('a'), $owner), new RosterEntry(3, RosterEntryKind::StewardAppointed, self::p('b'), $owner)],
            'end of a non-steward' => [new RosterEntry(1, RosterEntryKind::StewardEnded, self::p('a'), $owner)],
            'a notice twice' => [new RosterEntry(1, RosterEntryKind::PersonMergeReversed, mergeId: 'm-1'), new RosterEntry(2, RosterEntryKind::PersonMergeReversed, mergeId: 'm-1')],
        ] as $why => $entries) {
            try {
                StewardRoster::fromEntries($entries);
                $this->fail("must be refused: {$why}");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    /** A submitted revision with evidence, assessments, a flag, a validation and an approval; then re-prepared as version 2. */
    private static function revisionHistory(): array
    {
        $c = CompetentInstrument::competenceClaim();
        $reader = static fn (): DependencyRecordingReader => new DependencyRecordingReader(new InMemoryPublishedAtlas(1, [new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, 0), 1)]));
        $claim = new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000008001'), SlotKey::existence(self::u()), ClaimValue::of('exists'), new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Instrument, 'ev-1'));
        $r = Revision::prepare(RevisionId::fromString('9d9d9d9d-9d9d-4d9d-8d9d-9d9d9d9d9d9d'), self::p('a'));
        $r->addClaim($claim, self::p('a'));
        $r->attachEvidence(CompetentInstrument::evidence('ev-1'), self::p('a'));
        $r->flagJudgmentMatter(self::p('z'), JudgmentReason::CompetingInstruments);
        $states = ['draft' => $r->snapshot()];
        foreach (AssessmentKind::required() as $kind) {
            $r->recordAssessment(new Assessment($kind, 'satisfied', self::p('s')));
        }
        $r->validateWith(new RevisionValidator(), $reader());
        $r->submit(self::p('s'));
        $r->approve(new Approval(1, 3, self::p('x')));
        $states['submitted'] = $r->snapshot();
        $r->markStale();
        $r->reprepare(self::p('b'));
        $states['reprepared'] = $r->snapshot();

        return $states;
    }

    public function test_a_revision_round_trips_through_its_snapshot_in_every_state(): void
    {
        foreach (self::revisionHistory() as $state => $snapshot) {
            $this->assertEquals($snapshot, Revision::fromSnapshot($snapshot)->snapshot(), $state);
        }

        $rebuilt = Revision::fromSnapshot(self::revisionHistory()['submitted']);
        $this->assertSame(1, $rebuilt->currentVersion()?->number);
        $this->assertCount(1, $rebuilt->countingApprovals());
        $this->assertTrue($rebuilt->isJudgmentMatter());
        $this->assertTrue($rebuilt->assessor()?->equals(self::p('s')));
        $this->assertEqualsCanonicalizing([self::p('a')->value(), self::p('s')->value()], array_map(static fn ($p) => $p->value(), $rebuilt->excludedPersons()));
    }

    public function test_a_stored_revision_with_two_overlapping_claims_for_one_slot_is_refused(): void
    {
        $s = self::revisionHistory()['draft'];
        $twin = new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000008002'), $s->claims[0]->slot, ClaimValue::of('exists'), new ValidInterval(ValidDate::fromString('2017-01-01')), $s->claims[0]->basis);

        $this->expectException(\InvalidArgumentException::class);
        Revision::fromSnapshot(new RevisionSnapshot(...[...get_object_vars($s), 'claims' => [$s->claims[0], $twin]]));
    }

    public function test_a_submitted_revision_whose_stored_content_does_not_match_its_digest_is_refused(): void
    {
        $s = self::revisionHistory()['submitted'];
        $tampered = new Claim($s->claims[0]->id, $s->claims[0]->slot, ClaimValue::of('changed'), $s->claims[0]->interval, $s->claims[0]->basis);

        $this->expectException(\InvalidArgumentException::class);
        Revision::fromSnapshot(new RevisionSnapshot(...[...get_object_vars($s), 'claims' => [$tampered]]));
    }
}
