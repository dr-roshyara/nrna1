<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Create role_localizations table (Tenant-Specific Role Names & Rules).
     *
     * ARCHITECTURE DECISION: Tenant Customization Layer
     * - Maps universal role paths to tenant-specific names
     * - Multi-language support for international parties
     * - Tenant-specific rule overrides (term limits, eligibility, etc.)
     * - NO foreign key to role_hierarchy (loose coupling via path string)
     *
     * Real-World Examples:
     * - Nepal Congress: path '1.1.1' → 'President' / 'अध्यक्ष'
     * - German SPD: path '1.1.1' → 'Vorsitzender' / 'Vorsitzender'
     * - UK Labour: path '1.1.1' → 'Chair' / 'Chair'
     * - US Democrats: path '1.1.1' → 'Chairperson' / 'Chairperson'
     *
     * Benefits:
     * - Same universal role structure for all tenants
     * - Complete flexibility in naming and rules
     * - Multi-language support built-in
     * - Tenants can add custom roles by extending paths
     *
     * Connection Strategy: Single database with GlobalScope-based tenant isolation.
     */
    public function up(): void
    {
        Schema::create('role_localizations', function (Blueprint $table) {
            // Composite primary key (tenant + role_path)
            $table->string('tenant_id', 50)->nullable(false);
            $table->string('role_path', 255)->nullable(false); // References role_hierarchy.path

            // Localized names (multi-language support)
            $table->string('display_name', 100)->nullable(false); // Primary language name
            $table->string('display_name_local', 100)->nullable(); // Local language name

            // Tenant-specific description
            $table->text('tenant_description')->nullable();

            // Tenant-specific rule overrides (NULL = use role_hierarchy defaults)
            $table->integer('max_per_committee')->nullable(); // Override default limit
            $table->boolean('requires_election')->nullable(); // Override election requirement
            $table->integer('term_years')->nullable(); // Term limit in years

            // Eligibility rules (tenant-specific)
            $table->integer('min_age')->nullable(); // Minimum age requirement
            $table->integer('max_age')->nullable(); // Maximum age requirement
            $table->string('gender_requirement', 20)->nullable(); // 'male', 'female', 'any'

            // Display and UI
            $table->integer('display_order')->default(0); // For sorting in UI
            $table->string('display_color', 7)->nullable(); // Hex color for UI (#FF0000)

            // Role status
            $table->boolean('is_active')->default(true);
            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable();

            // Metadata for extensibility
            $table->json('metadata')->nullable();

            // Audit timestamps
            $table->timestamps();
            $table->softDeletes();

            // Composite primary key
            $table->primary(['tenant_id', 'role_path'], 'role_loc_tenant_path_pk');

            // Indexes for queries
            $table->index(['tenant_id', 'is_active'], 'idx_role_loc_tenant_active');
            $table->index('display_order', 'idx_role_loc_display_order');
            $table->index('role_path', 'idx_role_loc_path');
        });

        // Seed example role localizations (Nepal Congress example)
        // In production, this would be created during tenant setup wizard
        $this->seedExampleLocalizations();
    }

    /**
     * Seed example role localizations for demonstration.
     * In production, tenants create these via setup wizard or admin UI.
     */
    private function seedExampleLocalizations(): void
    {
        $now = now();

        // Example: Nepal Congress Party role localizations
        DB::table('role_localizations')->insert([
            // Central Committee Chairperson → President
            [
                'tenant_id' => 'example-tenant',
                'role_path' => '1.1.1',
                'display_name' => 'President',
                'display_name_local' => 'अध्यक्ष',
                'tenant_description' => 'President of the Central Committee',
                'max_per_committee' => 1,
                'requires_election' => true,
                'term_years' => 4,
                'min_age' => 25,
                'max_age' => null,
                'gender_requirement' => 'any',
                'display_order' => 1,
                'display_color' => '#FF0000',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Province Committee Chairperson → Provincial President
            [
                'tenant_id' => 'example-tenant',
                'role_path' => '1.1.2',
                'display_name' => 'Provincial President',
                'display_name_local' => 'प्रदेश अध्यक्ष',
                'tenant_description' => 'President of the Province Committee',
                'max_per_committee' => 1,
                'requires_election' => true,
                'term_years' => 4,
                'min_age' => 25,
                'max_age' => null,
                'gender_requirement' => 'any',
                'display_order' => 2,
                'display_color' => '#0000FF',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // District Committee Chairperson → District President
            [
                'tenant_id' => 'example-tenant',
                'role_path' => '1.1.3',
                'display_name' => 'District President',
                'display_name_local' => 'जिल्ला अध्यक्ष',
                'tenant_description' => 'President of the District Committee',
                'max_per_committee' => 1,
                'requires_election' => true,
                'term_years' => 4,
                'min_age' => 25,
                'max_age' => null,
                'gender_requirement' => 'any',
                'display_order' => 3,
                'display_color' => '#00FF00',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Ward Committee Chairperson → Ward President
            [
                'tenant_id' => 'example-tenant',
                'role_path' => '1.1.4',
                'display_name' => 'Ward President',
                'display_name_local' => 'वार्ड अध्यक्ष',
                'tenant_description' => 'President of the Ward Committee',
                'max_per_committee' => 1,
                'requires_election' => true,
                'term_years' => 2, // Shorter term for ward level
                'min_age' => 21, // Lower age requirement
                'max_age' => null,
                'gender_requirement' => 'any',
                'display_order' => 4,
                'display_color' => '#FFA500',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Vice Chairperson → Vice President
            [
                'tenant_id' => 'example-tenant',
                'role_path' => '1.2',
                'display_name' => 'Vice President',
                'display_name_local' => 'उपाध्यक्ष',
                'tenant_description' => 'Deputy to the President',
                'max_per_committee' => 2, // Can have 2 vice presidents
                'requires_election' => true,
                'term_years' => 4,
                'min_age' => 25,
                'max_age' => null,
                'gender_requirement' => 'any',
                'display_order' => 5,
                'display_color' => '#800080',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Secretary → General Secretary
            [
                'tenant_id' => 'example-tenant',
                'role_path' => '2.1',
                'display_name' => 'General Secretary',
                'display_name_local' => 'महासचिव',
                'tenant_description' => 'Administrative and organizational head',
                'max_per_committee' => 1,
                'requires_election' => true,
                'term_years' => 4,
                'min_age' => 25,
                'max_age' => null,
                'gender_requirement' => 'any',
                'display_order' => 6,
                'display_color' => '#008080',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Treasurer
            [
                'tenant_id' => 'example-tenant',
                'role_path' => '2.2',
                'display_name' => 'Treasurer',
                'display_name_local' => 'कोषाध्यक्ष',
                'tenant_description' => 'Financial management and accountability',
                'max_per_committee' => 1,
                'requires_election' => false, // Appointed role
                'term_years' => null, // No term limit
                'min_age' => 25,
                'max_age' => null,
                'gender_requirement' => 'any',
                'display_order' => 7,
                'display_color' => '#FFD700',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // General Member
            [
                'tenant_id' => 'example-tenant',
                'role_path' => '4.1',
                'display_name' => 'Committee Member',
                'display_name_local' => 'समिति सदस्य',
                'tenant_description' => 'General member of the committee',
                'max_per_committee' => null, // Unlimited
                'requires_election' => false,
                'term_years' => null,
                'min_age' => 18,
                'max_age' => null,
                'gender_requirement' => 'any',
                'display_order' => 100,
                'display_color' => '#808080',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_localizations');
    }
};
