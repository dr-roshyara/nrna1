# 🏗️ **ElectionContextService & Context-Aware Model System - Implementation Design**

## 🎯 **CORE DESIGN PRINCIPLES**

### **Transparency**: Controllers and existing code remain unchanged
### **Automatic**: Context switching happens seamlessly 
### **Secure**: Multiple validation layers prevent wrong database access
### **Performance**: Efficient connection management and caching
### **Fallback**: Graceful handling of missing or invalid contexts

---

## 🔧 **1. ElectionContextService - Core Architecture**

### **Primary Responsibilities:**
```php
class ElectionContextService 
{
    // Context Management
    public static function setElectionContext(Election $election): void
    public static function getCurrentElection(): ?Election  
    public static function clearElectionContext(): void
    
    // Database Connection Management
    public static function getElectionConnection(): ?string
    public static function switchToElectionDatabase(Election $election): void
    public static function restoreDefaultConnection(): void
    
    // Context Detection & Validation
    public static function detectElectionFromRequest(Request $request): ?Election
    public static function detectElectionFromUser(User $user): ?Election
    public static function validateElectionAccess(Election $election, User $user): bool
    
    // Utility Methods
    public static function withElectionContext(Election $election, callable $callback)
    public static function isElectionContextSet(): bool
    public static function getElectionDatabaseName(): ?string
}
```

### **Internal State Management:**
```php
class ElectionContextService 
{
    private static ?Election $currentElection = null;
    private static ?string $currentConnection = null;
    private static ?string $originalConnection = null;
    private static array $connectionCache = [];
    private static array $electionCache = [];
    
    // Thread-safe context management
    private static function setInternalState(Election $election): void
    private static function clearInternalState(): void
    private static function validateInternalState(): bool
}
```

---

## 🔌 **2. Database Connection Management System**

### **Dynamic Connection Registration:**
```php
class ElectionDatabaseConnectionManager
{
    /**
     * Register election-specific database connection
     */
    public static function registerElectionConnection(Election $election): string
    {
        $connectionName = "election_{$election->id}";
        
        // Skip if already registered
        if (self::isConnectionRegistered($connectionName)) {
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
        ];
        
        // Register connection in Laravel's database manager
        Config::set("database.connections.{$connectionName}", $config);
        
        // Test connection
        try {
            DB::connection($connectionName)->getPdo();
            Log::info("Election database connection registered successfully", [
                'election_id' => $election->id,
                'connection_name' => $connectionName,
                'database' => $election->database_name
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to connect to election database", [
                'election_id' => $election->id,
                'database' => $election->database_name,
                'error' => $e->getMessage()
            ]);
            throw new ElectionDatabaseConnectionException(
                "Cannot connect to election database: {$e->getMessage()}"
            );
        }
        
        return $connectionName;
    }
    
    /**
     * Purge unused connections to free memory
     */
    public static function purgeUnusedConnections(): void
    {
        $activeElections = Election::where('status', 'active')->pluck('id');
        
        foreach (Config::get('database.connections') as $name => $config) {
            if (Str::startsWith($name, 'election_')) {
                $electionId = Str::after($name, 'election_');
                if (!$activeElections->contains($electionId)) {
                    DB::purge($name);
                    Config::forget("database.connections.{$name}");
                }
            }
        }
    }
}
```

### **Connection Health Monitoring:**
```php
class ElectionConnectionHealthChecker
{
    public static function checkElectionConnection(Election $election): bool
    {
        try {
            $connection = ElectionDatabaseConnectionManager::registerElectionConnection($election);
            DB::connection($connection)->select('SELECT 1');
            return true;
        } catch (\Exception $e) {
            Log::warning("Election database connection health check failed", [
                'election_id' => $election->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    public static function getConnectionStatistics(Election $election): array
    {
        $connectionName = "election_{$election->id}";
        
        return [
            'connection_name' => $connectionName,
            'database_name' => $election->database_name,
            'is_connected' => self::checkElectionConnection($election),
            'table_count' => self::getTableCount($election),
            'user_count' => self::getUserCount($election),
            'vote_count' => self::getVoteCount($election),
        ];
    }
}
```

---

## 🎯 **3. Context Detection Strategy**

### **Multi-Layer Election Detection:**
```php
class ElectionContextDetector
{
    /**
     * Detect election from multiple sources with priority order
     */
    public static function detectElection(Request $request): ?Election
    {
        // Priority 1: Explicit election parameter
        if ($electionId = $request->input('election_id') ?? $request->route('election_id')) {
            if ($election = Election::find($electionId)) {
                Log::info("Election detected from explicit parameter", ['election_id' => $electionId]);
                return $election;
            }
        }
        
        // Priority 2: Session-stored election
        if ($electionId = $request->session()->get('current_election_id')) {
            if ($election = Election::find($electionId)) {
                Log::info("Election detected from session", ['election_id' => $electionId]);
                return $election;
            }
        }
        
        // Priority 3: URL-based detection (subdomain, path, etc.)
        if ($election = self::detectFromUrl($request)) {
            Log::info("Election detected from URL", ['election_id' => $election->id]);
            return $election;
        }
        
        // Priority 4: User's active election
        if ($user = $request->user()) {
            if ($election = self::detectFromUser($user)) {
                Log::info("Election detected from user context", [
                    'user_id' => $user->id,
                    'election_id' => $election->id
                ]);
                return $election;
            }
        }
        
        // Priority 5: Default active election
        if ($election = self::detectDefaultElection()) {
            Log::info("Using default election", ['election_id' => $election->id]);
            return $election;
        }
        
        Log::warning("No election context could be detected", [
            'url' => $request->url(),
            'user_id' => $request->user()?->id,
        ]);
        
        return null;
    }
    
    private static function detectFromUrl(Request $request): ?Election
    {
        // Check for election slug in URL path
        $pathSegments = explode('/', trim($request->path(), '/'));
        
        foreach ($pathSegments as $segment) {
            if ($election = Election::where('slug', $segment)->first()) {
                return $election;
            }
        }
        
        // Check subdomain for election
        $host = $request->getHost();
        if (preg_match('/^([^.]+)\./', $host, $matches)) {
            $subdomain = $matches[1];
            if ($election = Election::where('subdomain', $subdomain)->first()) {
                return $election;
            }
        }
        
        return null;
    }
    
    private static function detectFromUser(User $user): ?Election
    {
        // Strategy 1: User's primary constituency election
        $election = Election::where('constituency', $user->region)
            ->where('status', 'active')
            ->first();
            
        if ($election) {
            return $election;
        }
        
        // Strategy 2: User's eligible elections (if multi-eligibility supported)
        $election = Election::whereHas('eligibleUsers', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'active')->first();
        
        return $election;
    }
    
    private static function detectDefaultElection(): ?Election
    {
        // Get the most recently active general election
        return Election::where('constituency', 'general')
            ->where('status', 'active')
            ->latest()
            ->first();
    }
}
```

---

## 🛡️ **4. Context-Aware Base Model**

### **Enhanced Base Model for All Election Models:**
```php
abstract class ElectionAwareModel extends Model
{
    protected $guarded = [];
    
    /**
     * Get the database connection for the model
     */
    public function getConnectionName()
    {
        // If election context is set, use election database
        if (ElectionContextService::isElectionContextSet()) {
            $connection = ElectionContextService::getElectionConnection();
            if ($connection) {
                return $connection;
            }
        }
        
        // Fallback to default connection
        return $this->connection ?? config('database.default');
    }
    
    /**
     * Create a new Eloquent query builder for the model
     */
    public function newEloquentBuilder($query)
    {
        // Ensure we're using the correct database connection
        $connection = $this->getConnectionName();
        
        if ($connection !== $query->getConnection()->getName()) {
            $query = $this->newBaseQueryBuilder()
                ->connection(DB::connection($connection))
                ->from($this->getTable());
        }
        
        return new Builder($query);
    }
    
    /**
     * Save the model to the database with election context validation
     */
    public function save(array $options = [])
    {
        // Validate that we have proper election context for data modification
        if (!ElectionContextService::isElectionContextSet()) {
            throw new MissingElectionContextException(
                "Cannot save {$this->getTable()} record without election context"
            );
        }
        
        // Add election metadata to model
        $this->addElectionMetadata();
        
        return parent::save($options);
    }
    
    /**
     * Add election metadata to model before saving
     */
    protected function addElectionMetadata(): void
    {
        if ($election = ElectionContextService::getCurrentElection()) {
            // Add election_context_id for audit purposes
            $this->setAttribute('election_context_id', $election->id);
            $this->setAttribute('election_context_name', $election->name);
        }
    }
    
    /**
     * Scope query to current election context
     */
    public function scopeInCurrentElection($query)
    {
        if (!ElectionContextService::isElectionContextSet()) {
            throw new MissingElectionContextException(
                "Cannot query {$this->getTable()} without election context"
            );
        }
        
        return $query; // Already scoped by database connection
    }
}
```

### **Election-Aware Model Implementations:**
```php
class User extends ElectionAwareModel
{
    // All your existing User model code remains the same
    // The model will automatically use the correct database
    
    protected $fillable = [
        'name', 'email', 'password', 'nrna_id', 'region', 'is_voter', 'can_vote',
        // ... all existing fields
    ];
    
    // All existing relationships and methods work the same
    public function code()
    {
        return $this->hasOne(Code::class);
    }
    
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}

class Code extends ElectionAwareModel
{
    // All existing Code model code remains unchanged
    protected $fillable = [
        'user_id', 'code1', 'code2', 'can_vote_now', 'has_voted',
        // ... all existing fields
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

class Vote extends ElectionAwareModel
{
    // All existing Vote model code remains unchanged
    protected $fillable = [
        'user_id', 'voting_code', 'candidate_01', 'candidate_02',
        // ... all existing fields
    ];
}

class Post extends ElectionAwareModel
{
    // Post model automatically election-aware
    protected $fillable = [
        'post_id', 'name', 'nepali_name', 'is_national_wide', 'state_name', 'required_number'
    ];
    
    public function candidates()
    {
        return $this->hasMany(Candidacy::class);
    }
}

class Candidacy extends ElectionAwareModel
{
    // Candidacy model automatically election-aware
    protected $fillable = [
        'user_id', 'post_id', 'candidacy_id', 'candidacy_name', 'proposer_name', 'supporter_name'
    ];
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## 🔄 **5. Context Management Middleware**

### **Automatic Election Context Middleware:**
```php
class SetElectionContextMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Only apply to routes that need election context
        if (!$this->requiresElectionContext($request)) {
            return $next($request);
        }
        
        try {
            // Detect election from request
            $election = ElectionContextDetector::detectElection($request);
            
            if ($election) {
                // Validate user has access to this election
                if ($user = $request->user()) {
                    if (!$this->validateUserElectionAccess($user, $election)) {
                        return $this->handleAccessDenied($request, $election);
                    }
                }
                
                // Set election context for this request
                ElectionContextService::setElectionContext($election);
                
                // Store in session for subsequent requests
                $request->session()->put('current_election_id', $election->id);
                
                Log::info("Election context set for request", [
                    'election_id' => $election->id,
                    'election_name' => $election->name,
                    'user_id' => $request->user()?->id,
                    'route' => $request->route()?->getName(),
                ]);
                
            } else {
                return $this->handleMissingElection($request);
            }
            
        } catch (\Exception $e) {
            Log::error("Failed to set election context", [
                'error' => $e->getMessage(),
                'route' => $request->route()?->getName(),
                'user_id' => $request->user()?->id,
            ]);
            
            return $this->handleContextError($request, $e);
        }
        
        $response = $next($request);
        
        // Clean up context after request (optional, for memory management)
        // ElectionContextService::clearElectionContext();
        
        return $response;
    }
    
    private function requiresElectionContext(Request $request): bool
    {
        $routeName = $request->route()?->getName();
        
        // Routes that require election context
        $electionRoutes = [
            'dashboard',
            'vote.*',
            'code.*',
            'result.*',
            'voter.*',
            'candidacy.*',
            'post.*',
        ];
        
        foreach ($electionRoutes as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function validateUserElectionAccess(User $user, Election $election): bool
    {
        // Basic validation: user's region matches election constituency
        if ($election->constituency !== 'general' && $user->region !== $election->constituency) {
            return false;
        }
        
        // Additional validations can be added here
        // - User eligibility
        // - Election status
        // - Access permissions
        
        return true;
    }
    
    private function handleAccessDenied(Request $request, Election $election)
    {
        return redirect()->route('election.access-denied', [
            'election' => $election->id,
            'reason' => 'constituency_mismatch'
        ]);
    }
    
    private function handleMissingElection(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'No active election found',
                'message' => 'Please contact the administrator if you believe this is an error.'
            ], 404);
        }
        
        return redirect()->route('election.not-found');
    }
    
    private function handleContextError(Request $request, \Exception $e)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Election context error',
                'message' => 'Unable to determine election context.'
            ], 500);
        }
        
        return redirect()->route('dashboard')->with('error', 'Election context error. Please try again.');
    }
}
```

---

## 🎮 **6. Controller Integration Pattern**

### **Zero-Change Controller Integration:**
```php
// Your existing controllers require NO CHANGES!

class CodeController extends Controller
{
    public function create()
    {
        $auth_user = auth()->user();
        
        // This automatically reads from election-specific database
        // because ElectionContextService has set the context
        $code = Code::where('user_id', $auth_user->id)->first();
        
        if (!$code) {
            $code = Code::create([
                'user_id' => $auth_user->id,
                'client_ip' => $this->clientIP,
                // ... all existing fields
            ]);
        }
        
        // All existing logic remains exactly the same
        // The magic happens in the ElectionAwareModel base class
    }
}

class VoteController extends Controller  
{
    public function create(Request $request)
    {
        $auth_user = $request->user();
        
        // These queries automatically use election-specific database
        $national_posts = Post::with('candidates')->where('is_national_wide', 1)->get();
        $regional_posts = Post::with('candidates')->where('state_name', $auth_user->region)->get();
        
        // All existing logic works without any changes
    }
    
    public function store(Request $request)
    {
        // Vote saving automatically goes to election-specific database
        $vote = new Vote();
        $vote->user_id = $auth_user->id;
        $vote->save(); // Automatically saves to correct election database
        
        // All existing logic remains the same
    }
}

class VoterlistController extends Controller
{
    public function index(Request $request)
    {
        // This query automatically uses election-specific database
        $voters = User::where('is_voter', 1)->paginate(50);
        
        // All existing logic works without changes
    }
    
    public function approveVoter($id)
    {
        // This finds user in election-specific database
        $user = User::findOrFail($id);
        $user->update(['can_vote' => 1]);
        
        // All existing logic remains exactly the same
    }
}
```

---

## 🚦 **7. Error Handling & Edge Cases**

### **Comprehensive Error Management:**
```php
class ElectionContextException extends Exception {}
class MissingElectionContextException extends ElectionContextException {}
class InvalidElectionContextException extends ElectionContextException {}
class ElectionDatabaseConnectionException extends ElectionContextException {}

class ElectionContextErrorHandler
{
    public static function handleMissingContext(Request $request): Response
    {
        Log::warning("Missing election context", [
            'route' => $request->route()?->getName(),
            'user_id' => $request->user()?->id,
            'url' => $request->url(),
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'election_context_missing',
                'message' => 'No active election found for your account.'
            ], 404);
        }
        
        return redirect()->route('election.selection')
            ->with('error', 'Please select an active election to continue.');
    }
    
    public static function handleInvalidContext(Election $election, User $user): Response
    {
        Log::warning("Invalid election context", [
            'election_id' => $election->id,
            'user_id' => $user->id,
            'user_region' => $user->region,
            'election_constituency' => $election->constituency,
        ]);
        
        return redirect()->route('election.access-denied', [
            'election' => $election->id,
            'reason' => 'invalid_access'
        ]);
    }
    
    public static function handleDatabaseConnectionError(Election $election, \Exception $e): Response
    {
        Log::error("Election database connection failed", [
            'election_id' => $election->id,
            'database' => $election->database_name,
            'error' => $e->getMessage(),
        ]);
        
        return redirect()->route('dashboard')
            ->with('error', 'Election database temporarily unavailable. Please try again later.');
    }
}
```

---

## 📊 **8. Implementation Rollout Strategy**

### **Phase 1: Core Infrastructure (Week 1)**
1. ✅ Create `ElectionContextService` base class
2. ✅ Create `ElectionDatabaseConnectionManager`
3. ✅ Create `ElectionAwareModel` base class
4. ✅ Add election database fields to Election model

### **Phase 2: Context Detection (Week 2)**  
1. ✅ Implement `ElectionContextDetector`
2. ✅ Create `SetElectionContextMiddleware`
3. ✅ Add middleware to voting routes
4. ✅ Test basic context switching

### **Phase 3: Model Integration (Week 3)**
1. ✅ Convert User, Code, Vote models to extend ElectionAwareModel
2. ✅ Convert Post, Candidacy models to extend ElectionAwareModel  
3. ✅ Test all existing controllers with new models
4. ✅ Verify data isolation between elections

### **Phase 4: Testing & Optimization (Week 4)**
1. ✅ Comprehensive integration testing
2. ✅ Performance optimization and connection pooling
3. ✅ Error handling and edge case resolution
4. ✅ Documentation and team training

---

## 🎯 **Key Success Metrics**

### **Functional Requirements:**
- ✅ All existing controllers work without code changes
- ✅ Data is completely isolated between elections
- ✅ Context switching is automatic and transparent
- ✅ Performance impact is minimal (<5% overhead)

### **Technical Requirements:**
- ✅ Database connections are managed efficiently
- ✅ Error handling is robust and user-friendly
- ✅ Audit trails include election context
- ✅ System can handle multiple concurrent elections

This design provides **complete transparency** to your existing code while implementing sophisticated multi-database election isolation. The beauty is that your controllers remain unchanged while gaining powerful multi-election capabilities!
## 🎯 **IMPLEMENTATION DESIGN SUMMARY**

I've designed a comprehensive **ElectionContextService & Context-Aware Model System** that achieves your "Shared Logic, Isolated Data" architecture perfectly. Here are the key highlights:

## 🌟 **CORE DESIGN BENEFITS**

### **1. ZERO CODE CHANGES Required**
Your existing controllers (`CodeController`, `VoteController`, `VoterlistController`) work **exactly as they are** - no modifications needed!

### **2. AUTOMATIC Context Management**
```php
// Your existing code:
$code = Code::where('user_id', $auth_user->id)->first();

// Automatically reads from election-specific database
// Magic happens in ElectionAwareModel base class
```

### **3. TRANSPARENT Database Switching**
```
User Request → Middleware Detects Election → Sets Context → Models Use Correct DB
```

### **4. ROBUST Election Detection**
Priority-based detection from:
1. Explicit election parameter
2. Session-stored election  
3. URL-based detection
4. User's constituency election
5. Default active election

### **5. COMPLETE Data Isolation**
Each election gets its own database with identical schema but completely separate data.

## 🔧 **KEY IMPLEMENTATION COMPONENTS**

### **ElectionContextService**
- Central service managing database context
- Thread-safe context switching
- Connection caching and optimization

### **ElectionAwareModel Base Class**
- All models extend this class
- Automatic database connection resolution
- Election metadata injection
- Validation and error handling

### **SetElectionContextMiddleware**
- Automatic election detection on each request
- User access validation
- Session management
- Error handling

### **Dynamic Connection Management**
- Runtime database connection registration
- Health monitoring
- Memory-efficient connection pooling

## 📈 **IMPLEMENTATION ROADMAP**

### **Week 1: Core Infrastructure**
- ElectionContextService foundation
- Database connection management
- ElectionAwareModel base class

### **Week 2: Context Detection**
- Middleware implementation
- Election detection logic
- Route integration

### **Week 3: Model Conversion**
- Convert all models to ElectionAware
- Test existing controllers
- Verify data isolation

### **Week 4: Testing & Optimization**
- Comprehensive testing
- Performance optimization
- Error handling refinement

## 🎯 **IMMEDIATE NEXT STEPS**

1. **Start with ElectionContextService** - Core foundation class
2. **Create ElectionAwareModel** - Base class for all models  
3. **Test with one model** - Convert User model first
4. **Add middleware** - Automatic context detection

The beauty of this design is that it provides **enterprise-level multi-database isolation** while keeping your existing code **completely unchanged**. Your controllers will automatically read from the correct election database based on context.

Would you like me to start with the **ElectionContextService implementation code**, or would you prefer to begin with the **ElectionAwareModel base class**?