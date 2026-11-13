<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Approval;
use App\Contexts\ReferenceGeography\Domain\Assessment;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\EvidenceVersion;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionRefusal;
use App\Contexts\ReferenceGeography\Domain\RevisionRefused;
use App\Contexts\ReferenceGeography\Domain\RevisionState;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-2a (v3.11 §5 R-1, §5.3 content versions, §8 approvals, §16 Revision):
 * - a revision is prepared as a draft; R-1: no two claims for one slot with overlapping intervals;
 * - submission (by the assessing steward) freezes a content version whose digest covers the claims and intervals,
 *   the evidence versions relied on (id + fingerprint), the assessments with outcomes, and the preparers and assessor;
 * - a submitted revision cannot change; re-preparation (after staleness, or to change anything) opens a new version;
 * - an approval names (revision, content version, roster version); only approvals naming the CURRENT version count;
 * - `excluded(r)` = everyone who prepared, assessed or submitted ANY version (accumulated; for G-3);
 * - lifecycle: draft → submitted → stale / withdrawn; publication is G-4. There is no "approved" state: approvals
 *   are records counted at publication (§8).
 */
final class RevisionTest extends TestCase
{
    private const UNIT = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const FP = 'a3fe00f28f8fe70c388891cbf36042f428bc69a70d1efdf9d24f3cc8e129fb18';

    private static int $n = 0;

    private static function p(string $hexChar): PersonReference
    {
        return PersonReference::fromString(str_repeat($hexChar, 32));
    }

    private static function claim(string $parent = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d', string $from = '2015-09-20', ?string $to = null, string $scheme = 'admin', string $evidence = 'ev-1'): Claim
    {
        self::$n++;

        return new Claim(
            ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)),
            SlotKey::containment(ReferenceUnitId::fromString(self::UNIT), $scheme),
            ClaimValue::of($parent),
            new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to)),
            new Basis(BasisKind::Instrument, $evidence),
        );
    }

    private static function ready(?Claim $claim = null): Revision
    {
        $r = Revision::prepare(RevisionId::fromString('11111111-1111-4111-8111-111111111111'), self::p('a'));
        $r->addClaim($claim ?? self::claim(), self::p('a'));
        $r->attachEvidence(new EvidenceVersion('ev-1', self::FP), self::p('a'));
        foreach ([AssessmentKind::Authenticity, AssessmentKind::Competence, AssessmentKind::Applicability] as $kind) {
            $r->recordAssessment(new Assessment($kind, 'satisfied', self::p('b')));
        }

        return self::validated($r);
    }

    /**
     * Since G-2c, submission needs a valid validation of the current claims. The test atlas holds the claims' parent
     * (existing from 2000), so the containment claims here are valid.
     */
    private static function validated(Revision $r): Revision
    {
        $parent = new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000000999'), SlotKey::existence(ReferenceUnitId::fromString('8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d')), ClaimValue::of('exists'), new ValidInterval(ValidDate::fromString('2000-01-01')), new Basis(BasisKind::Instrument, 'act'));
        $atlas = new InMemoryPublishedAtlas(1, [new PublishedClaim($parent, BeforeImage::of(SlotHistory::for($parent->slot), $parent->interval, 0), 1)]);
        $r->validateWith(new RevisionValidator(), new DependencyRecordingReader($atlas));

        return $r;
    }

    private static function assessAll(Revision $r, string $by): void
    {
        foreach ([AssessmentKind::Authenticity, AssessmentKind::Competence, AssessmentKind::Applicability] as $kind) {
            $r->recordAssessment(new Assessment($kind, 'satisfied', self::p($by)));
        }
    }

    private function assertRefused(RevisionRefusal $expected, callable $act, string $message = ''): void
    {
        try {
            $act();
            $this->fail("expected {$expected->name} {$message}");
        } catch (RevisionRefused $e) {
            $this->assertSame($expected, $e->refusal);
        }
    }

    public function test_a_revision_starts_as_a_draft_with_no_version(): void
    {
        $r = Revision::prepare(RevisionId::fromString('11111111-1111-4111-8111-111111111111'), self::p('a'));

        $this->assertSame(RevisionState::Draft, $r->state());
        $this->assertNull($r->currentVersion());
    }

    public function test_r1_no_two_claims_for_one_slot_with_overlapping_intervals(): void
    {
        $r = self::ready();

        $this->assertRefused(RevisionRefusal::SlotOverlapInRevision, fn () => $r->addClaim(self::claim(from: '2016-01-01'), self::p('a')));
        $r->addClaim(self::claim(from: '2010-01-01', to: '2015-09-20'), self::p('a'));   // disjoint: allowed
        $r->addClaim(self::claim(from: '2016-01-01', scheme: 'electoral'), self::p('a'));   // another slot: allowed
        $this->assertCount(3, $r->claims());
    }

    public function test_submission_needs_claims_their_evidence_and_the_three_assessments(): void
    {
        $empty = Revision::prepare(RevisionId::fromString('11111111-1111-4111-8111-111111111111'), self::p('a'));
        $this->assertRefused(RevisionRefusal::Incomplete, fn () => $empty->submit(self::p('b')));

        $noEvidence = Revision::prepare(RevisionId::fromString('11111111-1111-4111-8111-111111111111'), self::p('a'));
        $noEvidence->addClaim(self::claim(evidence: 'ev-missing'), self::p('a'));
        foreach ([AssessmentKind::Authenticity, AssessmentKind::Competence, AssessmentKind::Applicability] as $kind) {
            $noEvidence->recordAssessment(new Assessment($kind, 'satisfied', self::p('b')));
        }
        $this->assertRefused(RevisionRefusal::Incomplete, fn () => $noEvidence->submit(self::p('b')));

        $noAssessment = Revision::prepare(RevisionId::fromString('11111111-1111-4111-8111-111111111111'), self::p('a'));
        $noAssessment->addClaim(self::claim(), self::p('a'));
        $noAssessment->attachEvidence(new EvidenceVersion('ev-1', self::FP), self::p('a'));
        $this->assertRefused(RevisionRefusal::Incomplete, fn () => $noAssessment->submit(self::p('b')));
    }

    public function test_submission_freezes_version_1_and_nothing_can_change_until_re_preparation(): void
    {
        $r = self::ready();
        $v1 = $r->submit(self::p('b'));

        $this->assertSame(RevisionState::Submitted, $r->state());
        $this->assertSame(1, $v1->number);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $v1->digest);
        $this->assertRefused(RevisionRefusal::NotDraft, fn () => $r->addClaim(self::claim(scheme: 'other'), self::p('a')));
        $this->assertRefused(RevisionRefusal::NotDraft, fn () => $r->attachEvidence(new EvidenceVersion('ev-2', self::FP), self::p('a')));
        $this->assertRefused(RevisionRefusal::NotDraft, fn () => $r->recordAssessment(new Assessment(AssessmentKind::Precedence, 'applied', self::p('b'))));
        $this->assertRefused(RevisionRefusal::NotDraft, fn () => $r->submit(self::p('b')));
    }

    public function test_the_digest_covers_everything_judged_and_is_deterministic(): void
    {
        $claim = self::claim();   // a claim's id is part of its content, so equal content means the same claim
        $base = self::ready($claim)->submit(self::p('b'))->digest;

        $this->assertSame($base, self::ready($claim)->submit(self::p('b'))->digest, 'same content, same digest');

        $otherInterval = Revision::prepare(RevisionId::fromString('11111111-1111-4111-8111-111111111111'), self::p('a'));
        $otherInterval->addClaim(self::claim(from: '2015-09-21'), self::p('a'));
        $otherInterval->attachEvidence(new EvidenceVersion('ev-1', self::FP), self::p('a'));
        foreach ([AssessmentKind::Authenticity, AssessmentKind::Competence, AssessmentKind::Applicability] as $kind) {
            $otherInterval->recordAssessment(new Assessment($kind, 'satisfied', self::p('b')));
        }
        $otherFingerprint = self::ready($claim);
        $otherFingerprint->attachEvidence(new EvidenceVersion('ev-1b', str_repeat('b', 64)), self::p('a'));
        $otherOutcome = self::ready($claim);
        $otherOutcome->recordAssessment(new Assessment(AssessmentKind::Precedence, 'applied', self::p('b')));

        $this->assertNotSame($base, self::validated($otherInterval)->submit(self::p('b'))->digest, 'a valid interval is judged');
        $this->assertNotSame($base, $otherFingerprint->submit(self::p('b'))->digest, 'evidence versions are judged');
        $this->assertNotSame($base, $otherOutcome->submit(self::p('b'))->digest, 'assessments are judged');
        $this->assertNotSame($base, self::ready($claim)->submit(self::p('c'))->digest, 'the assessor is recorded with the version');
    }

    public function test_an_evidence_version_is_immutable_one_fingerprint_per_identifier(): void
    {
        $r = self::ready();

        $this->assertRefused(RevisionRefusal::EvidenceChangedUnderSameIdentifier, fn () => $r->attachEvidence(new EvidenceVersion('ev-1', str_repeat('c', 64)), self::p('a')));
    }

    public function test_an_approval_counts_only_for_the_current_version(): void
    {
        $r = self::ready();
        $v1 = $r->submit(self::p('b'));
        $r->approve(new Approval($v1->number, 7, self::p('c')));

        $this->assertCount(1, $r->countingApprovals());

        $r->markStale();
        $this->assertSame(RevisionState::Stale, $r->state());
        $r->reprepare(self::p('a'));
        $r->addClaim(self::claim(from: '2010-01-01', to: '2015-09-20'), self::p('a'));
        $this->assertRefused(RevisionRefusal::Incomplete, fn () => $r->submit(self::p('b')), 'version-1 assessments do not count for version 2');
        self::assessAll($r, 'b');
        $v2 = self::validated($r)->submit(self::p('b'));

        $this->assertSame(2, $v2->number);
        $this->assertNotSame($v1->digest, $v2->digest);
        $this->assertSame([], $r->countingApprovals(), 'the version-1 approval does not count for version 2');
        $this->assertCount(1, $r->approvals(), 'but it stays recorded (append-only)');
        $this->assertRefused(RevisionRefusal::ApprovalNamesAnotherVersion, fn () => $r->approve(new Approval($v1->number, 8, self::p('c'))));
    }

    public function test_approval_only_of_a_submitted_revision(): void
    {
        $draft = self::ready();
        $this->assertRefused(RevisionRefusal::NotSubmitted, fn () => $draft->approve(new Approval(1, 1, self::p('c'))));

        $stale = self::ready();
        $stale->submit(self::p('b'));
        $stale->markStale();
        $this->assertRefused(RevisionRefusal::NotSubmitted, fn () => $stale->approve(new Approval(1, 1, self::p('c'))));
    }

    public function test_excluded_accumulates_everyone_who_prepared_assessed_or_submitted_any_version(): void
    {
        $r = self::ready();                              // prepared by a, assessed by b
        $r->submit(self::p('b'));
        $r->reprepare(self::p('d'));                     // d re-prepares
        self::assessAll($r, 'e');                        // e assesses version 2
        self::validated($r)->submit(self::p('e'));        // e submits version 2 (validated afresh)

        $refs = array_map(static fn (PersonReference $p): string => $p->value()[0], $r->excludedPersons());
        sort($refs);

        $this->assertSame(['a', 'b', 'd', 'e'], $refs, 'over all versions; an approver (c) is not a participant');
    }

    public function test_lifecycle_transitions_are_refused_out_of_order(): void
    {
        $draft = self::ready();
        $this->assertRefused(RevisionRefusal::NotSubmitted, fn () => $draft->markStale());
        $this->assertRefused(RevisionRefusal::NothingToReprepare, fn () => $draft->reprepare(self::p('a')));

        $withdrawn = self::ready();
        $withdrawn->withdraw(self::p('a'));
        $this->assertSame(RevisionState::Withdrawn, $withdrawn->state());
        $this->assertRefused(RevisionRefusal::Withdrawn, fn () => $withdrawn->reprepare(self::p('a')));
        $this->assertRefused(RevisionRefusal::Withdrawn, fn () => $withdrawn->withdraw(self::p('a')));
        $this->assertRefused(RevisionRefusal::NotDraft, fn () => $withdrawn->addClaim(self::claim(scheme: 'x'), self::p('a')));
    }

    public function test_a_claim_can_be_removed_in_a_draft_only(): void
    {
        $r = self::ready();
        $id = $r->claims()[0]->id;
        $r->addClaim(self::claim(scheme: 'electoral'), self::p('a'));
        $r->removeClaim($id, self::p('a'));

        $this->assertCount(1, $r->claims());
        $this->assertRefused(RevisionRefusal::UnknownClaim, fn () => $r->removeClaim($id, self::p('a')));
    }
}
