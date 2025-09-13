<?php

namespace App\Models;

use App\Services\ElectionContextService;
use App\Services\UnauthorizedDatabaseOperationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Exception;

/**
 * ElectionAwareModel - Base Model for Multi-Database Election Architecture
 * 
 * This abstract base class provides transparent database routing capabilities for models
 * in a multi-tenant election system where each election operates in its own database.
 * 
 * Key Features:
 * - Automatic database connection resolution based on election context
 * - Support for both administrative and client access patterns
 * - Query operation validation for security compliance
 * - Comprehensive audit trail integration
 * - Transparent operation for existing application code
 * 
 * Security Considerations:
 * - Validates database operations against access level (admin vs client)
 * - Prevents unauthorized cross-election data access
 * - Enforces read-only restrictions for client connections
 * - Maintains comprehensive audit logs
 * 
 * Performance Considerations:
 * - Caches connection resolution to minimize overhead
 * - Lazy-loads schema validation to reduce query count
 * - Optimizes connection reuse across model instances
 * 
 * @package App\Models
 * @author NRNA Election System
 * @version 2.0.0
 */
abstract class ElectionAwareModel extends Model
{
    /**
     * Cache for connection name resolution to improve performance
     * 
     * @var string|null
     */
    private static ?string $cachedConnectionName = null;
    
    /**
     * Cache for connection type validation
     * 
     * @var string|null
     */
    private static ?string $cachedConnectionType = null;
    
    /**
     * Cache for table schema validation results
     * 
     * @var array
     */
    private static array $schemaCache = [];
    
    /**
     * Attributes that should be hidden from serialization for security
     * Includes election context fields that may contain sensitive audit data
     * 
     * @var array
     */
    protected $hidden = [
        'election_context_id', 
        'election_context_name',
        'election_context_type',
        'election_context_user_id'
    ];
    
    /**
     * Attributes that should be cast to specific types
     * 
     * @var array
     */
    protected $casts = [
        'election_context_id' => 'integer',
        'election_context_type' => 'string',
        'election_context_user_id' => 'integer'
    ];
    
    /**
     * Get the database connection name for this model instance
     * 
     * This method provides the core routing logic for multi-database architecture.
     * It determines which database connection to use based on the current election
     * context, falling back to the default connection when no context is set.
     * 
     * Connection Resolution Priority:
     * 1. Election context connection (if set)
     * 2. Model-specific connection (if defined)
     * 3. Default application connection
     * 
     * @return string The database connection name to use
     */
    public function getConnectionName()
    {
        // Use cached connection if available and context hasn't changed
        if (self::$cachedConnectionName && self::$cachedConnectionType === ElectionContextService::getConnectionType()) {
            return self::$cachedConnectionName;
        }
        
        // Attempt to resolve connection from election context
        if (ElectionContextService::isElectionContextSet()) {
            $connectionName = ElectionContextService::getElectionConnection();
            
            if ($connectionName && $this->validateConnection($connectionName)) {
                // Cache successful connection resolution
                self::$cachedConnectionName = $connectionName;
                self::$cachedConnectionType = ElectionContextService::getConnectionType();
                
                return $connectionName;
            }
        }
        
        // Clear cache if election context resolution failed
        self::$cachedConnectionName = null;
        self::$cachedConnectionType = null;
        
        // Fallback to model-specific or default connection
        return $this->connection ?? config('database.default');
    }
    
    /**
     * Create a new Eloquent query builder for the model
     * 
     * Overrides the default query builder creation to ensure that all queries
     * are executed against the correct database connection as determined by
     * the election context.
     * 
     * @param QueryBuilder $query The base query builder instance
     * @return Builder The configured Eloquent query builder
     */
    public function newEloquentBuilder($query)
    {
        $expectedConnection = $this->getConnectionName();
        $currentConnection = $query->getConnection()->getName();
        
        // Recreate query builder if connection differs
        if ($expectedConnection !== $currentConnection) {
            $query = $this->newBaseQueryBuilder();
            $query->from($this->getTable());
            
            Log::debug("Query builder connection switched", [
                'model' => static::class,
                'from_connection' => $currentConnection,
                'to_connection' => $expectedConnection,
                'table' => $this->getTable()
            ]);
        }
        
        return new Builder($query);
    }
    
    /**
     * Get a new query builder instance for the correct connection
     * 
     * @return QueryBuilder
     */
    protected function newBaseQueryBuilder()
    {
        $connectionName = $this->getConnectionName();
        return DB::connection($connectionName)->query();
    }
    
    /**
     * Save the model to the database with election context validation
     * 
     * This method enhances the standard save operation with:
     * - Election context validation
     * - Access level verification (admin vs client)
     * - Audit trail metadata injection
     * - Security compliance checks
     * 
     * @param array $options Save operation options
     * @return bool True if save was successful
     * @throws Exception If election context is missing or access is denied
     */
    public function save(array $options = [])
    {
        // Validate election context is properly set
        $this->validateElectionContext('save');
        
        // Validate user has permission for write operations
        $this->validateWritePermission();
        
        // Add comprehensive audit metadata before saving
        $this->addElectionAuditMetadata();
        
        // Log the save operation for audit trail
        $this->logModelOperation('save', [
            'model_id' => $this->getKey(),
            'model_attributes' => $this->getDirty(),
            'is_new_record' => !$this->exists
        ]);
        
        try {
            $result = parent::save($options);
            
            // Log successful save
            if ($result) {
                $this->logModelOperation('save_success', [
                    'model_id' => $this->getKey(),
                    'was_recently_created' => $this->wasRecentlyCreated
                ]);
            }
            
            return $result;
            
        } catch (Exception $e) {
            // Log save failure for debugging
            $this->logModelOperation('save_failed', [
                'model_id' => $this->getKey(),
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Update the model in the database with validation
     * 
     * @param array $attributes Attributes to update
     * @param array $options Update operation options
     * @return bool True if update was successful
     * @throws Exception If election context is missing or access is denied
     */
    public function update(array $attributes = [], array $options = [])
    {
        $this->validateElectionContext('update');
        $this->validateWritePermission();
        
        $this->logModelOperation('update', [
            'model_id' => $this->getKey(),
            'update_attributes' => $attributes,
            'current_attributes' => $this->getOriginal()
        ]);
        
        return parent::update($attributes, $options);
    }
    
    /**
     * Delete the model from the database with validation
     * 
     * @return bool|null True if deletion was successful
     * @throws Exception If election context is missing or access is denied
     */
    public function delete()
    {
        $this->validateElectionContext('delete');
        $this->validateWritePermission();
        
        $this->logModelOperation('delete', [
            'model_id' => $this->getKey(),
            'model_data' => $this->toArray()
        ]);
        
        return parent::delete();
    }
    
    /**
     * Create a new instance of the model with proper connection context
     * 
     * @param array $attributes Model attributes
     * @param bool $exists Whether the model exists in database
     * @return static New model instance
     */
    public function newInstance($attributes = [], $exists = false)
    {
        $model = parent::newInstance($attributes, $exists);
        
        // Ensure new instance uses the same connection context
        if (ElectionContextService::isElectionContextSet()) {
            $connectionName = $this->getConnectionName();
            $model->setConnection($connectionName);
        }
        
        return $model;
    }
    
    /**
     * Validate that election context is properly set for database operations
     * 
     * @param string $operation The operation being performed
     * @throws Exception If context validation fails
     */
    protected function validateElectionContext(string $operation): void
    {
        if (!ElectionContextService::isElectionContextSet()) {
            throw new Exception(
                "Cannot perform '{$operation}' operation on " . static::class . " without election context. " .
                "Please ensure election context is set before performing database operations."
            );
        }
        
        // Validate that the connection is still valid
        $connectionName = ElectionContextService::getElectionConnection();
        if (!$this->validateConnection($connectionName)) {
            throw new Exception(
                "Election database connection '{$connectionName}' is not available for '{$operation}' operation."
            );
        }
    }
    
    /**
     * Validate that current user has permission for write operations
     * 
     * @throws UnauthorizedDatabaseOperationException If write access is denied
     */
    protected function validateWritePermission(): void
    {
        // Client connections are read-only
        if (ElectionContextService::isClientContext()) {
            throw new UnauthorizedDatabaseOperationException(
                "Write operations are not permitted with client database access. " .
                "Client connections are restricted to read-only operations for security compliance."
            );
        }
        
        // Additional validation can be added here for specific admin roles
        if (ElectionContextService::isAdminContext()) {
            // Admin write operations are generally allowed
            // Custom business logic can be added here if needed
        }
    }
    
    /**
     * Add comprehensive election audit metadata to the model
     * 
     * This method adds audit trail information to track which election context
     * and user performed the operation, enabling comprehensive audit capabilities.
     */
    protected function addElectionAuditMetadata(): void
    {
        $election = ElectionContextService::getCurrentElection();
        $connectionType = ElectionContextService::getConnectionType();
        
        if ($election) {
            // Add election context information for audit trail
            $auditData = [
                'election_context_id' => $election->id,
                'election_context_name' => $election->name,
                'election_context_type' => $connectionType,
                'election_context_database' => $election->database_name,
                'election_context_timestamp' => now()
            ];
            
            // Only set attributes that exist as columns in the table
            foreach ($auditData as $field => $value) {
                if ($this->isColumnExists($field)) {
                    $this->setAttribute($field, $value);
                }
            }
        }
    }
    
    /**
     * Check if a specific column exists in the model's table
     * 
     * Uses caching to avoid repeated schema queries for performance.
     * 
     * @param string $column Column name to check
     * @return bool True if column exists
     */
    protected function isColumnExists(string $column): bool
    {
        $connectionName = $this->getConnectionName();
        $tableName = $this->getTable();
        $cacheKey = "{$connectionName}.{$tableName}";
        
        // Use cached schema information if available
        if (!isset(self::$schemaCache[$cacheKey])) {
            try {
                $columns = Schema::connection($connectionName)->getColumnListing($tableName);
                self::$schemaCache[$cacheKey] = array_flip($columns);
            } catch (Exception $e) {
                // If schema query fails, assume column doesn't exist
                Log::warning("Failed to retrieve schema for table", [
                    'connection' => $connectionName,
                    'table' => $tableName,
                    'error' => $e->getMessage()
                ]);
                return false;
            }
        }
        
        return isset(self::$schemaCache[$cacheKey][$column]);
    }
    
    /**
     * Log model operations for comprehensive audit trail
     * 
     * @param string $operation The operation being performed
     * @param array $context Additional context information
     */
    protected function logModelOperation(string $operation, array $context = []): void
    {
        $election = ElectionContextService::getCurrentElection();
        $contextInfo = ElectionContextService::getContextInfo();
        
        $logData = array_merge([
            'model_class' => static::class,
            'table_name' => $this->getTable(),
            'operation' => $operation,
            'election_context' => $contextInfo,
            'timestamp' => now()->toISOString()
        ], $context);
        
        // Use different log levels based on operation type
        $logLevel = $this->getLogLevelForOperation($operation);
        
        Log::log($logLevel, "Election model operation: {$operation}", $logData);
    }
    
    /**
     * Determine appropriate log level for different operations
     * 
     * @param string $operation The operation being logged
     * @return string Log level
     */
    protected function getLogLevelForOperation(string $operation): string
    {
        $criticalOperations = ['delete', 'save_failed', 'unauthorized_access'];
        $warningOperations = ['update', 'connection_switch'];
        
        if (in_array($operation, $criticalOperations)) {
            return 'warning';
        }
        
        if (in_array($operation, $warningOperations)) {
            return 'info';
        }
        
        return 'debug';
    }
    
    /**
     * Validate that a database connection is working properly
     * 
     * @param string $connectionName Connection name to validate
     * @return bool True if connection is valid
     */
    protected function validateConnection(string $connectionName): bool
    {
        try {
            DB::connection($connectionName)->getPdo();
            return true;
        } catch (Exception $e) {
            Log::error("Election database connection validation failed", [
                'connection' => $connectionName,
                'model' => static::class,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Override the default query method to ensure election context
     * 
     * This ensures that all static queries (Model::where(), Model::find(), etc.)
     * automatically use the correct election database connection.
     * 
     * @return Builder Query builder with correct connection
     */
    public static function query()
    {
        $instance = new static;
        
        // Log query initiation for debugging
        if (ElectionContextService::isElectionContextSet()) {
            $contextInfo = ElectionContextService::getContextInfo();
            Log::debug("Query initiated with election context", [
                'model' => static::class,
                'context' => $contextInfo
            ]);
        } else {
            Log::debug("Query initiated without election context", [
                'model' => static::class,
                'connection' => $instance->getConnectionName()
            ]);
        }
        
        return $instance->newQuery();
    }
    
    /**
     * Scope query to current election context
     * 
     * This scope can be used for explicit election filtering when needed,
     * though database isolation usually makes this unnecessary.
     * 
     * @param Builder $query Query builder instance
     * @return Builder Modified query builder
     * @throws Exception If election context is not set
     */
    public function scopeInCurrentElection($query)
    {
        if (!ElectionContextService::isElectionContextSet()) {
            throw new Exception(
                "Cannot scope " . static::class . " to election without election context. " .
                "Please set election context before using this scope."
            );
        }
        
        // Database connection already provides isolation, but this scope
        // can be used for additional validation if needed
        return $query;
    }
    
    /**
     * Scope that requires election context to be set
     * 
     * Use this scope for operations that absolutely must have election context.
     * 
     * @param Builder $query Query builder instance
     * @return Builder Modified query builder
     * @throws Exception If election context is not set
     */
    public function scopeRequiresElectionContext($query)
    {
        if (!ElectionContextService::isElectionContextSet()) {
            throw new Exception(
                "Model " . static::class . " requires election context to operate safely. " .
                "Please set election context before performing this operation."
            );
        }
        
        return $query;
    }
    
    /**
     * Get comprehensive context information for debugging and monitoring
     * 
     * @return array Context information including election, connection, and audit data
     */
    public function getElectionContextInfo(): array
    {
        $contextInfo = ElectionContextService::getContextInfo();
        
        return [
            'model_class' => static::class,
            'table_name' => $this->getTable(),
            'connection_name' => $this->getConnectionName(),
            'model_exists' => $this->exists,
            'model_key' => $this->getKey(),
            'election_context' => $contextInfo,
            'has_election_context' => ElectionContextService::isElectionContextSet(),
            'connection_type' => ElectionContextService::getConnectionType(),
            'is_admin_context' => ElectionContextService::isAdminContext(),
            'is_client_context' => ElectionContextService::isClientContext(),
            'cached_connection' => self::$cachedConnectionName,
            'schema_cache_status' => isset(self::$schemaCache[$this->getConnectionName() . '.' . $this->getTable()])
        ];
    }
    
    /**
     * Clear static caches for memory management
     * 
     * This method can be called periodically to free up memory used by caches.
     */
    public static function clearElectionContextCache(): void
    {
        self::$cachedConnectionName = null;
        self::$cachedConnectionType = null;
        self::$schemaCache = [];
        
        Log::debug("Election context cache cleared for " . static::class);
    }
    
    /**
     * Get cache statistics for monitoring and debugging
     * 
     * @return array Cache statistics
     */
    public static function getCacheStatistics(): array
    {
        return [
            'cached_connection' => self::$cachedConnectionName,
            'cached_connection_type' => self::$cachedConnectionType,
            'schema_cache_entries' => count(self::$schemaCache),
            'schema_cache_keys' => array_keys(self::$schemaCache)
        ];
    }
}