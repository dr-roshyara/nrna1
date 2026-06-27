<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            // Business condition counters — these are the only columns missing
            $table->integer('posts_count')->default(0)->after('is_active');
            $table->integer('voters_count')->default(0)->after('posts_count');
            $table->integer('election_committee_members_count')->default(0)->after('voters_count');
            $table->integer('candidates_count')->default(0)->after('election_committee_members_count');
            $table->integer('pending_candidacies_count')->default(0)->after('candidates_count');
            $table->integer('votes_count')->default(0)->after('pending_candidacies_count');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn([
                'posts_count',
                'voters_count',
                'election_committee_members_count',
                'candidates_count',
                'pending_candidacies_count',
                'votes_count',
            ]);
        });
    }
};
