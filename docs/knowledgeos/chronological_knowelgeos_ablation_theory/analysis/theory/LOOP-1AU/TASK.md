# Task (frozen): prepare a decision package on revising an observation schema

**Context.** A research programme codes historical governance records (a register of rulings, session logs, commits) into "semantic observations" under `SCHEMA-v1.md`. An audit (files `AUDIT-1AR-*`) found that of 36 coded legality events:
- 19 are attested acts (performed or refused);
- 13 are statements of permission or prohibition;
- 1 is a generic practice;
- 3 are unclear.

Many statements were coded as if they were refused acts; for START, 10 of 11 (`START-1AQ-RECONCILIATION.md`). Under an act-only count, several variables lose their empirical support.

Use only the files in this directory; no commands, no web.

**Your job:** draft a compact decision package that lets a human decide whether to adopt a revised schema ("r2"). **Do not adopt anything; do not recode anything.**

The package must contain, each as a short section:
1. **The current schema:** the relevant parts of SCHEMA-v1, quoted.
2. **The conflation:** what exactly v1 fails to distinguish, with 3 concrete examples quoted from EVENTS.json / the audit files.
3. **The affected records:** a list, with counts per operation and per class. Distinguish *certain* from *disputed* classifications (the worker and reviewer disagreements in the audit files).
4. **The proposed r2 schema:** the minimal addition, e.g. a record-level field `observation_type` ∈ {ACT_OBSERVATION, NORM_STATEMENT, GENERIC_PRACTICE, UNKNOWN} plus the rule for NOT_APPLICABLE. **Question this proposal**: is a different or smaller change sufficient?
5. **The migration rule:** how existing records map to r2; which are recoded automatically, which need human or blind re-coding; lineage preservation.
6. **The ambiguity rule:** when UNKNOWN; no silent defaults.
7. **Expected impact** on each variable (authority, kind, target, state, evidence, conformance, operation, route, exception, history): its support under v1, under an act-only count, and what the NORM dataset could still support. **Separate the two datasets.**
8. **Failure modes:** e.g. over-splitting; the norm/act boundary being coder-dependent; ruling rows that are *both* a norm and an act (a ruling that permits is itself an act of permitting); loss of usable data.
9. **Falsification criteria:** what observation would show r2 is unnecessary, or wrong.
10. **Necessity:** is the distinction *necessary*? Answer with a concrete argument. For instance: could v1 plus an analysis-time filter achieve the same? Is the distinction already implicit in some v1 field?
11. **Options for the human:** adopt r2 as proposed · a smaller alternative · do not adopt. State the consequence of each.

Write **PACKAGE.md** (at most ~2 pages; labels SOURCE FACT / INFERENCE / PROPOSAL / UNKNOWN) and **PACKAGE.json** ({affected_counts, proposal, alternatives, necessity_argument, falsification_criteria, options}).
