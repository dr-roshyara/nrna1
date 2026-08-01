# WP-3A — Acceptance Review

**Date:** 2026-08-02 · **Prepared by:** Principal Architect / DDD Steward / Recording Architect
**Purpose:** determine whether WP-3A's implementation satisfies its authorized scope and preserves the approved architecture.
**Status:** ✅ **ACCEPTED — R-67 issued by the ARB, 2026-08-02.** The recommendation below was adopted **without condition**; the ruling is recorded in the register.

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

## 5. ⛔ WITHDRAWN — the "frozen catalog discrepancy" does not exist

**This section previously asked the ARB to choose between *documentation lag* and *contract-affecting*. Both options rested on a misreading of the catalog. The question dissolves.**

### What the column actually is

**`Canonical_Event_Catalog_v1.0.md` has no `visibility` column.** Its columns are:

```
| Event | Producer (only) | Classification | Stability | Security | SchemaVer |
```

**`internal` is a value of the SECURITY column**, whose other value is `restricted`. It is a **data-sensitivity classification**, not a publication marking. `restricted` is carried by `VoteAccepted`, `EvidenceRecorded` and `DeterminationIssued` — the vote, evidence and determination data. **`internal` is what everything else carries.**

**The proof that the column cannot mean "not published": `ElectionCorrectionApplied` is classified `Integration` — the catalog's own legend defines that as *"cross-context reaction"* — and it is marked `internal`.** **A column that marks an explicitly cross-context event `internal` is not recording publication status.**

### The authoritative contract already authorizes exactly what was built

**Round50-05 is the authoritative payload/delivery/security contract** (the v1.0 catalog names it as such). For `ChallengeRaised…Resolved`, which includes `ChallengeRouted`:

| | Round50-05 §2 |
|---|---|
| Security | **internal** |
| **Consumers (allowed)** | **AdjudicationService · Audit · NOT Voting** |

**And §1 gives the payload as `challengeId · routedTo · at` — exactly the three fields WP-3A publishes.**

> **The frozen artifact does not contradict the implementation. It specifies it, and it names Adjudication as an authorized consumer — which is precisely what WP-4 then built.**

### What remains — smaller, real, and not a blocker

**The catalog records no published-language status at all.** ADR-T21 declares `ChallengeRouted` published language; **the registry has no column for that dimension**, so the declaration has no counterpart there. **That is a gap in what the registry records, applying to the whole catalog — not a discrepancy about three events, and not a reason to withhold acceptance.**

### Provenance of the error

**The misreading originates in WP-3A's own plan (2026-07-30, finding 3), which called it *"its `visibility` marking"* — a column name the artifact does not have.** **I carried that finding forward into this review, CONTEXT and the session log without opening the catalog.** **Same pattern as the WP-4 header: I quoted a claim about an artifact instead of reading the artifact.**

**No ARB disposition is required. No v1.1 catalog is required on this ground.**

## 6. Classification

| Finding | Classification |
|---|---|
| Hydrator + publication mapping | **Engineering** |
| ~~Frozen catalog marks a published event `internal`~~ | ⛔ **WITHDRAWN (§5)** — the `Security` column was read as a visibility marking. **No finding** |
| The catalog records no published-language status for any event | **Documentation** — a registry gap, not a defect of this slice |
| The slice's authorization is recorded in the plan and session log, **not in the rulings register** | **Governance — recording gap.** Same class R-62 corrected for R-43/R-48 |

**No finding is classified as Architecture — none was observed.** **No PKS or KnowledgeOS classification: single occurrences.**

## 7. Decision Matrix

| Decision question | Result | Evidence |
|---|---|---|
| Authorized scope implemented? | ✅ | plan §GREEN — every acceptance-criteria row has its evidence |
| Architecture preserved? | ✅ | §2–3 |
| Definition of Done complete? | ✅ | triple qualification performed 2026-08-02 — **the gap that blocked this review is closed** |
| Unauthorized changes introduced? | ❌ | none observed |
| Outstanding items? | ❌ | **none.** The frozen-catalog item is **withdrawn** (§5); the authoritative contract specifies this event and authorizes Adjudication as its consumer |

## 8. Recommendation

> **The ARB is recommended to accept WP-3A. No condition attaches.** ✅ **ADOPTED — R-67 issued 2026-08-02.**
>
> **The catalog disposition this review previously asked for is withdrawn — there was nothing to dispose of.**
>
> **Engineering supplied the evidence; architecture supplies this recommendation; the ARB decides.** **At no point did the party producing the work also accept it.**

**Identifier:** **R-67.** The reservation of R-67 for *WP-4 authorization* lapsed when `2026-08-02-wp4-state-correction.md` established that authorization is moot; the next sequential identifier was assigned to the ARB's acceptance act. **The decision is the Board's; only the number is bookkeeping, and renumbering costs one edit.**

---

**Traceability:** `.claude/plans/WP-3-challengerouted-published-language.md` §WP-3A GREEN · §Triple Qualification · **ADR-T21 · ADR-T5 · ADR-T16 · ADR-T20 · ADR-MP-06 · ADR-T11/AT-Q7 · PB-005 F-2 · PB-006** · `Canonical_Event_Catalog_v1.0.md` (frozen) · **R-34 · R-62** · `composer merge-gate` PASS 2026-08-02. **No architecture redesigned · no ruling issued · no engineering re-run beyond re-verification.**
