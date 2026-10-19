# Scope decision: may RD 6 (unverifiable cancellations) enter the ALM-r2 core as an undecided parameter?

**Status: AUTHORIZED — Option A.**
- **Principal, 2026-10-18, verbatim:** *"Option A: include υ as a parameter, proceed with round 2."*
- The brief below is the one prepared for that decision. It is kept as the record of what was decided.

## 1. The question

**May RD 6, "does a recorded cancellation whose own authority cannot be established count?", be modelled in the ALM-r2 core as an undecided parameter (υ)?** Phase A excluded it from scope.

## 2. The business problem in plain language

Suppose our records show that someone cancelled a decision, but they do not show whether that person was allowed to.
- If we **ignore** such a cancellation, we may report a decision as current when it was in fact properly cancelled.
- If we **let it make the answer "cannot determine"**, then anyone can freeze any decision by writing an unverifiable cancellation (v1 safeguard 6).

**Evidence that this belongs to the core:**
- **[Reviewer-verified]** Review 2 showed that the round-1 model silently ignored such cancellations. As a result it answered "authorized / current" where TD-001's own rule implies "cannot determine": a **false positive** (finding R2-03; reproduced by the author, probe N3).
- **[Reviewer-verified]** The same false-positive mechanism appears when fields or act order are unknown (R2-01, R2-02). An unrecorded tie order was placed only after the recorded ones, so a same-instant revocation could never be placed first.

## 3. The options

| | **Option A: include υ as an undecided parameter** | **Option B: keep RD 6 outside the core** |
|---|---|---|
| What the model does | represents both treatments, "counts as undetermined" and "does not count", and reports the answer under each | models recognition only for record states containing **no** recorded ending act (cancellation, ending, confirmation) whose authority is not established |
| Research benefit | TD-001's recognition clause can be analysed **as a whole**. It shows exactly where C1 settles υ (reviewer's correction: under a uniform reading, C1-b → undetermined, C1-a → does not count) | a smaller model |
| Complexity / risk | more parameters; more "cannot determine" answers; with υ = undetermined, the freeze risk (safeguard 6) is visible | none added |
| Scope consequence | none beyond the scope change | **excluded:** any decision that has a recorded but unverifiable cancellation, ending, confirmation or superseding adoption. **Could no longer be claimed:** that ALM models TD-001's recognition clause in general; at most "for records without unverifiable endings" |

**Neither option decides the governance policy.** Including υ does not adopt either value. RD 6 stays a reserved decision of the Principal.

## 4. Recommendation (research opinion)

**Option A**, for research completeness. The reviewer showed that the recognition clause cannot be modelled faithfully without υ, and modelling both values decides nothing.

## 5. The exact scope change authorized

**Phase A, item A3** is amended for ALM-r2 only:
- **RD 6 (υ)** moves from "out of scope" to "**in scope as an undecided parameter**";
- it covers **every recorded act whose authority is not established on the records and whose effect bears on the question**: REVOKE, END, CONFIRM, and a superseding ADOPT under C6 (reviewer condition R2-03(a)).

**Unchanged:** every other out-of-scope item: C2 citations, suspension, C5, annulment with an effective time (RD 2 late values), C13 consumption, and C4 joint authority.

## 6. Not affected

- **Register:** no change to the register, no new ID, and no renumbering.
- **TD-001:** TD-001, r1 and r1.1 are unchanged; no r1.2.
- **Frozen material:** the ALM-r1 frozen subject and its review evidence are unchanged, and so are M-04 v1.0, CONTEXT.md and the session log.
