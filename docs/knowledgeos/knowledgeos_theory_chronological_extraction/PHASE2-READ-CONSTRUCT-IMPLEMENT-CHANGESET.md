# READ → CONSTRUCT → IMPLEMENT — Changeset

| | |
|---|---|
| **Source** | `PHASE2-READ-CONSTRUCT-IMPLEMENT-REVIEW.md` |
| **Target** | `prompts/knowledge_os_step2_theory_construction_protocol.md` §§3–3B |
| **Status** | ⚠️ **PROPOSED — not applied** |
| **Relation to prior changeset** | **Additive.** `PHASE2-ARCHITECTURE-CHANGESET.md`'s 13 edits stand; these 14 sit alongside. ⭐ `R-06` amends `E-11`; nothing else overlaps |
| **Classification** | `EVIDENCE_FORCED` · `METHODOLOGICALLY_FORCED` · `LABORATORY_REQUIRED` · `REVERSIBLE_DESIGN` · `OPEN` · `PREMATURE` |

---

## R-01 · The laboratory principle

**Section** §3 (new, leading) · **Class** `LABORATORY_REQUIRED`

> ## **Phase 2 is not where we implement KnowledgeOS.**
>
> **Phase 2 is where we READ the evidence, CONSTRUCT candidate theory, IMPLEMENT the minimum laboratory required to represent and test that construction, and use the results to discover what the theory and the methodology actually require.**
>
> ⭐ **The laboratory is disposable. The evidence and research record are not.**
>
> **The theory must emerge from the research; the software must never silently determine the theory.**

**Basis** Follows from `E-12`; stated at the top of the process rather than inside the architecture section, because it governs READ and CONSTRUCT as much as IMPLEMENT.
**Test** `EMERGENT_DISCOVERY_TEST`. **Dependencies** none.

---

## R-02 · Three activities, mapped to phases

**Section** §3 (new) · **Class** `METHODOLOGICALLY_FORCED`

| Activity | Phases | Output | ⛔ Must never |
|---|---|---|---|
| **READ** | 0 · A · B · C | `HISTORICAL-STORY.md` | become construction · resolve a contradiction it finds · convert *not searched* into *not present* |
| **CONSTRUCT** | D · B2 · E · F · G | `THEORY-SEED.md` | assert truth · adopt what the corpus declined · exceed its evidence |
| **IMPLEMENT** | ⭐ **new phase L** | laboratory + `ENGINE-COMPARISON` | implement the KnowledgeOS theory · decide what gets represented |

**Basis** The mapping is already true of the phases; it is simply never stated, so the protocol reads as one undifferentiated pipeline.
**Test** each phase is assignable to exactly one activity. **Dependencies** R-03, R-04.

---

## R-03 · ⭐ Make the algorithm iterative

**Section** §3.2 · **Class** `METHODOLOGICALLY_FORCED` · **Severity** `BLOCKING` for the laboratory model

**Current** `STEP2(registries):` — phases 0…K execute **once**. Verified: no loop construct, no iteration identity.

**Replacement**

```
STEP2(registries):
  assert class_A_prerequisites()

  iteration := 0
  repeat:
      iteration += 1
      # ---- READ ------------------------------------------
      Phase 0 · A · B · C          (re-READ only under R-09)
      # ---- CONSTRUCT -------------------------------------
      Phase D · B2 · E · F · G
      # ---- IMPLEMENT (R-04) ------------------------------
      Phase L
      # ---- TEST / COMPARE / DISCOVER ---------------------
      Phase H0 · H · I · J
      # ---- REFINE ----------------------------------------
      record REPRESENTATIONAL_GAPs, protocol defects, model defects
      revise METHODOLOGY and MODEL — never only the model
      Phase K checkpoint(iteration)
  until freeze_criteria_met()      # §3A.6 as revised by R-14
```

⭐ **Every artifact carries `iteration_id`.** A later iteration **never overwrites** an earlier one; it supersedes it, and `THEORY-EVOLUTION` records the transition.

**Basis** ⛔ `§3A.1` states the loop in prose; `§3.2` specifies a single pass. **`READ → CONSTRUCT → IMPLEMENT → again` is currently not expressible in the protocol's own algorithm.**
**Test** two iterations produce two distinguishable, co-existing seed versions. **Dependencies** none — ⭐ **land with R-04**.

---

## R-04 · ⭐ IMPLEMENT becomes a phase

**Section** §3.2 · **Class** `METHODOLOGICALLY_FORCED` · **Severity** `BLOCKING` for the laboratory model

**Current** `§3A`/`§3B` describe implementation but are **never referenced by the algorithm**; phases run `0 A B C D B2 E F G H0 H I J K` with **no IMPLEMENT phase**.

**Replacement — insert after Phase G:**

```
# ---- Phase L · IMPLEMENT (§3A, §3B) ---------------------
needs := representation_needs_of(construction_output)
# ⭐ DERIVED from what CONSTRUCT actually produced this iteration —
#    NOT read from a pre-specified kernel.
for each need in needs:
    if representable(current_model):  continue
    else: record REPRESENTATIONAL_GAP(need)        # R-07
build_or_extend(minimum capability satisfying needs)   # R-13
run_engine(F0001..F0025)
compare(expert_2A, engine_2B)                      # §3A.5 ladder
```

⛔ **Phase L may extend the laboratory. It may never extend the theory.**

**Basis** Implementation is a first-class activity that is procedurally orphaned. Nothing currently says when it runs, what it consumes, or what it returns.
**Test** Phase L consumes construction output and emits gaps + comparison. **Dependencies** R-03.

---

## R-05 · ⭐ CONSTRUCTED does not mean TRUE

**Section** §5 (leading) · **Class** `METHODOLOGICALLY_FORCED`

> ## ⛔ **CONSTRUCTED does not mean TRUE.**
>
> Construction produces **candidates**. A constructed formulation may later be **supported · weakened · refuted · split · merged · replaced · abandoned** — and every one of those is a legitimate laboratory result, not a failure.
>
> ⭐ **Abandonment is a result** (`REJECTED_INTERPRETATION`, §4A.4). Three of the corpus's strongest moves are refusals: `F0019` declined a fourth taxonomy, `F0014` withdrew `LG-1` as ill-posed, `F0018` conceded its own question was solution design.

**Basis** Verified absent. The ladder implies it (L2 = hypothesis) and `Q3` enforces strength ≤ evidence, but **the sentence is never stated** — and it is the load-bearing sentence of a theory laboratory.
**Test** every seed item at L2/L3 is labelled candidate in the seed's own prose. **Dependencies** none.

---

## R-06 · Re-label the kernel as iteration-derived

**Section** §3B.3 · **Class** `LABORATORY_REQUIRED` · ⭐ **amends `E-11`**

**Current** *"Corrected kernel — 10 primitives"*

**Replacement**
> **Iteration-1 representation needs — derived, revisable, not a model.**
>
> These primitives are **what construction over F0001–F0025 actually required**. ⛔ **They are not a settled ontology, and no later iteration is obliged to fit them.**
>
> ⚠️ **Under `READ → CONSTRUCT → IMPLEMENT`, representation is DERIVED from construction, never specified ahead of it.** A kernel fixed in advance decides what can be represented, and therefore what can be found.
>
> ⭐ **`OQ-L4` is the central empirical question of Phase 2B: does this set survive iteration 2, or does construction demand primitives we have not imagined?**

**Basis** Theory-neutrality violation `V-1`. The kernel is legitimate as an iteration-1 input and illegitimate as a fixed model; only the label is wrong.
**Test** iteration 2 may add or remove primitives without a protocol amendment. **Dependencies** R-03.

---

## R-07 · `REPRESENTATIONAL_GAP` record

**Section** §3B.4 · **Class** `LABORATORY_REQUIRED`

```yaml
representational_gap:
  id:                    # RG-####
  iteration:
  research_statement:    # what the research said, verbatim
  current_representation: # what the model can hold today
  missing_capability:
  why_insufficient:      # why existing structures do not suffice
  fault_class:           # PROTOCOL | CONCEPTUAL | ARCHITECTURAL
                         # | IMPLEMENTATION | HUMAN_JUDGEMENT
  proposed_experiment:
  resolution:            # OPEN | RESOLVED | ACCEPTED_LIMITATION
```

⛔ **The model is never silently changed.** A gap is recorded, classified, and resolved by an explicit act.

⚠️ **`HUMAN_JUDGEMENT` is not a defect.** *"`T-0016` was reformulated…"* included *"test = none — a human judgement"*; that is correct behaviour, not a missing feature.

**Basis** `§3B.4` defines the test but no record and no fault classification; gaps would be reported in prose and lost.
**Test** the `T-0016` worked example produces at least one `RG` with a non-`IMPLEMENTATION` fault class. **Dependencies** R-04.

---

## R-08 · `Experiment` record

**Section** §13 · **Class** `LABORATORY_REQUIRED`

```yaml
experiment:
  id:                # EXP-####
  iteration:
  hypothesis:        # what is being tested
  falsification_condition:   # ⭐ stated BEFORE execution
  test_type:         # T1..T8 (§13)
  design:
  executed:          # true | false
  result:            # SURVIVED | REFUTED | INCONCLUSIVE
  interpretation:
  affected_items: []
  provenance:
```

⭐ **`falsification_condition` is recorded before execution.** `OQ-11` already establishes why: a benchmark built from the theory's own concepts cannot refute it unless its falsifier was fixed in advance.

**Basis** §13 supplies eight test *types* and no experiment *object*. ⛔ **A laboratory without experiment records is not a laboratory.**
**Test** an experiment with no pre-stated falsifier is rejected. **Dependencies** none.

---

## R-09 · Re-READ bias control

**Section** §4 (new) · **Class** `METHODOLOGICALLY_FORCED`

> ⚠️ **In iteration 2+, reading happens while a candidate theory is already held. That is confirmation bias, and it has no control today.**
>
> **Re-READ is permitted only under a named obligation, and must record:**
>
> ```yaml
> reread:
>   obligation:            # which RO/RG required it
>   theory_state_at_read:  # what was believed — declared BEFORE reading
>   expected_finding:      # ⭐ stated BEFORE reading
>   actual_finding:
>   confirmed_expectation: # true | false
> ```
>
> ⭐ **A re-READ that only ever confirms expectations is a finding about the reader**, not about the corpus, and is reported in `SURPRISE-DISCOVERIES`.

**Basis** `Phase H0` guards verification against defending the seed; **nothing guards re-reading against confirming it**. And the practice has precedent: Step-1 batch 002 made falsifiable predictions about P3A **before** reading, confirmed 4/4 — which is exactly why that result carries weight.
**Test** every re-READ carries a pre-stated expectation. **Dependencies** R-03.

---

## R-10 · Laboratory insufficiency as a distinct finding

**Section** §10 · **Class** `LABORATORY_REQUIRED`

> **Three faults must never be conflated:**
>
> | Fault | Meaning | Fix |
> |---|---|---|
> | **THEORY** | the theory is wrong | revise the theory |
> | **PROTOCOL** | the rule is imprecise | revise the protocol |
> | ⭐ **INSTRUMENT** | the laboratory cannot represent or test it | revise the laboratory |
>
> ⛔ **An instrument failure is never evidence about the theory.** A telescope that cannot resolve a star says nothing about the star.

**Basis** `§3B.4` classifies a representational deficiency as a protocol or model defect but has **no category for the instrument itself being inadequate**.
**Test** at least one finding in the pilot is classified `INSTRUMENT`. **Dependencies** R-07.

---

## R-11 · Conceptual view vs dependency direction

**Section** §3B · **Class** `REVERSIBLE_DESIGN` · ⭐ **supersedes `E-13`**

> **Researcher's conceptual view** — `RESEARCHER → EXPERIMENT WORKBENCH → THEORY LAB CORE → EXPERIMENT ENGINES → CORPUS`
>
> ⛔ **This is not the dependency direction.**
>
> **Dependency direction:** `driving adapters → application → domain ← ports ← driven adapters`
>
> ⭐ **The inversion:** mathematical, statistical, ML/LLM engines, corpus readers and persistence are **external capabilities**. The Core **declares what it needs**; they implement it. **The Core depends on none of them** — otherwise changing an ML library changes the theory model.

**Basis** The five-layer stack shows the Core depending downward on the engines. In dependency terms that arrow inverts.
**Test** static import check on the Core. **Dependencies** `E-02`.

---

## R-12 · Research discovery ≠ final theory component

**Section** §4A · **Class** `METHODOLOGICALLY_FORCED`

> ⛔ **The eleven emergent kinds record RESEARCH DISCOVERIES. They are not, and do not become, components of the final theory.**
>
> A `NEW_CONCEPT` is *a concept this reconstruction proposes*, not *a concept KnowledgeOS contains*. Promotion from discovery to theory component requires the full ladder and, ultimately, a governance act Phase 2 cannot perform.

**Basis** §4A currently reads as though recording an emergent establishes it. `Q1` prevents L5 but the *conceptual* distinction is unstated.
**Test** no emergent record is cited as a theory component in the seed without an explicit level. **Dependencies** none.

---

## R-13 · Anti-over-engineering rule

**Section** §3B (new) · **Class** `LABORATORY_REQUIRED`

> ## **The correct architecture is the SMALLEST one that faithfully supports READ → CONSTRUCT → IMPLEMENT → TEST.**
>
| ⛔ Do not introduce | Why |
|---|---|
| microservices · databases · graph databases · frameworks | the pilot is 193 rows |
| fixed package taxonomies | `§3B.5` already defers names |
| final aggregates · final ontology | adjudicated **PREMATURE** |
| production scalability | ⛔ a Phase-4 concern about an engine whose model is not yet validated |

> ⭐ **The pilot is deliberately small. Its purpose is to discover what the laboratory needs — and over-engineering destroys that measurement**, because a model rich enough to hold anything reveals nothing about what was actually required.

**Basis** `E-12`: a disposable laboratory makes over-engineering strictly more costly than a slightly wrong abstraction.
**Test** every primitive in the pilot is traceable to a representation need from Phase L. **Dependencies** R-04, R-06.

---

## R-14 · Revised freeze criterion

**Section** §3A.6 · **Class** `METHODOLOGICALLY_FORCED`

> ⛔ **Do not freeze because the software runs.** Freeze only when:
>
> ```
> READ works → CONSTRUCT works → IMPLEMENT works → comparison works
>   → unexpected discoveries can be represented
>   → replacement / split / merge work
>   → provenance survives
>   → Step-1 remains immutable
>   → L5 cannot be produced by Phase 2
>   → a second run reproduces the methodology
> ```
>
> **Status vocabulary:** `PASS` · `PASS_WITH_OPEN_QUESTIONS` · `FAIL` · `INCONCLUSIVE`.
>
> ⛔ **No single percentage.** `INCONCLUSIVE` is recorded, never silently treated as a pass. Freeze requires no `FAIL` and no `INCONCLUSIVE` on: unexpected-discovery representability · replacement/split/merge · provenance survival · Step-1 immutability · L5 unreachability.

**Basis** Extends the seven-condition rule with the laboratory sequence and the four-status vocabulary.
**Test** the criterion is evaluable after iteration 2. **Dependencies** R-03, `E-10`.

---

## Classification summary

| Class | Count | Changes |
|---|---|---|
| `EVIDENCE_FORCED` | **0** | — ⭐ *this pass is methodological; the evidence-forced findings were settled in adjudication* |
| `METHODOLOGICALLY_FORCED` | **7** | R-02 · R-03 · R-04 · R-05 · R-09 · R-12 · R-14 |
| `LABORATORY_REQUIRED` | **6** | R-01 · R-06 · R-07 · R-08 · R-10 · R-13 |
| `REVERSIBLE_DESIGN` | **1** | R-11 |
| `OPEN` | **0** | — recorded as `OQ-L1`…`OQ-L6` instead |
| `PREMATURE` | **0** | — |

⭐ **R-03 and R-04 are the minimum viable pair.** Without them the protocol *describes* a READ→CONSTRUCT→IMPLEMENT loop while *specifying* a single pass with no implementation step. Everything else is refinement.

---

*Traceability: READ→CONSTRUCT→IMPLEMENT changeset, 2026-09-22 · 14 changes classified · additive to `PHASE2-ARCHITECTURE-CHANGESET.md` (13 edits), with `R-06` amending `E-11` and `R-11` superseding `E-13` · ⛔ **NOT APPLIED — Step-2 protocol unmodified · Step-1 Master Protocol unmodified · no code · Phase 2 not executed · no theory frozen** · awaiting explicit authorization.*
