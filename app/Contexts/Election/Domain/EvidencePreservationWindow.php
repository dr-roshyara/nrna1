<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain;

use DateInterval;
use DateTimeImmutable;
use InvalidArgumentException;

/**
 * How long an election's evidence must be preserved.
 *
 * Constitutional Policy 2: **EPW = Contestation Window + Maximum Adjudication Duration
 * + Legal Safety Margin**, measured from the election's anchor.
 *
 * A value, not a computation: two windows built from the same anchor and durations are
 * the same window. It takes business values only — no port, config, model or clock —
 * so the instant is an argument to `isOpenAt()`, never an injected dependency.
 */
final class EvidencePreservationWindow
{
    private function __construct(
        private readonly DateTimeImmutable $anchor,
        private readonly DateTimeImmutable $closesAt,
    ) {
    }

    /**
     * @throws InvalidArgumentException if any term is non-positive — a duration is
     *         Q-2's business policy, so an unusable one is a configuration error and
     *         is never clamped into something workable.
     */
    public static function forElection(
        DateTimeImmutable $anchor,
        DateInterval $contestationWindow,
        DateInterval $maximumAdjudicationDuration,
        DateInterval $legalSafetyMargin,
    ): self {
        $terms = [
            'contestation window' => $contestationWindow,
            'maximum adjudication duration' => $maximumAdjudicationDuration,
            'legal safety margin' => $legalSafetyMargin,
        ];

        foreach ($terms as $name => $term) {
            if (!self::isPositive($term)) {
                throw new InvalidArgumentException(
                    "The {$name} must be a positive duration. Fix the configuration -- "
                    . 'this value will not substitute one.'
                );
            }
        }

        return new self(
            $anchor,
            $anchor
                ->add($contestationWindow)
                ->add($maximumAdjudicationDuration)
                ->add($legalSafetyMargin),
        );
    }

    public function closesAt(): DateTimeImmutable
    {
        return $this->closesAt;
    }

    /** Open from the anchor until it closes; closed on either side. */
    public function isOpenAt(DateTimeImmutable $at): bool
    {
        return $at >= $this->anchor && $at <= $this->closesAt;
    }

    private static function isPositive(DateInterval $term): bool
    {
        if ($term->invert === 1) {
            return false;
        }

        return $term->y > 0 || $term->m > 0 || $term->d > 0
            || $term->h > 0 || $term->i > 0 || $term->s > 0 || $term->f > 0.0;
    }
}
