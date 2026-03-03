# Dashboard System - Complete Overview

## What This Guide Covers

This guide provides comprehensive documentation for the **3-Role Dashboard System** implemented in Public Digit. It explains:

1. **Architecture** - How users are routed to different dashboards
2. **Login Flow** - What happens when users authenticate
3. **Dashboard Resolver** - The 6-priority routing system
4. **Authentication** - Email verification and security layers
5. **Testing** - How to run and extend tests
6. **Security** - Multi-tenant isolation and GDPR compliance
7. **Troubleshooting** - Common issues and solutions

---

## Quick Start

### File Navigation

Navigate to these files based on what you need:

| Need | File | Content |
|------|------|---------|
| System overview | **00_OVERVIEW.md** (this file) | What you're reading now |
| How login works | **01_LOGIN_RESPONSE_ARCHITECTURE.md** | Post-login routing logic |
| 6-priority routing | **02_DASHBOARD_RESOLVER_ARCHITECTURE.md** | Priority decision tree |
| User authentication | **03_AUTHENTICATION_FLOW.md** | Email verification & sessions |
| Running tests | **04_TEST_SUITE_GUIDE.md** | PHPUnit test commands |
| Security | **05_SECURITY_GUIDELINES.md** | Multi-tenant isolation rules |
| Troubleshooting | **06_TROUBLESHOOTING.md** | Common problems & fixes |

---

## System Architecture at a Glance

### Three Dashboard Roles

```
┌─────────────────────────────────┐
│    PUBLIC DIGIT PLATFORM        │
├─────────────────────────────────┤
│                                 │
│  ┌────────────────────────────┐ │
│  │  ADMIN DASHBOARD           │ │
│  │  /dashboard/admin          │ │
│  │  Organisation managers     │ │
│  └────────────────────────────┘ │
│                                 │
│  ┌────────────────────────────┐ │
│  │  COMMISSION DASHBOARD      │ │
│  │  /dashboard/commission     │ │
│  │  Election monitors         │ │
│  └────────────────────────────┘ │
│                                 │
│  ┌────────────────────────────┐ │
│  │  VOTER DASHBOARD           │ │
│  │  /vote                     │ │
│  │  Members casting votes     │ │
│  └────────────────────────────┘ │
│                                 │
└─────────────────────────────────┘
```

### 6-Priority Routing System

When a user logs in, **DashboardResolver** checks in this order:

1. **Active Voting Session** → Direct to `/vote/{slug}`
2. **Active Election Available** → `/election/dashboard`
3. **New User Welcome** → `/dashboard/welcome`
4. **Multiple Roles** → `/dashboard/roles`
5. **Single Role** → Role-specific dashboard
6. **Platform User** → `/dashboard` (fallback)

---

## Database Scope

### Landlord Database

Stores **platform administration**:
- Tenants (organisations)
- Platform admins
- Shared configuration

### Tenant Database

Stores **election data** (scoped to one organisation):
- Elections
- Members / Voters
- Candidates
- Votes (completely anonymous)
- Results

---

## Key Security Principles

✅ **100% Tenant Isolation** - Organisations cannot see each other's data

✅ **Email Verification Required** - Every user must verify before accessing dashboards

✅ **Role-Based Access Control** - Middleware enforces role requirements

✅ **Vote Anonymity** - Votes table has NO user_id column

✅ **Audit Logging** - Every action logged per-user, per-election

---

## Current Implementation Status

| Component | Status | Coverage |
|-----------|--------|----------|
| Login routing | ✅ Complete | 100% |
| Dashboard resolver | ✅ Complete | 100% |
| Email verification | ✅ Complete | 100% |
| Role middleware | ✅ Complete | 100% |
| Admin dashboard | 🚧 In progress | 40% |
| Commission dashboard | 🚧 In progress | 50% |
| Voter dashboard | ✅ Complete | 100% |
| Tests | ✅ Complete | 16 tests |

---

## Next Steps

1. **Read 01_LOGIN_RESPONSE_ARCHITECTURE.md** to understand the entry point
2. **Read 02_DASHBOARD_RESOLVER_ARCHITECTURE.md** to understand the 6-priority system
3. **Read 03_AUTHENTICATION_FLOW.md** to understand security layers
4. **Read 04_TEST_SUITE_GUIDE.md** to learn how to run tests
5. **Read 05_SECURITY_GUIDELINES.md** to understand multi-tenancy
6. **Read 06_TROUBLESHOOTING.md** if you encounter issues

---

## Key Files

### Controllers & Services
- `app/Http/Responses/LoginResponse.php` - Post-login routing
- `app/Services/DashboardResolver.php` - 6-priority routing engine
- `app/Http/Controllers/Admin/AdminDashboardController.php` - Admin dashboard
- `app/Http/Controllers/Commission/CommissionDashboardController.php` - Commission dashboard
- `app/Http/Controllers/Voter/VoterDashboardController.php` - Voter dashboard (aliased as `/vote`)

### Middleware & Authorization
- `app/Http/Middleware/CheckUserRole.php` - Role validation
- `app/Http/Middleware/EnsureEmailIsVerified.php` - Email verification check
- `app/Models/User.php` - Dashboard role detection

### Tests
- `tests/Feature/Auth/DashboardResolverPriorityTest.php` - 16 comprehensive tests

### Vue Components
- `resources/js/Pages/Welcome/Dashboard.vue` - New user onboarding
- `resources/js/Pages/RoleSelection/Index.vue` - Role picker
- `resources/js/Pages/Admin/Dashboard.vue` - Admin interface
- `resources/js/Pages/Commission/Dashboard.vue` - Commission interface
- `resources/js/Pages/Vote/Dashboard.vue` - Voting interface

---

## Critical Design Decisions

### 1. Three-Role System
Rather than complex hierarchical permissions, we use explicit three-role model:
- **Admin** - Full organisation control
- **Commission** - Election monitoring only
- **Voter** - Voting only (limited to election window)

### 2. Priority-Based Routing
Rather than checking all conditions, we use priority order:
1. Most time-critical first (active voting)
2. Then less critical (active elections)
3. Then user onboarding
4. Then complex scenarios (multi-role)
5. Finally fallback

This ensures users experience correct routing even under edge cases.

### 3. Email Verification Enforcement
Not one middleware layer but TWO:
1. `EnsureEmailIsVerified` middleware on dashboard routes
2. Explicit check in `DashboardResolver.resolve()`

This belt-and-suspenders approach ensures no unverified users reach dashboards.

### 4. Vote Anonymity by Design
The `votes` table has **NO user_id column** by design. This is not an oversight - it's intentional:
- Votes cannot be linked to users
- No possible vote coercion
- Election integrity guaranteed

---

## Support & Questions

- **"How does login work?"** → See 01_LOGIN_RESPONSE_ARCHITECTURE.md
- **"What's the routing priority?"** → See 02_DASHBOARD_RESOLVER_ARCHITECTURE.md
- **"How is the system secure?"** → See 05_SECURITY_GUIDELINES.md
- **"How do I run tests?"** → See 04_TEST_SUITE_GUIDE.md
- **"Something's broken"** → See 06_TROUBLESHOOTING.md

---

**Built with:** Laravel 11, Vue 3, Inertia.js, PHPUnit
**Last Updated:** March 4, 2026
