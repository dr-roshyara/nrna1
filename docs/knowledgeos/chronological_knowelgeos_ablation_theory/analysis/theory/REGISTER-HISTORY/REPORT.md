# L0-REL-30: M3 invariant I-B4 tested on the rulings register's git history

| | |
|---|---|
| Status | research record, not canonical, authority none |
| Scope | **a census, not a sample:** all 71 rows over all 54 commits (2026-07-09 to 08-04), followed across the rename from `docs/adr/` |
| Method | `REGISTER-HISTORY/SPEC.json`, frozen with `register_history_check.py` before the first run (`f338d94ee`) |
| Instrument defect | **r1 was defective and is disclosed.** git ignores `--follow` when combined with `--reverse`, so r1 read only 1 of the 54 commits. The r1 output is retained as `RESULT-r1-DEFECTIVE.json`. The listing was fixed in `bcbeca945`; the method is unchanged |
| Result | `RESULT.json` (`c22ecf9b…`) |
| Post-hoc analysis | `POSTHOC.json`: secondary, labelled post-hoc. It does not change the primary result |
| Text exposure | no ruling text was printed or stored: only IDs, commits, classes and character counts |
| Log | F-LOG-0126 |

## Primary result (frozen classification)

| Cell | UNCHANGED | APPEND-ONLY | INSERT-ONLY | MODIFIED | REPLACED |
|---|---|---|---|---|---|
| Column 3 (decision / ruling) | **64** | 3 | 1 | 2 | 1 |
| Column 4 (effect / annotations) | 49 | 17 | 2 | 0 | 3 |

- There are no deleted rows and no duplicate IDs in any commit.
- **Falsifier candidates under the frozen rule:** R-37, R-38, R-51, R-96, R-98, R-99 and R-100.

## Post-hoc (mechanical, no text)
- **Timing:** for **all 7** candidates, the decision-cell change happened **on the same day as the row's first record**.
  - Result: **no decision cell was ever changed on a later day (0/71).**
- **Annotation markers** (⚠️ ✅ ⛔ or a bold capitalized label) appear in the additions of R-37, R-38 and R-100. The frozen regex missed them. The concatenated cells are INSERT-ONLY for R-37, R-51 and R-100, which is consistent with labelled additions.
- **Genuine in-place edits**, with deletions or replacements inside the decision cell:
  - **R-96:** REPLACED, similarity 0.43;
  - **R-98:** MODIFIED, 37 characters deleted, 2 edit commits;
  - **R-99:** MODIFIED, 37 characters deleted, 3 edit commits.
  - All three are dated 2026-08-04.
- R-51 is a 12-character insertion with no marker. It is unresolved.

## Verdict
- **The strict form of I-B4** ("decision text is never edited after its first record") is **FALSIFIED** mechanically, by R-96, R-98 and R-99.
- **The census result survives:** no decision cell changes after the day of its first record, and later change is annotation-only.
- **Competing refined hypotheses** (not yet decided):
  - **H-status:** decision text is amendable while the ruling is PREPARED, and immutable once ADOPTED/governing. This is source-motivated: the R-81…R-85 annotation, read earlier, says "a correction amends them before engineering builds on them".
  - **H-window:** edits are possible only inside a drafting window, before the ruling is relied on (for example, cited by another row).
  - **H-strict-with-exceptions:** the edits are violations the corpus did not record as such.
- **Discriminating observation:** the status of R-96, R-98 and R-99 at the time of each edit, plus the kind of edit. That needs a bounded read of those diff hunks (the spec's own rule: the diffs require a separate L0 release).

## L0-REL-31: what the same-day edits were (spec `SPEC-REL31.json`, frozen `75ce9526c`; extract `REL31-EXTRACT.txt`)

| Edit | Pre-edit status (frozen regex) | Cited by other rows | Kind |
|---|---|---|---|
| R-96 @562492d2b | NONE | 0 | LABELLED-CORRECTION, substantive and in place: "⚠️ CORRECTED 2026-08-04, SAME DAY, BEFORE ANY ENTRY WAS CREATED"; the owner assignment is removed ("the classification stands; the owner assignment does not") |
| R-98 @6c6d5866c | NONE | 0 | annotation insert ("✅ ACCEPTED BY THE ARB CHIEF"). Outside the frozen kind list; permitted by every hypothesis |
| R-98 @2f804b81e | NONE | **1** | LABELLED-CORRECTION, substantive and in place: "⚠️ QUALIFIED BY THE ARB … this is NOT 'no work remains'"; "stewardship mode" narrowed to "FOR THE CURRENT AUTHORIZED SCOPE" |
| R-99 @6c6d5866c | NONE | 0 | annotation insert ("✅ HOLD CONFIRMED … with a REFINEMENT") |
| **R-99 @f0ec38daa** | NONE (the row's text says "(HELD)") | 0 | **SUBSTANTIVE, not labelled as a correction.** The headline status is rewritten in place: "⏸️ … HELD FOR A REFERENT" becomes "✅ … CLOSED … the ruling takes effect UNAMENDED" |
| R-99 @8388ce911 | NONE | 0 | LABELLED-CORRECTION ("an earlier version delimited the subject as '145 artifacts'"; "AN ARTIFACT COUNT IS NOT PART OF THE DEFINITION") |
| R-51 @3be1e088e | NONE | 0 | STRUCTURAL: a one-word label insertion (commit: "separate ARB rulings from recording notes") |

### Hypothesis results (frozen decision rule; one counter-instance falsifies)
- **H-status (amendable while PREPARED): FALSIFIED as frozen.** The only SUBSTANTIVE edit is R-99 @f0ec38daa, and its pre-edit status was not PREPARED. The row was **HELD**.
  - The post-hoc variant H-status′ (amendable while *not governing*, i.e. PREPARED or HELD) is consistent with this edit. It is recorded, not adopted.
- **H-window (editable only before anything cites it): SURVIVES.** The SUBSTANTIVE edit had 0 citing rows.
  - The one edit made after a citation (R-98 @2f804b81e, cited by 1 row) is a **labelled** correction, which the frozen rule excludes.
- **H-strict-with-exceptions: NOT SUPPORTED.** No unlabelled substantive edit occurred on a cited, non-PREPARED ruling.

### What the census plus the diffs now say (SOURCE-FACT unless marked)
1. **Across days, decision text is never edited (0/71).**
2. **Within the first day, three practices occur:**
   - labelled in-place corrections (R-96, R-98, R-99). One of them was made after the ruling was already cited (R-98);
   - labelled annotation inserts;
   - **one status transition written as an in-place headline rewrite** (R-99: HELD → CLOSED). Elsewhere status changes are annotations (R-81…R-91), so this is a **second record practice for status**.
3. **The source's own licensing condition for in-place correction:** "SAME DAY, BEFORE ANY ENTRY WAS CREATED" (R-96). That is a *window* condition, stated by the source.
4. **Instrument gaps (disclosed):**
   - the frozen status regex had no HELD class;
   - the kind list had no ANNOTATION class.

   Both were handled conservatively. Annotations are excluded as permitted, and HELD was reported as NONE, which is what the regex returns.
5. **Open (MODEL-INTERPRETATION):** R-96…R-99 are ARB-Chief acts (accept, hold, correct) that carry **no PREPARED/ADOPTED** marker. The PREPARED mechanism may be **act-type-specific**, i.e. rulings needing Decision-Authority adoption vs Chief-level acts, and not only issuer-specific.

### Revised record invariant (candidate for M4; not adopted)
- **I-B4′:** text immutable after the drafting window (the day of first record).
- Within the window, edits are labelled corrections, annotations, or (once) a status rewrite of an uncited, non-governing ruling.
- **Falsifier:** any decision-cell change on a later day, or an unlabelled substantive edit to a cited or governing ruling.
