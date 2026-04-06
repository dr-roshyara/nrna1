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
        Schema::table('organisation_invitations', function (Blueprint $table) {
            $table->string('invitation_type')->default('member')->after('role'); // member | participant
            $table->string('participant_type')->nullable()->after('invitation_type'); // staff | guest | election_committee
        });
    }

    public function down(): void
    {
        Schema::table('organisation_invitations', function (Blueprint $table) {
            $table->dropColumn(['invitation_type', 'participant_type']);
        });
    }
};
