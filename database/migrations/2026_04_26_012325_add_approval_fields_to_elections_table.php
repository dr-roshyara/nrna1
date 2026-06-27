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
            $table->timestamp('submitted_for_approval_at')->nullable();
            $table->uuid('submitted_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->uuid('approved_by')->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->uuid('rejected_by')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->foreign('submitted_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropForeignKey(['submitted_by']);
            $table->dropForeignKey(['approved_by']);
            $table->dropForeignKey(['rejected_by']);

            $table->dropColumn([
                'submitted_for_approval_at',
                'submitted_by',
                'approved_at',
                'approved_by',
                'approval_notes',
                'rejected_at',
                'rejected_by',
                'rejection_reason',
            ]);
        });
    }
};
