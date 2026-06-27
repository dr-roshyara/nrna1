<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committee_associations', function (Blueprint $table) {
            // Add lineage_id column to track which constitutional lineage this episode belongs to
            $table->uuid('lineage_id')->after('organisation_id');
            $table->index('lineage_id');
        });
    }

    public function down(): void
    {
        Schema::table('committee_associations', function (Blueprint $table) {
            $table->dropIndex('committee_associations_lineage_id_index');
            $table->dropColumn('lineage_id');
        });
    }
};
