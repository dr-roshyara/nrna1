<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

final readonly class GeographicStructure
{
    /** @param GeographicLevelConfig[] $levels */
    public function __construct(private array $levels)
    {
        $this->validate();
    }

    public static function forNepal(): self
    {
        return new self([
            new GeographicLevelConfig(1, 'geo_unit', 1, 'Province',     'प्रदेश',    true),
            new GeographicLevelConfig(2, 'geo_unit', 2, 'District',     'जिल्ला',    true),
            new GeographicLevelConfig(3, 'geo_unit', 3, 'Municipality', 'नगरपालिका', false),
            new GeographicLevelConfig(4, 'geo_unit', 4, 'Ward',         'वडा',       false),
        ]);
    }

    public static function forWorldwideOrg(): self
    {
        return new self([
            new GeographicLevelConfig(1, 'static',   null, 'Worldwide',  null, true),
            new GeographicLevelConfig(2, 'region',   null, 'Region',     null, true),
            new GeographicLevelConfig(3, 'country',  null, 'Country',    null, true),
            new GeographicLevelConfig(4, 'geo_unit', 1,    'Province',   null, false),
        ]);
    }

    public static function fromArray(array $data): self
    {
        return new self(array_map(fn(array $l) => GeographicLevelConfig::fromArray($l), $data));
    }

    /** @return GeographicLevelConfig[] */
    public function getLevels(): array
    {
        return $this->levels;
    }

    public function getMaxDepth(): int
    {
        return count($this->levels);
    }

    /** [{index, db_level, label, type}] — for API/frontend, no local_label/required */
    public function getLevelsArray(): array
    {
        return array_map(fn(GeographicLevelConfig $l) => [
            'index'    => $l->index,
            'type'     => $l->type,
            'db_level' => $l->dbLevel,
            'label'    => $l->label,
        ], $this->levels);
    }

    public function getLabels(): array
    {
        return array_map(fn(GeographicLevelConfig $l) => $l->label, $this->levels);
    }

    /** DB-serialisable. No count field. */
    public function toArray(): array
    {
        return array_map(fn(GeographicLevelConfig $l) => $l->toArray(), $this->levels);
    }

    /** Append-only. Returns new immutable instance. */
    public function extend(string $type = 'geo_unit', ?int $dbLevel = null, string $label = '', ?string $localLabel = null, bool $required = false): self
    {
        $levels   = $this->levels;
        $levels[] = new GeographicLevelConfig(count($levels) + 1, $type, $dbLevel, $label, $localLabel, $required);
        return new self($levels);
    }

    private function validate(): void
    {
        $indexes  = array_map(fn($l) => $l->index,   $this->levels);
        $dbLevels = array_map(fn($l) => $l->dbLevel, $this->levels);
        // Filter out null values for duplicate check (non-geo_unit types can have null db_level)
        $nonNullDbLevels = array_filter($dbLevels, fn($v) => $v !== null);

        if (count($indexes) !== count(array_unique($indexes))) {
            throw new \DomainException('Duplicate level indexes in GeographicStructure');
        }
        if (count($nonNullDbLevels) !== count(array_unique($nonNullDbLevels))) {
            throw new \DomainException('Duplicate db_level values in GeographicStructure');
        }
    }
}
