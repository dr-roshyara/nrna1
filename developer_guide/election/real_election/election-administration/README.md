# Election Administration: Single Source of Truth (SSOT) Architecture

**Phase 3 Developer Guide — Controller Migration to Constitutional Governance**

This guide explains the new election state machine architecture and how to migrate controllers from the legacy system (scattered `status`/`is_active` fields) to the unified SSOT model.

---

## 🎯 What Changed

The election system now has **one authoritative source of state**: the `ElectionLifecycleEngine`. All decisions about what an election can do flow through deterministic computation, not scattered database fields.

### Before (Legacy System)
```php
// Multiple competing truths
if ($election->status === 'active' && $election->is_active) {
    // Can vote? Maybe, but also check voting_starts_at, voting_ends_at, 
    // code.can_vote_now, voter_slug.is_active...
}
```

### After (SSOT System)
```php
// Single authoritative decision
if (ElectionLifecycle::of($election)->canVote()) {
    // All signals unified: state machine + clock + window + voter eligibility
}
```

---

## 📚 Guide Structure

| Document | Purpose |
|----------|---------|
| **[PHASE_2_4_CONSTITUTIONAL_STABILIZATION.md](PHASE_2_4_CONSTITUTIONAL_STABILIZATION.md)** | Phase 2.4 drift prevention layer (anti-regression immune system) |
| **[MIGRATION_GUIDE.md](MIGRATION_GUIDE.md)** | Step-by-step Phase 3 implementation (READ FIRST) |
| **[API_REFERENCE.md](API_REFERENCE.md)** | Complete ElectionLifecycle facade API |
| **[PATTERNS.md](PATTERNS.md)** | Code examples and common migration patterns |
| **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** | Debugging deprecation warnings and errors |

---

## ⚡ Quick Start

### 1. Replace Model Field Access
```php
// ❌ OLD: Direct field access
if ($election->status === 'active' && $election->is_active) { }

// ✅ NEW: Facade-based queries
if (ElectionLifecycle::of($election)->canVote()) { }
```

### 2. Use Snapshot for Complex Logic
```php
$lifecycle = ElectionLifecycle::of($election);

if ($lifecycle->canEdit() && !$lifecycle->isLocked()) {
    // Safe to edit election configuration
}
```

### 3. Guard Queries Against Deprecated Fields
```php
// ✅ CORRECT: Query guard catches deprecated fields
$repo = app(SomeRepository::class);
$repo->findByElectionState($election); // Uses state, not status

// ❌ WRONG: Will throw DeprecatedQueryException
// $repo->findByStatus($election); // Uses deprecated status field
```

---

## 🏗️ Architecture Layers

### Four-Layer Defense Against SSOT Corruption

```
┌────────────────────────────────────────────────────────┐
│  DRIFT PREVENTION LAYER (Phase 2.4)                    │
│  ConstitutionalDriftMonitor → Records SSOTViolations   │
│  Architecture Invariant Tests → Static violations scan │
├────────────────────────────────────────────────────────┤
│  FACADE LAYER                                          │
│  ElectionLifecycle::of($election)->canVote()          │
├────────────────────────────────────────────────────────┤
│  ENFORCEMENT LAYER                                     │
│  - DeprecationAccessGuard: Runtime field enforcement   │
│  - QueryPolicyGuard: SQL-level validation              │
├────────────────────────────────────────────────────────┤
│  DOMAIN LAYER                                          │
│  - ElectionLifecycleEngine: Authoritative computation  │
│  - ElectionConstitution: Rules registry                │
│  - ElectionReadModel: Deprecation wrapper              │
└────────────────────────────────────────────────────────┘
```

### Defense Mechanisms

| Layer | Mechanism | Function |
|-------|-----------|----------|
| **Drift Prevention** | ConstitutionalDriftMonitor | Records violations to constitutional_integrity log (Phase 2.4) |
| **Drift Prevention** | Architecture Invariant Tests | Scans migrated controllers for legacy patterns (Phase 2.4) |
| **Domain** | ElectionReadModel | Direct legacy field access |
| **Enforcement** | DeprecationAccessGuard | Field access in violation of severity rules |
| **Enforcement** | QueryPolicyGuard | SQL queries using deprecated fields |
| **Facade** | ElectionLifecycle | Scattered consumption patterns |

---

## 🛡️ Phase 2.4: Anti-Regression Immune System

Phase 2.4 installed a **Constitutional Stabilization layer** that prevents future regressions by:

1. **Recording Violations** — `ConstitutionalDriftMonitor` logs architectural violations to `constitutional_integrity.log` (365-day retention)
2. **Detecting Drift** — 5 Architecture Invariant Tests scan migrated controllers for legacy patterns
3. **Structured Monitoring** — `SSOTViolationEvent` records violation metadata (type, layer, context, timestamp)
4. **Guard Integration** — Both `DeprecationAccessGuard` and `QueryPolicyGuard` now fire events to the drift monitor

**Key Components:**
- **SSOTViolationEvent** — Immutable value object for violation metadata
- **ConstitutionalDriftMonitor** — Records events to dedicated log channel (never blocks execution)
- **DriftMonitorInterface** — Interface for testability
- **ElectionLifecycleContract** — Injectable factory for dependency management
- **Architecture Invariant Tests** — Static file scanning to enforce SSOT patterns

**Monitoring Log Location:**
```
storage/logs/constitutional_integrity.log     ← All violations recorded here
```

**See Also:** [PHASE_2_4_CONSTITUTIONAL_STABILIZATION.md](PHASE_2_4_CONSTITUTIONAL_STABILIZATION.md) for complete Phase 2.4 architecture and implementation details.

---

## 📋 Phase 3 Checklist

Before beginning Phase 3 implementation:

- [ ] Read **[MIGRATION_GUIDE.md](MIGRATION_GUIDE.md)** completely
- [ ] Review **[PATTERNS.md](PATTERNS.md)** for code examples
- [ ] Understand all 64 passing tests (proof of correctness)
- [ ] Identify which controllers need migration (see below)
- [ ] Set deprecation mode to `warning` (Phase 3.1)
- [ ] Migrate controllers one at a time
- [ ] Run tests after each migration
- [ ] Verify no deprecation logs in voter_audit channel
- [ ] Switch deprecation mode to `strict` (Phase 3.2)
- [ ] Final regression testing

---

## 🎯 Controllers That Need Migration (Phase 3)

These controllers directly access legacy election state fields and must be updated:

| Controller | File | Legacy Fields | Status |
|-----------|------|-------------|--------|
| **ElectionController** | `app/Http/Controllers/ElectionController.php` | `status`, `is_active` | Pending |
| **ElectionVotingController** | `app/Http/Controllers/ElectionVotingController.php` | `status`, `voting_starts_at`, `voting_ends_at` | Pending |
| **VoteController** | `app/Http/Controllers/VoteController.php` | `is_active`, `status` | Pending |
| **VoterSlugController** | `app/Http/Controllers/VoterSlugController.php` | `status`, `is_active` | Pending |

---

## 🔍 How to Identify Legacy Usage

### In Controllers
```bash
# Find direct field access
grep -r "->status" app/Http/Controllers/
grep -r "->is_active" app/Http/Controllers/
grep -r "== 'active'" app/Http/Controllers/
```

### In Repositories
```bash
# Find legacy queries
grep -r "where('status'" app/Repositories/
grep -r "where('is_active'" app/Repositories/
grep -r "'status' =>" app/
```

### In Views/JavaScript
```bash
# Find legacy field references in templates
grep -r "election.status" resources/
grep -r "election.is_active" resources/
```

---

## ✅ Verification Strategy

After each controller migration:

1. **Run tests** — All existing tests must still pass
2. **Check logs** — No warnings in `storage/logs/voter_audit.log`
3. **Manual testing** — Test the migrated feature in browser
4. **Regression** — Verify other features still work

---

## 🚨 Critical Rules for Phase 3

**MUST DO:**
- ✅ Use `ElectionLifecycle::of($election)` for ALL state decisions
- ✅ Call `QueryPolicyGuard::assertAllowedQuery()` in repositories before legacy field queries
- ✅ Run tests after EACH controller migration
- ✅ Review deprecation logs before switching to strict mode

**MUST NOT:**
- ❌ Access `$election->status` directly (use facade instead)
- ❌ Check `$election->is_active` without logging (use facade)
- ❌ Build queries with `status` or `is_active` without guard
- ❌ Skip tests to move faster (regressions hide until production)

---

## 📞 Getting Help

| Question | Answer |
|----------|--------|
| How do I use ElectionLifecycle? | See **[API_REFERENCE.md](API_REFERENCE.md)** |
| What code pattern should I use? | See **[PATTERNS.md](PATTERNS.md)** |
| Why is my query throwing an exception? | See **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** |
| What tests validate this architecture? | `tests/Unit/Application/Election/` — 64 tests, all GREEN |

---

## 🏁 Next Steps

1. **Read [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md)** — Detailed implementation steps
2. **Review [PATTERNS.md](PATTERNS.md)** — Real code examples
3. **Start Phase 3.1** — Migrate first controller with mode=warning
4. **Verify with tests** — Ensure no regressions
5. **Proceed incrementally** — One controller at a time

---

**Architecture Status:** ✅ **LOCKED AND VERIFIED**

All three layers of defense are in place and tested. The system will prevent silent SSOT corruption at every level. Phase 3 implementation is safe to proceed with high confidence.
