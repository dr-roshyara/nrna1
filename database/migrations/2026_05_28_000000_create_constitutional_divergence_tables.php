<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Constitutional Evidence Infrastructure
     *
     * Creates tables for D.0.2 constitutional convergence aggregation.
     * NOT security tables. Pure constitutional archaeology.
     */
    public function up(): void
    {
        // SovereigntyDivergenceSummary: Aggregate constitutional evidence
        Schema::create('sovereignty_divergence_summaries', function (Blueprint $table) {
            $table->id();
            $table->uuid('election_id')->nullable()->index();
            $table->dateTime('observation_period_start');
            $table->dateTime('observation_period_end');
            $table->string('feature_flag_name');      // voting_security.enable_legacy_middleware_ip_check
            $table->string('feature_flag_value');     // enabled or disabled
            $table->integer('total_divergence_events');
            $table->integer('unique_users_with_divergence');
            $table->integer('unique_routes_with_divergence');
            $table->integer('deterministic_divergence_count');
            $table->integer('nondeterministic_divergence_count');
            $table->json('by_source_finding');        // { H.1: 150, H.2: 45, H.3: 12 }
            $table->json('by_outcome_type');          // { ALLOWED_vs_DENIED: 120, IP_MISMATCH: 87 }
            $table->json('by_route');                 // { /vote/create: 50, /vote/submit: 107 }
            $table->enum('constitutional_equivalence_status', ['EQUIVALENT', 'DETERMINISTICALLY_DIVERGENT', 'DIVERGENT']);
            $table->integer('equivalence_confidence_pct')->default(0); // 0-100
            $table->boolean('topology_leakage_detected')->default(false);
            $table->boolean('temporal_drift_detected')->default(false);
            $table->longText('archaeological_conclusion')->nullable();
            $table->boolean('safe_to_retire_procedural')->default(false);
            $table->dateTime('authorized_at')->nullable();
            $table->uuid('authorized_by_user_id')->nullable();
            $table->boolean('is_finalized')->default(false);
            $table->timestamps();

            $table->index(['election_id', 'observation_period_start']);
            $table->index('constitutional_equivalence_status');
            $table->index('is_finalized');
        });

        // DivergenceObservationWindow: Preserve replay-relevant dimensions
        Schema::create('divergence_observation_windows', function (Blueprint $table) {
            $table->id();
            $table->uuid('election_id')->nullable()->index();
            $table->uuid('user_id')->nullable()->index();
            $table->string('route')->nullable();      // /vote/create, /vote/submit, etc
            $table->dateTime('timestamp_window_start');
            $table->dateTime('timestamp_window_end');
            $table->string('registered_ip')->nullable();     // What procedural path saw
            $table->string('current_ip')->nullable();        // What request contained
            $table->text('user_agent')->nullable();
            $table->string('procedural_outcome')->nullable();     // ALLOWED, BLOCKED, etc
            $table->string('constitutional_outcome')->nullable();  // What PolicySequence returned
            $table->string('divergence_type')->nullable();        // IP_MISMATCH, ALLOWED_vs_DENIED, etc
            $table->string('source_finding')->nullable();         // H.1, H.2, H.3, H.4, etc
            $table->integer('divergence_frequency')->default(1);  // How many times observed
            $table->boolean('is_deterministic')->default(true);   // Consistent divergence?
            $table->longText('replay_notes')->nullable();         // Archaeological context
            $table->unsignedBigInteger('observation_window_id')->index();
            $table->timestamps();

            $table->foreign('observation_window_id')
                ->references('id')
                ->on('sovereignty_divergence_summaries')
                ->onDelete('cascade');

            $table->index(['election_id', 'user_id']);
            $table->index(['source_finding', 'divergence_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divergence_observation_windows');
        Schema::dropIfExists('sovereignty_divergence_summaries');
    }
};
