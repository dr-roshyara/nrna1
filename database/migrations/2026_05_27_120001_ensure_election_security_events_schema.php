<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('election_security_events')) {
            Schema::table('election_security_events', function (Blueprint $table) {
                if (!Schema::hasColumn('election_security_events', 'audit_context')) {
                    $table->json('audit_context')->default('{}')->after('voter_slug_id');
                }
                if (!Schema::hasColumn('election_security_events', 'trust_level_before')) {
                    $table->string('trust_level_before', 30)->nullable()->after('audit_context');
                }
                if (!Schema::hasColumn('election_security_events', 'trust_level_after')) {
                    $table->string('trust_level_after', 30)->nullable()->after('trust_level_before');
                }
                if (!Schema::hasColumn('election_security_events', 'policy_sequence')) {
                    $table->json('policy_sequence')->nullable()->after('trust_level_after');
                }
                if (!Schema::hasColumn('election_security_events', 'overlay_signal_type')) {
                    $table->string('overlay_signal_type', 50)->nullable()->after('policy_sequence');
                }
                if (!Schema::hasColumn('election_security_events', 'constitutional_outcome')) {
                    $table->string('constitutional_outcome', 30)->after('overlay_signal_type');
                }
                if (!Schema::hasColumn('election_security_events', 'recorded_at')) {
                    $table->timestamp('recorded_at')->useCurrent()->after('constitutional_outcome');
                }

                if (!Schema::hasIndex('election_security_events', 'election_security_events_election_id_recorded_at_index')) {
                    $table->index(['election_id', 'recorded_at']);
                }
                if (!Schema::hasIndex('election_security_events', 'election_security_events_election_id_event_type_index')) {
                    $table->index(['election_id', 'event_type']);
                }
            });
        } else {
            Schema::create('election_security_events', function (Blueprint $table) {
                $table->id();
                $table->string('event_type', 50);
                $table->unsignedBigInteger('election_id');
                $table->string('voter_slug_id', 100)->nullable();
                $table->json('audit_context')->default('{}');
                $table->string('trust_level_before', 30)->nullable();
                $table->string('trust_level_after', 30)->nullable();
                $table->json('policy_sequence')->nullable();
                $table->string('overlay_signal_type', 50)->nullable();
                $table->string('constitutional_outcome', 30);
                $table->timestamp('recorded_at')->useCurrent();

                $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
                $table->index(['election_id', 'recorded_at']);
                $table->index(['election_id', 'event_type']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('election_security_events')) {
            Schema::table('election_security_events', function (Blueprint $table) {
                if (Schema::hasColumn('election_security_events', 'audit_context')) {
                    $table->dropColumn('audit_context');
                }
                if (Schema::hasColumn('election_security_events', 'trust_level_before')) {
                    $table->dropColumn('trust_level_before');
                }
                if (Schema::hasColumn('election_security_events', 'trust_level_after')) {
                    $table->dropColumn('trust_level_after');
                }
                if (Schema::hasColumn('election_security_events', 'policy_sequence')) {
                    $table->dropColumn('policy_sequence');
                }
                if (Schema::hasColumn('election_security_events', 'overlay_signal_type')) {
                    $table->dropColumn('overlay_signal_type');
                }
                if (Schema::hasColumn('election_security_events', 'constitutional_outcome')) {
                    $table->dropColumn('constitutional_outcome');
                }
                if (Schema::hasColumn('election_security_events', 'recorded_at')) {
                    $table->dropColumn('recorded_at');
                }
            });
        }
    }
};
