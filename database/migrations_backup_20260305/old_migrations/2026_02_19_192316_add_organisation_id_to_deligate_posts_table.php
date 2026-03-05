<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganisationIdToDeligatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Only add column if the table exists
        if (Schema::hasTable('deligate_posts')) {
            Schema::table('deligate_posts', function (Blueprint $table) {
                if (!Schema::hasColumn('deligate_posts', 'organisation_id')) {
                    $table->unsignedBigInteger('organisation_id')->nullable()->after('id')->index();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Only drop if the table and column exist
        if (Schema::hasTable('deligate_posts') && Schema::hasColumn('deligate_posts', 'organisation_id')) {
            Schema::table('deligate_posts', function (Blueprint $table) {
                $table->dropIndex(['organisation_id']);
                $table->dropColumn('organisation_id');
            });
        }
    }
}
