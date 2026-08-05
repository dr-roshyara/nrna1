# `ContestedOutcomeRef` — Completeness and Allocation Verification

**Date:** 2026-08-03 · **Prepared by:** Recording Architect
**Two commissions, answered together because the second overturns the first's consequence.**
**Status:** verification only. **No architecture redesigned · no work package proposed · no ownership recommended.**

---

> # ⛔ **R-75's adopted path is prohibited by accepted architecture.**
>
> **Publishing `ChallengeRaised` is not unallocated work. It is EXPLICITLY EXCLUDED — by ADR-UL-01, by WP-5's accepted keystone, and by a live test that fails the moment it is done.**
>
> **The Board adopted that option on an option description I wrote, and my description did not disclose this. The error is mine, and it is a preparation failure, not a decision failure.**

---

## Part 1 — Completeness: is there any other source for `ContestedOutcomeRef`?

**Exhaustive sweep of every touchpoint in `app/`. For a challenge that has NOT yet been determined:**

| Candidate source | Result |
|---|---|
| **Repository** — `DeterminationRepository` | ⛔ **5 methods**: `nextIdentity` · `save` · `get` · `find` · `findByChallengeRef`. **None yields the ref for an undetermined challenge** |
| **Aggregate state** — `Determination::contestedOutcome()` | ⛔ **circular by INV-B1.** The aggregate holds it, but `issueDetermination()` throws `DeterminationAlreadyIssued` if one exists — **so the only determination that could supply it is the one being created** |
| **Persistence** — `DeterminationMapper` reads `contested_election_id` etc. | ⛔ same circularity: it reads **rows of already-issued determinations** |
| **Process state** — `AdjudicationProcessState` | ⛔ no such field; `openFor(ChallengeRef)` takes **identity alone** |
| **Inbound message** — `ChallengeRouted` payload | ⛔ `challengeId · routedTo · occurredAt` |
| **Reconstruction from primitives** (ADR-T16) | ⛔ requires `electionId`, `type`, `targetId` — **none of the three reaches Adjudication** |
| **Existing lookup / read model** | ⛔ none exists; a synchronous read of Contestation would contradict **TP-1** |
| **Published language** | ⛔ `DeterminationIssued` **carries** it — **outbound, Adjudication's own product.** The hydrator, adapter and Election's consumer are all downstream |

> ### ✅ Certified
>
> **No repository, aggregate state, process state, reconstruction path, lookup or published language provides `ContestedOutcomeRef` to Adjudication for an undetermined challenge.** **Every holder in the codebase is either Contestation-side or downstream of the issuance being constructed.**
>
> **Wording as the Board required: there is *no currently identified authoritative source*.** The sweep was exhaustive over `app/`; **exhaustive over the repository is what it claims, not exhaustive over what could be built.**

## Part 2 — Allocation: is publication of `ChallengeRaised` genuinely unallocated?

**Determination: outcome 3 — EXPLICITLY EXCLUDED.** *(Not 1, not 2, not 4.)*

| Artifact | What it says |
|---|---|
| **ADR-UL-01** (accepted) | *"**Carried by:** `ChallengeRaised` (Contestation — ***internal* domain event**, evolved in place) · **Published through:** `DeterminationIssued` payload **schema version 2** — the cross-context integration event"* |
| **ADR-PL-01** (accepted) | *"`ChallengeRaised` (Contestation) — evolved in place to carry `ContestedOutcomeRef`… **internal domain event — no version**"* |
| **WP-5 plan** (accepted) | *"after a full `raise → admit → route` sequence, **exactly one** outbox row exists and it is `ChallengeRouted`. This makes `ChallengeRaised`/`ChallengeAdmitted` **INTENTIONALLY UNPUBLISHED, not merely currently unmapped** — so a future engineer cannot silence an exception by adding a mapping"* |
| **Live test** | `tests/Feature/Contexts/Contestation/ChallengeRaisePathTest.php:153` — `assertCount(1, $rows, 'routing must write exactly one outbox…')` |

> **The exclusion was written to stop exactly what R-75 now directs: someone adding an outbox mapping for `ChallengeRaised`.** **It is not an omission anyone forgot to allocate. It is a decision, defended in prose and guarded by an assertion.**

### The gap the ADRs leave open

**ADR-UL-01 lists Adjudication as a consumer — *"a Determination inherits the Challenge's `ContestedOutcomeRef`"* — and names the publication path as `DeterminationIssued`, which is Adjudication's own OUTPUT.**

> **So the accepted architecture asserts that Adjudication holds the value, and specifies no inbound path by which it arrives.** **That is the actual architectural gap, and it predates this session by weeks.**

## Part 3 — What this means for R-75, stated without proposing a remedy

**R-75 is not wrong as an ownership decision** — Contestation does own the concept, and ADR-UL-01 says so. **What is unavailable is the adopted PATH.**

**Implementing R-75 as written would require:** amending **ADR-UL-01**'s classification of `ChallengeRaised` from *internal* to *published* · amending **ADR-PL-01** · overturning **WP-5's accepted keystone** · and **modifying a passing test that exists specifically to prevent this change.**

**Architecture proposes none of that, and does not propose an alternative path either.** **The three unchosen candidates from the decision package remain on the record; whether one of them, or an ADR amendment, is the way forward is the Board's.**

## Part 4 — My error, recorded

**The decision package described the `ChallengeRaised` option as *"a new published event; the outbox maps only Routed/Adjudicated/Resolved today"* and noted the catalog lists it with producer Contestation.**

**It did not say that two accepted ADRs classify the event as internal, that an accepted plan calls it *intentionally unpublished*, or that a live test enforces the exclusion.** **I checked what would have to be built and not what forbade building it.**

> **The Board decided correctly on the description it was given. The description was incomplete, and the option that looked cheapest was the one blocked by the most accepted architecture.**

---

**Traceability:** `docs/adr/ADR-UL-01-ContestedOutcome.md` · `docs/adr/ADR-PL-01-DeterminationIssued-v2.md` · `.claude/plans/WP-5-raise-path.md` §"Only integration events are published to the outbox" · `tests/Feature/Contexts/Contestation/ChallengeRaisePathTest.php:153` · `app/Contexts/Adjudication/Domain/Repository/` · `.../Domain/Determination/Determination.php` · `.../Infrastructure/Persistence/DeterminationMapper.php` · `.../Application/Process/AdjudicationProcessState.php` · `app/Contexts/Contestation/Infrastructure/Outbox/ChallengeOutboxAdapter.php` · **R-75 · R-73 · R-74 · R-76 · TP-1 · ADR-T16 · INV-B1** · `engineering/verification/commissions/2026-08-02-issuance-input-ownership-decision-package.md` (**the incomplete option description**). **Verification only — no ADR amended, no test modified, no path proposed, no ruling issued.**
