<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Published;

/**
 * resolve(B, p) (v3.11 §12, consumers): the canonical identity, and since when that is known. knownAtPosition is
 * authoritative; recordedAt is supporting information (ties are possible).
 */
final readonly class Resolution
{
    public function __construct(
        public string $requested,
        public string $canonical,
        public bool $merged,
        public ?int $knownAtPosition = null,
        public ?string $recordedAt = null,
        public ?string $revisionId = null,
    ) {
    }
}
