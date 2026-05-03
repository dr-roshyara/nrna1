<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Membership Context - DDD Bounded Context
     * Digital identity first, geography optional, events over coupling
     *
     * Connection Strategy: Single database with GlobalScope-based tenant isolation.
     * All Membership tables live in the main DB with tenant_id column.
     */
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            // Primary key (ULID)
            $table->string('id', 26)->primary();

            // Party-defined member ID (optional, unique per tenant)
            $table->string('member_id', 50)->nullable();

            // Digital identity (required, 1:1 with tenant_user)
            $table->string('tenant_user_id', 26)->nullable(false);

            // Tenant association
            $table->string('tenant_id', 50)->nullable(false);

            // Personal information (JSON)
            // Schema: {full_name, email, phone}
            $table->json('personal_info')->nullable(false);

            // Membership status (draft, pending, approved, active, suspended, inactive, archived)
            $table->string('status', 50)->default('draft');

            // Geography reference (string only, no FK - decoupling from Geography context)
            // Format: "np.3.15.234.1.2" (country.level1.level2...)
            $table->string('residence_geo_reference', 255)->nullable();

            // Membership type (regular, honorary, associate, etc.)
            $table->string('membership_type', 50)->default('regular');

            // Extensibility (JSON for future fields)
            $table->json('metadata')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Unique constraints (tenant-scoped)
            $table->unique(['tenant_id', 'member_id'], 'unique_member_id_per_tenant');
            $table->unique(['tenant_id', 'tenant_user_id'], 'unique_user_per_tenant');

            // Performance indexes
            $table->index(['tenant_id', 'status'], 'idx_tenant_status');
            $table->index(['tenant_id', 'residence_geo_reference'], 'idx_tenant_geo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
