<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Conflict;

use Countable;

final readonly class AuthorityConflictCollection implements Countable
{
    public function __construct(private array $conflicts) {}

    public static function empty(): self
    {
        return new self([]);
    }

    public static function of(AuthorityConflict ...$conflicts): self
    {
        return new self($conflicts);
    }

    public static function fromArray(array $conflicts): self
    {
        return new self($conflicts);
    }

    public function isEmpty(): bool
    {
        return empty($this->conflicts);
    }

    public function count(): int
    {
        return count($this->conflicts);
    }

    /**
     * @return AuthorityConflict[]
     */
    public function items(): array
    {
        return $this->conflicts;
    }
}
