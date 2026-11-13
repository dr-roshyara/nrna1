<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

/** One committed publication entry, as the read side needs it (v3.11 §6). */
final readonly class PublicationRecord
{
    public function __construct(public int $position, public string $recordedAt, public ?string $revisionId)
    {
    }
}
