# T-A OPERATION TYPING METHODOLOGY REVIEW

| | |
|---|---|
| **Kind** | methodology review. ⚠ authority: generated. **No decision is recorded here** |
| **Commission** | human, 2026-09-26: *"S-U + K-A + SP-S for all six"*, together with a senior assessment and prompt (*"do NOT immediately freeze … K-A"*; produce this review), and *"analyse the discussion and follow the prompt"* |
| **Reading of the commission** | the human's own words state S-U + K-A + SP-S **and** direct following the prompt, which defers K-A. Recorded therefore: **S-U and SP-S as the human's stated choices; K-A stated but not frozen.** The kind basis is re-examined here. Nothing is frozen; r3 is **not** written |
| **Reads** | ⛔ no corpus content. Only the lane's own artifacts (pre-registration r2, attack document, `results.json`) |
| **Candidate** | H-F2-1-R: unchanged |

---

## A. K-A (actor-based) vs K-S (source-semantic)

| Criterion | K-A: kind from the actor or authority | K-S: kind from source-described operation semantics |
|---|---|---|
| **actor dependence** | total. Actor ≠ operation type: a governance body may observe, correct, process or rule | the actor is **context only**, never decisive |
| **effect leakage** | low: the actor is fixed before the effect is read | **present unless constrained.** Action semantics can smuggle in the effect, e.g. "declares the observation Replicated" typed EVID *because* its object is evidential status. Needs rule **O-1** (below) |
| **falsifiability** | **biased toward refutation.** A governing body's evidential act (a replication it performs) becomes GOV, so an A2e "counterexample" is an artefact of the typing | unbiased **if O-1 holds**. Without O-1, biased toward survival (the classic immunization) |
| **ambiguity handling** | none: every actor yields a kind, so it forces classification | explicit **UNKNOWN** outcome |
| **reproducibility** | high: actor identification is shallow | medium; raised to high by a **pre-registered action lexicon** (priority 3) and a mandatory `typing_basis` |
| **suitability for a historical corpus** | poor: historical documents mix roles in one authority | good: the corpus describes *what was done* in its own terms |

**Verdict [D]:**
- The senior objection is correct. **K-A fails the intended semantics of the axioms**: A2e is about what a *ruling* can do, not about what a *governance body* can do.
- K-S is preferable, **but only together with O-1**. Without it, K-S re-opens the circularity the safety pass found.

**O-1 (object restriction):** the **object** of an action may never determine its kind when that object is a constrained component: evidential position, grant, bar or standing.
- *"The board rules that X is Replicated"*: the action is a ruling, so it is **GOV**, whatever its object. That is exactly the A2e test case.
- Typing it EVID because "Replicated" is an evidential status would type **by the effect**.

---

## B. Formal consequences of UNKNOWN

**B-1. UNKNOWN is epistemic, not ontological [D].**
- It is a fact about the **reader's** knowledge, not a sixth kind of step.
- The real operation has some kind; the reader cannot establish which without using the effect.
- **No change to the formal system, and no three-valued logic.**

**B-2. Which axioms need a kind [D].**
- **Kind-specific:** A2e, A3g, A3s, A3m, A4, A5e, A5g, A5s.
- **Kind-free:** A0 (a state constraint), **A1** and **A6** (they constrain *every* step).
- **Consequence:** an UNKNOWN-typed operation can still be a **counterexample to A6 or A1**. An untyped operation that changes the bar violates A6 regardless of its kind. **F-A6, the primary test, is immune to typing failure.**

**B-3. Which propositions depend on complete typing** [C, derived from the existing ablation, no new computation]:
- In the model, a step that no kind-specific axiom constrains, and that counts as neither GOV nor evidential, is **exactly** a COMP step with A4 removed. There, only A0, A1 and A6 bind.
- The existing −A4 row of the single-removal matrix is therefore the answer:

| Proposition | With UNKNOWN steps in a trajectory | Why |
|---|---|---|
| D1 | unaffected | quantifies over GOV-only trajectories |
| D2 | unaffected | quantifies over {EVID, EVIDREF, WORK}-only trajectories |
| **D3, D3+** | **conditional on complete typing** | the −A4 row: an unconstrained step can change e and g |
| D5 | unaffected | quantifies over a single EVID step |
| D6 | unaffected | A1 is kind-free |

**B-4. The assumption that must be stated [D].**
- **H-1′ (typing totality):** D3 and D3+ are asserted for trajectories **every step of which is typed**.
- This makes explicit what formal H-1 (*"each mechanism step has exactly one kind"*) already assumed.
- **No proof changes.**

**B-5. The counterexample definition.** The senior's four-part definition was checked:

| Part | Status |
|---|---|
| (1) an independently established kind | sound |
| (2) the axiom's preconditions hold | sound |
| (3) the prohibited transition occurs | sound. For effects this is `countermodel_effect_stated` |
| (4) *"cannot be explained by post-hoc retyping or reader splitting"* | **unsound as phrased.** Some retyping can *always* be proposed, so (4) is an escape clause that restores the immunization |

**Replace (4) with a procedural condition (4′):**
- the kind was assigned by the pre-registered K-S procedure;
- it was recorded **before** the effect was assessed;
- its basis does not use the effect (O-1);
- any split is source-explicit (SP-S).

A record meeting (1)–(3) + (4′) **is** a counterexample. No later explanation removes it.

**B-6. UNKNOWN within the existing outcome classes (no new outcome).** A passage whose kind is UNKNOWN is resolved **by enumeration**. For each kind the procedure leaves admissible, evaluate the passage:
- **same verdict for every admissible kind:** typing is irrelevant, so classify normally;
- **verdicts differ:** it is **AMBIGUOUS**, which is exactly §3.1 (*"≥ 2 readings with different verdicts"*). `competing_classification` takes the most adverse verdict. By §5.0 rule 2, a live counterexample reading gives **INCONCLUSIVE** plus a reconstruction obligation;
- UNKNOWN is **never** support, **never** a counterexample, and **never** silently converted.

**B-7. S-U, sharpened.** The proposed wording is sound **provided coverage is reported**:
- *"universal over all source-described operations that can be independently typed"* makes the claim conditional on typeability, which is honest;
- but a test in which most material operations are UNKNOWN is weak and must say so;
- so report **N_typed** and **N_unknown** per axiom. These are counts only; no rates are interpreted.

---

## C. Recommended final HD-S formulation

> **S-U:**
> - The locality axioms A2e, A3g, A4, A5e and A5g are universal over all source-described operations typed by the pre-registered K-S procedure.
> - A6, A1 and A0 are universal over all source-described operations, typed or not.
>
> **K-S:** the kind is assigned **before** the effect is assessed, by the first applicable basis:
> 1. an explicit source-declared operation type;
> 2. explicit source-described action semantics;
> 3. the pre-registered action lexicon (D-3);
> 4. the actor, as context only, never decisive;
> 5. otherwise **UNKNOWN**.
>
> Constraints: **O-1** (the object never decides the kind when it is e / g / u / s); the effect is never a basis.
>
> **SP-S:** split only where the source describes distinct steps, and quote the basis. One indivisible act that changes two constrained components stays **one** operation, and is typed COMP if its action semantics span two kinds (a direct F-A4 test).
>
> **Counterexample:** (1) + (2) + (3) + (4′) (§B-5).

This matches the human's stated **S-U** and **SP-S**. It differs from the stated **K-A**.

---

## D. Pre-registration changes required (for r3, after the human confirms the kind basis; not applied now)

| # | Change | Kind of change |
|---|---|---|
| D-1 | §3 flag replaced by the §C text (scope / K-S / O-1 / SP-S / counterexample 4′) | resolves PENDING HD-S |
| D-2 | §4 fields: `source_actor`, `source_action`, `source_object`, `source_declared_type`, `assigned_kind` (GOV \| EVID \| EVIDREF \| WORK \| COMP \| UNKNOWN), `typing_basis` (DECLARED \| ACTION_SEMANTICS \| LEXICON \| UNKNOWN), `ambiguity_reason`, `admissible_kinds` (if UNKNOWN), `split_basis` (NONE \| quoted source text) | additive |
| D-3 | **§4a, the action lexicon**, written now from the axiom semantics only:<ul><li>GOV: rule, decide, approve, ratify, adopt, authorize, grant, promote, amend (a rule)</li><li>EVID: observe, measure, test, replicate, verify-by-observation</li><li>EVIDREF: refute, falsify, fail to replicate, contradict-by-observation</li><li>WORK: execute, process, implement, schedule, elapse, expire</li><li>**ambiguous, so UNKNOWN unless basis 1–2 resolves it:** confirm, validate, accept, record, certify, review</li></ul> | additive; a pre-registered rule |
| D-4 | §5: UNKNOWN resolved by enumeration (§B-6); report `N_typed` and `N_unknown` per axiom | additive; no outcome definition changed |
| D-5 | §5.1 note: D3 and D3+ are conditional on typing totality (H-1′) | declarative |
| D-6 | §10 checklist step 6: record `assigned_kind` + `typing_basis` **before** writing `countermodel_effect_stated` | procedural |
| D-7 | `aggregate.py`: validate the D-2 fields; reject `typing_basis = EFFECT`; reject a record with `assigned_kind = UNKNOWN` and a non-AMBIGUOUS classification when the §B-6 verdicts differ; count `N_typed` and `N_unknown` | tooling, with synthetic tests |

**Unchanged:** every §3 question, every §3.2 prediction and every §3.1 outcome definition.

---

## E. Does the formal attack need modification?

**Declaratively only: YES (minimal).**
- The attack document gets a **note** stating H-1′ and B-2 (kind-free axioms).
- **No proof, no axiom, no minimal set and no instrument changes.** B-3 is read directly from the existing −A4 ablation.
- Applied with r3, as an appended note, like the state-count correction.

---

## F. Exact remaining human decision

**The kind basis:** confirm **K-A** (as stated), or replace it with **K-S + O-1** (recommended here and by the senior review).
- S-U and SP-S already stand as the human's stated choices.
- Also confirm the D-3 lexicon, or amend it, **before** any reading.

## G. Exact next execution step after that decision

1. Claude writes **r3** (D-1…D-6), the attack-document note (E) and the D-7 tooling, runs the synthetic tests, and records the hash.
2. **HD-1:** the human freezes r3 against that hash.
3. HD-2 / HD-3 / HD-4 through the Research Release Check.
4. T-A per §10, with no ML.

---

- **T-A readiness:** **NOT READY.** The kind basis is not yet confirmed.
- **HD-S status:** **PARTIAL.** S-U and SP-S stated by the human; the kind basis is open (K-A stated; K-S + O-1 recommended).
- **Pre-registration revision required:** **YES** (r3: D-1…D-6).
- **Formal revision required:** **YES, declarative only.** H-1′ is stated; no proof changes.
- **Corpus reading permitted:** **NO.**
- **Next human decision:** K-A vs K-S + O-1, plus the D-3 lexicon.
- **Next Claude execution step:** write r3 plus the attack-document note plus the D-7 tooling after that decision, then stop for HD-1.

*No theory validation or canonicalization is claimed. No corpus read. No release requested. H-F2-1-R unchanged.*
