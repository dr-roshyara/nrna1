# Independent review of the 1ay support chain (r1 / r2 / r3)

Scope: only the files in this directory. No code was run. Every count below was recomputed by hand from `CODING-1ax.json`, `REVIEW-1ax.json`, `MAPPING.json`, `REVIEW-1ayn.json`, `LINEAGE.json`, `EVENTS-v1.json` and `RECORDS.json`, then checked against `DIAG-r2-confounding.txt` and the `RESULT*.json` files.

## 0. Bottom line

1. **"No STRICT witness" is not an empirical result. It is guaranteed by construction in all three versions.** STRICT for a variable v requires that *every other* compared field be known on *both* records. Two of those fields are almost never known:
   - `v1_h` is known on exactly one polar record (R25 `retired`);
   - `v1_c` is known on exactly one polar record (R03 `collapsed`).

   No pair has both records known on `v1_h` (or on `v1_c`), so no pair can be STRICT for any variable except `v1_h` / `v1_c` themselves. Those two are UNTESTABLE (1 known value). The diagnostic already showed this: `unknown_on ≥ 4` for every one of the 25 NORM pairs, and those four fields are v1_e, v1_x, v1_h and v1_c, which are UNK on every START event. **Formal reasoning; the most severe issue.**
2. **The ACT claim "no same-operation opposite-polarity pair" is partly an artifact of the r2 R06/R07 merge.** Before the merge, under the 1ay-n mapping, PREPARE-ISSUE has [+1, −1]: R05 ACT_OCCURRED and R06 ACT_WITHDRAWN. The merge rule ("polarity only if equal, else exclude") removes R06, because R07 is ACT_OCCURRED. The unmerged pair would still be confounded on target, v1_a and v1_k, so no verdict changes. The descriptive claim, however, is false.
3. **The NORM START-EXECUTION recount reproduces the diagnostic exactly:** 5+/5−, 25 pairs, and 14 target-POSSIBLE pairs after r3's two field drops. Two of the 14 (R14~R32, R14~R34) come from the unexpanded composite target "Slices 7B and 7C". The strongest two pairs are within a single ruling: R13~R14 (R-47) and R32~R33 (R-58). **R32~R33 is one of the two pairs cut from the output by the `[:12]` truncation.**
4. The r3 fixes are principled. F2 is clearly justified; F1 is justified. They were specified from an outcome-proximal diagnostic, after the sealed expectation (NORM target strict) had failed. They also removed only the blockers of POSSIBLE for the expected variable, and left the blockers of STRICT that the same diagnostic displays. **r3 should be labelled exploratory.** Duplicate or circular fields remain (`v1_a`, `v1_r`, `v1_k`, and possibly `v1_s` in ACT); see §2.

## 1. Instrument fidelity

### r1 (`support_1ay.py`)
It implements its docstring: polarity sets, pairing on the normalized operation, and the KNOWN / STRICT / POSSIBLE rules. It also implements clusters as `section`, disjointness as a maximal pairwise-disjoint set, and the verdict ladder. Deviations:
- **Unparseable corrections are applied, not reported** (wording / formal reasoning, no verdict effect). The docstring says unparseable corrections are "REPORTED, never applied". Seven 1ax corrections have `field` = `norm.status (deontic, … premises)`. `split(".")` yields two parts, and `parts[0] == "norm"`. So they are written into junk keys and listed under `corrections_applied` (RESULT*.json, lines 25–51).
- **Correction values are inserted verbatim, annotations included** (coding): `"null (NOT_EVIDENCED)"` (R04 about_actor), `"the Authority (SOURCE_FACT)"`, `"… [SOURCE_FACT]"`. `known()` treats `"null (NOT_EVIDENCED)"` as a KNOWN actor value. This does not matter in r1, which had zero pairs, and r2 overwrites these values anyway (see r2).
- **`possible_pairs` is truncated to 12 without disclosure** (`[:12]`). This is a reporting issue: r3 prints 12 of 14 and omits R31~R36 and R32~R33.
- `disjoint()` treats a within-section pair as the single-cluster set `{sect}` and counts it as disjoint from other pairs. This is a design choice, not documented (independence).

### r2 (`support_1ay_r2.py`)
- **Exec composition is correct.** `src.split("res = {")[0]` is r1 up to line 52; "res = {" does not occur earlier. It runs in `ns`, and `coding`, `sect`, `lin` and `rows` all live in `ns`. `coding = ns["coding"]` is the *same dict object*, so the mapping loop and the `pop`s mutate what r1's `rows()` reads through its globals (`ns`). `ns["sect"][…]` and `ns["lin"][…]` are mutated in place. The final `exec` of r1's `res = {…` tail in `ns` calls `ns["rows"]`. The label substitution string matches r1 line 53. **The re-binding is real.**
- **Mapping application matches the docstring.** Keys are `(record_id, "act.f" / "norm.f")`, which matches both MAPPING.json and REVIEW-1ayn.json. Reviewer corrections override. A missing mapping or UNMAPPABLE becomes None, and None is UNKNOWN. Side effects the docstring does not declare:
  - **1ax reviewer corrections are silently overwritten** (coding / evidence scope). MAPPING.json maps the *coder's* values (e.g. R04 `norm.about_actor` "engineering" → ENGINEERING). The 1ax reviewer had set this field to NOT_EVIDENCED. After r2, R04's actor is KNOWN = ENGINEERING. The diagnostic confirms it: R04~R10 `unknown_on=4`. The same override hits R18 about_target and R06/R07 stated_by/actor, but those land on equivalent or UNMAPPABLE codes. This contradicts PREREG ("Reviewer corrections from 1ax are applied"). Its direction is to *add* knownness, so it inflates rather than hides.
  - `unm` is computed but never output (wording).
  - **v1 fields are not normalized** (coding). Examples: `AUTHORITY` vs `DECISION-AUTHORITY`, `PO/ARB`, and `7B` vs A′ `Slice 7B`.
- **The R06/R07 merge matches the docstring:** equal component fields are kept, otherwise None; polarity only if equal. ACT: ACT_WITHDRAWN ≠ ACT_OCCURRED, so the record is excluded. NORM: STATUS_CONSTRAINT = STATUS_CONSTRAINT, which is not polar, so it is excluded. However:
  - `sect` / `lin` come from R06 alone. R06 and R07 have *different* v1 events: REGISTER operational-acceptance (REFUSED) and REGISTER constitutional-decision (PERFORMED). The v1 fields are therefore R06's, not merged. There is no effect, because the record is excluded from both datasets.
  - **Substantive:** the merge rule destroys the only ACT_WITHDRAWN in the corpus (see §3). Schema §1 is itself ambiguous here. It says "(span, operation, target) must be unique" (which allows R06 and R07, since their operations differ) and also "Events resolving to one span are one record" (which forbids it). The "one record with both components" clause covers ACT + NORM, not two ACTs. **Formal reasoning / coding.**

### r3 (`support_1ay_r3.py`)
- **The composition is correct.** `src.split("rest = open", 1)` cuts r2 before line 32, and the string does not occur earlier. `pre` re-runs r1 + mapping + merge into `outer["ns"]`. The wrapper `rows` calls the original `_rows` (globals = `ns`), then pops `v1_t` in both datasets and `v1_s` in NORM only, as declared. `ns["rows"] = rows` is resolved by the r1 tail executed in `ns`. The tail is taken from r1, not from r2's `rest`, but it is identical code with its own label. `fields = list(R[0]["f"])` reflects the drops.
- **It changes only what it declares.** Every r3 verdict other than NORM target is identical to r2 once the v1_s and v1_t rows are removed (RESULT-r2 vs RESULT-r3).

## 2. Were the r2 / r3 fixes diagnostic-driven or tuned?

- **The normalization in r2 is justified.** RESULT-NOTE §1 showed that exact-string pairing made r1's zero-pair result an artifact. The fix was committed before the mapping existed and was run blind (worker + reviewer).
- **F2 (drop v1_s in NORM) is justified.** For every START event `s` ∈ {authorized, not-authorized, auth-proviso-unmet, permission-only}, which *is* the deontic polarity. r2's only NORM POSSIBLE pairs were v1_s on R31~R32 and R33~R34, i.e. the norm "explaining" itself.
- **F1 (drop v1_t) is justified.** For START events `t` = 7A/7B/7C/WP-4B/WP-8, which is A′ target. For R04 and R05, however, `t` = `own-slice`, so the two fields are not fully the same concept.
- **Tuning risk.**
  - PREREG sealed "NORM target strict". r2 did not deliver it. r3 removes exactly the two fields that sit in the `differ` lists of the target pairs, and r3's verdict moves toward the sealed expectation.
  - "Not from any verdict" is technically true. The diagnostic, however, prints which fields block each pair, so it is a verdict precursor.
  - The fixes addressed the blockers of POSSIBLE (v1_s, v1_t) and left untouched the STRICT blockers printed on every line (`unknown_on`).
  - In their favour: the author did **not** also drop v1_a / v1_r. That would have added the five R04 × {R10, R12, R14, R31, R33} pairs (19 POSSIBLE) and would have been defensible by F1's own logic. So this is not maximal tuning.
  - **Verdict:** justified on the merits, but post hoc. r3 is exploratory evidence, not a test of the sealed expectation.
- **Remaining duplicate or circular fields:**

| field | problem | effect |
|---|---|---|
| `v1_a` | In ACT it is the same concept as A′ `actor` (5 known values each). In NORM it equals `about_actor` for START-lineage events, but for records whose lineage is the norm-*making* act (R04 → "AUTHORIZE-IMPL R-70", a=ARB) it is ≈ `stated_by`. So it is a duplicate plus a level mismatch. | Blocks R04 × 5 NORM pairs (differ on v1_a) |
| `v1_r` | A function of the operation (START ⇒ EXECUTION). It differs within a same-operation pair only through the lineage level mismatch (R04: RULING). | Same R04 × 5 pairs |
| `v1_k` | A coarse kind of the target (`work`, `ruling`, `operational-acceptance`, `same:` tokens). For a *target* contrast it is a derived attribute of the variable under test. | Constant on START; blocks R05~R06 in ACT |
| `v1_s` (ACT) | `guards-met` on R04/R05 reads like an outcome-conditioned value. It should be checked for circularity against the act state before being treated as a guard. | No pairs today |
| `v1_e`, `v1_x`, `v1_h`, `v1_c` | Not duplicates, but *structurally* unknown (UNK on every START event; `v1_h` / `v1_c` known on one record each). Any "n/a" filling is also UNKNOWN (`N/A` ∈ UNK). | STRICT is unsatisfiable (see §0.1) |

## 3. Recounts

### ACT: positives and negatives per operation (r2 / r3 data)

| op | + | − | records |
|---|---|---|---|
| CLASSIFY | 9 | 0 | R08, R09, R15–R21 |
| AUTHORIZE-IMPL | 4 | 0 | R04, R32, R34, R36 |
| ADOPT | 2 | 0 | R02, R23 |
| DECLINE-INTERPRETATION · HOLD · PREPARE-ISSUE · START-EXECUTION · OTHER:RETIRE · OTHER:ADJUDICATE · OTHER:APPLY · ANNOTATE | 1 each | 0 | R01, R03, R05, R11, R25, R26, R27, R30 |
| (unmapped) | 0 | 1 | R29 (SUPERSEDE → UNMAPPABLE by the 1ayn reviewer) |

Totals are 23+ / 1− = 24, which matches the diagnostic. Pre-merge (r1 coding + mapping) the totals are 24+ / 2−. The extra entries are R07 (CLASSIFY +) and **R06 (PREPARE-ISSUE −)**, which gives **one same-op opposite-polarity pair, R05~R06**. That pair differs on target (WP-4C-1 vs R-90), v1_a (ARB-CHIEF vs AUTHORITY) and v1_k, and R06's actor is unknown. It is therefore neither POSSIBLE nor STRICT, and the verdicts stand.

Two further mechanisms remove negatives:
- **The 1ayn reviewer rule "non-occurred act ⇒ operation UNMAPPABLE"** (R29, also R22 and R31). This conflates polarity with operation, which r1 carries separately. It systematically removes negative ACT records from pairing (coding / formal reasoning). R29 has no SUPERSEDE positive in ACT today, so no pair is lost now.
- **The coder's choices on R03 (HOLD rather than ACT_REFUSED of ADOPT) and R01.** The 1ax reviewer flagged both as uncertain. Coded as refused ADOPT, R02~R03 would share actor DA, v1_a, v1_s PREPARED and v1_r. It would differ only on target (R-81..85 vs R-91), and v1_c (`collapsed`, the stated ground) would be known only on R03. That makes a **POSSIBLE-for-target ACT pair**. Classification: source interpretation.

### NORM: START-EXECUTION
- **Records:** + {R04, R13, R32, R34, R36}; − {R10, R12 (PROHIBITED); R14, R31, R33 (UNAUTHORIZED)}. That is 5 × 5 = 25 pairs, matching the diagnostic line for line. R05 (AUTHORIZED, "plan and implement" → UNMAPPABLE) is a sixth positive that was lost.
- **Under r3** (actor, target, stated_by, v1_a, v1_k, v1_e, v1_x, v1_h, v1_c, v1_r):
  - R04 × 5: target + v1_a + v1_r differ → none.
  - R31~R32 and R33~R34: nothing differs → none. These are the same target with the polarity flipped over time.
  - {R10, R12, R14, R33} ~ R36: target + stated_by (ARB vs DA) → none.
  - **Target-only difference → POSSIBLE (14):** R10~R13, R10~R32, R10~R34, R12~R13, R12~R32, R12~R34, R13~R14, R13~R31, R13~R33, R14~R32, R14~R34, R31~R34, R31~R36, R32~R33. This matches `n_possible: 14`.

### The 14 POSSIBLE pairs: blocking unknowns

| pairs | unknown fields besides v1_e/x/h/c |
|---|---|
| R10~R13, R10~R32, R10~R34 | none (A′-complete; only the V1 structural unknowns block STRICT) |
| R12~R13, R12~R32, R12~R34 | about_actor (R12) |
| R13~R14, R14~R32, R14~R34 | about_actor (R14) |
| R13~R33, R32~R33 | about_actor (R33) |
| R13~R31, R31~R34, R31~R36 | about_actor + stated_by (R31) |

**Fillable from sources?**
- **v1_e, v1_x, v1_h, v1_c: no.**
  - They are v1-coded fields, and v1 records are frozen by the schema.
  - The v1 schema defining them is not among these files.
  - For execution norms, `h` (identifier history) and `c` look inapplicable, and any "n/a" counts as UNKNOWN.
  - The only route is to drop structurally inapplicable fields in the instrument, not to fill them.
- **about_actor for R14 (R-47) and R33 (R-58): not as SOURCE_FACT from the span.** An in-section premise does exist: R-47 "execution responsibility transfers to engineering" (cited for R13), and R-58's own sibling span "Engineering is authorized to begin Slice 7B RED" (R32). This is the same INFERENCE grade already accepted for R10 and R13. It is weaker than what the 1ax reviewer rejected for R04 (a cross-section premise).
- **about_actor for R12 and R31, and stated_by for R31: not feasible from what is quoted.** R-79's directive names no actor, and the R-56 recording note's author is unnamed. Filling R31's stated_by with ARB would **remove** R31~R36 (ARB ≠ DA).
- **Composite targets.** The 1ayn reviewer required expansion ("expand composite targets into their members"), and r2 does not do it. After expansion, R14[7B]~R32 and R14[7C]~R34 have the *same* target: v1_t already agreed on R14~R32 (7B = 7B). **So at most 12 of the 14 are genuine target contrasts.**
- **Disjointness if the V1 structural blockers were removed.** A disjoint set of 4 exists: R10~R32 {R-72, R-58}, R12~R34 {R-79, R-65}, R13~R14 {R-47}, R31~R36 {R-56, R-86}. Using only A′-complete pairs, R10~R13, R10~R32 and R10~R34 all share R-72, so the disjoint count is 1. Adding R32~R33 (needs R33's actor) gives 2. **The verdict therefore hinges on instrument design and one INFERENCE-grade fill.**

## 4. Interpretation

**(a) "Act-level guards are NON-IDENTIFIABLE in this corpus, not falsified."**
- **"Not falsified": correct.** Nothing here can falsify anything, because STRICT is unreachable.
- **"NON-IDENTIFIABLE in this corpus": overstated.** It should read *not identified under this coding and instrument*. Three reasons:
  - The only same-operation negative was deleted by the merge (R06).
  - The 1ayn rule removes negative operations (R29).
  - A′ R-A2 / R-A3 plus coder choices (R01, R03, R26, R27, R29) turned v1 "REFUSED" outcomes into ACT_OCCURRED of *another* operation. v1 records 18 REFUSED outcomes among the 36 lineage events (many of them norm-level START refusals). A′ ACT has 2.
- **The sparsity of refusals is real in A′ coding.** It is partly produced by the schema, which is a legitimate property of the representation. It is not a fact about the corpus.

**(b) "NORM target-indexing is POSSIBLE but not strict."**
- **"POSSIBLE": correct in the instrument's sense, with caveats:**
  - 2 of the 14 are composite artifacts;
  - the pairs share records (R10, R12, R13 and R31 recur);
  - the cross-section pairs also differ in *time and authorizing ruling*, which is not a field. Only R13~R14 and R32~R33 hold the ruling fixed.
- **"Not strict": vacuous**, because strictness is unattainable (§0.1).
- **R31~R32 and R33~R34** (same target, same fields, opposite polarity at a later ruling) show that **target alone does not index polarity**. The intervening authorization act does.

**Defects that could hide a strict witness:** see `hidden_by_defect` in REVIEW.json.
- The main one is the STRICT rule over structurally unknown v1 fields.
- Next are the lineage level mismatch (v1 fields on R04), un-normalized v1 values, the R06/R07 merge, the operation-UNMAPPABLE rule for negatives, and NOT_EVIDENCED actors (R14, R33).
- Last is the `[:12]` truncation, which hides the best within-ruling pair from readers.
- Separately, some defects *inflate* rather than hide: the composite targets and the 1ax override on R04.

## 5. What the chain establishes and what it does not (for rule-making vs broad governance)

**It establishes:**
- a correct, composable instrument (r2 and r3 re-bind what they claim);
- that, under A′ coding with 1ay-n normalization, the ACT dataset is almost wholly positive (23/1), so act-level guard contrasts cannot be tested on it;
- that NORM START-EXECUTION contains opposite-polarity pairs with the same operation, actor and stated_by, differing only in target (up to 12 genuine ones; 2 within a single ruling);
- that the same target changes polarity across successive rulings (R31→R32, R33→R34) with every coded field equal.

**It does not establish:**
- any STRICT witness or EMPIRICALLY-SUPPORTED verdict, positive or negative, since none was reachable;
- that act-level guards are absent or untestable *in the corpus*, as opposed to in this coding;
- any independence beyond one or two clusters;
- any discriminating result between the hypotheses:
  - both a rule-making and a broad-governance account predict target-specific authorization norms;
  - the one variable that could separate them (`stated_by` / authority) has 0 POSSIBLE pairs (it is confounded with target on R36);
  - the most discriminating signal, polarity flipped by an intervening ruling, is not represented as a variable;
- anything resting on v1 fields beyond V1-CODED weight.

For the comparison, the fair input is: **ACT = no evidence either way; NORM = weak, non-strict, time-confounded support for target-specific norms. The same-target flips are compatible with rule-making but were not tested as such.**

## 6. Issues (classified)

| # | issue | class |
|---|---|---|
| 1 | STRICT is unsatisfiable (v1_h/v1_c known on one record each; v1_e/v1_x UNK on START) | formal reasoning |
| 2 | R06/R07 merge deletes the only ACT_WITHDRAWN; the "no same-op pair" claim is an artifact | formal reasoning / coding |
| 3 | Schema §1 is ambiguous for two acts on one span | formal reasoning |
| 4 | The 1ayn rule makes the operation of refused/non-occurred acts UNMAPPABLE | coding |
| 5 | R03 / R01 coding choices hide a possible ADOPT contrast | source interpretation |
| 6 | The mapping overwrites 1ax corrections (R04 about_actor) | coding |
| 7 | v1 fields are not normalized; lineage level mismatch (R04 v1_a/v1_r) | coding |
| 8 | v1_a, v1_r, v1_k remain as duplicates or derived fields | coding |
| 9 | Composite targets are not expanded (2 spurious POSSIBLE pairs) | coding |
| 10 | Time / authorizing ruling is not a field; cross-section pairs are confounded | substantive |
| 11 | r3 is post hoc and verdict-proximal; it moves toward the sealed expectation | independence |
| 12 | `[:12]` truncation; unparseable corrections applied; `unm` unreported | wording |
| 13 | about_actor NOT_EVIDENCED on R14/R33 despite in-section premises | evidence scope |
| 14 | "Non-identifiable in this corpus" should read "in this coding" | evidence scope |
