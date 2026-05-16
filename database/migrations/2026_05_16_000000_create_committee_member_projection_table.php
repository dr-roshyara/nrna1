<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_member_projection', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('tenant_id')->index();
            $table->uuid('committee_id')->index();
            $table->uuid('member_id');

            $table->timestamp('assigned_at')->useCurrent();

            $table->timestamps();

            // Idempotency: prevent duplicate (committee_id, member_id) pairs
            $table->unique(['committee_id', 'member_id']);

            // Tenant isolation
            $table->index(['tenant_id', 'committee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_member_projection');
    }
};
