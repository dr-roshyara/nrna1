<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Context;

final readonly class GeographicScope
{
    public function __construct(
        public string $level,
        public ?string $code,
    ) {}
}
