# 🚀 Demo Mode Two-Level System - Implementation Complete

## Overview

The election engine now supports **two operating modes**:

- **MODE 1**: Demo testing without organisation (`organisation_id = NULL`)
- **MODE 2**: Live multi-tenancy with organisation (`organisation_id = X`)

All code is unified - no branching logic required. The `BelongsToTenant` trait automatically handles both modes based on `session('current_organisation_id')`.

---

## ✅ Implementation Status

### Phase 1: Database Migrations
- ✅ All 8 tables have `organisation_id` column
- ✅ All columns are `NULLABLE` (supports both modes)
- ✅ All have indexes for performance
- ✅ All have proper down() methods

**Tables:**
1. elections
2. codes
3. votes
4. demo_votes
5. results
6. demo_results
7. voter_slugs
8. voter_slug_steps

### Phase 2: Models
- ✅ All 8 models use `BelongsToTenant` trait
- ✅ Models correctly include `organisation_id` in `$fillable`
- ✅ Vote anonymity preserved (no user_id columns)

### Phase 3: Middleware & Context
- ✅ TenantContext middleware sets session from user's organisation_id
- ✅ Supports NULL values for demo mode
- ✅ Logging added for audit trail

### Phase 4: Controllers
- ✅ CodeController properly tenant-aware
- ✅ VoteController auto-populates organisation_id
- ✅ All queries automatically scoped by trait

### Phase 5: Helper Functions
- ✅ `is_demo_mode()` - Check if in demo mode (NULL)
- ✅ `is_tenant_mode()` - Check if in tenant mode (with org)
- ✅ `current_mode()` - Get mode label (MODE_1_DEMO or MODE_2_TENANT_X)
- ✅ `get_tenant_id()` - Get current organisation_id (NULL or value)

### Phase 6: Demo Setup
- ✅ `SetupDemoElection` command with MODE 1 support
- ✅ `DemoElectionSeeder` updated
- ✅ Both verify `organisation_id = NULL`

### Phase 7: Testing
- ✅ `DemoModeTest` with 6 comprehensive tests
- ✅ Tests verify MODE 1 isolation
- ✅ Tests verify MODE 2 isolation
- ✅ Tests verify cross-mode isolation
- ✅ Tests verify helper functions
- ✅ Tests verify vote anonymity

---

## 📊 How It Works

### MODE 1: Demo Testing (No Organisation)

**Setup:**
```php
// User with NULL organisation
$demoUser = User::create([
    'name' => 'Test Customer',
    'organisation_id' => null  // ← MODE 1
]);

// Login triggers TenantContext middleware
$this->actingAs($demoUser);
// session('current_organisation_id') = null
```

**Creating Data:**
```php
// Trait automatically sets organisation_id = NULL
$election = Election::create([
    'name' => 'Demo Election',
    'type' => 'demo',
    // organisation_id auto-filled as NULL
]);

$vote = DemoVote::create([
    'election_id' => $election->id,
    // organisation_id auto-filled as NULL
]);
```

**Querying:**
```php
// Global scope filters: WHERE organisation_id IS NULL
$votes = DemoVote::where('election_id', $election->id)->get();
// Only returns votes with organisation_id = NULL
```

**Data Storage:**
```sql
elections:      id=1, name='Demo', organisation_id=NULL
demo_votes:     id=1, election_id=1, organisation_id=NULL
demo_results:   id=1, vote_id=1, organisation_id=NULL
```

### MODE 2: Live Multi-Tenancy (With Organisation)

**Setup:**
```php
// User with organisation
$orgUser = User::create([
    'name' => 'Org Admin',
    'organisation_id' => 1  // ← MODE 2
]);

// Login triggers TenantContext middleware
$this->actingAs($orgUser);
// session('current_organisation_id') = 1
```

**Creating Data:**
```php
// Trait automatically sets organisation_id = 1
$election = Election::create([
    'name' => 'Real Election',
    'type' => 'real',
    // organisation_id auto-filled as 1
]);

$vote = Vote::create([
    'election_id' => $election->id,
    // organisation_id auto-filled as 1
]);
```

**Querying:**
```php
// Global scope filters: WHERE organisation_id = 1
$votes = Vote::where('election_id', $election->id)->get();
// Only returns votes with organisation_id = 1
```

**Data Storage:**
```sql
elections:      id=2, name='Real', organisation_id=1
votes:          id=1, election_id=2, organisation_id=1
results:        id=1, vote_id=1, organisation_id=1
```

---

## 🛠️ Usage Commands

### Setup Demo Election (MODE 1)
```bash
# First time setup
php artisan demo:setup

# Force recreate (delete and rebuild)
php artisan demo:setup --force
```

**Output:**
```
🚀 Setting up demo election (MODE 1 - No Organisation)...
✅ Created Demo Election: Demo Election
   ID: 1
   Organisation ID: NULL (Demo Mode)
   ✓ Correctly set to NULL (MODE 1 - No organisation needed)
   ...
📊 Demo Election Summary:
  ✅ Election: Demo Election
  ✅ Posts: 3
  ✅ Total Candidates: 9
  ✅ Mode: MODE 1 (No organisation - Demo testing)
  ✅ Organisation ID: NULL

🚀 Access at: http://localhost:8000/election/demo/start
📢 This demo election requires NO organisation setup!
   Customers can test the voting system immediately.
```

### Run Database Migrations
```bash
php artisan migrate
```

### Run Tests
```bash
# Run demo mode tests
php artisan test tests/Feature/DemoModeTest.php

# Run with verbose output
php artisan test tests/Feature/DemoModeTest.php -v

# Run specific test
php artisan test tests/Feature/DemoModeTest.php --filter test_mode1_demo_works_without_organisation
```

### Seed Demo Data
```bash
# Seed demo election data
php artisan db:seed --class=DemoElectionSeeder

# Or run all seeders
php artisan db:seed
```

---

## 📝 Helper Functions in Code

### Check Current Mode
```php
// In controllers, views, or services
if (is_demo_mode()) {
    // Running in MODE 1 - no organisation
    // Show demo-specific UI
} else {
    // Running in MODE 2 - with organisation
    // Show production UI
}
```

### Get Current Tenant ID
```php
$orgId = get_tenant_id();  // NULL for MODE 1, or 1,2,3... for MODE 2
```

### Get Mode Label
```php
$mode = current_mode();  // 'MODE_1_DEMO' or 'MODE_2_TENANT_5'
```

### Check Tenant Mode
```php
if (is_tenant_mode()) {
    // Only accessible if user has organisation
}
```

---

## 🔐 Security & Vote Anonymity

### Confirmed: Vote Anonymity Preserved

✅ **No user_id in votes tables**
- Votes table: NO user_id column
- Results table: NO user_id column
- Demo votes/results: NO user_id column

✅ **organisation_id is for DATA ISOLATION only**
- NOT used to identify voters
- NOT linked to user identity
- Purely for multi-tenant scoping

✅ **Audit trail preserved**
- `voting_code` - hash for vote verification
- `ip_address` - for security audit
- `user_agent` - for security audit
- `timestamps` - for activity tracking

**Example:**
```php
$vote = Vote::create([
    'election_id' => 1,           // Which election
    'organisation_id' => 1,       // Which org (isolation only)
    'voting_code' => 'hash...',   // Audit trail
    'ip_address' => '192.168...',  // Security audit
    'user_agent' => 'Mozilla...',  // Security audit
    // NO user_id - ANONYMOUS
]);
```

---

## 📊 Data Isolation Examples

### MODE 1: Demo (NULL)
```
User A logs in (org_id = NULL)
  → session('current_organisation_id') = NULL
  → Can see: Elections with organisation_id = NULL
  → Cannot see: Elections with organisation_id = 1, 2, 3...

Election with organisation_id = NULL
  → Only visible to MODE 1 users
  → Completely isolated from tenants
```

### MODE 2: Tenant 1
```
User B logs in (org_id = 1)
  → session('current_organisation_id') = 1
  → Can see: Elections with organisation_id = 1
  → Cannot see: Elections with organisation_id = NULL, 2, 3...

Election with organisation_id = 1
  → Only visible to Org 1 users
  → Completely isolated from demo and other orgs
```

### MODE 2: Tenant 2
```
User C logs in (org_id = 2)
  → session('current_organisation_id') = 2
  → Can see: Elections with organisation_id = 2
  → Cannot see: Elections with organisation_id = NULL, 1, 3...

Election with organisation_id = 2
  → Only visible to Org 2 users
  → Completely isolated from demo and other orgs
```

---

## 🧪 Test Coverage

### DemoModeTest (6 tests)

1. **test_mode1_demo_works_without_organisation**
   - Verifies MODE 1 can create elections and votes
   - Confirms `organisation_id = NULL`
   - Checks data isolation

2. **test_mode2_tenant_works_with_organisation**
   - Verifies MODE 2 can create elections and votes
   - Confirms `organisation_id = X`
   - Checks data isolation

3. **test_mode1_and_mode2_are_isolated**
   - Creates 3 users (1 demo, 2 tenants)
   - Verifies each sees only their own data
   - Confirms complete isolation

4. **test_tenant_helper_functions**
   - Tests `is_demo_mode()`
   - Tests `is_tenant_mode()`
   - Tests `current_mode()`
   - Tests `get_tenant_id()`

5. **test_vote_anonymity_preserved_in_both_modes**
   - Confirms NO user_id in votes
   - Verifies audit data (voting_code, ip_address)
   - Works in both MODE 1 and MODE 2

6. **test_mode_transitions**
   - Tests switching between modes
   - Verifies session context changes
   - Confirms data isolation persists

---

## 🚀 Quick Start for Developers

### First Time Setup
```bash
# 1. Run migrations (includes all organisation_id columns)
php artisan migrate

# 2. Create demo election
php artisan demo:setup

# 3. Run tests to verify
php artisan test tests/Feature/DemoModeTest.php

# 4. Access demo election
# Go to: http://localhost:8000/election/demo/start
```

### Creating Demo Data Programmatically
```php
// Set MODE 1 context
session(['current_organisation_id' => null]);

// Create election (organisation_id auto-filled as NULL)
$election = Election::create([
    'name' => 'My Demo Election',
    'slug' => 'my-demo-' . now()->timestamp,
    'type' => 'demo',
]);

// Verify
$this->assertNull($election->organisation_id);
```

### Creating Tenant Data Programmatically
```php
// Set MODE 2 context for Org 5
session(['current_organisation_id' => 5]);

// Create election (organisation_id auto-filled as 5)
$election = Election::create([
    'name' => 'Org 5 Election',
    'slug' => 'org5-election',
    'type' => 'real',
]);

// Verify
$this->assertEquals(5, $election->organisation_id);
```

---

## 📋 File Changes Summary

### New Files Created
- `app/Helpers/TenantHelper.php` - Helper functions for mode detection
- `tests/Feature/DemoModeTest.php` - 6 comprehensive tests

### Files Modified
- `app/Traits/BelongsToTenant.php` - Updated to handle NULL values
- `app/Http/Middleware/TenantContext.php` - Added logging
- `app/Console/Commands/SetupDemoElection.php` - MODE 1 support
- `database/seeders/DemoElectionSeeder.php` - MODE 1 verification

### Migration Files (All Verified NULLABLE)
- `2026_02_19_185532_add_organisation_id_to_elections_table.php`
- `2026_02_19_190930_add_organisation_id_to_codes_table.php`
- `2026_02_19_190931_add_organisation_id_to_votes_table.php`
- `2026_02_19_204554_add_organisation_id_to_demo_votes_table.php`
- `2026_02_19_190933_add_organisation_id_to_results_table.php`
- `2026_02_19_204602_add_organisation_id_to_demo_results_table.php`
- `2026_02_19_192312_add_organisation_id_to_voter_slugs_table.php`
- `2026_02_19_192313_add_organisation_id_to_voter_slug_steps_table.php`

---

## ✅ Verification Checklist

Before deploying to production:

- [ ] Run all migrations: `php artisan migrate`
- [ ] Create demo election: `php artisan demo:setup`
- [ ] Run all tests: `php artisan test tests/Feature/DemoModeTest.php`
- [ ] Verify demo mode works (access demo election)
- [ ] Create test org and verify tenant mode
- [ ] Confirm vote anonymity (no user_id in votes)
- [ ] Check data isolation (mode 1 can't see mode 2 data)
- [ ] Verify helper functions work correctly
- [ ] Test session context switching
- [ ] Confirm logging records mode changes

---

## 🎯 Next Steps (Phase 2)

Future enhancements (not required for MVP):

1. **Audit Report Generation**
   - Generate CSV exports of election data
   - Track audit trail with IP/user_agent

2. **Advanced Security Middleware**
   - Validate tenant ownership of resources
   - 403 Forbidden for invalid access

3. **Foreign Key Constraints**
   - Add composite FK constraints with organisation_id
   - Enforce referential integrity

4. **Reporting Dashboard**
   - Analytics by organisation
   - Demo vs tenant comparison

---

## 📞 Support

For issues or questions:
- Check logs: `storage/logs/voting_audit.log`
- Run tests to verify isolation
- Use helper functions to debug mode
- Verify session('current_organisation_id') in controllers

**All two-level demo system functionality is now complete and tested!** ✅
