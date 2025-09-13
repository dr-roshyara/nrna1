
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Election Database Statistics Table
 * 
 * This migration creates a table to store periodic snapshots
 * of election database statistics for monitoring and analysis.
 * 
 * Features:
 * - Periodic database statistics snapshots
 * - Performance trend analysis
 * - Capacity planning data
 * - Growth tracking
 * - Automated cleanup of old statistics
 * 
 * @package Database\Migrations
 * @version 2.0.0
 */
class CreateElectionDatabaseStatisticsTable extends Migration
{
    /**
     * Run the migrations to create the database statistics table
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('election_database_statistics', function (Blueprint $table) {
            // Primary identification
            $table->id()->comment('Unique statistics record identifier');
            
            // Election Context
            $table->foreignId('election_id')
                ->constrained('elections')
                ->onDelete('cascade')
                ->comment('Reference to the election');
            
            $table->string('database_name')
                ->comment('Name of the election database');
            
            $table->timestamp('snapshot_date')
                ->comment('When this statistics snapshot was taken');
            
            // Database Size and Storage
            $table->bigInteger('database_size_bytes')
                ->comment('Total database size in bytes');
            
            $table->bigInteger('data_size_bytes')
                ->comment('Size of actual data in bytes');
            
            $table->bigInteger('index_size_bytes')
                ->comment('Size of indexes in bytes');
            
            $table->bigInteger('free_space_bytes')->nullable()
                ->comment('Available free space in bytes');
            
            // Table Statistics
            $table->json('table_row_counts')
                ->comment('Row counts for each table');
            
            $table->json('table_sizes')
                ->comment('Size information for each table');
            
            $table->json('index_statistics')
                ->comment('Index usage and efficiency statistics');
            
            // Performance Metrics
            $table->integer('average_query_time_ms')
                ->comment('Average query execution time in milliseconds');
            
            $table->integer('slow_query_count')
                ->comment('Number of slow queries (>1000ms) in last period');
            
            $table->integer('total_queries_period')
                ->comment('Total queries executed in the measurement period');
            
            $table->integer('failed_queries_period')
                ->comment('Number of failed queries in the measurement period');
            
            // Connection Statistics
            $table->integer('max_connections_used')
                ->comment('Maximum number of concurrent connections');
            
            $table->integer('average_connections')
                ->comment('Average number of concurrent connections');
            
            $table->integer('total_connections_period')
                ->comment('Total connections made during the period');
            
            $table->integer('failed_connections_period')
                ->comment('Number of failed connection attempts');
            
            // Activity Statistics
            $table->integer('active_users_period')
                ->comment('Number of unique users active during period');
            
            $table->timestamp('last_write_operation')
                ->comment('When the last write operation occurred');
            
            $table->timestamp('last_read_operation')
                ->comment('When the last read operation occurred');
            
            $table->integer('writes_per_hour')
                ->comment('Average write operations per hour');
            
            $table->integer('reads_per_hour')
                ->comment('Average read operations per hour');
            
            // Health Indicators
            $table->string('health_status', 20)->default('healthy')
                ->comment('Overall database health status');
            
            $table->json('health_checks')
                ->comment('Results of various health checks');
            
            $table->json('warnings')->nullable()
                ->comment('Any warnings or issues detected');
            
            $table->float('performance_score')->nullable()
                ->comment('Overall performance score (0-100)');
            
            // Backup and Maintenance
            $table->timestamp('last_backup_date')->nullable()
                ->comment('When the database was last backed up');
            
            $table->bigInteger('backup_size_bytes')->nullable()
                ->comment('Size of the last backup in bytes');
            
            $table->timestamp('last_maintenance_date')->nullable()
                ->comment('When maintenance was last performed');
            
            $table->json('maintenance_log')->nullable()
                ->comment('Log of maintenance activities');
            
            // Growth Projections
            $table->float('growth_rate_percent')->nullable()
                ->comment('Calculated growth rate percentage');
            
            $table->timestamp('projected_full_date')->nullable()
                ->comment('Projected date when database will be full');
            
            $table->json('capacity_projections')->nullable()
                ->comment('Various capacity and growth projections');
            
            // Standard timestamps
            $table->timestamps();
            
            // Indexes for efficient querying
            $table->index(['election_id', 'snapshot_date'], 'election_database_stats_election_date_index');
            $table->index('snapshot_date', 'election_database_stats_date_index');
            $table->index('health_status', 'election_database_stats_health_index');
            $table->index('database_size_bytes', 'election_database_stats_size_index');
            $table->index(['election_id', 'health_status'], 'election_database_stats_election_health_index');
            
            // Unique constraint to prevent duplicate snapshots
            $table->unique(['election_id', 'snapshot_date'], 'election_database_stats_unique_snapshot');
        });
    }

    /**
     * Reverse the migrations
     * 
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('election_database_statistics');
    }
}