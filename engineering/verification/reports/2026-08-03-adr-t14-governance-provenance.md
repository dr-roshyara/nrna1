# Governance Provenance Commission — ADR-T14

**Date:** 2026-08-03 · **Prepared by:** Recording Architect
**Question:** **did the programme already decide this?** Was ADR-T14 superseded, amended, narrowed, implicitly replaced, or is it still fully normative?
**Status:** ✅ **CONSIDERED — R-77 (ARB, 2026-08-03): the inconsistency characterization is NOT ADOPTED and ADR-T14 is NOT SUPERSEDED.** **⛔ And one clause of the option text I offered was expressly declined: I wrote that *“the readiness review's tick was not wrong.”* THE EVIDENCE DOES NOT SUPPORT THAT, AND I SHOULD NOT HAVE OFFERED IT — §4 of this report argues the review's scope was NARROWER than the ADR's full behaviour, which is not the same as the review being correct. **The Board will not declare the review fully correct without examining the review METHODOLOGY, and §6 Q4 remains OPEN.**
**Status:** provenance verification only. **No remedy · no ADR recommended · no transport path · no ownership · no implementation advice.**

---

## 1. Executive Summary

> ### ADR-T14 was never superseded, never narrowed, and never withdrawn. Its read path was never implemented — and an accepted readiness review certified it ✅ anyway, on half of what it says.
>
> **Classification: GOVERNANCE OMISSION.**

**Three findings carry it:**

1. **No supersession exists** — and the log proves the programme knows how to record one: **ADR-T23 says *"SUPERSEDES ADR-T17."*** **Nothing says that of ADR-T14.**
2. **My earlier explanation was wrong and is withdrawn.** I wrote that the implementation *"followed the later, stricter ADR."* **ADR-T2 and ADR-T14 are in the same accepted log, dated the same day — 2026-06-26. There is no seniority between them.**
3. **The method ADR-T14 names as its verification step exists and is orphaned.** `Challenge::canProceedToAdjudication()` was built **2026-06-27**, one day after the log, and **has no production caller** — only unit tests. **The read path was prepared and its caller never written.**

## 2. Governance Timeline

| Date | Event | Evidence |
|---|---|---|
| **2026-06-26** | **ADR-T1, ADR-T2/TP-1, ADR-T14, ADR-T16 adopted together** in one accepted log. *"Status: Accepted unless noted."* | `ADR-T-LOG-Tactical-Implementation.md` header |
| **2026-06-27** | **`Challenge::canProceedToAdjudication()` built** — *"pure query, TDD-first"* — the exact check ADR-T14 prescribes | commit `5608f01d3` |
| *(never)* | **A production caller for it** | `grep`: callers are `ChallengeTest` only |
| **2026-07-08** | **ADR-PL-01 ratified, citing ADR-T14 as authoritative:** *"Determination inherits the Challenge's `ContestedOutcomeRef` (**ADR-T14**: Challenge read-only during `IssueDetermination`)"* | ADR-PL-01 |
| *(later)* | **`CoordinatesAdjudication::issueDetermination(IssueDeterminationCommand)`** — takes a command; **loads no Challenge** | implementation |
| *(later)* | **Accepted readiness review records ✅** for *"50-08 one-aggregate-per-txn / ADR-T14"*, evidenced as *"`CoordinatesAdjudication` writes only `Determination`; Challenge read-only"* | `Architecture_Release_1.1_Readiness_Review.md:19` |
| **2026-08-03** | The consequence surfaces: `ContestedOutcomeRef` has no inbound path | this session |

## 3. Evidence Matrix

| Q | Finding | Evidence |
|---|---|---|
| **1. Explicitly superseded?** | ⛔ **No.** The only supersession in the log is **ADR-T23 → ADR-T17**. No entry supersedes, replaces or withdraws ADR-T14, and no ruling in the register does either | ADR-T log · rulings register sweep |
| **2. Implicitly narrowed?** | ⛔ **No.** No later ADR redefines a subset of its behaviour. **The opposite happened: ADR-PL-01 REAFFIRMED it** twelve days after the orphaned method was built | ADR-PL-01 |
| **3. Which is newer?** | **Neither. Same log, same date (2026-06-26).** **ADR-T2 and ADR-T14 were adopted simultaneously**, so chronology cannot resolve the tension and **cannot excuse the implementation choice either** | ADR-T log header |
| **4. When did implementation stop following it?** | **It never started.** The prescribed query was built on 2026-06-27 and **no production code ever called it.** **Accompanied by: no ADR, no ruling, no commission, no approval — nothing** | commit `5608f01d3` · grep |
| **5. Provenance** | **GOVERNANCE OMISSION** — see §5 | — |

## 4. Provenance Analysis

**The omission has a specific shape, and it is worse than silence.**

**ADR-T14 states two things:** *(a)* Adjudication **loads the Challenge and reads it**; *(b)* Adjudication **never writes the Challenge**.

> **The accepted readiness review checked (b) and recorded ✅ for the whole ADR.** Its evidence column — *"`CoordinatesAdjudication` writes only `Determination`; Challenge read-only"* — **is satisfied trivially by a service that never touches the Challenge at all.**
>
> **"Challenge read-only" is true of code that does not read it. The check could not distinguish compliance from absence.**

**So the programme did not decide to abandon the read path. It recorded that ADR-T14 was satisfied while half of ADR-T14 was unimplemented** — and then, in ADR-PL-01, **built a consumer contract on the unimplemented half.**

**Nothing in this is engineering's error, and nothing in it is a documentation lapse: the ADR is written clearly, the code is written clearly, and the review that connected them asked a question too narrow to see the gap.**

## 5. Classification

> ## **Governance omission**

**Not *explicit supersession*** — no such record exists, and the log shows the form one takes.
**Not *implicit supersession*** — ADR-PL-01 reaffirmed ADR-T14 after the divergence began.
**Not *recorded refinement*** — nothing narrows it.
**Not *no evidence*** — there is abundant evidence; it points to an omission rather than a decision.

## 6. Open Questions for the ARB

1. **ADR-T14 remains fully normative on the record. Is that the intended state?**
2. **Its read path was never implemented and never withdrawn. Which of the two should change?** *(Architecture does not answer this.)*
3. **ADR-PL-01's consumer contract rests on ADR-T14's read path. If that path is not restored, does the contract require a corrective governance act?**
4. **The readiness review recorded ✅ for an ADR half-implemented. Does that verification method need a corrective act of its own — and are other ADRs certified the same way?**
5. **ADR-T2 and ADR-T14 were adopted the same day. Was the tension visible then, and if so, where was it resolved?**

### Architecture impact — stated, not remedied

**The programme lacks governance traceability for ADR-T14: an accepted ADR is unimplemented in part, with no record of a decision to leave it so, and an accepted review recording that it was satisfied.**

**A corrective governance act is required to establish which mechanism governs the issuance interaction. Architecture does not propose which.**

---

**Traceability:** `docs/adr/ADR-T-LOG-Tactical-Implementation.md` (header date · **ADR-T1 · T2/TP-1 · T14 · T16 · T23's supersession of T17**) · `docs/adr/ADR-PL-01-DeterminationIssued-v2.md` · `docs/implementation/Architecture_Release_1.1_Readiness_Review.md:19` · `docs/implementation/Implementation_Readiness_Audit.md` §E · `app/Contexts/Contestation/Domain/Challenge/Challenge.php:160` · `app/Contexts/Adjudication/Application/Service/CoordinatesAdjudication.php` · commit **`5608f01d3`** (2026-06-27) · `engineering/verification/reports/2026-08-03-contestedoutcomeref-architecture-consistency.md` (**whose "later, stricter ADR" explanation this report withdraws**). **Provenance verification only — no remedy, no recommendation, no ADR proposed.**
