# Theory turn 1 of the chain (T9-22): settlement

*2026-11-11. The Professor.*
- *Commission: `03-methodology/COMMISSIONS-CHAIN-R2-R1-THEORY-TURNS-20261103.md`, turn 1 (dispatched 2026-11-11; index T2).*
- *Working instructions in force: `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`.*
- *Order kept:*
  - *the research question committed before any draft (`397d13f69`);*
  - *the draft (`66e5d9e00`);*
  - *the step-1 answers saved with sha256 before the step-2 brief (`178b255ee`);*
  - *the reports saved with sha256 after all three returned (`b47ec12fe`).*
- *Reviewers: A (kos-lane-reviewer, Opus), B (kos-lane-reviewer, Sonnet), C (kos-theory-reviewer); none on an outside service.*

## Process record

- **Usage-limit interruption:** all three step-2 reviewers stopped on the 5-hour usage limit (stop condition (e)). They were resumed at 17:20 Berlin time, after the reset, on the user's "continue". Each disclosed the interruption; none reports lost work or a changed conclusion.
- **Access deviations (A6, minor, recorded, no stop):**
  - A used two globs and an in-place grep of `QUOTE-BASELINE.txt` to find filenames at step 1.
  - B used one glob and one filtered listing at step 1.
  - C made about 60 `test -f` probes at step 1, and did not find §9.19 until the step-2 message named every Ch. 9 file.
  - In each case only allowed files were matched or read. Lesson for later briefs: name the section files.
- **Checks after settlement (the Professor):**
  - R2, `undefined_terms.py --no-corpus --scope 09.21`: tiers A–C none in scope.
  - R1: 0 flags.
  - Quote check: no bracketed quotations in §9.21 (every line reference is "in meaning"; the reviewers checked their quotes by hand and found them matching).
- **No model was written or run.** The reviewers' probes are their own scratch models, reported as form.

## Dispositions

All findings were accepted after the Professor checked them against the book. These lines were read before acceptance:
- §9.10 L186, L194 (A-01);
- §9.13 L122 (A-08);
- §9.13 L137–141 (C-01);
- §9.16 L93–98 (A-07, B F5);
- §9.1 L93–96 (A-05, B F1);
- §9.10 L115 (A-05, B F2, C-06).

| Finding(s) | Sev. | Disposition | Where in v2 |
|---|---|---|---|
| A-01, B F9, C-02 | high | Q2's scope narrowed to (S-a)/(S-c)/(S-b); the conflict with §9.10 L186/L194 recorded | §9.21.3 (4); §9.21.5 Question O |
| A-02, B F6, C-03 | high | the M6 row conditional on B-07; both readings shown; generic clauses and the fourth group added | §9.21.3 (4); §9.21.4 |
| A-03, B F7 | high | entries no output returns listed; whole-entry forms do not make the record minimal | §9.21.4 |
| A-04, B F3, C-05 | high | batch guarding as a premise; snapshot and all-or-nothing batches meet DC-4 | §9.21.3 (3) |
| A-05, B F1, F2, C-06 | high/normal | (2) and (3) Ded on the origin-rule premise (§9.1 L96), not decided; acts only; W batching undecided | §9.21.3 (2), (3) |
| A-06, C-12 | normal | "exactly" restated; inconsistency removed | §9.21.3 (4) |
| A-07, B F5 | normal/high | the merge defines the recorder, status and tombstone only | §9.21.3 (4) |
| A-08 | high | delegated grants' act times are read; R3 notes on §9.20.3 L48 and §9.20.5 L104 | §9.21.4; §9.20 |
| A-09, B F8, C-11 | normal/high | Q3 is the set of T bases, roots included | §9.21.3 (4) |
| A-10 | normal | Q4 included; title extended | §9.21.3 (4); title |
| A-11, B F11, C-09 | normal | (1) scoped to O1's kinds; the mapping of other D kinds and O2's cut stated as open | §9.21.3 (1) |
| A-12 | normal | §1.16 L171 against law 12, stated as a refinement premise | §9.21.3 (2) |
| B F4, C-07 | normal | held acts as a scope limit of (2) | §9.21.3 (2) |
| B F10, C-04 | high | G is not fixed by behaviour (case-split; guards); Question G | §9.21.3 (1); §9.21.5 |
| B F12 | high | the question reformed, with Q2's scope as an option | §9.21.5 |
| B F13 | normal | §9.21.4 labelled as restated, not a new model | §9.21.4 |
| B F14, C-08 | normal | G-guard bears on existence and uniqueness too; kernel scope noted | §9.21.3 (1) |
| B F15 | — | no defect | — |
| C-01 | high | status in a support is conveyed by the marks; R3 on §9.20.5 L106 | §9.21.4; §9.20 |
| C-10 | normal | Q2's form relabelled Ded; homes §9.13.2 and §9.18.4, the model as illustration | §9.21.3 (4) |
| C-13 | normal | premise "rests on := the marks' support" named | §9.21.3 (4) |

**Counts:** A 12, B 15, C 13; after merging, 26 distinct items; high 12 before merging. No critical finding.

**Disproof conditions as met by the draft:**
- DC-1 for (2) and (3) as labelled, and for Q2's scope;
- DC-3 for W's kinds;
- DC-4 for snapshot and all-or-nothing batches;
- DC-7 for the M6 row.

v2 restates each.

**R3 (every accepted correction):**
- §9.19.4 L164 (batching);
- §9.20.3 L48 and §9.20.5 L104 (delegated grants);
- §9.20.5 L106 (status via the marks);
- §9.20.3 L53 (content row);
- the T9-03, T9-04 and T9-22 rows;
- index row 9.21; the THEORY-MAP turn line and its chapters line (§9.1–9.21).

*Not affected:* §9.10's text (the L186/L194 conflict is recorded at §9.21, not resolved); §9.16 L93 (B-07 stays open, now a precondition of Question O).

## TODO and outline (consequences, not decisions)

- **T9-22: reduced** to Question O, with the B-07 precondition. Question G is separate.
- **T9-03, T9-04:** consequence notes.
- BOOK-OUTLINE: not changed.
- RESEARCH-PURPOSE.md: not edited.
- T9-21, the T10-15 turn, Ch. 10: not touched.

## Outcome and questions for the Principal

**Partially resolved.**
- **Derived:**
  - G-kind = G-act on O1's kinds;
  - G is not fixed by behaviour;
  - Q2's form is whole entries;
  - composites and batching of acts are excluded on the origin premise;
  - minimality restated over the join of outputs.
- **Narrowed:** Q2's scope; the sets and forms of Q1, Q3 and Q4; batching's guarding; G.
- **Decisions:** Question O, and Question G (needed only if mechanism minimality is stated), as worded in §9.21.5. They are asked one at a time in the report.
