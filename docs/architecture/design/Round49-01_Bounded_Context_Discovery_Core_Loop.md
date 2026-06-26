# Round 49-01 — Bounded Context Discovery: the Core Correction Loop (empirical)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Strategic DDD (Phase II) · **Built against:** Certified Release v1.0 / Landscape v1.0 / Package 1.0.0 / Vocabulary 1.0.0 / Ontology 1.0.0
**Status:** 🧱 BOUNDED CONTEXT DISCOVERY — the **Core Domain** (Adjudication ← Evidence&Replay ← Contestation, per `Round48-01` CM-1). **Empirical:** grounded in the actual `app/Contexts` + `app/Domain` code. Scored against ADQC (`Round48-00`).
**Date:** 2026-06-26

> **Why Core first.** CM-1 named the **correction loop** the Core Domain (the trustworthiness differentiator). This round defines its bounded contexts + candidate aggregates **against real code** — testing whether the certified design already exists, is partial, or is greenfield.
> **Empirical headline.** **2 of the 3 Core contexts are already substantially built and confirm the certified design; Contestation is greenfield** — making it the natural prototype target (and it closes the loop, S-5).

## 1. Code reality (what already exists)

- **`app/Contexts/`** — a **bounded-context code layer** exists (`Governance`, `Membership`) **in addition to** the older `app/Domain/Election`. *(Architectural debt AI-1: parallel evolution / two homes for the same concepts — needs a consolidation decision.)*
- **Adjudication** — BUILT: `Membership/.../Constitutional/ConstitutionalArbitrationKernel` (`decide(ctx,capability,at) → ConstitutionalGovernanceDecision`), `ConstitutionalArbitrationPolicy`, `ConstitutionalDecision`, `LegitimacyEvaluator`, `GovernanceLegitimacy`. Stateless kernel producing a Decision record ("evaluates but does not create truth").
- **Evidence & Replay** — BUILT: `app/Domain/Election/Replay/ReplayEvidenceEnvelope` (`readonly`, frozen evidence, deterministic `envelopeHash`, **hashed** `voterIdentifier`), `ReplaySession`, `ReplayCertification`, `GovernanceReplayService`, `ConstitutionalReplayFingerprint`, `ScopeAwareReplayValidator`.
- **Contestation** — **GREENFIELD** (zero files for challenge/appeal/standing/dispute). Promoted by the certified landscape (S-5 standing) but unbuilt.
- **Legitimacy** — derived: `LegitimacyOutcome` enum (single resolver, not persisted) + `LegitimacyEvaluator` + several `*Legitimacy` value objects across contexts.

## 2. Core bounded contexts (defined empirically)

### BC-CORE-1 — Adjudication
- **Decision ownership:** "Is this governance decision constitutionally valid? → issue a binding **Determination**."
- **Candidate aggregate:** **Determination** (= `ConstitutionalGovernanceDecision` / `ConstitutionalDecision`). Domain service: `ConstitutionalArbitrationKernel`. Policy: `ConstitutionalArbitrationPolicy`.
- **Build status:** **BUILT** (kernel + policy + decision record). **Gap:** vocabulary — `ConstitutionalDecision`/`ConstitutionalGovernanceDecision` → canonical **"Determination"**; `GovernanceLegitimacy`/`ConstitutionalLegitimacy`/`LegitimacyOutcome` proliferation → align to **"Legitimacy" (read model)**.
- **Certified mapping:** Adjudication seam; produces **Finality** (Determination); **Legitimacy is derived here** (not an aggregate — confirmed by `LegitimacyOutcome` single resolver).

### BC-CORE-2 — Evidence & Replay
- **Decision ownership:** "Was evidence integrity preserved? Does replay reproduce the original outcome?"
- **Candidate aggregates:** **EvidenceEnvelope** (immutable System of Record) · **ReplaySession** (aggregate root) · **ReplayCertification**.
- **Build status:** **BUILT (strong).** Immutability, deterministic hashing, schema-versioned, hashed voter identifier — matches the certified Evidence primitive exactly.
- **Certified mapping:** Record-Keeping seam (authoritative side); System of Record; feeds Adjudication via Published Language (`ReplayEvidenceEnvelope`). **Anonymity preserved** (hashed identifier — Q7 Pass, *verify hash non-reversible*).

### BC-CORE-3 — Contestation  ⭐ greenfield
- **Decision ownership:** "Does a party **with standing (S-5)** raise a valid **Challenge** against a Determination, and route it for review?"
- **Candidate aggregate:** **Challenge** (raised by a standing-holder; lifecycle Raised→Admitted/Dismissed→Routed-to-Adjudication).
- **Build status:** **GREENFIELD** — nothing exists. This is the **missing entry point of the correction loop** (the certified S-5 standing → detection→challenge→correction).
- **Certified mapping:** Contestation seam (promoted in `Round47-01`). Upstream Customer-Supplier into Adjudication (`Round48-01`).

## 3. ADQC scorecard (Core bounded contexts)

| Criterion | Verdict | Note |
|-----------|---------|------|
| Q1 Semantic fidelity | **Concern** | code uses `ConstitutionalDecision`/multiple `*Legitimacy` names → align to Determination / Legitimacy (Vocabulary) |
| Q2 Ownership consistency | **Pass** | Adjudication owns Determination; Evidence&Replay owns the envelope/session; clean single-writers |
| Q3 Autonomy | **Pass** | Arbitration kernel is stateless; Replay is self-contained |
| Q4 Cohesion | **Pass** | each context = one decision ownership |
| Q5 Coupling | **Pass** | Evidence→Adjudication via immutable PL; Contestation→Adjudication C/S |
| Q6 Traceability | **Pass** | built against Release v1.0 / Landscape v1.0 |
| Q7 Anonymity (gating) | **Pass** | envelope uses hashed voter id; no linkage — *verify hash non-reversible* |
| Q8 Evolutionary stability | **Concern** | **AI-1** dual code homes (`app/Domain/Election` vs `app/Contexts/*`) → consolidation needed |
| Q9 Certification compliance (gating) | **Pass** | Contestation is admitted/promoted; no Forbidden Transformation; Legitimacy stays a derived read model |

**No gating failure.** Core design is **validated by existing code** (Adjudication, Evidence&Replay) and has one clear **greenfield gap (Contestation)**.

## 4. Items raised (architecture + naming — NOT governance)

- **AI-1 (architecture debt):** two code homes for Core concepts (`app/Domain/Election/Replay` vs `app/Contexts/Membership/.../Constitutional` + `app/Contexts/Governance`). Decide the authoritative layout (the `app/Contexts/*` bounded-context structure is the DDD-aligned target) → a **consolidation/cleanup decision** for Round 50, not a governance change.
- **NM-1 (naming hygiene, TA-4 class):** `*Legitimacy`/`*Decision` proliferation across Governance/Membership/Election → converge to Canonical Vocabulary (**Determination**, **Legitimacy** read model). No governance change; a Vocabulary-guided refactor.
- *(No GI raised — these are software-side, not governance.)*

## 5. Verdict & next — the prototype target

The Core correction loop is **2/3 built and confirms the certified design**; **Contestation is the greenfield gap.** Per the recommended sequence (prototype one Core context to validate governance→software translation in practice), **Contestation is the highest-value prototype**: it is Core, certified-promoted, currently absent, and **building it closes the correction loop** (standing → challenge → adjudication → determination → correction).

```
48-01 Context Map ✓ → 49-01 BC Discovery (Core, empirical) ✓
   → LIT-3 (validate map/contexts vs DDD lit)  — optional, can run parallel
   → PROTOTYPE: Contestation bounded context (greenfield, closes the loop)  ← recommended next
   → reconcile AI-1 (consolidate app/Domain vs app/Contexts) + NM-1 (vocabulary refactor)
   → Round 50 Aggregate design (Determination · EvidenceEnvelope/ReplaySession · Challenge)
```

---

*Round 49-01 — Bounded Context Discovery: Core Correction Loop — ISSUED (empirical; built against Release v1.0 / Landscape v1.0).*
*Adjudication BUILT (ConstitutionalArbitrationKernel→Determination; Legitimacy derived) · Evidence&Replay BUILT (immutable hashed envelope = System of Record) · Contestation GREENFIELD (S-5 entry; closes the loop). ADQC: no gating failure; Q1/Q8 concerns (vocabulary NM-1, dual-home AI-1). Recommended next: PROTOTYPE Contestation. Anonymity preserved (hashed id, verify).*
