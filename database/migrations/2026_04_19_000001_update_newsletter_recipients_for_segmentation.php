<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('newsletter_recipients', function (Blueprint $table) {
            $table->uuid('member_id')->nullable()->change();
            $table->uuid('user_id')->nullable()->after('member_id');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamp('opened_at')->nullable()->after('sent_at');
            $table->timestamp('clicked_at')->nullable()->after('opened_at');
            $table->timestamp('consent_given_at')->nullable()->after('clicked_at');
            $table->string('consent_source')->nullable()->after('consent_given_at');
        });
    }

    public function down(): void
    {
        Schema::table('newsletter_recipients', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'opened_at', 'clicked_at', 'consent_given_at', 'consent_source']);
        });
    }
};
