<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->enum('suspension_status', ['none', 'proposed', 'confirmed'])
                ->default('none')
                ->after('status');
            $table->string('suspension_proposed_by')->nullable()->after('suspension_status');
            $table->timestamp('suspension_proposed_at')->nullable()->after('suspension_proposed_by');
        });
    }

    public function down(): void
    {
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->dropColumn(['suspension_status', 'suspension_proposed_by', 'suspension_proposed_at']);
        });
    }
};
