<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds support for election-only mode where organisations don't require formal
     * membership records. When uses_full_membership=false, any OrganisationUser can
     * participate in elections.
     */
    public function up(): void
    {
        Schema::table('organisations', function (Blueprint $table) {
            $table->boolean('uses_full_membership')
                  ->default(true)
                  ->comment('false=election-only mode (any org user can vote), true=full membership required');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table) {
            $table->dropColumn('uses_full_membership');
        });
    }
};
