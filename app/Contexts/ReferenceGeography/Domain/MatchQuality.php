<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** PlaceLookup match quality: the legacy matcher's bands, copied (G-5b; completion §2). Below 0.50 is no match. */
enum MatchQuality: string
{
    case Exact = 'exact';
    case VeryHigh = 'very_high';
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';

    public static function fromScore(float $score): ?self
    {
        return match (true) {
            $score >= 1.0 => self::Exact,
            $score >= 0.95 => self::VeryHigh,
            $score >= 0.85 => self::High,
            $score >= 0.70 => self::Medium,
            $score >= 0.50 => self::Low,
            default => null,
        };
    }
}
