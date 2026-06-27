<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot;

final readonly class SnapshotSchemaVersion
{
    public const CURRENT = '1.0';

    private function __construct(private string $value) {}

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function isCompatibleWith(self $other): bool
    {
        return $this->equals($other);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
