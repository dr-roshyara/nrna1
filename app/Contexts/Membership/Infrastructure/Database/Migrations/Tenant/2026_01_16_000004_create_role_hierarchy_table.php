<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Create role_hierarchy table (Hierarchical Role Paths - Like Geography).
     *
     * ARCHITECTURE DECISION: Hierarchical Path-Based System
     * - Same pattern as Geography Context (ltree-style paths)
     * - Universal role hierarchy shared across all tenants
     * - Pattern matching for role queries ('1.1.*' = all chairperson roles)
     * - Multi-level hierarchy (category → type → committee_level → local)
     * - Extensible without breaking existing roles
     *
     * Path Format Examples:
     * - '1' = Leadership Roles (Level 1: Category)
     * - '1.1' = Chairperson Roles (Level 2: Type)
     * - '1.1.1' = Central Committee Chairperson (Level 3: Committee Level)
     * - '1.1.1.1' = President (Level 4: Local Name - mapped by tenant)
     *
     * Benefits:
     * - Same developer experience as geography paths
     * - Pattern matching: '1.1.*' matches all chairperson roles
     * - Hierarchical queries without recursive CTEs
     * - Global structure, tenant-specific names
     * - Clean separation of concerns
     *
     * Connection Strategy: Single database with GlobalScope-based tenant isolation.
     */
    public function up(): void
    {
        Schema::create('role_hierarchy', function (Blueprint $table) {
            // Primary key (auto-increment)
            $table->id();

            // Hierarchical path (like geography ltree)
            $table->string('path', 255)->unique()->nullable(false); // '1.2.3.4'

            // Hierarchy level (1=category, 2=type, 3=committee_level, 4=local)
            $table->integer('level')->nullable(false);

            // Role identification
            $table->string('role_code', 50)->unique()->nullable(); // 'CHAIRPERSON_CENTRAL'
            $table->text('description')->nullable();

            // Role metadata
            $table->boolean('is_leadership')->default(false);
            $table->boolean('can_vote')->default(true);
            $table->integer('default_max_per_committee')->nullable(); // NULL = unlimited

            // Parent relationship (self-referencing)
            $table->unsignedBigInteger('parent_id')->nullable();

            // Audit timestamps
            $table->timestamps();

            // Indexes for hierarchical queries (like geography)
            $table->index('path', 'idx_role_path');
            $table->index('level', 'idx_role_level');
            $table->index('parent_id', 'idx_role_parent');

            // Self-referencing foreign key
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('role_hierarchy')
                  ->onDelete('restrict'); // Cannot delete if children exist
        });

        // Seed hierarchical role structure (Universal across all tenants)
        $this->seedRoleHierarchy();
    }

    /**
     * Seed the universal role hierarchy.
     * Tenants will map these paths to their local names via role_localizations.
     */
    private function seedRoleHierarchy(): void
    {
        $now = now();

        // LEVEL 1: Role Categories
        DB::table('role_hierarchy')->insert([
            [
                'id' => 1,
                'path' => '1',
                'level' => 1,
                'role_code' => 'LEADERSHIP',
                'description' => 'Leadership roles (chairperson, vice chairperson, etc.)',
                'is_leadership' => true,
                'can_vote' => true,
                'default_max_per_committee' => null,
                'parent_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'path' => '2',
                'level' => 1,
                'role_code' => 'ADMINISTRATIVE',
                'description' => 'Administrative roles (secretary, treasurer, etc.)',
                'is_leadership' => false,
                'can_vote' => true,
                'default_max_per_committee' => null,
                'parent_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'path' => '3',
                'level' => 1,
                'role_code' => 'ADVISORY',
                'description' => 'Advisory roles (advisor, consultant, etc.)',
                'is_leadership' => false,
                'can_vote' => true,
                'default_max_per_committee' => null,
                'parent_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'path' => '4',
                'level' => 1,
                'role_code' => 'MEMBER',
                'description' => 'General member roles',
                'is_leadership' => false,
                'can_vote' => true,
                'default_max_per_committee' => null,
                'parent_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // LEVEL 2: Role Types under Leadership
        DB::table('role_hierarchy')->insert([
            [
                'id' => 5,
                'path' => '1.1',
                'level' => 2,
                'role_code' => 'CHAIRPERSON',
                'description' => 'Chairperson roles (all levels)',
                'is_leadership' => true,
                'can_vote' => true,
                'default_max_per_committee' => 1,
                'parent_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'path' => '1.2',
                'level' => 2,
                'role_code' => 'VICE_CHAIRPERSON',
                'description' => 'Vice Chairperson roles (all levels)',
                'is_leadership' => true,
                'can_vote' => true,
                'default_max_per_committee' => null, // Can have multiple
                'parent_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // LEVEL 2: Administrative roles
        DB::table('role_hierarchy')->insert([
            [
                'id' => 7,
                'path' => '2.1',
                'level' => 2,
                'role_code' => 'SECRETARY',
                'description' => 'Secretary roles (all levels)',
                'is_leadership' => true,
                'can_vote' => true,
                'default_max_per_committee' => 1,
                'parent_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'path' => '2.2',
                'level' => 2,
                'role_code' => 'TREASURER',
                'description' => 'Treasurer roles (all levels)',
                'is_leadership' => true,
                'can_vote' => true,
                'default_max_per_committee' => 1,
                'parent_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // LEVEL 3: Committee-specific roles (Chairperson for different committee types)
        DB::table('role_hierarchy')->insert([
            [
                'id' => 9,
                'path' => '1.1.1',
                'level' => 3,
                'role_code' => 'CHAIRPERSON_CENTRAL',
                'description' => 'Central Committee Chairperson',
                'is_leadership' => true,
                'can_vote' => true,
                'default_max_per_committee' => 1,
                'parent_id' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 10,
                'path' => '1.1.2',
                'level' => 3,
                'role_code' => 'CHAIRPERSON_PROVINCE',
                'description' => 'Province Committee Chairperson',
                'is_leadership' => true,
                'can_vote' => true,
                'default_max_per_committee' => 1,
                'parent_id' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 11,
                'path' => '1.1.3',
                'level' => 3,
                'role_code' => 'CHAIRPERSON_DISTRICT',
                'description' => 'District Committee Chairperson',
                'is_leadership' => true,
                'can_vote' => true,
                'default_max_per_committee' => 1,
                'parent_id' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 12,
                'path' => '1.1.4',
                'level' => 3,
                'role_code' => 'CHAIRPERSON_WARD',
                'description' => 'Ward Committee Chairperson',
                'is_leadership' => true,
                'can_vote' => true,
                'default_max_per_committee' => 1,
                'parent_id' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // LEVEL 4: General member role
        DB::table('role_hierarchy')->insert([
            [
                'id' => 13,
                'path' => '4.1',
                'level' => 2,
                'role_code' => 'GENERAL_MEMBER',
                'description' => 'General committee member',
                'is_leadership' => false,
                'can_vote' => true,
                'default_max_per_committee' => null, // Unlimited
                'parent_id' => 4,
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
        Schema::dropIfExists('role_hierarchy');
    }
};
