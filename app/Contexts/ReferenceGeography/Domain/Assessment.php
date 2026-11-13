<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** One assessment with its recorded outcome and assessor. Its adequacy is judged at publication (G-3), not here. */
final readonly class Assessment
{
    public function __construct(public AssessmentKind $kind, public string $outcome, public PersonReference $by)
    {
        if (trim($outcome) === '') {
            throw new \InvalidArgumentException('An assessment records its outcome.');
        }
    }
}
