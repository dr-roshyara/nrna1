<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\Objection;
use App\Contexts\ReferenceGeography\Domain\ObjectionAssessment;
use App\Contexts\ReferenceGeography\Domain\ObjectionId;
use App\Contexts\ReferenceGeography\Domain\ObjectionRefused;
use App\Contexts\ReferenceGeography\Domain\ObjectionState;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-6b (strategic baseline S1.1; D-84; D-84a; v3.11 §16): an organisation's challenge to a PUBLISHED
 * fact, with counter-evidence. It never edits the fact: the claim is unchanged until an outcome is recorded, and an
 * outcome acts only through a new, governed revision.
 * - submitted (citing at least one instrument) → assessed (authenticity, mandate, applicability) → corrected ·
 *   disputed (both: all three findings positive; both propose a revision) · rejected (at least one negative finding,
 *   with a reason); decided objections are final;
 * - D-84a: the steward who recorded it on the organisation's behalf may neither assess nor decide it.
 */
final class ObjectionTest extends TestCase
{
    private const ORG = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const CLAIM = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const REV = '5e5e5e5e-5e5e-4e5e-8e5e-5e5e5e5e5e5e';

    private static function p(string $who): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $who));
    }

    private static function submitted(): Objection
    {
        return Objection::submitOnBehalf(ObjectionId::fromString('00000000-0000-4000-8000-000000000001'), self::ORG, ClaimId::fromString(self::CLAIM),
            'The 2026 gazette moves Ward 33 to Lalitpur', ['sha256:' . str_repeat('b', 64)], self::p('recorder'));
    }

    private static function assessed(bool $authentic = true, bool $mandate = true, bool $applicable = true): Objection
    {
        $o = self::submitted();
        $o->assess(new ObjectionAssessment($authentic, $mandate, $applicable), self::p('assessor'));

        return $o;
    }

    public function test_a_submission_cites_an_instrument_and_changes_no_fact(): void
    {
        $o = self::submitted();
        $this->assertSame(ObjectionState::Submitted, $o->state());
        $this->assertTrue($o->target()->equals(ClaimId::fromString(self::CLAIM)));
        $this->assertNull($o->revision());

        $this->expectException(\InvalidArgumentException::class);
        Objection::submitOnBehalf(ObjectionId::fromString('00000000-0000-4000-8000-000000000002'), self::ORG, ClaimId::fromString(self::CLAIM), 'no evidence', [], self::p('recorder'));
    }

    public function test_correction_and_dispute_need_every_finding_positive_and_propose_a_revision(): void
    {
        $corrected = self::assessed();
        $corrected->correct(RevisionId::fromString(self::REV), self::p('decider'));
        $this->assertSame([ObjectionState::Corrected, self::REV], [$corrected->state(), $corrected->revision()?->value()]);

        $disputed = self::assessed();
        $disputed->dispute(RevisionId::fromString(self::REV), self::p('decider'));
        $this->assertSame(ObjectionState::Disputed, $disputed->state());

        foreach ([[false, true, true], [true, false, true], [true, true, false]] as $f) {
            try {
                self::assessed(...$f)->correct(RevisionId::fromString(self::REV), self::p('decider'));
                $this->fail('corrected with a negative finding');
            } catch (ObjectionRefused) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_a_rejection_needs_a_negative_finding_and_a_reason(): void
    {
        $o = self::assessed(mandate: false);
        $o->reject('The issuer had no mandate for ward boundaries', self::p('decider'));
        $this->assertSame([ObjectionState::Rejected, 'The issuer had no mandate for ward boundaries'], [$o->state(), $o->reason()]);

        try {
            self::assessed()->reject('we prefer not to', self::p('decider'));
            $this->fail('rejected an authentic, mandated, applicable objection');
        } catch (ObjectionRefused) {
            $this->addToAssertionCount(1);
        }
        $this->expectException(\InvalidArgumentException::class);
        self::assessed(applicable: false)->reject('  ', self::p('decider'));
    }

    public function test_no_outcome_before_assessment_and_a_decided_objection_is_final(): void
    {
        foreach ([
            static fn () => self::submitted()->correct(RevisionId::fromString(self::REV), self::p('decider')),
            static fn () => self::submitted()->reject('x', self::p('decider')),
        ] as $i => $act) {
            try {
                $act();
                $this->fail("outcome {$i} before assessment");
            } catch (ObjectionRefused) {
                $this->addToAssertionCount(1);
            }
        }
        $done = self::assessed();
        $done->dispute(RevisionId::fromString(self::REV), self::p('decider'));
        $this->expectException(ObjectionRefused::class);
        $done->assess(new ObjectionAssessment(false, false, false), self::p('assessor'));
    }

    public function test_d84a_the_recording_steward_may_neither_assess_nor_decide(): void
    {
        try {
            self::submitted()->assess(new ObjectionAssessment(true, true, true), self::p('recorder'));
            $this->fail('the recorder assessed');
        } catch (ObjectionRefused) {
            $this->addToAssertionCount(1);
        }
        $this->expectException(ObjectionRefused::class);
        self::assessed()->correct(RevisionId::fromString(self::REV), self::p('recorder'));
    }

    public function test_it_survives_storage_only_through_its_own_rebuild_path(): void
    {
        $o = self::assessed(applicable: false);
        $o->reject('not applicable to 2020', self::p('decider'));
        foreach ([self::submitted(), self::assessed(), $o] as $original) {
            $this->assertEquals($original->snapshot(), Objection::fromSnapshot($original->snapshot())->snapshot());
        }

        $this->expectException(\UnexpectedValueException::class);
        Objection::fromSnapshot(new \App\Contexts\ReferenceGeography\Domain\ObjectionSnapshot(...array_merge(get_object_vars($o->snapshot()), ['assessment' => null])));
    }
}
