<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('governance_decisions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Constitutional decision identity
            $table->timestamp('decided_at');
            $table->string('capability_type');
            $table->string('winning_authority_id')->nullable();
            $table->string('legitimacy');
            $table->string('constitutional_scope')->nullable();

            // Constitutional reason and arbitration trace (canonical JSON)
            $table->json('constitutional_reason_json');
            $table->json('arbitration_trace_json');

            // Snapshot metadata (doctrine & policy versions at decision time)
            $table->string('schema_version');
            $table->string('doctrine_version');
            $table->string('legitimacy_policy_version');
            $table->string('arbitration_policy_version');
            $table->string('replay_engine_version');
            $table->string('replay_compatibility_version');
            $table->timestamp('metadata_generated_at');

            // Integrity seal
            $table->string('integrity_hash');

            $table->timestamps();

            // Query indexes
            $table->index('decided_at');
            $table->index(['legitimacy', 'decided_at']);
            $table->index('doctrine_version');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('governance_decisions');
    }
};
