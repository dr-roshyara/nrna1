<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->timestamp('suspended_at')->nullable()->after('archived_at');
            $table->string('suspended_from_state', 50)->nullable()->after('suspended_at');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn(['suspended_at', 'suspended_from_state']);
        });
    }
};
