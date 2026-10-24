# Theory turn 8: commission, steps 2 and 3

*Given to reviewers A (Opus) and B (Sonnet) after their step-1 inventories were saved with their sha256. W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`. REVIEWER-INSTRUCTIONS (topic review) governs. Re-read your saved inventory first.*

## The question and the deliverable

- **Research question:** `review/TURN8-RECORD-IDENTITY/RESEARCH-QUESTION.md`, committed before any search (`18c37dde3`), with the falsifiers stated there.
- **Deliverable (draft, commit `ca75864ef`):** `06-theory/manuscript/chapter_09/09.17-the-designated-record-and-its-anchors.md`, new, **in full**.

## Before and new

| Object | Before | New |
|---|---|---|
| §9.15 K2 | "a single view": readers agree on the entries of the cut | **restated**: a designated record, K2-d / K2-w / K2-r (§9.17 R2). The old row is in `chapter_09/09.99-change-log.md` |
| §9.15 K1 | refutable "with an external anchor, yes" | points to §9.17 R4 (coverage, independence, operand) |
| §9.15 R7 | L1–L10 | **L11 record designation** added |
| Register | — | CON-0261 (the designated record), CON-0262 (membership by the designation in force), CON-0263 (detection by an outside reference); follow-up notes on CON-0239, 0243, 0251, 0254, 0257, 0259 |
| Ch. 8 | open questions in §8.18.3, §8.19.3, §8.20.3, §8.21.3 | each points to §9.17 |
| EXTERNAL-SOURCES | — | EXT-095–097, **not opened** (from the Professor's knowledge) |
| Map, outline | — | a §9.17 line; the register count line (it read 122/93 after the consolidation's stage 2, an uncorrected count, now 124/94 with 263 concepts) |
| Tool | — | `tmp/tools/anchor_model.py`, run `anchor_model-run-20261024.txt` (32,000 scenarios) |

`git show ca75864ef` lists every changed line.

## The previous settlement's changes (Rulebook v1.4 §2 step 9: changed theory, in full)

The consolidation S0131–S0150, stage 2 (`1404bb448`; `review/CONSOLIDATION-S0131-S0150/SETTLEMENT.md` §2–§4 lists each change). It touched §8.18–§8.21, Ch. 2 and Ch. 3 throughout, Ch. 1 L269 and T1-02, the register and the map.
- **Review in full what this turn depends on:**
  - §8.18–§8.21;
  - CON-0239, 0240, 0242, 0243, 0245, 0249, 0251, 0253, 0254, 0257–0260;
  - the A-16 premises.
- **The rest (Ch. 2, Ch. 3):** check the restatements that settlement made against the copy's findings: CON-0162, the Ch. 3 re-verdicts and CON-0258. Report what you could not cover.

## Dependencies of §9.17 (structure only)

| §9.17 rests on | Status |
|---|---|
| §9.13.2 D and CE* (CON-0191) | PROVISIONAL: **unverified** dependency |
| §9.15 K1, K2, the criterion §9.15.2, L5 (CON-0193) | PROVISIONAL: unverified |
| §9.16 R2 (d), cuts across records | settled at turn 7, then C19 |
| §1.16.7, prevention versus detection | Ch. 1 |
| §8.18 C1 / CON-0239 | VALIDATED |
| CON-0243, 0251, 0254, 0257, 0259 | PROVISIONAL: unverified |
| CON-0245 | VALIDATED |
| §8.19 C18 (S0138 L119–127) | Src |

## Challenges recorded by the Professor (§9.17.4)

They are in the section's table: counterexample tried, boundary, rival and falsifier for R1–R4, and L11 as a definition. They are **records, not amendments**. Judge each **adequate or inadequate**. For R2, R3 and R4, give a **proof-step table** (REVIEWER-INSTRUCTIONS, "Refutation adequacy"). `tmp/tools/proof_step_check.py` supports it.

## Mechanical checks at the draft

- **Quote check** (`corpus_quote_check.py`): §9.17 12 found, 0 not found. §9.15, §8.18–§8.21: 0 not found. Baseline: 0 inherited failures (`tmp/tools/QUOTE-BASELINE.txt`).
- **R1** (`register_home_check.py`): 239 rows with a home; 1 flag, CON-0133, inherited, outside this turn.
- **R2** (`undefined_terms.py`, with the new tier D):
  - tier A in §9.17: `TURN8`, the review-folder identifier in the header (explained);
  - tier C: `append`, the kernel append of §9.13 (explained);
  - tier D in §9.17: `loss-free` (a definition of loss was added before this brief); `enumeration`, defined in R3 (c);
  - the remaining §9.17 tier-D rows are ordinary words: irreversible, ought, scenario, particular, replaced, dropped, weak, remove, equal, comparison.
  - §9.15's tier-D rows are on lines this turn did not change.

## Prior work (practice 2)

- **Logged scans:** six patterns with the prefix `TURN8:` in `tmp/corpus-reading/READ-LOG-CHRONO-20261018.tsv`.
- **Selection rule, fixed before reading:** hits in two or more scans, or three or more in one. It selects 36 files. The selection list itself was lost with the scratchpad; the patterns are logged and re-runnable.
- **Read whole as topic-full:** the six sources of step 1, logged by `close_source.py`.
- **S1375** was added by judgement, below the threshold: a deviation, recorded in §9.17's References.
- The Ext works are marked not opened.

## Questions in particular

1. **R2.**
   - Is M2 a valid countermodel to "K1 and K2 imply that the answers are answers for a cut of ρ"?
   - Is "K2-r ⇒ old K2" proved?
   - Is the classification (K2-w and K2-r kernel assumptions by §9.15.2's criterion; K2-d's competence as L11) consistent with §9.15.2 and with K3's treatment of inputs?
2. **R1.** Is designation an input of CE*, or does it duplicate K3 (the inputs)? Is L11 necessary, or already covered by DB-1 or L5?
3. **R3.**
   - Is (a) a proof?
   - Is (c)'s "exactly when recorded with an enumeration" an iff, or only an if?
   - Is the "if" / "only if" split for CON-0251 right?
   - Does R3 (d) restate CON-0254 correctly?
4. **R4.**
   - Do the model's scope and simultaneity assumption support "together sufficient"?
   - Is A1 as defined ("after the last write before the act") the same as CON-0243's cut condition?
   - Is the three-way decomposition minimal: is any condition derivable from the others?
5. **Trivial cases and must-do.** Is anything in §9.17 satisfied by a do-nothing kernel in a way that empties it?
6. **Coverage** against your inventory: COVERED / MISSING / DISTORTED / CONTRADICTED / OPEN / JUSTIFIABLY EXCLUDED, ranked.

## Access (binding)

- **Allowed now:** the working book (W/06-theory), the register, the map, the outline, W's tools and git history, this review folder, and the six sources.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`; list any you open.
- **No Ch. 0–2 basis files.** Never `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, or `docs/knowledgeos/chronological-read/*`.
- Do not read the other reviewer's inventory or report. No corpus or book text to any outside service. Edit nothing.

## Report

- Begin "TURN 8 REPORT A" (or B).
- Use REVIEWER-INSTRUCTIONS' six parts, about 1,200 words before the appendix.
- Finding IDs A-nn / B-nn.
- Severity as defined there: a missing premise that only F4 makes critical, with nothing depending on it, is high.
- End with the files you opened and the searches you ran.
- Deliver it once, as your final answer.
