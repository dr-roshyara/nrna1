<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * GeoPath Value Object
 *
 * Represents a PostgreSQL ltree path for geographic hierarchy.
 * Immutable and validates ltree format.
 *
 * Examples:
 * - "1" (province)
 * - "1.12" (province.district)
 * - "1.12.123.1234" (province.district.local.ward)
 *
 * Format: digits separated by dots, no leading/trailing dots.
 */
readonly class GeoPath
{
    private string $path;

    /**
     * Private constructor - use factory methods
     */
    private function __construct(string $path)
    {
        if (!self::isValid($path)) {
            throw new InvalidArgumentException("Invalid ltree path format: '{$path}'");
        }

        $this->path = $path;
    }

    /**
     * Create GeoPath from ltree string
     */
    public static function fromString(string $path): self
    {
        return new self($path);
    }

    /**
     * Create GeoPath from array of geography IDs
     *
     * @param array<int> $ids Geography unit IDs in hierarchy order
     */
    public static function fromIds(array $ids): self
    {
        // Filter null/empty values
        $filteredIds = array_filter($ids, fn($id) => $id !== null && $id !== '');

        if (empty($filteredIds)) {
            throw new InvalidArgumentException('Cannot create GeoPath from empty geography IDs');
        }

        // Validate all IDs are positive integers
        foreach ($filteredIds as $id) {
            if (!is_int($id) || $id <= 0) {
                throw new InvalidArgumentException("Invalid geography ID: {$id}. Must be positive integer.");
            }
        }

        return new self(implode('.', $filteredIds));
    }

    /**
     * Validate ltree format
     * PostgreSQL ltree: digits separated by dots, no leading/trailing dots
     */
    public static function isValid(string $path): bool
    {
        // Empty string is invalid
        if ($path === '') {
            return false;
        }

        // Must match pattern: digits separated by dots
        // Examples: "1", "1.12", "1.12.123"
        return preg_match('/^\d+(\.\d+)*$/', $path) === 1;
    }

    /**
     * Get ltree string representation
     */
    public function toString(): string
    {
        return $this->path;
    }

    /**
     * Get depth (number of levels)
     */
    public function getDepth(): int
    {
        return substr_count($this->path, '.') + 1;
    }

    /**
     * Get parent path (one level up)
     * Returns null if already at root level
     */
    public function getParentPath(): ?self
    {
        $lastDotPos = strrpos($this->path, '.');

        if ($lastDotPos === false) {
            return null; // No parent (root level)
        }

        $parentPath = substr($this->path, 0, $lastDotPos);
        return new self($parentPath);
    }

    /**
     * Check if this path is descendant of another path
     */
    public function isDescendantOf(self $ancestor): bool
    {
        // Check if this path starts with ancestor path followed by dot
        // or is equal to ancestor path (not descendant)
        return str_starts_with($this->path . '.', $ancestor->path . '.');
    }

    /**
     * Check if this path is ancestor of another path
     */
    public function isAncestorOf(self $descendant): bool
    {
        return $descendant->isDescendantOf($this);
    }

    /**
     * Get array of level IDs
     *
     * @return array<int>
     */
    public function getLevelIds(): array
    {
        return array_map('intval', explode('.', $this->path));
    }

    /**
     * Get ID at specific level (1-based)
     * Returns null if level doesn't exist
     */
    public function getLevelId(int $level): ?int
    {
        $ids = $this->getLevelIds();
        return $ids[$level - 1] ?? null;
    }

    /**
     * Check if path contains specific ID at any level
     */
    public function containsId(int $id): bool
    {
        return in_array($id, $this->getLevelIds(), true);
    }

    /**
     * String representation for debugging
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}