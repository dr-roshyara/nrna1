<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create committees table for political party organization structure.
     *
     * ARCHITECTURE DECISION: Committees within Membership Context
     * - Committee is an Entity (not separate Aggregate Root)
     * - Committees belong to Membership-Organization unified context
     * - NO foreign keys to Geography Context (loose coupling)
     * - Natural key references and JSONB cache for geography
     *
     * Committee Types in Political Parties:
     * - ward: Ward-level committee (operational_geo_reference at level 4)
     * - district: District-level committee (level 2)
     * - province: Provincial committee (level 1)
     * - central: Central/National committee (NO geography)
     * - youth: Youth wing (cross-geography)
     * - women: Women's wing (cross-geography)
     * - student: Student wing (cross-geography)
     *
     * Business Rules Enforced:
     * - Central committee MUST NOT have operational geography
     * - Non-central committees (ward/district/province) MUST have geography
     * - Committee hierarchy via parent_committee_id (self-referencing)
     *
     * Connection Strategy: Single database with GlobalScope-based tenant isolation.
     */
    public function up(): void
    {
        Schema::create('committees', function (Blueprint $table) {
            // Primary key (ULID)
            $table->string('id', 26)->primary();

            // Tenant association (organisation_id for BelongsToTenant trait)
            $table->string('organisation_id', 50)->nullable(false);

            // Committee identification
            $table->string('name', 255)->nullable(false);
            $table->string('code', 50)->nullable(false); // e.g., 'NC-KTM-WARD15'

            // Committee classification
            $table->string('type', 30)->nullable(false); // 'ward', 'district', 'province', 'central', 'youth', 'women'
            $table->integer('level')->nullable(false); // 1=central, 2=province, 3=district, 4=ward

            // Geography reference (LOOSE COUPLING - NO FOREIGN KEY)
            // Operational geography: Where committee operates
            // Format: 'np.1.12.123' for district, 'np.1.12.123.1234' for ward
            // Central committee: NULL (nationwide)
            $table->string('operational_geo_reference', 255)->nullable();

            // Cached operational geography details (JSONB)
            // Same format as members.residence_geo_cache
            // Populated via API call to Geography Context
            $table->jsonb('operational_geo_cache')->nullable();

            // Cache version for invalidation
            $table->integer('geo_cache_version')->default(1);

            // Last cache update timestamp
            $table->timestamp('geo_cache_updated_at')->nullable();

            // Committee hierarchy (self-referencing)
            // Province committee → parent: Central committee
            // District committee → parent: Province committee
            // Ward committee → parent: District committee
            $table->string('parent_committee_id', 26)->nullable();

            // Committee lifecycle
            $table->date('formation_date')->default(now());
            $table->date('term_end_date')->nullable();
            $table->string('status', 20)->default('active'); // 'active', 'dissolved', 'suspended'

            // Committee capacity
            $table->integer('max_members')->nullable(); // NULL = unlimited

            // Audit timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['organisation_id', 'type', 'status'], 'idx_committee_tenant_type_status');
            $table->index(['organisation_id', 'operational_geo_reference'], 'idx_committee_tenant_geo');
            $table->index('parent_committee_id', 'idx_committee_parent');

            // GIN index for JSONB geography cache
            // Allows queries like: WHERE operational_geo_cache->>'district' = 'Kathmandu'
            $table->index('operational_geo_cache')->algorithm('gin');

            // Unique constraints (tenant-scoped)
            $table->unique(['organisation_id', 'code'], 'unique_committee_code_per_tenant');
        });

        // Self-referencing FK added after table creation so PostgreSQL primary key is finalized first.
        Schema::table('committees', function (Blueprint $table) {
            $table->foreign('parent_committee_id')
                  ->references('id')
                  ->on('committees')
                  ->onDelete('set null');
        });

        // Add check constraints for business rules (PostgreSQL)
        if (config('database.default') === 'pgsql' || config('database.default') === 'tenant_test') {
            DB::statement("
                ALTER TABLE committees
                ADD CONSTRAINT chk_central_committee_no_geography
                CHECK (
                    (type = 'central' AND operational_geo_reference IS NULL)
                    OR
                    (type != 'central')
                )
            ");

            DB::statement("
                ALTER TABLE committees
                ADD CONSTRAINT chk_non_central_must_have_geography
                CHECK (
                    (type IN ('ward', 'district', 'province') AND operational_geo_reference IS NOT NULL)
                    OR
                    (type NOT IN ('ward', 'district', 'province'))
                )
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop check constraints first (if PostgreSQL)
        if (config('database.default') === 'pgsql') {
            DB::statement("ALTER TABLE committees DROP CONSTRAINT IF EXISTS chk_central_committee_no_geography");
            DB::statement("ALTER TABLE committees DROP CONSTRAINT IF EXISTS chk_non_central_must_have_geography");
        }

        Schema::dropIfExists('committees');
    }
};
