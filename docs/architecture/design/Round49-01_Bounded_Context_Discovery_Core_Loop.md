# Round 49-01 — Candidate Bounded Context Evaluation: the Core Correction Loop (v1.1)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Strategic DDD (Phase II) · **Built against:** Certified Release v1.0 / Landscape v1.0 / Package 1.0.0 / Vocabulary 1.0.0 / Ontology 1.0.0
**Status:** 🔎 CANDIDATE BC **EVALUATION** (v1.1 — was "Bounded Context Discovery"). Evaluates **evidence**, does **not** confirm boundaries or design aggregates. ADQC v1.1; `Round47-OP` discovery discipline.
**Date:** 2026-06-26

> **Methodological correction (v1.0→v1.1).** *Capability discovery ≠ bounded-context confirmation.* Finding related classes proves a **certified capability has implementation support** — it does **NOT** prove a **bounded context exists** (the classes might be one aggregate/module/subdomain inside another BC). This round therefore **evaluates candidates**; **BC confirmation is the next round**; **aggregate design is later** (Round 50+). Aggregates here are **Potential**, not designed.
> **Reasoning flow (per review):** Certified capability → Observed implementation (evidence) → Implementation alignment → BC discovery criteria → BC confidence → open questions.
> **Alignment scale:** Fully Aligned · Partially Aligned · Present · Absent · Conflicting. **BC confidence:** High · Medium · Low.

## 0. Headline (restated defensibly)

**Two certified capabilities — Adjudication and Evidence/Replay — already have substantial *implementation support* in the codebase; Contestation has none.** This is a statement about **code supporting capabilities**, *not* about bounded contexts already existing. Whether each capability becomes a standalone BC is evaluated below and **confirmed in the next round.**

---

## CC-1 — Adjudication
- **Certified capability:** issue a binding **Determination** (Finality).
- **Observed implementation (evidence):**
  | Class (app/Contexts/Membership/Domain/Committee/Constitutional) | Implies |
  |----|----|
  | `ConstitutionalArbitrationKernel` (`decide → ConstitutionalGovernanceDecision`) | adjudication *service* exists |
  | `ConstitutionalArbitrationPolicy` / `DefaultConstitutionalArbitrationPolicy` | arbitration rules exist |
  | `ConstitutionalDecision` | a decision *record* exists |
  | `LegitimacyEvaluator` / `LegitimacyOutcome` | legitimacy derived (single resolver) |
- **Implementation alignment:** **Partially Aligned** — capability present, but **vocabulary differs** (`ConstitutionalDecision` ≠ certified "Determination") and it lives **inside Membership/Committee**, not a standalone Adjudication module.
- **BC discovery criteria:** ownership ✓ · cohesion ✓ · autonomy ✓ (stateless kernel) · language ✓ · transactional boundary ✓ (single decision record) · evolutionary independence **?** (currently coupled to Committee) · team boundary unknown.
- **BC confidence: Medium-High.** Strong candidate — but may resolve as a **subdomain within Governance/Membership** rather than standalone. *Open:* is Adjudication its own BC or a subdomain of an Oversight BC?

## CC-2 — Evidence  *(split from Replay — see CC-3)*
- **Certified capability:** immutable **System of Record** (reviewable record); Anonymity-preserving.
- **Observed implementation (evidence):**
  | Class | Implies |
  |----|----|
  | `ReplayEvidenceEnvelope` (`readonly`, deterministic hash, **hashed** `voterIdentifier`) | immutable record + anonymity |
  | `EvidenceClassification` / `EvidenceSnapshot` / `EvaluationAuditTrail` | evidence is classified, snapshotted, trailed |
- **Implementation alignment:** **Partially Aligned / Present** — strong immutable-record support.
- **BC discovery criteria:** ownership ✓ · cohesion ✓ · language ✓ · lifecycle ✓ (frozen at creation) · transactional boundary ✓ (append-only immutable) · evolutionary independence **?**.
- **BC confidence: Medium.** Strong capability evidence; "Evidence as a BC" vs "Evidence as an aggregate inside a broader context" is **open**.

## CC-3 — Replay  *(deliberately separated from Evidence)*
- **Certified mapping:** integrity verification + outcome reproduction. **Evidence = truth; Replay = behaviour over truth** — they may evolve differently.
- **Observed implementation (evidence):** `ReplaySession`, `ReplayCertification`, `GovernanceReplayService`, `ScopeAwareReplayValidator`, `ConstitutionalReplayFingerprint`.
- **Implementation alignment:** **Present** (rich replay machinery).
- **⭐ Open question (do NOT decide now):** Is Replay a **domain capability** (own BC), an **application service** over the Evidence BC, or an **infrastructure capability**? Evidence leans toward *application/infrastructure behaviour consuming Evidence*, not a separate BC.
- **BC confidence (as a *separate* BC): Low.** More likely an application/infrastructure capability over Evidence → decide at Round 50.

## CC-4 — Contestation  *(greenfield)*
- **Certified capability:** a party **with standing (S-5)** raises a **Challenge** routed to Adjudication.
- **Observed implementation:** **Absent** (zero files — challenge/appeal/standing/dispute).
- **Implementation alignment:** **Absent.**
- **BC discovery criteria (by certification, not code):** ownership ✓ (standing/challenge) · language ✓ · autonomy ✓ · transactional **?** · evolutionary **?**.
- **BC confidence: High that the GAP is real** (absence confirmed); **Medium that it is a *separate* BC** (vs a module within Adjudication/Contestation). Closing it **closes the correction loop** — the highest-value governance→software gap.

---

## Summary

| Candidate | Alignment | BC confidence | Potential aggregate(s) *(TBD — Round 50)* |
|-----------|-----------|---------------|-------------------------------------------|
| Adjudication | Partially Aligned | Med-High | *Potential:* Determination |
| Evidence | Partially Aligned / Present | Medium | *Potential:* EvidenceEnvelope |
| Replay | Present | **Low (as separate BC)** | *Potential:* ReplaySession — or app/infra service |
| Contestation | Absent | High (gap) / Med (separate BC) | *Potential:* Challenge |

*("Potential," not "Candidate" — aggregate boundaries are discovered at Round 50, not asserted here. No Tactical DDD leakage.)*

## ADQC v1.1 (this evaluation as a decision)
Q1 PASS · Q2 PASS · Q9 PASS (only certified concepts) · **Q11 Boundary Clarity MINOR CONCERN** (Adjudication-vs-Oversight, Evidence-vs-Replay, Contestation-vs-Adjudication boundaries open) · **no gating FAIL.** *(Q10 invariant integrity deferred to BC confirmation — needs the boundaries first.)*

## Items (software-side, NOT governance)
- **AI-1 (architecture debt):** multi-home (`app/Domain/Election` + `app/Application/Election` + `app/Contexts/{Governance,Membership,Committee}`) — consolidation decided in `Round49-03` (→ `app/Contexts/*`).
- **NM-1 (naming):** `*Legitimacy`/`*Decision` proliferation → Canonical Vocabulary (Determination/Legitimacy).

## Roadmap (corrected — adds BC confirmation)
```
49-01 Candidate BC EVALUATION (this) ✓
   → BC CONFIRMATION (confirm/merge/reject candidates; ADQC v1.1 + boundary-evidence + confidence)
   → AGGREGATE discovery (Round 50; resolve Evidence-vs-Replay, Potential→designed aggregates)
   → Tactical DDD → code (Contestation prototype only after boundaries confirmed)
```
*(BC confirmation inserted before aggregate discovery — otherwise aggregates would be designed inside unconfirmed contexts.)*

---

*Round 49-01 — Candidate Bounded Context Evaluation (Core Loop) v1.1 — ISSUED.*
*Capability discovery ≠ BC confirmation: TWO certified capabilities (Adjudication, Evidence/Replay) have substantial IMPLEMENTATION SUPPORT; Contestation ABSENT. Alignment-graded (Partially Aligned/Present/Absent), evidence tables, discovery criteria, BC confidence (Adjudication Med-High · Evidence Med · Replay LOW-as-separate-BC · Contestation High-gap). Replay placement OPEN (domain/app/infra). Aggregates = POTENTIAL only. Next: BC Confirmation (before aggregates). No code until boundaries confirmed.*
