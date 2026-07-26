# DDD Tactical Governance Principles

**Status:** FROZEN (ARB, 2026-07-26) — changes require explicit ARB review; evolution by succession, never retroactive editing. *(Filename `DDD_PRINCIPLES.md` retained as the stable pointer target; the title carries the tactical qualifier per ARB editorial refinement.)*

## Scope

**These principles govern tactical DDD design** — aggregates, responsibilities, invariants, value objects, events, commands, repositories, domain services. They do **not** prescribe: Strategic DDD · Bounded Context discovery · Context Mapping · team organization / Conway's Law · Event Storming · Ubiquitous Language discovery. Those are governed by separate strategic guidance (the frozen Tactical DDD methodology's *entry* stages and the strategic-design charters).
**Applicability (ARB-corrected scope):** *These principles are constitutional for tactical DDD activities within this project. The AI Engineering Platform itself remains methodology-agnostic and applies these principles only when the active project or task declares DDD governance.* They do not bind non-DDD work (infrastructure scripts, UI styling, data notebooks, documentation).
**Nature:** a **methodology module**, not platform architecture — the platform *enforces* these when DDD governance is active; it is not *defined by* them (VS Code doesn't know DDD; the DDD extension does).
**Canonical home:** this document. `.claude/MEMORY.md` carries a one-line pointer (hints, never the rule text — the same consolidation discipline as ES-001..006). The EPIC-004 artifacts where each principle was minted are its **provenance** — historical records, cited below, never edited.
**Promotion status:** Product-tier governance. Per the track's own self-governance rule (*"principles are promoted only on repeated evidence and explicitly NOT promoted when evidence is domain-specific"*), promotion to the Engineering Platform (ES/pattern tier) is a future ES-006.1 crossing, gated on evidence from **more than one bounded context**. Today's evidence base is one context (Determination, Adjudication) — sufficient to freeze here, not yet to generalize.

---

## 1. Methodological Fitness Rule

> **A criterion that never rejects or modifies a candidate over the lifetime of the methodology is presumed ceremonial until evidence shows otherwise.**

**Purpose:** every evaluation criterion must have demonstrated discriminative power.
**Companion — the canonical acceptance chain:** `business evidence → obligation → decision → consistency → alternatives eliminated → removal test → protected domain truth → responsibility accepted` — a closed epistemic loop: the protection target is the positive form of the removal-test sentence; aggregates protect **properties, not objects**; a Business Truth is *implemented by* Business Invariant(s).
**Demonstrated:** the ownership criterion rejected R-3 out of the Determination aggregate; the Q-1 binding constraint cut R-5's validity half; classification pre-excluded coordination responsibilities; Criterion 7 (Business Decision Authority) rejected `IssuedAt` and `RulingContent`.
**Applies to:** any evaluation — aggregates, responsibilities, invariants, VOs, events, commands, repositories, services, and the acceptance criteria themselves.
*Provenance: EPIC-004D..K (the "healthy-criterion rule").*

## 2. Aggregate Protection Principle (APP)

> **An aggregate exists to protect domain properties (finality, uniqueness, attribution, authenticity, integrity), not merely to encapsulate domain objects — objects are the vehicle; protected properties are the purpose.**

**Purpose:** kills the anti-pattern "we have an entity, therefore we need an aggregate."
**Companion litmus (invariants):** *would a domain expert recognize the rule as business, even if the software didn't exist?* — implementation constraints are not domain invariants.
**Demonstrated:** Determination accepted as aggregate because it protects finality and uniqueness; "AdjudicationProceeding" resolved to Process Manager because no protected business truth demanded an aggregate.
*Provenance: EPIC-004E.*

## 3. Value Object Derivation Principle (VODP)

> **A Value Object is justified only when it strengthens the expression, validation, or protection of one or more accepted business invariants. Convenience grouping or data packaging alone is insufficient.**

**Purpose:** prevents technical VOs derived from data structures. Derivation direction: truth → invariant → concepts needed — never fields-that-travel-together.
**Companion (Domain Events — artifact-№6 entry condition):** *a Domain Event must trace to accepted invariants AND represent a business-significant occurrence* — litmus: would a domain expert describe it as something that happened in the business, regardless of implementation? Never method-executed → event.
**Demonstrated:** `DeterminationState` accepted (expresses lifecycle position, strengthens invariants); `IssuedAt` rejected (timestamp wrapper, no invariant strengthened).
*Provenance: EPIC-004F, EPIC-004G.*

## 4. Architectural Silence Principle (ASP)

> **The absence of an architectural element is a decision, not a default. Rejected candidates and deliberate non-events shall be recorded together with their rationale and reversal conditions when appropriate.**

**Purpose:** "why isn't there an X?" must have an answer already on the record.
**Demonstrated:** `DeterminationFinalized` rejected with a recorded, **armed** reversal condition (activation evidence: a *designed* retention consumer); the R-3 set-level invariant recorded out of the aggregate with its correct owner named.
*Provenance: EPIC-004G; Q-2 Resolution Package.*

## 5. Artifact Derivation Principle (ADP)

> **Every tactical artifact derives from the immediately preceding frozen artifact. New concepts may not bypass the derivation chain without explicit ARB authorization.**

**Canonical Tactical Derivation Chain** *(this organization's tactical methodology — not a claim about universal DDD process)*: `Aggregate → Responsibilities → Protected Domain Truths → Business Invariants → Value Objects → Domain Events → Commands → Repositories → Domain Services.`
**Companion (commands):** *business occurrence → business intention → command; never public-method → command.*
**Companion vocabulary — Emergent Design Cluster:** deferrals that cluster are symptoms of one missing concept — open them together. (Worked example, resolved 2026-07-26: FailureDeclared + DeclareFailure + EvidenceSet + R-4-expanded + loop-head request converged on the Adjudication Process Manager design; the distinct Q-2/finality center was cross-referenced, never conflated.)
*Provenance: EPIC-004G, EPIC-004H, EPIC-004K.*

## 6. Dormant Mechanism Trichotomy (DMT)

> **Dormant implementation (no caller, no expressed intention) must be classified through evidence, never intuition or default labels ("tech debt", "future work"):**
> **(1) implementation convenience meant to remain internal → evidence · (2) dead/obsolete capability → evidence · (3) missing business intention never modeled → evidence → recorded conclusion (a hypothesis, preserved as such).**

**Purpose:** prevents unexplained implementation being waved off by default.
**Companion insight (the `finalize()` case):** a missing intention may prove to be a **temporal business policy** (window-expiry → policy → action), in which case the corresponding COMMAND may never exist — the realization shape is decided when the owning question resolves, not presupposed by the reversal condition. (Q-2 resolved exactly this way: finality = temporal business policy; no `FinalizeDeterminationCommand`.)
*Provenance: EPIC-004H; Q-2 Resolution Package.*

## 7. Repository Minimal Surface Principle (RMSP)

> **A repository exposes only the operations required to preserve, reconstitute, or enforce accepted aggregate invariants. Query convenience belongs to read models, not aggregate repositories.**

**Purpose:** prevents query-zoo repositories. A repository exists because protected truths must survive time and process boundaries — persistence is the mechanism, not the reason.
**Companions:** *reconstitution is not an occurrence* (loading state is never a business event) · Domain-Service entry condition: *a Domain Service exists only when a business operation cannot naturally belong to a single Aggregate while preserving the participating aggregates' truths.*
**Demonstrated:** `save()`, `findByChallengeRef()` accepted (invariant enforcement); `findByAuthority()`, `listByOutcome()` rejected to read models.
*Provenance: EPIC-004I, EPIC-004J.*

---

## Application in AI-assisted development (when DDD governance is active)

1. Before creating any tactical artifact — confirm the derivation chain (ADP).
2. Before accepting any candidate — apply the relevant principle(s); criteria that never reject are suspect (Fitness Rule).
3. Before rejecting any candidate — record rationale and reversal conditions (ASP).
4. Before evaluating existing implementation — classify dormant mechanisms through evidence (DMT).
5. Before designing a repository — minimal surface only (RMSP).

## Related, deliberately NOT absorbed here (rules live once)

ARB Discriminator Principle · the frozen Tactical DDD methodology sequence · the four constitutional policies · Responsibility Traceability Rule — each has its own home (`.claude/MEMORY.md` hints → EPIC-003/004 records). This document hosts exactly the seven reusable principles and their companions, nothing more.

---
*Traceability: minted across EPIC-004D..K (Determination tactical chain, FROZEN baseline) · ratified as permanent governance by the ARB 2026-07-26 · consolidated to this canonical home per the integration commission (2026-07-26), applying the ARB's platform/methodology separation: the platform enforces, the module defines. Prior canonical text in `.claude/MEMORY.md` replaced by a pointer (the same MEMORY-to-standard consolidation as ES-001..006, F-OQ2 class).*
