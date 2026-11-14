<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PBDIGIT-345 task 7 (E-2; tactical design §3.10 Q-2): the table of the separate `organisation` queue connection,
 * on the CENTRAL database. Laravel's standard jobs-table shape. Payloads are identifier-only (Q-6): organisation id,
 * operation, aggregate ids; never business content or personal data. Failures go to the existing failed_jobs (§3.10.1).
 * CENTRAL migration. PRODUCTION: not before the migration-approval control exists.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisation_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisation_jobs');
    }
};
