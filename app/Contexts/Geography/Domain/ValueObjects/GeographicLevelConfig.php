<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

final readonly class GeographicLevelConfig
{
    private const VALID_TYPES = ['static', 'region', 'country', 'geo_unit'];

    public function __construct(
        public readonly int     $index,
        public readonly string  $type,
        public readonly ?int    $dbLevel,
        public readonly string  $label,
        public readonly ?string $localLabel,
        public readonly bool    $required,
    ) {
        if ($this->index < 1 || $this->index > 10) {
            throw new \InvalidArgumentException("Level index must be 1–10");
        }
        if (!in_array($this->type, self::VALID_TYPES)) {
            throw new \InvalidArgumentException("Invalid type: {$this->type}");
        }
        if ($this->type === 'geo_unit' && $this->dbLevel === null) {
            throw new \InvalidArgumentException("geo_unit type requires non-null db_level");
        }
        if ($this->type !== 'geo_unit' && $this->dbLevel !== null && $this->dbLevel < 1) {
            throw new \InvalidArgumentException("db_level must be >= 1 when provided");
        }
    }

    public static function fromArray(array $data): self
    {
        return new self(
            index:      (int) $data['index'],
            type:       (string) ($data['type'] ?? 'geo_unit'),
            dbLevel:    isset($data['db_level']) ? (int) $data['db_level'] : null,
            label:      (string) $data['label'],
            localLabel: $data['local_label'] ?? null,
            required:   (bool) ($data['required'] ?? false),
        );
    }

    public function toArray(): array
    {
        return [
            'index'       => $this->index,
            'type'        => $this->type,
            'db_level'    => $this->dbLevel,
            'label'       => $this->label,
            'local_label' => $this->localLabel,
            'required'    => $this->required,
        ];
    }
}
