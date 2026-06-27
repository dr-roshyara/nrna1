# Temporary Context Registry

**Phase:** DD.3b — Strategic DDD Discovery (P9 gate prerequisite)
**Date:** 2026-05-29
**Status:** Final — for P9 boundary review

## Purpose

Prevent temporary contexts from surviving beyond their intended lifetime. Every temporary context must have explicit creation reason, retirement condition, deletion phase, and success criteria.

---

## Registry

### Migration Context

| Field | Value |
|-------|-------|
| **Creation reason** | Sovereignty transition from procedural (middleware, helpers, services) to constitutional (PolicySequence, ConstitutionalLegitimacyDecision). Required for dual-reading during D.0-D.5 to detect divergence before permanent deletion. |
| **Retirement condition** | All procedural sovereignty paths deleted. Constitutional path is exclusive enforcement authority. |
| **Expected deletion phase** | D.5 |
| **Success criteria** | 1. Zero sovereignty divergence between constitutional and deleted procedural paths (D.1-D.2) |
|  | 2. Replay certification confirms deterministic evaluation after deletion (D.3) |
|  | 3. Sovereignty monotonicity verified (D.4) |
|  | 4. All D.0 gates passed (D.0.1-D.0.3) |
| **Types to retire** | `SovereigntyDivergenceRecord`, `ConstitutionalDivergenceType`, `DivergenceSeverity`, feature flags (`constitutional_mode`) |
| **Events to retire** | `SovereigntyBoundaryCrossed`, `ConstitutionalFallbackActivated`, `DivergenceObserved` |
| **Risk if not retired** | Divergence telemetry becomes permanent debt; drift baseline is never finalized; future engineers cannot distinguish between "active migration" and "abandoned telemetry" |
| **Hard deletion date** | End of M.1 phase (D.5 complete) — after this, Migration types are eligible for deletion without replacement |

---

### Dual Sovereignty Context

| Field | Value |
|-------|-------|
| **Creation reason** | Temporary concurrency of legacy (procedural) and constitutional enforcement during cutover. Required to prove equivalence before procedural deletion. |
| **Retirement condition** | Constitutional path proven equivalent to procedural path with zero divergence across all evaluation scenarios. |
| **Expected deletion phase** | D.2 |
| **Success criteria** | 1. D.0.1 shadow-mode telemetry shows zero IP-validation divergences over observation window |
|  | 2. 50/50 verification tests (ConstitutionalPrimaryTest) pass |
|  | 3. No sovereignty divergence events recorded during D.1 drift window |
|  | 4. `constitutional_mode` feature flag removed (always-on) |
| **Types to retire** | Dual-reading logic in `VoteController::store()`, shadow-mode code paths in `ValidateVotingIp` middleware |
| **Events to retire** | Dual-reading divergence events |
| **Risk if not retired** | Maintenance burden of parallel code paths; risk of divergence between dual paths after procedural code changes |
| **Hard deletion date** | End of D.2 — after this, dual-reading paths are eligible for deletion |

---

### Retirement Context

| Field | Value |
|-------|-------|
| **Creation reason** | Sequencing and orchestration of procedural sovereignty deletions. Ensures ordered, reversible retirement of legacy authority paths. |
| **Retirement condition** | All D.0 sequencing complete: middleware shadow (D.0.1), config flip (D.0.3a), middleware passive observer (D.0.3b), drift window (D.0.3c), replay certification (D.0.3d), final middleware retirement (D.0.3e). |
| **Expected deletion phase** | D.5 |
| **Success criteria** | 1. H.1-H.6 all remediated (hidden sovereignty paths) |
|  | 2. `ValidateVotingIp` middleware deleted or converted to non-authoritative logging |
|  | 3. `VotingSecurityService` sovereignty methods deleted |
|  | 4. `check_ip_address()` global scope removed |
|  | 5. All `voting_ip` cleartext references removed from projection |
|  | 6. No procedural authority path remains |
| **Types to retire** | Sequencing configuration, retirement feature flags, fallback mode switches |
| **Risk if not retired** | Retirement sequencing becomes permanent operational configuration; future engineers may re-enable procedural paths |
| **Hard deletion date** | End of D.5 (end of M.1) |

---

## Context Lifecycle Summary

| Context | Created In | Retired In | Lifetime |
|---------|-----------|------------|----------|
| Migration | D.0 | D.5 | ~1 phase |
| Dual Sovereignty | D.0 | D.2 | ~2 subphases |
| Retirement | D.0 | D.5 | ~1 phase |

All three are created in D.0 and must be fully retired by D.5.

---

## Lifespan Diagram

```
D.0 ───────────────────────────────────────────────────────── D.5
│                                                              │
├── D.0.1 (shadow mode)     ─── Retirement active              │
├── D.0.2 (certification)    ─── Migration active              │
├── D.0.3a (config flip)     ─── Dual Sovereignty active       │
├── D.0.3b (passive obs)     ─── Retirement active             │
├── D.0.3c (drift window)    ─── Dual Sovereignty active       │
├── D.0.3d (replay cert)     ─── Migration active              │
├── D.0.3e (final retire)    ─── Retirement active             │
├── D.1 (drift telemetry)    ─── Dual Sovereignty ends         │
├── D.2 (procedural delete)  ─── Dual Sovereignty deleted      │
├── D.3 (replay cert)        ─── Migration active              │
├── D.4 (monotonicity cert)  ─── Migration active              │
└── D.5 (M.1 complete)      ─── Migration + Retirement deleted │
```

---

## Deletion Checklist

Before deleting any temporary type:

- [ ] All consumers migrated to replacement
- [ ] Replacement has been production-verified
- [ ] Replay certification confirms deterministic behavior after deletion
- [ ] Divergence baseline records preserved (read-only archive, not active code)
- [ ] Feature flag removed (not just default-flipped)
- [ ] Configuration entries removed
- [ ] Documentation updated (references to deleted types removed)
- [ ] No active references in codebase (grep-confirmed)

---

## Enforcement

| Rule | Description |
|------|-------------|
| TCR-1 | Every temporary context must have a hard deletion date |
| TCR-2 | No temporary context may depend on another temporary context's types |
| TCR-3 | No permanent context may depend on a temporary context's types (blocks deletion) |
| TCR-4 | After hard deletion date, temporary types are eligible for deletion without replacement |
| TCR-5 | Any extension past hard deletion date requires Senior Architect approval |

---

## References

| Artifact | Section |
|----------|---------|
| ContextStabilityAssessment.md (P7) | Migration: EMERGING (Temporary) |
| BusinessInvariantCatalog.md (P7a) | Migration invariants (temporary) |
| Unretireable | Already live and permanent |
