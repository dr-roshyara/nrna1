
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Election Client Access Management Table
 * 
 * This migration creates a comprehensive table to manage client access
 * to election databases. It supports fine-grained access control,
 * audit trails, and time-limited access grants.
 * 
 * Features:
 * - Many-to-many relationship between users and elections
 * - Time-limited access with expiration
 * - Granular permission control
 * - Complete audit trail
 * - IP-based access restrictions
 * - Access usage tracking
 * 
 * @package Database\Migrations
 * @version 2.0.0
 */
class CreateElectionClientAccessTable extends Migration
{
    /**
     * Run the migrations to create the client access management table
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('election_client_access', function (Blueprint $table) {
            // Primary identification
            $table->id()->comment('Unique identifier for access grant');
            
            // Relationship fields
            $table->foreignId('election_id')
                ->constrained('elections')
                ->onDelete('cascade')
                ->comment('Reference to the election');
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('Reference to the user being granted access');
            
            // Access Management
            $table->timestamp('access_granted_at')->default(now())
                ->comment('When access was initially granted');
            
            $table->timestamp('access_expires_at')->nullable()
                ->comment('When access expires (null = permanent)');
            
            $table->timestamp('access_revoked_at')->nullable()
                ->comment('When access was revoked (null = active)');
            
            $table->string('access_level')->default('read_only')
                ->comment('Level of access granted (read_only, read_write, admin)');
            
            // Grant Management
            $table->foreignId('granted_by')
                ->constrained('users')
                ->onDelete('restrict')
                ->comment('User who granted this access');
            
            $table->foreignId('revoked_by')->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('User who revoked this access');
            
            $table->text('grant_reason')->nullable()
                ->comment('Reason for granting access');
            
            $table->text('revocation_reason')->nullable()
                ->comment('Reason for revoking access');
            
            // Access Restrictions
            $table->json('allowed_ip_addresses')->nullable()
                ->comment('Specific IP addresses allowed for this user');
            
            $table->json('allowed_operations')->nullable()
                ->comment('Specific database operations allowed');
            
            $table->json('restricted_tables')->nullable()
                ->comment('Tables this user cannot access');
            
            $table->integer('max_concurrent_connections')->default(5)
                ->comment('Maximum concurrent database connections');
            
            $table->integer('max_daily_queries')->default(10000)
                ->comment('Maximum queries per day');
            
            // Usage Tracking
            $table->timestamp('last_accessed_at')->nullable()
                ->comment('When database was last accessed by this user');
            
            $table->string('last_access_ip')->nullable()
                ->comment('IP address of last access');
            
            $table->integer('total_connections')->default(0)
                ->comment('Total number of database connections made');
            
            $table->integer('total_queries')->default(0)
                ->comment('Total number of queries executed');
            
            $table->timestamp('last_query_at')->nullable()
                ->comment('When last query was executed');
            
            // Security and Audit
            $table->boolean('is_active')->default(true)
                ->comment('Whether this access grant is currently active');
            
            $table->json('access_log')->nullable()
                ->comment('Log of access attempts and activities');
            
            $table->string('access_token')->nullable()
                ->comment('Unique token for API-based access');
            
            $table->timestamp('token_expires_at')->nullable()
                ->comment('When the access token expires');
            
            // Notification Settings
            $table->boolean('notify_on_access')->default(false)
                ->comment('Send notification when user accesses database');
            
            $table->boolean('notify_on_expiry')->default(true)
                ->comment('Send notification when access is about to expire');
            
            $table->integer('expiry_warning_days')->default(7)
                ->comment('Days before expiry to send warning notification');
            
            // Standard timestamps
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['election_id', 'user_id'], 'election_client_access_election_user_index');
            $table->index(['user_id', 'is_active'], 'election_client_access_user_active_index');
            $table->index(['access_expires_at', 'is_active'], 'election_client_access_expiry_active_index');
            $table->index('access_granted_at', 'election_client_access_granted_at_index');
            $table->index('last_accessed_at', 'election_client_access_last_accessed_index');
            $table->index(['election_id', 'access_level'], 'election_client_access_election_level_index');
            $table->index('granted_by', 'election_client_access_granted_by_index');
            
            // Unique constraint to prevent duplicate active grants
            $table->unique(['election_id', 'user_id'], 'election_client_access_unique_active');
        });
    }

    /**
     * Reverse the migrations
     * 
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('election_client_access');
    }
}
