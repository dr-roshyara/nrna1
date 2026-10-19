PROPOSED / NOT RATIFIED; materials only; not executed

# KC-1 external replication: analysis plan

Fixed before any encoding, frozen with the materials (PROTOCOL.md §8.1). `score.py` implements it; where the two differ, this plan governs and the difference is a reported deviation. Nothing here decides KC-1.

## 1. Units and scoring

### 1.1 What the encoder gives

An item set (C-ids) per row, plus reasons (answer_sheet_template.md §B). **The encoder never gives a verdict and never sees the placement table.**

### 1.2 Primary unit: the guard-row verdict, derived by the scorer

For each of R11, R14, R15, R16, R17 and R21, the scorer maps the encoder's item set to one of **CB1 / CB23 / X / none** with this published placement table (T3 r1.9 §1a, T3:56–62; `score.py` constants):

| item(s) | side |
|---|---|
| C13, C14, C15, C16, C17, C18, C23, C24, C27 | CB1 |
| C2–C12, C19, C20, C21 | CB23 |
| C22, C25, C26, C28 | no side (contribute nothing) |
| C1 | spanning: **S′ (primary): neither side**; S (reported alongside): both sides |

Verdict: items on both sides → **X**; only CB1 → **CB1**; only CB23 → **CB23**; no placed item → **none**. This is the mapping of `conv()` / `side()` in the earlier scripts (`blind2_eval.py`; `AUDIT-Q6-TEST1-scripts/blind_enc.py`), re-implemented without the FZ substitution, which is unnecessary because a guard-row verdict depends only on that row's items. S′ is fixed as primary to match blind2; this is not a selection among S, S′, S″ (K14 stays open). No guard row names an actor, so S and S′ should coincide; `score.py` checks it per encoder and reports any difference.

### 1.3 Secondary unit: exact item-set match

Per row (guard rows and controls): pairwise exact agreement within the arm, and the share of encoders holding the modal set. Descriptive only; enters no decision. Item-set differences that change no verdict (e.g. C4 vs C5, both CB23; C22, no side) are expected and are not evidence of indeterminacy at verdict level.

### 1.4 Controls

R1, R6, R9, R79, R80: verdicts reported against the control key (FZ). They enter no decision. Two uses: (i) **cross-arm spill-over check** — the controls are read by identical rules 1–5 in every arm, so their verdict distributions should not differ between arms; (ii) **encoder competence**, used only in a sensitivity analysis (§4.4). R80 is expected to depart from FZ in every arm (key_PRIVATE.md §2).

## 2. Decision rule (per arm; fixed in advance)

Let n = number of **valid** sheets in the arm (§4.1) and thr = ⌈0.8 n⌉.

| label | condition |
|---|---|
| **REPRODUCED** | for **each** of the six guard rows, at least thr encoders give the arm's key verdict |
| **DETERMINATE-OTHER** | not REPRODUCED, but for each of the six guard rows at least thr encoders give **the same** verdict (the text is determinate, but not on its convention's verdict at ≥ 1 row) |
| **INDETERMINATE** | some guard row has no verdict held by thr encoders; the rows are listed |
| **PROVISIONAL …** | n = 4: thr = 4 (unanimity); the label is prefixed "PROVISIONAL" |
| **INCONCLUSIVE** | n ≤ 3: no label; descriptive report only (as blind1 / blind2) |

- The **per-row result** is always reported too (k / n on the key verdict, modal verdict and share, unanimity), so a single failing row is visible.
- An ambiguous or missing row answer counts **against** agreement (it is in n and agrees with nothing).
- **Cluster qualifier** (§3.4): if the cluster-collapsed analysis gives a different label, the arm is reported as "<label> (FAMILY-DEPENDENT)".
- **SH arm.** SH is a closed rule ("listed rows read nothing"); REPRODUCED is expected by construction, so the arm is an **instrument and compliance check**. If SH is not REPRODUCED, every arm is reported with the flag "instrument compliance not shown", and the SH failures are adjudicated first.
- No label compares arms. "PARSE REPRODUCED and T3 REPRODUCED" means both texts were determinate for these encoders; it does not rank them.

### 2.1 Pre-specified hypotheses (PROTOCOL.md §3)

| id | arm | hypothesis | supported iff |
|---|---|---|---|
| H-T3 | T3 | T3's rule text with its enumerated list determines T3's six guard verdicts | T3 REPRODUCED |
| H-SH | SH | encoders comply with a closed "read nothing" rule (instrument check) | SH REPRODUCED |
| H-PARSE | PARSE | (c) + the K1c parse, stated as a general rule, determines K1c's six verdicts | PARSE REPRODUCED |
| H-PARSE_B (optional) | PARSE_B | (c) + the alternative parse determines C-R15ref-R14's verdicts | PARSE_B REPRODUCED |
| H-GEN (optional) | GEN | T3's rule in general form (no list) determines T3's verdicts | GEN REPRODUCED |

Prior evidence (Claude-family only; `KC-1-READINESS-AUDIT.md` §9, §12.4): general-form (a) was reproduced by 5 of 5 encoders; (c) **without** a parse gave T3's vector or T3's with R21 → CB1; general (b)-like text gave the SH vector in 0 of 2; T3 enumerated, SH as a closed rule, and (c) + parse were never tested. **No directional prediction is made for H-PARSE.** H-T3 and H-SH are expected to hold; for them, failure would be the informative outcome.

## 3. Statistics

### 3.1 Sample size

- **Minimum n = 5 valid sheets per mandatory arm (T3, SH, PARSE); recommended n = 10.** Avoid n = 8 and other n where ⌈0.8 n⌉ / n lies well above 0.8: in `tools/oc_table.out` the threshold 7 / 8 = 0.875 makes n = 8 *less* likely to pass than n = 5 at the same per-encoder accuracy.
- **Why at least 5.** With n = 3 (blind2) one dissenting run decides the outcome; with n = 5 the rule tolerates exactly one dissent per row. n = 5 also permits the cluster cap (≥ 3 clusters, ≤ 2 sheets each).
- **What n = 5 or 10 can and cannot show** (`tools/oc_table.out`; computed assuming independent encoders and rows, which is optimistic):
  - if each encoder gives the key verdict with probability 0.9 on each row, P(REPRODUCED) = 0.60 at n = 5 and 0.65 at n = 10; at 0.95: 0.87 and 0.93;
  - if one row is genuinely split (p = 0.6) and the others are perfect, the false-REPRODUCED rate is 0.34 at n = 5 and 0.17 at n = 10;
  - the exact 95 % interval for 5 / 5 is [0.48, 1.00], for 10 / 10 [0.69, 1.00]. **No n reachable here supports an inferential claim of high agreement**; the rule is a pre-specified descriptive criterion, not a test.
- **If fewer encoders are available:**
  - fill **PARSE** first (the informative arm), then T3, then SH; SH may run at n = 3 as a compliance check only (INCONCLUSIVE by rule, reported descriptively);
  - n = 4 in an arm → PROVISIONAL label, unanimity required;
  - n ≤ 3 → INCONCLUSIVE; only per-row counts are reported, as for blind1 / blind2;
  - optional arms (PARSE_B, GEN) run only after every mandatory arm has n = 5;
  - the reduced plan is fixed and recorded **before** encoding starts, never after seeing sheets.

### 3.2 Agreement measures

- **Per row:** count on the key verdict k / n; modal verdict and its share; unanimity. These are the decision quantities.
- **Exact interval:** Clopper–Pearson 95 % interval for k / n per row, **over encoders**. Descriptive: it assumes independent encoders, which clusters violate (§3.4).
- **Fleiss' κ** over six guard rows × n raters, four nominal categories: **description only**, never used for the decision. Caveats: with six items it is very unstable; it depends on category prevalence (high raw agreement can give a low κ when one category dominates, and it is undefined when the margins do not vary); its chance model (raters drawing categories at marginal rates) has no meaning for rule-following; it needs complete data (encoders with an ambiguous guard row are dropped from κ only).
- **Not computed:** significance tests, p-values, between-arm comparisons, bootstrap intervals over rows or items, multiplicity corrections. The design (≤ 15 encoders per arm, six non-independent rows, one corpus) cannot support them. Blind1's bootstrap over items is not repeated (audit §9).

### 3.3 What is not analysed

Item-level κ, Jaccard similarity to reference encodings, E3-REL counts per encoder, and ranks. They depend on the counting scheme rather than on the text's determinacy and invite reading the result as evidence for a convention. They may be computed **after** the report, labelled exploratory.

### 3.4 Correlated errors

- **Sources:** runs of one model family share priors; humans from one lab or briefing share training; all texts share one author family (Λ7 at the source, PROTOCOL.md §11).
- **Design control:** each mandatory arm needs **≥ 3 clusters**, and **no cluster may hold more than 40 %** of an arm's valid sheets (at n = 5: ≤ 2 per cluster). A cluster is a model family (all versions of one provider's family count as one), or a human cohort (same institution or same briefing session).
- **Analysis control:** the **cluster-collapsed sensitivity** replaces each cluster by its modal verdict per row (a tie counts as disagreement) and re-applies the rule with thr = ⌈0.8 × clusters⌉. If the label changes, the arm is reported FAMILY-DEPENDENT.
- Correlation across the six rows (one parse decision can move several rows) is why the rule is a conjunction over rows and why no per-row test or multiplicity correction is used.

### 3.5 Model encoders: sampling

One run per model per arm by default. Repeated runs of one model count in one cluster and are capped by the 40 % rule. Settings are recorded (answer sheet §A); provider defaults are used unless the human fixes others before the freeze. A refusal or an off-task reply is an invalid sheet (§4.1).

## 4. Missing, ambiguous and invalid answers

### 4.1 Validity of a sheet

A sheet is **valid** if its JSON parses and contains all 11 row ids. Otherwise it is **invalid** and is replaced **once** by a fresh encoder of the same cluster where possible (PROTOCOL.md §6.5). The invalid sheet is archived and reported. If the replacement is invalid too, the slot stays empty (n is reduced).

### 4.2 Ambiguous row answers

A row whose value is not a list of strings "C1"–"C28" (e.g. "C8?", "C4 or C5", a nested list, a comment) is **ambiguous**. It counts in n and agrees with nothing. The scorer never interprets it, and no one asks the encoder to clarify.

### 4.3 No clarification, no re-run

Encoders are not contacted after their reply, except to request a missing reply. No sheet is re-run because of its content.

### 4.4 Sensitivity analyses (reported beside, never instead of, the primary result)

1. Ambiguous rows excluded (n per row reduced) instead of counted against.
2. Encoders who miss two or more of the control verdicts R1, R6, R9 (which the general rules determine) excluded. Outcome-adjacent, therefore **sensitivity only**.
3. Cluster-collapsed (§3.4).
4. S instead of S′ (expected identical on guard rows).

## 5. Stopping rule

- **Fixed n, no interim looks.** The target n per arm, the allocation list (PROTOCOL.md §6.3) and a deadline are fixed before encoding. Collection stops when every mandatory arm has its target number of valid sheets, or at the deadline, whichever comes first.
- **No scoring during collection.** The coordinator checks validity only (§4.1: JSON parses, 11 row ids), without computing verdicts. Verdict tables are first produced after the raw-response archive is hashed (PROTOCOL.md §8.3).
- No top-up after scoring has begun. An arm that ends below its minimum is reported under the reduced-n labels of §2.
- No early stopping for success or futility.

## 6. Report structure (fixed)

1. Integrity: freeze commit; hash of every sendable body sent; raw-archive hash; deviations.
2. Encoders per arm: identity class, cluster, validity, replacements.
3. Per arm: per-row verdict table, decision label, cluster sensitivity, S vs S′, κ (descriptive).
4. Secondary unit and controls.
5. Adjudication (adjudication.md), after and separate from the primary table.
6. Limitations (PROTOCOL.md §11), verbatim.
7. What the result can and cannot change in KC-1 (PROTOCOL.md §10), verbatim.

## 7. Dry run (scorer test only; not a result)

`score.py` was run on the blind2 verbatim replies (`tools/dryrun_blind2.out`). They come from **ineligible** Claude-family encoders and from **different packets**: blind2 A′ is scored against the GEN key, the closest arm; blind2 B′ is scored key-free. As a test of parsing and reporting only:
- GEN (blind2 A′, n = 3): 3 / 3 on the key verdict at all six guard rows; label INCONCLUSIVE (n < 4); κ 1.000; exact sets differ at R15, R17 and R21; control R80 CB23 in 3 / 3 (key none).
- BPRIME_blind2 (B′, n = 3, no key): unanimous at five rows; R21 X 2, CB1 1; label INCONCLUSIVE; κ 0.783.
- Both are flagged "one cluster, > 40 %"; the cluster check is "not possible".

These reproduce `blind2_eval.out` (H2 true; H1 true at R21 only) and are **not** evidence for this protocol's hypotheses.
