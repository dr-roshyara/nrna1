<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Exception;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use DomainException;

/**
 * A determination already exists for the challenge — enforces logical uniqueness
 * (one determination per challenge). Raised by the application service.
 *
 * **R-84: it carries the EXISTING determination's MINIMAL IDENTITY, so the requester can
 * reconcile per EPIC-004K §12** — *ack* a self-redelivery, *dead-letter + escalate* a
 * competing writer. Without this the two branches are indistinguishable at the point of
 * catch, and §12 cannot be implemented at all.
 *
 * **Minimal identity, deliberately: an id and the deciding authority — NOT the aggregate.**
 * An exception may carry enough context to support recovery; it must not become a transport
 * object for a `Determination`.
 *
 * ⚠️ **`IssuedByAuthority` IS THE CURRENT RECONCILIATION DISCRIMINATOR. IT IS NOT THE
 * CANONICAL BUSINESS IDENTITY.** §12's actual question is *"did THIS PROCESS previously
 * request issuance?"* — a question about **process identity**, not about authority identity.
 * The two coincide today only because the `Determination` carries no process reference, and
 * giving it one would change the constitutional record and its published payload
 * (ADR-PL-01 · ADR-T5) — an architectural act, not an engineering one.
 *
 * **Do not infer that authority identity is the invariant.** If a process reference or an
 * issuance correlation is ever adopted onto the determination, THAT becomes the
 * discriminator and this one is retired.
 */
final class DeterminationAlreadyIssued extends DomainException
{
    private function __construct(
        string $message,
        private readonly ?DeterminationId $existingDeterminationId = null,
        private readonly ?IssuedByAuthority $existingIssuedByAuthority = null,
    ) {
        parent::__construct($message);
    }

    /**
     * Accessors rather than public readonly properties, deliberately: the reconciliation
     * discriminator is expected to CHANGE (see the note on the class), and a method keeps
     * that evolution from being a breaking change for every catch site.
     */
    public function existingDeterminationId(): ?DeterminationId
    {
        return $this->existingDeterminationId;
    }

    public function existingIssuedByAuthority(): ?IssuedByAuthority
    {
        return $this->existingIssuedByAuthority;
    }

    /**
     * Retained for callers that have no loaded determination to hand. **A refusal raised
     * this way cannot be reconciled** — both §12 branches need the existing identity — so
     * the seam treats a bare refusal as UNRECONCILABLE and escalates rather than guessing.
     */
    public static function forChallenge(ChallengeRef $challengeRef): self
    {
        return new self(sprintf(
            'A determination has already been issued for challenge "%s".',
            $challengeRef->toString()
        ));
    }

    /** R-84: the reconcilable form — raised where the existing determination is in hand. */
    public static function forExistingDetermination(
        ChallengeRef $challengeRef,
        DeterminationId $existingId,
        IssuedByAuthority $existingAuthority,
    ): self {
        return new self(
            sprintf(
                'A determination has already been issued for challenge "%s" (determination "%s", authority "%s").',
                $challengeRef->toString(),
                $existingId->toString(),
                $existingAuthority->toString(),
            ),
            $existingId,
            $existingAuthority,
        );
    }
}
