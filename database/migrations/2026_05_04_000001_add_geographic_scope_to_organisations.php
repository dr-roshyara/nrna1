<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisations', function (Blueprint $table) {
            $table->string('committee_structure', 20)->default('flat')
                ->after('settings')
                ->comment('flat, geographical');

            $table->string('geographic_scope', 20)->nullable()
                ->after('committee_structure')
                ->comment('worldwide, multi_country, single_country, sub_country — null if flat');

            $table->json('allowed_countries')->nullable()
                ->after('geographic_scope')
                ->comment('For multi_country: ["DE","AT","CH"]');

            $table->char('base_country_code', 2)->nullable()
                ->after('allowed_countries')
                ->comment('For single_country or sub_country');

            $table->unsignedBigInteger('base_region_id')->nullable()
                ->after('base_country_code')
                ->comment('For sub_country: FK to geo_administrative_units.id');

            // Indexes for filtered queries
            $table->index('committee_structure');
            $table->index('geographic_scope');
            $table->index('base_country_code');
        });

        // DB-level consistency constraints (PostgreSQL / MySQL 8+)
        // Ensures invalid states cannot be stored even via direct DB access
        DB::statement("
            ALTER TABLE organisations ADD CONSTRAINT chk_geographic_consistency CHECK (
                committee_structure != 'geographical' OR geographic_scope IS NOT NULL
            )
        ");
        DB::statement("
            ALTER TABLE organisations ADD CONSTRAINT chk_single_country_has_code CHECK (
                geographic_scope != 'single_country' OR base_country_code IS NOT NULL
            )
        ");
        DB::statement("
            ALTER TABLE organisations ADD CONSTRAINT chk_flat_no_scope CHECK (
                committee_structure != 'flat' OR geographic_scope IS NULL
            )
        ");
    }

    public function down(): void
    {
        // Drop constraints first (PostgreSQL syntax; MySQL uses DROP CHECK)
        try {
            DB::statement('ALTER TABLE organisations DROP CONSTRAINT IF EXISTS chk_geographic_consistency');
            DB::statement('ALTER TABLE organisations DROP CONSTRAINT IF EXISTS chk_single_country_has_code');
            DB::statement('ALTER TABLE organisations DROP CONSTRAINT IF EXISTS chk_flat_no_scope');
        } catch (\Exception $e) {
            // SQLite (testing) has no ALTER TABLE DROP CONSTRAINT — silently skip
        }

        Schema::table('organisations', function (Blueprint $table) {
            $table->dropIndex(['committee_structure']);
            $table->dropIndex(['geographic_scope']);
            $table->dropIndex(['base_country_code']);
            $table->dropColumn([
                'base_region_id',
                'base_country_code',
                'allowed_countries',
                'geographic_scope',
                'committee_structure',
            ]);
        });
    }
};
