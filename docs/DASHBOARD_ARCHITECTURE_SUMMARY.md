# 📊 Common Dashboard Architecture — Complete Analysis

**Comprehensive summary of the three-role system implementation for Public Digit**

---

## Executive Summary

The proposed architecture introduces a **role-based dashboard system** that replaces the current single-dashboard approach with three distinct user journeys:

- **👑 organisation Administrators** — Strategic oversight
- **⚖️ Election Commissioners** — Operational management
- **👤 Voters** — Member participation

---

## Current vs. Proposed Architecture

### Current State (❌ Problems)

```
┌─────────────────┐
│   User Login    │
└────────┬────────┘
         │
         ↓
    /dashboard ← Single interface mixing all roles
    (Voter Portal)
```

**Problems:**
- ❌ Admins see voter interface (confusing for election management)
- ❌ Voters see admin options (security & UX issue)
- ❌ No role isolation (information leakage risk)
- ❌ Poor user experience for administrators

### Proposed State (✅ Solution)

```
┌─────────────────┐
│   User Login    │
└────────┬────────┘
         │
         ↓
  /dashboard
  (Role Selection) ← Choose your role
    │  │  │
    │  │  └─→ /vote (Voter Portal)
    │  │       └─ Cast votes, view results
    │  │
    │  └─→ /dashboard/commission (Commission Dashboard)
    │       └─ Monitor election, manage voting
    │
    └─→ /dashboard/admin (Admin Dashboard)
        └─ Create elections, manage organisation
```

**Improvements:**
- ✅ Role-specific interfaces (Admin ≠ Voter experience)
- ✅ Clear separation of concerns (principle of least privilege)
- ✅ Better security (limited information exposure)
- ✅ Professional UX (users see only what they need)

---

## Technical Architecture

### Database Structure

```sql
users
├── id, email, name, primary_role
├── relationships:
│   └── roles() → user_roles table

user_roles (pivot table)
├── id, user_id, role, organisation_id, metadata
├── roles: ['admin', 'commission', 'voter']
└── constraints:
    └── unique(user_id, role, organisation_id)

organizations
├── id, name, description, type, settings
├── relationships:
│   ├── users() → many-to-many
│   └── elections() → one-to-many
└── types: ['ngo', 'diaspora', 'cultural', 'professional']

elections
├── ... existing fields ...
├── commission_members (JSON) ← new field
└── relationships:
    └── organisation_id
```

### Key Models

1. **User** (Enhanced)
   - `hasRole(role, org?)` - Check if user has role
   - `getAvailableRoles()` - Get all user's roles
   - `roles()` - Relationship to user_roles
   - `organizations()` - Relationship to organizations

2. **UserRole** (New)
   - Links user to organisation with specific role
   - Stores role-specific metadata
   - One user can have multiple roles in different organizations

3. **organisation** (New)
   - Container for elections and members
   - Assigns admin/commission roles to users
   - Type-based configuration

### Middleware

**CheckUserRole Middleware**
```php
// Usage:
Route::get('/admin', AdminController@index)
    ->middleware('role:admin');

// Checks if user has role
// Redirects to role selection if not
```

---

## Frontend Implementation

### Role Selection Dashboard (Vue 3)

**Location:** `resources/js/Pages/RoleSelection/Index.vue`

**Features:**
- Three role cards (Admin, Commission, Voter)
- Statistics for each role
- Quick action buttons
- Recent activity timeline
- Keyboard navigation (Alt+A/C/V)
- Accessibility features:
  - WCAG 2.1 AA compliant
  - Screen reader support
  - High contrast mode
  - Full keyboard navigation

**Display Logic:**
```vue
<template>
  <!-- Show card only if user has role -->
  <AdminCard v-if="userHasAdminRole" />
  <CommissionCard v-if="userHasCommissionRole" />
  <VoterCard v-if="userHasVoterRole" />
</template>
```

### Separate Dashboards

1. **Admin Dashboard** (`/dashboard/admin`)
   - organisation management
   - Election creation & management
   - Member management
   - Reports & analytics

2. **Commission Dashboard** (`/dashboard/commission/{election_id}`)
   - Election monitoring
   - Live vote tracking
   - Voter support tools
   - Fraud detection alerts

3. **Voter Portal** (`/vote`)
   - List available elections
   - Voting booth
   - Vote verification
   - Voting history

---

## Route Structure

### URL Hierarchy

```
Public Routes:
├── /                    (Welcome page)
├── /demo                (Demo election)
├── /login               (Login form)
└── /register            (Registration)

Authenticated Routes:
├── /dashboard           ← Role selection (DEFAULT)
│
├── /dashboard/admin     ← Admin dashboard
│   ├── /elections
│   ├── /voters
│   └── /reports
│
├── /dashboard/commission  ← Commission dashboard
│   └── /{election_id}
│
└── /vote                ← Voter portal
    ├── /
    └── /election/{id}

Session Routes:
├── /switch-role/{role}  (POST) ← Switch roles
└── /logout              (POST) ← Logout
```

### Middleware Stack

```php
'web' => [
    EncryptCookies,        // Decrypt cookies
    AddQueuedCookies,      // Queue responses
    StartSession,          // Start session
    AuthenticateSession,   // Verify session
    ShareErrors,           // Error sharing
    VerifyCsrf,           // CSRF protection
    SubstituteBindings,   // Route binding
    SetLocale,            // Locale handling
    HandleInertiaRequests, // Inertia props
]
```

---

## Implementation Phases

### Phase 1: Foundation (Week 1)
- [ ] Database migrations
- [ ] User role models
- [ ] Role middleware
- [ ] Role selection controller
- [ ] Role selection component (Vue)
- [ ] Basic styling & accessibility

### Phase 2: Dashboards (Week 2)
- [ ] Admin dashboard controller
- [ ] Admin dashboard Vue component
- [ ] Commission dashboard controller
- [ ] Commission dashboard Vue component
- [ ] Move existing voter dashboard to /vote

### Phase 3: Integration (Week 3)
- [ ] Role switching functionality
- [ ] Navigation updates
- [ ] Session persistence
- [ ] Testing & validation
- [ ] Accessibility testing
- [ ] Deployment

---

## Security Implications

### ✅ Improvements

1. **Principle of Least Privilege**
   - Users see only their role interface
   - Admin options hidden from voters
   - Commission members see limited data

2. **Data Isolation**
   - Middleware enforces role-based access
   - Database queries scoped to user's organisation
   - Sensitive data protected from unauthorized roles

3. **Audit Trail**
   - Role-specific activity logging
   - Track who accesses what
   - Compliance-ready architecture

### ⚠️ Considerations

1. **Migration of Existing Data**
   - Assign roles to current users
   - Determine default roles
   - Backup before migration

2. **Backward Compatibility**
   - Old /dashboard links redirect to /vote
   - Existing voter sessions preserved
   - Graceful fallback for unknown roles

3. **Role Verification**
   - Check user role on every protected request
   - Validate organisation membership
   - Log unauthorized access attempts

---

## Accessibility Compliance

### WCAG 2.1 AA Standards

✅ **Keyboard Navigation**
- Tab navigation between elements
- Enter/Space for selections
- Alt+A/C/V for role shortcuts
- Escape to close menus

✅ **Screen Reader Support**
- ARIA labels on all interactive elements
- Semantic HTML structure
- Live announcements for state changes
- Hidden labels for icons

✅ **Visual Accessibility**
- High contrast mode toggle
- Larger text option
- Color-blind friendly design
- Clear focus indicators

✅ **Motor Accessibility**
- 44px minimum touch targets
- Proper spacing between elements
- No time-limited interactions
- Predictable behavior

---

## Testing Strategy

### Unit Tests
```php
// Test role assignment
test('user can be assigned role');

// Test role checking
test('hasRole returns true for assigned role');
test('hasRole returns false for unassigned role');

// Test middleware
test('user without role is redirected');
test('user with role gains access');
```

### Integration Tests
```php
// Test role selection flow
test('admin user sees admin card');
test('voter user sees voter card');

// Test dashboard access
test('admin can access admin dashboard');
test('voter cannot access admin dashboard');

// Test role switching
test('user can switch between roles');
test('invalid role switch is rejected');
```

### Frontend Tests
```javascript
// Component rendering
test('role cards display for available roles');
test('role cards hidden for unavailable roles');

// Accessibility
test('keyboard shortcuts work (Alt+A/C/V)');
test('screen reader announcements present');
test('high contrast mode toggles');

// Navigation
test('clicking role navigates to dashboard');
test('role switcher updates current role');
```

### E2E Tests
```bash
# User flows
1. Login with admin credentials
2. See admin role card
3. Click admin button
4. Load admin dashboard
5. Switch to voter role
6. See voter interface
```

---

## Migration Plan

### Pre-Migration
- [ ] Create full database backup
- [ ] Test migrations on staging
- [ ] Prepare rollback procedures
- [ ] Document all changes

### Migration Steps
1. **Add columns to existing tables** (non-breaking)
2. **Create new tables** (organizations, user_roles)
3. **Run data seeding** (assign roles to current users)
4. **Deploy new code** (controllers, middleware, routes)
5. **Activate new routes** (role selection becomes /dashboard)
6. **Monitor error logs** (catch issues early)

### Post-Migration
- [ ] Verify all users can login
- [ ] Check role assignments
- [ ] Test all dashboard flows
- [ ] Gather user feedback
- [ ] Monitor performance

---

## Success Metrics

✅ **User Experience**
- Admin can create elections without confusion
- Voter sees simple voting interface
- Role switching is intuitive
- Page load times < 2 seconds

✅ **Security**
- Unauthorized access prevented (0 breaches)
- Role-based access verified on every request
- Activity logs complete and accurate
- Audit trail demonstrates compliance

✅ **Accessibility**
- WCAG 2.1 AA validation passes
- Screen reader testing successful
- Keyboard navigation fully functional
- Mobile experience optimized

✅ **Operations**
- Deployment completes without errors
- Rollback plan tested and ready
- Support team trained
- Documentation complete

---

## Documentation References

The following implementation guides have been created:

1. **ROLE_SELECTION_IMPLEMENTATION.md** (This Directory)
   - Complete step-by-step backend setup
   - Frontend component code
   - Database migrations
   - Testing procedures

2. **common_dashboard.md** (architecture/dashboard/)
   - Conceptual overview
   - Business benefits
   - User experience flow
   - Implementation priorities

3. **common_dashbaord_implementation.md** (architecture/dashboard/)
   - Detailed Vue component
   - Accessibility features
   - Full template with ARIA labels
   - Styling guide

4. **prompt_instructions.md** (architecture/dashboard/)
   - 10-step execution plan
   - Testing commands
   - Deployment checklist
   - Critical notes

---

## Quick Start

### For Developers

```bash
# 1. Read the complete implementation guide
cat docs/ROLE_SELECTION_IMPLEMENTATION.md

# 2. Create database migrations
php artisan make:migration create_user_roles_table

# 3. Run migrations
php artisan migrate

# 4. Create models
# See implementation guide for Model code

# 5. Create middleware
php artisan make:middleware CheckUserRole

# 6. Create controllers
php artisan make:controller RoleSelectionController

# 7. Create Vue component
# See implementation guide for Vue code

# 8. Update routes
# See implementation guide for route configuration

# 9. Test locally
npm run dev
# Navigate to http://localhost:8000/dashboard
```

### For Project Managers

**Timeline:** 2-3 weeks
**Team Size:** 2-3 developers
**Complexity:** Medium
**Risk:** Low (can rollback easily)
**Impact:** High (major UX improvement)

---

## Key Takeaways

1. **Three distinct roles** → Three different interfaces
2. **Database-backed system** → Users can have multiple roles
3. **Middleware-protected routes** → Role enforcement on every request
4. **Accessibility-first design** → WCAG 2.1 AA compliance
5. **Gradual deployment** → Non-breaking migration path
6. **Comprehensive testing** → Unit, integration, E2E coverage

---

**Status:** ✅ Architecture analyzed and documented
**Ready for:** Implementation phase
**Maintenance:** Requires role management system (future)

For detailed implementation, see `ROLE_SELECTION_IMPLEMENTATION.md` in this directory.
