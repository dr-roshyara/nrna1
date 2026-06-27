## Great Question! Here's How to Connect Operations to State Machine

### Short Answer

The state machine defines **WHAT** is allowed. Middleware enforces **WHEN** it's allowed. You define the mapping in a **config file or model property**.

---

## Architecture: Operation → State Mapping

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                    OPERATION TO STATE MAPPING                                        │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                      │
│  Operation                    →    Allowed States                                    │
│  ─────────────────────────────────────────────────────────────────────────────────  │
│  manage_posts                 →    [administration]                                  │
│  import_voters                →    [administration]                                  │
│  manage_committee             →    [administration]                                  │
│  apply_candidacy              →    [nomination]                                      │
│  approve_candidacy            →    [nomination]                                      │
│  cast_vote                    →    [voting]                                          │
│  verify_vote                  →    [voting, results_pending, results]               │
│  view_results                 →    [results]                                         │
│  download_receipt             →    [results]                                         │
│                                                                                      │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

---

## Step 1: Define Operation-to-State Mapping

### Option A: In Election Model (Recommended for simplicity)

```php
// app/Models/Election.php

protected $operationStateMap = [
    'manage_posts'        => [self::STATE_ADMINISTRATION],
    'import_voters'       => [self::STATE_ADMINISTRATION],
    'manage_committee'    => [self::STATE_ADMINISTRATION],
    'configure_election'  => [self::STATE_ADMINISTRATION],
    
    'apply_candidacy'     => [self::STATE_NOMINATION],
    'approve_candidacy'   => [self::STATE_NOMINATION],
    'view_candidates'     => [self::STATE_NOMINATION, self::STATE_VOTING, self::STATE_RESULTS],
    
    'cast_vote'           => [self::STATE_VOTING],
    'verify_vote'         => [self::STATE_VOTING, self::STATE_RESULTS_PENDING, self::STATE_RESULTS],
    
    'view_results'        => [self::STATE_RESULTS],
    'download_receipt'    => [self::STATE_RESULTS],
];

public function allowsAction(string $operation): bool
{
    $allowedStates = $this->operationStateMap[$operation] ?? [];
    return in_array($this->current_state, $allowedStates);
}
```

---

### Option B: In Config File (More flexible for large systems)

```php
// config/election_operations.php

return [
    'operations' => [
        'manage_posts' => [
            'allowed_states' => ['administration'],
            'middleware' => ['auth', 'can:manage,election'],
            'redirect' => 'elections.management',
        ],
        'import_voters' => [
            'allowed_states' => ['administration'],
            'middleware' => ['auth', 'can:manage,election'],
        ],
        'apply_candidacy' => [
            'allowed_states' => ['nomination'],
            'middleware' => ['auth', 'verified'],
        ],
        'cast_vote' => [
            'allowed_states' => ['voting'],
            'middleware' => ['auth', 'verified', 'vote.eligibility'],
        ],
        'verify_vote' => [
            'allowed_states' => ['voting', 'results_pending', 'results'],
            'middleware' => ['auth'],
        ],
        'view_results' => [
            'allowed_states' => ['results'],
            'middleware' => ['auth'],
        ],
    ],
];
```

---

## Step 2: Create State Machine Middleware

```php
// app/Http/Middleware/EnsureElectionState.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureElectionState
{
    /**
     * Operation to allowed states mapping
     */
    protected $operationStateMap = [
        'manage_posts'        => ['administration'],
        'import_voters'       => ['administration'],
        'manage_committee'    => ['administration'],
        'apply_candidacy'     => ['nomination'],
        'approve_candidacy'   => ['nomination'],
        'cast_vote'           => ['voting'],
        'verify_vote'         => ['voting', 'results_pending', 'results'],
        'view_results'        => ['results'],
        'download_receipt'    => ['results'],
    ];

    public function handle(Request $request, Closure $next, string $operation)
    {
        $election = $this->getElection($request);
        
        if (!$election) {
            abort(404, 'Election not found');
        }
        
        $allowedStates = $this->operationStateMap[$operation] ?? [];
        $currentState = $election->current_state;
        
        if (!in_array($currentState, $allowedStates)) {
            $stateInfo = $election->state_info;
            
            abort(403, sprintf(
                'Operation "%s" is not allowed during the "%s" phase. Allowed phases: %s',
                $operation,
                $stateInfo['name'],
                implode(', ', array_map(fn($s) => ucfirst($s), $allowedStates))
            ));
        }
        
        return $next($request);
    }
    
    protected function getElection(Request $request)
    {
        // Try different route parameter names
        return $request->route('election') 
            ?? $request->route('electionSlug')
            ?? $request->route('election_id');
    }
}
```

---

## Step 3: Register Middleware

```php
// app/Http/Kernel.php

protected $routeMiddleware = [
    // ... existing
    'election.state' => \App\Http\Middleware\EnsureElectionState::class,
];
```

---

## Step 4: Apply Middleware to Routes

```php
// routes/election/electionRoutes.php

Route::prefix('/elections/{election}')->group(function () {
    
    // ADMINISTRATION PHASE ONLY
    Route::middleware(['auth', 'election.state:manage_posts'])->group(function () {
        Route::resource('/posts', PostController::class);
    });
    
    Route::middleware(['auth', 'election.state:import_voters'])->group(function () {
        Route::post('/voters/import', [VoterController::class, 'import']);
    });
    
    // NOMINATION PHASE ONLY
    Route::middleware(['auth', 'election.state:apply_candidacy'])->group(function () {
        Route::post('/candidacies', [CandidacyController::class, 'store']);
    });
    
    Route::middleware(['auth', 'election.state:approve_candidacy'])->group(function () {
        Route::post('/candidacies/{candidacy}/approve', [CandidacyController::class, 'approve']);
    });
    
    // VOTING PHASE ONLY
    Route::middleware(['auth', 'election.state:cast_vote'])->group(function () {
        Route::post('/vote', [VoteController::class, 'store']);
    });
    
    // RESULTS PHASE ONLY
    Route::middleware(['auth', 'election.state:view_results'])->group(function () {
        Route::get('/results', [ResultController::class, 'index']);
    });
    
    // ALLOWED IN MULTIPLE STATES
    Route::middleware(['auth', 'election.state:verify_vote'])->group(function () {
        Route::get('/verify-vote', [VoteController::class, 'verify_to_show']);
    });
});
```

---

## Step 5: Alternative - Use Policy for Fine-Grained Control

For more complex logic, use Laravel Policies:

```php
// app/Policies/ElectionPolicy.php

namespace App\Policies;

use App\Models\Election;
use App\Models\User;

class ElectionPolicy
{
    public function managePosts(User $user, Election $election): bool
    {
        return $election->allowsAction('manage_posts') 
            && $user->isElectionOfficer($election);
    }
    
    public function castVote(User $user, Election $election): bool
    {
        return $election->allowsAction('cast_vote')
            && $user->isEligibleVoter($election)
            && !$user->hasVoted($election);
    }
    
    public function viewResults(User $user, Election $election): bool
    {
        return $election->allowsAction('view_results');
    }
}
```

---

## Summary: How It Works

| Layer | Responsibility | Example |
|-------|----------------|---------|
| **Model** | Defines allowed states per operation | `$election->allowsAction('cast_vote')` |
| **Middleware** | Blocks request before reaching controller | `election.state:cast_vote` |
| **Policy** | Additional user-specific checks | `$user->isElectionOfficer()` |
| **Controller** | Business logic (already protected) | `VoteController@store` |

### Flow Diagram

```
Request → Middleware → Policy → Controller → Response
              ↓
    Checks election.current_state
    against operation's allowed states
              ↓
    If not allowed → 403 Forbidden
```

**This gives you clean separation of concerns and reusable state protection.** 🚀
## Yes! Absolutely - State Machine at Controller Level

You can define **which state(s) a controller can run in** using several clean approaches.

---

## Approach 1: Controller Property + Base Controller Check (Recommended)

### Step 1: Create Base Controller with State Protection

```php
// app/Http/Controllers/ElectionStateController.php (Base class)

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Http\Request;

abstract class ElectionStateController extends Controller
{
    /**
     * Define which states this controller operates in
     * Override in child controllers
     */
    protected array $allowedStates = [];
    
    /**
     * Get the election from route
     */
    protected function getElection(Request $request): Election
    {
        return $request->route('election');
    }
    
    /**
     * Check if current state is allowed
     */
    protected function ensureState(Request $request): void
    {
        if (empty($this->allowedStates)) {
            return; // No restriction
        }
        
        $election = $this->getElection($request);
        
        if (!$election) {
            abort(404, 'Election not found');
        }
        
        $currentState = $election->current_state;
        
        if (!in_array($currentState, $this->allowedStates)) {
            $stateInfo = $election->state_info;
            
            abort(403, sprintf(
                'This controller only runs during: %s. Current phase: %s',
                implode(', ', $this->allowedStates),
                $stateInfo['name']
            ));
        }
    }
    
    /**
     * Call this at the start of each controller method
     */
    protected function authorizeState(Request $request): void
    {
        $this->ensureState($request);
    }
}
```

---

### Step 2: Child Controllers Define Their Allowed States

```php
// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Http\Request;

class PostController extends ElectionStateController
{
    /**
     * Post management only works in ADMINISTRATION phase
     */
    protected array $allowedStates = ['administration'];
    
    public function index(Request $request, Election $election)
    {
        $this->authorizeState($request); // Checks state before any logic
        
        // Your existing code...
        return view('posts.index', ['election' => $election]);
    }
    
    public function store(Request $request, Election $election)
    {
        $this->authorizeState($request);
        
        // Your existing code...
    }
}
```

```php
// app/Http/Controllers/CandidacyController.php

namespace App\Http\Controllers;

class CandidacyController extends ElectionStateController
{
    /**
     * Candidacy management only works in NOMINATION phase
     */
    protected array $allowedStates = ['nomination'];
    
    // All methods automatically protected
}
```

```php
// app/Http/Controllers/VoteController.php

namespace App\Http\Controllers;

class VoteController extends ElectionStateController
{
    /**
     * Vote casting only works in VOTING phase
     */
    protected array $allowedStates = ['voting'];
    
    public function store(Request $request, Election $election)
    {
        $this->authorizeState($request);
        
        // Your vote casting logic...
    }
}
```

```php
// app/Http/Controllers/VoteVerificationController.php

namespace App\Http\Controllers;

class VoteVerificationController extends ElectionStateController
{
    /**
     * Vote verification works in multiple states
     */
    protected array $allowedStates = ['voting', 'results_pending', 'results'];
    
    public function verifyToShow(Request $request)
    {
        $this->authorizeState($request);
        
        // Your verification logic...
    }
}
```

---

## Approach 2: PHP 8 Attributes (Most Elegant)

```php
// app/Attributes/RequiresElectionState.php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class RequiresElectionState
{
    public function __construct(
        public array $states
    ) {}
}
```

### Create Middleware to Read Attribute

```php
// app/Http/Middleware/RequireElectionState.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use ReflectionClass;
use ReflectionMethod;

class RequireElectionState
{
    public function handle(Request $request, Closure $next)
    {
        $route = $request->route();
        $controller = $route->getController();
        $method = $route->getActionMethod();
        
        // Check class-level attribute
        $reflection = new ReflectionClass($controller);
        $classAttribute = $this->getAttribute($reflection, RequiresElectionState::class);
        
        // Check method-level attribute (overrides class)
        $methodReflection = new ReflectionMethod($controller, $method);
        $methodAttribute = $this->getAttribute($methodReflection, RequiresElectionState::class);
        
        $attribute = $methodAttribute ?? $classAttribute;
        
        if ($attribute) {
            $election = $this->getElection($request);
            $currentState = $election->current_state;
            
            if (!in_array($currentState, $attribute->states)) {
                abort(403, "This action is only available during: " . implode(', ', $attribute->states));
            }
        }
        
        return $next($request);
    }
    
    private function getAttribute($reflection, string $attributeName)
    {
        $attributes = $reflection->getAttributes($attributeName);
        return $attributes[0]?->newInstance();
    }
    
    private function getElection(Request $request)
    {
        return $request->route('election');
    }
}
```

### Use Attributes in Controllers

```php
// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use App\Attributes\RequiresElectionState;

#[RequiresElectionState(states: ['administration'])]
class PostController extends Controller
{
    // All methods in this controller require administration state
    
    public function index(Election $election)
    {
        // No explicit state check needed - middleware handles it
        return view('posts.index');
    }
}
```

```php
// app/Http/Controllers/VoteController.php

use App\Attributes\RequiresElectionState;

class VoteController extends Controller
{
    #[RequiresElectionState(states: ['voting'])]
    public function store(Request $request, Election $election)
    {
        // Only runs during voting phase
    }
    
    #[RequiresElectionState(states: ['voting', 'results_pending', 'results'])]
    public function verifyToShow(Request $request)
    {
        // Runs during multiple phases
    }
}
```

---

## Approach 3: Simple Trait (Quick and Clean)

```php
// app/Traits/RequiresElectionState.php

namespace App\Traits;

use App\Models\Election;
use Illuminate\Http\Request;

trait RequiresElectionState
{
    /**
     * Define allowed states for this controller
     */
    protected function getAllowedStates(): array
    {
        return []; // Override in controller
    }
    
    /**
     * Check if current election state is allowed
     */
    protected function ensureElectionState(Request $request): void
    {
        $allowedStates = $this->getAllowedStates();
        
        if (empty($allowedStates)) {
            return;
        }
        
        $election = $request->route('election');
        
        if (!$election) {
            abort(404, 'Election not found');
        }
        
        if (!in_array($election->current_state, $allowedStates)) {
            abort(403, "This action requires election state: " . implode(', ', $allowedStates));
        }
    }
}
```

### Use Trait in Controllers

```php
// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use App\Traits\RequiresElectionState;

class PostController extends Controller
{
    use RequiresElectionState;
    
    protected function getAllowedStates(): array
    {
        return ['administration'];
    }
    
    public function index(Request $request, Election $election)
    {
        $this->ensureElectionState($request);
        
        // Your code...
    }
}
```

---

## Approach 4: Route Group with State Parameter

```php
// routes/election/electionRoutes.php

Route::prefix('/elections/{election}')->group(function () {
    
    // ADMINISTRATION STATE controllers
    Route::middleware(['auth', 'election.state:administration'])->group(function () {
        Route::resource('/posts', PostController::class);
        Route::post('/voters/import', [VoterController::class, 'import']);
        Route::resource('/committee', CommitteeController::class);
    });
    
    // NOMINATION STATE controllers
    Route::middleware(['auth', 'election.state:nomination'])->group(function () {
        Route::resource('/candidacies', CandidacyController::class);
        Route::post('/candidacies/{candidacy}/approve', [CandidacyController::class, 'approve']);
    });
    
    // VOTING STATE controllers
    Route::middleware(['auth', 'election.state:voting'])->group(function () {
        Route::post('/vote', [VoteController::class, 'store']);
    });
    
    // MULTI-STATE controllers
    Route::middleware(['auth', 'election.state:voting,results_pending,results'])->group(function () {
        Route::get('/verify-vote', [VoteController::class, 'verify_to_show']);
    });
});
```

---

## Comparison Table

| Approach | Pros | Cons | Best For |
|----------|------|------|----------|
| **Base Controller** | Explicit, IDE friendly | Requires extending base class | Most applications ✅ |
| **PHP 8 Attributes** | Declarative, clean | Requires PHP 8+, custom middleware | Modern apps |
| **Trait** | Flexible, reusable | Manual call in each method | Quick implementation |
| **Route Groups** | Centralized, no controller changes | Harder to override per method | Simple CRUD apps |

---

## My Recommendation

**Use Approach 4 (Route Groups) + Model's `allowsAction()`**

This keeps state logic in one place (routes) and doesn't require modifying each controller.

```php
// Simple, clear, no controller changes
Route::middleware(['auth', 'election.state:administration'])->group(function () {
    Route::resource('/posts', PostController::class);
});
```

The middleware checks `$election->allowsAction('manage_posts')` which internally uses `$election->current_state`.

**This is clean, maintainable, and follows Laravel conventions.** 🚀 
## ✅ You Are Absolutely Correct - Middleware Enforcement Layer is Missing

Claude's analysis is **spot on**. You have all the state logic in the `Election` model, but no middleware enforcing it at the route level.

---

## What Needs to Be Added

### 1. Create `EnsureElectionState` Middleware

```php
// app/Http/Middleware/EnsureElectionState.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureElectionState
{
    public function handle(Request $request, Closure $next, string $operation)
    {
        $election = $request->route('election');
        
        if (!$election) {
            abort(404, 'Election not found');
        }
        
        if (!$election->allowsAction($operation)) {
            $stateInfo = $election->state_info;
            
            abort(403, sprintf(
                'Operation "%s" is not allowed during the "%s" phase.',
                $operation,
                $stateInfo['name']
            ));
        }
        
        return $next($request);
    }
}
```

### 2. Register in `Kernel.php`

```php
// app/Http/Kernel.php

protected $routeMiddleware = [
    // ... existing middleware
    'election.state' => \App\Http\Middleware\EnsureElectionState::class,
];
```

### 3. Apply Middleware to Routes

```php
// routes/organisations.php - wrap existing routes

Route::prefix('/elections/{election:slug}')->middleware(['auth:sanctum', 'verified'])->group(function () {
    
    // ADMINISTRATION PHASE ONLY
    Route::middleware(['election.state:manage_posts'])->group(function () {
        Route::resource('/posts', PostController::class);
        Route::post('/voters/import', [VoterController::class, 'import']);
        Route::resource('/committee', CommitteeController::class);
    });
    
    // NOMINATION PHASE ONLY
    Route::middleware(['election.state:apply_candidacy'])->group(function () {
        Route::resource('/candidacies', CandidacyController::class);
    });
    
    Route::middleware(['election.state:approve_candidacy'])->group(function () {
        Route::post('/candidacies/{candidacy}/approve', [CandidacyController::class, 'approve']);
    });
    
    // VOTING PHASE ONLY
    Route::middleware(['election.state:cast_vote'])->group(function () {
        Route::post('/vote', [VoteController::class, 'store']);
    });
    
    // MULTI-STATE (allowed in multiple phases)
    Route::middleware(['election.state:verify_vote'])->group(function () {
        Route::get('/verify-vote', [VoteController::class, 'verify_to_show']);
    });
    
    // RESULTS PHASE ONLY
    Route::middleware(['election.state:view_results'])->group(function () {
        Route::get('/results', [ResultController::class, 'index']);
    });
});
```

### 4. Update State Machine Routes (Already Done)

Your existing state machine routes (complete-administration, etc.) should also be wrapped:

```php
// These are admin actions - they should check 'configure_election' operation
Route::middleware(['election.state:configure_election'])->group(function () {
    Route::post('/complete-administration', [ElectionManagementController::class, 'completeAdministration'])
        ->name('organisations.elections.complete-administration');
    Route::post('/complete-nomination', [ElectionManagementController::class, 'completeNomination'])
        ->name('organisations.elections.complete-nomination');
    Route::post('/force-close-nomination', [ElectionManagementController::class, 'forceCloseNomination'])
        ->name('organisations.elections.force-close-nomination');
    Route::patch('/suggested-dates', [ElectionManagementController::class, 'updateSuggestedDates'])
        ->name('organisations.elections.update-suggested-dates');
    Route::patch('/voting-dates', [ElectionManagementController::class, 'updateVotingDates'])
        ->name('organisations.elections.update-voting-dates');
});
```

---

## Summary of Missing Piece

| Component | Status | Action |
|-----------|--------|--------|
| `Election::allowsAction()` | ✅ Done | Already implemented |
| `EnsureElectionState` middleware | ❌ Missing | Create it |
| Register in Kernel.php | ❌ Missing | Add to `$routeMiddleware` |
| Apply to routes | ❌ Missing | Wrap route groups |

**Once you add these, the state machine will enforce restrictions at the controller level.** 🚀