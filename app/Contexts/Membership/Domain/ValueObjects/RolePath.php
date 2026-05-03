<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * RolePath Value Object
 *
 * Represents a hierarchical role path within a committee structure.
 * Used to identify specific positions in committee hierarchies.
 *
 * Format: Dot-separated numeric path (e.g., '1.1.1', '2.3.1')
 * Examples:
 * - '1.1.1' → Leadership → Chairperson → Central Committee
 * - '2.3.1' → Subcommittee → Treasurer → Provincial Committee
 *
 * Business Rules:
 * - Each committee type has specific valid role paths
 * - Role paths enforce hierarchical uniqueness (only one active per path per committee)
 * - Paths can be mapped to human-readable role names via configuration
 *
 * Design Decision:
 * - Store as string path, not foreign key (flexibility, decoupling)
 * - Numeric-only for consistency and ordering
 * - Max depth: 5 levels (committee hierarchies are typically shallow)
 */
final class RolePath
{
    private const MAX_DEPTH = 5;
    private const MAX_LENGTH = 255;

    private string $value;

    public function __construct(string $path)
    {
        $this->validate($path);
        $this->value = $path;
    }

    /**
     * Create RolePath from string
     */
    public static function fromString(string $path): self
    {
        return new self($path);
    }

    private function validate(string $path): void
    {
        $path = trim($path);

        if (empty($path)) {
            throw new InvalidArgumentException('Role path cannot be empty');
        }

        if (strlen($path) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Role path cannot exceed %d characters', self::MAX_LENGTH)
            );
        }

        // Must be dot-separated numeric segments
        if (!preg_match('/^[0-9]+(\.[0-9]+)*$/', $path)) {
            throw new InvalidArgumentException(
                'Role path must be dot-separated numeric segments (e.g., "1.1.1")'
            );
        }

        // Check depth limit
        $segments = explode('.', $path);
        if (count($segments) > self::MAX_DEPTH) {
            throw new InvalidArgumentException(
                sprintf('Role path cannot exceed %d levels', self::MAX_DEPTH)
            );
        }

        // Each segment must be non-zero
        foreach ($segments as $segment) {
            if ($segment === '0') {
                throw new InvalidArgumentException('Role path segments cannot be zero');
            }
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * Get path segments as array
     */
    public function segments(): array
    {
        return explode('.', $this->value);
    }

    /**
     * Get path depth (number of segments)
     */
    public function depth(): int
    {
        return count($this->segments());
    }

    /**
     * Check if this role path is a descendant of another role path
     *
     * Used for hierarchical role validation (e.g., '1.1.1' is descendant of '1.1')
     */
    public function isDescendantOf(RolePath $other): bool
    {
        $otherPrefix = $other->value . '.';
        return str_starts_with($this->value . '.', $otherPrefix);
    }

    /**
     * Check if this role path is an ancestor of another role path
     */
    public function isAncestorOf(RolePath $other): bool
    {
        return $other->isDescendantOf($this);
    }

    /**
     * Get parent role path (one level up)
     *
     * @return RolePath|null Returns null if already at root level
     */
    public function parent(): ?RolePath
    {
        $segments = $this->segments();
        if (count($segments) <= 1) {
            return null;
        }
        array_pop($segments);
        return new self(implode('.', $segments));
    }

    public function equals(RolePath $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    // Common role paths for convenience
    public static function centralChairperson(): self
    {
        return new self('1.1.1');
    }

    public static function centralSecretary(): self
    {
        return new self('1.1.2');
    }

    public static function centralTreasurer(): self
    {
        return new self('1.1.3');
    }

    public static function provincialChairperson(): self
    {
        return new self('2.1.1');
    }

    public static function provincialSecretary(): self
    {
        return new self('2.1.2');
    }

    public static function districtChairperson(): self
    {
        return new self('3.1.1');
    }

    public static function wardChairperson(): self
    {
        return new self('4.1.1');
    }
}