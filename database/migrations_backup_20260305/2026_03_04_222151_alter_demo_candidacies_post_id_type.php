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
        Schema::table('demo_candidacies', function (Blueprint $table) {
            // Drop foreign key constraint before modifying column
            $table->dropForeign(['post_id']);
            // Change post_id column from unsignedBigInteger to string
            $table->string('post_id')->change();
            // Add foreign key to demo_posts.post_id (string)
            // Note: demo_posts.post_id may not be unique, so we cannot add foreign key constraint
            // Remove the foreign key addition for now
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_candidacies', function (Blueprint $table) {
            // Revert post_id column to unsignedBigInteger
            $table->unsignedBigInteger('post_id')->change();
            // Re-add foreign key to demo_posts.id
            $table->foreign('post_id')->references('id')->on('demo_posts')->onDelete('cascade');
        });
    }
};
