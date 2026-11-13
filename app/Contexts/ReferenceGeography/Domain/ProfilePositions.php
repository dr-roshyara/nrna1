<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * Derived positions (v3.11 §13; D-81, D-91), for one validation window:
 * - level 2 is marked by ADMISSION; a level-2 entry is at position 2;
 * - country(u, t) = u's level-2 ancestor through containment in the scheme at t (bounded walk);
 * - position(u, t) = PROFILE_CLASS(country(u), scheme, class(u)); never computed by counting parents.
 * Every slot is read through the given function (the validator's recorded read with this revision on top), once.
 */
final class ProfilePositions
{
    private const MAX_DEPTH = 10;

    /** @var array<string, SlotHistory> */
    private array $read = [];

    /** @param \Closure(SlotKey): SlotHistory $history */
    public function __construct(private readonly \Closure $history)
    {
    }

    public function isAdmitted(ReferenceUnitId $unit, ValidDate $t): bool
    {
        return $this->history(SlotKey::admission($unit))->priorAt($t)->isSomeValue();
    }

    public function parentOf(ReferenceUnitId $unit, string $scheme, ValidDate $t): ?ReferenceUnitId
    {
        $parent = $this->history(SlotKey::containment($unit, $scheme))->resolve($t)?->value();
        if ($parent === null) {
            return null;
        }
        try {
            return ReferenceUnitId::fromString($parent);
        } catch (\InvalidArgumentException) {
            return null;
        }
    }

    public function countryOf(ReferenceUnitId $unit, string $scheme, ValidDate $t): ?ReferenceUnitId
    {
        $current = $unit;
        for ($depth = 0; $depth <= self::MAX_DEPTH; $depth++) {
            if ($this->isAdmitted($current, $t)) {
                return $current;
            }
            $current = $this->parentOf($current, $scheme, $t);
            if ($current === null) {
                return null;
            }
        }

        return null;
    }

    public function classOf(ReferenceUnitId $unit, string $scheme, ValidDate $t): ?string
    {
        return $this->history(SlotKey::classification($unit, $scheme))->resolve($t)?->value();
    }

    public function positionOfClass(ReferenceUnitId $country, string $scheme, string $class, ValidDate $t): ?int
    {
        $value = $this->history(SlotKey::profileClass($country, $scheme, $class))->resolve($t)?->value();

        return $value !== null && self::isProfilePosition($value) ? (int) $value : null;
    }

    public function positionOf(ReferenceUnitId $unit, string $scheme, ValidDate $t): ?int
    {
        if ($this->isAdmitted($unit, $t)) {
            return 2;
        }
        $class = $this->classOf($unit, $scheme, $t);
        $country = $class === null ? null : $this->countryOf($unit, $scheme, $t);

        return $country === null ? null : $this->positionOfClass($country, $scheme, $class, $t);
    }

    public function tierAt(ReferenceUnitId $country, string $scheme, int $position, ValidDate $t): ?TierMeaning
    {
        $value = $this->history(SlotKey::profileTier($country, $scheme, $position))->resolve($t)?->value();
        try {
            return $value === null ? null : TierMeaning::parse($value);
        } catch (\InvalidArgumentException) {
            return null;
        }
    }

    /** @return list<ValidDate> the window's start and every date inside it where a slot read so far changes */
    public function checkpoints(ValidInterval $window): array
    {
        $points = [$window->from->value() => $window->from];
        foreach ($this->read as $history) {
            foreach ($history->boundariesWithin($window) as $d) {
                $points[$d->value()] = $d;
            }
        }
        ksort($points);

        return array_values($points);
    }

    /** Profile positions 3–10 (v3.11 §13: 0–2 are fixed), as a canonical decimal. */
    public static function isProfilePosition(string $value): bool
    {
        return preg_match('/^(?:[3-9]|10)$/', $value) === 1;
    }

    private function history(SlotKey $slot): SlotHistory
    {
        return $this->read[$slot->encoded()] ??= ($this->history)($slot);
    }
}
