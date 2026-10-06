<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * voter_ip_verifications — IP evidence, separately bounded from identity evidence (PBDIGIT-222,
 * PO decisions 2026-10-04 / 2026-10-06). Election- and voter-scoped; one current row per
 * (election, voter) — a re-capture replaces it. Never read by admission or entitlement; no
 * enforcement here (P-BINDING is PBDIGIT-247). The address column holds IPv4 or IPv6 (45 chars).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('voter_ip_verifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('election_id');
            $table->uuid('user_id');
            $table->uuid('organisation_id');
            $table->string('ip_address', 45);
            $table->uuid('captured_by');
            $table->timestamp('captured_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['election_id', 'user_id']);

            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('captured_by')->references('id')->on('users');
                $table->foreign('organisation_id')->references('id')->on('organisations');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_ip_verifications');
    }
};
