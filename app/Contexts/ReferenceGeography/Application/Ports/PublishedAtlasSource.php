<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Domain\PublishedAtlas;

/** The published atlas at the committed position, for validation before the lock (v3.11 §6 "before the lock"). */
interface PublishedAtlasSource
{
    public function current(): PublishedAtlas;
}
