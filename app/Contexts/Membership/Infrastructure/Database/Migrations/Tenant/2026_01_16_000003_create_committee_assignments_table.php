<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create committee_assignments table (Rich Pivot Table).
     *
     * ARCHITECTURE DECISION: Many-to-Many with Rich Relationship Data
     * - Members can belong to MULTIPLE committees (e.g., Ward + Youth Wing)
     * - Same member can have DIFFERENT roles in different committees
     * - Track assignment history (joined_date, left_date, is_active)
     * - Support elections and appointments
     * - Enforce business rules via constraints
     *
     * Committee Roles in Political Parties:
     * - chairperson: Committee head (only ONE active per committee)
     * - vice_chairperson: Deputy (can be multiple)
     * - secretary: Committee secretary (usually ONE)
     * - treasurer: Financial officer
     * - member: General member
     * - advisor: Senior advisory role
     *
     * Business Rules Enforced:
     * - One active membership per member per committee
     * - Only ONE active chairperson per committee
     * - Track election vs appointment for democratic legitimacy
     * - Support term limits and historical tracking
     *
     * Connection Strategy: Single database with GlobalScope-based tenant isolation.
     */
    public function up(): void
    {
        Schema::create('committee_assignments', function (Blueprint $table) {
            // Primary key (ULID)
            $table->string('id', 26)->primary();

            // Foreign keys to members and committees
            // NOTE: These are WITHIN Membership Context, NOT cross-context
            $table->string('committee_id', 26)->nullable(false);
            $table->string('member_id', 26)->nullable(false);

            // Assignment details
            // Reference by hierarchical PATH, not string (like geography)
            $table->string('role_path', 255)->nullable(false); // e.g., '1.1.1' = Central Chairperson
            $table->date('joined_date')->default(now());
            $table->date('left_date')->nullable();
            $table->boolean('is_active')->default(true);

            // Election/Appointment details (for democratic legitimacy)
            $table->date('election_date')->nullable(); // Date elected to this role
            $table->date('term_end_date')->nullable(); // When term expires
            $table->string('nomination_type', 20)->default('appointed'); // 'elected', 'appointed', 'volunteered'

            // Appointment authority (who assigned this member)
            $table->string('appointed_by_user_id', 26)->nullable(); // TenantUser who made assignment

            // Notes and metadata
            $table->text('notes')->nullable(); // Assignment notes, reasons, etc.
            $table->json('metadata')->nullable(); // Extensibility for future fields

            // Tenant isolation
            $table->uuid('organisation_id')->nullable(false);

            // Audit timestamps
            $table->timestamps();
            $table->softDeletes();

            // FK to committees (same ULID varchar(26) type — safe)
            $table->foreign('committee_id')
                  ->references('id')
                  ->on('committees')
                  ->onDelete('cascade');

            // FK to organisations and member_id: omitted intentionally
            // Referential integrity is enforced at application layer for cross-database references

            // Performance indexes
            $table->index('member_id', 'idx_assignment_member_id');
            $table->index(['member_id', 'is_active'], 'idx_member_active_assignments');
            $table->index(['committee_id', 'is_active'], 'idx_committee_active_members');
            $table->index(['committee_id', 'role_path', 'is_active'], 'idx_committee_role_active');
            $table->index('role_path', 'idx_assignment_role_path');
            $table->index('joined_date', 'idx_assignment_joined_date');
            $table->index('term_end_date', 'idx_assignment_term_end');
            $table->index('organisation_id', 'idx_assignment_organisation_id');

            // Unique constraint: One active membership per member per committee
            // PostgreSQL partial unique index (only for active assignments)
            // This allows historical records (is_active = false) for same member-committee
            if (config('database.default') === 'pgsql' || config('database.default') === 'tenant_test') {
                // Use raw SQL for partial unique index
                // Cannot be done via Blueprint in Laravel
            }
        });

        // Add unique constraints and check constraints (PostgreSQL)
        if (config('database.default') === 'pgsql' || config('database.default') === 'tenant_test') {
            // Unique active assignment per member per committee
            DB::statement("
                CREATE UNIQUE INDEX unique_active_assignment
                ON committee_assignments (committee_id, member_id)
                WHERE is_active = TRUE
            ");

            // Only ONE active chairperson per committee per role path (critical business rule)
            // This ensures one President, one Provincial President, etc. per committee
            DB::statement("
                CREATE UNIQUE INDEX unique_active_role_per_committee
                ON committee_assignments (committee_id, role_path)
                WHERE is_active = TRUE
            ");

            // Check constraint: left_date must be after joined_date
            DB::statement("
                ALTER TABLE committee_assignments
                ADD CONSTRAINT chk_left_date_after_joined
                CHECK (left_date IS NULL OR left_date >= joined_date)
            ");

            // Check constraint: term_end_date must be after election_date
            DB::statement("
                ALTER TABLE committee_assignments
                ADD CONSTRAINT chk_term_end_after_election
                CHECK (
                    term_end_date IS NULL
                    OR election_date IS NULL
                    OR term_end_date >= election_date
                )
            ");

            // Check constraint: if nomination_type is 'elected', election_date is required
            DB::statement("
                ALTER TABLE committee_assignments
                ADD CONSTRAINT chk_elected_must_have_election_date
                CHECK (
                    nomination_type != 'elected'
                    OR election_date IS NOT NULL
                )
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop constraints first (if PostgreSQL)
        if (config('database.default') === 'pgsql') {
            DB::statement("DROP INDEX IF EXISTS unique_active_assignment");
            DB::statement("DROP INDEX IF EXISTS unique_active_role_per_committee");
            DB::statement("ALTER TABLE committee_assignments DROP CONSTRAINT IF EXISTS chk_left_date_after_joined");
            DB::statement("ALTER TABLE committee_assignments DROP CONSTRAINT IF EXISTS chk_term_end_after_election");
            DB::statement("ALTER TABLE committee_assignments DROP CONSTRAINT IF EXISTS chk_elected_must_have_election_date");
        }

        Schema::dropIfExists('committee_assignments');
    }
};
