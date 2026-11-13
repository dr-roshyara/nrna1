<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * Aggregate: who may decide for Reference Geography (v3.11 §8; D-84, D-84a, D-89). Outside the knowledge stream (§15).
 *
 * Protects:
 * - append-only entries, each with the next gap-free roster version (local to Geography; persisted under the
 *   publication lock in G-4);
 * - authority AT a version v: from entries ≤ v only, so an act is judged by the roster it was given under;
 * - a matter reviewer acts for one revision only and is not a steward appointment;
 * - `secondStewardEverAppointed`: set by the write that brings the appointed stewards to two, with that version;
 *   never unset (D-89).
 * Who may make a write (D-84: the platform owner) is checked by the application layer; the entry records who did.
 */
final class StewardRoster
{
    /** @var list<RosterEntry> */
    private array $entries = [];
    private ?int $thresholdActivatedAt = null;

    private function __construct()
    {
    }

    public static function empty(): self
    {
        return new self();
    }

    /**
     * Rebuilds a stored roster (G-4b) by replaying its entries through the domain's own writes. Refused unless the
     * versions run 1..n without a gap and every write is one the domain accepts at that point (no impossible end,
     * no notice twice), so a stored roster can never hold a history the domain could not have produced.
     *
     * @param list<RosterEntry> $entries
     */
    public static function fromEntries(array $entries): self
    {
        $roster = new self();
        foreach (array_values($entries) as $i => $e) {
            try {
                $version = match ($e->kind) {
                    RosterEntryKind::StewardAppointed => $roster->appointSteward(self::required($e->subject), self::required($e->by)),
                    RosterEntryKind::StewardEnded => $roster->endSteward(self::required($e->subject), self::required($e->by)),
                    RosterEntryKind::MatterReviewerAppointed => $roster->appointMatterReviewer(self::required($e->subject), $e->matter ?? throw new \InvalidArgumentException('A reviewer has a matter.'), self::required($e->by)),
                    RosterEntryKind::PersonsMerged => $roster->recordMerge((string) $e->mergeId, $e->merged),
                    RosterEntryKind::PersonMergeReversed => $roster->recordReversal((string) $e->mergeId),
                    RosterEntryKind::ConflictDeclared => $roster->declareConflict(self::required($e->subject), $e->matter ?? throw new \InvalidArgumentException('A declaration has a matter.'), $e->interest ?? throw new \InvalidArgumentException('A declaration states the interest.')),
                };
            } catch (RosterRefused $refused) {
                throw new \InvalidArgumentException("Roster history refused at entry {$e->version}: {$refused->reason->value}.", 0, $refused);
            }
            if ($version !== $i + 1 || $e->version !== $i + 1) {
                throw new \InvalidArgumentException("Roster history has a gap or a repeated notice at entry {$e->version}.");
            }
        }

        return $roster;
    }

    private static function required(?PersonReference $person): PersonReference
    {
        return $person ?? throw new \InvalidArgumentException('This roster entry names a person.');
    }

    public function version(): int
    {
        return count($this->entries);
    }

    public function appointSteward(PersonReference $steward, PersonReference $by): int
    {
        if ($this->isStewardAt($steward, $this->version())) {
            throw new RosterRefused(RosterRefusal::AlreadyAppointed);
        }
        $version = $this->append(RosterEntryKind::StewardAppointed, $steward, $by);
        if ($this->thresholdActivatedAt === null && count($this->stewardsAt($version)) >= 2) {
            $this->thresholdActivatedAt = $version;
        }

        return $version;
    }

    public function endSteward(PersonReference $steward, PersonReference $by): int
    {
        if (!$this->isStewardAt($steward, $this->version())) {
            throw new RosterRefused(RosterRefusal::NotAppointed);
        }

        return $this->append(RosterEntryKind::StewardEnded, $steward, $by);
    }

    public function appointMatterReviewer(PersonReference $reviewer, RevisionId $matter, PersonReference $by): int
    {
        return $this->append(RosterEntryKind::MatterReviewerAppointed, $reviewer, $by, $matter);
    }

    /**
     * Records Identity's merge notice (IG-4). Idempotent by (mergeId, kind): a replay writes nothing and returns null.
     *
     * @param list<PersonReference> $persons the references Identity says denote one person
     */
    public function recordMerge(string $mergeId, array $persons): ?int
    {
        $distinct = [];
        foreach ($persons as $p) {
            $distinct[$p->value()] = $p;
        }
        if (trim($mergeId) === '' || count($distinct) < 2) {
            throw new \InvalidArgumentException('A merge notice names its merge and at least two distinct references.');
        }
        if ($this->hasNotice(RosterEntryKind::PersonsMerged, $mergeId)) {
            return null;
        }
        $version = $this->version() + 1;
        $this->entries[] = new RosterEntry($version, RosterEntryKind::PersonsMerged, mergeId: $mergeId, merged: array_values($distinct));

        return $version;
    }

    /** Records Identity's reversal of merge $mergeId (IG-5), even before the merge arrives. Idempotent. */
    public function recordReversal(string $mergeId): ?int
    {
        if (trim($mergeId) === '') {
            throw new \InvalidArgumentException('A reversal names its merge.');
        }
        if ($this->hasNotice(RosterEntryKind::PersonMergeReversed, $mergeId)) {
            return null;
        }
        $version = $this->version() + 1;
        $this->entries[] = new RosterEntry($version, RosterEntryKind::PersonMergeReversed, mergeId: $mergeId);

        return $version;
    }

    /**
     * E(v): "known to be the same person, as known to Identity at roster version v". Distinct and unlinked references
     * are NOT proven different (completion §5a).
     */
    public function knownSameAt(PersonReference $a, PersonReference $b, int $version): bool
    {
        if ($a->equals($b)) {
            return true;
        }
        $entries = $this->entriesUpTo($version);
        $reversed = [];
        foreach ($entries as $entry) {
            if ($entry->kind === RosterEntryKind::PersonMergeReversed) {
                $reversed[$entry->mergeId] = true;
            }
        }
        $parent = [];
        $find = static function (string $x) use (&$parent, &$find): string {
            $parent[$x] ??= $x;

            return $parent[$x] === $x ? $x : ($parent[$x] = $find($parent[$x]));
        };
        foreach ($entries as $entry) {
            if ($entry->kind !== RosterEntryKind::PersonsMerged || isset($reversed[$entry->mergeId])) {
                continue;
            }
            $first = $entry->merged[0]->value();
            foreach ($entry->merged as $other) {
                $parent[$find($other->value())] = $find($first);
            }
        }

        return $find($a->value()) === $find($b->value());
    }

    /**
     * A person's declaration for one matter (D-84; D-84a: for a matter reviewer, the declaration of independence):
     * whether they have a direct organisational interest in it. Made by the declarer; the latest one ≤ v counts.
     */
    public function declareConflict(PersonReference $person, RevisionId $matter, bool $interest): int
    {
        $version = $this->version() + 1;
        $this->entries[] = new RosterEntry($version, RosterEntryKind::ConflictDeclared, $person, $person, $matter, interest: $interest);

        return $version;
    }

    /** @return bool|null the declared interest at version v, or null when nothing is declared */
    public function declaredInterestAt(PersonReference $person, RevisionId $matter, int $version): ?bool
    {
        $interest = null;
        foreach ($this->entriesUpTo($version) as $entry) {
            if ($entry->kind === RosterEntryKind::ConflictDeclared && $entry->subject?->equals($person) && $entry->matter?->equals($matter)) {
                $interest = $entry->interest;
            }
        }

        return $interest;
    }

    public function isStewardAt(PersonReference $person, int $version): bool
    {
        return isset($this->stewardsAt($version)[$person->value()]);
    }

    /** A steward, or the matter reviewer appointed for this revision, at version v. */
    public function mayActForAt(PersonReference $person, RevisionId $matter, int $version): bool
    {
        if ($this->isStewardAt($person, $version)) {
            return true;
        }
        foreach ($this->entriesUpTo($version) as $entry) {
            if ($entry->kind === RosterEntryKind::MatterReviewerAppointed && $entry->subject->equals($person) && $entry->matter?->equals($matter)) {
                return true;
            }
        }

        return false;
    }

    public function secondStewardEverAppointedAt(int $version): bool
    {
        $this->requireKnown($version);

        return $this->thresholdActivatedAt !== null && $this->thresholdActivatedAt <= $version;
    }

    public function thresholdActivatedAt(): ?int
    {
        return $this->thresholdActivatedAt;
    }

    /** @return list<RosterEntry> */
    public function entries(): array
    {
        return $this->entries;
    }

    /** @return array<string, PersonReference> stewards appointed and not ended at version v */
    private function stewardsAt(int $version): array
    {
        $stewards = [];
        foreach ($this->entriesUpTo($version) as $entry) {
            if ($entry->kind === RosterEntryKind::StewardAppointed) {
                $stewards[$entry->subject->value()] = $entry->subject;
            } elseif ($entry->kind === RosterEntryKind::StewardEnded) {
                unset($stewards[$entry->subject->value()]);
            }
        }

        return $stewards;
    }

    /** @return list<RosterEntry> */
    private function entriesUpTo(int $version): array
    {
        $this->requireKnown($version);

        return array_slice($this->entries, 0, $version);
    }

    private function hasNotice(RosterEntryKind $kind, string $mergeId): bool
    {
        foreach ($this->entries as $entry) {
            if ($entry->kind === $kind && $entry->mergeId === $mergeId) {
                return true;
            }
        }

        return false;
    }

    private function requireKnown(int $version): void
    {
        if ($version < 0 || $version > $this->version()) {
            throw new RosterRefused(RosterRefusal::UnknownVersion);
        }
    }

    private function append(RosterEntryKind $kind, PersonReference $subject, PersonReference $by, ?RevisionId $matter = null): int
    {
        $version = $this->version() + 1;
        $this->entries[] = new RosterEntry($version, $kind, $subject, $by, $matter);

        return $version;
    }
}
