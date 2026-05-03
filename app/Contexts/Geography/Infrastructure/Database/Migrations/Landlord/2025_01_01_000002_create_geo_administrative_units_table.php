<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the POLYMORPHIC geo_administrative_units table.
     * This single table handles ALL countries' administrative divisions.
     *
     * Design:
     * - Nepal: 7 provinces, 77 districts, 753 local levels, ~6,743 wards
     * - India: 28 states, 766 districts, etc. (FUTURE)
     * - USA: 50 states, 3,143 counties, etc. (FUTURE)
     */
    public function up(): void
    {
        Schema::connection('landlord')->create('geo_administrative_units', function (Blueprint $table) {
            $table->id();

            // Country & Hierarchy (CRITICAL - determines structure)
            $table->char('country_code', 2)->index()->comment('References countries.code (NP, IN, US)');
            $table->tinyInteger('admin_level')->unsigned()->index()->comment('1=province/state, 2=district, 3=local level, 4=ward');
            $table->string('admin_type', 50)->index()->comment('province, state, district, municipality, ward');

            // Hierarchical Structure
            $table->foreignId('parent_id')->nullable()->index()->comment('Parent unit in hierarchy');
            $table->string('path', 768)->nullable()->index()->comment('Materialized path: /1/23/456/ for fast queries');

            // Identification Codes
            $table->string('code', 50)->index()->comment('Unique code: NP-P1, NP-DIST-01, IN-UP');
            $table->string('local_code', 50)->nullable()->comment('Country-specific code (e.g., CBS code for Nepal)');

            // Multilingual Names (JSON for flexibility)
            $table->json('name_local')->comment('
                Multilingual names for this unit.
                Example: {"en": "Koshi Province", "np": "कोशी प्रदेश"}
            ');

            // Country-Specific Metadata (Flexible JSON)
            $table->json('metadata')->nullable()->comment('
                Country-specific attributes.
                Nepal example: {"total_wards": 14, "cbs_code": "P1"}
                India example: {"parliament_seats": 80, "iso_code": "IN-UP"}
            ');

            // Spatial Data (Optional - for GIS features)
            // $table->point('centroid')->nullable()->comment('Geographic center point');
            // $table->polygon('boundary')->nullable()->comment('Administrative boundary polygon');

            // Status & Validity
            $table->boolean('is_active')->default(true)->index();
            $table->date('valid_from')->nullable()->comment('For temporal support (boundary changes)');
            $table->date('valid_to')->nullable()->comment('For temporal support');

            // Timestamps
            $table->timestamps();

            // Indexes for Performance
            $table->index(['country_code', 'admin_level'], 'idx_country_level');
            $table->index(['country_code', 'parent_id'], 'idx_country_parent');
            $table->index(['country_code', 'code'], 'idx_country_code');
            $table->unique(['country_code', 'code'], 'uk_country_code');

            // Foreign Keys
            $table->foreign('country_code')
                  ->references('code')
                  ->on('countries')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('geo_administrative_units')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // Spatial Indexes (if MySQL supports it)
            // Note: Uncomment if using MySQL 8.0+ with spatial extensions
            // DB::statement('ALTER TABLE geo_administrative_units ADD SPATIAL INDEX idx_centroid (centroid)');
            // DB::statement('ALTER TABLE geo_administrative_units ADD SPATIAL INDEX idx_boundary (boundary)');
        });

        // NOTE: Table partitioning removed - not compatible with PostgreSQL
        // PostgreSQL uses declarative partitioning with different syntax
        // For future optimization, implement PostgreSQL native partitioning:
        // CREATE TABLE geo_administrative_units_np PARTITION OF geo_administrative_units
        // FOR VALUES IN ('NP');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->dropIfExists('geo_administrative_units');
    }
};
