<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates TENANT-SPECIFIC geo_administrative_units table.
     *
     * HYBRID ARCHITECTURE:
     * - Mirrored official geography (levels 1-5) from landlord database
     * - Custom party-specific units (levels 6-8) unique to this tenant
     *
     * Design Philosophy:
     * - Same schema as landlord for compatibility
     * - Additional fields: is_official, landlord_geo_id for tracking
     * - Enables foreign keys from members table (same database)
     * - Supports custom geography for political party organization
     *
     * Levels (Nepal):
     * 1 = Province (official - mirrored)
     * 2 = District (official - mirrored)
     * 3 = Local Level (official - mirrored)
     * 4 = Ward (official - mirrored)
     * 5 = Neighborhood (official - mirrored)
     * 6 = Committee (custom - party creates)
     * 7 = Street Organization (custom - party creates)
     * 8 = Household (custom - party creates)
     *
     * @see TenantGeographyTableTest for complete schema verification
     * @see GeographyMirrorService for mirroring logic
     */
    public function up(): void
    {
        Schema::create('geo_administrative_units', function (Blueprint $table) {
            $table->id();

            // Country & Hierarchy (CRITICAL - determines structure)
            $table->char('country_code', 2)->index()->comment('Country code (NP, IN, US) - filtered during mirroring');
            $table->tinyInteger('admin_level')->unsigned()->index()->comment('1=province/state, 2=district, 3=local level, 4=ward, 5-8=custom');
            $table->string('admin_type', 50)->index()->comment('province, state, district, municipality, ward, committee, etc.');

            // Hierarchical Structure
            $table->foreignId('parent_id')->nullable()->index()->comment('Parent unit in hierarchy (self-referential)');
            $table->string('path', 768)->nullable()->index()->comment('Materialized path: /1/23/456/ for fast ancestry queries');

            // Identification Codes
            $table->string('code', 50)->index()->comment('Unique code: NP-P1, NP-DIST-01, custom codes for levels 6-8');
            $table->string('local_code', 50)->nullable()->comment('Country-specific code (e.g., CBS code for Nepal official units)');

            // Multilingual Names (JSON for flexibility)
            $table->json('name_local')->comment('
                Multilingual names for this unit.
                Example: {"en": "Koshi Province", "np": "कोशी प्रदेश"}
                Party-custom units use party\'s primary language
            ');

            // Country-Specific Metadata (Flexible JSON)
            $table->json('metadata')->nullable()->comment('
                Country-specific or party-specific attributes.
                Official example: {"total_wards": 14, "cbs_code": "P1"}
                Custom example: {"committee_type": "ward_committee", "meeting_location": "Community Hall"}
            ');

            // TENANT-SPECIFIC FIELDS (distinguishes from landlord)
            $table->boolean('is_official')->default(true)->index()->comment('TRUE = mirrored from landlord (levels 1-5), FALSE = party-created custom (levels 6-8)');
            $table->unsignedBigInteger('landlord_geo_id')->nullable()->unique()->comment('References landlord.geo_administrative_units.id for official units. NULL for custom party units.');

            // Status & Validity
            $table->boolean('is_active')->default(true)->index()->comment('Active units shown in dropdowns. Inactive preserved for historical data.');
            $table->date('valid_from')->nullable()->comment('For temporal support (boundary changes over time)');
            $table->date('valid_to')->nullable()->comment('For temporal support');

            // Timestamps
            $table->timestamps();

            // Foreign Keys (Self-Referential Only - No cross-database FKs)
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('geo_administrative_units')
                  ->onDelete('cascade')
                  ->onUpdate('cascade')
                  ->name('fk_geo_admin_units_parent');

            // Indexes for Performance
            $table->index(['country_code', 'admin_level'], 'idx_country_level');
            $table->index(['country_code', 'parent_id'], 'idx_country_parent');
            $table->index(['country_code', 'code'], 'idx_country_code');
            $table->index(['parent_id', 'is_active'], 'idx_parent_active');
            $table->index(['is_official', 'is_active'], 'idx_official_active');

            // Unique constraint for official codes (tenant-scoped)
            $table->unique(['country_code', 'code'], 'uk_country_code');

            // Index on landlord_geo_id for sync lookups
            // Unique constraint allows NULL (multiple NULLs for custom units)
            // This index supports GeographyMirrorService and DailyGeographySync
            $table->index('landlord_geo_id', 'idx_landlord_geo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * NOTE: Dropping this table will fail if members table exists with FKs.
     * Members table foreign keys should be dropped first.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_administrative_units');
    }
};
