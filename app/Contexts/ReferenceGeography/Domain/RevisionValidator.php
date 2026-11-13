<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * Validates a revision's claims against the published atlas at p₀ (v3.11 §5). It reads ONLY through the
 * DependencyRecordingReader, so every predicate it relies on is recorded for the staleness check (§5.2, G-4).
 *
 * Implemented: before-images (§5.1); the structural classification (§14); existence of parents; admission before
 * grouping (D-86); I-1 across the read set. The other §5.2 invariants (G-4, G-5, G-6, D-82, D-83, evidence basis)
 * wait for the clarifications reported at the G-2 boundary. Nothing here decides who may approve or publish (G-3).
 */
final class RevisionValidator
{
    /** @param list<Claim> $claims */
    public function validate(array $claims, DependencyRecordingReader $reader): ValidationResult
    {
        $p0 = $reader->p0();
        $images = [];
        foreach ($claims as $claim) {
            $images[$claim->id->value()] = BeforeImage::of($reader->history($claim->slot, $claim->interval), $claim->interval, $p0);
        }
        $violations = [];
        foreach ($claims as $claim) {
            if ($claim->value->isNone()) {
                $violations = [...$violations, ...$this->withdrawalBasis($claim)];
                if ($claim->slot->kind() === ClaimKind::Existence) {
                    $violations = [...$violations, ...$this->noLiveChildUnderEnded($claim, $claims, $reader)];
                }
                continue;
            }
            $violations = [...$violations, ...match ($claim->slot->kind()) {
                ClaimKind::Succession => $this->succession($claim, $claims, $reader),
                ClaimKind::Containment => $this->parentExists($claim, $claims, $reader),
                ClaimKind::Grouping => $this->admittedBeforeGrouping($claim, $claims, $reader),
                ClaimKind::Identifier => $this->identifierUnique($claim, $claims, $reader),
                default => [],
            }];
        }

        return new ValidationResult($violations, $reader->dependencies(), $images, StructuralClassification::ofClaims($claims));
    }

    /** @param list<Claim> $claims @return list<Violation> */
    private function parentExists(Claim $claim, array $claims, DependencyRecordingReader $reader): array
    {
        try {
            $parent = ReferenceUnitId::fromString($claim->value->value());
        } catch (\InvalidArgumentException) {
            return [new Violation('PARENT_NOT_A_UNIT', $claim->id, 'A parent is a ReferenceUnitId, never a name or code.')];
        }
        $existence = $this->withRevision(SlotKey::existence($parent), $claim->interval, $claims, $reader);

        return $this->holdsThroughout($existence, $claim->interval)
            ? []
            : [new Violation('PARENT_EXISTS', $claim->id, 'The parent does not exist over the whole interval.')];
    }

    /** @param list<Claim> $claims @return list<Violation> */
    private function admittedBeforeGrouping(Claim $claim, array $claims, DependencyRecordingReader $reader): array
    {
        $entry = $claim->slot->subject();
        if ($entry === null) {
            return [new Violation('ADMISSION_BEFORE_GROUPING', $claim->id, 'A grouping names its entry.')];
        }
        $admission = $this->withRevision(SlotKey::admission($entry), $claim->interval, $claims, $reader);

        return $this->holdsThroughout($admission, $claim->interval)
            ? []
            : [new Violation('ADMISSION_BEFORE_GROUPING', $claim->id, 'The entry is not admitted over the whole grouping interval (D-86).')];
    }

    /** v3.11 §3 withdrawal row; D-228: a withdrawal rests on an instrument (a real ending) or a correction. @return list<Violation> */
    private function withdrawalBasis(Claim $claim): array
    {
        return in_array($claim->basis->kind, [BasisKind::Instrument, BasisKind::Correction], true)
            ? []
            : [new Violation('WITHDRAWAL_BASIS', $claim->id, 'A withdrawal rests on an instrument or a correction.')];
    }

    /**
     * G-5 (v3.11 §5.2; D-228): an event of a known kind with ≥ 1 successor; successors exist on the event date;
     * division, merger and absorption end the predecessor by then.
     *
     * @param list<Claim> $claims
     * @return list<Violation>
     */
    private function succession(Claim $claim, array $claims, DependencyRecordingReader $reader): array
    {
        try {
            $event = SuccessionEvent::parse($claim->value->value());
        } catch (\InvalidArgumentException) {
            return [new Violation('G-5_SUCCESSION_FORM', $claim->id, 'A succession names its kind and successor identities.')];
        }
        $date = $claim->interval->from;
        $violations = [];
        foreach ($event->successors as $successor) {
            if (!$this->withRevision(SlotKey::existence($successor), $claim->interval, $claims, $reader)->priorAt($date)->isSomeValue()) {
                $violations[] = new Violation('G-5_SUCCESSOR_EXISTS', $claim->id, "Successor {$successor->value()} does not exist on {$date->value()}.");
            }
        }
        $predecessor = $claim->slot->subject();
        if ($event->kind->endsPredecessor() && $predecessor !== null
            && $this->withRevision(SlotKey::existence($predecessor), $claim->interval, $claims, $reader)->priorAt($date)->isSomeValue()) {
            $violations[] = new Violation('G-5_PREDECESSOR_ENDS', $claim->id, "A {$event->kind->value} ends the predecessor by {$date->value()}.");
        }

        return $violations;
    }

    /**
     * G-5 (v3.11 §5.2 "no live child under an ended parent"): the children of u, in ANY scheme, over the ended range
     * (a recorded family read), with this revision on top, must not still have u as parent while they exist.
     *
     * @param list<Claim> $claims
     * @return list<Violation>
     */
    private function noLiveChildUnderEnded(Claim $claim, array $claims, DependencyRecordingReader $reader): array
    {
        $ended = $claim->slot->subject();
        if ($ended === null) {
            return [];
        }
        $childSlots = [];
        foreach ($reader->family(ClaimKind::Containment, [], $ended->value(), $claim->interval) as $slot) {
            $childSlots[$slot->encoded()] = $slot;
        }
        foreach ($claims as $other) {
            if ($other->slot->kind() === ClaimKind::Containment && !$other->value->isNone() && $other->value->value() === $ended->value()) {
                $childSlots[$other->slot->encoded()] = $other->slot;
            }
        }
        foreach ($childSlots as $slot) {
            $child = $slot->subject();
            if ($child === null) {
                continue;
            }
            $underEnded = $this->segmentsWhere($this->withRevision($slot, $claim->interval, $claims, $reader), $claim->interval, $ended->value());
            $live = $this->segmentsWhere($this->withRevision(SlotKey::existence($child), $claim->interval, $claims, $reader), $claim->interval, null);
            foreach ($underEnded as $a) {
                foreach ($live as $b) {
                    if ($a->overlaps($b)) {
                        return [new Violation('G-5_LIVE_CHILD', $claim->id, "Unit {$child->value()} is still a live child of the ended unit.")];
                    }
                }
            }
        }

        return [];
    }

    /**
     * I-1 (v3.11 §2, §5.2): u takes X in namespace N over V. Other holders come from the family read
     * (IDENTIFIER, *, N) = X over V and from this revision. A holder counts only while it holds X (its slot, with this
     * revision on top) AND is live (its EXISTENCE, with this revision on top).
     *
     * @param list<Claim> $claims
     * @return list<Violation>
     */
    private function identifierUnique(Claim $claim, array $claims, DependencyRecordingReader $reader): array
    {
        $subject = $claim->slot->subject();
        $namespace = $claim->slot->component('namespace');
        if ($subject === null || $namespace === null) {
            return [];
        }
        $value = $claim->value->value();
        $holderSlots = [];
        foreach ($reader->family(ClaimKind::Identifier, ['namespace' => $namespace], $value, $claim->interval) as $slot) {
            $holderSlots[$slot->encoded()] = $slot;
        }
        foreach ($claims as $other) {
            if ($other->slot->kind() === ClaimKind::Identifier && $other->slot->component('namespace') === $namespace
                && !$other->value->isNone() && $other->value->value() === $value) {
                $holderSlots[$other->slot->encoded()] = $other->slot;
            }
        }
        $assignments = [new IdentifierAssignment($subject, $namespace, $value, $claim->interval)];
        foreach ($holderSlots as $slot) {
            $holder = $slot->subject();
            if ($holder === null || $holder->equals($subject)) {
                continue;
            }
            $holding = $this->segmentsWhere($this->withRevision($slot, $claim->interval, $claims, $reader), $claim->interval, $value);
            $live = $this->segmentsWhere($this->withRevision(SlotKey::existence($holder), $claim->interval, $claims, $reader), $claim->interval, null);
            foreach ($holding as $h) {
                foreach ($live as $l) {
                    $both = $h->intersect($l);
                    if ($both !== null) {
                        $assignments[] = new IdentifierAssignment($holder, $namespace, $value, $both);
                    }
                }
            }
        }

        return IdentifierInvariants::i1Violations($assignments) === []
            ? []
            : [new Violation('I-1', $claim->id, "Value '{$value}' in '{$namespace}' already identifies another live subject.")];
    }

    /**
     * The parts of the interval where the history holds $value (or, with null, any value).
     *
     * @return list<ValidInterval>
     */
    private function segmentsWhere(SlotHistory $history, ValidInterval $interval, ?string $value): array
    {
        $out = [];
        foreach (BeforeImage::of($history, $interval, PHP_INT_MAX)->segments() as [$segment, $prior]) {
            if ($value === null ? $prior->isSomeValue() : $prior->isValue($value)) {
                $out[] = $segment;
            }
        }

        return $out;
    }

    /**
     * The published history of a slot (a recorded read) with this revision's own claims for it on top, as they would
     * be after publication. R-1 guarantees the revision's claims in one slot do not overlap.
     *
     * @param list<Claim> $claims
     */
    private function withRevision(SlotKey $slot, ValidInterval $interval, array $claims, DependencyRecordingReader $reader): SlotHistory
    {
        $history = $reader->history($slot, $interval);
        $position = $reader->p0() + 1;
        foreach ($claims as $claim) {
            if ($claim->slot->equals($slot)) {
                $history = $history->with($claim, $position++);
            }
        }

        return $history;
    }

    private function holdsThroughout(SlotHistory $history, ValidInterval $interval): bool
    {
        foreach (BeforeImage::of($history, $interval, PHP_INT_MAX)->segments() as [, $prior]) {
            if (!$prior->isSomeValue()) {
                return false;
            }
        }

        return true;
    }
}
