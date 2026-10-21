# Window C22: per-source measures, step 8, falsification attempts, checks

*2026-10-21. Sources: S0116–S0120, in order, each read whole and depth full. **Timing as the Principal set it (2026-10-21):** each source was started when opened and closed after its theory was written into §8.15. New: §8.15 (C1–C11); register CON-0215–CON-0223. CON-0213 is restated, and §8.14 C1 carries a correction pointer. Changed theory under review: the Principal's decision after C21 (a consumable grant is single-use; §9.13 v14; model v15) and §9.16 R4c.*

## Per source (time includes reading and writing the theory into the book)

| Source | Lines | Start → stop (UTC) | Time | Depth | Findings | In §8.15 |
|---|---|---|---|---|---|---|
| S0116 | 170 | 06:15:03 → 06:15:48 | 45 s | full | 6 | C1, C2, C3 |
| S0117 | 300 | 06:15:48 → 06:16:50 | 62 s | full | 6 | C3, C4, C5 |
| S0118 | 77 | 06:16:50 → 06:17:12 | 22 s | full | 3 | C6 |
| S0119 | 77 | 06:17:12 → 06:17:33 | 21 s | full | 4 | C7 |
| S0120 | 714 | 06:17:39 → 06:18:47 | 68 s | full | 7 | C8–C11 |

*The times are the environment's clock. They are the first per-source times that include writing the theory into the book. They are short against the volume written, so the clock's resolution or pace is itself uncertain; they are recorded as measured, not corrected.*

**Skim.** None. S0118 is the nearest: it registers a third act and two defects, and its one theory point is C6. S0119 mostly prepares a selection, and its theory point is C7.

## Book search

| Query | Hits read | Relation |
|---|---|---|
| two-limb test, presupposition, performance | CON-0199 (§8.12 C7), CON-0213 (§8.14 C1) | narrowed (C1); corrected (C8) |
| owner-only handoff | §9.16 R4a (S0071 DP-3 rejected) | corpus case (C2) |
| trigger, observer, detection | §9.16 R3a | extends (C3) |
| evidence class, provenance, reproducib | CON-0196, CON-0201, §9.13 marks | related scales (C4) |
| label, identity of a decision, content-derived identity | §9.16 R2 | the same rule (C6) |
| referent, required field | CON-0203 | resolved (C7) |
| U never PASS, laundering | §9.13 U, CON-0201, CON-0212 | the same rule (C9) |
| record vs world | §9.15 (internal vs world) | the corpus form (C10) |

## Step 8: dependencies

| Relies on | Status | Check |
|---|---|---|
| §8.12 C7, §8.14 C1 | provisional; §8.14 C1 is corrected here | C1, C8 |
| §9.13 v14 (U, marks, D) | changed theory, **not yet reviewed** | C9, C11 compare |
| §9.15 (L10, the separation) | settled at C20/C21 | C10 |
| §9.16 R2, R3a, R4a, R4c | R4c **not yet reviewed** | C2, C3, C6 |

## Falsification attempts

| Concept | (a) counterexample | (b) boundary | (c) rival | (d) falsifier |
|---|---|---|---|---|
| CON-0215 | the two-limb test is still the right test for C-10, and only its automatic import is forbidden | the C-10 commission | the prohibition is procedural, not a claim about tests | a later act re-adopting the two-limb test for C-10 |
| CON-0216 | a self-firing trigger (a mechanical check) needs no observer | decisions to reopen | the observer can be the next mandatory read (S0117 does this) | — |
| CON-0217 | reproducibility of an observation of a self-declared field reproduces the declaration, not its truth | evidence about realization | independence as a fifth dimension | a reproducible observation later shown false with no change in the record |
| CON-0218 | a level structure lets a weak level be cited as establishment | establishment tests | a single conjunctive test, indexed by time | a capability established at L1 and cited as established |
| CON-0219 | letters are fine when the act's text fixes them | decision slots | identity by act id plus letter | a resolved "adopt Option C" on a record with crossed labels |
| CON-0220 | an optional reference that is always empty adds nothing | receipts | keep the field required, defer populating | a populatable receipt under a required, unfillable field |
| CON-0221 | a compensating control that records "accepted risk" is a resolution | status values | resolution by authority is not laundering | an estate act converting NOT ATTESTED to PASS, uncorrected |
| CON-0222 | "acknowledgement was recorded" still needs the recorder to be who it says | receipt claims | world claims under named premises | a record claim that fails while the record is intact |
| CON-0223 | a well-known name can carry its definition by convention | criteria, deliverables | a name adopted plus a pending definition | — |

## Checks

- Quote check: §8.15 33 found, 0 not found. Whole book: 14, the baseline.
- R1: 199 rows with a home; 1 inherited flag. R2: no candidates in §8.15.
- Model `ce_model.py` v15, run `ce_model-run-20261021-v15.txt`, all assertions passed (check 27 new).
