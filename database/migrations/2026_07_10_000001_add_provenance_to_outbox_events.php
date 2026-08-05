<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * F-PB006-2 (ADR-MP-06 / D-1): additive provenance columns on the outbox so the
 * constitutional audit chain (one CorrelationId per loop; CausationId linking each hop)
 * is queryable end-to-end (IT-8). Nullable — rows may predate provenance stamping, and a
 * chain-starting producer has no cause. Indexed for IT-8 queryability.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('outbox_events', function (Blueprint $table) {
            $table->uuid('correlation_id')->nullable()->index()->after('payload');
            $table->uuid('causation_id')->nullable()->index()->after('correlation_id');
        });
    }

    public function down(): void
    {
        Schema::table('outbox_events', function (Blueprint $table) {
            $table->dropColumn(['correlation_id', 'causation_id']);
        });
    }
};
