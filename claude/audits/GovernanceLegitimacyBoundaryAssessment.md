# Governance-Legitimacy Boundary Assessment

**Phase:** DD.3b — Strategic DDD Discovery (P4)
**Date:** 2026-05-29
**Prerequisite:** AuthorityOwnershipMatrix.md, ConstitutionalContextMap.md
**Status:** Initial — open for Senior Architect review

## Purpose

Split **Governance** from **Legitimacy**. These concepts are frequently conflated but answer fundamentally different questions. This assessment determines whether Governance is a subdomain of Legitimacy, a peer context, or a generic domain best handled by Laravel conventions.

---

## The Core Distinction

| Aspect | Legitimacy | Governance |
|--------|-----------|------------|
| **Question** | Is participation legitimate? | What action should the organization take? |
| **Nature** | Constitutional — derives authority | Administrative — enforces rules |
| **Output** | `LegitimacyOutcome` (Allowed, Denied, Deferred, Investigate) | Enforcement actions (block, allow, review, log) |
| **Source of truth** | Constitutional evidence + policy sequence | Election configuration, lifecycle state, organizational rules |
| **Change driver** | Security policy changes, new evidence types | Election lifecycle, administrative config |
| **Replay scope** | Must be replay-deterministic | Not replay-scoped |
| **Current location** | `ConstitutionalLegitimacyDecision` + `LegitimacyOutcome` | Controllers, middleware, Election model, Spatie policies |

---

## Where They Overlap

In the current codebase, Legitimacy and Governance are **conflated**:

1. **Controllers** enforce both simultaneously — `canVote()` checks membership (Governance) while constitutional evaluation checks legitimacy
2. **Middleware** (`ValidateVotingIp`) performs governance enforcement (blocking) based on what should be a legitimacy concern
3. **Election model** lifecycle methods (is_active, dates) are governance concerns but are checked alongside legitimacy
4. **Feature flags** (`constitutional_mode`) are governance mechanisms controlling which legitimacy path is used

This conflation is the source of the H.1-H.6 hidden sovereignty paths identified in C.5b.

---

## Where They Diverge

| Scenario | Legitimacy says | Governance says |
|----------|----------------|-----------------|
| Voter trusted, election active | Allowed | Proceed to ballot |
| Voter trusted, election closed | Allowed | BLOCK — election is closed |
| Voter denied, election active | Denied | Show denial — but election IS active |
| Voter deferred, manual review | Deferred | Queue for review |
| Admin override exists | (not applicable — legitimacy is constitutional, not administrative) | May override based on organizational rules |

**Key insight:** Governance can BLOCK what Legitimacy ALLOWS (e.g., closed election). But Governance must NEVER ALLOW what Legitimacy DENIES (that would be a constitutional violation). Governance is a secondary gate, not an override authority.

---

## Governance Sub-Concepts

### Election Lifecycle Governance
- Election active/inactive dates
- Voting window enforcement
- Phase transitions (setup → active → closed → results)

### Participation Governance
- Voter roll management
- Membership verification
- Code/voter-slug assignment

### Enforcement Governance
- Action on legitimacy outcome (block, allow, queue)
- Shadow vs primary mode (D.0 transition)
- Divergence telemetry routing

### Administrative Governance
- Admin override capabilities (must be constrained)
- Manual review workflows
- Dashboard access control

---

## Assessment: Governance as Generic Domain

### Option A: Governance as Subdomain of Legitimacy

**Argument:** Governance enforces what Legitimacy decides — they are the same concern.

**Counter-argument:** Governance includes concerns that have nothing to do with legitimacy (election lifecycle, voter rolls, admin workflows). Co-locating these with `ConstitutionalLegitimacyDecision` would bloat the Core Domain with administrative concerns.

**Verdict:** Rejected — Governance is too broad to be a subdomain of Legitimacy.

### Option B: Governance as Independent Bounded Context

**Argument:** Governance is a distinct concern worthy of its own formal BC.

**Counter-argument:** Governance in this system is primarily convention-based (Laravel policies, middleware, model lifecycle). Formalizing it as a DDD bounded context would over-engineer what is essentially administrative logic. There are no complex invariants requiring aggregate protection.

**Verdict:** Over-engineered for current system maturity.

### Option C: Governance as Generic Domain (Laravel Conventions) ✅ **RECOMMENDED**

**Argument:** Governance concerns (election lifecycle, voter rolls, enforcement actions) are well-handled by Laravel's existing patterns — middleware, FormRequest authorization, Spatie policies, model lifecycle events. These do not require DDD bounded-context formalization.

**Rationale:**
- Governance is classified as **Generic Domain** in the Core Domain Identification
- It has **no complex invariants** requiring aggregate boundaries
- The constitutional critical parts (legitimacy enforcement) are already in Legitimacy Context
- The remaining governance concerns are standard CRUD + middleware
- Over-engineering Governance would pull attention from Legitimacy (Core Domain)

**What this means:**
- Governance remains in Laravel conventions (Controllers, Middleware, Models)
- No formal Governance bounded context
- No Governance aggregate or repository
- Governance must never re-derive legitimacy (trust the outcome it receives)
- Governance must never ALLOW what legitimacy DENIES (constitutional constraint)

---

## Boundary Rules

| Rule | Description | Enforcement |
|------|-------------|-------------|
| G1 | Governance must trust Legitimacy outcomes | Convention (integration test) |
| G2 | Governance must never re-derive LegitimacyOutcome | F4/F10 fitness functions |
| G3 | Governance may block what Legitimacy allows (election closed) | Convention |
| G4 | Governance must never allow what Legitimacy denies | Constitutional invariant (F4/F5) |
| G5 | Governance enforcement actions are NOT replay-scoped | Convention |
| G6 | Shadow/primary mode switch is a Governance concern | Config flag |
| G7 | Admin overrides must be logged as constitutional events | Convention |

---

## Current Governance Code Locations (not moving)

These remain in their current locations under Laravel conventions:

```
app/Models/Election.php                  — lifecycle state
app/Http/Middleware/                     — request enforcement
app/Http/Middleware/ValidateVotingIp.php — conditional enforcement (D.0)
app/Services/VotingSecurityService.php   — legacy (D.6 retirement)
app/Policies/                            — Spatie authorization
routes/                                  — route enforcement
config/voting_security.php              — mode switching
```

---

## Summary

| Question | Answer |
|----------|--------|
| Is Governance part of Legitimacy? | No — they answer different questions |
| Is Governance an independent BC? | No — over-engineered for current maturity |
| **What is Governance?** | **Generic Domain — Laravel conventions** |
| What is the critical constraint? | Governance must never ALLOW what Legitimacy DENIES |
| What is the enforcement mechanism? | F4/F10 fitness functions + G1-G7 conventions |
