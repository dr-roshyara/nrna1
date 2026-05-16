<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committee_member_projection', function (Blueprint $table) {
            $table->string('member_name')->nullable()->after('member_id');
            $table->string('member_email')->nullable()->after('member_name');
        });
    }

    public function down(): void
    {
        Schema::table('committee_member_projection', function (Blueprint $table) {
            $table->dropColumn(['member_name', 'member_email']);
        });
    }
};
