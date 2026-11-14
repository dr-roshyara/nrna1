<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * A Unicode CLDR release (D-87; v3.11 §9 "recording the CLDR release"), e.g. "46" or "46.1". A CLDR_NAME or
 * CLDR_INHERITANCE claim records the release it comes from in its basis: kind Other, reference "cldr:<release>"
 * (G-7; RevisionValidator rule D-87_RELEASE). Releases order numerically, part by part.
 */
final readonly class CldrRelease
{
    private const PREFIX = 'cldr:';

    private function __construct(private string $value)
    {
    }

    public static function of(string $value): self
    {
        if (preg_match('/^\d+(\.\d+)*$/', $value) !== 1) {
            throw new \InvalidArgumentException("A CLDR release is numeric, like 46 or 46.1, not '{$value}'.");
        }

        return new self($value);
    }

    /** The release a CLDR claim's basis names, or null if the basis names none. */
    public static function fromBasis(Basis $basis): ?self
    {
        if ($basis->kind !== BasisKind::Other || !str_starts_with($basis->reference, self::PREFIX)) {
            return null;
        }
        $release = substr($basis->reference, strlen(self::PREFIX));

        return preg_match('/^\d+(\.\d+)*$/', $release) === 1 ? new self($release) : null;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isNewerThan(self $other): bool
    {
        return version_compare($this->value, $other->value, '>');
    }

    public function equals(self $other): bool
    {
        return version_compare($this->value, $other->value, '==');
    }
}
