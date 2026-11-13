<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * I-1 (v3.11 §2): within a namespace, at any valid instant, a value identifies at most one live subject.
 * Code reuse with disjoint intervals is allowed (ISO "CS"). I-2 is structural (the IDENTIFIER slot is
 * (subject, namespace)), so it needs no check here.
 */
final class IdentifierInvariants
{
    /**
     * @param list<IdentifierAssignment> $assignments
     * @return list<array{string, string, string, string}> [namespace, value, subjectA, subjectB] per violation
     */
    public static function i1Violations(array $assignments): array
    {
        $violations = [];
        $count = count($assignments);
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                [$a, $b] = [$assignments[$i], $assignments[$j]];
                if ($a->namespace === $b->namespace && $a->value === $b->value
                    && !$a->subject->equals($b->subject) && $a->interval->overlaps($b->interval)) {
                    $violations[] = [$a->namespace, $a->value, $a->subject->value(), $b->subject->value()];
                }
            }
        }

        return $violations;
    }
}
