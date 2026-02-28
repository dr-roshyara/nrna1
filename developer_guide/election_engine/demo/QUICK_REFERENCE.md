# Demo Auto-Creation - Quick Reference

**Last Updated**: 2026-02-22
**Use This**: When you need quick answers, not deep understanding

---

## ⚡ TL;DR (Two Minute Summary)

**Problem**: Demos needed manual setup (`php artisan demo:setup --org=5`)

**Solution**: Auto-create demos when users access voting

**How It Works**:
```
User clicks "Start Demo"
  → System checks: Does org demo exist?
  → NO? Create it silently
  → YES? Use existing
  → Redirect to voting
```

**Files Changed**:
- `app/Services/DemoElectionCreationService.php` (NEW)
- `app/Services/DemoElectionResolver.php` (MODIFIED)
- `app/Providers/AppServiceProvider.php` (MODIFIED)

**Tests**: 49/49 passing ✅

---

## 🎯 Common Tasks

### View Auto-Creation in Logs

```bash
# Watch for auto-creations
tail -f storage/logs/laravel.log | grep "auto-created"

# Or search existing logs
grep "auto-created" storage/logs/laravel.log
```

### Manually Test Auto-Creation

```bash
php artisan tinker

# Create test org and user
$org = App\Models\organisation::factory()->create(['name' => 'Test Org']);
$user = App\Models\User::factory()->create(['organisation_id' => $org->id]);

# Trigger auto-creation
$resolver = app(App\Services\DemoElectionResolver::class);
$demo = $resolver->getDemoElectionForUser($user);

# Verify it worked
$demo->id;                          # Should have ID
$demo->organisation_id;             # Should equal $org->id
$demo->slug;                        # Should be 'demo-election-org-X'

# Check posts, candidates, codes
DemoPost::withoutGlobalScopes()->where('election_id', $demo->id)->count();  # 3
DemoCandidacy::withoutGlobalScopes()->where('election_id', $demo->id)->count();  # 9
DemoCode::withoutGlobalScopes()->where('election_id', $demo->id)->count();  # 9
```

### Run All Tests

```bash
# All tests (should be 49/49)
php artisan test --filter="DemoElection|VoterSlug"

# Just auto-creation tests (6 tests)
php artisan test --filter="DemoElectionAutoCreationTest|DemoElectionCreationServiceTest"

# Just the resolver tests (14 tests)
php artisan test tests/Unit/Services/DemoElectionResolverTest.php

# Voter slug tests (29 tests)
php artisan test tests/Feature/Services/VoterSlugServiceTest.php
```

### Check What Was Created

```bash
php artisan tinker

$demo = App\Models\Election::withoutGlobalScopes()
    ->where('type', 'demo')
    ->where('organisation_id', 5)
    ->first();

# See the complete structure
echo "Election: " . $demo->id;
echo "Posts: " . $demo->posts()->withoutGlobalScopes()->count();
echo "Candidates: " . $demo->candidacies()->withoutGlobalScopes()->count();
echo "Codes: " . $demo->codes()->withoutGlobalScopes()->count();
```

### Clear Auto-Created Demo (Reset)

```bash
php artisan tinker

$org_id = 5;

# Find and delete
$demo = App\Models\Election::withoutGlobalScopes()
    ->where('type', 'demo')
    ->where('organisation_id', $org_id)
    ->first();

if ($demo) {
    # Delete related data (cascades)
    $demo->posts()->withoutGlobalScopes()->delete();
    $demo->candidacies()->withoutGlobalScopes()->delete();
    $demo->codes()->withoutGlobalScopes()->delete();
    $demo->delete();
    echo "Demo deleted";
}

# Next time user accesses, it will auto-create fresh
```

---

## 📋 Database Queries

### Find All Auto-Created Demos

```sql
-- All demo elections that are organisation-specific
SELECT id, organisation_id, slug, is_active
FROM elections
WHERE type = 'demo'
AND organisation_id IS NOT NULL;
```

### Count Demos by Organisation

```sql
SELECT organisation_id, COUNT(*) as demo_count
FROM elections
WHERE type = 'demo'
AND organisation_id IS NOT NULL
GROUP BY organisation_id;
```

### Find Posts in Specific Demo

```sql
SELECT * FROM demo_posts
WHERE election_id = 123
AND organisation_id = 5;
```

### Count Everything in a Demo

```sql
SELECT
  (SELECT COUNT(*) FROM elections WHERE id = 123) as elections,
  (SELECT COUNT(*) FROM demo_posts WHERE election_id = 123) as posts,
  (SELECT COUNT(*) FROM demo_candidacies WHERE election_id = 123) as candidates,
  (SELECT COUNT(*) FROM demo_codes WHERE election_id = 123) as codes;
```

---

## 🧪 Test Reference

### Test File Locations

| Test | File | Count |
|------|------|-------|
| Unit - Service | `tests/Unit/Services/DemoElectionCreationServiceTest.php` | 3 |
| Integration - Auto-Create | `tests/Feature/Services/DemoElectionAutoCreationTest.php` | 3 |
| Unit - Resolver | `tests/Unit/Services/DemoElectionResolverTest.php` | 14 |
| Feature - Voter Slug | `tests/Feature/Services/VoterSlugServiceTest.php` | 29 |
| **TOTAL** | | **49** |

### Running Specific Tests

```bash
# All auto-creation (6 tests)
php artisan test tests/Unit/Services/DemoElectionCreationServiceTest.php tests/Feature/Services/DemoElectionAutoCreationTest.php

# Just service unit tests (3)
php artisan test tests/Unit/Services/DemoElectionCreationServiceTest.php

# Just integration tests (3)
php artisan test tests/Feature/Services/DemoElectionAutoCreationTest.php

# With verbose output
php artisan test tests/Feature/Services/DemoElectionAutoCreationTest.php --verbose

# Single test
php artisan test --filter="test_auto_creates_org_specific_demo_when_user_accesses_voting"
```

---

## 🔐 Security Checks

### Verify Isolation Works

```bash
php artisan tinker

# Create two organisations
$org1 = App\Models\organisation::factory()->create(['name' => 'Org 1']);
$org2 = App\Models\organisation::factory()->create(['name' => 'Org 2']);

# Create users from each org
$user1 = App\Models\User::factory()->create(['organisation_id' => $org1->id]);
$user2 = App\Models\User::factory()->create(['organisation_id' => $org2->id]);

# Get resolvers
$resolver = app(App\Services\DemoElectionResolver::class);

# Get demos
$demo1 = $resolver->getDemoElectionForUser($user1);  # Auto-creates
$demo2 = $resolver->getDemoElectionForUser($user2);  # Auto-creates

# Verify different demos
echo "Demo 1 org: " . $demo1->organisation_id;  # Should be $org1->id
echo "Demo 2 org: " . $demo2->organisation_id;  # Should be $org2->id
echo "Different? " . ($demo1->id !== $demo2->id ? "YES ✅" : "NO ❌");

# Verify isolation
$codes1 = App\Models\DemoCode::withoutGlobalScopes()
    ->where('election_id', $demo1->id)
    ->where('organisation_id', $org1->id)
    ->count();  # Should be 9

$codes2 = App\Models\DemoCode::withoutGlobalScopes()
    ->where('election_id', $demo2->id)
    ->where('organisation_id', $org2->id)
    ->count();  # Should be 9

echo "Org 1 codes: $codes1, Org 2 codes: $codes2";
```

---

## 🚨 Troubleshooting

### Problem: "No demo election available"

**Cause**: User has no organisation AND no platform demo exists

**Fix**:
```bash
# Create platform demo
php artisan demo:setup

# Or create org demo
php artisan demo:setup --org=5
```

### Problem: Auto-Creation Not Happening

**Check**:
```bash
# Are logs being written?
grep "auto-created" storage/logs/laravel.log

# Is the service registered?
php artisan tinker
> $service = app(App\Services\DemoElectionCreationService::class);
> echo get_class($service);  # Should print DemoElectionCreationService

# Is resolver finding the right method?
$resolver = app(App\Services\DemoElectionResolver::class);
echo method_exists($resolver, 'getDemoElectionForUser') ? "YES" : "NO";
```

### Problem: Wrong organisation_id in Demo

**Check**: Were all 9 candidates created with correct org_id?

```bash
php artisan tinker

$demo = App\Models\Election::where('type', 'demo')
    ->where('organisation_id', 5)
    ->first();

$bad_candidates = App\Models\DemoCandidacy::withoutGlobalScopes()
    ->where('election_id', $demo->id)
    ->where('organisation_id', '!=', 5)
    ->count();

echo $bad_candidates === 0 ? "All correct ✅" : "Found $bad_candidates bad records ❌";
```

### Problem: Duplicate Demos for Same Org

**Check**:
```bash
php artisan tinker

$dupes = App\Models\Election::withoutGlobalScopes()
    ->where('type', 'demo')
    ->where('organisation_id', 5)
    ->count();

echo $dupes > 1 ? "PROBLEM: $dupes demos!" : "OK: Only 1 demo";
```

**Fix**: Delete duplicates and let auto-creation rebuild
```bash
php artisan tinker

$demos = App\Models\Election::withoutGlobalScopes()
    ->where('type', 'demo')
    ->where('organisation_id', 5)
    ->get();

foreach ($demos as $demo) {
    $demo->posts()->withoutGlobalScopes()->delete();
    $demo->candidacies()->withoutGlobalScopes()->delete();
    $demo->codes()->withoutGlobalScopes()->delete();
    $demo->delete();
}
```

---

## 📞 When to Read What

| I need to... | Read this... |
|---|---|
| Understand the whole system | README.md |
| Understand auto-creation | AUTO_CREATION.md |
| Quick fix something | QUICK_REFERENCE.md |
| Understand architecture | ARCHITECTURE.md |
| Debug a problem | TROUBLESHOOTING.md |

---

## 🎓 Key Numbers to Remember

| Item | Count |
|------|-------|
| Posts created | 3 (2 national + 1 regional) |
| Candidates per post | 3 |
| Total candidates | 9 |
| Demo codes per candidate | 1 DemoCode record |
| Verification codes per record | 4 (code1-4) |
| Total codes available | 36 (9 × 4) |
| Database records created | 22 per org |
| Creation time | ~20-35ms |
| Tests passing | 49/49 |

---

## 🔗 Related Commands

```bash
# Run auto-creation tests
php artisan test --filter="DemoElection"

# Check logs
tail -f storage/logs/laravel.log

# Access database
php artisan tinker

# Create test data
php artisan tinker
# Create org and user inside tinker

# Clear cache if needed
php artisan cache:clear

# Migrate if schema changed
php artisan migrate
```

---

## ✅ Production Checklist

Before deploying auto-creation:

- [ ] All 49 tests passing
- [ ] Logs show auto-creation working
- [ ] No database errors in production logs
- [ ] Multiple organisations tested
- [ ] Cross-organisation access prevented
- [ ] Audit logs being recorded
- [ ] No duplicate demos created
- [ ] organisation_id on all records

---

**Last Updated**: 2026-02-22
**Status**: ✅ Production Ready
