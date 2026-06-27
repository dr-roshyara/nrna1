<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committee_structures', function (Blueprint $table) {
            $table->uuid('activated_by')->nullable()->after('status');
            $table->timestamp('activated_at')->nullable()->after('activated_by');
            $table->text('activation_reason')->nullable()->after('activated_at');
            $table->json('activation_metadata')->nullable()->after('activation_reason');
        });
    }

    public function down(): void
    {
        Schema::table('committee_structures', function (Blueprint $table) {
            $table->dropColumn(['activated_by', 'activated_at', 'activation_reason', 'activation_metadata']);
        });
    }
};
