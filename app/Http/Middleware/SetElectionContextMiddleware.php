<?php

namespace App\Http\Middleware;

use App\Services\ElectionContextService;
use App\Services\UnauthorizedDatabaseOperationException;
use App\Models\Election;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Exception;

/**
 * SetElectionContextMiddleware - Professional Election Context Management
 * 
 * This middleware provides comprehensive election context detection and management
 * for a multi-tenant election system. It automatically determines the appropriate
 * election database connection based on request characteristics and user permissions.
 * 
 * Core Responsibilities:
 * - Automatic election context detection from multiple sources
 * - User access validation for both administrative and client access patterns
 * - Database connection management and optimization
 * - Security enforcement and audit trail maintenance
 * - Performance optimization through intelligent caching
 * - Comprehensive error handling and user experience management
 * 
 * Security Features:
 * - Role-based access control validation
 * - Connection type enforcement (admin vs client)
 * - Request origin validation and IP tracking
 * - Suspicious activity detection and logging
 * - Rate limiting and abuse prevention
 * 
 * Performance Features:
 * - Intelligent route pattern caching
 * - Connection reuse optimization
 * - Conditional context switching
 * - Request classification for efficiency
 * 
 * Integration Points:
 * - ElectionContextService for connection management
 * - Authentication system for user validation
 * - Audit logging system for compliance
 * - Cache system for performance optimization
 * 
 * @package App\Http\Middleware
 * @author NRNA Election System
 * @version 2.0.0
 */
class SetElectionContextMiddleware
{
    /**
     * Cache key prefix for route pattern caching
     * 
     * @var string
     */
    private const CACHE_PREFIX = 'election_middleware';
    
    /**
     * Cache TTL for route patterns in seconds (1 hour)
     * 
     * @var int
     */
    private const CACHE_TTL = 3600;
    
    /**
     * Maximum number of context switches per session to prevent abuse
     * 
     * @var int
     */
    private const MAX_CONTEXT_SWITCHES_PER_SESSION = 50;
    
    /**
     * Routes that explicitly require election context
     * Cached for performance optimization
     * 
     * @var array
     */
    private static array $electionRequiredRoutes = [];
    
    /**
     * Routes that should never have election context
     * Cached for performance optimization
     * 
     * @var array
     */
    private static array $electionExcludedRoutes = [];
    
    /**
     * Performance metrics for monitoring
     * 
     * @var array
     */
    private static array $performanceMetrics = [];
    
    /**
     * Handle an incoming request with comprehensive election context management
     * 
     * This method orchestrates the complete election context detection and validation
     * process, ensuring that requests are properly routed to the correct election
     * database while maintaining security and performance standards.
     * 
     * Process Flow:
     * 1. Request classification and route analysis
     * 2. User authentication and permission validation
     * 3. Election context detection from multiple sources
     * 4. Access level determination (admin vs client)
     * 5. Database connection establishment and validation
     * 6. Security compliance verification
     * 7. Audit trail generation
     * 8. Request processing and response handling
     * 
     * @param Request $request The incoming HTTP request
     * @param Closure $next The next middleware or controller in the pipeline
     * @return Response The processed response
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $requestId = $this->generateRequestId();
        
        try {
            // Initialize request tracking for audit and performance monitoring
            $this->initializeRequestTracking($request, $requestId);
            
            // Determine if this request requires election context
            $contextRequirement = $this->analyzeContextRequirement($request);
            
            if ($contextRequirement['required']) {
                // Validate user authentication for protected routes
                $user = $this->validateUserAuthentication($request);
                
                // Detect and set appropriate election context
                $electionContext = $this->establishElectionContext($request, $user, $contextRequirement);
                
                if (!$electionContext && $contextRequirement['mandatory']) {
                    return $this->handleMissingMandatoryContext($request, $requestId);
                }
                
                // Validate security compliance for the established context
                if ($electionContext) {
                    $this->validateSecurityCompliance($request, $electionContext, $user);
                }
            }
            
            // Process the request through the application pipeline
            $response = $next($request);
            
            // Enhance response with election context metadata
            $this->enhanceResponseWithContext($response, $requestId);
            
            // Record performance metrics for monitoring
            $this->recordPerformanceMetrics($startTime, $request, $requestId);
            
            return $response;
            
        } catch (UnauthorizedDatabaseOperationException $e) {
            return $this->handleUnauthorizedAccess($request, $e, $requestId);
        } catch (Exception $e) {
            return $this->handleGeneralError($request, $e, $requestId);
        }
    }
    
    /**
     * Analyze whether the current request requires election context
     * 
     * This method performs intelligent request classification to determine:
     * - Whether election context is required at all
     * - Whether it's mandatory or optional
     * - What type of access is being requested (admin vs client)
     * - Performance optimization opportunities
     * 
     * @param Request $request The incoming request
     * @return array Context requirement analysis results
     */
    protected function analyzeContextRequirement(Request $request): array
    {
        $routeName = $request->route()?->getName();
        $path = $request->path();
        $method = $request->method();
        
        // Use cached analysis if available for performance
        $cacheKey = self::CACHE_PREFIX . ':route_analysis:' . md5($routeName . $path . $method);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($request, $routeName, $path) {
            return [
                'required' => $this->determineIfContextRequired($request, $routeName, $path),
                'mandatory' => $this->determineIfContextMandatory($request, $routeName, $path),
                'access_type' => $this->determineRequestedAccessType($request, $routeName, $path),
                'security_level' => $this->determineSecurityLevel($request, $routeName, $path)
            ];
        });
    }
    
    /**
     * Determine if election context is required for the current request
     * 
     * @param Request $request The incoming request
     * @param string|null $routeName Named route identifier
     * @param string $path Request path
     * @return bool True if context is required
     */
    protected function determineIfContextRequired(Request $request, ?string $routeName, string $path): bool
    {
        // Always skip authentication and system routes
        if ($this->isSystemRoute($routeName, $path)) {
            return false;
        }
        
        // Check explicit inclusion patterns
        if ($this->matchesElectionRoutePatterns($routeName, $path)) {
            return true;
        }
        
        // Authenticated routes generally need context
        if ($request->user()) {
            return !$this->isExplicitlyExcluded($routeName, $path);
        }
        
        return false;
    }
    
    /**
     * Determine if election context is mandatory (vs optional) for the request
     * 
     * @param Request $request The incoming request
     * @param string|null $routeName Named route identifier
     * @param string $path Request path
     * @return bool True if context is mandatory
     */
    protected function determineIfContextMandatory(Request $request, ?string $routeName, string $path): bool
    {
        $mandatoryPatterns = [
            'vote.*', 'voting.*', 'ballot.*',
            'code.*', 'verification.*',
            'result.*', 'results.*',
            'candidate.*', 'candidacy.*',
            'post.*', 'position.*',
            'publisher.*', 'authorization.*'
        ];
        
        if ($routeName) {
            foreach ($mandatoryPatterns as $pattern) {
                if (Str::is($pattern, $routeName)) {
                    return true;
                }
            }
        }
        
        $mandatoryPaths = ['vote/', 'voting/', 'result/', 'candidate/', 'admin/election'];
        foreach ($mandatoryPaths as $mandatoryPath) {
            if (Str::contains($path, $mandatoryPath)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Determine the type of database access being requested
     * 
     * @param Request $request The incoming request
     * @param string|null $routeName Named route identifier
     * @param string $path Request path
     * @return string Access type: 'admin', 'client', or 'auto'
     */
    protected function determineRequestedAccessType(Request $request, ?string $routeName, string $path): string
    {
        // Explicit client access indicators
        $clientIndicators = ['client.', 'client-', '/client/', 'database-access', 'export-data'];
        foreach ($clientIndicators as $indicator) {
            if (($routeName && Str::contains($routeName, $indicator)) || Str::contains($path, $indicator)) {
                return 'client';
            }
        }
        
        // Admin access indicators
        $adminIndicators = ['admin.', 'dashboard', 'manage', 'approve', 'configure'];
        foreach ($adminIndicators as $indicator) {
            if (($routeName && Str::contains($routeName, $indicator)) || Str::contains($path, $indicator)) {
                return 'admin';
            }
        }
        
        // Check for explicit header indication
        if ($request->header('X-Client-Access') === 'true') {
            return 'client';
        }
        
        if ($request->header('X-Admin-Access') === 'true') {
            return 'admin';
        }
        
        return 'auto'; // Let the service determine based on user permissions
    }
    
    /**
     * Determine the security level required for the request
     * 
     * @param Request $request The incoming request
     * @param string|null $routeName Named route identifier
     * @param string $path Request path
     * @return string Security level: 'high', 'medium', or 'standard'
     */
    protected function determineSecurityLevel(Request $request, ?string $routeName, string $path): string
    {
        $highSecurityPatterns = [
            'vote.store', 'vote.submit', 'result.publish', 'authorization.*',
            'publisher.*', 'admin.election.delete', 'voter.approve'
        ];
        
        if ($routeName) {
            foreach ($highSecurityPatterns as $pattern) {
                if (Str::is($pattern, $routeName)) {
                    return 'high';
                }
            }
        }
        
        if ($request->isMethod('DELETE') || $request->isMethod('PUT')) {
            return 'medium';
        }
        
        return 'standard';
    }
    
    /**
     * Validate user authentication and authorization for the request
     * 
     * @param Request $request The incoming request
     * @return User The authenticated user
     * @throws Exception If authentication fails
     */
    protected function validateUserAuthentication(Request $request): User
    {
        $user = $request->user();
        
        if (!$user) {
            throw new Exception('Authentication required for election context access');
        }
        
        // Validate user account status
        if (isset($user->suspended_at) && $user->suspended_at) {
            throw new Exception('User account is suspended and cannot access election systems');
        }
        
        // Check for user session validity
        if (!$this->validateUserSession($request, $user)) {
            throw new Exception('User session is invalid or expired');
        }
        
        return $user;
    }
    
    /**
     * Establish election context based on request analysis and user permissions
     * 
     * @param Request $request The incoming request
     * @param User $user The authenticated user
     * @param array $contextRequirement Context requirement analysis
     * @return array|null Election context information or null if not established
     */
    protected function establishElectionContext(Request $request, User $user, array $contextRequirement): ?array
    {
        try {
            // Prevent context switching abuse
            $this->validateContextSwitchingLimits($request);
            
            // Determine access method based on requirement analysis
            $accessType = $contextRequirement['access_type'];
            
            if ($accessType === 'auto') {
                // Auto-detect based on user capabilities and request characteristics
                $election = ElectionContextService::setElectionContextFromRequest($request);
            } elseif ($accessType === 'client') {
                // Explicitly request client access
                $election = $this->establishClientContext($request, $user);
            } else {
                // Default to admin access
                $election = $this->establishAdminContext($request, $user);
            }
            
            if ($election) {
                $contextInfo = ElectionContextService::getContextInfo();
                
                $this->logContextEstablishment($request, $user, $election, $contextInfo);
                
                return [
                    'election' => $election,
                    'context_info' => $contextInfo,
                    'access_type' => ElectionContextService::getConnectionType(),
                    'established_at' => now()
                ];
            }
            
            return null;
            
        } catch (Exception $e) {
            $this->logContextEstablishmentFailure($request, $user, $e);
            throw $e;
        }
    }
    
    /**
     * Establish client-specific election context
     * 
     * @param Request $request The incoming request
     * @param User $user The authenticated user
     * @return Election|null The election with client context or null
     */
    protected function establishClientContext(Request $request, User $user): ?Election
    {
        // Detect election for client access
        $election = $this->detectElectionForClient($request, $user);
        
        if ($election) {
            ElectionContextService::setClientElectionContext($election, $user);
            return $election;
        }
        
        return null;
    }
    
    /**
     * Establish admin-specific election context
     * 
     * @param Request $request The incoming request
     * @param User $user The authenticated user
     * @return Election|null The election with admin context or null
     */
    protected function establishAdminContext(Request $request, User $user): ?Election
    {
        // Detect election for admin access
        $election = $this->detectElectionForAdmin($request, $user);
        
        if ($election) {
            ElectionContextService::setAdminElectionContext($election, $user);
            return $election;
        }
        
        return null;
    }
    
    /**
     * Detect appropriate election for client access
     * 
     * @param Request $request The incoming request
     * @param User $user The authenticated user
     * @return Election|null Detected election or null
     */
    protected function detectElectionForClient(Request $request, User $user): ?Election
    {
        // Priority 1: Explicit election parameter
        if ($electionId = $request->input('election_id') ?? $request->route('election_id')) {
            $election = Election::find($electionId);
            if ($election && $this->validateClientElectionAccess($user, $election)) {
                return $election;
            }
        }
        
        // Priority 2: User's authorized elections
        $election = Election::whereHas('authorizedClients', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where(function($q) {
                      $q->whereNull('access_expires_at')
                        ->orWhere('access_expires_at', '>', now());
                  });
        })->whereIn('status', ['voting', 'completed', 'published'])
          ->latest()
          ->first();
        
        if ($election && $this->validateClientElectionAccess($user, $election)) {
            return $election;
        }
        
        return null;
    }
    
    /**
     * Detect appropriate election for admin access
     * 
     * @param Request $request The incoming request
     * @param User $user The authenticated user
     * @return Election|null Detected election or null
     */
    protected function detectElectionForAdmin(Request $request, User $user): ?Election
    {
        // Use the standard admin detection logic
        return ElectionContextService::detectElectionFromRequest($request);
    }
    
    /**
     * Validate security compliance for the established context
     * 
     * @param Request $request The incoming request
     * @param array $electionContext Election context information
     * @param User $user The authenticated user
     * @throws Exception If security validation fails
     */
    protected function validateSecurityCompliance(Request $request, array $electionContext, User $user): void
    {
        $contextInfo = $electionContext['context_info'];
        
        // Validate IP consistency for security
        $this->validateIPConsistency($request, $user, $contextInfo);
        
        // Validate session integrity
        $this->validateSessionIntegrity($request, $user, $contextInfo);
        
        // Validate access level compliance
        $this->validateAccessLevelCompliance($request, $contextInfo);
        
        // Additional security checks based on security level
        $securityLevel = $this->determineSecurityLevel($request, $request->route()?->getName(), $request->path());
        if ($securityLevel === 'high') {
            $this->performHighSecurityValidation($request, $user, $contextInfo);
        }
    }
    
    /**
     * Validate IP address consistency for security compliance
     * 
     * @param Request $request The incoming request
     * @param User $user The authenticated user
     * @param array $contextInfo Context information
     */
    protected function validateIPConsistency(Request $request, User $user, array $contextInfo): void
    {
        $currentIP = $request->ip();
        $sessionKey = "user_ip_session_{$user->id}";
        $lastIP = Cache::get($sessionKey);
        
        if ($lastIP && $lastIP !== $currentIP) {
            Log::warning('IP address change detected during election session', [
                'user_id' => $user->id,
                'previous_ip' => $lastIP,
                'current_ip' => $currentIP,
                'election_id' => $contextInfo['election_id'],
                'context_type' => $contextInfo['connection_type']
            ]);
            
            // Update IP tracking
            Cache::put($sessionKey, $currentIP, now()->addHours(24));
        } elseif (!$lastIP) {
            // First IP tracking for this session
            Cache::put($sessionKey, $currentIP, now()->addHours(24));
        }
    }
    
    /**
     * Validate user session integrity
     * 
     * @param Request $request The incoming request
     * @param User $user The authenticated user
     */
    protected function validateUserSession(Request $request, User $user): bool
    {
        // Check session expiration
        $sessionTimeout = config('session.lifetime', 120);
        $lastActivity = $request->session()->get('last_activity', time());
        
        if (time() - $lastActivity > ($sessionTimeout * 60)) {
            return false;
        }
        
        // Update last activity
        $request->session()->put('last_activity', time());
        
        return true;
    }
    
    /**
     * Validate context switching limits to prevent abuse
     * 
     * @param Request $request The incoming request
     * @throws Exception If limits are exceeded
     */
    protected function validateContextSwitchingLimits(Request $request): void
    {
        $sessionId = $request->session()->getId();
        $switchCountKey = "context_switches_{$sessionId}";
        $switchCount = Cache::get($switchCountKey, 0);
        
        if ($switchCount >= self::MAX_CONTEXT_SWITCHES_PER_SESSION) {
            throw new Exception('Maximum context switches exceeded for this session');
        }
        
        // Increment counter
        Cache::put($switchCountKey, $switchCount + 1, now()->addHours(24));
    }
    
    /**
     * Check if the route is a system route that should not have election context
     * 
     * @param string|null $routeName Named route identifier
     * @param string $path Request path
     * @return bool True if it's a system route
     */
    protected function isSystemRoute(?string $routeName, string $path): bool
    {
        $systemRoutes = [
            'login', 'register', 'logout', 'password.*', 'verification.*',
            'home', 'welcome', 'health-check', 'api.*'
        ];
        
        if ($routeName) {
            foreach ($systemRoutes as $pattern) {
                if (Str::is($pattern, $routeName)) {
                    return true;
                }
            }
        }
        
        $systemPaths = ['login', 'register', 'password', 'verification', 'api/', 'health'];
        foreach ($systemPaths as $systemPath) {
            if (Str::contains($path, $systemPath)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if route matches election-specific patterns
     * 
     * @param string|null $routeName Named route identifier
     * @param string $path Request path
     * @return bool True if it matches election patterns
     */
    protected function matchesElectionRoutePatterns(?string $routeName, string $path): bool
    {
        $electionPatterns = [
            'dashboard', 'vote.*', 'code.*', 'result.*', 'voter.*',
            'candidacy.*', 'post.*', 'election.*', 'publisher.*'
        ];
        
        if ($routeName) {
            foreach ($electionPatterns as $pattern) {
                if (Str::is($pattern, $routeName)) {
                    return true;
                }
            }
        }
        
        $electionPaths = ['dashboard', 'vote', 'voting', 'code', 'result', 'candidate', 'election'];
        foreach ($electionPaths as $electionPath) {
            if (Str::contains($path, $electionPath)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Validate client access to specific election
     * 
     * @param User $user The user requesting access
     * @param Election $election The target election
     * @return bool True if access is valid
     */
    protected function validateClientElectionAccess(User $user, Election $election): bool
    {
        // Use the service's validation logic
        try {
            return ElectionContextService::validateClientAccess($user, $election);
        } catch (Exception $e) {
            Log::warning('Client election access validation failed', [
                'user_id' => $user->id,
                'election_id' => $election->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Handle missing mandatory election context
     * 
     * @param Request $request The incoming request
     * @param string $requestId Request tracking ID
     * @return Response Error response
     */
    protected function handleMissingMandatoryContext(Request $request, string $requestId): Response
    {
        $this->logSecurityEvent('missing_mandatory_context', $request, $requestId, [
            'route' => $request->route()?->getName(),
            'path' => $request->path(),
            'user_id' => $request->user()?->id
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'election_context_required',
                'message' => 'This operation requires an active election context.',
                'request_id' => $requestId,
                'available_actions' => $this->getAvailableActions($request)
            ], 403);
        }
        
        return redirect()->route('dashboard')
            ->with('error', 'Please select an active election to continue.')
            ->with('request_id', $requestId);
    }
    
    /**
     * Handle unauthorized access attempts
     * 
     * @param Request $request The incoming request
     * @param UnauthorizedDatabaseOperationException $exception The authorization exception
     * @param string $requestId Request tracking ID
     * @return Response Error response
     */
    protected function handleUnauthorizedAccess(Request $request, UnauthorizedDatabaseOperationException $exception, string $requestId): Response
    {
        $this->logSecurityEvent('unauthorized_access_attempt', $request, $requestId, [
            'error' => $exception->getMessage(),
            'user_id' => $request->user()?->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'access_denied',
                'message' => 'You do not have permission to perform this operation.',
                'request_id' => $requestId
            ], 403);
        }
        
        return redirect()->route('dashboard')
            ->with('error', 'Access denied. Please contact the administrator if you believe this is an error.')
            ->with('request_id', $requestId);
    }
    
    /**
     * Handle general middleware errors
     * 
     * @param Request $request The incoming request
     * @param Exception $exception The error exception
     * @param string $requestId Request tracking ID
     * @return Response Error response
     */
    protected function handleGeneralError(Request $request, Exception $exception, string $requestId): Response
    {
        Log::error('Election context middleware error', [
            'request_id' => $requestId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'route' => $request->route()?->getName(),
            'user_id' => $request->user()?->id
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'system_error',
                'message' => 'A system error occurred. Please try again.',
                'request_id' => $requestId
            ], 500);
        }
        
        return redirect()->route('dashboard')
            ->with('error', 'A system error occurred. Please try again or contact support.')
            ->with('request_id', $requestId);
    }
    
    /**
     * Enhance response with election context metadata
     * 
     * @param Response $response The response to enhance
     * @param string $requestId Request tracking ID
     */
    protected function enhanceResponseWithContext(Response $response, string $requestId): void
    {
        // Add context headers for debugging and monitoring
        if (ElectionContextService::isElectionContextSet()) {
            $contextInfo = ElectionContextService::getContextInfo();
            
            $response->headers->set('X-Election-Context', $contextInfo['election_id']);
            $response->headers->set('X-Election-Database', $contextInfo['database_name']);
            $response->headers->set('X-Election-Access-Type', $contextInfo['connection_type']);
        }
        
        $response->headers->set('X-Request-ID', $requestId);
        $response->headers->set('X-Middleware-Version', '2.0.0');
    }
    
    /**
     * Log election context establishment for audit trail
     * 
     * @param Request $request The incoming request
     * @param User $user The authenticated user
     * @param Election $election The established election
     * @param array $contextInfo Context information
     */
    protected function logContextEstablishment(Request $request, User $user, Election $election, array $contextInfo): void
    {
        Log::info('Election context established', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'election_name' => $election->name,
            'access_type' => $contextInfo['connection_type'],
            'database_name' => $contextInfo['database_name'],
            'route' => $request->route()?->getName(),
            'path' => $request->path(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'timestamp' => now()->toISOString()
        ]);
    }
    
    /**
     * Log context establishment failures for debugging
     * 
     * @param Request $request The incoming request
     * @param User $user The authenticated user
     * @param Exception $exception The failure exception
     */
    protected function logContextEstablishmentFailure(Request $request, User $user, Exception $exception): void
    {
        Log::warning('Election context establishment failed', [
            'user_id' => $user->id,
            'error' => $exception->getMessage(),
            'route' => $request->route()?->getName(),
            'path' => $request->path(),
            'ip_address' => $request->ip()
        ]);
    }
    
    /**
     * Log security events for monitoring and compliance
     * 
     * @param string $eventType Type of security event
     * @param Request $request The incoming request
     * @param string $requestId Request tracking ID
     * @param array $additionalData Additional event data
     */
    protected function logSecurityEvent(string $eventType, Request $request, string $requestId, array $additionalData = []): void
    {
        Log::warning("Security event: {$eventType}", array_merge([
            'event_type' => $eventType,
            'request_id' => $requestId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'route' => $request->route()?->getName(),
            'method' => $request->method(),
            'timestamp' => now()->toISOString()
        ], $additionalData));
    }
    
    /**
     * Initialize request tracking for monitoring and debugging
     * 
     * @param Request $request The incoming request
     * @param string $requestId Request tracking ID
     */
    protected function initializeRequestTracking(Request $request, string $requestId): void
    {
        // Store request context for potential use by other middleware or controllers
        $request->attributes->set('election_middleware_request_id', $requestId);
        $request->attributes->set('election_middleware_start_time', microtime(true));
    }
    
    /**
     * Record performance metrics for monitoring
     * 
     * @param float $startTime Request start time
     * @param Request $request The processed request
     * @param string $requestId Request tracking ID
     */
    protected function recordPerformanceMetrics(float $startTime, Request $request, string $requestId): void
    {
        $executionTime = microtime(true) - $startTime;
        
        $metrics = [
            'request_id' => $requestId,
            'execution_time_ms' => round($executionTime * 1000, 2),
            'route' => $request->route()?->getName(),
            'method' => $request->method(),
            'has_election_context' => ElectionContextService::isElectionContextSet(),
            'connection_type' => ElectionContextService::getConnectionType(),
            'timestamp' => now()->toISOString()
        ];
        
        // Log performance metrics for monitoring
        if ($executionTime > 0.1) { // Log slow requests (>100ms)
            Log::info('Slow election middleware execution', $metrics);
        } else {
            Log::debug('Election middleware performance', $metrics);
        }
        
        // Store metrics for aggregation
        self::$performanceMetrics[] = $metrics;
    }
    
    /**
     * Generate unique request ID for tracking
     * 
     * @return string Unique request identifier
     */
    protected function generateRequestId(): string
    {
        return 'req_' . uniqid() . '_' . substr(md5(microtime(true)), 0, 8);
    }
    
    /**
     * Get available actions for error responses
     * 
     * @param Request $request The incoming request
     * @return array Available user actions
     */
    protected function getAvailableActions(Request $request): array
    {
        $user = $request->user();
        if (!$user) {
            return ['login'];
        }
        
        $actions = ['dashboard'];
        
        if ($user->hasRole(['admin', 'super-admin'])) {
            $actions[] = 'election-management';
        }
        
        if ($user->hasRole('client')) {
            $actions[] = 'client-dashboard';
        }
        
        return $actions;
    }
    
    /**
     * Clean up request after processing (called by terminate method)
     * 
     * @param Request $request The processed request
     * @param Response $response The generated response
     */
    public function terminate(Request $request, $response): void
    {
        $requestId = $request->attributes->get('election_middleware_request_id');
        
        // Log request completion
        if (ElectionContextService::isElectionContextSet()) {
            $contextInfo = ElectionContextService::getContextInfo();
            
            Log::debug('Election context request completed', [
                'request_id' => $requestId,
                'election_id' => $contextInfo['election_id'],
                'response_status' => $response->getStatusCode(),
                'context_type' => $contextInfo['connection_type']
            ]);
        }
        
        // Cleanup connections if needed (optional - for memory management)
        ElectionContextService::cleanupUnusedConnections();
        
        // Clear performance metrics if they get too large
        if (count(self::$performanceMetrics) > 1000) {
            self::$performanceMetrics = array_slice(self::$performanceMetrics, -100);
        }
    }
    
    /**
     * Get performance statistics for monitoring
     * 
     * @return array Performance statistics
     */
    public static function getPerformanceStatistics(): array
    {
        $metrics = self::$performanceMetrics;
        
        if (empty($metrics)) {
            return ['no_data' => true];
        }
        
        $executionTimes = array_column($metrics, 'execution_time_ms');
        
        return [
            'total_requests' => count($metrics),
            'average_execution_time_ms' => round(array_sum($executionTimes) / count($executionTimes), 2),
            'max_execution_time_ms' => max($executionTimes),
            'min_execution_time_ms' => min($executionTimes),
            'requests_with_context' => count(array_filter($metrics, fn($m) => $m['has_election_context'])),
            'slow_requests_count' => count(array_filter($executionTimes, fn($t) => $t > 100))
        ];
    }
}