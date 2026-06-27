<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_associations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('association_id')->unique();
            $table->uuid('organisation_id');
            $table->string('member_id');
            $table->string('committee_id');
            $table->enum('association_type', ['residence', 'exception', 'manual']);
            $table->enum('status', ['active', 'suspended', 'terminated'])->default('active');
            $table->timestamp('associated_at');

            // Audit trail with actor type semantics
            $table->string('approved_by_actor_id')->nullable();
            $table->string('approved_by_actor_type', 50)->nullable();

            $table->string('suspended_by_actor_id')->nullable();
            $table->string('suspended_by_actor_type', 50)->nullable();
            $table->string('suspension_reason', 500)->nullable();

            $table->string('terminated_by_actor_id')->nullable();
            $table->string('terminated_by_actor_type', 50)->nullable();
            $table->string('termination_reason', 500)->nullable();

            $table->timestamps();

            // Indexes for query performance and active uniqueness checks
            $table->index(['organisation_id', 'member_id', 'committee_id', 'status']);
            $table->index(['organisation_id', 'committee_id', 'status']);
            $table->index(['organisation_id', 'member_id', 'status']);

            // Foreign keys
            $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
            $table->foreign('committee_id')->references('id')->on('committees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_associations');
    }
};
