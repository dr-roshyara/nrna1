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
     * Extends tenant geography hierarchy to support Levels 0-10:
     * - Extends admin_level constraint from 1-8 to 0-10
     * - Makes country_code nullable for continents (Level 0)
     * - Adds check constraint for country_code based on admin_level
     * - Updates column comment to reflect new range
     *
     * Tenant Geography Levels 0-10:
     * 0: Continent (country_code=NULL, parent_id=NULL)
     * 1: Country (country_code=ISO code, parent_id=continent_id)
     * 2: Province/State (official - mirrored)
     * 3: District/County (official - mirrored)
     * 4: Local Level/City (official - mirrored)
     * 5: Ward/ZIP Code (official - mirrored)
     * 6: Neighborhood/Tole (custom - party creates)
     * 7: Street/Block (custom - party creates)
     * 8: House Number (custom - party creates)
     * 9: Block/Unit (custom - party creates)
     * 10: Reserved for future (custom - party creates)
     *
     * Country Code Rules:
     * - Level 0 (continents): country_code IS NULL
     * - Level 1+ (countries & below): country_code IS NOT NULL
     *
     * @see GeographyMirrorService for sync of Levels 0-5 from landlord
     * @see TenantGeographyProfile for tenant-specific root/leaf configuration
     */
    public function up(): void
    {
        // 1. Make country_code nullable (for Level 0 continents)
        DB::statement("
            ALTER TABLE geo_administrative_units
            ALTER COLUMN country_code DROP NOT NULL
        ");

        // 2. Update admin_level constraint to 0-10 range
        DB::statement("
            ALTER TABLE geo_administrative_units
            DROP CONSTRAINT IF EXISTS geo_administrative_units_admin_level_check
        ");

        DB::statement("
            ALTER TABLE geo_administrative_units
            ADD CONSTRAINT geo_administrative_units_admin_level_check
            CHECK (admin_level BETWEEN 0 AND 10)
        ");

        // 3. Add country_code constraint based on admin_level
        DB::statement("
            ALTER TABLE geo_administrative_units
            DROP CONSTRAINT IF EXISTS geo_administrative_units_country_code_check
        ");

        DB::statement("
            ALTER TABLE geo_administrative_units
            ADD CONSTRAINT geo_administrative_units_country_code_check
            CHECK (
                (admin_level = 0 AND country_code IS NULL) OR
                (admin_level >= 1 AND country_code IS NOT NULL)
            )
        ");

        // 4. Update column comment to reflect new range
        DB::statement("
            COMMENT ON COLUMN geo_administrative_units.admin_level IS
            '0=continent (country_code=NULL), 1=country, 2=province/state, 3=district/county, 4=local level/city, 5=ward/zip code, 6=neighborhood/tole, 7=street/block, 8=house number, 9=block/unit, 10=reserved for future'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Remove country_code constraint
        DB::statement("
            ALTER TABLE geo_administrative_units
            DROP CONSTRAINT IF EXISTS geo_administrative_units_country_code_check
        ");

        // 2. Restore admin_level constraint to 1-8
        DB::statement("
            ALTER TABLE geo_administrative_units
            DROP CONSTRAINT IF EXISTS geo_administrative_units_admin_level_check
        ");

        DB::statement("
            ALTER TABLE geo_administrative_units
            ADD CONSTRAINT geo_administrative_units_admin_level_check
            CHECK (admin_level BETWEEN 1 AND 8)
        ");

        // 3. Make country_code NOT NULL again (assuming no continents exist)
        // Note: Will fail if any NULL values exist
        DB::statement("
            UPDATE geo_administrative_units
            SET country_code = COALESCE(country_code, '')
            WHERE country_code IS NULL
        ");

        DB::statement("
            ALTER TABLE geo_administrative_units
            ALTER COLUMN country_code SET NOT NULL
        ");

        // 4. Restore original column comment
        DB::statement("
            COMMENT ON COLUMN geo_administrative_units.admin_level IS
            '1=province/state, 2=district, 3=local level, 4=ward, 5-8=custom'
        ");
    }
};