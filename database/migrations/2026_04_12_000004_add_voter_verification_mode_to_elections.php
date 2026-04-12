<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            // String (not enum) for SQLite compatibility
            // Values: 'none' | 'ip_only' | 'fingerprint_only' | 'both'
            $table->string('voter_verification_mode')->default('none');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn('voter_verification_mode');
        });
    }
};
