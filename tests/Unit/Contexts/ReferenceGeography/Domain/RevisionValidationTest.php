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
use App\Contexts\ReferenceGeography\Domain\RevisionRefusal;
use App\Contexts\ReferenceGeography\Domain\RevisionRefused;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * PBDIGIT-346 G-2c, cycle 5 (v3.11 §5.3 "the digest covers … the dependency predicates and p₀ … the derived
 * classification"; §5.1 before-images "stored with the claim forever"; §5.3 "stale means re-prepare"):
 * - submission needs a VALID validation of exactly the current claims;
 * - changing a claim voids the validation; evidence and assessments do not;
 * - re-preparation voids it (the next version is validated at its own p₀);
 * - the digest covers p₀, the dependencies, the before-images and the structural flag.
 */
final class RevisionValidationTest extends TestCase
{
    private const C = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const P = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const FP = 'a3fe00f28f8fe70c388891cbf36042f428bc69a70d1efdf9d24f3cc8e129fb18';

    private static function p(string $c): PersonReference
    {
        return PersonReference::fromString(str_repeat($c, 32));
    }

    private static function claim(string $id, string $parent = self::P, string $from = '2016-01-01'): Claim
    {
        return new Claim(ClaimId::fromString($id), SlotKey::containment(ReferenceUnitId::fromString(self::C), 'admin'), ClaimValue::of($parent), new ValidInterval(ValidDate::fromString($from)), new Basis(BasisKind::Instrument, 'ev-1'));
    }

    private static function reader(int $p0 = 3): DependencyRecordingReader
    {
        $exists = new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000000900'), SlotKey::existence(ReferenceUnitId::fromString(self::P)), ClaimValue::of('exists'), new ValidInterval(ValidDate::fromString('2010-01-01')), new Basis(BasisKind::Instrument, 'act'));

        $competence = CompetentInstrument::competenceClaim();   // D-82: the evidence's issuer is competent

        return new DependencyRecordingReader(new InMemoryPublishedAtlas($p0, [
            new PublishedClaim($exists, BeforeImage::of(SlotHistory::for($exists->slot), $exists->interval, 0), 1),
            new PublishedClaim($competence, BeforeImage::of(SlotHistory::for($competence->slot), $competence->interval, 0), 2),
        ]));
    }

    private static function prepared(Claim $claim): Revision
    {
        $r = Revision::prepare(RevisionId::fromString('11111111-1111-4111-8111-111111111111'), self::p('a'));
        $r->addClaim($claim, self::p('a'));
        $r->attachEvidence(CompetentInstrument::evidence('ev-1'), self::p('a'));
        foreach (AssessmentKind::required() as $kind) {
            $r->recordAssessment(new Assessment($kind, 'satisfied', self::p('b')));
        }

        return $r;
    }

    private function assertRefused(RevisionRefusal $expected, callable $act): void
    {
        try {
            $act();
            $this->fail("expected {$expected->name}");
        } catch (RevisionRefused $e) {
            $this->assertSame($expected, $e->refusal);
        }
    }

    public function test_submission_needs_a_validation(): void
    {
        $r = self::prepared(self::claim('00000000-0000-4000-8000-000000000001'));

        $this->assertRefused(RevisionRefusal::NotValidated, fn () => $r->submit(self::p('b')));
    }

    public function test_an_invalid_revision_cannot_be_submitted(): void
    {
        $r = self::prepared(self::claim('00000000-0000-4000-8000-000000000001', parent: '7f7f7f7f-7f7f-4f7f-8f7f-7f7f7f7f7f7f'));   // unknown parent
        $result = $r->validateWith(new RevisionValidator(), self::reader());

        $this->assertFalse($result->isValid());
        $this->assertRefused(RevisionRefusal::Invalid, fn () => $r->submit(self::p('b')));
    }

    public function test_a_valid_validation_allows_submission_and_is_kept_with_the_version(): void
    {
        $r = self::prepared(self::claim('00000000-0000-4000-8000-000000000001'));
        $r->validateWith(new RevisionValidator(), self::reader());

        $r->submit(self::p('b'));

        $this->assertSame(3, $r->validation()?->dependencies->p0);
        $this->assertTrue($r->validation()?->structural);
    }

    public function test_changing_a_claim_voids_the_validation_but_evidence_and_assessments_do_not(): void
    {
        $r = self::prepared(self::claim('00000000-0000-4000-8000-000000000001'));
        $r->validateWith(new RevisionValidator(), self::reader());
        $r->attachEvidence(new EvidenceVersion('ev-2', self::FP), self::p('a'));
        $r->recordAssessment(new Assessment(AssessmentKind::Precedence, 'applied', self::p('b')));
        $this->assertNotNull($r->validation());

        $r->addClaim(new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000000002'), SlotKey::sourceName(ReferenceUnitId::fromString(self::C), 's', 'ne', 'official'), ClaimValue::of('Koshi'), new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Instrument, 'ev-1')), self::p('a'));

        $this->assertNull($r->validation());
        $this->assertRefused(RevisionRefusal::NotValidated, fn () => $r->submit(self::p('b')));
    }

    public function test_re_preparation_voids_the_validation(): void
    {
        $r = self::prepared(self::claim('00000000-0000-4000-8000-000000000001'));
        $r->validateWith(new RevisionValidator(), self::reader());
        $r->submit(self::p('b'));
        $r->markStale();

        $r->reprepare(self::p('a'));

        $this->assertNull($r->validation(), 'the next version is validated at its own p₀');
    }

    public function test_the_digest_covers_p0_and_the_validation(): void
    {
        $digest = static function (int $p0): string {
            $r = self::prepared(self::claim('00000000-0000-4000-8000-000000000001'));
            $r->validateWith(new RevisionValidator(), self::reader($p0));

            return $r->submit(self::p('b'))->digest;
        };

        $this->assertSame($digest(3), $digest(3));
        $this->assertNotSame($digest(3), $digest(4), 'p₀ is judged');
    }

    public function test_validation_is_only_allowed_in_a_draft(): void
    {
        $r = self::prepared(self::claim('00000000-0000-4000-8000-000000000001'));
        $r->validateWith(new RevisionValidator(), self::reader());
        $r->submit(self::p('b'));

        $this->assertRefused(RevisionRefusal::NotDraft, fn () => $r->validateWith(new RevisionValidator(), self::reader()));
    }
}
