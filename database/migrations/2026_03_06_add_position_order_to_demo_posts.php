<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add position_order to demo_posts if it doesn't exist
        Schema::table('demo_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('demo_posts', 'position_order')) {
                $table->integer('position_order')->default(0)->after('required_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('demo_posts', function (Blueprint $table) {
            if (Schema::hasColumn('demo_posts', 'position_order')) {
                $table->dropColumn('position_order');
            }
        });
    }
};
