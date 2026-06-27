# Round 47-01 — Strategic Domain Revalidation

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Round 47 — Strategic DDD (Software Architecture Layer) · **Built against:** Certified Release v1.0 (Package 1.0.0 / Vocabulary 1.0.0 / Ontology 1.0.0)
**Status:** 🔁 REVALIDATION — re-examines the **earlier bounded contexts as a hypothesis** against the Certified Knowledge Package. Empirical (compares theory vs existing code). Obeys the Strategic DDD Constitution (SD-1..7).
**Date:** 2026-06-26

> **Phase boundary.** **Phase I — Governance Knowledge Engineering (Rounds 38–46B): CLOSED.** **Phase II — Strategic Domain Engineering (Round 47+): IN PROGRESS.** This is Phase II's first artifact.
> **Reframing (per review).** The Round 29 Bounded Context Catalog (9 contexts) was discovered **before** the ontology/projection/ownership/certification existed. It is therefore **Candidate Domain Landscape v0 — a hypothesis**, not authoritative. This round revalidates it (Confirmed/Split/Merge/Boundary-changed/Rejected/Renamed/Ownership-changed/Deferred) → **Certified Strategic Domain Landscape v1.0 (candidate)**.
> **Empirical, not retrofit (SD-5/TA-2).** Compares the certified theory against the existing `app/Domain` implementation to understand reality — it does **not** bend the architecture to the code, nor silently change governance to fit the code (SD-2/SD-6: tensions become governance items, not edits).

---

## 1. Empirical findings (theory vs existing code)

The codebase already contains a substantial `app/Domain/Election` + `app/Domain/Voting` layer from Rounds 17–36. Key reality checks:

| Certified concept | Existing code | Finding |
|-------------------|---------------|---------|
| **Anonymity** (invariant, supreme) | Voting context core invariant "**no user_id column**"; `add_anonymity_*` migration | ✅ **already realized** — the strongest theory↔code alignment |
| **Legitimacy** (derived, one projection, never persisted) | `LegitimacyOutcome` enum — computed, **single exclusive resolver**, no `fromTrustState`, not persisted | ✅ **already honors** constraint #3 + Forbidden(persist) |
| **Evidence** (System of Record, immutable) | `EvidenceClassification/Snapshot`, `EvaluationAuditTrail`, Replay `EvidenceEnvelope` (immutable seals) | ✅ strong alignment |
| **Eligibility** (BLOCKED — RQ-EL-01) | `EligibilityEvaluator` ("deterministic, read-only"), `Eligibility` **STABLE bounded context** (R29 #2) | ⚠ **TENSION** — code has a stable Eligibility context; certified package **blocks** the governance family. See GI-1. |
| **Constitutional Trust-Anchor** (external) vs PKI | `TrustLevel`, `DeviceTrustContext`, `NetworkTrustEvidence` = **device/PKI trust** | ⚠ **naming collision confirmed in code** — "Trust" here is operational, NOT the constitutional anchor (LIT-2 disambiguation needed). |
| **Adjudication / Finality / Determination** | `Arbitration/Legitimacy` context (UNRESOLVED, D35/36/37); `ConstitutionalArbitrationKernel` | partially built; debts now answerable by certified F-REV knowledge |
| **Appointment / Mandate** (F-AUTH seam) | scattered (`ElectionRole`, `CommitteeMemberProjection`, `UserOrganisationRole`) — no owning context | gap — under-represented |

---

## 2. Revalidation of Candidate Domain Landscape v0 (Round 29's 9 + C10)

| # | v0 Context | Status | Rationale (against Certified Release v1.0) |
|---|-----------|--------|--------------------------------------------|
| 1 | **Trust Attestation** | **Split + Renamed** | Splits into **Attestation Evidence** (Record-Keeping seam — Confirmed) + **Identity-Trust decision** (touches **Voter-identity = BLOCKED** RQ-ID-01 → Deferred). Rename to avoid collision with *Constitutional Trust-Anchor*. |
| 2 | **Eligibility** | **Deferred** | Governance *Eligibility family* is **Blocked** (RQ-EL-01). The stateless read-only evaluator survives as an **F-PROC voting precondition (policy)**, not an owning context. → **GI-1** (resolving it = **Breaking** release). |
| 3 | **Authorization** | **Confirmed (vocabulary-aligned)** | = capability resolution constrained by **Mandate** + **Decisional independence**. "Authority" is overloaded → qualify. Ownership spans Appointment (Mandate) + Adjudication (decisional). |
| 4 | **Constitutional Governance** | **Renamed + Confirmed** | It is the **lifecycle state machine**, not the governance Constitution → rename **"Election Lifecycle Governance."** Disambiguate "Constitutional" (per-election rules ≠ Methodology/Governance Constitution). |
| 5 | **Audit** | **Confirmed + Boundary-clarified** | Observability Record-Keeping (fire-and-forget). **Distinct** from *authoritative Evidence* (which feeds the correction loop) — that lives with #8. |
| 6 | **Voting** | **Confirmed (strong)** | F-PROC plant + **Anonymity invariant already realized**. D42B (verifiability scope) remains a discovery debt. |
| 7 | **Results / Tallying** | **Boundary-changed → reclassified Read Model** | Certified: a **Derived View / Read Model**, not an owning aggregate. Matches the R25 Projection Test. Not a bounded context. |
| 8 | **Governance Evidence Replay** | **Confirmed** | = **Evidence as System of Record** (immutable seals) + integrity certification. Core of the Record-Keeping seam's authoritative side. |
| 9 | **Arbitration / Legitimacy** | **Split + Confirmed** | **Arbitration → Adjudication seam** (Review→Determination→Finality) = Confirmed. **Legitimacy → derived Read Model** (single resolver, already in code). **D35/36/37 now RESOLVED** by certified F-REV knowledge (legitimacy=consent-based; finality constitutive; recursion terminates by fiat/axiom/consent). |
| C10 | **Challenge / Dispute** *(R29 demoted to "not a context")* | **Promoted (reversal)** | Certified knowledge elevates **Contestation** to an **owning seam** (S-5 standing; the correction loop). R29's demotion is **reversed** → candidate **Contestation** context. |
| — | **Appointment / Mandate** *(absent in v0)* | **New (candidate)** | The F-AUTH **Appointment** seam (who is mandated, institutional independence) has no owning context in v0 → candidate new context. |

---

## 3. Certified Strategic Domain Landscape v1.0 (candidate) — mapped to the 4 owning seams

| Owning seam (R45) | Candidate context(s) | From v0 |
|-------------------|----------------------|---------|
| **Record-Keeping** | Attestation Evidence · Audit (observability) · **Evidence/Replay** (authoritative, System of Record) | 1(part), 5, 8 |
| **Adjudication** | **Arbitration → Determination/Finality** · Authorization (decisional) | 9(part), 3 |
| **Contestation** | **Contestation/Appeal** (promoted) | C10 |
| **Appointment** | **Appointment/Mandate** (new) · Authorization (mandate) | new, 3 |
| **F-PROC substrate** *(plant, not a governance seam)* | **Voting** · Election Lifecycle Governance · (Eligibility evaluator as policy) | 6, 4, 2 |
| **Derived / cross-cutting / external** | Results = Read Model · **Legitimacy** = Read Model · **Anonymity** = invariant · Constitutional **Trust-Anchor** / Consent = external | 7, 9(part) |

**Net change from v0:** 9 contexts → revalidated to **~6–7 owning candidates + Voting/Lifecycle substrate + reclassified Read Models + external boundaries.** Two promotions (Contestation; new Appointment), two reclassifications (Results, Legitimacy → Read Models), one deferral (Eligibility), several renames/splits.

---

## 4. Governance items raised (NOT actioned — Architecture Change Protocol, SD-6)

These tensions between the certified package and the code/v0 **must not** be silently resolved in DDD. Each is a governance item:

- **GI-1 (Eligibility):** code has a STABLE Eligibility context; certified package Blocks the family (RQ-EL-01). → Governance Research must rule whether the procedural evaluator is F-PROC machinery (no release change) or whether Eligibility is admitted (**Breaking release v2.0**). **Deferred to governance.**
- **GI-2 (Trust naming):** code's device/PKI "Trust" collides with Constitutional Trust-Anchor. → Vocabulary clarification (**Patch/Minor** release); DDD uses "Constitutional Trust-Anchor" for the anchor, retains device-trust under a distinct name.
- **GI-3 (D35/36/37 closure):** certified F-REV knowledge resolves the old Arbitration/Legitimacy debts → propose a governance note confirming closure (**Minor** release).

*Per SD-2/SD-6, DDD proceeds on the admitted concepts only; these items route to Knowledge Release Governance.*

---

## 5. Verdict & next

The earlier 9-context catalog **largely survives revalidation** — Voting, Audit, Evidence/Replay, Authorization, Lifecycle Governance, and Arbitration are confirmed (with renames/splits), validating the earlier discovery work. But the Certified Knowledge **materially reshapes** it: **Contestation is promoted**, **Appointment is added**, **Results and Legitimacy become Read Models**, **Eligibility is deferred (blocked)**, and the **Trust** naming collision is confirmed in code. The strongest empirical result is that the code **already honors** the two hardest certified constraints — **Anonymity** (no user_id) and **single non-persisted Legitimacy** (the `LegitimacyOutcome` exclusive resolver).

This is the **Certified Strategic Domain Landscape v1.0 (candidate)** — still a landscape, not a context map. **Next: Round 48 Context Mapping** (relationships/upstream-downstream among the revalidated contexts), then bounded-context definitions — all under SD-1..7, built against Release v1.0, with GI-1/2/3 carried to Knowledge Release Governance.

```
Phase I (38–46B) CLOSED → Phase II (47+): 47-00 SDD Constitution ✓ → 47-01 Revalidation (this) ✓
   → Round 48 Context Mapping → 49 Bounded Contexts → 50 Aggregates → ...
   (parallel pending: Methodology Governance Review MB-39.2 [optimization, non-blocking]; GI-1/2/3 → KRG)
```

---

*Round 47-01 — Strategic Domain Revalidation — ISSUED (Phase II; built against Certified Release v1.0).*
*v0 (Round 29's 9 contexts) revalidated: Voting/Audit/Evidence-Replay/Authorization/Lifecycle/Arbitration CONFIRMED (renames/splits); Contestation PROMOTED; Appointment ADDED; Results+Legitimacy→Read Models; Eligibility DEFERRED (blocked, GI-1). Code already honors Anonymity + single-Legitimacy-resolver. Phase I CLOSED. Next: Round 48 Context Mapping. SD-1..7 obeyed; GI-1/2/3 → governance.*
