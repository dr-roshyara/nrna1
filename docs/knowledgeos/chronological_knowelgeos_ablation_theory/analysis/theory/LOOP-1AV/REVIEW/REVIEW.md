# Review of the 1av blind pilot (norm/act, two-level form)

**Inputs read:** REVIEW-TASK.md, TASK.md, SOURCES.md, PREREG.md, SCORE.json, score_1av.py, PACKAGE-REVIEW.json, CODING-P1.json, CODING-P2.json, NOTES-P1.md, NOTES-P2.md, AUDIT-1AR-WORKER.json, AUDIT-1AR-REVIEW.json.
**Not present in this folder:** `EVENTS.json`. I could not check each event's cluster, target or description beyond the `event_id` string. Anything below that depends on the cluster is marked PLAUSIBLE, not CONFIRMED. No code was run; every number was worked out by hand.

**Verdict in one line:** The arithmetic is right. The 1.000 agreement is what you would expect from two runs of one model on one prompt, and it does not show the distinction is clear. The labels are mostly valid, but three are contestable and shared by both coders. The one departure from the audit (R-60 recording note) lands on the record the prior review marked as verdict-fragile. The pilot shows that option A can be coded. It gives no evidence against option B, because B was never tested.

---

## 1. Scoring: recomputed by hand. All figures match.

| Measure | SCORE.json | Recomputed | Basis |
|---|---|---|---|
| observation_type, 4-class | 1.000, κ 1.000 | **1.000, κ 1.000** | 36/36 labels identical. p_o = 1, p_e < 1 |
| ACT binary | 1.000, κ 1.000 | **1.000, κ 1.000** | same |
| Class counts (each coder) | 20/12/1/3 | **ACT 20 · NORM 12 · GENERIC 1 · UNKNOWN 3** | ACT: 3 ADOPT, 2 AUTHORIZE-IMPL, R-90 REGISTER, 2 OPEN-WORK, git START, 7 R-36 + R-41, R-77, 2 R-94 |
| source_act.operation | 0.972, κ 0.965 | **35/36 = 0.972; κ = (0.9722−0.2137)/0.7863 = 0.965** | Only difference: *RAISE L493-B*, P1 `UNKNOWN` vs P2 `REGISTER`. p_e = 277/1296 |
| dual = YES | 27 / 27 / both 27 | **27 / 27 / 27** | NO = 6 (R-90 REGISTER, REGISTER rule, git, R-90 retired, R-94 ×2). UNK = 3 (the UNKNOWNs). Identical in both coders |
| UNKNOWN share | 0.083 | **3/36 = 0.083** | |
| vs audit | 0.972, κ 0.952 | **35/36; κ = (0.9722−0.4213)/0.5787 = 0.952** | The audit equals the reviewer classes, because `classes_reviewer` covers all 36. Audit counts 19/13/1/3. p_e = 546/1296 |

The scorer logic is sound. One consequence is worth knowing: because the review file lists all 36 classes, the "audit" is entirely the 1ar reviewer's classes, and the worker's classes play no part. This makes no difference here, because worker and reviewer differ only on R-89, and both of their classes for R-89 map to ACT.

## 2. Degenerate agreement

**Textual overlap of `type_quote`, 36 events (normalised for `**`/`*` markup and `…` vs `...`):**

| Overlap | Events |
|---|---|
| **Identical span (25)** | R-86 ADOPT · R-72 · R-47 7B · R-36 ×7 · REGISTER rule · R-41 · ASSIGN never-used · ASSIGN retired · D-12 · R-94 ×2 · R-56 · R-58 7B · R-58 7C · R-65 · R-81 · git · R-60 note · R-60 refiled |
| **One quote contains the other, extended by one clause (9)** | Chief annotation (P1 adds "— that is the one reading…") · R-91 (P2 adds "The Decision Authority held it") · R-70 (P1 adds "NO EXTERNALLY OBSERVABLE…") · R-90 REGISTER · R-79 · R-47 7A · R-77 · R-83 · R-86 §12 |
| **Different span (2)** | R-89 (P1: body + provenance; P2: heading) · L493-B (P1: excerpt header; P2: the vocabulary ruling) |

**Estimate:** about **88% span-level overlap** (25 × 100%, 9 × ~70%, 1 × ~20%, 1 × 0%). 34/36 quotes share a common verbatim core.

**Beyond the quotes:**
- `source_act.operation` matches on 35/36, and the only miss is on an UNKNOWN event. Both coders also invented the **same free-text verbs**: `OTHER:NARRATE` (×3), `OTHER:DEFER`, `OTHER:ADJUDICATE`. `OTHER:<verb>` is an open slot, so two independent coders would be expected to diverge there.
- The anchor strings are near-identical: "as carried **on** R-81 and R-83" vs "as carried **in** R-81 and R-83"; "R-91 (HELD annotation)" vs "R-91 (HELD annotation 2026-08-04)".
- `deontic` matches 11/12 (R-83: REQUIRED vs UNK). `dual` matches 36/36.
- The notes flag the same hard events with the same arguments. Both mention: R-77's same-day "WP-4B REMAINS BLOCKED" against the git commit; the missing matrix `swirling-jingling-blossom.md`; "could also be read as NORM_STATEMENT" for the REGISTER rule; the R-60 split; and R-83 as PREPARED until R-86.

**The decisive observation.** On the events that the coders' **own notes call ambiguous**, both chose the same side every time:
- R-60 note: "genuinely split" (P1), "sits between" (P2). Both chose ACT.
- REGISTER rule: both chose GENERIC, and both noted it could be NORM.
- R-83: both chose NORM, and both noted it could be a refused option.
- R-89: both chose ACT, and both noted it is PREPARED.
- R-77: both chose ACT on an implicit proposal.
- R-36 item mapping: both chose ACT, and both called it unverifiable.

That is 6 of 6 self-declared hard cases resolved the same way. If those cases were really open, independent coders would split on some of them; a chance of roughly 1/2 per case gives about 1/64 for 6 of 6. This pattern points to **shared deterministic resolution (or shared bias)**, not to a clear boundary. Perfect agreement on items each coder calls unclear cannot be evidence that the distinction is clear.

**A second independence problem: the event ids carry cues.** The `event_id` strings encode the intended reading, for example "(permission only)", "(proviso unmet)", "(remains unauthorized)", "(declined)", "(held)", "(rule)", "promoted / not promoted", "declined / chosen". Those descriptions come from the audit side. They drive both coder agreement and convergence with the audit. So PREREG's "coders do not see the audit classes" holds only in letter.

**What would tell clarity apart from determinism:**
1. Coders from a different model family, or human coders.
2. A within-model baseline: the same model re-run with a paraphrased TASK.md, shuffled event order, and **event ids stripped to operation + anchor + target**. If the between-coder agreement is no higher than this re-run baseline, the 1.000 is determinism.
3. A tie-break flip test: tell one coder "when in doubt, prefer NORM" and another "prefer ACT". Labels that flip mark the real boundary.
4. Report agreement separately on the hard subset (the ~8 events flagged in the notes) and on effective units. The R-36 cluster counts as one unit, so there are ≈ 28 row-units.
5. A small gold set of minimal pairs: the same row text coded against different operations, e.g. R-58 against START vs against AUTHORIZE-IMPL. This tests validity, not only reliability.

## 3. Validity against SOURCES.md

I checked all 36 events: all 12 NORM, all 3 UNKNOWN, the audit-difference event, and every ACT. Full list in REVIEW.json `validity_checks`. Summary:

- **Correct (27):**
  - ADOPT (3): the Chief's decline, R-86, and R-91 held.
  - R-70, and R-89, which performs the authorization in its PREPARED form.
  - R-90 REGISTER: filed, then withdrawn.
  - R-60 refiled, the git START, and R-41.
  - R-77: an offered supersession question, adjudicated and refused.
  - R-94 ×2, and the REGISTER rule (GENERIC).
  - 11 of the 12 NORM events: R-72, R-79, R-47 ×2, R-56, R-58 ×2, R-65, R-81, R-86 and R-90 retired. For each of the ten register-row START norms I confirmed that no START is recorded; only permission or prohibition is stated.
  - All 3 UNKNOWN.
- **Contestable, and shared by both coders (the "same wrong way" cases, 9 events):**
  - **R-36 ×7 (RAISE promoted #2/#3/#9/#10, not promoted #5/#14/#17).** The row attests the *class-level* promotion act ("Promoted into AST-013 … 6 one-line behaviours"; "Expressly NOT promoted: …"). It does **not** attest the act *on the event's target* (item #N). Those items live in a matrix that was not provided, and both coders concede this ("If item-level verification is required, all seven become UNKNOWN"). The coders applied the opposite standard to L493-B, which they coded UNKNOWN because its line is not in the excerpt. By the task's own question ("attested … on this target"), strict validity is UNKNOWN for all 7. "Not promoted" also covers re-routing ("Category-B follow-ups … separate slices") and holding ("remain Candidates"), not only refusal. That still counts as ACT under the "refused or held" clause, but only at row level.
  - **R-83 (SUPERSEDE §12), NORM.** "cannot be achieved by SCOPING — only by SUPERSEDING §12 — and no evidence was presented for superseding it" is a necessity argument, not a permission, prohibition or requirement to supersede. P1's REQUIRED misreads a conditional as a requirement; P2's UNK admits the fit is poor. The row actually rejects an *option* (exclude B) whose only route would have been supersession. That reads more like a refused implicit proposal (ACT) or UNKNOWN than like a NORM. The audit shares this label.
  - **R-60 recording note:** see §4.
- **Quote weaknesses that do not change any label:** Both coders open the R-56 quote with the sentence inside "*(Recording note, not part of this ruling …)*". The operative anchor is the outcome column, "execution still NOT authorized"; the prior audit's D6 made the same point. P2's quotes keep stray `**` markers, e.g. "7A ONLY — 7B and 7C are NOT authorized** and follow…".

## 4. The one audit difference: `OPEN-WORK by a recording note (R-60)`

- **Pilot (P1 = P2):** ACT_OBSERVATION, read as a refused note-based opening.
- **Audit (worker and reviewer):** PERMISSION-STATEMENT → NORM, because "the opening was granted by refiling the text as a ruling and nothing was turned down".
- **Source:** "the issuing text placed this motion under a heading reading "Recording Notes -- Not Part of Ruling", while its own handover statement and governance queue both declare the package OPEN. A recording note cannot open a work package — that is the ruling/note distinction this register enforces — so it is filed as a ruling in line with the operative text."

**Who is right: the audit, narrowly.** The row attests one *opening*, and it succeeded as a ruling. It also attests a *reclassification* of the note heading. What it says about "opening by a recording note" is a power rule ("cannot open"). Nobody's note-based opening was turned down; the note label was overridden "in line with the operative text". The coders' reading has a foothold in TASK.md ("attempted … and then refused") and in the event id's framing ("*by a recording note*"). So this is a genuine boundary case, which again shows that the pilot's unanimity is not evidence of clarity.

**Why it matters (substantive):** PACKAGE-REVIEW's fragility note says "If R-60-note is blind-recoded ACT-REFUSED, k becomes WEAK, not 0". That recode is exactly what the pilot produced, from two coders of the same model. If the human took the pilot's class at face value, k would move from 0 to WEAK. I do not think the pilot supports that move, for the reasons above.

## 5. Dual nature (F4)

**Distribution.** Of the 36 events, 29 are anchored on a register row; 3 on the session log (all dual NO); 1 on the git commit (NO); and 3 are UNKNOWN (UNK). **Dual = YES on 27 of the 29 register-row events**, and the only exceptions are the two R-94 events. So `dual` in effect encodes "the anchor is a ruling row".

**Five YES cases checked:**

| Event | The row's own act | Norm about another act | Verdict |
|---|---|---|---|
| R-86 ADOPT | ADOPT R-81..85 | "Any future ruling issued by the ARB Chief remains PREPARED, NOT ADOPTED, until the Decision Authority acts on it" | Genuine |
| R-77 | adjudicates a characterization | "supersession requires an EXPLICIT ACT, NEVER INFERENCE" | Genuine |
| R-60 note | OPEN-WORK | "A recording note cannot open a work package" | Genuine |
| R-70 | AUTHORIZE-IMPL | "NO EXTERNALLY OBSERVABLE BEHAVIOUR SHALL CHANGE" | True, but trivial: an authorization's content *is* a norm |
| R-36 ×7 | ADOPT promotion | the promoted behaviours | True at row level; one row counted 7 times |

**All NO cases checked.** The three log-anchored events and git are NO by definition, since the anchor performs no listed act. **R-94 NO is questionable, and it goes the other way:** "PB-006 is neither reopened nor re-verified" is a norm about other acts, so by the task's definition R-94 is dual. The coding is therefore not too liberal. It is **definitional**: in a norm register, a row that performs a governance act nearly always states a norm, and a register-anchored NORM record (11/11) is dual by construction.

**What the frequency implies.** The count supports the need for a **mandatory source_act** in this genre: for the 11 register-anchored NORM records, the row's act (AUTHORIZE-IMPL, ADOPT, OTHER:DEFER, AUTHORIZE-PLAN) differs from the event's operation (START, SUPERSEDE, ASSIGN-ID). A single observation_type cannot carry both. But the `dual` flag itself adds almost nothing beyond `source_act` plus the anchor genre. And "27/36" measures the source genre, not a phenomenon the coders detected independently. PREREG's sealed expectation (≥ 10 NORM dual) was met, 11/12, but it was met by construction. Whether dual status matters for *verdicts* depends on whether NORM records enter any analysis. Under the prior review's rule, NORM records never enter R3. The ACT records whose outcome rests on a norm determination are few: the Chief's decline, R-91 held, R-89.

## 6. UNKNOWN (F5)

**Only two of the three are cases with no source text.** `ASSIGN a never-used number` (register-numbering) and `SUPERSEDE D-12 by ADR-MP` have none, matching SOURCES.md's "NO SOURCE TEXT PROVIDED". **`RAISE L493-B` is different:** its cluster excerpt (S0815, lines 480-490) is provided, but the target line is not in it. P2 even found a plausible candidate (the "BINDING VOCABULARY RULING — applies to ALL future documentation, not just this artifact", which raises a single finding to a general rule) and anchored it as `REGISTER`. That produced the only source_act disagreement. UNKNOWN is still the right label for all three.

Under a strict reading of "on this target", the UNKNOWN share of 0.083 is a **lower bound**. The 7 R-36 item events (and arguably R-83) would also be UNKNOWN, which gives up to 10–11/36 ≈ 0.28–0.31.

## 7. What the pilot can and cannot establish for A (two-level r2) vs B (minimal v1 rules)

**It can establish:**
- The two-level form can be filled in: every field was completed, source_act matched 35/36, and deontic matched 11/12.
- The form is reproducible *within one model on one prompt*. This is the upper bound PREREG itself names.
- In this norm-register genre, most records have a stating act that differs from the event's operation. So any analysis that needs *who stated the norm* needs a source_act-like field, and B does not record one.
- The scorer and SCORE.json are arithmetically correct.

**It cannot establish:**
- **Reliability in the F3 sense.** The coders are not independent: same model family, near-identical text, identical invented verbs, the same side on all 6 self-declared hard cases, and cue-bearing event ids.
- **Validity.** Agreement is not correctness. The 7 R-36 records and R-83 are contestable in the same way for both coders, and the one audit departure (R-60) is, on my reading, the pilot's error.
- **Convergence as external validation.** The audit is the same model family and cued by the same event descriptions.
- **Anything about B.** There was no B arm, and F1 ("v1 coders reproduce the error") remains untested. The pilot removes one objection to A (that it can't be coded). It adds no evidence that B is insufficient for act-only verdicts.
- **Generalisation.** There are 36 events and ≈ 28 independent row-units; 7 events come from one row, and only one non-register execution record (git) exists. With 0 disagreements in ≈ 28 units, the rule-of-three lower bound on agreement is ≈ 0.89 even under independence. The real bound is weaker, since independence fails.

The adoption threshold is the human's, and I do not set it. The next test that would actually discriminate between the options is a cross-family or perturbed-prompt recode with event ids stripped, plus a B-arm coding the same 36 events under the v1-internal rules. Both arms should be scored on verdicts (k, s, h, a, e), not only on classes.

## Issues and classification

| # | Issue | Class |
|---|---|---|
| I1 | 1.000 is consistent with near-deterministic same-model output: ~88% quote overlap, identical OTHER verbs, the same side on 6/6 self-declared hard cases | independence |
| I2 | Event ids encode interpretive cues ("permission only", "declined", "held", "rule") drawn from the audit side | independence |
| I3 | Convergence with the audit is not external validation (same family, same cues) | independence / evidence scope |
| I4 | R-36 ×7 coded ACT although the item-level target is unattested; inconsistent with L493-B → UNKNOWN | source interpretation |
| I5 | R-83 coded NORM (REQUIRED/UNK) for a necessity argument; better read as a refused implicit option or UNKNOWN; shared with the audit | source interpretation |
| I6 | R-60 note: the pilot's ACT is weaker than the audit's NORM; the case is verdict-fragile (k 0 → WEAK) | source interpretation / substantive |
| I7 | `dual` ≈ "the anchor is a ruling row" (27/29); YES is definitional, not liberal; R-94 NO is the inconsistent case | coding / formal reasoning |
| I8 | F4 "confirmed" by construction; it shows source_act is needed, not that dual is an independent frequent phenomenon | formal reasoning |
| I9 | Only 2 of the 3 UNKNOWNs have no source text; L493-B has partial text; the UNKNOWN share is a lower bound | evidence scope |
| I10 | Effective N ≈ 28, not 36 (R-36 cluster, paired rows) | evidence scope |
| I11 | R-56 quotes open with the recording-note sentence ("not part of this ruling"); P2 quotes carry stray `**` | wording |
| I12 | No B arm: the pilot cannot bear on A vs B beyond showing A can be coded | evidence scope / substantive |
| I13 | EVENTS.json is absent from the review folder; clusters and targets are unverifiable here | evidence scope |
