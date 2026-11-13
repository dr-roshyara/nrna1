<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * "Every slot, of every kind, about subject A, over all time" (v3.11 §5.2, reconciliation). Incoming mappings
 * (a value naming A) are a separate family dependency, e.g. (RECONCILIATION, *) with value A.
 */
final readonly class SubjectFamilyDependency implements Dependency
{
    public function __construct(public ReferenceUnitId $subject)
    {
    }

    public function isInvalidatedBy(PublishedClaim $published): bool
    {
        return $published->claim->slot->subject()?->equals($this->subject) ?? false;
    }

    public function describe(): string
    {
        return 'subject|' . $this->subject->value();
    }
}
