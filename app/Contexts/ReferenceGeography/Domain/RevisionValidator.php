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
    /** Claim kinds whose basis column in v3.11 §3 is "Territorial" (D-82 applies when they rest on an instrument). */
    private const TERRITORIAL_KINDS = [
        ClaimKind::Existence, ClaimKind::Containment, ClaimKind::Classification, ClaimKind::Boundary, ClaimKind::Succession,
        ClaimKind::ProfileScheme, ClaimKind::ProfileTier, ClaimKind::ProfileClass,
    ];

    /**
     * @param list<Claim> $claims
     * @param array<string, EvidenceVersion> $evidence the revision's evidence versions, by id
     */
    public function validate(array $claims, DependencyRecordingReader $reader, array $evidence = []): ValidationResult
    {
        $disputed = [];
        $authority = $this->authorityBasis($claims, $evidence, $reader, $disputed);
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

        $violations = [...$violations, ...$this->profileForm($claims), ...$this->tiersAndPositions($claims, $reader), ...$this->profileChanges($claims, $reader), ...$this->countryChanges($claims, $reader)];

        return new ValidationResult([...$authority, ...$violations], $reader->dependencies(), $images, StructuralClassification::ofClaims($claims), array_values($disputed));
    }

    /**
     * v3.11 §13: profile slots exist for positions 3–10 only (0–2 are fixed); a PROFILE_TIER value is a tier meaning
     * (kind + label); a PROFILE_CLASS value is a position.
     *
     * @param list<Claim> $claims
     * @return list<Violation>
     */
    private function profileForm(array $claims): array
    {
        $violations = [];
        foreach ($claims as $claim) {
            if ($claim->value->isNone()) {
                continue;
            }
            $kind = $claim->slot->kind();
            $ok = match ($kind) {
                ClaimKind::ProfileTier => ProfilePositions::isProfilePosition((string) $claim->slot->component('position')) && $this->isTierMeaning($claim->value->value()),
                ClaimKind::ProfileClass => ProfilePositions::isProfilePosition($claim->value->value()),
                default => true,
            };
            if (!$ok) {
                $violations[] = new Violation('PROFILE_FORM', $claim->id, 'Profile positions are 3–10; a tier value is KIND:label; a class value is its position.');
            }
        }

        return $violations;
    }

    private function isTierMeaning(string $value): bool
    {
        try {
            TierMeaning::parse($value);

            return true;
        } catch (\InvalidArgumentException) {
            return false;
        }
    }

    /**
     * D-83 and G-4 (v3.11 §5.2, §13): for each classification claim, the class sits at a TERRITORIAL position of the
     * unit's country; for each containment claim and each classification change, position(parent) < position(child)
     * for every edge involved (the unit's parent and, for a classification change, its children: a recorded family
     * read). Checked at every date of the claim's interval where a read fact changes. A broken edge is reported once.
     *
     * @param list<Claim> $claims
     * @return list<Violation>
     */
    private function tiersAndPositions(array $claims, DependencyRecordingReader $reader): array
    {
        $violations = [];
        $brokenEdges = [];
        foreach ($claims as $claim) {
            $kind = $claim->slot->kind();
            $unit = $claim->slot->subject();
            $scheme = $claim->slot->component('scheme');
            if ($claim->value->isNone() || $unit === null || $scheme === null || !in_array($kind, [ClaimKind::Classification, ClaimKind::Containment], true)) {
                continue;
            }
            $window = $claim->interval;
            $positions = new ProfilePositions(fn (SlotKey $slot): SlotHistory => $this->withRevision($slot, $window, $claims, $reader));
            $children = [];
            if ($kind === ClaimKind::Classification) {
                foreach ($reader->family(ClaimKind::Containment, ['scheme' => $scheme], $unit->value(), $window) as $slot) {
                    $children[$slot->encoded()] = $slot->subject();
                }
                foreach ($claims as $other) {
                    if ($other->slot->kind() === ClaimKind::Containment && $other->slot->component('scheme') === $scheme
                        && !$other->value->isNone() && $other->value->value() === $unit->value()) {
                        $children[$other->slot->encoded()] = $other->slot->subject();
                    }
                }
            }
            $d83 = false;
            $checked = [];
            do {
                $new = false;
                foreach ($positions->checkpoints($window) as $t) {
                    if (isset($checked[$t->value()])) {
                        continue;
                    }
                    $checked[$t->value()] = $new = true;
                    if ($kind === ClaimKind::Classification && !$d83 && !$this->classIsTerritorial($positions, $unit, $scheme, $claim->value->value(), $t)) {
                        $d83 = true;
                        $violations[] = new Violation('D-83', $claim->id, "Class '{$claim->value->value()}' does not sit at a territorial position on {$t->value()}.");
                    }
                    $edges = [];
                    $parent = $positions->parentOf($unit, $scheme, $t);
                    if ($parent !== null) {
                        $edges[] = [$unit, $parent];
                    }
                    foreach ($children as $child) {
                        if ($child !== null && $positions->parentOf($child, $scheme, $t)?->equals($unit)) {
                            $edges[] = [$child, $unit];
                        }
                    }
                    foreach ($edges as [$child, $parentUnit]) {
                        $key = $child->value() . '>' . $parentUnit->value() . '@' . $scheme;
                        if (isset($brokenEdges[$key])) {
                            continue;
                        }
                        $pc = $positions->positionOf($child, $scheme, $t);
                        $pp = $positions->positionOf($parentUnit, $scheme, $t);
                        if ($pc !== null && $pp !== null && $pp >= $pc) {
                            $brokenEdges[$key] = true;
                            $violations[] = new Violation('G-4', $claim->id, "Position {$pp} of {$parentUnit->value()} is not above position {$pc} of {$child->value()} on {$t->value()}.");
                        }
                    }
                }
            } while ($new);
        }

        return $violations;
    }

    /**
     * G-6 (v3.11 §13 "G-6 (derived)", §5.2): a PROFILE_CLASS or PROFILE_TIER claim (a new value or an end) may not
     * leave a unit of that country, classified into an affected class, without a position, at a non-territorial
     * position, or breaking G-4 with its parent or children. Affected classes: the class itself, or (for a tier) the
     * classes at that position, a recorded family read (PROFILE_CLASS, country, scheme, *) = position. Units: the
     * recorded family (CLASSIFICATION, *, scheme) = class, plus this revision. Each unit is reported once per claim.
     *
     * @param list<Claim> $claims
     * @return list<Violation>
     */
    private function profileChanges(array $claims, DependencyRecordingReader $reader): array
    {
        $violations = [];
        foreach ($claims as $claim) {
            $kind = $claim->slot->kind();
            $country = $claim->slot->subject();
            $scheme = $claim->slot->component('scheme');
            if (!in_array($kind, [ClaimKind::ProfileClass, ClaimKind::ProfileTier], true) || $country === null || $scheme === null) {
                continue;
            }
            $window = $claim->interval;
            $positions = new ProfilePositions(fn (SlotKey $slot): SlotHistory => $this->withRevision($slot, $window, $claims, $reader));
            $classes = [];
            if ($kind === ClaimKind::ProfileClass) {
                $classes[(string) $claim->slot->component('class')] = true;
            } else {
                $at = (string) $claim->slot->component('position');
                foreach ($reader->family(ClaimKind::ProfileClass, ['country_entry' => $country->value(), 'scheme' => $scheme], $at, $window) as $slot) {
                    $classes[(string) $slot->component('class')] = true;
                }
                foreach ($claims as $other) {
                    if ($other->slot->kind() === ClaimKind::ProfileClass && $other->slot->subject()?->equals($country)
                        && $other->slot->component('scheme') === $scheme && !$other->value->isNone() && $other->value->value() === $at) {
                        $classes[(string) $other->slot->component('class')] = true;
                    }
                }
            }
            foreach (array_keys($classes) as $class) {
                $class = (string) $class;
                $units = [];
                foreach ($reader->family(ClaimKind::Classification, ['scheme' => $scheme], $class, $window) as $slot) {
                    $units[$slot->encoded()] = $slot->subject();
                }
                foreach ($claims as $other) {
                    if ($other->slot->kind() === ClaimKind::Classification && $other->slot->component('scheme') === $scheme
                        && !$other->value->isNone() && $other->value->value() === $class) {
                        $units[$other->slot->encoded()] = $other->slot->subject();
                    }
                }
                foreach ($units as $unit) {
                    if ($unit !== null && ($problem = $this->unitBrokenByProfile($positions, $reader, $claims, $unit, $country, $scheme, $class, $window)) !== null) {
                        $violations[] = new Violation('G-6', $claim->id, $problem);
                    }
                }
            }
        }

        return $violations;
    }

    /** @param list<Claim> $claims */
    private function unitBrokenByProfile(ProfilePositions $positions, DependencyRecordingReader $reader, array $claims, ReferenceUnitId $unit, ReferenceUnitId $country, string $scheme, string $class, ValidInterval $window): ?string
    {
        $children = [];
        foreach ($reader->family(ClaimKind::Containment, ['scheme' => $scheme], $unit->value(), $window) as $slot) {
            $children[$slot->encoded()] = $slot->subject();
        }
        $checked = [];
        do {
            $new = false;
            foreach ($positions->checkpoints($window) as $t) {
                if (isset($checked[$t->value()])) {
                    continue;
                }
                $checked[$t->value()] = $new = true;
                if ($positions->classOf($unit, $scheme, $t) !== $class || !$positions->countryOf($unit, $scheme, $t)?->equals($country)) {
                    continue;
                }
                $position = $positions->positionOfClass($country, $scheme, $class, $t);
                if ($position === null) {
                    return "Unit {$unit->value()} would be classified '{$class}' with no position on {$t->value()}.";
                }
                if ($positions->tierAt($country, $scheme, $position, $t)?->kind !== TierKind::Territorial) {
                    return "Unit {$unit->value()} would sit at a non-territorial position {$position} on {$t->value()} (D-83).";
                }
                $parent = $positions->parentOf($unit, $scheme, $t);
                $parentPosition = $parent === null ? null : $positions->positionOf($parent, $scheme, $t);
                if ($parentPosition !== null && $parentPosition >= $position) {
                    return "Unit {$unit->value()} at {$position} would not sit below its parent at {$parentPosition} on {$t->value()} (G-4).";
                }
                foreach ($children as $child) {
                    if ($child !== null && $positions->parentOf($child, $scheme, $t)?->equals($unit)) {
                        $childPosition = $positions->positionOf($child, $scheme, $t);
                        if ($childPosition !== null && $position >= $childPosition) {
                            return "Unit {$unit->value()} at {$position} would not sit above its child at {$childPosition} on {$t->value()} (G-4).";
                        }
                    }
                }
            }
        } while ($new);

        return null;
    }

    /**
     * F-1, option (a) (Principal 2026-11-13; v3.11 §13): a CONTAINMENT or ADMISSION claim (a value or an end) that
     * changes country(u) at some date re-derives every descendant's position from another profile. At each such date
     * D-83 is re-checked for u and its affected descendants, and G-4 for each descendant's edge to its parent.
     * Descendants are found through recorded family reads (CONTAINMENT, scheme) = node, bounded by the profile depth,
     * and only when the country changes, so a move within one country reads no descendants. A descendant that is
     * itself admitted (its own country), or whose classification this revision also changes (checked by G-4/D-83 on
     * that claim), is skipped. Each (rule, unit) is reported once per revision.
     *
     * @param list<Claim> $claims
     * @return list<Violation>
     */
    private function countryChanges(array $claims, DependencyRecordingReader $reader): array
    {
        $violations = [];
        $reported = [];
        foreach ($claims as $claim) {
            $kind = $claim->slot->kind();
            $root = $claim->slot->subject();
            if ($root === null || !in_array($kind, [ClaimKind::Containment, ClaimKind::Admission], true)) {
                continue;
            }
            $window = $claim->interval;
            $after = new ProfilePositions(fn (SlotKey $slot): SlotHistory => $this->withRevision($slot, $window, $claims, $reader));
            $before = new ProfilePositions(fn (SlotKey $slot): SlotHistory => $reader->history($slot, $window));
            $schemes = $kind === ClaimKind::Containment ? [(string) $claim->slot->component('scheme')] : $this->childSchemes($root, $window, $claims, $reader);
            foreach ($schemes as $scheme) {
                $descendants = null;
                $checked = [];
                do {
                    $new = false;
                    $points = [];
                    foreach ([...$after->checkpoints($window), ...$before->checkpoints($window)] as $d) {
                        $points[$d->value()] = $d;
                    }
                    ksort($points);
                    foreach ($points as $t) {
                        if (isset($checked[$t->value()])) {
                            continue;
                        }
                        $checked[$t->value()] = $new = true;
                        $was = $before->countryOf($root, $scheme, $t);
                        $is = $after->countryOf($root, $scheme, $t);
                        $sameCountry = ($was === null && $is === null) || ($was !== null && $is !== null && $was->equals($is));
                        if ($sameCountry) {
                            continue;
                        }
                        $descendants ??= $this->descendants($root, $scheme, $window, $claims, $reader);
                        foreach ([$root, ...$descendants] as $unit) {
                            $isRoot = $unit->equals($root);
                            if ((!$isRoot && !$this->inSubtreeAt($after, $unit, $root, $scheme, $t)) || $after->isAdmitted($unit, $t) || $this->revisionClassifies($claims, $unit, $scheme)) {
                                continue;
                            }
                            $class = $after->classOf($unit, $scheme, $t);
                            if ($class === null) {
                                continue;
                            }
                            $key = 'D-83|' . $unit->value() . '|' . $scheme;
                            if (!isset($reported[$key]) && !$this->classIsTerritorial($after, $unit, $scheme, $class, $t)) {
                                $reported[$key] = true;
                                $violations[] = new Violation('D-83', $claim->id, "After the country change, unit {$unit->value()} ('{$class}') has no territorial position on {$t->value()}.");
                            }
                            $parent = $isRoot ? null : $after->parentOf($unit, $scheme, $t);
                            $key = 'G-4|' . $unit->value() . '|' . $scheme;
                            if ($parent === null || isset($reported[$key])) {
                                continue;
                            }
                            $pc = $after->positionOf($unit, $scheme, $t);
                            $pp = $after->positionOf($parent, $scheme, $t);
                            if ($pc !== null && $pp !== null && $pp >= $pc) {
                                $reported[$key] = true;
                                $violations[] = new Violation('G-4', $claim->id, "After the country change, position {$pp} of {$parent->value()} is not above position {$pc} of {$unit->value()} on {$t->value()}.");
                            }
                        }
                    }
                } while ($new);
            }
        }

        return $violations;
    }

    /**
     * @param list<Claim> $claims
     * @return list<string> the schemes in which $unit has children over the window (recorded family read, all schemes)
     */
    private function childSchemes(ReferenceUnitId $unit, ValidInterval $window, array $claims, DependencyRecordingReader $reader): array
    {
        $schemes = [];
        foreach ($reader->family(ClaimKind::Containment, [], $unit->value(), $window) as $slot) {
            $schemes[(string) $slot->component('scheme')] = true;
        }
        foreach ($claims as $other) {
            if ($other->slot->kind() === ClaimKind::Containment && !$other->value->isNone() && $other->value->value() === $unit->value()) {
                $schemes[(string) $other->slot->component('scheme')] = true;
            }
        }

        return array_map('strval', array_keys($schemes));
    }

    /**
     * Candidate descendants of $root in $scheme at any time of the window: breadth-first over recorded family reads,
     * plus this revision's containment claims, bounded by the profile depth.
     *
     * @param list<Claim> $claims
     * @return list<ReferenceUnitId>
     */
    private function descendants(ReferenceUnitId $root, string $scheme, ValidInterval $window, array $claims, DependencyRecordingReader $reader): array
    {
        $seen = [$root->value() => true];
        $found = [];
        $level = [$root];
        for ($depth = 0; $depth < 10 && $level !== []; $depth++) {
            $next = [];
            foreach ($level as $node) {
                $children = array_map(static fn (SlotKey $s): ?ReferenceUnitId => $s->subject(), $reader->family(ClaimKind::Containment, ['scheme' => $scheme], $node->value(), $window));
                foreach ($claims as $other) {
                    if ($other->slot->kind() === ClaimKind::Containment && $other->slot->component('scheme') === $scheme && !$other->value->isNone() && $other->value->value() === $node->value()) {
                        $children[] = $other->slot->subject();
                    }
                }
                foreach ($children as $child) {
                    if ($child !== null && !isset($seen[$child->value()])) {
                        $seen[$child->value()] = true;
                        $found[] = $next[] = $child;
                    }
                }
            }
            $level = $next;
        }

        return $found;
    }

    /** At t, does $unit's containment chain reach $root before any other admitted entry (its own country)? */
    private function inSubtreeAt(ProfilePositions $positions, ReferenceUnitId $unit, ReferenceUnitId $root, string $scheme, ValidDate $t): bool
    {
        $current = $positions->parentOf($unit, $scheme, $t);
        for ($depth = 0; $current !== null && $depth < 10; $depth++) {
            if ($current->equals($root)) {
                return true;
            }
            if ($positions->isAdmitted($current, $t)) {
                return false;
            }
            $current = $positions->parentOf($current, $scheme, $t);
        }

        return false;
    }

    /** @param list<Claim> $claims */
    private function revisionClassifies(array $claims, ReferenceUnitId $unit, string $scheme): bool
    {
        foreach ($claims as $other) {
            if ($other->slot->kind() === ClaimKind::Classification && $other->slot->component('scheme') === $scheme && $other->slot->subject()?->equals($unit)) {
                return true;
            }
        }

        return false;
    }

    private function classIsTerritorial(ProfilePositions $positions, ReferenceUnitId $unit, string $scheme, string $class, ValidDate $t): bool
    {
        $country = $positions->countryOf($unit, $scheme, $t);
        $position = $country === null ? null : $positions->positionOfClass($country, $scheme, $class, $t);
        $tier = $position === null ? null : $positions->tierAt($country, $scheme, $position, $t);

        return $tier?->kind === TierKind::Territorial;
    }

    /**
     * D-82 and the evidence basis, under D-227: for each claim resting on an instrument, the issuer's territorial
     * competence (territorial kinds only), and every cited mandate and precedence rule, must be in force ON THE
     * INSTRUMENT'S ISSUE/ADOPTION DATE. A relied-upon authority claim whose dispute state is "disputed" is recorded
     * as visible; it never refuses by itself. All reads are recorded over [issue date, next day).
     *
     * @param list<Claim> $claims
     * @param array<string, EvidenceVersion> $evidence
     * @param array<string, ClaimId> $disputed out: relied-upon claims under dispute
     * @return list<Violation>
     */
    private function authorityBasis(array $claims, array $evidence, DependencyRecordingReader $reader, array &$disputed): array
    {
        $violations = [];
        $checkedEvidence = [];
        foreach ($claims as $claim) {
            if ($claim->basis->kind !== BasisKind::Instrument) {
                // D-82: a territorial claim needs an issuer whose competence can be checked, so it rests on an
                // instrument. The exceptions are those v3.11 names: a BOUNDARY from a cartographic source attributed as
                // such (§3; only that basis kind, F-2) and withdrawals. No duplicate-search exception: an EXISTENCE
                // claim always rests on an instrument (I-4 as amended by D-229, enforced by Claim).
                $exempt = $claim->value->isNone()
                    || ($claim->slot->kind() === ClaimKind::Boundary && $claim->basis->kind === BasisKind::AttributedCartographicSource);
                if (in_array($claim->slot->kind(), self::TERRITORIAL_KINDS, true) && !$exempt) {
                    $violations[] = new Violation('D-82_BASIS', $claim->id, 'A territorial claim rests on an instrument of a competent issuer.');
                }
                continue;
            }
            $instrument = $evidence[$claim->basis->reference] ?? null;
            if (in_array($claim->slot->kind(), self::TERRITORIAL_KINDS, true)) {
                if ($instrument?->issuer === null || $instrument->issuedOn === null) {
                    $violations[] = new Violation('D-82_ISSUER_UNKNOWN', $claim->id, 'A territorial claim on an instrument needs the instrument\'s issuer and its issue/adoption day, per D-227.');
                    continue;
                }
                if (!$this->inForceOn(SlotKey::competence($instrument->issuer, 'territorial'), $instrument->issuedOn, $claims, $reader, $disputed)) {
                    $violations[] = new Violation('D-82', $claim->id, "Issuer {$instrument->issuer} had no territorial competence on {$instrument->issuedOn->value()}.");
                }
            }
            if ($instrument === null || $instrument->issuedOn === null || isset($checkedEvidence[$instrument->id])) {
                continue;
            }
            $checkedEvidence[$instrument->id] = true;
            foreach ($instrument->mandates as $mandate) {
                if (!$this->inForceOn(SlotKey::mandate($mandate), $instrument->issuedOn, $claims, $reader, $disputed)) {
                    $violations[] = new Violation('EVIDENCE_MANDATE', $claim->id, "Mandate {$mandate} was not in force on {$instrument->issuedOn->value()}.");
                }
            }
            foreach ($instrument->precedenceRules as $rule) {
                if (!$this->inForceOn(SlotKey::precedence($rule), $instrument->issuedOn, $claims, $reader, $disputed)) {
                    $violations[] = new Violation('EVIDENCE_PRECEDENCE', $claim->id, "Precedence rule {$rule} was not in force on {$instrument->issuedOn->value()}.");
                }
            }
        }

        return $violations;
    }

    /**
     * Is the registry slot in force on the date (published, with this revision on top)? If so, its dispute state on
     * that date is also read (recorded) and a "disputed" state is noted as visible.
     *
     * @param list<Claim> $claims
     * @param array<string, ClaimId> $disputed
     */
    private function inForceOn(SlotKey $slot, ValidDate $date, array $claims, DependencyRecordingReader $reader, array &$disputed): bool
    {
        $day = new ValidInterval($date, $date->nextDay());
        $relied = $this->withRevision($slot, $day, $claims, $reader)->claimAt($date);
        if ($relied === null || $relied->value->isNone()) {
            return false;
        }
        $state = $this->withRevision(SlotKey::dispute($relied->id), $day, $claims, $reader)->resolve($date);
        if ($state !== null && $state->value() === 'disputed') {
            $disputed[$relied->id->value()] = $relied->id;
        }

        return true;
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
