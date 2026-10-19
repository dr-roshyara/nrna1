# Decision brief: late evidence. A recording deadline, or re-examination? (2026-10-18)

**Status:** prepared for the Principal or ARB. **Not decided.**
- Register: T1-20 (the horizon); the remedy for reversals has **no register ID** (candidate, not minted).

## The business problem

KnowledgeOS may decide on Monday that an action was authorized. On Wednesday a cancellation dated Sunday is entered. The Monday decision rested on incomplete records.

- **[Reviewer-verified]** No record-based system can rule this out: a later entry can always change what the records establish (Th-4).
- The question is how the organisation **limits** the problem and **what it does** when it happens.

## Options

| | **A. Recording deadline** | **B. Re-examination on every new relevant record** | **A + B** |
|---|---|---|---|
| Rule | every act must be recorded within Δ of being made. KnowledgeOS decides only about matters older than Δ | decide as soon as the records establish authority; re-check affected decisions whenever a relevant record arrives | decide early, re-examine, and use the deadline as a backstop |
| Benefit | if the duty is honoured, decisions about matters older than Δ **cannot be reversed by late records** (research claim, provable from Th-S1 with Comp(k, now − Δ)) | no delay | speed plus a bounded reversal window |
| Risk | every decision waits Δ. A breach of the duty reintroduces silent errors | reversals happen, and actions taken in the meantime must be handled | the most process |
| Obligation | enforce the duty: someone must monitor late entries | a reversal remedy (continue / suspend / roll back / ARB review) and an owner for it | both |
| **What research can establish** | the no-reversal theorem under the duty; the size of the window as a trade-off curve, once real timing data exists (none exists today: 2/274 transitions carry dates) | completeness of detection (Th-S4) | both |
| **What only the Principal or ARB can decide** | whether to impose the duty; the value of Δ; the sanction for a breach | **the remedy** for actions already taken | all of these |

## Current-store reality

**The store records no times at all**, apart from 2 dates.

- A deadline **cannot be monitored** until entries carry act and record times (data prerequisites R-Q1 and R-Q2).
- Re-examination **can** run on record positions alone.
- **Practical sequence:** B is technically possible first; A needs R-Q1 and R-Q2.

## Recommendation (research opinion; not a decision)

**A + B once R-Q1 and R-Q2 exist; B alone until then**, with "suspend and escalate to the ARB" as the default *proposal* for the reversal remedy.

**The remedy itself is the Principal's or ARB's decision.**
