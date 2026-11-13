<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Candidate;
use App\Contexts\ReferenceGeography\Domain\CandidateId;
use App\Contexts\ReferenceGeography\Domain\CandidateRefused;
use App\Contexts\ReferenceGeography\Domain\CandidateState;
use App\Contexts\ReferenceGeography\Domain\CandidateSubmitter;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-6a (v3.11 §16; strategic baseline: D-31, Q-1 (a)+(c); completion §3): a submitted missing or changed
 * place. It is a REQUEST, never a fact: nothing it says becomes geography except through a verified, separately
 * governed Revision.
 * - submitted → under verification → accepted (names the revision prepared from it) · linked (a duplicate of a
 *   primary candidate) · rejected (with a reason); decided candidates are final;
 * - acceptance only after verification has started; a link never points at itself or at another link (no chains);
 * - every act records who did it (a platform person reference, D-225); a submitter is an organisation or a steward.
 */
final class CandidateTest extends TestCase
{
    private static int $n = 0;

    private static function p(string $who): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $who));
    }

    private static function submitted(): Candidate
    {
        self::$n++;

        return Candidate::submit(
            CandidateId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)),
            CandidateSubmitter::steward(self::p('steward')),
            'Ward 33 of Kathmandu was created by the 2026 act',
            null,
            ['sha256:' . str_repeat('a', 64)],
            self::p('steward'),
        );
    }

    public function test_a_submission_is_recorded_and_creates_nothing_else(): void
    {
        $c = self::submitted();

        $this->assertSame(CandidateState::Submitted, $c->state());
        $this->assertSame('Ward 33 of Kathmandu was created by the 2026 act', $c->summary());
        $this->assertNull($c->revision());
        $this->assertTrue($c->recordedBy()->equals(self::p('steward')));
    }

    public function test_verification_then_acceptance_names_the_revision_and_who_decided(): void
    {
        $c = self::submitted();
        $c->startVerification(self::p('steward'));
        $revision = RevisionId::fromString('3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f');
        $c->accept($revision, self::p('second'));

        $this->assertSame(CandidateState::Accepted, $c->state());
        $this->assertTrue($c->revision()?->equals($revision));
        $this->assertTrue($c->decidedBy()?->equals(self::p('second')));
    }

    public function test_acceptance_without_verification_is_refused(): void
    {
        $this->expectException(CandidateRefused::class);
        self::submitted()->accept(RevisionId::fromString('3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f'), self::p('steward'));
    }

    public function test_a_rejection_needs_a_reason(): void
    {
        $c = self::submitted();
        $c->reject('No competent instrument establishes this ward', self::p('steward'));
        $this->assertSame(CandidateState::Rejected, $c->state());
        $this->assertSame('No competent instrument establishes this ward', $c->reason());

        $this->expectException(\InvalidArgumentException::class);
        self::submitted()->reject('  ', self::p('steward'));
    }

    public function test_a_duplicate_is_linked_to_its_primary_never_to_itself_or_to_another_link(): void
    {
        $primary = self::submitted();
        $duplicate = self::submitted();
        $duplicate->linkTo($primary, self::p('steward'));
        $this->assertSame(CandidateState::Linked, $duplicate->state());
        $this->assertTrue($duplicate->linkedTo()?->equals($primary->id()));

        foreach ([[self::submitted(), null], [self::submitted(), $duplicate]] as [$c, $target]) {
            try {
                $c->linkTo($target ?? $c, self::p('steward'));
                $this->fail('linked to itself or to a link');
            } catch (CandidateRefused) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_a_decided_candidate_is_final(): void
    {
        $c = self::submitted();
        $c->reject('duplicate of an existing unit', self::p('steward'));

        foreach ([
            static fn () => $c->startVerification(self::p('steward')),
            static fn () => $c->reject('again', self::p('steward')),
            static fn () => $c->linkTo(self::submitted(), self::p('steward')),
        ] as $i => $act) {
            try {
                $act();
                $this->fail("act {$i} changed a decided candidate");
            } catch (CandidateRefused) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_a_submitter_is_an_organisation_or_a_steward(): void
    {
        $this->assertSame('organisation', CandidateSubmitter::organisation('0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60')->kind);
        $this->assertSame('steward', CandidateSubmitter::steward(self::p('x'))->kind);

        $this->expectException(\InvalidArgumentException::class);
        CandidateSubmitter::organisation('not an id');
    }

    public function test_a_candidate_survives_storage_only_through_its_own_rebuild_path(): void
    {
        $c = self::submitted();
        $c->startVerification(self::p('steward'));
        $c->accept(RevisionId::fromString('3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f'), self::p('second'));
        $subject = ReferenceUnitId::fromString('8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d');
        $change = Candidate::submit(CandidateId::fromString('00000000-0000-4000-8000-999999999999'), CandidateSubmitter::organisation('0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60'), 'Rename', $subject, [], self::p('steward'));

        foreach ([$c, $change] as $original) {
            $this->assertEquals($original->snapshot(), Candidate::fromSnapshot($original->snapshot())->snapshot());
        }

        $this->expectException(\UnexpectedValueException::class);
        Candidate::fromSnapshot(new \App\Contexts\ReferenceGeography\Domain\CandidateSnapshot(...array_merge(get_object_vars($c->snapshot()), ['revision' => null])));
    }
}
