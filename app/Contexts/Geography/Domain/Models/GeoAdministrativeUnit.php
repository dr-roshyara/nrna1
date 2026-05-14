<?php

namespace App\Contexts\Geography\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * GeoAdministrativeUnit Model
 *
 * Polymorphic model for ALL countries' administrative divisions.
 * - Nepal: provinces, districts, local levels, wards
 * - India: states, districts, etc. (FUTURE)
 * - USA: states, counties, etc. (FUTURE)
 *
 * @property int $id
 * @property string $country_code ISO country code (NP, IN, US)
 * @property int $admin_level Level in hierarchy (1, 2, 3, 4)
 * @property string $admin_type Type of unit (province, district, ward, etc.)
 * @property int|null $parent_id Parent unit ID
 * @property string|null $path Materialized path (/1/23/456/)
 * @property string $code Unique code (NP-P1, NP-DIST-01)
 * @property string|null $local_code Country-specific code
 * @property array $name_local Multilingual names (JSON)
 * @property array|null $metadata Country-specific data (JSON)
 * @property bool $is_active Active status
 */
class GeoAdministrativeUnit extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'geo_administrative_units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organisation_id',
        'country_code',
        'admin_level',
        'admin_type',
        'parent_id',
        'path',
        'code',
        'local_code',
        'name_local',
        'metadata',
        'centroid',
        'boundary',
        'is_active',
        'valid_from',
        'valid_to',
        'version',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name_local' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'version' => 'integer',
    ];

    /**
     * Get the country this unit belongs to.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }

    /**
     * Get the parent administrative unit.
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get all child administrative units.
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get active children only.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function activeChildren()
    {
        return $this->children()->where('is_active', true)->get();
    }

    /**
     * Get all ancestors (using materialized path).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function ancestors()
    {
        if (!$this->path) {
            return collect([]);
        }

        $ancestorIds = array_filter(explode('/', trim($this->path, '/')));

        return self::whereIn('id', $ancestorIds)
                   ->orderBy('admin_level')
                   ->get();
    }

    /**
     * Get all descendants (using materialized path).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function descendants()
    {
        return self::where('path', 'like', $this->path . $this->id . '/%')
                   ->orderBy('path')
                   ->get();
    }

    /**
     * Get the name in a specific language.
     *
     * @param string $language Language code (en, np, hi, etc.)
     * @return string
     */
    public function getName(string $language = 'en'): string
    {
        return $this->name_local[$language] ?? $this->name_local['en'] ?? 'Unknown';
    }

    /**
     * Get metadata value.
     *
     * @param string $key Metadata key
     * @param mixed $default Default value
     * @return mixed
     */
    public function getMeta(string $key, $default = null)
    {
        return $this->metadata[$key] ?? $default;
    }

    /**
     * Scope a query to only include units from a specific country.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $countryCode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCountry($query, string $countryCode)
    {
        return $query->where('country_code', $countryCode);
    }

    /**
     * Scope a query to only include units at a specific admin level.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $level
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLevel($query, int $level)
    {
        return $query->where('admin_level', $level);
    }

    /**
     * Scope a query to only include active units.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include units of a specific type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('admin_type', $type);
    }

    /**
     * Get full hierarchy path as string.
     *
     * @param string $language
     * @param string $separator
     * @return string
     */
    public function getFullPath(string $language = 'en', string $separator = ' > '): string
    {
        $ancestors = $this->ancestors();
        $names = $ancestors->map(fn($a) => $a->getName($language))->toArray();
        $names[] = $this->getName($language);

        return implode($separator, $names);
    }
}
