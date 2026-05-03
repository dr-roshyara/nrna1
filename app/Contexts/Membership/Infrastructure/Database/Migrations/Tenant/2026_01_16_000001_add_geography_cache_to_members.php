<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add geography cache columns to members table (LOOSE COUPLING).
     *
     * ARCHITECTURE DECISION: No Foreign Keys to Geography Context
     * - Members reference geography by NATURAL KEY (string: 'np.1.12.123.1234')
     * - NO foreign key constraints to Geography Context
     * - Cache denormalized geography data (JSONB) for performance
     * - Eventual consistency via API calls and domain events
     *
     * This enables:
     * - Independent deployments of Geography and Membership contexts
     * - Geography service can be down without breaking member operations
     * - Fast queries without cross-context database joins
     * - Loose coupling between bounded contexts
     *
     * Connection Strategy: Single database with GlobalScope-based tenant isolation.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $existingIndexNames = collect(Schema::getIndexes('members'))->pluck('name');

            // Cached residential geography details (JSONB)
            // Populated via API call to Geography Context, NOT database join
            // Format: {
            //   "country": "Nepal",
            //   "country_code": "NP",
            //   "province_id": 1,
            //   "province": "Province 1",
            //   "province_nepali": "प्रदेश १",
            //   "district_id": 12,
            //   "district": "Kathmandu",
            //   "district_nepali": "काठमाडौं",
            //   "local_id": 123,
            //   "local": "Kathmandu Metropolis",
            //   "local_nepali": "काठमाडौं महानगरपालिका",
            //   "ward_id": 1234,
            //   "ward": "Ward 15",
            //   "ward_nepali": "वार्ड १५",
            //   "full_hierarchy": "Ward 15, Kathmandu Metropolis, Kathmandu, Province 1, Nepal",
            //   "path": "1.12.123.1234"
            // }
            // Benefits:
            // - Display member location without Geography Context queries
            // - Fast filtering by district/province using JSONB operators
            // - System works even if Geography Context is unavailable
            if (!Schema::hasColumn('members', 'residence_geo_cache')) {
                $table->jsonb('residence_geo_cache')->nullable()->after('residence_geo_reference');
            }

            if (!Schema::hasColumn('members', 'geo_cache_version')) {
                $table->integer('geo_cache_version')->default(1)->after('residence_geo_cache');
            }

            if (!Schema::hasColumn('members', 'geo_cache_updated_at')) {
                $table->timestamp('geo_cache_updated_at')->nullable()->after('geo_cache_version');
            }

            if (!$existingIndexNames->contains('members_residence_geo_cache_index')) {
                $table->index('residence_geo_cache')->algorithm('gin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex(['residence_geo_cache']);
            $table->dropColumn([
                'residence_geo_cache',
                'geo_cache_version',
                'geo_cache_updated_at',
            ]);
        });
    }
};
