# Independent review of 1an-r5 (START single-guard LOCO diagnostic, frozen)

**Basis.** I used only `diag_1an_r5.py` (its docstring is the spec), `RESULT-r5.json`, `START-EVENTS.json`, `START-CODING-LINES.txt`, `PRIOR-REVIEW-r4.md` and `SCHEMA.md`. I ran no code; every number below was recomputed by hand from START-EVENTS.json.

**Unverifiable from these files:**
- the imported modules `minimize_1an_r3.py` (`isknown`, `legal`, `variant`) and `models_1al.py`;
- the claim "committed before its first run";
- the START events of the REV and REV-TYPE variants. START-EVENTS.json lists only BASE. PRIOR-REVIEW-r4 §3(a) states they are identical, and RESULT-r5 reports identical numbers for all three variants.

**Rule applied throughout.** Everything here is formal and relative to the coded data. Nothing is empirical proof.

---

## 1. Summary

- **Recomputation matches.** Every fold for G = {s} and G = {s,t} agrees with RESULT-r5: predictions, correctness, abstention reasons, folds with predictions, coverage, both baselines and both verdicts. **mismatches = none.**
- **Spec fidelity is good.** There is one reporting gap: baseline (ii) is reported as a count with no denominator, and baseline tie-abstentions count as baseline failures (D-2).
- **The diagnostic is faithful to PRIOR-REVIEW-r4 §4(b).** The code adds a {s,t} comparator, a baseline and a verdict rule; these are additions, not divergences.
- **Circularity.** No event's `s` is shown to be OUTCOME-DERIVED. However, 4 of the 10 events with s known (R58b, R65, R81, R86) have only a bare or interpretive basis, so they are UNCLEAR.
  - With those 4 excluded, {s} is still DIAGNOSTICALLY PREDICTIVE, but **exactly at the threshold**: 4 predictions in 3 folds.
  - It flips to NOT SHOWN if any one of R47b, R58a, R47a or R56 is also excluded. R47b is an event whose `s` was recoded after the fact in r3.
- **The baseline is inadequate.** A leave-one-cluster-out majority baseline on 5 PERFORMED / 6 REFUSED data is structurally anti-correlated with the held-out event:
  - it can be right only in the two mixed clusters, so it can never score more than 2;
  - so "correct > baseline" holds for any predictor with at least 3 correct predictions.
  - The trivial rule "s = authorized ⇒ PERFORMED, else REFUSED" scores 10/10 on the events with s known, with no training at all.
- **Independence.** The 8 correct predictions come from 6 held-out clusters. Every one of them is a lookup of one of two values, and each value is backed by 4 clusters. Those 6 clusters are 2 decision episodes: the stepwise 7A/7B/7C execution authorizations, and the Batch 7 freeze and release.

---

## 2. Spec fidelity

### 2.1 Docstring vs code

| Item | Docstring | Code | Verdict |
|---|---|---|---|
| Data | START legality events (PERFORMED or RULE; CHOICE excluded) for each r3 variant | `r3.legal(r3.variant(obs, v))`, filtered to `o == "START"` | ✓ in form; `legal`/`variant` are unverifiable. All 11 events are PERFORMED or RULE ✓ |
| Fixed guard, no refit or vote | G ∈ {{s}, {s,t}} | `diag(evs, ("s",))` and `diag(evs, ("s","t"))`; no fitting | ✓ |
| LOCO | train = events not in C | ✓ (the cluster set is sorted; the order has no effect) | ✓ |
| Table | tuple on G → set of outcomes, over train events with all of G known | `tab.setdefault(...).add(P(t))`, guarded by `isknown` | ✓ git (s = UNK) never enters the table |
| Predict / abstain | predict iff all of G known and the tuple is in the table with exactly one outcome; reasons: UNK-on-G / unseen / conflicting | the checks run in that order, each with its counter | ✓ The consistency check is per tuple, not for the whole table. No conflicts occur here, so there is no effect |
| Baseline | train majority; a tie means ABSTAIN; scored on (i) all held-out events and (ii) the predicted subset | `base = None` on a tie. (i) `baseline_all_correct/scored` ✓. (ii) `baseline_on_predicted_correct` only | **Gap (D-2).** (ii) has no denominator; for {s} it is 6 scored of 8, which RESULT does not show. "Accuracy" is reported as counts |
| Reporting | per G and variant: held-out, predicted, correct, wrong, abstentions by reason, coverage, baselines | ✓, plus per-fold n/pred/correct | ✓ apart from D-2 |
| Verdict | wrong = 0 ∧ predicted ≥ 4 across ≥ 3 folds ∧ correct > baseline correct on the same subset | exactly this conjunction | ✓ literal. Tie-abstentions count as baseline failures, which favours G (D-2) |
| Labels | "Neither is empirical proof" | the `labels` string says so | ✓ |
| Selftest | — | 5 events: {s} predicts all 5, {s,t} predicts none, 2 folds scored | ✓ I checked it by hand. It also shows the baseline pathology: 0 of 2 correct |

**spec_fidelity_ok = true** (minor reporting gap D-2).

### 2.2 Against PRIOR-REVIEW-r4 §4(b)

| Proposal | r5 | Verdict |
|---|---|---|
| Predict with {s} alone | fixed G = {s} | ✓ |
| Define consistency as *table* consistency, so that git (s = UNK) stays out | per-tuple table consistency, and UNK is excluded from the table | ✓ (per-tuple vs whole-table makes no difference here) |
| Freeze before running | docstring: "committed before its first run" | claimed; unverifiable |
| R72 and R79 still abstain | both abstain as unseen-tuple | ✓ as predicted |
| — | adds a {s,t} comparator, a majority baseline and a verdict rule | additions; they do not conflict with the proposal |

**faithful_to_prior_proposal = true.**

---

## 3. Recomputation by hand

Events (BASE). P = PERFORMED, N = REFUSED.

| Event | Cluster | s | t | Outcome |
|---|---|---|---|---|
| R72 | R-72 | auth-proviso-unmet | WP-4B | N |
| git | git-6a67da5d7 | UNK | WP-4B | P |
| R79 | R-79 | permission-only | WP-8 | N |
| R47a | R-47 | authorized | 7A | P |
| R47b | R-47 | not-authorized | 7B | N |
| R56 | R-56 | not-authorized | 7B | N |
| R58a | R-58 | authorized | 7B | P |
| R58b | R-58 | not-authorized | 7C | N |
| R65 | R-65 | authorized | 7C | P |
| R81 | R-81 | not-authorized | §12 | N |
| R86 | R-86 | authorized | §12 | P |

The data has 5 P and 6 N events in 9 clusters.

### 3.1 G = {s}

| Fold | Train P/N | Baseline | Held-out → prediction | Correct | Baseline on held-out |
|---|---|---|---|---|---|
| R-47 | 4/5 | N | R47a auth → P (via R58a, R65, R86); R47b na → N (via R56, R58b, R81) | 2/2 | R47a ✗, R47b ✓ |
| R-56 | 5/5 | tie | R56 na → N | 1/1 | not scored |
| R-58 | 4/5 | N | R58a auth → P (via R47a, R65, R86); R58b na → N | 2/2 | R58a ✗, R58b ✓ |
| R-65 | 4/6 | N | R65 auth → P | 1/1 | ✗ |
| R-72 | 5/5 | tie | apu: unseen-tuple | — | not scored |
| R-79 | 5/5 | tie | po: unseen-tuple | — | not scored |
| R-81 | 5/5 | tie | R81 na → N | 1/1 | not scored |
| R-86 | 4/6 | N | R86 auth → P | 1/1 | ✗ |
| git | 4/6 | N | s = UNK: UNK-on-G | — | ✗ |

**Totals:**
- held-out 11; predicted 8; correct 8; wrong 0;
- abstentions: UNK 1 / unseen 2 / conflicting 0;
- folds with a prediction: 6; coverage 0.727;
- baseline (i): 2/7;
- baseline (ii): 2 correct, 6 scored of 8 predicted;
- verdict: PREDICTIVE.

**All ✓ against RESULT-r5**, in all three variants as reported.

### 3.2 G = {s,t}

| Fold | Held-out tuple(s) | Result |
|---|---|---|
| R-47 | (auth, 7A) unseen; (na, 7B) → N via R56 | 1 prediction, correct |
| R-56 | (na, 7B) → N via R47b | 1 prediction, correct |
| R-58 | (auth, 7B): the table only has (na, 7B). (na, 7C): the table only has (auth, 7C) | 2 unseen |
| R-65, R-72, R-79, R-81, R-86 | each tuple is unique | unseen |
| git | s = UNK | UNK-on-G |

**Totals:**
- predicted 2; correct 2; abstentions: UNK 1 / unseen 8;
- folds with a prediction: 2; coverage 0.182;
- baseline (i): 2/7; baseline (ii): 1 (R47b);
- verdict: NOT SHOWN (predicted < 4).

**All ✓.**

**recomputation_matches = true; mismatches = [].**

---

## 4. Circularity (event by event)

**Criteria.**
- **INDEPENDENT:** the basis names an authorization instrument, or its absence or scope (an AUTHORIZE act, plan approval vs execution authorization, a proviso, a permission), and the value follows from it without reference to whether the start happened.
- **OUTCOME-DERIVED:** the value merely restates the outcome.
- **UNCLEAR:** the basis only restates the value, or it maps a non-authorization state onto "authorized" by interpretation.
- The same standard led r2 to downgrade git's 'auth-full' to UNK, because it "rested on an INTERPRETATION".

**Evidence caveat (D-8).** Every "quoted basis" in START-CODING-LINES is the coder's parenthetical in the `event_id`. None is source text with an anchor.

| Event | s | Quoted basis | Class | Reason |
|---|---|---|---|---|
| R72 | auth-proviso-unmet | "START WP-4B at R-72 (proviso unmet)" | INDEPENDENT | Names a proviso on an authorization that was unmet before the act (it abstains anyway) |
| git | UNK | semobs_r2: "state 'auth-full' rested on an INTERPRETATION of R-78 (F-LOG-0129: I-A1 UNDETERMINED) → s = UNK" | N/A (not coded) | Already treated as unknown; it never enters the table and affects only the baseline |
| R79 | permission-only | "START WP-8 (permission only)" | INDEPENDENT | Names the level of authorization held before the act (it abstains anyway) |
| R47a | authorized | "START 7A after AUTHORIZE(7A)" | INDEPENDENT | A prior AUTHORIZE act whose target is 7A |
| R47b | not-authorized | "START 7B after AUTHORIZE(7A)"; semobs_r3: "for START, `s` = the authorization state OF THE ACT'S TARGET" | INDEPENDENT (derived) | Derived from the scope of AUTHORIZE(7A), which excludes 7B. R-56 ("execution not issued" for 7B) corroborates it. **But** the original coding was s = "auth-full(7A)", the same value as R47a, and it was changed in r3 after the data had been seen (D-6) |
| R56 | not-authorized | "START 7B at R-56 (plan approved, execution not issued)" | INDEPENDENT | Separates plan approval from execution authorization and states the pre-act state |
| R58a | authorized | "START 7B at R-58 (execution authorized)" | INDEPENDENT (weak) | Names the execution-authorization instrument, in contrast with R-56. It sits in the same ruling as the start, and no separate AUTHORIZE observation for 7B appears in these files |
| R58b | not-authorized | "START 7C at R-58 (remains unauthorized)" | UNCLEAR | Restates the value and names no instrument. It becomes derivable (like R47b) only if R-58's authorization scope is documented |
| R65 | authorized | "START 7C at R-65 (authorized)" | UNCLEAR | Only restates the value; no pre-act authorization act is named. It cannot be told apart from "authorized because it was started" |
| R81 | not-authorized | "START §12 reconcile at R-81 (Batch 7 frozen)" | UNCLEAR | Maps a batch state (frozen) onto the authorization of a different target (§12 reconcile). This is an interpretation, the same kind that made git UNK |
| R86 | authorized | "START §12 reconcile at R-86 (Batch 7 released)" | UNCLEAR | Same issue as R81: "released" is mapped to "authorized" |

**No event is demonstrably OUTCOME-DERIVED.**

**Reverse direction (D-7).** For the REFUSED events R56, R58b and R81, no attempted act is visible. The REFUSED outcome may itself have been inferred from the stated state, which would make s → outcome true by construction. These files cannot settle this.

### 4.1 Verdict excluding the UNCLEAR events (R58b, R65, R81, R86)

The remaining set has 7 events in 6 clusters: R72 N, git P, R79 N, R47a P, R47b N, R56 N, R58a P. That is 3 P and 4 N.

**G = {s}:**

| Fold | Train P/N | Baseline | Prediction | Baseline on predicted |
|---|---|---|---|---|
| R-47 | 2/3 | N | R47a → P (via R58a) ✓; R47b → N (via R56) ✓ | 1 |
| R-56 | 3/3 | tie | R56 → N (via R47b) ✓ | — |
| R-58 | 2/4 | N | R58a → P (via R47a) ✓ | 0 |
| R-72 | 3/3 | tie | unseen | — |
| R-79 | 3/3 | tie | unseen | — |
| git | 2/4 | N | UNK | (baseline wrong) |

**Result:** predicted 4, correct 4, wrong 0, folds 3; baseline (ii) 1 (scored 3); baseline (i) 1/4. **DIAGNOSTICALLY PREDICTIVE, exactly at the threshold** (predicted = 4, folds = 3).

**G = {s,t}:** 2 predictions (R47b and R56 via (na, 7B)), so NOT SHOWN.

**Sensitivity:**
- Additionally excluding **R47b** (post-hoc recode) gives NOT SHOWN: R56's value "na" becomes unseen, leaving 2 predictions.
- Additionally excluding **R58a** gives NOT SHOWN: R47a becomes unseen, leaving 2 predictions. Excluding R47a or R56 has the same effect, by symmetry.
- Reclassifying **R58b** as INDEPENDENT gives PREDICTIVE: 5 correct, 3 folds, baseline (ii) 3.
- Also dropping **git** gives PREDICTIVE: 4 correct, 3 folds, baseline (ii) 2.

The strict verdict holds, but it is knife-edge. It rests on 2 mutually supporting pairs: {R47a, R58a} for "authorized → P" and {R47b, R56} for "not-authorized → N".

---

## 5. Independence

- **Correct predictions (full data):** 8, held out from **6 distinct clusters** (R-47 ×2, R-56, R-58 ×2, R-65, R-81, R-86).
- **Training rows behind them:** every prediction is a lookup of one of two values.
  - "authorized → P" is supported by R47a, R58a, R65, R86, from 4 clusters.
  - "not-authorized → N" is supported by R47b, R56, R58b, R81, from 4 clusters.
  - Together these are the same 8 events, from the same 6 clusters.
  - The set is **closed and mutually supporting**: each correct prediction is backed only by the other predicted events.
- **Distinct source decisions:** 6 coded clusters (rulings). These fall into **2 decision episodes**:
  - the stepwise execution authorization of 7A/7B/7C (R-47, R-56, R-58, R-65);
  - the freeze and release of Batch 7 (R-81, R-86).
- **Within-cluster rows:** R-47 and R-58 each contribute one P row and one N row from a single decision.
- **Common origin:** all events are register rows from one project's ruling sequence.
- **Strict set:** 4 predictions from 3 clusters and 1 episode (the 7-series).

---

## 6. Baseline adequacy

- **The LOCO majority baseline is degenerate here.** With 5 P and 6 N events:
  - holding out a single P cluster leaves an N majority, so the baseline is wrong;
  - holding out a single N cluster leaves 5/5, a tie, so it abstains.
- It can be correct only on the N event of a mixed cluster (R-47, R-58), so its maximum is **2**, and it scores exactly 2.
- The verdict condition "correct > baseline" is therefore satisfied by any predictor with at least 3 correct predictions. It adds almost no discriminating power (D-1).
- **Better comparators:**
  - the full-data majority (N) scores 4/8 on the {s}-predicted subset (2/4 on the strict set);
  - the 50% chance rate scores 4/8.
  - {s} still beats both, but these comparisons are not frozen and not part of the verdict.
- **Trivial rule.** "s = 'authorized' ⇒ PERFORMED, otherwise REFUSED" needs no training and scores **10/10** on the events with s known (11/11 if git is abstained).
  - This does not make the result a logical tautology, because no `s` is shown to be outcome-derived.
  - It does make it **definitionally close**. Since r3, `s` is coded as "the authorization state of the act's target", and the rule being tested ("START requires target authorization") is the coding's own premise.
  - So the diagnostic tests whether the coded states are *consistent* with the outcomes across clusters, not whether anything was discovered.
  - For the 4 UNCLEAR events, and the possible reverse inference in D-7, consistency may hold by construction.
- **Pooling.** The guard's "generalization across 4 clusters" also depends on pooling two different kinds of state under one label: target execution authorization, and batch freeze/release (D-16).

---

## 7. Scope of the claim

**Strongest supported claim.**
- On the strict START observations as coded (11 events, 9 clusters; reported identical for BASE, REV and REV-TYPE), the fixed guard {s} used as a lookup table:
  - predicts 8 of 11 held-out events across 6 held-out clusters with 0 errors;
  - does this because each of the two recurring values ('authorized', 'not-authorized') occurs with only one outcome, across 4 clusters each.
- Under the frozen rule this is DIAGNOSTICALLY PREDICTIVE: a formal consistency property of the coded data.
- Restricted to events with an independent pre-act authorization basis, the property still holds, but only at the threshold (4 predictions, 3 folds, 1 decision episode).

**Not supported:**
1. That authorization state is empirically necessary or sufficient for START (no empirical replication).
2. Independent confirmation. It is the same corpus and the same coders; the r3 recode of R47b came after the data; the diagnostic was proposed after seeing r4.
3. Generalization beyond this project's ruling register, or beyond the 2 decision episodes.
4. That {s} beats a meaningful baseline. The frozen baseline is structurally capped at 2.
5. That t is unnecessary, or that r4's {s,t} guard was wrong. {s} abstains on 3 of 11 events, and the git × R72 pair is still unseparated.
6. Robustness to coding choices. One reclassification flips the strict verdict.
7. That this rescues or overturns r4's verdicts. The docstring itself says "not a rescue".
8. Any statistical or causal claim.

---

## 8. Disagreement register

| ID | Disagreement | Category |
|---|---|---|
| D-1 | The frozen LOCO majority baseline is structurally anti-correlated on 5P/6N data (maximum 2). "correct > baseline" is nearly automatic | formal reasoning |
| D-2 | Baseline (ii) is reported without a denominator (6 of 8 scored for {s}). Tie-abstentions count as baseline failures, so "the same predicted subset" is not really the same for the baseline. "Accuracy" is reported as counts | coding |
| D-3 | R81/R86: 'Batch 7 frozen/released' is mapped onto the authorization state of §12 reconcile. This is an interpretation, the same kind r2 used to set git to UNK. Class: UNCLEAR | source interpretation |
| D-4 | R65: the basis "(authorized)" only restates the value; no prior authorization act is named. Class: UNCLEAR | source interpretation |
| D-5 | R58b: "(remains unauthorized)" names no instrument or scope. Class: UNCLEAR, unless R-58's authorization scope is documented | source interpretation |
| D-6 | R47b's s was recoded in r3 from 'auth-full(7A)', which did not separate it from R47a, to 'not-authorized', which does. The rule is outcome-neutral, but the choice came after the data. The strict verdict depends on this event | coding |
| D-7 | Possible reverse circularity: the REFUSED outcomes of R56, R58b and R81 may be inferred from the stated state; no attempted act is visible | evidence scope |
| D-8 | Every "quoted basis" is a coder paraphrase in the event_id. No source anchors or text are in the provided files | evidence scope |
| D-9 | The 8 correct predictions come from 6 clusters and 2 decision episodes, and form a closed, mutually supporting set of two value classes | independence |
| D-10 | The trivial rule "authorized ⇒ P" gives 10/10 without training. The guard is the coding's own premise, so the result is consistency, not discovery | formal reasoning |
| D-11 | Excluding the UNCLEAR events leaves the verdict exactly at the threshold (4 predictions, 3 folds). Excluding any one of R47a, R47b, R56 or R58a flips it to NOT SHOWN | formal reasoning |
| D-12 | The r3/m1 dependencies, the legality filter and the REV/REV-TYPE START data are unverifiable. Identical numbers across the variants are accepted as reported | evidence scope |
| D-13 | "Committed before its first run" is unverifiable from these files | evidence scope |
| D-14 | {s,t} NOT SHOWN does not show that t is unnecessary, and r5 does not address D-N1 (git's s = UNK) | formal reasoning |
| D-15 | The label "DIAGNOSTICALLY PREDICTIVE" can be read as predictive validity. It means only lookup consistency across clusters under a weak baseline | wording |
| D-16 | 'authorized' pools two different kinds of state: target execution authorization (the 7-series) and batch release (§12). The apparent spread across 4 clusters partly comes from this pooling | substantive |

**Not in dispute:**
- every number in RESULT-r5.json;
- the code's implementation of the fixed guard, the LOCO folds, the tables, the abstention reasons, the baseline and the verdict;
- the r5 labels "not empirical proof; not a rescue".
