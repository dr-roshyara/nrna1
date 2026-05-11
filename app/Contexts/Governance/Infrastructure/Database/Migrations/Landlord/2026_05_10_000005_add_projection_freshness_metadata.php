<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committee_governance_projections', function (Blueprint $table) {
            $table->string('projection_generation', 32)->nullable()->after('rebuilt_at');
            $table->unsignedInteger('projection_schema_version')->default(1)->after('projection_generation');
            $table->index('projection_generation');
        });
    }

    public function down(): void
    {
        Schema::table('committee_governance_projections', function (Blueprint $table) {
            $table->dropIndex(['projection_generation']);
            $table->dropColumn(['projection_generation', 'projection_schema_version']);
        });
    }
};
