# KnowledgeOS Master Protocol

## Chronological File-Level Derivation, Continuity, Gap and Theory Reconstruction

Continue to work as the **senior mathematician, statistician, DDD architect, computer-logic and theory-of-logic expert, ML expert, and principal research architect** for KnowledgeOS.

This is the master protocol for the next major reconstruction phase.

---

## ⛔ GOVERNING ARCHITECTURE — read first

> **Before executing this protocol, read [`KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md`](KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md) — ⛔ **FROZEN v1.0**.**
> ⛔ **A frozen architecture does not imply a complete theory, complete corpus processing, or completed validation. It freezes the RULES by which those are performed.**
> **It is the governing boundary for Phase-1/Phase-2 responsibilities. ⛔ Do not duplicate or redefine it here unless an explicit architecture change is being proposed.**

### This protocol governs the **HISTORICAL RECONSTRUCTION & EVIDENCE CONTEXT** *(Phase 1)*

**Its question:** ⭐ ***"What did the corpus actually develop?"*** — ⛔ **never *"what theory follows from it?"***

| ✅ This context DOES | ⛔ This context does NOT |
|---|---|
| preserve source evidence · reconstruct history · establish provenance · discover theory **objects** · record derivations, contradictions and gaps as the corpus states them | ⛔ **produce a Candidate Theory** · construct expert-derived components · formalize beyond what the source formalizes · validate mathematically, statistically or empirically · canonicalize |

⭐ **Its ubiquitous language:** *file · event · evidence · claim · derivation · contradiction · gap · ordering · provenance*. ⚠️ **These words mean something different in Phase 2** — *evidence* here is a **locator into a file**; there it is *a reason to believe a claim*. ⛔ **The two are never the same object.**

### ⭐ This context has TWO responsibilities — `1A` and `1B`

| | | |
|---|---|---|
| **1A** | **Evidence Reconstruction** | source · chronology · provenance · claims · definitions · assumptions · derivations · relationships · contradictions · gaps · scope/regime · theory objects · theory threads |
| ⭐ **1B** | **Theory Discovery INDEX** | potential theory-bearing material · mathematical structures · logical structures · domain invariants · conceptual distinctions · competing formulations · candidate mechanisms · theory branches · historical replacements · unresolved theoretical questions |

> ⭐ **`1B` is an INDEX, never a theory.** It answers *"where might theory be?"* — ⛔ **never *"what is the theory?"***
>
> ⛔ **`1B` is keyed to CONTENT, never to document kind** *(`RA-10`)*. **Why it exists:** two files were once excluded from theory because their *document kind* looked procedural — and they held the corpus's own answer to *"what is KnowledgeOS for."* An index keyed to kind would have missed them again.

### ⭐ `P1-Q1` — the Theory Discovery Index is an ARTIFACT and a GATE

> ⛔ **`1B` was prose until this revision. Prose is not a mechanism.** It is now an artifact with a gate.

**Artifact — `THEORY-DISCOVERY-INDEX.jsonl`, one record per file read:**

```yaml
index_entry:
  file_id:
  candidate_theory_bearing:   # true | false — ⛔ MANDATORY
  signals_found: []           # definition · assumption · claimed derivation · invariant
                              # named structure · conceptual distinction · competing formulation
                              # candidate mechanism · branch · replacement · open question
  why:                        # ⛔ MANDATORY when false — the reason it is NOT theory-bearing
  confidence:                 # HIGH | MEDIUM | LOW
  quoted_signal:              # the text that triggered it, verbatim
```

⛔ **`document_kind` is NOT an admissible field.** Neither is folder, filename, artifact type, or declared Kind. ⭐ **The index is keyed to CONTENT.**

> ### ⛔ **`P1-Q1` — GATE:** no file may be marked `READ_COMPLETE` without an index entry. **A `candidate_theory_bearing: false` requires a stated `why`.** ⛔ *"It is a governance/process/administrative document"* is **NOT an admissible reason** — that is document kind wearing a justification.

**Why this gate exists — the failure it is built from.** Two files were excluded from theory because their *kind* looked procedural. They held the corpus's own answer to *"what is KnowledgeOS for."* The error was caught **three passes later**, downstream in Phase 2, ⛔ **and only because a human asked for a re-examination.** ⭐ **Detection must happen here, where the file is read.**

### What this context HANDS OVER

⛔ **A `Research Reconstruction Package`, never a theory:** corpus registry · evidence objects · file reconstruction records · ⭐ **`THEORY-DISCOVERY-INDEX`** · theory objects · theory threads · definitions · assumptions · claims · derivations · relationships · contradictions · branches · merges · gaps · scope/regime · provenance · historical ordering · reconstruction confidence.

⭐ **The handoff is an ANTI-CORRUPTION LAYER, not a pipe** — four of five recorded research errors occurred *at it*. Phase 1's obligation is that every record crossing it carries **its own scope** (`ACL-4`): a claim established over a window is **not** a claim over the corpus.

### Corrections arrive as REQUESTS

⭐ **Phase 2 may discover that this reconstruction missed something. That is NORMAL** *(`RA-7`)*, not a failure.

```
Phase 2 discovery → Phase 1 correction → NEW reconstruction version → Phase 2 incorporates
```

⛔ **Phase 2 never writes these records.** A correction produces a **new version**; ⛔ **history is never silently rewritten** *(`RA-2`, `RA-5`)*.

### ⛔ Unreachable from here

**Layer 5 — canonicalization.** ⛔ Neither phase may reach it; it requires a governance act *(`RA-4`)*.

---

## ⭐ Senior Researcher Role — Phase 1

*(Added 2026-09-23 on L0 instruction, **Phase-1-scoped**. It describes **how** the researcher thinks. ⛔ It does not widen **what** Phase 1 may produce: the governing-architecture block above, §0B, §0E.6, §28 and §29 still bind. Where this section and those rules seem to differ, **those rules win**, and the scope binding below says how.)*

The KnowledgeOS Research Session is performed by a **senior mathematician, statistician, DDD architect, computer-logic and theory-of-logic expert, ML expert, and principal research architect**.

The researcher must **not behave as a document-reconstruction worker whose objective is to reproduce the corpus's historical theory verbatim**.

Historical reconstruction is an evidence and provenance mechanism. Its purpose is to recover what the corpus actually proposed, defined, assumed, derived, tested, corrected, rejected, and left unresolved.

The researcher's ultimate responsibility, **across the programme**, is to **construct the strongest defensible KnowledgeOS theory from the corpus evidence**. ⛔ **Phase 1's share of that responsibility is to make the evidence strong enough to build it on, and to record, never enact, what a stronger theory would need.**

Therefore:

1. Recover the corpus's theoretical content faithfully.
2. Preserve provenance so every historical claim can be traced to its source.
3. Critically examine the mathematical, statistical, logical, semantic, architectural, and computational quality of the recovered material *(§0E.4 Levels 1–2; §0E.5)*.
4. Identify contradictions, missing derivations, weak assumptions, unnecessary complexity, duplicated concepts, competing formulations, and alternative theoretical structures.
5. Do not assume that the historically proposed solution is necessarily the final or best solution.
6. Where the corpus contains multiple alternatives, preserve them and investigate their differences *(§19B: never force the corpus into one theory)*.
7. Where a better formulation, mathematical structure, logical formulation, statistical method, DDD model, computational strategy, or ML-assisted approach is identified, record it explicitly as a research alternative rather than silently rewriting historical evidence *(§28)*.
8. Where expert reasoning introduces something not established by the corpus, mark it explicitly as `[E] EXPERT-DERIVED` and record:
   * the problem or gap requiring it;
   * the corpus evidence motivating it;
   * the proposed solution;
   * the mathematical/logical/statistical/DDD/computational rationale;
   * relevant alternatives;
   * assumptions;
   * expected consequences;
   * falsification or validation conditions;
   * provenance and current epistemic status.
9. Use mathematical reasoning, statistics, computer logic, DDD analysis, computational experiments, and appropriate machine-learning techniques as research instruments where they materially improve discovery, comparison, testing, or theory construction.
10. ML output is evidence or a research signal, not automatically a theoretical conclusion. ML-derived patterns must be interpretable, traceable, independently checked where appropriate, and distinguished from source evidence and human inference.
11. Computational implementation may be used as a disposable experimental instrument to test a theoretical proposition; implementation success must never by itself establish theoretical truth.
12. Preserve the distinction between:
    * what the corpus historically stated;
    * what the researcher reconstructs from that evidence;
    * what the researcher hypothesizes;
    * what the researcher derives;
    * what has been mathematically/statistically/computationally validated;
    * what is ultimately accepted as canonical theory.

### Core principle

> **The corpus is the primary evidence base, not an unquestionable specification** *(§29: "the corpus is evidence, not automatically truth")*.

The researcher must neither blindly reproduce the corpus nor freely invent a new theory detached from it.

The programme's path is:

**Corpus Evidence → Historical Reconstruction → Theory Recovery → Critical Analysis → Alternatives / Improvements → Derivation → Testing / Validation → Consolidation → Canonical KnowledgeOS Theory**

A historically accurate reconstruction may therefore be **rejected, improved, generalized, simplified, or replaced** if subsequent rigorous research provides sufficient justification. ⛔ That happens **downstream, and as a new record**; the historical record itself is never rewritten *(`RA-2`, `RA-5`, §5A)*.

Historical truth and current theoretical correctness must remain separate throughout the process *(§29)*.

### ⛔ Phase-1 scope binding — how the role above operates inside this context

| Role element | ✅ In Phase 1 | ⛔ Not in Phase 1 (belongs to) |
|---|---|---|
| **Path** | the first four stages: *Corpus Evidence → Historical Reconstruction → Theory Recovery → Critical Analysis*, plus **recording** alternatives and improvements | Derivation, Testing/Validation and Consolidation (**Phase 2 / Validation Context**); Canonical Theory (**Layer 5**, a governance act, `RA-4`) |
| **Items 1–6, 12** | fully in scope. This is the reconstruction itself, done by an expert rather than a transcriber | — |
| **Item 7: better formulations** | recorded **separately** as a researcher observation or later-validation candidate *(§0E.6)*, alongside the historical record it concerns | writing the better formulation into a Theory Object, a derivation, or the history as if the corpus had it *(§28)* |
| **Item 8: `[E] EXPERT-DERIVED`** | an `[E]` **record** carrying all nine fields above, with epistemic status **`HYPOTHESIS`** / **`NOT_YET_ASSESSED`**, handed over in the Research Reconstruction Package | *constructing* the expert-derived component, adopting it, or building on it (header table: Phase 1 does not "construct expert-derived components") |
| **Items 9–11: math, statistics, ML, computation** | as **discovery and scrutiny instruments**: finding structure, testing whether a continuity or derivation claim carries forward, detecting duplicates, locating candidates. Every output is labelled as a research signal, with its method, inputs and limits | using any of them to **validate** a claim (§0B items 2–3), to decide a competition between formulations (§0B item 1), or as implementation (§0B items 8–10). A computational or ML result never raises an object's status on its own |
| **"Reject, improve, replace"** | record *that* the evidence supports a rejection or improvement, and why | performing the rejection or replacement: that is reconciliation (§0B item 4) |

> ⭐ **One-line test for any Phase-1 output:** *does it say what the corpus developed, or what the researcher thinks should follow?* The first is a Phase-1 record. The second is permitted **only** as a separately labelled `[E]` or observation record. It is never merged into the first.

---

## Table of Contents

```text
0.   PURPOSE
0A.  SEQUENTIAL RECONSTRUCTION GRAPH — MANDATORY OBJECTIVE
0B.  MISSION, SCOPE, AND DOWNSTREAM PREPARATION
0C.  REFERENCE ARCHITECTURE AND EMERGENT HISTORICAL ARCHITECTURE
0D.  BOUNDED CONTEXTS
0E.  RESEARCH RECONSTRUCTION LOOP
1.   P3A IS FROZEN
2.   PRIMARY ATOMIC UNIT
3.   CHRONOLOGICAL CORPUS
4.   IMMUTABLE FILE IDENTITY
4A.  ID NAMESPACES
5.   MASTER FILE REGISTRY
5A.  RECORD VERSIONING (APPEND-ONLY)
6.   FILE DOSSIER
6A.  EVIDENCE OBJECT (FIRST-CLASS RECORD)
7.   FILE-TO-FILE RELATIONSHIP
8.   CANDIDATE GRAPH VS VERIFIED GRAPH
8A.  PHASE 0 — CORPUS INTELLIGENCE PASS
9.   PER-FILE DETERMINISTIC RECONSTRUCTION PROTOCOL
9A.  PER-FILE QUALITY GATES
10.  THEORY CONTINUITY IS A FIRST-CLASS OBJECT
11.  CONTINUITY STATUS
12.  DERIVATION CONTINUITY
13.  GAP RECORD (FORMAL, FIRST-CLASS RESEARCH OBJECT)
14.  GAP TAXONOMY
15.  GAP SEARCH PROTOCOL
16.  GAP RESOLUTION
17.  DEFINITION TRACKING
18.  PROPOSITION / CLAIM TRACKING
19.  ASSUMPTION TRACKING
19A. THEORY OBJECT REGISTRY
19B. THEORY THREAD
19C. CANDIDATE THEORY REGISTRY
20.  SCOPE / REGIME TRACKING
21.  TYPE AND ONTOLOGY CHECK
22.  CONTRADICTIONS
23.  BRANCHES
24.  MERGES
25.  INDEPENDENCE
26.  TRACK-A / TRACK-B FIREWALL
27.  ML POLICY
28.  NO SILENT REPAIR
29.  HISTORICAL TRUTH VS CURRENT CORRECTNESS
30.  GRAPH LAYERS
30A. IMPLEMENTATION RECONSTRUCTION LAYER
31.  FILE-LEVEL EDGE DOES NOT EQUAL THEORY-LEVEL EDGE
32.  PROGRESSIVE RECONSTRUCTION
33.  REVISITING EARLIER FILES
34.  PATHS ARE DERIVED VIEWS
35.  QUALITY STATES
36.  BATCH PROCESSING
36A. RECONSTRUCTION STATE & CHECKPOINT SCHEMA
37.  BATCH VALIDATION
38.  CORE DATA MODEL
39.  THE KEY RESEARCH QUESTION
40.  SUCCESS CRITERION
41.  REQUIRED OUTPUTS
42.  P3A COMPARISON LAYER
43.  FINAL ARCHITECTURAL SEPARATION
44.  GOVERNING PRINCIPLE
44A. EXECUTION-FREEZE VALIDATION — FOUR-FILE SIMULATION
45.  FIRST EXECUTION TASK
46.  FINAL REPORTING FORMAT
47.  MASTER PRINCIPLE
48.  THE INVENTORY IS PROVENANCE, NOT THEORY
49.  OPEN EXECUTION QUESTIONS (NOT YET RESOLVED)
```

Letter-suffixed sections (`0A`, `0B`, `9A`, `19A`, `30A`) were inserted between existing numbers as the protocol grew; they are not typos and do not require renumbering the rest.

---

# 0. PURPOSE

The objective is NOT merely to create a graph showing which files are related.

The objective is to reconstruct, from the corpus itself:

1. how the KnowledgeOS theory evolved;
2. which files introduced which ideas;
3. which files continued, refined, corrected, replaced, contradicted, validated, or branched from earlier work;
4. whether theoretical derivations are actually continuous and meaningful;
5. whether definitions remain semantically stable;
6. whether conclusions are properly supported by premises;
7. whether intermediate derivations are missing;
8. whether assumptions are silently introduced or dropped;
9. whether mathematical/logical/type/scope consistency is preserved;
10. where the historical theory contains genuine gaps, underived steps, contradictions, unresolved branches, or incomplete arguments.

The final objective is therefore:

$$
\boxed{
\text{Chronological Corpus}
\rightarrow
\text{File Evidence}
\rightarrow
\text{Verified File Graph}
\rightarrow
\text{Derivation Structure}
\rightarrow
\text{Continuity Analysis}
\rightarrow
\text{Gap Structure}
\rightarrow
\text{Historical Theory Reconstruction}
}
$$

Do not skip layers.

Do not prematurely canonicalize.

Do not repair history silently.

---

# 0A. SEQUENTIAL RECONSTRUCTION GRAPH — MANDATORY OBJECTIVE

This is the central execution requirement implied by §0: reconstructing a **sequential historical graph** and testing, along that graph, whether the mathematical/statistical/logical reasoning actually carries forward. It is not a detail buried under "continuity" (§10) — it is what the whole reconstruction is for.

For the chronological corpus

$$
F_1
\rightarrow
F_2
\rightarrow
F_3
\rightarrow
\cdots
\rightarrow
F_n
$$

the system must determine the **actual evidence-supported sequence of theoretical development**, never assume that chronological adjacency means derivation.

For every relevant transition

$$
F_i
\rightarrow
F_j
$$

answer two separate questions:

1. **Historical relationship** — why is \(F_j\) connected to \(F_i\)?
2. **Derivation continuity** — does the mathematical/statistical/logical argument actually continue correctly from \(F_i\) to \(F_j\)?

The second question is essential and must never be skipped merely because the first is answered.

### Mathematical derivation audit

For every mathematical or statistical theory thread, reconstruct:

$$
\text{Definitions}
\rightarrow
\text{Assumptions}
\rightarrow
\text{Premises}
\rightarrow
\text{Intermediate derivations}
\rightarrow
\text{Proposition/Theorem}
\rightarrow
\text{Conclusion}
$$

Then check whether each arrow is actually supported by corpus evidence — see §14 for the full gap taxonomy this audit must detect against.

For every arrow, ask explicitly:

> **Does the derivation actually follow, or has the author jumped from one claim to another?**

> **A later file must not be considered a valid continuation merely because it uses similar terminology. The actual mathematical dependency must be demonstrated.**

### Sequential Reconstruction Graph

A dedicated graph, distinct from the file graph (§7) and the candidate/verified graphs (§8):

$$
G_S=(F,E_S)
$$

where \(F\) = complete corpus files and \(E_S\) = evidence-supported sequential/theoretical transitions.

Each \(G_S\) edge must contain at least:

```text
source_file
target_file
relationship_type
evidence
continuation_claim
definitions_carried_forward
assumptions_carried_forward
mathematical_dependencies
historical_derivation_status
derivation_completeness
mathematical_validation_status
statistical_validation_status
gap_status
confidence
```

These four status fields are kept deliberately separate — mixing "is it documented," "how much of it is documented," and "is it correct" into one enum is exactly the ambiguity that let a merely-absent derivation get recorded as an invalid one.

`historical_derivation_status` — is the required reasoning documented in the corpus at all? — must distinguish, at minimum:

```text
DOCUMENTED
PARTIALLY_DOCUMENTED
UNDERIVED
BROKEN
CORRECTED
CONTRADICTED
NOT_APPLICABLE
UNCERTAIN
```

`derivation_completeness` — how much of the chain is present? — must distinguish, at minimum:

```text
COMPLETE
PARTIAL
MISSING
UNKNOWN
```

`mathematical_validation_status` and `statistical_validation_status` — is the reasoning actually correct? — must distinguish, at minimum:

```text
NOT_YET_ASSESSED
VALIDATION_DOCUMENTED_IN_CORPUS
VALIDATED
INVALIDATED
UNCERTAIN
```

`VALIDATION_DOCUMENTED_IN_CORPUS` means the historical corpus itself contains an explicit validation result — it does **not** mean the reconstruction team validated the mathematics (§0B explicitly puts that out of scope). This is a deliberately safer name than a generic "supported," which could be misread as this phase having performed the support.

`historical_derivation_status`/`derivation_completeness` are distinct from `continuity_status` (§11): `continuity_status` assesses the transition as a whole; these two assess specifically whether, and how much of, the mathematical/statistical argument was documented.

During this phase, defaults are:

```text
historical_derivation_status   = assessed, per the enum above
derivation_completeness        = assessed, per the enum above
mathematical_validation_status = NOT_YET_ASSESSED
statistical_validation_status  = NOT_YET_ASSESSED
```

unless the corpus itself contains an explicit validation result. This prevents recording "the proof is invalid" when what was actually found is "the proof is absent" (§29): `UNDERIVED_BY_CORPUS` (§14) means the latter, never the former.

### Not every consecutive pair needs an edge

The protocol does not require every consecutive chronological pair to have a theoretical edge. For example:

```text
F001 → F002 → F003 → F004 → F005
```

may actually reconstruct as:

```text
F001 ──→ F003 ──→ F005
          │
          └──→ F004
```

while F002 is unrelated. Chronological order determines the search order (§3); evidence determines the graph (§31).

### What the reconstruction ultimately aims to show

```text
Definition D1
   ↓
Assumption A1
   ↓
Lemma L1
   ↓
F014
   ↓
F021
   ↓
Theorem T1
   ↓
F037
   ↓
Statistical extension S1
   ↓
F044
   ↓
[ MISSING DERIVATION ]
   ↓
F052
   ↓
Claim C2
```

`[MISSING DERIVATION]` is a first-class research finding (§13), never something the system repairs automatically (§28).

---

# 0B. MISSION, SCOPE, AND DOWNSTREAM PREPARATION

### Mission

> Reconstruct KnowledgeOS chronologically from the complete historical corpus, establish an evidence-backed sequential/derivation graph (§0A), verify continuity of mathematical, statistical and logical reasoning (§10–§12), record all gaps and contradictions (§13–§14, §22), and maintain a provenance-preserving Theory Object Registry (§19A) that prepares the material for later validation, canonicalization and implementation.

### The single most important sentence

> **The current phase is not responsible for writing the final KnowledgeOS theory. It is responsible for building the evidence-backed, provenance-preserving reconstruction model from which the later validated and canonical theory can be derived.**

### In scope for this phase

The current phase produces:

```text
1. Master File Registry
2. File Reconstruction Records
3. Sequential File Graph (G_S)
4. Verified Derivation Graph (G_D)
5. Continuity Graph
6. Gap Ledger
7. Theory Object Registry
8. Assumption Registry
9. Definition Registry
10. Contradiction Registry
11. Mathematical/Statistical Audit Records
12. Implementation Readiness Registry
13. Provenance/Traceability Matrix
14. Batch validation reports
15. Candidate Theory Registry (§19C) — provisional, retractable consolidation
    of Theory Objects into candidate theoretical concepts, explicitly not
    validated or canonical
```

### Explicitly out of scope for this phase

The current phase MUST capture all information required for later phases but MUST NOT perform those phases prematurely:

```text
1. Formal / rigorous Cross-Theory Comparison — determining, with the
   authority of a resolved conclusion, that two formulations ARE
   equivalent, that one DOES correct another, or that a competition
   between formulations IS resolved (§33; §12, §19B) — distinct from
   Candidate Theory Construction (§19C, in scope, item 15 above), which
   only records a provisional, retractable grouping hypothesis
2. Mathematical validation
3. Statistical validation
4. Theory reconciliation (accepting one formulation as authoritative over
   another — distinct from candidate consolidation, §19C, which accepts
   nothing and resolves nothing)
5. Gap resolution (beyond what corpus evidence already resolves, §16)
6. Canonicalization
7. Formal theory writing
8. Implementation architecture
9. Executable implementation
10. Testing
```

Formal Cross-Theory Comparison and Theory Reconciliation are the natural next stage once reconstruction reaches `CORPUS_PROCESSING_COMPLETE` (§37): for each Theory Object with multiple Derivation Instances (§12), Definitions with a non-`SAME_DEFINITION` relationship (§17), or candidate consolidation in the Candidate Theory Registry (§19C), ask *with resolving authority* whether they are the same concept, mathematically equivalent, different regimes, independent rediscoveries, one correcting the other, built on different assumptions, or resolve a gap in one another — and accept that answer. Candidate Theory Construction (§19C) asks the same comparison questions during this phase but never accepts an answer; every candidate grouping stays retractable and explicitly unvalidated until this later, resolving stage confirms or rejects it.

A limited validation necessary only to classify a current transition (§12, §43) is permitted; performing any of the nine items above as a final judgment is not.

### Overall architecture

```text
                    HISTORICAL CORPUS
                           │
                           ▼
                 ┌───────────────────┐
                 │ Sequential Files  │
                 └─────────┬─────────┘
                           │
              ┌────────────┴────────────┐
              ▼                         ▼
        File Graph                File Records
              │                         │
              └────────────┬────────────┘
                           ▼
                  Derivation Graph
                           │
             ┌─────────────┼─────────────┐
             ▼             ▼             ▼
        Continuity       Gaps       Contradictions
             │             │             │
             └─────────────┼─────────────┘
                           ▼
                  Theory Objects
                           │
             ┌─────────────┴─────────────┐
             ▼                           ▼
       Validation                    Implementation
       Preparation                   Preparation
             │                           │
             └─────────────┬─────────────┘
                           ▼
                 LATER PHASES ONLY
                           │
             ┌─────────────┼─────────────┐
             ▼             ▼             ▼
        Validated       Canonical      Executable
          Theory         Theory          System
```

---

# 0C. REFERENCE ARCHITECTURE AND EMERGENT HISTORICAL ARCHITECTURE

Before asking how a file relates to previous files (§10, §31), Layer 2 of the per-file protocol (§9) must first ask how the file relates to KnowledgeOS itself: what KnowledgeOS is supposed to accomplish, what its reference architecture is, and where this file belongs in it. This section defines that comparison so it is never skipped or improvised per file (§9's invariant rule).

### Two architectures, not one

Giving Claude only a predefined KnowledgeOS architecture would make that architecture a bias baked into every reconstruction decision. Two architectures must be maintained and compared, never merged:

### A. Reference Architecture

What we currently believe KnowledgeOS is intended to be — a classification/reference model, for example:

```text
KnowledgeOS
├── Evidence
├── Dependency
├── Determination
├── Validation
├── Knowledge / Theory
├── Kernel
└── Implementation
```

The Reference Architecture must be frozen before execution for *consistency* (§45, §49) — but freezing it does not make it true. It remains a hypothesis about KnowledgeOS, and must carry its own status so a future agent never unconsciously treats an `AR-###` component as historically proven:

```text
REFERENCE_MODEL
REFERENCE_VERSION
REFERENCE_SOURCE
REFERENCE_FREEZE_DATE
REFERENCE_CONFIDENCE
REFERENCE_RATIONALE
reference_architecture_is_not_historical_truth = true
```

### B. Emergent Historical Architecture

What the corpus actually reveals, built up incrementally as files are processed, for example:

```text
Historical Corpus
       ↓
Evidence
       ↓
Observation
       ↓
Dependency
       ↓
Determination
       ↓
...
```

Unlike the Reference Architecture, the Emergent Historical Architecture is never frozen — it evolves as evidence accumulates, so each component needs explicit version semantics rather than being silently overwritten:

```text
HA-001
version: 1
first_seen: F004
last_confirmed: F029
status: ACTIVE
```

```text
HA-001
version: 2
changed_by: F041
change_type: REFINEMENT
```

The two architectures are compared, never forced into agreement — this prevents the serious methodological error of forcing historical material into today's architecture.

### The dependency between them is one-way

> *(Pilot finding, batch 001.)* The Emergent Historical Architecture needs **no** Reference Architecture to be built — the corpus supplies its own architectural vocabulary directly (the first file alone hands over five tiers and six named subsystems, each with a per-subsystem evidence status). The Reference Architecture is needed only for the *comparison*, not for the historical side of it.

Therefore, when the Reference Architecture has not yet been authored and frozen (§45 Gate 1, §49 item 1):

* `HA-###` extraction **proceeds** — Layer 2 records what architectural vocabulary the file itself uses;
* `AR-###` mapping, `reference_architecture_alignment`, and `architecture_relationship` are recorded as `UNAVAILABLE_NO_REFERENCE_ARCHITECTURE` — **not** as `UNCLASSIFIED`, which would wrongly suggest the file was examined and found unclassifiable;
* `kernel_classification` (§19A) likewise stays `UNCLASSIFIED` with that reason recorded.

Do not block the whole of Layer 2 on an input only half of it requires.

### Per-file architecture fields

Each file's reconstruction record (§9) carries:

```text
reference_architecture_alignment
historical_architecture_position
architecture_relationship
architecture_evidence
architecture_confidence
```

`architecture_relationship` must distinguish, at minimum:

```text
ALIGNS
EXTENDS
REFINES
CORRECTS
CONTRADICTS
INTRODUCES_NEW_COMPONENT
RETIRES_COMPONENT
AMBIGUOUS
NO_ARCHITECTURAL_ROLE
```

### Architecture Object Registry

Assign every architectural component (in either architecture) a stable identifier `AR-###`, with at least:

```text
architecture_object_id
name
purpose
responsibility
boundary               # what this component's consistency/ownership boundary is
inputs
outputs
owned_concepts         # Theory Objects (§19A) this component is authoritative over
non_responsibilities   # explicit "this is NOT owned here" — prevents scope creep between components
source
architecture_version
reference_status
historical_status
confidence
related_theory_objects
related_files
conflicts
```

### Worked example

```text
F0837

Reference Architecture:
    AR-004 Dependency

Historical Architecture:
    HA-007 Observation/Dependency boundary

Alignment:
    PARTIAL

Conflict:
    G-xxxx
```

The Reference Architecture's own content must be authored and frozen (§45) before large-scale execution begins — see §49.

---

# 0D. BOUNDED CONTEXTS

The reconstruction implicitly separates five areas of ownership. Making them explicit clarifies which section governs which decision and prevents one context's concerns from silently leaking into another's (§28, §0B) — this is the strategic-DDD step ("which bounded context owns this capability?") applied to the protocol itself.

| Context | Owns | Canonical sections |
| --- | --- | --- |
| **Historical Reconstruction Context** | What the corpus actually says, file by file, chronologically | §2–§9, §17–§22, §32–§34 |
| **Theory Context** | Theory Objects, their identity, evolution, and threads | §19A, §19B |
| **Architecture Context** | Reference Architecture vs Emergent Historical Architecture | §0C |
| **Validation Context** | Mathematical/statistical correctness — out of scope this phase | §0B, §29, §43 |
| **Implementation Context** | Implementation readiness, and later, design/build/test | §30A, §43 |

No context may silently decide something that belongs to another. For example: the Historical Reconstruction Context records that a derivation is undocumented (§0A); only the Validation Context, in a later phase, may say whether it would have been correct (§29).

---

# 0E. RESEARCH RECONSTRUCTION LOOP

*(Requested as "§0D" by the controlled architectural update that introduced this section; §0D was already occupied by Bounded Contexts, added in a prior revision, so this lands at §0E instead — nothing existing was renamed or removed.)*

### Purpose

This section defines the **behavior model** of the reconstruction worker — how it thinks and investigates while reading. §9's 30-step, 5-layer pipeline remains the **recording model** — what must be recorded, validated, persisted, and reproducibly audited. The two are complementary, not alternatives:

```text
RESEARCH RECONSTRUCTION LOOP
        ↓
defines how the worker thinks and investigates

DETERMINISTIC RECONSTRUCTION PROTOCOL (§9)
        ↓
defines what must be recorded, validated, persisted,
and reproducibly auditable
```

Forcing human research reasoning to *be* a data model was an implicit error in earlier revisions — a mathematician does not think in JSON schemas, but a protocol does. §0E is the thinking; §9 is the audit trail that thinking must produce.

### Primary objective

The worker must behave as a senior mathematician, senior statistician, research theorist, logical analyst, and historical theory reconstructor — never merely as a document classifier, keyword matcher, embedding matcher, or file-similarity detector (§2).

> Read the current complete file, understand its mathematical/statistical content, assess its documented reasoning quality, determine what theory/definitions/derivations it contains, compare it with the evolving reconstruction state, investigate relevant predecessors or successors when necessary, determine its historical relationship to existing theory, and update the reconstruction state before proceeding chronologically.

The worker reconstructs the theory **while** reading the corpus, not by attempting to reconstruct the whole theory before reading it (§32).

### The loop

```text
READ COMPLETE FILE
        ↓
UNDERSTAND CONTENT
        ↓
IDENTIFY DEFINITIONS, ASSUMPTIONS, PREMISES, CLAIMS,
DERIVATIONS, CONCLUSIONS, MATHEMATICAL OBJECTS,
STATISTICAL OBJECTS
        ↓
ASSESS REASONING DOCUMENTATION QUALITY (§0E.4)
        ↓
COMPARE WITH PREVIOUS RECONSTRUCTION STATE
(active threads first — §19B, §36A)
        ↓
ASK:
    What does this depend on?
    Where did this idea originate?
    What does this file inherit / change / extend / correct / contradict / test?
    Is it independent?
        ↓
TARGETED BACKWARD INVESTIGATION IF NECESSARY (§0E.2)
        ↓
TARGETED FORWARD INVESTIGATION IF NECESSARY (§0E.3)
        ↓
RECONSTRUCT THEORY THREAD RELATIONSHIP (§19B)
        ↓
CHECK DERIVATION CONTINUITY (§0A, §10–§12)
        ↓
CHECK DEFINITIONS / ASSUMPTIONS / SCOPE / TYPES-SEMANTICS (§17–§21)
        ↓
IDENTIFY EVIDENCE-SUPPORTED GAPS (§13–§15)
        ↓
CHECK FOR LATER RESOLUTION WHEN REQUIRED (§16)
        ↓
UPDATE FILE / THEORY / GAP / THREAD STATE (§9 Layer 5)
        ↓
CONTINUE TO NEXT CHRONOLOGICAL FILE
```

This loop does not introduce a second methodology. Every step already has a canonical home in §9's pipeline; it narrates the same work in the order and language a researcher would actually use, in place of a bare step list.

### 0E.1 Chronology is the traversal order, not the consultation boundary

The chronological registry (§3) remains authoritative for traversal order. But:

> **Chronology determines the primary traversal order, not the complete set of files that may be consulted.**

The worker may temporarily inspect earlier or later files to understand a dependency, definition, derivation, correction, promised validation, research obligation, continuation, branch, merge, or contradiction:

$$
\text{Chronological Sequence} \neq \text{Theory Sequence}
$$

For example, the reconstructed theory sequence for one thread might be `F043 → F067 → F082 → F100 → F118`, skipping many chronologically-intervening, unrelated files — while every one of those intervening files is still read in full and in order (§2, §32). "Targeted investigation" means where the worker looks *first* for context, never which files get skipped from the pipeline — §8A already establishes this for Phase 0; the same rule applies here to Layer 3 (§9).

### 0E.2 Targeted backward investigation

Compare the current file first against: the immediately preceding file, the recent reconstruction state, and the active/relevant Theory Threads (§19B, §36A's `active_theory_threads`) — not the whole corpus (§15 gives the full progressive fallback when that isn't enough).

If the current file uses a concept whose origin or dependency cannot be explained from that immediate context, search further back:

```text
F137
  ↓
uses Definition X
  ↓
X not established in F136
  ↓
search relevant predecessors (§15)
  ↓
F092
  ↓
X introduced
  ↓
return to F137
  ↓
update reconstruction
```

This is permitted and encouraged. Do not perform exhaustive all-file comparison unless a specific unresolved research question requires it.

### 0E.3 Targeted forward investigation and Research Obligations

> **`SOURCE-REQUIRED` ≠ `RESEARCHER-DESIRED`.** Record a Research Obligation only when the *source itself* explicitly creates the commitment — e.g. "future experiment required," "proof to be supplied," "validation pending," "this result must be tested." Do not create one merely because the worker thinks an experiment would have been useful, and do not create one simply because a derivation is incomplete — an incomplete derivation with no explicit forward commitment is a Gap Record (§13), not a Research Obligation. Keep this object lightweight; it is not a second workflow.

If a file explicitly promises a proof, requires an experiment, states validation is pending, or otherwise creates a source-required forward-looking commitment, record a **Research Obligation** — a first-class object, not a loose note:

```text
RO-####
source_file_id
obligation_type          # PROMISED_PROOF / PENDING_EXPERIMENT / PENDING_VALIDATION / OTHER
description
status                    # OPEN / RESOLVED / ABANDONED / UNCERTAIN
resolution_source
```

`RO` joins the ID namespace (§4A).

The worker may then search forward to determine whether later corpus material resolves it:

```text
F101
  ↓
"requires empirical verification"  →  RO-014 (OPEN)
  ↓
search forward
  ↓
F118
  ↓
experiment documented
  ↓
link F101 → F118, RO-014 → RESOLVED (resolution_source: F118)
```

If no resolution is found, the obligation stays `OPEN` — never inferred as resolved merely because a later file discusses the same topic (§28).

### 0E.4 Three distinct types of assessment

The protocol has always kept historical reconstruction separate from mathematical validation (§0A, §0B, §29); a third axis was missing and is added here. The worker performs Levels 1 and 2 during the first pass; Level 3 remains a downstream activity.

**Level 1 — Historical Reconstruction / Source Understanding** — *what did the source actually state, assume, derive, claim, test, or conclude?* Uses `historical_derivation_status`/`derivation_completeness` (§0A).

**Level 2 — Reasoning Documentation Quality (researcher quality assessment)** — *does the reasoning, as documented, appear coherent, complete, internally consistent, and sufficiently connected to its premises?* Never a truth judgment, only a documentation-clarity judgment. Assess qualitatively per dimension, never as one overall score:

**Required six** — these carried all the signal in batch 001 and are assessed for every file:

```text
definition_clarity
assumption_clarity
derivation_completeness
internal_consistency
evidence_quality
documentation_quality
```

**Conditional six** — assessed only when the file contains material of that kind; otherwise `NOT_APPLICABLE` without further comment:

```text
mathematical_clarity        statistical_clarity
mathematical_coherence      statistical_coherence
logical_coherence           scope_consistency
```

> *(Pilot finding, batch 001: all six conditional dimensions returned `NOT_APPLICABLE` for all ten files — the window is governance/architecture prose with no mathematical or statistical derivations. Assessing them per file was pure overhead. `internal_consistency` alone produced the batch's most useful quality finding: F0008's title verdict conflicting with the scope correction directly beneath it, which F0010 independently criticises.)*

> ⛔ **`NOT_APPLICABLE` means the phenomenon is genuinely absent from the file — never that it was not examined.** Conditionality is a saving on *recording*, never on *looking*: deciding a file contains no statistical reasoning is itself a judgment that requires having read it (§2). Where a file does contain the material but the assessment was not made, the honest value is `UNCERTAIN`, not `NOT_APPLICABLE`.
>
> This is the same discipline §41 applies to absent artifacts and §25 applies to absent relationships: **absence-because-checked and absence-because-unchecked are different claims and must never share a value.**

each taking a value from:

```text
CLEAR
ADEQUATE
PARTIAL
INCOMPLETE
AMBIGUOUS
INTERNALLY_INCONSISTENT
UNCERTAIN
NOT_APPLICABLE
```

These are research observations, not correctness scores — do not collapse them into a single numeric quality score; §40's rejection of ranking applies here too.

**Level 3 — Mathematical/Statistical Validation** — *is the reasoning actually correct under rigorous independent analysis?* Out of scope this phase by default (§0B), using `mathematical_validation_status`/`statistical_validation_status` (§0A).

> **No finding may simultaneously serve as historical state, documentation-quality assessment, and mathematical validation unless it is explicitly recorded in all three layers it claims to represent.** Recording one is never permission to silently imply the others.

### 0E.5 Substantive mathematical/statistical understanding during reading — scope

Determining whether one file continues another (§0A's derivation-status fields, §10's continuity) is impossible without genuinely understanding the mathematics/statistics in both files — not a shallow scan. The worker must inspect definitions, assumptions, domain/scope, type, notation, premises, derivation dependencies, statistical population/sample, variables, parameters, conditions, and conclusions (§17–§21) closely enough to tell whether, e.g., the `Y` claimed in `F101` is actually the same mathematical object as the `Y` established in `F100`. Without this, sequential matching degenerates into keyword matching (§2) — exactly what this protocol exists to prevent.

This scrutiny may surface directly observable, source-internal problems: an undefined symbol, inconsistent notation, incompatible definitions, a missing stated premise, an algebraic step that doesn't follow from the immediately preceding line, a conclusion that visibly exceeds its stated premises, a required statistical assumption that's absent, an explicit internal contradiction, or a condition the source itself requires but doesn't satisfy. When directly demonstrable from the source's own stated rules, record it (§14's `ALGEBRAIC_INCONSISTENCY`/`LOGICAL_INCONSISTENCY`/etc., already gated to exactly this standard).

> **The worker must understand and scrutinize the mathematics/statistics sufficiently to reconstruct and assess continuity, but must not silently replace historical reasoning with its own corrected mathematics. Formal independent validation remains separately recorded** (`mathematical_validation_status`/`statistical_validation_status`, §0A) **and stays out of scope for this phase** (§0B) **regardless of how much local scrutiny occurred.**

This is **not** a complete formal mathematical proof audit under independent rigorous analysis — that remains the separate, downstream Validation Context (§0D, Level 3 of §0E.4). Understanding and locally scrutinizing the mathematics is required and expected; certifying it as formally, independently correct is not.

### 0E.6 No silent mathematical repair — worked example

Restates §28 in the loop's own terms. If the worker sees:

```text
A
↓
?
↓
B
```

it must not insert a mathematically plausible missing step into the historical reconstruction. Record:

```text
Historical derivation:
    A → [missing] → B

historical_derivation_status:
    UNDERIVED

derivation_completeness:
    PARTIAL

mathematical_validation_status:
    NOT_YET_ASSESSED
```

A possible completion the worker can see may be recorded separately as a researcher observation or later-validation candidate — never presented as historical fact (§28, §29).

### 0E.7 The first-pass question is simple — keep it that way

Do not turn every file into a full independent research investigation. For each file \(F_i\), the primary question is:

> **"What is this file doing relative to the research state established so far?"**

then, in order:

> "Is it a continuation of the previous/relevant theory, or is it independent/new?"
> "If it continues, what exactly does it inherit and what does it change?"
> "Does the mathematical/statistical reasoning actually carry forward?"
> "Is anything important missing?"

Most files answer these quickly against the immediate context (§0E.2: \(F_{i-1}\), the current state, active Theory Threads) without needing any backward/forward investigation at all. Reserve §0E.2/§0E.3's targeted searches for when the immediate context genuinely doesn't answer the question — not as a default step for every file.

**Do not:** compare every file against every other file (no O(N²) sweep — §9's Layer 3 search order, §15's progressive levels, exist precisely to avoid this); perform full theorem-proving on every file; attempt Level 3 formal validation for every claim; reconcile all definitions immediately; canonicalize theories while reading; construct a complete final ontology before the corpus is understood; or solve the whole theory while reading the first file. The worker gradually reconstructs the theory across `State(F_1) → State(F_2) → ... → State(F_n)` (§9's stateful processing); it does not need to know the final theory before beginning.

**Do not force the corpus into one theory.** If the evidence reveals `Theory Thread A`, `Theory Thread B`, `Theory Thread C` as genuinely distinct, maintain them separately (§19B) — a file may belong to one thread, several, connect two, branch one, merge two, apply one to another, or carry no theory relationship at all. Thread membership is evidence-based (§19A's identity test), never inferred from similar wording alone.

---

# 1. P3A IS FROZEN

The previous P3A investigation is now a **frozen baseline**.

P3A must not be treated as the canonical historical derivation graph.

P3A remains available as:

* candidate evidence;
* navigation aid;
* prior hypothesis;
* relationship candidate source;
* comparison baseline;
* recall benchmark for the new reconstruction.

But:

$$
\boxed{
\text{P3A candidate}
\neq
\text{historical truth}
}
$$

A P3A relationship must not automatically become a verified relationship in the new graph.

The new reconstruction must be capable of discovering relationships independently of P3A.

### Consult P3A's file-level metadata — then verify it independently

> *(Pilot finding, batch 001.)* P3A's `02-FILES.jsonl` already carries, per file: a source ID, `explicit_dates`, `best_historical_date` **with basis**, `order_evidence`, `mtime_block`, `provenance`, and an `objects_touched` list. P3A had **already detected the bulk-mtime problem** (`mtime_block: BULK-01`) and built an explicit-date layer because of it. The protocol never says to look, so the pilot re-derived all of it from scratch.
>
> And P3A's dates were **wrong for 3 of 10 files** — systematically, by selecting the *minimum* explicit date, which for a synthesis document is a date it *cites*, not the date it was written. On this window that would have placed the refuting document **first of ten**, ahead of the document it refutes and lists as an input.

Both halves matter, and they are the reason this is a *comparison* layer (§42) rather than an input:

1. **Do consult it.** Reading P3A's per-file metadata before deriving chronology, provenance and object-touch costs one lookup and tells you what a prior pass already found — including its detection of corpus-level hazards.
2. **Never adopt it.** Every P3A field is a `p3a_*` cross-reference, verified against the complete file before any reconstruction depends on it. Where they disagree, the complete-file reading wins (§2) and the disagreement is recorded (§42).

Recording *agreement* is as valuable as recording disagreement: it is the only way the P3A recovery rate becomes measurable later.

---

# 2. PRIMARY ATOMIC UNIT

The atomic unit of this reconstruction is:

$$
\boxed{\text{COMPLETE FILE}}
$$

Never use the following as the primary unit:

* word;
* phrase;
* keyword;
* search hit;
* embedding;
* filename similarity;
* S-number;
* short code;
* P3A label;
* isolated proposition;
* isolated sentence.

These may be **evidence within a file**.

They are not the reconstruction unit.

The complete file must be read and understood before assigning substantive historical meaning.

---

# 3. CHRONOLOGICAL CORPUS

Use the supplied chronological file inventory as the initial registry.

The inventory contains timestamped file paths and is the traversal order for the reconstruction.

**Canonical chronological corpus registry:**

```text
docs/knowledgeos/list_of_files_to_read.log
```

* One row = one corpus file.
* First column = the authoritative `file_id`.
* Remaining columns = timestamp and path, the metadata needed to locate and process the file.

> ⛔ **THE REGISTRY TIMESTAMP IS NOT NECESSARILY THE AUTHORSHIP DATE — VERIFY BEFORE RELYING ON IT.** *(Pilot finding, batch 001: all ten files in the first window carry the identical registry timestamp `Aug 5 15:44`, which is the filesystem mtime from a bulk copy. Their actual authorship dates, recoverable only from document content, split 5/5 across two days — and registry order places five later documents BEFORE five earlier ones.)*
>
> Before traversal begins, the Registry Normalization Step (§4) must determine, for this corpus, whether the timestamp column carries authorship chronology or merely file-system mtime. Record the answer explicitly as `registry_timestamp_semantics`:
>
> ```text
> AUTHORSHIP_CHRONOLOGY     — the column can be trusted as the traversal order
> FILESYSTEM_MTIME_ONLY     — it cannot; authored_date must be extracted per file
> MIXED / UNKNOWN           — treat as FILESYSTEM_MTIME_ONLY until proven otherwise
> ```
>
> When the value is anything other than `AUTHORSHIP_CHRONOLOGY`, §0A's sequence, §0E.2's "immediately preceding file", and §9's `State(Fᵢ)` chain are ordered by the derived `historical_sequence_date` defined below — never by registry row order. Registry row order remains the *read* order (it guarantees completeness); it is not automatically the *chronological* order.
>
> **Reading a later document before the earlier one it corrects is the precise failure §3 exists to prevent** — and it happens silently if this check is skipped.

### Dates are typed evidence, not a scalar

> ⛔ **A date appearing in a file is not the date of the file.** *(Pilot finding, batch 001 — the deepest lesson of the pilot, and it cuts against the pilot's own first fix.)*
>
> P3A's `best_historical_date` was wrong for 3 of 10 files because it reduced many dates to one by taking the minimum — which, for a synthesis document that cites older artifacts by construction, systematically selects a **cited** date and makes the synthesis look older than its own sources. That inverts derivation direction.
>
> The pilot's own first correction — a scalar `authored_date_from_content` — **repeats the same structural error one level up.** Its output already contains `date_type: "COMMISSION_DATE + REVISION_DATE (REV 2)"`: two typed events crammed into one string, because one scalar cannot hold what the file actually says. Replacing a bad heuristic with a better-sourced scalar does not fix a **shape** problem.

Every date found in a file is recorded as its own **Date Event**; the file's chronological position is **derived** from them:

```text
date_events[]
    date_value
    date_type       # closed list below
    date_source     # header / traceability line / table row / filename / body quote
    date_basis      # EXPLICIT | INFERRED
    date_confidence # HIGH | MEDIUM | LOW
    applies_to      # THIS_FILE | A_CITED_ARTIFACT | AN_EXTERNAL_EVENT
    evidence        # E-#### (§6A)
```

`date_type` must be one of:

```text
AUTHORSHIP     the file was written
COMMISSION     the work the file reports was commissioned
REVISION       a later in-file revision (REV 2, CORRECTED …)
PUBLICATION    issued / submitted
ADOPTION       an artifact was adopted or ratified     ← usually A_CITED_ARTIFACT
DECISION       a decision or ruling was made           ← usually AN_EXTERNAL_EVENT
EVENT          something described happened
VALIDATION     a validation or experiment was run
REFERENCE      a date appearing only as a citation
UNKNOWN
```

### Deriving `historical_sequence_date`

```text
historical_sequence_date
historical_sequence_date_basis        # which date_event(s) produced it
historical_sequence_date_confidence
```

**Derivation rule, in order:**

1. Consider **only** date events with `applies_to: THIS_FILE`. A date whose `applies_to` is `A_CITED_ARTIFACT` or `AN_EXTERNAL_EVENT` can never set the file's position, however explicit it is. **All three P3A errors violated exactly this rule** — they selected an ARB refinement, a quoted DA clarification, and a cited artifact's adoption date.
2. Among those, prefer `AUTHORSHIP`, then `COMMISSION`, then `PUBLICATION`.
3. `REVISION` events do **not** move the file's original position. They are recorded, and may establish a second position for the revised state (§5A), but a file's historical origin is when it was first written.
4. If step 1 yields nothing, or yields conflicting candidates the evidence cannot resolve, record `historical_sequence_date: UNCERTAIN`, order that file by registry position as a last resort, and flag it as such.

> ⛔ **Never select a date arbitrarily to avoid `UNCERTAIN`.** An arbitrary pick is precisely the failure being corrected here; an honest `UNCERTAIN` is recoverable later, a wrong date is not.

`historical_sequence_date` is **derived** (§41 Tier 3) — never authored directly, never copied from P3A.
* This log is the single starting registry for this reconstruction phase. Do not derive a second, competing inventory.
* Any directory/prefix deliberately excluded from this log (for example prior-phase output areas) must be documented as **out of the primary historical corpus for this phase**, never silently omitted.

Chronology determines:

> **where we read next**

but chronology does NOT determine:

> **what caused what**

Never infer:

$$
t(A)<t(B)
\Rightarrow
A\rightarrow B
$$

A later file may:

* continue an earlier file;
* correct an earlier file;
* contradict an earlier file;
* independently rediscover an idea;
* branch from an earlier line;
* merge multiple earlier lines;
* introduce unrelated research.

Evidence determines relationships.

---

# 4. IMMUTABLE FILE IDENTITY

Every corpus file already has an immutable identifier: the `file_id` in the first column of the canonical registry (`docs/knowledgeos/list_of_files_to_read.log`), e.g.:

```text
F0001
F0002
F0003
...
```

**Do not generate a second, competing File ID scheme.** The reconstruction must import and preserve these IDs exactly as they appear in the registry — never renumber, never reassign, never regenerate them from scratch.

The ID must have **no semantic meaning**.

Do not encode:

* domain;
* theory;
* phase;
* relationship;
* Track-A/B;
* file type.

The ID is only an identity key.

The original path and timestamp (also carried in the registry) must remain preserved.

### Registry Normalization / Identity Import Step

**Verified as of this revision:** `docs/knowledgeos/list_of_files_to_read.log` already carries `F####` as its first column (e.g. `F0001\tAug 5 15:44\tdocs/knowledgeos/...`) — checked directly against the file, not assumed. There is no second, competing identity system, and none is needed: the log's first column is already the authoritative `file_id`.

For any future re-export or regeneration of this registry, run this bootstrap exactly once, before reconstruction begins, never silently on every execution:

```text
1. read the actual inventory as it exists;
2. preserve timestamp exactly;
3. preserve path exactly;
4. check whether an authoritative file_id mapping already exists
   (as it does today) — if so, use it verbatim, do not regenerate it;
5. if and only if no file_id column exists, assign F#### once, by
   current row order, and never again;
6. persist the resulting mapping;
7. hash the resulting registry (corpus_manifest_hash, §36A);
8. freeze it before reconstruction begins (§45 Gate 3).
```

If a source inventory ID and a KnowledgeOS-internal ID are ever genuinely both necessary (not currently the case), keep them explicitly distinct fields rather than one field silently meaning two things — but do not introduce a second ID system merely to future-proof against a scenario that hasn't occurred.

---

# 4A. ID NAMESPACES

Every identifier introduced by this protocol uses a fixed, immutable namespace prefix so cross-references remain unambiguous at corpus scale:

```text
F  = File                      (§3, §4)
E  = Evidence Object           (§6A)
AR = Architecture Object       (§0C)
T  = Theory Object             (§19A)
TH = Theory Thread             (§19B)
RO = Research Obligation       (§0E.3)
DI = Derivation Instance       (§12)
CT = Candidate Theory Object    (§19C)
D  = Definition                (§17)
P  = Proposition / Claim       (§18)
A  = Assumption                (§19)
S  = Sequential Edge (G_S)     (§0A)
R  = File/Theory Relationship  (§7)
G  = Gap                       (§13)
C  = Contradiction             (§22)
B  = Branch                    (§23)
M  = Merge                     (§24)
I  = Implementation Record     (§30A)
```

An ID's prefix never changes once assigned, regardless of later reinterpretation (§5, §37).

### Collision with the corpus's own registers — MANDATORY CHECK

> ⛔ **Every namespace above collides with an identifier series that already exists inside this corpus.** *(Pilot finding, batch 001 — observed in the first ten files alone.)*

| Protocol namespace | Corpus already uses the same prefix for |
|---|---|
| `F` File | `F-1`, `F-2`, `F-3` — the MVK's load-bearing **failures** |
| `AR` Architecture Object | `AR-1` — an architectural **risk** |
| `T` Theory Object | `T1`–`T5` — the product architecture **tiers** |
| `C` Contradiction | `C-1`..`C-12` **canonical concerns**, `C-1`..`C-3` **corrections**, and `C-1` **model amnesia** — three registers, one prefix |
| `P` Proposition | `P1`–`P5` — the baseline's **principles** |
| `G` Gap | `G-1`..`G-10` — **evidence gaps** (semantically adjacent, which makes it *worse*, not better) |
| `D` Definition | `D-1`..`D-10` — ARB **decision packages** |
| `R` Relationship | `R-1`..`R-77` **rulings**, `R-1`..`R-12` **rejected claims**, `R-A`/`R-B`/`R-C` **responsibilities** |

**Rules:**

1. **Protocol IDs are always zero-padded to four digits** (`G-0001`, `T-0002`, `F0001`). A corpus identifier written `G-1` or `T-5` is therefore never confusable with a protocol identifier — the padding is the discriminator, and it must not be dropped in any record or diagram.
2. **A corpus identifier is quoted, never minted.** When a record refers to `C-1` meaning the corpus's own label, it is stored as a `historical_names[]` entry (§19A) or inside `source_terms`, never in an `id` field.
3. **Before execution, enumerate the corpus's own registers** encountered so far and record them, so the collision set is known rather than rediscovered per file.
4. Where ambiguity survives both rules, prefer a longer protocol prefix (`GAP-0001`) over silent reuse.

---

# 5. MASTER FILE REGISTRY

`file_id`, `sequence` (row order), `timestamp`, and `path` are already populated by the canonical registry (`docs/knowledgeos/list_of_files_to_read.log`) and must be copied verbatim, not regenerated. The remaining fields below are added as a derived layer during reconstruction, keyed by the existing `file_id`.

Create a canonical registry containing at least:

| Field                 | Meaning                                                                                            |
| --------------------- | --------------------------------------------------------------------------------------------------- |
| `file_id`             | immutable file identity                                                                              |
| `sequence`            | chronological position                                                                               |
| `timestamp`           | source timestamp                                                                                     |
| `path`                | exact corpus path                                                                                     |
| `track`               | `TRACK-A` / `TRACK-B` / `MIXED` / `UNKNOWN` (§26)                                                     |
| `read_status`         | `UNREAD` / `READING` / `READ`                                                                         |
| `dossier_status`      | `NOT_STARTED` / `IN_PROGRESS` / `COMPLETE` — may only become `COMPLETE` once every §9A gate passes    |
| `relationship_status` | `NONE` / `CANDIDATES` / `VERIFIED`                                                                    |
| `continuity_status`   | `NOT_ASSESSED` / `CONTINUOUS` / `PARTIAL` / `BROKEN` / `CORRECTED` / `CONTRADICTORY` / `UNCERTAIN` (§11) |
| `gap_status`          | `NONE` / `OPEN` / `RESOLVED` — a per-file summary; the authoritative per-gap status is `resolution_status` on the Gap Record itself (§13) |
| `notes`               | controlled notes                                                                                      |

Do not overwrite historical metadata merely because interpretation changes.

---

# 5A. RECORD VERSIONING (APPEND-ONLY)

§5's "do not overwrite" and §37's "a later correction is an appended version, not an overwrite" are principles; this is the mechanism. Backward discovery is explicitly expected (§33): `F100` may reveal a dependency on `F037` only while `F100` is being processed, which means `F037`'s already-written record must change. Without an explicit versioning contract, an agent can accidentally overwrite `F037`'s original reconstruction instead of appending to it.

Every record type that can be revised after its first write — File Reconstruction Records (§9), Theory Objects (§19A), Candidate Theory Objects (§19C), Contradictions (§22), Branches (§23), Gaps (§13) — carries:

```text
record_version
previous_record_hash
new_record_hash
changed_by_file
change_reason
change_evidence
changed_at
```

The record behaves as an append-only event log, not an in-place mutation:

$$
R_1 \rightarrow R_2 \rightarrow R_3
$$

never:

$$
R_1 \leftarrow R_2
$$

`changed_by_file` and `change_evidence` tie every revision back to the specific later file whose evidence justified it — the same provenance discipline already required elsewhere (§6A, §16) applied to the act of revision itself, not just to the record's content.

### The third case: the SOURCE revises itself in place

> *(Pilot finding, batch 001.)* This corpus corrects itself **inside single files**, not only across them: a fitness assessment carrying *"CORRECTED — '0 OF 11 ENFORCING' IS TOO STRONG"* beneath its own finding; a decision docket whose Package 1 amendment downgrades **its own** supporting evidence; an experiment report whose `F-3` row is struck through in place with the refuting document named.

Three distinct revision cases now exist, and they must not be conflated:

| Case | Who revised | Mechanism |
|---|---|---|
| **1 · Reconstruction revision** | this protocol | `record_version` chain (above) |
| **2 · Cross-file correction** | a later corpus file | Gap resolution (§16) / Contradiction (§22) — a file→file edge |
| **3 · Intra-file revision** | the same file, later | **neither of the above** — one `file_id`, one path, two epistemic states |

For case 3, the File Reconstruction Record carries:

```text
intra_file_revisions[]
    original_claim         # or UNRECOVERABLE — see below
    revised_claim
    revision_type          # REFUTATION / SCOPE_NARROWING / DOWNGRADE / FACTUAL_CORRECTION / SOFTENING
    revision_marker        # the source's own wording: "CORRECTED", "REV 2", "REFUTED", struck-through
    revision_date_if_given
    refuting_source_if_named
    both_states_preserved  # true | false
```

Two cases the pilot hit that the bare schema did not cover:

**`original_claim: UNRECOVERABLE`.** A source may record *that* it revised a claim without preserving the claim's original wording — e.g. a header noting a position was "SOFTENED to X" with the pre-revision text nowhere in the file. Record `UNRECOVERABLE` and set `both_states_preserved: false`. **Never reconstruct the original from the revision** — that is inventing history to fill a schema field (§28). The fact that the original is unrecoverable is itself a finding about the corpus.

**`revision_type` is not always refutation.** Observed in one ten-file window: a refutation (a claim shown false), a scope narrowing (a verdict kept but its blast radius restricted), a downgrade along the evidence ladder (a *finding* demoted to a *hypothesis* at n=1 — §35's states changing without the claim being wrong), a factual correction (an artifact said to be missing turned out to exist), and a softening (an identity claim weakened to an extension claim). Collapsing these into "revised" loses the distinction between *"this was wrong"* and *"this was over-claimed"* — which is exactly the distinction §35 and §29 exist to preserve.

**Record both states.** The original claim is historical fact even after the same file retracts it — and a file that visibly corrects itself is evidence *for* the corpus's discipline, not against its reliability. Do not silently read only the corrected text (§28, §29).

---

# 6. FILE DOSSIER

For every processed file create a File Dossier.

At minimum record:

## 6.1 Identity

* File ID
* timestamp
* path
* sequence

## 6.2 Purpose

What is the file trying to do?

Examples:

* define;
* investigate;
* derive;
* test;
* correct;
* synthesize;
* challenge;
* validate;
* document;
* implement;
* compare;
* summarize;
* open a research question.

Do not infer purpose solely from filename.

Use the complete file.

## 6.3 What the file establishes

Record:

* definitions;
* objects;
* propositions;
* assumptions;
* rules;
* mathematical statements;
* algorithms;
* models;
* decisions;
* conclusions;
* evidence.

## 6.4 What the file assumes

Record every important assumption that is explicit or necessary for its reasoning.

Distinguish:

* explicitly stated;
* implicitly required;
* inferred but not stated;
* uncertain.

## 6.5 What the file depends on

Record:

* explicit file references;
* named prior work;
* semantic dependencies;
* mathematical prerequisites;
* definitions required for interpretation.

## 6.6 What the file changes

Identify whether it:

* introduces;
* extends;
* refines;
* corrects;
* replaces;
* supersedes;
* rejects;
* contradicts;
* validates;
* applies;
* branches from;
* merges previous work.

---

# 6A. EVIDENCE OBJECT (FIRST-CLASS RECORD)

`evidence[]` embedded inside other records (§9, §13) is not itself modeled — for research-grade provenance, evidence must be a first-class object with its own identifier, distinct from whatever record it supports.

Assign every important piece of evidence a stable identifier `E-####` (§4A), with at least:

```text
evidence_id
file_id
location
excerpt_or_reference
evidence_type
supports
contradicts
source_status
track
confidence
```

An Evidence Object can be cited by a relationship, a gap, or a theory object:

$$
E
\rightarrow
T
$$

$$
E
\rightarrow
R
$$

$$
E
\rightarrow
G
$$

This gives every claim in the reconstruction a traceable, reusable evidence identifier rather than a copy of the same excerpt duplicated across records.

### Three provenance layers

Source provenance is not the whole chain. There are three distinct layers, and only the first is source evidence:

```text
SOURCE PROVENANCE
    ↓
RECONSTRUCTION INTERPRETATION
    ↓
DERIVED MODEL
```

For example: `F032` says X → the agent interprets X as Theory Object `T007` → `T007` is connected to `T012`. The second arrow is not source evidence; it is an **analytical interpretation**, and must be recorded as one:

```text
interpretation_basis
interpretation_method
interpreter
interpretation_confidence
```

### Negative evidence

Absence of a relationship is not automatically the same as evidence *for* independence (§25). But when a file explicitly states independence — e.g. "this work is independent of the previous model" — that statement is itself an Evidence Object, not merely a `NONE_FOUND` (§25). Record it as:

```text
negative_evidence
```

a first-class `evidence_type` alongside supporting and contradicting evidence. This matters particularly for branch/parallel research threads (§23).

### Confidence is not one-dimensional

`confidence` fields appear throughout this protocol (§0A, §0C, §13, §19A). They are not interchangeable — a high-confidence file relationship must never be read as high confidence in the mathematical derivation it might imply. Distinguish at least:

```text
evidence_confidence
relationship_confidence
semantic_identity_confidence
continuity_confidence
gap_confidence
```

---

# 7. FILE-TO-FILE RELATIONSHIP

The basic file graph is:

$$
G_F=(F,E_F)
$$

Each verified edge must contain more than:

```text
F001 → F002
```

It must contain:

```text
source
target
relationship
direction
evidence
evidence_location
reason
continuity_status
verification_status
theory_objects
derivation_dependency
```

> **A verified file edge must contain evidence supporting the file-to-file relationship. It SHOULD identify Theory Objects when semantic identity has already been established. If Theory Object identity is still unresolved, the file relationship may still be verified *as a file relationship* — it must simply not be presented as a verified *theory-level* relationship (§31) until `theory_objects` is populated.** Do not force premature Theory Object identity merely to satisfy this schema — that would recreate exactly the "same label = same concept" error §19A's identity test exists to prevent.

For example, a file relationship with resolved theory identity:

```text
F012 → F019

relationship:
    CONTINUATION

theory_objects:
    T-004
    T-011

evidence:
    E-0231

derivation_dependency:
    T-004 → T-011
```

and a file relationship verified before theory identity is resolved — still a valid, verified `G_F` edge (§7), just not yet promoted to a `G_D` theory-level claim (§30):

```text
F031 → F033

relationship:
    CONTINUATION

evidence:
    E-0298

theory_objects:
    []
```

Possible relationship types include:

* CONTINUATION
* DERIVATION
* EXTENSION
* REFINEMENT
* CORRECTION
* CONTRADICTION
* REPLACEMENT
* SUPERSESSION
* VALIDATION
* APPLICATION
* BRANCH
* MERGE
* NEW-RESEARCH-PATH
* INDEPENDENT
* UNCERTAIN

Do not force an existing relationship type if none fits.

---

# 8. CANDIDATE GRAPH VS VERIFIED GRAPH

Maintain two layers.

## Candidate graph

$$
G_C=(F,E_C)
$$

Candidate edges may come from:

* P3A;
* explicit citations;
* filename references;
* S-number references;
* chronology;
* textual clues;
* ML;
* embeddings;
* human/agent hypotheses.

## Verified graph

$$
G_V=(F,E_V)
$$

where:

$$
E_V \subseteq E_C
$$

and the relationship is supported by complete-file evidence.

> **`VERIFIED` means evidence-verified — the relationship is supported by complete-file evidence (§2) — never mathematically validated.** A verified edge can carry `historical_derivation_status: UNDERIVED` or `mathematical_validation_status: NOT_YET_ASSESSED` (§0A) and remain fully verified: verification is about the *relationship*, validation is about the *reasoning*, and these are different bounded contexts (§0D).

The verified graph is the authoritative graph for this reconstruction phase.

### Discovery-to-verification pipeline

Candidate discovery is broad and permissive; verification is narrow and evidence-based. Make the execution algorithm explicit rather than leaving it implicit in the candidate/verified distinction above:

```text
Candidate Search
      ↓
Candidate Edge
      ↓
Evidence Retrieval (§6A)
      ↓
Complete-file comparison
      ↓
Verified Edge
```

---

# 8A. PHASE 0 — CORPUS INTELLIGENCE PASS

Running the full 30-step, 5-layer pipeline (§9) for every file is expensive, and most of that cost is search: Layer 3's predecessor discovery (§9, §15) is cheap once an index exists and expensive without one. This phase builds that index before deep reconstruction begins — it does **not** relax complete-file reading (§2) or replace chronological traversal (§3, §32); it makes the searches those principles require cheaper to execute.

### What Phase 0 does

For every file, in chronological order, read the **complete file** and extract only:

```text
definitions_mentioned
claims_mentioned
explicit_references
named_objects
named_methods            # optional — named algorithms/procedures/techniques, when distinct from named_objects
research_question
architecture_terms
```

`named_objects` captures any named concept, model, or theory candidate mentioned by name — even before it is clear whether it is a Theory Object (§19A) in the full sense — so Phase 0's index can seed thread discovery (§19B) on more than just formal definitions and claims.

No continuity assessment (§10). No gap analysis (§13). No derivation reconstruction (§0A). This is Layer 1 of §9 only, run across the whole corpus first.

### What Phase 0 produces

```text
CORPUS-INDEX.jsonl
```

one row per file, feeding candidate Theory Thread discovery (§19B) and candidate edges (§8) with a starting point instead of a blind search.

### What Phase 0 does not do

It does not decide relationships, continuity, or gaps — those remain the province of the full per-file protocol (§9), which still runs on every file afterward, in the same chronological order. Phase 0 makes that later pass's searches targeted instead of exhaustive; it does not shrink the set of files that receive the full 30-step treatment, and it never substitutes for reading a file completely.

> **Phase 0 must never be used to exclude a file from Layers 2–5. It only reorders search priority within them.** A file with a sparse Phase-0 index (few or no `named_objects`) still receives the full pipeline — sparseness there may itself be evidence of an isolated or transitional file (§17's `Drift`, §25's `INDEPENDENT`), which is a finding, not a reason to skip it.

---

# 9. PER-FILE DETERMINISTIC RECONSTRUCTION PROTOCOL

> **NO FILE MAY BE PROCESSED USING AN AD-HOC ANALYSIS PATTERN.**

The processing procedure, required fields, validation checks, gap detection procedure, continuity checks, and provenance requirements are invariant across all files. Only the resulting content may differ according to what the file actually contains. If 3,000 files are processed, an auditor must be able to confirm that `F1723` was analysed by the same method as `F0012` — otherwise the resulting graph reflects changes in analysis behaviour rather than changes in the historical theory itself.

> **For every file, execute Layers 1–5 below in exactly this order, and complete and validate the current file's reconstruction record before proceeding to the next file. The same procedure, schema, evidence rules, graph rules, gap rules, and provenance rules apply to every file. Only the resulting content may differ.**

For every `file_id` in the canonical registry (§3), execute the same sequence, grouped into five layers that follow the natural dependency — first what the file says, then what it means, then how it connects/derives, then what is missing, then how the accumulated model changes:

```text
READ FILE
    │
    ▼
┌─────────────────────────────────────────────────────────┐
│ LAYER 1 — EVIDENCE & COMPLETE FILE UNDERSTANDING         │
├─────────────────────────────────────────────────────────┤
│ 1. IDENTIFY                                              │
│ 2. READ COMPLETE FILE                                    │
│ 3. EXTRACT THEORY                                        │
│ 4. IDENTIFY DEFINITIONS                                  │
│ 5. IDENTIFY ASSUMPTIONS                                  │
│ 6. IDENTIFY PREMISES                                     │
│ 7. IDENTIFY MATHEMATICAL / STATISTICAL CLAIMS            │
│ 8. IDENTIFY DERIVATIONS                                  │
│ 9. IDENTIFY CONCLUSIONS                                  │
│ 10. IDENTIFY DEPENDENCIES                                │
└─────────────────────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────────────────────┐
│ LAYER 2 — KNOWLEDGEOS PURPOSE, ARCHITECTURE & THEORY     │
│ ALIGNMENT (§0C)                                          │
├─────────────────────────────────────────────────────────┤
│ 11. IDENTIFY KNOWLEDGEOS PURPOSE RELEVANCE               │
│ 12. MAP FILE TO REFERENCE ARCHITECTURE (§0C)             │
│ 13. IDENTIFY ARCHITECTURAL ROLE                          │
│ 14. DETECT NEW / MISSING / CHANGED COMPONENTS            │
│ 15. COMPARE DEFINITIONS / ASSUMPTIONS / SEMANTICS        │
│     WITH PREVIOUS RELEVANT FILES                         │
│ 16. IDENTIFY THEORY OBJECTS (§19A)                       │
└─────────────────────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────────────────────┐
│ LAYER 3 — SEQUENTIAL GRAPH, RELATIONSHIPS &              │
│ DERIVATION RECONSTRUCTION                                │
├─────────────────────────────────────────────────────────┤
│ 17. BUILD / UPDATE SEQUENTIAL GRAPH (§0A)                │
│ 18. DETERMINE FILE & THEORY RELATIONSHIPS (§7, §31)      │
│ 19. RECONSTRUCT DERIVATION CHAIN                         │
└─────────────────────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────────────────────┐
│ LAYER 4 — CONTINUITY, DERIVATION-COMPLETENESS & GAP AUDIT│
├─────────────────────────────────────────────────────────┤
│ 20. ASSESS CONTINUITY (§10, §11)                         │
│ 21. AUDIT DERIVATION COMPLETENESS                        │
│     — historical, not mathematical validity (§0C, §29)   │
│ 22. RECORD EVERY GAP (§13)                               │
│ 23. RECORD CONTRADICTIONS (§22)                          │
└─────────────────────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────────────────────┐
│ LAYER 5 — RECONSTRUCTION STATE & IMPLEMENTATION READINESS│
├─────────────────────────────────────────────────────────┤
│ 24. UPDATE THEORY MODEL (§19A)                           │
│ 25. BUILD / UPDATE CANDIDATE THEORY OBJECTS (§19C)       │
│ 26. BUILD / UPDATE CANDIDATE THEORY RELATIONSHIPS (§19C) │
│ 27. UPDATE ARCHITECTURE OBJECT REGISTRY (§0C)            │
│ 28. UPDATE IMPLEMENTATION MODEL (§30A)                   │
│ 29. RECORD PROVENANCE                                    │
│ 30. VALIDATE THE FILE DOSSIER (§6)                       │
└─────────────────────────────────────────────────────────┘
    │
    ▼
VALIDATE FILE
    │
    ▼
CHECKPOINT
    │
    ▼
NEXT FILE
```

Never stop at a keyword match.

### The 30 steps are an audit checklist, not an execution order

> *(Pilot finding, batch 001.)* Steps 4–10 — `IDENTIFY DEFINITIONS` / `ASSUMPTIONS` / `PREMISES` / `CLAIMS` / `MATHEMATICAL OBJECTS` / `STATISTICAL OBJECTS` / `CONCLUSIONS` — are **not seven separable operations**. They are one attentive reading of the file, described seven ways. Executing them as seven passes wastes effort and produces no additional fidelity; a researcher extracts all seven while reading once.

The **five layers are the real sequence** and their order is load-bearing: evidence before meaning, meaning before connection, connection before audit, audit before state update. Inverting *those* produces the retrofitting error §9's Layer-2 dependency order exists to prevent.

The **numbered steps are the completeness check** applied after each layer: *"did I actually extract assumptions, or did I skim past them?"* Their value is that nothing is silently omitted — not that each is a separate act.

**Therefore:** execute five layers in order; use the thirty steps to verify each layer is complete before moving to the next; never report a step as "performed" if it was merely not applicable — record `NOT_APPLICABLE` and why (§9's "empty is a valid result").

### What each layer asks

| Layer | Question | Steps |
| --- | --- | --- |
| 1 — Evidence & Complete File Understanding | What is actually present in this file? No interpretation beyond faithful understanding — source-grounded facts only. | 1–10 |
| 2 — KnowledgeOS Purpose, Architecture & Theory Alignment | What is KnowledgeOS supposed to accomplish, what is its reference architecture (§0C), and where does this file belong? Only then: how do this file's semantic objects compare with what's already reconstructed — and only after *that*, what Theory Object candidates does it establish or change (§19A)? Do **not** yet decide whether the mathematics is correct. Fixed dependency order: source → purpose → architectural role → semantic objects → Theory Object candidates → relationships (Layer 3) — an agent must never identify a Theory Object first and retrofit the architecture/semantics around it. | 11–16 |
| 3 — Sequential Graph, Relationships & Derivation Reconstruction | How does this file connect to what came before, and what does the derivation chain look like? Chronological order alone is never treated as proof of a relationship (§3, §31); every verified edge must cite the Theory Objects that justify it (§7). Purely reconstructive — not yet an audit of correctness. | 17–19 |
| 4 — Continuity, Derivation-Completeness & Gap Audit | Does the theory actually continue (§10–§11)? Does the corpus provide the required reasoning (derivation *completeness*) — distinct from whether that reasoning is mathematically *correct* (§0C, §29)? Every finding becomes a Gap Record (§13) or Contradiction Record (§22). | 20–23 |
| 5 — Reconstruction State & Implementation Readiness | What does this file change in the accumulated model, including provisional Candidate Theory consolidation (§19C)? Only after Layers 1–4 are complete does the persistent state update. | 24–30 |

Layer 3's predecessor/successor search follows the same progressive order as the Gap Search Protocol (§15): same file → previous relevant files → known theory thread → explicit references → known branches → later files when needed → whole-corpus search only when required. Do not search the entire corpus for every file — the graph built so far tells you where to look next.

### Reference pseudocode

```text
LOAD chronological file inventory (§3)
LOAD reconstruction state
LOAD reference architecture (§0C) — frozen before execution

FOR each file in chronological order:

    file = READ_COMPLETE_FILE()

    ── LAYER 1: EVIDENCE ──
    evidence = extract_source_grounded_content(file)
    validate_evidence_record(evidence)

    ── LAYER 2: PURPOSE / ARCHITECTURE / THEORY ──
    identify_purpose_relevance(file)
    map_to_reference_architecture(file)
    identify_architectural_role(file)
    detect_architecture_changes(file)
    detect_definition_changes()
    detect_assumption_changes()
    detect_scope_changes()
    detect_semantic_changes()
    theory_objects = identify_theory_objects(evidence)      # after semantics, not before
    compare_with_existing_theory(theory_objects)

    ── LAYER 3: GRAPH / RELATIONSHIPS / DERIVATION ──
    find_relevant_predecessors()
    determine_file_and_theory_relationships()
    update_sequential_graph()
    reconstruct_derivation_chain()

    ── LAYER 4: CONTINUITY / DERIVATION-COMPLETENESS / GAP ──
    assess_continuity()
    audit_derivation_completeness()          # historical, not validity
    detect_missing_definitions()
    detect_missing_assumptions()
    detect_missing_premises()
    detect_missing_derivation_steps()
    detect_unjustified_inferences()
    detect_scope_or_type_errors()
    detect_contradictions()
    search_for_possible_gap_resolution()
    create_or_update_gap_records()
    create_or_update_contradiction_records()

    ── LAYER 5: STATE / IMPLEMENTATION ──
    update_theory_object_registry()
    build_or_update_candidate_theory_objects()      # §19C — provisional, retractable
    build_or_update_candidate_theory_relationships() # §19C — never VALIDATED/CANONICAL
    update_architecture_object_registry()
    update_definition_registry()
    update_assumption_registry()
    update_derivation_graph()
    update_gap_ledger()
    update_contradiction_registry()
    update_implementation_readiness()
    record_full_provenance()
    validate_file_record()
    SAVE_CHECKPOINT()

    NEXT FILE
```

Mathematical and statistical *validation* — is the reasoning actually correct, not merely present — is explicitly out of scope for this phase (§0B, §0C, §29) and deliberately does not appear in the pseudocode above.

### File Reconstruction Record

Each file produces a **File Reconstruction Record** — the machine-readable counterpart of the File Dossier (§6) — with a fixed schema:

```text
file_id
sequence
timestamp                     # the registry timestamp, preserved verbatim (§4)
date_events[]                 # (§3) typed date evidence — NEVER a scalar "the file's date"
historical_sequence_date      # (§3) DERIVED from date_events[]; may be UNCERTAIN
historical_sequence_date_basis
historical_sequence_date_confidence
path

purpose
source_self_declared_status   # (§29) what the file says about ITSELF — see below
source_research_question      # the question the file's author appears to be asking
reconstruction_question       # the question this reconstruction is asking about the file — never conflate the two

definitions[]
assumptions[]
premises[]
claims[]
mathematical_objects[]
statistical_objects[]
derivations[]
conclusions[]
dependencies[]

previous_related_files[]
successor_candidates[]
relationships[]

continuity_assessment
mathematical_continuity
statistical_continuity
logical_continuity
semantic_continuity
reasoning_documentation_quality[]   # (§0E.4 Level 2) — per-dimension documentation clarity, never a truth judgment

gaps[]
contradictions[]
corrections[]
scope_changes[]
definition_changes[]

theory_state
implementation_state

evidence[]
confidence[]

validation_status
```

`source_research_question` must be one of `EXPLICITLY_STATED_BY_SOURCE`, `RECONSTRUCTED_FROM_SOURCE`, or `NONE_IDENTIFIABLE` — never silently merged with `reconstruction_question`, which is the reconstruction's own question about the file, not the author's.

### `source_self_declared_status` — capture what the file says about itself

> *(Pilot finding, batch 001.)* Every file in the first window opens with a header table declaring its own `Kind`, `Status` and `Authority` — e.g. *"CANDIDATE — NOT ADOPTED"*, *"Generated — never authoritative without human review"* — and most close with *"Nothing in this document executes."* Several also declare a machine-verifiable `Placement: DERIVED → … (exit 0)`.

This is the corpus doing §29's `SOURCE_ASSERTED_CONCLUSION` work *for* the reconstruction, and it is free to capture:

```text
declared_kind             e.g. SYNTHESIS / PROJECTION / EXPERIMENT RESULT / OBSERVATION INSTRUMENT
declared_status           e.g. CANDIDATE — NOT ADOPTED
declared_authority        e.g. Generated — never authoritative without human review
declared_execution_effect e.g. "nothing in this document executes"
declared_placement        e.g. DERIVED → docs/knowledgeos (exit 0)
```

Record it verbatim and **never upgrade it**. A file declaring itself `CANDIDATE — NOT ADOPTED` constrains every claim drawn from it: no such claim may be recorded at a higher standing than the file claims for itself (§19A, §35). Where a file makes *no* self-declaration, record `NONE_DECLARED` — its absence is itself a signal.

**Empty is a valid result.** If a file contains no statistical derivation:

```text
statistical_objects: []
statistical_continuity: NOT_APPLICABLE
```

Do not invent content merely because the schema expects a field.

### Stateful chronological processing

The theory is sequential, so files are not processed as thousands of independent summaries. After processing \(F_i\), update the reconstruction state before reading \(F_{i+1}\):

$$
\text{State}(F_0)
\rightarrow
\text{Read } F_1
\rightarrow
\text{State}(F_1)
\rightarrow
\text{Read } F_2
\rightarrow
\text{State}(F_2)
\rightarrow
\cdots
\rightarrow
\text{State}(F_n)
$$

Equivalently, per file:

```text
FILE F₁
   │
Layer 1 — Evidence
   ↓
Layer 2 — Purpose + Architecture + Theory
   ↓
Layer 3 — Graph + Relationships + Derivation
   ↓
Layer 4 — Continuity + Completeness + Gaps + Contradictions
   ↓
Layer 5 — State + Implementation Readiness
   │
   ▼
RECONSTRUCTION STATE₁
   │
   ▼
FILE F₂
   │
  ...
   ▼
RECONSTRUCTION STATEₙ
```

The five layers do not produce the final KnowledgeOS theory (§0B) — they produce the structured, provenance-preserving reconstruction from which the later validated/canonical theory can be derived.

When processing \(F_{i+1}\), ask explicitly: what did it inherit, change, extend, contradict, complete, or leave unresolved from the reconstructed state up to \(F_i\)? Never start from zero.

---

# 9A. PER-FILE QUALITY GATES

A file cannot be marked complete simply because it was read. Track explicit gates, in order:

```text
FILE_READ
EXTRACTION_COMPLETE
SEMANTIC_UNDERSTANDING_COMPLETE
ARCHITECTURE_ALIGNMENT_CHECKED
RELATIONSHIP_CHECKED
DERIVATION_CHECKED
CONTINUITY_ASSESSED
GAP_CHECKED
THEORY_AND_ARCHITECTURE_OBJECTS_UPDATED
IMPLEMENTATION_IMPLICATIONS_CHECKED
PROVENANCE_COMPLETE
RECONSTRUCTION_RECORD_VALIDATED
```

`RECONSTRUCTION_RECORD_VALIDATED` (not bare `VALIDATED`) means the File Reconstruction Record passed its protocol integrity checks — every required field present, every reference resolvable. It does **not** mean the mathematics has been validated; that is `mathematical_validation_status`/`statistical_validation_status` (§0A), a separate and almost always still-`NOT_YET_ASSESSED` field on this same file's content.

A file can technically be read and extracted while remaining semantically ambiguous. If understanding is genuinely uncertain after extraction, the gate does not force completion — it becomes `UNDERSTANDING_UNCERTAIN`, recorded and carried forward rather than silently passed.

Each gate corresponds to one or more steps of the pipeline in §9. A file's `dossier_status` (§5) may not be set to `COMPLETE` until every gate above is satisfied.

A batch has its own separate gate — see §37 (Batch Validation).

---

# 10. THEORY CONTINUITY IS A FIRST-CLASS OBJECT

For every significant transition:

$$
F_A\rightarrow F_B
$$

assess whether the theory actually continues correctly.

### What counts as a significant transition

Chronological adjacency alone never triggers a theoretical edge (§3, §31). A transition \(F_A \rightarrow F_B\) is **significant** — and therefore must receive full continuity/derivation assessment — if at least one of the following holds:

```text
1. shared Theory Object (§19A)
2. explicit reference
3. explicit continuation/correction claim
4. shared mathematical dependency
5. shared definition
6. shared assumption
7. shared research question
8. candidate graph evidence above threshold (§8)
9. architectural component overlap (§0C)
10. explicit contradiction
11. branch/merge evidence (§23, §24)
```

An unassessed pair with none of the above is simply not investigated further; it need not be recorded as `NONE_FOUND` (§25) unless it was already a candidate edge (§8).

Record:

### Semantic continuity

Does B preserve the meaning of concepts established by A?

### Definition continuity

Are important terms still defined consistently?

### Derivation continuity

Are the premises needed by B actually established?

### Mathematical continuity

Does the corpus provide an explicit or reconstructible justification for the mathematical transformation? (Not: is the transformation valid — that is mathematical validation, out of scope for this phase; §0A, §0B.)

### Logical continuity

Does the corpus contain the premises and reasoning required for the claimed inference? (Not: is the inference valid — same scope boundary as above.)

### Type continuity

Do objects remain the same semantic/mathematical types?

### Assumption continuity

Are assumptions preserved?

### Scope continuity

Does B remain inside the regime under which A was established?

### Evidence continuity

Does B have adequate evidence for its claims?

### Dependency continuity

Are all necessary dependencies present?

### Conclusion continuity

Does B legitimately extend what A established?

---

# 11. CONTINUITY STATUS

Every significant transition must receive one of:

```text
CONTINUOUS
PARTIAL
BROKEN
CORRECTED
CONTRADICTORY
UNCERTAIN
```

### CONTINUOUS

The later file follows from the earlier work without a material unresolved gap.

### PARTIAL

The later file continues the earlier work but one or more dependencies/steps remain incomplete.

### BROKEN

A necessary transition cannot currently be justified.

### CORRECTED

The later file explicitly or demonstrably corrects an earlier result.

### CONTRADICTORY

The later file conflicts with the earlier result and the conflict is not merely a correction already reconciled.

### UNCERTAIN

Available evidence is insufficient to determine continuity.

Never force `CONTINUOUS`.

See §0A for the companion `historical_derivation_status`/`derivation_completeness` fields, which assess specifically whether and how much of the mathematical/statistical argument was carried through (as distinct from overall continuity, and from mathematical validity).

---

# 12. DERIVATION CONTINUITY

For a mathematical or logical argument, reconstruct:

$$
P_1,P_2,\ldots,P_n
\rightarrow
I_1,I_2,\ldots,I_m
\rightarrow
C
$$

where:

* \(P_i\) = premises;
* \(I_i\) = intermediate derivations;
* \(C\) = conclusion.

Check whether every required transition exists.

If:

$$
P_1,P_2
\rightarrow
?
\rightarrow
C
$$

then the missing step must be recorded.

Do not silently fill it using general knowledge.

### Multiple derivations of the same result must remain visible

The File Reconstruction Record's `derivations[]` (§9) is a flat list with no provenance-per-entry schema — insufficient once the same Theory Object has been derived more than once across the corpus, which loses exactly the information a later cross-theory comparison (§0B) needs. When that happens, give each derivation its own **Derivation Instance**, `DI-####` (§4A):

```text
derivation_instance_id
theory_object_id
source_file_id
premises
assumptions
intermediate_steps
conclusion
derivation_status
evidence
gap_ids
relationship_to_other_derivations
```

For example:

```text
Theory Object T-019

DI-001   F023   Method A
DI-002   F041   Method B
DI-003   F077   Corrected Method C
```

Do not collapse these into one canonical derivation during the first pass — that decision (whether they are equivalent, competing, or one corrects another) belongs to the later cross-theory comparison stage, once the whole corpus has been read (§0B). The goal of `DI-####` is not to create more objects for their own sake; it is simply to never lose the fact that the same result was derived more than once.

---

# 13. GAP RECORD (FORMAL, FIRST-CLASS RESEARCH OBJECT)

A missing derivation is a first-class recorded research object, not a note attached to a file. Every gap must receive a unique, immutable `gap_id` (e.g. `G-00017`) and be represented as a formal **Gap Record**.

> **Every missing, incomplete, unsupported, or broken mathematical, statistical, logical, definitional, or dependency step MUST be recorded as a Gap Record. A gap must never be silently filled, repaired, or ignored.**

A Gap Record must contain at least:

| Field | Purpose |
| --- | --- |
| `gap_id` | Immutable identifier, e.g. `G-00017` |
| `gap_type` | What kind of gap exists (§14) |
| `source_file_id` | File where the gap becomes visible |
| `source_location` | Section/page/heading/line if available |
| `preceding_claim` | Last established statement before the gap |
| `missing_element` | Exactly what is missing |
| `expected_element` | What would be required to continue |
| `following_claim` | First statement after the gap |
| `dependency` | What the following claim appears to depend on |
| `evidence` | Exact source evidence supporting the gap finding |
| `mathematical_status` | Complete / partial / underived / etc. |
| `statistical_status` | Same, where applicable |
| `historical_status` | What the historical corpus actually establishes — never conflated with mathematical validity (§29) |
| `resolution_status` | Open / resolved / superseded / contradicted |
| `resolution_source` | Later file or evidence that resolves it, if any |
| `confidence` | Confidence in the gap identification |
| `notes` | Additional explanation |

### Worked example

```text
F014
  Definition D1
      ↓
  Assumption A1
      ↓
  Lemma L1
      ↓
  [G-00017]
  UNDERIVED_STEP
      ↓
F021
  Theorem T1
```

```text
G-00017
gap_type: UNDERIVED_STEP

preceding_claim:
    Lemma L1

missing_element:
    Derivation connecting L1 to the conditions required
    for Theorem T1.

following_claim:
    Theorem T1 is asserted in F021.

expected_element:
    A proof or intermediate proposition establishing
    that L1 implies the premises of T1.

resolution_status:
    OPEN
```

This is a Gap Record, not a note that "there seems to be a missing step" — every field is machine-checkable and auditable (§37). Any diagram label for a gap (the bracketed `[G-#####]` marker) must use an actual `gap_type` value from §14, never an ad hoc phrase — the diagram above uses `UNDERIVED_STEP` because that is the `gap_type` this record actually declares.

> **Every detected discontinuity between an established statement and a subsequent claimed result MUST be represented by a unique Gap Record. The record must identify what is established before the gap, what is claimed after the gap, what mathematical/statistical/logical element is required to connect them, what corpus search was performed (§15), and whether the gap was later resolved (§16). The reconstruction system MUST NOT silently supply the missing reasoning (§28).**

A gap is a first-class research object.

### One event, one primary record

> *(Pilot finding, batch 001.)* A single historical event is often legitimately recordable in several registers at once. One refutation in the corpus qualified simultaneously as an intra-file revision (§5A), a contradiction (§22), and a verified `CORRECTION` edge (§7) — and was written to all three. At corpus scale that triples the ledger for the corpus's **most common and most valuable** event type.

**Rule:** each event gets **one primary record** in the register that owns its *kind*, plus lightweight cross-references from the others:

| Event kind | Primary register | Cross-referenced from |
|---|---|---|
| A later file corrects an earlier one | **Verified edge** (§7), `relationship: CORRECTION` | contradiction, if the claims genuinely conflict |
| A file corrects itself in place | **Intra-file revision** (§5A) | — |
| Two claims conflict with no correction between them | **Contradiction** (§22) | — |
| A required step is absent | **Gap** (§13) | — |
| The source demands future work | **Research Obligation** (§0E.3) | gap, only if the obligation is also a missing step |

A cross-reference is an ID in a `related_records[]` field — never a duplicated narrative. Where an event genuinely belongs to two kinds (a gap that is *also* a contradiction, as when two closed vocabularies collide and no scoping rule exists), record it once in each **and say so in both**, so a reader counting findings does not double-count.

---

# 14. GAP TAXONOMY

At minimum support:

### MISSING_DEFINITION

A concept is used without an adequate definition.

### MISSING_PREMISE

A derivation requires a premise not established.

### UNDERIVED_STEP

An intermediate logical/mathematical step is absent — the general "gap in the middle" type. Use `DERIVATION_CONTINUATION_MISSING` instead when the entire connecting argument between a stated start and a stated end is absent, not just one step.

### UNJUSTIFIED_INFERENCE

An inference is asserted without sufficient justification.

### MISSING_DEPENDENCY

A required prior result/object is not identified.

### SEMANTIC_DRIFT

A term changes meaning without explicit justification.

### SCOPE_SHIFT

A result moves to a different regime without establishing validity there.

### TYPE_MISMATCH

The semantic or mathematical type changes incompatibly.

### ASSUMPTION_DROPPED

A required assumption disappears.

### CONTRADICTION

Two established claims conflict.

### UNRESOLVED_BRANCH

Multiple competing derivation branches remain unresolved.

### MISSING_ASSUMPTION

A derivation step relies on an assumption that is never stated anywhere in the corpus (distinct from `ASSUMPTION_DROPPED`, where a stated assumption disappears from later use).

### UNDEFINED_SYMBOL

A variable, symbol, or term is used without ever being defined.

### INCOMPLETE_PROOF

A formal proof specifically is started but never completed within the searched corpus. Use `DERIVATION_END_UNRESOLVED` for the same situation in a non-proof derivation (an argument, a construction, a statistical procedure).

### DERIVATION_START_UNRESOLVED

The starting premises of a derivation are themselves not established.

### DERIVATION_CONTINUATION_MISSING

A derivation is established at its start and claimed at its end, but the entire connecting argument is absent — distinct from a single `UNDERIVED_STEP`, which is one missing step inside an otherwise-present chain.

### DERIVATION_END_UNRESOLVED

A derivation begins correctly but never reaches a stated conclusion. General case of `INCOMPLETE_PROOF` for any derivation, not only a formal proof.

### ALGEBRAIC_INCONSISTENCY

An algebraic transformation does not follow from the preceding line.

### LOGICAL_INCONSISTENCY

A stated inference does not follow under standard logical rules.

### STATISTICAL_METHOD_INCONSISTENCY

A statistical method is applied outside the conditions under which it is valid.

### MATHEMATICAL_CONDITION_MISSING

A theorem or proposition is applied without establishing a condition required for it to hold.

### STATISTICAL_JUSTIFICATION_MISSING

A probability or statistical claim is asserted without the supporting distributional/model assumption.

### UNJUSTIFIED_GENERALIZATION

A result established in a specific case is applied as if general, without demonstrating the generalization.

### CONCLUSION_EXCEEDS_PREMISES

The stated conclusion is logically or mathematically stronger than what the premises support.

### POTENTIAL_VALIDATION_ISSUE

A possible mathematical/statistical/logical problem is suspected but is not directly demonstrable from the source's own stated rules, definitions, equations, or conditions.

> **`ALGEBRAIC_INCONSISTENCY`, `LOGICAL_INCONSISTENCY`, `STATISTICAL_METHOD_INCONSISTENCY`, and `MATHEMATICAL_CONDITION_MISSING` may be assigned only when the inconsistency is directly demonstrable from the source's own stated rules, definitions, equations, or explicit conditions — never merely because the reconstruction agent believes the mathematics is wrong. Otherwise use `POTENTIAL_VALIDATION_ISSUE` or `UNCERTAIN` (§35).** This is what keeps the reconstruction agent from accidentally becoming the mathematical validator (§0B, §29).

### OUT_OF_WINDOW_DEPENDENCY

A required artifact is named by the file but lies outside the batch currently being processed — **not** outside the corpus. Expected to resolve trivially once the pass reaches it.

> *(Pilot finding, batch 001: all twelve instruments a synthesis file cited as CANONICAL lay outside the ten-file window. Every small batch will produce these in quantity — they are a property of **sampling**, not of the corpus.)*

This type exists so that batch-scope absences do not contaminate the Gap Ledger's real findings. It carries `expected_resolution_batch` where predictable, and **must never be promoted to `UNDERIVED_BY_CORPUS`** without the full progressive search of §15 — the two mean opposite things about the corpus.

### UNDERIVED_BY_CORPUS

A required result was not found after a sufficiently defined corpus search.

Do not use `UNDERIVED_BY_CORPUS` merely because it was not found between two adjacent files.

`UNDERIVED_BY_CORPUS` means no supporting derivation was found in the searched corpus. It does **not** mean the derivation is mathematically invalid or impossible (§29) — that is a separate question for a later mathematical validation phase (§43).

---

# 15. GAP SEARCH PROTOCOL

When a gap appears between:

$$
F_A\rightarrow F_B
$$

do not immediately conclude that the theory is incomplete.

Search progressively:

### Level 1

Same file.

### Level 2

Immediately preceding relevant files.

### Level 3

Earlier files in the same theory thread.

### Level 4

Explicitly referenced files.

### Level 5

Branches / parallel derivations.

### Level 6

Later files that explicitly claim to complete the step.

### Level 7

Entire primary corpus.

Only after an appropriately bounded search may the status become:

```text
UNDERIVED_BY_CORPUS
```

The search scope must be recorded.

---

# 16. GAP RESOLUTION

A gap may later be resolved by discovering:

### A. An intermediate file

$$
F_A\rightarrow F_C\rightarrow F_B
$$

### B. An external dependency inside the corpus

$$
F_A\rightarrow F_B
$$

with:

$$
F_B\text{ depends on }F_X
$$

### C. An implicit, corpus-supported derivation

Only if the complete source actually supports it — the source contains enough information to reconstruct the author's intended intermediate reasoning, without the reconstruction adding external mathematical premises of its own. This is deliberately named `IMPLICIT_SUPPORTED_DERIVATION`, not "valid": establishing mathematical validity is out of scope for this phase (§0B, §0C).

### D. A later correction

The later file explicitly repairs an established but incorrect result.

### E. A later derivation

A chronologically later file explicitly supplies the missing derivation, without the earlier result having been wrong (distinct from D, which corrects an error). This is the outcome anticipated by Level 6 of the Gap Search Protocol (§15).

### F. No resolution

Then retain the gap as OPEN.

Never delete the historical fact that a gap existed.

If resolved, record:

```text
resolved_by
resolution_date
resolution_evidence
resolution_type
```

`resolution_type` corresponds to categories A–E above:

```text
INTERMEDIATE_FILE
EXTERNAL_DEPENDENCY
IMPLICIT_SUPPORTED_DERIVATION
LATER_CORRECTION
LATER_DERIVATION
```

### Worked example

Suppose F021 leaves gap `G-00017` open, and F038 later provides the missing derivation. Do not delete `G-00017`. Instead:

```text
G-00017
resolution_status: RESOLVED
resolution_source: F038
resolution_type: LATER_DERIVATION
```

The historical graph then preserves both facts:

```text
F014
  ↓
G-00017 (OPEN at this point)
  ↓
F021
  ↓
...
F038
  ↓
Resolution of G-00017
```

This preserves the actual history: the derivation was incomplete at F021 and was completed later at F038 — exactly the historical continuity information the reconstruction exists to capture.

---

# 17. DEFINITION TRACKING

Definitions must be tracked across files.

For every important concept:

$$
D_X=\{d_1,d_2,\ldots,d_n\}
$$

record:

* first introduction;
* subsequent definitions;
* refinements;
* changes;
* corrections;
* deprecated definitions;
* current historical status.

When the same term recurs across files, do not automatically merge the occurrences. Determine which of the following relationships holds between each pair — this is the formal `definition_relationship` enum a Definition record (§4A's `D-###`) uses:

```text
SAME_DEFINITION
REFINEMENT
EXPANSION
NARROWING
BROADENING
CORRECTION
SEMANTIC_DRIFT
INDEPENDENT_REDERIVATION
COMPETING_DEFINITION
UNCERTAIN
```

### SAME_DEFINITION

Meaning preserved (formerly "Stable definition").

### REFINEMENT

Later definition clarifies earlier meaning without changing its extent.

### NARROWING

Later definition restricts which cases the earlier meaning covers.

### BROADENING / EXPANSION

Later definition extends earlier meaning to cover more cases.

### CORRECTION

Later definition explicitly changes an error.

### SEMANTIC_DRIFT

Meaning changes without explicit reconciliation (formerly "Drift").

### INDEPENDENT_REDERIVATION

The same concept is independently defined again, apparently without knowledge of the earlier occurrence — distinct from a deliberate refinement.

### COMPETING_DEFINITION

Same name used for genuinely different concepts (formerly "Collision") — not yet resolved as one correcting or refining the other.

### UNCERTAIN

Available evidence is insufficient to classify the relationship — never force one of the above merely to fill the field.

**Preserve the historical definitions themselves regardless of classification.** A later analysis may determine that two definitions are mathematically equivalent; do not decide that prematurely by collapsing them during the first pass (§0E.1's chronological-not-final-theory principle applies here too).

This is especially important because a smooth filename/path graph can conceal semantic discontinuity.

---

# 18. PROPOSITION / CLAIM TRACKING

Where useful, assign stable identifiers to important propositions or claims.

For example:

```text
P001
P002
P003
```

Track:

$$
P_i:
\text{introduced}
\rightarrow
\text{refined}
\rightarrow
\text{tested}
\rightarrow
\text{corrected/rejected/validated}
$$

Do not assume two similarly worded propositions are identical.

Identity requires evidence.

### Claim type — never trust the source's own label

Classify what *kind* of claim is actually being made, independent of what the source calls it:

```text
DEFINITION
LEMMA
PROPOSITION
THEOREM
CONJECTURE
HEURISTIC
EMPIRICAL_OBSERVATION
ALGORITHMIC_RULE
INTERPRETATION
```

> **Never call something a `THEOREM` merely because the source calls it one.** If a file asserts "this proves..." or "PASS" or "validated," record the assertion as `SOURCE_ASSERTED_CONCLUSION` (§29) and classify its `claim_type` from what is actually demonstrated in the file, not from the source's own characterization. This is a labeling/classification act, not a correctness judgment — it does not decide whether the claim is true, only what kind of claim it is.

---

# 19. ASSUMPTION TRACKING

Track assumptions independently.

For each assumption:

```text
A001
introduced_in = F012
used_in = F020
status = ACTIVE / DROPPED / REPLACED / UNKNOWN
```

Detect when:

$$
C \text{ was valid only under } A
$$

but a later file applies C without A.

This should produce an:

```text
ASSUMPTION_DROPPED
```

gap unless the later file explicitly establishes independence from A.

---

# 19A. THEORY OBJECT REGISTRY

Definitions (§17), Propositions/Claims (§18), and Assumptions (§19) are tracked individually, but the reconstruction also needs a unifying registry of **Theory Objects** — the bridge between historical files and the later canonical theory (§0B).

Assign every significant theory object a stable identifier:

```text
T-001 Definition
T-002 Assumption
T-003 Proposition
T-004 Mathematical structure
T-005 Statistical model
T-006 Algorithm
T-007 Invariant
T-008 Transformation
...
```

Do not immediately write a "final theory" from these objects (§0B, §43).

Each Theory Object retains at least:

```text
theory_object_id
status
object_type
kernel_classification
historical_names
historical_sources
evolution
dependencies
assumptions
derivation
alternatives
gaps
contradictions
open_questions
validation_obligations
mathematical_status
statistical_status
logical_status
empirical_status
computational_status
implementation_status
provenance
```

`kernel_classification` records what *kind* of thing the object is relative to KnowledgeOS itself — a labeling act, not a validation verdict — distinguishing at least:

```text
KERNEL_PRIMITIVE
KERNEL_RELATION
KERNEL_SEMANTIC_RULE
KERNEL_INVARIANT
DERIVED_KNOWLEDGEOS_CONCEPT
SPECIALIZED_KNOWLEDGEOS_CONCEPT
EXTERNAL_THEORETICAL_REGIME
APPLICATION_CONCEPT
IMPLEMENTATION_CONCEPT
ANALYTICAL_VIEW
COMPUTATIONAL_OPERATION
UNCLASSIFIED
```

This is a candidate classification like everything else on this record (§0B) — assigning `KERNEL_PRIMITIVE` here never means the object has been validated as foundational, only that it currently reads that way from the evidence.

`historical_names[]` preserves every distinct name the object has appeared under across files — required precisely because §19A's identity test (below) forbids assuming a shared label means a shared object, which cuts both ways: different labels can also turn out to be the same object.

`alternatives[]` cross-references other Theory Objects or Derivation Instances (§12) that are candidate alternative formulations of this one — recorded, never resolved, during this phase (§0B's Cross-Theory Comparison is where that resolution happens).

`open_questions[]` and `validation_obligations[]` capture what remains unresolved about the object — distinct from a Gap Record (§13), which is anchored to a specific file-to-file discontinuity; these are anchored to the object itself.

`logical_status`, `empirical_status`, and `computational_status` follow the same discipline as `mathematical_status`/`statistical_status` (§0A): default to `NOT_YET_ASSESSED`/`NOT_APPLICABLE`, never silently implied by any other field on this record.

Even a repeatedly-asserted, elegant, or "no new primitive required"-style universal principle stays `THEORY_OBJECT_CANDIDATE` — canonical status is never assigned for elegance, repetition, or an author's own confidence, only by the later canonicalization phase (§43).

`status` must distinguish, at minimum:

```text
THEORY_OBJECT_CANDIDATE
RECONSTRUCTED_THEORY_OBJECT
VALIDATED_THEORY_OBJECT
CANONICAL_THEORY_OBJECT
```

Calling something `T-001 Definition` does not by itself mean it has been accepted as genuine canonical theory. During this phase, objects remain at `THEORY_OBJECT_CANDIDATE` or, once cross-file evidence supports them, `RECONSTRUCTED_THEORY_OBJECT`. `VALIDATED_THEORY_OBJECT` and `CANONICAL_THEORY_OBJECT` require the later mathematical validation and canonicalization phases (§0B, §43) and must not be assigned now.

### Theory Object identity requires agreement, not just a matching label

If `F100`, `F150`, and `F300` each use the word "Determination," the system must **not** automatically merge them into one `T-object` — semantic drift (§17) is one of the core research questions this protocol exists to surface, and a shared label can conceal exactly that. Identity requires agreement on all of:

```text
1. semantic meaning
2. object type
3. scope/regime (§20)
4. defining properties
5. dependencies
6. role in the theory
```

If any of these materially differ: `same label ≠ same Theory Object` — record them as distinct candidates and let a `DRIFT`/`COLLISION` finding (§17) connect them, rather than silently merging.

### Aggregate ownership

Theory Object is the aggregate root for its Definitions (§17), Assumptions (§19), and Propositions (§18): each `D-###`/`A-###`/`P-###` sits within exactly one Theory Object's consistency boundary at any point in the reconstruction, referenced from its owning `T-###`. This does not change their ID namespace (§4A) — they remain independently identified for evidence-linking (§6A) — it clarifies who decides their lifecycle status. See §0D for the bounded context this belongs to, and §19B for how Theory Objects themselves aggregate into threads across files.

Ownership need not be resolved the moment a child entity is extracted (§9 Layer 1) — a definition can exist before it is clear which Theory Object it belongs to, especially early in Layer 2. Every child entity's `ownership_status` must distinguish, at minimum:

```text
UNASSIGNED   — extracted, no candidate owner identified yet
CANDIDATE    — a likely owning Theory Object identified, not confirmed
ASSIGNED     — ownership confirmed at Layer 2's theory-object identification (§9 step 16)
REASSIGNED   — later evidence moved this entity to a different owning Theory Object (§5A records the revision)
CONTESTED    — more than one Theory Object plausibly owns this entity; unresolved
```

Never force a premature `ASSIGNED` to whichever `T-###` happens to exist first; the field defaults to `UNASSIGNED` and only advances when Layer 2 actually determines the owner.

The Theory Object Registry is populated incrementally as files are processed — semantic comparison happens at §9 step 15 and Theory Object candidates are identified at step 16 ("IDENTIFY THEORY OBJECTS"), in that order (§9's Layer 2 dependency order) — and the registry is updated at step 24 ("UPDATE THEORY MODEL"). It is never retroactively rewritten without provenance (§5, §37).

---

# 19B. THEORY THREAD

A Theory Object (§19A) rarely lives in one file. A **Theory Thread** is the reconstructed lineage of a Theory Object across the files that establish, extend, refine, correct, or apply it — arguably the real domain of interest (theory evolution), of which files are evidence rather than the domain itself.

Assign every thread a stable identifier `TH-###` (§4A), with at least:

```text
thread_id
primary_theory_object
member_theory_objects
supporting_files
status
events[]
```

`status` and `events[]` are kept separate: `status` is the thread's current state, `events[]` is the append-only log of transitions that produced it — collapsing the two would make it impossible to see *when* a thread merged or split without destroying the state that says what it is *now*.

`status` must distinguish, at minimum:

```text
DISCOVERED   — candidate cluster identified, identity not yet confirmed (§19A)
ACTIVE       — confirmed thread, currently being extended by new files
BRANCHED     — the thread has split into distinct sub-threads (§23)
MERGED       — absorbed into another thread (§24)
SUPERSEDED   — replaced by a later, better-supported thread — not an error correction (distinct from a Gap's `CORRECTED`, §14)
DORMANT      — no new supporting file found for an extended span, but not closed
UNCERTAIN    — evidence is genuinely ambiguous about which of the above applies
CLOSED       — deliberately retired and tracked as closed, never deleted (§28)
```

Given this richer status set, a thread's `status` alone already carries most of what a coarser `CANDIDATE`/`RETIRED`-style enum would need an event log to express — but the append-only event log below is still required for *when* and *by what evidence* each transition happened, which `status` alone cannot preserve.

### Thread events

Each entry in `events[]` records one lifecycle transition, with `event_type`, `file_id` (the file whose evidence triggered it), and `timestamp` — the same append-only discipline as Gap resolution (§16):

```text
DISCOVERY   — a candidate cluster of files/objects sharing a Theory Object emerges
IDENTITY    — the thread is confirmed distinct from other threads (§19A's identity test)
BRANCH      — the thread splits into distinct sub-threads (§23)
MERGE       — two threads are shown to be the same theory (§24)
SUPERSESSION — a later thread is shown to replace this one
DORMANCY    — no supporting file found for an extended chronological span
CLOSURE     — the thread is deliberately retired
```

### Threads accelerate; they do not replace, chronological reading

Threads are a **derived index** (built during §8A), not a new traversal order. Files are still read completely and chronologically (§2, §32) — a Theory Thread tells Layer 3's predecessor search (§9, §15) where to look first; it does not exempt any file from being read, and it does not permit skipping ahead out of chronological order.

A thread may only be marked `CURRENTLY_RECONSTRUCTED`, never `HISTORICALLY_COMPLETE`, per the rule in §33.

---

# 19C. CANDIDATE THEORY REGISTRY

Historical Reconstruction (§0–§19B) preserves *what the corpus says and how it developed*. It does not, by itself, give the reconstruction anywhere to record: *"based on everything recovered so far, these 500 historical Theory Objects appear to represent roughly 120 underlying candidate concepts."* That question belongs to this section — a fourth layer between Historical Reconstruction and Validated/Canonical Theory:

```text
1. SOURCE EVIDENCE            — what does a file actually say? (§6A)
2. HISTORICAL RECONSTRUCTION  — how did the ideas develop? (§0A, §9, §17–§19B)
3. CANDIDATE THEORY           — what coherent structure can currently be
                                 constructed from the recovered evidence?
                                 (this section)
4. VALIDATED / CANONICAL THEORY — what survives formal validation? (§0B item
                                 "out of scope," §43 — downstream, not here)
```

Only layers 1–3 belong to this phase. Layer 4 remains downstream, exactly as §0B and §43 already state — nothing in this section authorizes validation or canonicalization.

### Candidate consolidation is not canonicalization

Multiple historically-distinct Theory Objects may, on current evidence, appear to be the same underlying concept:

```text
F102 → "connection"
F217 → "relationship"
F435 → "typed relation"
F812 → "semantic relation"
```

The historical objects remain untouched and fully traceable — §17's `definition_relationship` classification (`SAME_DEFINITION`/`REFINEMENT`/... ) and §19A's Theory Object records for each are never edited or deleted. This section additionally records a **Candidate Theory Object**, `CT-####` (§4A), with at least:

```text
candidate_theory_object_id
candidate_name
historical_sources          # T-#### / F#### this candidate consolidates
source_terms                # the distinct historical names/labels observed
consolidation_basis         # why these appear to be the same concept
alternatives                # other candidate groupings this competes with
unresolved_questions
validation_obligations
candidate_relationships
kernel_classification        # reuses §19A's enum — still candidate-only here too
status
provenance
```

`status` must distinguish, at minimum:

```text
CANDIDATE_IDENTIFIED
CANDIDATE_SUPPORTED
CANDIDATE_CONSOLIDATED
CANDIDATE_CONTESTED
CANDIDATE_UNDERIVED
CANDIDATE_BLOCKED
CANDIDATE_DEFERRED
```

**Never introduce `VALIDATED` or `CANONICAL` into this state machine.** If either becomes appropriate, that is by definition the formal Cross-Theory Comparison / validation phase (§0B item 1, §43) acting on this record — not this phase relabeling it.

`candidate_relationships` uses:

```text
EQUIVALENT_CANDIDATE
REFINES_CANDIDATE
SPECIALIZES_CANDIDATE
GENERALIZES_CANDIDATE
DERIVED_CANDIDATE
POSSIBLE_DUPLICATE
COMPETING_CANDIDATE
REGIME_SPECIALIZATION
POSSIBLE_KERNEL_PRIMITIVE
POSSIBLE_DERIVED_CONCEPT
```

None of these are verified mathematical equivalences. They are hypotheses a later validation phase either confirms or rejects.

### Retractability

A candidate consolidation is reversible without touching the historical record:

```text
Historical:
    A, B, C  (T-#### each, untouched)

Candidate interpretation:
    A ≈ B  (CT-017, status: CANDIDATE_SUPPORTED)
    C refines A/B

Canonical status:
    NOT_YET_DETERMINED
```

If later validation shows A and B are actually different concepts, `CT-017` is retracted or split — the historical Theory Objects `T-A` and `T-B` are never modified to make that true; only `CT-017`'s own record changes, with provenance for why (§5, §37's no-silent-overwrite discipline applies here too).

### Candidate Theory Snapshot

The evolving candidate theory is checkpointed like reconstruction state (§36A), never overwritten in place:

```text
CT-S####  Candidate Theory Snapshot

source_corpus_state          # last_processed_file_id / sequence at snapshot time (§36A)
candidate_objects
candidate_relationships
candidate_kernel_classification_summary
open_contradictions
open_validation_obligations
provenance
```

### Candidate Theory Graph

A dedicated graph, kept separate from the file/sequential/derivation/gap graphs already defined (§30):

$$
G_{CT} = (CT, E_{CT})
$$

where \(CT\) = Candidate Theory Objects and \(E_{CT}\) = candidate relationships between them. Do not collapse \(G_{CT}\) with \(G_F\) (§7), \(G_S\) (§0A), \(G_D\) (§30), or \(G_G\) (§30) — each answers a structurally different question, and merging them would silently promote candidate hypotheses to the same status as verified file evidence.

### What Candidate Theory Construction may and may not do

**May:** semantic consolidation of historical objects into candidates; concept comparison; candidate abstraction; candidate kernel/regime classification (reusing §19A's `kernel_classification`); duplicate detection *among candidates*; candidate invariant identification (§21's non-collapse practice, at the candidate-object level); candidate relationship construction; local consistency checking (§0E.5); provenance analysis; candidate derivation reconstruction (§12).

**Must not claim:** mathematical validity; statistical validity; formal proof; empirical validation; computational optimality; canonical status. Those remain Phase 2 (§0B, §43) regardless of how confident a candidate consolidation appears.

---

# 20. SCOPE / REGIME TRACKING

Every significant mathematical result should be associated with its known regime.

Examples:

* finite;
* deterministic;
* bounded;
* source-grounded;
* executable;
* observational;
* semantic;
* experimental;
* hypothetical.

Never allow:

$$
\text{result valid in regime }R_1
$$

to silently become:

$$
\text{result valid universally}
$$

Record scope expansion explicitly.

---

# 21. TYPE AND ONTOLOGY CHECK

For important objects determine:

* what type they are;
* what relation they participate in;
* what operations are allowed;
* whether the type remains stable.

Detect:

$$
\text{Object}\rightarrow\text{Relation}
$$

or:

$$
\text{Observation}\rightarrow\text{Function}
$$

or similar unexplained type shifts.

Such shifts must be recorded as:

```text
TYPE_MISMATCH
```

unless explicitly justified.

### Non-collapse candidate invariants

Related concepts that read as similar are not automatically the same, and the corpus may implicitly or explicitly rely on them staying distinct. Record such distinctions as **candidate non-collapse invariants** — evidence-backed observations, not yet formally validated — the same "preserve, don't merge" discipline §17 applies to definitions and §19A applies to Theory Object identity, extended to relations and concepts generally. For example:

```text
Interest(a,x)          ↛ EvidenceFor(x)
Preference(a,x)        ↛ Truth(x)
Incentive(a,x)         ↛ Misconduct(a)
StrategicBehavior      ↛ Malice
Influence(a,D)         ↛ Authority(a,D)
Influence(a,D)         ↛ Causation(a,D)
Agreement              ↛ Authorization
Consensus              ↛ Truth
Pattern                ↛ Intent
Intention              ↛ Action
Outcome                ↛ Intention
```

The same discipline applies to a statistical chain that is easy to accidentally collapse into one step:

```text
Observed pattern ≠ Statistical association ≠ Causal effect ≠ Mechanism ≠ Theoretical law
```

Treat each such distinction as a candidate invariant (`THEORY_OBJECT_CANDIDATE`, §19A) until a later formal validation phase (§43) confirms it — recording the distinction is a preservation act, not a proof. Search the corpus for further non-collapse relationships as they're encountered; do not construct an exhaustive list up front.

---

# 22. CONTRADICTIONS

Do not immediately "resolve" contradictions.

First record:

```text
C001

claim_a
claim_b
source_a
source_b
scope_a
scope_b
type
status
first_observed_at
resolved_at
resolution_type
resolution_source
```

Then determine whether the apparent contradiction is:

* true contradiction;
* scope difference;
* definition difference;
* temporal correction;
* refinement;
* different model;
* unresolved.

`first_observed_at`/`resolved_at`/`resolution_source` follow the same discipline as a Gap Record's resolution fields (§13, §16): they record *when it was first noticed*, *when* and *by what evidence* a contradiction's status changed, without deleting the original `claim_a`/`claim_b` record. `resolution_type` reuses the "then determine whether..." classification above (`true contradiction`/`scope difference`/`definition difference`/`temporal correction`/`refinement`/`different model`/`unresolved`) as a formal field rather than leaving it as prose only.

Historical contradiction must remain visible even after later resolution.

---

# 23. BRANCHES

If a derivation genuinely branches:

$$
F_A
\rightarrow
\begin{cases}
F_B\\
F_C
\end{cases}
$$

preserve both branches.

Do not choose one merely because it appears later.

A branch may represent:

* alternative model;
* competing hypothesis;
* research fork;
* implementation path;
* rejected approach;
* later convergent approach.

Every branch record contains at least:

```text
branch_id           # B-#### (§4A)
origin               # F_A, the file where the branch point occurs
branch_a
branch_b
status
opened_at
resolved_at
resolution
resolution_source
```

`resolution` records the outcome per §24 (combines/chooses/reconciles/rejects/abstracts) once a merge or retirement occurs; `opened_at`/`resolved_at`/`resolution_source` carry the same temporal-resolution discipline as a Contradiction (§22). `resolved_at` records when a branch converged (§24) or was retired (§28) — never when it was merely noticed.

---

# 24. MERGES

When two branches later converge:

$$
F_B\rightarrow F_D
$$

and

$$
F_C\rightarrow F_D
$$

record a MERGE.

But do not assume the merge means the two branches became identical.

Determine whether D:

* combines both;
* chooses one;
* reconciles both;
* rejects one;
* abstracts both.

---

# 25. INDEPENDENCE

Absence of an edge does NOT imply independence.

Use:

```text
NONE_FOUND
```

when a defined search found no relationship.

Use:

```text
INDEPENDENT
```

only when evidence supports genuine independence — typically an explicit `negative_evidence` Evidence Object (§6A), such as a file stating "this work is independent of the previous model," not merely the absence of a found relationship.

Use:

```text
UNCERTAIN
```

when the search/evidence is insufficient.

Use:

```text
NOT_SEARCHED
```

when the relevant relationship has not been examined.

---

# 26. TRACK-A / TRACK-B FIREWALL

Maintain strict provenance.

Every file must be classified as:

```text
TRACK-A
TRACK-B
MIXED
UNKNOWN
```

Track-B research must not silently redefine Track-A historical development.

Track-B may be used later for:

* comparison;
* falsification;
* validation;
* external challenge.

But it must not repair historical Track-A gaps by retroactively inserting later theory.

### Deterministic classification rule

Classification must not be a per-file judgment call. Apply, in order:

```text
IF file/path belongs to the registered Track-B corpus
    → TRACK-B

ELSE IF the file is explicitly marked Track-B
    → TRACK-B

ELSE IF the file explicitly combines Track-A and Track-B material
    → MIXED

ELSE IF the file is registered as admissible historical corpus
    → TRACK-A

ELSE
    → UNKNOWN
```

> ⛔ **THIS RULE IS NON-EXECUTABLE UNTIL THE TWO REGISTERS EXIST.** *(Pilot finding, batch 001: no Track-B corpus register and no admissible-historical-corpus register were found. Every one of the first ten files therefore fell through to `UNKNOWN`, and the firewall this section exists to enforce protected nothing.)*
>
> Both registers are **inputs the protocol consumes, not outputs it produces** — like the Reference Architecture (§0C). Before execution:
>
> ```text
> TRACK-B-CORPUS.txt        path/prefix list of the registered Track-B corpus
> TRACK-A-CORPUS.txt        path/prefix list of admissible historical corpus
> ```
>
> Until both exist, a reconstruction may still proceed — but it must record `track: UNKNOWN` honestly across the board and must **not** claim the Track-A/Track-B firewall is being enforced. An all-`UNKNOWN` batch is a valid, truthful result; a batch that guesses tracks from file *kind* ("this one looks experimental, so Track-B") is not, and is forbidden — that is inference presented as provenance (§6A).
>
> This is a `BLOCKING` prerequisite, tracked in §49.

### Citation does not change track

A Track-A document can **cite** Track-B material without itself becoming Track-B. Distinguish:

```text
source_origin
source_track
current_processing_track
```

`source_track` records where cited material originated; `current_processing_track` records how the citing file itself is classified. Conflating the two would let a single Track-B citation silently reclassify an entire Track-A file, defeating the firewall above.

---

# 27. ML POLICY

ML may assist:

* candidate edge discovery;
* similarity detection;
* clustering;
* prioritization;
* likely continuation detection;
* anomaly detection.

ML may NOT independently establish:

* historical relationship;
* theoretical continuity;
* mathematical validity;
* semantic identity;
* gap resolution.

Required workflow:

$$
\text{ML candidate}
\rightarrow
\text{complete-file reading}
\rightarrow
\text{evidence}
\rightarrow
\text{verification}
$$

Record when ML contributed to candidate generation.

---

# 28. NO SILENT REPAIR

This is a hard rule.

If the historical corpus contains:

$$
A\rightarrow ?\rightarrow C
$$

do NOT insert the mathematically correct missing step merely because it is obvious to an expert.

Record the gap.

The purpose is reconstruction, not retrospective rewriting.

Later, a separate synthesis/repair phase may propose:

```text
RECONSTRUCTION
vs
REPAIR
```

but the two must remain distinct.

---

# 29. HISTORICAL TRUTH VS CURRENT CORRECTNESS

For every important claim distinguish:

### Historical status

What the corpus actually said at that point.

### Logical/mathematical assessment

Whether the claim appears valid.

### Later status

Whether subsequent work corrected, rejected, refined, or validated it.

Do not rewrite an earlier file's historical meaning based on later knowledge.

### The corpus is evidence, not automatically truth

A source file asserting "this proves...", "therefore...", "the kernel is...", "PASS", "validated," "canonical," or "minimal" is making a claim *about itself* — not establishing KnowledgeOS truth merely by asserting it. Record such statements as:

```text
SOURCE_ASSERTED_CONCLUSION
```

then independently track, as separate fields (never collapsed into one): historical support (§0A), derivation completeness (§0A, §12), `claim_type` (§18), mathematical/statistical/logical/empirical status (§19A). "No new primitive required" in a source means *no new primitive was identified under that file's tested construction* — it does not mean no new primitive can ever be required; do not silently upgrade a source's local, bounded claim into an unbounded one.

### Worked example

If the corpus contains no proof that a transition

$$
A
\Rightarrow
B
$$

holds, the Gap Record for that transition records `historical_status: UNDERIVED_BY_CORPUS` (§13, §14). It must **not** automatically record a mathematical invalidity verdict such as `mathematically_invalid: true`. Those are different questions: the historical record states what the corpus does and does not establish; a later, separate mathematical validation phase (§43) may determine that \(A \Rightarrow B\) is invalid, or that it holds but the proof simply never appeared in the historical corpus.

---

# 30. GRAPH LAYERS

The reconstruction should eventually contain at least:

## Layer 1 — File Graph

$$
G_F
$$

Which files relate?

## Layer 2 — Verified Derivation Graph

$$
G_D
$$

Which concepts/propositions/assumptions/derivations depend on which others?

The Sequential Reconstruction Graph \(G_S\) (§0A) is the file-level edge schema (`historical_derivation_status`, `derivation_completeness`, `mathematical_dependencies`, etc.) whose evidence populates this layer; \(G_D\) itself operates over the concept-level objects (§17–§19) those edges cite.

## Layer 3 — Continuity Graph

Records whether transitions are:

* continuous;
* partial;
* broken;
* corrected;
* contradictory;
* uncertain.

## Layer 4 — Gap Graph

$$
G_G
$$

Records missing/underived/inconsistent elements.

## Layer 5 — Historical Theory View

A derived view showing how the theory evolved.

## Layer 6 — Implementation Theory View

A derived view of how the reconstructed theory could be represented and eventually programmed, once sufficiently established. Derived from Layers 1–5 only — see §30A for the required firewall, states, and record schema.

## Layer 7 — Candidate Theory View

$$
G_{CT}
$$

A provisional, retractable consolidation of Theory Objects into candidate theoretical concepts — see §19C for the schema, statuses, and the explicit prohibition on ever assigning `VALIDATED`/`CANONICAL` here. Distinct from Layer 5 (Historical Theory View, which never merges distinct historical objects) and from the later Validated/Canonical Theory, which this layer only hypothesizes toward.

Do not collapse these layers into one graph.

---

# 30A. IMPLEMENTATION RECONSTRUCTION LAYER

While the first five layers (§30) tell us what the theory became, the reconstruction should simultaneously maintain a living representation of how the reconstructed theory could be implemented — **strictly separated** from historical reconstruction.

### The firewall

For every implementation element, record its origin:

```text
Historical evidence
       ↓
Reconstructed concept
       ↓
Mathematical/logical validation
       ↓
Implementation specification
       ↓
Code candidate
```

Never the reverse:

```text
Code/design idea
      ↓
retroactively claim
      ↓
historical theory
```

Programming convenience must never silently change the historical reconstruction (§28, §29).

### Implementation status

Split into what this phase may assign and what is reserved for later phases (§0B, §43) — assigning a later-phase status now is scope leakage, not a harmless label.

**Usable during this phase:**

```text
NOT_IMPLEMENTABLE_YET
CONCEPT_IDENTIFIED
IMPLEMENTATION_RELEVANCE_IDENTIFIED
SPECIFICATION_CANDIDATE
IMPLEMENTATION_BLOCKED_BY_GAP
DEFERRED_TO_IMPLEMENTATION_PHASE
```

**Reserved for later implementation phases — never assigned now:**

```text
SPECIFICATION_READY
MATHEMATICALLY_VALIDATED
IMPLEMENTATION_DESIGN_READY
PROTOTYPE_READY
IMPLEMENTED
TESTED
```

The current phase records readiness signals; it does not perform the design/build/test work those reserved statuses represent.

`IMPLEMENTATION_BLOCKED_BY_GAP` applies whenever the underlying theory has an unresolved mathematical/statistical/logical gap (§13). This is a new observation about implementation readiness — it is never permission to invent the missing mathematics.

### Implementation Record schema

**Current phase.** For 3,000+ historical files, designing candidate domain/data models during reconstruction creates a second cognitive workload and another source of contamination between history and design. This phase records only a readiness signal for each Theory Object (§19A):

```text
theory_object_id
implementation_relevance
implementation_status
blocked_by_gap_ids
provenance
```

`implementation_relevance` must distinguish, at minimum:

```text
NONE
POSSIBLE
SIGNIFICANT
BLOCKED
```

`implementation_status` remains the finer-grained signal defined above, still restricted to the current-phase-safe subset.

**Reserved for the later implementation phase — do not populate now:**

```text
candidate_domain_model
candidate_data_model
candidate_algorithm
candidate_invariant
candidate_test
implementation_dependencies
```

### Worked example

```text
F012
  ↓
Definition: Dependency
  ↓
F018
  ↓
Proposition: dependency is transitive under condition C
  ↓
F027
  ↓
[G-00031]
  INCOMPLETE_PROOF
  ↓
F041
  ↓
proof supplied
  ↓
Reconstructed concept
  ↓
Implementation relevance identified
```

```text
Implementation Concept: DependencyGraph

source:
    F012, F018, F027, F041

mathematical_basis:
    Proposition P7

historical_derivation_status:
    DOCUMENTED

mathematical_validation_status:
    NOT_YET_ASSESSED

implementation_relevance:
    SIGNIFICANT

implementation_status:
    SPECIFICATION_CANDIDATE

blocked_by_gap_ids:
    []
```

Even though the derivation chain is historically documented, `mathematical_validation_status` stays `NOT_YET_ASSESSED` unless the corpus itself contains an explicit validation result (§0A). No `candidate_domain_model` is populated — that is reserved for the later implementation phase.

### Feedback loop

```text
                 ┌──────────────────────┐
                 │ Chronological Files  │
                 └──────────┬───────────┘
                            ↓
                    File Reconstruction
                            ↓
                     Sequential Graph
                            ↓
                    Derivation Graph
                       ↙          ↘
              Continuity          Gap
                 ↓                  ↓
        Mathematical/          Missing/
        Statistical Audit      Underived
                 ↘                  ↙
                  Theory State
                       ↓
             Implementation Relevance
                       ┊
                 ── LATER PHASE ──
                       ┊
                Implementation Design
```

The arrows are **one-way with provenance**.

> **During chronological reconstruction, the system SHOULD maintain a parallel Implementation Reconstruction Layer. Whenever a concept, definition, mathematical object, statistical procedure, logical rule, dependency relation, state transition, or algorithm becomes sufficiently established, its potential computational representation SHALL be recorded with explicit provenance to the historical evidence and validated derivation. Implementation design MUST NOT modify, repair, reinterpret, or retroactively complete the historical theory. If implementation is blocked by an unresolved mathematical, statistical, logical, or definitional gap, the implementation record SHALL reference the corresponding Gap Record.**

This allows history → theory → mathematics → implementation to be built progressively, in parallel, rather than waiting for the full corpus reconstruction to finish — while keeping the boundary between reconstruction and implementation explicit.

---

# 31. FILE-LEVEL EDGE DOES NOT EQUAL THEORY-LEVEL EDGE

A relationship:

$$
F_A\rightarrow F_B
$$

does not automatically mean:

$$
T_A\rightarrow T_B
$$

A file may contain multiple theories, branches, examples, or unrelated sections.

Therefore:

> File relationship is evidence for possible theory relationship.

The theory relationship must be established at the appropriate semantic level.

---

# 32. PROGRESSIVE RECONSTRUCTION

Do not attempt to find "the beginning of KnowledgeOS" first.

Start with the first file in chronological order and progress.

At each step:

$$
\text{current file}
\rightarrow
\text{understanding}
\rightarrow
\text{candidate predecessors/successors}
\rightarrow
\text{complete-file comparison}
\rightarrow
\text{verified edges}
\rightarrow
\text{continuity}
\rightarrow
\text{gaps}
$$

New evidence may cause earlier graph structures to be extended.

Do not rewrite previous evidence silently.

Use versioned updates.

---

# 33. REVISITING EARLIER FILES

The process must allow backward discovery.

Suppose F100 reveals that it depends on F037.

Even though F037 was processed earlier, update:

$$
F037\rightarrow F100
$$

and reassess any affected continuity/gap structures.

The graph is therefore dynamic during reconstruction.

Chronological reading does not mean chronological limitation of discovery.

### A theory thread is never "historically complete," only "currently reconstructed"

> **No theory thread may be declared complete while unresolved predecessor candidates remain within the searched corpus scope.**

Declaring `T1 → T2 → COMPLETE` prematurely, only to discover at `F2500` that a dependency was missing, is exactly the failure this rule prevents. A theory thread may be labeled:

```text
CURRENTLY_RECONSTRUCTED
```

but never:

```text
HISTORICALLY_COMPLETE
```

until the appropriate corpus search (§15) has actually been performed for every open predecessor candidate.

---

# 34. PATHS ARE DERIVED VIEWS

Do not make path detection the primary algorithm.

First build the verified graph:

$$
G_V
$$

Then derive:

* derivation paths;
* correction paths;
* research paths;
* branches;
* merges;
* episodes.

A path is a view over verified relationships.

---

# 35. QUALITY STATES

Every important conclusion should have one of:

```text
SOURCE-ASSERTED
EVIDENCE-SUPPORTED
DERIVED
VALIDATED
REFUTED
OPEN
UNCERTAIN
HYPOTHESIS
```

Never use one status to mean another.

Especially:

$$
\text{SOURCE-ASSERTED}
\neq
\text{PROVEN}
$$

and:

$$
\text{UNWITNESSED}
\neq
\text{FALSE}
$$

This section's `VALIDATED` is a general evidentiary quality state for any tracked conclusion (a definition, a proposition, a claim — §17–§18). It is **not** the same field as `mathematical_validation_status`/`statistical_validation_status` (§0A), which apply specifically to a derivation's mathematical/statistical correctness, or `RECONSTRUCTION_RECORD_VALIDATED` (§9A), which means a record passed its protocol integrity checks. Three different fields, three different questions — never interchange them.

---

# 36. BATCH PROCESSING

The corpus may be processed in approximately three large batches.

Target:

* Batch 1: ~1,000 files
* Batch 2: ~1,000 files
* Batch 3: remainder

These are operational targets only.

> **Batch size is dynamically determined by checkpoint integrity, processing time, context capacity, validation quality, and state consistency (§9A, §37). Approximately 1,000 files is an operational planning target, not a semantic or theoretical boundary.** Never treat a batch boundary as a theory boundary.

Evidence quality has priority over throughput.

For every batch report:

* files processed;
* complete dossiers;
* candidate edges;
* verified edges;
* continuity statuses;
* gaps discovered;
* gaps resolved;
* open gaps;
* contradictions;
* branches;
* merges;
* Track-A/B distribution;
* unresolved files;
* validation findings.

### Reproducibility requirements

Because the corpus spans thousands of files, likely across many sessions, the batch process must also define:

* persistent state (§9 stateful processing) that survives a session boundary;
* checkpointing after each file or small file group;
* a deterministic resume procedure that continues from the last checkpoint without reprocessing or skipping files;
* duplicate prevention — a `file_id` already marked `RECONSTRUCTION_RECORD_VALIDATED` (§9A) is never silently reprocessed into a second, conflicting record;
* versioning of registries and ledgers so a later correction is an appended version, not an overwrite (§5, §16);
* no loss of previously recorded findings, and no overwriting of historical findings without provenance (§5, §37).

---

# 36A. RECONSTRUCTION STATE & CHECKPOINT SCHEMA

The reproducibility requirements above (persistent state, checkpointing, resume) are meaningless without an actual state object to persist. This freezes it.

```text
RECONSTRUCTION-STATE.json

state_version
protocol_version
corpus_registry_hash
last_processed_file_id
last_processed_sequence
current_processing_file_id
current_processing_state

file_counts
edge_counts
theory_object_counts
architecture_object_counts
gap_counts
contradiction_counts

active_theory_threads          # e.g. [TH-002, TH-011, TH-037] — what §0E.2's targeted backward investigation compares against first
active_theory_objects
active_definitions
active_assumptions
active_architecture_objects
recent_relevant_files          # small working set, not the whole registry — §0E.2's immediate-context comparison

open_gaps
open_branches
open_derivations               # DI-#### with derivation_status ≠ COMPLETE, §12
open_contradictions
open_research_obligations      # RO-#### with status = OPEN, §0E.3

tier2_absences                 # {artifact: ABSENT_BY_CONTENT|NOT_SEARCHED|OUT_OF_WINDOW|UNCERTAIN} (§41)

last_checkpoint
checkpoint_hash

schema_versions
```

### Crash safety: in-flight vs. completed

`last_processed_file_id` only tells you the last file that *finished*. It cannot distinguish "nothing was happening" from "a file was half-processed when the session crashed." `current_processing_file_id` and `current_processing_state` close that gap:

```text
current_processing_state:
    STARTED
    IN_PROGRESS
    COMPLETED
    FAILED
```

On resume: if `current_processing_state` is `STARTED` or `IN_PROGRESS`, that file's partial work is discarded and it is reprocessed from Layer 1 (§9) — never resumed mid-layer, since a partial File Reconstruction Record is not a valid one (§9A). `COMPLETED` promotes the file to `last_processed_file_id` and clears the in-flight fields.

### Corpus/protocol/schema hashing

Because the reconstruction is historical and reproducibility is critical, every checkpoint records:

```text
corpus_manifest_hash
registry_hash
protocol_hash
schema_version
```

for example:

```text
Checkpoint C001
    corpus   = SHA...
    protocol = SHA...
    schemas  = SHA...
```

If the corpus (`docs/knowledgeos/list_of_files_to_read.log`, §3) or this protocol changes mid-reconstruction, a hash mismatch surfaces it immediately instead of silently reconstructing against a moving target.

---

# 37. BATCH VALIDATION

At the end of every batch perform a quality check.

Verify:

1. File IDs are unique.
2. No file has disappeared from the registry.
3. Original paths remain unchanged.
4. Timestamps remain unchanged.
5. Every verified edge points to existing files.
6. Every verified edge has evidence.
7. Every continuity assessment has a basis.
8. Every gap has a location/context.
9. No candidate edge has silently become verified.
10. No Track-B result has silently entered Track-A.
11. No historical statement has been rewritten as a later conclusion.
12. No "not connected" result has been converted into "independent."
13. No un-created Tier-2 artifact (§41) is recorded as `ABSENT_BY_CONTENT` without an actual search having been performed — the artifact-level form of check 12.
14. No `historical_sequence_date` was derived from a date event whose `applies_to` is `A_CITED_ARTIFACT` or `AN_EXTERNAL_EVENT` (§3).

### Batch and corpus completion criteria

§49 previously left "how much is enough" undefined. The answer is deliberately **not** a numeric threshold (e.g. "95% validated") — that invites treating an arbitrary cutoff as if it meant something about the theory. Instead, completion is defined as *process* completion:

```text
BATCH_PROCESSING_COMPLETE
```

when, for every file assigned to the batch:

1. it has reached a terminal processing state (§36A: `COMPLETED`, never left `STARTED`/`IN_PROGRESS`/`FAILED`);
2. every §9A gate has been evaluated (not necessarily passed cleanly — `UNDERSTANDING_UNCERTAIN` is a valid terminal outcome);
3. all required records exist (File Reconstruction Record, §9; Evidence Objects, §6A; Gap Records where applicable, §13);
4. all cross-references resolve (no edge, gap, or theory object points to a non-existent ID);
5. this section's checks above pass;
6. any unresolved findings are explicitly recorded as open, not silently dropped.

```text
CORPUS_PROCESSING_COMPLETE
```

when every corpus file (§3) has reached `BATCH_PROCESSING_COMPLETE` status.

**Neither status means `THEORY_COMPLETE`.** No such status exists in this protocol, and none should be introduced — a theory thread stays `CURRENTLY_RECONSTRUCTED`, never `HISTORICALLY_COMPLETE` (§33), regardless of how much of the corpus has been processed.

---

# 38. CORE DATA MODEL

The conceptual model should be approximately:

$$
\boxed{
\text{File}
\rightarrow
\text{Evidence}
\rightarrow
\text{Relationship}
\rightarrow
\text{Continuity}
\rightarrow
\text{Gap}
}
$$

Extended to reflect the architecture-duality (§0C) and evidence-object (§6A) additions, a file produces three parallel outputs before they converge:

```text
                    FILE
                     │
          ┌──────────┼──────────┐
          ↓          ↓          ↓
      Evidence   Architecture  Theory
       (§6A)       (§0C)      (§19A)
          │          │          │
          └──────────┼──────────┘
                     ↓
              Relationships (§7)
                     ↓
                Derivations (§0A)
                     ↓
               Continuity (§10–§11)
                     ↓
        ┌────────────┼────────────┐
        ↓            ↓            ↓
      Gaps    Contradictions   Branches
     (§13)         (§22)        (§23)
        └────────────┼────────────┘
                     ↓
             Reconstruction State
                     ↓
        ┌────────────┴────────────┐
        ↓                         ↓
Candidate Theory (§19C)   Implementation Readiness (§30A)
  provisional, retractable
        │                         │
        └────────────┬────────────┘
                     ↓
              ── LATER PHASES ──
                     │
        ┌────────────┼────────────┐
        ↓            ↓            ↓
  Mathematical   Theory Repair  Canonical
  Validation                     Theory
```

with semantic objects progressively discovered beneath the file layer:

$$
\boxed{
\text{File}
\rightarrow
\{\text{Definition, Assumption, Proposition, Derivation, Result, Decision}\}
}
$$

and:

$$
\boxed{
\text{Gap}
\rightarrow
\{\text{missing definition, premise, derivation, dependency, justification, scope, type, assumption}\}
}
$$

and, once a semantic object is sufficiently established, it becomes a Theory Object (§19A) which may in turn yield an Implementation Record (§30A):

$$
\boxed{
\{\text{Definition, Assumption, Proposition, Derivation, Result, Decision}\}
\rightarrow
\text{Theory Object}
\rightarrow
\text{Implementation Record}
}
$$

This last arrow is one-way (§30A): an Implementation Record never feeds back into or edits the Theory Object it was derived from.

Multiple historical Theory Objects may also, provisionally, consolidate into one Candidate Theory Object (§19C):

$$
\boxed{
\{\text{Theory Object}_1, \text{Theory Object}_2, \ldots\}
\rightarrow
\text{Candidate Theory Object}
\rightarrow
\text{Validated / Canonical Theory Object (later phase, §0B, §43)}
}
$$

The middle arrow is retractable (§19C); the historical Theory Objects on the left are never edited to make a consolidation true, and the right-hand arrow is never taken during this phase.

---

# 39. THE KEY RESEARCH QUESTION

For every apparent theoretical progression ask:

> **Is the theory actually continuous between these two files, or does the later file merely look like a continuation because it uses similar terminology?**

Then ask:

> **If it is not fully continuous, exactly what is missing?**

This second question is as important as discovering the relationship itself.

---

# 40. SUCCESS CRITERION

The first successful milestone is NOT:

> "We found a large graph."

It is:

> **We can explain, with evidence, how the corpus's theoretical ideas move from file to file, and we can explicitly identify where that movement is continuous, incomplete, corrected, contradictory, or underived.**

The final graph may be small or large.

Graph size is not the success metric.

Evidence-backed explanatory power is.

### Process-completeness indicators

The qualitative criterion above is correct and stays primary — it is not being replaced by a score. But a multi-thousand-file process also needs measurable indicators of whether *the process itself* is complete, tracked per batch (§36) and cumulatively:

```text
files_read / files_expected
files_validated / files_read
evidence_records / files_read
verified_edges / candidate_edges
gaps_open
gaps_resolved
contradictions_open
theory_objects_candidate
theory_objects_reconstructed
architecture_objects
unresolved_files
```

These measure process completeness, never theory quality or correctness — a thread with many open gaps is not a worse reconstruction than one with few, if the gaps are genuinely what the corpus contains.

---

# 41. REQUIRED OUTPUTS

The registries below are numerous; classifying them prevents treating a derived view as if it were a second source of truth.

### Frozen/Controlled Inputs (authored before execution, not derived from evidence)

```text
Chronological Corpus Registry   (§3–§5) — the file inventory itself
Reference Architecture          (§0C) — a hypothesis about KnowledgeOS, frozen for consistency (§45), never treated as historical truth
```

### First-class objects (source of truth — own identity and lifecycle, built up as evidence is processed)

```text
File            (§3–§5)
Evidence        (§6A)
Theory Object   (§19A) — owns Definition/Assumption/Proposition as child entities (§0D)
Gap             (§13)
Research Obligation (§0E.3)
```

Theory Thread (§19B) is deliberately **not** listed here as a DDD aggregate root. It is a stateful, first-class historical-reconstruction/semantic-organization object — it aggregates member Theory Objects, supporting files, gaps, and status across a lineage in the plain sense of the word — but formally declaring it a DDD Aggregate Root would impose ownership/consistency-boundary semantics on historical material before the evidence and domain model justify that decision. Revisit this classification only if a later phase's domain model actually requires it.

### Derived Read Models (computed views, rebuildable from the aggregates above)

```text
Continuity            (§10–§11)
Contradictions        (§22)
Branches              (§23)
Merges                (§24)
Historical Architecture (§0C) — evidence-derived, unlike its Reference Architecture counterpart above
Implementation        (§30A)
```

### Derived Analytics (candidate/exploratory, never authoritative)

```text
Candidate Edges          (§8)
Verified Edges           (§7)
Sequential Edges         (§0A)
Derivation Edges         (§9 Layer 3)
Candidate Theory Objects (§19C) — explicitly retractable, never VALIDATED/CANONICAL
Candidate Theory Edges / G_CT (§19C)
```

Create a dedicated reconstruction area:

```text
docs/knowledgeos/knowledgeos_theory_chronological_extraction/
```

Deliberately outside `docs/knowledgeos/chronological-read/`, which already holds the frozen P3A baseline (§1: `00-ROADMAP-VALIDATED.jsonl`, `02-FILES.jsonl`, `03-CONTRIBUTIONS.jsonl`, `11-OBJECT-INDEX.jsonl`, `audit-p3a/`, `ledger-p3a/`, `ledger-p3b/`, etc.) — this reconstruction's outputs must never be written into or mixed with that directory (§1's candidate-evidence-not-canonical-truth rule applies to that whole tree, not just individual files in it).

with:

The tree below is the **full catalogue of artifact names and their owning sections** — it is not a list of files that must all exist. Which of them are created in any given batch is governed by the tiers immediately after it; the `[1]`/`[2]`/`[3]` markers give each entry's tier.

```text
FILE-REGISTRY.jsonl          [1]
FILE-REGISTRY.md             [3] derived

CORPUS-INDEX.jsonl           [1] Phase 0 output, one row per file, see §8A
SOURCE-LOCAL-IDENTIFIERS.jsonl [1] every identifier classified, see §4A
THEORY-THREADS.jsonl         [2] TH-### registry, see §19B
RESEARCH-OBLIGATIONS.jsonl   [2] RO-### registry, see §0E.3
INTRA-FILE-REVISIONS.jsonl   [2] source self-revision, see §5A
P3A-COMPARISON.jsonl         [2] per-field P3A agreement/disagreement, see §1, §42

DOSSIERS/
    F0001.md
    F0002.md
    ...

FILE-RECONSTRUCTION.jsonl    [1] one File Reconstruction Record per file_id, see §9

EVIDENCE.jsonl               [1] E-#### registry, see §6A

CANDIDATE-EDGES.jsonl        [2]
VERIFIED-EDGES.jsonl         [1]
SEQUENTIAL-EDGES.jsonl       [3] G_S — derived, see §0A
DERIVATION-EDGES.jsonl       [3] derived derivation chains
DERIVATION-INSTANCES.jsonl   [2] DI-### registry, see §12

ARCHITECTURE-OBJECTS.jsonl   [2] AR-###/HA-### registry, see §0C
DEFINITIONS.jsonl            [2]
PROPOSITIONS.jsonl           [2]
ASSUMPTIONS.jsonl            [2]
THEORY-OBJECTS.jsonl         [1] T-### registry, see §19A
CANDIDATE-THEORY-OBJECTS.jsonl [2] CT-### registry, see §19C — never VALIDATED/CANONICAL
CANDIDATE-THEORY-EDGES.jsonl   [2] G_CT, candidate relationships, see §19C
CANDIDATE-THEORY-SNAPSHOTS/    [3] CT-S#### checkpoints, see §19C

CONTINUITY.jsonl             [3] derived
GAPS.jsonl                   [1] the Gap Ledger — one row per gap_id, see §13
CONTRADICTIONS.jsonl         [2]
BRANCHES.jsonl               [2]
MERGES.jsonl                 [2]

IMPLEMENTATION-READINESS.jsonl [2] implementation_relevance per concept, see §30A

RECONSTRUCTION-STATE.json    [1] see §36A
GRAPH-STATE.md               [3] derived
BATCH-REPORTS/               [1]

METHODOLOGY.md               [1]
```

### Artifact tiers — create what the corpus actually yields

> *(Pilot finding, batch 001: of ~22 artifacts above, **10 would have been empty** — not from a shallow pass, but because a ten-file window genuinely contains no branches, no merges and no candidate edges. Meanwhile the two most valuable records produced, `SOURCE-LOCAL-IDENTIFIERS` and `INTRA-FILE-REVISIONS`, were not on the list at all.)*

**Tier 1 — CORE. Always produced, every batch, no exceptions:**

```text
FILE-REGISTRY.jsonl            identity + chronology semantics (§3, §4)
CORPUS-INDEX.jsonl             Phase 0 (§8A)
FILE-RECONSTRUCTION.jsonl      one record per file (§9)
EVIDENCE.jsonl                 (§6A)
VERIFIED-EDGES.jsonl           (§7)
THEORY-OBJECTS.jsonl           (§19A)
GAPS.jsonl                     the Gap Ledger (§13)
SOURCE-LOCAL-IDENTIFIERS.jsonl (§4A) — every identifier classified protocol / source-local / external / ambiguous
RECONSTRUCTION-STATE.json      (§36A)
BATCH-REPORTS/                 (§36)
```

**Tier 2 — CONDITIONAL. Created when, and only when, the corpus yields the content:**

```text
CONTRADICTIONS.jsonl · INTRA-FILE-REVISIONS.jsonl · BRANCHES.jsonl · MERGES.jsonl
DERIVATION-INSTANCES.jsonl · THEORY-THREADS.jsonl · ARCHITECTURE-OBJECTS.jsonl
RESEARCH-OBLIGATIONS.jsonl · CANDIDATE-THEORY-OBJECTS.jsonl + CANDIDATE-THEORY-EDGES.jsonl
CANDIDATE-EDGES.jsonl · P3A-COMPARISON.jsonl · IMPLEMENTATION-READINESS.jsonl
DEFINITIONS.jsonl · PROPOSITIONS.jsonl · ASSUMPTIONS.jsonl
```

> **An absent Tier-2 artifact is a valid result, not an incomplete batch** — but only when the batch state says *which kind* of absence it is. An empty file asserting nothing and a missing file asserting nothing are the same claim; write the claim once, in the batch state, rather than creating empty files to look complete.
>
> ⛔ **This tiering relaxes no standard.** Nothing here permits skipping a *finding*; it permits skipping a *container* for findings that do not exist. If the corpus yields one branch, `BRANCHES.jsonl` becomes mandatory for that batch.

**Every un-created Tier-2 artifact carries an explicit absence reason.** These are four different claims about the world and must never collapse into "missing" — the same discipline §25 already applies to relationships:

```text
ABSENT_BY_CONTENT   searched for, genuinely not present in this window
NOT_SEARCHED        no search was performed — an admission, not a finding
OUT_OF_WINDOW       the phenomenon is expected but its material lies outside this batch (§14)
UNCERTAIN           searched, and the evidence cannot settle whether it is present
```

> ⛔ **`ABSENT_BY_CONTENT` is a positive claim and must be earned by an actual search.** Recording it without having looked is the artifact-tier equivalent of converting "not connected" into "independent" — the exact error §37's check 12 exists to catch. When in doubt the honest value is `NOT_SEARCHED`.

Record these in `RECONSTRUCTION-STATE.json` as `tier2_absences{}`, so a later batch can tell what was looked for from what was skipped.

**Tier 3 — DERIVED. Generated from Tiers 1–2, never hand-authored:**

```text
SEQUENTIAL-EDGES.jsonl (G_S) · DERIVATION-EDGES.jsonl · CONTINUITY.jsonl
GRAPH-STATE.md · FILE-REGISTRY.md · CANDIDATE-THEORY-SNAPSHOTS/
```

Hand-writing a Tier-3 artifact is a defect: it means the same fact now exists in two places that can disagree (§5's no-duplicate-authority rule).

Use JSONL or another machine-readable format for structured data.

Markdown should provide human-readable audit views.

The Gap Ledger (`GAPS.jsonl`) must carry at least: `gap_id`, `first_detected_at`, `source_file_id`, `target_file_id`, `gap_type`, `theory_thread`, `preceding_claim`, `missing_element`, `following_claim`, `search_scope`, `search_result`, `historical_status`, `mathematical_status`, `statistical_status`, `resolution_status`, `resolution_source`, `confidence`. This is what makes questions like "how many derivations are incomplete," "which theory threads have the most gaps," and "which gaps were eventually resolved" machine-answerable rather than anecdotal.

---

# 42. P3A COMPARISON LAYER

Do not merge P3A into the verified graph automatically.

Instead maintain comparison fields such as:

```text
p3a_candidate = true/false
p3a_relationship = ...
new_relationship = ...
agreement = ...
disagreement = ...
```

This will allow us later to measure:

$$
\text{P3A candidate recovery rate}
$$

against the independently reconstructed verified-edge set — deliberately not called "P3A recall." Classical recall, \(Recall = TP / (TP + FN)\), requires a known ground-truth universe of all true relationships, which this reconstruction does not have until its own verified-edge set is demonstrably stable and complete enough to serve as a reference. Until then, report this as a **provisional empirical recovery estimate**, and:

$$
\text{P3A false/misleading candidate rate}
$$

against the independently reconstructed graph.

This is valuable empirical evidence about the old methodology — reported without overstating what an incomplete reference set can actually support.

---

# 43. FINAL ARCHITECTURAL SEPARATION

Keep these concepts separate:

$$
\boxed{
\text{Historical Reconstruction}
}
$$

$$
\boxed{
\text{Mathematical Validation}
}
$$

$$
\boxed{
\text{Theory Repair}
}
$$

$$
\boxed{
\text{Theory Synthesis}
}
$$

$$
\boxed{
\text{Canonicalization}
}
$$

$$
\boxed{
\text{Implementation Design}
}
$$

Do not perform all six simultaneously. Implementation Design (§30A) is derived from, and must never modify, the other five.

The present phase is primarily:

$$
\boxed{\text{Historical Reconstruction + Continuity + Gap Discovery}}
$$

Mathematical validation may be performed when necessary to classify a transition, but do not silently turn validation into historical rewriting.

---

# 44. GOVERNING PRINCIPLE

Use this methodology throughout:

$$
\boxed{
\text{READ COMPLETELY}
\rightarrow
\text{UNDERSTAND}
\rightarrow
\text{CONNECT}
\rightarrow
\text{CHECK CONTINUITY}
\rightarrow
\text{CHECK DERIVATION}
\rightarrow
\text{RECORD GAPS}
\rightarrow
\text{VERIFY}
}
$$

Not:

$$
\text{SEARCH WORD}
\rightarrow
\text{MATCH}
\rightarrow
\text{ASSUME RELATIONSHIP}
$$

---

# 44A. EXECUTION-FREEZE VALIDATION — FOUR-FILE SIMULATION

> **Provenance note:** this section records a simulation *reported* against an earlier revision of this protocol, using four representative real KnowledgeOS files (governance/DDD design, product/architecture, mathematical kernel research, and experimental/Track-B computational evidence). It was not independently re-run against this revision as part of writing it — the corresponding files were not supplied for inspection here. This section documents the reported methodological findings; it is not this document's own verification.

The reported simulation found that the protocol's conceptual model correctly distinguishes:

```text
architecture              ≠ theory
file relationship         ≠ theory relationship
historical derivation     ≠ mathematical validity
experimental evidence     ≠ historical lineage
candidate theory          ≠ canonical theory
chronology                ≠ causality
Track-A                   ≠ Track-B
missing derivation        ≠ permission to invent one
absence of an edge        ≠ independence
ML candidate              ≠ verified historical relationship
```

> **The simulation, as reported, validates the protocol's information model and reconstruction procedure. It does not validate the KnowledgeOS theory itself.** No substantive theoretical conclusion from the four files is recorded here — only the methodological finding that the protocol's distinctions held up against real corpus material. If a substantive theoretical issue was surfaced by the simulation, it belongs in the corpus reconstruction itself (as a Gap Record, §13, or Research Obligation, §0E.3) once that file is actually processed under this protocol — not as a conclusion asserted here.

This finding is one input to the Gate sequence in §45; it is not, by itself, a substitute for Gates 4–5's pilot on this exact protocol revision.

---

# 45. FIRST EXECUTION TASK

Close the blockers in §49 through an explicit gate sequence — freeze, pilot, measure, then scale. §0E's behavior model and §9's recording model are complete; this section is about executing what already exists, not designing more of it (do not add further methodology at this point).

### Gate 0 — the diagnostic pilot comes FIRST

> *(Pilot finding, batch 001 — this gate exists because the sequence below was tried in its original order and did not work.)*

Gates 1–3 freeze the Reference Architecture, the schemas, and the state semantics. But **what should be frozen is not knowable until a small batch has actually been run**: a ten-file diagnostic pilot surfaced a non-chronological registry column (§3), eight namespace collisions (§4A), an unenforceable track firewall (§26), and a one-way architecture dependency (§0C) — four findings that change what Gates 1–3 must produce, and none of which were visible from reading the corpus index or the protocol.

Strictly obeying Gates 1→7 therefore freezes the wrong things, and Gate 1 cannot even start without human authoring, so a literal reading stalls indefinitely.

**Run a small diagnostic pilot (~10 files) BEFORE Gates 1–3.** Its output is not a reconstruction to keep — it is a **list of corrections to apply to the protocol and to the artifacts Gates 1–3 will freeze**. Record its findings, apply them, then proceed to Gate 1 with what was learned. The pilot's own reconstruction artifacts are explicitly disposable; only its findings are load-bearing.

A pilot that produces no protocol corrections has almost certainly not been executed honestly.

### Gate 1 — Freeze the Reference Architecture (closes §49 item 1)

1. Author the Reference Architecture's actual content (§0C) — not just component names, but for every `AR-###`: purpose, responsibility, boundary, inputs, outputs, owned concepts, non-responsibilities, source, confidence, version (§0C's Architecture Object schema).

### Gate 2 — Freeze the remaining machine-readable schemas (closes §49 item 2)

2. Freeze, at minimum: File Dossier, Candidate Edge, Continuity Record, Contradiction, Branch, Merge, Candidate Theory Object (§19C), Theory Thread (§19B), Research Obligation (§0E.3), and the Checkpoint schema (§36A) — alongside the schemas already frozen: File Reconstruction Record (§9), Evidence Object (§6A), Architecture Object (§0C), Verified Edge (§7), Sequential Edge/`G_S` (§0A), Definition (§17), Proposition (§18), Assumption (§19), Theory Object (§19A), Gap Record (§13), Implementation Record (§30A).

### Gate 3 — Freeze state/version semantics and import the registry (closes part of §49 item 4)

3. Freeze the append-only record-versioning contract (§5A) and the Reconstruction State/checkpoint schema (§36A), including `corpus_manifest_hash`/`protocol_hash`/`schema_version`.
4. Freeze/commit the P3A baseline (§1) and verify the commit is clean.
5. Import the canonical chronological corpus registry (`docs/knowledgeos/list_of_files_to_read.log`) as the initial File Registry (§3).
6. Preserve the existing `file_id` values from its first column as the immutable identifiers — do not create a new ID scheme (§4).
7. Extend the imported registry with the master File Registry fields (§5) as a derived layer.

### Gate 4 — Pilot Phase 0 on a small chronological sample

8. Run the Corpus Intelligence Pass (§8A) — Layer 1 only, chronological, complete-file — on a **small representative chronological window, not the full corpus and not a ~1,000-file batch** (§36's batch targets are for later scale, not this pilot). Test extraction quality, named-object detection, thread seeding, and architecture mapping; look explicitly for false positives and missed objects.

### Gate 5 — Pilot the full pipeline on the same sample

9. Process that same small sample through the full pipeline (§9), using the Corpus Index to seed candidate Theory Threads (§19B) and Candidate Theory Objects (§19C) — closing §49 items 7 and 8 empirically rather than by definition alone. Then inspect manually: does the resulting reconstruction actually look like the historical development, not merely a schema-shaped report? Validate that it captures file relationships, theoretical continuity, missing derivations, semantic drift, assumptions, contradictions, branches, and gaps.

### Gate 6 — Freeze the execution contract

10. Only after Gates 4–5 demonstrate the schema and pipeline work on the pilot sample, freeze the execution contract. Do not spend this phase writing thousands of dossiers if the schema itself has not been validated on the pilot.

### Gate 7 — Scale

11. Proceed batch by batch (§36), each followed by batch validation (§37) before the next begins, ending in a global corpus-level consistency check (§37's `CORPUS_PROCESSING_COMPLETE`) — never all at once.

---

# 46. FINAL REPORTING FORMAT

At the end of each execution session report:

## Completed

Exact work actually performed.

## Evidence

Files, records, computations, and tests supporting the work.

## Discovered

New relationships, continuity findings, gaps, contradictions, branches, etc.

## Unresolved

Items that remain uncertain or underived.

## Deferred

Items deliberately postponed.

## Quality

Validation results and known limitations.

## Next step

Only the immediate next action.

Never claim more than the evidence supports.

---

# 47. MASTER PRINCIPLE

The ultimate objective is not to produce a pretty graph.

It is to reconstruct:

$$
\boxed{
\text{what was proposed}
\rightarrow
\text{what was defined}
\rightarrow
\text{what was assumed}
\rightarrow
\text{what was derived}
\rightarrow
\text{what was tested}
\rightarrow
\text{what was corrected}
\rightarrow
\text{what remained unresolved}
}
$$

across the complete chronological corpus.

The system must preserve both:

$$
\boxed{\text{the theory}}
$$

and:

$$
\boxed{\text{the places where the theory fails to connect}}
$$

because the gaps, underived steps, contradictions, and corrections are themselves important historical and mathematical evidence.

Do not hide them.

Do not repair them silently.

Do not collapse them.

Record them as first-class research objects.

---

# 48. THE INVENTORY IS PROVENANCE, NOT THEORY

The chronological inventory itself is not theory. It is the registry defining the reconstruction corpus. Its file IDs and ordering are provenance metadata; relationships, derivations, continuity, and gaps must still be established from the contents of the referenced files.

`docs/knowledgeos/list_of_files_to_read.log` tells us what the corpus is and in what order to traverse it. It does not tell us what the theory means or how one file derives from another.

---

# 49. OPEN EXECUTION QUESTIONS (NOT YET RESOLVED)

The corpus-source ambiguity is resolved by §3–§5 and §45 above: the registry and its `file_id` column are already authoritative. The `G_S` edge schema (including the `historical_derivation_status`/`derivation_completeness`/`mathematical_validation_status`/`statistical_validation_status` split) and the Gap Record/Gap Ledger schema are now frozen by §0A and §13. The per-file method is now invariant and machine-checkable via §9's 30-step, 5-layer pipeline and File Reconstruction Record schema. Per-file completion is now gated explicitly by §9A, and batch reproducibility (checkpointing, resume, duplicate prevention, versioning) is now specified in §36. Mission, in-scope outputs, and out-of-scope downstream phases are now stated explicitly in §0B. **Transition trigger** is resolved by §10's "significant transition" criteria, and **Track-A/Track-B classification** is resolved by §26's deterministic rule plus the `source_origin`/`source_track`/`current_processing_track` distinction. Those items are resolved. The following remain genuine execution blockers and must be resolved before large-scale processing:

Each item below carries a classification — `BLOCKING` (must close before any Layer-2+ execution), `PILOT_REQUIRED` (schema exists, needs empirical validation on a small sample), `OPERATIONAL` (infrastructure/tooling decision, not a schema gap), or `DECISION_REQUIRED` (a human authorization call, not something this document can resolve by further editing). A blocker is marked resolved only when the repository actually contains the required artifact — never merely because this document asserts it does.

1. `[BLOCKING]` **Reference Architecture content freeze** — §0C requires a Reference Architecture and defines its shape (per-file alignment fields, `architecture_relationship` enum, `AR-###`/`HA-###` schemas, `REFERENCE_*` status fields), but its actual content — the specific components a file gets mapped against (e.g. Evidence/Dependency/Determination/Validation/Knowledge-Theory/Kernel/Implementation) — must be authored and frozen before Layer 2 (§9) can be executed consistently across files.
2. `[BLOCKING]` **Remaining machine-readable schemas** — freeze the still-unspecified minimum schemas (File Dossier, Candidate Edge, Continuity Record, Contradiction, Branch, Merge — §41, §45 step 6) before large-scale execution.
3. `[READY]` ~~**Batch-level completion threshold**~~ — **resolved.** §37 now defines `BATCH_PROCESSING_COMPLETE`/`CORPUS_PROCESSING_COMPLETE` as process completion (every file terminal, every §9A gate evaluated, every reference resolved, §37's checks pass), deliberately not a numeric quality threshold, and explicitly distinct from a nonexistent `THEORY_COMPLETE`.
4. `[OPERATIONAL]` **Execution strategy across sessions** — §36A now defines the `RECONSTRUCTION-STATE.json` schema, checkpoint hashing, and crash-safe `current_processing_file_id`/`current_processing_state`; the remaining gap is purely operational — which store hosts this state, and which agent/session boundary owns writing it.
5. `[DECISION_REQUIRED]` **Governance freeze** — explicitly decide whether this reconstruction protocol is permitted to execute under the current repository methodology freeze.
6. `[DECISION_REQUIRED]` **Implementation-layer scope decision** — §30A now restricts this phase to recording only `implementation_relevance` (`NONE`/`POSSIBLE`/`SIGNIFICANT`/`BLOCKED`) plus the existing narrow `implementation_status` subset, explicitly deferring all candidate domain/data/algorithm modeling to a later phase. Confirm whether even that narrowed readiness-recording activity is in scope, since it independently touches the governance-freeze question in item 5.
7. `[PILOT_REQUIRED]` **Theory Thread strategy content freeze** — §19B now defines the Theory Thread schema and lifecycle (`DISCOVERY`/`IDENTITY`/`MERGE`/`SPLIT`/`RETIREMENT`), and §8A defines the Corpus Intelligence Pass that seeds thread discovery — but, like the Reference Architecture (item 1), the schema being defined is not the same as the strategy being exercised. Before large-scale execution, confirm the Phase 0 extraction fields (§8A) are sufficient to seed thread discovery in practice, on a small chronological batch, before relying on it across the full corpus.
8. `[PILOT_REQUIRED]` **Candidate Theory Registry pilot** — §19C's schema, statuses, and `G_CT` graph are now frozen, but the consolidation ratio is an empirical claim about this specific corpus, not something the schema guarantees. **Batch-001 measurement:** ten files yielded ~60 named objects, i.e. ~6/file. Linear extrapolation to 3,081 files ≈ **18,000 named objects** — roughly 36× the "500 objects → 120 candidates" figure this item previously carried. The registry must be designed for 10⁴, not 10². Whether consolidation actually compresses that, or leaves everything `CANDIDATE_CONTESTED`, is still unmeasured: batch 001 minted no `CT-###` because Layer 2 was blocked (item 1).

### Added by the batch-001 diagnostic pilot (§45 Gate 0)

9. `[BLOCKING]` **Registry chronology semantics** — the registry's timestamp column is filesystem mtime, not authorship date: all ten files in the first window share `Aug 5 15:44`, and registry order places five later documents before five earlier ones. Until `registry_timestamp_semantics` is determined and the typed `date_events[]` → `historical_sequence_date` derivation (§3) is in place, every chronological construct in the protocol (§0A's sequence, §0E.2's "preceding file", §9's `State(Fᵢ)` chain) is ordering by a field that does not carry order. **This is the most consequential single finding of the pilot.**
10. `[BLOCKING]` **Track-A/Track-B registers do not exist** — §26's deterministic rule consumes two registers (`TRACK-B-CORPUS`, `TRACK-A-CORPUS`) that were not found. All ten pilot files fell through to `UNKNOWN`; the firewall protected nothing. Reconstruction may proceed with honest all-`UNKNOWN` classification, but must not claim the firewall is enforced.
11. `[BLOCKING]` **Protocol namespaces collide with the corpus's own registers** — all eight of `F`/`AR`/`T`/`C`/`P`/`G`/`D`/`R` are already in live use inside the corpus with different meanings (§4A). The zero-padding convention now in §4A must be verified as sufficient against a wider sample before IDs are minted at scale; `C-1` alone carries three distinct corpus meanings in ten files.
12b. `[BLOCKING]` **P3A's `best_historical_date` is systematically unreliable and must not be inherited** — measured on batch 001: wrong for **3 of 10 files (30%)**, always by selecting the *minimum* explicit date, which for a synthesis document is a date it *cites* rather than the date it was written. The bias has a direction: it makes synthesizing documents look **older than their own sources**, which inverts derivation. On this window it would have placed the refuting document first of ten, ahead of the document it refutes and lists as an input. The deeper defect is **shape, not accuracy**: a scalar `best_historical_date` cannot express that a file contains an authorship date *and* a revision date *and* three cited dates. §3 therefore replaces it with typed `date_events[]` and a derived `historical_sequence_date` whose first rule is that only `applies_to: THIS_FILE` events may set position — the rule all three P3A errors violated. P3A stays frozen and is not repaired (§1); its `best_historical_date` is retained as `p3a_best_historical_date`, a **candidate** cross-reference for comparison (§42), never an input to chronology.
13. `[OPERATIONAL]` **Checkpoint hashing is specified but unimplemented** — §36A requires `corpus_manifest_hash`/`protocol_hash`/`schema_version`; no tooling computes them, so the pilot recorded `NOT_COMPUTED`. Not blocking for a single-session pilot; blocking for resumable multi-session execution.

---

This master protocol governs the entire File-Level Derivation and Theory Reconstruction phase.
