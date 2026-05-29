<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            // Frozen constitutional law snapshot at election creation
            $table->json('security_articles_snapshot')->nullable()->after('trust_overlay_reason');

            // Version identifier for future amendment tracking (currently D.2.5)
            $table->string('security_articles_version')->default('D.2.5')->after('security_articles_snapshot');

            // SHA-256 hash for tamper detection and replay verification
            $table->string('constitutional_hash')->nullable()->after('security_articles_version');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn([
                'security_articles_snapshot',
                'security_articles_version',
                'constitutional_hash',
            ]);
        });
    }
};
