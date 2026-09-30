Your Phase 1 protocol is conceptually strong and, in several respects, more disciplined than the Phase 2 draft. It has excellent provenance, chronology, gap, contradiction, revision, and immutability controls.

However, it is currently **too broad for a single bounded context** and contains several architectural contradictions with your frozen v1.2 architecture. The most important issue is that it still contains **Phase 2 responsibilities inside Phase 1**, even though it repeatedly says they are out of scope.

My recommendation is:

> Keep Phase 1 as the authoritative reconstruction protocol, but narrow it to evidence, lineage, historical relationships, terminology, gaps, contradictions, and theory discovery indexing. Move candidate-theory consolidation, expert analysis, architecture design, and implementation readiness into Phase 2 or a separate governance/readiness service.

## Overall assessment

| Dimension | Assessment |
|---|---|
| Corpus immutability | Strong |
| File identity and versioning | Strong |
| Chronology discipline | Excellent |
| Source provenance | Strong |
| AI-generated corpus suitability | Strong, but generation lineage needs promotion |
| Gap modeling | Excellent |
| Contradiction modeling | Strong |
| Theory Discovery Index | Strong and correctly gated |
| Phase boundary discipline | Mixed |
| Bounded-context alignment | Mixed |
| Operational complexity | Low-to-medium suitability |
| Immediate execution readiness | Not yet |

The protocol has many good ideas, but it has grown by accretion. The result is a powerful research specification with duplicated concepts, multiple identity systems, and some responsibilities that should not belong to Phase 1.

# 1. What Phase 1 gets right

## 1.1 The primary unit is correct

The rule:

> “The atomic unit of this reconstruction is the complete file.”

is appropriate for your corpus.

Search hits, embeddings, filenames, isolated sentences, or prior labels can be discovery mechanisms, but they should not be treated as the reconstruction unit. This is particularly important for AI-generated files, where meaning can depend on:

- headers and status banners;
- self-corrections;
- caveats;
- appendices;
- examples;
- “not authoritative” declarations;
- references to earlier generated work.

The complete-file rule should remain.

## 1.2 The chronology model is excellent

Your distinction between:

```text
registry order
read order
historical order
theory order
```

is one of the strongest parts of the protocol.

The date-event model correctly avoids reducing a document containing several dates to one scalar:

```yaml
date_event:
  date_value:
  date_type:
  date_source:
  date_basis:
  applies_to:
  evidence:
```

The rule that a cited date cannot determine the file’s authorship order is also essential.

I would preserve:

```text
chronology determines traversal
evidence determines relationships
```

That is exactly the right invariant.

## 1.3 Append-only revision is well designed

The protocol correctly distinguishes:

1. reconstruction revision;
2. cross-file correction;
3. intra-file revision.

That is a high-quality distinction.

The `UNRECOVERABLE` value for a lost original claim is particularly important. Do not allow the AI to reconstruct an earlier wording merely because a later file says it was “softened” or “corrected.”

The following rule should remain binding:

```text
Unknown historical content must remain unknown.
Do not infer the missing original merely to satisfy a schema.
```

## 1.4 The Theory Discovery Index is correctly designed

`P1-Q1` is a good addition.

The record:

```yaml
candidate_theory_bearing:
signals_found:
why:
confidence:
quoted_signal:
```

correctly makes theory relevance content-based rather than document-kind-based.

One correction is necessary, however: `document_kind` should not be prohibited from the record entirely. It may be retained as metadata, but it must be prohibited as the **reason for theory exclusion**.

Use:

```yaml
document_kind: procedural
candidate_theory_bearing: true
relevance_decision_basis: content_signal
```

rather than deleting useful metadata.

A more precise rule is:

> Document kind may support routing and prioritization but may never establish theory irrelevance.

## 1.5 The distinction between absent and not searched is excellent

The protocol repeatedly distinguishes:

```text
NOT_SEARCHED
SEARCHED_AND_NOT_FOUND
ABSENT_BY_CONTENT
OUT_OF_WINDOW
UNCERTAIN
```

This is essential for corpus research.

The same distinction should be applied consistently to:

- definitions;
- relationships;
- lineage;
- theory objects;
- derivations;
- later resolution;
- independent corroboration.

This is one of the most valuable design principles in the whole system.

## 1.6 The gap taxonomy is mature

Your gap model is one of the strongest features:

```text
MISSING_DEFINITION
MISSING_PREMISE
UNDERIVED_STEP
UNJUSTIFIED_INFERENCE
MISSING_DEPENDENCY
SEMANTIC_DRIFT
SCOPE_SHIFT
TYPE_MISMATCH
ASSUMPTION_DROPPED
CONTRADICTION
UNRESOLVED_BRANCH
...
```

The distinction between:

```text
UNDERIVED_STEP
DERIVATION_CONTINUATION_MISSING
DERIVATION_START_UNRESOLVED
DERIVATION_END_UNRESOLVED
```

is useful and should remain.

The rule:

> “A gap must never be silently filled, repaired, or ignored.”

is exactly right for AI-assisted reconstruction.

## 1.7 The candidate graph and verified graph are properly separated

This is sound:

```text
candidate graph
    ↓ evidence retrieval and complete-file comparison
verified graph
```

And this clarification is important:

> Verified means evidence-verified, not mathematically validated.

That distinction must remain visible in all APIs and schemas.

## 1.8 The protocol correctly separates documentation quality from truth

The three levels are well conceived:

```text
Level 1: historical reconstruction
Level 2: reasoning documentation quality
Level 3: mathematical/statistical validation
```

The statement that Phase 1 may identify a source-internal inconsistency without performing full independent validation is reasonable.

However, this distinction must be reflected in field names. Do not use ambiguous labels such as:

```text
quality: weak
valid: false
supported: true
```

Use precise fields:

```yaml
documentation_assessment:
  derivation_completeness: incomplete
  internal_consistency: internally_inconsistent

validation_status:
  mathematical: not_yet_assessed
  statistical: not_applicable
```

# 2. Major architectural problems

## 2.1 The protocol defines too many bounded contexts

Section 0D lists:

```text
Historical Reconstruction Context
Theory Context
Architecture Context
Validation Context
Implementation Context
```

But your frozen architecture says there are:

```text
ONE architecture
TWO bounded contexts
```

This is a direct conflict.

Even though the protocol labels some contexts as later or out of scope, naming them as ownership contexts inside Phase 1 reintroduces the architecture that v1.2 explicitly rejected.

Replace §0D with a responsibility map:

| Capability | Phase 1 status |
|---|---|
| Historical reconstruction | Owned by Phase 1 |
| Theory-object discovery | Phase 1, limited to reconstruction/indexing |
| Architecture comparison | Phase 1 records source and reference-model relationships |
| Validation | Not owned by Phase 1; status may be recorded |
| Implementation readiness | Phase 1 may extract source-declared implications, but does not own readiness decisions |
| Canonicalization | Governance-only |

Use “capability” or “responsibility,” not “bounded context.”

The protocol must not define a Validation Context if the frozen architecture says validation belongs to Phase 2.

## 2.2 Phase 1 still performs theory construction

The largest issue is the inclusion of:

```text
Candidate Theory Registry (§19C)
Candidate Theory Objects
Candidate Theory Relationships
```

and the statement that these are in scope.

Your architecture says Phase 1:

```text
does not produce a Candidate Theory
```

A “provisional, retractable candidate theory” is still a Candidate Theory, regardless of the qualification.

This creates a direct contradiction:

```text
Phase 1 does not produce Candidate Theory
versus
Phase 1 produces Candidate Theory Registry
```

You need to choose one of two designs:

### Recommended design

Remove `Candidate Theory Registry` from Phase 1.

Replace it with:

```text
Theory Discovery Index
Theory Object Registry
Theory Thread Registry
Theory Relationship Candidates
Theory Consolidation Candidates
```

These records can say:

```text
“These objects may belong together.”
```

But they must not be called a candidate theory or presented as a coherent theory.

### Alternative design

Change the frozen architecture and explicitly allow Phase 1 to produce a preliminary Candidate Theory.

I do not recommend this. It weakens the boundary you have worked to establish.

The better rule is:

> Phase 1 may record candidate relationships and candidate groupings, but it may not assemble them into a Candidate Theory.

## 2.3 The “senior researcher” section leaks Phase 2 into Phase 1

This section says Phase 1 should:

- critically examine mathematical quality;
- identify better formulations;
- record expert-derived alternatives;
- use mathematics, statistics, ML, and computational experiments;
- investigate improvements and replacements.

Some of this is appropriate as **observation**, but the current wording is too broad.

The following are safe in Phase 1:

```text
The source contains an algebraic inconsistency.
The source does not define symbol X.
The source explicitly claims correction Y.
The corpus contains competing formulations.
The source’s own premises do not include a required assumption.
The corpus uses term X in two different ways.
```

The following belong in Phase 2:

```text
A better formulation is Z.
The correct mathematical structure is Q.
Theory A should be replaced by Theory B.
The expert-derived component should be added.
The statistical model should be changed.
The DDD model should be redesigned.
```

Phase 1 may record a **research observation**:

```yaml
observation:
  type: possible_reformulation
  statement: "A simpler formulation may explain the same evidence."
  origin: phase1_observation
  status: unassessed
```

But it should not create an `[E] Expert-Derived` theory element. `[E]` is a Phase 2 origin.

Use this boundary:

```text
Phase 1 may identify a candidate need for expert construction.
Phase 2 may perform expert construction.
```

## 2.4 Phase 1’s “critical analysis” needs two meanings

The protocol currently uses critical analysis in both senses:

1. source-internal reconstruction;
2. independent expert evaluation.

These must be separated.

Use:

```text
source-internal scrutiny
```

for Phase 1:

- missing premise;
- undefined symbol;
- explicit contradiction;
- scope mismatch;
- documented derivation gap;
- inconsistent use of a term.

Use:

```text
independent theory critique
```

for Phase 2:

- alternative mathematical derivation;
- counterexample;
- model replacement;
- formal validation;
- statistical critique beyond source-internal assessment.

The phrase “critical analysis” is too ambiguous for a governed protocol.

# 3. Architecture and reference-model concerns

## 3.1 The Reference Architecture is potentially dangerous

Section 0C requires a Reference Architecture to be frozen before large-scale execution.

This is risky because it can become an implicit ontology that biases every file reconstruction.

You correctly state:

```text
reference architecture is not historical truth
```

but the per-file process still requires:

```text
map file to reference architecture
identify architectural role
detect new or missing components
```

This can cause Claude to force files into the reference model.

I recommend changing the order and authority:

```text
1. Extract source-native architecture vocabulary.
2. Build or update Emergent Historical Architecture.
3. Compare against Reference Architecture, if one exists.
4. Record alignment, divergence, or unavailability.
```

The Reference Architecture should be:

```text
optional comparison lens
not a prerequisite for historical reconstruction
```

This is already partly recognized in your text, but the requirement that it be frozen before large-scale execution conflicts with the stronger principle that Phase 1 should not impose an architecture on the corpus.

A safer rule is:

> Historical extraction proceeds without a Reference Architecture. Reference comparison is an optional, separately scoped projection.

## 3.2 Architecture objects may be theory objects

You correctly observed that architecture objects can carry theory. But the protocol needs an explicit relation:

```text
architecture_object
    may_be_evidence_for
theory_object
```

Do not assume:

```text
architecture object = non-theoretical
```

and do not assume:

```text
architecture object = theory object
```

Represent the relationship explicitly:

```yaml
architecture_relation:
  architecture_object: HA-0007
  theory_object: T-0023
  relation: supports | instantiates | constrains | contrasts_with
  evidence:
    - E-0041
```

## 3.3 “Architecture Context” should become an index

If you keep the reference/emergent architecture comparison, call it:

```text
Architecture Reconstruction Index
```

rather than Architecture Context.

Its role is:

```text
record source-native architecture signals
record emergent components
compare with reference hypotheses
preserve conflicts
```

It should not own a separate domain model unless the frozen architecture is changed.

# 4. Problems in the object and ID model

## 4.1 The ID namespaces remain too collision-prone

The protocol recognizes collisions but still uses very short prefixes:

```text
F
E
T
D
P
G
R
C
A
```

This is risky even with zero-padding because the corpus itself uses forms such as:

```text
F-1
T1
D-1
G-1
C-1
```

The distinction between `F0001` and `F-1` is workable for machines but fragile for humans and natural-language model interpretation.

Use explicit namespaces:

```text
FILE-0001
EVID-0001
THEORY-0001
DEF-0001
PROP-0001
GAP-0001
CONTR-0001
REL-0001
ASSUMP-0001
EVENT-0001
```

You can preserve the existing corpus labels in:

```yaml
historical_names:
  - "T1"
```

This is safer than relying on formatting conventions.

If you cannot change the current protocol IDs, add a non-negotiable rendering rule:

```text
Every protocol identifier is rendered in backticks and includes its full namespace.
Historical labels are always represented in historical_names[].
```

## 4.2 `A` is overloaded

The protocol uses:

```text
A = Assumption
```

but also uses:

```text
A/B/C
```

for other conceptual distinctions in the corpus and likely as agent or phase labels.

Use:

```text
ASM-0001
```

for assumptions.

## 4.3 `D` is especially problematic

`D` is used for:

- definitions;
- corpus decision packages;
- derivation notation;
- possible document labels.

Use:

```text
DEF-0001
DER-0001
DEC-0001
```

Do not rely on zero-padding alone.

# 5. Problems in the five-layer pipeline

## 5.1 Layer 2 still risks ontology-first reconstruction

The fixed order is:

```text
source
→ purpose
→ architecture
→ semantic objects
→ theory objects
```

This is better than identifying theory objects immediately, but the word “architecture” is still too early if the Reference Architecture is frozen in advance.

Use:

```text
source evidence
→ source purpose
→ source-native concepts
→ source-native architecture signals
→ candidate identity assertions
→ theory-object records
→ relations
```

Only then compare against external/reference architecture.

## 5.2 Layer 5 is too broad

Layer 5 includes:

```text
update theory model
build candidate theory objects
build candidate theory relationships
update architecture registry
update implementation model
record provenance
validate dossier
```

This is too much state mutation after a single file.

A safer Phase 1 Layer 5 is:

```text
update append-only reconstruction records
update theory-object and theory-thread overlays
update discovery index
update gaps and contradictions
update provenance
validate dossier
```

Move these out:

```text
candidate theory consolidation
implementation model
theory-level architectural synthesis
```

Implementation readiness should be renamed:

```text
Implementation Evidence Extraction
```

That means:

```text
what the source claims about implementation
what implementation artifacts exist
what implementation dependencies are stated
```

It does not mean Phase 1 decides whether the theory is implementation-ready.

## 5.3 “Validate the file dossier” is potentially ambiguous

The gate name:

```text
RECONSTRUCTION_RECORD_VALIDATED
```

is good, but keep the exact distinction everywhere:

```text
schema/provenance validation
not
content truth validation
```

Avoid using `validated` without a qualifier.

# 6. Review of the chronological state model

The stateful chronological model is appropriate:

```text
State(F0)
→ read F1
→ State(F1)
→ read F2
→ ...
```

However, the protocol also permits backward and forward search. This creates a concurrency and revision issue.

When a later file causes a revision to an earlier record, the state is no longer a simple linear fold.

The actual model is:

```text
primary traversal:
F1 → F2 → F3 → ...
```

with append-only corrections:

```text
F3 → correction overlay for F1
```

Represent this explicitly as two structures:

```text
Traversal State
  current file position and completed windows

Reconstruction Knowledge State
  append-only records and revision overlays
```

Do not represent the whole research state as only `State(Fi)`.

Recommended:

```yaml
traversal_state:
  current_sequence: 42
  completed_files: [...]
  active_file: F0042

knowledge_state:
  registry_version: R-0017
  overlays_pending:
    - OV-0031
  active_threads:
    - TH-0004
```

This is important for resumability and for Claude Code execution.

# 7. Review of the graph model

## 7.1 Too many graphs

The protocol contains:

```text
file graph
candidate graph
verified graph
sequential graph
derivation graph
continuity graph
architecture graph
emergent historical architecture
```

These are useful analytical views, but too many independently maintained graphs will create inconsistency.

Use one canonical typed event/relationship ledger:

```yaml
relationship:
  id: REL-0001
  source:
  target:
  relation_type:
  evidence_refs:
  status:
  scope:
  provenance:
```

Then define graph views:

```text
G_F = view of file-to-file relations
G_C = candidate relationship view
G_V = verified relationship view
G_S = sequence-constrained view
G_D = derivation dependency view
G_A = architecture relation view
```

The ledger is authoritative; graphs are projections.

This is strongly recommended for your architecture style. Maintaining multiple independent graph stores will eventually produce contradictory edges.

## 7.2 “Verified edge” needs a verification authority

The protocol defines `VERIFIED` as evidence-supported, which is good. But who or what can promote a candidate edge?

Define:

```yaml
verification:
  method: complete_file_comparison
  verifier_role: phase1_reconstruction_agent
  evidence_refs:
  decision:
  confidence:
```

Also distinguish:

```text
machine-extracted explicit edge
human/agent reconstructed edge
source-declared edge
```

A source-declared relationship and an inferred relationship should not share the same verification class.

# 8. Review of the Phase 0 intelligence pass

Phase 0 is a good optimization, but it must be carefully framed.

## What is good

It:

- reads every file;
- extracts lightweight index fields;
- does not exclude files;
- does not decide relationships;
- reduces later search cost.

That is sound.

## What needs correction

You say Phase 0 reads the complete file, then extract only a small set of fields. That is not necessarily cheap. The cost is still close to full reading for long documents.

Call it:

```text
Corpus Indexing Pass
```

rather than “intelligence pass,” and state two modes:

```text
deterministic indexing:
  headings, references, IDs, quoted terms, dates, hashes

semantic indexing:
  definitions, claims, research questions, architecture terms
```

Semantic indexing may require Claude and should be versioned with model/prompt metadata.

For AI-generated files, add:

```text
generation lineage hints
parent references
prompt/session identifiers
repeated phrase signatures
source quotation signatures
```

These are at least as important as named objects.

## Add generation lineage

The current Phase 1 protocol handles source provenance but not sufficiently the generation lineage of AI-generated files.

Add to Phase 0 and every dossier:

```yaml
generation_lineage:
  parent_file_refs: []
  cited_file_refs: []
  derived_from_refs: []
  generation_session: unknown
  prompt_ref: unknown
  model_ref: unknown
  transformation_type:
    - original_generation
    - summary
    - critique
    - reformulation
    - synthesis
    - unknown
  independence_status:
    - independent_unknown
    - derived
    - likely_derived
    - independent_candidate
```

This is essential because provenance of the source and provenance of generation are different graphs.

# 9. Review of Phase 1’s handling of mathematical content

The protocol is correct that Phase 1 must understand mathematics enough to reconstruct continuity. It is also correct that this is not the same as formal validation.

The boundary should be expressed more sharply:

## Phase 1 may establish

```text
The symbol is undefined in the source.
The source changes the definition of X.
The proof omits a stated intermediate step.
The later file assumes a premise not found in the earlier reconstruction.
The same symbol refers to different objects.
The source’s own equations are internally inconsistent.
The source explicitly claims a derivation that is not documented.
```

## Phase 1 may not establish

```text
The theorem is mathematically false under all interpretations.
A replacement proof is correct.
A different model is superior.
A statistical method is invalid in general.
A candidate theory should be accepted.
```

Use separate labels:

```yaml
source_internal_observation:
  type: undefined_symbol
  status: observed_in_source

independent_validation:
  status: not_performed
```

This will prevent Claude from converting a local source-reading observation into a general mathematical verdict.

# 10. Review of the Reference Architecture requirement

This is the most important conceptual issue after the Phase 2 leakage.

The protocol says:

```text
The Reference Architecture must be frozen before execution.
```

But the entire project is trying to discover what KnowledgeOS is. A frozen reference architecture risks becoming a hidden prior.

I recommend one of these two options.

## Recommended option: reference model as a comparison artifact

Define:

```text
Reference Architecture:
a provisional comparison model, versioned and explicitly non-historical.
```

It may be frozen for reproducibility of a run, but it is not a prerequisite for Phase 1 execution.

Processing order:

```text
Phase 1 source reconstruction
    ↓
Emergent historical architecture
    ↓
optional comparison against reference model
```

The reference model can be frozen per run:

```yaml
reference_model:
  version: R-0.3
  frozen_for_run: true
  historical_truth: false
  execution_dependency: optional
```

## Do not use this

```text
reference architecture frozen before Phase 1
→ every file mapped against it
→ historical architecture reconstructed
```

That architecture-first sequence will bias the corpus reconstruction.

# 11. Review of the “Candidate Theory Registry” decision

This requires a clear correction.

The Phase 1 protocol currently says:

```text
Candidate Theory Registry — provisional, retractable consolidation
```

and later:

```text
candidate theory construction is in scope
```

This contradicts the frozen architecture and the Phase 2 protocol.

Replace with:

```text
Theory Discovery Registry
```

It may contain:

```yaml
discovery:
  id:
  candidate_statement:
  supporting_objects:
  candidate_relationships:
  competing_interpretations:
  origin: reconstruction_observation
  status: unintegrated
  phase2_action:
    - recover
    - compare
    - formalize
    - investigate
```

But do not call this a Candidate Theory.

The phrase “candidate theory” should be reserved for Phase 2.

# 12. Review of Theory Threads

Theory Threads are useful, but their semantics need to be constrained.

The protocol says threads are:

```text
Theory Objects, their identity, evolution, and threads
```

and Phase 2 says threads are provisional groupings.

That is acceptable if threads are treated as:

```text
research-index entities representing hypothesized historical continuity
```

not as actual theory modules.

Define:

```yaml
theory_thread:
  id:
  member_objects:
  membership_basis:
  confidence:
  status:
    - candidate
    - historically_supported
    - contested
    - split
    - merged
  may_be_used_for:
    - retrieval
    - grouping
    - targeted search
  may_not_be_used_for:
    - declaring theoretical identity
    - resolving competing theories
    - establishing correctness
```

This protects against thread membership becoming an unexamined ontology.

# 13. Review of implementation readiness

Phase 1 includes:

```text
Implementation Readiness Registry
Implementation Model
Implementation Readiness Checked
```

This is too strong for Phase 1.

The protocol can extract:

```text
implementation evidence
implementation claims
implementation dependencies
implementation constraints
implementation gaps
```

But “readiness” implies an evaluative judgment.

Rename:

```text
Implementation Evidence and Constraint Registry
```

Phase 2 may then use it to determine whether a candidate theory is computationally testable or whether a laboratory is needed.

# 14. Recommended Phase 1 artifact model

I recommend these as the authoritative Phase 1 outputs:

```text
01 Corpus Registry
02 File Reconstruction Records
03 Date/Event Registry
04 Evidence Registry
05 Generation Lineage Registry
06 Source-Native Terminology Registry
07 Definition Registry
08 Assumption Registry
09 Claim/Proposition Extraction Registry
10 Derivation Instance Registry
11 File Relationship Ledger
12 Candidate Relationship Ledger
13 Verified Relationship Ledger
14 Theory Object Registry
15 Theory Thread Registry
16 Theory Discovery Index
17 Gap Ledger
18 Contradiction Ledger
19 Branch/Merge Ledger
20 Scope/Regime Registry
21 Architecture Signal Registry
22 Implementation Evidence Registry
23 Reconstruction State
24 Coverage and Quality Reports
25 Phase 1 Handoff Package
```

Remove from Phase 1:

```text
Candidate Theory
Theory reconciliation
Expert-derived theory components
Mathematical validation
Statistical validation
Implementation readiness decisions
Canonicalization
```

# 15. Recommended reduced Phase 1 pipeline

The current five layers are useful, but I would simplify the execution model:

## Phase 1A: Corpus and lineage

```text
inventory
identity
hashes
date events
generation lineage
duplicate/derivative hints
```

## Phase 1B: Source reconstruction

```text
complete-file reading
purpose
definitions
claims
assumptions
derivations
self-declared status
source-native concepts
source-native architecture
```

## Phase 1C: Historical relations

```text
candidate edges
verified file relationships
theory-object candidates
theory threads
branches
merges
contradictions
continuity
scope
```

## Phase 1D: Gaps and discovery index

```text
gap records
research obligations
Theory Discovery Index
possible structures
possible invariants
possible mechanisms
unresolved questions
```

## Phase 1E: Reconstruction audit

```text
provenance completeness
coverage
schema validity
negative-evidence audit
independence/lineage audit
handoff package
```

This keeps Phase 1 inside its bounded context.

# 16. Recommended Phase 1 state machine

Use separate states for files, records, and handoff.

## File state

```text
UNREAD
READING
EXTRACTED
RECONSTRUCTED
AUDITED
COMPLETE
REOPEN_REQUESTED
SUPERSEDED
```

## Evidence record state

```text
EXTRACTED
SOURCE_VERIFIED
CONTEXTUALIZED
SUPERSEDED
CORRECTED
```

## Theory discovery state

```text
DISCOVERED
INDEXED
REQUIRES_PHASE2_RECOVERY
CONTESTED
REJECTED_AS_NON_THEORY
```

## Handoff state

```text
DRAFT
AUDIT_PENDING
READY_FOR_PHASE2
ACCEPTED_BY_PHASE2
REENTRY_REQUIRED
SUPERSEDED
```

Do not use `VALIDATED` for Phase 1 completion unless you write:

```text
RECONSTRUCTION_RECORD_VALIDATED
```

# 17. Specific required corrections

Before adopting the Phase 1 protocol, I would make these changes.

## Required correction 1: architecture version

The protocol still says:

```text
FROZEN v1.0
```

Your governing architecture is v1.2. Update this immediately and require exact version verification.

## Required correction 2: remove extra bounded contexts

Replace §0D’s five contexts with a responsibility map aligned to the frozen two-context architecture.

## Required correction 3: remove Candidate Theory from Phase 1

Replace:

```text
Candidate Theory Registry
Candidate Theory Objects
Candidate Theory Relationships
```

with:

```text
Theory Discovery Registry
Candidate Grouping Records
Candidate Relationship Records
```

## Required correction 4: narrow expert reasoning

Change Phase 1 `[E]` records to:

```text
expert-observation
possible-expert-requirement
candidate-for-phase2-construction
```

Do not create expert-derived theory components in Phase 1.

## Required correction 5: clarify validation language

Replace:

```text
mathematical validation
```

inside Phase 1 assessments with:

```text
source-internal mathematical scrutiny
```

unless the field explicitly says:

```text
validation_documented_in_corpus
```

## Required correction 6: remove implementation readiness decisions

Use:

```text
implementation evidence and constraints
```

rather than:

```text
implementation readiness
```

## Required correction 7: add AI generation lineage

Make generation lineage a first-class registry, separate from source provenance.

## Required correction 8: make the Reference Architecture optional for reconstruction

It may be frozen for comparison, but it must not block or bias source reconstruction.

## Required correction 9: unify relationship storage

Use one relationship ledger with graph views rather than maintaining many independent graph authorities.

## Required correction 10: split traversal state from knowledge state

A later file can update an earlier reconstruction record. The state model must represent this through append-only overlays.

# 18. Suggested corrected Phase 1 mission

Replace the current mission with:

> Phase 1 reconstructs the corpus as an immutable, provenance-preserving historical evidence model. It records what each artifact states, assumes, derives, changes, contradicts, references, and leaves unresolved; reconstructs source-supported chronology and relationships; preserves generation lineage; identifies theory-bearing material through a content-based Theory Discovery Index; and produces a versioned Research Reconstruction Package for Phase 2. Phase 1 does not construct, validate, reconcile, or canonicalize the Candidate Theory.

This is clearer and fully aligned with your v1.2 architecture.

# 19. Suggested corrected handoff

The Phase 1 handoff should contain:

```text
corpus registry
file dossiers
date-event registry
source provenance
generation lineage
definitions
assumptions
claims
derivation instances
candidate and verified relationships
theory objects
theory threads
theory discovery index
contradictions
gaps
branches and merges
scope/regime records
architecture signals
implementation evidence
coverage report
known exclusions
uncertainties
re-entry targets
```

It should explicitly not contain:

```text
candidate theory
expert-derived theory
validated proposition
resolved competition
canonical object
```

# Final verdict

Your Phase 1 protocol is **very strong as a reconstruction and provenance specification**, especially in:

- complete-file reading;
- date-event chronology;
- append-only revision;
- intra-file revision handling;
- gap taxonomy;
- source versus interpretation separation;
- candidate versus verified graph;
- Theory Discovery Index;
- negative evidence;
- scope discipline;
- source-internal versus independent validation distinction.

But it currently violates or risks violating the frozen architecture in five major ways:

1. It defines additional bounded contexts.
2. It produces a Candidate Theory Registry despite Phase 1 being prohibited from producing a Candidate Theory.
3. It permits expert-derived construction inside Phase 1.
4. It treats implementation readiness as a Phase 1 responsibility.
5. It risks allowing a frozen Reference Architecture to bias historical reconstruction.

My classification:

```text
Historical reconstruction quality: very high
Provenance discipline: very high
Chronology discipline: excellent
AI-generated corpus readiness: high after lineage addition
Bounded-context conformance: medium
Protocol complexity: high
Phase boundary integrity: medium
Ready for pilot: after targeted corrections
Ready for full 3,000-file execution: no
```

The correct next step is not to redesign the entire protocol. Create a **Phase 1 conformance revision** with these priority changes:

```text
1. Update architecture reference to v1.2.
2. Remove extra bounded contexts.
3. Remove Candidate Theory from Phase 1.
4. Replace it with Theory Discovery and Candidate Grouping records.
5. Add generation lineage.
6. Separate source-internal scrutiny from validation.
7. Rename implementation readiness to implementation evidence.
8. Make reference architecture comparison optional.
9. Consolidate graph storage into one relationship ledger.
10. Define the handoff package and re-entry protocol.
```

After those changes, Phase 1 and Phase 2 will have a much cleaner relationship:

```text
Phase 1:
  reconstructs the evidence world

Phase 2:
  reasons over that reconstructed world

Architecture:
  governs the boundary between them
```

This separation is especially important for AI-assisted research, where auditability requires preserving prompts, model involvement, validation procedures, limitations, and human oversight rather than treating generated outputs as self-authenticating evidence. [onlinelibrary.wiley](https://onlinelibrary.wiley.com/doi/full/10.1002/cesm.70080)