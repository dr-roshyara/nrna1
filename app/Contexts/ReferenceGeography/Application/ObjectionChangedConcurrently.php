<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

/** The objection changed since it was loaded; nothing was saved. */
final class ObjectionChangedConcurrently extends \RuntimeException
{
    public function __construct(string $objectionId)
    {
        parent::__construct("Objection {$objectionId} changed concurrently.");
    }
}
