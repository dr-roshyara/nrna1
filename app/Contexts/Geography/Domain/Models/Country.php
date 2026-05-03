<?php

namespace App\Contexts\Geography\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Country Model
 *
 * Represents a country with its administrative hierarchy configuration.
 * Uses the landlord database connection.
 *
 * @property string $code ISO 3166-1 alpha-2 code (NP, IN, US)
 * @property string $code_alpha3 ISO 3166-1 alpha-3 code (NPL, IND, USA)
 * @property string $code_numeric ISO 3166-1 numeric code (524, 356, 840)
 * @property string $name_en English name
 * @property array $name_local Multilingual names (JSON)
 * @property string|null $phone_code Phone country code (+977, +91)
 * @property string|null $currency_code ISO 4217 currency code (NPR, INR)
 * @property array $admin_levels Administrative hierarchy configuration (JSON)
 * @property array|null $id_validation_rules ID validation rules (JSON)
 * @property array|null $phone_validation_rules Phone validation rules (JSON)
 * @property bool $is_active Whether country is active
 * @property bool $is_supported Whether platform supports this country
 */
class Country extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'landlord';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'code_alpha3',
        'code_numeric',
        'name_en',
        'name_local',
        'phone_code',
        'currency_code',
        'capital_en',
        'admin_levels',
        'id_validation_rules',
        'phone_validation_rules',
        'is_active',
        'is_supported',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name_local' => 'array',
        'admin_levels' => 'array',
        'id_validation_rules' => 'array',
        'phone_validation_rules' => 'array',
        'is_active' => 'boolean',
        'is_supported' => 'boolean',
    ];

    /**
     * Get all administrative units for this country.
     *
     * @return HasMany
     */
    public function administrativeUnits(): HasMany
    {
        return $this->hasMany(GeoAdministrativeUnit::class, 'country_code', 'code');
    }

    /**
     * Get administrative units at a specific level.
     *
     * @param int $level Admin level (1, 2, 3, 4)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnitsAtLevel(int $level)
    {
        return $this->administrativeUnits()
                    ->where('admin_level', $level)
                    ->where('is_active', true)
                    ->orderBy('name_local->en')
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
        if ($language === 'en') {
            return $this->name_en;
        }

        return $this->name_local[$language] ?? $this->name_en;
    }

    /**
     * Get the admin level configuration.
     *
     * @param int $level Admin level (1, 2, 3, 4)
     * @return array|null
     */
    public function getAdminLevelConfig(int $level): ?array
    {
        return $this->admin_levels[$level] ?? null;
    }

    /**
     * Get the name of an admin level in a specific language.
     *
     * @param int $level Admin level (1, 2, 3, 4)
     * @param string $language Language code (en, local)
     * @return string|null
     */
    public function getAdminLevelName(int $level, string $language = 'en'): ?string
    {
        $config = $this->getAdminLevelConfig($level);

        if (!$config) {
            return null;
        }

        if ($language === 'en') {
            return $config['name'] ?? null;
        }

        return $config['local_name'] ?? $config['name'] ?? null;
    }

    /**
     * Get the total count of units at a specific admin level.
     *
     * @param int $level Admin level (1, 2, 3, 4)
     * @return int|null
     */
    public function getExpectedCountAtLevel(int $level): ?int
    {
        $config = $this->getAdminLevelConfig($level);
        return $config['count'] ?? null;
    }

    /**
     * Scope a query to only include active countries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include supported countries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSupported($query)
    {
        return $query->where('is_supported', true);
    }
}
