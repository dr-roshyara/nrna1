# Convergence report 2: Gate 1 proper (prepared) · non-circular ablation · second witnesses · cross-family test · choice layer

| | |
|---|---|
| Status | research record. **The candidate theory is NOT canonical.** Authority: none |
| Log | F-LOG-0140 |
| Governing principle | a researcher is responsible for observing the current corpus as brainstorming material and deriving a robust theory from it; the corpus is not the specification |

## 1. Gate 1 proper: prepared; it needs a human to commission it
- `~/F-GATE1-PROPER-BUNDLE-01.tar` (sha256 `08cf04b2784d00047b9941a7b43a9f51186ad3f87f6b59c3ce4e7f4d1ff0278a`) holds both frozen bundles, **unchanged**.
- Every file was verified against the manifests committed at `ac1ad57b8` (GATE1) and in `RESERVE/BUNDLE-MANIFEST.sha256`.
- It contains no Claude results, expected answers or theory.
- **No non-Claude coder can be reached from this environment** (Codex is excluded). A human or a DeepSeek run must code it. The returned `CODING.json` files can then be scored with the existing `gate1_agreement.py` / `reserve_agreement.py`.
- Gate 1 measures **reproducibility of the operationalization**, not the truth of the theory.

## 2. The earlier ablation, reclassified
`ablation.py` (F-LOG-0139) is **a model-consistency / representational-necessity analysis**. Its observation table hard-codes which component each observation needs. **It is not evidence of necessity.** It is kept as a diagnostic.

## 3. Non-circular ablation (`ablation2.py` committed before its run, `a41c9887f`; `ABLATION2/RESULT.json`)
The design contains no statement that an observation requires a component. Instead:
- there are 27 coded events (26 legality events), with raw field values;
- legality is deterministic, choice may be nondeterministic;
- a model without field X represents the data iff every rule-grounded outcome is still a function of the retained fields.

**Results (frozen):**

| Field removed | Representable? | Counterexample from the data |
|---|---|---|
| **r** route | **yes** | none |
| **x** exception | **yes** | none |
| o operation | no | R-89 vs R-95 (Chief implementation authorization PREPARED vs Chief planning authorization not) |
| a authority | no | Chief vs DA adoption; R-70 vs R-89 |
| k kind | no | R-90 vs constitutional decision; recording note vs ruling (R-60) |
| s state | no | START WP-4B before/after the proviso; WP-8 |
| t target/scope | no | START 7B after AUTHORIZE(7A) (R-47); 4C-2 (R-89) |
| e evidence | no | promotion matrix #2/#3/#9/#10 vs #5/#14/#17 |
| h history summary | no | the retired number R-90 vs a never-used number |
| c conformance | no | R-86 vs R-91 |

- **The minimal representable field set is exactly {o, a, k, s, t, e, h, c}.** Route and exception are **not required by any observation**.
- **Complexity (disclosed revision r2, `ablation2_r2.py`).** The frozen special-case measure, connected components of the conflict graph, **was degenerate**: it made {e} alone look optimal. r2 uses the minimum vertex cover (= the maximum matching, since the graph is bipartite).
  - Special cases forced by removing each field: e 3 · a, k, s, t 2 each · o, h, c 1 each · r, x 0.
  - Parsimony optimum:
    - λ = 0.5: all 8 fields (score 4.0);
    - **λ = 1: {e, k, s, t} + 3 row-level exceptions** (score 7);
    - λ = 2: {e} (score 10).
- **Reading:**
  - {e, k, s, t} is the part of the model that survives parsimony pressure;
  - a is kept up to λ = 1;
  - **o, h and c each rest on one witness pair and do not pay for themselves at λ ≥ 1.**
- **Frames F** are not a legality field. A model without frames can still *represent* every observation, because frames only restrict. What frames buy is **entailment** of the recurring explicit non-effects ("closure is an ACCEPTANCE OUTCOME, NOT AN AUTHORIZATION DECISION"; acceptance closes work in 7+ rows). Their value is explanatory, not representational.

## 4. The choice layer (formal test)
- R-94 (two legal options, one chosen on a principle) **is representable without a choice layer**: a nondeterministic legal set suffices.
- The choice layer is needed only to **explain / predict** which option is selected.
- So: **Eligible(S, e) and Select(candidates, policy) are distinct, and Select is not representationally necessary.** It is an explanatory hypothesis with 1 witness (R-94).

## 5. Second witnesses

| Variable | Before | Now | Status |
|---|---|---|---|
| authority A | 1 pair (Chief vs DA adoption) | **+ R-70 (ARB) vs R-89 (Chief, PREPARED)** | 2 pairs → **supported**. X6 adds support that A is orthogonal to K: "Taxonomy INFORMS Authority; governance rules mediate" |
| kind K | 1 pair (register admission) | **+ R-60: "A recording note cannot open a work package"** | 2 pairs → **supported** |
| evidence E | 1 pair (R-36) | **+ the R-36 promotion matrix: 12 contrasts** (4 promoted with ≥ 2 independent slices vs 3 not promoted with 1; **no falsifier**; #18 AMBIGUOUS, a context-diversity bar) | **one decision cluster** (the matrix *is* R-36's basis) → multiple contrasts but effectively **1 independent decision**. Refinement: evidence is counted in **independent slices / contexts**, and the threshold can be set per candidate (X5: a parked "≥ 3" criterion; the matrix: "2 slices is the floor") |
| role separation RS | 1 refusal (R-86 vs R-91) | no second refusal found; principle statements: R-57 (plan amendment "is a governance act", engineering did not edit), R-71 ("Engineering does not self-certify it"), X2 ("detection may be automatic, correction may not"), X5 ("evidence + reasoning is not authority") | **RS-HYPOTHESIS**, outside the core |

## 6. Cross-family test (8 seeded session-log sections, double-coded; spec `f65517a90`; main sealed `d63195d5f`)
- **Invariants: no guard or frame violation by the blind coder.** The only frame case is the recurring "confirm" overload (X3).
- **The operationalization does NOT transfer:**
  - V1 κ −0.19, V3 κ 0.00, V4 κ 0.16, V6 κ 0.29 (only V7 κ 1.0);
  - operation Jaccard 0.74;
  - coverage 0.64 (main) vs **0.48** (blind: 44 segmented acts against 25).
- **Authority naming fails in the genre:** by the manual, 2/8 sections report an adoption without naming the adopter (X3, X7).
- **Genre differences, classified rather than absorbed:**
  1. session logs **report** acts, often in the passive voice;
  2. they record the **agent's own self-restraint** ("NOT self-promoted into a standards document");
  3. they contain operations on a **new object kind: claims / generalizations** (X6 "over-generalizations corrected"; X8 a claim "WITHDRAWN … Superseded, retained", with the old text struck through, which **the record semantics predict**).
- **Coder bias persists:** the main coder again coded V1 SUPPORTED where record-only gives UNTESTABLE.
- **Diagnosis:** the *coding ontology* is register-specific. The theory is **not refuted** by this genre, and **not validated** either.

## 7. The candidate model after this phase
- **Representational core:** Legal = G_o(s, t, e, a, k, h; c?), with operation o. S′ = δ(S, e), with Δ⁺ ⊆ Frame⁺(o) as an explanatory constraint.
- **Parsimony core (λ = 1): {e, k, s, t}.**
  - o, h and c are single-witness, and so **weakly supported**;
  - RS and choice are **hypotheses**.
- **Not required:** route and exception.
- **Path dependence:** finite summaries sufficed in every case examined.

## 8. The three kinds of claim, kept apart
- **Representational necessity** (non-circular FD ablation): o, a, k, s, t, e, h, c.
- **Empirical witnesses** (controlled pairs):
  - a: 2 · k: 2 · s: 2 · t: 2;
  - e: 1 decision cluster with 12 contrasts;
  - h: 1 · o: 1 · c: 1.
- **Theoretical necessity:** **not established** for any component.

## 9. Exact remaining discriminating experiments
1. **Gate 1 proper:** a non-Claude coding of the tarball, giving an independent κ.
2. **Coding manual r3, genre-aware:**
   - "reported act" vs "performed act";
   - a report-verb stoplist for segmentation;
   - V6 regime/genre rules;
   - claims as an object kind;
   - the "confirm" split (no-change vs decisive).

   Freeze r3, then re-run the cross-family test on a **new** seeded sample.
3. **A second independent decision for e** (outside the R-36 cluster), and a second witness each for **h, o and c**. If none is found, **drop them from the core** at λ ≥ 1.
4. **Route vs operation:** no corpus observation can decide it. It needs a designed future decision record, with every field recorded.
5. **Only after 1–3:** an event-level dataset with families and regimes as clusters, then hierarchical models. ML only if a retrieval-recall gap is measured.
