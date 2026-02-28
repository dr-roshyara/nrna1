# Three-Role Dashboard System - Architecture Diagram

## System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────┐
│                          PUBLIC DIGIT PLATFORM                          │
└─────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                        AUTHENTICATION LAYER                              │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  Laravel Fortify + Jetstream                                            │
│  ├─ Login Form (Auth/Login.vue)                                         │
│  ├─ Register Form (Auth/Register.vue)                                   │
│  ├─ Email Verification                                                  │
│  └─ Password Reset                                                      │
│                                                                          │
│  On successful login:                                                   │
│  User → /dashboard/roles (RoleSelectionController)                      │
│                                                                          │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                      ROLE SELECTION LAYER                               │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  Route: GET /dashboard/roles                                            │
│  Component: RoleSelection/Index.vue                                     │
│                                                                          │
│  ┌──────────────────────────────────────────────────────────────┐      │
│  │ Fetch User Dashboard Roles                                   │      │
│  │ User::getDashboardRoles() → ['admin', 'commission', 'voter'] │      │
│  └──────────────────────────────────────────────────────────────┘      │
│                       ↓                                                 │
│  ┌──────────────────────────────────────────────────────────────┐      │
│  │ Display Role Cards                                           │      │
│  │ • Admin (📊) - Organizations & System                        │      │
│  │ • Commission (⚖️) - Elections & Oversight                    │      │
│  │ • Voter (🗳️) - Voting & History                              │      │
│  └──────────────────────────────────────────────────────────────┘      │
│                       ↓                                                 │
│  ┌──────────────────────────────────────────────────────────────┐      │
│  │ User Selects Role                                            │      │
│  │ POST /switch-role/{role}                                     │      │
│  │ • Store role in session: session('dashboard_role')           │      │
│  │ • Redirect to appropriate dashboard                          │      │
│  └──────────────────────────────────────────────────────────────┘      │
│                                                                          │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                    THREE DASHBOARD SYSTEMS                              │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  ┌─────────────────────┐  ┌──────────────────────┐  ┌──────────────────┐
│  │   ADMIN DASHBOARD   │  │ COMMISSION DASHBOARD │  │ VOTER DASHBOARD  │
│  ├─────────────────────┤  ├──────────────────────┤  ├──────────────────┤
│  │ Route:              │  │ Route:               │  │ Route:           │
│  │ /dashboard/admin    │  │ /dashboard/commission│  │ /vote            │
│  │                     │  │                      │  │                  │
│  │ Controller:         │  │ Controller:          │  │ Controller:      │
│  │ AdminDashboard      │  │ CommissionDashboard  │  │ VoterDashboard   │
│  │                     │  │                      │  │                  │
│  │ Middleware:         │  │ Middleware:          │  │ Middleware:      │
│  │ role:admin          │  │ role:commission      │  │ role:voter       │
│  │                     │  │                      │  │                  │
│  │ Component:          │  │ Component:           │  │ Component:       │
│  │ Admin/Dashboard.vue │  │Commission/           │  │Vote/Dashboard    │
│  │                     │  │ Dashboard.vue        │  │ .vue             │
│  │                     │  │                      │  │                  │
│  │ Features:           │  │ Features:            │  │ Features:        │
│  │ • Organizations     │  │ • Elections          │  │ • Active         │
│  │ • System Stats      │  │ • Vote Stats         │  │   Elections      │
│  │ • User Management   │  │ • Pending Voters     │  │ • Voting History │
│  │ • Create Elections  │  │ • Audit Logs         │  │ • Cast Vote      │
│  │                     │  │ • Monitor Votes      │  │ • Verify Vote    │
│  │                     │  │                      │  │                  │
│  │ Data Access:        │  │ Data Access:         │  │ Data Access:     │
│  │ • organisations     │  │ • elections          │  │ • activeElections│
│  │ • quickStats        │  │ • quickStats         │  │ • votingHistory  │
│  │                     │  │                      │  │ • quickStats     │
│  └─────────────────────┘  └──────────────────────┘  └──────────────────┘
│                                                                          │
└──────────────────────────────────────────────────────────────────────────┘
```

## Database Schema Diagram

```
┌────────────────────────────────────────────────────────────────────────┐
│                          DATABASE SCHEMA                               │
└────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────┐      ┌──────────────────────────┐
│       users              │      │    organisations         │
├──────────────────────────┤      ├──────────────────────────┤
│ • id (PK)                │      │ • id (PK)                │
│ • email                  │      │ • name                   │
│ • password               │      │ • slug                   │
│ • name                   │      │ • type                   │
│ • is_committee_member    │      │ • settings (JSON)        │
│ • created_at             │      │ • languages (JSON)       │
│ • updated_at             │      │ • created_at             │
└──────────────────────────┘      │ • updated_at             │
         ▲                         └──────────────────────────┘
         │ 1                                   ▲
         │                                     │ N
         │                          ┌──────────┴────────────┐
         │                          │                       │
         │          ┌───────────────────────────────┐       │
         │          │ user_organization_roles       │       │
         │          ├───────────────────────────────┤       │
         │          │ • user_id (FK)                │       │
         │          │ • organisation_id (FK)        │───────┘
         │          │ • role (enum: admin,          │
         │          │   commission, voter)          │
         │          │ • permissions (JSON)          │
         │          │ • created_at                  │
         └──────────┤ • updated_at                  │
                    └───────────────────────────────┘

┌──────────────────────────┐      ┌──────────────────────────┐
│      elections           │      │     candidates           │
├──────────────────────────┤      ├──────────────────────────┤
│ • id (PK)                │      │ • id (PK)                │
│ • organisation_id (FK)   │      │ • election_id (FK)       │
│ • title                  │      │ • name                   │
│ • status                 │      │ • votes_count            │
│ • votes_count            │      │ • created_at             │
│ • created_at             │      │ • updated_at             │
│ • updated_at             │      └──────────────────────────┘
└──────────────────────────┘
         ▲
         │ 1
         │
         │
         │          ┌────────────────────────────────┐
         │          │ election_commission_members    │
         │          ├────────────────────────────────┤
         │          │ • user_id (FK) ─────┐          │
         │          │ • election_id (FK)──┘          │
         │          │ • permissions (JSON)           │
         └──────────┤ • created_at                   │
                    │ • updated_at                   │
                    └────────────────────────────────┘
```

## User Role Resolution Flow

```
┌────────────────────────────────────────────────────────────────┐
│     USER ACCESSES ROUTE: GET /dashboard/admin                  │
└────────────────────────────────────────────────────────────────┘
                              ↓
┌────────────────────────────────────────────────────────────────┐
│  Middleware: auth (check if authenticated)                      │
└────────────────────────────────────────────────────────────────┘
                      YES ↓        ↓ NO
                         │        └─→ Redirect to /login
                         │
┌────────────────────────────────────────────────────────────────┐
│  Middleware: role:admin (check if user has 'admin' role)       │
└────────────────────────────────────────────────────────────────┘
                         │
         ┌───────────────┴───────────────┐
         │                               │
    Has Role? (Check Database)    Check Legacy Field
    user_organization_roles            is_committee_member
    where role = 'admin'               (for commission role)
         │                               │
    YES ↓                           NO ↓
         │                           └─→ Redirect to /role.selection
         │
┌────────────────────────────────────────────────────────────────┐
│  Middleware: Check Session                                      │
│  - Is session['dashboard_role'] === 'admin'?                   │
│  - If missing: Redirect to /role.selection                     │
│  - If mismatch: Redirect to /role.selection                    │
│  - If match: Continue ✓                                         │
└────────────────────────────────────────────────────────────────┘
                         │
                         ↓
┌────────────────────────────────────────────────────────────────┐
│  AdminDashboardController::index()                              │
│  - Fetch user's organisations                                   │
│  - Calculate statistics                                         │
│  - Render Admin/Dashboard.vue                                   │
└────────────────────────────────────────────────────────────────┘
```

## Translation i18n Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                   VUE COMPONENT RENDERING                       │
│          (e.g., Commission/Dashboard.vue)                       │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│  Template: {{ $t('pages.commission.commissionDashboard.title') }}│
└─────────────────────────────────────────────────────────────────┘
                              │
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│              resources/js/i18n.js                               │
│  ├─ Imports all translation files                              │
│  └─ Registers under messages object                            │
└─────────────────────────────────────────────────────────────────┘
                              │
                 ┌────────────┼────────────┐
                 │            │            │
                 ↓            ↓            ↓
    ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
    │  de.json     │  │  en.json     │  │  np.json     │
    ├──────────────┤  ├──────────────┤  ├──────────────┤
    │ messages.    │  │ messages.    │  │ messages.    │
    │   pages: {   │  │   pages: {   │  │   pages: {   │
    │  'commission'│  │ 'commission' │  │ 'commission' │
    │    DeDe      │  │    EnEn      │  │    NpNp      │
    │   }          │  │   }          │  │   }          │
    └──────────────┘  └──────────────┘  └──────────────┘
                 │            │            │
                 └────────────┼────────────┘
                              │
                              ↓
    ┌─────────────────────────────────────────┐
    │ Vue i18n Instance resolves key:         │
    │ 'pages.commission.commissionDashboard'  │
    │ Based on current locale ($i18n.locale)  │
    │                                          │
    │ Returns: German/English/Nepali string   │
    └─────────────────────────────────────────┘
                              │
                              ↓
    ┌─────────────────────────────────────────┐
    │ Browser Renders Translated Text         │
    │ "Wahlkommissions-Dashboard" (German)     │
    │ OR                                       │
    │ "Commission Dashboard" (English)         │
    │ OR                                       │
    │ "चुनाव आयोग ड्यासबोर्ड" (Nepali)          │
    └─────────────────────────────────────────┘
```

## Request Flow Diagram

```
USER JOURNEY
━━━━━━━━━━━

┌──────────┐
│  LOGIN   │  → Credentials submitted
└──────────┘     ↓

┌──────────────────────────────────────┐
│ Fortify Authentication                │
│ ├─ Validate email & password          │
│ ├─ Create session                     │
│ └─ Redirect to HOME (/dashboard/roles)│
└──────────────────────────────────────┘
         ↓

┌──────────────────────────────────────┐
│ RoleSelectionController::index()      │
│ ├─ Check user authenticated           │
│ ├─ Fetch user's dashboard roles       │
│ ├─ Calculate stats for each role      │
│ └─ Render RoleSelection/Index.vue     │
└──────────────────────────────────────┘
         ↓
    ┌─────────────────────┐
    │ Display 3 Role Cards│
    │ ┌──────────────────┐│
    │ │ Admin  │Commission││
    │ │ Voter  │          ││
    │ └──────────────────┘│
    └─────────────────────┘
         ↓
    ┌──────────────────┐
    │ User clicks role │
    └──────────────────┘
         ↓
┌──────────────────────────────────────┐
│ POST /switch-role/commission          │
│ ├─ Verify role exists in DB           │
│ ├─ Store in session                   │
│ └─ Redirect to dashboard              │
└──────────────────────────────────────┘
         ↓
┌──────────────────────────────────────┐
│ CommissionDashboardController::index()│
│ ├─ auth middleware                    │
│ ├─ role:commission middleware         │
│ ├─ Verify session['dashboard_role']   │
│ ├─ Fetch elections & stats            │
│ └─ Render Commission/Dashboard.vue    │
└──────────────────────────────────────┘
         ↓
    ┌──────────────────────────┐
    │ Display Commission Data   │
    │ ├─ Elections List         │
    │ ├─ Quick Stats            │
    │ └─ Action Buttons         │
    └──────────────────────────┘
         ↓
    ┌──────────────────────────┐
    │ User can:                │
    │ ├─ Monitor Elections      │
    │ ├─ Manage Elections       │
    │ ├─ View Audit Logs        │
    │ └─ Switch Roles           │
    └──────────────────────────┘
```

## Middleware Stack Order

```
REQUEST PROCESSING ORDER
━━━━━━━━━━━━━━━━━━━━━━━

1. Global Middleware (HttpKernel.php)
   ├─ EncryptCookies
   ├─ AddQueuedCookiesToResponse
   ├─ StartSession
   ├─ AuthenticateSession
   └─ ShareErrorsFromSession

2. Route Middleware Group: 'web'
   ├─ \App\Http\Middleware\VerifyCsrfToken
   ├─ \Illuminate\View\Middleware\ShareErrorsFromSession
   ├─ \App\Http\Middleware\HandleInertiaRequests
   ├─ \Illuminate\Routing\Middleware\SubstituteBindings
   └─ Others...

3. Route-Level Middleware (in specific route groups)

   // For role-based routes:
   Route::middleware(['auth'])->group(function() {
       Route::middleware(['role:admin'])->group(function() {
           Route::get('/dashboard/admin', ...)
       });
   });

   Applied in order:
   a) 'auth' middleware
      → Check if user is authenticated
      → If not: Redirect to login

   b) 'role:admin' middleware (CheckUserRole.php)
      → Check if user has 'admin' role in database
      → Check if session['dashboard_role'] === 'admin'
      → If not: Redirect to role.selection with error

   c) Controller method is called
      → Fetch data
      → Render view
```

## State Management (Session)

```
┌─────────────────────────────────────────────────────────────┐
│                   SESSION STATE                             │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  After login (stored in $_SESSION):                        │
│  ┌─────────────────────────────────────────────────────┐  │
│  │ 'auth.user' : {                                     │  │
│  │   'id': 1,                                          │  │
│  │   'email': 'user@example.com',                      │  │
│  │   'is_committee_member': true/false,                │  │
│  │   'created_at': '2026-02-07 ...',                   │  │
│  │   ...                                               │  │
│  │ }                                                   │  │
│  └─────────────────────────────────────────────────────┘  │
│                                                             │
│  After role selection (user navigates to /switch-role):   │
│  ┌─────────────────────────────────────────────────────┐  │
│  │ 'dashboard_role': 'commission'                      │  │
│  └─────────────────────────────────────────────────────┘  │
│                                                             │
│  This session key is:                                      │
│  - Set by RoleSelectionController::switchRole()          │
│  - Checked by CheckUserRole middleware                    │
│  - Cleared on logout (Laravel default behavior)           │
│                                                             │
└─────────────────────────────────────────────────────────────┘

TYPICAL SESSION LIFECYCLE:
━━━━━━━━━━━━━━━━━━━━━━━━━

1. User not logged in
   session: {}

2. User logs in successfully
   session: {
       'auth.user': { id, email, ... },
       'CSRF_TOKEN': 'xxx...',
       'login_web_...': timestamp
   }

3. User navigates to /dashboard/roles
   session: {
       'auth.user': { ... },
       'dashboard_role': undefined  ← not set yet
   }

4. User clicks role card & POSTs /switch-role/commission
   session: {
       'auth.user': { ... },
       'dashboard_role': 'commission'  ← stored here
   }

5. User navigates through commission dashboards
   session: unchanged

6. User clicks different role card & POSTs /switch-role/admin
   session: {
       'auth.user': { ... },
       'dashboard_role': 'admin'  ← updated
   }

7. User logs out
   session: {} ← cleared completely
```

## Component Hierarchy

```
┌─────────────────────────────────────────────────────────┐
│               App.vue (Root)                            │
│         (Handles i18n provider)                         │
└─────────────────────────────────────────────────────────┘
                        │
        ┌───────────────┼───────────────┐
        │               │               │
        ↓               ↓               ↓
    ┌────────┐   ┌──────────┐   ┌─────────┐
    │ Layout │   │  Header  │   │ Footer  │
    └────────┘   └──────────┘   └─────────┘
        │
        ↓
┌─────────────────────────────────────────────────────────┐
│    RoleSelection/Index.vue (First Dashboard)            │
│  ├─ Props: currentRole, organisations, quickStats     │
│  ├─ Data: role cards (admin, commission, voter)        │
│  └─ Actions: switchRole() → POST /switch-role/{role}   │
└─────────────────────────────────────────────────────────┘
        │
        ├─→ User selects Admin role
        │        │
        │        ↓
        │   ┌──────────────────────────┐
        │   │  Admin/Dashboard.vue     │
        │   ├─ Props: organisations    │
        │   ├─ Stats: elections, users │
        │   └─ Manage: organisations   │
        │   └──────────────────────────┘
        │
        ├─→ User selects Commission role
        │        │
        │        ↓
        │   ┌──────────────────────────┐
        │   │ Commission/Dashboard.vue │
        │   ├─ Props: elections        │
        │   ├─ Stats: votes, voters    │
        │   └─ Actions: monitor,manage │
        │   └──────────────────────────┘
        │
        └─→ User selects Voter role
                 │
                 ↓
            ┌──────────────────────────┐
            │ Vote/Dashboard.vue       │
            ├─ Props: elections,history│
            ├─ Stats: pending, cast    │
            └─ Actions: vote, verify   │
            └──────────────────────────┘
```

---

## Performance Considerations

### Caching Strategy

```
┌────────────────────────────────────────────────────────┐
│                    CACHE LAYERS                        │
├────────────────────────────────────────────────────────┤
│                                                        │
│ 1. User Dashboard Roles Cache (60 minutes)            │
│    Key: "user_{id}_dashboard_roles"                   │
│    Value: ['admin', 'commission', 'voter']            │
│    Hit Rate: ~99% (only refreshed if roles change)    │
│                                                        │
│ 2. Session (Browser/Server)                           │
│    Key: 'dashboard_role'                              │
│    Value: 'commission'                                │
│    Duration: Session lifetime (~hours)                │
│                                                        │
│ 3. Vue Component State (Browser Memory)               │
│    ├─ organisation data (loaded once)                 │
│    ├─ Election data (refreshed on navigate)           │
│    └─ Translation data (preloaded)                    │
│                                                        │
└────────────────────────────────────────────────────────┘

CACHE INVALIDATION:
When a user's role changes (admin assigns/removes role):
→ User's cache is flushed: Cache::forget("user_{id}_dashboard_roles")
→ User needs to re-login or refresh page to see updated roles
```

### Database Query Optimization

```
Typical Request: GET /dashboard/commission
Without Optimization:
  Query 1: SELECT * FROM users WHERE id = 1
  Query 2: SELECT * FROM user_organization_roles WHERE user_id = 1
  Query 3: SELECT * FROM elections WHERE id IN (...)
  Query 4: SELECT COUNT(*) FROM votes WHERE election_id = 1
  ... etc (N+1 queries)

With Optimization (current):
  Query 1: SELECT * FROM users WHERE id = 1
           →includes eager loading of organizationRoles
  Query 2: SELECT * FROM elections WHERE id IN (...)
           →preloaded with single query

  Total: 2-3 queries instead of 10+
```

---

## Security Considerations

### Role-Based Access Control (RBAC)

```
┌──────────────────────────────────────────────────────┐
│           SECURITY LAYERS                            │
├──────────────────────────────────────────────────────┤
│                                                      │
│ Layer 1: Authentication                             │
│ ├─ User must be logged in                           │
│ ├─ Session token validated on each request          │
│ └─ CSRF token required for state-changing requests  │
│                                                      │
│ Layer 2: Authorization (Role Check)                 │
│ ├─ User must have role in database                  │
│ ├─ Role stored in user_organization_roles table     │
│ ├─ Database record = source of truth                │
│ └─ Cannot fake role via session                     │
│                                                      │
│ Layer 3: Session Validation                         │
│ ├─ Selected role must match database role           │
│ ├─ Session can be hijacked but role check fails     │
│ └─ Middleware validates on each request             │
│                                                      │
│ Layer 4: Data Access Control                        │
│ ├─ Queries filtered by user's organisations         │
│ ├─ Commission users see only their elections        │
│ ├─ Voters see only assigned elections               │
│ └─ No cross-tenant data leakage                     │
│                                                      │
└──────────────────────────────────────────────────────┘

ATTACK PREVENTION:
━━━━━━━━━━━━━━━

✓ Privilege Escalation Prevented
  User cannot modify session to add 'admin' role
  → Middleware checks database, not session alone

✓ Cross-Tenant Access Prevented
  Data queries are scoped to user's organisations
  → Cannot access other organisation's elections

✓ Session Hijacking Mitigated
  Hijacker gets session but cannot change role
  → Database role check on every request

✓ CSRF Attacks Prevented
  Fortify + middleware handle CSRF tokens
  → POST /switch-role requires valid CSRF token
```

---

**Last Updated**: 2026-02-07
**Maintained By**: Development Team
**Status**: Complete Architecture Documentation
