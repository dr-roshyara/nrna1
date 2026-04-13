<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            // Note: after() omitted — defensive approach avoids failure if column order differs
            $table->boolean('ip_restriction_enabled')->default(false);
            $table->unsignedInteger('ip_restriction_max_per_ip')->default(4);
            $table->json('ip_whitelist')->nullable();
            $table->boolean('no_vote_option_enabled')->default(false);
            $table->string('no_vote_option_label', 100)->default('No vote / Abstain');

            // SQLite doesn't support enum — use string instead
            if (DB::getDriverName() === 'sqlite') {
                $table->string('selection_constraint_type')->default('maximum');
            } else {
                $table->enum('selection_constraint_type', ['any','exact','range','minimum','maximum'])
                      ->default('maximum');
            }

            $table->unsignedInteger('selection_constraint_min')->nullable();
            $table->unsignedInteger('selection_constraint_max')->nullable();
            $table->unsignedInteger('settings_version')->default(0);
            $table->uuid('settings_updated_by')->nullable();
            $table->timestamp('settings_updated_at')->nullable();
            $table->json('settings_changes')->nullable();

            // Foreign key — skip for SQLite if it causes issues
            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign('settings_updated_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['settings_updated_by']);
            }
            $table->dropColumn([
                'ip_restriction_enabled',
                'ip_restriction_max_per_ip',
                'ip_whitelist',
                'no_vote_option_enabled',
                'no_vote_option_label',
                'selection_constraint_type',
                'selection_constraint_min',
                'selection_constraint_max',
                'settings_version',
                'settings_updated_by',
                'settings_updated_at',
                'settings_changes',
            ]);
        });
    }
};
