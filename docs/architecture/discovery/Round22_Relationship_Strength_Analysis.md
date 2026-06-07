# Round 22 — Relationship Strength Analysis

**Date:** 2026-06-07

**Phase:** Strategic DDD Relationship Strength Analysis (Step 3 Final Checkpoint)

**Status:** Complete — Awaiting ARB Review

---

## 1. Scope

**Purpose:** Evaluate each mapped relationship from Round 21 and classify it by type, strength, essentiality, and boundary implications.

**Relationship classifications:**
- **Core Dependency** — Observed implementation exhibits a strong dependency between the two candidate contexts
- **Supporting Dependency** — Useful but not essential; context can function independently
- **Observability Dependency** — Consumes data for recording/monitoring; no business impact
- **Governance Dependency** — Required for compliance or policy but not for core operation
- **Boundary-Collapse Indicator** — Relationship is so tight that contexts may be the same
- **Boundary-Separation Indicator** — Relationship is so loose that contexts are clearly distinct

**For each relationship:**
1. Does it strengthen separation or suggest merger?
2. Does one context own decisions the other cannot make?
3. Could one context exist without the other?
4. Is the relationship operational, informational, or governance-driven?

---

## 2. Relationship Strength Inventory

### R1: Trust Attestation → Eligibility

| Dimension | Assessment |
|-----------|-----------|
| **Classification** | Core Dependency |
| **Strength** | Strong — Eligibility requires verification as prerequisite, but decision ownership is separate |
| **Essentiality** | High — Eligibility cannot be evaluated without verification status |
| **Could E exist without T?** | Yes — Eligibility could evaluate other criteria without verification (e.g., membership-only elections) |
| **Could T exist without E?** | Yes — Trust Attestation can verify identities without checking eligibility |
| **Separation or Merger** | **Strengthens separation** — Each owns distinct decisions (identity trust vs. process eligibility) |
| **Dependency type** | Informational — Eligibility reads verification status but does not change it |
| **Boundary implication** | Clear upstream/downstream with separate decision ownership |

---

### R2: Eligibility → Authorization

| Dimension | Assessment |
|-----------|-----------|
| **Classification** | Core Dependency |
| **Strength** | Strong — Authorization formula requires eligibility as prerequisite |
| **Essentiality** | High — Observed implementation requires eligibility input for authorization |
| **Could A exist without E?** | Observed implementation suggests no — Authorization's read of eligibility status is required in examined code |
| **Could E exist without A?** | Yes — Eligibility can determine eligibility without granting authorization |
| **Separation or Merger** | **Strengthens separation** — Each owns distinct decisions (eligibility vs. permission) |
| **Dependency type** | Informational — Authorization reads eligibility status but does not determine it |
| **Boundary implication** | Clear upstream/downstream. Verified → Eligible → Authorized chain is compositional. |

---

### R3: Constitutional Governance → Authorization

| Dimension | Assessment |
|-----------|-----------|
| **Classification** | Core Dependency |
| **Strength** | Moderate — Authorization needs lifecycle state to determine valid actions, but capability resolution is separate logic |
| **Essentiality** | High — Authorization cannot determine action validity without lifecycle context |
| **Could A exist without G?** | Observed implementation suggests no — Authorization reads lifecycle state in examined capability resolution |
| **Could G exist without A?** | Yes — Constitutional Governance defines states independently of any capability check |
| **Separation or Merger** | **Strengthens separation** — Rule definition (Governance) is distinct from rule enforcement (Authorization) |
| **Dependency type** | Informational — Authorization reads lifecycle state from Governance |
| **Boundary implication** | Clear separation with explicit dependency. Governance defines "what"; Authorization enforces "whether allowed." |

---

### R4: Constitutional Governance → Voting

| Dimension | Assessment |
|-----------|-----------|
| **Classification** | Governance Dependency |
| **Strength** | Moderate — Voting depends on lifecycle state being 'voting_active' |
| **Essentiality** | High — Voting cannot accept submissions unless lifecycle allows it |
| **Could V exist without G?** | Observed implementation suggests no — Voting reads Governance lifecycle state to determine voting window in examined code |
| **Could G exist without V?** | Yes — Governance can define voting states without Voting accepting actual votes |
| **Separation or Merger** | **Strengthens separation** — Governance defines timing; Voting owns the electoral process |
| **Dependency type** | Governance-driven — Lifecycle state is a governance constraint, not operational data |
| **Boundary implication** | Clear separation. Voting is gated by Governance but owns vote recording independently. |

---

### R5: Authorization → Voting

| Dimension | Assessment |
|-----------|-----------|
| **Classification** | Core Dependency |
| **Strength** | Strong — Every vote must pass authorization check before acceptance |
| **Essentiality** | High — Voting cannot accept unauthorized votes |
| **Could V exist without A?** | Observed implementation suggests no — Voting reads authorization via capability resolver in examined code |
| **Could A exist without V?** | Yes — Authorization can determine who can vote without recording votes |
| **Separation or Merger** | **Strengthens separation** — Authorization is gate; Voting is action |
| **Dependency type** | Operational — Authorization check is performed at vote submission time |
| **Boundary implication** | Clear upstream/downstream. Authorization is a precondition; Voting is the authorized action. |

---

### R6: Voting → Results/Tallying

| Dimension | Assessment |
|-----------|-----------|
| **Classification** | **Boundary-Collapse Indicator** |
| **Strength** | **Very strong** — Synchronous coupling, same request lifecycle, results regenerable from vote data |
| **Essentiality** | Results are entirely dependent on Voting — cannot exist without vote data |
| **Could R exist without V?** | **No** — Results are a derived projection of Vote data in examined implementation. No vote = no results in current design. |
| **Could V exist without R?** | Yes — Votes could be recorded without pre-computing results (results computed on-demand only) |
| **Separation or Merger** | **Suggests merger** — Results own no unique decisions, share same language, are synchronously coupled, and can be regenerated from Vote data |
| **Dependency type** | Operational — Synchronous data projection in same request lifecycle |
| **Boundary implication** | **MERGER CANDIDATE.** Results appear to function as a computational projection within examined implementation. However, the constitutional "counting" state (ADR-003) may imply separate business activity. Decision deferred to D39. |

---

### R7: Voting → Audit

| Dimension | Assessment |
|-----------|-----------|
| **Classification** | Observability Dependency |
| **Strength** | Weak — Fire-and-forget; Audit does not affect Voting |
| **Essentiality** | Low — Voting functions without Audit; Audit adds accountability but does not enable voting |
| **Could V exist without A?** | Yes — Voting can record votes without audit trail |
| **Could A exist without V?** | Yes — Audit can record other governance actions (authorization, state changes) |
| **Separation or Merger** | **Strongly supports separation** — Different concerns, different ownership, fire-and-forget pattern |
| **Dependency type** | Observability — One-way event notification with no feedback |
| **Boundary implication** | Clear separation. Audit is an observability layer, not a core decision-maker. |

---

### R8: Constitutional Governance → Audit

| Dimension | Assessment |
|-----------|-----------|
| **Classification** | Observability Dependency |
| **Strength** | Weak — Fire-and-forget; Audit does not affect Governance |
| **Essentiality** | Low — Governance transitions function without audit recording |
| **Could G exist without A?** | Yes — Governance transitions can proceed without audit trail |
| **Could A exist without G?** | Yes — Audit can record actions from other sources (voting, authorization) |
| **Separation or Merger** | **Strongly supports separation** — Different concerns, fire-and-forget pattern |
| **Dependency type** | Observability — One-way event notification |
| **Boundary implication** | Clear separation. Audit is an observability layer. |

---

### R9: Authorization → Audit

| Dimension | Assessment |
|-----------|-----------|
| **Classification** | Observability Dependency |
| **Strength** | Weak — Fire-and-forget (SecurityEventRecorder); Audit does not affect Authorization |
| **Essentiality** | Low — Authorization decisions function without security event recording |
| **Could A exist without A?** | Yes — Authorization can make decisions without audit trail |
| **Could A exist without A?** | Yes — Audit can record actions from other sources |
| **Separation or Merger** | **Strongly supports separation** — Different concerns, fire-and-forget pattern |
| **Dependency type** | Observability — One-way event notification |
| **Boundary implication** | Clear separation. Audit is an observability layer. |

---

### R10: Constitutional Governance → Arbitration (Distributed)

| Dimension | Assessment |
|-----------|-----------|
| **Classification** | Governance Dependency (Unresolved) |
| **Strength** | Weak — Implementation exists but operational invocation not observed |
| **Essentiality** | Low — Governance decisions function without Arbitration review |
| **Could G exist without Ar?** | Yes — Governance can manage state transitions without constitutional review |
| **Could Ar exist without G?** | Would have no decisions to evaluate |
| **Separation or Merger** | **Boundary unresolved** — Pending D35, D36, D37 |
| **Dependency type** | Governance-driven — Constitutional validity evaluation |
| **Boundary implication** | Unresolved. May be separate context or Governance subdomain. |

---

## 3. Relationship Classification Summary

| Relationship | Type | Strength | Essential | Separation Indicator |
|-------------|------|----------|-----------|---------------------|
| R1: Trust → Eligibility | Core Dependency | Strong | High | Strengthens separation |
| R2: Eligibility → Authorization | Core Dependency | Strong | High | Strengthens separation |
| R3: Governance → Authorization | Core Dependency | Moderate | High | Strengthens separation |
| R4: Governance → Voting | Governance Dependency | Moderate | High | Strengthens separation |
| R5: Authorization → Voting | Core Dependency | Strong | High | Strengthens separation |
| R6: Voting → Results | **Boundary-Collapse Indicator** | **Very Strong** | **Results are derived from Vote data in examined implementation** | **Suggests merger** |
| R7: Voting → Audit | Observability | Weak | Low | Strengthens separation |
| R8: Governance → Audit | Observability | Weak | Low | Strengthens separation |
| R9: Authorization → Audit | Observability | Weak | Low | Strengthens separation |
| R10: Governance → Arbitration | Governance (Unresolved) | Weak | Low | Unresolved |

---

## 4. Essential vs. Incidental Relationships

### Essential Relationships (Core business flow)

| Relationship | Reason |
|-------------|--------|
| Trust → Eligibility | Eligibility requires verification prerequisite |
| Eligibility → Authorization | Authorization requires eligibility input |
| Governance → Authorization | Authorization requires lifecycle state |
| Governance → Voting | Voting requires governance permission to open |
| Authorization → Voting | Voting requires authorization gate |
| Voting → Results | Results derived from vote data |

### Incidental Relationships (Supporting/Observability)

| Relationship | Reason |
|-------------|--------|
| Voting → Audit | Accountability only; voting functions without audit |
| Governance → Audit | Accountability only; governance functions without audit |
| Authorization → Audit | Accountability only; authorization functions without audit |

### Unresolved Relationships

| Relationship | Reason |
|-------------|--------|
| Governance → Arbitration | Operational path not observed; boundary pending D35/D36/D37 |

---

## 5. Boundary Implications

### Merger Candidates

| Candidate | Strength of Evidence | Condition |
|-----------|--------------------|-----------|
| Voting + Results/Tallying | Strong — synchronous coupling, no unique decisions observed within examined evidence, regenerable data | Pending D39 (counting state meaning) |

### Evidence Supporting Separation

| Separation | Evidence |
|-----------|----------|
| Trust Attestation ≠ Eligibility | ADR-002, different decision ownership, different language |
| Eligibility ≠ Authorization | ADR-002, compositional dependency (not ownership) |
| Governance ≠ Authorization | Different concerns (rule definition vs. rule enforcement) |
| Governance ≠ Voting | Governance gates but does not record votes |
| Authorization ≠ Voting | Authorization gates but does not record votes |
| All contexts ≠ Audit | Fire-and-forget observability; no business dependency |
| Governance ≠ Arbitration | Distinct concepts; operational status unresolved |

---

## 6. Evidence Supporting Candidate Context Independence

### Evidence Supporting Independent Candidate Context (HIGH Confidence)

1. **Trust Attestation** — Strong evidence of unique decision ownership (verification decisions), clear boundaries, ADR-001/002/003 support
2. **Eligibility** — Strong evidence of unique decision ownership (process eligibility decisions), ADR-002 support, compositional dependency (not ownership)
3. **Authorization** — Strong evidence of unique decision ownership (capability decisions), centralized authority, ADR-001/004 support
4. **Constitutional Governance** — Strong evidence of unique decision ownership (lifecycle state, transition rules), centralized rules, mandatory enforcement
5. **Audit** — Strong evidence of fire-and-forget observability pattern, no business coupling to any context, distinct data ownership

### Evidence Supporting Provisional Independence

6. **Voting** — Strong evidence of unique decision ownership (vote recording) but boundary with Verification potentially affected by D42B
7. **Results/Tallying** — No unique decisions observed within examined evidence; strong merger indicator with Voting; separate storage and constitutional counting state suggest provisional independence pending D39

### Evidence Supporting Deferral

8. **Governance Replay** — Operational status unresolved (Phase 6 deferral, D36)
9. **Arbitration/Legitimacy** — Boundary unresolved pending D35/D36/D37
10. **Challenge/Dispute** — Distributed capability (Outcome F from Stream 6A) — not a candidate context

---

## 7. Open Strategic Questions

### Question A: Does the Trust → Eligibility → Authorization → Voting Chain Represent a Single Decision Pipeline?

The observed dependency chain (Trust → Eligibility → Authorization → Voting) forms a sequential decision pipeline in the examined implementation. This pipeline may represent a core strategic backbone of the election domain, spanning multiple candidate contexts. Whether this pipeline is a single domain concept (participant access control) or a set of independent but sequential decisions is unresolved.

This is a discovery question, not a conclusion.

### Question B: Do the 5 HIGH-confidence candidates and 2 provisional candidates exhibit sufficient evidence for aggregate discovery to begin?

**Evidence supporting aggregate discovery readiness:**
- 5 candidates exhibit unique decision ownership, stable relationship patterns, and strong separation indicators from mapped relationships
- Voting and Results/Tallying have documented boundary conditions (D39, D42B) that do not prevent internal aggregate exploration
- All essential and incidental relationships are classified
- Merger candidates and unresolved boundaries are explicitly flagged

**Evidence supporting deferral of aggregate discovery:**
- Voting/Results merger decision depends on D39 resolution
- Voting/Verification boundary depends on D42B resolution
- Two candidate contexts are not yet ready for mapping (Governance Replay operational status unresolved, Arbitration boundary unresolved)
- One distributed capability (Challenge/Dispute) is not a candidate context

**Note:** This question is presented for ARB deliberation. Whether aggregate discovery proceeds is an ARB decision, not a recommendation of this analysis.

---

**Round 22 Relationship Strength Analysis — READY FOR ARB REVIEW**

**5 HIGH-confidence candidates exhibit evidence supporting independent bounded context status. 2 provisional candidates exhibit evidence supporting provisional independence. 2 candidates deferred. 1 distributed capability. Merger indicator for Voting+Results pending D39. ARB deliberation required to determine next phase.**
