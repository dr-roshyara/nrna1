<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_security_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 50);
            $table->foreignUuid('election_id')->constrained('elections')->cascadeOnDelete();
            $table->string('voter_slug_id', 100)->nullable();
            $table->json('network_evidence');
            $table->json('device_evidence');
            $table->string('trust_level_before', 30);
            $table->string('trust_level_after', 30);
            $table->string('policy_evaluated', 100);
            $table->string('overlay_applied', 100)->nullable();
            $table->json('policy_evaluation_sequence');
            $table->json('overlay_influence_chain');
            $table->string('trust_state_transition', 100);
            $table->string('final_constitutional_outcome', 20);
            $table->integer('retention_days')->default(730)->unsigned();
            $table->timestamp('recorded_at')->useCurrent();

            $table->index(['election_id', 'recorded_at']);
            $table->index(['election_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_security_events');
    }
};
