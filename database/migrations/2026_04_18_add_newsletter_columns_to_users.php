<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'newsletter_unsubscribed_at')) {
                $table->timestamp('newsletter_unsubscribed_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'newsletter_bounced_at')) {
                $table->timestamp('newsletter_bounced_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['newsletter_unsubscribed_at', 'newsletter_bounced_at']);
        });
    }
};
