<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->boolean('voting_locked')->default(false)->after('voting_ends_at');
            $table->timestamp('voting_locked_at')->nullable()->after('voting_locked');
            $table->uuid('voting_locked_by')->nullable()->after('voting_locked_at');
            $table->boolean('results_locked')->default(false)->after('results_published_at');
            $table->timestamp('results_locked_at')->nullable()->after('results_locked');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn(['voting_locked', 'voting_locked_at', 'voting_locked_by', 'results_locked', 'results_locked_at']);
        });
    }
};
