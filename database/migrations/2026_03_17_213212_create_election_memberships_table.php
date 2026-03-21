<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('election_memberships', function (Blueprint $table) {
            // Primary key
            $table->uuid('id')->primary();

            // Core references
            $table->uuid('user_id');
            $table->uuid('organisation_id');
            $table->uuid('election_id');

            // Membership details
            $table->enum('role', ['voter', 'candidate', 'observer', 'admin'])->default('voter');
            $table->enum('status', ['invited', 'active', 'inactive', 'removed'])->default('active');

            // Audit / metadata
            $table->uuid('assigned_by')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            // ── Composite foreign keys ──────────────────────────────────────
            // Ensures user_id + organisation_id exists in user_organisation_roles
            // (user is actually a member of this organisation)
            $table->foreign(['user_id', 'organisation_id'])
                  ->references(['user_id', 'organisation_id'])
                  ->on('user_organisation_roles')
                  ->onDelete('cascade');

            // Ensures election_id + organisation_id exists in elections
            // (election belongs to this organisation)
            $table->foreign(['election_id', 'organisation_id'])
                  ->references(['id', 'organisation_id'])
                  ->on('elections')
                  ->onDelete('cascade');

            // Ensures assigned_by is a valid user
            $table->foreign('assigned_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();

            // ── Business rule constraints ───────────────────────────────────
            // One role per user per election
            $table->unique(['user_id', 'election_id'], 'unique_user_election');

            // ── Performance indexes ─────────────────────────────────────────
            $table->index(['election_id', 'role', 'status'], 'idx_election_role_status');
            $table->index(['user_id', 'status'],             'idx_user_status');
            $table->index(['organisation_id', 'role'],       'idx_org_role');
            $table->index(['assigned_by', 'assigned_at'],    'idx_assigned');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_memberships');
    }
};
