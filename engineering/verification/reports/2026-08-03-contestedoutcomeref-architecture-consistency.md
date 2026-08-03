# Architecture Consistency Commission — `ContestedOutcomeRef`

**Date:** 2026-08-03 · **Prepared by:** Recording Architect
**Question:** **where does the accepted architectural model become internally inconsistent regarding `ContestedOutcomeRef`?**
**Status:** verification only. **No remedy proposed · no transport path recommended · no ADR amendment recommended · no artifact declared "correct."**

---

## 1. Executive Summary

> ### The inconsistency is located, and it is not a documentation gap.
>
> **ADR-T14 (accepted) specifies the producer path explicitly: *"`AdjudicationService` **loads Challenge (read)**… never writes Challenge."*** **That is a cross-context synchronous read.**
>
> **ADR-T2/TP-1 (accepted) forbids it: *"Cross-aggregate collaboration via domain events only… no foreign repo calls."***
>
> **Two accepted ADRs APPEAR TO PRESCRIBE DIFFERENT INTERACTION MECHANISMS for the issuance interaction. NO SUPERSEDING ADR AND NO EXPLICIT PRECEDENCE DECISION HAS BEEN IDENTIFIED.** **The implementation follows one of them; `ContestedOutcomeRef` has no inbound path as a result.**

**⚠️ SOFTENED AT ARB DIRECTION.** The earlier wording — *"specify incompatible mechanisms"* — was already an architectural conclusion. **Whether this is a genuine contradiction, an intentional supersession, a refinement, or two statements at different levels of abstraction is the Board's to determine. Verification must not silently become adjudication.**

**⛔ AND ONE EXPLANATION IS WITHDRAWN: this report said the implementation *"followed the later, stricter"* ADR. IT IS NOT LATER. ADR-T2 and ADR-T14 are in the SAME accepted log, dated the SAME DAY (2026-06-26) — there is NO SENIORITY between them, so chronology neither resolves the tension nor excuses the implementation choice.** See `2026-08-03-adr-t14-governance-provenance.md`.

## 2. Evidence Matrix

| # | Accepted artifact | Statement, verbatim |
|---|---|---|
| **E1** | **ADR-UL-01** (ARB-ratified 2026-07-08) | *"**Owned by:** Contestation BC (the `Challenge` aggregate and its `ContestedOutcomeRef` VO)."* |
| **E2** | **ADR-UL-01** | *"**Consumed by:** Adjudication (a `Determination` **inherits** the Challenge's `ContestedOutcomeRef`), Election…"* |
| **E3** | **ADR-UL-01** | *"**Carried by:** `ChallengeRaised` (Contestation — ***internal* domain event**, evolved in place) · **Published through:** `DeterminationIssued` payload schema version 2 (Adjudication…)"* |
| **E4** | **ADR-PL-01** (ARB-ratified 2026-07-08) | *"`ChallengeRaised` is an **internal domain event confined to the Contestation bounded context** — it is **not part of the stable published language**."* |
| **E5** | **ADR-PL-01** | *"Determination **inherits** the Challenge's `ContestedOutcomeRef` (**ADR-T14: Challenge read-only during `IssueDetermination`**)."* |
| **E6** | **ADR-T14** | *"**Adjudication interaction = Option A: Challenge READ-ONLY** during `IssueDetermination`… **`AdjudicationService` loads Challenge (read)**, verifies `canProceedToAdjudication()`, creates+saves Determination… never writes Challenge."* |
| **E7** | **ADR-T2 / TP-1** | *"**Cross-aggregate collaboration via domain events only** (TP-1)… **no foreign repo calls**."* |
| **E8** | **ADR-T16** | *"Cross-context references are **local opaque VOs**… never import another context's aggregate types… **the id string crosses the boundary, not the type**"* — **fitness-tested** |
| **E9** | **WP-5 plan** (accepted) | *"`ChallengeRaised`/`ChallengeAdmitted` **intentionally unpublished**, not merely currently unmapped."* |
| **E10** | Implementation | `CoordinatesAdjudication::issueDetermination(IssueDeterminationCommand)` — **loads no Challenge**; the ref arrives as a command field |
| **E11** | Implementation | `ChallengeOutboxAdapter` maps Routed · Adjudicated · Resolved. **`ChallengeRaised` is not published** |
| **E12** | Test (passing) | `ChallengeRaisePathTest.php:153` — *"routing must write exactly one outbox…"* |
| **E13** | Deptrac (passing, 0 violations) | no cross-context read is permitted from `AdjudicationApplication` |

## 3. Consistency Analysis — the seven questions

| Q | Finding |
|---|---|
| **1. Ownership** | **Unambiguous and uncontested: Contestation.** E1. Adjudication holds a **local reconstruction** under ADR-T16 (E8) |
| **2. Consumer contract** | **Two accepted statements, both saying the same thing:** E2 and E5 — *a Determination **inherits** the Challenge's `ContestedOutcomeRef`*. **Both use "inherits"; neither states a mechanism except by reference to ADR-T14** |
| **3. Producer contract** | **Explicit, not implied — and this is the discovery.** **E6 names the mechanism: Adjudication LOADS the Challenge and reads it.** The producer is Contestation's `Challenge` aggregate, obtained by a **synchronous read** |
| **4. Transport contract** | see §4a |
| **5. Implementation consistency** | **CONTRADICTORY — with respect to ADR-T14, and CONSISTENT with respect to TP-1/ADR-T16.** The code (E10) does not load the Challenge; it satisfies E7/E8/E13 and violates E6. **The implementation chose one accepted ADR over another** |
| **6. Architecture consistency** | **ONE apparent divergence, between `ADR-T14` and `ADR-T2/TP-1` (+ ADR-T16) — its CHARACTER (contradiction · supersession · refinement · differing abstraction levels) is the Board's to determine.** *(A second candidate — R-75 versus E3/E4/E9/E12 — is a **consequence** of the first, not an independent conflict: R-75 selected the path it did because the ADR-T14 path had already been foreclosed in practice.)* |
| **7. Classification** | see §5 |

### 4a. Transport contract — every path, classified

| Path | Status |
|---|---|
| **Synchronous read of the Challenge during issuance** (ADR-T14) | **SPECIFIED** by E6 · **PROHIBITED** by E7/E8 · **NOT IMPLEMENTED** (E10) · **structurally blocked** (E13) |
| **`ChallengeRaised` as an integration event** | **PROHIBITED** — E3, E4, E9 classify it internal and intentionally unpublished; E12 enforces it |
| **`ChallengeRouted` carrying the ref** | **SILENT.** No accepted artifact specifies or forbids it; the payload does not carry it |
| **`DeterminationIssued` carrying the ref** | **SPECIFIED and IMPLEMENTED** (E3, ADR-PL-01) — **but OUTBOUND.** It publishes what Adjudication already knows; it cannot supply what Adjudication does not |
| **Authority-carried** | **SILENT** in accepted architecture |

## 4. Conflict Matrix

| | ADR-T14 (E6) | ADR-T2/TP-1 (E7) | ADR-T16 (E8) | ADR-UL-01 (E2/E3) | ADR-PL-01 (E4/E5) | Implementation (E10–E13) |
|---|---|---|---|---|---|---|
| **ADR-T14 (E6)** | — | ⛔ **CONFLICT** | ⚠️ tension — a read implies loading a foreign aggregate | ✅ compatible (supplies the "inherits" mechanism) | ✅ **PL-01 CITES T14** as the mechanism | ⛔ **CONFLICT** — not implemented |
| **ADR-T2/TP-1 (E7)** | ⛔ **CONFLICT** | — | ✅ | ✅ | ⚠️ inherited via E5's citation of T14 | ✅ satisfied |
| **ADR-UL-01 / ADR-PL-01** | ✅ | ⚠️ | ✅ | — | ✅ mutually consistent | ⚠️ **the consumer contract (E2/E5) is unsatisfied** |
| **Implementation** | ⛔ | ✅ | ✅ | ⚠️ unsatisfied | ⚠️ unsatisfied | — |

> **One cell carries the whole finding: `ADR-T14 × ADR-T2/TP-1`.** **Everything else in this matrix is either agreement or a downstream effect of that cell.**

## 5. Classification

> ### **ADR inconsistency** — with an implementation gap as its *consequence*, not its cause.

**Justification:**

- **Not a documentation gap:** the producer path is **stated explicitly** in an accepted ADR (E6). Nothing is missing from the record.
- **Not primarily an implementation gap:** the implementation is **consistent with the ADRs it followed** (E7, E8) and is **structurally prevented** from following E6 (E13). Engineering did not err.
- **Not a published-language inconsistency:** ADR-UL-01 and ADR-PL-01 agree with each other; `ChallengeRaised`'s internal status is stated consistently in three accepted artifacts and enforced by a test.
- **Not a roadmap inconsistency:** the roadmap allocates no work to this and contradicts nothing.
- **Not a governance inconsistency:** every artifact was properly ratified; **the defect is in what two of them say, not in how they were adopted.**
- **ADR inconsistency:** **ADR-T14 and ADR-T2/TP-1 prescribe incompatible mechanisms for the same interaction**, and ADR-PL-01's consumer contract (E5) rests on the one that cannot be executed.

**Not classified as *mixed*, deliberately: the implementation state and R-75's blocked path are both entailed by the single ADR conflict.** **Reporting them as separate inconsistencies would count one defect three times.**

## 6. Open Questions for the ARB

1. **ADR-T14 and ADR-T2/TP-1 cannot both govern the issuance interaction. Which is normative?** *(Architecture does not answer this.)*
2. **If ADR-T14 is superseded in practice, was that supersession ever recorded?** **No superseding entry was found in the ADR-T log or the rulings register.**
3. **ADR-UL-01's and ADR-PL-01's consumer contract — *"a Determination inherits the Challenge's `ContestedOutcomeRef`"* — currently has no executable mechanism. Is the contract still binding?**
4. **Does R-75 stand, fall, or await the answer to Question 1?** **Its ownership finding is independent of the conflict; its path selection is not.**
5. **Is the `ChallengeRouted`-carries-the-ref path genuinely SILENT in accepted architecture, or was it considered and rejected somewhere not surfaced by this sweep?**

---

**Traceability:** `docs/adr/ADR-UL-01-ContestedOutcome.md` · `docs/adr/ADR-PL-01-DeterminationIssued-v2.md` · `docs/adr/ADR-T-LOG-Tactical-Implementation.md` **ADR-T2 · ADR-T14 · ADR-T16** · `.claude/plans/WP-5-raise-path.md` · `app/Contexts/Adjudication/Application/Service/CoordinatesAdjudication.php` · `app/Contexts/Contestation/Infrastructure/Outbox/ChallengeOutboxAdapter.php` · `tests/Feature/Contexts/Contestation/ChallengeRaisePathTest.php:153` · `deptrac.yaml` · **R-73–R-76** · `engineering/verification/reports/2026-08-03-contestedoutcomeref-completeness-and-allocation.md`. **Consistency verification only — no remedy, no recommendation, no artifact declared correct.**
