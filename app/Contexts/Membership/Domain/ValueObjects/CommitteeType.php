<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * CommitteeType Value Object
 *
 * Represents the type of a committee in a political party.
 * Immutable and self-validating.
 *
 * Political Party Committee Types:
 * - CENTRAL: National level, no geography, 2+ years membership required
 * - PROVINCE: Province level, member must live in same province
 * - DISTRICT: District level, member must live in same district
 * - WARD: Ward level, member must live in same ward
 * - YOUTH_WING: Youth wing, age 18-35, geography optional
 * - WOMEN_WING: Women wing, women only, age 18+, geography optional
 * - STUDENT_WING: Student wing, student status, geography optional
 * - DIASPORA: Members living abroad, foreign country geography
 */
final class CommitteeType
{
    private const CENTRAL = 'central';
    private const PROVINCE = 'province';
    private const DISTRICT = 'district';
    private const WARD = 'ward';
    private const YOUTH_WING = 'youth_wing';
    private const WOMEN_WING = 'women_wing';
    private const STUDENT_WING = 'student_wing';
    private const DIASPORA = 'diaspora';
    private const GEOGRAPHIC = 'geographic';

    private const VALID_TYPES = [
        self::CENTRAL,
        self::PROVINCE,
        self::DISTRICT,
        self::WARD,
        self::YOUTH_WING,
        self::WOMEN_WING,
        self::STUDENT_WING,
        self::DIASPORA,
        self::GEOGRAPHIC,
    ];

    private string $value;

    private function __construct(string $type)
    {
        $normalized = strtolower(trim($type));

        if (!in_array($normalized, self::VALID_TYPES, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid committee type "%s". Valid types: %s',
                    $type,
                    implode(', ', self::VALID_TYPES)
                )
            );
        }

        $this->value = $normalized;
    }

    public static function fromString(string $type): self
    {
        return new self($type);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(CommitteeType $other): bool
    {
        return $this->value === $other->value;
    }

    // Factory methods for each committee type
    public static function central(): self
    {
        return new self(self::CENTRAL);
    }

    public static function province(): self
    {
        return new self(self::PROVINCE);
    }

    public static function district(): self
    {
        return new self(self::DISTRICT);
    }

    public static function ward(): self
    {
        return new self(self::WARD);
    }

    public static function youth(): self
    {
        return new self(self::YOUTH_WING);
    }

    public static function youthWing(): self
    {
        return self::youth(); // Alias for consistency
    }

    public static function women(): self
    {
        return new self(self::WOMEN_WING);
    }

    public static function womenWing(): self
    {
        return self::women(); // Alias for consistency
    }

    public static function student(): self
    {
        return new self(self::STUDENT_WING);
    }

    public static function studentWing(): self
    {
        return self::student(); // Alias for consistency
    }

    public static function diaspora(): self
    {
        return new self(self::DIASPORA);
    }

    public static function geographic(): self
    {
        return new self(self::GEOGRAPHIC);
    }

    // Business rule checks
    public function requiresGeography(): bool
    {
        // CENTRAL committee has no geography requirement
        // YOUTH_WING, WOMEN_WING, STUDENT_WING wings have optional geography
        // Others require geography
        return match ($this->value) {
            self::CENTRAL => false,
            self::YOUTH_WING, self::WOMEN_WING, self::STUDENT_WING => false, // optional, but not required
            default => true,
        };
    }

    public function isCentral(): bool
    {
        return $this->value === self::CENTRAL;
    }

    public function isYouthWing(): bool
    {
        return $this->value === self::YOUTH_WING;
    }

    public function isYouth(): bool
    {
        return $this->isYouthWing();
    }

    public function isWomenWing(): bool
    {
        return $this->value === self::WOMEN_WING;
    }

    public function isWomen(): bool
    {
        return $this->isWomenWing();
    }

    public function isStudentWing(): bool
    {
        return $this->value === self::STUDENT_WING;
    }

    public function isStudent(): bool
    {
        return $this->isStudentWing();
    }

    public function isDiaspora(): bool
    {
        return $this->value === self::DIASPORA;
    }

    public function isWing(): bool
    {
        return $this->isYouthWing() || $this->isWomenWing() || $this->isStudentWing() || $this->isDiaspora();
    }

    public function isGeographic(): bool
    {
        // Committees that operate in a specific geographic area
        return in_array($this->value, [
            self::PROVINCE,
            self::DISTRICT,
            self::WARD,
            self::GEOGRAPHIC,
        ], true);
    }

    public function getDescription(): string
    {
        return match ($this->value) {
            self::CENTRAL => 'Central Committee (National Level)',
            self::PROVINCE => 'Province Committee',
            self::DISTRICT => 'District Committee',
            self::WARD => 'Ward Committee',
            self::YOUTH_WING => 'Youth Wing',
            self::WOMEN_WING => 'Women Wing',
            self::STUDENT_WING => 'Student Wing',
            self::DIASPORA => 'Diaspora Committee',
            self::GEOGRAPHIC => 'Geographic Committee',
            default => 'Unknown Committee Type',
        };
    }
}