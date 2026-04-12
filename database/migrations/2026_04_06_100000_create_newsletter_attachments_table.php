<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_newsletter_id')
                  ->constrained('organisation_newsletters')
                  ->cascadeOnDelete();
            $table->string('original_name');
            $table->string('stored_path');
            $table->string('mime_type');
            $table->unsignedInteger('size'); // bytes
            $table->uuid('uploaded_by');
            $table->timestamps();
            $table->softDeletes();

            $table->index('organisation_newsletter_id');
            $table->foreign('uploaded_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_attachments');
    }
};
