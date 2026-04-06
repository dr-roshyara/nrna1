# Backend Services Documentation

## Overview

The dashboard backend is composed of specialized services, each with a single responsibility. Services are orchestrated by `UserStateBuilder` and follow the dependency injection pattern.

All services are located in `app/Services/Dashboard/`

## Services Overview

| Service | Responsibility | Input | Output |
|---------|-----------------|-------|--------|
| UserStateBuilder | Orchestrate all services | User | UserStateData |
| RoleDetectionService | Determine user roles | User | Collection<string> |
| ConfidenceCalculator | Calculate experience score | User | int (0-100) |
| OnboardingTracker | Track setup progress | User | int (1-5) |
| ActionService | Map available actions | User, State | array |
| TrustSignalService | Generate compliance signals | UserStateData | array |
| ContentBlockPipeline | Render dynamic blocks | UserStateData | array |

---

## UserStateBuilder (Factory)

**File:** `app/Services/Dashboard/UserStateBuilder.php`

**Pattern:** Factory

**Purpose:** Orchestrate all services to build complete user state for dashboard.

### Key Method: `build(User $user): UserStateData`

Orchestrates the complete flow:
1. **Eager Load:** Load all user relationships (prevents N+1 queries)
2. **Role Detection:** Determine user's roles
3. **Confidence Score:** Calculate experience level
4. **Onboarding:** Track setup progress
5. **Actions:** Map available actions
6. **Return:** Immutable UserStateData DTO

### Performance: Safe Eager Loading

The builder includes critical N+1 query fixes:

```php
private function eagerLoadUserData(User $user): User
{
    // Build relationships array with existence checks
    $relationships = [];

    // Only add relationships that actually exist
    if (method_exists(User::class, 'organizationRoles')) {
        $relationships[] = 'organizationRoles.organisation';
    }
    if (method_exists(User::class, 'organizations')) {
        $relationships[] = 'organizations';
    }
    if (method_exists(User::class, 'commissions')) {
        $relationships[] = 'commissions';
    }
    if (method_exists(User::class, 'roles')) {
        $relationships[] = 'roles:id,name';
    }

    // Load only if relationships exist
    if (!empty($relationships)) {
        $query->with($relationships);
    }

    return $query->find($user->id);
}
```

**Critical:** Only loads relationships that actually exist on the User model to prevent `RelationNotFoundException`.

---

## RoleDetectionService

**File:** `app/Services/Dashboard/RoleDetectionService.php`

**Purpose:** Determine user's roles and detect composite state.

### Methods

#### `getDashboardRoles(User $user): Collection`

Returns collection of user's roles: `['admin', 'commission', 'voter']`

**Safe Relationship Access:**
```php
public function getDashboardRoles(User $user): Collection
{
    // Check if relationship is already loaded
    $organizationRoles = $user->relationLoaded('organizationRoles')
        ? $user->organizationRoles
        : $user->organizationRoles()->get();

    if ($organizationRoles->count() > 0) {
        $roles->push('admin');
    }
    // ... check other roles
}
```

**Critical:** Uses `relationLoaded()` to prevent unnecessary queries.

#### `getPrimaryRole(User $user): string`

Returns highest priority role:
- Priority: admin > commission > voter > guest

#### `detectCompositeState(User $user): string`

Returns state based on roles and context:

| State | Meaning |
|-------|---------|
| `new_user_no_roles` | Brand new user |
| `multi_role_user` | User has 2+ roles |
| `admin_no_org` | Admin with no organisation |
| `admin_setup_started` | Admin, setup <30% complete |
| `admin_setup_in_progress` | Admin, setup 30-99% complete |
| `admin_with_elections` | Admin with active elections |
| `voter_with_pending_votes` | Voter with votes to cast |
| `voter_no_pending_votes` | Voter, all votes cast |
| `commission_no_election` | Commission member, no election |
| `commission_election_active` | Commission, election active |
| `commission_election_inactive` | Commission, election ended |

#### `getOrganizationSetupCompletion(organisation $org): int`

Returns setup completion percentage (0-100):

Checks:
- Profile complete (has name)
- Members added (2+)
- Election created
- Settings configured

**Safe implementation:**
```php
private function getOrganizationSetupCompletion($organisation): int
{
    // Safe relationship access
    $membersCount = $organisation->relationLoaded('members')
        ? $organisation->members->count()
        : $organisation->members()->count();

    $electionsCount = $organisation->relationLoaded('elections')
        ? $organisation->elections->count()
        : $organisation->elections()->count();

    // ... calculate and return percentage
}
```

---

## ConfidenceCalculator

**File:** `app/Services/Dashboard/ConfidenceCalculator.php`

**Purpose:** Calculate user's experience level (0-100).

### Method: `calculate(User $user): int`

Scoring factors:

| Factor | Range | Logic |
|--------|-------|-------|
| Account Age | -20 to +15 | Newer = lower, established = higher |
| Actions Completed | 0 to +20 | More activity = higher score |
| Login Frequency | 0 to +10 | Regular login = higher |
| Role Complexity | 0 to +15 | Multiple roles = higher |
| organisation Management | 0 to +15 | More orgs = higher |

**Score Calculation:**
- Base Score: 50
- Add factors: 50 + (factors)
- Final: Min 0, Max 100

### Method: `getUIMode(int $confidenceScore): string`

Returns appropriate interface complexity:

| Score | Mode | Description |
|-------|------|-------------|
| < 40 | `simplified` | New/inactive users - minimal options |
| 40-70 | `standard` | Regular users - standard interface |
| >= 70 | `advanced` | Experienced users - full features |

**Safe Relationship Implementation:**
```php
private function roleComplexityScore(User $user): int
{
    $roleCount = 0;

    // Use loaded data if available
    if ($user->relationLoaded('organizations')) {
        if ($user->organizations->count() > 0) {
            $roleCount++;
        }
    } elseif ($user->relationLoaded('organizationRoles')) {
        if ($user->organizationRoles->count() > 0) {
            $roleCount++;
        }
    }

    // ... check other role types

    return $roleCount > 1 ? 15 : 0;
}
```

---

## OnboardingTracker

**File:** `app/Services/Dashboard/OnboardingTracker.php`

**Purpose:** Track user's setup progress through 5 steps.

### Onboarding Steps

| Step | Condition | User Task | Progress |
|------|-----------|-----------|----------|
| 1 | New user (no organisation) | Create organisation | 0% |
| 2 | organisation exists, < 2 members | Add members | 25% |
| 3 | 2+ members, no elections | Create election | 50% |
| 4 | Election exists, < 2 voters | Invite voters | 75% |
| 5 | All setup complete | Launch election | 100% |

### Method: `getNextStep(User $user): int`

Determines which step user is on.

**Safe Relationship Implementation:**
```php
public function getNextStep(User $user): int
{
    // Safe organizations check
    $organizations = $user->relationLoaded('organizations')
        ? $user->organizations
        : $user->organizations()->get();

    if ($organizations->isEmpty()) {
        return 1; // New user
    }

    $organisation = $organizations->first();

    // Safe members count
    $membersCount = $organisation->relationLoaded('members')
        ? $organisation->members->count()
        : $organisation->members()->count();

    if ($membersCount < 2) {
        return 2; // Needs members
    }

    // ... continue checking
}
```

### Method: `getStepDetails(int $step): array`

Returns step metadata for frontend:

```php
[
    'step' => 1,
    'title' => 'Erstellen Sie Ihre Organisation',
    'description' => 'Beginnen Sie mit der Erstellung einer neuen Organisation',
    'primary_action' => 'create_organization',
    'progress' => 0,
]
```

---

## ActionService

**File:** `app/Services/Dashboard/ActionService.php`

**Purpose:** Map available actions based on user's composite state.

### Method: `getAvailableActions(User $user, string $compositeState): array`

Maps state to available actions:

| State | Available Actions |
|-------|-------------------|
| `new_user_no_roles` | create_organization, join_organization, request_assistance |
| `admin_no_org` | create_organization, view_profile |
| `admin_setup_started` | add_members, view_organization, manage_settings |
| `admin_setup_in_progress` | create_election, add_members, view_organization |
| `admin_with_elections` | view_organization, create_election, manage_elections, view_results |
| `voter_with_pending_votes` | cast_vote, view_pending_votes, verify_vote |
| `voter_no_pending_votes` | view_profile, view_voted_elections, verify_previous_vote |
| `commission_no_election` | view_commission, view_profile |
| `commission_election_active` | manage_election, monitor_participation, view_live_results |
| `commission_election_inactive` | view_results, download_report, view_commission |

### Method: `getPrimaryAction(string $compositeState): string`

Returns the most important action for the user's current state.

---

## TrustSignalService

**File:** `app/Services/Dashboard/TrustSignalService.php`

**Purpose:** Generate GDPR compliance signals to build user trust.

### Method: `getSignalsForUser(UserStateData $userState): array`

Returns array of contextual trust signals.

**Signal Structure:**
```php
[
    'type' => 'compliance|security|audit|support',
    'level' => 1|2|3,
    'icon' => '✓|🔒|📋|⏱️',
    'message' => 'Signal message',
    'tooltip' => 'Detailed explanation',
    'link' => '/path/to/details',
    'priority' => 1|2|3,
]
```

**Signals Generated:**

1. **Compliance Signal** (Always shown)
   - Message: "DSGVO-konform seit 2024"
   - Type: compliance
   - Priority: 1

2. **Security Signal** (Admin users)
   - Message: "Daten geschützt in Frankfurt"
   - Type: security
   - Priority: 2

3. **Audit Signal** (Admin users)
   - Message: "Audit-Trail verfügbar"
   - Type: audit
   - Priority: 3

4. **Support Signals** (Role/state specific)
   - Response time guarantees
   - Support channel information

---

## ContentBlockPipeline (Registry)

**File:** `app/Services/Dashboard/ContentBlockPipeline.php`

**Pattern:** Registry

**Purpose:** Dynamically render content blocks based on user state.

### Method: `process(UserStateData $userState): array`

Returns structured block data:
```php
[
    'blocks' => [/* rendered blocks */],
    'count' => 3,
    'user_state' => [/* state data */],
]
```

### Method: `register(BaseContentBlock $block): self`

Add block to registry for rendering:
```php
$pipeline->register(new RoleBasedActionBlock());
$pipeline->register(new OrganizationStatusBlock());
$pipeline->register(new PendingActionsBlock());
```

### Available Content Blocks

#### RoleBasedActionBlock
- **File:** `app/Services/Dashboard/Blocks/RoleBasedActionBlock.php`
- **Purpose:** Display role-specific action cards
- **Always renders:** Yes
- **Priority:** 10

#### OrganizationStatusBlock
- **File:** `app/Services/Dashboard/Blocks/OrganizationStatusBlock.php`
- **Purpose:** Show organisation setup progress
- **Condition:** Admin role only
- **Priority:** 20

#### PendingActionsBlock
- **File:** `app/Services/Dashboard/Blocks/PendingActionsBlock.php`
- **Purpose:** Show pending user actions/alerts
- **Condition:** When pending_actions exist
- **Priority:** 30

---

## UserStateData (DTO)

**File:** `app/DataTransferObjects/UserStateData.php`

**Purpose:** Immutable data transfer object for user state.

### Properties

```php
public readonly string $composite_state;      // e.g., 'admin_with_elections'
public readonly array $roles;                 // e.g., ['admin', 'voter']
public readonly string $primary_role;         // e.g., 'admin'
public readonly int $confidence_score;        // 0-100
public readonly int $onboarding_step;         // 1-5
public readonly array $available_actions;     // Array of action IDs
public readonly array $pending_actions;       // Array of pending action objects
public readonly string $primary_action;       // Primary action to highlight
public readonly string $ui_mode;              // 'simplified'|'standard'|'advanced'

// Computed properties
public readonly bool $is_new_user;            // true if roles are empty
public readonly bool $has_multiple_roles;     // true if count($roles) > 1
```

### Method: `toArray(): array`

Converts DTO to array for Vue component:

```php
public function toArray(): array
{
    return [
        'composite_state' => $this->composite_state,
        'roles' => $this->roles,
        'primary_role' => $this->primary_role,
        'confidence_score' => $this->confidence_score,
        'onboarding_step' => $this->onboarding_step,
        'available_actions' => $this->available_actions,
        'pending_actions' => $this->pending_actions,
        'primary_action' => $this->primary_action,
        'ui_mode' => $this->ui_mode,
        'is_new_user' => empty($this->roles),
        'has_multiple_roles' => count($this->roles) > 1,
    ];
}
```

---

## Performance Optimizations

### Problem: N+1 Queries

**Before Optimization:**
```
50+ database queries
30+ second timeout
```

**After Optimization:**
```
6 database queries
~180ms response time
```

### Solution 1: Eager Loading with Checks

```php
// ✅ GOOD: Check if already loaded
$orgs = $user->relationLoaded('organizations')
    ? $user->organizations
    : $user->organizations()->get();

// ❌ BAD: Always queries
$orgs = $user->organizations()->get();
```

### Solution 2: Method Existence Checks

```php
// ✅ GOOD: Check if method exists
if (method_exists(User::class, 'organizations')) {
    $relationships[] = 'organizations';
}

// ❌ BAD: Assume method exists
$relationships[] = 'nonexistent_relationship'; // RelationNotFoundException!
```

### Solution 3: Results

**Query Breakdown (6 total):**
1. Load User
2. Load organizationRoles
3. Load related organizations
4. Load commissions
5. Load elections (via organizations)
6. Load roles (Spatie)

---

## Error Handling

### Service Exceptions

Services throw specific exceptions for domain errors:

```php
throw new DomainException('Invalid state detected');
throw new InvalidArgumentException('User not found');
```

### Controller Handling

```php
try {
    $userState = $this->userStateBuilder->build($user);
} catch (Exception $e) {
    \Log::error('Dashboard error: ' . $e->getMessage());
    return redirect()->route('dashboard');
}
```

---

## Testing Services

### Test UserStateBuilder Performance

```bash
php artisan tinker
> $builder = app(\App\Services\Dashboard\UserStateBuilder::class)
> $user = \App\Models\User::first()
> $state = $builder->build($user)
# Expected: < 200ms execution time
```

### Test Query Count

```bash
php artisan tinker
> \DB::enableQueryLog()
> $state = $builder->build($user)
> count(\DB::getQueryLog())
# Expected: 6 queries (not 50+)
```

### Run Unit Tests

```bash
php artisan test tests/Unit/Services/Dashboard/
```

---

## Usage Example

```php
// In DashboardController
$user = Auth::user();

// Build user state using factory
$builder = app(\App\Services\Dashboard\UserStateBuilder::class);
$userState = $builder->build($user);

// Use the DTO in your response
return Inertia::render('Dashboard/Welcome', [
    'userState' => $userState->toArray(),
    'trustSignals' => $trustService->getSignalsForUser($userState),
    'contentBlocks' => $pipeline->process($userState),
]);
```
