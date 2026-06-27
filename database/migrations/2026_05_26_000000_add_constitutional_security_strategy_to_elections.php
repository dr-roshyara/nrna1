<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->string('network_binding_strategy')->default('ip_count')->after('is_active');
            $table->string('device_binding_strategy')->default('none')->after('network_binding_strategy');
            $table->integer('max_votes_per_ip')->default(6)->after('device_binding_strategy');
            $table->string('ballot_authorization_protocol')->default('single_code')->after('max_votes_per_ip');
            $table->boolean('trust_overlay_active')->default(false)->after('ballot_authorization_protocol');
            $table->string('trust_overlay_priority')->nullable()->after('trust_overlay_active');
            $table->string('trust_overlay_reason')->nullable()->after('trust_overlay_priority');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn([
                'network_binding_strategy',
                'device_binding_strategy',
                'max_votes_per_ip',
                'ballot_authorization_protocol',
                'trust_overlay_active',
                'trust_overlay_priority',
                'trust_overlay_reason',
            ]);
        });
    }
};
