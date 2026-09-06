# PHASE 1F · Experiment Registry

**Executes:** master prompt §1F + continuation instruction — special attention to R4's four-algebra
experiment (read in detail) and R5's Step 050 / Step 100 / Step 109. *"Separate actual experiment
results from later interpretation."*

---

## EXP-01 · The four evidence algebras (R4) — the corpus's first genuine experiment

| Field | Content ⟦READ⟧ |
|---|---|
| **Sources** | design: `20260827-134514`; verdict: `20260827-135038`; setup: `closure-04a/04b` |
| **Objective** | determine whether any simple scalar aggregation operator can serve as the epistemic foundation |
| **Initial hypothesis** | one of four candidates might suffice |
| **Method** | ⟦C⟧ *"formal design problem, not an AI-confidence-scoring exercise"* under triple role (mathematician/statistician/DDD); same normalized evidence universe for all four candidates: `A₁ = max`, `A₂ = weighted mean`, `A₃ = 1 − ∏(1−sᵢ)` (saturating), `A₄ = Bayesian updating`. Layer separation imposed first: evidence representation ≠ aggregation ≠ (third layer). **Adversarial property test** over 7 named properties; results exported (⟦C⟧ CSV report referenced) |
| **Observation** (actual result) | property matrix ⟦READ⟧: MAX — passes duplicates/dependency/order/staleness/bounded, **fails independent corroboration**; Weighted Mean — **fails duplicates, corroboration, dependency**; Saturating — passes all 7; Bayesian-like — passes all 7; irrelevance handled by the normalization layer for MAX/mean (source's own footnote) |
| **Verdict** (source's own) | ⟦C⟧ *"**No simple scalar operator is sufficient as the KnowledgeOS epistemic foundation.**"* Strongest single result: ⟦C⟧ *"**dependency must come first**"* — 3 apparent evidences may be 1 observation + 2 derived representations |
| **Limitations** (recorded here, not in source) | single normalized universe; property list chosen by the same author; no external replication; "Bayesian-like" not fully specified at reading depth |
| **Architectural consequence** | AD-03; feeds `025c`/`025n` aggregation algebras |
| **Validation status** | **COMPUTATIONALLY TESTED — the only R4 claim class with a recorded test artifact** |

⟦INT⟧ Per the instruction's caution: **what R4 actually established** is the *negative* result (no
scalar suffices) plus two *property requirements* (duplicate invariance, corroboration sensitivity) and
one *ordering requirement* (dependency resolution precedes aggregation). It did **not** establish a
validated aggregation architecture — saturating and Bayesian both passing 7 properties is candidate
survival, not selection.

---

**ADJUDICATION NOTE (GN-27, 2026-08-28):** the repository CSV export
(`tests/experiments/knowledgeos_evidence_calculus_property_tests.csv`) disagrees with the prose
matrix above in three cells (WM/duplicate, Bayes/irrelevance, MAX/irrelevance). Adjudicated: the
prose verdict document prevails; this registry's transcription is faithful; the CSV is graded
UNRELIABLE-WITHOUT-ITS-GENERATOR. Record: `phase-3c-dispositions-resolution.md`.

## EXP-02 · Step 050 — adversarial consistency audit of the M₄₉ kernel

| Field | Content ⟦READ⟧ |
|---|---|
| **Objective** | ⟦C⟧ *"stop extending the theory and try to **break it** … Find a counterexample"* |
| **Input** | the Step-49 normalized kernel: 8 primitives `{Entity, State, Event, Observation, Proposition, Relation, Policy, Action}` |
| **Method** | **50 attack classes** executed sequentially (§50.1–50.56) |
| **Observations** (sampled ⟦READ⟧) | *"the model survives because observation and epistemic qualification are separate concepts"* · *"the typed relation model survives"* · one liveness attack **succeeds** (*"Liveness fails"* §~1014) → *"the distinction between safety and liveness survives"* — i.e. the failure is absorbed as a distinction, and at least one further local *"fails"* (§~1223) |
| **Result** | ⟦C⟧ `Counterexample(M₄₉) = ∅` **within the tested attack set** |
| **Verdict** (source's own, precise) | ⟦C⟧ *"M₄₉ survives the current adversarial consistency audit"* + ⟦C⟧ *"This is **not** a proof … No counterexample was found in the tested scenarios"* ≠ *"no counterexample exists"* |
| **Limitations** | attacks authored by the model's own author (no independent red team); the two local "fails" become refinements rather than counterexamples — legitimate but worth Phase-2 scrutiny of whether any attack *could* have counted as fatal |
| **Validation status** | **VALIDATED-WITHIN-TESTED-SCOPE** |

---

## EXP-03 · Step 100 — architecture closure test

| Field | Content |
|---|---|
| **Objective** ⟦READ⟧ | milestone test after Steps 1–99: do the individually-established dimensions (Knowledge, Evidence, Inference, Decision, Authority, Execution, …) close as one architecture? |
| **Method** | per-dimension closure checks, each with a `### Result` block (six+ observed ⟦READ headings⟧) |
| **Result** | NOT ESTABLISHED at this reading depth — the per-dimension verdicts were not individually read |
| **Status** | **RECORDED, CONTENT-PENDING** — Phase 2 must read the Result blocks |

---

## EXP-04 · Step 109 → 124 — evidence-based repository reconstruction (the mode change)

| Field | Content ⟦READ (109 head) / TITLE (110–124)⟧ |
|---|---|
| **Objective** | ⟦C⟧ *"The previous steps established the **target architecture**. Step 109 begins the reconstruction of the **actual KnowledgeOS/EKS system**."* |
| **Method** | ⟦C⟧ *"No architectural assertion without evidence"*, with a four-way epistemic register: `Observed / Inferred / Specified / Claimed` |
| **Executed sequence** ⟦TITLE⟧ | 110 system boundary → 111 component inventory → 112 semantic ownership → 113 semantic core → **114 semantic core evidence test** → 115/116 graph reconstruction/extraction → **117 traceability experiment** → 118 governance-to-engineering closure test → 119 control-loop evidence test → 120 constitutional invariants → **121 constitution-to-implementation conformance test** → 122 evidence execution protocol → **123 self-verification** → 124 self-verification vs self-governance |
| **Results** | NOT ESTABLISHED — titles prove the tests were *specified*; whether they were *executed against the real repository* and what they found requires reading. ⚠ Do **not** cite Steps 109–124 as completed validation on current evidence |
| **Status** | **THE DECISIVE OPEN ITEM** — if executed, this is the corpus's only repository-validation evidence; if merely specified, R5's validation claim rests on Step 50 alone |

---

## Comparative answer to the instruction's question — does R5 validate more strongly than R4?

⟦INT⟧ **Different kinds, and R4's is currently the cleaner evidence.**
- **R4** = *property-based falsification of candidate mathematics* — narrow scope, explicit properties,
  recorded artifact (CSV), decisive negative result. Highest evidential cleanliness in the corpus.
- **R5/EXP-02** = *adversarial self-audit of the whole kernel* — far broader scope, but attacks and
  model share an author, and the verdict is survival-within-scope.
- **R5/EXP-04** = potentially the strongest kind (external conformance against a real repository) but
  its execution status is **NOT ESTABLISHED**.

Ranking by current evidential standing: **EXP-01 > EXP-02 > EXP-04 (pending) > EXP-03 (pending).**
