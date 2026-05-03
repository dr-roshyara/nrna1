<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Extends geography hierarchy to support Levels 0-10:
     * - Makes country_code nullable for Level 0 (continents)
     * - Extends admin_level constraint from 1-8 to 0-10
     * - Adds continent data (Level 0) for diaspora tenants
     */
    public function up(): void
    {
        // 1. Make country_code nullable (for Level 0 continents)
        Schema::connection('landlord')->table('geo_administrative_units', function (Blueprint $table) {
            $table->char('country_code', 2)->nullable()->change();
        });

        // 2. Update admin_level constraint to 0-10 range
        DB::connection('landlord')->statement("
            ALTER TABLE geo_administrative_units
            DROP CONSTRAINT IF EXISTS geo_administrative_units_admin_level_check
        ");

        DB::connection('landlord')->statement("
            ALTER TABLE geo_administrative_units
            ADD CONSTRAINT geo_administrative_units_admin_level_check
            CHECK (admin_level BETWEEN 0 AND 10)
        ");

        // 3. Update column comment to reflect new range
        DB::connection('landlord')->statement("
            COMMENT ON COLUMN geo_administrative_units.admin_level IS
            '0=continent, 1=country, 2=province/state, 3=district/county, 4=local level/city, 5=ward/zip code, 6=neighborhood/tole, 7=street/block, 8=house number, 9=block/unit, 10=reserved for future'
        ");

        // 4. Add continent data (Level 0)
        $this->seedContinents();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Delete continent data first
        DB::connection('landlord')->table('geo_administrative_units')
            ->where('admin_level', 0)
            ->delete();

        // 2. Restore admin_level constraint to 1-8
        DB::connection('landlord')->statement("
            ALTER TABLE geo_administrative_units
            DROP CONSTRAINT IF EXISTS geo_administrative_units_admin_level_check
        ");

        DB::connection('landlord')->statement("
            ALTER TABLE geo_administrative_units
            ADD CONSTRAINT geo_administrative_units_admin_level_check
            CHECK (admin_level BETWEEN 1 AND 8)
        ");

        // 3. Restore original column comment
        DB::connection('landlord')->statement("
            COMMENT ON COLUMN geo_administrative_units.admin_level IS
            '1=province/state, 2=district, 3=local level, 4=ward'
        ");

        // 4. Make country_code NOT NULL again
        // Note: This will fail if any NULL values exist
        // We deleted continents, so should be safe
        DB::connection('landlord')->statement("
            UPDATE geo_administrative_units
            SET country_code = COALESCE(country_code, '')
            WHERE country_code IS NULL
        ");

        Schema::connection('landlord')->table('geo_administrative_units', function (Blueprint $table) {
            $table->char('country_code', 2)->nullable(false)->change();
        });
    }

    /**
     * Seed continent data (Level 0)
     */
    private function seedContinents(): void
    {
        $continents = [
            [
                'code' => 'AS',
                'name_en' => 'Asia',
                'name_local' => json_encode(['en' => 'Asia']),
                'admin_type' => 'continent',
            ],
            [
                'code' => 'EU',
                'name_en' => 'Europe',
                'name_local' => json_encode(['en' => 'Europe']),
                'admin_type' => 'continent',
            ],
            [
                'code' => 'AF',
                'name_en' => 'Africa',
                'name_local' => json_encode(['en' => 'Africa']),
                'admin_type' => 'continent',
            ],
            [
                'code' => 'NA',
                'name_en' => 'North America',
                'name_local' => json_encode(['en' => 'North America']),
                'admin_type' => 'continent',
            ],
            [
                'code' => 'SA',
                'name_en' => 'South America',
                'name_local' => json_encode(['en' => 'South America']),
                'admin_type' => 'continent',
            ],
            [
                'code' => 'OC',
                'name_en' => 'Oceania',
                'name_local' => json_encode(['en' => 'Oceania']),
                'admin_type' => 'continent',
            ],
            [
                'code' => 'AN',
                'name_en' => 'Antarctica',
                'name_local' => json_encode(['en' => 'Antarctica']),
                'admin_type' => 'continent',
            ],
        ];

        foreach ($continents as $continent) {
            // Check if continent already exists
            $exists = DB::connection('landlord')->table('geo_administrative_units')
                ->where('code', $continent['code'])
                ->where('admin_level', 0)
                ->exists();

            if (!$exists) {
                DB::connection('landlord')->table('geo_administrative_units')->insert([
                    'country_code' => null, // Continents have no country
                    'admin_level' => 0,
                    'admin_type' => $continent['admin_type'],
                    'parent_id' => null, // Root level
                    'path' => null, // Will be set by materialized path logic
                    'code' => $continent['code'],
                    'local_code' => null,
                    'name_local' => $continent['name_local'],
                    'metadata' => json_encode(['type' => 'continent']),
                    'is_active' => true,
                    'valid_from' => null,
                    'valid_to' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
};