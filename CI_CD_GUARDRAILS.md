# CI/CD Architecture Guardrails

**Version:** 1.0  
**Status:** ACTIVE (enforces Phase D + E architecture)  
**Last Updated:** 2026-05-14

---

## 🎯 Purpose

This CI/CD pipeline **transforms architectural rules from suggestions into machine-enforced law**. Every push and PR is validated against:

* Application layer purity (no DB access)
* Controller purity (no domain logic or value object construction)
* Phase C authority (no duplicate eligibility logic)
* Tenant boundary enforcement (organisation scoping)
* Test suite stability (72/72 required)

---

## 🔐 The Seven Gates

### GATE 1: DB Access Enforcement

**Rule:** No `DB::table()` in Application layer

```
app/Contexts/*/Application/
```

**Forbidden:**
```php
DB::table('committees')
->selectRaw(...)
```

**Required:**
```php
$this->committeeRepository->findById($id)
```

**Why:** Keeps business logic separate from infrastructure concerns

---

### GATE 2: Controller Purity

**Rule:** Controllers cannot construct value objects

**Forbidden:**
```php
$geoPath = GeoPathChain::fromString($request->get('geo_path'));
```

**Required:**
```php
$geoPath = $this->geoPathProvider->resolveForMember($memberId, $tenantId);
```

**Why:** Prevents controller-level domain logic coupling

---

### GATE 3: Phase C Authority

**Rule:** `EligibleCommitteeQueryService` is the ONLY eligibility source

**Forbidden:**
```php
if ($this->eligibilityPolicy->isEligible(...)) { ... }
```

**Required:**
```php
$eligible = $this->eligibleCommitteeService->eligibleForMember(...);
```

**Why:** Guarantees read + write path consistency

---

### GATE 4: Tenant Scoping

**Rule:** All endpoints must be organisation-scoped

**Forbidden:**
```
GET /api/members/{id}/committees
```

**Required:**
```
GET /organisations/{tenantId}/my-committees
```

**Why:** Enforces multi-tenant boundary isolation

---

### GATE 5: Constitutional Tests

**Rule:** 72/72 tests must pass

```bash
php artisan test tests/Unit/Constitutional/Membership/
```

**Required Target:**
```
72 passed (4 incomplete)
```

**Why:** Baseline stability guarantee — any regression blocks merge

---

### GATE 6: Phase C Query Service

**Rule:** Phase C service tests must pass (15/15)

```bash
php artisan test tests/Unit/Constitutional/Membership/Query/EligibleCommitteeQueryServiceTest.php
```

**Required Target:**
```
15 passed
```

**Why:** Ensures eligibility engine correctness

---

### GATE 7: Feature Tests (Soft Gate)

**Rule:** Feature tests should pass (but not blocking)

```bash
php artisan test tests/Feature/
```

**Status:** Warning if fails, does not block merge

---

## 🚨 When a Gate Fails

### Your code was rejected because:

If the build fails at a specific gate, look at the error message:

```
❌ FAIL: DB::table found in Application layer
   Application layer must use repositories, not raw queries
```

### To fix:

1. **Identify the violation** from the error message
2. **Locate the file** mentioned in the output
3. **Apply the pattern** from this document
4. **Push again** — the check will rerun

### Rollback option:

If the violation is from a merge conflict or bad merge:

```bash
git reset --hard E-DONE
```

This reverts to the last known-good stable state (Phase E completion).

---

## 📋 Cheat Sheet — Quick Fixes

### Error: "DB::table found in Application layer"

**Fix:** Use repository instead

```php
// ❌ WRONG
$data = DB::table('committees')->where(...)->get();

// ✅ RIGHT
$committee = $this->committeeRepository->findById($id);
```

---

### Error: "GeoPathChain constructed in controller"

**Fix:** Use port for resolution

```php
// ❌ WRONG
$geoPath = GeoPathChain::fromString($request->get('geo_path'));

// ✅ RIGHT
$geoPath = $this->geoPathProvider->resolveForMember($memberId, $tenantId);
```

---

### Error: "Member-scoped endpoint detected"

**Fix:** Scope to organisation

```php
// ❌ WRONG
Route::get('/api/members/{id}/committees', ...);

// ✅ RIGHT
Route::get('/api/organisations/{tenantId}/my-committees', ...);
```

---

### Error: "72 tests not passing"

**Fix:** Run locally and diagnose

```bash
php artisan test tests/Unit/Constitutional/Membership/ --no-coverage
```

Look for:
* New test failures (code change broke logic)
* Missing tests (accidental deletion)
* Incomplete tests marked (incomplete count > 4)

---

## 🔄 The Two CI Workflows

### 1. `membership-architecture.yml` (On Every Push + PR)

**Runs:** Every commit  
**Time:** ~2 minutes  
**Behavior:** BLOCKING (PR cannot merge if fails)

Gates:
1. DB access enforcement
2. Controller purity
3. Phase C authority
4. Tenant scoping
5. Constitutional tests (72/72)
6. Phase C tests (15/15)
7. Feature tests (soft gate)

---

### 2. `regression-detector.yml` (Daily + Main branch)

**Runs:** Daily at 2 AM UTC + every main/postgressql push  
**Time:** ~3 minutes  
**Behavior:** INFORMATIONAL (shows status, generates report)

Checks:
* Test count stability
* Architecture drift
* Phase C authority

---

## 🧠 Why This Matters

### Before CI/CD

Architecture was a **social contract**:
* "We agreed not to use DB::table"
* "We agreed Phase C is the source of truth"
* But violations only appeared during code review

### After CI/CD

Architecture is a **machine law**:
* Violations are **impossible to merge**
* Violations are **caught immediately**
* Team discipline becomes **system guarantee**

---

## 📊 Expected Pipeline Output

### Success:

```
✅ GATE 1: No DB access in Application layer
✅ GATE 2: No value object construction in controllers
✅ GATE 3: Phase C is sole eligibility authority
✅ GATE 4: Tenant scoping enforcement
✅ GATE 5: Constitutional Membership Tests (72/72 required)
✅ GATE 6: Phase C Query Service Tests
✅ GATE 7: Feature Tests (optional)

BUILD: SAFE FOR MERGE
```

### Failure (example):

```
❌ FAIL: DB::table found in Application layer
   Application layer must use repositories, not raw queries

BUILD: BLOCKING — Fix required before merge
```

---

## 🛠️ For New Team Members

1. **Read this document** (you are here ✓)
2. **Understand the gates** — they exist for architectural integrity
3. **When you hit a gate failure:**
   - Look at the error message
   - Apply the fix from the "Cheat Sheet" section
   - Push again
4. **If stuck:** Check the "Forbidden vs Required" patterns above

---

## 🚀 Future Enhancements

This system can be extended with:

* **PR comments** — automatic suggestions on violations
* **Performance gates** — watch for query regressions
* **Coverage gates** — maintain test coverage thresholds
* **Auto-rollback** — revert to last stable on failure (advanced)

---

## 📞 Support

**If a gate blocks your merge and you're confused:**

1. Read the error message carefully
2. Find your pattern in the "Cheat Sheet"
3. Ask: "Is my code following the rule?"
4. If rule seems wrong: create issue with `@architecture` tag

---

**Remember:** These gates protect the system you just built. They are not obstacles — they are **guardrails that keep your architecture clean as the team grows**.

---

*Last Updated: 2026-05-14 | Enforces: Phase D + E Architecture*
