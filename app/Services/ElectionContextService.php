<?php

namespace App\Services;

use App\Models\Election;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Exception;

/**
 * Complete ElectionContextService
 * Handles both admin and client database access with proper security
 */
class ElectionContextService
{
    /**
     * Current election context
     */
    private static ?Election $currentElection = null;
    
    /**
     * Current connection name
     */
    private static ?string $currentConnection = null;
    
    /**
     * Connection type: 'admin' or 'client'
     */
    private static ?string $connectionType = null;
    
    /**
     * Current user context
     */
    private static ?User $currentUser = null;
    
    /**
     * Original connection before context switch
     */
    private static ?string $originalConnection = null;
    
    /**
     * Connection cache
     */
    private static array $connectionCache = [];
    
    /**
     * Set election context for admin access
     */
    public static function setAdminElectionContext(Election $election, User $user): void
    {
        try {
            if (!self::validateAdminAccess($user, $election)) {
                throw new Exception("User {$user->id} does not have admin access to election {$election->id}");
            }
            
            self::validateElectionForContext($election);
            
            $connectionName = self::registerAdminConnection($election);
            
            self::setInternalContext($election, $connectionName, 'admin', $user);
            
            Log::info("Admin election context set", [
                'election_id' => $election->id,
                'user_id' => $user->id,
                'connection' => $connectionName,
                'database' => $election->database_name
            ]);
            
        } catch (Exception $e) {
            Log::error("Failed to set admin election context", [
                'election_id' => $election->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
     * Set election context for client access
     */
    public static function setClientElectionContext(Election $election, User $user): void
    {
        try {
            if (!self::validateClientAccess($user, $election)) {
                throw new Exception("User {$user->id} does not have client access to election {$election->id}");
            }
            
            self::validateElectionForContext($election);
            self::validateClientCredentials($election);
            
            $connectionName = self::registerClientConnection($election);
            
            self::setInternalContext($election, $connectionName, 'client', $user);
            
            Log::info("Client election context set", [
                'election_id' => $election->id,
                'user_id' => $user->id,
                'connection' => $connectionName,
                'database' => $election->database_name,
                'access_type' => 'client'
            ]);
            
        } catch (Exception $e) {
            Log::error("Failed to set client election context", [
                'election_id' => $election->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
     * Auto-detect and set election context from request
     */
    public static function setElectionContextFromRequest(Request $request): ?Election
    {
        $user = $request->user();
        if (!$user) {
            return null;
        }
        
        $isClientRequest = self::isClientAccessRequest($request);
        
        if ($isClientRequest) {
            $election = self::detectElectionForClientAccess($request);
            if ($election) {
                self::setClientElectionContext($election, $user);
                return $election;
            }
        } else {
            $election = self::detectElectionForAdminAccess($request);
            if ($election) {
                self::setAdminElectionContext($election, $user);
                return $election;
            }
        }
        
        return null;
    }
    
    /**
     * Register admin database connection
     */
    private static function registerAdminConnection(Election $election): string
    {
        $connectionName = "election_admin_{$election->id}";
        
        if (self::isConnectionRegistered($connectionName) && self::testConnection($connectionName)) {
            return $connectionName;
        }
        
        $config = [
            'driver' => 'mysql',
            'host' => $election->database_host ?? config('database.connections.mysql.host'),
            'port' => $election->database_port ?? config('database.connections.mysql.port'),
            'database' => $election->database_name,
            'username' => $election->database_username ?? config('database.connections.mysql.username'),
            'password' => $election->database_password ?? config('database.connections.mysql.password'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            'options' => [
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_STRINGIFY_FETCHES => false,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_TIMEOUT => 30,
            ]
        ];
        
        Config::set("database.connections.{$connectionName}", $config);
        
        // Test connection
        try {
            DB::connection($connectionName)->getPdo();
        } catch (Exception $e) {
            throw new Exception("Cannot connect to admin database: {$e->getMessage()}");
        }
        
        return $connectionName;
    }
    
    /**
     * Register client database connection (read-only)
     */
    private static function registerClientConnection(Election $election): string
    {
        $connectionName = "election_client_{$election->id}";
        
        if (self::isConnectionRegistered($connectionName) && self::testConnection($connectionName)) {
            return $connectionName;
        }
        
        $config = [
            'driver' => 'mysql',
            'host' => $election->database_host ?? config('database.connections.mysql.host'),
            'port' => $election->database_port ?? config('database.connections.mysql.port'),
            'database' => $election->database_name,
            'username' => $election->client_database_username,
            'password' => $election->client_database_password ? decrypt($election->client_database_password) : null,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            'options' => [
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_STRINGIFY_FETCHES => false,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_TIMEOUT => 30,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED"
            ]
        ];
        
        Config::set("database.connections.{$connectionName}", $config);
        
        // Test connection
        try {
            DB::connection($connectionName)->getPdo();
        } catch (Exception $e) {
            throw new Exception("Cannot connect to client database: {$e->getMessage()}");
        }
        
        return $connectionName;
    }
    
    /**
     * Detect election for admin access
     */
    private static function detectElectionForAdminAccess(Request $request): ?Election
    {
        $user = $request->user();
        
        // Priority 1: Explicit election parameter
        if ($electionId = $request->input('election_id') ?? $request->route('election_id')) {
            $election = Election::find($electionId);
            if ($election && self::validateAdminAccess($user, $election)) {
                return $election;
            }
        }
        
        // Priority 2: Session-stored election
        if ($electionId = Session::get('admin_election_id')) {
            $election = Election::find($electionId);
            if ($election && self::validateAdminAccess($user, $election)) {
                return $election;
            }
        }
        
        // Priority 3: URL-based detection
        if ($election = self::detectFromUrl($request)) {
            if (self::validateAdminAccess($user, $election)) {
                return $election;
            }
        }
        
        // Priority 4: User's constituency election
        if ($election = self::detectFromUserConstituency($user)) {
            if (self::validateAdminAccess($user, $election)) {
                return $election;
            }
        }
        
        // Priority 5: Default active election for admins
        if ($user->hasRole(['admin', 'super-admin'])) {
            return Election::whereIn('status', ['active', 'voting', 'setup'])
                ->latest()
                ->first();
        }
        
        return null;
    }
    
    /**
     * Detect election for client access
     */
    private static function detectElectionForClientAccess(Request $request): ?Election
    {
        $user = $request->user();
        
        // Priority 1: Explicit election parameter
        if ($electionId = $request->input('election_id') ?? $request->route('election_id')) {
            $election = Election::find($electionId);
            if ($election && self::validateClientAccess($user, $election)) {
                return $election;
            }
        }
        
        // Priority 2: Session-stored client election
        if ($electionId = Session::get('client_election_id')) {
            $election = Election::find($electionId);
            if ($election && self::validateClientAccess($user, $election)) {
                return $election;
            }
        }
        
        // Priority 3: User's authorized elections
        $election = Election::whereHas('authorizedClients', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where(function($q) {
                      $q->whereNull('access_expires_at')
                        ->orWhere('access_expires_at', '>', now());
                  });
        })->whereIn('status', ['active', 'voting', 'completed'])
          ->latest()
          ->first();
        
        if ($election && self::validateClientAccess($user, $election)) {
            return $election;
        }
        
        return null;
    }
    
    /**
     * Validate admin access to election
     */
    private static function validateAdminAccess(User $user, Election $election): bool
    {
        // Super admin can access any election
        if ($user->hasRole('super-admin')) {
            return true;
        }
        
        // Admin can access elections in their constituency
        if ($user->hasRole('admin')) {
            return $election->constituency === 'general' || 
                   $user->region === $election->constituency;
        }
        
        // Committee members can access elections they're assigned to
        if ($user->hasRole('committee')) {
            return $election->committeeMembers()->where('user_id', $user->id)->exists();
        }
        
        // Election creator can access their election
        if ($election->created_by === $user->id) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Validate client access to election
     */
    private static function validateClientAccess(User $user, Election $election): bool
    {
        // User must have client role
        if (!$user->hasRole(['client', 'election-client'])) {
            return false;
        }
        
        // Check if user is authorized for this election
        $hasAccess = $election->authorizedClients()
            ->where('user_id', $user->id)
            ->where(function($query) {
                $query->whereNull('access_expires_at')
                      ->orWhere('access_expires_at', '>', now());
            })
            ->exists();
            
        if (!$hasAccess) {
            return false;
        }
        
        // Verify client credentials exist and are not revoked
        if (!$election->client_database_username || $election->client_access_revoked_at) {
            return false;
        }
        
        // Election must be in a state where client access is allowed
        if (!in_array($election->status, ['voting', 'completed', 'published'])) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if request is for client access
     */
    private static function isClientAccessRequest(Request $request): bool
    {
        $routeName = $request->route()?->getName();
        
        if ($routeName && Str::contains($routeName, ['client.', 'client-'])) {
            return true;
        }
        
        $path = $request->path();
        if (Str::contains($path, ['client/', 'client-access/', 'database-access/'])) {
            return true;
        }
        
        return $request->header('X-Client-Access') === 'true';
    }
    
    /**
     * Validate operation is allowed for current connection type
     */
    public static function validateOperation(string $operation): bool
    {
        if (self::$connectionType === 'client') {
            $allowedOperations = ['select', 'show', 'describe', 'explain'];
            $operation = strtolower(trim($operation));
            
            if (!in_array($operation, $allowedOperations)) {
                throw new UnauthorizedDatabaseOperationException(
                    "Client access cannot perform '{$operation}' operations. Only read operations are allowed."
                );
            }
        }
        
        return true;
    }
    
    /**
     * Validate SQL query for client access
     */
    public static function validateQuery(string $query): bool
    {
        if (self::$connectionType === 'client') {
            $query = strtolower(trim($query));
            $forbiddenKeywords = [
                'insert', 'update', 'delete', 'drop', 'create', 'alter', 
                'truncate', 'replace', 'grant', 'revoke', 'set', 'call',
                'execute', 'prepare', 'deallocate'
            ];
            
            foreach ($forbiddenKeywords as $keyword) {
                if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/', $query)) {
                    throw new UnauthorizedDatabaseOperationException(
                        "Client access cannot execute queries containing '{$keyword}'"
                    );
                }
            }
        }
        
        return true;
    }
    
    /**
     * Get current election
     */
    public static function getCurrentElection(): ?Election
    {
        return self::$currentElection;
    }
    
    /**
     * Get current connection name
     */
    public static function getElectionConnection(): ?string
    {
        return self::$currentConnection;
    }
    
    /**
     * Get current connection type
     */
    public static function getConnectionType(): ?string
    {
        return self::$connectionType;
    }
    
    /**
     * Check if election context is set
     */
    public static function isElectionContextSet(): bool
    {
        return self::$currentElection !== null && 
               self::$currentConnection !== null &&
               self::$connectionType !== null;
    }
    
    /**
     * Check if current context is admin
     */
    public static function isAdminContext(): bool
    {
        return self::$connectionType === 'admin';
    }
    
    /**
     * Check if current context is client
     */
    public static function isClientContext(): bool
    {
        return self::$connectionType === 'client';
    }
    
    /**
     * Clear election context
     */
    public static function clearElectionContext(): void
    {
        Log::debug("Clearing election context", [
            'previous_election_id' => self::$currentElection?->id,
            'previous_connection' => self::$currentConnection,
            'previous_type' => self::$connectionType
        ]);
        
        self::$currentElection = null;
        self::$currentConnection = null;
        self::$connectionType = null;
        self::$currentUser = null;
        
        Session::forget(['admin_election_id', 'client_election_id']);
    }
    
    /**
     * Execute callback with election context
     */
    public static function withElectionContext(Election $election, User $user, string $accessType, callable $callback)
    {
        $previousElection = self::$currentElection;
        $previousConnection = self::$currentConnection;
        $previousType = self::$connectionType;
        $previousUser = self::$currentUser;
        
        try {
            if ($accessType === 'admin') {
                self::setAdminElectionContext($election, $user);
            } else {
                self::setClientElectionContext($election, $user);
            }
            
            return $callback();
            
        } finally {
            // Restore previous context
            self::$currentElection = $previousElection;
            self::$currentConnection = $previousConnection;
            self::$connectionType = $previousType;
            self::$currentUser = $previousUser;
        }
    }
    
    /**
     * Get context information for debugging
     */
    public static function getContextInfo(): array
    {
        return [
            'election_id' => self::$currentElection?->id,
            'election_name' => self::$currentElection?->name,
            'connection_name' => self::$currentConnection,
            'connection_type' => self::$connectionType,
            'database_name' => self::$currentElection?->database_name,
            'user_id' => self::$currentUser?->id,
            'is_context_set' => self::isElectionContextSet()
        ];
    }
    
    /**
     * Set internal context state
     */
    private static function setInternalContext(Election $election, string $connectionName, string $type, User $user): void
    {
        if (self::$originalConnection === null) {
            self::$originalConnection = config('database.default');
        }
        
        self::$currentElection = $election;
        self::$currentConnection = $connectionName;
        self::$connectionType = $type;
        self::$currentUser = $user;
        
        // Store in appropriate session
        if ($type === 'admin') {
            Session::put('admin_election_id', $election->id);
        } else {
            Session::put('client_election_id', $election->id);
        }
    }
    
    /**
     * Validate election can be used for context
     */
    private static function validateElectionForContext(Election $election): bool
    {
        if (!$election->database_name) {
            throw new Exception("Election {$election->id} does not have a database configured");
        }
        
        if (!in_array($election->status, ['active', 'voting', 'completed', 'setup', 'published'])) {
            throw new Exception("Election {$election->id} is not in a valid state for database access");
        }
        
        return true;
    }
    
    /**
     * Validate client credentials exist
     */
    private static function validateClientCredentials(Election $election): void
    {
        if (!$election->client_database_username) {
            throw new Exception("Election {$election->id} does not have client database credentials configured");
        }
        
        if ($election->client_access_revoked_at) {
            throw new Exception("Client access to election {$election->id} has been revoked");
        }
    }
    
    /**
     * Detect election from URL patterns
     */
    private static function detectFromUrl(Request $request): ?Election
    {
        $pathSegments = explode('/', trim($request->path(), '/'));
        
        foreach ($pathSegments as $segment) {
            if ($election = Election::where('slug', $segment)->first()) {
                return $election;
            }
        }
        
        return null;
    }
    
    /**
     * Detect election from user constituency
     */
    private static function detectFromUserConstituency(User $user): ?Election
    {
        return Election::where('constituency', $user->region)
            ->whereIn('status', ['active', 'voting', 'setup'])
            ->latest()
            ->first();
    }
    
    /**
     * Test if connection is working
     */
    private static function testConnection(string $connectionName): bool
    {
        try {
            DB::connection($connectionName)->select('SELECT 1');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Check if connection is registered
     */
    private static function isConnectionRegistered(string $connectionName): bool
    {
        return Config::has("database.connections.{$connectionName}");
    }
    
    /**
     * Cleanup unused connections
     */
    public static function cleanupUnusedConnections(): void
    {
        $activeElections = Election::whereIn('status', ['active', 'voting', 'setup'])
            ->pluck('id')
            ->toArray();
        
        foreach (Config::get('database.connections') as $name => $config) {
            if (Str::startsWith($name, ['election_admin_', 'election_client_'])) {
                preg_match('/election_(?:admin|client)_(\d+)/', $name, $matches);
                if (isset($matches[1]) && !in_array($matches[1], $activeElections)) {
                    DB::purge($name);
                    Config::forget("database.connections.{$name}");
                    
                    Log::debug("Cleaned up unused connection", ['connection' => $name]);
                }
            }
        }
    }
}

/**
 * Custom exception for unauthorized database operations
 */
class UnauthorizedDatabaseOperationException extends Exception
{
    //
}