<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

use App\Contexts\Membership\Domain\Committee\Exceptions\InvalidCommitteeSlugException;

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
        $normalized = self::slugify($input);

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
        return self::fromString($name);
    }

    // Pure-PHP slug — the domain layer must not depend on Illuminate\Support\Str.
    private static function slugify(string $value): string
    {
        $transliterated = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', trim($value));
        if ($transliterated !== false) {
            $value = $transliterated;
        }

        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';

        return trim($value, '-');
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
