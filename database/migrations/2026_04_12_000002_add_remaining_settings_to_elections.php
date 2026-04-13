<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            // IP mismatch action: 'block' or 'warn'
            $table->string('ip_mismatch_action')->default('block');

            // Voting IP mode: 'strict' or 'flexible'
            $table->string('voting_ip_mode')->default('strict');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn('ip_mismatch_action');
            $table->dropColumn('voting_ip_mode');
        });
    }
};
