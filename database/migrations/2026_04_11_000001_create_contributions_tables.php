<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('user_id');

            $table->string('title', 255);
            $table->text('description');

            // Track & workflow
            $table->enum('track', ['micro', 'standard', 'major'])->default('micro');
            $table->enum('status', [
                'draft', 'pending', 'verified', 'approved',
                'rejected', 'appealed', 'completed',
            ])->default('draft');

            // Scoring inputs (stored so points can be re-calculated if formula changes)
            $table->integer('effort_units')->default(0);
            $table->json('team_skills')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->integer('outcome_bonus')->default(0);
            $table->integer('calculated_points')->default(0);

            // Verification
            $table->enum('proof_type', [
                'self_report', 'photo', 'document', 'third_party', 'institutional',
            ])->default('self_report');
            $table->string('proof_path')->nullable();
            $table->text('verifier_notes')->nullable();
            $table->uuid('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();

            // Approval
            $table->uuid('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Audit — nullable so the FK can use onDelete('set null') if creator is removed
            $table->uuid('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys — cascade for ownership, set null for optional actors
            $table->foreign('organisation_id')
                  ->references('id')->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('verified_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');

            $table->foreign('approved_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');

            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');

            $table->index(['organisation_id', 'user_id', 'status']);
            $table->index(['organisation_id', 'track', 'created_at']);
        });

        // Immutable audit ledger — every point transaction is permanent
        Schema::create('points_ledger', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('user_id');
            $table->uuid('contribution_id');
            $table->integer('points');
            $table->enum('action', ['earned', 'spent', 'adjusted', 'appealed']);
            $table->text('reason')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')
                  ->references('id')->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('contribution_id')
                  ->references('id')->on('contributions')
                  ->onDelete('cascade');

            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');

            $table->index(['organisation_id', 'user_id', 'created_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('leaderboard_visibility', ['public', 'anonymous', 'private'])
                  ->default('anonymous')
                  ->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('leaderboard_visibility');
        });

        Schema::dropIfExists('points_ledger');
        Schema::dropIfExists('contributions');
    }
};
