<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\DTOs;

use JsonSerializable;

/**
 * Lightweight geographic unit summary for selects, dropdowns, and cascaders.
 *
 * Carries only the fields needed for UI selection — no temporal or hierarchy data.
 */
final readonly class GeoUnitSummaryResponse implements JsonSerializable
{
    public function __construct(
        public int $id,
        public string $adminType,
        public string $name,
        public ?string $code = null,
        public ?int $parentId = null,
    ) {}

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
