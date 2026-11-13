<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Assessment;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\EvidenceVersion;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * Q-DS, Option A (Principal decision D-229, 2026-11-13; amends v3.11 I-4, consistent with D-82, D-227):
 * - an Existence claim always cites a competent establishing instrument; a duplicate search is NOT a basis;
 * - a duplicate search is recorded IN ADDITION, as a Revision assessment (the existing mechanism), covered by the
 *   content-version digest; it never bypasses issuer competence;
 * - "establishing instrument" is not "the founding document": a later competent instrument qualifies.
 */
final class DuplicateSearchEvidenceTest extends TestCase
{
    private const UNIT = '3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c';
    private const FP = 'a3fe00f28f8fe70c388891cbf36042f428bc69a70d1efdf9d24f3cc8e129fb18';

    private static function p(string $c): PersonReference
    {
        return PersonReference::fromString(str_repeat($c, 32));
    }

    private static function existence(string $evidenceId, string $from = '1950-01-01'): Claim
    {
        return new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000005001'), SlotKey::existence(ReferenceUnitId::fromString(self::UNIT)), ClaimValue::of('exists'), new ValidInterval(ValidDate::fromString($from)), new Basis(BasisKind::Instrument, $evidenceId));
    }

    private static function reader(): DependencyRecordingReader
    {
        $c = CompetentInstrument::competenceClaim();

        return new DependencyRecordingReader(new InMemoryPublishedAtlas(1, [new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, 0), 1)]));
    }

    /** A revision establishing the unit on $evidence, with the required assessments and, optionally, a duplicate search. */
    private static function revision(EvidenceVersion $evidence, bool $duplicateSearch): Revision
    {
        $r = Revision::prepare(RevisionId::fromString('9d9d9d9d-9d9d-4d9d-8d9d-9d9d9d9d9d9d'), self::p('a'));
        $r->addClaim(self::existence($evidence->id), self::p('a'));
        $r->attachEvidence($evidence, self::p('a'));
        foreach (AssessmentKind::required() as $kind) {
            $r->recordAssessment(new Assessment($kind, 'satisfied', self::p('b')));
        }
        if ($duplicateSearch) {
            $r->recordAssessment(new Assessment(AssessmentKind::DuplicateSearch, 'no existing identity found: names, identifiers and location searched', self::p('b')));
        }

        return $r;
    }

    public function test_a_duplicate_search_is_not_a_basis_and_existence_needs_an_instrument(): void
    {
        $this->assertNull(BasisKind::tryFrom('duplicate-search-assessment'), 'the duplicate search is no longer a basis kind');
        foreach (BasisKind::cases() as $kind) {
            if ($kind === BasisKind::Instrument) {
                continue;
            }
            try {
                new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000005002'), SlotKey::existence(ReferenceUnitId::fromString(self::UNIT)), ClaimValue::of('exists'), new ValidInterval(ValidDate::fromString('1950-01-01')), new Basis($kind, 'ref'));
                $this->fail("An existence claim on a '{$kind->value}' basis must be refused (I-4 as amended by D-229).");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_a_duplicate_search_is_recorded_as_a_supplementary_assessment_and_frozen_with_the_version(): void
    {
        $evidence = CompetentInstrument::evidence('ev-1');
        $digest = static function (bool $duplicateSearch) use ($evidence): string {
            $r = self::revision($evidence, $duplicateSearch);
            $r->validateWith(new RevisionValidator(), self::reader());

            return $r->submit(self::p('b'))->digest;
        };

        $this->assertNotContains(AssessmentKind::DuplicateSearch, AssessmentKind::required(), 'v3.11 does not define when it is applicable: recorded, not demanded');
        $this->assertNotSame($digest(false), $digest(true), 'the recorded duplicate search is part of what is approved');
        $this->assertSame(BasisKind::Instrument, self::revision($evidence, true)->claims()[0]->basis->kind, 'it never becomes the claim\'s basis');
    }

    public function test_a_duplicate_search_does_not_bypass_issuer_competence(): void
    {
        $r = self::revision(new EvidenceVersion('ev-x', self::FP, 'unrecognised-authority', ValidDate::fromString('2015-01-01')), true);

        $result = $r->validateWith(new RevisionValidator(), self::reader());

        $this->assertSame(['D-82'], array_map(static fn ($v): string => $v->rule, $result->violations));
    }

    public function test_a_later_competent_instrument_establishes_a_long_existing_place(): void
    {
        // the place exists from 1950; the competent instrument establishing that fact was issued in 2015
        $r = self::revision(new EvidenceVersion('ev-2015', self::FP, CompetentInstrument::ISSUER, ValidDate::fromString('2015-06-01')), true);

        $this->assertTrue($r->validateWith(new RevisionValidator(), self::reader())->isValid(), 'not only the founding document');
    }
}
