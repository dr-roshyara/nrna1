<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('committee_membership_applications', function (Blueprint $table) {
            $table->string('id', 26)->primary();
            $table->string('organisation_id', 50);
            $table->string('member_id', 50);
            $table->string('committee_id', 26);
            $table->string('reason', 20);
            $table->text('exception_justification')->nullable();
            $table->string('status', 20);
            $table->timestamp('submitted_at')->nullable();
            $table->string('reviewed_by', 50)->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['organisation_id', 'member_id', 'committee_id'], 'idx_cma_org_member_committee');
            $table->index(['organisation_id', 'status'], 'idx_cma_org_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_membership_applications');
    }
};
