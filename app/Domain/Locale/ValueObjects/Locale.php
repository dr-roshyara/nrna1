<?php

namespace App\Domain\Locale\ValueObjects;

use InvalidArgumentException;

final readonly class Locale
{
    private const SUPPORTED = ['de', 'en', 'np'];

    public function __construct(private string $value)
    {
        if (!in_array($value, self::SUPPORTED, strict: true)) {
            throw new InvalidArgumentException("Unsupported locale: {$value}");
        }
    }

    public static function default(): self
    {
        return new self('en');
    }

    public static function isSupported(string $locale): bool
    {
        return in_array($locale, self::SUPPORTED, strict: true);
    }

    public function value(): string
    {
        return $this->value;
    }
}
