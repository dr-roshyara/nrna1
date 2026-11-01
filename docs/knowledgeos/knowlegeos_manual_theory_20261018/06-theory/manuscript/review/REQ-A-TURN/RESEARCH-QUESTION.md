# The Req/A theory turn: the research question and its disproof conditions

*Written 2026-11-01, under the Principal's commission `03-methodology/COMMISSION-REQ-A-THEORY-TURN-20261101.md` (issued, `96407e3bc`) and the Principal's start instruction of 2026-11-01 (Rulebook v1.4 §12b: "a research question first"). Reading stays at S0302.*

*Process disclosure: this file is committed after task 1's evidence check (§9.10 against BOOK-OUTLINE L179) and after one toy model was written and run (`tmp/tools/req_a_model.py`, run file `req_a_model-run-20261101.txt`, 2026-11-01 ≈15:20Z). The disproof conditions below were therefore written with that model's output known. The model tests the form of the granularity claims; it is reported as such in §9.19, and the conditions below are not weakened to fit it.*

## Starting point (not redone)

- **Req** = (Q, O), §9.10 version 2 (theory turn 1, settled 2026-10-20; CON-0188 provisional): core questions Q1–Q4, inputs O1–O4; Q5 grounded by chapter must-dos only; candidates identity resolution, Q6, claim conflicts, Q7, T-c, T-a, ownership (§1.11).
- **§9.1:** Kernel(M) = P(M) ∧ Req(M); Minimal(M); C3 (existence by one check, closed reading); C4 (one toy model: minimal kernels depend on the pool).
- **Decided theory that applies:** T10-01 (b); T9-19 with Q-A and Q-B; T9-20; T10-21; Q-C.

## The research question (the commission's, verbatim)

"Relative to which requirement set `Req` and which interface assumption `A` is 'a minimal kernel' well posed, and what does the book's evidence establish about it?"

**Task 1, first (the Principal's):** how §9.10 relates to BOOK-OUTLINE L179's "missing" item: which of the six deferring claims (§5.5 C3, §6.5 C1, §9.3, §9.4 C7, §9.5 C1, C5) §9.10 already answers, which in part, and which need new capabilities.

## What would disprove the answer (fixed here, before §9.19 is written)

In §9.10.1's form, extended to A:
- **(F-ins) insufficiency.** A requirement the book's evidence grounds (a must-do of a chapter, or a judgement of the central question) that the stated Req does not contain and that no stated reason places outside it. *For task 1:* one of the six claims whose requirement part §9.19 calls answered, while some part of it is met by no Req item and is not shown to be application, world or kit.
- **(F-min) redundancy.** A Req item, or a mechanism called necessary, whose removal leaves every judgement it was said to serve decided; or a mechanism called necessary that is absent from some kernel of the stated pool.
- **(F-vac) vacuity.** A trivial system (the empty mechanism set, refuse-all, the unchanged state, record-and-dump with a constant answer) that meets the stated Req, or a minimality claim that the trivial cases satisfy.
- **(F-A) granularity and boundary.** For every minimality, uniqueness or necessity result stated: a granularity of the mechanism pool, or a placement of the system boundary, consistent with the stated A, at which the result fails. A result for which such a granularity is exhibited holds only for the A that excludes it, and is stated with that scope.
- **(F-str) an unsupported uniqueness.** Any "the minimal kernel" (definite article) that rests on no proved structure condition.

## Limits (the commission's)

Engineering knowledge enters only as a labelled assumption; RESEARCH-PURPOSE.md is unchanged; T9-02 is not absorbed (escalated if Req needs it); the primitive set is not finalised; no unique minimal kernel without a structure theorem; no selection criterion among minimal candidates; at most 8 sources by §12b, predictions recorded first.
