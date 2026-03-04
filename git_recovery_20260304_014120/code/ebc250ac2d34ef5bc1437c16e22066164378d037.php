<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds onboarding tracking and user preference fields to users table
     * to support enhanced dashboard resolution and session freshness validation.
     *
     * Fields added:
     * - onboarded_at: Tracks when user completed initial onboarding
     * - last_used_organisation_id: Remembers user's last active organisation (for UX)
     * - dashboard_preferences: JSON field for user UI/UX preferences
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Track when user completed first onboarding
            // Used in first-time user detection logic
            if (!Schema::hasColumn('users', 'onboarded_at')) {
                $table->timestamp('onboarded_at')
                    ->nullable()
                    ->after('email_verified_at')
                    ->comment('Timestamp when user completed first-time onboarding');
            }

            // Remember user's last organisation for UX
            // When user logs in, they can quickly return to their last active org
            if (!Schema::hasColumn('users', 'last_used_organisation_id')) {
                $table->unsignedBigInteger('last_used_organisation_id')
                    ->nullable()
                    ->after('organisation_id')
                    ->comment('Last organisation user was active in (for UX improvements)');

                // Add foreign key constraint
                $table->foreign('last_used_organisation_id')
                    ->references('id')
                    ->on('organisations')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            }

            // Store user dashboard/UI preferences
            // Examples: preferred theme, sidebar position, default view, etc.
            if (!Schema::hasColumn('users', 'dashboard_preferences')) {
                $table->json('dashboard_preferences')
                    ->nullable()
                    ->after('last_used_organisation_id')
                    ->comment('User dashboard preferences (theme, layout, etc)');
            }

            // Track last activity for session freshness validation
            // Cache invalidation checks this to prevent stale routing
            if (!Schema::hasColumn('users', 'last_activity_at')) {
                $table->timestamp('last_activity_at')
                    ->nullable()
                    ->after('dashboard_preferences')
                    ->comment('Last activity timestamp for session freshness validation');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Removes all onboarding and preference tracking fields.
     * Safe to rollback - no data loss to critical systems.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove foreign key constraint first
            if (Schema::hasColumn('users', 'last_used_organisation_id')) {
                $table->dropForeign(['last_used_organisation_id']);
                $table->dropColumn('last_used_organisation_id');
            }

            // Remove onboarding tracking
            if (Schema::hasColumn('users', 'onboarded_at')) {
                $table->dropColumn('onboarded_at');
            }

            // Remove preferences
            if (Schema::hasColumn('users', 'dashboard_preferences')) {
                $table->dropColumn('dashboard_preferences');
            }

            // Remove activity tracking
            if (Schema::hasColumn('users', 'last_activity_at')) {
                $table->dropColumn('last_activity_at');
            }
        });
    }
};
