<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * IP Binding (P-BINDING) switch — PBDIGIT-247, PO 2026-10-06 (B2).
 *
 * A security-policy field (Election::SECURITY_POLICY_FIELDS, locked from ReadyForVoting). When on, the
 * Security Verdict Authority refuses a voter whose request IP is not their captured IP in this election
 * (voter_ip_verifications, PBDIGIT-222), at forEntry and forCast. Default off.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->boolean('ip_binding_enabled')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn('ip_binding_enabled');
        });
    }
};
