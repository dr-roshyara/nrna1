<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

final readonly class GeoScope
{
    public function __construct(
        public string $code
    ) {
        if (empty($code)) {
            throw new \InvalidArgumentException('GeoScope code cannot be empty');
        }
    }

    public function equals(GeoScope $other): bool
    {
        return $this->code === $other->code;
    }
}
