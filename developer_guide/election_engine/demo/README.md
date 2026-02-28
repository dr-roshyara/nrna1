# Demo Election System - Auto-Creation Architecture

**Last Updated**: 2026-02-22
**Version**: 2.0 (Auto-Creation Feature)
**Status**: Production Ready ✅

---

## 📖 Overview

This document explains the **Demo Election Auto-Creation System** - a feature that automatically creates organisation-specific demo elections when users with an `organisation_id` attempt to access demo voting for the first time.

### What Problem Does This Solve?

**Before**: Organisation administrators had to manually run `php artisan demo:setup --org={id}` to create demo elections. This was:
- ❌ Manual and error-prone
- ❌ Requires command-line access
- ❌ Easy to forget or skip

**After**: Demo elections are automatically created on-demand:
- ✅ Zero manual setup required
- ✅ Automatic when user first accesses demo voting
- ✅ Each organisation gets its own isolated demo
- ✅ Proper context preserved throughout voting flow

---

## 🎯 Core Concept

The system uses a **three-tier priority resolution** to determine which demo election a user gets:

```
User Request to Access Demo Voting
         ↓
Does user have organisation_id?
    ↙ YES              NO ↘
    ↓                      ↓
Org-specific           Platform Demo
demo exists?           (fallback)
  ↙ YES  NO ↘
  ↓        ↓
Return   AUTO-CREATE
Existing with service
 demo      ↓
        Return new
        org-specific
         demo
```

---

## 🏗️ Architecture

### Three Core Components

#### 1. **DemoElectionCreationService**
**File**: `app/Services/DemoElectionCreationService.php`

Responsible for creating complete demo election structures:

```
Input: organisationId, organisation model
         ↓
    Create Election
    (with organisation_id)
         ↓
    Create 2 National Posts
    - President (3 candidates)
    - Vice President (3 candidates)
         ↓
    Create 1 Regional Post
    - State Representative (Europe, 3 candidates)
         ↓
    Create 9 DemoCode Records
    (1 per candidate, 4 codes each)
         ↓
    Output: Complete Election ready for voting
```

**Key Method**:
```php
createOrganisationDemoElection(int $organisationId, organisation $organisation): Election
```

**Guarantees**:
- ✅ `organisation_id` propagated to ALL records (Election, Posts, Candidates, Codes)
- ✅ Unique slug per organisation: `demo-election-org-{id}`
- ✅ Audit logging to `voting_audit` channel
- ✅ Consistent data structure across all organisations

#### 2. **DemoElectionResolver**
**File**: `app/Services/DemoElectionResolver.php`

Decision logic for which demo election to use:

```php
// Priority 1: Org-specific demo (auto-creates if missing)
if ($user->organisation_id !== null) {
    $orgDemo = find existing OR auto-create;
    if ($orgDemo) return $orgDemo;
}

// Priority 2: Platform demo (fallback)
$platformDemo = find with organisation_id = NULL;
if ($platformDemo) return $platformDemo;

// Priority 3: No demo available
return null;
```

**Key Method**:
```php
getDemoElectionForUser(User $user): ?Election
```

**Auto-Creation Logic**:
1. Check if org-specific demo exists
2. If NOT found AND user has `organisation_id`:
   - Fetch Organisation model
   - Call DemoElectionCreationService
   - Log auto-creation to audit channel
   - Return created demo
3. If found, return existing (no duplication)

#### 3. **Service Registration**
**File**: `app/Providers/AppServiceProvider.php`

Registers both services as singletons:

```php
$this->app->singleton(DemoElectionCreationService::class, function () {
    return new DemoElectionCreationService();
});

$this->app->singleton(DemoElectionResolver::class, function () {
    return new DemoElectionResolver();
});
```

**Why Singletons?**
- ✅ Consistent instance across entire request
- ✅ Dependency injection friendly
- ✅ Easy to mock in tests
- ✅ Performance: no repeated instantiation

---

## 🔄 Data Flow Example

### Scenario: User from NRNA Europe (org_id=5) Accesses Demo Voting

```
Step 1: User navigates to /election/demo/start
         ↓
Step 2: ElectionController.startDemo() called
         ↓
Step 3: DemoElectionResolver.getDemoElectionForUser($user) called
         ↓
Step 4: Check: Is org 5's demo in database?
         ↓
    NO - Demo doesn't exist yet
         ↓
Step 5: Create Organisation model for org_id=5
         ↓
Step 6: DemoElectionCreationService.createOrganisationDemoElection(5, $org)

         Creation Process:
         ├─ Election table
         │  └─ INSERT: {name, slug='demo-election-org-5', type='demo',
         │             organisation_id=5, is_active=true}
         │
         ├─ DemoPost table (3 posts)
         │  ├─ President (national)
         │  │  └─ 3 DemoCandidacy records
         │  │     └─ 3 DemoCode records
         │  ├─ Vice President (national)
         │  │  └─ 3 DemoCandidacy records
         │  │     └─ 3 DemoCode records
         │  └─ State Representative (Europe)
         │     └─ 3 DemoCandidacy records
         │        └─ 3 DemoCode records
         │
         └─ Log: {level='info', channel='voting_audit',
                  organisation_id=5, action='auto-created'}
         ↓
Step 7: Return Election object to resolver
         ↓
Step 8: Resolver returns election to controller
         ↓
Step 9: VoterSlugService.getOrCreateActiveSlug($user)

         Creates voter slug with:
         ├─ user_id = user.id
         ├─ election_id = created_election.id
         └─ organisation_id = 5 (from election)
         ↓
Step 10: Redirect to /v/{slug}/code-create page
         ↓
Step 11: User can now vote with their organisation's demo
```

---

## 🗄️ Database Schema Context

### Key Tables Affected

**elections**
```
id: PRIMARY KEY
name: 'Demo Election'
slug: 'demo-election-org-5'
type: 'demo'
organisation_id: 5 (CRITICAL - enables isolation)
is_active: true
```

**demo_posts**
```
id: PRIMARY KEY
post_id: 'president-{election_id}'
election_id: FK → elections
organisation_id: 5 (CRITICAL - inherited from election)
is_national_wide: 1 or 0
state_name: 'Europe' or NULL
```

**demo_candidacies**
```
id: PRIMARY KEY
post_id: FK → demo_posts
election_id: FK → elections
organisation_id: 5 (CRITICAL - inherited from election)
user_name: 'Alice Johnson'
```

**demo_codes**
```
id: PRIMARY KEY
election_id: FK → elections
organisation_id: 5 (CRITICAL - inherited from election)
code1, code2, code3, code4: verification codes
```

**voter_slugs**
```
id: PRIMARY KEY
user_id: FK → users
election_id: FK → elections (points to auto-created demo)
organisation_id: 5 (CRITICAL - from election context)
slug: unique voting token
```

**Important**: Every table has `organisation_id` for tenant isolation via `BelongsToTenant` trait.

---

## ✅ Test Coverage

### 6 New Tests (All Passing)

**Unit Tests** (3):
- `DemoElectionCreationServiceTest::test_creates_election_with_correct_organisation_id`
- `DemoElectionCreationServiceTest::test_creates_national_posts_with_candidates`
- `DemoElectionCreationServiceTest::test_creates_regional_posts_for_europe`

**Integration Tests** (3):
- `DemoElectionAutoCreationTest::test_auto_creates_org_specific_demo_when_user_accesses_voting`
- `DemoElectionAutoCreationTest::test_uses_existing_org_demo_when_already_created`
- `DemoElectionAutoCreationTest::test_organisation_id_propagated_to_all_demo_data`

### Existing Tests - No Regression

- **VoterSlugServiceTest**: 29/29 passing ✅
- **DemoElectionResolverTest**: 14/14 passing ✅

**Total**: 49/49 tests passing ✅

---

## 🔐 Security & Isolation Guarantees

### Multi-Tenancy Protection

**1. Database-Level Isolation**
```php
// Every query on demo models gets filtered:
DemoPost::where(...)  // Automatically adds: AND organisation_id = current_org
DemoCandidacy::where(...)  // Same filtering
DemoCode::where(...)  // Same filtering
```

**2. Unique Slugs Per Organisation**
```
Org 1: demo-election-org-1
Org 2: demo-election-org-2
Org 5: demo-election-org-5
```
→ No conflicts, proper isolation

**3. Context Preservation**
```php
Election → organisation_id=5
    ↓
DemoPost → organisation_id=5 (inherited)
    ↓
DemoCandidacy → organisation_id=5 (inherited)
    ↓
DemoCode → organisation_id=5 (inherited)
    ↓
VoterSlug → organisation_id=5 (from election)
```

**4. Audit Trail**
```
Log Channel: voting_audit
Event: Demo election auto-created
Data:
  - user_id: who triggered it
  - organisation_id: which org
  - election_id: what was created
  - timestamp: when
```

---

## 🚀 Production Deployment

### Pre-Deployment Verification

```bash
# 1. Run all tests
php artisan test --filter="DemoElection|VoterSlug"
# Expected: 49/49 passing

# 2. Check database has organisation_id columns
php artisan tinker
> DB::select("SHOW COLUMNS FROM demo_posts");
// Verify organisation_id exists

# 3. Verify global scopes are active
> $post = DemoPost::first();
> $post->organisation_id;  // Should have value, not NULL for all
```

### Deployment Steps

```bash
# 1. Pull latest code
git pull origin main

# 2. Run any pending migrations (none for this feature)
php artisan migrate

# 3. Clear cache
php artisan cache:clear
php artisan config:cache

# 4. Run tests locally
php artisan test

# 5. Monitor logs for auto-creation
tail -f storage/logs/laravel.log | grep "auto-created"
```

---

## 📚 Related Documentation

- **AUTO_CREATION.md** - Deep dive into auto-creation feature
- **QUICK_REFERENCE.md** - Commands and common operations
- **ARCHITECTURE.md** - System design and class relationships
- **TROUBLESHOOTING.md** - Common issues and solutions
- **../voter_slug/README.md** - Voter slug system (uses auto-created demos)

---

## 💡 Key Takeaways for Future Developers

### When Reading This in 1 Year...

**Remember:**
1. **The Problem**: Demo elections needed manual creation (slow, error-prone)
2. **The Solution**: Auto-create on-demand when users access voting
3. **The Implementation**: Three components working together:
   - Service creates demo data
   - Resolver decides which demo to use
   - AppServiceProvider registers everything
4. **The Safety Net**: Tests verify 49 scenarios across new and existing functionality

### Critical Points

- ✅ **Organisation_id is EVERYWHERE** - Election, Posts, Candidates, Codes, VoterSlugs
- ✅ **Global scopes filter automatically** - Use `withoutGlobalScopes()` only in tests
- ✅ **Singletons for consistency** - Both services registered as singletons
- ✅ **No breaking changes** - Platform demo still works as fallback
- ✅ **Audit logging enabled** - All auto-creations logged to `voting_audit`

---

## 🔗 Implementation Timeline

- **2026-02-22**: Initial implementation
  - Created DemoElectionCreationService (200 lines)
  - Modified DemoElectionResolver (60 new lines)
  - Added service registration (4 lines)
  - Created 6 comprehensive tests
  - Commit: `da2bcc0a1`

---

**Status**: ✅ Production Ready
**Maintainer**: Check git history for changes
**Last Review**: 2026-02-22
