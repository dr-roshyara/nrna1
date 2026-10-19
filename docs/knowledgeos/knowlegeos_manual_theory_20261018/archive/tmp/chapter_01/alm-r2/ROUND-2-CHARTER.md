# ALM round 2: charter (set before the round starts; 2026-10-18)

**The goal is the KnowledgeOS theory of authority, validity and recognition, not a perfect ALM.** ALM-r2 is a **bounded experiment** that tests whether ALM can carry that theory.

## Research exit criterion (Principal guidance, verbatim)

> The research is complete when the authority, validity and recognition questions within the agreed scope have precise definitions, defensible results, explicit assumptions and limitations, and independent review finds no unresolved major defect. Any remaining governance choices must be clearly identified for the Principal rather than silently decided by the researcher.

## Bounds of this round

- **One round:**
  - specification (done: `ALM-R2-SPEC.md`);
  - an oracle built by a separate agent from the specification only;
  - implementation;
  - differential and bounded tests;
  - re-proofs;
  - **one** fresh independent review (review 3).
- **No round 3 of repairs without a new Principal decision.**
- Scope: the TD-001 core, plus υ (RD 6) as an undecided parameter. Nothing else is added.

## Stopping rule and the two outcomes

| Review 3 result | Outcome | What happens next |
|---|---|---|
| no BLOCKER/MAJOR | **ALM succeeds**: a well-supported *candidate* | report the theory content, the assumptions, and the reserved decisions. Then a separate step: **what KnowledgeOS needs to adopt** (never the whole research model automatically) |
| any BLOCKER/MAJOR | **ALM fails again** | **stop repairing.** Write a *theory-status report* that keeps every verified result, and recommend **narrowing** (e.g. the validity layer plus a deliberately conservative recognition rule) or **replacing** the model. The Principal decides |

**In either case the round yields:**
- the verified results already established: the validity layer (Th-1 and Th-2, reviewer-verified); Th-4; "never F under λ⁻ = ∞"; Pr-10;
- the reading results: C1 settles υ under a uniform reading; C1-a makes negatives auditable (Th-5a, to be proved);
- a list of the questions that genuinely belong to the Principal.

## Measures reported at the end of the round

- the regression and condition closure count;
- proof status per theorem;
- oracle agreement counts per layer;
- **the share of verdicts each repair turns into "cannot determine"** (the reviewer's requirement);
- the open MAJOR count in review 3.

## What this round does not do

- No TD-001 r1.2, no manuscript change, no register change.
- No adoption of any reserved policy.
- No commitment to ALM as the final architecture.
