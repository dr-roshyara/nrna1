<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('governance_level_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id', 36);
            $table->unsignedTinyInteger('level'); // 0–10
            $table->string('committee_name', 100);
            $table->string('committee_code', 20);
            $table->string('geo_name', 100);
            $table->string('geo_code', 20);
            $table->string('geo_parent_code', 20)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('created_by', 36)->nullable();
            $table->string('updated_by', 36)->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'level']);
            $table->index(['tenant_id', 'is_active', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('governance_level_definitions');
    }
};
