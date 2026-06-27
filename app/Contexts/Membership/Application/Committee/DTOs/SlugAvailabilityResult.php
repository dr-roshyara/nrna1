<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\DTOs;

final readonly class SlugAvailabilityResult
{
    public function __construct(
        public bool $exists,
        public bool $reserved,
        public string $slug,
        public array $suggestions,
        public ?string $errorKey = null,
    ) {}

    public function toArray(): array
    {
        return [
            'exists' => $this->exists,
            'reserved' => $this->reserved,
            'slug' => $this->slug,
            'suggestions' => $this->suggestions,
            'error_key' => $this->errorKey,
        ];
    }

    public function isAvailable(): bool
    {
        return !$this->exists && !$this->reserved;
    }
}
