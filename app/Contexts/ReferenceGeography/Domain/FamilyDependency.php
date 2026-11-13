<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * "All slots of one kind, with fixed key components, whose value is X, over V" (v3.11 §5.1), e.g. children of A
 * in scheme S = (CONTAINMENT, *, S) with value A. Invalidated by a claim in the family whose NEW value is X over an
 * overlapping interval (a gain), or whose BEFORE-IMAGE had X there (a loss). One of the two always satisfies the
 * condition when the family's result changes, so gains and losses are both caught.
 */
final readonly class FamilyDependency implements Dependency
{
    /** @param array<string, string> $fixed key components that must match; all others are wildcards */
    private function __construct(public ClaimKind $kind, public array $fixed, public string $value, public ValidInterval $interval)
    {
    }

    /** @param array<string, string> $fixed */
    public static function of(ClaimKind $kind, array $fixed, string $value, ValidInterval $interval): self
    {
        if (trim($value) === '') {
            throw new \InvalidArgumentException('A family dependency names the value it watches.');
        }
        ksort($fixed);

        return new self($kind, $fixed, $value, $interval);
    }

    public function isInvalidatedBy(PublishedClaim $published): bool
    {
        $claim = $published->claim;
        if ($claim->slot->kind() !== $this->kind) {
            return false;
        }
        foreach ($this->fixed as $label => $expected) {
            if ($claim->slot->component($label) !== $expected) {
                return false;
            }
        }
        $gain = !$claim->value->isNone() && $claim->value->value() === $this->value && $claim->interval->overlaps($this->interval);

        return $gain || $published->beforeImage->hadValue($this->value, $this->interval);
    }

    public function describe(): string
    {
        $fixed = implode(',', array_map(static fn (string $k, string $v): string => $k . '=' . strlen($v) . ':' . $v, array_keys($this->fixed), $this->fixed));

        return 'family|' . $this->kind->value . '|' . $fixed . '|' . strlen($this->value) . ':' . $this->value . '|' . $this->interval->from->value() . '|' . ($this->interval->to?->value() ?? '');
    }
}
