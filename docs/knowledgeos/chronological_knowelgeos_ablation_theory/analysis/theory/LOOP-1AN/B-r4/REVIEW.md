# Independent review of 1an r4 (generalization instrument, frozen)

**Basis.** I used only `minimize_1an_r4.py` (its docstring is the spec), `minimize_1an_r3.py`, `RESULT-r4.json`, `OBSERVATIONS.json` and `PRIOR-REVIEW-r3.md`. I ran no code; every number below was recomputed by hand from OBSERVATIONS.json. Anything that depends on `models_1al.py` or `MODELS-1AL/SPEC-1AM-SUPERSEDE.json` is marked **unverifiable**.

**Rule applied throughout.** Every result here is formal and model-relative. "Generalization" means behaviour on the strict observations under this instrument. It is never empirical proof.

---

## 1. Summary

- **Recomputation matches.** I recomputed START recurrence, LOCO for RAISE, ADOPT, SUPERSEDE and START (fold by fold), disjoint support, and the operation-test counts for BASE and REV+R44. Every one agrees with RESULT-r4. I also recomputed the operation-test power for all six variants: 145 / 155 / 142 / 153 / 137 / 147, all ✓. **mismatches = none.**
- **Spec fidelity is good, with two gaps in the spec itself.**
  - (i) The docstring does not say how the *several* minimum guards are combined into one prediction. The code takes the union of votes from the guards that can vote, and predicts only when that union is a single outcome.
  - (ii) "The full-data minimum guard" is ambiguous when there are several of them. The code counts a fold as stable if *any* full-data minimum guard is among the train minima.

  Gap (i) matters: all 4 correct RAISE predictions and both correct SUPERSEDE-BASE predictions come from **one** guard, {r}, voting while the other guards abstain.
- **Faithfulness to the PRIOR-REVIEW-r3 criterion is partial.**
  - **Faithful:** recurrence (per pair, with the outcome condition).
  - **Divergent:**
    - LOCO is scored per operation, not per variable, so there is no variable-level attribution;
    - disjoint support is reported only as the pooled count, without the cross-cluster-only and per-operation counts;
    - the INSUFFICIENT threshold is "< 3 clusters" rather than "2 events or 1 cluster" (no effect on this data).
  - The operation test is a new design that the prior review did not propose.
- **Main validity findings:**
  1. **RAISE 4/8 is not evidence that e generalizes.**
     - All 4 correct predictions come from the route guard {r}, fitted on one training pair (L493-B vs P1).
     - The only prediction made with e was wrong (P1: e = 1, yet PERFORMED).
     - Always predicting PERFORMED would score 5/8.
  2. **START LOCO (2/11 coverage) is uninformative about s.**
     - The fitted guard {s,t} requires the exact (s,t) tuple to recur, and t is essentially a target identifier.
     - Both predictions are the one recurring tuple, (not-authorized, 7B).
     - The support for "s generalizes" comes from recurrence (4 GENERALIZING pairs) and disjoint support (2), not from LOCO.
  3. **The operation-test verdict "MODEL-COND-REDUNDANT under MO" overreaches.**
     - "0 decisive of 145 comparable" shows only that o is never the *sole* difference on the shared variables.
     - o is confounded with a, k and r. Several comparable pairs differ *only* in k.
     - 103 of the 248 cross-operation pairs (BASE) are untested.
     - In BASE and REV-TYPE, one of them (ADOPT Chief-declined vs ANNOTATE) has no known separating variable other than o.
     - The only structurally possible decisive pair (ANNOTATE vs SUPERSEDE R-94) is excluded as CHOICE.
  4. **What R-44 changes.** Outside SUPERSEDE, it changes only the operation-test power (+10 / +11 / +10) and disjoint support k (2 → 3). The k = 3 comes from three operations; two of the three are within-cluster contrasts, and all three are identity-like lookups.

---

## 2. Spec fidelity

### 2.1 Item by item (r4 docstring vs code)

| Item | Docstring | Code | Verdict |
|---|---|---|---|
| Data | r3 variants × {without, with R-44} | `run(sm, base)` and `run(sm, base + [R44])` for BASE, REV, REV-TYPE | ✓. The provenance through `models_1al.r4_observations()` is unverifiable. OBSERVATIONS.json is taken as the exact data |
| R-44 event | o SUPERSEDE, r RULING, a ARB, k mechanism, e UNK, x UNK, PERFORMED, cluster R-44 | the `R44` dict has all of these, plus s = t = "n/a", h = c = UNK, ground n/a, and genre/speech_act | ✓ against the docstring. "n/a" and UNK are both unknown to `isknown`, and s/t are outside SUPERSEDE's APPL: **no effect**. Agreement with SPEC-1AM-SUPERSEDE.json is **unverifiable**. Cluster R-44 (not R-57, where it is reported) is a source-interpretation choice |
| Recurrence | p-side recurs iff an event of o in a different cluster has p[v] known and p's outcome | `rec(a)`: `e is not a`, different cluster, `isknown(e[v])`, `e[v]==a[v]`, same PERFORMED/non-PERFORMED side | ✓ exact. Classes: 0 → LOOKUP, 1 → HALF, 2 → GENERALIZING ✓ |
| Forced pairs | D(p,q) = {v}, over the MF pool | `r3.D(p,q,pool,U) == {v}` for v FR | ✓ (a pair with D = {v} makes v FR, so restricting to FR variables loses nothing) |
| INSUFFICIENT | < 3 distinct clusters among legality events | the recurrence field is replaced by the string if clusters < 3. `loco` returns INSUFFICIENT | ✓. `recurrence_raw` is still emitted for INSUFFICIENT operations (wording risk, D-12) |
| LOCO train/test | train = events not in C | ✓ | ✓ |
| LOCO guards | G ranges over train's minimum hitting guards (MF) | `r3.analyse_op(train, pool)`, minimum_guards; `[]` if UNCONSTRAINED | ✓. A PARTIAL train set whose pairs are all unseparated also gives `[]`, so the fold abstains (ADOPT BASE fold R-81..85) |
| LOCO table | tuple → outcome from train events with all G known; drop tuples seen with both outcomes | stores the set of outcomes and uses the key only if `len == 1` | ✓ equivalent |
| LOCO predict/abstain | predict if all G known and tuple in table, else abstain | per guard ✓ | ✓ per guard |
| **Voting across guards** | **not specified** | union of the votes from the guards that can vote; predict iff the union is a single outcome; guards that cannot vote are ignored | **Spec gap (D-1).** This is decisive for RAISE (7 of the 8 predictions) and SUPERSEDE BASE (2 of 2) |
| Coverage | predictions / held-out | ✓ | ✓ |
| **Stability** | fraction of folds whose train minima "include the full-data minimum guard" | `any(g in mins for g in fullmins)` | **Ambiguous (D-2).** With several full-data minima (RAISE BASE, SUPERSEDE BASE), "any" and "all" give the **same numbers on this data** |
| Disjoint support | per variant, MF, across operations; the maximum number of forced pairs whose cluster sets are pairwise disjoint; a within-cluster pair uses one cluster; exhaustive | distinct cluster-pair frozensets, then the largest pairwise-disjoint subfamily found by exhaustive search | ✓ exact |
| Operation test | S = (APPL[o_p] ∩ APPL[o_q]) ∪ {r}; comparable iff all of S known on both; decisive iff comparable and equal on all of S; power = number comparable; verdicts | ✓ line by line | ✓ exact. The problem is the **meaning** of the MCR verdict (§4c), not the implementation |

**spec_fidelity_ok = true**, subject to the two under-specifications (D-1, D-2).

### 2.2 Against the PRIOR-REVIEW-r3 criterion (§4a/§4b)

| Prior proposal | r4 | Divergence |
|---|---|---|
| Recurrence: both sides recur with their outcome in another cluster → generalizing; one → HALF; neither → LOOKUP | per forced pair, exactly this | **Faithful.** The prior review's own *application* table was looser than its criterion: it gave ADOPT a and c as HALF, but by the stated rule both are LOOKUP, which is what r4 gives (D-9). It called e "single-cluster"; r4 gives HALF because e = 1/REFUSED recurs in L493-B |
| LOCO: recompute the minimum guards per fold; "a **variable** whose separations all fail LOCO is non-generalizing" | recomputes per fold ✓, but scores per **operation** and never attributes a prediction to a variable or guard | **Divergent (D-3).** The prior's variable-level verdict cannot be read off r4. For example, RAISE's correct predictions come from r, not e |
| Operations with 2 events or 1 cluster → INSUFFICIENT | < 3 clusters → INSUFFICIENT | Stricter. On this data every operation with 2 clusters has exactly 2 events, so **no effect** (D-4) |
| Disjoint support: R4 count, plus cross-cluster-only count, plus per-operation maximum | only the pooled R4 count | **Divergent (D-5).** Pooled k = 3 (+R44) = OPEN-WORK (within R-60) + REGISTER (within S0804-L576) + SUPERSEDE (R-44/R-77). The cross-cluster-only k is 1, and the per-operation maximum for k is 1 |
| D-N5 (R-44 scope) | R-44 added as a separate +R44 variant | Faithful |
| D-N2 (operation test has zero power) | new shared-APPL comparability test | Not a prior proposal; it is r4's own design. Its verdict semantics overreach (D-6, D-7) |
| D-N1 (START t is required by ignorance) | not addressed | Still open. It is the direct cause of START's LOCO abstentions (D-10) |

**criterion_faithful = false (partial).** Recurrence is faithful. LOCO attribution and the disjoint-support breakdown diverge.

---

## 3. Recomputation by hand

Notation: P = PERFORMED, N = not PERFORMED. MF pool = APPL[o] ∪ {r}.

### 3(a) START recurrence (all variants: the START events are identical in all six)

Events:

| Event | Cluster | s | t | Outcome |
|---|---|---|---|---|
| R72 | R-72 | auth-proviso-unmet | WP-4B | N |
| git | git-6a67da5d7 | **UNK** | WP-4B | P |
| R79 | R-79 | permission-only | WP-8 | N |
| R47a | R-47 | authorized | 7A | P |
| R47b | R-47 | not-authorized | 7B | N |
| R56 | R-56 | not-authorized | 7B | N |
| R58a | R-58 | authorized | 7B | P |
| R58b | R-58 | not-authorized | 7C | N |
| R65 | R-65 | authorized | 7C | P |
| R81 | R-81 | not-authorized | §12 | N |
| R86 | R-86 | authorized | §12 | P |

That is 9 clusters. a, k and r are constant.

**s-forced pairs:**

| Pair | p-side recurs? | q-side recurs? | Class | r4 |
|---|---|---|---|---|
| (R47b, R58a) | na/N in R-56, R-58, R-81 ✓ | auth/P in R-47, R-65, R-86 ✓ | GENERALIZING | ✓ |
| (R56, R58a) | ✓ | ✓ | GENERALIZING | ✓ |
| (R58b, R65) | na/N in R-47, R-56, R-81 ✓ | auth/P in R-47, R-58, R-86 ✓ | GENERALIZING | ✓ |
| (R81, R86) | ✓ | ✓ | GENERALIZING | ✓ |

**t-forced pairs.** All five run through git. git's side is WP-4B/P, and the only other WP-4B event is R72, which is N. So git's side never recurs.

| Pair | Other side | Recurs? | Class | r4 |
|---|---|---|---|---|
| (git, R79) | WP-8/N | no | LOOKUP | ✓ |
| (git, R47b) | 7B/N | in R-56 ✓ | HALF | ✓ |
| (git, R56) | 7B/N | in R-47 ✓ | HALF | ✓ |
| (git, R58b) | 7C/N | no (R65 is 7C/P) | LOOKUP | ✓ |
| (git, R81) | §12/N | no (R86 is P) | LOOKUP | ✓ |

(git, R72) has D = {} and stays unseparated.

### 3(b) LOCO

**START** (full-data minimum guard {s,t}). The table uses only events with both s and t known, so git never enters it.

| Held-out cluster | Train minima | Stable | Prediction(s) | Correct? |
|---|---|---|---|---|
| R-72 | {s,t} (git's {t}-pairs and the s-forced pairs remain) | ✓ | (apu, WP-4B) not in table → abstain | — |
| git | **{s}** (without git, every D contains s) | ✗ | git's s is UNK → abstain | — |
| R-79 | {s,t} | ✓ | (po, WP-8) unseen → abstain | — |
| R-47 | {s,t} | ✓ | R47a (auth, 7A) unseen → abstain. R47b (na, 7B) → N, via R56 | ✓ |
| R-56 | {s,t} | ✓ | (na, 7B) → N, via R47b | ✓ |
| R-58 | {s,t} ({t} from git; {s} from R81/R86) | ✓ | R58a (auth, 7B) and R58b (na, 7C) unseen → abstain | — |
| R-65 | {s,t} | ✓ | (auth, 7C) unseen → abstain | — |
| R-81 | {s,t} | ✓ | (na, §12) unseen → abstain | — |
| R-86 | {s,t} | ✓ | (auth, §12) unseen → abstain | — |

Totals: held 11, predictions 2, correct 2, wrong 0, abstain 9, coverage 0.182, stability 8/9. **✓ matches.**

**RAISE BASE** (full-data minima {a,e} and {e,t}; pool {a,k,s,t,e,x,r}):

| Held-out cluster | Train | Train minima | Stable | Predictions | Result |
|---|---|---|---|---|---|
| R-36 (7 events) | L493-B (N), P1 (P). D = {a,t,r} | {a}, {t}, {r} | ✗ | {a}: ARB not in table, no vote. {t}: AST-013 not in table, no vote. **{r}: RULING → P.** All 7 predicted P | P2, P3, P9, P10 correct (4); N5, N14, N17 wrong (3) |
| S0815-L483 (L) | P\*/N\* give {e}; P1/N\* give {a,t} | {a,e}, {e,t} | ✓ | (PO/ARB, 1) not in the {a,e} table; (1, methodology) not in the {e,t} table → abstain | — |
| R-41 (P1) | P\*/N\* give {e}; P\*/L give {a,k,t,e,r} | **{e}** | ✗ | e = 1 → N (N5, N14, N17 and L are all N). Actual P | wrong |

Totals: held 9, predictions 8, correct 4, wrong 4, abstain 1, coverage 0.889, stability 1/3. **✓**

**RAISE REV / REV+R44.** Here L493-B has a = UNK.
- Fold R-36: D(L,P1) = {t,r}, so the train minima are {t} and {r}. Only {r} votes: 4 correct, 3 wrong.
- Fold L: the train minima are {a,e} and {e,t}; the full-data minimum is {e,t}, so the fold is stable. L's a is UNK and (1, methodology) is not in the {e,t} table → abstain.
- Fold R-41: {e} → wrong.

Totals: 4/4/1 (correct/wrong/abstain), stability 1/3. **✓**

**ADOPT.** E1 = Chief declined (N), E2 = DA at R-86 (P), E3 = R-91 held (N).

| Variant | Full-data minimum | Fold E1 | Fold E2 | Fold E3 | Totals | Match |
|---|---|---|---|---|---|---|
| BASE | {a} (D(E2,E3) = {}) | train E2, E3: only D = {}, so no guards → abstain, ✗ | train E1, E3: both N → UNCONSTRAINED → abstain, ✗ | train {a}, ✓: DA → P; actual N → wrong | 1 prediction, 0 correct, 1 wrong, 2 abstain; 0.333; 1/3 | ✓ |
| REV+R44 (= REV) | {a,c} | train {c}, ✗: E1 c = conformant → P; actual N → wrong | UNCONSTRAINED → abstain, ✗ | train {a}, ✗: DA → P → wrong | 2 predictions, 0 correct, 2 wrong, 1 abstain; 0.667; 0/3 | ✓ |

**SUPERSEDE BASE** (R-77 N, R-83 N, D-12 P; R-94 is CHOICE and is dropped). Full data: D = {k,r} for both pairs, so the minima are {k} and {r}.

| Held-out cluster | Train minima | Stable | Prediction | Result |
|---|---|---|---|---|
| R-77 | {k}, {r} | ✓ | {k}: ADR not in table. **{r}: RULING → N** | correct |
| R-83 | {k}, {r} | ✓ | {r}: RULING → N | correct |
| ADR-MP | train is all N → UNCONSTRAINED | ✗ | abstain | — |

Totals: 2 predictions, 2 correct, 0 wrong, 1 abstain; 0.667; 2/3. **✓**

**SUPERSEDE +R44** (identical in BASE, REV and REV-TYPE):
- The new pairs are (R77, R44) with D = {k}, and (R83, R44) with D = {a,k}. So the full-data minimum is {k} alone: k FR, a and r MCR.
- Flags: e and k. a is no longer flagged, because ARB now occurs twice.

| Held-out cluster | Train minima | Stable | Prediction | Result |
|---|---|---|---|---|
| R-44 | {k}, {r} | ✓ | {r}: RULING → N; actual P | **wrong** |
| R-77 | {k} | ✓ | ADR unseen → abstain | — |
| R-83 | {k} | ✓ | design-rule unseen → abstain | — |
| ADR-MP | {k} | ✓ | decision-log-entry unseen → abstain | — |

Totals: 1 prediction, 0 correct, 1 wrong, 3 abstain; 0.25; 4/4. **✓**

Recurrence of k on the pair (R77, R44): ADR/N and mechanism/P are both unique → LOOKUP ✓.

### 3(c) Disjoint support (pooled across operations, MF)

The forced cluster sets for BASE, by variable:

| Variable | Forced cluster sets | Disjoint count |
|---|---|---|
| a | ADOPT {R-81..85-ann, R-86}; AUTHORIZE-IMPL {R-70, R-89} | **2** ✓ |
| k | OPEN-WORK {R-60}; REGISTER {S0804-L576} | **2** ✓ |
| s | {47,58}, {56,58}, {58,65}, {81,86}. The first three share R-58 | **2** ✓ |
| t | 5 sets, all containing git | **1** ✓ |
| e | {R-36} | 1 ✓ |
| h | {S0804-L576, register-numbering} | 1 ✓ |

- **REV+R44:** a 2, c 1 ({R-86, R-91}), h 1, **k 3** (+ {R-44, R-77}), e 1, s 2, t 1. **✓**
- **Cross-cluster-only counts, not reported by r4:** a 2, k 1 (+R44) or 0 (without it), s 2, t 1, e 0, c 1, h 1.

### 3(d) Operation test

Two facts decide most pairs. First, r is always in S. Second, every pairwise APPL intersection contains a and k. So every pair involving any of these events is non-comparable:
- ADOPT E1 and E2 (k is a token in BASE);
- RAISE P1 (k is UNK);
- SUPERSEDE D-12 (a is UNK);
- the ASSIGN-ID events (r is UNK).

git (s = UNK) is comparable only when S has no s, i.e. with REGISTER, OPEN-WORK and SUPERSEDE partners.

**BASE: comparable pairs counted per P-event.**

| P-event(s) | Comparable N-events in other operations | Count |
|---|---|---|
| AI70 | E3, RG1, OW1, START N ×6, RAISE N ×4, R77, R83 | 15 |
| RG2 | E3, AI89, OW1, START N ×6, RAISE N ×4, R77, R83 | 15 |
| OW2 | E3, AI89, RG1, START N ×6, RAISE N ×4, R77, R83 | 15 |
| git | RG1, OW1, R77, R83 | 4 |
| 4 START P (non-git) | E3, AI89, RG1, OW1, RAISE N ×4, R77, R83 | 10 each = 40 |
| 4 RAISE P\* | E3, AI89, RG1, OW1, START N ×6. SUPERSEDE is excluded: S includes x, and x is UNK on R77/R83 | 10 each = 40 |
| ANNOTATE | E3, AI89, RG1, OW1, START N ×6, RAISE N ×4, R77, R83 | 16 |

**Total = 145 ✓.**

**The other variants, as differences:**

| Variant | Change | Total |
|---|---|---|
| BASE+R44 | R-44 (a, k, r known; e UNK) is comparable with E3, AI89, RG1, OW1 and the 6 START N. Not with RAISE N (S contains e): +10 | **155** ✓ |
| REV | E2 (k now ruling) gains RG1, R77, R83 (+3). E1 gains RG2 and ANNOTATE (+2). L493-B (a UNK) loses 8 | **142** ✓ |
| REV+R44 | R-44 gains 11 (the 10 above plus E1) | **153** ✓ |
| REV-TYPE | 142 − 5 | 137 ✓ |
| REV-TYPE+R44 | 137 + 10 | 147 ✓ |

**Spot-checked comparable pairs.** None is decisive.

| # | Pair | S | Differing variables |
|---|---|---|---|
| 1 | AI70 × START R72 | a, k, s, t, r | a, s, t, r |
| 2 | OW2 × E3 | a, k, t, r | a, t (k = ruling on both) |
| 3 | git × OW1 | a, k, t, r | a, k, t, r |
| 4 | RAISE P2 × AI89 | a, k, s, t, r | a, k, s, t |
| 5 | ANNOTATE × R83 | a, k, r | **k only** |
| 6 | ANNOTATE × AI89 | a, k, r | **k only** |
| 7 | REV+R44: R-44 × OW1 | a, k, r | **k only** (mechanism vs recording-note) |
| 8 | REV+R44: E1 × ANNOTATE | a, k, r | **k only** (ruling vs acceptance-record) |
| 9 | REV+R44: E2 × RG1 | a, k, r | a, k |

Non-comparable examples:
- RAISE P2 × R77: S contains x, and x is UNK on R77.
- R-44 × RAISE N5: S contains e, and e is UNK on R-44.
- REV L493-B × AI70: L493-B's a is UNK.

Decisive = 0 in all six variants ✓.

**recomputation_matches = true; mismatches = [].**

(The REV-TYPE guard and LOCO figures, not required here, are consistent with REV: ADOPT still gives D(E1,E2) = {a} and D(E2,E3) = {c}.)

---

## 4. Validity of the measures

### (a) Is LOCO with minimum guards refitted per fold appropriate?

LOCO is appropriate in form: holding out whole clusters is the right unit. For this data, though, it is weak in three ways.

1. **It tests the learning procedure, not the guard.** On tiny training sets the minimum-cardinality hitting set collapses to singletons that are not full-data guards:
   - RAISE fold R-36 (2 training events) gives {a}, {t}, {r};
   - RAISE fold R-41 gives {e};
   - START fold git gives {s}.

   Stability (1/3 for RAISE) records this, but the scores mix predictions from unrelated guards.
2. **Structural abstention.**
   - A held-out cluster whose outcome class is absent from training can never be predicted (ADOPT fold R-86; SUPERSEDE fold ADR-MP).
   - Guards whose values are near-unique (t, k in SUPERSEDE) can never predict: SUPERSEDE+R44 has stability 4/4 and coverage 0.25.
3. **The voting rule is decisive and unspecified** (D-1). One guard voting while the others abstain is enough for a prediction.

**What RAISE 4/8 means, described against baselines.** No statistics are computed here.
- The 8 predicted events are 5 P and 3 N.
- A constant "PERFORMED" predictor would get 5/8. A coin flip gets 4/8 in expectation.
- The 4 correct predictions are exactly the 4 P events of R-36, predicted by "route RULING → P". That rule was learned from the single pair L493-B (PROMOTION, N) vs P1 (RULING, P).
- The same rule gets the 3 N events of R-36 wrong.
- The one prediction made by the FR variable e (fold R-41) was wrong: P1 has e = 1 and was PERFORMED.
- e = 2+ never occurs outside R-36, so e's positive side cannot be tested out of cluster at all.

So 4/8 is at chance and below the trivial majority rule. It carries no information about e.

### (b) START coverage and abstention

Yes, the 2/11 coverage makes LOCO uninformative about s.
- The fitted guard is {s,t}. A prediction requires the exact (s,t) tuple to occur in training, and t is almost a per-cluster target identifier. So LOCO effectively looks up (s, target).
- Both predictions are the single recurring tuple (not-authorized, 7B). They are equally consistent with "s generalizes" and "7B/N is memorized".
- t is in the guard only because git's s is UNK (the prior review's D-N1). The one fold without git in training shows this: its train guard is {s}.
- The reading "s generalizes" therefore rests on recurrence (4/4 GENERALIZING) and disjoint support (2), not on LOCO. LOCO neither supports nor undermines it.

**A guard-restricted LOCO would be more informative.** That means predicting with {s} alone, whenever the {s} table is consistent among s-known training events.
- In the data, "authorized" occurs only with P and "not-authorized" only with N, each across four clusters. Such a test would therefore actually exercise s out of cluster.
- Caveats:
  - "Consistent in training" must be defined as *table* consistency. Under r3 separating semantics {s} does **not** separate folds that contain git, because of the D = {t} pairs. Under table consistency it does, because git (s = UNK) never enters the table.
  - It would be a new instrument, chosen after seeing r4's output, so it must be frozen before it is run.
  - The singleton-valued events (R72, R79) would still abstain.

### (c) The operation test

Comparability is **not** trivially broken: 145 of the 248 BASE cross-operation pairs are comparable. Decisiveness, however, is structurally near-impossible.
- A decisive pair needs the same actor, the same object kind and the same route across two operations, with opposite outcomes.
- In this corpus o co-varies with k, a and r: START is the only EXECUTION operation, L493-B is the only PROMOTION event, and each operation acts on its own kinds.
- Several comparable pairs differ *only* in k: ANNOTATE × R83, ANNOTATE × AI89, R-44 × OW1, and REV E1 × ANNOTATE.

What "0 decisive" shows is that **o is never the sole difference on a comparable pair, because o is confounded with k (and a, r)**. It does not show that o is redundant.

Three further points against the MCR label:
1. **Untested pairs.** 103 of the 248 BASE pairs are untested. In BASE and REV-TYPE, the pair *ADOPT R-81..85 by the Chief (declined)* × *ANNOTATE PB-006 row chosen* has r = RULING and a = ARB-CHIEF equal and k unknown on E1. So o is its only known separator. The instrument excludes it (correctly, on anti-ignorance grounds), but that exclusion is exactly why "redundant" cannot be claimed.
2. **The one structural candidate is excluded.** *SUPERSEDE PB-006 row declined (R-94)* × *ANNOTATE PB-006 row chosen (R-94)* has the same a, k and r and opposite outcomes: it would be decisive. It is excluded because it is CHOICE-grounded, which is correct by r3's legality rule.
3. **The label changes meaning.** In r3, MCR means "omitted by some inclusion-minimal hitting set". r4 computes no hitting set over the cross-operation problem.

The honest label is "o NOT FORCED on 145 comparable pairs; o confounded with k/a/r; UNDETERMINED".

### (d) Does R-44 change anything other than SUPERSEDE?

The per-operation analyses (ADOPT, RAISE, START and the rest) are unchanged. Three things change:
- **SUPERSEDE:**
  - the guard goes from {k}|{r} to {k};
  - k becomes FR, a goes from NEVER to MCR, and r stays MCR;
  - a is no longer flagged;
  - LOCO goes from 2/0/1 to 0/1/3 (correct/wrong/abstain), and stability from 2/3 to 4/4;
  - {r}'s two "correct" predictions are refuted by R-44 itself.
- **Operation test:** power +10 (BASE, REV-TYPE) or +11 (REV), and still 0 decisive. R-44 × OW1 is near-decisive (differs only in k).
- **Disjoint support:** k goes from 2 to 3 in every variant. The added contrast (R-44, R-77) is an identity lookup: all four SUPERSEDE k values are distinct.

---

## 5. Scope of claims: overreach flags

1. **"operation: MODEL-COND-REDUNDANT under MO"** reads as "o is redundant". It is only "no decisive pair among the comparable pairs" (§4c). This is formal reasoning, not an empirical claim.
2. **"guard_stability" 8/9 (START) and 4/4 (SUPERSEDE+R44)** could be read as robustness. Stability means the guard persists; it does not mean predictions succeed. SUPERSEDE+R44 is 4/4 with 0 correct predictions.
3. **RAISE "correct 4 / coverage 0.889"** could be read as partial generalization of e, or as the guard covering the data. The correct predictions all come from {r}, and e's only prediction was wrong.
4. **SUPERSEDE BASE "correct 2, wrong 0"** could be read as generalization. It is the {r} route rule, and R-44 refutes it (scope).
5. **START "GENERALIZING" (s)** is formal recurrence on 4 pairs, of which 2 are disjoint, all target-indexed and within one project's ruling sequence. It is not empirical confirmation of an authorization rule.
6. **disjoint_support k = 3** pools three operations. Two are within-cluster contrasts from INSUFFICIENT operations, and the third is an identity lookup. The cross-cluster-only count is 1.
7. **`recurrence_raw` for INSUFFICIENT operations** (e.g. ASSIGN-ID h LOOKUP) could be cited as a result, although the instrument declares those operations INSUFFICIENT.
8. **HALF-LOOKUP for RAISE e** could be read as "half generalizing". The recurring side (e = 1 → N via L493-B) is contradicted by e = 1 → P in R-41, and recurrence ignores contrary occurrences.
9. The file-level label ("not empirical proof") is correct. There are **no per-operation verdicts** in RESULT-r4, so readers will supply their own. The table below supplies them explicitly.

---

## 6. Summary table (BASE; variant notes in brackets)

C/W/A = correct / wrong / abstain.

| Operation | Guard | Recurrence | LOCO (C/W/A) | Stability | Disjoint support | Reviewer verdict |
|---|---|---|---|---|---|---|
| ADOPT | {a} [REV, REV-TYPE: {a,c}] | a: LOOKUP [c: LOOKUP] | 0/1/2 [REV: 0/2/1] | 1/3 [0/3] | a 2 pooled (1 in ADOPT) [c 1] | **DOES NOT GENERALIZE** |
| ANNOTATE | UNCONSTRAINED | — | INSUFFICIENT (1 cluster) | — | — | **INSUFFICIENT** |
| ASSIGN-ID | {h} | INSUFFICIENT (2 clusters) | INSUFFICIENT | — | h 1 | **INSUFFICIENT** |
| AUTHORIZE-IMPL | {a} | INSUFFICIENT (2 clusters) | INSUFFICIENT | — | a 2 pooled (1 here) | **INSUFFICIENT** |
| OPEN-WORK | {k} | INSUFFICIENT (1 cluster) | INSUFFICIENT | — | k: within-cluster, 1 | **INSUFFICIENT** |
| REGISTER | {k} | INSUFFICIENT (1 cluster) | INSUFFICIENT | — | k: within-cluster, 1 | **INSUFFICIENT** |
| RAISE | {a,e} \| {e,t} [REV: {e,t}] | e: 12 × HALF-LOOKUP | 4/4/1 (from {r}; e's one prediction wrong) | 1/3 | e 1 (within R-36) | **DOES NOT GENERALIZE** (e untestable out of cluster; LOCO at chance) |
| START | {s,t} (PARTIAL: R72 × git unseparated) | s: 4 × GENERALIZING. t: 3 LOOKUP, 2 HALF | 2/0/9 (coverage 0.182) | 8/9 | s 2, t 1 | **UNCLEAR** at operation level. s: GENERALIZES (recurrence + 2 disjoint; LOCO uninformative). t: DOES NOT GENERALIZE (by ignorance) |
| SUPERSEDE | {k} \| {r} [+R44: {k}] | none FR [+R44: k LOOKUP] | 2/0/1 via {r} [+R44: 0/1/3] | 2/3 [4/4] | — [+R44: k 3 pooled; 1 cross-cluster] | **DOES NOT GENERALIZE** (k is an identity lookup; r refuted by R-44) |
| o (MO test) | — | — | — | — | — | **UNCLEAR**: power 145 [155/142/153/137/147], 0 decisive; o confounded with k/a/r; not "redundant" |

---

## 7. Disagreement register

| ID | Disagreement | Category |
|---|---|---|
| D-1 | The docstring does not specify how several minimum guards are combined. The code uses the union of votes from the guards that can vote. RAISE (4 correct, 3 wrong from {r} alone) and SUPERSEDE BASE (2 correct from {r} alone) depend on this. A rule requiring all guards to vote would abstain on all of them | coding (spec gap) |
| D-2 | "Include the full-data minimum guard" is ambiguous with several full-data minima. The code uses *any*. No numeric effect on this data | wording |
| D-3 | LOCO is scored per operation. The prior criterion's per-variable verdict ("a variable whose separations all fail LOCO") cannot be derived, and no prediction is attributed to a guard | formal reasoning |
| D-4 | INSUFFICIENT is "< 3 clusters" rather than the prior "2 events or 1 cluster". No effect on this data | wording |
| D-5 | Disjoint support is only the pooled R4 count. The prior's cross-cluster-only and per-operation counts are dropped. k = 3 (+R44) is two within-cluster contrasts plus one lookup | independence |
| D-6 | The operation-test verdict "MODEL-COND-REDUNDANT" overreaches. 0 decisive only shows o is never the sole difference. o is confounded with k/a/r (pairs differ only in k). 103 BASE pairs are untested, including E1 × ANNOTATE, where o is the only known separator. MCR in r3 is a hitting-set notion that r4 does not compute | formal reasoning |
| D-7 | "Power" (the number of comparable pairs) is not power to detect a decisive pair. The one structural candidate (ANNOTATE × SUPERSEDE R-94) is CHOICE-excluded | evidence scope |
| D-8 | Recurrence ignores contrary recurrence. RAISE e = 1 recurs with N (L493-B) but also with P (R-41), and HALF-LOOKUP hides this | formal reasoning |
| D-9 | The prior review's application table (ADOPT a HALF, c HALF; e "single-cluster") is inconsistent with its own stated criterion. r4's LOOKUP/LOOKUP/HALF is the faithful application | formal reasoning (prior review's error) |
| D-10 | D-N1 is still open: START t is FR only because git's s is UNK. This causes the {s,t} lookup in LOCO and the 9/11 abstentions; the one fold without git in training fits {s} | formal reasoning |
| D-11 | R-44 coding: s and t are "n/a" rather than UNK (no effect); the cluster is R-44 rather than the reporting ruling R-57; agreement with SPEC-1AM-SUPERSEDE.json is unverifiable | source interpretation / evidence scope |
| D-12 | `recurrence_raw` is emitted for INSUFFICIENT operations and can be misread as a result | wording |
| D-13 | RAISE's LOCO correct predictions come from a route guard fitted on 2 training events. At 4/8 the result is at chance and below the always-P rate (5/8). It is not evidence about e | formal reasoning |
| D-14 | Stability is uninformative for identity-valued guards: SUPERSEDE+R44 is 4/4 stable with 0 correct predictions | formal reasoning |
| D-15 | Data provenance through `models_1al.r4_observations()` and SPEC-1AM is unverifiable. OBSERVATIONS.json was taken as exact | evidence scope |
| D-16 | "s generalizes" is formal recurrence on strict observations (4 pairs, 2 disjoint, target-indexed). It must not be read as empirical confirmation | evidence scope |

**Not in dispute:** every number in RESULT-r4.json, the correctness of the recurrence, table, abstention and disjoint-support code against the docstring, and the R-44 event's field values against the docstring.
