# Phase 2 as a Theory Laboratory — READ → CONSTRUCT → IMPLEMENT Review

| | |
|---|---|
| **Subject** | `prompts/knowledge_os_step2_theory_construction_protocol.md` v2.3 §§3–3B |
| **Purpose** | Make Phase 2 operate explicitly as a **scientific theory laboratory** |
| **Date** | 2026-09-22 |
| ⛔ **Not done** | No protocol edit · no code · Phase 2 not executed · no theory frozen |
| **Result** | **2 structural contradictions · 3 theory-neutrality violations · 5 missing laboratory capabilities · 14 proposed changes** |

---

## 1. Current protocol assessment

**The protocol already does READ and CONSTRUCT well.** The phase pipeline maps cleanly:

| Activity | Current phases | Output |
|---|---|---|
| **READ** | 0 sweep · A admit events · B admit objects · C narrate | `HISTORICAL-STORY.md` |
| **CONSTRUCT** | D synthesize · B2 emerge · E compete · F formalize · G seed | `THEORY-SEED.md` |
| **IMPLEMENT** | ⛔ **§3A/§3B only — not a phase** | — |

**Two structural defects, both verified by inspecting the specification:**

### ⛔ Defect 1 — the algorithm is single-pass while §3A claims iteration

`§3A.1` states the loop *"…improve protocol AND model → run again → freeze."* But `§3.2`'s algorithm is `STEP2(registries): Phase 0 → K`, executed **once**. Checked: no `while`, no `iterate`, no `repeat`, no iteration variable.

> **The iteration exists in prose and not in the executable specification.** `READ → CONSTRUCT → IMPLEMENT → again` is currently **not expressible** in the protocol's own algorithm.

### ⛔ Defect 2 — IMPLEMENT is adjacent to the process, not inside it

`§3A` and `§3B` precede `§4` and are **never referenced by the algorithm** (verified: the string `3A`/`3B` does not occur inside `§3.2`). Phases run `0 A B C D B2 E F G H0 H I J K` — there is **no IMPLEMENT phase**.

> Implementation is therefore architecturally described but **procedurally orphaned**. Nothing says *when* it happens, *what it consumes*, or *what it returns to the loop*.

---

## 2. READ — definition

> **READ establishes what the corpus says. It never establishes what the corpus means.**

| READ does | READ must never |
|---|---|
| reconstruct available evidence | ⛔ silently become construction |
| preserve historical provenance | ⛔ smooth over reversals |
| identify definitions · assumptions · claims · derivations · conclusions | ⛔ resolve a contradiction it finds |
| identify contradictions and revisions | ⛔ prefer the later or more frequent claim |
| identify what is unknown | ⛔ convert *not searched* into *not present* |
| **distinguish source evidence from interpretation** | ⛔ import a later interpretation into an earlier record |

**Already enforced:** `Q16` (no L2 in the story) · `Q25` (story never revised by a later phase) · Step-1 §2 (complete-file atomicity) · §25 (negative-evidence discipline).

### ⚠️ The gap — re-READ under a known theory

Reading currently happens **once**, in Phase A/B; re-reading is permitted only under a named obligation. But in **iteration 2+**, re-reading happens *while a candidate theory is already held*. That is textbook confirmation bias, and the protocol has **no control for it**.

`Phase H0` guards verification against defending the seed. **Nothing guards re-reading against confirming it.**

---

## 3. CONSTRUCT — definition

> **CONSTRUCT forms candidate theory from evidence. It is allowed to produce what no single file states.**

Permitted: candidate concepts · candidate relationships · abstractions · derivations · competing interpretations · candidate mathematical/statistical/logical structures · emergent principles · hypotheses · **split, merge, replace, abandon**.

**Every constructed object records:** evidence · reasoning · identity basis · epistemic level · assumptions · falsifiability · provenance.

### ⛔ The statement the protocol is missing

> ## **CONSTRUCTED does not mean TRUE.**

Verified absent. The epistemic ladder *implies* it (L2 = hypothesis) and `Q3` enforces strength ≤ evidence — but **the sentence is never stated**, and it is the single most load-bearing sentence in a theory laboratory.

A constructed theory may later be **supported · weakened · refuted · split · merged · replaced · abandoned** — and the protocol must say so where construction is defined, not only where verification is.

---

## 4. IMPLEMENT — definition

> **IMPLEMENT builds the laboratory's ability to represent, inspect, reproduce and test the research construction.**
> ⛔ **It does not implement the KnowledgeOS theory.**

### ⛔ The ordering error in the current protocol

The correct sequence is:

```
READ      → identify what must be represented
CONSTRUCT → identify the MINIMUM representation that construction actually needed
IMPLEMENT → build only that; test whether it holds
```

But `§3B.3` presents a **pre-specified 10-primitive kernel** as *"the corrected kernel"* — a fixed model stated **before** the construction whose needs it is supposed to serve.

⚠️ **This is defensible but mislabelled.** The kernel *was* derived from real Step-1 registries, so it is a legitimate **iteration-1 input**. It is **not** a settled model, and presenting it as *"corrected"* invites exactly the failure mode `§3B.2` forbids: the representation deciding what gets represented.

---

## 5. Current contradictions

| # | Contradiction | Location |
|---|---|---|
| **X-1** | Iterative loop claimed; single-pass algorithm specified | §3A.1 vs §3.2 |
| **X-2** | IMPLEMENT is a first-class activity but not a phase | §3A/3B vs §3.2 |
| **X-3** | *"Theory-neutral core"* vs a **named, fixed 10-primitive kernel** | §3B.2 vs §3B.3 |
| **X-4** | *"Domain is KnowledgeOS theory"* vs *"domain contains only methodological concepts"* | §3B.1 vs §3B.2 — ⭐ already in changeset `E-01` |

---

## 6. Theory-neutrality violations

| # | Violation | Severity | Why it is one |
|---|---|---|---|
| **V-1** | §3B.3's kernel labelled *"corrected"* | **HIGH** | Fixes the representation before construction establishes what needs representing |
| **V-2** | §9.1 enumerates `S-1..S-5` as *the* structures | **MEDIUM** | ⚠️ Mitigated by Phase F.1's discovery step, but a reader still meets a list before a search |
| **V-3** | `Verification` · `Competition` named in the primitive set | **MEDIUM** | Adjudicated **PREMATURE**/**OPEN**; naming them in a kernel pre-commits the ontology |

### Audit of every named concept

| Concept | Classification |
|---|---|
| `Relationship` | ⭐ **1 · EVIDENCE-FORCED** — 19 edges; an edge is not a node |
| `Event` (≠ Source) | ⭐ **1 · EVIDENCE-FORCED** — 8 of 25 files carry >1 typed event |
| n-ary `Derivation` | ⭐ **1 · EVIDENCE-FORCED** — *"two of four grounds falsified"* |
| Reject `type = UNKNOWN` | ⭐ **1 · EVIDENCE-FORCED** |
| Maturity not writable | **2 · METHODOLOGY-FORCED** |
| Absence distinction | **2 · METHODOLOGY-FORCED** |
| `TheoryObject` | **3 · USEFUL LABORATORY CONCEPT** — ⛔ not an aggregate root |
| `TheoryEvolution` | **3 · USEFUL LABORATORY CONCEPT** |
| `Provenance` | **3** — invariant forced, form open |
| `TheoryThread` | **4 · PROVISIONAL RESEARCH CONCEPT** — projection refuted by test |
| `S-1..S-5` structures | **4 · PROVISIONAL** — all at F1, none reaches F2 |
| `Classification` representation | **5 · OPEN** |
| `Assertion` | **5 · OPEN** — 3 of 6 boundaries unstatable |
| Bounded contexts | **5 · OPEN** — only 2 of 4 demonstrated |
| `Competition` | ⛔ **6 · PREMATURE** — over-unifies three corpus shapes |
| `TheoryObject` as aggregate root | ⛔ **6 · PREMATURE** — no invariant requires it |
| `KnowledgeObject` · fundamental relations | ✅ **absent — correctly** |

---

## 7. Premature architecture decisions

Carried from adjudication, unchanged: `Competition` entity · aggregate root · freezing four bounded contexts · `SearchRecord` as a named entity · `ClassificationAssertion` as *the* mechanism · package names · any persistence beyond the pilot.

**Added by this pass:** ⛔ **the 10-primitive kernel as a settled model** (V-1).

---

## 8. Missing laboratory capabilities

| # | Missing | Why it matters |
|---|---|---|
| **M-1** | ⛔ **Iteration identity** — no `iteration_id`, no seed version | Cannot answer *"which pass produced this?"*; `THEORY-EVOLUTION` tracks item changes but not laboratory runs |
| **M-2** | ⛔ **`REPRESENTATIONAL_GAP` record** | §3B.4 defines the *test* but no record schema and no fault classification. Gaps would be reported in prose and lost |
| **M-3** | ⛔ **Experiment record** | §13 has eight test *types* but no `hypothesis → design → execution → result → interpretation` object. A laboratory without experiment records is not a laboratory |
| **M-4** | ⛔ **Re-READ bias control** | §2 above |
| **M-5** | ⛔ **Laboratory-insufficiency finding** | No way to record *"the failure is in the instrument, not the theory"* — distinct from a theory finding and from a protocol defect |

---

## 9. Proposed minimal changes

**14 changes.** Full text and classification in `PHASE2-READ-CONSTRUCT-IMPLEMENT-CHANGESET.md`.

| # | Change | Class |
|---|---|---|
| **R-01** | State the laboratory principle prominently | `LABORATORY_REQUIRED` |
| **R-02** | Define READ / CONSTRUCT / IMPLEMENT as the three activities, mapped to phases | `METHODOLOGICALLY_FORCED` |
| **R-03** | ⭐ Make the algorithm **iterative** — wrap phases in an iteration with an identity | `METHODOLOGICALLY_FORCED` |
| **R-04** | ⭐ Add **IMPLEMENT as a phase** consuming construction's representation needs | `METHODOLOGICALLY_FORCED` |
| **R-05** | ⭐ State **CONSTRUCTED does not mean TRUE** where construction is defined | `METHODOLOGICALLY_FORCED` |
| **R-06** | Re-label the kernel *iteration-1 derived, revisable* | `LABORATORY_REQUIRED` |
| **R-07** | Add `REPRESENTATIONAL_GAP` record with fault classification | `LABORATORY_REQUIRED` |
| **R-08** | Add `Experiment` record | `LABORATORY_REQUIRED` |
| **R-09** | Add re-READ bias control | `METHODOLOGICALLY_FORCED` |
| **R-10** | Add laboratory-insufficiency as a distinct finding class | `LABORATORY_REQUIRED` |
| **R-11** | Separate the conceptual five-layer view from the dependency direction | `REVERSIBLE_DESIGN` |
| **R-12** | State *research discovery ≠ final theory component* for the eleven emergent kinds | `METHODOLOGICALLY_FORCED` |
| **R-13** | Anti-over-engineering rule | `LABORATORY_REQUIRED` |
| **R-14** | Revise the freeze criterion to the laboratory sequence with four statuses | `METHODOLOGICALLY_FORCED` |

⭐ **R-03 and R-04 together close X-1 and X-2** — the two structural defects. They are the minimum needed for the protocol to *be* a READ→CONSTRUCT→IMPLEMENT loop rather than describe one.

---

## 10. Remaining open questions

| # | Question | Why it stays open |
|---|---|---|
| **OQ-L1** | What is the right iteration granularity — per batch, per obligation, per representational gap? | Only running one iteration will show |
| **OQ-L2** | Should `Experiment` subsume `Test`, or are they distinct? | 8 test types, 0 executed — no evidence yet |
| **OQ-L3** | Can re-READ bias be controlled procedurally, or does it need a fresh reader? | `F0015` suggests a **blind** reader is the only reliable control — expensive |
| **OQ-L4** | Does the kernel survive iteration 2, or does construction demand primitives we have not imagined? | ⭐ **The central empirical question of Phase 2B** |
| **OQ-L5** | Is a disposable laboratory actually disposable, or does tacit knowledge accumulate in the code? | Test by rewriting it once |
| **OQ-L6** | When does READ legitimately reopen? | Obligation-gated today; may be too strict for an iterative loop |

---

*Traceability: READ→CONSTRUCT→IMPLEMENT review, 2026-09-22 · protocol v2.3 §§3–3B assessed against the laboratory principle · 2 structural contradictions verified by inspecting the specification · 3 theory-neutrality violations · 5 missing laboratory capabilities · 17 named concepts classified · 14 changes proposed · ⛔ **no protocol edit · no code · Phase 2 not executed · nothing frozen** · awaiting explicit authorization.*
