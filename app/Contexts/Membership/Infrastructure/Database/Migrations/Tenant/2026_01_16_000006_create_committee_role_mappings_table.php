<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Create committee_role_mappings table (Committee-Role Pattern Matching).
     *
     * ARCHITECTURE DECISION: Pattern-Based Role Validation
     * - Defines which role paths each committee type supports
     * - Uses PostgreSQL pattern matching like geography queries
     * - Tenant-specific committee type configurations
     * - Committee-level role limit overrides
     *
     * Pattern Matching Examples:
     * - '1.1.*' = All chairperson roles (all levels)
     * - '1.*' = All leadership roles
     * - '1.1.4' = Only ward chairperson (exact match)
     * - '4.*' = All general member roles
     *
     * Real-World Committee Type Examples:
     * - central: Supports all leadership roles ('1.*', '2.*', '3.*')
     * - province: Supports province-specific roles ('1.1.2', '1.2.*')
     * - district: Supports district-specific roles ('1.1.3', '4.*')
     * - ward: Supports ward leadership + members ('1.1.4', '4.*')
     *
     * Benefits:
     * - Flexible role assignment rules per committee type
     * - Pattern matching eliminates hardcoded role lists
     * - Tenants can customize committee structures
     * - Easy to add new role patterns without migration
     *
     * Connection Strategy: Single database with GlobalScope-based tenant isolation.
     */
    public function up(): void
    {
        Schema::create('committee_role_mappings', function (Blueprint $table) {
            // Composite primary key
            $table->string('tenant_id', 50)->nullable(false);
            $table->string('committee_type', 50)->nullable(false); // 'central', 'province', 'district', 'ward'

            // Pattern matching for allowed role paths
            // JSONB array of path patterns: ['1.1.*', '1.2.*', '2.1', '4.*']
            $table->jsonb('allowed_role_patterns')->nullable(false);

            // Committee-specific role limits (overrides role defaults)
            // JSONB object mapping path → max count: {'1.1.1': 1, '1.2': 2}
            $table->jsonb('role_limits')->nullable();

            // Committee type metadata
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            // Audit timestamps
            $table->timestamps();

            // Composite primary key
            $table->primary(['tenant_id', 'committee_type'], 'comm_role_map_pk');

            // Indexes for queries
            $table->index(['tenant_id', 'is_active'], 'idx_comm_role_map_tenant_active');
            $table->index('committee_type', 'idx_comm_role_map_type');

            // GIN index for JSONB pattern matching
            $table->index('allowed_role_patterns')->algorithm('gin');
        });

        // Seed example committee-role mappings (Nepal Congress example)
        $this->seedExampleMappings();
    }

    /**
     * Seed example committee-role mappings for demonstration.
     * In production, tenants configure these via setup wizard.
     */
    private function seedExampleMappings(): void
    {
        $now = now();

        // Example: Nepal Congress Party committee-role mappings
        DB::table('committee_role_mappings')->insert([
            // Central Committee: All leadership and administrative roles
            [
                'tenant_id' => 'example-tenant',
                'committee_type' => 'central',
                'allowed_role_patterns' => json_encode([
                    '1.1.1',  // Central Chairperson
                    '1.2',    // All Vice Chairpersons
                    '2.*',    // All Administrative roles (Secretary, Treasurer)
                    '3.*',    // All Advisory roles
                    '4.*'     // All Member roles
                ]),
                'role_limits' => json_encode([
                    '1.1.1' => 1,  // Only 1 Central President
                    '1.2' => 2,    // Max 2 Vice Presidents
                    '2.1' => 1,    // Only 1 General Secretary
                    '2.2' => 1     // Only 1 Treasurer
                ]),
                'description' => 'Central Committee supports all leadership and administrative roles',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Province Committee: Province-specific leadership
            [
                'tenant_id' => 'example-tenant',
                'committee_type' => 'province',
                'allowed_role_patterns' => json_encode([
                    '1.1.2',  // Province Chairperson
                    '1.2',    // Vice Chairpersons
                    '2.1',    // Secretary
                    '2.2',    // Treasurer
                    '4.*'     // Members
                ]),
                'role_limits' => json_encode([
                    '1.1.2' => 1,  // Only 1 Provincial President
                    '1.2' => 2,    // Max 2 Vice Presidents
                    '2.1' => 1,
                    '2.2' => 1
                ]),
                'description' => 'Province Committee with provincial leadership structure',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // District Committee: District-specific leadership
            [
                'tenant_id' => 'example-tenant',
                'committee_type' => 'district',
                'allowed_role_patterns' => json_encode([
                    '1.1.3',  // District Chairperson
                    '1.2',    // Vice Chairpersons
                    '2.1',    // Secretary
                    '2.2',    // Treasurer
                    '4.*'     // Members
                ]),
                'role_limits' => json_encode([
                    '1.1.3' => 1,  // Only 1 District President
                    '1.2' => 2,
                    '2.1' => 1,
                    '2.2' => 1
                ]),
                'description' => 'District Committee with district-level leadership',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Ward Committee: Ward-specific leadership (simplified structure)
            [
                'tenant_id' => 'example-tenant',
                'committee_type' => 'ward',
                'allowed_role_patterns' => json_encode([
                    '1.1.4',  // Ward Chairperson only
                    '4.*'     // General members
                ]),
                'role_limits' => json_encode([
                    '1.1.4' => 1  // Only 1 Ward President
                    // No limit on general members
                ]),
                'description' => 'Ward Committee with simplified structure (President + Members)',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Youth Wing: Cross-geography youth leadership
            [
                'tenant_id' => 'example-tenant',
                'committee_type' => 'youth',
                'allowed_role_patterns' => json_encode([
                    '1.1.*',  // Any chairperson role (youth can operate at any level)
                    '1.2',    // Vice Chairpersons
                    '2.*',    // Administrative roles
                    '4.*'     // Members
                ]),
                'role_limits' => json_encode([
                    '1.1.1' => 1,  // Central Youth President
                    '1.2' => 2
                ]),
                'description' => 'Youth Wing committee structure',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Women's Wing: Cross-geography women's leadership
            [
                'tenant_id' => 'example-tenant',
                'committee_type' => 'women',
                'allowed_role_patterns' => json_encode([
                    '1.1.*',  // Any chairperson role
                    '1.2',    // Vice Chairpersons
                    '2.*',    // Administrative roles
                    '4.*'     // Members
                ]),
                'role_limits' => json_encode([
                    '1.1.1' => 1,  // Central Women's President
                    '1.2' => 2
                ]),
                'description' => "Women's Wing committee structure",
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
        Schema::dropIfExists('committee_role_mappings');
    }
};
