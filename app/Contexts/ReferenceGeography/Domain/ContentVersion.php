<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** A frozen content version (v3.11 §5.3): its number and the SHA-256 digest of everything judged. */
final readonly class ContentVersion
{
    public function __construct(public int $number, public string $digest)
    {
    }
}
