<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            if (! Schema::hasColumn('voter_slugs', 'vote_completed_at')) {
                $table->timestamp('vote_completed_at')->nullable()->after('has_voted');
                $table->index('vote_completed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            if (Schema::hasColumn('voter_slugs', 'vote_completed_at')) {
                $table->dropIndex(['vote_completed_at']);
                $table->dropColumn('vote_completed_at');
            }
        });
    }
};
