<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidacy_applications', function (Blueprint $table) {
            $table->uuid('candidacy_id')->nullable()->after('post_id');
            $table->foreign('candidacy_id')->references('id')->on('candidacies')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('candidacy_applications', function (Blueprint $table) {
            $table->dropForeign(['candidacy_id']);
            $table->dropColumn('candidacy_id');
        });
    }
};
