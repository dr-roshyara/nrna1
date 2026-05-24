<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            // Rename column: suspended_from_state → suspended_lifecycle_context
            // "from_state" implies restoration target. It is NOT.
            // It preserves context at suspension time for audit trails and forensic reconstruction only.
            // Resume does not read it.
            $table->renameColumn('suspended_from_state', 'suspended_lifecycle_context');

            // Add governance audit metadata
            $table->uuid('suspended_by')->nullable()->after('suspended_at');
            $table->text('suspended_reason')->nullable()->after('suspended_by');
            $table->string('suspension_category', 50)->nullable()->after('suspended_reason');
            // Allowed values: fraud_investigation, legal_hold, operational_incident,
            //                 governance_dispute, manual_admin_hold
            $table->timestamp('resumed_at')->nullable()->after('suspension_category');
            $table->uuid('resumed_by')->nullable()->after('resumed_at');

            $table->index('suspended_at');
            $table->index('resumed_at');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropIndex(['suspended_at']);
            $table->dropIndex(['resumed_at']);

            $table->dropColumn([
                'suspended_by',
                'suspended_reason',
                'suspension_category',
                'resumed_at',
                'resumed_by',
            ]);

            $table->renameColumn('suspended_lifecycle_context', 'suspended_from_state');
        });
    }
};
