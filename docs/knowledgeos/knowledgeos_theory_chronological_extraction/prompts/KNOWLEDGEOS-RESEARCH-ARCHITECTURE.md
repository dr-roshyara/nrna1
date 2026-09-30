# KnowledgeOS Research Architecture — v1.2 ⛔ FROZEN

| | |
|---|---|
| **Kind** | ⭐ **GOVERNING ARCHITECTURE** for the research process. ⛔ *Not a theory · not a protocol · not an implementation* |
| **Status** | ⛔ **FROZEN — v1.2, 2026-09-23.** ⭐ *`O-1` RESOLVED by L0 the same day (interpretation `A`); the resolution is an annotation under §8B, ⛔ not an architecture change.* *(v1.0 frozen; **Addendum A** v1.1; **Addendum B** v1.2 — both added under §9 with evidence, ⛔ neither editing a frozen element)* Changes require an architecture-change proposal with evidence (§9) |
| **Scope** | **ONE architecture, TWO bounded contexts, ONE controlled handoff, ONE feedback protocol** |
| **Binding** | The Phase-1 and Phase-2 protocols **reference** this document. ⛔ **They must not duplicate or redefine it.** |

## ⛔⛔ WHAT A FROZEN ARCHITECTURE DOES AND DOES NOT MEAN

> ## **A frozen architecture does not imply a complete theory, complete corpus processing, or completed validation.**
> ## ⭐ **It freezes the RULES by which those activities are subsequently performed.**

⛔ **Do not read "Architecture v1.0 FROZEN" as "KnowledgeOS theory is complete."** It is not, and nothing here claims it is.

```
RESEARCH ARCHITECTURE  ──── FROZEN ────┐
  rules · boundaries · responsibilities │
  handoff · discovery · reconstruction  │
  constructive completion · attack      │
  traceability                          │
                                        ▼
                              RESEARCH EXECUTION
                          ┌─────────┼──────────┐
                          ▼         ▼          ▼
                         C1        Q59        Q60
                      coverage  relationships completeness
                       ⛔ OPEN    ⛔ OPEN      ✅ 11/11
```

### Freeze status — per element

| Element | Status |
|---|---|
| **Research architecture** *(contexts · layers · ACL · invariants)* | ⛔ **FROZEN** |
| **Phase 1 → Phase 2 contract** *(handoff · Published Language)* | ⛔ **FROZEN** |
| ⭐ **Candidate Theory** | ✅ **NOT FROZEN — provisional and evolvable, by design** |
| **`P1-Q1`** Theory Discovery Index | ⛔ enforcement **FROZEN** · ⚠️ **no index exists yet — backlog open** |
| **`Q59`** relational completeness | ⛔ enforcement **FROZEN** · ⛔ **BACKLOG OPEN** — existing objects non-compliant |
| **`Q60`** theory completeness | ⛔ enforcement **FROZEN** · ✅ **11/11 passing today** |
| **`C1`** corpus coverage — **25 / 3,081 ≈ 0.8 %** | ✅ **OPEN — research execution progress, ⛔ not an architectural failure** |

> ⭐ **Enforcement frozen ≠ compliance achieved.** A rule can be binding while the work it demands is unfinished. **That is what a frozen architecture is for.**

---

> ### **Read this before executing either protocol. It is the governing boundary for Phase-1/Phase-2 responsibilities. ⛔ Do not duplicate or redefine it unless an explicit architecture change is being proposed.**

---

## 1. The two contexts

```
                 KNOWLEDGEOS RESEARCH ARCHITECTURE
                              │
                 ┌────────────┴────────────┐
                 │                         │
      HISTORICAL RECONSTRUCTION      THEORY RECOVERY,
      & EVIDENCE CONTEXT             CONSTRUCTION & VALIDATION
           (Phase 1)                      (Phase 2)
                 │                         │
      "What did the corpus            "What theory can be
       actually develop?"              constructed from it?"
                 │                         │
                 └───────────┬─────────────┘
                             │
                    CONTROLLED HANDOFF
                             │
              Evidence-backed Reconstruction Package
```

⭐ **The names are load-bearing.** *Historical Reconstruction & Evidence* and *Theory Recovery, Construction & Validation* each state what their context is permitted to do. Use them.

⛔ **Phase 1 does not produce "the theory." Phase 2 does not treat its first Candidate Theory as truth.**

## 2. Why these are genuinely bounded contexts — not stages

Three DDD tests, all passed:

| Test | Phase 1 | Phase 2 |
|---|---|---|
| **Own ubiquitous language** | file · event · evidence · claim · derivation · contradiction · gap · ordering · provenance | recovered theory · candidate · hypothesis · formalization · experiment · falsifier · maturity · readiness |
| **Own invariants** | ⛔ **the record is immutable and append-only**; negative evidence must be earned; no silent repair | ⛔ **L5 unreachable**; rivals coexist; constructed ≠ true; expert additions labelled `[E]` |
| **Own lifecycle** | closes when the window is read and validated | ⛔ **never closes** — iterative until freeze criteria |

> ⭐ **Same word, different meaning, in both contexts — which is the decisive test.** *Evidence* in Phase 1 is a locator into a file. *Evidence* in Phase 2 is a reason to believe a claim. Translating between them is work, not a cast.

## 2A. ⭐ Phase 1 has TWO responsibilities — not one

> ⛔ **Phase 1 is not "just historical."** Splitting it is what would have surfaced the two files excluded by document kind.

### Phase 1A — **Evidence Reconstruction**

Preserve: source · chronology · provenance · claims · definitions · assumptions · derivations · relationships · contradictions · gaps · scope/regime · theory objects · theory threads.

### ⭐ Phase 1B — **Theory Discovery Index**

⛔ **Without constructing the theory**, identify and index: potential theory-bearing material · mathematical structures · logical structures · domain invariants · conceptual distinctions · competing formulations · candidate mechanisms · theory branches · historical replacements · unresolved theoretical questions.

> ⭐ **1B is an INDEX, never a theory.** It answers *"where might theory be?"* — ⛔ never *"what is the theory?"*
>
> ⚠️ **Why it exists:** two files were excluded from theory because their *document kind* looked procedural. A Theory Discovery Index is keyed to **content**, not kind — so `ACL-1` gets an upstream partner instead of relying on Phase 2 to catch the error downstream.

## 2B. ⭐ Phase 2 has THREE jobs

| | Job | Question |
|---|---|---|
| **2A** | **Theory Recovery** | *what theory has the corpus already developed?* — includes targeted search **outside** the immediate file window |
| **2B** | **Constructive Completion** | *what is missing?* — ⛔ additions only where expert reasoning justifies them, labelled `[C]` / `[S]` / `[E]` |
| **2C** | **Theory Attack** | *does it survive?* — mathematical · logical · statistical · empirical · computational |

⚠️ **Validation is `2C`, not a separate context.** Layer 4 names the *activity*; `2C` names its *owner*. ⛔ **There is no third bounded context** — a validation context would need its own ubiquitous language and invariants, and it has neither.

## 3. The five layers

| Layer | Owner | Contains |
|---|---|---|
| **1 · Corpus** | ⛔ **nobody** — immutable source | files · code · schemas · governance standards · historical records |
| **2 · Reconstruction** | **Phase 1** | what was said · when · by which artifact · what depended on what · how concepts changed · what contradictions and gaps existed |
| **3 · Theory recovery & construction** | **Phase 2** | what theory was developed · what can be synthesized · what is missing · what must be added for coherence · what stays competing |
| **4 · Validation** | **Phase 2** | mathematical · logical · statistical · empirical · computational · implementation |
| **5 · Canonicalization** | ⛔ **neither phase** | validated → canonical → specification → implementation |

⛔ **Layer 5 is unreachable from both contexts.** It requires a governance act.

## 4. ⭐⭐ The handoff is an ANTI-CORRUPTION LAYER, not a pipe

> ### This is the architecture's most important element, and it is **evidence-derived**.

**Measured:** of the five substantive errors made so far, **four occurred at the translation from evidence to theory** — not inside either context:

| Error | Where | What went wrong |
|---|---|---|
| `knowledge-lint` called a violation | ⭐ **handoff** | a Phase-1 artifact typed with a Phase-2 category **that did not exist** |
| F0022/F0023 excluded | ⭐ **handoff** | a Phase-1 **document kind** used as a Phase-2 **relevance filter** |
| realization layers | ⭐ **handoff** | a Phase-2 construct built **without querying** Phase 1 or the corpus |
| *"zero propagation"* | ⭐ **handoff** | a Phase-1 **fact** (which files are dated later) mis-consumed in Phase 2 |
| invented `bar` | inside Phase 2 | construction before search |

> ⭐ **A boundary where four of five errors occur is not a pipe. It is where the discipline must live.**

### 4.1 The Published Language — `Research Reconstruction Package`

⛔ **Phase 1 hands over a Reconstruction Package. It does NOT hand over a Candidate Theory.**

```
corpus registry · evidence objects · file reconstruction records
⭐ THEORY DISCOVERY INDEX  (1B — keyed to content, never document kind)
theory objects · theory threads · definitions · assumptions · claims
derivations · relationships · contradictions · branches · merges
gaps · scope/regime · provenance · historical ordering
reconstruction confidence & status
```

Phase 2 returns:

```
recovered theory · synthesized theory · expert-derived candidates
candidate theory · theory change log · experiments · formal derivations
falsifications · validation obligations · open questions · readiness state
```

### 4.2 ⛔ The four ACL rules — each derived from an error above

| # | Rule | Prevents |
|---|---|---|
| **ACL-1** | ⛔ **A Phase-1 classification is never a Phase-2 relevance filter.** Document kind, artifact type and folder say **nothing** about theory-bearing content | F0022/F0023 |
| **ACL-2** | ⛔ **A Phase-2 category may not be applied to a Phase-1 artifact until the category is shown to exist in the corpus.** Search first | `knowledge-lint` |
| **ACL-3** | ⛔ **Before constructing, query Phase 1 and the corpus for the construct.** Naming collisions with corpus terms are UL violations | realization layers |
| **ACL-4** | ⛔ **A Phase-1 fact consumed in Phase 2 carries its Phase-1 scope with it.** A claim over a window is not a claim over the corpus | *"zero propagation"* |

### 4.2b Enforcement — every ACL rule has exactly one gate

| Rule | Phase-2 gate |
|---|---|
| `ACL-1` | `Q54` |
| `ACL-2` | `Q38` + `Q53` |
| `ACL-3` | `Q53` |
| ⭐ `ACL-4` | ⭐ `Q55` |

⛔ **An ACL rule without a gate is a statement, not a control.** `ACL-4` had none until this revision — and it is the rule that would have caught *"the correction propagated to zero files."*

### 4.2c ⭐⭐ `RA-9` — the TERM COLLISION RULE

> ## ⛔ **No new theoretical term may reuse an existing corpus term with a different meaning without explicit namespace qualification or renaming.**

This is a **DDD / ontology rule**, ⛔ **not a style preference.** A term that means one thing in the corpus and another in the reconstruction produces claims that are true in one reading and false in the other — and nothing in the text marks which is meant.

**Procedure, before minting any term:**

```
1. search the corpus for the term
2. if found with a DIFFERENT meaning:
      rename           (preferred)
   or qualify          "Phase-2 <term>" / "<term> (realization)"
   or establish the relationship EXPLICITLY and record it
3. record the decision
```

⛔ **Silent reuse is a UL violation** — and the corpus already measures its own collision load (`Baseline` ×5, `Constitution` ×5, `Capability` ×3). **Adding to it is a regression.**

**Worked violation — this architecture's own:**

| Corpus term | Corpus meaning | Reconstruction meaning | Verdict |
|---|---|---|---|
| **Structural** | the meta-model — element types | *does the schema encode it* | ⛔ **collision** |
| **Behavioral** | decision resolution (Decision Model + EEP) | *does the implementation enforce it* | ⛔ **collision** |

⭐ **Resolved by renaming** — see `§4.2d`.

### 4.2d ⭐ Realization layers — RENAMED to clear the collision

⛔ **Old (colliding):** ~~semantic · structural · behavioral · operational · empirical~~
✅ **New:** **`SPECIFIED` → `REPRESENTED` → `ENFORCED` → `INVOKED` → `EFFECTIVE`**

| Layer | Question |
|---|---|
| **SPECIFIED** | does the concept have a coherent, stable meaning? |
| **REPRESENTED** | does the architecture or schema encode it? |
| **ENFORCED** | does the implementation enforce it when executed? |
| **INVOKED** | is the capability actually used in the real workflow? |
| **EFFECTIVE** | does using it produce the claimed observable effect? |

⚠️ **And the corpus's critique applies to the arrows.** The corpus records its four viewpoints as *"complementary **siblings, not a hierarchy**"* and *"projections do not flow into each other."* ⭐ **These five are different: they carry a genuine dependency** — nothing is `ENFORCED` that is not `REPRESENTED`. ⛔ **But that dependency is an argued claim, not an assumption**, and it is recorded as such.

### 4.3 The shared kernel — deliberately minimal

**Only two things are shared:** ⭐ **identifiers** (`F####`, `T-####`, `HA-####`, …) and ⭐ **provenance links**. ⛔ **Nothing else.** Every other concept is translated at the boundary, and the translation is recorded.

## 5. The relationship — Customer/Supplier, upstream immutable

```
PHASE 1  ──── upstream, IMMUTABLE ────▶  PHASE 2
   ▲                                        │
   └──────── correction REQUEST ─────────────┘
              ⛔ never a write
```

**Phase 2 may query Phase 1 evidence. ⛔ Phase 2 may never rewrite historical evidence.**

When Phase 2 discovers *"Phase 1 missed something"*:

```
Phase 2 discovery → Phase 1 reconstruction CORRECTION
   → new reconstruction version → Phase 2 incorporates it
```

⛔ **Never:** `Phase 2 silently changes history.`

## 6. ⭐ Feedback is NORMAL, not exceptional

```
CORPUS → PHASE 1 → PHASE 2 → TEST/VALIDATE
                                   │
                            discovery of a gap
                                   ▼
                      TARGETED CORPUS RE-EXAMINATION
                                   ▼
                          Phase 1 update → Phase 2 update
```

⭐ **This has already happened and worked.** A Phase-2 experiment exposed an interpretive problem; targeted search recovered a missing role (`ES-004.1`, the analyst); the Candidate Theory changed and a false finding was withdrawn.

> **The architecture makes that the normal path — ⛔ not an exception, and not a failure.**

## 7. Responsibilities — the binding table

| Responsibility | Phase 1 | Phase 2 |
|---|---|---|
| Preserve source evidence | ✅ **YES** | ⛔ no modification |
| Historical reconstruction | ✅ **YES** | consume |
| Provenance | ✅ **YES** | preserve |
| Theory-object discovery | ✅ **YES** | consume + extend |
| Theory recovery | supporting | ⭐ **PRIMARY** |
| Theory synthesis | limited | ✅ **YES** |
| Expert construction | ⛔ no / very limited | ✅ **YES, justified and labelled `[E]`** |
| Mathematical formalization | record the source's | ✅ **construct / analyse** |
| Mathematical validation | ⛔ no | ✅ **YES, when ready** |
| Statistical validation | ⛔ no | ✅ **YES** |
| Experiments | evidence only | ✅ **YES** |
| Falsification | record historical | ✅ **YES** |
| Candidate Theory | ⛔ **no** | ✅ **YES** |
| Canonical Theory | ⛔ no | ⛔ **no** |
| Implementation | historical evidence only | laboratory only |
| Final canonicalization | ⛔ no | ⛔ later, by governance |

## 8. Architectural invariants

| # | Invariant |
|---|---|
| **RA-1** | The corpus is immutable |
| **RA-2** | Phase-1 records are append-only; Phase 2 never writes them |
| **RA-3** | The handoff is a Published Language; every crossing is translated and recorded |
| **RA-4** | ⛔ **Layer 5 is unreachable from both contexts** |
| **RA-5** | Corrections flow **backwards as requests**, never as writes |
| **RA-6** | ⭐ **A Phase-1 classification never determines Phase-2 relevance** (`ACL-1`) |
| **RA-7** | ⭐ **Feedback is normal**; a re-examination is not a failure |
| **RA-8** | Each context keeps its own ubiquitous language; ⛔ **shared kernel = identifiers + provenance only** |
| ⭐ **RA-9** | ⛔ **No new term reuses a corpus term with a different meaning** without renaming, qualification, or an explicitly recorded relationship |
| ⭐ **RA-11** | **Research State is authoritative for execution position**; ⛔ no protocol depends on a human to supply it |
| ⭐ **RA-12** | **Next work is RECOMPUTED from state**, ⛔ never read from a static TODO list |
| ⭐ **RA-10** | **Phase 1 carries a Theory Discovery Index (1B)** keyed to content, ⛔ never to document kind — enforced by **`P1-Q1`**, an artifact + gate, ⛔ not prose |
| ⭐ **RA-13** | **Phase 2 does not require Phase 1 completion.** ⛔ A blocked obligation blocks its **thread**, never the phase *(§8B)* |
| ⭐ **RA-14** | **Every stage of the provenance chain is traversed explicitly**; ⛔ no stage is skipped and each arrow is recorded *(§8B)* |
| ⭐⭐ **RA-15** | ⛔ **The four epistemic statuses are never collapsed** — `SOURCE-SUPPORTED` · `RECONSTRUCTION-VALID` · `THEORY-CONSISTENT` · `INDEPENDENTLY CORROBORATED`. **Repetition never raises maturity** *(§8B)* |
| ⭐ **RA-16** | **Governance state is READ before every execution unit**, never assumed. ⛔ A staging decision may not answer a reading question *(§8B)* |

## 8A. ⭐ ADDENDUM A — the three control layers *(v1.1, added under §9)*

> ⚠️ **Recorded as an addendum, not a silent edit.** ⛔ **No frozen element changed:** contexts, layers, handoff, ACL rules and invariants `RA-1`…`RA-10` are untouched. This **names a third control layer that already existed in pieces** and gives it authority.

**Evidence requiring it:** every continuation so far has depended on a human supplying execution position — *which files are done, what remains, what comes next*. ⛔ **An architecture that requires a human to remember where the work is has not finished specifying itself.**

```
┌──────────────────────────────────────────────────────┐
│ 1 · RESEARCH ARCHITECTURE   permanent — WHAT is it?  │
│     boundaries · responsibilities · invariants · ACL │
└───────────────────────────┬──────────────────────────┘
                            ▼
┌──────────────────────────────────────────────────────┐
│ 2 · RESEARCH PROTOCOLS      permanent — HOW is work  │
│     Phase 1 · Phase 2 · gates · procedures           │
└───────────────────────────┬──────────────────────────┘
                            ▼
┌──────────────────────────────────────────────────────┐
│ 3 · RESEARCH STATE      ⭐ dynamic — WHERE are we?   │
│     progress · backlog · next target · versions      │
└──────────────────────────────────────────────────────┘
```

| | |
|---|---|
| ⭐ **`RA-11`** | **`KNOWLEDGEOS-RESEARCH-STATE.md` is the authoritative record of execution position.** ⛔ **Neither protocol may depend on a human to supply it** |
| ⭐ **`RA-12`** | **Work is DETERMINED FROM STATE, not from prompts.** At the end of every execution unit, the next actionable work is **recomputed** from the current state — ⛔ never read from a maintained TODO list, which goes stale |

⛔ **Layer 3 holds no rules.** A rule that appears in State and not in Architecture or Protocols is **misplaced**, and moves.

## 8B. ⭐ ADDENDUM B — epistemic status, the provenance chain, concurrency, and the control plane *(v1.2, added under §9)*

> ⚠️ **Recorded as an addendum, not a silent edit.** ⛔ **No frozen element changed:** contexts, layers, handoff, ACL rules and invariants `RA-1`…`RA-12` are untouched. Addendum B adds **four invariants** and records **one open question it does not decide**.

**Provenance of this addendum.** An architecture review proposed seven corrections against `architecture_phase_1_phase_2.md` — ⛔ **the superseded chat proposal, not this artifact.** Checked against v1.1: **three were already implemented**, **one would have regressed it**, **four were genuine gaps**. Only the four are adopted.

| # | Review correction | Verdict against v1.1 |
|---|---|---|
| 1 | feedback loop must be primary, not a correction mechanism | ✅ **ALREADY** — §6, `RA-7` |
| 3 | "Validation appears twice" → add a Validation Context | ⛔ **REJECTED — see below** |
| 4 | Phase 1B must not construct theory | ✅ **ALREADY** — §2A, *"1B is an INDEX, never a theory"* |
| 2 | Phase 2 must not require Phase 1 completion | ⭐ **ADOPTED → `RA-13`** |
| 5 | an explicit epistemic pipeline | ⭐ **ADOPTED → `RA-14`** |
| 6 | four distinct epistemic statuses | ⭐⭐ **ADOPTED → `RA-15`** — the highest-value addition |
| 7 | a governance/control plane around both phases | ⭐ **ADOPTED → `RA-16`** |

### ⛔ Why correction 3 is REJECTED

The review proposed extracting **VALIDATION** into its own context between Phase 2 and canonicalization. ⛔ **v1.1 §2B already considered and refused exactly this**, on the DDD test this architecture uses throughout:

> *"Validation is `2C`, not a separate context. Layer 4 names the **activity**; `2C` names its **owner**. ⛔ There is no third bounded context — a validation context would need its own ubiquitous language and invariants, and it has neither."*

⭐ **Adopting the correction would reintroduce the confusion it was trying to remove**, and would break §2's three-test standard for what counts as a context. **The distinction the review actually wanted — provisional attack versus a final maturity boundary — is `RA-15`, not a new context.**

### ⭐ `RA-13` — Phase 2 does NOT require Phase 1 completion

> **Phase 1 is file-driven; Phase 2 is theory-driven. ⛔ Neither waits for the other.** Phase 2 may begin as soon as a sufficiently coherent Theory Object, Thread or cluster exists. **The Reconstruction Package is a data contract, ⛔ never a completion gate.**

⚠️ **Why this is an architecture rule and not an execution note:** it was stated only in `prompts/readme.md`, which is Layer 3 (State). ⛔ **§8A already rules that "a rule that appears in State and not in Architecture or Protocols is misplaced, and moves."** This is that move.

⭐ **Consequence, stated because it is the failure mode:** ⛔ **a blocked research obligation blocks its own thread, never Phase 2.** *A thread with an unresolved evidence obligation is `BLOCKED`; every other actionable thread continues.* **Escalating a thread-level block to a phase-level stop is an invalid transition.**

### ⭐ `RA-14` — the provenance chain, and what each arrow costs

⛔ **One chain, not a second one.** The standing chain is `Evidence → Reconstruction → Hypothesis → Derivation → Validation → Canonical`. Addendum B does **not** replace it; it names the artifacts each stage carries and the phase that owns them:

```
  SOURCE EVIDENCE ──▶ RECONSTRUCTION ──▶ THEORY OBJECT ──▶ THEORY THREAD
        └─────────── Phase 1 ───────────────────────────────────┘
                                    │
                                    ▼
   RECOVERED ──▶ SYNTHESIZED ──▶ [E] EXPERT-DERIVED ──▶ CANDIDATE THEORY
        └─────────── Phase 2A / 2B ──────────────────────────────┘
                                    │
                                    ▼
                          ATTACK / TEST  (2C)
                                    │
                                    ▼
              VALIDATED ──▶ CANONICAL   ⛔ Layer 5 · governance act
```

> ⛔ **Every arrow preserves provenance, and every arrow is a place where a claim can gain strength it has not earned.** ⭐ **`RA-15` is the instrument that stops it.**

### ⭐⭐ `RA-15` — FOUR epistemic statuses, never collapsed into one

> ## ⛔ **A single `status` field cannot carry these four. Collapsing them is how repetition becomes corroboration.**

| # | Status | The question it answers | Who can establish it |
|---|---|---|---|
| **A** | **SOURCE-SUPPORTED** | does the source actually say this? | Phase 1 · verbatim quotation |
| **B** | **RECONSTRUCTION-VALID** | did Phase 1 faithfully establish what the source meant in context? | Phase 1 · §9/§9A |
| **C** | **THEORY-CONSISTENT** | does it fit the emerging theory? | Phase 2 |
| **D** | ⛔ **INDEPENDENTLY CORROBORATED** | does a **genuinely independent** source support it? | ⛔ **Phase 2 — and rarely** |

**Worked example, from the record that produced this rule:**

```
F0032 says formal n=0
   A  SOURCE-SUPPORTED        ✅ verbatim
   B  RECONSTRUCTION-VALID    ✅ §9/§9A recorded
   C  THEORY-CONSISTENT       ⚠️ possibly
   D  INDEPENDENTLY CORROB.   ⛔ NO — same programme, same day as F0026,
                                  reusing F0026's labels and evidence
```

⛔ **`D` is the one that is almost always claimed and almost never earned.** The binding rule is already stated in the Step-2 protocol §11.1 and is **restated here as an architectural invariant, not a protocol detail**:

> ⛔ **Repetition NEVER raises maturity.** *"Seven repetitions of one copied claim are **one** observation."*

**Three tests that defeat a claim of `D`:**

| Test | Fails `D` when |
|---|---|
| **Same author / programme / day** | the second source is the first one's neighbour |
| ⭐ **Synthesis of the first** | the second source **cites** the first — *transmission, not corroboration* |
| **Shared underlying evidence** | both rest on the same register, count or run |

⭐ **Evidence for this invariant, both independently found:** governance verification `GVR-F0032` §N-1 rejected *"four independent corroborations"* on the first test; the F0001/F0010 retrofit rejected six objects' apparent two-source support on the second — **F0001's own traceability names F0010 as one of its four sources.**

### ⭐ `RA-16` — the control plane is an INPUT to execution, not a wrapper around it

> ⛔ **`RA-11` made Research State authoritative for *execution position*. It said nothing about *authority to execute*. That gap is `RA-16`.**

```
┌──────────────────────────────────────────────────────┐
│ 0 · CONTROL / GOVERNANCE   authority · scope         │
│     admission · provenance · identity · audit        │
│  ⛔ OWNED BY GOVERNANCE — this architecture does not │
│     define its rules, only that they must be READ    │
└───────────────────────────┬──────────────────────────┘
                            ▼
              1 · ARCHITECTURE → 2 · PROTOCOLS → 3 · STATE
```

**Before any execution unit, the current governance state is read**, not assumed: active restrictions · unresolved authorization decisions · verification records covering prior units · pending corrections · the conformance ledger.

⛔ **This mints no new mechanism.** The mechanism exists — `governance/gates.yaml`, `governance/gate-runner.py`, `.claude/hooks/governance-preflight.sh` — and `prompts/readme.md` §*Immediate task* already requires a state inspection before a work batch. ⭐ **`RA-16` binds those together and says the binding is architectural. Building a second preflight layer beside them would be an `ACL-3` violation by this architecture's own rule.**

**Evidence requiring it.** Two governance verification records covering the immediately preceding units sat unread in the working tree while the next unit executed. They were seen, classified as *not mine to commit*, and never asked about as *evidence to read*. ⛔ **Two already-identified defects then recurred in the very next unit** — a line-count error and a stale conformance ledger — **and the authority question they raised was carried forward unexamined into three more files.**

> ⭐ **The generalisation, which is the point of the invariant: a staging decision was allowed to answer a reading question. Those are different questions, and only one of them is about evidence.**

### ✅ `O-1` — **RESOLVED BY L0, 2026-09-23 · interpretation `A`**

> ## ⭐ **"Other" means Theory Objects OTHER THAN those the current file establishes or directly evidences.**
>
> **Phase 1 may create and update the objects its own file establishes.** ⛔ **It may not upgrade or downgrade an unrelated object merely because this file was audited.**

| Consequence | |
|---|---|
| **Gate 9** | ⭐ **SATISFIABLE.** `dossier_status` may reach `COMPLETE` |
| **§9 steps 24–27** | unblock — ⛔ **for own-file objects only** |
| **Master Protocol §397** | ⭐ the mission statement's *"maintain a provenance-preserving Theory Object Registry"* becomes dischargeable |

⚠️ **A constraint that survives this resolution:** `SAFE-RESEARCH-EXCEPTION-01` still forbids *"changing theory v0.9 or **existing registry rows**."* ⭐ **So an own-file object is corrected by an APPEND-ONLY overlay, never by editing its row** — the `C4-a` remedy **B** pattern, and `RCI-015`.

⛔ **The original question is preserved below as issued** *(`RCI-015` — a resolved question is annotated, not deleted)*.

### ⚠️ `O-1` as originally recorded — ⛔ superseded by the resolution above

**Scope of the Theory-Object rule during a Phase-1 unit.** A retrofit instruction read *"do not upgrade or downgrade **other** Theory Objects merely because this file was audited."* Two readings:

| | Reading | Consequence |
|---|---|---|
| **A** | *other* = objects **other than those this file establishes** | Phase 1 updates its own objects; gate 9 is satisfiable |
| **B** | *other* = **any** Theory Object | ⛔ **gate 9 is never satisfiable; no file can ever reach `dossier_status: COMPLETE`** |

**Measured:** gate 9 is `NOT_SATISFIED` and `dossier_status` is `INCOMPLETE_BY_DESIGN` in **all five** files that currently carry a gate sheet — `F0001 · F0010 · F0026 · F0027 · F0032`.

⚠️ **Relevant fact, offered as evidence and not as an answer:** the Master Protocol's own mission statement (§397) requires Phase 1 to *"maintain a provenance-preserving Theory Object Registry (§19A)"*, and §4.1 lists Theory Objects inside the Reconstruction Package.

> ⛔ **Reading B would make Phase 1 unable to discharge an obligation its own protocol assigns it.** ⭐ **That is a concrete contradiction, which is exactly what §9 requires before an architecture change — but the instruction is L0's, not this architecture's, so `O-1` is RECORDED and REFERRED, not resolved.**

---

## 9. Change control

⛔ **This architecture is referenced, never restated.** A protocol that contradicts it is **defective**; the protocol changes, not the architecture. Changing the architecture requires an explicit architecture-change proposal with evidence — ⭐ **exactly as `ACL-1`…`ACL-4` were derived from five recorded errors.**

---

*KnowledgeOS Research Architecture · **v1.2** · one architecture · two bounded contexts · one Published Language · one ACL with four evidence-derived rules · **16 invariants** · **4 epistemic statuses, never collapsed** · ⭐ **`O-1` RESOLVED by L0 — interpretation `A`** · ⛔ not a theory, not a protocol · referenced by both phase protocols.*

> ### ⛔ **Superseded proposal:** `architecture_phase_1_phase_2.md` is the **chat proposal that produced v1.0**. It is a historical record. ⛔ **Do not review, cite or amend it as the architecture — this file is the architecture.**
