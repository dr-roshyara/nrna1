<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voter_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('voter_registrations', 'status')) {
                $table->string('status')->nullable()->default('pending')->after('registered_at');
            }
            if (!Schema::hasColumn('voter_registrations', 'election_type')) {
                $table->string('election_type')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('voter_registrations', function (Blueprint $table) {
            $table->dropColumn(['status', 'election_type']);
        });
    }
};
