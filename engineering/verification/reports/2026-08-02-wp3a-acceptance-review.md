# WP-3A — Acceptance Review

**Date:** 2026-08-02 · **Prepared by:** Principal Architect / DDD Steward / Recording Architect
**Purpose:** determine whether WP-3A's implementation satisfies its authorized scope and preserves the approved architecture.
**Status:** **RECOMMENDATION ONLY — no ruling issued. Acceptance is the ARB's act (R-34).**

> **Engineering ended at VERIFY on 2026-07-30. Triple qualification was performed 2026-08-02** (`.claude/plans/WP-3-challengerouted-published-language.md` §Triple Qualification). **This review consumes that evidence rather than reproducing it.**

---

## 1. What WP-3A delivered

**`ChallengeRouted` becomes published language.** Three components: `ChallengeRoutedHydrator` (registration half) · `ChallengeOutboxAdapter::writeRouted()` (publication half, schema v1) · registration in `ContestationServiceProvider::boot()`.

**`ChallengeRouted` itself is unmodified — WP-3A publishes an event that already existed.**

## 2. Strategic DDD Verification

| Concern | Result |
|---|---|
| Bounded-context ownership | **unchanged** — Contestation owns the event and its producer-side Infrastructure |
| Capability ownership | **unchanged** |
| Context-map relationships | **a Published Language relationship is completed, not created** — ADR-T21 declared it; this slice realizes it |
| Ubiquitous Language | **unchanged** — no term renamed, introduced or redefined |
| Strategic invariants | **preserved** — anonymity (ADR-T11) pinned by two tests; one-mint-per-conversation (ADR-MP-06) untouched, provenance supplied not minted |

## 3. Tactical DDD Verification

| Evidence | Architectural conclusion |
|---|---|
| **Deptrac 0 violations** | dependency direction preserved; **no cross-context import** |
| No aggregate, entity, repository or domain service added; the domain event unmodified | **the tactical model is extended at the edge only** — Infrastructure gained a hydrator and a mapping |
| Reconstructed event carries **exactly three facts** | **PB-005 F-2 honoured** — enrichment stays in the Application layer |
| Registration in the service provider | composition root, not the domain |

## 4. Engineering Verification — accepted as reported, and re-verified

| | |
|---|---|
| Tests | **11/11 GREEN**, re-run 2026-08-02 |
| `composer merge-gate` | **PASS** — 266 tests · 665 assertions · 101 pre-existing risky notices |
| Developer guide (DoD) | `developer_guide/contestation/05_*` + index row |
| **Triple qualification** | ✅ **Architecture · DDD · Trustworthiness — all PASS** (performed 2026-08-02) |

**RED was genuine and two Phase-15 diagnoses returned *"incorrect test"* — including one where the corrected assertion is stronger than the draft** (a chain start has `causationId = null`, and that is ADR-MP-06 behaving correctly).

## 5. ⚠️ One item the ARB must dispose of before or with acceptance

> **`Canonical_Event_Catalog_v1.0.md` is 🧊 FROZEN and still marks `ChallengeRouted` — with `ChallengeAdjudicated` and `ChallengeResolved` — as `internal`.**
>
> **The code publishes the event. The canonical registry records it as internal.** Correcting the marking requires a **v1.1 catalog — an ARB act**. WP-3A recorded the staleness and correctly did not edit a frozen artifact, following the precedent PB-005 set for two of these same events.

**Two readings are available and architecture does not choose between them:**

| Reading | Consequence |
|---|---|
| **Documentation lag** | accept WP-3A; record the catalog correction as follow-up governance |
| **The Published Language contract is affected** | the marking is part of the contract, and acceptance should wait on v1.1 |

**This is a governance question about the authority of a frozen artifact, not an engineering defect.**

## 6. Classification

| Finding | Classification |
|---|---|
| Hydrator + publication mapping | **Engineering** |
| Frozen catalog marks a published event `internal` | **Governance** — undisposed since 2026-07-30 |
| The slice's authorization is recorded in the plan and session log, **not in the rulings register** | **Governance — recording gap.** Same class R-62 corrected for R-43/R-48 |

**No finding is classified as Architecture — none was observed.** **No PKS or KnowledgeOS classification: single occurrences.**

## 7. Decision Matrix

| Decision question | Result | Evidence |
|---|---|---|
| Authorized scope implemented? | ✅ | plan §GREEN — every acceptance-criteria row has its evidence |
| Architecture preserved? | ✅ | §2–3 |
| Definition of Done complete? | ✅ | triple qualification performed 2026-08-02 — **the gap that blocked this review is closed** |
| Unauthorized changes introduced? | ❌ | none observed |
| Outstanding items? | ⚠️ | **the frozen-catalog disposition (§5)** |

## 8. Recommendation

> **The ARB is recommended to accept WP-3A, and to dispose of the frozen-catalog marking in the same act — either as follow-up governance (documentation lag) or as a precondition (contract-affecting).**
>
> **Engineering supplied the evidence; architecture supplies this recommendation; the ARB decides.** **At no point did the party producing the work also accept it.**

**Identifier:** **not assigned here.** R-67 was reserved for *WP-4 authorization*, which `2026-08-02-wp4-state-correction.md` shows is moot. **Rulings are not minted by inference (R-34).**

---

**Traceability:** `.claude/plans/WP-3-challengerouted-published-language.md` §WP-3A GREEN · §Triple Qualification · **ADR-T21 · ADR-T5 · ADR-T16 · ADR-T20 · ADR-MP-06 · ADR-T11/AT-Q7 · PB-005 F-2 · PB-006** · `Canonical_Event_Catalog_v1.0.md` (frozen) · **R-34 · R-62** · `composer merge-gate` PASS 2026-08-02. **No architecture redesigned · no ruling issued · no engineering re-run beyond re-verification.**
