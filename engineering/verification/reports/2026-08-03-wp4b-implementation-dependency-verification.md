# WP-4B — Implementation Dependency Verification

**Date:** 2026-08-03 · **Prepared by:** Recording Architect
**Question:** given R-73–R-76, **is WP-4B genuinely blocked**, or does equivalent enabling infrastructure exist or admit introduction **without altering the approved roadmap**?
**Status:** verification only. **No architecture redesigned · no work package proposed · no re-sequencing recommended.**

---

> # RESULT — **of the three inferred blockers, ONE survives verification.**
>
> **My statement *"WP-4B is the last of four builds"* was over-strong. Two of the three prerequisites do not block WP-4B's construction.**
>
> **P1 (WP-4D) and P2 (Evidence) block the loop being EXERCISED in production. P3 (`ContestedOutcomeRef`) blocks WP-4B being BUILT.**

---

## P1 — `Jurisdiction` via the authority decision: **does not block**

**The Board's four questions, answered:**

| Question | Answer |
|---|---|
| **Is WP-4D the only place the authority-decision intake can exist?** | For the **port and adapter**, yes — that is WP-4D's recorded scope. **But WP-4B does not need the port.** |
| **Can the intake be introduced without completing WP-4D?** | **The question does not arise.** What R-73 requires of WP-4B is that `receiveRulingDecision()` **accept `Jurisdiction` and the concluded state retain it** — a signature and a field, **both inside `Adjudication/Application`, needing no port, no adapter and no crossing.** |
| **Does the roadmap already assume the dependency?** | **No.** Roadmap §WP-4 lists the intake port as its own item, and §WP-4B as its own — **it does not make one a prerequisite of the other.** |
| **Amendment required?** | **No.** |

**The distinction the earlier report missed:** **who CALLS `receiveRulingDecision()` in production is WP-4D's concern. What the method ACCEPTS is not.** **The seam can be built and tested against the widened entry point exactly as today's tests already invoke it.**

> ⚠️ **One scope question, flagged and not decided:** widening that signature is work — **is it WP-4B's?** Roadmap §WP-4B names *"PM conclusion txn → issuance txn"*, which arguably includes the conclusion entry point. **A scope reading, and the Board's to make if it matters.**

## P2 — `EvidenceEnvelopeRef` via an Evidence integration event: **does not block construction**

**An interim provider effectively already exists, and the deferral is pre-existing and explicit.**

| Finding | Evidence |
|---|---|
| **The value already flows into the PM as a string** | `AdjudicationProcessManager::admitEvidence(ChallengeRef, string $reference)` → `AdjudicationProcessState::admitEvidence()`. **The house test data is literally `'envelope-sha256…'`** — the admitted reference **is** the envelope hash |
| **The concluded state retains them** | `consideredEvidence(): ?EvidenceSet`, a `non-empty-list<string>` |
| **The gap is a PRODUCER, not a type** | **nothing calls `admitEvidence()` in production** — only the PM itself and tests |
| **⭐ The deferral is not new and not a consequence of R-74** | **EPIC-004K §8, an accepted artifact, already says it:** *"Evidence admission arrivals — upstream satisfaction of demands **(transport counterpart deferred; see §14)**"* |

> **R-74 named the authoritative owner of a value whose transport EPIC-004K had already deferred. It did not create a new dependency; it named the owner of an old one.**

**Consequence: WP-4B can obtain the reference from the concluded process state, which is where the adopted pipeline puts it — Evidence produces → admission records → issuance fixes.** **What is missing is the first arrow, and it was deferred before this session.** **That gap bites at WP-8's full-loop proof, not at WP-4B's construction.**

**Amendment required? No — the deferral is already recorded in an accepted artifact.**

## P3 — `ContestedOutcomeRef` via a published `ChallengeRaised`: ⛔ **this one blocks**

**No equivalent infrastructure exists, and none can be introduced without an allocation decision.**

| Check | Result |
|---|---|
| Does Adjudication hold the value anywhere? | ⛔ **no.** Its local VO exists as a **type**; nothing populates it |
| Does anything carry it across today? | ⛔ **no.** `ChallengeRouted`'s payload is `challengeId · routedTo · occurredAt`; `ChallengeRaised` **is not published** — `ChallengeOutboxAdapter` maps Routed, Adjudicated and Resolved only |
| Could the PM retain it from process open? | ⛔ **no.** `openFor(ChallengeRef)` takes identity alone, and the message it comes from does not carry the value |
| Is an interim stand-in available? | ⛔ **none identified.** Unlike P2, there is no existing field, admission path or string already flowing |

**So `IssueDeterminationCommand` cannot be constructed. This is the block, and it is the only one.**

### Is a roadmap amendment required for P3?

> **Yes — one allocation question, not three.** **Publishing `ChallengeRaised` is Contestation-side work that no current work package covers**, and roadmap §WP-3's scope was *publication of `ChallengeRouted`* — a different event. **Whether it becomes a WP-3 slice, a WP-4 slice, or its own package is an allocation decision reserved to the Board.**

**Architecture proposes none of those. It reports that the allocation is absent.**

## Corrected sequencing statement

| Earlier claim | Verified |
|---|---|
| *"WP-4D is upstream of WP-4B"* | ⛔ **not supported.** WP-4B needs a widened signature, not the port |
| *"The Evidence context must exist first"* | ⛔ **not supported for construction.** The reference already reaches the PM as a string, and the producer gap predates R-74 (EPIC-004K §8) |
| *"WP-4B is the last of four builds"* | ⛔ **withdrawn** |
| **One prerequisite blocks WP-4B: a path carrying `ContestedOutcomeRef` into Adjudication** | ✅ **verified** |

**The governance decisions R-73–R-76 stand unchanged. Only the sequencing inference drawn from them was wrong, and it was mine.**

---

**Traceability:** **R-72 · R-73 · R-74 · R-75 · R-76** · `docs/implementation/EPIC-004K_*.md` §8 (the recorded deferral) · §9 · §11 · `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-4 · `app/Contexts/Adjudication/Application/Process/AdjudicationProcessManager.php` (`admitEvidence` · `receiveRulingDecision` · `openFor`) · `.../Process/AdjudicationProcessState.php` · `.../Application/Command/IssueDeterminationCommand.php` · `app/Contexts/Contestation/Infrastructure/Outbox/ChallengeOutboxAdapter.php` · `tests/Unit/Contexts/Adjudication/Process/AdjudicationProcessStateTest.php` (`'envelope-sha256…'`) · `engineering/verification/reports/2026-08-03-wp4b-executability-determination.md` (**corrected by this report**). **Verification only — no redesign, no work package proposed, no re-sequencing recommended.**
