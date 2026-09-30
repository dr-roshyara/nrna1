# KnowledgeOS — Step 2: Theory Construction Protocol

| | |
|---|---|
| **Kind** | **PROPOSAL — a protocol for review.** ⛔ *Not adopted · not frozen · does not modify the Step-1 Master Protocol (`knowledge_os_protocoll.md`)* |
| **Status** | ⚠️ **DRAFT v3.2 — READ → CONSTRUCT → IMPLEMENT laboratory model. Awaiting final pre-execution audit** |
| ⭐ **v3.3 change** | **Senior Researcher Role and Research Objective** section added, plus the **`[T]` test-derived** ORIGIN value (§5C.2, `Q51`, `Q52`). **Human-directed, 2026-09-23.** ⛔ *Adds no job, no level, no gate and no architecture change. `[T]` is a new value on an existing axis* |
| ⭐ **v3.2 change** | **§0.3 governance gates at the TWO PHASE BOUNDARIES** — end of Phase 1 and end of Phase 2. **Human-directed, 2026-09-23.** ⛔ *Adds no gate, no epistemic rule and no architecture change — **execution wiring** only* |
| ⛔ **v3.1 CORRECTED** | v3.1 made the preflight **mandatory before every 5-file window**. ⛔ **That was more than was asked for and is withdrawn** — the per-window call is now **optional/experimental** and never satisfies the boundary checks |
| ⚠️ **Authorization basis** | ⭐ Architecture **§9**: *"A protocol that contradicts it is defective; **the protocol changes, not the architecture.**"* The architecture is untouched. ⚠️ The **methodology freeze** (`CLAUDE.md`, 2026-08-01) bars protocol refinement absent a genuine deficiency — ⛔ **the deficiency is recorded: `gates.yaml` existing does not make any session invoke it**, and *"a control that requires a human to invoke it is not a control"* is this programme's own finding |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Evidence base** | **F0001–F0025**, reconstructed under Step 1. Every design decision cites the observation that forced it. |
| ⭐ **Phase 2 is** | a **disposable, theory-neutral scientific laboratory with durable research records** — ⛔ *not an implementation of KnowledgeOS* |
| **Experiment served** | `F0001–F0025 Step 1 → historical story → theory seed → expert verification → testing agenda → improved seed → distance-to-final-theory assessment` |
| ⭐ **THE PRIMARY OUTPUT** | **`CANDIDATE-KNOWLEDGEOS-THEORY.md`** — a human-readable candidate theory. ⛔ *Every other artifact exists to support it* (§5A) |
| **Three deliverables** | `HISTORICAL-STORY.md` (what happened) · `THEORY-SEED.md` (the specification) · ⭐ **`CANDIDATE-KNOWLEDGEOS-THEORY.md` (the theory a human reads)** |
| ⭐ **Output location (BINDING)** | **All Step-2 output is written to `docs/knowledgeos/knowledgeos_theory_chronological_extraction/phase2_extraction/`** — see §15.0. ⛔ *Nothing is written outside it* |
| **Non-goals** | ⛔ Producing a final theory · ⛔ adopting anything · ⛔ reconciling competing formulations by preference · ⛔ repairing the historical record |
| **Change report** | `../STEP2-PROTOCOL-CHANGE-REPORT.md` |

---

## ⛔ GOVERNING ARCHITECTURE — read first

> **Before executing this protocol, read [`KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md`](KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md) — ⛔ **FROZEN v1.0**.**
> ⛔ **A frozen architecture does not imply a complete theory, complete corpus processing, or completed validation. It freezes the RULES by which those are performed.**
> **It is the governing boundary for Phase-1/Phase-2 responsibilities. ⛔ Do not duplicate or redefine it here unless an explicit architecture change is being proposed.**

### This protocol governs the **THEORY RECOVERY, CONSTRUCTION & VALIDATION CONTEXT** *(Phase 2)*

**Its question:** ⭐ ***"What theory can be constructed from the reconstruction?"***

⭐ **Its ubiquitous language:** *recovered theory · candidate · hypothesis · formalization · experiment · falsifier · maturity · readiness*. ⚠️ **Phase-1 words mean something different here** — *evidence* there is a **locator into a file**; here it is **a reason to believe a claim**. ⛔ **Translating between them is work, not a cast.**

### ⭐ This context has THREE jobs — `2A` · `2B` · `2C`

| | Job | Question |
|---|---|---|
| **2A** | **Theory Recovery** | *what theory has the corpus already developed?* — ⭐ includes targeted search **outside** the immediate file window |
| **2B** | **Constructive Completion** | *what is missing?* — additions only where expert reasoning justifies them, labelled `[C]`/`[S]`/`[E]` |
| **2C** | **Theory Attack** | *does it survive?* — mathematical · logical · statistical · empirical · computational. A formulation a test changes becomes a new `[T]` record (§5C.2) |

⚠️ **Validation is `2C`, ⛔ not a separate context.** It has no ubiquitous language or invariants of its own.

### ⛔ What this context consumes — and may never do

It consumes a **`Research Reconstruction Package`** *(`RA-3`)*. ⛔ **It never writes a Phase-1 record** *(`RA-2`, `Q17`)*. Corrections flow back as **requests**, never as writes *(`RA-5`)*.

### ⭐⭐ The handoff is an ANTI-CORRUPTION LAYER — four ACL rules

**Measured: four of five recorded research errors occurred AT THE TRANSLATION**, not inside either context. Each rule below is derived from one:

| # | Rule | Derived from |
|---|---|---|
| **ACL-1** | ⛔ **A Phase-1 classification is NEVER a Phase-2 relevance filter.** Document kind, artifact type and folder say **nothing** about theory-bearing content | two files excluded by kind — which held the corpus's own answer to *"what is KnowledgeOS for"* |
| **ACL-2** | ⛔ **A Phase-2 category may not be applied to a Phase-1 artifact until the category is shown to exist in the corpus** | an analyst measured against an instrument's contract; a false violation recorded |
| **ACL-3** | ⛔ **Before constructing, query Phase 1 and the corpus for the construct.** A naming collision with corpus terms is a UL violation | a five-layer construct that reused four corpus viewpoint names, with arrows the corpus had dropped |
| **ACL-4** | ⛔ **A Phase-1 fact carries its Phase-1 SCOPE.** A claim over a window is not a claim over the corpus | *"the correction propagated to zero files"* — true of the files read, false of the corpus |

#### ACL enforcement — one gate per rule

| Rule | Enforced by | State |
|---|---|---|
| `ACL-1` kind ≠ relevance | **`Q54`** | ❌ violated once |
| `ACL-2` category must exist in corpus | **`Q38`** *(search before declaring)* + **`Q53`** | ✅ paid twice |
| `ACL-3` query before constructing | **`Q53`** | ❌ violated once |
| ⭐ `ACL-4` facts carry their scope | ⭐ **`Q55`** *(added — previously unenforced)* | ❌ violated once |

⚠️ **`ACL-4` had no dedicated gate.** It was claimed as covered by `Q49`'s statistical scope statement — ⛔ **that was a stretch**: `Q49` governs *counts*, while `ACL-4` governs *any* Phase-1 fact. **`Q55` closes it.**

### ⭐ Feedback is normal

```
Phase-2 test → gap discovered → TARGETED corpus re-examination
   → Phase-1 correction → Phase-2 update
```

⛔ **A re-examination is not a failure.** This path has already recovered a missing role and withdrawn a false finding *(`RA-7`)*.

### ⛔ Unreachable from here

**Layer 5 — canonicalization.** ⛔ Neither phase may reach it *(`RA-4`, `Q1`)*.

---

## ⭐ Senior Researcher Role and Research Objective

*(Added 2026-09-23 on L0 instruction. It states **how** Phase 2 reasons and **what it aims at**. ⛔ It adds no job, no level and no gate: the three jobs `2A`/`2B`/`2C`, the epistemic ladder (§1), the origin axis (§5C.2) and the governing architecture still bind. The mapping at the end of this section says how each element lands in them.)*

Phase 2 is executed by a **senior mathematician, statistician, DDD architect, computer-logic and theory-of-logic expert, ML expert, and principal research architect**.

The researcher must not behave as a passive compiler of the historical corpus.

The corpus is the **primary evidence base**, but it is not an unquestionable specification of the final KnowledgeOS theory.

### Phase 2 has five responsibilities

1. **Recover**
   * Identify the theoretical substance already developed by the corpus.
   * Preserve provenance and historical formulations.
   * Distinguish corpus-derived statements from later synthesis.

2. **Critically examine**
   * Examine mathematical correctness.
   * Examine statistical validity.
   * Examine logical consistency.
   * Examine DDD/domain coherence.
   * Examine conceptual and terminological consistency.
   * Examine computational implications and unnecessary complexity.
   * Identify hidden assumptions, missing derivations, contradictions, redundancies and alternative formulations.

3. **Construct**
   * Construct relationships, abstractions, definitions, propositions and theoretical structures that are justified by the available evidence.
   * A constructed result may combine evidence from multiple files even when no individual file states the resulting formulation.
   * Preserve competing formulations when the evidence does not justify resolution.

4. **Improve where justified**
   * The historically proposed formulation is not automatically the scientifically preferred formulation.
   * The researcher may simplify, generalize, specialize, reformulate, replace or reject a historical formulation when rigorous reasoning or testing justifies doing so.
   * Such a change must NEVER silently rewrite the historical record.
   * The historical formulation remains preserved as provenance; the improved formulation is recorded as a separate research result with explicit status and justification.

5. **Attack and validate**
   * Subject recovered and constructed theory to mathematical, statistical, logical, DDD, empirical, computational and implementation-oriented scrutiny.
   * Search actively for counterexamples and alternative explanations.
   * Use experiments and machine-learning techniques where they provide useful evidence or research signals.
   * No ML result, simulation result or implementation result automatically becomes theory.

### Origin of a Phase-2 formulation

Every non-trivial formulation must identify its origin *(the ORIGIN axis, §5C.2; gate `Q51`)*:

* **[C] CORPUS-DERIVED** — directly supported by corpus evidence.
* **[S] CORPUS-SYNTHESIZED** — constructed by combining multiple corpus-supported elements.
* **[E] EXPERT-DERIVED** — introduced through expert reasoning because the corpus does not adequately provide the required component.
* **[T] TEST-DERIVED** — introduced or modified as a result of an executed experiment or validation finding.

For `[E]` and `[T]` results, record at minimum *(gate `Q52`)*:

* problem or gap being addressed;
* corpus evidence relevant to the problem;
* proposed formulation;
* reasoning;
* assumptions;
* alternatives considered;
* consequences;
* falsification conditions;
* validation required or performed;
* provenance;
* epistemic level;
* reversible/research status.

### Core principle

> **Historical reconstruction preserves what the corpus developed. Scientific research determines what should survive, what should be improved, and what should be rejected.**

Therefore:

**Corpus Evidence
→ Historical Reconstruction
→ Theory Recovery
→ Critical Mathematical / Statistical / Logical / DDD Analysis
→ Alternative Formulations
→ Constructive Completion
→ Formalization
→ Experimental / ML / Computational Investigation
→ Falsification / Validation
→ Theory Consolidation
→ Candidate KnowledgeOS Theory**

The transition from historical formulation to improved formulation must always remain traceable.

### Important separation

The researcher must continuously distinguish:

1. **What the corpus said**
2. **What Phase 1 reconstructed**
3. **What Phase 2 recovered**
4. **What Phase 2 synthesized**
5. **What the researcher proposed**
6. **What was mathematically/logically/statistically derived**
7. **What was experimentally tested**
8. **What survived testing**
9. **What remains unresolved**
10. **What is eventually accepted as canonical**

No historical formulation is improved by silently editing its historical record.

No expert formulation becomes theory merely because an expert proposed it.

No ML output becomes theory merely because a model produced it.

No implementation success becomes theoretical validation merely because the software runs.

### Researcher's freedom

The protocol constrains **evidence, provenance, epistemic status, testing, falsifiability and traceability**.

It does not constrain legitimate scientific reasoning *(§0.2)*.

The researcher may discover that:

* the historical theory is incomplete;
* two historical formulations are equivalent;
* two apparently related concepts are actually distinct;
* a historical formulation is mathematically incorrect;
* a simpler formulation explains the same evidence;
* a stronger generalization is justified;
* a proposed structure is unnecessary;
* an alternative mathematical structure is better supported;
* an ML or computational experiment reveals an unexpected pattern;
* the existing candidate theory should be replaced by a better-supported formulation.

Such findings are research outcomes and must be recorded explicitly rather than suppressed merely because they differ from the historical corpus.

### Final objective

The objective of Phase 2 is therefore **not historical fidelity alone**.

Its objective is:

> **to construct the strongest defensible, mathematically coherent, logically consistent, statistically meaningful, computationally viable and empirically testable candidate theory that can be justified from the corpus and rigorous expert research, while preserving complete provenance of how every substantive element arose.**

### ⛔ Binding — how this section lands in the existing protocol

| Element above | Lands in | ⛔ Boundary that still holds |
|---|---|---|
| **Five responsibilities** | **Recover** = `2A` · **Construct** and **Improve** = `2B` · **Attack and validate** = `2C` · **Critically examine** runs through all three | the context still has **three jobs**, fixed by the architecture. The five are a reading of them, not five new jobs |
| **Origin `[C]/[S]/[E]/[T]`** | §5C.2 ORIGIN axis, gates `Q51`/`Q52` | ORIGIN ≠ STRENGTH. ⛔ **A `[T]` formulation is not validated by being test-derived**: it is created *because of* a test, so it enters at **L2** and needs its own test to reach L4 (§1.1) |
| **Improve: replace or reject** | a new L2+ record, with the historical formulation kept as provenance. A refuted formulation is **demoted and kept in a competition** (§1.1, §6), never deleted | ⛔ Phase 2 never writes a Phase-1 record (`RA-2`, `Q17`). A historical correction is a **request** back to Phase 1 (`RA-5`) |
| **Resolve competitions** | only where a named test or derivation decides it (§1.1 SURVIVED/REFUTED) | ⛔ never "by preference" (header: non-goals). Otherwise both are kept, with a discriminator |
| **Separation items 1–10** | items 1–2 = L0/L1 · 3–5 = L1/L2 with ORIGIN · 6 = L3 · 7–8 = L4 (§1.1 outcomes) · 9 = gaps with a `Q57` disposition · 10 = L5 | ⛔ **item 10 is never produced here.** L5 is reachable only by a human act (§1; `RA-4`) |
| **Final objective: the strongest defensible candidate theory** | `CANDIDATE-KNOWLEDGEOS-THEORY.md`, the primary output | ⛔ "candidate": still retractable, not adopted, not canonical |
| **ML / simulation / implementation** | `2C` instruments and the disposable laboratory (§3A, §3B), each result labelled as a research signal with method, inputs and limits | ⛔ no such result raises a level on its own. Only a named test from §13 that the item survives does (§1.1) |

---

## 0. Orientation

Step 1 answers *"what did the corpus say, and in what order?"* It is **complete for 25 files** and its discipline held.

Step 2 answers two further questions — *"what story does this corpus tell?"* and *"what theory is it building?"* — and the 25-file experience shows the Step-1 machinery **does not transfer unchanged**:

| # | Step-1 observation | Consequence for Step 2 |
|---|---|---|
| **1** | Reading was ~70 % of effort and is **irreducible**; synthesis was ~20 % | Step 2 operates on the **registries**. Re-opening a file requires a **named obligation**. |
| **2** | Theory lives across **files**, in threads and elsewhere. `TH-0006` spans 4 files | Synthesis is **not file-by-file**. ⚠️ **But the thread is not the only unit** — see the correction below. |
| **3** | A file is **not a point in time** (`OC-0003`) | State is keyed to **events and threads**, never a file cursor. |
| **4** | The corpus **refuses to adopt its own best self-description** (`T-0019`) | Step 2 inherits that refusal (§7). |

### 0.1 ⛔ Prior art found *after* drafting v1 — recorded, not erased

> **`EKS-35 — File timestamps do not encode argument order`** (`docs/knowledgeos/backlog/`, Lane T, 2026-09-09) already registers findings F-1/F-2, with **stronger evidence**: ~12 documents from one morning re-saved that afternoon in **exact reverse order**, 11 of 12 pairs matching.

| | |
|---|---|
| ⭐ **Corroboration** | Two independent derivations from different evidence. `OC-0003`'s event-granularity argument and EKS-35's reverse-save block are **different failure modes of the same assumption** — jointly stronger than either. |
| ⛔ **A failure — mine** | v1's ordering machinery was designed **before searching that folder**. That is *proposing-before-searching*, the failure mode `T-0023` counts and `F0015` records as occurrence #11. |

**Binding consequences:** Phase A must consume EKS-35 (mtime order can be *systematically inverted*, a stronger claim than `FILESYSTEM_MTIME_ONLY` carries); and Canonical Discovery becomes a **per-formulation obligation** (`Q10`) plus a **one-time Phase-0 sweep** — ⛔ **not an entry gate**, because a gate there would be circular (§2.2).

### 0.1b ⛔ Correction found by the pre-execution dry run — the thread is NOT the unit of synthesis

A dry run of v2's algorithm against the actual Step-1 registries produced:

| Measured | Result |
|---|---|
| Theory objects inside a thread | **12 of 23** |
| Theory objects the algorithm would **park as orphans** | ⛔ **11** — including **`T-0019`** (which answers seed question 1, *what problem does KnowledgeOS solve?*), **`T-0023`** (the batch's most useful finding), `T-0001`, `T-0020`, `T-0022` |
| Architecture objects routable | ⛔⛔ **0 of 10** — `HA-0006`..`HA-0010` **silently dropped**; threads carry only `member_theory_objects` |

> ### ⛔ **Under v2 as written, the seed's headline answer would have come from a parked object, and the four bounded contexts would not have entered synthesis at all.**

**Correction (applied):** synthesis ranges over **threads, clusters, ungrouped nodes and any grouping Claude constructs**. All typed Step-1 nodes are admitted (Phase B). **An ungrouped node is a first-class synthesis input**; `ORPHANS` records a finding about the *grouping*, never a reason to exclude the *node*.

### 0.1c ⭐⭐ What Phase 2 is — and what it is **not**

> ## **Phase 2 produces a RESEARCH RECONSTRUCTION PACKAGE from which a book can later be generated.**
> ## ⛔ **Phase 2 does not produce the book.**

```
~3,000 historical files
   ↓  PHASE 1 — Historical Reconstruction          "What actually happened?"
evidence · relationships · derivations · gaps · theory objects
   ↓  PHASE 2 — Theory Construction   ← THIS PROTOCOL
historical story · theory candidates · formalizations
competing interpretations · verification · tests        (provenance ATTACHED)
   ↓  PHASE 3 — Theory Synthesis / Exposition      "How is it explained?"
book-like, reader-oriented; corpus chronology no longer drives the text
   ↓  PHASE 4+ — Validation / Canonicalization
   ↓
Canonical KnowledgeOS Theory  →  BOOK / MONOGRAPH / SPECIFICATION
```

| ⛔ `THEORY-SEED.md` is **not** the book | ✅ It is a **provisional scientific theory specification** |
|---|---|
| ⛔ `HISTORICAL-STORY.md` is **not** the book | ✅ It is the **historical narrative the theory emerged from** |

**The two representations Phase 2 must sustain:**

| | **Research representation** *(the important one here)* | **Reader representation** *(begins in Phase 3)* |
|---|---|---|
| Answers | *what did the corpus discover · how did ideas evolve · what supports each claim · what is uncertain · what rival explanations exist* | *what is the theory* |
| Form | JSONL registries + traceable records | continuous prose |
| Voice | *"F0042 introduces… F0087 refines… F0172 challenges…"* | *"A Knowledge Object consists of…"* |
| Chronology | **drives the text** | **absent from the main narrative**; provenance moves to a footnote/appendix layer |

> ⭐ **Phase 2 must make KnowledgeOS *readable as a coherent theory* — without forcing it into book form.** `HISTORICAL-STORY.md` may read like a chapter *(§4.4)*, but every claim in it carries provenance and epistemic status rather than presenting itself as established truth.
>
> ⛔ **A beautiful book written around a theory the evidence has not yet justified is the specific failure this separation exists to prevent.**

**Audit view — must hold for every Phase-2 statement, and remain true through Phase 3:**

```
statement → theory object → derivation → evidence → historical source
```

A reader must be able to ask *"why is this concept, definition or proposition in the theory?"* and be answered from the record (§14).

### 0.2 ⭐⭐ The conceptual rule — what this protocol does and does not constrain

> ## **The protocol constrains what must be evidenced, recorded, separated, tested and preserved.**
> ## ⛔ **It does not constrain legitimate reasoning or theory construction.**

| The protocol governs | The protocol does **not** govern |
|---|---|
| that a formulation records its **level**, **provenance** and **falsifier** | **whether** to propose it |
| that synthesis states its **identity basis** | **which** objects to relate |
| that competing formulations are **both kept** with a discriminator | which one looks more promising |
| that a structure declares its **formalization stage** (§9) | which structure to look for |
| that findings are **recorded, never silently applied** | how hard to criticise |

**Step 2 is expected to construct hypotheses and theory objects no single file states.** That is the point. `T-0023` (the occurrence counter as an ordering instrument) was assembled from five files, **none of which says the counter orders anything** — and it is the batch's most useful finding. A protocol that forbade such synthesis would have suppressed it.

> **Discovery of unexpected relationships is a success condition, not a deviation.** The obligation is to *mark* it L2 and link its provenance — never to avoid it.

---

### 0.3 ⭐⭐ Governance gates — MANDATORY at the two PHASE BOUNDARIES

> ## ⛔ **The required checks are exactly two: when Phase 1 finishes, and when Phase 2 finishes.**

```
.claude/hooks/governance-preflight.sh
```

| Boundary | Where | Before proceeding to |
|---|---|---|
| ⭐ **End of Phase 1** | the handoff-acceptance point at the head of `STEP2()` | **Phase 2** |
| ⭐ **End of Phase 2** | immediately after `until freeze_criteria_met()` | **anything beyond Phase 2** |

⛔ **There is NO mandatory gate before each 5-file reading window.** A per-window call remains available as an **optional, experimental** early-feedback mechanism; ⛔ **it never satisfies, replaces or obscures the two boundary checks.**

**Why the call lives in the protocol rather than in a habit:** ⭐ *a control that depends on the executing session remembering to invoke it is not a control* — this programme's own enforcement audit, about a different gate. **Putting the call at the boundary is what makes it binding; `gates.yaml` merely existing is not.**

#### The four outcomes, and what each obliges

| Status | Obligation |
|---|---|
| ⛔ **`BLOCK`** | **STOP at the boundary.** ⛔ Do not enter the next phase. Record the failing gate ids |
| ⛔ **`GOVERNANCE_INOPERATIVE`** | **STOP.** ⭐ **The mechanism itself is broken** — this is neither a pass nor a failure, and it grants nothing |
| ✅ **`CLEAR`** | proceed · record the status |
| ⚠️ **`NO_ACTIVE_GOVERNANCE_CONTROLS`** | ⭐ **proceed AND record it.** ⛔ **This is not a pass.** Recording is mandatory *because* nothing was enforced |

#### ⛔ On a `BLOCK`, three things are forbidden

| ⛔ | Why |
|---|---|
| **reading on anyway** | the block is the whole mechanism |
| **repairing the gate** | ⭐ **fixing the instrument that just failed you is the author grading himself.** Fix the *artifact* the gate measured, in a later authorized unit |
| **deactivating the gate** | activation and deactivation are **human acts**, recorded in `governance-state.yaml`. ⛔ **A research session that switches off its own gate has ended the arrangement, not passed it** |

#### ⚠️ What a recorded status is worth

⭐ **`NO_ACTIVE_GOVERNANCE_CONTROLS` is the expected status today** — the mechanism ships inert with `activated: []`, and only a human act changes that.

> ⛔ **Recording it is not a formality.** A run of windows all stamped `NO_ACTIVE_GOVERNANCE_CONTROLS` is **evidence that the research proceeded ungoverned**, and it should be uncomfortable to read. **That is the point of recording it rather than printing `CLEAR` and moving on.**

⚠️ **Scope, stated so it does not drift:** the preflight reports **process conformance against activated rules**. ⛔ **It never states that the research is valid, the theory right, or the window well chosen.** Those remain `REVIEW`- and `HUMAN`-class questions, and `KOS-G-060` — *engineering never accepts its own work* — is **outstanding**.

*Mechanism: `governance/README.md`. ⛔ It is **temporary, research-scoped, and tamper-evident rather than tamper-proof** — never described as an independent governance boundary.*

---

## 1. The six-level epistemic ladder

Every statement Step 2 produces sits at exactly one level, carries it as a field, and moves only under a named rule.

```
L0  HISTORICAL EVIDENCE   what a file literally says          immutable, verbatim, never edited
L1  RECONSTRUCTION        what Step 1 established             objects · edges · threads · gaps
L2  HYPOTHESIS            a formulation NO file states        synthesis proposes it
L3  DERIVATION            a consequence of L1/L2              premises recorded, validity graded
L4  VALIDATION            an L2/L3 item TESTED and surviving  a named test, executed
L5  CANONICAL             adopted                             ⛔ REACHABLE ONLY BY A HUMAN ACT
```

> ### ⛔ **Step 2 can never produce an L5 statement. Not once, not for anything.**

**Why this shape — the corpus's own mechanism.** `T-0013` (F0018): ***evidence EARNS; governance GRANTS; promotion requires BOTH.*** L0→L4 is what evidence can earn; L5 is what only governance grants. **The protocol obeys the theory it reconstructs.**

### 1.1 Promotion and demotion

| Transition | Rule | Window evidence |
|---|---|---|
| L0 → L1 | a Step-1 record with a verbatim location | 19 verified edges |
| L1 → L2 | ≥ 2 L1 objects no single file relates **+ a stated identity basis** | `T-0023` from 5 files |
| L2 → L3 | premises enumerated · inference stated · validity graded | `DI-0006`..`DI-0014` |
| L3 → L4 | **a named test from §13 executed, and the item survived** | `P3A-CMP-0007`: prediction made before reading, confirmed 4/4 |
| L4 → L5 | ⛔ **NOT AVAILABLE** | `HA-0007`: "no document can settle it" |

**Test outcomes are three, not two** — v1 had no rule for a failed test:

| Outcome | Effect |
|---|---|
| **SURVIVED** | promote to L4 |
| **REFUTED** | ⛔ **demote to L2 and open a competition** (§6) — never delete |
| **INCONCLUSIVE** | level unchanged; record why the test could not decide |

**Demotion is always permitted and never requires authority.** `DI-0008`: F0019's premise was refuted while its conclusion was retained. Step 2 must record such a retention as a **demoted derivation**, not an unchanged conclusion.

---

## 2. Inputs and entry conditions

### 2.1 What Step 2 consumes

| Input | Role | State at F0025 |
|---|---|---|
| `THEORY-OBJECTS.jsonl` | the nodes | 23 — 3 underspecified |
| `THEORY-THREADS.jsonl` | **the synthesis units** | 8 — 1 `historically_complete` |
| `VERIFIED-EDGES.jsonl` | the relations | 19, all textually stated |
| `DERIVATION-INSTANCES.jsonl` | reasoning, with validity grades | 14 |
| `ORDERING-CONSTRAINTS.jsonl` | partial order over **events** | 8 |
| `CONTRADICTIONS.jsonl` | competing formulations | 9 |
| `GAPS.jsonl` · `RESEARCH-OBLIGATIONS.jsonl` | the boundary of the knowable | 11 · 16 |
| `ARCHITECTURE-OBJECTS.jsonl` | structural claims | 10 |
| `SOURCE-LOCAL-IDENTIFIERS.jsonl` | **the collision map** | 18 — 4 meanings of `D-` |
| `INTRA-FILE-REVISIONS.jsonl` | within-file events | 15 |
| `FILE-REGISTRY.jsonl` | typed date events | 25 |

⛔ **Step 2 never writes to a Step-1 artifact.** Thread state is extended in `STEP2-THREADS.jsonl` as an **overlay keyed by `thread_id`**, so Step 1 remains independently re-runnable and auditable.

### 2.2 ⭐⭐ Entry conditions, in three classes — the circularity is removed

> ### ⛔ **v1 created a circular gate.** `G2-D` demanded every competition carry a discriminator *before* Step 2 could start — but **naming discriminators is Phase D of Step 2**. `G2-F` demanded Canonical Discovery *before* proposing formulations that do not yet exist. **A gate may never require the output of the stage it guards.**

#### Class A — **PREREQUISITES** ⛔ *must be true before Step 2 starts*

A prerequisite is admitted only if its absence would make Step 2's **output silently wrong**, not merely incomplete.

| # | Prerequisite | Why it qualifies | State |
|---|---|---|---|
| **P-1** | No dangling references among Step-1 registries | A broken graph makes every traversal wrong **without error** | ✅ clean |
| **P-2** | One registry schema | A query across two schemas returns wrong results silently | ❌ **`RO-0014`** — 10 rows, mechanical |
| **P-3** | Identifier collision map present | 4 meanings of `D-`; an unguarded join **fuses distinct objects** | ✅ present |

#### Class B — **STEP-2 DISCOVERIES** ⛔ *never gates — these are what Step 2 exists to find*

| Former gate | Reclassified as | Where it now lives |
|---|---|---|
| `G2-D` discriminators | **Phase D output** | §3.2, §6 |
| `G2-F` canonical discovery | **per-formulation obligation** `Q10` + **one-time Phase-0 sweep** | §3.2 Phase 0 |
| `G2-B` underspecified objects | ⭐ **per-thread precondition, not a global gate** | §2.3 |

#### Class C — **DECLARED OPEN OBLIGATIONS** ⚠️ *may remain open; must be declared in the seed's boundary section*

`RO-0008` (R-36/41/63) · `RO-0010` (counter continuity) · `RO-0013` (nine criteria) · `RO-0015`/`RO-0016` (EKS sweep) · `G-0007` (corpus not self-contained).

> **These are boundary conditions of the seed, not blockers.** A seed that declares its boundaries is a result. A seed that waits for all of them never exists.

### 2.3 The per-thread precondition

> A thread may be synthesized **iff its primary theory object is fully specified.** A thread with an underspecified *member* is synthesized with that member marked `INCOMPLETE` and the missing element named (§6.3).

**Current effect:** `T-0006`, `T-0010`, `T-0011` are underspecified. None is a *primary* object of any thread, so **all 8 threads are synthesizable today** — `RO-0011` is a Class-C obligation, not a blocker. *(v1 wrongly made this a hard gate.)*

### 2.4 Start condition

> **Step 2 may begin when Class A is closed.** Today that is **`RO-0014` alone** — a bounded, mechanical migration of 10 registry rows.

---

## 3. The pseudo-algorithm

> ## **Phase 2 is not where we implement KnowledgeOS.**
>
> **Phase 2 is where we READ the evidence, CONSTRUCT candidate theory, IMPLEMENT the minimum laboratory required to represent and test that construction, and use the results to discover what the theory and the methodology actually require.**
>
> ⭐ **The laboratory is disposable. The evidence and research record are not.**
>
> **The theory must emerge from the research; the software must never silently determine the theory.**

### 3.0 ⭐ The three activities

| Activity | Phases | Output | ⛔ Must never |
|---|---|---|---|
| **READ** | 0 · A · B · C | `HISTORICAL-STORY.md` | become construction · resolve a contradiction it finds · convert *not searched* into *not present* |
| **CONSTRUCT** | D · B2 · E · F · G | `THEORY-SEED.md` | assert truth · adopt what the corpus declined · exceed its evidence |
| ⭐ **IMPLEMENT** | **L** | laboratory capability · `REPRESENTATIONAL-GAPS` · `ENGINE-COMPARISON` | implement the KnowledgeOS theory · decide what gets represented |

```
READ → CONSTRUCT → discover representation needs → IMPLEMENT → TEST → learn → repeat
```

⛔ **Never:** `READ → force evidence into predefined objects → IMPLEMENT.`

Operational and resumable. It specifies **what must be recorded**, not how to think (§0.2).

### 3.1 State

```python
STATE = {
  "iteration":         0,   # ⭐ which pass; every artifact carries it (Q32)
  "model":             {},  # ⭐ the laboratory's CURRENT representation.
                            #    Iteration 1 starts EMPTY — §3B.3's list is an
                            #    input hypothesis, never a preloaded model (Q33).
  "admitted_events":   [],  # event ids, in G_S partial order
  "story":             {},  # narrative state (§4)
  "threads":           {},  # thread_id -> overlay state
  "seed":              {},  # evolving theory seed (§5)
  "competitions":      {},  # id -> {formulations[], discriminator, outcome}
  "provisional_set":   [],  # items forbidden promotion (§7)
  "structures":        {},  # id -> {stage: F0..F3}  (§9)
  "obligations":       [],  # evidence to search for (§8)
  "findings":          [],  # verification findings (§10)
  "agenda":            [],  # ranked tests (§13)
  "maturity":          {},  # per-item maturity (§11)
  "distance":          {},  # multidimensional assessment (§12)
  "level_census":      {},  # L0..L5 counts — L5 must always be 0
  "provenance_index":  {},  # item -> full chain (§14)
}
```

### 3.2 Main loop

```
STEP2(registries):

  # ===== PHASE-1 BOUNDARY GATE · §0.3 · MANDATORY ========================
  # Phase 1 ENDS here: this is where Step 2 accepts the Phase-1 handoff.
  # Run the activated research gates BEFORE entering Phase 2.
  g1 := run(.claude/hooks/governance-preflight.sh)
  case g1.status:
      BLOCK                         -> STOP. ⛔ Do not enter Phase 2.
      GOVERNANCE_INOPERATIVE        -> STOP. ⛔ The mechanism is broken.
      CLEAR                         -> record and continue
      NO_ACTIVE_GOVERNANCE_CONTROLS -> record and continue  # ⛔ NOT a pass
  record g1.status in PREFLIGHT-LOG and the state file

  assert class_A_prerequisites()          # §2.2 — P-1, P-2, P-3 only
  declare class_C_obligations()           # boundaries, not blockers

  iteration := 0
  repeat:                                 # ⭐ READ -> CONSTRUCT -> IMPLEMENT -> again
      iteration += 1
      # every artifact written this pass carries iteration_id.
      # A later iteration NEVER overwrites an earlier one — it supersedes it,
      # and THEORY-EVOLUTION records the transition.

      # ---- per-window preflight · §0.3 · ⚠️ OPTIONAL / EXPERIMENTAL -----
      # ⛔ NOT a required gate. The REQUIRED checks are the two PHASE-BOUNDARY
      #    gates above and below. This one may be run for early feedback and
      #    must never be presented as satisfying, replacing or obscuring them.
      optionally: run(.claude/hooks/governance-preflight.sh) and record it

  # ======================= READ =========================================
  # ---- Phase 0 · CANONICAL DISCOVERY SWEEP (one-time) ------------------
  sweep(backlog EKS-01..56, registers, prior analyses)
  record each hit as PRIOR_ART with what it corroborates or corrects
  # NOT a gate (§2.2). A missed hit is a finding (Q10), not a halt.

  # ---- Phase A · ADMIT -------------------------------------------------
  events := date_events + revision events            # FILE-REGISTRY + IFR
  order  := topological_sort(events, ORDERING-CONSTRAINTS + EKS-35 caveat)
  if cyclic at file granularity:  re-key to event granularity   # OC-0005
  if two events incomparable:     keep BOTH orders live         # never invent

  # ---- Phase B · ADMIT OBJECTS -----------------------------------------
  # ⛔ ALL Step-1 objects enter synthesis: theory objects AND architecture
  #    objects AND any other typed Step-1 node. Nothing is dropped.
  nodes := THEORY-OBJECTS + ARCHITECTURE-OBJECTS + other typed Step-1 nodes
  for each event e in order:
      for each node o touched by e:
          g := match_grouping(o)     # a thread, OR a cluster, OR standalone
          # §19A six criteria where identity is claimed; EVIDENCED, not inferred
          admit(o, g)
  # ⭐ An UNGROUPED node is NOT parked. It is a first-class synthesis input
  #    and is recorded in ORPHANS as a FINDING about the grouping, not the node.

  # ---- Phase C · NARRATE (§4) ------------------------------------------
  story := build_narrative(order, nodes)
  # ⛔ historical only: what was introduced, changed, connected,
  #    challenged, refuted, left open. NO synthesis here.
  # ⚠️ iteration >= 2: any re-READ obeys §4.3b bias control —
  #    declare theory_state and expected_finding BEFORE reading.

  # ======================= CONSTRUCT ====================================
  # ---- Phase D · SYNTHESIZE (open) -------------------------------------
  # ⭐ Synthesis ranges over THREADS, CLUSTERS, UNGROUPED NODES, and any
  #    grouping Claude constructs. The thread is ONE grouping mechanism,
  #    not the only admissible one (§0 obs. 2, corrected).
  for each synthesis unit u:
      collect whatever the evidence carries — definitions, assumptions,
              derivations, mechanisms, AND anything the Step-1 taxonomy
              has no category for (-> Phase B2)
      u.formulation := strongest statement its evidence supports
      assert strength(formulation) <= strength(evidence)     # the final rule
      u.level := L2 | L3 | L4

  # ---- Phase B2 · EMERGE (§4A) — runs WITH Phase D, not after -----------
  # ⭐ THE THEORY-CONSTRUCTION OPERATION. Claude MAY at any point:
  emit EMERGENT record of kind:
      NEW_CONCEPT | NEW_RELATIONSHIP | ABSTRACTION | UNIFICATION
    | SPECIALIZATION | DECOMPOSITION | COMPETING_INTERPRETATION
    | REJECTED_INTERPRETATION | EMERGENT_PRINCIPLE | EMERGENT_STRUCTURE
    | TAXONOMY_INADEQUATE
  # Each carries provenance (§14) + epistemic level. NOTHING requires that it
  # fit the Step-1 taxonomy — that taxonomy is itself under test.
  # Threads MAY be merged or split here (§4A.3) when identity evidence warrants.

  # ---- Phase E · COMPETE (§6) ------------------------------------------
  for each contradiction c (Step-1) and each COMPETING_INTERPRETATION (Phase B2):
      register(c) with BOTH formulations kept
      d := name_discriminating_observation(c)
      classify d: CORPUS_FINDABLE | EXPERIMENT | GOVERNANCE_ACT | NONE_YET
      # NEVER resolve by recency, authority, elegance or count of assertions

  # ---- Phase F · FORMALIZE (§9) ----------------------------------------
  # ⭐ F.1 DISCOVER — structures are FOUND here, not read from a list.
  candidates := search_for_structure(all synthesis units, all emergents)
  # ⛔ §9.1's S-1..S-5 are PRIOR FINDINGS carried as input, NOT the search space.
  #    Claude may find that they are incomplete, wrong, unrelated, or merely
  #    surface manifestations of a deeper structure — and MUST record that.
  # ⭐ F.2 STAGE
  for each candidate structure s in (candidates + S-1..S-5):
      s.stage := F0 pattern | F1 candidate | F2 proposition | F3 validated
      if any defining condition open:  cap s.stage at F1       # Q14

  # ---- Phase G · SEED (§5) ---------------------------------------------
  seed := assemble(story, threads, competitions, structures)
  seed.boundaries  := gaps + out_of_window + unresolvable + class_C
  seed.provisional := provisional_set                          # §7

  # ======================= IMPLEMENT ====================================
  # ---- Phase L · IMPLEMENT — EXPERIMENTAL, not a software lifecycle ----
  # ⭐ Representation is DERIVED from what CONSTRUCT actually produced this
  #    iteration. It is NEVER read from a pre-specified kernel (§3B.3).
  needs := representation_needs_of(construction_output)
  # ⭐ BOOTSTRAP: at iteration 1, STATE.model is EMPTY. Every need is therefore
  #    a gap, and that is the correct starting state — the model is BUILT from
  #    what construction asked for, never preloaded from §3B.3's list.
  for each need in needs:
      if not representable(STATE.model, need):
          record REPRESENTATIONAL_GAP(need)          # §3B.4
  STATE.model := build_or_extend(STATE.model, MINIMUM satisfying needs)  # §3B.7
  experiment := run_engine(F0001..F0025, iteration)
  compare(expert_2A, engine_2B)                      # §3A.5 seven-class ladder

  # ⛔ Phase L may extend the LABORATORY. It may never extend the THEORY.
  # ⭐ Every comparison outcome is classified into exactly one of four:
  classify each divergence as:
        THEORY_FINDING      the theory is wrong          -> revise theory
      | PROTOCOL_FINDING    the rule is imprecise        -> revise protocol
      | INSTRUMENT_FINDING  the laboratory cannot hold it-> revise laboratory
      | RESEARCH_DISCOVERY  something new was found      -> Phase B2
  # ⛔ An INSTRUMENT_FINDING is NEVER evidence about the theory (§10.3c).

  # ======================= TEST / COMPARE / DISCOVER ====================
  # ---- Phase H0 · ADVERSARIAL FRAMING (§10.2) — BEFORE verifying --------
  # ⛔ Verification must not become a defence of the seed.
  for each seed item s:
      state_before_verifying(s):
          what evidence would SUPPORT it
          what evidence would WEAKEN it
          what evidence would FALSIFY it
          what ALTERNATIVE theory explains the same observations
  # These four are recorded FIRST and are themselves verifiable outputs.

  # ---- Phase H · VERIFY (§10) — MANDATORY ------------------------------
  for mode in [SELF, INDEPENDENT, EXTERNAL_REQUIRED]:
    for lens in [MATH, STATS, DDD, LOGIC, COHERENCE,
                 FALSIFIABILITY, ALTERNATIVES, IMPLEMENTATION]:
      for each seed item s:
          f := examine(s, lens, mode)
          if problem:
              record(CLAIM, EVIDENCE, PROBLEM, WHY,
                     POSSIBLE_CORRECTION, TEST_REQUIRED, STATUS, SEED_EFFECT)
              if invalidating: demote(s)        # ⛔ NEVER edit s

  # ---- Phase I · AGENDA (§13) ------------------------------------------
  agenda := classify_and_rank(findings)         # 8 test types

  # ---- Phase J · ASSESS (§11, §12) -------------------------------------
  maturity := per_item_maturity(seed)           # M0..M5
  distance := nine_dimensional_assessment(seed) # ⛔ no single percentage
  evolution := record_theory_changes()          # §15.2 THEORY-EVOLUTION
  surprises := report_unanticipated(§16)        # ⭐ the reasoning check

  # ======================= REFINE =======================================
      revise METHODOLOGY **and** MODEL — ⛔ never only the model
      # a representation failure may reveal an incomplete theory, an imprecise
      # protocol rule, an inadequate instrument, a missing distinction, or a
      # human judgement that should stay outside automation. ⛔ Do NOT
      # classify every representation problem as a software defect.

  # ======================= CONSOLIDATE ==================================
  # ---- Phase M · SYNTHESIZE THE HUMAN-READABLE THEORY (§5A) ------------
  # ⭐ THE PRIMARY OUTPUT. ⛔ A checkpoint is INVALID while this is stale.
  theory := write_candidate_theory(story, seed, emergents, structures,
                                   competitions, experiments, falsifications)
  # ⛔ NO NEW THEORY — synthesis only. Gaps -> UNKNOWN, never filled.
  # every statement carries an epistemic level A..E;  F never appears.
  write(CANDIDATE-THEORY-SNAPSHOT, THEORY-CHANGELOG)

  # ---- Phase K · CHECKPOINT --------------------------------------------
  assert level_census["L5"] == 0
  assert provenance_early_links_complete(every seed item)   # §14 — see note
  assert all_writes_within("phase2_extraction/")            # §15.0 — Q18
  assert every_step1_node_admitted()                        # Q19
  assert candidate_theory_is_current()                     # Q41 — PRIMARY
  write(all artifacts, STATE, iteration)  ->  phase2_extraction/

  until freeze_criteria_met()             # §3A.6 — ⛔ never because it runs

  # ===== PHASE-2 BOUNDARY GATE · §0.3 · MANDATORY =======================
  # Phase 2 ENDS here: the loop has met its freeze criteria.
  # Run the activated research gates BEFORE proceeding beyond Phase 2.
  g2 := run(.claude/hooks/governance-preflight.sh)
  case g2.status:
      BLOCK                         -> STOP. ⛔ Do not proceed beyond Phase 2.
      GOVERNANCE_INOPERATIVE        -> STOP. ⛔ The mechanism is broken.
      CLEAR                         -> record and continue
      NO_ACTIVE_GOVERNANCE_CONTROLS -> record and continue  # ⛔ NOT a pass
  record g2.status in PREFLIGHT-LOG and the state file
  # NOTE: only the EARLY links (evidence -> object/thread -> reasoning) are
  # asserted. Verification and test links are populated as those phases run;
  # their absence is recorded as NOT_YET_REACHED, never as absent (§14).
  # v2 asserted the FULL chain here, which no first run could satisfy.
```

> ⛔ **Phase H may not edit the seed.** It may *demote* (always permitted, §1.1) and *record* a proposed correction. It may never apply one. **A verification pass that silently improves what it verifies has destroyed its own evidence.**

### 3.3 Resumption

Resumable at any **event boundary**, **phase boundary** and ⭐ **iteration boundary**. On resume, read `STATE.iteration`, replay that iteration's `admitted_events`, rebuild thread overlays and `STATE.model`, and continue from the last completed phase.

⭐ **Earlier iterations are never replayed or rewritten** — they are complete records. A resume re-enters the *current* iteration only. A source file is re-read only when a named obligation requires it, and then under §4.3b's bias control.

---

## 3C. ⭐⭐ Evidence-scope discipline and the resolution loop

> ### ⛔ **Being evidence is not the same as being part of the theory corpus.**
> A file may **resolve** a theoretical question without **becoming** a theory object.

### 3C.1 The six evidence categories

| Category | Role | ⛔ May it become theory? |
|---|---|---|
| **CORE CORPUS** | the theory's own record | ✅ yes |
| **GOVERNANCE EVIDENCE** | rules that constrain or define the subject | ⛔ **no** — it *bounds* what the theory may claim |
| **METHOD / DESIGN EVIDENCE** | rubrics and instruments the corpus cites | ⛔ no |
| **IMPLEMENTATION EVIDENCE** | what actually executes | ⛔ no |
| **SCHEMA / EXECUTABLE EVIDENCE** | machine-checkable artifacts | ⛔ no — but often **decisive** |
| **PRODUCT / ADMINISTRATIVE** | case law, session state | ⛔ **never** |

⛔ **Out of scope** is a seventh, and must be declared, not assumed.

### 3C.2 Controlled evidence expansion — consult, never absorb

Every consultation outside the core corpus is recorded in `EVIDENCE-EXPANSION.jsonl`:

```yaml
expansion:
  id: EX-####
  source · category · why_consulted · question_resolved · finding
  changes_candidate_theory: true|false
  effect
  authority_status          # what the SOURCE claims for itself
  becomes_theory: false     # ⛔ default; a true requires an explicit reason
```

> ⛔ **This prevents the error:** *"we found it elsewhere, therefore it is part of the theory."*

### 3C.3 ⭐ The targeted-resolution rule

> **When a term appears missing, search the controlled evidence base BEFORE declaring a gap — and before inventing a formalization that needs it.**

**Worked example, and the reason this rule exists.** A promotion threshold `bar` was introduced by *the model's own formalization*, then called the highest-value open term. Targeted search found `ES-003.2 — Score-Persistence Stop`: numeric scores are *"conversational, never architectural"* and are **not persisted**.

⛔ **`bar` was never missing. A numeric threshold is forbidden.** The formalization had imposed a quantitative structure the governance explicitly refuses. **Had the laboratory been built first, every downstream result would have been an artefact of that invention.**

### 3C.4 ⭐ The scope-drift finding

> **If answers repeatedly come from outside the declared evidence boundary, that is an ARCHITECTURAL FINDING — report it and propose a boundary adjustment. ⛔ Do not continue blindly.**

A boundary that excludes the material answering the theory's questions is a defect in the boundary, not a property of the theory.

### 3C.5 Selecting the next target — ⛔ methodology, not a research decision

> At each checkpoint, select the **highest-value unresolved candidate** by: **evidence availability · theoretical centrality · falsifiability · expected information gain.**

⛔ **No specific candidate is named in this protocol.** Which item is highest-value is a **research-state decision** recorded in the current checkpoint — never a permanent rule. A protocol that names today's target stops being reusable when the target changes.

### 3C.6 ⭐ Theory-driven, not file-count-driven

> ## **A file count is an operational detail. Theory completeness determines the boundary.**

⛔ **Five files is a checkpoint, never a Phase-2 boundary.** Continue until a candidate reaches a **readiness boundary** — 2 files, 17, or 100. And when the next useful act is a *targeted search* rather than sequential reading, **do that instead**.

### 3C.7 Controlled progression

```
incomplete            → targeted search
still incomplete      → continue corpus
formalizable          → formal validation
experimentally spec'd → laboratory
falsified             → revise / demote  (⛔ never delete)
supported             → SUPPORTED CANDIDATE — ⛔ never canonical truth
```

---

## 3A. ⭐⭐ Phase 2A and 2B — the protocol becomes executable

> ### **Programming begins DURING Phase 2, not after it.**
> ### ⛔ **But the first program must never define the theory.**

```
Phase-2 Protocol
   ↓  design data model / schemas
   ↓  implement a small Phase-2 engine
   ↓  run it against F0001–F0025
   ↓  COMPARE program output with expert reconstruction
   ↓  improve protocol AND program
   ↓  run again
   ↓  freeze Phase-2 methodology + implementation
   ↓  scale to the remaining corpus
```

⛔ **Never:** `software → whatever it produces becomes the theory.`

### 3A.1 The two layers

| | **2A — Methodology + expert reconstruction** | **2B — Executable implementation** |
|---|---|---|
| Produces | the seed, by expert judgement, per §3 | the engine: ingestion · extraction · threads · derivations · provenance · contradiction detection · gap tracking · evolution · maturity · checks · seed generation |
| Establishes | **what the process actually needs** | **whether it can be represented cleanly** |
| Runs on | F0001–F0025 | the same F0001–F0025 — ⭐ **the pilot is the engine's first test bed** |
| Output | `phase2_extraction/` | the same schemas, independently produced |

**2A runs first and 2B is compared against it** — not the reverse. 2A establishes the requirements the engine must meet.

### 3A.2 ⭐ Programming is itself a test of the protocol

> **If a protocol rule cannot be represented cleanly in software, that is evidence the rule is imprecise — a finding about the protocol, not a limitation of the program.**

**This is not speculative; it is the observed pattern in this project:**

| Executable check | What it caught that prose review had not |
|---|---|
| Step-1 §37 check 4 | a real dangling reference in **two consecutive batches** |
| Step-1 §37 check 14 | ⭐ **a defect in the check's own specification** — it tested value coincidence where it should have tested provenance |
| ⭐ **The v2 dry run** | ⛔ **3 BLOCKING defects invisible in the prose**: no object-creation operation · 21 of 33 nodes unroutable · a closed structure list |

**v2 read as a careful protocol.** It took thirty lines of executed code against real registries to show it would have produced a seed missing its own headline answer. **That is the argument for 2B, made with this project's own evidence.**

### 3A.3 Infrastructure schemas — what 2B implements

These are **bookkeeping**, not conclusions:

```
TheoryObject                    TheoryEvolution
 ├── identity                    ├── previous_formulation
 ├── definition                  ├── new_formulation
 ├── scope                       ├── reason_for_change
 ├── properties                  ├── triggering_evidence
 ├── dependencies                ├── triggering_test
 ├── theoretical_role            ├── epistemic_transition
 ├── epistemic_status            └── affected_theory_threads
 ├── maturity
 └── provenance
```

### 3A.4 ⛔ The architectural rule — what may never be hard-coded

> ## **Phase-2 software is a theory-construction and evidence-management system.**
> ## ⛔ **It is NOT an implementation of the final KnowledgeOS theory.**

| ⛔ Never hard-code | Why — from this window |
|---|---|
| *"KnowledgeOS MUST contain X"* | the corpus declines to adopt its own best self-description (`T-0019`) |
| *"X is the fundamental object"* | `MULTIPLE_THEORY_FAMILIES_REMAIN` is a permitted result (§10.3b) |
| *"Theory Y is correct"* | `C-0007` shows a claim in 7 files can be false |
| *"`S-3` is the canonical structure"* | **all five structures sit at F1**; none is even a proposition |
| *"the four bounded contexts"* | already wrong when written — `PD-3` makes five (`HA-0010`) |
| *"`T-0013` is the centre"* | ⭐ v2's coherence lens did exactly this and was corrected as theory bias |

**Instead the engine must be able to hold rivals side by side and let evidence decide:**

```
Candidate Theory A          Candidate Theory B
 ├── evidence: 12            ├── evidence: 9
 ├── derivations: 4          ├── derivations: 6
 ├── contradictions: 1       ├── contradictions: 0
 ├── failed tests: 2         ├── unresolved assumptions: 3
 └── maturity: M3            └── maturity: M2
```

⛔ **The engine reports these. It does not pick.** ⭐ And note: **A has more evidence and B has fewer contradictions** — precisely the case where a scoring function would silently become the theory.

### 3A.5 The comparison — what agreement means

Run 2A and 2B on F0001–F0025 and record, in `ENGINE-COMPARISON.md`:

| Class | Expectation |
|---|---|
| **Structure · provenance · bookkeeping · consistency · reproducibility** | ⭐ **the engine should match, reliably.** Divergence = an engine defect |
| **Candidate generation** | the engine should propose a **superset**; expert judgement prunes |
| **Identity decisions** (§19A six criteria) | ⚠️ **divergence expected.** `TH-0003` was kept `UNCERTAIN` by judgement a similarity function would have merged |
| **Formalization staging** | expect divergence; F1→F2 needs a term to be *closed*, which is a judgement |

> ⛔ **The goal is NOT that the engine reproduces every expert judgement.** Some judgements stay human. The goal is that it reliably handles structure, provenance, bookkeeping, candidate generation, consistency checks and reproducibility — **and that every divergence is explained, not averaged away.**

⭐ **A divergence is a finding about whichever side is wrong** — and the expert side is not privileged. Step-1's `AUDIT-05` was a case where the *executable* check was wrong and the specification had to change.

### 3A.6 Freeze condition

> ⛔ **Do not freeze because the software runs.**

```
READ works → CONSTRUCT works → IMPLEMENT works → comparison works
  → unexpected discoveries can be represented
  → replacement / split / merge work
  → provenance survives
  → Step-1 remains immutable
  → L5 cannot be produced by Phase 2
  → a second run reproduces the methodology
```

**Status vocabulary — four values, not two:** `PASS` · `PASS_WITH_OPEN_QUESTIONS` · `FAIL` · `INCONCLUSIVE`.

⛔ **No single percentage.** `INCONCLUSIVE` is recorded, **never silently treated as a pass**.

**Freeze requires:** no `FAIL`, and no `INCONCLUSIVE` on — unexpected-discovery representability · replacement/split/merge · provenance survival · Step-1 immutability · L5 unreachability — with every open question recorded.

⛔ **One agreeing run is not validation** — ES-006.1's rule against promoting from a single occurrence applies to this judgement too.

---

## 3B. ⭐⭐ Phase-2B software architecture — the Theory Laboratory

> ## **Phase-2 software is infrastructure for DISCOVERING the architecture of KnowledgeOS — not the architecture of KnowledgeOS.**
>
> It is a **Theory Laboratory**: an instrument, like a telescope, not the thing observed.
>
> ```
> Corpus → [ THEORY LAB ] → evidence · findings · experiments · candidate theories
>                              ↓
>                      DISCOVERED THEORY        ← the valuable output
>                              ↓
>                      FINAL KNOWLEDGEOS        ← a later phase
> ```

### 3B.0 ⭐ The laboratory is disposable — and what that forces

> ⛔ **If the code is disposable and the data is not, THE DATA FORMAT MUST OUTLIVE THE CODE.**

| Therefore | Rather than |
|---|---|
| self-describing, inspectable records | an ORM-shaped schema |
| formats readable **without** the program | formats requiring the program |
| fidelity · auditability · provenance · versionability · reproducibility | performance · reuse · elegance |
| ⭐ a lab that can be thrown away without losing a finding | a lab whose rewrite loses the research |

⚠️ This also **lowers the cost of being wrong** about any reversible decision, and **raises the cost of over-engineering** (§3B.7).

**Test:** delete the engine — every Phase-2 artifact remains readable and its provenance reconstructable.

### 3B.1 The architectural rule

> **The domain is THEORY RECONSTRUCTION AND THEORY CONSTRUCTION.**
>
> ⭐ **KnowledgeOS theory is an aggregate WITHIN that domain** — a research outcome: mutable, epistemically graded, replaceable. ⛔ **It is not the domain.**
>
> Persistence, LLM/ML, files and external tools are adapters.

**Why this is forced.** Every invariant the system must hold — provenance completeness · Step-1 immutability · L5 unreachable · rivals coexist · failed tests persist — is a **research-process rule**. **None is a KnowledgeOS-theory claim**; the theory's own invariants are exactly what is unknown. And under the rejected reading, a theory revision would be a **domain-model change** — a software rewrite — contradicting the replacement requirement.

#### The two invariants — non-negotiable

```
DOMAIN DEPENDS ON NO INFRASTRUCTURE OR TECHNOLOGY
EXTERNAL CAPABILITIES ENTER THROUGH EXPLICIT PORTS
```

**Dependency direction:** `adapters → ports → application → domain`. **The domain imports nothing.**

⚠️ **The decomposition below is ONE valid reading of those invariants, not the only one — revisable:** repositories and inference are *driven ports*; storage is an *adapter*; evidence is a *domain concept*; validation splits into **domain policy** / **application orchestration** / **external capability**.

```
  DRIVING ADAPTERS      CLI · batch runner · (later API/UI)
          ↓
  APPLICATION           orchestration only — no domain rules
          ↓
  DOMAIN                entities · value objects · services · POLICIES
          ↓ declares
  DRIVEN PORTS          SourceReader · *Repository · CandidateGenerator
                        SimilarityScorer · TestRunner · Clock · IdGenerator
          ↑ implemented by
  DRIVEN ADAPTERS       JSONL · (DB) · LLM · ML · P3A reader · math/stats engines
```

**The research process will change** — today `LLM → candidate extraction`; later `LLM + symbolic + ML + human review`; later still a theorem prover. ⛔ **The domain model must not change because the verification technology changed.**

### 3B.2 ⭐ Conceptual view vs dependency direction

**Researcher's conceptual view:**

```
RESEARCHER → EXPERIMENT WORKBENCH → THEORY LAB CORE
           → EXPERIMENT ENGINES → CORPUS / BASELINES
```

> ⛔ **This is NOT the dependency direction.**

| Conceptual layer | Hexagonal role |
|---|---|
| Researcher | driving actor |
| Experiment Workbench | driving adapter + application services |
| **Theory Lab Core** | ⭐ **the domain** |
| Experiment Engines | ⛔ **driven adapters** — the Core *declares ports*; engines *implement* them |
| Corpus / Baselines | driven adapters, behind ACLs |

⭐ **The inversion:** mathematical, statistical, ML/LLM engines, corpus readers and persistence are **external capabilities**. The stack diagram shows the Core depending downward on the engines; **in dependency terms that arrow inverts.** Otherwise changing an ML library changes the theory model.

### 3B.3 ⭐ The theory-neutral core

The domain contains concepts necessary for **the research process**, never concepts we merely *believe* belong to KnowledgeOS.

| ✅ Safe now — methodological | ⛔ Never hard-coded — research outcomes |
|---|---|
| `Evidence` `Source` `Event` `Claim` `Definition` `Assumption` `Proposition` `Derivation` `Relationship` `TheoryObject` `TheoryThread` `Gap` `Contradiction` `Obligation` `Test` `Experiment` `Finding` `Provenance` `TheoryEvolution` `Maturity` | *"KnowledgeOS has exactly N fundamental objects"* · *"knowledge is represented by X"* · *"the canonical structure is S"* · *"the theory is based on lattice / category theory"* · *"the fundamental relation is R"* · *"`T-0013` is the centre"* · *"the four bounded contexts"* |

#### ⛔ `TheoryObject` carries no `type` field

A single mutable enum presupposes **one** type, a **closed** set, and classification as an **intrinsic property** — ⛔ **all three falsified by the corpus.** `F0020` classifies `D-5` as *"a knowledge kind, NOT a bounded context"*, `D-7` as *"a context TYPE, not a context"*, and **splits** `D-6`; `F0024` then **changes** several; `T-0011` is `UNCERTAIN`.

**Three kinds, never conflated:**

| Kind | Example | Nature |
|---|---|---|
| **Structural identity** | *this record is a `Source`* | ⛔ intrinsic, not contestable |
| **Interpretive classification** | *`T-0016` is a Proposition* | contestable, evidence-backed, revisable |
| **Contested classification** | *`D-5` is a knowledge kind, **not** a bounded context* | two or more interpretive claims |

⭐ *"We do not yet know what this is"* = **zero interpretive classifications. No sentinel.**

⚠️ **The representation is REVERSIBLE** — a classification value-collection, classification-as-relationship, or a classification-assertion record all satisfy this. **The pilot decides.**

#### Further rules on the primitives

| Rule | Status |
|---|---|
| ⭐ **`Derivation` is n-ary; premises individually addressable** | ⭐ **EVIDENCE-FORCED** — `T-0016`: *"two of four grounds falsified"* (`F0024`, `DI-0009`) is unrepresentable with binary edges |
| ⭐ **The model must distinguish `NOT_SEARCHED` · `SEARCHED_AND_NOT_FOUND` · `ABSENT_BY_CONTENT` · `OUT_OF_WINDOW` · `UNCERTAIN`** | **distinction FORCED** (Step-1 §37 check 13); ⚠️ **form OPEN** — a `SearchRecord`, or `Event(kind=SEARCH)`. Either must carry scope · method · query · time · actor · corpus state · outcome · provenance |
| ⛔ **`Maturity` is never directly writable** | **prohibition FORCED** (`C-0007`: a false claim in **7 files** would be promoted by any corroboration-count path); ⚠️ **mechanism OPEN** — computed value object, policy result, or read model. Same for `evidence_strength` and every confidence figure |
| ⭐ **`TheoryThread` is a provisional research ENTITY** | ⛔ *An earlier proposal to demote it to a projection is **WITHDRAWN — refuted by executed test**: thread fields derivable from edges **4 of 11**; thread members appearing as edge endpoints **0 of 12**. Edges connect FILES; members are OBJECTS — there is no object-graph to project from. And `identity_basis` is a recorded human judgement, the content that kept `TH-0003` `UNCERTAIN`.* |
| ⚠️ **`Assertion` · `Competition` · aggregate roots · bounded contexts** | ⛔ **NOT admitted.** `Assertion`: 3 of 6 boundaries unstatable — test **composition** (an `Asserted` trait on distinct types) instead. `Competition`: over-unifies three different corpus shapes. `TheoryObject` as aggregate root: **no invariant requires it**. Bounded contexts: only 2 of 4 demonstrated |

#### ⚠️ Iteration-1 representation needs — derived, revisable, NOT a model

```
Source · Event · Evidence · TheoryObject · Relationship
Derivation · TheoryThread · Gap · Verification · Provenance
(+ TheoryEvolution, Obligation, Maturity as first-class records)
```

> ⛔ **These are what construction over F0001–F0025 actually required. They are not a settled ontology, and no later iteration is obliged to fit them.**
>
> ⚠️ **Under `READ → CONSTRUCT → IMPLEMENT`, representation is DERIVED from construction, never specified ahead of it.** A kernel fixed in advance decides what can be represented, and therefore what can be found.
>
> ⭐ **`OQ-L4` is the central empirical question of Phase 2B: does this set survive iteration 2, or does construction demand primitives we have not imagined?**

**How it was derived** — an adequacy diagnostic over the Step-1 registries found two primitives the first candidate set could not hold: **`Relationship`** (19 edges; an edge is not a node) and **`Event` distinct from `Source`** (8 of 25 files carry >1 typed date event; `OC-0003` proved a file occupies multiple positions). A third concept, `BaselineComparison` (P3A), was found to belong in an **adapter**, not the domain.

⚠️ **The diagnostic's coverage figure is NOT a measure of architectural quality and must never be quoted as one.** Its unit was JSONL rows, which are heterogeneous; several apparent gaps collapsed to one missing primitive; the mapping was the reviewer's; and a richer use of existing primitives could have covered some. ⭐ **What it legitimately established is the two missing primitives. That is the finding.**

### 3B.4 Representational adequacy — a standing laboratory experiment

> **If the expert reconstruction can state something the model cannot represent, that is a FINDING — ⛔ not automatically a programming problem.**

```yaml
representational_gap:
  id:                     # RG-####
  iteration:
  research_statement:     # what the research said, verbatim
  current_representation: # what the model can hold today
  missing_capability:
  why_insufficient:       # why existing structures do not suffice
  fault_class:            # PROTOCOL | CONCEPTUAL | ARCHITECTURAL
                          # | IMPLEMENTATION | HUMAN_JUDGEMENT
  proposed_experiment:
  resolution:             # OPEN | RESOLVED | ACCEPTED_LIMITATION
```

⛔ **The model is never silently changed.** A gap is recorded, classified, and resolved by an explicit act.

⚠️ **`HUMAN_JUDGEMENT` is not a defect.** The worked example — *"`T-0016` was reformulated because two of four grounds were falsified by `F0024`"* — yields `test = none, a human judgement`. That is correct behaviour, not a missing feature. ⭐ It also yields one genuine `CONCEPTUAL` gap: **premises must be individually addressable**.

**Repeat at every kernel change and every iteration.**

### 3B.5 Domain model ≠ record schema

**Six questions, answered separately for every object. Collapsing them is how a schema becomes an ontology.**

| Layer | Question | Example — `TheoryObject` |
|---|---|---|
| conceptual research object | what does the research mean by it? | a thing the corpus theorizes about |
| domain role | entity / value / service / policy? | **entity** — ⛔ not an aggregate root |
| identity | what makes two the same? | ⚠️ **unresolved** — §19A's six criteria are human judgement |
| lifecycle | how does it change? | by third-party assertion, never self-mutation |
| persistence representation | how is it stored? | one JSONL record *(pilot)* |
| projection / read model | how is it queried? | thread views, distance dimensions |

⛔ **A change at the persistence layer is never a change to the conceptual object.**

### 3B.6 Architectural invariants

| # | Invariant | Executable test |
|---|---|---|
| **I-1** | Step-1 artifacts immutable | run engine; `git diff ../` empty |
| **I-2** | Step-2 is an overlay | every record references Step-1 by id; **zero copies** |
| **I-3** | Every derived item has a reconstructable provenance chain | no item with an empty early chain |
| **I-4** | ⭐ **The Phase-2 bounded context has no operation capable of producing L5** | *(⛔ not "no L5 anywhere" — that would block Phase-3/4 canonicalization)* |
| **I-5a** | ⭐ **No ordering may silently carry epistemic standing** | analytical and research-priority ordering are **legitimate** |
| **I-5b** | ⭐ **Canonical selection requires an explicit `AuthorityAct`** | promotion fails without a recorded human act |
| **I-6** | Competing formulations coexist | two rivals persist across a full run |
| **I-7** | Absence of classification representable **as absence** | zero-classification object round-trips |
| **I-8** | Event ≠ Source | a source with 3 events yields 3 order positions |
| **I-9** | Relationships first-class where required | a relationship carries provenance and a version |
| **I-10** | Provenance survives transformation | chain intact after every application service |
| **I-11** | Failed tests cannot disappear | `FAILED-TESTS` append-only |
| **I-12** | Theory evolution append-only | old formulation byte-identical after revision |
| **I-13** | External comparison ≠ domain truth | P3A enters through an ACL, tagged `EXTERNAL` |
| **I-14** | ⭐ **No machine-generated *inference* acquires epistemic authority by virtue of being machine-produced** | see the six provenance classes below |
| **I-15** | Maturity never directly writable | no setter exists |
| **I-16** | `ABSENT_BY_CONTENT` requires a recorded search | — |

#### ⭐ Six provenance classes — `asserted_by` is mandatory and non-defaultable

```
SOURCE_ASSERTION         the file itself says it        — deterministic
DETERMINISTIC_DERIVATION reproducible transformation    — no judgement
MACHINE_PROPOSAL         LLM/ML inference               ⛔ no authority
HUMAN_ASSERTION          a person asserts it
VALIDATED_FINDING        survived an executed test
GOVERNANCE_ACT           ⛔ not available to Phase 2
```

⛔ A `MACHINE_PROPOSAL` cannot reach L3+ without a `HUMAN_ASSERTION` or `VALIDATED_FINDING` referencing it. ⚠️ *"Only humans assert"* would be **too strong** — *"this file contains definition X"* is a deterministic extraction needing no human act.

#### Anti-corruption boundaries

| Source | Enters as | Enforcement |
|---|---|---|
| **P3A** | `EXTERNAL_BASELINE`, never evidence | adapter; cannot mint a domain claim |
| **LLM / ML** | `PROPOSAL`, level L2 | ⭐ **the port's return type is `Proposal`** |
| **External research** | `EXTERNAL_CLAIM`, not Track-A | tagged at ingest |
| **Step-1 records** | `EVIDENCE_INPUT` — read-only | repository exposes **no write method** |

⭐ **Type-level enforcement beats policy.** If only a human act converts a `Proposal` into a domain claim, `I-14` cannot be violated by accident.

### 3B.7 ⛔ Do not over-engineer the laboratory

> ## **The correct architecture is the SMALLEST one that faithfully supports READ → CONSTRUCT → IMPLEMENT → TEST.**

| ⛔ Do not introduce | Why |
|---|---|
| microservices · databases · graph databases · frameworks | the pilot is 193 rows |
| fixed package taxonomies | names are deliberately deferred |
| final aggregates · final ontology | adjudicated **PREMATURE** |
| production scalability | a Phase-4 concern about an engine whose model is not yet validated |

⭐ **The pilot is deliberately small. Its purpose is to discover what the laboratory needs — and over-engineering destroys that measurement**, because a model rich enough to hold anything reveals nothing about what was actually required.

**Persistence for the pilot: JSONL** — 193 rows; append-only matches `I-11`/`I-12`; the audit trail becomes the VCS; Step 1 is already JSONL. ⚠️ **A pilot implementation decision, not architectural truth.** The rule is: **persistence must not leak through the port** (no line numbers as identity, no file-order dependence).

**Package structure** — `domain/ application/ ports/ adapters/ bootstrap/`, or by bounded context. ⛔ **Names are NOT frozen; the pilot tests the boundaries first.**

### 3B.8 The four laboratory experiments

| Test | Requirement | Live case |
|---|---|---|
| ⭐ `EMERGENT_DISCOVERY_TEST` | a discovery unanticipated by Step 1, Step 2, the schema, the taxonomy or the architecture is representable **without modifying the software core** | `T-0023` — assembled from five files, **none of which states the counter orders anything** |
| ⭐ `THEORY_REPLACEMENT_TEST` | B replaces A **without** deleting A, mutating A into B, rewriting provenance, changing Step 1, or corrupting derivations. *A existed · had evidence · was rejected · B emerged later* stay recoverable | `IFR-0012` — `F0020` stamped a falsification and **left the body unchanged** |
| ⭐ `THEORY_SPLIT_TEST` | `A → {B, C}` with A's identity preserved and split provenance recorded | `T-0015` — premise and conclusion refuted separately (`DI-0008`) |
| ⭐ `THEORY_MERGE_TEST` | both historical objects preserved, later relationship recorded; ⛔ **IDs never merged** | `TH-0003` — held `UNCERTAIN` to avoid a premature merge |

#### `THEORY_NON_DETERMINATION_TEST`

Construct rivals where **all nine** heuristics favour A (more records · more evidence refs · encountered first · higher similarity · fewer contradictions · more supporting documents · more recent · higher ML probability · easier to formalize). Assert: all nine are **reported**; ⛔ **no output labels A as standing higher**; neither status changes; promotion fails without an `AuthorityAct`.

| Ordering | Permitted? |
|---|---|
| **Analytical** (*similarity 0.82 vs 0.76*) | ✅ a measurement |
| **Research-priority** (agenda ranking) | ✅ needed by §13 |
| **Epistemic ranking** | ⚠️ only with stated semantics, never as standing |
| ⛔ **Canonical selection** | ⛔ **`AuthorityAct` required** |

⚠️ **The window supplies the real trap:** a rival with *more evidence but more contradictions* versus one with *less evidence and none*. Any scoring function resolves that silently — and thereby becomes the theory.

### 3B.9 The firewall

```
                    PHASE 2
          ┌────────────┴────────────┐
   Research / theory            Software
     construction              construction
          └────────────┬────────────┘
                  comparison  (§3A.5)
                       ↓
              protocol AND model refinement
```

> **The software supports the research. ⛔ It must never silently decide it.**

---

## 4. ⭐ Deliverable 1 — `HISTORICAL-STORY.md`

> ### ⛔ **This is history, not theory. It is written in Phase C, before any synthesis, and never revised by later phases.**

### 4.1 What it must be

**A coherent narrative of how the ideas evolve across F0001–F0025** — what was introduced, changed, connected, challenged, refuted, and left unresolved.

| ⛔ It must NOT be | ✅ It must be |
|---|---|
| 25 file summaries | a narrative organized by **idea**, following threads across files |
| a chronology of documents | a chronology of **moves**: a claim made, tested, conceded, dissolved |
| a smoothing of the record | explicit about reversals — `IFR-0012`'s banner-vs-body disagreement stays visible |
| interpretation | **quotation and sequence**; interpretation belongs in §5 |

### 4.2 Required narrative elements

| Element | Recorded as | Window example |
|---|---|---|
| **Concept introduced** | first appearance + file + verbatim | `T-0014` authority = provenance × standing, F0018 |
| **Concept changed** | before/after + the event that changed it | `T-0016` D-2 Strong → MEDIUM (F0024) |
| **Concepts connected** | the edge + where it is stated | `R-0016` F0018 → F0016 |
| **Claim challenged** | challenger + ground | `F0024`'s four falsifiers |
| **Claim refuted** | refutation + **whether it propagated** | `C-0007`: falsified once, propagated to **zero** files |
| **Question dissolved** | the question + why it was ill-posed | `LG-1` → `PM-1`/`PM-2` (`R-0015`) |
| **Left unresolved** | the open question + who can close it | `HA-0007` — governance only |
| **Self-correction** | the in-place revision | `C-0006`: F0019 refutes its own correlation |

### 4.3 Structural rules

1. **Order by the event partial order**, not by file id or date. Where two events are incomparable, say so — do not impose a sequence.
2. **Every narrative claim carries a file + location.** The story is L0/L1 only.
3. ⛔ **No L2 statement may appear.** If the narrative needs a connecting idea no file states, that idea belongs in the seed and the story records the *gap* instead.
4. **Reversals are kept.** The story of F0020 includes both its original `D-3: STRONG` and the banner downgrading it to `WEAK`, in that order, with the disagreement visible.

> **Why separation is enforced.** `F0019`'s own narrative was revised *after the fact* to soften its framing, and the first form is now **unrecoverable** (`IFR-0010`). A story that silently absorbs later interpretation loses exactly what makes it evidence.

### 4.3b ⭐ Re-READ bias control — iteration 2 onward

> ⚠️ **From iteration 2, reading happens while a candidate theory is already held. That is confirmation bias.**

Re-READ is permitted **only under a named obligation**, and must record:

```yaml
reread:
  iteration:
  obligation:             # which RO / RG required it
  theory_state_at_read:   # what was believed — declared BEFORE reading
  expected_finding:       # ⭐ stated BEFORE reading
  actual_finding:
  confirmed_expectation:  # true | false
```

⭐ **A re-READ that only ever confirms expectations is a finding about the reader**, not about the corpus, and is reported in `SURPRISE-DISCOVERIES`.

**Precedent:** Step-1 batch 002 made falsifiable predictions about P3A **before** reading and confirmed 4/4. That is exactly why the result carries weight — and why the prediction had to come first.

### 4.4 ⭐ The story may read like a chapter — it may not read like the book

`HISTORICAL-STORY.md` is written as **continuous prose organized by idea**, so a human can follow how a concept emerged. That much is intended:

> *"The earliest corpus evidence does not define the promotion mechanism explicitly. F0018 arrives at it only after inventorying ten progression mechanisms against seven questions each — and states it as something the repository had already practised three times without naming."*

⛔ **But every claim carries its file and its epistemic status.** The distinction from the Phase-3 book:

| `HISTORICAL-STORY.md` (Phase 2) | The book (Phase 3) |
|---|---|
| *"F0018 arrives at… F0024 challenges…"* | *"Promotion requires both an evidential threshold and a governance act."* |
| chronology **drives** the text | chronology is **absent** from the main narrative |
| provenance **inline** | provenance in a **footnote / appendix / audit layer** |
| epistemic status on **every claim** | claims stated as theory, with status recoverable on request |

> ⛔ **Do not write Phase-3 prose here.** A sentence that reads *"A Knowledge Object consists of…"* belongs to the book; in the story it must read *"F0042 introduces… and F0087 changes it in three ways."* **The tell is whether the sentence could survive having its corpus references deleted.** If it could, it is exposition, and it is premature.

---

## 4A. ⭐⭐ The emergent-theory mechanism — where theory is actually constructed

> ### **This is the protocol's theory-construction operation.** Everything else records, separates or tests. **This is where Claude thinks.**

> ## ⛔ **These record RESEARCH DISCOVERIES. They are not, and do not become, components of the final theory.**
>
> A `NEW_CONCEPT` is *a concept this reconstruction proposes* — **not** *a concept KnowledgeOS contains*. Promotion from discovery to theory component requires the full ladder and, ultimately, a governance act Phase 2 cannot perform.

### 4A.1 The ten emergent record kinds

⛔ **A discovery is never forced into the Step-1 taxonomy — that taxonomy is itself under test.** `TAXONOMY_INADEQUATE` exists precisely so that "the categories are wrong" is a recordable result rather than a silent distortion.

| Kind | Records | Window example / candidate |
|---|---|---|
| `NEW_CONCEPT` | a concept no Step-1 object captures | — |
| `NEW_RELATIONSHIP` | a relation no file states and no Step-1 edge records | — |
| `ABSTRACTION` | several objects are instances of a more general one | `T-0014` + `HA-0008` may both instance "a field conflating two questions" |
| `UNIFICATION` | previously separate objects are **one** | `T-0013` and `T-0017` may be one splitting/joining principle |
| `SPECIALIZATION` | an object is a special case of another | `T-0022` nesting ⊂ a general containment |
| `DECOMPOSITION` | one object is really several | ⭐ `T-0015` — its premise and conclusion were refuted separately (`DI-0008`) |
| `COMPETING_INTERPRETATION` | a rival reading of the same evidence | feeds Phase E |
| `REJECTED_INTERPRETATION` | a reading considered and **abandoned**, with the reason | ⭐ **mandatory to record** — see 4A.4 |
| `EMERGENT_PRINCIPLE` | a principle visible only across objects | `dissolution-by-checking`, seen 3× (`DI-0014`) |
| `EMERGENT_STRUCTURE` | a formal structure found in synthesis | feeds Phase F.1 |
| `TAXONOMY_INADEQUATE` | ⛔ the Step-1 categories cannot hold this | a finding about Step 1, routed to obligations |

### 4A.2 Record schema

```yaml
emergent:
  id:                  # EM-####
  kind:                # one of the eleven above
  statement:
  level:               # L2 | L3 | L4 — never L5
  provenance:          # §14 chain — MANDATORY
  identity_basis:      # for UNIFICATION / ABSTRACTION: §19A six criteria, EVIDENCED
  supersedes: []       # objects/threads this replaces or reorganizes
  falsifiable_as:      # §12 typed, or FALSIFIABILITY_NOT_YET_SPECIFIED
  surprise:            # true if not anticipated by Step 1 or the protocol (§16)
```

### 4A.3 Thread merge and split

Threads are **Step-1 hypotheses about grouping, not fixed structure.** Step 2 may `UNIFICATION`-merge or `DECOMPOSITION`-split them when identity evidence warrants — recorded in the **overlay**, never by editing Step-1 (`Q17`).

**Immediate case:** `TH-0003` is `UNCERTAIN` — F0001's P1/P2 and F0010's two blocker classes look like the same distinction reached from different directions, but neither file says so. v2 had **no operation able to resolve it in either direction.** Now it does, and either outcome is a legitimate result.

### 4A.4 ⭐ Abandonment is a first-class result

> **Claude may abandon a promising formulation.** Recording `REJECTED_INTERPRETATION` with the reason is a **success**, not a failure to deliver.

`F0019` declined to produce a fourth taxonomy and said so; `F0014` withdrew `LG-1` as ill-posed; `F0018` conceded its own question was solution design. **Three of the corpus's strongest moves are refusals.** A protocol that only recorded positive findings would have lost all three.

---

## 5. Deliverable 2 — `THEORY-SEED.md`

> ## ⛔ **CONSTRUCTED does not mean TRUE.**
>
> Construction produces **candidates**. A constructed formulation may later be **supported · weakened · refuted · split · merged · replaced · abandoned** — and every one of those is a legitimate laboratory result, **not a failure**.
>
> ⭐ **Abandonment is a result** (`REJECTED_INTERPRETATION`, §4A.4). Three of the corpus's strongest moves are refusals: `F0019` declined a fourth taxonomy, `F0014` withdrew `LG-1` as ill-posed, `F0018` conceded its own question was solution design.

The seed answers **nine questions**, each item levelled, provenance-linked and falsifiable. *"Not determinable from F0001–F0025"* is a valid, valuable answer.

| # | Question | Field | What the window supplies |
|---|---|---|---|
| **1** | Core problem KnowledgeOS solves | `core_problem` | `T-0019` (governance platform, not ontology platform) + `T-0015` (unreachability). ⚠️ both L2/L3; `T-0019` is corpus-declined (§7) |
| **2** | Fundamental concepts | `concepts[]` | `T-0014` · `T-0008` PKS · `T-0017` protocol vs records · `T-0022` nesting |
| **3** | Relationships | `relations[]` | 19 edges · `HA-0006` contexts · `HA-0008` progression kinds |
| **4** | Definitions · assumptions | `definitions[]` `assumptions[]` | typed per object and per derivation |
| **5** | Mathematical / logical structures | `structures[]` | 5 candidates — §9, **staged F0–F3** |
| **6** | Mechanisms forming | `mechanisms[]` | `T-0013` — strongest |
| **7** | Strongly supported | `supported[]` | `TH-0006` · `TH-0008` · `HA-0008` |
| **8** | Hypotheses | `hypotheses[]` | `HA-0007` · `T-0019` · `T-0015`'s widened cause |
| **9** | Missing / unresolved | `open[]` | 11 gaps · 16 obligations · `C-0007` |

### 5.1 Seed item schema

```yaml
seed_item:
  id:                    # SI-####
  answers_question:      # 1..9 — which seed question this serves
  level:                 # L2 | L3 | L4 — never L5
  statement:
  maturity:              # M0..M4 (§11)
  provenance:            # §14 — the full chain, mandatory
    step1_evidence: []   # file ids + verbatim locations
    theory_objects: []   # T-/HA-/TH- ids
    synthesis_reasoning: # why these compose; the identity basis
    verification: []     # VF- finding ids
    tests: []            # TEST- ids + results
  evidence_strength:     # STRONG | MEDIUM | WEAK | CONTESTED
  strength_check:        # statement strength <= evidence strength
  assumptions: []
  competing_with: []
  discriminator:
  provisional_reason:
  falsifiable_as:        # MANDATORY, non-empty
  supersedes: []
```

### 5.2 ⭐ Falsifiability — typed, and honestly absent when absent

`falsifiable_as` takes **one of eight typed values**, matched to §13's test types:

`CORPUS_FALSIFIER` · `MATHEMATICAL_COUNTEREXAMPLE` · `STATISTICAL_FALSIFIER` · `LOGICAL_COUNTEREXAMPLE` · `COMPUTATIONAL_TEST` · `ARCHITECTURAL_COUNTEREXAMPLE` · `EXTERNAL_EMPIRICAL_TEST` · ⭐ `FALSIFIABILITY_NOT_YET_SPECIFIED`

> ### ⛔ **Do not manufacture a weak falsifier to satisfy the schema.**
> v2's `Q2` demanded a non-empty falsifier for every item — which **forces fabrication** exactly where the item is least well understood. `FALSIFIABILITY_NOT_YET_SPECIFIED` is now an honest, countable answer, reported in **D4/D8** of the distance assessment. *(v2 defect, corrected.)*

### 5.3 ⭐ The anti-overfitting rule

> ## **The F0001–F0025 seed is a hypothesis ABOUT the corpus — not a template later files must fit.**

Later files **must** be able to:

| confirm the seed | extend it | **split** it | **contradict** it | **replace** it |
|---|---|---|---|---|
| reveal apparently separate threads are **one** | reveal an apparently unified theory is **several** | | | |

⛔ **Seed churn is a measurement, not a failure.** A seed that never changes across 3,056 files has almost certainly overfitted its first 25. Churn and conceptual replacement are recorded in `THEORY-EVOLUTION.jsonl` (§15.2) and reported as **D1/D6** — never as defects.

**Evidence this is the real risk:** the window is **2 days of one programme's output** (2026-08-02 to 08-04), with 9 of 25 files sharing a single date. It is a narrow, highly correlated sample. Treating it as representative is the most likely way this reconstruction goes wrong.

---

## 5A. ⭐⭐ The PRIMARY output — `CANDIDATE-KNOWLEDGEOS-THEORY.md`

> ### ⛔ **The objective of Phase 2 is NOT to produce more machine-readable records.**
> ### ⭐ **It is to progressively construct an increasingly coherent, provenance-backed, falsifiable Candidate Theory.**
>
> The registries exist to make that theory **auditable · reproducible · traceable · falsifiable · mathematically checkable · experimentally testable**. ⛔ **They are supporting infrastructure. They are not the theory.**

### 5A.1 The architecture — JSON is subordinate

```
CANDIDATE-KNOWLEDGEOS-THEORY.md     ← the intellectual product; a human reads THIS
              ▲ supported by
      Theory Object Registry         ← audit substrate
              ▲ supported by
       Evidence Records
              ▲
            Corpus
```

### 5A.2 ⛔ The failure mode this section exists to prevent

**Observed in Iteration 1.** The run produced 32 artifacts, 11 seed items, 6 structures, 5 competitions, 7 emergent constructions, maturity states and checkpoints — **and no document answering *"so what is KnowledgeOS?"***

> ⭐ **If Phase 2 produces more machine-readable records without periodically producing a coherent human-readable theory, the methodology has become an information-management system rather than a theory-construction process.**

⛔ **A checkpoint may not be declared complete while the candidate theory is out of date.**

### 5A.3 Requirements

| # | Requirement |
|---|---|
| 1 | ⭐ **Readable start to finish without opening a single JSON file** |
| 2 | ⛔ **Opens in business language** — not with IDs, schemas or metadata. *What is it · what problem · what mechanism · what does it produce* |
| 3 | ⛔ **No new theory.** Built only from evidence and constructions already produced |
| 4 | ⭐ **Gaps say `UNKNOWN` or `NOT YET ESTABLISHED`** — ⛔ never filled to look complete |
| 5 | **Every important statement carries an epistemic level** (§5A.4) and traces to the registries |
| 6 | **A conceptual graph built FROM the evidence**, every arrow status-labelled — ⛔ never drawn because it looks tidy |
| 7 | **Falsifications are prominent**, including the model's own demoted constructions |
| 8 | **Ends with what is unknown and what must happen next** |
| ⭐ 9 | **States its PRIMITIVE concepts** — what is taken as undefined, *before* anything is defined from it |
| ⭐ 10 | **Every structure it states carries a DDD interpretation and a COMPUTATIONAL interpretation** — what it means for the domain model, and what it would mean to compute. ⛔ *A structure with neither is notation, not theory* |
| ⭐⭐ 11 | **THREE REPRESENTATIONS, never collapsed** *(see 5A.3a)* |

#### ⭐⭐ 5A.3a — the three representations

> **Scientific theory ≠ knowledge representation ≠ machine representation.**

| # | Representation | Contains |
|---|---|---|
| **1** | ⭐ **the scientific theory** — *this document* | primitive concepts · definitions · assumptions · axioms *(only where genuinely required)* · formal structures · logical rules · propositions · derivations and **proofs where the structure admits one** · counterexamples · statistical model and estimand *(where statistics apply)* · DDD interpretation · competing formulations · falsification conditions · computational interpretation · provenance · epistemic status · known limitations |
| **2** | **the knowledge representation** | Theory Objects · definitions · relations · derivations · propositions · dependencies · evidence · provenance · epistemic status |
| **3** | **the machine representation** | JSON · schema · graph · executable rules · algorithms · code · ML features · validators |

> ### ⛔ **2 and 3 are GENERATED FROM 1. They are views of the theory, never substitutes for it.**
>
> ⭐ **Why this is stated rather than assumed:** an `[E]` or `[T]` formulation must carry *reasoning · assumptions · alternatives · consequences · falsification conditions* (`Q52`). ⛔ **None of those fields can be expressed in representation 2 or 3.** A machine record of an `[E]` structure that omits its justification is not a compressed theory — it is an unjustified assertion with a schema.
>
> ⚠️ **This adds no job, no level and no gate.** It names what §5A.2's failure mode is *about*, and extends 5A.3's existing requirements.

### 5A.4 The six levels — ⛔ never collapsed

| | Level | Meaning |
|---|---|---|
| `A` | **Corpus fact** | the source explicitly says it |
| `B` | **Historical reconstruction** | assembled from several source records |
| `C` | **Candidate theory** | our coherent interpretation of the evidence |
| `D` | **Formal result** | survives mathematical or logical analysis |
| `E` | **Empirical result** | an experiment has actually tested it |
| `F` | **Canonical** | ⛔ **never produced by Phase 2** |

⭐ **Orthogonal to these, and never collapsed into them: the five REALIZATION LAYERS (§13A)** — semantic · structural · behavioral · operational · empirical. *Epistemic level asks how well we know it; realization layer asks how far it is actually real.*

**Arrow statuses for the graph:** `SOURCE_SUPPORTED` · `RECONSTRUCTED` · `CANDIDATE` · `FORMALLY_SUPPORTED` · `EMPIRICALLY_SUPPORTED` · `CONTESTED` · `FALSIFIED` · `UNKNOWN`.

⛔ **Do not claim mathematical or empirical validity unless the corresponding validation actually exists.**

### 5A.7 ⭐ Traceability without turning the theory into a database dump

> ⚠️ **`Q42` (readable without JSON) and `Q60` (every item traceable) pull in opposite directions.** Resolved by **separating body from trace**:

| Layer | Carries | Identifiers? |
|---|---|---|
| **Body** | the theory, as prose | ⛔ **no** — identifiers degrade reading |
| ⭐ **Trace appendix** | `seed item → theory section → origin → status` | ✅ **yes — mechanically checkable** |

⭐ **The set difference `{seed items} − {items in the trace}` must be empty**, or each difference recorded `DELIBERATELY_OMITTED` with a reason. **One line to check; it would have caught all three dropped items.**

### 5A.5 Companion documents

| Artifact | Purpose |
|---|---|
| ⭐ `CANDIDATE-THEORY-SNAPSHOT.md` | *"If I have 10 minutes, what do I need to know?"* — definition · mechanism · concepts · relationships · strongest evidence · falsifications · biggest open question · next step |
| ⭐ `THEORY-CHANGELOG.md` | per version: what changed · why · the evidence responsible · whether it **strengthened / weakened / split / merged / falsified** · affected objects and open questions. ⛔ **History is never rewritten** |

### 5A.6 ⭐ Consolidation is a first-class act

> **When the machinery has outrun the theory, STOP EXPANDING THE CORPUS AND CONSOLIDATE.**

Consolidation is **not** overhead between real work — it is the act that converts records into knowledge, and the only one that answers the question the whole exercise exists to answer.

---

## 5C. ⭐⭐⭐ Constructive theory RECOVERY — the corrected mental model

> ### ⛔ **The Candidate Theory is NOT the container the corpus is judged against.**
> ### ⭐ **The corpus is the source. The Candidate Theory is a provisional map of territory already explored.**

### 5C.1 The correction

⛔ **Wrong model** — *existing theory → does the new file fit? → add only compatible material.*

✅ **Correct model:**

```
COMPLETE CORPUS → theory RECOVERY → compare with current Candidate Theory
   → missing / partial / misrepresented / competing → CONSOLIDATE
   → expanded Candidate Theory → attack → revise
```

> **Phase 2 is neither passive transcription of the corpus nor unrestricted invention.**
> ## ⭐ **It is EVIDENCE-GROUNDED CONSTRUCTIVE THEORY RECOVERY:**
> **recover what the corpus established · construct what rigorous analysis shows is missing · validate what was constructed.**

⛔ **Permanent rule: the Candidate Theory is an open reconstruction hypothesis, not a closed ontology.** When a file contains a concept absent from it, ⛔ **do not** classify it non-theoretical by default. **First ask: is this previously-developed theory our reconstruction has not yet recovered?**

### 5C.2 ⭐ THREE AXES — and a conflation in the old ladder, now resolved

The protocol previously ran one A–F ladder. ⚠️ **Its first two entries were ORIGIN labels sitting inside a STRENGTH ladder.** Three orthogonal axes, never collapsed:

| Axis | Values | Asks |
|---|---|---|
| ⭐ **ORIGIN** | `[C]` corpus-derived · `[S]` corpus-synthesized · `[E]` expert-derived · `[T]` test-derived *(added 2026-09-23)* | **where did this statement come from?** |
| **STRENGTH** | candidate → formal → empirical → ⛔ canonical | how well do we know it? |
| **REALIZATION** (§13A) | semantic · structural · behavioral · operational · empirical | how far is it actually real? |

⛔ **An `[E]` never silently becomes a `[C]`** unless evidence genuinely establishes the corpus contained it.

⛔ **`[T]` is an ORIGIN value, not a STRENGTH value.** It says a formulation was *introduced or modified because of* an executed experiment or validation finding. It does **not** say the formulation survived a test: it enters at **L2** and reaches L4 only through its own named test (§1.1). When a test changes an existing formulation, the original keeps its own origin and level, and the changed formulation is a new `[T]` record that references it.

### 5C.3 Expert completion — permitted, never free

⭐ **Claude MAY add a missing component when expert analysis requires it.** ⛔ **But expert judgement is not evidence.** Every `[E]` addition records:

**what is missing · why the theory cannot be coherent/typed/meaningful without it · which corpus objects motivate it · the mathematical / logical / statistical / DDD reasoning · the alternative if the theory could stand without it · assumptions introduced · what would falsify it · status `EXPERT-DERIVED CANDIDATE`.**

**`[T]` results record the same fields**, plus **the test or experiment id, its executed outcome, and the formulation it modifies (by id)**. The full minimum list for `[E]` and `[T]` is in the Senior Researcher Role section, *Origin of a Phase-2 formulation*.

**Completion categories:** `MATHEMATICAL` · `LOGICAL` · `STATISTICAL` · `DDD` · `ARCHITECTURAL` · `COMPUTATIONAL` · `SEMANTIC` · `EMPIRICAL` completeness.

### 5C.4 ⛔ SEARCH BEFORE CONSTRUCTING

```
SEARCH → RECONSTRUCT → COMPARE → confirm genuinely missing → ONLY THEN expert-derive
```

⛔ *"Not found"* has **eight** possible meanings — never developed · developed implicitly · exists under another name · in another thread · developed later · assumed but unformalized · genuinely absent · historically absent but necessary. **Distinguish them.**

> ⭐ **This rule has now paid twice.** A promotion threshold was invented, then found *forbidden*. An instrument contract was called a violation, then found to be **conformance with a different standard**.

### 5C.5 Constructive operations — all provenance-backed and reversible

`ADD` · `EXPAND` · `REFINE` · `SPLIT` · `MERGE` · `GENERALIZE` · `SPECIALIZE` · `FORMALIZE` · `OPERATIONALIZE` · `TYPE` · `REFACTOR` · `REPLACE` · `DEMOTE` · `PRESERVE AS COMPETING`.

⭐ **Recovery may restructure the Candidate Theory itself** — not merely append paragraphs to it.

### 5C.6 ⛔ Two dumping grounds, both forbidden

| ⛔ Failure | Rule |
|---|---|
| *"looks architectural → classify architecture → exclude from theory"* | **Ask instead: does this encode a reusable invariant, mechanism, semantic distinction, mathematical relation, governance principle or explanatory model?** Architecture and theory are related, not identical — ⭐ **the classification must be justified, never assumed from document kind** |
| *"Claude thinks X would be useful → add X"* | **Required: corpus evidence + identified gap + expert reasoning + necessity + alternatives + explicit status.** Nothing less |

### 5C.7 Recovery coverage ≠ theory quality

| Dimension | Asks |
|---|---|
| **RECOVERY COVERAGE** | how much already-developed corpus theory has been recovered? |
| **THEORY QUALITY** | how coherent · sound · supported · viable is it? |

⛔ **Never infer one from the other.** ⭐ **Coherence ≠ completeness; stability ≠ coverage.** A coherent theory after 25 of ~3,000 files implies **nothing** about recovery.

### 5C.8 When is recovery sufficient? — ⛔ never a file count

Major threads identified · major objects recovered · major branches investigated · major structures identified · contradictions preserved · later refinements incorporated · missing-theory candidates investigated · **no unexplained large theory-bearing regions** · remaining gaps explicitly known.

### 5C.9 Historical recovery survives falsification

> **Record both, always:** *"the corpus developed X"* **and** *"X is currently contradicted."*
> ⛔ **Never delete X from the historical reconstruction because validation later rejects it. Never preserve X as current truth because the corpus once asserted it.**

---

## 5B. ⭐⭐ Corpus Contribution Audit — the theory must COVER its corpus

> **A Candidate Theory can be internally coherent and still have ignored half its evidence.** The audit is what makes coverage demonstrable.

### 5B.1 The requirement

For every file in the audited range, classify each meaningful contribution as one or more of:

`THEORY-BEARING` · `CANDIDATE-THEORY-BEARING` · `EVIDENCE-BEARING` · `ARCHITECTURE-BEARING` · `GOVERNANCE-CONSTRAINT` · `IMPLEMENTATION-EVIDENCE` · `HISTORICAL/CONTEXTUAL` · `CONTRADICTION` · `FALSIFIER` · `VALIDATION-OBLIGATION` · `OPEN-QUESTION` · `NON-THEORETICAL/ADMINISTRATIVE`

⛔ **Not every sentence must appear in the theory.** The requirement is:

> ### **Every potentially theory-bearing contribution receives an explicit DISPOSITION.**

### 5B.2 Theory-coverage record

For each `THEORY-BEARING` or `CANDIDATE-THEORY-BEARING` contribution: **source file · location · claim · existing theory object (if any) · status · reason · theory section · follow-up obligation.**

**Status is one of:** `represented` · `partially represented` · `new` · `competing` · `contradictory` · `unresolved` · `correctly excluded`.

```
SOURCE FILE → CONTRIBUTION → THEORY OBJECT → CANDIDATE THEORY → VALIDATION OBLIGATION
```

### 5B.3 ⛔ Coverage is NOT theory inflation

> **Corpus coverage means every potentially theory-bearing contribution has a disposition. ⛔ It does NOT mean every corpus statement becomes theory.**

Migration maps, file counts, directory locations, deployment details, stage roadmaps and implementation mechanics normally stay **evidence or architecture material**, unless they reveal a genuine mechanism or invariant.

When a contribution is intentionally excluded, record **`EXCLUDED FROM THEORY` · reason · where it is preserved**. ⛔ **Never silently discard it.**

### 5B.4 ⛔ No theory by accumulation

> ## **KnowledgeOS theory does not emerge by accumulating observations. It emerges by identifying stable explanatory structures that survive provenance checks, semantic analysis, logical analysis, adversarial testing, implementation examination, and eventually mathematical or statistical validation.**

```
more files ≠ more theory      more JSON ≠ more understanding      more experiments ≠ better theory
```

**The objective is information gain and theoretical clarification — ⛔ not artifact volume.**

---

## 6. Competing and incomplete formulations

### 6.1 The rule

> ⛔ **Never reconcile. Register both, name the discriminator, and wait.**

`C-0007` — "0 traversals" asserted in **≥ 7 files**, falsified in **1**. Every tempting heuristic fails:

| Heuristic | Verdict | Why wrong |
|---|---|---|
| Majority of assertions | claim stands | repetition ≠ evidence; the 7 are plausibly one claim copied |
| Most recent wins | falsification wins | same date — the date cannot order them |
| Most authoritative wins | undetermined | all carry `Generated — never authoritative` |
| ⭐ **Discriminating observation** | **locate R-36/R-41/R-63** | the only method that can be **wrong in a checkable way** |

### 6.2 Outcomes — a closed set

| Outcome | Meaning | Example |
|---|---|---|
| `RESOLVED_BY_EVIDENCE` | discriminator found and executed | `TH-0008` |
| `OPEN_DISCRIMINATOR_KNOWN` | we know what would settle it, and where | `C-0007` → `RO-0008` |
| `UNDECIDABLE_ON_PRESENT_EVIDENCE` | no discriminator identified yet | `C-0008` |
| ⭐ `UNRESOLVABLE_BY_RECONSTRUCTION` | **only a governance act can settle it** | `HA-0007` |

> **The fourth outcome is the important one.** `HA-0007` cannot be closed by reading 3,056 more files. Classifying it correctly stops effort being burned on a question that is not an evidence question — and it is why **100 % resolution is not the target** (§12).

### 6.3 Incomplete formulations

Recorded `INCOMPLETE` with the **missing element named**. Never completed by inference.

**Evidence:** `T-0006` was left `UNDETERMINED` where its reference network was too thin, while `T-0008` — reference network *plus* source corroboration — was completed. **Two confidences, two treatments, both recorded.**

---

## 7. What must remain provisional

**Step 2 may not promote an item the corpus itself declined to promote.**

| Class | Rule | Evidence |
|---|---|---|
| **Corpus-declined** | a file marks it CANDIDATE and names a blocking act → seed carries it no higher | `T-0019`: refused because the mission (D-5) is unratified — *"adopting a positioning sentence before ratifying the mission would invert the order"* |
| **Governance-gated** | awaiting ARB/DA/sponsor | `PM-1`..`PM-5`, `LG-5`, `R-1`, `U-GR-1` |
| **Single-occurrence** | never promote methodology from n=1 | ES-006.1 |
| **Out-of-window-dependent** | the rule producing a verdict lies outside the window | `G-0008` |
| **Counter-dependent** | orderings resting on the occurrence counter | `RO-0010` |

> **A theory seed that adopts more than its corpus does is not a reconstruction — it is a new theory wearing the corpus's clothes.**

---

## 8. New evidence Step 2 must search for

| Priority | Target | Unblocks | Reachable? |
|---|---|---|---|
| **1** | `R-36`, `R-41`, `R-63` | `C-0007`/`TH-0005` — largest theoretical risk | ⛔ outside registry |
| **2** | `Round47-OP` nine admissible justifications | every bounded-context verdict | ⛔ `docs/architecture/` |
| **3** | Occurrence-counter continuity | every counter-based ordering | ✅ inside — cheap |
| **4** | `RQ-002` / K-19 meta-model | `C-0008`, `G-0009` | ⛔ `docs/implementation/` |
| **5** | `Round39-MC`, `Round38C-04`, `Platform_Capability_Pattern` | `T-0015`, `T-0016`, `T-0018` | ⛔ outside registry |
| **6** | EKS-01..56 backlog sweep | prior art; `RO-0015`/`RO-0016` | ✅ inside |

> ⚠️ **Scope escalation.** Four of six are unreachable under the current registry. Either scope widens or the seed permanently carries them as boundary conditions. **A governance decision, not a protocol one.**

---

## 9. ⭐ Formalization discipline — four stages

> ### ⛔ **v1 stated five formal structures while one had an undefined term. Elegance is not evidence.**

| Stage | Name | Requirement | May be called |
|---|---|---|---|
| **F0** | **Observed structural pattern** | a regularity visible in the corpus | *"a pattern"* |
| **F1** | **Candidate formal structure** | a proposed formalism; **terms may be open** | *"a candidate structure"* |
| **F2** | **Formal proposition** | **every term defined**, conditions stated, well-formed | *"a proposition"* |
| **F3** | **Validated proposition** | F2 **plus** a §13 test executed and survived | *"validated"* |

**Promotion rules:** F0→F1 requires a stated formalism. **F1→F2 requires every defining condition closed** — an open term caps the item at F1 permanently until closed. F2→F3 requires an executed test.

### 9.1 The five structures, correctly staged

| # | Structure | Form | Source | ⭐ Stage |
|---|---|---|---|---|
| **S-1** | Promotion as a conjunction over two independent orders | `promote(k) ⟺ evidence(k) ≥ bar ∧ ∃a: grants(a,k)` | `T-0013` | ⛔ **F1** — `bar` is undefined (3 incompatible candidates: `ES-006.1` n≥2 · `P-4`'s 5-point scale · `CAP-001`). **Cannot be F2 until `OQ-9` closes** |
| **S-2** | Authority as a product | `authority = P × S`, P constant, S a state machine | `T-0014` | **F1** — whether product/lattice/two-fields is open (`PM-1`) |
| **S-3** | The splitting rule | if `owner` is not a function over a concept's parts, the concept is not atomic | `T-0017` | **F1** — generality untested (`OQ-4`) |
| **S-4** | Recursive containment | a Knowledge Space contains Knowledge Spaces | `T-0022` | **F1** |
| **S-5** | Monotone counter as an order | a global monotone counter induces an order finer than dates | `T-0023` | **F1** — rests on `RO-0010` |

> ### ⛔ **Nothing in the window reaches F2. Not one structure.**
> That is the honest state after 25 files, and stating it is more valuable than five elegant formulas with open terms. *(v1 presented all five as though they were propositions — the defect this section exists to prevent.)*

### 9.2 The formalization rule

> **Formalize only where the corpus already reasons formally.** `T-0013`/`T-0014` were *derived* by their sources (10 mechanisms × 7 questions; a schema's own two-question header). `T-0015` was not — its premise was refuted (`DI-0008`). **Formalizing `T-0015` would lend it rigor its evidence does not support.**

---

## 10. Expert verification — MANDATORY

**The seed is not a deliverable until verified.** Verification does not ask *"does this look reasonable?"* but whether the theory is **internally coherent, sufficiently defined, logically expressible, mathematically and statistically meaningful, architecturally consistent, and testable.**

### 10.1 ⛔ The recording rule — no silent repair

```yaml
finding:
  id:                    # VF-####
  mode:                  # SELF | INDEPENDENT | EXTERNAL_REQUIRED   (§10.2)
  lens:                  # MATH | STATS | DDD | LOGIC | COHERENCE |
                         # FALSIFIABILITY | ALTERNATIVES | IMPLEMENTATION
  claim:                 # the seed item, quoted
  evidence:              # what the corpus actually supplies
  problem:               # the defect, stated precisely
  why_it_is_a_problem:   # the consequence if left standing
  possible_correction:   # a PROPOSAL — never applied here
  test_required:         # TEST- id and type (§13) — MANDATORY
  status:                # OPEN | TESTED_CONFIRMED | TESTED_REFUTED | WITHDRAWN
  seed_effect:           # NONE | DEMOTE_TO_Lx | CAP_STRUCTURE_AT_Fx
                         # | CONTEST | WITHDRAW_PROPOSED
```

> **Why the separation is load-bearing.** `F0020` stamped a falsification verdict into itself and **left the falsified body unchanged** (`IFR-0012`, `C-0009`). The corpus does this natively. A verification stage that edited the seed would be **strictly worse than the corpus it reconstructs.**

### 10.2 ⭐ Three verification modes

| Mode | Who | Sees | May conclude | Window evidence |
|---|---|---|---|---|
| **SELF** | the Step-2 process | everything, including its own reasoning | findings + demotions | cheapest, and **demonstrably insufficient** |
| **INDEPENDENT / BLIND** | a fresh pass | ⛔ **only the seed as written** — not the reasoning that produced it | findings + demotions | ⭐ `F0015`, an independent blind review, produced the window's single most consequential correction (`C-0007`) — **correcting claims five self-reviewing documents had repeated without noticing** |
| **EXTERNAL REQUIRED** | a human expert | — | ⛔ **nothing is concluded by the protocol**; the finding is recorded as `EXTERNAL_REQUIRED` and routed | `HA-0007`: "no document can settle it" |

**Routing rule.** A question goes to `EXTERNAL_REQUIRED` when the corpus or the process **cannot legitimately decide it** — a governance act, a domain judgement outside the corpus, or an expertise the reconstruction cannot self-supply. ⛔ **Not** when it is merely hard.

> **SELF alone is not sufficient verification.** SELF must always run; ⭐ **INDEPENDENT runs when a genuinely separate reader is available**, and `OQ-8` measures their overlap.
>
> ⚠️ **A fresh in-session pass is a `SECONDARY_REVIEW`, not an INDEPENDENT one** — see §11.0. It is worth running and worth recording; it does not confer M2.

### 10.3 The eight lenses — with defects already visible

| Lens | Examines | ⚠️ Already visible in F0001–F0025 |
|---|---|---|
| **MATH** | definitions · axioms · derivations · consistency · missing conditions · counterexamples | ⛔ **`S-1`'s `bar` undefined**, three incompatible candidates. A conjunction with an undefined threshold is **not yet a proposition** → capped at F1 |
| **STATS** | probability/measure assumptions · estimands · uncertainty · independence · identifiability · sampling · **whether a metric measures what the theory claims** | ⛔⛔ **F0019 reports "25–35 % discoverable" then states "the estimate is structural, not counted"** — a percentage with no denominator, sample or estimand. ⛔ **Is `n` a count of *independent* occurrences?** 3 of 9 rediscoveries occurred **in one day against one author's own output** — the opposite of independent. This threatens **every `n ≥ 2` promotion claim** |
| **DDD** | bounded contexts · aggregates · invariants · ownership · state transitions · clean mapping | ⚠️ `HA-0007` ownership unresolved; `HA-0006`'s "four contexts, not eight" was **already wrong when written** (`PD-3` makes five) |
| **LOGIC** | predicates · relations · inference rules · dependency structure · decidability · precise representability | ⚠️ `grants(a,k)` is decidable over an act log; `evidence(k) ≥ bar` is **undecidable until MATH closes `bar`**. The corpus already runs 18 enforced lint rules and a decidable guard — the benchmark for "expressible" |
| **COHERENCE** | whether concepts form **one system**, not a collection | ⚠️ **The test:** does every object connect to the promotion mechanism (`T-0013`)? `T-0020`/`T-0021` may be orthogonal |
| **FALSIFIABILITY** | what observation would show a principle wrong | ✅ **copy the corpus** — F0019 falsified its own correlation; F0024 used four named falsifiers. ⛔ But `T-0015`'s conclusion was **retained after its premise was refuted** — the seed must not inherit that |
| **ALTERNATIVES** | whether another formulation explains the same evidence **more simply** | ⚠️ `S-2`: product, lattice, or two fields? (`PM-1`, unanswered). `HA-0008`: are the four progression kinds a **partition** or overlapping? `P-3`/`P-6` are composite — suggesting **not** a partition |
| **IMPLEMENTATION** | whether the theory becomes computational rules **without silently adding assumptions** | ⚠️ **`F0025` is the only file in 25 recording something built.** Everything else declares "nothing executes." The theory↔implementation gap is unmeasured |

### 10.3b ⭐ Lens depth requirements

The table above says *what* each lens examines. These are the **required elements** — a lens report missing them is incomplete.

#### MATH — per candidate structure

**Declare:** ⭐ **primitive concepts** · notation · domain · codomain · objects · relations · operators · axioms · assumptions · conditions · propositions · derivations · ⭐ **proofs where the structure admits one** · counterexamples · invariants.

**Then select the tests that fit the structure** (⛔ not all tests for every structure):

`type/dimensional consistency` · `boundary cases` · `degenerate cases` · `counterexample search` · `closure` · `uniqueness` · `existence` · `monotonicity` · `invariance` · `composability`

> ⛔ **A formula is not mathematically meaningful because it is syntactically elegant.** `S-1` reads well and has an undefined term — it is capped at F1 for that reason alone.

**Worked example — `S-1` degenerate case:** if `bar = 0`, `promote(k)` reduces to `∃a: grants(a,k)`, i.e. governance alone — which **contradicts `T-0013`'s central claim** that both conjuncts are required. So the structure is not merely under-defined; **some admissible values of `bar` falsify the theory it formalizes.** That is a MATH finding available today, before any execution.

#### STATS — per statistical claim or metric

**Identify all eleven:** population · sample · observation unit · estimand · denominator · dependence structure · uncertainty · assumptions · selection mechanism · possible bias · reproducibility.

> ⛔ **No percentage, rate, probability or count is statistically meaningful until what it measures is identified.** F0019's *"25–35 % discoverable"* fails at *denominator*, *sample* and *estimand* simultaneously — and the file itself concedes *"the estimate is structural, not counted."*

**Selection mechanism matters most here.** The 9 rediscoveries were found **by the author, in their own output, on one day**. That is a selection mechanism guaranteeing dependence — so `n` cannot be read as an independent count, and **every `n ≥ 2` promotion claim inherits the problem.**

#### LOGIC — five distinct states, never conflated

```
CONCEPTUALLY_DEFINED  →  FORMALLY_SPECIFIED  →  COMPUTATIONALLY_DECIDABLE
                      →  IMPLEMENTED         →  EMPIRICALLY_TESTED
```

**Determine where applicable:** predicates · inputs · outputs · inference rules · state transitions · decidability · computability · termination · determinism · consistency · representability.

> **Current honest placement:** `grants(a,k)` is `COMPUTATIONALLY_DECIDABLE` over an act log. `evidence(k) ≥ bar` is only `CONCEPTUALLY_DEFINED`. The corpus's 18 lint rules are `IMPLEMENTED`. **Nothing in the seed is `EMPIRICALLY_TESTED`.**

#### DDD — seven tests, and a legitimate negative result

Test whether: concepts have **stable identity** · bounded-context ownership is **meaningful** · aggregates have **coherent invariants** · **state transitions** are defined · responsibilities are **not duplicated** · contexts are **genuinely separated** · relationships **do not depend on accidental implementation details**.

> ### ⭐ **`DDD_MAPPING_NOT_JUSTIFIED` is a permitted verdict.**
> ⛔ Do not force a DDD reading onto evidence that does not support one. `HA-0006`'s contexts were derived from a rubric outside the window (`G-0008`) and one (`D-3`) was downgraded to WEAK on the ground that *"an ACL that performs an identity mapping is not an ACL"* — a warning that at least one boundary may be an accidental implementation detail.

#### COHERENCE — minimal-set analysis, not a hub test

> ⛔ **v2 asked "does every object connect to `T-0013`?" — which presumes `T-0013` is the centre.** That is accidental theory bias. Corrected.

**The question:** *what is the minimal set of concepts and relations that explains the central KnowledgeOS phenomenon?* Then classify **every** object:

`LOAD_BEARING` · `AUXILIARY` · `INDEPENDENT` · `REDUNDANT` · `UNEXPLAINED` · `NOT_YET_CONNECTABLE`

> ### ⭐ ⛔ **Do not force all objects into one theory.**
> `MULTIPLE_THEORY_FAMILIES_REMAIN` is a legitimate, reportable result. **The window makes it plausible:** the governance/promotion cluster (`T-0013`, `T-0014`, `HA-0008`) and the retrieval-positioning cluster (`T-0019`, `T-0020`) may simply be **two theories**, and `T-0021`'s UL-collision measurement may belong to neither.

### 10.3c ⭐ Three faults, never conflated

| Fault | Meaning | Fix |
|---|---|---|
| **THEORY** | the theory is wrong | revise the theory |
| **PROTOCOL** | the rule is imprecise | revise the protocol |
| ⭐ **INSTRUMENT** | the laboratory cannot represent or test it | revise the laboratory |

> ⛔ **An instrument failure is NEVER evidence about the theory.** A telescope that cannot resolve a star says nothing about the star.

A fourth outcome is not a fault at all: **`RESEARCH_DISCOVERY`** — something new was found, routed to Phase B2.

### 10.4 Verification is not authorized to conclude

| May | May **not** |
|---|---|
| record a finding | edit a seed item |
| **demote** a level | **promote** anything |
| **cap** a structure's stage | advance a stage |
| propose a correction | apply a correction |
| open a test | declare a test passed without running it |
| mark `CONTEST` | resolve a competition |
| ⛔ — | **reach L5 — ever** |

---

## 11. ⭐ Theory maturity model

> ⛔ **F0001–F0025 cannot produce the final theory.** The model exists so that partial maturity is reportable without pretending to completeness.

```
M0  RECONSTRUCTED     a Step-1 record exists                        L0/L1
M1  SEEDED            first synthesis; unverified                   L2/L3
M2  VERIFIED          8 lenses applied; findings recorded           L2/L3 + VF
M3  TESTED            ≥1 agenda test executed; result recorded      L4 if survived
M4  MATURING          multiple independent tests; survives;
                      extended by further corpus; still revisable   L4
M5  CANONICAL         adopted                              ⛔ HUMAN ACT ONLY
```

| Transition | Requires |
|---|---|
| M0→M1 | a stated identity basis + provenance chain |
| M1→M2 | all eight lenses **and** ⭐ **a genuinely independent verification pass** — see §11.0 |
| M2→M3 | a named test **executed**, outcome recorded (survived/refuted/inconclusive) |
| M3→M4 | ≥ 2 independent tests **and** corroboration from corpus outside the seeding window |
| M4→M5 | ⛔ **not available** |

### 11.0 ⭐ M2 and the meaning of independence — resolved 2026-09-22

> ## ⛔ **M2 is unreachable during Iteration 1 unless a genuinely independent reader/reviewer is available.**

| Rule | |
|---|---|
| **Iteration 1** | **M0 and M1 are reachable.** |
| **M2 requires** | an independent verification pass whose reader **does not have access to the reasoning that produced the candidate** |
| ⚠️ **A fresh in-session pass** | may be recorded as a **`SECONDARY_REVIEW`** — ⛔ **it does NOT qualify as M2** |
| ✅ **A genuinely separate reader** | qualifies for M2 |
| ⭐ **Expected Iteration-1 state** | **mostly M1, potentially none at M2** |

> ### ⛔ **"Independent" is NOT redefined to mean "the same agent deliberately ignores its previous reasoning."**
>
> That would weaken the epistemic meaning of independence **precisely in order to make the ladder easier to climb** — which is the failure `Q22` exists to prevent, one level up.

**Why this and not the alternatives.** `F0015` — an independent blind review — produced the window's single most consequential correction (`C-0007`), catching claims that **five self-reviewing documents had repeated without noticing**. Independence is doing real work in this corpus, so its meaning is not negotiable for convenience.

⛔ **Requiring an external reader is NOT assumed here.** That is an **experimental resource decision**, not something the protocol may silently impose. The protocol states the bar; whether the resource exists is decided per run and recorded. `OQ-8` remains open and now has a defined resolution path: run SELF, run a separate reader, **measure the overlap**.

### 11.1 ⭐ Demotion, and what may never raise maturity

| Rule | Why |
|---|---|
| ⭐ **A failed test REDUCES maturity** — `REFUTED` at M3 returns the item to **M1**, with the finding id recorded | v2 defined only upward transitions; `DI-0008` shows a refuted premise whose conclusion kept its standing |
| ⛔ **Repetition NEVER raises maturity** | ⭐ `C-0007`: *"0 traversals"* appears in **≥ 7 files**. Under a count-based rule that is the corpus's best-attested claim. **It is false.** Seven repetitions of one copied claim are **one** observation |
| ⛔ **Volume of corpus never raises maturity** | only executed tests (M3) and independent corroboration (M4) do |
| **Demotion requires no authority; promotion always does** | §1.1 — mirrors `T-0013`'s own asymmetry |

**Maturity is per item, never per theory.** Reporting one number for "the theory" would repeat exactly the error §12 forbids.

> ⭐ **Expected honest state after Iteration 1 on F0001–F0025: mostly M1, potentially NONE at M2, none above M2.**
>
> M2 needs a genuinely independent reader (§11.0); M3 needs an executed agenda test, and none has been executed. ⛔ **If the run reports items at M2 without a separate reader, that is a protocol violation, not a result.**

---

## 12. ⭐ Distance-to-final-theory assessment

> ### ⛔ **Never report a single percentage.** *"KnowledgeOS is 30 % complete"* is meaningless: it hides which dimension is weak, and 100 % is not even the target (§6.2 — `HA-0007` is unclosable by reconstruction).

`DISTANCE-ASSESSMENT.md` reports **nine dimensions separately**, each with its own state and its own route forward:

| # | Dimension | Reports | Route forward |
|---|---|---|---|
| **D1** | **What we understand** | narrative coverage: threads with a coherent arc | more corpus |
| **D2** | **Strongly supported** | items at M2+ with STRONG evidence | — |
| **D3** | **Provisional** | items in the provisional set, **with the reason class** (§7) | governance or evidence, per class |
| **D4** | **Mathematically unverified** | structures below **F2**, with the open term named | `OQ-9` and MATH tests |
| **D5** | **Statistically unverified** | metrics without estimands; independence unargued | STATS tests |
| **D6** | **Competing formulations** | count by outcome class (§6.2) | discriminators |
| **D7** | **Missing evidence** | gaps + obligations, split **reachable / unreachable** | corpus search or scope decision |
| **D8** | **Experiments required** | agenda tests by type (§13), unexecuted | run them |
| **D9** | **Governance decisions required** | `UNRESOLVABLE_BY_RECONSTRUCTION` items | ⛔ **human act — never research** |

### 12.0 ⭐ The dimensions are distinct — each answers a different question

⚠️ **v2 did not state what separates them, and several look like near-complements.** Disambiguated:

| Dim | The question only it answers | Distinct from |
|---|---|---|
| **D1** | *Can we tell a coherent story?* — narrative, **not truth** | D2: a coherent story may be wrong |
| **D2** | *What would survive challenge?* — M2+ **and** STRONG | D1: support ≠ coverage |
| **D3** | *What are we deliberately refusing to promote, and why?* — by **reason class** | D2: provisional items may be well-evidenced but **governance-blocked** (`T-0019`) |
| **D4** | *Which structures cannot yet be stated?* — below F2, **with the open term named** | D5: definedness ≠ measurement |
| **D5** | *Which numbers don't mean what they claim?* | D4: a metric can be well-defined and still unidentifiable |
| **D6** | *Where does the corpus disagree with itself?* | D7: a contradiction is present evidence; a gap is absent evidence |
| **D7** | *What is missing, and is it reachable at all?* — split **reachable / unreachable** | D8: missing evidence may need a scope decision, not an experiment |
| **D8** | *What would we have to do to find out?* — by **test type** | D7: some gaps close by search, not by experiment |
| **D9** | *What can no amount of work settle?* | D8: ⛔ **never scheduled as research** |

> **The four together answer the real question:** *what do we know · what don't we know · what could change our mind · what work remains.*

### 12.1 Supporting indicators

| Indicator | Definition | At F0025 |
|---|---|---|
| **M1** thread completeness | threads `historically_complete` | 1 / 8 |
| **M2** boundary dependence | threads with ≥1 out-of-window predecessor | 7 / 8 |
| **M3** contest load | competitions open or undecidable | 3 open, 1 unresolvable |
| **M4** structural yield | candidate structures, **by stage** | 5 at F1, **0 at F2** |
| **M5** level distribution | statements per L0–L4 | mostly L1/L2 |
| ⭐ **M6** classification completeness | **every open node classified evidence-resolvable or act-resolvable** | ⭐ **the closure criterion** |

### 12.2 The closure criterion

> **The theory is "as complete as reconstruction can make it" when M6 = 100 %** — every open node classified as *resolvable by more corpus*, *resolvable by experiment*, or *requiring a governance act*. ⛔ **Not when every node is closed.**

### 12.3 Saturation

Stop-and-report when, across a **full batch**: no new theory object, no new competition, no new structure, and M6 unchanged. ⛔ **One quiet batch is not saturation** — ES-006.1's "never promote from a single occurrence" applies to the saturation judgement itself.

---

## 13. ⭐ Testing agenda — eight test types

`VERIFICATION-AGENDA.md` classifies every test by **type** and ranks by **what it unblocks**.

| Type | Settles | ⛔ Cannot settle | Window example |
|---|---|---|---|
| **T1 Corpus search** | whether an artifact/claim exists | whether it is *correct* | locate `R-36`/`R-41`/`R-63` (`RO-0008`) |
| **T2 Mathematical** | definedness · consistency · counterexamples | empirical truth | define `bar`, or show no single threshold exists (`OQ-9`) |
| **T3 Statistical** | estimands · independence · identifiability · whether a metric measures its claim | logical validity | classify the 11 occurrences by author/day/artifact (`OQ-10`) |
| **T4 Logical** | decidability · expressibility · dependency structure | whether the axioms are *right* | which seed predicates are lint-checkable |
| **T5 DDD / architecture** | ownership · boundaries · invariants · clean mapping | mathematical soundness | apply `S-3`'s splitting rule to all 23 objects (`OQ-4`) |
| **T6 Computational / implementation** | whether rules execute **without added assumptions** | conceptual adequacy | measure the `F0025` implementation against the seed |
| **T7 Synthetic benchmark** | behaviour under controlled conditions | ⛔ **anything, if built from the theory's own concepts** | ⚠️ `OQ-11` — **state the falsification condition first, or it is not a test** |
| **T8 External validation** | convergence with independent practice | internal coherence | `F0017`'s PROV survey pattern |

### 13.0 ⭐ The `Experiment` record

> ⛔ **A laboratory without experiment records is not a laboratory.**

```yaml
experiment:
  id:                       # EXP-####
  iteration:
  hypothesis:               # what is being tested
  falsification_condition:  # ⭐ stated BEFORE execution
  test_type:                # T1..T8 (§13)
  design:
  executed:                 # true | false
  result:                   # SURVIVED | REFUTED | INCONCLUSIVE
  interpretation:
  affected_items: []
  provenance:
```

⭐ **`falsification_condition` is recorded before execution**, and an experiment lacking one is **rejected**.

### 13.0a ⭐⭐ The mandatory EXPERIMENTAL SEQUENCE

> ⚠️ **Named the *experimental sequence*, not "four stages"** — §9 already owns that phrase for formalization F0–F3. ⛔ **Do not conflate them.**

Every substantive theory test records **four separated stages**. ⛔ **A stage may not be written before the one above it.**

| Stage | Contains | ⛔ Must not contain |
|---|---|---|
| **1 · TEST** | proposition · hypothesis · **population** · unit of analysis · expected observation · **falsifier** · what would support / weaken / refute | — |
| **2 · RAW RESULT** | observations only — *file exists · exit code is always 0 · N occurrences · no invocation found in the searched scope* | ⛔ **"therefore this proves…"** — the raw layer stays observational |
| **3 · INTERPRETATION** | what follows · **what does not follow** · assumptions · definitions and types · necessary vs sufficient · **alternative explanations** · derivation where mathematical · §13B where statistical | — |
| **4 · IMPACT** | what the claim was · what was shown · **what changed** · ⭐ **what did NOT change** · strengthened / weakened / refined / split / generalized / narrowed / contradicted / demoted / unchanged · new obligations | — |

⛔ **The hypothesis and falsifier are written BEFORE inspection wherever practical. Never formulate the hypothesis after seeing the result.**

> ⭐ **Two asymmetries, both mandatory:**
> **A failed implementation hypothesis does not automatically falsify the theory.**
> **An implementation success does not automatically validate it.**

### 13A ⭐⭐ The five REALIZATION LAYERS

> ⛔ **Orthogonal to the epistemic levels of §5A.4 — this is NOT a seventh level.** Epistemic level asks *how well do we know it*; realization layer asks *how far is it actually real*.

> ⛔ **RENAMED — the original names collided with corpus terms** *(`RA-9`, `Q56`)*. The corpus owns *semantic · structural · behavioral · operational* as **viewpoints**, with different referents: its *structural* means the meta-model's element types; ours meant *does the schema encode it*. ⭐ **Silent reuse was a UL violation; these names are collision-free.**

| | Layer | Question |
|---|---|---|
| **1** | ⭐ **SPECIFIED** | does the concept have a coherent, stable meaning? |
| **2** | ⭐ **REPRESENTED** | does the architecture / schema / design encode it? |
| **3** | ⭐ **ENFORCED** | does the implementation enforce it when executed? |
| **4** | ⭐ **INVOKED** | is the capability actually used in the real workflow? |
| **5** | ⭐ **EFFECTIVE** | does using it produce the claimed observable effect? |

⚠️ **On the arrows.** The corpus records its four viewpoints as *"complementary **siblings, not a hierarchy**"* — *"projections do not flow into each other."* ⭐ **These five differ: they carry a genuine dependency** — nothing is `ENFORCED` that is not `REPRESENTED`. ⛔ **But that is an argued claim, not an assumption.**

```
specified ≠ architecturally represented ≠ implemented ≠ executed ≠ used ≠ empirically effective
```

⛔ **A claim may pass one layer and fail another, and the layers must be reported separately.**

**Worked example — `EXP-0002`:** the separation of powers passes `SPECIFIED` and `REPRESENTED`, passes `ENFORCED` *partially* (4 of 6 instruments; one repairs what it measures, one returns no verdict), and is ⛔ **untested at `INVOKED` and `EFFECTIVE`**.

### 13A.1 ⭐ Recovered POINTERS — recover the definition before formalizing

> ⛔ **A corpus phrase naming a structure is a POINTER, not the structure.**

When recovery yields a named structure whose **content** is absent, follow this and ⛔ **do not construct from the name**:

```
1  find the structure in the corpus
2  recover its EXACT corpus definition
3  determine domains · elements · relations
4  determine whether the parts are independent
5  determine what the corpus CLAIMS it makes visible
6  ONLY THEN formalize
```

**Live case:** the corpus states *"the **four partial orders** … in which direction may change propagate … the thin back-edge is visible **only through them**."* ⭐ **Four partial orders is a mathematical structure, and the pointer is all we have.** ⛔ **Inventing four partial orders from the phrase would be the `bar` error with better vocabulary.**

### 13B ⭐⭐ Statistical discipline for counts

> ## ⛔ **Counts are observations. They are not automatically statistical evidence.**

Whenever a count, ratio, percentage, frequency or rate is written — `4 of 6`, `10 of 39`, `21 occurrences`, `0 of N` — **state all six**:

**population** *(what universe is the denominator?)* · **sampling frame** *(why these objects?)* · **unit of analysis** *(what is one observation?)* · **dependence** *(independent, clustered, or repeated manifestations of one underlying object?)* · **generalization** *(what broader population, if any?)* · **limitation** *(what cannot be concluded)*.

⛔ **Never silently turn a convenience count into a statistical law.**

**Binding example:** `EXP-0002`'s **4 of 6** is a **descriptive repository finding** about six named instruments. ⛔ It is **not** a rate, not a confidence level, and licenses **no** claim about instruments generally.

⚠️ And recall the corpus's own instance of this error: a rediscovery count was read as an *accelerating rate* when the peak-detection day was also the peak-output day — **the detector confounded the measurement.** `OQ-11` establishes why: a benchmark built from the theory's own concepts cannot refute it unless its falsifier was fixed in advance.

### 13.1 Ranking

| Rank | Criterion |
|---|---|
| **1** | Blocks a structure from reaching **F2** (the undefined `bar`) |
| **2** | Invalidates a **metric** the theory relies on (the uncounted 25–35 %) |
| **3** | Affects **many seed items** at once (independence of `n`) |
| **4** | Resolvable by **corpus search** → routes to `RESEARCH-OBLIGATIONS` |
| **5** | Requires an **experiment** |
| **6** | ⛔ Requires a **governance act** — routed to D9, **never scheduled as research** |

> ### ⭐ The agenda, not the seed, decides what to look for in the remaining 3,056 files.
> Reading in registry order learns whatever the corpus says next. **Reading in agenda order reads in order of what would change our mind** — the difference between accumulating a record and testing a theory.

---

## 14. ⭐ Provenance — mandatory and complete

> **Every Step-2 concept must be traceable end to end:**

```
Step-1 evidence  →  theory object / thread  →  synthesis reasoning
                 →  verification finding    →  test + result
```

**Enforced by `Q15`:** a seed item whose `provenance` chain is missing a link **cannot be written**. Early links are mandatory; later links (verification, tests) are populated as those phases run, and their absence is recorded as *not yet reached*, never as absent.

`PROVENANCE-INDEX.jsonl` gives the reverse index — from a Step-1 file or object to every seed item depending on it — so that **a refuted Step-1 record can be traced to everything it contaminates.** `C-0007` shows why: one falsified claim is load-bearing for a bounded-context verdict four documents away.

---

## 15. Artifacts

### 15.0 ⭐ Output location — BINDING

> ### **Every Step-2 artifact is written to:**
>
> ```
> docs/knowledgeos/knowledgeos_theory_chronological_extraction/phase2_extraction/
> ```

| Path | Contains | Written by |
|---|---|---|
| `knowledgeos_theory_chronological_extraction/` *(root)* | **Step-1 output** — the 13 registries, `RECONSTRUCTION-STATE.json`, batch reports | Step 1 only |
| `…/prompts/` | the two protocols | neither step — human-curated |
| ⭐ `…/phase2_extraction/` | **all Step-2 output** (§15.1) | **Step 2 only** |

**Rules:**

1. ⛔ **Step 2 writes nothing outside `phase2_extraction/`.** Not to the root, not to `prompts/`, not elsewhere in the repository.
2. ⛔ **Step 2 never writes to a Step-1 artifact** (`Q17`). It reads them from the root and records its own state in its own folder, so **Step 1 remains independently re-runnable and auditable**.
3. **Reads are unrestricted** — Step 2 reads the Step-1 root, `prompts/`, the registry and the corpus as its obligations require.
4. **The separation is the audit boundary.** If Step-2 synthesis is later found wrong, deleting `phase2_extraction/` restores a clean Step-1 state with no residue.

### 15.0b ⭐ The Phase-2 package layout

```
phase2_extraction/
│
├── HISTORICAL-STORY.md              the narrative (§4)
├── THEORY-SEED.md                   provisional theory specification (§5)
│
├── THEORY-CONSTRUCTION.jsonl        ⭐ the eleven emergent kinds (§4A)
├── THEORY-EVOLUTION.jsonl           the change ledger (§15.2)
├── THEORY-OBJECTS.jsonl        [E]  objects ORIGINATED in Phase 2
├── THEORY-THREADS.jsonl        [E]  thread overlay + merges/splits
├── DERIVATION-INSTANCES.jsonl  [E]  derivations constructed in Phase 2
├── CONTRADICTIONS.jsonl        [E]  contradictions found in Phase 2
├── COMPETING-INTERPRETATIONS.jsonl  rival readings + discriminators (§6)
│
├── ADVERSARIAL-FRAMING.jsonl        support/weaken/falsify/alternative (Phase H0)
├── VERIFICATION-RESULTS.jsonl       findings, 8 lenses × 3 modes (§10.1)
├── FAILED-TESTS.jsonl          ⭐   refuted items — kept, never deleted
├── SEED-REVISIONS.jsonl             every demotion/cap, with its finding id
│
├── FORMALIZATION/
│   ├── definitions/                 one file per definition
│   ├── propositions/                F2+ only (§9)
│   ├── structures/                  F0–F3 staged
│   └── notation/                    symbol table — see §15.4
│
├── ARCHITECTURE/
│   ├── reference-alignment/         vs a Reference Architecture (⚠️ absent — §49)
│   └── emergent-architecture/       HA- objects + Phase-2 architecture findings
│
├── GAP-ANALYSIS/                    gaps by reachability (§8, D7)
├── OPEN-QUESTIONS.md                unresolved, by who can close them
├── RESEARCH-AGENDA.md               tests by type, ranked (§13)
├── SURPRISE-DISCOVERIES.md          the reasoning check (§16A)
├── DISTANCE-ASSESSMENT.md           nine dimensions (§12)
├── PROVENANCE-INDEX.jsonl           forward + reverse chains (§14)
│
├── STEP2-CHECKPOINT.md              human-readable state
└── STEP2-STATE.json                 machine state (§3.1)
```

#### ⚠️ `[E]` — the same-name collision, and the rule that resolves it

**Four filenames duplicate Step-1 artifacts.** Same name, different directory, **different meaning** — a real ambiguity, so it is ruled explicitly:

| | `../THEORY-OBJECTS.jsonl` | `phase2_extraction/THEORY-OBJECTS.jsonl` |
|---|---|---|
| Contains | objects **found in the corpus** (Step 1) | objects **constructed in Phase 2** |
| Level | L0/L1 | L2/L3/L4 |
| Written by | Step 1 only | Step 2 only |

**Rules:** every `[E]` record carries `"origin": "STEP2"`; an `[E]` file **never copies or restates a Step-1 record** — it references it by id; `Q17` still forbids writing `../`. ⛔ **Never read an `[E]` file as if it were its Step-1 namesake** — a join across the two without checking `origin` fuses L1 evidence with L2 hypothesis, which is the single worst error available in this design.

#### ⭐ 15.0c Per-object lifecycle trace — required

Every theory object carries a **trace**, in `THEORY-EVOLUTION.jsonl` and rendered in `THEORY-SEED.md`:

```
T-0016
  first appears      F0020   as "the model's PRINCIPAL DISCOVERY", STRONG
  challenged         F0024   two of four grounds falsified
  reformulated       F0020   survives on ONE narrower ground (scope exclusion)
  verdict recorded   F0020   banner; body left unchanged (IFR-0012)
  formalization      —       not attempted; rubric out of window (G-0008)
  verification       pending
```

> This is the research representation in miniature: **not prose, and not a summary — a record of what happened to a claim.**

### 15.1 The artifact set

| Tier | Artifact | Content |
|---|---|---|
| ⭐⭐ **PRIMARY** | **`CANDIDATE-KNOWLEDGEOS-THEORY.md`** | ⭐ **the human-readable theory — the front door (§5A)** |
| ⭐ **PRIMARY** | `CANDIDATE-THEORY-SNAPSHOT.md` | the 10-minute executive version |
| ⭐ **PRIMARY** | `THEORY-CHANGELOG.md` | versioned change record |
| ⭐ **PRIMARY** | `CORPUS-CONTRIBUTION-AUDIT.md` | per-file contribution dispositions and theory coverage (§5B) |
| **CORE** | ⭐ `HISTORICAL-STORY.md` | the narrative (§4) — **history only** |
| **CORE** | `THEORY-SEED.md` | the nine-question seed (§5) |
| **CORE** | `SEED-ITEMS.jsonl` | one record per item, levelled, provenance-linked, falsifiable |
| **CORE** | `COMPETITIONS.jsonl` | formulations + discriminator + outcome class |
| **CORE** | ⭐ `STEP2-THREADS.jsonl` | thread overlay — ⛔ **Step-1 artifacts are never written to** |
| **CORE** | `STRUCTURES.jsonl` | structures **with F0–F3 stage** (§9) |
| **CORE** | `VERIFICATION-FINDINGS.jsonl` | §10.1 records, with `mode` |
| **CORE** | `VERIFICATION-AGENDA.md` | tests by type, ranked (§13) |
| **CORE** | `SEED-REVISIONS.jsonl` | every demotion/cap with its finding id — **the audit trail that no repair was silent** |
| **CORE** | ⭐ `DISTANCE-ASSESSMENT.md` | nine dimensions (§12) — ⛔ no single percentage |
| **CORE** | ⭐ `PROVENANCE-INDEX.jsonl` | forward and reverse chains (§14) |
| **CORE** | `STEP2-STATE.json` | resumable state (§3.1) |
| ⭐ **CORE** | `EMERGENT-OBJECTS.jsonl` | the eleven emergent kinds (§4A) — **where theory is constructed** |
| ⭐ **CORE** | `THEORY-EVOLUTION.jsonl` | the change ledger (§15.2) |
| ⭐ **CORE** | `SURPRISE-DISCOVERIES.md` | what synthesis found that Step 1 and this protocol did not anticipate (§16) |
| ⭐ **CORE** | `ADVERSARIAL-FRAMING.jsonl` | per seed item: support / weaken / falsify / alternative, recorded **before** verification (Phase H0) |
| ⭐ **CORE (2B)** | `ENGINE-COMPARISON.md` | expert (2A) ↔ program (2B) divergences, classified (§3A.5) |
| ⭐ **CORE** | `REPRESENTATIONAL-GAPS.jsonl` | `RG-####` — gaps with fault class (§3B.4) |
| ⭐ **CORE** | `EXPERIMENTS.jsonl` | `EXP-####` — falsification condition stated before execution (§13.0) |
| ⭐ **CORE** | `REREAD-LOG.jsonl` | expected vs actual finding, per re-READ (§4.3b) |
| ⭐ **CORE** | `ITERATIONS.json` | per-iteration state; a later iteration supersedes, never overwrites |
| **CONDITIONAL** | `ORPHANS.jsonl` | objects joining no thread — **a finding, not a failure** |
| **CONDITIONAL** | `PROVISIONAL-SET.jsonl` | items forbidden promotion, with reasons (§7) |
| **CONDITIONAL** | `PRIOR-ART.jsonl` | Phase-0 sweep hits |
| **DERIVED** | `STEP2-BATCH-REPORT.md` | per batch |

**Absence discipline (from Step-1 §41/§25):** an un-created conditional artifact records `ABSENT_BY_CONTENT` / `NOT_SEARCHED` / `OUT_OF_WINDOW` / `UNCERTAIN`. `ABSENT_BY_CONTENT` is a positive claim that must be **earned by an actual search**.

### 15.2 ⭐ `THEORY-EVOLUTION.jsonl` — the change ledger

The final theory will likely differ substantially from this seed. **The ledger is how that difference stays legible instead of becoming quiet drift.**

```yaml
evolution:
  id:                    # EV-####
  old_formulation:       # verbatim, as it stood
  new_formulation:
  reason:
  evidence:              # what forced the change
  trigger:               # TEST-#### | VF-#### | file id | EM-####
  epistemic_transition:  # e.g. L3->L2 (refuted) | M3->M1 | F1->F2 | WITHDRAWN
  affected_threads: []
  affected_seed_items: []
  change_class:          # CONFIRM | EXTEND | SPLIT | CONTRADICT
                         # | REPLACE | UNIFY | FRAGMENT
```

⛔ **The old formulation is kept verbatim, always.** `IFR-0010` shows what is lost otherwise: F0019's annotation was itself rewritten and its first form is now **unrecoverable**.

### 15.3 ⭐ ID namespaces — declared, because this corpus collides

Step-1 found **4 meanings of `D-`** and **3 of `R-`**. Step 2 mints only in these namespaces:

`SI-####` seed items · `EM-####` emergents · `VF-####` findings · `TEST-####` tests · `EV-####` evolution · `CMP-####` competitions · `STR-####` structures

⛔ **Never reuse a Step-1 namespace** (`T- TH- HA- R- C- G- DI- IFR- OC- F####`) and never mint a bare single-letter series. `Q8` checks every join against the collision map.

---

## 16. Quality gates

| # | Check | Prevents | Observed? |
|---|---|---|---|
| **Q1** | `level_census[L5] == 0` | the protocol adopting something | — |
| **Q2** | ⭐ every seed item has a **typed** `falsifiable_as` (§5.2) — `FALSIFIABILITY_NOT_YET_SPECIFIED` permitted and **counted** | unfalsifiable theory **and** manufactured weak falsifiers | ⚠️ v2 forced fabrication; corrected |
| **Q3** | `strength(statement) ≤ strength(evidence)` | overclaiming | ✅ the corpus's own final rule |
| **Q4** | no competition closed without a discriminator | resolution by preference | ⚠️ `C-0007` |
| **Q5** | no dangling references — **per item** | broken graph | ✅ caught real defects in **2/2** batches |
| **Q6** | every provisional item has a `provisional_reason` | silent promotion | ✅ `T-0019` |
| **Q7** | no thread merged without §19A's six criteria **evidenced** | false identity | ✅ `TH-0003` kept UNCERTAIN |
| **Q8** | identifier joins checked against the collision map | cross-file fusion | ⚠️ 4 meanings of `D-` |
| **Q9** | no historical text altered | silent repair | ✅ the corpus does this natively |
| **Q10** | Canonical Discovery run per proposed formulation | proposing-before-searching | ❌ **violated once** (§0.1) |
| **Q11** | all eight lenses × ≥2 modes applied to every seed item | a seed verified only where strong | — |
| **Q12** | no seed item **edited** in Phase H — only demoted/capped, with a finding id | silent repair during verification | ✅ `IFR-0012` |
| **Q13** | every finding has a non-empty `test_required` with a **type** | criticism that cannot be settled | — |
| **Q14** | ⭐ no structure above **F1** while a defining condition is open | elegance mistaken for rigor | ✅ **now enforced** — all 5 capped at F1 |
| **Q15** | ⭐ provenance chain complete for every seed item (§14) | untraceable theory | — |
| **Q16** | ⭐ `HISTORICAL-STORY.md` contains **no L2 statement** | history contaminated by interpretation | — |
| **Q17** | ⭐ no Step-1 artifact written to | Step 1 must stay independently re-runnable | — |
| **Q18** | ⭐ **every write lands inside `phase2_extraction/`** (§15.0) | Step-2 residue contaminating the Step-1 record | — |
| **Q19** | ⭐ **every Step-1 node is admitted to synthesis** — T- **and** HA- and any other typed node; ungrouped ≠ excluded | ⛔ the dry-run defect: 11 objects parked, 10 architecture objects dropped | ❌ **v2 failed this** |
| **Q20** | ⭐ **Phase F.1 runs a structure SEARCH** before staging; `S-1..S-5` enter as input, never as the search space | theory bias toward pre-listed structures | ❌ **v2 failed this** |
| **Q21** | ⭐ **Phase H0 adversarial framing recorded before any verification** | verification degenerating into defence of the seed | — |
| **Q22** | ⭐ **maturity never raised by repetition or corpus volume** (§11.1) | `C-0007`: a false claim in 7 files outranking its single true refutation | — |
| **Q23** | ⭐ **`REJECTED_INTERPRETATION` recorded whenever a formulation is abandoned** | silent abandonment; losing the corpus's strongest move | — |
| **Q24** | ⭐ **every theory change written to `THEORY-EVOLUTION` with the old text verbatim** | quiet drift; `IFR-0010`'s unrecoverable original | — |
| **Q25** | ⭐ **`HISTORICAL-STORY.md` is never revised by a later phase** | history rewritten to match the theory | — |
| **Q26** | ⭐ **no `[E]` file copies or restates a Step-1 record** — reference by id, and every record carries `"origin": "STEP2"` | ⛔ fusing L1 evidence with L2 hypothesis across same-named files (§15.0b) | — |
| **Q27** | ⭐ **no Phase-3 exposition in Phase-2 output** — every story claim carries file + epistemic status (§4.4) | a book written around a theory the evidence has not justified | — |
| **Q28** | ⭐ **no theory content hard-coded in Phase-2B software** (§3A.4) — no canonical object, structure or correct theory | the first program silently becoming the theory | — |
| **Q29** | ⭐ **every 2A↔2B divergence classified and explained**, never averaged | a scoring function replacing judgement | — |
| **Q37** | ⭐ **every out-of-core consultation recorded in `EVIDENCE-EXPANSION` with its question and effect** (§3C.2) | external evidence silently becoming theory | ✅ 4 recorded in iteration 1 |
| **Q38** | ⭐ **targeted search run before declaring a term missing** (§3C.3) | inventing a component the governance forbids | ✅ caught the `bar` error |
| **Q39** | ⭐ **repeated out-of-boundary answers reported as a scope finding** (§3C.4) | reading on inside a boundary that cannot answer the question | ✅ 8 of 10 instruments outside the registry |
| **Q40** | ⭐ **next target selected by the §3C.5 criteria, and the choice justified in the checkpoint** | a protocol that hard-codes today's research state | — |
| **Q41** | ⭐⭐ **`CANDIDATE-KNOWLEDGEOS-THEORY.md` exists and is current at every checkpoint** (§5A) | ⛔ machinery outrunning the theory — **Iteration 1 failed this** | ❌ **failed once** |
| **Q42** | ⭐ **the theory document is readable without opening any JSON**, and opens in business language | a theory legible only to its own author | — |
| **Q43** | ⭐ **every important statement carries an epistemic level `A`–`E`; `F` never appears** | collapsing corpus fact into validated result | — |
| **Q44** | ⭐ **gaps marked `UNKNOWN`, never filled for completeness** | a theory that looks finished because its holes were papered over | — |
| **Q45** | ⭐ **every file in the audited range has a contribution disposition** (§5B.1) | a theory that ignored half its evidence | — |
| **Q46** | ⭐ **every theory-bearing contribution is represented, excluded-with-reason, or marked unresolved** | silent discard | — |
| **Q47** | ⭐ **every substantive experiment carries the experimental sequence, stages separated** (§13.0a) | jumping from observation to theory — ⛔ the `bar` failure | ✅ `EXP-0002` |
| **Q48** | ⭐ **claims about mechanisms distinguish the five realization layers** (§13A) | *specified* read as *implemented* read as *used* | ✅ `EXP-0002` |
| **Q49** | ⭐ **every count states population · frame · unit · dependence · generalization · limitation** (§13B) | a convenience count becoming a statistical law | — |
| **Q50** | ⭐ **no experiment result silently promoted to validated or canonical** | an implementation success read as theory validation | ✅ `SI-0009` held at `D*` |
| **Q51** | ⭐ **every statement carries an ORIGIN label `[C]`/`[S]`/`[E]`/`[T]`**, never collapsed into strength | an expert construction reading as a corpus fact; a test-derived change reading as validated | — |
| **Q52** | ⭐ **every `[E]` or `[T]` addition records necessity · basis · reasoning · alternatives · falsifier** (`[T]` also: test id, outcome, modified formulation) | expert judgement or a test result as a dumping ground | — |
| **Q53** | ⭐ **targeted search performed before any expert construction** (§5C.4) | inventing what the corpus already holds | ✅ **paid twice** |
| **Q54** | ⭐ **no contribution excluded from theory by document KIND alone** (§5C.6) | architecture as a dumping ground | ❌ **violated once** |
| **Q59** | ⭐⭐ **every recovered theory object states its RELATIONSHIPS or records `NONE_FOUND` with a reason.** Minimum: proposition → premises · derivation → conclusion · alternative → what it competes with · replacement → what it supersedes · contradiction → both claims | ⛔ **a theory of disconnected fragments** — 14 relationship types were vocabulary with **0 of 14 gated** | ❌ **currently violated** |
| **Q61** | ⭐⭐ **every execution unit updates `KNOWLEDGEOS-RESEARCH-STATE.md`** — position · compliance · next target with its justification (§16B) | ⛔ continuation depending on a human to remember where the work is | — |
| **Q60** | ⭐⭐ **every seed item appears in `CANDIDATE-KNOWLEDGEOS-THEORY.md`, or is recorded `DELIBERATELY_OMITTED` with a reason** — with a **mechanically checkable** identifier link (§5A.7) | ⛔ theory items silently dropped from the primary document | ❌ **violated — 3 of 11 items were absent and no gate saw it** |
| **Q57** | ⭐⭐ **every identified gap carries a DISPOSITION at each checkpoint** — `FILLED [E]` · `REFUSED (reason)` · `POINTER (content not recovered)` · `WAITING (named search)` · `BLOCKED (named act)`. ⛔ **`unresolved` alone is not a disposition** | gaps accumulating silently with no forcing function | ❌ **currently violated** — several gaps sit undispositioned |
| **Q58** | ⭐⭐ **the theory document states its OWN coverage** — files read / corpus size — wherever completeness could be inferred | ⛔ a 356-line theory over **0.8 %** of the corpus reading as if it were the theory | ⚠️ partially — the scope note exists, the ratio was not stated |
| **Q56** | ⭐⭐ **no new term reuses a corpus term with a different meaning** without renaming, qualification, or a recorded relationship (`RA-9`) | ⛔ a claim true in one reading and false in the other, with nothing marking which is meant | ❌ **violated once** — corrected by renaming |
| **Q55** | ⭐⭐ **every Phase-1 fact consumed in Phase 2 states the WINDOW it was established over** (`ACL-4`) | ⛔ a claim true of the files read becoming a claim about the corpus | ❌ **violated once** — *"the correction propagated to zero files"* was true of 25 files, false of the corpus |
| **Q32** | ⭐ **every artifact carries `iteration_id`; no iteration overwrites an earlier one** | losing which pass produced what | — |
| **Q33** | ⭐ **representation derived from CONSTRUCT, never from a pre-specified kernel** (§3B.3) | the model deciding what can be found | — |
| **Q34** | ⭐ **every re-READ declares `expected_finding` BEFORE reading** (§4.3b) | confirmation bias in iteration 2+ | ✅ precedent: P3A predictions, 4/4 |
| **Q35** | ⭐ **every experiment has a `falsification_condition` stated before execution** | a test that cannot fail | — |
| **Q36** | ⭐ **every divergence classified THEORY / PROTOCOL / INSTRUMENT / RESEARCH_DISCOVERY** (§10.3c) | an instrument failure read as evidence about the theory | — |
| **Q30** | ⭐ **every representational deficiency raised against the PROTOCOL**, not silently worked around in the schema (§3B.4) | an imprecise rule hidden by a clever data model | ✅ **already fired once** — the 8-concept kernel covered 34 % |
| **Q31** | ⭐ **domain core imports no adapter** — no persistence, LLM/ML, filesystem or transport type in `domain/` (§3B.1) | the research process's technology deciding the theory's shape | — |

> **Q5 cadence.** §37 check 4 caught a real dangling reference in **both** batches. At thread granularity a dangling reference corrupts a synthesis unit, not one row.

---

## 16A. ⭐ Surprise report — the check that reasoning actually happened

`SURPRISE-DISCOVERIES.md` reports, explicitly:

| Category | Why it is the measure |
|---|---|
| **Discoveries not anticipated by Step 1** | Step 1 recorded; Step 2 must find something Step 1 could not see |
| **Relationships absent from P3A** | P3A is the mechanical baseline. Step 1 already beat it on dates (7 of 25 wrong, uniform mechanism). Step 2 must beat it on **relations** |
| **Structures not listed in §9** | ⛔ if the only structures found are `S-1..S-5`, the search space was the list |
| **Contradictions between the emerging seed and the historical corpus** | a seed that never disagrees with its corpus has not been tested against it |
| **Concepts important only through synthesis** | ⭐ the direct evidence of construction over transcription |

> ### ⛔ **A Step-2 run reporting zero surprises has probably filled a schema rather than reasoned.**
> That outcome is itself a finding, and it must be stated plainly rather than padded. Precedent: `T-0023` — assembled from five files, none of which says the counter orders anything, and not anticipated by any protocol section. **One such discovery per 25 files is the observed rate; zero warrants suspicion of the process, not of the corpus.**

---

## 16B. ⭐⭐ State-driven continuation — how the next work is determined

> ## ⛔ **Work is recomputed from STATE. It is never supplied by a prompt.**
> *(`RA-11`, `RA-12`)*

⛔ **An execution unit is not complete until `KNOWLEDGEOS-RESEARCH-STATE.md` is updated.** A prompt is an **execution trigger**, ⛔ **not the methodology** — the methodology lives in the architecture and these protocols.

### 16B.1 The recompute procedure — run at the END of every execution unit

```
current state
   ↓ what is DONE?              files processed · items traced · tests executed
   ↓ what is BLOCKED?           by a governance act · by out-of-scope evidence
   ↓ what is NON-COMPLIANT?     open gate violations (the compliance backlog)
   ↓ what is OPEN?              gaps · pointers · competitions · obligations
   ↓ what is READY?             items that can now be formalized or tested
   ↓ rank by §3C.5              evidence availability · centrality
                                falsifiability · expected information gain
   ↓
NEXT ACTION  — with the reason it outranks the alternatives
```

⭐ **The next corpus file is one candidate among several, never the automatic answer** (`§3C.6`). A targeted search, a compliance item, or a formalization may outrank it — and has, repeatedly.

### 16B.2 What the state file must answer

architecture version & freeze status · current phase · corpus progress *(last / next / processed / total)* · which sub-phases are active · Candidate Theory version · **open compliance work per gate** · open research threads · **next execution target, with its justification**.

⛔ **No rules live in the state file.** A rule found there is misplaced and moves to a protocol.

### 16B.3 ⭐ The continuation contract

> **Given the frozen architecture, these protocols, and the current Research State, the next authorized work is determinable without further instruction.**

⛔ **If it is not, that is a DEFECT** — in the state file if position is missing, in a protocol if the procedure is missing, ⛔ **never a reason to ask for a longer prompt.**

### 16B.4 `Q61`

⛔ **An execution unit that does not update Research State is INCOMPLETE**, regardless of what else it produced.

---

## 17. Open questions requiring experimentation

| # | Question | Why design cannot settle it | Experiment |
|---|---|---|---|
| **OQ-1** | Does the thread survive as synthesis unit at 100+ files? | 8 threads / 25 files; ratio may not hold | Run to F0100; measure threads-per-file |
| **OQ-2** | Is the occurrence counter global and unforked? | assumed by `OC-0001` | corpus-wide sweep (`RO-0010`) |
| **OQ-3** | Does the seed survive the next 100 files, or is it replaced? | **unknown — the honest answer** | version the seed; measure churn |
| **OQ-4** | Is `S-3` general? | derived from one concept | apply to all 23 objects; count splits |
| **OQ-5** | Right batch size for Step 2? | Step 1 used 10 then 15 | vary; measure new-objects-per-file |
| **OQ-6** | Should registry scope widen beyond `docs/knowledgeos/`? | ⛔ **governance, not protocol** | escalate — 4 of 6 obligations unreachable |
| **OQ-7** | Can `UNRESOLVABLE_BY_RECONSTRUCTION` be assigned reliably? | same hazard as `NOT_APPLICABLE` | require a **quoted source statement**, as `HA-0007` has |
| **OQ-8** | Can the process verify its own seed? | `F0015`: blind review caught what 5 self-reviews missed | run SELF, then INDEPENDENT; **measure overlap** |
| **OQ-9** | What is `bar` in `S-1`? | 3 candidate definitions, no reconciliation | corpus search; **if none exists, S-1 stays F1 — and that is the finding** |
| **OQ-10** | Are the `n` counts independent? | 3 of 9 in one day against one author's own output | classify all 11 by author/day/artifact |
| **OQ-11** | Is a synthetic benchmark a legitimate test? | a benchmark from the theory's own concepts cannot refute it | **state its falsification condition first**; if none can be stated, it is not a test |
| ⭐ **OQ-12** | Does separating story from seed actually prevent contamination, or just add cost? | v1 had no story; unmeasured | write both; check whether any seed item's evidence is traceable **only** through the narrative |

---

## 18. ⭐ Consistency review — defects found in v1 and how they were resolved

Performed across §1–§17 per the review commission.

| # | Defect in v1 | Class | Resolution |
|---|---|---|---|
| **1** | ⛔ **Circular gate `G2-D`** — required discriminators before Step 2, but naming them **is Phase D of Step 2** | **BLOCKING** | Reclassified to Class B (§2.2); now a Phase-E output |
| **2** | ⛔ **Circular gate `G2-F`** — required Canonical Discovery of formulations that do not yet exist | **BLOCKING** | Split: one-time Phase-0 sweep + per-formulation `Q10`. **Not a gate** |
| **3** | ⛔ **Three contradictory gate counts** — §2.1 "four of six", §14 "three of five", closing line named three | **BLOCKING** | Single Class-A list; one count, one place |
| **4** | ⛔ **§3.2 asserted `G2-A..G2-E`**, silently omitting `G2-F` | **BLOCKING** | Asserts Class A only |
| **5** | ⛔ **§9 violated `Q14`** — five structures stated as formalisms with an undefined term | **BLOCKING** | F0–F3 ladder (§9); **all five capped at F1; none reaches F2** |
| **6** | ⚠️ **Ladder had no failed-test outcome** — L3→L4 assumed success | **REQUIRED** | Three outcomes: SURVIVED / REFUTED / INCONCLUSIVE (§1.1) |
| **7** | ⚠️ **`G2-B` over-strict** — blocked all synthesis for 3 underspecified objects, none of them a thread's primary | **REQUIRED** | Per-thread precondition (§2.3); **all 8 threads synthesizable today** |
| **8** | ⚠️ **`THEORY-THREADS.jsonl` listed as a Step-2 output** | **REQUIRED** | Overlay `STEP2-THREADS.jsonl`; `Q17` forbids writing Step-1 artifacts |
| **9** | ⚠️ **No historical story** — v1 jumped registries → seed | **REQUIRED** | §4, with `Q16` forbidding L2 in the narrative |
| **10** | ⚠️ **Provenance unenforced** — `supported_by` only | **REQUIRED** | §14 five-link chain + `Q15` + reverse index |
| **11** | ⚠️ **Verification modes undefined** | **REQUIRED** | §10.2 three modes; SELF alone declared insufficient |
| **12** | ⚠️ **No maturity model** | **REQUIRED** | §11 M0–M5, **per item** |
| **13** | ⚠️ **M6 asserted as "the real target", never operationalized** | **REQUIRED** | §12.2 closure criterion; M-indicators demoted to *supporting* under nine dimensions |
| **14** | ⚠️ **Agenda had no test taxonomy** | **REQUIRED** | §13 eight types, each with what it **cannot** settle |
| **15** | ⚠️ **Seed schema had no link to the nine questions** | **RECOMMENDED** | `answers_question` field |
| **16** | ⚠️ **Batch semantics undefined** for saturation | **RECOMMENDED** | §12.3 |
| **17** | ⚠️ **Protocol read as constraining reasoning** | **RECOMMENDED** | §0.2 states the opposite explicitly |
| **18** | ⚠️ **§10.4 rank 6 vs OQ-6** — governance "never scheduled as research" while OQ-6 listed escalation as an experiment | **DEFERRED** | Retained: escalation is not research. Noted here rather than reworded |
| **19** | ⛔ **No output location defined** — v1 named artifacts but never said where they land, so Step-2 output would have mixed into the Step-1 record | **BLOCKING** | §15.0 binds all output to `phase2_extraction/`; `Q18` enforces it; the separation is now the audit boundary |

**No remaining circular dependency was found.** The dependency order is now strictly: Class A → Phase 0 → A → B → C(story) → D(synthesis) → E(compete) → F(formalize) → G(seed) → H(verify) → I(agenda) → J(assess) → K(checkpoint). **No phase requires the output of a later phase.**

---

## 19. What this proposal deliberately does not do

| ⛔ Not done | Why |
|---|---|
| Modify `knowledge_os_protocoll.md` | Step 1 is under freeze-readiness review |
| **Execute Step 2** | Explicitly out of scope for this revision |
| Resolve `C-0007` | No discriminator available in the window |
| Adopt `T-0019` | The corpus declined to adopt it; §7 binds |
| Formalize `T-0015` | Its premise was refuted; §9.2 forbids it |
| Advance any structure past F1 | No defining condition is closed |
| Widen corpus scope | Governance decision — `OQ-6` |

---

*Traceability: Step-2 protocol proposal **v2**, 2026-09-22 · revised under a senior-architect review commission (15 instructions) · derived from the executed Step-1 reconstruction of F0001–F0025 · consistency review of §1–§17 performed, **18 defects found and classified**, including **5 BLOCKING (2 circular gates, 3 internal contradictions)** · every design decision cites the observation that forced it · ⛔ **PROPOSAL — not adopted, not frozen; the Step-1 Master Protocol is unmodified; Step 2 was NOT executed** · ⛔ **nothing in this document executes.***

> **⛔ Submitted for human review and freezing. Step 2 may begin once Class A is closed — today that is `RO-0014` alone.**
