<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** A domain refusal (Rule 8): the step is not allowed for this revision now. */
final class RevisionRefused extends \DomainException
{
    public function __construct(public readonly RevisionRefusal $refusal)
    {
        parent::__construct("Revision: {$refusal->value}");
    }
}
