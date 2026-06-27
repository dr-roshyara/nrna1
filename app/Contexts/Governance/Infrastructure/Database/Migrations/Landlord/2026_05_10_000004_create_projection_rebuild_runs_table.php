<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projection_rebuild_runs', function (Blueprint $table) {
            $table->string('generation', 32)->primary();
            $table->string('tenant_id', 26)->nullable()->index();
            $table->dateTime('started_at');
            $table->dateTime('completed_at')->nullable();
            $table->string('status', 16); // running, completed, failed, rolled_back
            $table->unsignedInteger('total_committees')->default(0);
            $table->unsignedInteger('rebuilt_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->text('error_log')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projection_rebuild_runs');
    }
};
