# Phase 4: Quick Start Guide

**Duration**: 6 hours | **Risk**: LOW | **Ready**: NOW

---

## What is Phase 4?

After Phase 2's bootstrap migration, your application needs **configuration cleanup** and **environment variable fixes**. Phase 4 ensures everything is properly configured and tested.

---

## 8 Simple Tasks

### Task 1: Verify Bootstrap (20 min)
```bash
# Check bootstrap/app.php is complete
cat bootstrap/app.php | grep -E "withRouting|withMiddleware|middleware->alias"

# Expected: 3 sections found ✅
```

**Check**: All middleware aliases registered? ✅

### Task 2: Fix .env Issues (20 min)

**Issues to fix**:
```bash
# Issue 1: Duplicate ELECTION_RESULTS_PUBLISHED
grep -n "ELECTION_RESULTS_PUBLISHED" .env
# Expected: 1 occurrence, you have 2 → Delete line 93

# Issue 2: Typo - CONTROL_IP_ADDRESS
grep -n "CONTROL_IP_ADDRESS\|MAX_USE_IP_ADDRESS" .env
# Expected: Only MAX_USE_IP_ADDRESS → Delete CONTROL_IP_ADDRESS line

# Issue 3: MIX_* variables are outdated
grep -n "MIX_" .env
# Expected: Replace with VITE_* format
```

**Actions**:
```env
# Remove these (they're wrong):
# - Line 93: ELECTION_RESULTS_PUBLISHED=false (duplicate)
# - Line 108: CONTROL_IP_ADDRESS=1 (typo)
# - Lines 79-80: MIX_PUSHER_* (outdated)

# Add these (new Vite format):
VITE_APP_NAME="Public Digit"
VITE_API_BASE_URL="https://publicdigit.local/api"
VITE_SELECT_ALL_REQUIRED="yes"
```

**Backup first**:
```bash
cp .env .env.backup-phase4
```

### Task 3: Review Config Files (30 min)

**Critical files to verify**:
```bash
# 1. Session config (multi-tenancy)
cat config/session.php | grep "'driver'" | grep "'database'" && echo "✅ OK" || echo "❌ WRONG"

# 2. Database config (strict mode)
cat config/database.php | grep "'strict' => true" && echo "✅ OK" || echo "⚠️ Check"

# 3. Mail config
cat config/mail.php | grep "'mailer' => env" && echo "✅ OK"

# 4. Sanctum (API tokens)
cat config/sanctum.php | grep "stateful" && echo "✅ OK"
```

### Task 4: Run All Phase 3 Tests (60 min)
```bash
# Run tests (with database migrations)
php artisan migrate:fresh --seed
php artisan test --parallel --no-coverage

# Expected: 191+ tests passing ✅
# If failing: Check .env variables

# Generate coverage
php artisan test --coverage-html coverage/
# Open: coverage/index.html (target ≥ 85%)
```

### Task 5: Add CI/CD (20 min)
```bash
# Create GitHub Actions workflow
mkdir -p .github/workflows

# Add this file: .github/workflows/tests.yml
# (See PHASE4_CONFIGURATION_PLAN.md for content)

# Test locally first:
php artisan test --env=testing
```

### Task 6: Create .env Files (10 min)
```bash
# Create testing environment
cp .env .env.testing
# Edit: Change DB_CONNECTION=sqlite, SESSION_DRIVER=array, etc.

# Create production environment
cp .env .env.production
# Edit: Change APP_DEBUG=false, SESSION_SECURE_COOKIE=true, etc.
```

### Task 7: Verify Security Settings (15 min)
```bash
# CSRF protection
grep "'same_site'" config/session.php | grep "lax" && echo "✅ CSRF OK"

# Password hashing
grep "'rounds'" config/hashing.php && echo "✅ Hashing OK"

# Database foreign keys
grep "'mysql'" config/database.php | grep "'strict' => true" && echo "✅ FK OK"

# Sanctum stateful
cat config/sanctum.php | grep "stateful" && echo "✅ Sanctum OK"
```

### Task 8: Documentation (10 min)
```bash
# Create CONFIGURATION.md
touch docs/CONFIGURATION.md

# Document:
# - All environment variables
# - Production checklist
# - Security requirements
```

---

## Common Issues & Fixes

| Issue | Fix |
|-------|-----|
| Tests fail with "Table not found" | Run `php artisan migrate:fresh --seed` |
| `.env` duplicate warnings | Delete duplicate lines manually |
| "MIX_* variable not found" | Replace with VITE_* format |
| "Duplicate key error" | Check for duplicate environment variable names |
| Coverage < 85% | Review test results for skipped tests |

---

## Verification Checklist

Before moving to Phase 5, verify:

```bash
# 1. All tests pass
php artisan test --parallel

# 2. Coverage >= 85%
php artisan test --coverage-html coverage/ && open coverage/index.html

# 3. No .env issues
grep -E "^#\s*ELECTION|^#\s*CONTROL|^MIX_" .env || echo "✅ No deprecated variables"

# 4. Config files reviewed
ls config/*.php | wc -l  # Should be 26

# 5. Security verified
grep -E "strict|http_only|same_site|rounds" config/*.php | wc -l  # Should be > 10
```

---

## What Gets Done

✅ **Before Phase 4**: 191+ tests, 1 duplicate variable, 2 typos, outdated variables
✅ **After Phase 4**: 200+ tests, no duplicates, no typos, proper Vite variables, CI/CD ready

---

## Timeline

```
20 min: Fix .env
20 min: Verify bootstrap
30 min: Review configs
60 min: Run tests & coverage
20 min: Setup CI/CD
10 min: Create .env variants
15 min: Security checks
10 min: Documentation
─────────────────────────
~3.5 hours actual work (buffer to 6 hours)
```

---

## What You'll Have After Phase 4

✅ Clean .env file (no duplicates or typos)
✅ All 26 config files reviewed
✅ 200+ tests passing
✅ Coverage ≥ 85%
✅ GitHub Actions CI/CD configured
✅ Configuration documentation
✅ Ready for Phase 5 (Vite)

---

## Next Phase (Phase 5)

After Phase 4 succeeds:
```bash
# Phase 5: Vite Migration
npm install vite @vitejs/plugin-vue --save-dev
npm run build
# ... more frontend work
```

---

## Need Help?

1. **Full details**: See `PHASE4_CONFIGURATION_PLAN.md`
2. **Progress**: See `UPGRADE_PROGRESS_SUMMARY.md`
3. **Tests**: Run `php artisan test --parallel`
4. **Coverage**: Open `coverage/index.html`

---

## Start Now

```bash
# Step 1: Backup
cp .env .env.backup-phase4

# Step 2: Review issues
cat PHASE4_QUICK_START.md
cat PHASE4_CONFIGURATION_PLAN.md

# Step 3: Fix .env (Tasks 1-2)
# Remove duplicate ELECTION_RESULTS_PUBLISHED
# Remove typo CONTROL_IP_ADDRESS
# Update MIX_* to VITE_*

# Step 4: Run tests (Task 4)
php artisan migrate:fresh --seed
php artisan test --parallel

# Step 5: Success!
echo "✅ Phase 4 Complete!"
```

---

**Ready?** Start with Task 1 (Verify Bootstrap) now!

**Estimated Completion**: 3-6 hours from start

**Next**: Phase 5 (Vite Migration) - 8 hours
