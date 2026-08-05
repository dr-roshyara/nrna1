<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

use InvalidArgumentException;

/**
 * The considered-evidence set — the set of evidence references the authority's
 * ruling actually considered, fixed at the act of issuance (WP-1, ADR-T22).
 *
 * Parent invariant: INV-4's rider (R-4-expanded) — the record of what was
 * considered and the announcement of it can never diverge. The VO therefore
 * fails loud on anything that would corrupt a fixation record: an empty set,
 * blank refs, duplicates. References are opaque strings (refs cross context
 * boundaries as strings — ADR-T16 discipline); this VO holds no transport.
 *
 * Entered through artifact №5's frozen deferral path (EPIC-004F) when the PM
 * design resolved its seat to the aggregate's issuance (EPIC-004K §11).
 */
final readonly class EvidenceSet
{
    /** @param non-empty-list<string> $refs */
    private function __construct(private array $refs)
    {
    }

    public static function fromRefs(string ...$refs): self
    {
        if ($refs === []) {
            throw new InvalidArgumentException(
                'EvidenceSet must contain at least one evidence reference — a record of consideration cannot be empty.'
            );
        }

        $clean = [];
        foreach ($refs as $ref) {
            $trimmed = trim($ref);
            if ($trimmed === '') {
                throw new InvalidArgumentException('EvidenceSet refs must be non-blank strings.');
            }
            if (in_array($trimmed, $clean, true)) {
                throw new InvalidArgumentException(sprintf(
                    'EvidenceSet contains duplicate reference "%s" — a duplicate in a fixation record signals an upstream defect.',
                    $trimmed,
                ));
            }
            $clean[] = $trimmed;
        }

        return new self($clean);
    }

    /** @return non-empty-list<string> */
    public function toArray(): array
    {
        return $this->refs;
    }

    public function count(): int
    {
        return count($this->refs);
    }
}
