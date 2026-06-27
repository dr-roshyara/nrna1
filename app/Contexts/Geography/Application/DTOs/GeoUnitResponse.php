<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\DTOs;

use JsonSerializable;

final readonly class GeoUnitResponse implements JsonSerializable
{
    public function __construct(
        public int $id,
        public string $countryCode,
        public int $adminLevel,
        public string $adminType,
        public ?int $parentId,
        public ?string $code,
        public array $name,
        public bool $isActive,
        public ?string $validFrom,
        public ?string $validTo,
    ) {}

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
