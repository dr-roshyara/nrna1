---
name: sovereign-execution-sequence-audit
description: D.5.5 Phase 1C - Verify execution order integrity to prevent hidden authority derivation before resolver
metadata:
  type: audit
  phase: D.5.5
  priority: CRITICAL
  risk_level: HIDDEN_SOVEREIGNTY
---

# D.5.5 Phase 1C: Sovereign Execution Sequence Audit

## Executive Summary

Hidden sovereignty emerges when participation authority is derived BEFORE the resolver sees the request. This can happen through:

- Middleware running before controller
- Request validators (FormRequest::authorize())
- Event listeners triggered before resolver
- Model observers executing before controller
- Async handlers processing before resolver
- Authorization gates checked before resolver

This audit maps execution order and classifies each component as either transport/infrastructure (safe) or authority derivation (dangerous).

---

## Critical Invariant

```
Request → Auth → TenantContext → [TRANSPORT ONLY] → Controller
            ↓
        [AUTHORITY DECISION FORBIDDEN]
            ↓
       Controller → TrustPolicyEvaluator → ElectionCapabilityResolver [ONLY HERE]
```

No component between HTTP request entry and controller invocation may derive participation authority.

---

## HTTP Request Execution Sequence

### Standard Laravel Request Lifecycle

```
1. HttpKernel receives request
   ├─ Register service providers ✅ (transport only)
   │
2. Global middleware stack processes in order
   ├─ App\Http\Middleware\* ⚠️ AUDIT EACH
   │  ├─ Authentication ✅ (identity only)
   │  ├─ TenantContext ✅ (context only)
   │  ├─ ValidateVotingIp ❌ (AUTHORITY DERIVATION)
   │  ├─ CSRF protection ✅ (transport security)
   │  └─ etc.
   │
3. Route middleware stack ⚠️ AUDIT
   ├─ Route-specific guards ⚠️
   └─ Route-specific validators ⚠️
   │
4. FormRequest validation (if used)
   ├─ rules() method ✅ (validation rules only)
   ├─ authorize() method ❌ (DANGEROUS - may derive authority)
   │
5. Route model binding ⚠️ AUDIT
   ├─ Implicit binding callbacks ⚠️
   │
6. Controller invoked
   ├─ Constructor dependency injection ⚠️ (watch for listeners)
   │
7. Controller method executes [AUTHORITY DERIVATION MUST START HERE]
   ├─ TrustPolicyEvaluator::evaluate()
   ├─ ElectionCapabilityResolver::evaluate()
   └─ ✅ ONLY HERE may authority be decided
   │
8. Response construction
   ├─ Data transformation (projection only) ✅
   └─ HTTP response sent
```

---

## Middleware Classification Matrix

### Complete Middleware Stack Audit Template

| Order | Middleware | File Path | Type | Derives Authority? | Safe? | Action |
|-------|------------|-----------|------|-------------------|-------|--------|
| 1 | EncryptCookies | `app/Http/Middleware/EncryptCookies.php` | Transport | ❌ | ✅ | Keep |
| 2 | AddQueuedCookiesToResponse | `app/Http/Middleware/AddQueuedCookiesToResponse.php` | Transport | ❌ | ✅ | Keep |
| 3 | StartSession | `app/Http/Middleware/StartSession.php` | Transport | ❌ | ✅ | Keep |
| 4 | ShareErrorsFromSession | `app/Http/Middleware/ShareErrorsFromSession.php` | Transport | ❌ | ✅ | Keep |
| 5 | VerifyCsrfToken | `app/Http/Middleware/VerifyCsrfToken.php` | Transport | ❌ | ✅ | Keep |
| 6 | SubstituteBindings | Laravel core | Transport | ❌ | ✅ | Keep |
| 7 | Auth | `app/Http/Middleware/Authenticate.php` | Identity | ❌ | ✅ | Keep |
| 8 | TenantContext | `app/Http/Middleware/TenantContext.php` | Context | ❌ | ✅ | Keep |
| 9 | ValidateVotingIp | `app/Http/Middleware/ValidateVotingIp.php` | Authority | ✅ | ❌ | **Remove D.6** |
| 10 | ValidateVotingCode | `app/Http/Middleware/ValidateVotingCode.php` | Identity | ❌ | ✅ | Audit |
| 11 | VoterVerificationCheck | `app/Http/Middleware/VoterVerificationCheck.php` | Authority | ✅ | ❌ | **Audit** |
| 12 | RateLimitVoting | `app/Http/Middleware/RateLimitVoting.php` | Transport | ❌ | ✅ | Keep |

---

## Dangerous Patterns to Search For

### Pattern 1: Middleware Direct Authority Denial

```php
// app/Http/Middleware/ValidateVotingIp.php - DANGEROUS
public function handle(Request $request, Closure $next)
{
    if ($this->resolveIpBlock($request, $election)) {
        return response('Voting not permitted', 403);  // ❌ AUTHORITY DERIVED HERE
    }
    return $next($request);
}
```

**Audit Task**: Search for middleware that returns HTTP responses denying participation.

**Expected Result**: ValidateVotingIp exists and must be removed D.6.

---

### Pattern 2: FormRequest Authorization Gate

```php
// app/Http/Requests/SubmitVoteRequest.php - DANGEROUS
public function authorize(): bool
{
    return $this->user()->canVote($this->election);  // ❌ AUTHORITY DERIVED IN MIDDLEWARE STACK
}
```

**Audit Task**: Check all FormRequest classes used in voting routes for `authorize()` methods that derive participation authority.

**Expected Result**: 
- ✅ Returns true (only validates rules, doesn't derive authority)
- ❌ Checks user eligibility, IP count, device match (DANGEROUS)

---

### Pattern 3: Route Model Binding Callbacks

```php
// routes/web.php - DANGEROUS
Route::post('/voting/{election}', function (Election $election) {
    // election already loaded via implicit binding
})->middleware(function ($request, $next) {
    if (!$this->canVoteInElection($request->user(), $request->route('election'))) {
        return abort(403);  // ❌ AUTHORITY DERIVED DURING BINDING
    }
    return $next($request);
});
```

**Audit Task**: Check route definitions for binding callbacks that restrict participation.

**Expected Result**: Binding callbacks only load model, no authority checks.

---

### Pattern 4: Event Listeners Before Resolver

```php
// app/Listeners/ValidateVoterEligibility.php - DANGEROUS
class ValidateVoterEligibility implements ShouldQueue
{
    public function handle(VotingStarted $event)
    {
        if (!$this->isEligible($event->voter)) {
            throw new \Exception('Not eligible to vote');  // ❌ AUTHORITY CHECK BEFORE RESOLVER
        }
    }
}
```

**Audit Task**: Check event listeners listening to voting-related events.

**Expected Result**: Listeners should be projection/audit only, never authority derivation.

---

### Pattern 5: Model Observers

```php
// app/Observers/ElectionObserver.php - DANGEROUS
public function creating(Election $model)
{
    if ($model->voter_count > $this->maxVoters) {
        throw new \Exception('Election too large');  // ❌ AUTHORITY CHECK IN OBSERVER
    }
}
```

**Audit Task**: Check model observers for voting-related models (Election, Vote, VoterVerification).

**Expected Result**: Observers should only handle logging/caching, not authority.

---

### Pattern 6: Authorization Providers

```php
// app/Providers/AuthServiceProvider.php - DANGEROUS
Gate::define('vote_in_election', function (User $user, Election $election) {
    return $this->canVote($user, $election);  // ❌ IF THIS DERIVES AUTHORITY
});

// Then used in middleware/controller:
if (!auth()->user()->can('vote_in_election', $election)) {
    abort(403);  // ❌ AUTHORITY DERIVED OUTSIDE RESOLVER
}
```

**Audit Task**: Check all Gate::define() and Policy methods for voting-related gates.

**Expected Result**: 
- ✅ Gates read from ElectionCapabilitySnapshot only
- ❌ Gates compute new authority (DANGEROUS)

---

## Complete Execution Sequence Map

Create this for the actual codebase:

```
VOTING REQUEST LIFECYCLE
═══════════════════════════════════════════════════════════

[1] HTTP Request received
    ↓
[2] Global Middleware Stack
    ├─ App\Http\Middleware\EncryptCookies (✅)
    ├─ App\Http\Middleware\AddQueuedCookies (✅)
    ├─ App\Http\Middleware\StartSession (✅)
    ├─ App\Http\Middleware\ShareErrorsFromSession (✅)
    ├─ App\Http\Middleware\VerifyCsrfToken (✅)
    ├─ Illuminate\Middleware\SubstituteBindings (✅)
    ├─ App\Http\Middleware\Authenticate (✅ identity only)
    ├─ App\Http\Middleware\TenantContext (✅ context only)
    ├─ App\Http\Middleware\ValidateVotingIp (❌ AUTHORITY DERIVATION)
    │  └─ Returns 403 if IP blocked
    │
    └─ [TRANSPORT LAYER COMPLETE]
    ↓
[3] Route Defined
    Route::post('/voting/submit', 'DemoVoteController@store')
         ->middleware('verified')  // ⚠️ Audit this
    ↓
[4] Route-Specific Middleware ('verified')
    └─ Middleware\EnsureEmailIsVerified (✅ identity check)
    ↓
[5] FormRequest Validation (if DemoVoteRequest used)
    ├─ rules() → validates form structure
    └─ authorize() → ⚠️ AUDIT: may derive authority illegally
    ↓
[6] Route Model Binding
    ├─ Election $election → loaded by implicit binding (✅)
    └─ Callbacks: ⚠️ audit for authority checks
    ↓
[7] Controller Constructor
    public function __construct(
        TrustPolicyEvaluator $trustEvaluator,  // ✅
        ElectionCapabilityResolver $resolver,  // ✅
    )
    └─ No listeners fire yet that derive authority (✅)
    ↓
[8] Controller Method (DemoVoteController::store)
    ├─ Invoke TrustPolicyEvaluator::evaluate()
    ├─ Invoke ElectionCapabilityResolver::evaluate()
    │  └─ [AUTHORITY DERIVED HERE - CORRECT]
    └─ Return response
    ↓
[9] Response sent
```

---

## Audit Checklist

### Phase 1: Identify All Potential Authority Points

- [ ] List all middleware in `app/Http/Middleware/`
- [ ] List all FormRequest classes using voting routes
- [ ] List all event listeners related to voting
- [ ] List all model observers for voting-related models
- [ ] List all authorization gates/policies for voting
- [ ] List all route model binding definitions

### Phase 2: Classify Each Point

For each identified point:

- [ ] Does it derive new participation authority? (YES = dangerous)
- [ ] Does it check authority conditions before resolver? (YES = dangerous)
- [ ] Does it execute before controller method? (YES = must be safe)
- [ ] Does it only check transport/identity/context? (YES = safe)

### Phase 3: Document Execution Order

- [ ] Create complete middleware stack map
- [ ] Document route-specific middleware order
- [ ] Document FormRequest validation order
- [ ] Document event listener firing order
- [ ] Document observer execution order

### Phase 4: Verify No Authority Before Resolver

- [ ] No middleware returns 403 based on voting authority
- [ ] No FormRequest::authorize() checks voting eligibility
- [ ] No event listener throws for voting eligibility
- [ ] No observer blocks voting operations
- [ ] No authorization gate derives new authority

---

## Dangerous Listeners to Search For

```bash
# Find event listeners that might derive authority
grep -r "class.*Listener" app/Listeners/
grep -r "Voting\|Vote\|Election" app/Listeners/

# Check for authority checks in listeners
grep -r "canVote\|isEligible\|authorize\|deny\|allow" app/Listeners/
```

**Expected Findings**:
- ✅ Listeners for audit/logging OK
- ❌ Listeners for authority checks DANGEROUS

---

## Dangerous Observers to Search For

```bash
# Find model observers
grep -r "class.*Observer" app/Observers/

# Check voting-related observers
grep -r "Election\|Vote\|VoterVerification" app/Observers/

# Check for authority enforcement
grep -r "throw\|abort" app/Observers/
```

**Expected Findings**:
- ✅ Observers for cache invalidation OK
- ❌ Observers blocking operations DANGEROUS

---

## Dangerous Authorization Gates

```bash
# Find all authorization gates
grep -r "Gate::define" app/

# Find policy methods
grep -r "function.*class Policy" app/Policies/

# Check for voting-related gates
grep -r "vote\|election\|eligible" app/Providers/AuthServiceProvider.php
```

**Expected Pattern**:

```php
// ❌ DANGEROUS - derives authority
Gate::define('can_vote', function (User $user, Election $election) {
    return $this->checkIpCount($user, $election) 
        && $this->checkDeviceMatch($user)
        && $this->checkVerification($user);
});

// ✅ SAFE - reads existing decision
Gate::define('can_vote', function (User $user, Election $election) {
    $snapshot = $this->resolver->evaluate(new CapabilityContext(
        election: $election,
        user: $user,
        action: 'vote',
    ));
    return $snapshot->capabilities['vote'] ?? false;
});
```

---

## Expected Execution Order (After Fixes)

| Order | Component | Type | Sovereign? | Safe? |
|-------|-----------|------|-----------|-------|
| 1 | EncryptCookies | Transport | ❌ | ✅ |
| 2 | StartSession | Transport | ❌ | ✅ |
| 3 | VerifyCsrfToken | Transport | ❌ | ✅ |
| 4 | SubstituteBindings | Transport | ❌ | ✅ |
| 5 | Authenticate | Identity | ❌ | ✅ |
| 6 | TenantContext | Context | ❌ | ✅ |
| 7 | **ValidateVotingIp** | **Authority** | **✅** | **❌ REMOVE D.6** |
| 8 | EnsureEmailIsVerified | Identity | ❌ | ✅ |
| 9 | FormRequest::authorize() | Identity/Transport | ❌ | ✅ (audit required) |
| 10 | FormRequest::rules() | Validation | ❌ | ✅ |
| 11 | Route model binding | Context | ❌ | ✅ |
| 12 | Controller constructor | Context | ❌ | ✅ |
| 13 | **TrustPolicyEvaluator** | **Authority** | **✅** | **✅ REQUIRED** |
| 14 | **ElectionCapabilityResolver** | **Authority** | **✅** | **✅ ONLY HERE** |
| 15 | Controller logic | Implementation | ❌ | ✅ |
| 16 | Response sent | Transport | ❌ | ✅ |

---

## Audit Results Template

### Middleware Stack

| # | Middleware | Derives Authority? | Safe? | Notes |
|---|------------|-------------------|-------|-------|
| 1 | EncryptCookies | ⬜ | ⬜ | |
| 2 | StartSession | ⬜ | ⬜ | |
| ... | ... | ⬜ | ⬜ | |

### Event Listeners

| Listener | Voting-Related? | Derives Authority? | Safe? | Notes |
|----------|---|---|---|---|
| | ⬜ | ⬜ | ⬜ | |

### Model Observers

| Model | Observer | Derives Authority? | Safe? | Notes |
|-------|----------|---|---|---|
| | | ⬜ | ⬜ | |

### Authorization Gates

| Gate | Derives Authority? | Safe? | Notes |
|------|---|---|---|
| | ⬜ | ⬜ | |

### FormRequest Classes (Voting Routes)

| Route | Request Class | Has authorize()? | Safe? | Notes |
|-------|---|---|---|---|
| | | ⬜ | ⬜ | |

---

## Expected Outcome

After this audit:

✅ Complete execution order mapped
✅ All authority derivation points identified
✅ ValidateVotingIp classified for D.6 removal
✅ No hidden authority derivation before resolver
✅ Execution sequence integrity proven

If ANY authority derivation is found before the controller/resolver, it must be eliminated BEFORE D.6 proceeds.
