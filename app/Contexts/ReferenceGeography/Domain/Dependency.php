<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** A dependency predicate recorded at validation (v3.11 §5.1). */
interface Dependency
{
    /** Would this published claim change what the validation relied on? */
    public function isInvalidatedBy(PublishedClaim $published): bool;

    /** A canonical description, for the content-version digest (§5.3). */
    public function describe(): string;
}
