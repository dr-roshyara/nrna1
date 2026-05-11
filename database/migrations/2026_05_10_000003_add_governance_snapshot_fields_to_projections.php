<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committee_governance_projections', function (Blueprint $table) {
            $table->boolean('can_act')->default(false)->after('legitimacy');
            $table->boolean('is_fully_operational')->default(false)->after('can_act');
            $table->dateTime('rebuilt_at')->nullable()->after('evaluated_at');
        });
    }

    public function down(): void
    {
        Schema::table('committee_governance_projections', function (Blueprint $table) {
            $table->dropColumn(['can_act', 'is_fully_operational', 'rebuilt_at']);
        });
    }
};
