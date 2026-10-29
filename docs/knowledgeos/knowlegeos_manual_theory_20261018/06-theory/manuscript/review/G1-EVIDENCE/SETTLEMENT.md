# The G1 theory turn (evidence in the record): settlement

*2026-10-29. Reviewers A (Opus) and B (Sonnet), commission `BRIEF.md`. Reports saved verbatim with sha256: `REPORT-A.md` (`d9bb0c00…e2be1`), `REPORT-B.md` (`c13bbb85…a3d62`; full hashes in the SHA256 files). Draft `a5416f3be` (model v21). Settled with model v22. With the Principal's decisions T10-16 (b) (addendum 10:46:12Z) and on designation and routing (`814b4608a`), applied here: the Principal allowed parts 1 and 3 to be folded in at this settlement.*

"V (Professor)" marks evidence the Professor re-checked or re-ran. "R" marks evidence accepted on a reviewer's reading or probe.

## Reviewer findings and dispositions

| Finding(s) | Sev. | Check | Disposition |
|---|---|---|---|
| A-01, B F1, F2 | **critical** (A) / high | **V (Professor):** reproduced in v22's development (the designation case failed in v21; check 36); the single-use coupling read from `single_use_grants`, `uses_of`, `marks` | Accepted. Containment restated with premises (c1)–(c5). Single-use coupling removed by **W's own rule**: a W entry may not take a single-use grant as a basis (c3; reading of O2, flagged: if the Principal reads the single-use rule to include evidence, (c3) fails). Designation coupling removed by making designation a **record-level clause** (the Principal's decision) and keeping W entries as opaque members (c4). Checks 33 (values and marks, interleaved), 35, 36. "Changes no kernel law" withdrawn |
| A-02, B F3 | high | **V (Professor):** check 34 ("standing outside D_S: refused") | Accepted. `wclass` checks D_S and D_W on the whole record |
| A-03, B F7 | high / normal | **V (Professor):** check 34 (tie behaviour) | Accepted. "g0 reads no order among W's entries; ties reach the class through standing's answers, as U named"; the string test replaced by a behavioural test |
| A-04, B F9 | high / normal | R (A P16, P17) | Accepted. Correction = annulment voids the recorded assertion and is reversible; content found wrong = a refuting entry; only purpose scope separates withdrawal; `erst` kept as proposed, not shown necessary (part of T9-16) |
| A-05, B F15 | high / normal | **V (Professor):** check 34 (causes) | Accepted. Each entry's cause in the reasons; the "no purpose named" listing implemented |
| A-06, B F4, F5 | high | R (A P6, P7; B P5, P6); **V (Professor):** S0888 L360–470 consulted (BRANCH-02) | Accepted. Four choices for the Principal: T9-12, T9-16 (new), T9-17 (new), adoption |
| A-07, B F14 | high / normal | R | Accepted. Answer per class with conditions; G1–G10 table; G7 open; Q4's inputs extended by the purpose, stated |
| B F6 | high | **V (Professor):** §9.10 L114–119 | Accepted. "Not an act" reconciled: the producer's authority is read by Φ's basis clause; the entry is a sink and not a use (c3). Outcomes and source-versus-actor recorded as not carried |
| A-08, B F13 | normal | R | Accepted. Options relabelled: the model's modes are (a) and (c); (b) not modelled |
| A-09, B F16 | normal | R | Accepted. One criterion for generic clauses ("what an entry is or where it belongs"); the store-known clause noted for a later model version (T9-13) |
| A-10, B F8 | normal | **V (Professor):** check 33 restated | Accepted. "By construction" stated for the breach result; generator interleaves; marks compared |
| A-11, B F10 | normal | R | Accepted. CON-0212 cited for the absence clause only; the different sense of NOT ATTESTED stated |
| A-12 | normal | **V (Professor):** S0888 read and logged | Accepted. Added as one line of support, same estate and origin as S0887; CON-0150's first-hand exclusion added |
| A-13 | normal | **V (Professor):** §10.1 L78; S0237 L209 | Accepted. Quotes made exact (and S0237's line corrected from L214 to L209) |
| A-14, B F11 | normal | R | Accepted in part. "Fault containment" used; D_S, D_W and "generic clause" defined in §9.18.6; review IDs kept only in attributions |
| B F12 | normal | **V (Professor):** check 34 (target before the withdrawal) | Accepted. Field presence first; targets must be earlier entries |
| B F17 | normal | R | Accepted. Register statuses carried; S0294's context stated |
| B F18 | — | — | Recorded (process note already in the section) |

**Model:** `ce_model.py` v22; checks 1–32 give output identical to v20 (verified by diff); checks 33–36 pass; run `ce_model-run-20261029-v22.txt`. v21's checks 33–35 are replaced; v21's run stays reproducible from commit `a5416f3be`.

**R1:** 1 flag, pre-existing (CON-0133). **R2** (`--scope 09.18`): 1 tier A, `wclass`, a model identifier glossed as g0. **Quote check:** 0 in the tool's format; the corpus quotes were checked by hand by both reviewers and, for S0237 L209 and S0888, by the Professor. **R3:** the citing lines named by A for §9.18's restated claims (10.03, 10.04, 10.05, the TODOs, THEORY-MAP, the Ch. 9 index) were updated where they stated the withdrawn claims.

**Process deviations recorded:** reviewer A's two recursive greps traversed `chapter_09/review/` without returning or opening anything there; A's `git log` was refused by worktree isolation. Reviewer B ran probes inline after heredocs were refused. Reviewer A read S0888 (allowed). *Recorded.*

## The Principal's decisions applied here (parts 1 and 3; folded in at settlement, as allowed)

- **Designation:** the record element's (§9.18.7; §10.3.7; §10.4 R0; §10.5.2 A).
- **Removing and annulling, classified** (§9.18.7): test "does the operation change any area's answers?"
  - Removal changes no value (§9.16 L79): **record-keeping**.
  - Annulment changes values: **standing's**.
  - The distinction is clear under that test; it does not return to the Principal.
- **SD5-01** (split §10.5.2): accepted, §10.5.2 A/B with a partition table.
- **SD4-04** (R17): accepted, "a consumer, in the delivery role → U".
- **The record element's subdomain class:** supporting (Int, §10.1), rival core recorded.
- **Stale remnants:** §10.4.4 ("doubtful and pending" → "not admitted today"); §10.4.3 ("and on G11" → "on whether norms gains kinds of its own").
- **SD5-02, SD5-04** (into the G1 turn): naming choices touching the promise's words and the decision record's term; prepared as one naming question with SD5-03 (T10-21).
- **SD5-06:** W's refusals carry reasons (G8, check 35); standing's guard returns a reason on all 17 refusal paths. Recommended for publication, not adopted (the Principal's decision authorises clarification only).
- **SD5-03** (part 3): T10-15 marked "to be settled before part 2"; the naming half in T10-21.
- **New relationship R0′** (S → the record element: authority of its operations). The map now has 23 relationships, 16 conditional.

## Levels

Every line of §9.18 and of the Ch. 10 changes stays **S0**. Reviewer A gave (i) the register-style verdict VALIDATED (scope: item level, two polarities) and also stated "Every line stays S0. Nothing here supports raising a level"; reviewer B gave (i) PROVISIONAL. *No level is raised.*

## For the Principal (one question at a time)

1. **T9-12, whose evidence counts:** (a) any producer's; (b) only authorised entries accepted; (c) all accepted and shown, only authorised counted. Recommended (c).
2. Then T9-16 (who may withdraw or restore), T9-17 (how support and refutation combine), adoption of the proposed kinds, and the naming question T10-21.

## Measures

| Measure | Value |
|---|---|
| Reviewer A (Opus), harness | 871.2 s |
| Reviewer B (Sonnet), harness | 692.1 s |
| Review total | 1,563.3 s (26 min 3 s) |
| Turn elapsed | from 10:45:46Z (start) to this settlement's commit (see the session log) |
| Findings | A: 1 critical, 6 high, 7 normal; B: 0 critical, 8 high, 10 normal; all accepted (A-14 in part) |
