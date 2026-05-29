<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // C5: Deprecate final_constitutional_outcome + constitutional_outcome in favor of evaluation_summary
        DB::statement('ALTER TABLE election_security_events ALTER COLUMN final_constitutional_outcome DROP NOT NULL');
        if (Schema::hasColumn('election_security_events', 'constitutional_outcome')) {
            DB::statement('ALTER TABLE election_security_events ALTER COLUMN constitutional_outcome DROP NOT NULL');
        }

        Schema::table('election_security_events', function (Blueprint $table) {
            if (!Schema::hasColumn('election_security_events', 'evaluation_summary')) {
                $table->json('evaluation_summary')->nullable()->after('trust_state_transition');
            }
            if (!Schema::hasColumn('election_security_events', 'constitution_schema_version')) {
                $table->string('constitution_schema_version', 10)->nullable()->default('D.2.5')->after('evaluation_summary');
            }
            if (!Schema::hasColumn('election_security_events', 'interpreter_version')) {
                $table->string('interpreter_version', 10)->nullable()->default('E.2')->after('constitution_schema_version');
            }
            if (!Schema::hasColumn('election_security_events', 'evaluation_protocol_version')) {
                $table->string('evaluation_protocol_version', 10)->nullable()->default('1.0')->after('interpreter_version');
            }
        });
    }

    public function down(): void
    {
        Schema::table('election_security_events', function (Blueprint $table) {
            $table->dropColumn(['evaluation_summary', 'constitution_schema_version', 'interpreter_version', 'evaluation_protocol_version']);
        });
    }
};
