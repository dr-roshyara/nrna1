<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectionDatabaseLogsTable extends Migration
{
    /**
     * Run the migrations to create the database logs table
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('election_database_logs', function (Blueprint $table) {
            // Primary identification
            $table->id()->comment('Unique log entry identifier');
            
            // Election and User Context
            $table->foreignId('election_id')
                ->constrained('elections')
                ->onDelete('cascade')
                ->comment('Reference to the election');
            
            $table->foreignId('user_id')->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('User who performed the operation');
            
            $table->string('connection_type', 20)
                ->comment('Type of connection (admin, client, system)');
            
            // Connection Details
            $table->string('connection_name')
                ->comment('Laravel database connection name used');
            
            $table->string('database_name')
                ->comment('Actual database name accessed');
            
            $table->timestamp('connection_started_at')
                ->comment('When the database connection was established');
            
            $table->timestamp('connection_ended_at')->nullable()
                ->comment('When the database connection was closed');
            
            $table->integer('connection_duration_ms')->nullable()
                ->comment('Duration of connection in milliseconds');
            
            // Operation Details
            $table->string('operation_type', 50)
                ->comment('Type of operation (SELECT, INSERT, UPDATE, DELETE, etc.)');
            
            $table->text('query_hash')->nullable()
                ->comment('Hash of the executed query for analysis');
            
            $table->text('query_summary')->nullable()
                ->comment('Summary of the query (table, operation)');
            
            $table->integer('query_execution_time_ms')->nullable()
                ->comment('Query execution time in milliseconds');
            
            $table->integer('rows_affected')->nullable()
                ->comment('Number of rows affected by the operation');
            
            // Request Context
            $table->string('ip_address', 45)
                ->comment('IP address of the client');
            
            $table->text('user_agent')->nullable()
                ->comment('User agent string from the request');
            
            $table->string('request_id')->nullable()
                ->comment('Unique request identifier for correlation');
            
            $table->string('session_id')->nullable()
                ->comment('Session identifier');
            
            $table->string('route_name')->nullable()
                ->comment('Laravel route name that triggered the operation');
            
            // Security and Status
            $table->string('status', 20)->default('success')
                ->comment('Operation status (success, error, unauthorized)');
            
            $table->text('error_message')->nullable()
                ->comment('Error message if operation failed');
            
            $table->string('error_code')->nullable()
                ->comment('Error code for categorization');
            
            $table->boolean('is_suspicious')->default(false)
                ->comment('Whether this operation was flagged as suspicious');
            
            $table->text('security_notes')->nullable()
                ->comment('Security-related notes or flags');
            
            // Performance Metrics
            $table->integer('memory_usage_bytes')->nullable()
                ->comment('Memory usage during operation');
            
            $table->float('cpu_usage_percent')->nullable()
                ->comment('CPU usage percentage during operation');
            
            $table->json('performance_metrics')->nullable()
                ->comment('Additional performance metrics');
            
            // Standard timestamp
            $table->timestamp('created_at')->default(now())
                ->comment('When this log entry was created');
            
            // Indexes for efficient querying
            $table->index(['election_id', 'created_at'], 'election_database_logs_election_date_index');
            $table->index(['user_id', 'created_at'], 'election_database_logs_user_date_index');
            $table->index(['connection_type', 'created_at'], 'election_database_logs_type_date_index');
            $table->index(['operation_type', 'created_at'], 'election_database_logs_operation_date_index');
            $table->index(['status', 'created_at'], 'election_database_logs_status_date_index');
            $table->index(['is_suspicious', 'created_at'], 'election_database_logs_suspicious_date_index');
            $table->index('ip_address', 'election_database_logs_ip_index');
            $table->index('request_id', 'election_database_logs_request_index');
            $table->index('query_execution_time_ms', 'election_database_logs_performance_index');
        });
    }

    /**
     * Reverse the migrations
     * 
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('election_database_logs');
    }
}
