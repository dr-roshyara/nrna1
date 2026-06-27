<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisations', function (Blueprint $table) {
            $table->jsonb('geographic_levels')->nullable()->after('base_region_id');
        });

        // Seed existing geographical organisations with Nepal default
        $nepalDefault = json_encode([
            ['index' => 1, 'db_level' => 1, 'label' => 'Province',     'local_label' => 'प्रदेश',    'required' => true],
            ['index' => 2, 'db_level' => 2, 'label' => 'District',     'local_label' => 'जिल्ला',    'required' => true],
            ['index' => 3, 'db_level' => 3, 'label' => 'Municipality', 'local_label' => 'नगरपालिका', 'required' => false],
            ['index' => 4, 'db_level' => 4, 'label' => 'Ward',         'local_label' => 'वडा',       'required' => false],
        ]);

        DB::table('organisations')
            ->where('committee_structure', 'geographical')
            ->whereNull('geographic_levels')
            ->update(['geographic_levels' => $nepalDefault]);
    }

    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table) {
            $table->dropColumn('geographic_levels');
        });
    }
};
