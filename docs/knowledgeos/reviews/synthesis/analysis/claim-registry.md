# PHASE 1C · Claim Registry

**Executes:** master prompt §1C + continuation instruction (`20260828_1426_prompt.md`)
**Corpus:** `phase_measure_theory/` (READ-ONLY) · regimes R1–R5 per `reasoning-timeline.md`
**Rule applied:** *a claim that exists only as a conceptual proposal must not be reported as validated
architecture.* Status ladder used: `OBSERVED → DERIVED → HYPOTHESIS → FORMALIZED → COMPUTATIONALLY
TESTED → REPOSITORY-VALIDATED → ACCEPTED / SUPERSEDED / OPEN`.
**Depth:** claims marked ⟦READ⟧ come from direct reads of the source; ⟦TITLE⟧ from content-derived
titles + section reads only.

---

| ID | Claim | Regime | First appearance | First formalization | First computational test | First repository validation | Status |
|---|---|---|---|---|---|---|---|
| **C-001** | Knowledge is probably **not the Kernel object** | R1 | `20260825-214833` ⟦READ⟧ | — | — | — | **HYPOTHESIS · OPEN** |
| **C-002** | *Extraction retrieves material; **determination** establishes what it warrants asserting* | R1 | `20260825-234405` ⟦READ⟧ | — | — | — | **DERIVED · OPEN** |
| **C-003** | **Determination is the missing mathematical object** of the framework | R1 | `20260825-235855` ⟦READ⟧ | partially, via R5 acceptance/commitment (`step-008`) ⟦TITLE⟧ | NOT ESTABLISHED | NOT ESTABLISHED | **HYPOTHESIS** |
| **C-004** | Correctness ≠ completeness | R1 | `20260825-220537` ⟦TITLE⟧ | — | — | — | DERIVED |
| **C-005** | Knowledge is temporally situated and potentially evolving | R1 | `20260825-225949` ⟦TITLE⟧ | R5 `step-016` temporal knowledge ⟦TITLE⟧ | Step 50 audit family ⟦READ⟧ | NOT ESTABLISHED | DERIVED → FORMALIZED |
| **C-006** | A **dimension** is an aspect along which a state can be characterized; a knowledge state depends on its known dimensions | R2 | `20260826-103508` / `103946` ⟦TITLE⟧ | `mathematical-redefinition-dimension-discovery` ⟦TITLE⟧ | NOT ESTABLISHED | NOT ESTABLISHED | HYPOTHESIS → FORMALIZED |
| **C-007** | **Zero Lens**: *what is absent, undefined, unrepresented, or assumed away?* — protecting `UNKNOWN ≠ ABSENT`, `NOT_ASSESSED ≠ LOW_CONFIDENCE`, `NO_EVIDENCE ≠ INVALID_EVIDENCE` | R2 | `20260826-105126` ⟦READ⟧ — notes the lens was *"used before"* (pre-corpus) | ⚠ `step-025d` **under a changed meaning** (see CON-01) | Step 50 audit (indirect) ⟦READ⟧ | NOT ESTABLISHED | **FORMALIZED — but see CON-01** |
| **C-008** | **Lord Lens**: observe knowledge space as an ideal unbounded whole Ω; explicitly *"does NOT assert Lord = Ω"* — an analogy | R2 | `20260826-113213` ⟦READ⟧ | ⚠ `step-025g` **under a changed meaning** (see CON-02) | via reference machine (Step 56) ⟦TITLE⟧ | NOT ESTABLISHED | **FORMALIZED — but see CON-02** |
| **C-009** | Knowledge Space → Knowledge State → Knowledge Element, with Ω unbounded and a participant's knowledge a projection at time t | R2 | `20260826-105126` (citing earlier research) ⟦READ⟧ | R2 mathematical-architecture docs ⟦TITLE⟧ | NOT ESTABLISHED | NOT ESTABLISHED | HYPOTHESIS |
| **C-010** | The **Knowledge Atom** is the unit of knowledge | R2 | `20260826-172732` ⟦TITLE⟧ | same doc | NOT ESTABLISHED | NOT ESTABLISHED | HYPOTHESIS |
| **C-011** | ⚠ *"The Complete Mathematical Model"* — completeness claimed for the R2 model | R2 | `20260826-174215` ⟦TITLE⟧ | self-declared | **contradicted 3 days later by `step-050`'s own caution** ⟦READ⟧ | — | **SUPERSEDED** (see CON-04) |
| **C-012** | **Ātma can be the KnowledgeOS Kernel** | R3 | `20260827-090350` ⟦READ (title+context)⟧ — 4-doc escalation 08:51→09:05 | ⚠ survives as *Knowledge Atma* in `step-025s` ⟦TITLE⟧ | NOT ESTABLISHED | NOT ESTABLISHED | **HYPOTHESIS · OPEN** |
| **C-013** | Krishna/Ω is a **lens-derived abstraction**; the theological entity must not be equated with the mathematical set | R3 | `20260826-151244` ⟦READ⟧ | — (a guard, not a model) | — | — | **ACCEPTED as discipline** |
| **C-014** | **Sārathi does not own the frame** — it may challenge/reframe, but the Knower owns problem, purpose, decision | R3 | `20260826-151244` ⟦READ⟧ | `step-025h` (authorization role) ⟦READ⟧ | NOT ESTABLISHED | NOT ESTABLISHED | DERIVED → FORMALIZED |
| **C-015** | KnowledgeOS **does not have one input** — the input universe is plural (three distinct information pathways) | R4 | `20260827-113303` ⟦TITLE⟧ · `125515` ⟦TITLE⟧ | closure-01…04 ⟦TITLE⟧ | R4 experiment ⟦READ⟧ (partially) | NOT ESTABLISHED | DERIVED |
| **C-016** | **No simple scalar operator is sufficient as the KnowledgeOS epistemic foundation** | R4 | `20260827-135038` ⟦READ⟧ — the experimental verdict box | the four-algebra comparison | **YES — property test, 7 properties × 4 operators, CSV report** ⟦READ⟧ | NOT ESTABLISHED | **COMPUTATIONALLY TESTED** |
| **C-017** | **Dependency must come first**: N pieces of evidence may be 1 underlying observation + derived representations | R4 | `20260827-135038` ⟦READ⟧ | dependency calculus `step-001`ff ⟦TITLE⟧ | same experiment | NOT ESTABLISHED | **COMPUTATIONALLY TESTED** |
| **C-018** | Duplicate evidence must not increase confidence (duplicate invariant); independent corroboration must | R4 | property table ⟦READ⟧ | evidence algebras | **YES** — MAX fails corroboration; Weighted Mean fails duplicates; Saturating + Bayesian pass both | NOT ESTABLISHED | **COMPUTATIONALLY TESTED** |
| **C-019** | **Zero(K,G,EC) must be computable from the KnowledgeState** — else Zero is *"an AI metaphor"* not a primitive; Zero ≠ simple subtraction | R5 | `step-025d` ⟦READ⟧ | same doc (the algebra) | Step 50 family ⟦READ, indirect⟧ | NOT ESTABLISHED | FORMALIZED |
| **C-020** | **Lord = selects the next useful action** (given K_t, Z_t, G) — *"without allowing Lord to become an uncontrolled LLM planner"* | R5 | `step-025g` ⟦READ⟧ | same doc | reference machine ⟦TITLE⟧ | NOT ESTABLISHED | FORMALIZED · **meaning shifted, see CON-02** |
| **C-021** | **Action ≠ decision**: Lord proposes actions; **Sārathi determines the decision** | R5 | `step-025h` ⟦READ⟧ | same doc | NOT ESTABLISHED | NOT ESTABLISHED | FORMALIZED |
| **C-022** | The normalized kernel has **8 primitives**: `{Entity, State, Event, Observation, Proposition, Relation, Policy, Action}` | R5 | `step-049` → restated as reference in `step-050` ⟦READ⟧ | step-049 | **Step 50: 50 attack classes, no counterexample** ⟦READ⟧ | Steps 101–124 conformance ⟦TITLE⟧ | **COMPUTATIONALLY TESTED** |
| **C-023** | **M₄₉ survives the adversarial audit — but** ⟦C⟧ *"This is NOT a proof… No counterexample was found in the tested scenarios ≠ no counterexample exists"* | R5 | `step-050` ⟦READ⟧ | — | the audit itself | — | **VALIDATED-WITHIN-TESTED-SCOPE** (the corpus's own careful wording) |
| **C-024** | *"No architectural assertion without evidence"* — with the four-way register `Observed / Inferred / Specified / Claimed` | R5 | `step-109` ⟦READ⟧ | — | — | **this claim IS the repository-validation instrument** | **ACCEPTED as method** |
| **C-025** | Observation and epistemic qualification are separate concepts (the model survives attack because of it) | R5 | `step-050` §attack replies ⟦READ⟧ | step-049 | Step 50 | NOT ESTABLISHED | COMPUTATIONALLY TESTED |
| **C-026** | KnowledgeOS is an **epistemic operating system** | R3 | `20260827-082029` ⟦TITLE⟧ | R5 operating-model steps 125/156 ⟦TITLE⟧ | — | — | HYPOTHESIS → FORMALIZED |

---

## Register notes

1. **The only claims that reach COMPUTATIONALLY TESTED are C-016…C-018, C-022/C-023/C-025** — i.e.
   the R4 evidence-algebra experiment and the R5 Step-49/50 kernel audit. Everything else is at
   HYPOTHESIS/DERIVED/FORMALIZED.
2. **No claim reaches full REPOSITORY-VALIDATED status in this registry.** Steps 101–124 perform
   conformance testing ⟦TITLE⟧, but which specific claims passed is NOT ESTABLISHED at this reading
   depth — recorded as the top item for Phase 2.
3. **The two lens-formalization claims (C-007/C-019, C-008/C-020) carry meaning shifts** — the algebra
   does not formalize the lens as defined. Detailed in the contradiction registry (CON-01, CON-02).
4. C-011's completeness claim is the registry's only **SUPERSEDED** entry, and it was superseded by the
   corpus's own later caution — an example of healthy self-correction.
