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
        Schema::table('demo_codes', function (Blueprint $table) {
            $table->boolean('has_agreed_to_vote')->default(false)->after('has_voted');
            $table->dateTime('has_agreed_to_vote_at')->nullable()->after('has_agreed_to_vote');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            $table->dropColumn(['has_agreed_to_vote', 'has_agreed_to_vote_at']);
        });
    }
};
