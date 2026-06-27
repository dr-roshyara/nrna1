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
        Schema::table('elections', function (Blueprint $table) {
            // Administration Phase
            $table->timestamp('administration_suggested_start')->nullable()->after('settings_changes');
            $table->timestamp('administration_suggested_end')->nullable()->after('administration_suggested_start');
            $table->boolean('administration_completed')->default(false)->after('administration_suggested_end');
            $table->timestamp('administration_completed_at')->nullable()->after('administration_completed');

            // Nomination Phase
            $table->timestamp('nomination_suggested_start')->nullable()->after('administration_completed_at');
            $table->timestamp('nomination_suggested_end')->nullable()->after('nomination_suggested_start');
            $table->boolean('nomination_completed')->default(false)->after('nomination_suggested_end');
            $table->timestamp('nomination_completed_at')->nullable()->after('nomination_completed');

            // Voting Phase (strict time enforcement — integrity critical)
            $table->timestamp('voting_starts_at')->nullable()->after('nomination_completed_at');
            $table->timestamp('voting_ends_at')->nullable()->after('voting_starts_at');

            // Auto-transition configuration
            $table->boolean('allow_auto_transition')->default(true)->after('voting_ends_at');
            $table->unsignedInteger('auto_transition_grace_days')->default(7)->after('allow_auto_transition');

            // Append-only audit log (fast-read, capped at 200 entries)
            $table->json('state_audit_log')->nullable()->after('auto_transition_grace_days');

            // Indexes for grace-period command performance
            $table->index(['administration_completed', 'administration_suggested_end'], 'idx_admin_phase');
            $table->index(['nomination_completed', 'nomination_suggested_end'], 'idx_nomination_phase');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropIndex('idx_admin_phase');
            $table->dropIndex('idx_nomination_phase');
            $table->dropColumn([
                'administration_suggested_start',
                'administration_suggested_end',
                'administration_completed',
                'administration_completed_at',
                'nomination_suggested_start',
                'nomination_suggested_end',
                'nomination_completed',
                'nomination_completed_at',
                'voting_starts_at',
                'voting_ends_at',
                'allow_auto_transition',
                'auto_transition_grace_days',
                'state_audit_log',
            ]);
        });
    }
};
