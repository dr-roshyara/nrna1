<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Precedence;

use Countable;

final readonly class AuthorityPrecedenceRanking implements Countable
{
    public function __construct(private array $ranked) {}

    public static function empty(): self
    {
        return new self([]);
    }

    public static function fromArray(array $ranked): self
    {
        return new self($ranked);
    }

    public function top(): ?array
    {
        return empty($this->ranked) ? null : $this->ranked[0];
    }

    public function isEmpty(): bool
    {
        return empty($this->ranked);
    }

    public function count(): int
    {
        return count($this->ranked);
    }

    public function items(): array
    {
        return $this->ranked;
    }
}
