<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidacies', function (Blueprint $table) {
            $table->string('image_path_1')->nullable()->after('status');
            $table->string('image_path_2')->nullable()->after('image_path_1');
            $table->string('image_path_3')->nullable()->after('image_path_2');
        });
    }

    public function down(): void
    {
        Schema::table('candidacies', function (Blueprint $table) {
            $table->dropColumn(['image_path_1', 'image_path_2', 'image_path_3']);
        });
    }
};
