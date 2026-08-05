<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication\Infrastructure\Issuance;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Application\Port\IdentityGenerator;
use App\Contexts\Adjudication\Application\Service\CoordinatesAdjudication;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\ElectionId;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Determination\TargetId;
use App\Contexts\Adjudication\Domain\Determination\TargetType;
use App\Contexts\Adjudication\Domain\Exception\DeterminationAlreadyIssued;
use App\Contexts\Adjudication\Infrastructure\Issuance\CoordinatorIssuanceRequest;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tests\Support\Adjudication\InMemoryDeterminationRepository;
use Tests\Support\Adjudication\InMemoryEventOutbox;

/**
 * Coverage for the seam's second half — **the only production class in WP-4B that no test had
 * ever executed.**
 *
 * Every `ConcludeToIssuanceSeamTest` keystone supplies a hand-rolled `RequestsDeterminationIssuance`
 * double, deliberately: resolving the manager from the container drags in the outbox adapter and
 * fails on `TenantContext::require()`. **The consequence, unnoticed until now, is that the real
 * adapter bound at `AdjudicationServiceProvider` line 49 was never exercised at all.**
 *
 * **WHY THIS IS NOT A TRIVIAL DELEGATION TEST.** `CoordinatorIssuanceRequest`'s docblock claims it
 * *"adds nothing — no retry, no pre-check, no translation"*, and **R-84 depends on that claim
 * literally**: the seam's §12 reconcile fires only if `DeterminationAlreadyIssued` reaches it
 * **unchanged**. An adapter that caught, wrapped, or retried on that exception would leave the
 * reconcile permanently unreachable — and the seam would fail closed forever, silently, on exactly
 * the crash model R-83 put in scope.
 *
 * **The collaborator is REAL, not doubled.** `CoordinatesAdjudication` is `final`, so rather than
 * change production code to make it mockable, it is constructed with the same in-memory
 * repository and outbox `AdjudicationServiceTest` uses. That exercises the actual collaboration.
 *
 * Traceability: **R-84** (§12 reconcile depends on unchanged propagation) · R-83 · R-72 ·
 * EPIC-004K §11 · §12 · ADR-T1 (the adapter exists so the manager does not hold the issuance
 * service) · INV-B1.
 */
final class CoordinatorIssuanceRequestTest extends TestCase
{
    private InMemoryDeterminationRepository $repo;
    private InMemoryEventOutbox $outbox;
    private CoordinatesAdjudication $coordinator;

    protected function setUp(): void
    {
        $this->repo = new InMemoryDeterminationRepository();
        $this->outbox = new InMemoryEventOutbox();
        $this->coordinator = new CoordinatesAdjudication(
            $this->repo,
            $this->outbox,
            new class implements IdentityGenerator {
                public function next(): string
                {
                    return 'corr-minted';
                }
            },
        );
    }

    private function request(): CoordinatorIssuanceRequest
    {
        return new CoordinatorIssuanceRequest($this->coordinator);
    }

    private function command(string $challenge = 'ch-adapter-1'): IssueDeterminationCommand
    {
        return new IssueDeterminationCommand(
            ChallengeRef::fromString($challenge),
            DeterminationOutcome::Upheld,
            Legitimacy::Legitimate,
            Reason::fromString('The contested tally omitted two ballot boxes.'),
            IssuedByAuthority::fromString('constitutional-council'),
            Jurisdiction::fromString('EU-DE-BY'),
            EvidenceEnvelopeRef::fromString('env-9f2c'),
            ContestedOutcomeRef::of(
                ElectionId::fromString('elec-2026-eu'),
                TargetType::ElectionResult,
                TargetId::fromString('result-bayern-07'),
            ),
            EvidenceSet::fromRefs('env-9f2c'),
            new DateTimeImmutable('2026-08-04T12:00:00+00:00'),
        );
    }

    // ── it delegates, and the determination actually reaches the repository ──

    public function test_the_request_reaches_the_issuance_service(): void
    {
        $this->request()->request($this->command());

        $issued = $this->repo->findByChallengeRef(ChallengeRef::fromString('ch-adapter-1'));

        $this->assertNotNull($issued, 'the adapter must delegate to the issuance service');
        $this->assertSame('constitutional-council', $issued->issuedByAuthority()->toString());
    }

    // ── R-84's dependency: the refusal must arrive UNCHANGED ─────────────────

    /**
     * **The load-bearing assertion.** §12's reconcile discriminates on
     * `DeterminationAlreadyIssued::existingIssuedByAuthority()`. If this adapter caught the
     * refusal, wrapped it in another exception type, or stripped its payload, the reconcile could
     * never run — and R-83's crash model B would be unrecoverable while appearing handled.
     */
    public function test_an_inv_b1_refusal_propagates_unchanged(): void
    {
        // First issuance succeeds and populates the repository.
        $this->request()->request($this->command());

        $caught = null;

        try {
            // Second request for the same challenge: INV-B1's guard must refuse.
            $this->request()->request($this->command());
        } catch (\Throwable $e) {
            $caught = $e;
        }

        $this->assertInstanceOf(
            DeterminationAlreadyIssued::class,
            $caught,
            'the adapter must not translate or swallow INV-B1s refusal — R-84s reconcile depends on it',
        );

        // And its reconciliation payload must survive the trip.
        $this->assertNotNull(
            $caught->existingIssuedByAuthority(),
            'the refusals identifying authority must reach the requester, or reconciliation is impossible',
        );
        $this->assertSame('constitutional-council', $caught->existingIssuedByAuthority()->toString());
        $this->assertNotNull($caught->existingDeterminationId());
    }

    // ── it adds nothing: no retry, no second attempt, no pre-check ───────────

    /**
     * *"No retry"* asserted rather than trusted: a refused request must leave exactly one
     * determination, not two, and must not silently re-attempt.
     */
    public function test_a_refused_request_creates_no_second_determination(): void
    {
        $this->request()->request($this->command());

        try {
            $this->request()->request($this->command());
        } catch (DeterminationAlreadyIssued) {
            // expected
        }

        $this->assertSame(
            1,
            $this->repo->count(),
            'INV-B1: exactly one determination per challenge, and the adapter adds no retry',
        );
    }
}
