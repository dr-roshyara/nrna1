<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisations', function (Blueprint $table) {
            $table->string('governance_status', 30)
                ->default('pending_setup')
                ->after('status')
                ->comment('pending_setup, governance_configured, active, suspended');

            $table->timestamp('governance_configured_at')
                ->nullable()
                ->after('governance_status');

            $table->uuid('governance_configured_by')
                ->nullable()
                ->after('governance_configured_at');
        });
    }

    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table) {
            $table->dropColumn(['governance_status', 'governance_configured_at', 'governance_configured_by']);
        });
    }
};
