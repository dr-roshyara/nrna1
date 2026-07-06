---
name: phase3_fatigue_risk
description: Critical discipline warning - Phase 3 fatigue will reintroduce sovereignty ambiguity if purification is skipped
metadata: 
  node_type: memory
  type: feedback
  originSessionId: 94035d89-d58a-4291-be69-415431680e24
---

# ⚠️ CRITICAL: Phase 3 Fatigue Risk

## The Most Important Future Danger

After Phase 2 stabilizes constitutional snapshot sovereignty, the biggest architectural risk in Phase 3 is:

```
Pressure to "move faster" will lead to skipping purification.
```

This will manifest as:
- Expanding before fallback path removal
- Tolerating mixed runtime authority indefinitely
- Rushing into Phase 4 (participation authority) before Phase 3 complete
- Vocabulary fragmentation (ElectionMode vs eventual VoterSourceStrategy)
- Invariants documented but not enforced

**Result if this happens:**
- Sovereignty ambiguity returns
- Runtime drift reappears
- Governance inconsistency permanently bakes in
- Constitutional-runtime integrity collapses

---

## What Phase 3 Fatigue Looks Like

| Red Flag | Real Risk |
|----------|-----------|
| "Can we just expand federation now?" | YES - will destabilize just-stabilized authority |
| "Backfill can wait for Phase 4" | YES - fallback will stay indefinitely |
| "Let's skip vocabulary cleanup" | YES - mixed terms become permanent |
| "These invariant tests are nice-to-have" | NO - they're governance contracts |
| "We can tolerate org boolean in election context" | YES - sovereignty will fragment |

---

## Why Phase 3 Discipline Is Non-Negotiable

Phase 2 achieved:
- ✅ Constitutional snapshot sovereignty
- ✅ Participation freeze semantics
- ✅ Snapshot immutability

BUT it still has:
- ⚠️ Fallback paths (transitional)
- ⚠️ Mixed vocabulary (org boolean + election authority)
- ⚠️ Invariants documented, not enforced
- ⚠️ Anti-corruption layer still active

**Phase 3 MUST complete this transition.** If it doesn't:
- Fallback becomes permanent
- Vocabulary becomes fragmented
- Invariants become aspirational
- And sovereignty becomes ambiguous forever

---

## The Discipline Required

**Phase 3 priorities are non-negotiable in order:**

1. **Snapshot Backfill** — ALL elections must have non-null voter_source_strategy
2. **Remove Fallback** — Delete ElectionMode::fromOrganisation() fallback entirely
3. **Vocabulary Convergence** — Complete transition to domain terms
4. **Invariant Hardening** — Encode rules as tests, not conventions
5. **Overlay Formalization** — Clarify suspension/emergency semantics

**Only after ALL five are complete** can Phase 4 begin.

---

## How to Prevent Phase 3 Fatigue

### Before Phase 3 starts:
- Review this memory file
- Reread the Phase 3 strategy roadmap
- Confirm all stakeholders understand: **expansion is AFTER purification**

### During Phase 3:
- Track backfill progress relentlessly
- Verify fallback path is truly removed (not just "unused")
- Test vocabulary convergence rigorously
- Enforce invariants in CI, not just documentation

### If pressure arises to skip steps:
- Return to this discipline record
- Remember: Phase 2 took discipline to avoid ParticipationRuntime
- Remember: Phase 3 discipline is even MORE critical
- The architecture is only as strong as its purification

---

## The Professional Principle

```
A governance-runtime system that skips purification
will eventually collapse under its own ambiguity.
```

This is not theoretical. This is what happens to:
- legacy voting systems,
- financial governance platforms,
- constitutional runtime systems,
- that tolerated mixed authority indefinitely.

---

## Why This Matters

The system has now established:
- ✅ Constitutional authority snapshots
- ✅ Participation freezing semantics
- ✅ Sovereignty preservation

But it has NOT yet:
- Completed the transition
- Removed transitional artifacts
- Hardened against regression
- Made governance contracts enforcement-safe

**Phase 3 is the work that makes Phase 2 permanent.**

Skip Phase 3 discipline, and Phase 2 becomes fragile.

---

## When Phase 3 is Done Correctly

After Phase 3 complete:
- ✅ All elections have snapshots (no fallback needed)
- ✅ Fallback path is deleted (irreversible)
- ✅ Vocabulary is unified (no mixed terms)
- ✅ Invariants are enforced (architecture contracts)
- ✅ Overlay semantics are explicit

**THEN** the architecture can safely expand.

NOT before.

---

## Critical Reminder

If you read nothing else: **DO NOT EXPAND BEFORE PURIFYING.**

Phase 3 fatigue will try to convince you to:
- Add federation features,
- Support delegation,
- Integrate external registries,
- Before the foundation is solid.

**This is exactly when everything breaks.**

The discipline to purify first is what separates professional governance-runtime systems from systems that eventually collapse.

Maintain it.
