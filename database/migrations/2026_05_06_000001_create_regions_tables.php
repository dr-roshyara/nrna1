<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('region_country', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->char('country_code', 2);
            $table->foreign('country_code')->references('code')->on('countries')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['region_id', 'country_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('region_country');
        Schema::dropIfExists('regions');
    }
};
