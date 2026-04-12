<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('voter_verifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('election_id');
            $table->uuid('user_id');
            $table->uuid('organisation_id');
            $table->string('verified_ip', 45)->nullable();
            $table->string('verified_device_fingerprint_hash', 64)->nullable();
            $table->json('verified_device_components')->nullable();
            $table->uuid('verified_by');
            $table->timestamp('verified_at');
            $table->text('notes')->nullable();
            $table->string('status')->default('active'); // active | revoked (string for SQLite)
            $table->uuid('revoked_by')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->unique(['election_id', 'user_id']);
            $table->index(['election_id', 'status']);

            // SQLite-safe foreign keys
            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('verified_by')->references('id')->on('users');
                $table->foreign('revoked_by')->references('id')->on('users');
                $table->foreign('organisation_id')->references('id')->on('organisations');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_verifications');
    }
};
