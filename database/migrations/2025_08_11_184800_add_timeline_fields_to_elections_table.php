<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('elections', function (Blueprint $table) {
            // Timeline Management Fields
            $table->string('timezone')->default('UTC')->after('description');

            
            // Registration Phase
            $table->timestamp('registration_start')->nullable()->after('timezone');
            $table->timestamp('registration_end')->nullable()->after('registration_start');
            
            // Nomination Phase  
            $table->timestamp('candidate_nomination_start')->nullable()->after('registration_end');
            $table->timestamp('candidate_nomination_end')->nullable()->after('candidate_nomination_start');
            
            // Result Publication
            $table->timestamp('result_publication_date')->nullable()->after('authorization_deadline');
            
            // Phase Management
            $table->timestamp('phase_changed_at')->nullable()->after('phase');
            $table->unsignedBigInteger('phase_changed_by')->nullable()->after('phase_changed_at');
            $table->string('phase_change_reason')->nullable()->after('phase_changed_by');
            
            // Automation Settings
            $table->boolean('auto_phase_transition')->default(true)->after('phase_change_reason');
            $table->boolean('notification_enabled')->default(true)->after('auto_phase_transition');
            $table->boolean('public_registration')->default(true)->after('notification_enabled');
            
            // Add foreign key for phase_changed_by
            $table->foreign('phase_changed_by')->references('id')->on('users')->onDelete('set null');
            
            // Add indexes for performance
            $table->index(['status', 'phase']);
            $table->index(['registration_start', 'registration_end']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('elections', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['phase_changed_by']);
            
            // Drop indexes
            $table->dropIndex(['status', 'phase']);
            $table->dropIndex(['registration_start', 'registration_end']);
            $table->dropIndex(['start_date', 'end_date']);
            
            // Drop columns
            $table->dropColumn([
                'timezone',
                'registration_start',
                'registration_end', 
                'candidate_nomination_start',
                'candidate_nomination_end',
                'result_publication_date',
                'phase_changed_at',
                'phase_changed_by',
                'phase_change_reason',
                'auto_phase_transition',
                'notification_enabled',
                'public_registration',
            ]);
        });
    }
};