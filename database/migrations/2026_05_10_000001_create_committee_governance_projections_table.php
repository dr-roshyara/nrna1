<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_governance_projections', function (Blueprint $table) {
            $table->string('committee_id', 26)->primary();
            $table->unsignedInteger('projection_version')->default(1);
            $table->string('last_event_id', 64)->nullable();
            $table->dateTime('last_event_occurred_at')->nullable();
            $table->string('operational_state', 32);
            $table->string('temporal_state', 32);
            $table->string('legitimacy', 32);
            $table->dateTime('evaluated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_governance_projections');
    }
};
