<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

use App\Contexts\Membership\Domain\Committee\Exceptions\InvalidCommitteeSlugException;
use Illuminate\Support\Str;

final readonly class CommitteeSlug
{
    private const RESERVED = [
        'admin', 'api', 'dashboard', 'settings', 'committee',
        'committees', 'create', 'new', 'edit', 'delete', 'system',
        'root', 'login', 'logout', 'register',
    ];

    private function __construct(private string $value) {}

    public static function fromString(string $input): self
    {
        $normalized = Str::slug($input);

        if ($normalized === '') {
            throw InvalidCommitteeSlugException::empty();
        }
        if (in_array($normalized, self::RESERVED, true)) {
            throw InvalidCommitteeSlugException::reserved($normalized);
        }

        return new self($normalized);
    }

    public static function fromName(string $name): self
    {
        return self::fromString(Str::slug($name));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
