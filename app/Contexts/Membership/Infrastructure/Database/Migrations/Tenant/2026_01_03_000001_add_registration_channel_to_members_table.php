<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add registration_channel column to members table
     *
     * Business Rule:
     * - Mobile registration → 'mobile' channel → DRAFT status
     * - Desktop registration → 'desktop' channel → PENDING status
     * - Bulk import → 'import' channel → PENDING status
     *
     * This column tracks HOW the member was registered, which determines
     * the initial status and verification requirements.
     *
     * Connection Strategy: Single database with GlobalScope-based tenant isolation.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'registration_channel')) {
                $table->string('registration_channel', 20)
                    ->nullable()
                    ->after('membership_type')
                    ->comment('Channel used for registration: mobile, desktop, import');
            }

            $existingIndexNames = collect(Schema::getIndexes('members'))->pluck('name');
            if (!$existingIndexNames->contains('members_registration_channel_index')) {
                $table->index('registration_channel', 'members_registration_channel_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex('members_registration_channel_index');
            $table->dropColumn('registration_channel');
        });
    }
};
