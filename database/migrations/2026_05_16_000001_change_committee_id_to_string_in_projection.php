<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committee_member_projection', function (Blueprint $table) {
            // Change committee_id from UUID to string to support ULID format
            $table->string('committee_id')->change();
        });
    }

    public function down(): void
    {
        Schema::table('committee_member_projection', function (Blueprint $table) {
            $table->uuid('committee_id')->change();
        });
    }
};
