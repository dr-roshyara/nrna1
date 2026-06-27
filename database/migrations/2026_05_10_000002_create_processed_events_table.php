<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('processed_events', function (Blueprint $table) {
            $table->string('event_id', 64)->primary();
            $table->string('event_type', 128);
            $table->string('committee_id', 26)->nullable();
            $table->dateTime('processed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processed_events');
    }
};
