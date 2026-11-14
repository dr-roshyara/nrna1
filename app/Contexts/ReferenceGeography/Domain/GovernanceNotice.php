<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * A D-90 governance notice (design revision 3 §4, §11): a stream entry with no claims.
 * - finding: concerns a revision publication; records the merges that made its approval defective;
 * - confirmation: concerns a FINDING; the confirmer and the digest of the confirmed content;
 * - closure: concerns a FINDING; what ended the defect (the roster change).
 * Each records the roster version it was decided at.
 */
final readonly class GovernanceNotice
{
    /** @param list<string> $mergeBasis */
    private function __construct(
        public NoticeKind $kind,
        public int $concerns,
        public int $rosterVersion,
        public array $mergeBasis = [],
        public ?string $endedBy = null,
        public ?PersonReference $confirmedBy = null,
        public ?string $digest = null,
    ) {
        if ($concerns < 1 || $rosterVersion < 0) {
            throw new \InvalidArgumentException('A notice concerns an existing position and records its roster version.');
        }
    }

    /** @param list<string> $mergeBasis */
    public static function finding(int $publication, array $mergeBasis, int $rosterVersion): self
    {
        if ($mergeBasis === []) {
            throw new \InvalidArgumentException('A finding records the merges that made the approval defective.');
        }

        return new self(NoticeKind::Finding, $publication, $rosterVersion, mergeBasis: array_values($mergeBasis));
    }

    public static function confirmation(int $finding, PersonReference $confirmer, string $digest, int $rosterVersion): self
    {
        if (trim($digest) === '') {
            throw new \InvalidArgumentException('A confirmation names the digest of the confirmed content.');
        }

        return new self(NoticeKind::Confirmation, $finding, $rosterVersion, confirmedBy: $confirmer, digest: $digest);
    }

    public static function closure(int $finding, string $endedBy, int $rosterVersion): self
    {
        if (trim($endedBy) === '') {
            throw new \InvalidArgumentException('A closure records what ended the defect.');
        }

        return new self(NoticeKind::Closure, $finding, $rosterVersion, endedBy: $endedBy);
    }
}
