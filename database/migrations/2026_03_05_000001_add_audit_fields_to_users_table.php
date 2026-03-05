<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_audit_fields_to_users_table.php

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
        Schema::table('users', function (Blueprint $table) {
            // Audit fields for voter approvals/suspensions
            if (!Schema::hasColumn('users', 'approvedBy')) {
                $table->string('approvedBy')->nullable()->after('can_vote');
            }

            if (!Schema::hasColumn('users', 'suspendedBy')) {
                $table->string('suspendedBy')->nullable()->after('approvedBy');
            }

            if (!Schema::hasColumn('users', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('suspendedBy');
            }

            // Voting timestamps
            if (!Schema::hasColumn('users', 'voting_started_at')) {
                $table->timestamp('voting_started_at')->nullable()->after('suspended_at');
            }

            if (!Schema::hasColumn('users', 'vote_submitted_at')) {
                $table->timestamp('vote_submitted_at')->nullable()->after('voting_started_at');
            }

            if (!Schema::hasColumn('users', 'vote_completed_at')) {
                $table->timestamp('vote_completed_at')->nullable()->after('vote_submitted_at');
            }

            if (!Schema::hasColumn('users', 'voter_registration_at')) {
                $table->timestamp('voter_registration_at')->nullable()->after('vote_completed_at');
            }

            // Code-related fields
            if (!Schema::hasColumn('users', 'has_used_code1')) {
                $table->boolean('has_used_code1')->default(false)->after('voter_registration_at');
            }

            if (!Schema::hasColumn('users', 'has_used_code2')) {
                $table->boolean('has_used_code2')->default(false)->after('has_used_code1');
            }

            if (!Schema::hasColumn('users', 'code1')) {
                $table->string('code1')->nullable()->after('has_used_code2');
            }

            if (!Schema::hasColumn('users', 'code2')) {
                $table->string('code2')->nullable()->after('code1');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'approvedBy',
                'suspendedBy',
                'suspended_at',
                'voting_started_at',
                'vote_submitted_at',
                'vote_completed_at',
                'voter_registration_at',
                'has_used_code1',
                'has_used_code2',
                'code1',
                'code2',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};