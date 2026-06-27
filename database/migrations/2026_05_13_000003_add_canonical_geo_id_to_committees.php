<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add canonical_geo_id to committees table.
     *
     * This is a read-optimized projection field (CQRS-style) that stores
     * the complete geographic hierarchy as a deterministic string.
     *
     * Format: "region:{region}.country:{country}.geo:{geoUnitId}"
     * Example: "region:asia.country:IN.geo:7"
     *
     * RULES:
     * - MUST be nullable (backward compatibility with existing rows)
     * - MUST NOT replace existing columns (geo_unit_id, region_code, country_code)
     * - MUST NOT be required in inserts
     * - NEVER treat as authoritative — geo_unit_id is the source of truth
     */
    public function up(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->string('canonical_geo_id', 750)
                ->nullable()
                ->after('geo_unit_id');

            $table->index('canonical_geo_id', 'idx_committees_canonical_geo');
        });
    }

    public function down(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->dropIndex('idx_committees_canonical_geo');
            $table->dropColumn('canonical_geo_id');
        });
    }
};
