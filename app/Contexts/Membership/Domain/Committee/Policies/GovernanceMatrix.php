<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Policies;

final readonly class GovernanceMatrix
{
    /** @param MatrixCell[] $cells */
    public function __construct(private array $cells) {}

    public function isAllowed(int $gov, int $geo): bool
    {
        foreach ($this->cells as $cell) {
            if ($cell->governanceLevel === $gov && $cell->geoLevel === $geo) {
                return $cell->allowed;
            }
        }
        return false;
    }

    public function allowedGovernanceLevelsForGeo(int $geo): array
    {
        $result = [];
        foreach ($this->cells as $cell) {
            if ($cell->geoLevel === $geo && $cell->allowed) {
                $result[] = $cell->governanceLevel;
            }
        }
        return array_values(array_unique($result));
    }

    public static function fromRows(array $rows): self
    {
        $cells = [];
        foreach ($rows as $row) {
            $gov = (int) $row['level'];
            $cells[] = new MatrixCell(
                governanceLevel: $gov,
                geoLevel: $gov, // diagonal seed — awaits geo_level column migration @todo
                allowed: (bool) ($row['is_active'] ?? true),
            );
        }
        return new self($cells);
    }
}
