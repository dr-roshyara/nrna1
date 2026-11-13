<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** The evidence a claim rests on: its kind and a reference to the instrument, assessment or source record. */
final readonly class Basis
{
    public function __construct(public BasisKind $kind, public string $reference)
    {
        if (trim($reference) === '') {
            throw new \InvalidArgumentException('A basis needs its reference.');
        }
    }
}
