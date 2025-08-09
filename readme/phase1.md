# 🔒 **PHASE 1: PRE-ELECTION SEALING - DETAILED TODO**

## **📋 TODO 1.1: UPDATE ELECTION MODEL** (15 minutes)

### **Add Missing Methods to Election.php**

Add these methods to your `app/Models/Election.php`:

```php
/**
 * Enhanced canViewResults method for phase integration
 */
public function canViewResults(): bool
{
    // Emergency override (committee can force publication)
    if (Setting::isEnabled('results_published')) {
        return true;
    }

    // Phase-based logic (your seal/unseal system)
    switch ($this->getCurrentPhase()) {
        case 'sealed':
            return false;  // Before election: results sealed
        
        case 'voting':
            return false;  // During election: results locked
        
        case 'unsealing':
            return false;  // After election: waiting for publishers to unseal
        
        case 'published':
            return $this->results_published && $this->authorization_complete;
        
        default:
            return false;
    }
}

/**
 * Enhanced startSealing method
 */
public function startSealing(): bool
{
    if ($this->getCurrentPhase() !== 'sealed') {
        $this->update(['phase' => 'sealed']);
    }

    $result = $this->startAuthorization();
    
    if ($result['success']) {
        Log::info('Sealing process started', [
            'election_id' => $this->id,
            'session_id' => $result['session_id']
        ]);
        return true;
    }
    
    return false;
}

/**
 * Complete sealing and enable voting
 */
public function completeSealingProcess(): bool
{
    $this->update([
        'phase' => 'voting',
        'authorization_complete' => true,
        'authorization_completed_at' => now(),
        'status' => 'active', // Enable voting system
    ]);
    
    Log::info('Sealing completed - Voting system activated', [
        'election_id' => $this->id,
        'phase_transition' => 'sealed → voting'
    ]);
    
    return true;
}
```

### **Update Fillable and Casts**

Ensure these are in your `Election.php`:

```php
protected $fillable = [
    // ... existing fields
    'phase',  // ✅ CRITICAL: Add this
    // ... rest of fields
];

protected $casts = [
    // ... existing casts
    'phase' => 'string',  // ✅ CRITICAL: Add this
    // ... rest of casts
];
```

---

## **📋 TODO 1.2: COMPLETE PUBLISHER AUTHORIZATION CONTROLLER** (30 minutes)

### **Replace Your Empty Controller**

Replace your `app/Http/Controllers/Election/PublisherAuthorizationController.php` with:

```php
<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Publisher;
use App\Models\ResultAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PublisherAuthorizationController extends Controller
{
    /**
     * Show authorization interface (renders Authorization.vue)
     */
    public function index(Request $request)
    {
        // Your middleware provides validated data
        $publisher = $request->input('publisher');
        $election = $request->input('current_election');
        
        $phase = $election->getCurrentPhase();
        
        $currentAuthorization = $publisher->getCurrentAuthorization(
            $election->id, 
            $election->authorization_session_id
        );

        $progress = ResultAuthorization::getAuthorizationProgress(
            $election->id,
            $election->authorization_session_id
        );

        $agreedPublishers = ResultAuthorization::getCompletedAuthorizations(
            $election->id,
            $election->authorization_session_id
        );

        $pendingPublishers = ResultAuthorization::getPendingPublishers(
            $election->id,
            $election->authorization_session_id
        );

        return Inertia::render('Publisher/Authorization', [
            'phase' => $phase,
            'publisher' => [
                'id' => $publisher->id,
                'name' => $publisher->name,
                'title' => $publisher->title,
                'agreed' => $currentAuthorization ? $currentAuthorization->agreed : false,
                'agreed_at' => $currentAuthorization ? $currentAuthorization->agreed_at : null,
            ],
            'election' => [
                'id' => $election->id,
                'name' => $election->name,
                'phase' => $phase,
                'authorization_deadline' => $election->authorization_deadline,
            ],
            'progress' => [
                'required' => $progress['required'],
                'agreed' => $progress['completed'],
                'remaining' => $progress['remaining'],
                'percentage' => $progress['percentage'],
                'complete' => $progress['is_complete'],
            ],
            'agreedPublishers' => $agreedPublishers->map(function($auth) {
                return [
                    'name' => $auth->publisher->name,
                    'title' => $auth->publisher->title,
                    'agreed_at' => $auth->agreed_at,
                ];
            }),
            'pendingPublishers' => $pendingPublishers->map(function($pub) {
                return [
                    'name' => $pub->name,
                    'title' => $pub->title,
                ];
            }),
        ]);
    }

    /**
     * Handle authorization form submission
     */
    public function authorize(Request $request)
    {
        $request->validate([
            'authorization_password' => 'required|string',
            'agree' => 'required|accepted',
        ], [
            'authorization_password.required' => 'प्राधिकरण पासवर्ड आवश्यक छ।',
            'agree.accepted' => 'तपाईंले सहमति दिनुपर्छ।',
        ]);

        $publisher = $request->input('publisher');
        $election = $request->input('current_election');

        if ($publisher->hasAuthorized($election->id, $election->authorization_session_id)) {
            return back()->with('error', 'तपाईंले पहिले नै प्राधिकरण दिनुभएको छ।');
        }

        $result = $publisher->authorizeResults(
            $election->id,
            $election->authorization_session_id,
            $request->authorization_password,
            [
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toISOString(),
                'phase' => $election->getCurrentPhase(),
            ],
            $request->ip(),
            $request->userAgent()
        );

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        Log::info('Publisher authorization completed', [
            'publisher_id' => $publisher->id,
            'election_id' => $election->id,
            'phase' => $election->getCurrentPhase(),
        ]);

        // 🔑 KEY: Check if sealing is complete
        $allComplete = ResultAuthorization::areAllAuthorizationsComplete(
            $election->id,
            $election->authorization_session_id
        );

        if ($allComplete && $election->getCurrentPhase() === 'sealed') {
            // Complete sealing and activate voting system
            $election->completeSealingProcess();
        }

        return back()->with('success', 'प्राधिकरण सफल भयो।');
    }

    /**
     * API: Real-time progress
     */
    public function progress()
    {
        $election = Election::current();
        
        if (!$election || !$election->authorization_session_id) {
            return response()->json(['error' => 'No active authorization session'], 400);
        }

        $progress = ResultAuthorization::getAuthorizationProgress(
            $election->id,
            $election->authorization_session_id
        );

        return response()->json([
            'progress' => [
                'required' => $progress['required'],
                'agreed' => $progress['completed'],
                'remaining' => $progress['remaining'],
                'percentage' => $progress['percentage'],
                'complete' => $progress['is_complete'],
            ],
            'phase' => $election->getCurrentPhase(),
            'deadline' => $election->authorization_deadline,
        ]);
    }

    /**
     * Committee: Start sealing process
     */
    public function startSealing(Request $request)
    {
        $election = Election::current();
        if (!$election) {
            return response()->json(['error' => 'No active election'], 400);
        }

        $result = $election->startSealing();

        if ($result) {
            return response()->json([
                'message' => 'Sealing process started successfully',
                'phase' => 'sealed',
            ]);
        }

        return response()->json(['error' => 'Failed to start sealing process'], 500);
    }
}
```

---

## **📋 TODO 1.3: FIX ROUTES** (10 minutes)

### **Update electionRoutes.php**

Add these routes to your `routes/election/electionRoutes.php`:

```php
// Publisher Authorization Routes (Phase 1 & 3)
Route::middleware(['auth:sanctum', 'verified', 'publisher'])->group(function () {
    Route::get('/publisher/authorize', [PublisherAuthorizationController::class, 'index'])
        ->name('publisher.authorize.index');
    
    Route::post('/publisher/authorize', [PublisherAuthorizationController::class, 'authorize'])
        ->name('publisher.authorize.submit');
});

// Real-time progress API
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/api/authorization-progress', [PublisherAuthorizationController::class, 'progress'])
        ->name('api.authorization.progress');
});

// Committee routes
Route::middleware(['auth:sanctum', 'verified', 'role:election-committee,super-admin'])->group(function () {
    Route::post('/committee/election/start-sealing', [PublisherAuthorizationController::class, 'startSealing'])
        ->name('committee.election.start-sealing');
});
```

---

## **📋 TODO 1.4: CREATE TEST DATA** (15 minutes)

### **Run This in Tinker**

```bash
php artisan tinker
```

```php
// Create test election
$election = App\Models\Election::create([
    'name' => 'NRNA EU Election 2025',
    'description' => 'Test election for seal/unseal system',
    'voting_start_time' => now()->addDay(), // Tomorrow
    'voting_end_time' => now()->addDays(2), // Day after tomorrow
    'status' => 'draft', // Not active yet
    'phase' => 'sealed', // Start with sealed
    'results_verified' => false,
]);

// Create test publishers
$users = App\Models\User::limit(3)->get();
foreach ($users as $index => $user) {
    App\Models\Publisher::create([
        'publisher_id' => 'PUB_' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
        'user_id' => $user->id,
        'name' => $user->name,
        'title' => 'Election Publisher ' . ($index + 1),
        'should_agree' => true,
        'authorization_password' => bcrypt('test123'),
        'is_active' => true,
        'priority_order' => $index + 1
    ]);
}

// Give first user committee role
$firstUser = App\Models\User::first();
$firstUser->update(['is_committee_member' => true]);

echo "Phase 1 test data created!\n";
echo "Election ID: " . $election->id . "\n";
echo "Publishers created: " . App\Models\Publisher::count() . "\n";
```

---

## **📋 TODO 1.5: TEST PHASE 1** (20 minutes)

### **Test 1: Committee Starts Sealing**

1. **Login as committee user**
2. **Test API call** (Postman or browser dev tools):
```javascript
fetch('/committee/election/start-sealing', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
})
```

### **Test 2: Publisher Sealing**

1. **Login as publisher user**
2. **Visit**: `/publisher/authorize`
3. **Should see**: "Seal Container" interface
4. **Enter password**: `test123`
5. **Check agree box and submit**
6. **Repeat for all publishers**

### **Test 3: Verify Sealing Complete**

```bash
php artisan tinker
```

```php
$election = App\Models\Election::current();
echo "Current phase: " . $election->getCurrentPhase();
echo "\nStatus: " . $election->status;
// Should show: voting phase, active status
```

---

## **📋 TODO 1.6: INTEGRATION WITH PHASE 2** (10 minutes)

### **Add Phase Check to Your Voting System**

In your existing voting controllers, add this check at the beginning:

```php
// Add this to your VoteController methods
public function create()
{
    $election = Election::current();
    
    // 🔑 INTEGRATION: Check if voting is allowed
    if (!$election || $election->getCurrentPhase() !== 'voting') {
        return redirect()->route('dashboard')
            ->with('error', 'मतदान अहिले उपलब्ध छैन। | Voting is not currently available.');
    }
    
    // Your existing voting logic continues unchanged...
}
```

### **Update ElectionController Dashboard**

Add phase information to your dashboard:

```php
// In your ElectionController::dashboard() method, add:
$election = Election::current();
$electionPhase = $election ? $election->getCurrentPhase() : null;

// Add to Inertia render data:
'electionPhase' => $electionPhase,
'canVote' => $election && $electionPhase === 'voting',
```# 🔒 **PHASE 1: PRE-ELECTION SEALING - DETAILED TODO**

## **📋 TODO 1.1: UPDATE ELECTION MODEL** (15 minutes)

### **Add Missing Methods to Election.php**

Add these methods to your `app/Models/Election.php`:

```php
/**
 * Enhanced canViewResults method for phase integration
 */
public function canViewResults(): bool
{
    // Emergency override (committee can force publication)
    if (Setting::isEnabled('results_published')) {
        return true;
    }

    // Phase-based logic (your seal/unseal system)
    switch ($this->getCurrentPhase()) {
        case 'sealed':
            return false;  // Before election: results sealed
        
        case 'voting':
            return false;  // During election: results locked
        
        case 'unsealing':
            return false;  // After election: waiting for publishers to unseal
        
        case 'published':
            return $this->results_published && $this->authorization_complete;
        
        default:
            return false;
    }
}

/**
 * Enhanced startSealing method
 */
public function startSealing(): bool
{
    if ($this->getCurrentPhase() !== 'sealed') {
        $this->update(['phase' => 'sealed']);
    }

    $result = $this->startAuthorization();
    
    if ($result['success']) {
        Log::info('Sealing process started', [
            'election_id' => $this->id,
            'session_id' => $result['session_id']
        ]);
        return true;
    }
    
    return false;
}

/**
 * Complete sealing and enable voting
 */
public function completeSealingProcess(): bool
{
    $this->update([
        'phase' => 'voting',
        'authorization_complete' => true,
        'authorization_completed_at' => now(),
        'status' => 'active', // Enable voting system
    ]);
    
    Log::info('Sealing completed - Voting system activated', [
        'election_id' => $this->id,
        'phase_transition' => 'sealed → voting'
    ]);
    
    return true;
}
```

### **Update Fillable and Casts**

Ensure these are in your `Election.php`:

```php
protected $fillable = [
    // ... existing fields
    'phase',  // ✅ CRITICAL: Add this
    // ... rest of fields
];

protected $casts = [
    // ... existing casts
    'phase' => 'string',  // ✅ CRITICAL: Add this
    // ... rest of casts
];
```

---

## **📋 TODO 1.2: COMPLETE PUBLISHER AUTHORIZATION CONTROLLER** (30 minutes)

### **Replace Your Empty Controller**

Replace your `app/Http/Controllers/Election/PublisherAuthorizationController.php` with:

```php
<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Publisher;
use App\Models\ResultAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PublisherAuthorizationController extends Controller
{
    /**
     * Show authorization interface (renders Authorization.vue)
     */
    public function index(Request $request)
    {
        // Your middleware provides validated data
        $publisher = $request->input('publisher');
        $election = $request->input('current_election');
        
        $phase = $election->getCurrentPhase();
        
        $currentAuthorization = $publisher->getCurrentAuthorization(
            $election->id, 
            $election->authorization_session_id
        );

        $progress = ResultAuthorization::getAuthorizationProgress(
            $election->id,
            $election->authorization_session_id
        );

        $agreedPublishers = ResultAuthorization::getCompletedAuthorizations(
            $election->id,
            $election->authorization_session_id
        );

        $pendingPublishers = ResultAuthorization::getPendingPublishers(
            $election->id,
            $election->authorization_session_id
        );

        return Inertia::render('Publisher/Authorization', [
            'phase' => $phase,
            'publisher' => [
                'id' => $publisher->id,
                'name' => $publisher->name,
                'title' => $publisher->title,
                'agreed' => $currentAuthorization ? $currentAuthorization->agreed : false,
                'agreed_at' => $currentAuthorization ? $currentAuthorization->agreed_at : null,
            ],
            'election' => [
                'id' => $election->id,
                'name' => $election->name,
                'phase' => $phase,
                'authorization_deadline' => $election->authorization_deadline,
            ],
            'progress' => [
                'required' => $progress['required'],
                'agreed' => $progress['completed'],
                'remaining' => $progress['remaining'],
                'percentage' => $progress['percentage'],
                'complete' => $progress['is_complete'],
            ],
            'agreedPublishers' => $agreedPublishers->map(function($auth) {
                return [
                    'name' => $auth->publisher->name,
                    'title' => $auth->publisher->title,
                    'agreed_at' => $auth->agreed_at,
                ];
            }),
            'pendingPublishers' => $pendingPublishers->map(function($pub) {
                return [
                    'name' => $pub->name,
                    'title' => $pub->title,
                ];
            }),
        ]);
    }

    /**
     * Handle authorization form submission
     */
    public function authorize(Request $request)
    {
        $request->validate([
            'authorization_password' => 'required|string',
            'agree' => 'required|accepted',
        ], [
            'authorization_password.required' => 'प्राधिकरण पासवर्ड आवश्यक छ।',
            'agree.accepted' => 'तपाईंले सहमति दिनुपर्छ।',
        ]);

        $publisher = $request->input('publisher');
        $election = $request->input('current_election');

        if ($publisher->hasAuthorized($election->id, $election->authorization_session_id)) {
            return back()->with('error', 'तपाईंले पहिले नै प्राधिकरण दिनुभएको छ।');
        }

        $result = $publisher->authorizeResults(
            $election->id,
            $election->authorization_session_id,
            $request->authorization_password,
            [
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toISOString(),
                'phase' => $election->getCurrentPhase(),
            ],
            $request->ip(),
            $request->userAgent()
        );

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        Log::info('Publisher authorization completed', [
            'publisher_id' => $publisher->id,
            'election_id' => $election->id,
            'phase' => $election->getCurrentPhase(),
        ]);

        // 🔑 KEY: Check if sealing is complete
        $allComplete = ResultAuthorization::areAllAuthorizationsComplete(
            $election->id,
            $election->authorization_session_id
        );

        if ($allComplete && $election->getCurrentPhase() === 'sealed') {
            // Complete sealing and activate voting system
            $election->completeSealingProcess();
        }

        return back()->with('success', 'प्राधिकरण सफल भयो।');
    }

    /**
     * API: Real-time progress
     */
    public function progress()
    {
        $election = Election::current();
        
        if (!$election || !$election->authorization_session_id) {
            return response()->json(['error' => 'No active authorization session'], 400);
        }

        $progress = ResultAuthorization::getAuthorizationProgress(
            $election->id,
            $election->authorization_session_id
        );

        return response()->json([
            'progress' => [
                'required' => $progress['required'],
                'agreed' => $progress['completed'],
                'remaining' => $progress['remaining'],
                'percentage' => $progress['percentage'],
                'complete' => $progress['is_complete'],
            ],
            'phase' => $election->getCurrentPhase(),
            'deadline' => $election->authorization_deadline,
        ]);
    }

    /**
     * Committee: Start sealing process
     */
    public function startSealing(Request $request)
    {
        $election = Election::current();
        if (!$election) {
            return response()->json(['error' => 'No active election'], 400);
        }

        $result = $election->startSealing();

        if ($result) {
            return response()->json([
                'message' => 'Sealing process started successfully',
                'phase' => 'sealed',
            ]);
        }

        return response()->json(['error' => 'Failed to start sealing process'], 500);
    }
}
```

---

## **📋 TODO 1.3: FIX ROUTES** (10 minutes)

### **Update electionRoutes.php**

Add these routes to your `routes/election/electionRoutes.php`:

```php
// Publisher Authorization Routes (Phase 1 & 3)
Route::middleware(['auth:sanctum', 'verified', 'publisher'])->group(function () {
    Route::get('/publisher/authorize', [PublisherAuthorizationController::class, 'index'])
        ->name('publisher.authorize.index');
    
    Route::post('/publisher/authorize', [PublisherAuthorizationController::class, 'authorize'])
        ->name('publisher.authorize.submit');
});

// Real-time progress API
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/api/authorization-progress', [PublisherAuthorizationController::class, 'progress'])
        ->name('api.authorization.progress');
});

// Committee routes
Route::middleware(['auth:sanctum', 'verified', 'role:election-committee,super-admin'])->group(function () {
    Route::post('/committee/election/start-sealing', [PublisherAuthorizationController::class, 'startSealing'])
        ->name('committee.election.start-sealing');
});
```

---

## **📋 TODO 1.4: CREATE TEST DATA** (15 minutes)

### **Run This in Tinker**

```bash
php artisan tinker
```

```php
// Create test election
$election = App\Models\Election::create([
    'name' => 'NRNA EU Election 2025',
    'description' => 'Test election for seal/unseal system',
    'voting_start_time' => now()->addDay(), // Tomorrow
    'voting_end_time' => now()->addDays(2), // Day after tomorrow
    'status' => 'draft', // Not active yet
    'phase' => 'sealed', // Start with sealed
    'results_verified' => false,
]);

// Create test publishers
$users = App\Models\User::limit(3)->get();
foreach ($users as $index => $user) {
    App\Models\Publisher::create([
        'publisher_id' => 'PUB_' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
        'user_id' => $user->id,
        'name' => $user->name,
        'title' => 'Election Publisher ' . ($index + 1),
        'should_agree' => true,
        'authorization_password' => bcrypt('test123'),
        'is_active' => true,
        'priority_order' => $index + 1
    ]);
}

// Give first user committee role
$firstUser = App\Models\User::first();
$firstUser->update(['is_committee_member' => true]);

echo "Phase 1 test data created!\n";
echo "Election ID: " . $election->id . "\n";
echo "Publishers created: " . App\Models\Publisher::count() . "\n";
```

---

## **📋 TODO 1.5: TEST PHASE 1** (20 minutes)

### **Test 1: Committee Starts Sealing**

1. **Login as committee user**
2. **Test API call** (Postman or browser dev tools):
```javascript
fetch('/committee/election/start-sealing', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
})
```

### **Test 2: Publisher Sealing**

1. **Login as publisher user**
2. **Visit**: `/publisher/authorize`
3. **Should see**: "Seal Container" interface
4. **Enter password**: `test123`
5. **Check agree box and submit**
6. **Repeat for all publishers**

### **Test 3: Verify Sealing Complete**

```bash
php artisan tinker
```

```php
$election = App\Models\Election::current();
echo "Current phase: " . $election->getCurrentPhase();
echo "\nStatus: " . $election->status;
// Should show: voting phase, active status
```

---

## **📋 TODO 1.6: INTEGRATION WITH PHASE 2** (10 minutes)

### **Add Phase Check to Your Voting System**

In your existing voting controllers, add this check at the beginning:

```php
// Add this to your VoteController methods
public function create()
{
    $election = Election::current();
    
    // 🔑 INTEGRATION: Check if voting is allowed
    if (!$election || $election->getCurrentPhase() !== 'voting') {
        return redirect()->route('dashboard')
            ->with('error', 'मतदान अहिले उपलब्ध छैन। | Voting is not currently available.');
    }
    
    // Your existing voting logic continues unchanged...
}
```

### **Update ElectionController Dashboard**

Add phase information to your dashboard:

```php
// In your ElectionController::dashboard() method, add:
$election = Election::current();
$electionPhase = $election ? $election->getCurrentPhase() : null;

// Add to Inertia render data:
'electionPhase' => $electionPhase,
'canVote' => $election && $electionPhase === 'voting',
```