<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain;

use InvalidArgumentException;
use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;

/**
 * The domain service that APPLIES IdentifierPolicy to a proposed identifier.
 *
 * Behaviour lives here; the value objects stay pure (PA direction, 2026-08-02).
 *
 * Owns EXECUTION only. It owns no identifier, no register(ns), no criterion and no
 * numbering scheme — AP-4 forbids a central authority, AP-7/DR-4 make criteria
 * read-only inputs, and AP-1 makes the verdict an output rather than a decision.
 *
 * Verdict mapping (plan 20260802-0015 §0.7), each traced:
 *   free in its register(ns)            → PASS
 *   already minted there                → FAIL          (the defect PMR-10 prevents)
 *   cited but unminted                  → WARN          (collision HAZARD — R-65..R-71)
 *   series not a governed register(ns)  → INCONCLUSIVE  (fail-closed; never PASS)
 */
final readonly class AssessesIdentifierIntegrity
{
    public function __construct(private IdentifierPolicy $policy = new IdentifierPolicy())
    {
    }

    public function validate(Identifier $identifier, SeriesContents $contents): Assessment
    {
        if (! $this->policy->appliesTo($identifier, $contents)) {
            throw new InvalidArgumentException(sprintf(
                "Identifier '%s' belongs to series '%s' and cannot be validated against series '%s'.",
                $identifier->value(),
                $identifier->series()->prefix(),
                $contents->series()->prefix(),
            ));
        }

        if (! $this->policy->isEvaluable($contents)) {
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Series '%s' is not a governed register(ns); no criterion exists to evaluate '%s'. "
                    .'Absence of evidence is not PASS.',
                    $contents->series()->prefix(),
                    $identifier->value(),
                ),
                $identifier->value(),
            );
        }

        if ($this->policy->isViolatedBy($identifier, $contents)) {
            return Assessment::of(
                Verdict::FAIL,
                sprintf(
                    "'%s' is already minted in series '%s' (%d minted). Minting it again would collide.",
                    $identifier->value(),
                    $contents->series()->prefix(),
                    $contents->mintedCount(),
                ),
                $identifier->value(),
            );
        }

        if ($this->policy->isAtRiskOfViolation($identifier, $contents)) {
            return Assessment::of(
                Verdict::WARN,
                sprintf(
                    "'%s' is cited in the corpus but not minted in series '%s'. "
                    .'Either the citations expect this subject, or a reservation lapsed. '
                    .'A human must dispose it before minting — the tool cannot tell which.',
                    $identifier->value(),
                    $contents->series()->prefix(),
                ),
                $identifier->value(),
            );
        }

        return Assessment::of(
            Verdict::PASS,
            sprintf(
                "'%s' is free in series '%s' (checked against %d minted, %d cited).",
                $identifier->value(),
                $contents->series()->prefix(),
                $contents->mintedCount(),
                $contents->citedCount(),
            ),
            $identifier->value(),
        );
    }
}
