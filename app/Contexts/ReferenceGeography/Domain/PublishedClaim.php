<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** A claim as published: with its stored before-image and its publication position (v3.11 §5.1, §6). */
final readonly class PublishedClaim
{
    public function __construct(public Claim $claim, public BeforeImage $beforeImage, public int $position)
    {
        if ($position < 1) {
            throw new \InvalidArgumentException('A publication position is positive.');
        }
    }
}
