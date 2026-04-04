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
        // membership_applications: dashboard queries filter by org+status+date
        Schema::table('membership_applications', function (Blueprint $table) {
            $table->index(['organisation_id', 'status', 'submitted_at'], 'ma_org_status_submitted');
            $table->index(['organisation_id', 'user_id'],                'ma_org_user');
        });

        // membership_fees: dashboard totals filter by org+status+due_date
        Schema::table('membership_fees', function (Blueprint $table) {
            $table->index(['organisation_id', 'status', 'due_date'], 'mf_org_status_due');
            $table->index(['organisation_id', 'status', 'paid_at'],  'mf_org_status_paid');
        });

        // members: expiry queries + active count
        Schema::table('members', function (Blueprint $table) {
            $table->index(['organisation_id', 'status', 'membership_expires_at'], 'mem_org_status_expires');
        });
    }

    public function down(): void
    {
        Schema::table('membership_applications', function (Blueprint $table) {
            $table->dropIndex('ma_org_status_submitted');
            $table->dropIndex('ma_org_user');
        });

        Schema::table('membership_fees', function (Blueprint $table) {
            $table->dropIndex('mf_org_status_due');
            $table->dropIndex('mf_org_status_paid');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex('mem_org_status_expires');
        });
    }
};
