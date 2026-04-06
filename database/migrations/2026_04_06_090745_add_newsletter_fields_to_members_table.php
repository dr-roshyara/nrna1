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
        Schema::table('members', function (Blueprint $table) {
            $table->timestamp('newsletter_unsubscribed_at')->nullable()->after('updated_by');
            $table->string('newsletter_unsubscribe_token', 64)->nullable()->unique()->after('newsletter_unsubscribed_at');
            $table->timestamp('newsletter_bounced_at')->nullable()->after('newsletter_unsubscribe_token');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['newsletter_unsubscribed_at', 'newsletter_unsubscribe_token', 'newsletter_bounced_at']);
        });
    }
};
