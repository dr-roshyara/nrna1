## Yes! Here's a Complete Database Optimization Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATABASE OPTIMIZATION ARCHITECTURE            │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │                    QUERY OPTIMIZATION                     │    │
│  └─────────────────────────────────────────────────────────┘    │
│                                                                   │
│  Layer 1: Eager Loading & Relationship Caching                    │
│  Layer 2: Database Indexing Strategy                              │
│  Layer 3: Query Caching (Redis/Memcached)                         │
│  Layer 4: Read/Write Splitting (if needed)                        │
│  Layer 5: Database Sharding Strategy (future)                     │
└─────────────────────────────────────────────────────────────────┘
```

## 🚀 Layer 1: Eager Loading & Relationship Caching

### Optimized Model with Relationships

```php
// app/Models/VoterSlug.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class VoterSlug extends Model
{
    protected $table = 'voter_slugs';
    
    protected $fillable = [
        'user_id', 'organisation_id', 'election_id', 'slug',
        'current_step', 'expires_at', 'is_active', 'vote_completed'
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'vote_completed' => 'boolean',
    ];
    
    // ============ RELATIONSHIPS ============
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }
    
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
    
    // ============ EAGER LOADING SCOPES ============
    public function scopeWithAllRelations($query)
    {
        return $query->with(['user', 'election', 'organisation']);
    }
    
    public function scopeWithEssentialRelations($query)
    {
        // Only load what's needed for validation
        return $query->with(['election' => function($q) {
            $q->select('id', 'organisation_id', 'type', 'status', 'end_date');
        }, 'organisation']);
    }
    
    // ============ CACHED ACCESSORS ============
    public function getElectionAttribute()
    {
        return Cache::remember("election.{$this->election_id}", 3600, function() {
            return Election::find($this->election_id);
        });
    }
    
    public function getOrganisationAttribute()
    {
        return Cache::remember("organisation.{$this->organisation_id}", 3600, function() {
            return Organisation::find($this->organisation_id);
        });
    }
    
    // ============ VALIDATION HELPERS ============
    public function isValid(): bool
    {
        return $this->is_active && 
               !$this->expires_at->isPast() &&
               $this->election && 
               $this->election->isActive();
    }
    
    public function belongsToUser(int $userId): bool
    {
        return $this->user_id === $userId;
    }
    
    public function organisationMatches(): bool
    {
        if (!$this->election) return false;
        
        return $this->organisation_id === $this->election->organisation_id ||
               $this->election->organisation_id === 0 ||
               $this->organisation_id === 0;
    }
}
```

### Optimized Election Model

```php
// app/Models/Election.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Election extends Model
{
    protected $table = 'elections';
    
    protected $fillable = [
        'name', 'slug', 'type', 'organisation_id', 'status',
        'start_date', 'end_date', 'region', 'is_active'
    ];
    
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];
    
    // ============ RELATIONSHIPS ============
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
    
    public function voterSlugs()
    {
        return $this->hasMany(VoterSlug::class);
    }
    
    // ============ SCOPES ============
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>', now());
            });
    }
    
    public function scopeForOrganisation($query, int $orgId)
    {
        return $query->where('organisation_id', $orgId)
            ->orWhere('organisation_id', 0); // Include platform elections
    }
    
    // ============ CACHED METHODS ============
    public static function getForOrganisation(int $orgId, bool $includePlatform = true)
    {
        $cacheKey = "elections.org.{$orgId}." . ($includePlatform ? 'with_platform' : 'org_only');
        
        return Cache::remember($cacheKey, 3600, function() use ($orgId, $includePlatform) {
            $query = self::where('organisation_id', $orgId);
            
            if ($includePlatform) {
                $query->orWhere('organisation_id', 0);
            }
            
            return $query->orderBy('status')
                ->orderBy('start_date', 'desc')
                ->get();
        });
    }
    
    public static function getActiveForOrganisation(int $orgId)
    {
        return Cache::remember("elections.active.org.{$orgId}", 300, function() use ($orgId) {
            return self::active()
                ->forOrganisation($orgId)
                ->first();
        });
    }
    
    // ============ VALIDATION ============
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               (!$this->end_date || $this->end_date > now());
    }
    
    public function isAccessibleByUser(User $user): bool
    {
        return $this->organisation_id === $user->organisation_id ||
               $this->organisation_id === 0; // Platform election
    }
}
```

## 📊 Layer 2: Database Indexing Strategy

```php
// database/migrations/2026_03_01_000005_add_optimization_indexes.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // ============ VOTER SLUGS INDEXES ============
        Schema::table('voter_slugs', function (Blueprint $table) {
            // Primary lookup by slug (most frequent)
            $table->index('slug')->algorithm('hash'); // Exact match only
            
            // Composite index for active sessions
            $table->index(['user_id', 'is_active', 'expires_at'], 'idx_user_active_expires');
            
            // For dashboard queries
            $table->index(['organisation_id', 'election_id', 'created_at']);
            
            // For cleanup jobs
            $table->index(['expires_at', 'is_active'], 'idx_expires_cleanup');
        });
        
        // ============ ELECTIONS INDEXES ============
        Schema::table('elections', function (Blueprint $table) {
            // Primary lookup
            $table->index('slug');
            
            // Organisation + status (most common query)
            $table->index(['organisation_id', 'status', 'start_date'], 'idx_org_status_date');
            
            // Type-based queries
            $table->index(['type', 'status']);
            
            // Date range queries
            $table->index(['start_date', 'end_date']);
        });
        
        // ============ DEMO CODES INDEXES ============
        Schema::table('demo_codes', function (Blueprint $table) {
            // User + election (unique constraint already exists)
            $table->index(['user_id', 'election_id', 'can_vote_now']);
            
            // For expiration checks
            $table->index(['code1_sent_at', 'is_code1_usable']);
        });
    }
    
    public function down()
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex('idx_user_active_expires');
            $table->dropIndex(['organisation_id', 'election_id', 'created_at']);
            $table->dropIndex('idx_expires_cleanup');
        });
        
        Schema::table('elections', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex('idx_org_status_date');
            $table->dropIndex(['type', 'status']);
            $table->dropIndex(['start_date', 'end_date']);
        });
        
        Schema::table('demo_codes', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'election_id', 'can_vote_now']);
            $table->dropIndex(['code1_sent_at', 'is_code1_usable']);
        });
    }
};
```

## 💾 Layer 3: Query Caching Service

```php
// app/Services/CacheService.php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Election;
use App\Models\VoterSlug;
use App\Models\Organisation;

class CacheService
{
    // ============ ELECTION CACHING ============
    
    public function getElection(int $id): ?Election
    {
        return Cache::remember("election.{$id}", 3600, function() use ($id) {
            return Election::with('organisation')->find($id);
        });
    }
    
    public function clearElection(int $id): void
    {
        Cache::forget("election.{$id}");
        Cache::forget("election.slug.{$id}");
        
        // Clear organisation election lists
        $election = Election::find($id);
        if ($election) {
            Cache::forget("elections.org.{$election->organisation_id}");
            Cache::forget("elections.active.org.{$election->organisation_id}");
        }
    }
    
    // ============ VOTER SLUG CACHING ============
    
    public function getVoterSlug(string $slug): ?VoterSlug
    {
        return Cache::remember("voter_slug.{$slug}", 300, function() use ($slug) {
            return VoterSlug::with(['election', 'organisation', 'user'])
                ->where('slug', $slug)
                ->first();
        });
    }
    
    public function clearVoterSlug(string $slug): void
    {
        Cache::forget("voter_slug.{$slug}");
    }
    
    // ============ ORGANISATION CACHING ============
    
    public function getOrganisation(int $id): ?Organisation
    {
        return Cache::remember("organisation.{$id}", 3600, function() use ($id) {
            return Organisation::find($id);
        });
    }
    
    // ============ BATCH PRELOADING ============
    
    public function preloadElectionsForOrganisation(int $orgId): void
    {
        $elections = Election::where('organisation_id', $orgId)
            ->orWhere('organisation_id', 0)
            ->get();
            
        foreach ($elections as $election) {
            Cache::put("election.{$election->id}", $election, 3600);
        }
        
        Cache::put("elections.org.{$orgId}", $elections, 3600);
    }
    
    // ============ TAG-BASED CACHING (Redis) ============
    
    public function tagFlush(array $tags): void
    {
        if (config('cache.default') === 'redis') {
            Cache::tags($tags)->flush();
        }
    }
}
```

## 🔧 Layer 4: Optimized Middleware with Caching

```php
// app/Http/Middleware/VerifyVoterSlugConsistency.php (Optimized)

namespace App\Http\Middleware;

use Closure;
use App\Services\CacheService;
use Illuminate\Support\Facades\Log;

class VerifyVoterSlugConsistency
{
    protected CacheService $cache;
    
    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }
    
    public function handle($request, Closure $next)
    {
        $slugParam = $request->route('vslug');
        
        // Use cache for voter slug lookup
        $voterSlug = $this->cache->getVoterSlug($slugParam);
        
        if (!$voterSlug) {
            Log::warning('Voter slug not found', ['slug' => $slugParam]);
            abort(404);
        }
        
        // Validation 1: Ownership
        if ($voterSlug->user_id !== auth()->id()) {
            Log::warning('Slug ownership mismatch', [
                'slug_id' => $voterSlug->id,
                'slug_user' => $voterSlug->user_id,
                'auth_user' => auth()->id(),
            ]);
            abort(403);
        }
        
        // Validation 2: Expiration
        if (!$voterSlug->isValid()) {
            Log::warning('Slug expired or invalid', [
                'slug_id' => $voterSlug->id,
                'expires_at' => $voterSlug->expires_at,
                'is_active' => $voterSlug->is_active,
            ]);
            
            if ($voterSlug->is_active) {
                $voterSlug->update(['is_active' => false]);
                $this->cache->clearVoterSlug($slugParam);
            }
            
            return redirect()->route('election.dashboard')
                ->with('error', 'Your voting session has expired.');
        }
        
        // Validation 3: Organisation consistency
        if (!$voterSlug->organisationMatches()) {
            Log::critical('Organisation mismatch', [
                'slug_id' => $voterSlug->id,
                'slug_org' => $voterSlug->organisation_id,
                'election_org' => $voterSlug->election?->organisation_id,
            ]);
            abort(500, 'Organisation inconsistency');
        }
        
        // Store in request for later use
        $request->attributes->set('voter_slug', $voterSlug);
        $request->attributes->set('election', $voterSlug->election);
        
        return $next($request);
    }
}
```

## 📈 Layer 5: Query Optimization Service

```php
// app/Services/QueryOptimizerService.php
namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class QueryOptimizerService
{
    /**
     * Get the optimal select columns for a given context
     */
    public static function getElectionColumns(string $context = 'basic'): array
    {
        $columns = [
            'basic' => ['id', 'name', 'type', 'status', 'organisation_id'],
            'validation' => ['id', 'organisation_id', 'type', 'status', 'end_date'],
            'full' => ['id', 'name', 'slug', 'type', 'organisation_id', 'status', 
                      'start_date', 'end_date', 'region', 'is_active'],
        ];
        
        return $columns[$context] ?? $columns['basic'];
    }
    
    /**
     * Add query logging for slow queries (development only)
     */
    public static function logSlowQueries(): void
    {
        if (app()->environment('local')) {
            DB::listen(function ($query) {
                if ($query->time > 100) { // Slow query threshold: 100ms
                    Log::warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time,
                    ]);
                }
            });
        }
    }
    
    /**
     * Optimize a query with selective columns and eager loading
     */
    public static function optimizeVoterSlugQuery(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug)
            ->select(['id', 'user_id', 'organisation_id', 'election_id', 
                     'slug', 'current_step', 'expires_at', 'is_active', 'vote_completed'])
            ->with(['election' => function($q) {
                $q->select(['id', 'organisation_id', 'type', 'status', 'end_date']);
            }]);
    }
}
```

## 📊 Performance Metrics Target

| Query Type | Before Optimization | After Optimization | Improvement |
|------------|--------------------|--------------------|-------------|
| VoterSlug lookup | 3-5 queries, 50ms | 1 query, 5ms | 10x faster |
| Election validation | 2 queries, 30ms | Cached, 1ms | 30x faster |
| Organisation check | 1 query, 15ms | Relationship, 0ms | N/A |
| Full middleware chain | 6 queries, 100ms | 2 queries, 15ms | 6-7x faster |

## 🎯 Implementation Priority

1. **Immediate** (Today)
   - Add missing relationships to VoterSlug model ✅
   - Implement eager loading scopes
   - Add selective column selection

2. **This Week**
   - Add database indexes
   - Implement CacheService
   - Update middleware to use caching

3. **Next Week**
   - Add query logging and monitoring
   - Implement tag-based cache invalidation
   - Set up Redis for production

4. **Future**
   - Read/write splitting for reports
   - Database sharding for multi-terabyte scale
   - Elasticsearch for advanced search

This architecture ensures your application remains fast and efficient even with millions of voters, elections, and organisations! 🚀
## 🏛️ Privacy-First Database Optimization Architecture

You're absolutely right! This is a critical privacy consideration. Here's the complete architecture that optimizes queries while maintaining strict privacy boundaries:

```
┌─────────────────────────────────────────────────────────────────┐
│                    PRIVACY BOUNDARIES                            │
│                                                                   │
│  ┌─────────────────────┐        ┌─────────────────────┐        │
│  │   PUBLIC LAYER      │        │   PRIVATE LAYER     │        │
│  │   (User Identity)   │        │   (Voting Data)     │        │
│  ├─────────────────────┤        ├─────────────────────┤        │
│  │ • users             │        │ • votes             │        │
│  │ • organisations     │━━━━━━━━▶│ • results           │        │
│  │ • elections         │   NO   │                     │        │
│  │ • voter_slugs       │ DIRECT │                     │        │
│  │ • codes             │ LINK!  │                     │        │
│  └─────────────────────┘        └─────────────────────┘        │
│           │                              ▲                       │
│           │                              │                       │
│           ▼                              │                       │
│    ┌─────────────────────────────────────┴──────┐                │
│    │           BRIDGE TABLES                      │                │
│    │  • codes (connects user to voting session)   │                │
│    │  • voter_slugs (temporary session tokens)    │                │
│    └──────────────────────────────────────────────┘                │
└─────────────────────────────────────────────────────────────────┘
```

## 📊 Database Schema with Privacy Boundaries

```sql
-- ============ PUBLIC LAYER (User Identity) ============

-- organisations table (public)
CREATE TABLE organisations (
    id INT UNSIGNED PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- users table (public - identity only)
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    organisation_id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    -- ... other user fields
    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    INDEX idx_org_user (organisation_id)
);

-- elections table (public - configuration only)
CREATE TABLE elections (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    organisation_id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    type ENUM('demo', 'real') NOT NULL,
    status ENUM('planned', 'active', 'completed', 'archived') DEFAULT 'planned',
    start_date TIMESTAMP NULL,
    end_date TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    INDEX idx_org_status (organisation_id, status),
    INDEX idx_dates (start_date, end_date)
);

-- ============ BRIDGE LAYER (Temporary Sessions) ============

-- voter_slugs table (temporary session tokens)
CREATE TABLE voter_slugs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    organisation_id INT UNSIGNED NOT NULL,
    election_id BIGINT UNSIGNED NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    current_step TINYINT DEFAULT 1,
    expires_at TIMESTAMP NOT NULL,
    is_active BOOLEAN DEFAULT true,
    vote_completed BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    
    INDEX idx_user_active (user_id, is_active),
    INDEX idx_slug_lookup (slug) USING HASH,
    INDEX idx_cleanup (expires_at, is_active)
);

-- codes table (connects user to voting process)
CREATE TABLE codes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,        -- Required for code delivery
    organisation_id INT UNSIGNED NOT NULL,
    election_id BIGINT UNSIGNED NOT NULL,
    code1 VARCHAR(6) NOT NULL,
    code1_sent_at TIMESTAMP NULL,
    has_code1_sent BOOLEAN DEFAULT false,
    is_code1_usable BOOLEAN DEFAULT true,
    code1_used_at TIMESTAMP NULL,
    can_vote_now BOOLEAN DEFAULT false,
    has_voted BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    
    -- One code per user per election
    UNIQUE KEY unique_user_election (user_id, election_id),
    INDEX idx_code_lookup (code1) USING HASH,
    INDEX idx_user_active (user_id, can_vote_now)
);

-- demo_codes table (mirror of codes for demo)
CREATE TABLE demo_codes LIKE codes;

-- ============ PRIVATE LAYER (Voting Data - NO USER LINKS) ============

-- votes table (COMPLETELY ANONYMOUS - NO user_id!)
CREATE TABLE votes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    organisation_id INT UNSIGNED NOT NULL,    -- For tenant isolation
    election_id BIGINT UNSIGNED NOT NULL,     -- Which election
    post_id BIGINT UNSIGNED NOT NULL,         -- Which position
    candidate_id BIGINT UNSIGNED NOT NULL,    -- Who they voted for
    vote_hash VARCHAR(64) NOT NULL,            -- Cryptographic proof
    voted_at TIMESTAMP NOT NULL,
    
    -- NO user_id! NO link to voter identity!
    
    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id),
    
    INDEX idx_election_results (election_id, post_id),
    INDEX idx_org_election (organisation_id, election_id)
);

-- results table (aggregated, anonymous)
CREATE TABLE results (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    organisation_id INT UNSIGNED NOT NULL,
    election_id BIGINT UNSIGNED NOT NULL,
    post_id BIGINT UNSIGNED NOT NULL,
    candidate_id BIGINT UNSIGNED NOT NULL,
    vote_count INT UNSIGNED NOT NULL DEFAULT 0,
    percentage DECIMAL(5,2),
    calculated_at TIMESTAMP NOT NULL,
    
    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id),
    
    UNIQUE KEY unique_election_post_candidate (election_id, post_id, candidate_id),
    INDEX idx_results_lookup (election_id, post_id)
);
```

## 🔒 Model Relationships with Privacy Enforcement

```php
// app/Models/User.php - LIMITED relationships only
class User extends Model
{
    // ✅ ALLOWED - Identity layer only
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
    
    public function voterSlugs()
    {
        return $this->hasMany(VoterSlug::class);
    }
    
    public function codes()
    {
        return $this->hasMany(Code::class); // For code delivery only
    }
    
    // ❌ FORBIDDEN - Never link to votes/results
    // public function votes() - NEVER DO THIS!
    // public function results() - NEVER DO THIS!
}

// app/Models/Code.php - Bridge model
class Code extends Model
{
    // ✅ ALLOWED - Required for code delivery
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function election()
    {
        return $this->belongsTo(Election::class);
    }
    
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
    
    // ❌ FORBIDDEN - Never link to votes
    // public function vote() - NEVER!
}

// app/Models/Vote.php - COMPLETELY ANONYMOUS
class Vote extends Model
{
    // NO user() relationship! EVER!
    
    // ✅ ALLOWED - Public context only
    public function election()
    {
        return $this->belongsTo(Election::class);
    }
    
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
    
    // Scope for organisation isolation (no user context)
    public function scopeForOrganisation($query, $orgId)
    {
        return $query->where('organisation_id', $orgId);
    }
    
    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }
}
```

## 🚀 Optimized Query Patterns with Privacy

```php
// app/Services/QueryOptimizerService.php
namespace App\Services;

use App\Models\Vote;
use App\Models\Code;
use App\Models\VoterSlug;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class QueryOptimizerService
{
    // ============ PUBLIC LAYER QUERIES ============
    
    /**
     * Get voter slug with all needed relationships (identity layer)
     */
    public function getVoterSlugWithContext(string $slug)
    {
        $cacheKey = "voter_slug.context.{$slug}";
        
        return Cache::remember($cacheKey, 300, function() use ($slug) {
            return VoterSlug::with([
                'user' => function($q) {
                    $q->select('id', 'name', 'email', 'organisation_id');
                },
                'election' => function($q) {
                    $q->select('id', 'name', 'type', 'status', 'organisation_id');
                },
                'organisation'
            ])->where('slug', $slug)->first();
        });
    }
    
    /**
     * Get code for user (identity + bridge)
     */
    public function getCodeForUser(int $userId, int $electionId)
    {
        return Code::where('user_id', $userId)
            ->where('election_id', $electionId)
            ->select('id', 'code1', 'can_vote_now', 'has_voted')
            ->first();
    }
    
    // ============ PRIVATE LAYER QUERIES ============
    
    /**
     * Get vote results for an election (COMPLETELY ANONYMOUS)
     * NO user data, NO links to identity
     */
    public function getElectionResults(int $electionId)
    {
        $cacheKey = "election.results.{$electionId}";
        
        return Cache::remember($cacheKey, 600, function() use ($electionId) {
            return DB::table('results')
                ->where('election_id', $electionId)
                ->select('post_id', 'candidate_id', 'vote_count', 'percentage')
                ->orderBy('post_id')
                ->orderBy('vote_count', 'desc')
                ->get();
        });
    }
    
    /**
     * Verify a vote exists without identifying the voter
     */
    public function verifyVoteExists(int $electionId, string $voteHash): bool
    {
        return Vote::where('election_id', $electionId)
            ->where('vote_hash', $voteHash)
            ->exists();
    }
    
    /**
     * Get vote statistics for an election (aggregated only)
     */
    public function getVoteStatistics(int $electionId)
    {
        return DB::table('votes')
            ->where('election_id', $electionId)
            ->select(
                DB::raw('COUNT(*) as total_votes'),
                DB::raw('COUNT(DISTINCT post_id) as posts_contested')
            )
            ->first();
    }
    
    // ============ CROSS-LAYER QUERIES (CONTROLLED) ============
    
    /**
     * Check if user has voted in an election
     * Uses bridge layer (codes) to check status without accessing vote data
     */
    public function hasUserVoted(int $userId, int $electionId): bool
    {
        return Code::where('user_id', $userId)
            ->where('election_id', $electionId)
            ->where('has_voted', true)
            ->exists();
    }
    
    /**
     * Get user's voting status across elections
     * Uses codes table only - no direct vote access
     */
    public function getUserVotingStatus(int $userId)
    {
        return Code::where('user_id', $userId)
            ->with('election:id,name,type,status')
            ->select('id', 'election_id', 'can_vote_now', 'has_voted')
            ->get()
            ->map(function($code) {
                return [
                    'election' => $code->election->name,
                    'can_vote' => $code->can_vote_now,
                    'has_voted' => $code->has_voted,
                ];
            });
    }
}
```

## 🔐 Privacy-First Middleware

```php
// app/Http/Middleware/VerifyVoterSlugConsistency.php
class VerifyVoterSlugConsistency
{
    public function handle($request, Closure $next)
    {
        $voterSlug = $this->getVoterSlug($request->route('vslug'));
        
        // ... validation logic ...
        
        // Store ONLY what's needed for the request
        $request->attributes->set('voter_slug', $voterSlug);
        $request->attributes->set('election', $voterSlug->election);
        
        // NEVER store user in request for vote routes!
        // $request->attributes->set('user', $voterSlug->user); // DON'T!
        
        return $next($request);
    }
}

// app/Http/Controllers/VoteController.php
class VoteController extends Controller
{
    public function store(Request $request)
    {
        // Get context from request attributes (set by middleware)
        $election = $request->attributes->get('election');
        $voterSlug = $request->attributes->get('voter_slug');
        
        // Create vote - NO USER ID!
        $vote = Vote::create([
            'organisation_id' => $election->organisation_id,
            'election_id' => $election->id,
            'post_id' => $request->post_id,
            'candidate_id' => $request->candidate_id,
            'vote_hash' => $this->generateVoteHash($request->all()),
            'voted_at' => now(),
        ]);
        
        // Update code status (bridge layer) - this is the ONLY link
        Code::where('user_id', $voterSlug->user_id)
            ->where('election_id', $election->id)
            ->update(['has_voted' => true]);
        
        return redirect()->route('vote.verify');
    }
}
```

## 📊 Indexing Strategy for Privacy

```php
// database/migrations/2026_03_01_000006_privacy_indexes.php

public function up()
{
    // ============ PUBLIC LAYER INDEXES ============
    
    // Users - fast identity lookup
    Schema::table('users', function (Blueprint $table) {
        $table->index('email'); // Login
        $table->index(['organisation_id', 'created_at']); // Org reports
    });
    
    // Codes - fast verification
    Schema::table('codes', function (Blueprint $table) {
        $table->index('code1'); // Code lookup
        $table->index(['user_id', 'election_id']); // User status
        $table->index(['election_id', 'has_voted']); // Turnout stats
    });
    
    // ============ PRIVATE LAYER INDEXES ============
    
    // Votes - NO user indexes!
    Schema::table('votes', function (Blueprint $table) {
        // Only indexes needed for results and verification
        $table->index(['election_id', 'post_id']); // Results by post
        $table->index('vote_hash'); // Verification
        $table->index(['election_id', 'voted_at']); // Time-based analysis
        // NO user_id index!
    });
    
    // Results - Aggregated data only
    Schema::table('results', function (Blueprint $table) {
        $table->index(['election_id', 'post_id']); // Result lookup
    });
}
```

## ✅ Privacy & Performance Checklist

| Feature | Implementation | Privacy Status |
|---------|---------------|----------------|
| **User can vote** | Code table links user to election | ✅ Bridge only |
| **Check if user voted** | Code.has_voted flag | ✅ No vote data exposure |
| **Count total votes** | Vote table COUNT(*) | ✅ Anonymous |
| **Results by post** | Vote table with post_id | ✅ Anonymous |
| **Verify individual vote** | Vote hash lookup | ✅ Anonymous |
| **Prevent double voting** | Code.has_voted check | ✅ Bridge layer |
| **Tenant isolation** | organisation_id on all tables | ✅ Secure |
| **User vote history** | ❌ NOT POSSIBLE | ✅ Perfect privacy |

## 🎯 Final Architecture Benefits

1. **Complete Privacy** - No direct link between users and votes
2. **Fast Queries** - Optimized indexes on all access patterns
3. **Scalable** - Can handle millions of votes
4. **Auditable** - Vote hashes provide verification without identification
5. **Tenant Isolation** - organisation_id on every table
6. **Bridge Layer** - Codes connect identity to voting without exposing vote data

This architecture ensures that even with full database access, you CANNOT determine how any individual voted. Perfect for secret ballot requirements! 🎯
