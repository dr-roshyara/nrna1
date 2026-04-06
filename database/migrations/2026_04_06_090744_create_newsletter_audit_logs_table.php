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
        Schema::create('newsletter_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_newsletter_id')->constrained('organisation_newsletters');
            $table->uuid('organisation_id');
            $table->uuid('actor_user_id');
            $table->string('action'); // created|dispatched|cancelled|completed|failed
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['organisation_newsletter_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_audit_logs');
    }
};
