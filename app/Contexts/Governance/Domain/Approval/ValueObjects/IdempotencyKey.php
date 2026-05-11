<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Approval\ValueObjects;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;

final readonly class IdempotencyKey
{
    private function __construct(private string $value) {}

    public static function generate(CommitteeId $committeeId, DateTimeImmutable $now): self
    {
        $timestamp = $now->format('Ymd_His');
        $random = bin2hex(random_bytes(4));
        $key = "approval_{$committeeId->value()}_{$timestamp}_{$random}";

        return new self($key);
    }

    public static function from(string $value): self
    {
        if (trim($value) === '') {
            throw new \DomainException('IdempotencyKey cannot be empty');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
