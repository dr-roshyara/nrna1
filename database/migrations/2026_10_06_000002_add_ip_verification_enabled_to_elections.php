<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "Voter's IP Verification" election setting (PBDIGIT-222, PO 2026-10-06).
 *
 * Controls Capture-IP availability only (evidence into voter_ip_verifications). It is not
 * security policy — not one of Election::SECURITY_POLICY_FIELDS, changeable at any time — and
 * it enforces nothing: IP Binding is PBDIGIT-247. Default off.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->boolean('ip_verification_enabled')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn('ip_verification_enabled');
        });
    }
};
