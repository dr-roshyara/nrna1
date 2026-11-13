<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;

/** A Geography act was refused by governance (v3.11 §8; D-84, D-84a, D-88): nothing was written. */
final class GeographyActRefused extends \RuntimeException
{
    /** @param list<GovernanceRefusal> $refusals */
    public function __construct(public readonly array $refusals)
    {
        parent::__construct('Refused: ' . implode(', ', array_map(static fn (GovernanceRefusal $r): string => $r->value, $refusals)));
    }
}
