---
name: phase3_consolidation_strategy
description: Phase 3 is runtime purification (NOT expansion). Stabilize → purify → formalize → federate.
metadata: 
  node_type: memory
  type: project
  originSessionId: 94035d89-d58a-4291-be69-415431680e24
---

# Phase 3+ Strategic Roadmap: Constitutional Governance Runtime

**Principle:** Architectural evolution must follow the sequence:
```
stabilize → purify → formalize → federate
```

NOT: stabilize → immediately expand

---

## Phase 2 (✅ Complete)

**Achievement:** Established constitutional snapshot sovereignty

- Election owns voter-source authority (snapshot at creation)
- Authority is immutable after snapshot
- Participation freezes at SetupAdministration
- ElectionMode::fromElection() reads snapshot first
- Architecture invariant tests protect sovereignty

---

## Phase 3 (Next) — CONSOLIDATION & PURIFICATION

**Goal:** Eliminate transitional ambiguity. Make snapshot authority sole runtime source.

### Priority 1: Snapshot Backfill ⭐ CRITICAL

**Task:** Backfill all existing elections with voter_source_strategy values

**Why critical:** Right now `ElectionMode::fromElection()` still has:
```php
if ($election->voter_source_strategy !== null) {
    return self::from($election->voter_source_strategy);
}
// @deprecated Phase 2 compatibility: fallback to org
return self::fromOrganisation($election->organisation);
```

The fallback means sovereignty is still **transitional**.

**Success criteria:**
- All elections have non-null voter_source_strategy
- Fallback path becomes unreachable
- Tests verify fallback is unnecessary

### Priority 2: Remove Legacy Fallback

After verified backfill:
- Delete `ElectionMode::fromOrganisation()` fallback branch
- Make `voter_source_strategy` NOT NULL in database
- Enforce: election snapshot is SOLE authority

**Why:** Removes dual-runtime ambiguity that weakens governance

### Priority 3: Vocabulary Convergence

Gradually shift from anti-corruption vocabulary to domain vocabulary:

| Current | Target | Timeline |
|---------|--------|----------|
| ElectionMode | VoterSourceStrategy | Phase 3 cleanup |
| uses_full_membership | removed from election contexts | Phase 3 |
| org.uses_full_membership | org governance default only | Phase 3 |

**Why:** Current names no longer represent true domain. Renaming clarifies authority.

### Priority 4: Runtime Audit & Invariant Hardening

Encode architectural rules as enforced invariants:

```
✅ No election runtime may read org.uses_full_membership
✅ No projection may derive authority from org settings
✅ No controller may bypass snapshot resolution
✅ No lifecycle logic may duplicate participation semantics
```

These should be architecture tests, not just conventions.

### Priority 5: Overlay Runtime Formalization (Lightweight)

NOT full federation yet. Just document:
- Suspension authority (orthogonal to election state)
- Emergency governance behavior
- Whether overlays preserve freeze state
- Clear vocabulary around "operational overlay" vs "constitutional state"

---

## Phase 4 (Future) — PARTICIPATION AUTHORITY MODELING

**Goal:** Formalize the `Constitutional Participation Authority` bounded context

Do NOT start until Phase 3 is complete.

**Topics:**
- Voter authority value objects
- Hybrid participation semantics
- Delegation authority
- Participation governance rules
- Capability coupling

**Why wait:**
- Phase 3 must stabilize and purify first
- Vocabulary must converge
- Fallback paths must be gone
- Authority model must be clear before extending it

---

## Phase 5 (Later) — FEDERATION & EXTERNAL REGISTRIES

**Goal:** Constitutional runtime across organization boundaries

**Topics:**
- External voter authorities
- Federated identity
- Delegated participation authority
- Distributed governance resolution
- Cross-tenant election participation

**Why this is Phase 5, not Phase 3:**
- Requires Phase 3 purity as foundation
- Requires Phase 4 authority model as language
- Premature federation would introduce duplication
- Would destabilize snapshot sovereignty if attempted too early

---

## Why This Sequence Is Critical

Attempting Phase 4/5 before Phase 3 would cause:
- Reintroduced fragmented authority (bad)
- Vocabulary instability (bad)
- Dual-runtime semantics (bad)
- Premature abstraction pressure (bad)
- Constitutional runtime collapse risk (very bad)

Current state:
- Sovereignty just established
- Fallback still exists (transitional)
- Vocabulary still mixed (org boolean vs election authority)
- Invariants documented but not enforced

Phase 3 must **complete the foundation** before expansion.

---

## Phase 3 Success Metrics

| Metric | Current | Phase 3 Target |
|--------|---------|-----------------|
| Elections with snapshot | ~0% (only new) | 100% |
| Fallback path usage | Required | Dead code (removable) |
| Vocabulary consistency | Mixed | Unified |
| Architecture tests | Good | Comprehensive |
| Overlay semantics | Implicit | Explicit |

---

## Most Important Rule

```
DO NOT federate before purifying.
DO NOT expand before stabilizing.
DO NOT formalize before converging.
```

This discipline prevents governance-runtime instability.
