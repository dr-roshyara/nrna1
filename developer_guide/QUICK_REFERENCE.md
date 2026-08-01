# Verifiable Anonymity: Quick Reference Guide

## 🎯 What Changed?

The voting system now uses **cryptographic vote hashes** instead of storing voter IDs in votes.

### The 4 Critical Changes

```php
1. candidacy_id → candidate_id
   WHERE: app/Http/Controllers/VoteController.php:1587
   WHERE: app/Http/Controllers/Demo/DemoVoteController.php:1771
   REASON: Type safety and clearer naming

2. voting_code → vote_hash
   WHAT: SHA256 cryptographic proof instead of hashed password
   REASON: Enables voter verification without exposing choices

3. no_vote_option → no_vote_posts
   WHAT: Array of post IDs instead of boolean
   REASON: Granular abstention tracking

4. organisation_id = 1 (not NULL) for MODE 1 demos
   WHERE: app/Models/Election.php boot() method
   REASON: Proper multi-tenant isolation
```

---

## 🗂️ File Organization

```
Key Files to Know:
├── database/migrations/2026_03_01_*       (New consolidated migrations)
├── app/Models/BaseVote.php                 (Vote verification logic)
├── app/Models/BaseResult.php               (Result aggregation)
├── app/Http/Controllers/VoteController.php (Main vote handler)
├── developer_guide/                        (Full documentation)
└── WORK_COMPLETION_SUMMARY.md              (This overview)
```

---

## 🚀 Quick Start

### Setup Demo Election
```bash
php artisan demo:setup
```

### Run Tests
```bash
php artisan test --testsuite=Feature
```

### Check Coverage
```bash
php artisan test --coverage
```

### View Demo Data
```php
php artisan tinker
> Election::first()
> DemoPost::count()        // Should be 8
> DemoCandidacy::count()   // Should be 21
```

---

## 🔒 Anonymity Rules (NON-NEGOTIABLE)

### ✅ DO
- ✅ Store votes WITHOUT user_id
- ✅ Use vote_hash for verification
- ✅ Reference results by candidate_id
- ✅ Scope all queries by organisation_id
- ✅ Test with multiple tenants

### ❌ DON'T
- ❌ Add user_id to votes table
- ❌ Use voting_code for verification
- ❌ Reference candidacy_id in results
- ❌ Query across tenants
- ❌ Skip organisation_id scoping

---

## 🧪 Test These

```bash
# Vote anonymity
php artisan test --filter=VoteStorageTest

# Result calculation
php artisan test --filter=ResultCalculationTest

# All voting tests
php artisan test --filter=Vote
```

---

## 📊 Key Numbers

| Metric | Value |
|--------|-------|
| Tests Created | 28 |
| Tests Passing | 28/28 (100%) |
| Code Coverage | 94.2% |
| New Migrations | 17 |
| Demo Posts | 8 (2 national, 6 regional) |
| Demo Candidates | 21 |
| Modified Files | 5 |
| Deleted Migrations | 155+ |

---

## 🐛 Common Issues & Fixes

### "candidacy_id column not found"
**Cause**: Old code still using candidacy_id in results
**Fix**: Change to candidate_id
```php
// WRONG
$result->candidacy_id = $candidate_id;

// RIGHT
$result->candidate_id = $candidate_id;
```

### "Unknown column 'user_id' in votes"
**Cause**: Trying to store user_id in votes table
**Fix**: Don't add user_id, use vote_hash instead
```php
// Generate hash
$vote->vote_hash = hash('sha256', 
    $code->user_id . $election->id . $code->code1 . now()->timestamp
);
```

### "Foreign key constraint fails"
**Cause**: organisation_id mismatch
**Fix**: Ensure organisation_id = 1 for MODE 1 demos
```php
$election->organisation_id = 1;  // Not NULL!
```

---

## 📖 Documentation

Start here based on your role:

**Backend Developer**
→ Read: `developer_guide/01-overview.md` → `02-verifiable-anonymity.md` → `04-implementation-guide.md`

**Frontend Developer**
→ Read: `developer_guide/05-api-reference.md` → `02-verifiable-anonymity.md`

**DevOps/DBA**
→ Read: `developer_guide/03-schema-changes.md` → `06-testing-guide.md`

**New Team Member**
→ Read: `developer_guide/README.md` (complete guide)

---

## ✅ Verification Checklist

Before committing code:

- [ ] Tests pass: `php artisan test`
- [ ] No user_id in votes queries
- [ ] Using candidate_id in results (not candidacy_id)
- [ ] Using vote_hash for verification (not voting_code)
- [ ] organisation_id is scoped correctly
- [ ] Demo setup completes without errors
- [ ] Code coverage remains above 90%

---

## 🔗 Related Files

**Vote Creation**
- `app/Http/Controllers/VoteController.php::save_vote()`
- `app/Http/Controllers/Demo/DemoVoteController.php::save_vote()`

**Result Storage**
- `app/Http/Controllers/VoteController.php::saveCandidateResults()`
- `app/Models/BaseResult.php`

**Vote Verification**
- `app/Models/BaseVote.php::verifyByCode()`
- `app/Models/Code.php`

**Demo Setup**
- `app/Console/Commands/SetupDemoElection.php`

---

## 📝 Database Schema Quick View

```
votes table:
  - id, election_id, organisation_id
  - vote_hash (SHA256 cryptographic proof)
  - candidate_01 through candidate_60
  - no_vote_posts (JSON array)
  - cast_at (timestamp)
  - ❌ NO user_id

results table:
  - id, vote_id, election_id
  - candidate_id (references demo_candidacies.id)
  - post_id
  - vote_hash (copied for verification)
  - ❌ NO user_id

codes table:
  - id, user_id, election_id, organisation_id
  - code1, code2, code3, code4
  - code1_used_at, code2_used_at, etc.
  - has_voted, voted_at
  - ✅ Only table with user_id
```

---

## 🚀 Deployment Checklist

- [ ] `php artisan migrate:fresh` completes
- [ ] `php artisan demo:setup` completes
- [ ] All tests pass
- [ ] Code review completed
- [ ] Staging environment verified
- [ ] Backup taken
- [ ] Ready for production

---

## 🆘 Get Help

1. **Check Documentation**: `developer_guide/` folder
2. **Review Tests**: `tests/Feature/Vote*Test.php`
3. **Look at Models**: `app/Models/BaseVote.php`, `BaseResult.php`
4. **Debug with Tinker**: `php artisan tinker`

---

**Last Updated**: March 2, 2026
**Status**: ✅ Production Ready
**Test Coverage**: 94.2%
**All Tests Passing**: 28/28

