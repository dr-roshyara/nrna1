# KC-1 blind2: pre-registration (normalized-specificity determinacy replication)

Written 2026-10-17, **before any encoding of `packet_A.md` or `packet_B.md` exists** (`blind2/results/` does not exist at the time of writing). Nothing here is adopted or decided; this fixes the test before data.

## 1. Purpose and change from `blind/`

The first blind test (`../blind/`) compared encoders of interpretation A (T3) and interpretation B (principle C), but the packets differed in specificity: A carried an operational gloss, B omitted principle C's unfolding clause, its bound-variable sort rule and several shared rules. Here **each packet carries its convention's complete operational rule set**, quoted as closely as possible from the sources, at about equal length (section 2: A′ 404 words, B′ 393 words). Rows, concept list, glossary, task text and output format are identical across packets. Source trace and every paraphrase: `key_sources.md`.

## 2. Hypotheses

- **H1:** under equal specificity, **B′ encoders still disagree** at the guard-row verdict level on **at least one** of the six guard rows (R11, R14, R15, R16, R17, R21).
- **H2:** **A′ encoders agree** on **all six** guard-row verdicts.

H1 is supported if, for at least one guard row, the three B′ verdicts are not all equal. H2 is supported if, for every guard row, the three A′ verdicts are all equal. No other outcome is treated as support. Neither hypothesis concerns agreement with T3's own verdicts; that comparison is reported as a secondary, descriptive result.

## 3. Units

- **Primary unit: the guard-row verdict** for each of R11, R14, R15, R16, R17, R21, one of **CB1-only / CB23-only / both / none**. It is derived **afterwards by the analyst** from each encoder's item set, using the T1 placement (the CB1 / CB23 sets of T3 §1a [r1.6]) through the existing scripts: the encoder's C-id list is converted by `conv()` of `../blind_eval.py` (C1 becomes the actor flag) and classified by `side()` of `AUDIT-Q6-TEST1-scripts/blind_enc.py` (loaded via `KC-MKC-REASSESSMENT-scripts/mkc.py`, as `blind_eval.py` does). Mapping: `CB1` → CB1-only, `CB23` → CB23-only, `X` → both, `none` → none. Items with no side (C22, C25, C26, C28) contribute nothing.
  - **Spanning rule:** the primary verdict is computed under **S′** (C1 counted on neither side). The verdict under **S** is reported alongside; any row where S and S′ differ is flagged. This choice is fixed now and is not a selection among S, S′, S″ (K14 stays open).
- **Secondary unit: exact item-set match per row**, for all 11 rows (6 guard rows and the controls R1, R6, R9, R79, R80), within each arm.
- Controls are reported for both units; they enter neither hypothesis.

## 4. Design

- **3 encoders per arm** (A′: A1, A2, A3; B′: B1, B2, B3). Each receives exactly one packet file and nothing else, with the instruction not to read any other file. Encoders are independent runs; none sees another's output.
- Outputs are saved unedited as `blind2/results/<id>.json` (the JSON object) and `blind2/results/<id>.txt` (the full reply including the one-line reasons). A reply whose JSON is invalid or misses a row is re-requested once from a fresh encoder and the replacement is recorded.
- The packets ask for a one-line reason for **every** row (not only the guard rows, to avoid disclosing which rows they are).

## 5. Statistics

**Agreement statistics are descriptive only.** Per arm: number of guard rows with unanimous verdicts (of 6); per-row verdict pattern; exact item-set matches per row (pairs agreeing of 3). **No significance testing** is done, and no p-value, confidence interval or kappa test is reported as evidence for H1 or H2 (n = 3 per arm).

## 6. Classification of disagreements

Every disagreement (primary unit, and secondary unit on guard rows) is classified **afterwards by the analyst, using the encoders' stated reasons**, as one of:
- **instruction gap:** the packet lacks a rule needed to decide the row (e.g. a reason says the rule set is silent, or the encoders fill the same hole differently);
- **inherent:** the rule as given admits both readings (the encoders cite the same rule and apply it differently).

The classification of each case and the reason lines it rests on are reported. Where the reasons do not allow a classification, it is reported as "unclassified".

## 7. Limitations

- **Λ7: all encoders are Claude-family models.** Agreement may reflect a shared model prior rather than determinacy of the rule text; disagreement may reflect sampling. Results do not generalize to human encoders.
- n = 3 per arm; a single disagreement decides H1, so H1 is sensitive to one noisy run.
- Cited definitions, axioms, clauses and rows are not supplied (task text); both rule sets ask encoders to read some of them, so some encodings rest on the encoders' handling of unsupplied text. This applies to both arms but more to B′ (unfolding, row references read through).
- B′ deliberately omits the §2.4 parse of "object-level parts" (see `key_sources.md`); A′ omits T3's enumerated guard list and R14 example. The analyst's paraphrases are listed in `key_sources.md`.
- The verdicts depend on the T3 [r1.6] placement used by the scripts.

## 8. Decision relevance

The result informs **whether B′ (principle C) still needs a named parse** for denial rows (the "object-level parts" of a statement) before it can be used as a determinate reading convention. If H1 holds with disagreements classified "inherent", the rule text of C does not determine guard-row verdicts and a named parse is needed. If B′ encoders agree, or disagree only through "instruction gaps", that need is not shown by this test. This test decides nothing by itself; it is evidence for KC-1.

## 9. Integrity

Hashes (sha256). The hash of this file is computed over its content **with the single line beginning `- prereg.md:` removed** (that line and its newline deleted; all other bytes unchanged).

- packet_A.md: 7a62f67636d165583c5638897b3c6fa5775a5b76062508b6e46b24ce72a932c8
- packet_B.md: 08225e82501d3f865d66fdaab36114f23c7a717a2de2f679b36719c689224fd4
- prereg.md: 2055aa682a314b2ff4c5cfc4066f42d697941672f82745f7cb2ef6ab33723578
