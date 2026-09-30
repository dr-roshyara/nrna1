# Independent review — three-way coding comparison (GATE1 n=28, RESERVE n=13)

Inputs used: `{GATE1,RESERVE}/{MANUAL.md, ROWS.md, FORM.json (GATE1), CODING-MAIN.json, CODING-B1.json, CODING-B2.json, NOTES-B1.md, NOTES-B2.md, *_agreement.py, AGREEMENT-MAIN-vs-B1.json, AGREEMENT-MAIN-vs-B2.json, AGREEMENT-B1-vs-B2.json}`. No code was run; every number below was recomputed by hand from the codings.

**Summary of findings**
- **Scoring fidelity: OK.** I recomputed 27 agreement/kappa values, 17 Jaccard rows and 2 coverage means by hand. All match the files.
- **The scorer's normalisation changes no result in these codings**, but several of its design choices can hide or create disagreement (§1.3).
- **"B1 and B2 agree more with each other than with MAIN"** holds for 5 of 7 GATE1 measures and 4 of 8 RESERVE measures. The rows show **no single direction of bias**.
  - The main driver is that MAIN almost never codes AMBIGUOUS: 2 vs 14 vs 14 in GATE1, 3 vs 9 vs 9 in RESERVE.
  - In GATE1, MAIN is more often right than the blind pair.
  - In RESERVE, MAIN three times reads more into a record than its text states: it uses knowledge of other records (R-54, R-33) and reads VIOLATED where the text says "consequence" (R-76). A fourth possible case is R-74's REJECT.
- **V1 instability is mostly the manual.** V1 has no rule for the vacuous case (no §C guard applies), and B2 settled it the opposite way from MAIN and B1. That one convention explains 15 of 20 MAIN–B2 V1 disagreements. V4 has the same problem in GATE1, where the manual has no applicability rule.
- **This is a same-family, partly non-blind result.** It is not independent confirmation and not Gate 1 proper (§6).

---

## 1. Scoring fidelity

### 1.1 Setup check
- Both bundles contain the same scorer, byte for byte. It hard-codes `MAIN-CODING-SEALED.json` (read as `["rows"]`) and `BLIND-CODING.json`.
- The three AGREEMENT files must therefore come from renaming files. For B1-vs-B2, B1 has to be wrapped as `{"rows": …}` and put in the "main" slot. This step is **not documented**.
- The outputs are consistent with it having been done correctly:
  - B1-vs-B2's `coverage_rowmean.main` (GATE1 0.751, RESERVE 0.654) equals B1's `blind` coverage in MAIN-vs-B1.
  - B1-vs-B2's `blind_not_modelled` equals B2's list.
- In B1-vs-B2 files the labels "main" and "blind" are misleading. There, "main" means B1.

### 1.2 Hand spot-checks (all ✓)

**How to read the kappa column:** pe = Σ(count in coder A × count in coder B) / n². Counts are listed as S = SUPPORTED, U = UNTESTABLE, A = AMBIGUOUS, NM = NOT-MODELLED, VIOL = VIOLATED.

| Bundle | Judgement | Pair | Hand po | Hand pe → κ | File |
|---|---|---|---|---|---|
| GATE1 | V1 | M–B1 | 21/28 = .750 | M{S27,NM1}·B1{S20,NM1,A5,U2}: 541/784 = .690 → **.193** | .75 / .193 ✓ |
| GATE1 | V1 | M–B2 | 8/28 = .286 | M{S27,NM1}·B2{S7,NM1,A5,U15}: 190/784 = .242 → **.057** | .286 / .057 ✓ |
| GATE1 | V1 | B1–B2 | 11/28 = .393 | (140+1+25+30)/784 = .250 → **.190** | .393 / .19 ✓ |
| GATE1 | V3 | M–B1 | 23/28 = .821 | 603/784 = .769 → **.227** | ✓ |
| GATE1 | V3 | M–B2 | 22/28 = .786 | 578/784 = .737 → **.184** | ✓ |
| GATE1 | V3 | B1–B2 | 27/28 = .964 | 527/784 = .672 → **.891** | ✓ |
| GATE1 | V4 | M–B1 | 19/28 = .679 | M{S12,U16}·B1{S3,U25}: 436/784 → **.276** | ✓ |
| GATE1 | V4 | M–B2 | 20/28 = .714 | M{S12,U16}·B2{S8,U20}: 416/784 → **.391** | ✓ |
| GATE1 | V4 | B1–B2 | 21/28 = .750 | 524/784 → **.246** | ✓ |
| GATE1 | V5 | M–B2 | 25/28 = .893 | 703/784 = .897 → **−.037** | ✓ |
| RESERVE | V1 | M–B1 | 10/13 = .769 | M{S10,NM3}·B1{S9,U1,A2,NM1}: 93/169 → **.487** | ✓ |
| RESERVE | V1 | M–B2 | 9/13 = .692 | 83/169 → **.395** | ✓ |
| RESERVE | V1 | B1–B2 | 12/13 = .923 | 79/169 → **.856** | ✓ |
| RESERVE | V3 | M–B1 / M–B2 / B1–B2 | .923 / .846 / .923 | 90, 96, 97 over 169 → **.835 / .644 / .819** | ✓ |
| RESERVE | V4 | M–B1 / M–B2 / B1–B2 | .692 / .692 / .846 | 89, 89, 77 over 169 → **.350 / .350 / .717** | ✓ |
| GATE1 | per-op CORRECT-TEXT | M–B1 | 27/28 | 704/784 → **.650** | ✓ |

**Ops-Jaccard rows.** GATE1 is the same for M–B1 and M–B2, because B1's and B2's ops are identical on all 28 rows.

| Row | MAIN ops | B1 ops | B2 ops | Hand Jaccard | File |
|---|---|---|---|---|---|
| G R-34 | CREATE-NORM | +ACCEPT, SUPERSEDE | same as B1 | 1/3 = .33 | ✓ |
| G R-40 | APPROVE, AUTHORIZE, DEFER, MODIFY-SEQUENCE | +CORRECT-TEXT | same as B1 | 4/5 = .80 | ✓ |
| G R-50 | AUTHORIZE | +ANNOTATE | same as B1 | .50 | ✓ |
| G R-62 | ANNOTATE | +APPROVE | same as B1 | .50 | ✓ |
| G mean | | | | 26.13/28 = .933 (B1–B2: 1.0) | ✓ |
| R R-59 | ACCEPT, ANNOTATE | ACCEPT | ACCEPT, CORRECT-TEXT | M–B1 .50 · M–B2 .33 · B1–B2 .50 | ✓ |
| R R-60 | ANNOTATE, OPEN-WORK | OPEN-WORK | OPEN-WORK, CORRECT-TEXT | .50 · .33 · .50 | ✓ |
| R R-71 | ACCEPT, ADOPT-DECISION | same as MAIN | +OPEN-WORK | 1 · .67 · .67 | ✓ |
| R R-74 | ADOPT-DECISION, REJECT | ADOPT-DECISION | ADOPT-DECISION | .50 · .50 · 1 | ✓ |
| R R-42 | CREATE-NORM, RATIFY | RATIFY, CREATE-NORM | RATIFY | 1 · .50 · .50 | ✓ |
| R means | | | | 11/13 = .846 · 10.33/13 = .795 · 10.67/13 = .821 | ✓ |

**Coverage (row mean of ops / (ops + not_modelled)):** GATE1 MAIN = 25.0/28 = .893 ✓. RESERVE MAIN = 8.167/13 = .628 ✓.

**Per-op filter (an op is shown only if it appears ≥ 3 times across both coders):** RESERVE shows CREATE-NORM only in M–B1 (1+2) and OPEN-WORK only in M–B2 and B1–B2 (1+2, 2+1) ✓.

Minor point: the Jaccard mean averages per-row values that were already rounded to 2 decimals. The effect is ≤ 0.005 and does not change any reported value here.

### 1.3 Normalisation that could hide or create disagreement
1. **`norm()` truncation.** It keeps only the text before the first space or "(", then upper-cases it.
   - It is **inert here**: every V value in all six codings is already a single upper-case token.
   - But it would turn `NOT MODELLED` into `NOT`, and strip any qualifier such as `AMBIGUOUS (V1 reading …)` or `SUPPORTED (weak)`. That silently merges qualified and unqualified codes, so it can hide disagreement.
2. **Case folding is one-sided.** Ops in the second slot are upper-cased; ops in the first slot (MAIN, or B1 in B1-vs-B2) are not. A lower-case op in the first slot would create a false disagreement. Inert here.
3. **`not_modelled` is never compared between coders.** It only enters coverage, and coverage *falls* the more carefully a coder lists unmodelled verbs. The GATE1 gap (MAIN .893 vs blind .751/.773) is mostly enumeration habit: MAIN lists 8 unmodelled items in total, B1 lists 23. It is not a difference in what the coders think the theory covers.
4. **Empty ops on both sides score Jaccard = 1.0** (GATE1 R-31; RESERVE R-54, R-57). This creates agreement on rows where no modelled act was found.
5. **`bool(authority_named)` maps null to False**, which would hide an unset field. No nulls occur here.
6. **κ = None when pe = 1** (RESERVE V5, M–B2: every row UNTESTABLE for both coders). This must be reported as "not estimable", not dropped.
7. **The ≥ 3 per-op filter** hides every rare-op disagreement: R-34 SUPERSEDE, R-74 REJECT, R-62 APPROVE's single-row effect, RAISE, and so on.
8. **Nominal kappa and exact-match agreement count NOT-MODELLED vs UNTESTABLE (RESERVE R-33) as a full disagreement**, although both mean "no test performed". They weight SUPPORTED/UNTESTABLE the same as SUPPORTED/VIOLATED.
9. **`annotations_by_others` and `note` are ignored.** The RESERVE attribution disagreement on R-59/R-60 therefore shows up only indirectly, through ops.
10. **V6 agreement of 1.0 everywhere is mechanical.** It is fixed by row number (PRE-R43) plus whether the governance triple is present, so it is not evidence of shared interpretation.

---

## 2. Main-coder bias

"Holds" means B1–B2 agreement is **strictly** higher than both MAIN–B1 and MAIN–B2.

| Bundle | Measure | M–B1 | M–B2 | B1–B2 | Holds? |
|---|---|---|---|---|---|
| GATE1 | V1 | .750 | .286 | .393 | **No** (driven by B2's convention) |
| GATE1 | V2 | .893 | .857 | .964 | Yes |
| GATE1 | V3 | .821 | .786 | .964 | Yes |
| GATE1 | V4 | .679 | .714 | .750 | Yes (barely) |
| GATE1 | V5 | .857 | .893 | .893 | No (tie) |
| GATE1 | V6 / auth | 1 | 1 | 1 | n/a |
| GATE1 | V7 | .929 | .929 | .964 | Yes |
| GATE1 | ops Jaccard | .933 | .933 | 1.000 | Yes |
| GATE1 | coverage | .893 vs .751 | .893 vs .773 | .751 vs .773 | Yes |
| RESERVE | V1 | .769 | .692 | .923 | Yes |
| RESERVE | V2 | .615 | .692 | .769 | Yes |
| RESERVE | V3 | .923 | .846 | .923 | No (tie) |
| RESERVE | V4 | .692 | .692 | .846 | Yes |
| RESERVE | V5 / V7 | .923 | 1.0 | .923 | No |
| RESERVE | authority_named | .923 | .923 | 1.0 | Yes (R-33 only) |
| RESERVE | ops Jaccard | .846 | .795 | .821 | No |
| RESERVE | coverage | .628 vs .654 | .628 vs .644 | .654 vs .644 | Trivially (all within .026) |

### Rows where the pattern holds (B1 = B2 ≠ MAIN)

**GATE1 V2.** R-46, R-55 and R-97: MAIN UNTESTABLE, blind SUPPORTED.
- **R-46: MAIN right.** The record only approves a plan ("Approval attaches to **the plan**"). Nothing is said about permission, authorization, commissioning or execution. The blind coders over-apply V2.
- **R-55: blind right.** "requiring its own authorization if work is desired". The record explicitly keeps authorization apart from repair.
- **R-97: blind right.** "implementation may proceed only where Execution Governance has explicitly authorized it; future work begins only under new authorized commissions".
- So here MAIN *under*-reads. It is not reading more into the records.

**GATE1 V3.** R-38, R-45, R-61 and R-92: MAIN SUPPORTED, blind AMBIGUOUS. R-40: MAIN AMBIGUOUS, blind SUPPORTED.
- The blind coders code AMBIGUOUS when the *identity of the operation* is unclear, even when every reading gives the same V3 result.
- R-38: under the freeze reading the change is a regime change, which is inside FREEZE's frame. Under the reading "not a sixth freeze … introduces NO new governance", nothing changes. Neither reading gives VIOLATED.
- **MAIN is right on R-38, R-45 and R-61, and weakly on R-92.**
- **On R-40, MAIN's AMBIGUOUS is defensible**: "OQ-ENG-004 protocol commissioned" is a stated change that fits no §B frame, and the body never mentions it. The blind coders put the same concern under V2 instead.

**GATE1 V4.** R-40, R-49, R-56, R-65 and R-75: MAIN SUPPORTED, blind UNTESTABLE.
- **R-49: MAIN right** — "keeps evidence collection separate from repair".
- **R-75: MAIN right** — the counter-evidence annotation says "the DECISION stands as issued".
- **R-65: MAIN reads in a silent default.** No evidence is cited, so V4 is UNTESTABLE.
- **R-56:** MAIN is defensible — "Evidence: the Slice 7B Authorization Package", followed by approval through an explicit act.
- **R-40:** undecidable under the v1 manual.

**GATE1 V7.** R-97: MAIN UNTESTABLE, blind AMBIGUOUS.
- "replacing any broader claim" uses no supersession verb and names no record. **MAIN is right** under §A's verb rule, and r2 F6 later made this explicit.
- Elsewhere MAIN codes **R-30 V7 SUPPORTED without coding any SUPERSEDE op** — a silent default.

**GATE1 ops.**
- **Blind right** on R-62 APPROVE ("RECORDING CORRECTIONS A1 AND A3 **APPROVED**" is the headline verb), on R-34 SUPERSEDE ("Superseded in detail by …" is a table verb), and weakly on R-40 CORRECT-TEXT.
- **MAIN right** on R-50 (the annotation's own text credits it to "R-62") and on R-34 ACCEPT (the object is rulings, not completed work).
- **MAIN is inconsistent on R-34**: V7 SUPPORTED, but no SUPERSEDE op.

**RESERVE V1.** R-33, R-42, R-54.
- **R-54: MAIN reads more than the text.** Its note says "'R-51 granted permission' said of an authorization (**pre-R-80 term use**)". That uses knowledge of another record, which r2 F1 forbids, to settle the text's own tension between "previously authorized" and "R-51 granted permission". §C does model START, so MAIN's NOT-MODELLED is also wrong. **Blind AMBIGUOUS is right.**
- **R-42: blind right.** The refusal "Option C … is REFUTED" cites the registry contract, which is not a §C rule. MAIN's SUPPORTED ignores V1's refusal clause.
- **R-33: blind right, weakly.** Under F1, the one guarded act (SUPERSEDE) is UNTESTABLE. The manual has no rule for combining per-act values into one row value.

**RESERVE V2.**
- **R-95: blind right.** "implementation proceeds under the normal engineering lifecycle once the plan is reviewed" allows the reading that plan review stands in for authorization.
- **R-71: MAIN right.** "Engineering does not self-certify it" separates execution from acceptance, which is not one of V2's four categories. B2 applied exactly this reasoning to R-57 but not to R-71.
- **R-69:** weakly blind.

**RESERVE V4.**
- **R-57: blind right.** A finding (P7B-1) bears on an approved plan, and the plan is changed by a governance act, not by the evidence: "deliberately NOT edited by engineering, because amending an approved plan is a governance act".
- **R-48 and R-69:** the records are genuinely ambiguous ("Also closed by this slice", "the contract, not the code, is corrected"), so blind AMBIGUOUS is defensible.

**RESERVE authority_named, R-33: blind right.** "ARB progress snapshot" names the ARB as the source of a snapshot, not as the deciding authority. MAIN is probably drawing on knowledge of the log.

**RESERVE V3, R-76.** The pattern does not hold numerically (tie), but this is the joint-blind row that matters most.
- MAIN codes the dataset's **only VIOLATED**.
- The record itself labels the change as derived: "**CONSEQUENCES:** the scope ambiguity … IS CLOSED". §B treats consequences as derived, not as direct changes. The triple ("Adoption") also offers an ADOPT-DECISION reading.
- **Blind AMBIGUOUS is right.**

### Verdict on main bias
It is a partial and mixed pattern, not one bias.
- The dominant, systematic difference is that MAIN resolves toward a determinate code. It uses AMBIGUOUS 2 times in GATE1 (vs 14 and 14) and 3 times in RESERVE (vs 9 and 9), and it uses SUPPORTED as a default where a guard or trigger is absent (GATE1 V1, V4 R-65, V5 R-97, V7 R-30).
- In GATE1 this is more often right than wrong, because the blind coders over-use AMBIGUOUS.
- In RESERVE, which uses the stricter r2 record-only rules, MAIN's deviations more often come from reading beyond the record:
  - knowledge of other records (R-54, R-33);
  - VIOLATED contrary to the text's own "consequence" label (R-76);
  - REJECT coded from "settled by implication" (R-74).
- MAIN's GATE1 coding is also **not blind to the manual**. Its header reads "1ab rows recoded under v1.1 (development for v1.1)". Some of MAIN's agreement with the manual is therefore circular.

---

## 3. Correlated same-family error (rows where B1 = B2 ≠ MAIN)

**Tally:** MAIN right on 13; blind right on 17; 1 undecidable (see REVIEW.json).
- **Shared blind error is real in GATE1:**
  - AMBIGUOUS inflation on V3: R-38, R-45, R-61, R-92;
  - V2 over-application: R-46;
  - V4 under-application: R-49, R-75;
  - literal verb matching: R-50 ANNOTATE.
- **It is rare in RESERVE** (R-71 V2 only).
- **Correlation marker:** B1 and B2 produced **identical ops on all 28 GATE1 rows** and near-identical `not_modelled` lists. Two independent coders would be unlikely to do this. It points to shared priors or shared prompt effects, and possibly to B2 having seen B1's output. This must be checked.

| Row | Judgement | MAIN | B1 = B2 | Right under the manual | Quote |
|---|---|---|---|---|---|
| G R-46 | V2 | U | S | MAIN | "Approval attaches to **the plan**" (no permission/authorization/execution content) |
| G R-55 | V2 | U | S | Blind | "requiring its own authorization if work is desired" |
| G R-97 | V2 | U | S | Blind | "implementation may proceed only where Execution Governance has explicitly authorized it" |
| G R-38 | V3 | S | A | MAIN | "R-38 is a **consolidation/assessment ruling, not a sixth freeze**" (neither reading breaks a frame) |
| G R-45 | V3 | S | A | MAIN | "**no responsibility holder changes.**" |
| G R-61 | V3 | S | A | MAIN | "accepted as the **authoritative assessment**" (§A: ACCEPT covers "a completed assessment") |
| G R-92 | V3 | S | A | MAIN (weak) | "ADOPTED … is ACCEPTED" (the two-rows rule picks the headline); "review closed" is derived |
| G R-40 | V3 | A | S | MAIN (weak) | "A4 candidate + OQ-ENG-004 protocol commissioned" (no frame covers it; not in the body) |
| G R-49 | V4 | S | U | MAIN | "keeps evidence collection separate from repair" |
| G R-75 | V4 | S | U | MAIN | "the DECISION stands as issued" |
| G R-65 | V4 | S | U | Blind | no evidence cited: "Engineering shall implement Slice 7C subject to the Architectural Constraints" |
| G R-97 | V7 | U | A | MAIN | "replacing any broader claim" (no supersession verb, no named record) |
| G R-62 | ops | ANNOTATE | +APPROVE | Blind | "RECORDING CORRECTIONS A1 AND A3 **APPROVED**" |
| G R-50 | ops | AUTHORIZE | +ANNOTATE | MAIN | "RECORDING CORRECTION A3 (**R-62**, 2026-08-01 — annotation …)" |
| G R-34 | ops | CREATE-NORM | +SUPERSEDE, +ACCEPT | Blind on SUPERSEDE, MAIN on ACCEPT | "Superseded in detail by the A/B/C/D classification" · "R-32/R-33 accepted as grandfathered" |
| R R-54 | V1 | NM | A | Blind | "R-51 granted permission, R-54 starts the clock" |
| R R-42 | V1 | S | A | Blind | "Option C of the ownership investigation is REFUTED" |
| R R-33 | V1 | NM | U | Blind (weak) | "supersedes any file-count framing" (guard not stated → F1 UNTESTABLE) |
| R R-95 | V2 | S | A | Blind | "implementation proceeds under the normal engineering lifecycle once the plan is reviewed" |
| R R-71 | V2 | U | S | MAIN | "Engineering does not self-certify it." |
| R R-76 | V3 | VIOL | A | Blind | "**CONSEQUENCES:** the scope ambiguity … IS CLOSED" |
| R R-57 | V4 | U | S | Blind | "deliberately NOT edited by engineering, because amending an approved plan is a governance act" |
| R R-48 | V4 | S | A | Blind (genuinely ambiguous) | "Also closed by this slice: A-1's one labelled evidence limit" |
| R R-33 | authority_named | true | false | Blind | "ARB progress snapshot at definition" |
| R R-74 | ops (single-coder) | +REJECT | — | Blind | "ALSO SETTLED BY IMPLICATION: H2/H3 are rejected" |

---

## 4. Why V1 and V4 are unstable

### V1 — definition
"SUPPORTED when every act the record performs is permitted by §C, and every refusal that cites a rule concerns an act §C forbids."

The definition has four gaps:
- it does not say what to code when no §C guard applies at all;
- it has no rule for combining per-act values into one row value;
- "demonstrated need" and "successor slice" are undefined;
- ADOPT vs ADOPT-DECISION turns on "PREPARED", which records mention only as "prepared package".

| Cause | Rows | Share |
|---|---|---|
| **Manual ambiguity — vacuous case.** B2: "V1 = UNTESTABLE when no §C guard applies"; MAIN and B1 code SUPPORTED | G R-30, 32, 35, 38, 45, 46, 49, 55, 56, 61, 62, 63, 67, 68, 93 | 15 of 20 M–B2; 15 of 17 B1–B2 |
| **Manual ambiguity — ADOPT vs ADOPT-DECISION** ("from the prepared package", "decision-authority-adoption-package") | G R-73, R-75, R-92 | 3 (r2 F3 settles R-73 and R-75; R-92 stays arguable) |
| **Manual ambiguity plus genuinely ambiguous record — "demonstrated need"** for new categories | G R-34, R-40 | 2 |
| **Manual ambiguity, then coder error under r2 — predecessor-acceptance not stated** | G R-58, R-65 (B1 U; B2 S, which contradicts B2's own convention), R R-70 (B2 U) | 3 |
| **Manual ambiguity — refusal clause** citing a non-§C rule | R R-42 | 1 |
| **Manual ambiguity — combining acts** (NOT-MODELLED vs UNTESTABLE) | R R-33 | 1 |
| **Genuinely ambiguous record, resolved by MAIN using knowledge of other records (coder error)** | R R-54 | 1 |
| **Normalisation artifact** | none (all values are single tokens); but NOT-MODELLED vs UNTESTABLE scored as disagreement (R-33) is a metric artifact | 0 |

### V4 — definition
"nothing's standing or status changes by evidence alone."

In the GATE1 (v1) manual this can be satisfied vacuously by almost any record, and there is no applicability rule. The three coders applied different, internally inconsistent thresholds: MAIN coded 12 SUPPORTED, B1 3, B2 8, with overlapping but different rows.
- **Manual ambiguity (GATE1, dominant):** R-40, 49, 50, 52, 55, 56, 61, 63, 65, 67, 75, 93.
- **Coder error once r2 F4 exists:**
  - MAIN R-57, which should apply;
  - B1 R-76, which B1 applied correctly while MAIN and B2 did not;
  - B2 R-95, where the evidence bears on a new decision, not an existing one;
  - GATE1 B2 R-50, which should be SUPPORTED: "R-43 is NOT amended … no claim is made about its evidence".
- **Genuinely ambiguous record:** R R-48 ("closed by this slice") and R R-69 ("the contract, not the code, is corrected"). In both, the actor behind an evidence-linked change is not stated.
- **Normalisation artifact:** none.

The r2 F4 rule reduced but did not remove V4 instability (RESERVE κ .35 / .35 / .72). What remains is the unnamed-actor case.

### Smallest manual clarifications (recommendations only — not applied; the frozen manual and theory are unchanged)
1. **V1 vacuous case.** "If no act of this record is subject to a §C guard and no refusal cites a rule, code V1 UNTESTABLE." This matches §D's "UNTESTABLE (does not apply)".
2. **V1 row value.** "Code V1 per act. Row value: VIOLATED if any act is VIOLATED; else AMBIGUOUS if any is; else SUPPORTED if at least one guarded act is satisfied and none is UNTESTABLE; else UNTESTABLE. Use NOT-MODELLED only when every act is not modelled."
3. **Refusal clause.** "A refusal citing a rule outside §C does not bear on V1."
4. **Guard terms.** "Demonstrated need" = the record states at least one instance of the problem having occurred; a stated purpose does not count. "Successor slice" = the record names the predecessor.
5. **V4 actor rule.** "When a status change and evidence appear together, code SUPPORTED if the record attributes the change to an act of an authority, AMBIGUOUS if it names no actor."
6. **AMBIGUOUS threshold (all judgements).** "Code AMBIGUOUS only if the competing readings would give *different* codes for this judgement." This addresses the GATE1 V3 inflation.
7. **V2 applicability.** "V2 applies only if the record mentions permission, authorization, commissioning or execution. Separating approval or acceptance from execution is outside V2."

---

## 5. Kappa paradoxes (high agreement, κ near 0 or undefined)

| Bundle | Judgement / pair | Agreement | κ | Cause |
|---|---|---|---|---|
| GATE1 | V5 M–B2 | .893 | **−.037** | 26–27 of 28 UNTESTABLE for both coders; 3 minority codes that never coincide |
| GATE1 | V5 M–B1 | .857 | .304 | same prevalence |
| GATE1 | V1 M–B1 | .750 | .193 | MAIN 27/28 SUPPORTED |
| GATE1 | V3 M–B1 / M–B2 | .821 / .786 | .227 / .184 | SUPPORTED at 82–93% |
| GATE1 | V7 M–B1 / M–B2 | .929 | .472 / .576 | UNTESTABLE at about 90% |
| RESERVE | V5 M–B1, B1–B2 | .923 | **0.0** | one minority code in 13 rows |
| RESERVE | V5 M–B2 | 1.0 | **null** | every row UNTESTABLE for both coders (pe = 1) |
| All | V6 | 1.0 | 1.0 | mechanical (PRE-R43 by row number) — agreement is not informative |

**How to report them:**
- Always give raw agreement **and** the marginals: the share of the majority code for each coder, and the count of non-majority codes.
- Report κ = null as "not estimable (no variation)", never as 0 or 1.
- Add a prevalence-robust index, such as PABAK or Gwet's AC1.
- Report n. With n = 13, one row moves κ from 1 to 0.
- Do not average κ across judgements, and do not read κ < .2 on skewed judgements (V5, V7) as poor agreement.
- For V5 and V7, the meaningful quantity is agreement on the 1–3 rows where **any** coder left the default. List those rows (as in §3) instead of summarising them with κ.

---

## 6. Scope

**What this three-way result establishes**
- The frozen scorer's outputs follow arithmetically from the three codings.
- Under the frozen manuals, two blind runs of the same model family reproduce each other closely on ops (GATE1 Jaccard 1.0) and on V2, V3, V6 and V7.
- Most V1 and V4 disagreement comes from the instrument: vacuous-case, combining and applicability gaps in the manual. It does not come from coder idiosyncrasy.
- No blind coder coded any VIOLATED in 41 rows. MAIN's single VIOLATED (R-76) is not supported by either blind coder.
- MAIN differs from the blind coders mainly by resolving ambiguity toward a determinate code. In RESERVE it sometimes does so using knowledge of other records.

**What it does not establish**
- **Independent confirmation.** All three coders are the same model family. The identical B1/B2 GATE1 ops show correlated priors, and blind-to-each-other status is unverified.
- **Gate 1 proper.** That needs a different-family or human coder, pre-registered thresholds, and a MAIN coding sealed *before* the manual was finalised. MAIN's GATE1 coding was partly done while developing the manual (v1.1).
- **A held-out test of r2.** RESERVE's manual was revised *after* seeing Gate 1, n = 13, and its rows come from the same log.
- **Validity of the theory's violation detection.** VIOLATED is essentially never used, so the V-judgements' ability to detect violations is untested.
- **Precise reliability estimates.** Confidence intervals are wide at n = 28 and n = 13, and κ is prevalence-distorted (§5).
- **That the B1-vs-B2 scorer run was done as intended.** The renaming and wrapping are undocumented; the outputs are only consistent with it.

---

## 7. Disagreement classification
Categories: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive. The full list is in REVIEW.json `disagreements`. Summary:
- **Formal reasoning:**
  - V1 vacuous-truth convention (15 GATE1 rows);
  - V1 refusal clause (R R-42);
  - AMBIGUOUS used when readings converge (G V3 R-38, R-45, R-61, R-92).
- **Source interpretation:**
  - ADOPT vs ADOPT-DECISION (G R-73, R-75, R-92);
  - R-76 consequence vs direct change (also **substantive**: it is the only VIOLATED);
  - R-95 V2;
  - R-48 and R-69 V4;
  - R-59 and R-60 annotation attribution;
  - R-74 REJECT;
  - V5 reopening (G R-52, R-63, R-67, R-75).
- **Evidence scope:**
  - demonstrated need (G R-34, R-40);
  - predecessor acceptance (G R-58, R-65; R R-70);
  - MAIN's knowledge of other records (R R-54, R R-33 authority_named).
- **Coding:**
  - V2 applicability (G R-46, R-55, R-97, R-67; R R-69, R-71);
  - V4 applicability (GATE1 rows);
  - V1 combining acts (R R-33);
  - R-62 APPROVE;
  - `not_modelled` enumeration, which drives coverage.
- **Wording:**
  - "becomes" / "replacing" / "superseded" (G R-30, R-97 V7; R R-57 V7; G R-34 SUPERSEDE);
  - "binding" / "shall" as CREATE-NORM (R R-42, R-70);
  - "opened" of a non-work-package (R R-71).
- **Independence:** identical B1/B2 GATE1 ops, and MAIN's GATE1 coding done during manual development.
