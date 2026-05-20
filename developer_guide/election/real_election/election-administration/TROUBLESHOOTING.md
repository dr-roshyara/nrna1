# Phase 3.2-3.3: Troubleshooting Guide

**Date:** 2026-05-20  
**Phase:** 3.2-3.3  
**Status:** Complete and verified  
**Audience:** Operators, debugging engineers, support team

---

## 🆘 Quick Diagnosis Tree

```
Something is wrong with election state?

├─ "Management page shows wrong state"
│  └─ → Go to: DIVERGENCE ISSUES (Section A)
│
├─ "Can't submit election for approval"
│  └─ → Go to: TRANSITION BLOCKED (Section B)
│
├─ "Got UnauthorizedStateMutationException"
│  └─ → Go to: AUTHORIZATION ISSUES (Section C)
│
├─ "State won't change even after action"
│  └─ → Go to: STATE NOT CHANGING (Section D)
│
├─ "Metrics showing violations"
│  └─ → Go to: VIOLATIONS DETECTED (Section E)
│
└─ "Need to manually fix election state"
   └─ → Go to: MANUAL REPAIR (Section F)
```

---

## 🔍 Section A: Divergence Issues

### Problem: Management Page Shows "Unknown State"

**Symptoms:**
```
GET /elections/{slug}/management → HTTP 403
Error: "Unknown state: import_voters"
```

**Root Cause:** Database state column diverged from SSOT engine computation.

**Diagnosis:**

```bash
# Check what's in database vs what engine computes
php artisan tinker

>>> $e = Election::where('slug', 'test-election')->first();
>>> echo "Column state: " . $e->state;
=> "import_voters"

>>> $engine = app(\App\Application\Election\Services\ElectionLifecycleEngineImpl::class);
>>> $computed = $engine->getState($e);
>>> echo "Engine state: " . $computed->value;
=> "draft"

# They don't match - divergence detected!
```

**Fix:**

```bash
# Option 1: Audit divergences
php artisan app:backfill-election-state --audit-only
# Output shows all divergences, doesn't fix

# Option 2: Automatically repair all divergences
php artisan app:backfill-election-state
# Updates all divergent elections' state columns to match engine
```

**Why It Happened:**

1. Code directly set `$election->state = 'import_voters'` (invalid state)
2. No validation or authorization gate
3. SSOT engine correctly computed a different state from facts

**Prevention:**
- Always use `ElectionLifecycle::of($election)->transitionVia('action')`
- Never directly set state column: `$election->state = ...` (❌ wrong)
- Use ElectionStateWriteContext for any necessary state mutations

---

## 🎯 Summary

**Troubleshooting approach:**
1. ✅ Use diagnosis tree to identify issue type
2. ✅ Check relevant section in this guide
3. ✅ Follow diagnostic steps to find root cause
4. ✅ Apply recommended fix
5. ✅ Run verification checklist

**Key tools:**
- `php artisan app:backfill-election-state` - Fix divergences
- `php artisan election:constitution:health` - Check system health
- `php artisan tinker` - Interactive debugging
- `tail -f storage/logs/constitutional_integrity.log` - View audit trail

---

## 📚 Additional Resources

- **Architecture documentation:** See `.claude/UI_GUIDELINES.md`
- **Project overview:** See project CLAUDE.md
- **Test examples:** See `tests/Feature/Election/` and `tests/Unit/Application/Election/`
- **Health checks:** See `app/Console/Commands/` for available artisan commands
