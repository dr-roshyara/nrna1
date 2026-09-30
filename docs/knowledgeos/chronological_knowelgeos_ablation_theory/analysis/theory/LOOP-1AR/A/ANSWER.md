# Which legality events are attested acts?

Inputs used: `EVENTS.json`, `CODING-LINES.txt`, `SOURCES.md` only. **`WITNESSES.md` was not read.** TASK.md restricts inputs to those three files ("No other path") but also asks for the pairs named in WITNESSES.md. Those two instructions conflict, and I followed the explicit restriction. The witness pairs below were recomputed from `EVENTS.json` under the task's definition, so they may not match WITNESSES.md. Per-event quotes, anchors and reasons are in `ANSWER.json`.

## 1. Classification (36 events)

| Class | n |
|---|---|
| ACT-PERFORMED | 10 |
| ACT-REFUSED | 9 |
| PERMISSION-STATEMENT | 13 |
| GENERIC-PRACTICE | 1 |
| UNCLEAR | 3 |

| Operation | PERF | REF | PERM | GEN | UNCL |
|---|---|---|---|---|---|
| ADOPT | 1 | 2 | | | |
| AUTHORIZE-IMPL | 1 | 1 | | | |
| REGISTER | | 1 | | 1 | |
| OPEN-WORK | 1 | | 1 | | |
| START | 1 | | 10 | | |
| RAISE | 5 | 3 | | | 1 |
| ASSIGN-ID | | | 1 | | 1 |
| SUPERSEDE | | 2 | 1 | | 1 |
| ANNOTATE | 1 | | | | |

**SOURCE FACT:**
- **ACT-PERFORMED:**
  - R-86 adopts R-81..85: "Decision Authority confirms and adopts R-81 through R-85".
  - R-70 authorizes WP-7B-R1.
  - R-60 opens WP-7B-R1 as a ruling.
  - R-41 adopts ES-004.3.
  - R-94 adopts ANNOTATE: "Executed at IMPLEMENTATION_PROGRESS.md:58".
  - R-36 promotes 6 behaviours into AST-013.
  - Commit `6a67da5d7` records WP-4B RED actually written.
- **ACT-REFUSED:**
  - R-81/R-83 provenance annotation: the Chief's five filed rulings are "PREPARED RULINGS AWAITING ADOPTION".
  - R-91 is "HELD … NOT ADOPTED".
  - R-89 is "PREPARED, NOT ADOPTED".
  - The filed R-90 row was "withdrawn before adoption" as operational acceptance (S0804-L576).
  - R-36 "Expressly NOT promoted" candidates.
  - R-77: "ADR-T14 IS NOT SUPERSEDED" after an offered characterization.
  - R-94: supersede was one of three presented outcomes and was not chosen.
- **PERMISSION-STATEMENT:**
  - All 10 START events except the git commit. R-47, R-56, R-58, R-65, R-72, R-79, R-81 and R-86 only say that work is or is not authorized, or "may begin" / "does not begin" / "shall not commence". None of them records a start, or an attempted start being refused.
  - R-60 "A recording note cannot open a work package".
  - R-90 "MUST NOT BE REUSED".
  - R-83's statement that excluding B could be done only by superseding §12, for which "no evidence was presented".
- **GENERIC-PRACTICE:** "the register holds constitutional decisions".
- **UNCLEAR:**
  - ADR-MP and register-numbering: no source text is provided.
  - RAISE L493-B: the excerpt covers lines 480–490 only, and L493-B does not appear in it.

**INFERENCE:** The START operation is almost entirely unattested as acts. Its only attested act is the git commit, and 10 of its 11 coded outcomes (PERFORMED or REFUSED) come from authorization or permission text, not from starts or refusals.

## 2. Witness recount (recomputed from EVENTS.json)

The rule applied:
- The two events have the same `o`.
- One outcome is PERFORMED; the other is REFUSED or NOT-IN-FORCE.
- The events differ in exactly one of r, a, k, s, t, e, x, h, c.
- UNK is compared literally as a value.

| Var | Pairs | Survive | Fail | Why |
|---|---|---|---|---|
| a | ADOPT Chief vs DA (R-86); AUTHORIZE-IMPL R-89 vs R-70 | 2 | 0 | Both sides of each pair are attested acts |
| k | REGISTER op-acceptance vs constitutional; OPEN-WORK note vs ruling | 0 | 2 | GENERIC-PRACTICE / PERMISSION-STATEMENT |
| s | WP-4B R-72 vs git; 7B R-47 vs R-58; 7B R-56 vs R-58; 7C R-58 vs R-65; §12 R-81 vs R-86 | 0 | 5 | At least one side of each pair is PERMISSION-STATEMENT |
| t | none | 0 | 0 | none |
| e | 4 promoted × 3 not-promoted (R-36) = 12 | 12 | 0 | Both sides attested at ruling level |
| c | none | 0 | 0 | none |
| h | ASSIGN never-used vs retired R-90 | 0 | 1 | UNCLEAR + PERMISSION-STATEMENT |

**FORMAL CONSEQUENCE:**
- **surviving_support:** a=2, k=0, s=0, t=0, e=12, c=0, h=0.
- **Before filtering:** a=2, k=2, s=5, t=0, e=12, c=0, h=1.
- **s and h lose all their strict support, and k loses its only two pairs.** The s-dimension evidence was built entirely on authorization or permission rows, not on attested starts.
- **e keeps all 12 pairs, but every one of them rests on a single row (R-36).** The item numbering and e-values behind them come from an unprovided matrix.

## 3. Ambiguities

1. **WITNESSES.md was not read.** TASK.md's input restriction conflicts with its instruction to use WITNESSES.md, so the listed pairs are my own recomputation (UNKNOWN whether they match).
2. **R-89 (NOT-IN-FORCE):** it is unclear whether this is ACT-REFUSED (held by the PREPARED→ADOPTED rule of R-86) or ACT-PERFORMED (the Chief did issue an authorization). The a-witness survives under either reading.
3. **ADOPT by the Chief:** the "declined" wording is the Chief declining to read their own authority broadly. I treated the Chief's filing as an act that was put forward and then held. It could instead be read as a statement of the Chief's authority (PERMISSION-STATEMENT), which would make the first a-pair fail.
4. **R-60 recording note:** I read "cannot open" as a prohibition (PERMISSION-STATEMENT). It could also be read as refusing the motion that had been placed under a notes heading (ACT-REFUSED).
5. **R-83 §12:** no supersession was proposed; the ruling only states when supersession would be needed. I classified it as PERMISSION-STATEMENT; UNCLEAR is also defensible.
6. **R-36 item numbering (#2/#3/#9/#10, #5/#14/#17) and e-values:** these come from `claude/plans/swirling-jingling-blossom.md`, which is not provided (genre "plan"). The row confirms that promotions and express non-promotions were performed, but not which numbered item is which. The R-36 events are also absent from CODING-LINES. (UNKNOWN)
7. **R-41 coded e=1:** the row cites the WP-1 inconsistency *and* "a second instance" caught the same day, so e=1 is itself questionable (INFERENCE).
8. **"ASSIGN a never-used number":** no source is provided. The R-90 row ("Issued as R-90") contains a possible instance, but the event is not anchored to it.
9. **The coded s-values differ between the two files:**
   - Git WP-4B: EVENTS.json s=UNK vs CODING-LINES `auth-full`.
   - R-47 7A/7B: EVENTS.json `authorized`/`not-authorized` vs CODING-LINES both `auth-full(7A)`. Under CODING-LINES, 7A vs 7B would be a **t**-witness (it would fail anyway, since both sides are PERMISSION-STATEMENT).
10. **Other mismatches between CODING-LINES and EVENTS.json:**
    - CODING-LINES has "AUTHORIZE-PLAN R-95 (Chief)", which is not in EVENTS.json, and there is no R-95 source.
    - Lines 27–28 are test fixtures (p/q).
    - The lines carry no "quoted basis", despite what TASK.md says.
11. **Timing oddity:** the git WP-4B RED commit (08-03 16:10) coexists with R-72 ("proviso … NOT MET") and R-77 (08-03, "WP-4B REMAINS BLOCKED"). The sources show no authorization for that start (SOURCE FACT). Whether it was a governance breach is UNKNOWN.
12. **Outcome opposition:** treating NOT-IN-FORCE as opposite to PERFORMED is my assumption.
