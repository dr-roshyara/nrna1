# Proposed methodology addendum: formal-model research under M-04 v1.0 (2026-10-18)

**Status: PROPOSED.** It is not part of M-04.
- M-04 v1.0 (`03-methodology/M-04-v1.0-approved-baseline.md`) and the working practice (`03-methodology/M-04-working-practice-20261018.md`) are **frozen and unchanged**.
- This addendum lives in `tmp/` per the Principal's storage instruction.
- Adopting it into `03-methodology/` requires lifting the freeze for that path.

**Basis:**
- the Principal's ALM-r1 mandate (2026-10-18), including "Update the methodology accordingly";
- the lessons from the M1, M2, ALM and ALM-r1 rounds.

## 1. When it applies

Whenever a TD/TODO question is answered by a **formal model**, i.e. definitions, theorems and executable checks.

## 2. The gate sequence

1. **Freeze the baseline** by hashes. Re-run and record its outputs, and inspect existing regression tests for *behavioural* content (ALM-r1 Phase A found 5 of 13 were placeholders).
2. **Specify before implementing.** Every symbol is defined. Every reserved policy is a parameter with its **register ID**; no new numbering. **The configuration object has no defaults.**
3. **Separate the questions:** validity, authority, recognition, verdict, effect, eligibility. No layer is defined from a later layer's verdict.
4. **Two kinds of tests:**
   - *conformance* tests: what the requirement fixes;
   - *policy* tests: the consequences of each alternative.

   A test that passes only under one policy value must say so.
5. **An independent oracle:** a different algorithm, no shared code, written from the spec text. It must test *claims* (e.g. uniqueness), not assume them.
6. **Bounded exhaustive checks + seeded random tests + counterexample minimization.** Always disclose the alphabet and the bounds.
7. **Proof-status labels** on every claim: **proved** (under stated premises) · **bounded evidence** · **unproved**. *A passing suite is never a proof.*
8. **Machine learning only as a discovery aid:**
   - report the base rate;
   - use class balance for rare events;
   - check every learned rule against a theorem;
   - never cite it as evidence of meaning.
9. **An independent review in a fresh context**, which derives findings before reading the author's dispositions. The report is kept **verbatim**; dispositions are written separately; every counterexample is reproduced by the author.
10. **Exit only on:**
    - regressions passing for the intended reason;
    - stated proof status;
    - complete traceability;
    - no reserved policy embedded;
    - no BLOCKER/MAJOR in review.

    Otherwise **stop, report, and ask before a new revision.**

## 3. Standing cautions (evidence from these rounds)

- **Author encodings hide defects.** M2 G-16 occurred 4 times before ALM review 1; review 1 found it again; ALM-r1's scenario tests caught a 6th instance (default period). **Hence:** oracle + reviewer + behavioural regressions, never the author's suite alone.
- **Search incompleteness masquerades as falsification:** ALM-r1's Th-5 check looked false until the completion family was widened. A counterexample search must report the family it searched.
- **Earlier records are corrected openly, never silently:** e.g. the checkpoint's Q4 (n = 2 → n = 1) and "new finding" (not new).

## 4. What this addendum does not change

- access rules;
- batch authorizations;
- M-04's research scope;
- the freeze;
- the requirement that the Principal decides policy.
