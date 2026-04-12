<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_applications', function (Blueprint $table) {
            // Allow null for public applications (no user account yet)
            $table->foreignUuid('user_id')->nullable()->change();
            // Allow null for public applications (admin assigns type at approval)
            $table->foreignUuid('membership_type_id')->nullable()->change();
            // Store applicant email for public applications
            $table->string('applicant_email')->nullable()->after('user_id');
            // Track whether application came from public form or internal flow
            $table->enum('source', ['internal', 'public'])->default('internal')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('membership_applications', function (Blueprint $table) {
            $table->dropColumn(['applicant_email', 'source']);
            $table->foreignUuid('user_id')->nullable(false)->change();
            $table->foreignUuid('membership_type_id')->nullable(false)->change();
        });
    }
};
