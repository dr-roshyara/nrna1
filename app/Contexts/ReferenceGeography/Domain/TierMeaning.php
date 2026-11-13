<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The value of a PROFILE_TIER claim (v3.11 §13): the meaning of one position, as a tier kind and a tier label.
 * Canonical encoding "KIND:label", stable for digests and comparison.
 */
final readonly class TierMeaning
{
    public function __construct(public TierKind $kind, public string $label)
    {
        if (trim($label) === '') {
            throw new \InvalidArgumentException('A tier has a label.');
        }
    }

    public static function parse(string $encoded): self
    {
        $parts = explode(':', $encoded, 2);
        $kind = TierKind::tryFrom($parts[0]);
        if ($kind === null || !isset($parts[1]) || trim($parts[1]) === '') {
            throw new \InvalidArgumentException('A tier value is "TERRITORIAL:label" or "ADDRESS_COMPONENT:label".');
        }

        return new self($kind, $parts[1]);
    }

    public function encoded(): string
    {
        return $this->kind->value . ':' . $this->label;
    }
}
