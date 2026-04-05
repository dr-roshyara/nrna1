<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->uuid('membership_type_id')->nullable()->after('organisation_user_id');
            $table->foreign('membership_type_id')
                  ->references('id')->on('membership_types')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['membership_type_id']);
            $table->dropColumn('membership_type_id');
        });
    }
};
