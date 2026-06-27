# Geography Context - Models & Value Objects

## Overview

Geography models are organized in a **Domain-Driven Design** structure:

- **Domain Layer**: Pure PHP with no framework dependencies
- **Application Layer**: Handlers and services for use cases
- **Infrastructure Layer**: Eloquent models and database mapping

---

## Domain Models

### 1. GeographicUnit (Domain Aggregate)

The core domain model representing a geographic location in a hierarchy.

```php
namespace App\Contexts\Geography\Domain;

class GeographicUnit
{
    public function __construct(
        private GeoUnitId $id,
        private TenantId $tenantId,
        private CountryCode $countryCode,
        private GeographyLevel $level,
        private ?GeoUnitId $parentId,
        private GeoPath $path,
        private LocalizedName $name,
        private ?GeographicCode $code = null,
        private bool $isActive = true,
    ) {
        $this->validate();
    }

    public static function create(
        GeoUnitId $id,
        TenantId $tenantId,
        CountryCode $countryCode,
        GeographyLevel $level,
        ?GeoUnitId $parentId,
        GeoPath $path,
        LocalizedName $name,
        ?GeographicCode $code = null,
    ): self {
        return new self(
            $id,
            $tenantId,
            $countryCode,
            $level,
            $parentId,
            $path,
            $name,
            $code,
            true
        );
    }

    private function validate(): void
    {
        // Business rule: A non-root unit MUST have a parent
        if ($this->level->value() > 1 && $this->parentId === null) {
            throw InvalidParentChildException::missingParent($this->id);
        }
    }

    public function getId(): GeoUnitId { return $this->id; }
    public function getTenantId(): TenantId { return $this->tenantId; }
    public function getCountryCode(): CountryCode { return $this->countryCode; }
    public function getLevel(): GeographyLevel { return $this->level; }
    public function getParentId(): ?GeoUnitId { return $this->parentId; }
    public function getPath(): GeoPath { return $this->path; }
    public function getName(): LocalizedName { return $this->getName; }
    public function getCode(): ?GeographicCode { return $this->code; }
    public function isActive(): bool { return $this->isActive; }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function belongsToTenant(TenantId $tenantId): bool
    {
        return $this->tenantId->equals($tenantId);
    }
}
```

---

## Value Objects

### 1. GeoUnitId

Type-safe identifier for geographic units.

```php
namespace App\Contexts\Geography\Domain\ValueObjects;

use InvalidArgumentException;

class GeoUnitId
{
    public function __construct(private string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('GeoUnitId cannot be empty');
        }
    }

    public static function generate(): self
    {
        return new self(\Ramsey\Uuid\Uuid::uuid4()->toString());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(GeoUnitId $other): bool
    {
        return $this->value === $other->value;
    }
}
```

### 2. CountryCode

ISO 3166-1 alpha-2 country code.

```php
class CountryCode
{
    private const SUPPORTED_COUNTRIES = ['NP', 'IN', 'US'];

    public function __construct(private string $code)
    {
        $normalized = strtoupper($code);
        
        if (!in_array($normalized, self::SUPPORTED_COUNTRIES)) {
            throw CountryNotSupportedException::code($code);
        }
        
        $this->code = $normalized;
    }

    public function value(): string { return $this->code; }
    public function equals(CountryCode $other): bool { return $this->code === $other->code; }
}
```

### 3. GeographyLevel

Represents hierarchical depth (1-8).

```php
class GeographyLevel
{
    private const MAX_LEVEL = 8;

    public function __construct(private int $level)
    {
        if ($level < 1 || $level > self::MAX_LEVEL) {
            throw new InvalidArgumentException("Level must be 1-8");
        }
    }

    public function value(): int { return $this->level; }
    public function isRoot(): bool { return $this->level === 1; }
    public function next(): self { return new self($this->level + 1); }
    public function equals(GeographyLevel $other): bool { return $this->level === $other->level; }
}
```

### 4. GeoPath

PostgreSQL ltree materialized path (e.g., "1.12.123.1234").

```php
class GeoPath
{
    private const MAX_DEPTH = 8;
    private const SEGMENT_DELIMITER = '.';

    public function __construct(private string $path)
    {
        $this->validate();
    }

    public static function fromSegments(array $segments): self
    {
        return new self(implode(self::SEGMENT_DELIMITER, $segments));
    }

    private function validate(): void
    {
        $segments = explode(self::SEGMENT_DELIMITER, $this->path);

        if (count($segments) > self::MAX_DEPTH) {
            throw MaxHierarchyDepthException::exceeded(count($segments));
        }

        foreach ($segments as $segment) {
            if (!is_numeric($segment) || intval($segment) < 1) {
                throw InvalidHierarchyException::invalidSegment($segment);
            }
        }
    }

    public function append(int $segment): self
    {
        if ($segment < 1) {
            throw new InvalidArgumentException("Path segment must be >= 1");
        }

        $newPath = $this->path . self::SEGMENT_DELIMITER . $segment;
        return new self($newPath);
    }

    public function depth(): int
    {
        return substr_count($this->path, self::SEGMENT_DELIMITER) + 1;
    }

    public function toString(): string { return $this->path; }
}
```

### 5. LocalizedName

Multi-language geographic name support.

```php
class LocalizedName
{
    public function __construct(
        private string $name,
        private string $language = 'en',
        private ?string $nameAlternate = null
    ) {
        if (empty($name)) {
            throw new InvalidArgumentException('Name cannot be empty');
        }
    }

    public function getName(): string { return $this->name; }
    public function getAlternate(): ?string { return $this->nameAlternate; }
    public function getLanguage(): string { return $this->language; }

    public function toString(): string
    {
        return $this->nameAlternate 
            ? "{$this->name} ({$this->nameAlternate})"
            : $this->name;
    }
}
```

### 6. GeographyHierarchy

Encapsulates country-specific rules.

```php
class GeographyHierarchy
{
    private const HIERARCHIES = [
        'NP' => [
            'levels' => [
                1 => 'Country',
                2 => 'Province',
                3 => 'District',
                4 => 'Municipality',
                5 => 'Ward',
            ],
            'required_levels' => [1, 2, 3, 4],
        ],
        'IN' => [
            'levels' => [
                1 => 'Country',
                2 => 'State',
                3 => 'District',
                4 => 'Village',
            ],
            'required_levels' => [1, 2, 3, 4],
        ],
        'US' => [
            'levels' => [
                1 => 'Country',
                2 => 'State',
                3 => 'County',
                4 => 'City',
            ],
            'required_levels' => [1, 2],
        ],
    ];

    private array $config;

    public function __construct(CountryCode $countryCode)
    {
        $code = $countryCode->value();
        
        if (!isset(self::HIERARCHIES[$code])) {
            throw CountryNotSupportedException::code($code);
        }

        $this->config = self::HIERARCHIES[$code];
    }

    public function getLevelName(GeographyLevel $level): string
    {
        return $this->config['levels'][$level->value()] ?? "Level {$level->value()}";
    }

    public function getRequiredLevels(): array
    {
        return $this->config['required_levels'];
    }

    public function isValidLevel(int $levelNumber): bool
    {
        return isset($this->config['levels'][$levelNumber]);
    }

    public function isLevelRequired(GeographyLevel $level): bool
    {
        return in_array($level->value(), $this->config['required_levels']);
    }

    public function getMaxLevel(): int
    {
        return max(array_keys($this->config['levels']));
    }
}
```

---

## Eloquent Models (Infrastructure Layer)

### 1. GeoUnit (Eloquent Model)

Maps Domain `GeographicUnit` to PostgreSQL `geographic_units` table.

```php
namespace App\Models\Geography;

use Illuminate\Database\Eloquent\Model;

class GeoUnit extends Model
{
    protected $connection = 'pgsql_geo';
    protected $table = 'geographic_units';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'tenant_id',
        'country_code',
        'level',
        'parent_id',
        'path',
        'name',
        'name_alternate',
        'code',
        'is_active',
    ];

    protected $casts = [
        'level' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(GeoUnit::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(GeoUnit::class, 'parent_id', 'id');
    }

    // Query descendants using ltree operator <@
    public function descendants()
    {
        return static::whereRaw('path <@ ?', [$this->path])
            ->where('id', '!=', $this->id);
    }

    // Query ancestors using ltree operator @>
    public function ancestors()
    {
        return static::whereRaw('path @> ?', [$this->path])
            ->where('id', '!=', $this->id);
    }

    // Find by depth
    public static function atLevel($level)
    {
        return static::whereRaw('nlevel(path) = ?', [$level]);
    }
}
```

---

## Model Relationships

```
GeoUnit (Root: NP)
├── GeoUnit (Level 2: NP.1 - Province 1)
│   ├── GeoUnit (Level 3: NP.1.1 - District 1)
│   │   └── GeoUnit (Level 4: NP.1.1.1 - Municipality 1)
│   └── GeoUnit (Level 3: NP.1.2 - District 2)
└── GeoUnit (Level 2: NP.2 - Province 2)
    └── GeoUnit (Level 3: NP.2.1 - District 1)
```

---

## Next Steps

→ See `03-MIGRATIONS.md` for creating the PostgreSQL schema with ltree support

---
