<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demo_results', function (Blueprint $table) {
            $table->boolean('no_vote')->default(false)->after('position_order');
            // Allow candidacy_id to be null for no-vote rows
            $table->uuid('candidacy_id')->nullable()->change();
        });

        Schema::table('results', function (Blueprint $table) {
            $table->boolean('no_vote')->default(false)->after('position_order');
            // Allow candidacy_id to be null for no-vote rows
            $table->uuid('candidacy_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('demo_results', function (Blueprint $table) {
            $table->dropColumn('no_vote');
            $table->uuid('candidacy_id')->nullable(false)->change();
        });

        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn('no_vote');
            $table->uuid('candidacy_id')->nullable(false)->change();
        });
    }
};
