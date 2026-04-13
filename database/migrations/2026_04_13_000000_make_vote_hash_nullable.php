<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make vote_hash nullable in votes table (replaced by receipt_hash)
     *
     * vote_hash was replaced by receipt_hash for verification, but was never
     * made nullable. This caused "Field 'vote_hash' doesn't have a default value" errors.
     */
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // Make vote_hash nullable since receipt_hash is now used for verification
            $table->string('vote_hash')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // Restore vote_hash as non-nullable
            $table->string('vote_hash')->nullable(false)->change();
        });
    }
};
