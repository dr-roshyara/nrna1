<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('constitutional_committees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('jurisdiction_scope');
            $table->string('jurisdiction_reference')->nullable();
            $table->timestamp('established_at');
            $table->timestamps();

            $table->index('jurisdiction_scope');
            $table->index(['jurisdiction_scope', 'jurisdiction_reference']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('constitutional_committees');
    }
};
