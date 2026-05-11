<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committee_structure_levels', function (Blueprint $table) {
            $table->string('code', 50)->nullable()->after('level_index');
        });
    }

    public function down(): void
    {
        Schema::table('committee_structure_levels', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
