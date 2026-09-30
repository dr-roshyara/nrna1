We need to refine the next step.

I have reviewed the S-Series research-architecture material and want to use its **architectural strategy**, not copy its domain model.

The important principle is:

> **The corpus is research/brainstorming material. It is not assumed to be a coherent theory.**

Therefore, F-Series must serve a broader **KnowledgeOS Research Architecture** rather than becoming a self-contained theory-reconstruction pipeline.

## 1. STOP — do not implement F-Series v1.3 yet

Do not write v1.3 code.

Do not start F3082 extraction.

Do not continue broad corpus reading.

Do not modify the frozen v1.2 implementation.

First produce a design artifact called:

`KNOWLEDGEOS-RESEARCH-ARCHITECTURE-FOR-REVIEW.md`

This is an architecture/design document only.

---

# 2. Architectural principle

Use the S-Series architecture strategy as the pattern:

```text
CORPUS
  ↓
RESEARCH / RECONSTRUCTION
  ↓
EVIDENCE PACKAGE
  ↓
THEORY RECOVERY
  ↓
THEORY CONSTRUCTION
  ↓
RESEARCH / TESTING
  ↓
VALIDATION
  ↓
CANONICALIZATION
```

But explicitly adapt it to the KnowledgeOS corpus, which contains:

- brainstorming
- research notes
- mathematical ideas
- philosophical ideas
- architecture
- implementation discussions
- experiments
- failed approaches
- competing formulations
- terminology
- historical decisions
- abandoned ideas
- partial derivations
- speculative hypotheses

Do not assume that these artifacts collectively constitute one consistent theory.

---

# 3. Define the epistemic layers

The architecture must distinguish at least:

### A. Corpus

Immutable source material.

### B. Corpus Research & Reconstruction

Determine what the corpus actually contains.

Questions:

- What was said?
- When?
- In which artifact?
- By whom/what artifact?
- What definitions were given?
- What claims were made?
- What mathematical structures were proposed?
- What relationships were proposed?
- What derivations were presented?
- What experiments were performed?
- What failed?
- What contradictions exist?
- What ideas were abandoned?
- What remains unresolved?
- What theory-bearing material was present but previously overlooked?

This layer must not construct the final KnowledgeOS theory.

### C. Theory Discovery / Recovery

Determine what theoretical structures can be reconstructed from the corpus.

But preserve epistemic origin.

Use at least:

```text
[C] Corpus-derived
[R] Corpus-reconstructed
[S] Corpus-synthesized
[E] Expert-derived
```

Do not silently upgrade one category into another.

### D. Theory Research / Construction

Here expert reasoning is permitted.

Possible activities:

- mathematical formalization
- logical analysis
- statistical modelling
- conceptual synthesis
- competing model construction
- hypothesis generation
- experiment design
- counterexample search
- computational experiments
- falsification

Every newly introduced object must identify its origin.

### E. Validation

Attack candidate theories using:

- mathematics
- logic
- statistics
- empirical evidence
- computation
- experiments
- implementation tests where appropriate

### F. Canonicalization

Only after sufficient validation.

Do not allow a candidate theory to become canonical merely because it is coherent or elegant.

---

# 4. Create a hard epistemic firewall

The architecture must explicitly prevent:

```text
Candidate Theory
      ↓
Corpus interpretation
      ↓
Confirmation
```

Instead:

```text
Corpus
  ↓
Evidence / Reconstruction
  ↓
Theory Research
  ↓
Candidate Theory
```

If theory research discovers a possible missing corpus fact, the permitted path is:

```text
Theory Research
      ↓
Research Question / Gap
      ↓
Targeted Corpus Search
      ↓
New Evidence
      ↓
Reconstruction Update
      ↓
Theory Update
```

Phase 2/research must never silently rewrite historical reconstruction.

This feedback path must be an explicit architectural transition.

---

# 5. Treat the corpus as a discovery space

Do NOT define corpus processing as:

> "Find evidence supporting KnowledgeOS."

Instead define it as:

> "Characterize the knowledge-bearing content of the corpus without assuming in advance which ideas are theoretically important."

The extraction/reconstruction process should therefore surface:

- theory-bearing artifacts
- definitions
- claims
- assumptions
- mathematical notation
- mathematical structures
- logical arguments
- domain invariants
- mechanisms
- conceptual distinctions
- competing formulations
- historical replacements
- branches
- merges
- contradictions
- unresolved questions
- experiments
- negative results
- terminology collisions
- possible theoretical structures

The process must permit a previously ignored document to become important because of its actual content.

Do not classify theory-bearing importance solely from document kind, folder name, filename, or historical role.

---

# 6. Explicitly separate terminology namespaces

Add a mandatory rule:

> A corpus term and a newly constructed research term must not be treated as equivalent merely because they share a name.

If a Phase-2 researcher introduces a term that collides with an existing corpus term but means something different, the architecture requires:

- namespace qualification, or
- a new term, or
- an explicit semantic-equivalence argument.

Example:

```text
CORPUS:
"Structural Viewpoint"

RESEARCH:
"Structural Realization"

These are NOT automatically equivalent.
```

This must be treated as an ontology/DDD issue, not merely a documentation issue.

---

# 7. Define the Research Reconstruction Package

The handoff from corpus research to theory research should be a package, not a Candidate Theory.

Include at least:

```text
Corpus Registry
Evidence Objects
Source hashes
Chronology
Provenance
Reconstruction Records
Theory Objects
Theory Threads
Definitions
Assumptions
Claims
Derivations
Relationships
Contradictions
Branches
Merges
Gaps
Scope / Regime
Experiments
Negative Results
Terminology Map
Uncertainty
Epistemic Origin
Reconstruction Status
```

The package must preserve uncertainty.

It must not contain a hidden "final theory."

---

# 8. Define research feedback formally

Create an explicit transition:

```text
THEORY-RESEARCH-GAP
        ↓
TARGETED-CORPUS-RESEARCH
        ↓
NEW-EVIDENCE
        ↓
RECONSTRUCTION-REVISION
        ↓
THEORY-REVISION
```

For each feedback cycle record:

- triggering research question
- hypothesis/question that caused the search
- corpus scope
- search method
- files examined
- evidence found
- evidence not found
- reconstruction change
- theory change
- remaining uncertainty

This must be normal research behaviour, not an exceptional recovery path.

---

# 9. Define the role of F-Series

F-Series should be explicitly positioned as:

> **the controlled evidence and reconstruction mechanism inside the larger KnowledgeOS Research Architecture.**

It is NOT:

- the KnowledgeOS theory engine
- the candidate theory generator
- the final ontology
- the validation framework
- the canonicalization mechanism

F-Series provides trustworthy historical/research evidence and reconstruction artifacts to the broader research process.

Therefore the architecture should show:

```text
KnowledgeOS Research Architecture
          │
          ├── Corpus
          │
          ├── F-Series
          │     └── Evidence + Reconstruction
          │
          ├── Theory Discovery
          │
          ├── Theory Research
          │
          ├── Experiments
          │
          ├── Validation
          │
          └── Canonicalization
```

---

# 10. Map S-Series strategy without copying its implementation

Use the attached S-Series material only as architectural inspiration.

Do NOT:

- copy S-Series domain objects blindly
- copy its state machine
- assume its boundaries are identical
- assume its terminology is identical

Instead identify:

```text
S-Series architectural principle
        ↓
Why it exists
        ↓
Whether the same principle applies to KnowledgeOS
        ↓
KnowledgeOS-specific realization
```

Record any principle that does NOT transfer.

---

# 11. Research-oriented success criteria

The architecture is successful only if it allows us to answer:

1. What knowledge is actually present in the corpus?
2. What was explicitly developed?
3. What can be reconstructed from multiple artifacts?
4. What was only brainstorming?
5. What was later abandoned?
6. What competing theories existed?
7. What mathematical structures were proposed?
8. Which structures can be formally justified?
9. What does expert reasoning add?
10. Which additions are genuinely supported?
11. What remains uncertain?
12. What can be experimentally tested?
13. What was falsified?
14. What survives validation?
15. What, if anything, deserves canonical status?

---

# 12. Critical methodological rule

Do not optimize the architecture for producing a Candidate Theory quickly.

Optimize it for:

```text
discovery
+
provenance
+
epistemic separation
+
reproducibility
+
competing explanations
+
falsification
+
research feedback
```

The goal is not to prove that our current KnowledgeOS ideas are correct.

The goal is to determine **what the corpus contains, what can be reconstructed, what can be justified, and what remains an open research hypothesis.**

---

# 13. Deliverables

Create only the architecture document.

Then provide:

### A. Architecture
The complete proposed KnowledgeOS Research Architecture.

### B. Context map
Corpus → F-Series → Reconstruction → Theory Research → Validation → Canonicalization.

### C. Responsibility matrix
Who/what is allowed to do what at each stage.

### D. Epistemic-origin model
[C], [R], [S], [E], and any additional categories you believe are necessary.

### E. Firewall rules
Explicit forbidden information flows.

### F. Feedback protocol
How theory research can trigger targeted corpus re-examination without contaminating reconstruction.

### G. S-Series mapping
Which architectural principles transfer and which do not.

### H. Open design questions
Do not resolve them by assumption.

### I. Relationship to F-Series v1.3
Explain exactly which responsibilities belong to F-Series and which belong to the larger research architecture.

Do NOT implement anything.

Do NOT modify the F-Series code.

Do NOT extract F3082.

Do NOT resume broad corpus reading.

After producing this architecture, stop and wait for senior review.