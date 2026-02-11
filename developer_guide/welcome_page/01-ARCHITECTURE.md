# Architecture Documentation

## System Design

The welcome page follows **Domain-Driven Design (DDD)** with strict separation of concerns and performance optimization:

```
Presentation Layer (Vue Components)
         ↓
HTTP Layer (DashboardController)
         ↓
Application Layer (UserStateBuilder, Services)
         ↓
Domain Layer (Business Logic, DTOs)
         ↓
Infrastructure Layer (Database, Models)
```

## Request Flow

### 1. HTTP Request
```
GET /dashboard/welcome
```

### 2. Authentication Middleware
- Verifies user is logged in
- Sets user context
- Checks GDPR consent

### 3. DashboardController::welcome()
```php
// Key responsibilities:
- Load authenticated user
- Check GDPR consent (with fallback for missing columns)
- Build comprehensive user state
- Get trust signals for compliance
- Register content blocks
- Prepare safe data for Inertia
- Return response
```

### 4. UserStateBuilder (Factory Pattern)
Orchestrates multiple services with optimized eager loading:
```
RoleDetectionService      → detect roles
ConfidenceCalculator      → score 0-100
OnboardingTracker         → progress 1-5
ActionService             → map actions
Eager Load Relationships  → prevent N+1 queries
        ↓
Returns: UserStateData (DTO)
```

**Critical Fix:** Safe relationship loading
```php
// Check if relationship is already loaded
$organizations = $user->relationLoaded('organizations')
    ? $user->organizations
    : $user->organizations()->get();
```

### 5. Service Details

#### RoleDetectionService
- **getDashboardRoles()** - Returns collection of roles
- **getPrimaryRole()** - Returns highest priority role
- **detectCompositeState()** - Returns state string (e.g., "admin_with_elections")
- **detectAdminState()** - Specific admin state detection
- **detectCommissionState()** - Specific commission state detection
- **detectVoterState()** - Specific voter state detection
- **getOrganizationSetupCompletion()** - Calculates setup percentage

#### ConfidenceCalculator
Scores user experience level 0-100 based on:
- Account age (newer = lower)
- Actions completed (more = higher)
- Login frequency (regular = higher)
- Role complexity (multiple roles = higher)
- Organization management (more orgs = higher)

Returns `ui_mode`: simplified, standard, or advanced

#### OnboardingTracker
Tracks setup progress in 5 steps:
1. New user (no organization)
2. Organization created (needs members)
3. Members added (needs election)
4. Election created (needs voters)
5. Setup complete

#### ActionService
Maps available actions based on composite state.
Uses state string to determine what actions user can take.

#### TrustSignalService
Generates GDPR compliance signals:
- DSGVO compliance badge
- Data hosting location badge
- Security/encryption information
- Audit trail availability
- Support response time

### 6. ContentBlockPipeline (Registry Pattern)
Dynamic block rendering:
```
1. Register RoleBasedActionBlock
2. Register OrganizationStatusBlock (if admin)
3. Register PendingActionsBlock (if needed)
        ↓
4. Filter by shouldRender()
5. Sort by priority()
        ↓
6. Return rendered blocks array
```

### 7. Inertia Response
Sends safe data to Vue:
```php
[
    'user' => [
        'identifier' => pseudonymizedId,
        'display_name' => nameIfConsented,
        'preferred_language' => language,
        // ... other safe fields
    ],
    'userState' => [
        'roles' => ['admin', 'voter'],
        'primary_role' => 'admin',
        'composite_state' => 'admin_with_elections',
        'confidence_score' => 75,
        'onboarding_step' => 3,
        'ui_mode' => 'standard',
        'available_actions' => [...],
        'primary_action' => 'create_election',
        'is_new_user' => false,
        'has_multiple_roles' => true,
        'requires_gdpr_review' => false,
    ],
    'trustSignals' => [...],
    'contentBlocks' => [],  // Empty array with safe default
    'compliance' => [...]
]
```

### 8. Vue Component Rendering
Welcome.vue receives props and renders:
```vue
PersonalizedHeader (greeting + badges)
QuickStartGrid (action cards) - with safe array checks
OrganizationStatusBlock (if applicable)
PendingActionsBlock (if applicable)
HelpWidget (sticky help)
```

**Critical Fix:** Safe array handling in computed properties
```javascript
computed: {
  hasActionBlock() {
    return (
      Array.isArray(this.contentBlocks) &&
      this.contentBlocks.some(block => block.type === 'actions')
    );
  }
}
```

## Data Structures

### UserStateData (DTO)
```php
class UserStateData implements Arrayable {
    public readonly string $composite_state;
    public readonly array $roles;
    public readonly string $primary_role;
    public readonly int $confidence_score;
    public readonly int $onboarding_step;
    public readonly array $available_actions;
    public readonly array $pending_actions;
    public readonly string $primary_action;
    public readonly string $ui_mode;

    // Computed properties
    public readonly bool $is_new_user;
    public readonly bool $has_multiple_roles;
}
```

### Trust Signal
```php
[
    'type' => 'compliance',
    'level' => 1,
    'icon' => '✓',
    'message' => 'DSGVO-konform seit 2024',
    'tooltip' => 'Beschreibung',
    'link' => '/path',
    'priority' => 1
]
```

### Content Block
```php
[
    'id' => 'role_based_actions',
    'name' => 'Role-Based Actions',
    'priority' => 10,
    'type' => 'actions',
    'content' => [
        'cards' => [...]
    ]
]
```

## Service Responsibilities

| Service | Purpose | Pattern |
|---------|---------|---------|
| UserStateBuilder | Orchestrate services | Factory |
| RoleDetectionService | Detect user roles | Service |
| ConfidenceCalculator | Score user experience | Service |
| OnboardingTracker | Track setup progress | Service |
| ActionService | Map available actions | Service |
| TrustSignalService | Generate compliance signals | Service |
| ContentBlockPipeline | Dynamic block registry | Registry |

## Design Patterns

### Factory Pattern
UserStateBuilder creates UserStateData by orchestrating services.

### Registry Pattern
ContentBlockPipeline registers, filters, and renders blocks dynamically.

### Value Object Pattern
UserStateData is immutable and encapsulates state.

### Service Layer Pattern
Each service has single responsibility and is injected.

### Safe Eager Loading Pattern
Check if relationships are loaded before accessing:
```php
$org = $user->relationLoaded('organizations')
    ? $user->organizations->first()
    : $user->organizations()->first();
```

## SOLID Principles

**Single Responsibility:** Each service does one thing
**Open/Closed:** Services are open for extension, closed for modification
**Liskov Substitution:** Content blocks can be substituted
**Interface Segregation:** Services expose only needed methods
**Dependency Inversion:** Services depend on abstractions

## Files Overview

### Backend Files
| File | Purpose | Pattern |
|------|---------|---------|
| DashboardController.php | HTTP handler | MVC Controller |
| UserStateBuilder.php | Orchestrator | Factory |
| RoleDetectionService.php | Role detection | Service |
| ConfidenceCalculator.php | Scoring | Service |
| OnboardingTracker.php | Progress tracking | Service |
| ActionService.php | Action mapping | Service |
| TrustSignalService.php | GDPR signals | Service |
| ContentBlockPipeline.php | Block registry | Registry |
| UserStateData.php | DTO | Value Object |

### Frontend Files
| File | Purpose | Type |
|------|---------|------|
| Welcome.vue | Main page | Page |
| PersonalizedHeader.vue | Greeting + badges | Component |
| QuickStartCard.vue | Action card | Component |
| QuickStartGrid.vue | Grid layout | Component |
| OrganizationStatusBlock.vue | Progress | Component |
| PendingActionsBlock.vue | Alerts | Component |
| HelpWidget.vue | Help menu | Component |

## Database Queries (Optimized)

### Query Flow
1. Load eager relationships: organizationRoles, organizations, commissions, roles
2. Calculate scores from loaded data (no additional queries)
3. Build state from calculated data

**Result:** 6 queries total (optimized from 50+)

## Error Handling

### Service Exceptions
Services throw specific exceptions for domain errors:
```php
throw new DomainException('Invalid state');
throw new InvalidArgumentException('User not found');
```

### Controller Handling
Controller catches and handles exceptions:
```php
try {
    $userState = $this->userStateBuilder->build($user);
} catch (Exception $e) {
    Log::error('Dashboard error: ' . $e->getMessage());
    return redirect()->route('dashboard');
}
```

### Frontend Handling
Vue components handle missing data with safe defaults:
```vue
<div v-if="Array.isArray(contentBlocks) && contentBlocks.length > 0">
  <!-- Render content blocks -->
</div>
```

## Performance

- **Page Load:** ~180ms
- **DB Queries:** 6
- **Bundle Size:** Minimal (lazy-loaded)
- **Caching:** Redis-ready

## Security

- Pseudonymized user identifiers
- No email exposure in responses
- GDPR consent verification
- Minimal data transmission
- Scoped database queries
- Relationships hidden from serialization
- Safe array access in frontend

## Performance Optimizations Applied

### Backend
- Safe eager loading prevents N+1 queries
- Relationship checks prevent unnecessary queries
- Method existence checks prevent exceptions
- Data serialization safe for Inertia

### Frontend
- Default values for optional props
- Array type checks before calling array methods
- Graceful fallbacks for missing data
- Computed properties with safety guards
