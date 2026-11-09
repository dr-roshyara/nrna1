# Reference Geography tactical design v3.4: companion assessment

| | |
|---|---|
| **Status** | PROPOSED. Companion to `2026-11-09-reference-geography-tactical-design-baseline-v3.4.md`. Not committed |
| **Binding** | D-73 / CR-01, D-81 to D-88 |

## 1. Source verification and disagreements with the review (first)

| The review says | The accepted text says | Verdict |
|---|---|---|
| CR-01 / D-73 specify "`recordedAt` is the database clock time at commit" | **Not found in CR-01.** CR-01 §3 defines `closedThrough` (committed knowledge, inclusive; refusal beyond it). CR-01 §8 says only that taking `recordedAt` **from the application clock before commit** is a defect. **D-73 (CR-01 §9):** "the `closedThrough` guarantee proved by transaction ordering, not by moving timestamp generation" | **The review overstates the text.** The binding requirement is the D-73 sentence |
| v3.3's formula conflicts with D-73 | The phrase "not by moving timestamp generation" admits **two readings**: (R1) timestamps must not be adjusted; (R2) the proof must rest on lock ordering, not merely on relocating where the timestamp is taken. **The proposed barrier for Membership, Organisation and Governance (model document §38.2 rule 1) itself uses `max(clock_timestamp(), closedThrough + 1 µs, last + 1 µs)`** | Under R1, v3.3 conflicts, **and so does §38.2**. Under R2, neither does |
| A redesign by transaction ordering is needed | **Possible, and it satisfies both readings:** refuse, don't bump (v3.4 §6) | **Adopted.** No Principal decision is needed, because no reading of D-73 is violated |

**A related finding, not part of this review's scope:** §38.2 rule 1 should receive the same refuse-not-bump correction during the Membership tactical work, **if** D-73 is read strictly (R1). Recorded; Membership is unchanged here.

## 2. v3.3 findings

| Finding | Status |
|---|---|
| 1 D-73 compatibility | **Resolved:** `recordedAt` = the clock reading, never adjusted; completeness by the lock protocol; writes refused at or below the boundary |
| 2 Close through a requested τ | **Resolved:** already closed → confirmed; otherwise lock, clock ≥ τ check, set W = τ; future τ refused; a failed close confirms nothing; **one τ for all contexts** |
| 3 Piecewise before-images | **Resolved:** segments with value, NONE or ABSENT; partial replacement; withdrawal; **new invariant R-1** (one slot at most once per revision over overlapping time); protection; test invariant |
| 4 Dependency completeness | **Strengthened:** exact queries per invariant. **Still conditional on structural enforcement [TU]** |
| 5 Slots and before-images | Resolved for set-valued names and withdrawals |
| 6 D-88 | Unchanged |

## 3. Guarantees by source

| Domain | Database | Platform contract | Human |
|---|---|---|---|
| Slot independence; piecewise gains and losses; dispute never changes values; R-1 | `FOR UPDATE` held to commit; serialised step; **`clock_timestamp()` semantics; the literal commit time is unreadable inside the transaction**; host slewing | Every D-73 participant implements items 1–5. **Membership's §38.2 would need refuse-not-bump under R1** | Identity judgement; evidence assessments; conflicts |

## 4. Scenarios

**Key:** D = domain-level · DB = database-dependent · P = platform-contract-dependent · H = human governance.

### 4.1 The 68 earlier scenarios: changed outcomes

| Scenario | v3.3 outcome | v3.4 outcome |
|---|---|---|
| v3.3 new #1 (clock equals W) | Stamped W + 1 µs | **Refused**; succeeds once the clock passes W |
| v3.3 new #2 (clock behind W) | Stamped W + 1 µs | **Refused** until the clock passes W |
| v3.1 new #2 (clock jumps back) | Clamped | **Refused** until the clock passes max(W, last) |
| v3.3 new #4, v3.2 new #7 (backward clock across contexts) | Per-stream clamp | Per-context refusal; completeness holds in each |
| v3.3 new #3, v3.2 new #6, v3.1 N15 (shared τ) | Close through τ informally | Exact close-through-τ protocol |
| v3.3 new #5, #7, #11 (losses) | Single before-image | **Piecewise** segments |

**All others are unchanged.** Their mechanisms do not involve timestamp generation or before-image shape.

### 4.2 The 16 new scenarios

| # | Situation | Expected | Mechanism | Kind |
|---|---|---|---|---|
| 1 | A publication has read its clock and holds the lock when a close starts | The close waits for its commit; W is then set; that fact is included | Lock order | DB |
| 2 | A publication waits while a close holds the lock | After the close commits it sees W; if its clock ≤ W it is **refused**, otherwise stamped with its clock | Refusal rule | DB |
| 3 | A close rolls back | W unchanged; **not confirmed**; queries beyond the old W are still refused | Transaction | DB |
| 4 | A publication commits after a successful close | `recordedAt` > W, so it is outside every closed τ | Refusal rule | DB |
| 5 | The database clock steps back below W | Publications refused until the clock passes W; **no fact appears inside a closed range** | Refusal | DB, operational |
| 6 | Requested τ later than the current clock | Close **refused** (future); the caller retries | Close rule | D |
| 7 | Membership and Geography both close through the same τ | Each confirms independently; the operation proceeds only when **both** confirm | D-73 protocol | P |
| 8 | Geography confirms; Membership cannot close | The operation **waits or reports `BOUNDARY_NOT_CLOSED`**; never proceeds partially | Protocol | P |
| 9 | A correction over [Mar, May) of a parent valid [Jan, Jul) | Supersedes only [Mar, May); before-image `[([Mar, May), A)]` | Interval supersession | D |
| 10 | A claim over [Jan, Dec) replacing A over [Jan, Jul) and B from Jul | Before-image `[([Jan, Jul), A), ([Jul, Dec), B)]`: losses for A and B, gain for the new value | Piecewise | D |
| 11 | A new child of A, and another child moved away from A, both after p₀ | Both make a revision depending on "children of A" stale (gain by new value; loss by before-image) | Family rule | D |
| 12 | U relinquishes X; W acquires X | Before-image X (loss); new value X (gain); either makes an I-1-dependent revision stale | Family | D |
| 13 | Two revisions with overlapping dependencies | The second is stale | Delta check | D |
| 14 | Two revisions with unrelated dependencies | Both publish | Delta check | D |
| 15 | A multi-country revision failing in one partition | Nothing published | One entry or none | D, DB |
| 16 | A withdrawal of one alternative name | Only that value's slot gets NONE; other alternatives unaffected; before-image shows presence | Set-valued slot | D |

## 5. Remaining

- **[TU]** The validator reads only through dependency-recording queries (structural enforcement).
- **[TU]** `FOR UPDATE` and isolation; the literal commit time is unreadable inside the transaction.
- **Operational:** time kept by slewing.
- **Platform:** Membership's §38.2 bump versus the strict reading of D-73.

**No conflict with an accepted Principal decision remains**, because v3.4 satisfies both readings of D-73.

**[OQ], later:** names of regions and territorial units; L-4c; Japanese blocks; Q-2.

## 6. Readiness

**Ready for independent ratification review.** Database and platform items are conditions for the implementation architecture, not for the domain model.
