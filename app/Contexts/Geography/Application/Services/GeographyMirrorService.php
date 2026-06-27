<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\Services;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * GeographyMirrorService - Initial Geography Provisioning
 *
 * PURPOSE:
 * Mirrors official geography from landlord database to tenant database
 * during tenant provisioning. This is a ONE-TIME operation per tenant.
 *
 * DISTINCTION FROM DailyGeographySync:
 * - GeographyMirrorService: Initial copy during tenant creation
 * - DailyGeographySync: Ongoing updates for existing tenants
 *
 * HYBRID ARCHITECTURE PATTERN:
 * 1. Landlord DB: Master geography for all countries
 * 2. Tenant DB: Filtered copy (tenant's country only) + custom units
 * 3. Foreign keys work (same database)
 * 4. Tenants can add custom levels 6-8
 *
 * ID MAPPING STRATEGY:
 * Landlord IDs ≠ Tenant IDs (auto-increment resets)
 * Must remap parent_id relationships during copy
 *
 * Example:
 * Landlord: Province(id=1) → District(id=12, parent_id=1)
 * Tenant:   Province(id=5) → District(id=25, parent_id=5) [REMAPPED]
 *
 * MULTI-COUNTRY SCALING:
 * - Nepal tenant gets NP geography only (~10,000 units, 15MB)
 * - US tenant gets US geography only (~50,000 units, 180MB)
 * - Storage: 4.9GB for 100 tenants (efficient)
 *
 * @see GeographyMirrorServiceTest for complete test coverage
 * @see DailyGeographySync for ongoing sync mechanism
 * @see app/Contexts/Membership/Application/Jobs/InstallMembershipModule.php
 */
class GeographyMirrorService
{
    /**
     * Supported countries with geography data
     */
    private const SUPPORTED_COUNTRIES = ['NP', 'IN', 'US'];

    /**
     * Official admin levels per country (levels 1-5, custom levels 6-10)
     * These are the official geography levels mirrored from landlord.
     * Custom levels (6-10) are created by tenants.
     * Level 0 (continents) is landlord-only global data.
     */
    private const OFFICIAL_MAX_LEVELS = [
        'NP' => 5, // Nepal: Country(1) → Province(2) → District(3) → Local Level(4) → Ward(5)
        'IN' => 4, // India: Country(1) → State(2) → District(3) → Village/Town(4)
        'US' => 4, // USA: Country(1) → State(2) → County(3) → ZIP Code(4)
    ];

    /**
     * Mirror official geography from landlord to tenant database
     *
     * @param string $tenantSlug Tenant identifier (used for logging)
     * @param string $countryCode ISO 3166-1 alpha-2 country code (NP, IN, US)
     * @param int $minLevel Minimum admin level to mirror (inclusive, default: 1)
     * @param int|null $maxLevel Maximum admin level to mirror (inclusive, default: country official max)
     * @return array Summary statistics ['units_mirrored' => int, 'country_code' => string, 'levels_copied' => array]
     * @throws InvalidArgumentException if country not supported or invalid level range
     */
    public function mirrorCountryToTenant(
        string $tenantSlug,
        string $countryCode = 'NP',
        int $minLevel = 1,
        ?int $maxLevel = null
    ): array
    {
        // Validate country support
        $countryCode = strtoupper($countryCode);
        if (!in_array($countryCode, self::SUPPORTED_COUNTRIES, true)) {
            throw new InvalidArgumentException(
                "Country '{$countryCode}' not supported. Available: " . implode(', ', self::SUPPORTED_COUNTRIES)
            );
        }

        // Validate level range
        $officialMaxLevel = self::OFFICIAL_MAX_LEVELS[$countryCode] ?? 5;
        if ($maxLevel === null) {
            $maxLevel = $officialMaxLevel;
        }

        if ($minLevel < 0 || $minLevel > 10) {
            throw new InvalidArgumentException("Minimum level must be between 0 and 10, got {$minLevel}");
        }
        if ($maxLevel < 0 || $maxLevel > 10) {
            throw new InvalidArgumentException("Maximum level must be between 0 and 10, got {$maxLevel}");
        }
        if ($minLevel > $maxLevel) {
            throw new InvalidArgumentException("Minimum level {$minLevel} cannot be greater than maximum level {$maxLevel}");
        }

        // Handle continents (Level 0) - special case with country_code=NULL
        $includeContinents = ($minLevel === 0);

        // Warn if trying to mirror custom levels (6-10)
        if ($maxLevel > $officialMaxLevel) {
            Log::warning("GeographyMirrorService: Requested max level {$maxLevel} exceeds official max level {$officialMaxLevel} for country {$countryCode}. " .
                "Levels " . ($officialMaxLevel + 1) . "-{$maxLevel} are custom levels created by tenants, not mirrored from landlord.");
        }

        $effectiveMaxLevel = min($maxLevel, $officialMaxLevel);

        Log::info("GeographyMirrorService: Starting mirror for tenant '{$tenantSlug}', country '{$countryCode}', levels {$minLevel}-{$maxLevel}" .
            ($includeContinents ? " (including continents)" : ""));

        // STEP 1: Fetch units from landlord
        // Complex query because continents (Level 0) have country_code=NULL
        // Country-specific units (Levels 1+) have country_code=$countryCode

        $continentUnits = collect();
        $countryUnits = collect();

        // Fetch continents if needed (Level 0, country_code=NULL)
        if ($includeContinents) {
            Log::info("GeographyMirrorService: Fetching continents (Level 0)");
            $continentUnits = DB::connection('landlord')
                ->table('geo_administrative_units')
                ->whereNull('country_code')  // Continents have no country
                ->where('admin_level', 0)
                ->where('is_active', true)
                ->orderBy('id')
                ->get();
        }

        // Fetch country-specific units (Levels 1+)
        $countryMinLevel = $includeContinents ? 1 : $minLevel;
        $countryMaxLevel = $effectiveMaxLevel;

        if ($countryMinLevel <= $countryMaxLevel) {
            Log::info("GeographyMirrorService: Fetching country units for levels {$countryMinLevel}-{$countryMaxLevel}");
            $countryUnits = DB::connection('landlord')
                ->table('geo_administrative_units')
                ->where('country_code', $countryCode)
                ->where('is_active', true)
                ->whereBetween('admin_level', [$countryMinLevel, $countryMaxLevel])
                ->orderBy('admin_level')
                ->orderBy('id') // Stable order within same level
                ->get();
        }

        // Combine units: continents first (Level 0), then country units (Levels 1+)
        $units = $continentUnits->merge($countryUnits);

        if ($units->isEmpty()) {
            Log::warning("GeographyMirrorService: No geography data found for country '{$countryCode}' in landlord database");
            return [
                'units_mirrored' => 0,
                'country_code' => $countryCode,
                'levels_copied' => [],
            ];
        }

        Log::info("GeographyMirrorService: Found {$units->count()} units in landlord DB for country '{$countryCode}'");

        // STEP 2: Mirror units to tenant database with ID mapping
        $idMapping = []; // landlord_id => tenant_id
        $levelStats = []; // admin_level => count
        $mirrored = 0;

        DB::connection('tenant')->transaction(function () use ($units, &$idMapping, &$levelStats, &$mirrored) {
            foreach ($units as $unit) {
                // Remap parent_id: Use tenant ID if parent was already mirrored
                $tenantParentId = null;
                if ($unit->parent_id !== null) {
                    if (!isset($idMapping[$unit->parent_id])) {
                        Log::error("GeographyMirrorService: Parent unit {$unit->parent_id} not found in mapping for unit {$unit->id}");
                        throw new \RuntimeException(
                            "Parent unit ID {$unit->parent_id} not mirrored yet for child unit ID {$unit->id}. " .
                            "Check landlord geography hierarchy integrity."
                        );
                    }
                    $tenantParentId = $idMapping[$unit->parent_id];
                }

                // Insert into tenant database
                $newId = DB::connection('tenant')
                    ->table('geo_administrative_units')
                    ->insertGetId([
                        // Core fields (same as landlord)
                        'country_code' => $unit->country_code,
                        'admin_level' => $unit->admin_level,
                        'admin_type' => $unit->admin_type,
                        'parent_id' => $tenantParentId, // REMAPPED from landlord parent_id
                        'path' => $unit->path, // Will be regenerated if using ltree
                        'code' => $unit->code,
                        'local_code' => $unit->local_code,
                        'name_local' => $unit->name_local,
                        'metadata' => $unit->metadata,

                        // Tenant-specific fields
                        'is_official' => true, // All mirrored units are official
                        'landlord_geo_id' => $unit->id, // Track landlord source

                        // Status
                        'is_active' => $unit->is_active,
                        'valid_from' => $unit->valid_from,
                        'valid_to' => $unit->valid_to,

                        // Timestamps
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                // Store mapping for child units
                $idMapping[$unit->id] = $newId;

                // Track statistics
                $level = $unit->admin_level;
                $levelStats[$level] = ($levelStats[$level] ?? 0) + 1;
                $mirrored++;
            }
        });

        Log::info("GeographyMirrorService: Successfully mirrored {$mirrored} units to tenant '{$tenantSlug}'", [
            'country_code' => $countryCode,
            'levels' => $levelStats,
        ]);

        return [
            'units_mirrored' => $mirrored,
            'country_code' => $countryCode,
            'levels_copied' => $levelStats,
        ];
    }

    /**
     * Check if tenant already has geography data
     *
     * Useful to prevent duplicate mirroring.
     *
     * @param string|null $countryCode Optional country filter
     * @return bool TRUE if tenant has geography data
     */
    public function tenantHasGeography(?string $countryCode = null): bool
    {
        try {
            $query = DB::connection('tenant')
                ->table('geo_administrative_units')
                ->where('is_official', true);

            if ($countryCode !== null) {
                $query->where('country_code', strtoupper($countryCode));
            }

            return $query->exists();
        } catch (QueryException $e) {
            // If table doesn't exist yet, return false (no geography data)
            // Common error codes: 42P01 (PostgreSQL relation doesn't exist)
            if (str_contains($e->getMessage(), 'existiert nicht') ||
                str_contains($e->getMessage(), 'does not exist') ||
                str_contains($e->getMessage(), 'relation')) {
                Log::debug('GeographyMirrorService: geo_administrative_units table does not exist yet', [
                    'error' => $e->getMessage(),
                    'country_code' => $countryCode,
                ]);
                return false;
            }

            // Re-throw unexpected database errors
            throw $e;
        }
    }

    /**
     * Get mirroring statistics for tenant
     *
     * Returns count of units by country and level.
     *
     * @return array ['country_code' => ['level' => count]]
     */
    public function getMirroringStats(): array
    {
        $units = DB::connection('tenant')
            ->table('geo_administrative_units')
            ->select('country_code', 'admin_level', DB::raw('COUNT(*) as count'))
            ->where('is_official', true)
            ->groupBy('country_code', 'admin_level')
            ->orderBy('country_code')
            ->orderBy('admin_level')
            ->get();

        $stats = [];
        foreach ($units as $unit) {
            $stats[$unit->country_code][$unit->admin_level] = $unit->count;
        }

        return $stats;
    }

    /**
     * Verify mirror integrity
     *
     * Checks that all parent_id references are valid within tenant DB.
     *
     * @return array ['valid' => bool, 'issues' => array]
     */
    public function verifyMirrorIntegrity(): array
    {
        // Find units with parent_id that don't reference existing units
        $orphanedUnits = DB::connection('tenant')
            ->table('geo_administrative_units as child')
            ->leftJoin('geo_administrative_units as parent', 'child.parent_id', '=', 'parent.id')
            ->whereNotNull('child.parent_id')
            ->whereNull('parent.id')
            ->select('child.id', 'child.code', 'child.parent_id')
            ->get();

        $issues = [];
        foreach ($orphanedUnits as $orphan) {
            $issues[] = "Unit {$orphan->code} (ID: {$orphan->id}) references non-existent parent_id: {$orphan->parent_id}";
        }

        return [
            'valid' => empty($issues),
            'issues' => $issues,
        ];
    }
}
