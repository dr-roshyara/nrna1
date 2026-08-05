---
name: phase1_constitutional_parity_strategy
description: "Constitutional Parity Verification strategy — 10 Critical Paths first, then full matrix. Sovereignty preservation through typed semantic comparison."
metadata: 
  node_type: memory
  type: project
  originSessionId: 9059105f-c80d-49a0-8438-aee9dca2bed8
---

# Phase 1: Constitutional Parity Verification Strategy

**Status:** Architecture Review Complete — Ready for Critical Paths Implementation  
**Date:** 2026-05-26  
**Architectural Level:** Constitutional Governance Systems Engineering

---

## Core Architectural Achievement

We have successfully transitioned from:
- **Application Architecture** (controller behavior, HTTP responses, feature implementation)
- **DDD Architecture** (domain models, semantic types, aggregate boundaries)

To:

- **Constitutional Governance Architecture** (sovereign equivalence, replay-safe migration, judicial jurisprudence)

This is a critical threshold crossing. We are no longer validating "what the code does." We are validating **"what constitutional authority the code derives."**

---

## Five Corrected Domain Objects

### 1. CapabilityParitySnapshot — Typed Constitutional Semantics

```php
readonly class CapabilityParitySnapshot
{
    public function __construct(
        public ?TrustLevel $networkLegitimate,              // Network evidence confirms legitimacy
        public ?TrustLevel $deviceLegitimate,               // Device evidence confirms legitimacy
        public ?TrustLevel $verificationLegitimate,         // Attestation confirms legitimacy
        public ?TrustLevel $trustLegitimate,                // Composite constitutional trust
        public ?string $overlayInfluence,                   // OverlayInfluence enum (signal, not authority)
        public ?string $authorizationProtocol,              // BallotAuthorizationProtocol enum
        public ElectionLifecycleState $lifecycleState,      // Constitutional state at decision
        public string $participationAllowed,                // 'allowed' | CapabilityDenialReason value
    ) {}

    // CRITICAL: Compare ALL 8 fields for semantic equivalence
    public function equals(self $other): bool
    {
        return $this->networkLegitimate === $other->networkLegitimate
            && $this->deviceLegitimate === $other->deviceLegitimate
            && $this->verificationLegitimate === $other->verificationLegitimate
            && $this->trustLegitimate === $other->trustLegitimate
            && $this->overlayInfluence === $other->overlayInfluence
            && $this->authorizationProtocol === $other->authorizationProtocol
            && $this->lifecycleState === $other->lifecycleState
            && $this->participationAllowed === $other->participationAllowed;
    }
}
```

**Key Distinction:**
- `trustLegitimate` = Constitutional trust infrastructure confirms legitimacy (NOT final authority)
- `participationAllowed` = Final sovereign decision (can this voter participate?)
- These MUST be separate to preserve resolver supremacy

### 2. ConstitutionalDivergenceType — Typed Classification

18 enum cases covering all constitutional domains, each with:
- `.severity()` → Critical|High|Medium|Low|Unknown
- `.article()` → Constitutional Article reference (for dispute replay)

Example cases:
- `NetworkBindingThresholdDifference` — MEDIUM severity, "Election Constitution: Network Binding Strategy"
- `OverlayPriorityOrdering` — CRITICAL severity, "Overlay Governance: Priority Ordering"
- `TrustCompositionLogic` — CRITICAL severity, "Constitutional Trust: Composition from Evidence"

### 3. ConstitutionalDivergenceLedger — Governance Migration Jurisprudence

Immutable record with full provenance:
- `divergenceType` (typed enum)
- `severity` (typed enum)
- `constitutionalArticle` (from type.article())
- `legacyBehavior` / `resolverBehavior` (actual extracted behavior)
- `resolverDecision` (what resolver chose)
- `approvedBy` (governance authority — 'system_architect', 'board_chairman', etc.)
- `rationale` (why this divergence is acceptable)

This evolves the ledger from **debugging artifact** to **constitutional migration jurisprudence** — essential for future amendments, federation, dispute resolution.

### 4. Extraction Methods — Actual Runtime Behavior (NOT Reimplementation)

**CRITICAL RULE:** Do NOT reconstruct legacy logic.

- `getLegacyResult()` — Calls ACTUAL legacy code paths. Captures real sovereign behavior exactly as runtime executes.
- `getResolverResult()` — Runs TrustPolicyEvaluator + ElectionCapabilityResolver. Extracts constitutional semantics from resolver.

Both return `CapabilityParitySnapshot` with identical 8 fields for comparison.

---

## Execution Strategy: 10 Critical Paths FIRST

**IMPORTANT:** Avoid combinatorial explosion blindness.

Not all 432 constitutional state combinations are equally dangerous. Implement **10 Critical Paths first** to expose highest-risk sovereignty intersections.

### P0 — Resolver Sovereignty (Must Pass)

1. **Happy Path** — All legitimate, no overlay, voting active, single-code
   - Expected: `participationAllowed = 'allowed'`
   - Constitutional semantics: All legitimacy fields green, no overlay influence, lifecycle permits

2. **Overlay Does NOT Grant Authority** — Overlay review + elevated trust + lifecycle active
   - Expected: `participationAllowed = 'constitutional_review_pending'` (denial from TrustCapabilityPolicy overlay interpretation)
   - Constitutional semantics: TrustCapabilityPolicy interprets overlay signal, resolver enforces denial

3. **Lifecycle Denial Overrides Trust** — Trust fully legitimate + election closed
   - Expected: `participationAllowed = 'election_closed'` (denial from LifecyclePolicy, NOT trust)
   - Constitutional semantics: Trust legitimacy irrelevant; lifecycle state is sovereign

4. **Authorization Protocol Enforced** — Dual-code requirement + single code provided
   - Expected: `participationAllowed = 'denied'` (denial from AuthorizationPolicy)
   - Snapshot fields: `authorizationProtocol = 'dual_code'`, both commit requirements satisfied

5. **Trust Denial Short-Circuits Lifecycle** — Network limit exceeded → trust denied, lifecycle open
   - Expected: `participationAllowed = 'trust_denied'` (denial from TrustCapabilityPolicy, lifecycle never evaluated)
   - Constitutional semantics: Correct policy ordering (Overlay→Trust→Lifecycle)

### P0 — Overlay Non-Sovereignty (Must Pass)

6. **Emergency Overlay Does NOT Suspend Authority** — Emergency condition active + registrar elevated trust
   - Expected: `participationAllowed = 'require_constitutional_review'` (overlay signals, not grants)
   - Constitutional semantics: Overlay raises signal flag; resolver interprets; final decision is constitutional review requirement

7. **Suspicious Activity Signals Review** — Velocity threshold exceeded + valid trust
   - Expected: `participationAllowed = 'trust_evaluation_inconclusive'` (overlay signals inconclusive trust)
   - Constitutional semantics: IpVelocityOverlay signals, TrustCapabilityPolicy denies via ConstitutionalReviewPending

### P0 — Replay Integrity (Must Pass)

8. **Commit Token Reuse Rejected** — Same commit token used twice
   - Expected: `participationAllowed = 'replay_rejected'`
   - Constitutional semantics: CommitAuthorizationFreshness prevents replay; constitutional trust integrity preserved

9. **Stale Authorization Denied** — Authorization issued 1 hour ago, now voting
   - Expected: `participationAllowed = 'authorization_expired'`
   - Constitutional semantics: Fresh authorization enforced; replay window bounded

### P1 — Constitutional Sequencing (Must Pass)

10. **Overlay Evaluated Before Trust** — Overlay suspends + trust valid
    - Expected: `participationAllowed = 'suspended'` (Overlay policy priority 1 < Trust policy priority 2)
    - Constitutional semantics: Correct policy stratification enforced

---

## Success Criteria for Phase 1

✅ **All 10 Critical Paths pass parity** — Legacy and resolver produce identical 8-field snapshots

✅ **No unknown divergences** — All differences are classified, typed, and approved

✅ **Divergence ledger is signed** — Governance authority approved each intentional divergence

✅ **Constitutional semantics preserved** — trustLegitimate ≠ participationAllowed; overlay signals don't grant authority; replay is safe

✅ **Replay-safe metadata** — Divergence ledger includes constitutional article reference for future dispute replay

---

## Key Warnings from Senior Architect Review

### 1. Avoid Parity by Downgrading Resolver

When divergence appears, do NOT instinctively move resolver toward procedural legacy behavior.

First classify: **Is legacy constitutionally correct?**

This is the core migration governance question.

### 2. Beware Hidden Procedural Coupling

Legacy may depend on:
- Request timing
- Session state
- Implicit caching
- Middleware side effects
- Stale DB reads
- Ordering accidents

Resolver is deterministic constitutional derivation. Divergence may reveal **accidental procedural behavior, not constitutional rule**.

Carefully distinguish:
- Constitutional rule difference (OK to diverge)
- Accidental procedural behavior (must replicate)

### 3. Each Scenario Must Represent Constitutional Jurisprudence

Do NOT mass-generate 432 scenarios via loops.

Each must have:
- Named constitutional meaning
- Explicit sovereignty purpose
- Human-readable divergence explanation

Otherwise suite becomes combinatorial noise.

### 4. Expected Divergences Never Become Undocumented

The 4 "approved improvements" must:
- Exist in ConstitutionalDivergenceLedger
- Reference constitutional article
- Have explicit governance approval
- Explain sovereignty rationale

Otherwise future replay becomes impossible.

---

## Next Architectural Phase (After Critical Paths Pass)

1. **Validate Divergence Ledger Process** — Confirm classification system works
2. **Verify Replay Lineage Recording** — Ensure constitutional article references enable dispute replay
3. **THEN Expand Toward Full Matrix** — 432 combinations with same rigor
4. **ONLY After Stable Parity:** Resolver wiring, Controller integration, Guardrails, D.6 convergence

---

## Long-Term Architectural Asset

If done correctly, this parity suite becomes:

# Executable Constitutional Jurisprudence

Future constitutional amendments can be validated against:
- Historical legitimacy (constitutional article lineage)
- Replay correctness (semantic equivalence preserved)
- Sovereignty consistency (no distributed authority leakage)
- Governance invariants (trustLegitimate vs participationAllowed maintained)

This is a massive long-term architectural asset for evolution, federation, and dispute resolution.

---

## Architectural Maturity Level

**CURRENT:** Constitutional Governance Systems Engineering

**TRANSITION COMPLETE:** From feature behavior validation → constitutional state equivalence validation

**NEXT:** Replay-safe constitutional migration infrastructure (Phase 1 critical paths validate this)

---

**The work ahead is governance validation, not application testing.**
