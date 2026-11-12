# C1/C2 specification: contract-conformance and architectural validation

| | |
|---|---|
| **Subject** | `docs/publicdigit/reviews/2026-11-10-membership-cr01-c1-c2-implementation-specification.md` (proposal) |
| **Commission** | "Principal Instruction — C1/C2 Contract-Conformance and Architectural Validation", accepted by the Principal 2026-11-10 |
| **Reviewer** | Claude, **the author of the subject**. **Not independent**; a self-review against the sources |
| **Sources read** | Ratified Membership architecture `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md` (§56–§122 definitions and invariants, §154, §197, §201); CR-01 (full); D-6 / cutover gate §4; D-73, D-77, D-153, D-159, D-160; Organisation §49.8, §50.8; kernel `Member::knownAt`, `ResolvedMember`, `ResolvedPeriod`; `PostgresMemberRepository::appendInTransaction` (lines 184–238) |

**Labels:** [RAT] ratified · [AD] decided · [BF] fact · [AC] conclusion · [TD] tactical design · [TU] proof obligation · [OQ] Principal question.

## 1. Executive conclusion

- **The specification implements the ratified contract without changing its meaning**, and keeps Membership and Organisation apart.
- **Its record-time guarantee is sound in principle, but rests on two unstated database assumptions**, which must be written in (K-2).
- **One business consequence was never put to the Principal: lapsed members.**
  - People whose membership term expired without renewal are **inside coverage** (ratified), but **not members** under the ratified `IsMember`.
  - Under D-153 as worded, they would still post in forums and receive newsletters in release 1. That needs one Principal answer (§10).
- **Verdict: READY AFTER SPECIFIC CORRECTIONS (K-1 … K-7) and the answer to the lapse question.**

## 2. Contract-conformance findings

| Specification element | Source | Status |
|---|---|---|
| C1 fields, consumer rules, full state, gaps in `seq` | CR-01 C1; ratified §197 | **Conforms** [RAT] |
| C1 emitted only when coverage changes | CR-01 C1 ("published only when coverage changes") | **Conforms** |
| C2 signature with `personRefs[]` | CR-01 C2; D-77; ratified §201 | **Conforms** |
| `closedThrough` meaning and `BOUNDARY_NOT_CLOSED` | CR-01 §3 | **Conforms**; mechanism in §6 |
| Proof by transaction ordering, not by moving timestamp generation | CR-01 §9 | **Conforms**: the clock row orders commits; generation stays in the application |
| **`SOURCE_NOT_LEDGER` refusal** | **not in CR-01**; required by **D-160** (Principal) and D-6 (one store per organisation) | **Extension of the refusal set** [TD under D-160]. It is a refusal, never a partial answer, so **CR-01 §3's meaning is preserved**. Must be labelled as such (K-3) |
| Unknown `membershipId` → error | CR-01 silent | [TD]; acceptable (Organisation only holds ids Membership issued) |
| Organisation never reads Membership tables or derives coverage | CR-01 C0, C1 ("never derives coverage itself") | **Conforms** |
| `closedThrough` per organisation | CR-01 §3 (no scope stated); every C2 call names one organisation | [AC] **A narrower but sufficient scope**; label it (K-4) |

## 3. Coverage semantics and business consequences

| Question | Answer | Derivation |
|---|---|---|
| Lapsed period inside coverage? | **Yes** | ratified §92: Renew, Suspend, Restore, ChangeType "never change BaseClaim, so they can **never change coverage**"; §87 `lifetime = BaseClaim − LiveCuts`; §102 `Lapsed = InLifetime ∧ ¬TermCovered` → [RAT] |
| Suspension affects coverage? | **No** | §92, §103 → [RAT] |
| End changes coverage? | **Yes**, cuts the lifetime | §122 INV-COV ("changes legitimately through Admit, End, Correct, Annul and Void") → [RAT] |
| Annul, Void, Correct? | Change base claims; an annulled End restores coverage | §122; CR-01 §7 → [RAT] |
| Unknown `PersonRef` → empty coverage? | **Yes, when τ ≤ `closedThrough`** | one Membership per (Person, Organisation) (ratified, D-77); a complete boundary means no unseen membership → [AC] |
| Empty versus unavailable distinguishable? | **Yes**: empty = an answer (`seq` 0, no intervals); unavailable = a refusal (`BOUNDARY_NOT_CLOSED`, `SOURCE_NOT_LEDGER`, cross-tenant) | [TD]; **consumers must never turn a refusal into "not a member"** (K-5) |

**Correction to the specification:** its §0 finding 4 labels "coverage = lifetimes, lapse included" as **[AC, reading]**. **It is [RAT]** (§92). The label changes; the content does not (K-1).

**Business consequence:**
- **Coverage is "where the person still belongs":** the period has not ended.
- **`IsMember` is "a member in good standing as to term":** in force.
- **Lapsed people are the gap between the two.** INV-3: "lapse never implies an ending", so they keep their unit.
- Release 1's rights (forum write, D-153; newsletter audience, D-158) **are wired to coverage**. So, as worded:
  - **lapsed people post and receive;**
  - **so do suspended people** (which D-153 stated).
- **This is not a contradiction in the text; it is a consequence the Principal has not seen.** §10.

## 4. C1 assessment

- **Meaning and ownership:** Membership's statement of the whole coverage of one membership. **Correct.**
- **Identity:** `eventId` unique; stream = (organisation, membership). **Correct.**
- **Atomicity:** fact, clock and outbox row in one transaction. **Correct**; rollback leaves none.
- **Duplicates and out of order:** full state + highest `seq` wins. **Correct.**
- **Equal `seq` with different content:** integrity error, not applied, raised. **Correct.**
- **Gaps in `seq`:** valid (CR-01).
- **[TU] Interval representation:** kernel `TimePoint` versus Organisation's dates (K-6).
  - The event must state its time unit and boundary convention (`[from, to)`, open `to`).
  - **Organisation must compare its business dates to it without its own interpretation of Membership time.**

## 5. C2 assessment

- **τ semantics:** facts with `recordedAt ≤ τ`; no `asOf`. The consumer evaluates its date against the intervals. **Conforms.**
- **No partial answers; refusal carries `closedThrough`.** Conforms.
- **Pagination:** every page uses the same τ. **Safe**, because facts at or before τ ≤ `closedThrough` never change.
- **Cross-organisation:** refused before anything else. Conforms with INV-ORG-SCOPE-1.
- **Organisation's historical use is safe:** it stores the τ with each belonging answer it relies on (§50.8), as CR-01 §7 requires.

## 6. Temporal consistency: the ledger clock

**Contract requirement:**
- (R1) every fact with `recordedAt ≤ closedThrough` is committed and visible;
- (R2) **none can commit later.**

| Step | Argument | Assumption it needs |
|---|---|---|
| Writer locks the organisation's clock row, sets `recordedAt > max(last_recorded_at, closed_through)`, commits | Writers of one organisation are **totally ordered by the lock**, so record order = commit order | **A1:** after acquiring `FOR UPDATE`, the writer reads the **latest committed** row. **True in PostgreSQL READ COMMITTED** (row re-read on lock). **Under REPEATABLE READ or SERIALIZABLE the lock instead raises a serialisation error**; the specification must state READ COMMITTED for the append, or treat that error as a retry |
| Reader takes the lock `NOWAIT`; if free, sets `closed_through = max(closed_through, now)` and commits | No writer was in flight, so (R1) holds up to that point. **Every later writer reads the new `closed_through` and records after it**, so (R2) holds | A1 again |
| Reader finds the lock busy and returns the stored `closed_through` | A writer in flight read that value (or a later one) when it locked, so it records after it: (R2). Facts at or before it were committed by earlier writers: (R1) | **A2:** the stored value read is the latest committed (READ COMMITTED) |
| Reader then loads facts with `recordedAt ≤ τ` | **They must be read in a snapshot taken after `closedThrough` was fixed**, otherwise a fact committed just before can be missed | **A3: unstated.** Fix it: determine `closedThrough` in its own committed step, **then** read facts (new statement under READ COMMITTED, or a new transaction) |
| Clock moves backwards | `max(…, last + 1µs, closed + 1µs)` keeps order | none |
| Clock moves forwards or servers disagree | Record times may run ahead of wall time; **ordering still holds**; "as known at τ" stays consistent but shifted | **[TU]:** use one clock source for writers and readers. The application clock on one host, or the database clock read under the lock; **either keeps proof by ordering** |
| Writer rolls back | Its clock update rolls back too; no fact, nothing to order | none |
| Several memberships in one organisation | Same lock: ordered | — |
| Two organisations | Different rows: independent | — |

**Conclusion [AC]:** the mechanism **establishes R1 and R2 given A1–A3**. A3 is a real gap in the specification (K-2). **Proof is still owed by tests** ([TU]):
- interleaved writers and readers;
- a reader between lock and commit;
- clock regression;
- a REPEATABLE READ misuse that must fail closed.

## 7. Membership and Organisation boundary

| Side | Owns | Check |
|---|---|---|
| **Membership (provider)** | ledger, coverage, record-time order, C1, C2, source refusal | Specification §2–§6. **Conforms** |
| **Organisation (consumer)** | its port, adapter, translation, current-view projection, belonging decisions | Specification §7. **Conforms**: no table reads (architecture test), no domain imports, no coverage derivation, projection for current views only, history via C2 |

**Refinement (K-7):**
- the specification should be presented as **two halves of one contract**, each with its own tests and its own "done";
- **an architecture test in both directions:** Membership does not import Organisation; Organisation does not import Membership's domain or tables.

**No new context.**

## 8. Release 1 dependency and capability picture

| Category | Items | Evidence |
|---|---|---|
| **Implemented and live** | Legacy membership store and its screens | [BF] |
| **Implemented, not activated** | Ledger kernel, port, PostgreSQL adapter (PBDIGIT-258/259/260); correction slice (formal acceptance pending); ledger migrations (not rolled out) | [BF] CONTEXT-publicdigit; migrations `2026_10_09`, `2026_11_02`, `2026_11_03` |
| **Not implemented** | C1, C2, ledger clock, source-of-truth flag (gate C-1), application routing of admit / end / correct to the ledger, cutover machinery | [BF] |
| **Required before C1/C2 can be trusted** | ledger rolled out (C-10); source flag set; **every membership act of a ledger organisation goes through the ledger** (otherwise coverage is incomplete); ledger clock with A1–A3 | gate §4; this review §6 |
| **Required by Organisation before belonging** | C2 working with `closedThrough`; C1 for current views; the time-unit convention (K-6) | §50.8 |

**Reconciliation with the integrated review:** its capability map says "Membership: apply, admit, end, **standing** — yes".
- **Standing exists inside Membership** (`Lapsed`, `Suspended`, `IsMember`: ratified predicates).
- **No release 1 consumer receives it.** Forum and newsletter use coverage only.

The map row is corrected to say so (K-6 in the integrated review), so the product never promises enforcement it does not have.

## 9. Corrections required (to the specification and one row of the integrated review)

| # | Correction |
|---|---|
| **K-1** | §0 finding 4: label coverage-includes-lapse-and-suspension **[RAT] (ratified §92, §102–§104)**, not [AC] |
| **K-2** | §4: state **A1–A3**: READ COMMITTED for append and the `closedThrough` step; **facts read only after `closedThrough` is fixed**; serialisation errors are retried or fail closed; one clock source |
| **K-3** | §6: label `SOURCE_NOT_LEDGER` as **an extension of the refusal set under D-160**, not CR-01 text |
| **K-4** | §4: label `closedThrough` as **per organisation** [AC], sufficient because every C2 call names one organisation |
| **K-5** | §7: **consumers never convert a refusal into "not a member"**; refusal → the dependent act is refused |
| **K-6** | §5: C1/C2 state the interval time unit and boundary convention. **Integrated review §2:** "standing" → "standing exists in Membership; **release 1 consumers use coverage only**" |
| **K-7** | Present provider and consumer as two halves, each with its own tests; architecture tests both ways |

## 10. Genuine Principal question

**Lapsed members in release 1.** D-153 says members **with coverage** may write. You were told this lets **suspended** members post. You were **not** told it also lets **lapsed** members post and receive newsletters: people whose term ran out without renewal, usually unpaid, who are **not members in good standing** under the ratified `IsMember`.

**C1/C2 are not yet built**, so this is the cheapest moment to choose. Asked separately.

## 11. Verdict

**READY AFTER SPECIFIC CORRECTIONS:** K-1 … K-7, plus the Principal's answer in §10.
- None of the corrections changes the ratified contract's meaning.
- The concurrency guarantee is **argued, not proved**; its tests are proof obligations.

## 12. Recommended next DDD step

1. Apply K-1 … K-7.
2. Then the Principal's acceptance decision on the specification.
3. **After that:** the Organisation ⇄ Governance AuthorityCheck answer shape (Addendum A-2) against the ratified six fields. It is the last cross-context contract release 1 needs that has not been checked field by field.

**Outcome (2026-11-10):**
- **§10 answered: D-161, CR-02 accepted** (standing in C1/C2; good standing to write and receive).
- **K-1 … K-7 applied** in the specification §11.
- **The specification is now ready for the Principal's acceptance decision.** The concurrency guarantee is still argued, not proved; its tests remain proof obligations.
