<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientAccessFieldsToElectionsTable extends Migration
{
    /**
     * Run the migrations to add multi-database client access support
     * 
     * This method adds all necessary fields for client database access
     * while maintaining backward compatibility with existing data.
     * 
     * @return void
     */
    public function up()
    {
        Schema::table('elections', function (Blueprint $table) {
            // Client Database Access Management
            $table->string('client_database_username')->nullable()->after('database_created_at')
                ->comment('Database username for client read-only access');
            
            $table->text('client_database_password')->nullable()->after('client_database_username')
                ->comment('Encrypted password for client database access');
            
            $table->timestamp('client_access_granted_at')->nullable()->after('client_database_password')
                ->comment('When client database access was first granted');
            
            $table->timestamp('client_access_revoked_at')->nullable()->after('client_access_granted_at')
                ->comment('When client database access was revoked (null = active)');
            
            $table->bigInteger('client_access_count')->default(0)->after('client_access_revoked_at')
                ->comment('Number of active client access grants');
            
            // Database Status and Management
            $table->enum('database_status', [
                'pending',      // Database creation requested but not started
                'creating',     // Database creation in progress
                'created',      // Database created but schema not deployed
                'migrating',    // Database schema deployment in progress
                'migrated',     // Schema deployed but not seeded
                'seeding',      // Default data insertion in progress
                'ready',        // Fully operational database
                'maintenance',  // Database under maintenance
                'archived',     // Election completed, database archived
                'failed',       // Database creation or setup failed
                'dropped'       // Database has been permanently deleted
            ])->default('pending')->after('client_access_count')
                ->comment('Current status of the election database');
            
            $table->text('database_status_message')->nullable()->after('database_status')
                ->comment('Detailed message about current database status');
            
            $table->timestamp('database_last_backup')->nullable()->after('database_status_message')
                ->comment('When the database was last backed up');
            
            // Election Organization and Routing
            $table->string('constituency')->nullable()->after('database_last_backup')
                ->comment('Electoral constituency (europe, americas, asia_pacific, etc.)');
            
            $table->string('slug')->nullable()->after('constituency')
                ->comment('URL-friendly identifier for election routing');
            
            $table->string('subdomain')->nullable()->after('slug')
                ->comment('Subdomain for election-specific access (optional)');
            
            $table->string('election_code', 20)->nullable()->after('subdomain')
                ->comment('Short code for election identification (e.g., EUR2024)');
            
            // Database Statistics and Monitoring
            $table->bigInteger('database_size_bytes')->nullable()->after('election_code')
                ->comment('Current size of election database in bytes');
            
            $table->integer('total_users')->default(0)->after('database_size_bytes')
                ->comment('Total number of users in this election');
            
            $table->integer('total_voters')->default(0)->after('total_users')
                ->comment('Total number of approved voters');
            
            $table->integer('total_candidates')->default(0)->after('total_voters')
                ->comment('Total number of candidates');
            
            $table->integer('total_votes')->default(0)->after('total_candidates')
                ->comment('Total number of votes cast');
            
            $table->integer('total_posts')->default(0)->after('total_votes')
                ->comment('Total number of positions/posts');
            
            // Election Metadata and Configuration
            $table->string('election_type', 50)->default('general')->after('total_posts')
                ->comment('Type of election (general, youth, women, regional)');
            
            $table->json('election_config')->nullable()->after('election_type')
                ->comment('Election-specific configuration settings');
            
            $table->string('contact_email')->nullable()->after('election_config')
                ->comment('Primary contact email for this election');
            
            $table->string('contact_phone')->nullable()->after('contact_email')
                ->comment('Primary contact phone for this election');
            
            // Access Control and Security
            $table->json('ip_whitelist')->nullable()->after('contact_phone')
                ->comment('Allowed IP addresses for admin access');
            
            $table->json('client_ip_whitelist')->nullable()->after('ip_whitelist')
                ->comment('Allowed IP addresses for client access');
            
            $table->boolean('require_2fa')->default(false)->after('client_ip_whitelist')
                ->comment('Whether 2FA is required for admin access');
            
            $table->boolean('client_access_enabled')->default(true)->after('require_2fa')
                ->comment('Whether client database access is enabled');
            
            // Archival and Cleanup
            $table->timestamp('scheduled_archive_date')->nullable()->after('client_access_enabled')
                ->comment('When this election is scheduled for archival');
            
            $table->timestamp('scheduled_cleanup_date')->nullable()->after('scheduled_archive_date')
                ->comment('When this election data should be cleaned up');
            
            $table->boolean('auto_cleanup_enabled')->default(true)->after('scheduled_cleanup_date')
                ->comment('Whether automatic cleanup is enabled');
            
            // Performance Indexes
            $table->index('database_status', 'elections_database_status_index');
            $table->index('constituency', 'elections_constituency_index');
            $table->index('slug', 'elections_slug_index');
            $table->index('subdomain', 'elections_subdomain_index');
            $table->index('election_code', 'elections_code_index');
            $table->index('election_type', 'elections_type_index');
            $table->index('client_access_enabled', 'elections_client_access_enabled_index');
            $table->index('scheduled_archive_date', 'elections_archive_date_index');
            $table->index(['status', 'database_status'], 'elections_status_composite_index');
            $table->index(['constituency', 'election_type'], 'elections_constituency_type_index');
            
            // Unique Constraints
            $table->unique('slug', 'elections_slug_unique');
            $table->unique('subdomain', 'elections_subdomain_unique');
            $table->unique('election_code', 'elections_code_unique');
        });
    }

    /**
     * Reverse the migrations
     * 
     * @return void
     */
    public function down()
    {
        Schema::table('elections', function (Blueprint $table) {
            // Drop indexes first (in reverse order)
            $table->dropIndex('elections_constituency_type_index');
            $table->dropIndex('elections_status_composite_index');
            $table->dropIndex('elections_archive_date_index');
            $table->dropIndex('elections_client_access_enabled_index');
            $table->dropIndex('elections_type_index');
            $table->dropIndex('elections_code_index');
            $table->dropIndex('elections_subdomain_index');
            $table->dropIndex('elections_slug_index');
            $table->dropIndex('elections_constituency_index');
            $table->dropIndex('elections_database_status_index');
            
            // Drop unique constraints
            $table->dropUnique('elections_code_unique');
            $table->dropUnique('elections_subdomain_unique');
            $table->dropUnique('elections_slug_unique');
            
            // Drop columns (in reverse order of addition)
            $table->dropColumn([
                'auto_cleanup_enabled',
                'scheduled_cleanup_date',
                'scheduled_archive_date',
                'client_access_enabled',
                'require_2fa',
                'client_ip_whitelist',
                'ip_whitelist',
                'contact_phone',
                'contact_email',
                'election_config',
                'election_type',
                'total_posts',
                'total_votes',
                'total_candidates',
                'total_voters',
                'total_users',
                'database_size_bytes',
                'election_code',
                'subdomain',
                'slug',
                'constituency',
                'database_last_backup',
                'database_status_message',
                'database_status',
                'client_access_count',
                'client_access_revoked_at',
                'client_access_granted_at',
                'client_database_password',
                'client_database_username'
            ]);
        });
    }

}
