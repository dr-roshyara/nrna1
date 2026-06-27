<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            if (!Schema::hasColumn('elections', 'network_binding_strategy')) {
                $table->string('network_binding_strategy', 30)->default('ip_count')->after('ballot_type');
            }
            if (!Schema::hasColumn('elections', 'device_binding_strategy')) {
                $table->string('device_binding_strategy', 30)->default('none')->after('network_binding_strategy');
            }
            if (!Schema::hasColumn('elections', 'ballot_authorization_protocol')) {
                $table->string('ballot_authorization_protocol', 30)->default('single_code')->after('device_binding_strategy');
            }
            if (!Schema::hasColumn('elections', 'max_votes_per_ip')) {
                $table->integer('max_votes_per_ip')->default(6)->after('ballot_authorization_protocol');
            }
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn([
                'network_binding_strategy',
                'device_binding_strategy',
                'ballot_authorization_protocol',
                'max_votes_per_ip',
            ]);
        });
    }
};
