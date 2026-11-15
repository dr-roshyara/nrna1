<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Domain;

/**
 * D-199 (option C1): how long an ordinary claim code stays valid.
 * - the organisation's rules (or a decision of a body they empower) set the period, passed in by the issuing context;
 * - rules silent → 14 days, labelled "platform default (D-199)"; it assigns no authority (D-167);
 * - platform-wide maximum 30 days: a longer requested period is capped at 30 and the cap is recorded.
 */
final readonly class ClaimWindow
{
    public const PLATFORM_DEFAULT_DAYS = 14;
    public const PLATFORM_MAXIMUM_DAYS = 30;

    private function __construct(public int $days, public string $source, public bool $capped)
    {
    }

    public static function fromRules(?int $requestedDays): self
    {
        if ($requestedDays === null) {
            return new self(self::PLATFORM_DEFAULT_DAYS, 'platform-default', false);
        }
        if ($requestedDays < 1) {
            throw new \InvalidArgumentException('A claim window is at least one day.');
        }

        return $requestedDays > self::PLATFORM_MAXIMUM_DAYS
            ? new self(self::PLATFORM_MAXIMUM_DAYS, 'rules', true)
            : new self($requestedDays, 'rules', false);
    }
}
