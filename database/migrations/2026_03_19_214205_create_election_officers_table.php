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
        Schema::create('election_officers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('organisation_id');
            $table->uuid('user_id');
            $table->uuid('election_id')->nullable();
            $table->uuid('appointed_by')->nullable();

            $table->enum('role', ['chief', 'deputy', 'commissioner'])->default('commissioner');
            $table->enum('status', ['pending', 'active', 'inactive', 'resigned'])->default('pending');

            $table->timestamp('appointed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('term_ends_at')->nullable();
            $table->json('permissions')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('election_id')->references('id')->on('elections')->nullOnDelete();
            $table->foreign('appointed_by')->references('id')->on('users')->nullOnDelete();

            $table->unique(['user_id', 'organisation_id'], 'unique_officer_per_org');
            $table->index(['organisation_id', 'status', 'role'], 'idx_org_status_role');
            $table->index(['user_id', 'status'], 'idx_user_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_officers');
    }
};
