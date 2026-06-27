<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisation_newsletters', function (Blueprint $table) {
            $table->string('audience_type')->default('all_members')->after('status');
            $table->json('audience_meta')->nullable()->after('audience_type');
            $table->timestamp('scheduled_for')->nullable()->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('organisation_newsletters', function (Blueprint $table) {
            $table->dropColumn(['audience_type', 'audience_meta', 'scheduled_for']);
        });
    }
};
