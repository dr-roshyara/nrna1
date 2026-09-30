Your v1.2 architecture is substantially stronger than the earlier proposal. It now has explicit boundaries, a controlled handoff, feedback, state governance, terminology protection, provenance stages, and epistemic-status separation. The central design is sound.

My main recommendation is **not to reopen the architecture wholesale**. Instead, record a small number of architectural observations and create a v1.2 conformance backlog. The most important issues concern the meaning of “immutable,” the placement of validation, the semantics of “independent corroboration,” the maturity of the handoff, and the fact that several controls are named but not yet operationally specified.

The DDD concepts you use are appropriate: bounded contexts require context-specific language, while a Published Language and Anti-Corruption Layer provide an explicit translation boundary between models.  Your provenance distinction is also well-founded: provenance records where a claim came from, while verification determines whether it deserves acceptance; those are different properties. [learnixo](https://learnixo.io/courses/ddd/ddd-bounded-contexts)

# Overall assessment

| Dimension | Assessment | Comment |
|---|---|---|
| Context boundaries | Strong | Phase 1 and Phase 2 have distinct missions, language, invariants, and lifecycles |
| Handoff design | Very strong | Treating it as an ACL rather than a pipe is the right architectural move |
| Provenance | Strong conceptually | Needs a machine-checkable provenance schema and stage transition rules |
| AI-corpus suitability | Strong | ACL-1, ACL-3, ACL-4 directly address common AI synthesis failures |
| Theory/reconstruction separation | Strong | Phase 1B is correctly constrained as an index, not theory construction |
| State governance | Strong direction | RA-11, RA-12, and RA-16 are useful but need executable state semantics |
| Epistemic model | Strong but incomplete | Four statuses are valuable, but they are orthogonal dimensions rather than one maturity ladder |
| Validation placement | Defensible | Keeping validation in Phase 2 is coherent, but the architecture needs stronger independence controls |
| Change control | Strong | Addenda and explicit architecture-change proposals are appropriate |
| Operational completeness | Incomplete | Gates, schemas, ownership, and transition semantics need to be specified outside the architecture |

My conclusion: **v1.2 is architecturally coherent and should remain frozen, but it is not yet execution-complete.** That distinction is already present in the document; it now needs to be enforced through protocols and machine-readable artifacts.

# What is especially strong

## 1. The handoff is correctly treated as a translation boundary

The strongest part is §4:

> “The handoff is an Anti-Corruption Layer, not a pipe.”

This is exactly the right DDD interpretation. A Published Language should be an explicit, stable exchange contract, while the downstream context translates it into its own model rather than importing the upstream model directly. [learnixo](https://learnixo.io/courses/ddd/ddd-bounded-contexts)

Your four ACL rules are particularly useful:

- document kind must not determine theory relevance;
- Phase 2 categories must be found or justified before use;
- construction requires prior corpus search;
- Phase 1 facts retain their original scope.

These are not generic principles. They are concrete controls derived from observed failure modes.

## 2. Phase 1B is well bounded

The statement:

> “1B is an INDEX, never a theory.”

is exactly the right boundary.

It allows Phase 1 to detect:

- mathematical structures;
- invariants;
- candidate mechanisms;
- theory-bearing documents;
- competing formulations;
- unresolved questions.

But it prevents Phase 1 from silently promoting those discoveries into a theory.

The critical implementation requirement is that every Theory Discovery Index entry must carry a status such as:

```yaml
status:
  - discovered
  - requires_recovery
  - recovered
  - rejected
```

Otherwise the index will gradually become an ungoverned theory draft.

## 3. The correction path is correctly designed

This is sound:

```text
Phase 2 discovery
    ↓
Phase 1 correction request
    ↓
new reconstruction version
    ↓
Phase 2 incorporates it
```

The distinction between a correction request and a write is essential. Phase 2 may identify a suspected omission, but it cannot directly alter the historical record.

The same distinction should apply to Phase 1 itself:

```text
new interpretation ≠ correction of source history
```

A correction should be allowed only when:

- the source was misread;
- a source location was wrong;
- an artifact was misclassified;
- a lineage relationship was incorrectly recorded;
- a previous extraction omitted evidence that was actually present.

A new theory or reinterpretation belongs in a new reconstruction annotation, not as a rewrite of the historical artifact.

## 4. RA-15 is valuable, but it is not a single status field

The four statuses are excellent:

```text
SOURCE-SUPPORTED
RECONSTRUCTION-VALID
THEORY-CONSISTENT
INDEPENDENTLY CORROBORATED
```

However, these are not four sequential maturity states. They are four different epistemic dimensions.

For example:

```text
source_supported: true
reconstruction_valid: true
theory_consistent: false
independently_corroborated: false
```

A proposition can be faithfully reconstructed from the corpus but conflict with the emerging theory. That does not invalidate the reconstruction.

Likewise:

```text
source_supported: false
reconstruction_valid: false
theory_consistent: true
independently_corroborated: false
```

This could describe an expert-derived proposition that fits the theory but is not present in the corpus.

I recommend representing RA-15 as a vector:

```yaml
epistemic_profile:
  source_supported: true
  reconstruction_valid: true
  theory_consistent: true
  independently_corroborated: false
```

Do not encode it as:

```yaml
status: theory-consistent
```

That would reintroduce the collapse the architecture is trying to prevent.

## 5. RA-16 correctly distinguishes execution state from authority

This is an important addition:

```text
Research State answers: where are we?
Governance State answers: are we authorized and permitted to proceed?
```

Those are different questions.

The preflight mechanism should therefore produce a decision record such as:

```yaml
execution_preflight:
  unit_id: UNIT-0042
  architecture_version: 1.2
  protocol_version: P1-2.4
  state_version: STATE-2026-09-24T06:55:00Z
  governance_version: GOV-2026-09-24T06:52:00Z
  authorization: permitted
  restrictions: []
  pending_corrections:
    - CORR-00007
  required_gates:
    - P1-Q1
    - Q54
    - Q55
  result: proceed
```

Claude Code supports lifecycle hooks that can execute automatically at defined points, so your governance preflight can be enforced rather than merely described. [code.claude](https://code.claude.com/docs/en/hooks)

# Architectural issues to resolve outside v1.2

These are not necessarily reasons to change the frozen architecture. They are conformance and protocol obligations.

## 1. “Immutable” is used in three different senses

The architecture uses:

```text
immutable corpus
immutable upstream
append-only Phase 1 records
corrections
overlays
new reconstruction versions
```

These concepts need sharper separation.

I recommend:

| Object | Rule |
|---|---|
| Raw corpus | Immutable; never edited |
| Historical artifact record | Immutable after publication |
| Phase 1 evidence record | Append-only; corrections are new records |
| Reconstruction package | Versioned snapshot |
| Phase 1 interpretation | Append-only annotation |
| Phase 2 theory | Mutable by versioned change |
| Candidate Theory | Mutable by explicit change log |
| Canonical Theory | Governance-controlled |

Use this vocabulary:

```text
immutable = content cannot be changed
append-only = new records may be added, old records remain
versioned = a new snapshot may be published
superseded = old version remains readable but is no longer active
corrected = a new record explains why the prior record was insufficient
```

The phrase “Phase-1 records are append-only” is good, but “Phase-1 upstream, immutable” could be misread as forbidding correction records. State clearly that **the source record is immutable; the reconstruction layer is append-only and correctable by overlay**.

## 2. The Published Language is not yet fully specified

You identify the Reconstruction Package as the Published Language, but the architecture does not yet define its minimum contract.

Create a schema outside the architecture with:

```text
package_id
package_version
corpus_version
lineage_version
terminology_version
phase1_protocol_version
created_at
status
records
scope
exclusions
known_gaps
epistemic_profiles
query_interface
```

Each record should carry:

```yaml
record_id: CLM-00231
record_type: claim
source_refs:
  - F0032#section-4#paragraph-2
lineage_refs:
  - L-0007
scope:
  corpus_window: W-003
  temporal_range: "2026-09-01/2026-09-10"
explicitness: explicit
epistemic_profile:
  source_supported: true
  reconstruction_valid: true
  theory_consistent: unknown
  independently_corroborated: false
status: active
```

Without this, “Published Language” remains architectural intent rather than an executable contract.

## 3. The shared kernel is probably too small for implementation

The statement:

> “Only two things are shared: identifiers and provenance links. Nothing else.”

is excellent as a conceptual protection against model contamination. However, the handoff still needs shared structural conventions.

You may need to distinguish:

```text
Shared Kernel:
  identifiers
  provenance references
  version identifiers
  artifact type discriminators
  schema version
  scope identifiers
```

from:

```text
Phase-specific meaning:
  evidence
  claim
  theory object
  hypothesis
  candidate
  validation result
```

If only identifiers and provenance are shared literally, then the Published Language must still define neutral envelope fields. Otherwise each side may interpret the handoff inconsistently.

A safer formulation would be:

> The shared kernel contains only identity, versioning, provenance, scope, and envelope metadata. Domain meanings are translated at the boundary.

That preserves your intent without making interoperability impractical.

## 4. The “five layers” are not the same kind of thing

The architecture calls these “layers”:

```text
Corpus
Reconstruction
Theory recovery and construction
Validation
Canonicalization
```

But they mix different architectural categories:

- Corpus is an asset or source boundary.
- Reconstruction and theory construction are bounded-context activities.
- Validation is an activity owned by Phase 2.
- Canonicalization is a governance decision.

Your document explicitly acknowledges some of this, but the word “layer” still risks misleading implementation.

I recommend keeping the five-layer diagram for communication but adding a classification:

```text
Layer 1: source boundary
Layer 2: context activity
Layer 3: context activity
Layer 4: owned validation capability
Layer 5: governance transition
```

This avoids later treating Layer 5 as a service or Layer 4 as an independent context.

## 5. “Phase 1 closes when the window is read and validated” conflicts slightly with RA-13

Section 2 says Phase 1’s lifecycle:

> “closes when the window is read and validated.”

RA-13 says Phase 2 does not require Phase 1 completion and that Phase 1 is file-driven while Phase 2 is theory-driven.

These can coexist, but “closes” needs qualification.

Use:

```text
A Phase 1 work window closes when its assigned reconstruction obligations
and required gates are complete. Phase 1 as a research context remains open
while corpus coverage, targeted re-entry, or correction work remains.
```

Otherwise, the architecture could imply that Phase 1 is globally complete for a local window, while later targeted re-examination remains necessary.

## 6. “Independently corroborated” needs a stronger operational definition

RA-15 correctly warns that repetition is not corroboration. However, the current tests are necessary but not sufficient.

A genuinely independent source may still share:

- the same original experiment;
- the same dataset;
- the same prompt template;
- the same generated parent;
- the same institutional assumption;
- the same hidden source;
- the same model-generated misconception.

Define an independence profile:

```yaml
independence_profile:
  author_independence: unknown
  generation_independence: false
  lineage_independence: false
  data_independence: unknown
  method_independence: unknown
  temporal_independence: true
  conceptual_independence: false
  independence_decision: not_corrobated
```

Then define corroboration as a decision, not a similarity count:

```text
independently corroborated =
  sufficient independence across the dimensions relevant to the claim
  + compatible evidence
  + no shared-origin explanation that accounts for both observations
```

For AI-generated files, “same programme, same day” is only one warning signal. Prompt lineage and shared generated parents may be more important.

## 7. Phase 2 validation needs independence controls

You correctly keep validation inside Phase 2 to avoid creating a third bounded context. That is defensible.

But Phase 2 contains both:

```text
candidate theory construction
and
candidate theory attack
```

This creates a self-review risk. The same context or agent may generate and validate its own theory.

You do not need a third bounded context, but you do need separate **roles or execution modes**:

```text
construction agent
formalization agent
counterexample agent
empirical-evidence agent
implementation-test agent
readiness assessor
```

They should have:

- separate prompts;
- isolated contexts;
- separate output artifacts;
- no authority to silently revise the candidate they attack;
- an independent review pass before a readiness decision.

The architecture should therefore add a protocol-level invariant:

```text
A theory-construction execution unit may not be the sole validator
of the same theory element.
```

This is a control rule, not necessarily an architecture change.

# Specific review of the ACL rules

## ACL-1 is correct, but “says nothing” is too absolute

You write:

> “Document kind, artifact type and folder say nothing about theory-bearing content.”

As a guard against exclusion, this is good. Literally, however, metadata can provide useful prior information.

A more precise formulation:

> Document kind, artifact type, folder, and chronology may be used for routing, prioritization, or search planning, but may not be used as sufficient evidence for theory irrelevance.

That preserves the protection without discarding useful metadata.

## ACL-2 is strong

This rule is excellent:

> A Phase-2 category may not be applied to a Phase-1 artifact until the category is shown to exist in the corpus.

Add one exception:

```text
If the category is explicitly declared expert-derived [E],
it may be introduced, but it must be namespace-qualified,
recorded in the terminology registry, and prohibited from being
represented as corpus-derived.
```

Otherwise Phase 2 cannot introduce any genuinely new analytical category.

## ACL-3 is essential

The construction-before-search failure is common. Keep this rule.

Add a required search record:

```yaml
construct_search:
  term: "realization"
  corpus_queries:
    - "realization"
    - "represented"
    - "enforced"
  files_examined: 3081
  relevant_hits: 17
  collision_decisions:
    - "rename structural to represented"
  result: permitted_with_new_name
```

## ACL-4 is perhaps the most important

This rule protects scope:

> A Phase 1 fact consumed in Phase 2 carries its Phase 1 scope with it.

I would extend it to all boundary crossings:

```text
A transferred object carries identity, provenance, scope, time,
lineage, uncertainty, and epistemic profile.
```

Otherwise scope may survive while uncertainty or lineage is accidentally lost.

# Specific review of RA-9 and the realization layers

The renamed sequence is much better:

```text
SPECIFIED → REPRESENTED → ENFORCED → INVOKED → EFFECTIVE
```

However, it has two architectural risks.

## Risk 1: the arrows may not all be strict implications

You correctly note that the arrows represent an argued dependency. But even the sequence may not be universally linear.

For example:

- something can be invoked without being formally represented in the architecture if it is an external capability;
- something can be effective accidentally without being explicitly specified;
- something can be represented but not enforced;
- something can be enforced but never invoked;
- something can be invoked and produce an effect unrelated to the claimed mechanism.

I recommend modeling the realization states as a **partial-order or relation graph**, not automatically as a single chain:

```text
SPECIFIED ──supports──> REPRESENTED
REPRESENTED ──enables──> ENFORCED
ENFORCED ──permits──> INVOKED
INVOKED ──may produce──> EFFECTIVE
```

Then record exceptions explicitly.

The current architecture says these dependencies are a claim, which is good. The protocol must test them rather than assume them.

## Risk 2: “effective” is not purely a realization layer

`EFFECTIVE` introduces outcome evaluation. It depends on:

- an operational definition of effect;
- an observation design;
- a counterfactual or comparison;
- a measurement method;
- a causal interpretation.

Therefore `EFFECTIVE` belongs at the boundary between theory validation and implementation evaluation, not merely in a realization ladder.

That does not require changing v1.2, but the protocol should define:

```text
effective_for_what
measured_by_which_observation
compared_against_what_baseline
under_which_conditions
with_what_uncertainty
```

# Specific review of RA-10, RA-11, and RA-12

These are useful, but they need a clear distinction between **state** and **derived work queue**.

## RA-11

Good:

> Research State is authoritative for execution position.

Add:

```text
State records facts about completed, active, blocked, and pending work.
It does not itself define architectural rules or reinterpret protocol obligations.
```

You already say this in §8A; keep it as a machine-checkable constraint.

## RA-12

Good:

> Next work is recomputed from state.

But a static TODO list can still be useful as a non-authoritative projection. Instead of prohibiting it entirely, say:

```text
A TODO list may exist only as a generated view of state.
It may not be an independent source of truth.
```

This is more practical and prevents people from maintaining two conflicting work systems.

## RA-13

This is valuable:

> A blocked obligation blocks its thread, never the phase.

Add a dependency condition:

```text
A phase may proceed while a thread is blocked only if no active work unit
claims an output that depends on the blocked thread.
```

Otherwise a blocked foundational thread could be ignored while downstream artifacts incorrectly proceed.

# Specific review of RA-14 and the provenance chain

The chain is useful:

```text
Source Evidence
→ Reconstruction
→ Theory Object
→ Theory Thread
→ Recovered
→ Synthesized
→ Expert-Derived
→ Candidate Theory
→ Attack/Test
→ Validated
→ Canonical
```

But it currently combines:

- artifact transformations;
- epistemic transitions;
- workflow stages;
- governance transitions.

These should be represented as separate edges.

For example:

```yaml
edge:
  from: claim_004
  to: proposition_009
  relation: synthesizes
  actor: phase2
  operation: integration
  evidence_refs:
    - claim_004
    - claim_007
  status_effect:
    theory_consistent: true
```

Then:

```yaml
edge:
  from: proposition_009
  to: candidate_theory_003
  relation: included_in
  decision: accepted
  rationale: "..."
```

And:

```yaml
edge:
  from: candidate_theory_003
  to: validation_result_007
  relation: attacked_by
  attack_type: counterexample
```

This avoids treating “recovered” and “validated” as if they were the same kind of object.

# Specific review of the epistemic statuses

RA-15 is conceptually excellent, but four statuses are not enough for operational theory governance.

Keep the four architectural dimensions, and add protocol-level fields for:

```text
support strength
uncertainty
conflict state
scope
independence profile
validation result
decision status
```

Example:

```yaml
epistemic_profile:
  source_supported: true
  reconstruction_valid: true
  theory_consistent: true
  independently_corroborated: false

support:
  strength: medium
  source_count: 7
  independent_lineage_count: 1

conflict:
  status: contested
  conflicting_claims:
    - CLM-00481

scope:
  applies_to:
    - "window W-003"
  generality: local

validation:
  status: not_tested

decision:
  lifecycle: candidate
```

This is important because:

```text
7 source files
1 independent lineage
medium internal support
not externally corroborated
```

is very different from:

```text
2 independent experiments
2 independent lineages
externally corroborated
```

# Your open-item accounting is good, but the numbers need definitions

The document states:

```text
C1: 25 / 3,081 ≈ 0.8%
Q59: open
Q60: 11/11 passing today
```

This is useful operationally, but each metric needs a formal measurement definition.

For C1:

```yaml
coverage_metric:
  numerator: "files with completed and accepted reconstruction dossier"
  denominator: "files in corpus registry at snapshot time"
  exclusions:
    - "duplicate files?"
    - "unreadable files?"
    - "administrative files?"
  snapshot: "2026-09-23T..."
```

For Q60:

```yaml
completeness_metric:
  subject: "theory completeness obligations"
  numerator: 11
  denominator: 11
  scope: "current theory thread T-..."
  meaning: "all defined checks pass"
  does_not_mean:
    - "the theory is true"
    - "the corpus is complete"
    - "all corpus files are processed"
```

Otherwise readers may mistake Q60’s 11/11 for global theory completeness, despite the excellent warning that it is not equivalent to completion.

# Important omission: no explicit evidence for negative claims

The architecture says:

> “negative evidence must be earned.”

This is excellent, but it needs a rule.

Define three distinct statements:

```text
not found
not present
false
```

These are not equivalent.

For example:

```yaml
finding:
  statement: "No propagation was found."
  valid_interpretation: "No propagation was found in the searched scope."
  invalid_interpretation: "No propagation exists in the corpus."
```

A negative claim should carry:

```yaml
negative_evidence:
  search_scope
  search_method
  search_terms
  files_examined
  lineage_examined
  known_blind_spots
  stopping_rule
```

This is particularly important for AI-generated corpora because a term may be expressed through many paraphrases.

# Important omission: generated-file lineage is not explicit enough

The architecture already discusses scope and provenance, but because the 3,081 files are AI-generated, I recommend making **generation lineage** a named architectural concept.

Add it to the Published Language:

```text
generation lineage
```

Distinguish:

```text
source provenance:
  where the claim ultimately came from

generation lineage:
  which AI artifact produced or transformed this artifact

research lineage:
  which theory thread or decision process it belongs to
```

These are different graphs.

Example:

```text
Primary source ──source provenance──> claim
Prompt session ──generation lineage──> file
File ──research lineage──> theory thread
Claim ──theory relation──> proposition
```

Without these three distinctions, AI-derived repetition can be mistaken for corroboration.

# Recommended protocol backlog

I would not modify the frozen architecture immediately. Create a conformance backlog with these items:

| ID | Required artifact or control | Priority |
|---|---|---:|
| P1 | Reconstruction Package JSON/YAML schema | Critical |
| P2 | Provenance and generation-lineage schema | Critical |
| P3 | Epistemic-profile schema implementing RA-15 | Critical |
| P4 | Phase 1 correction/overlay protocol | Critical |
| P5 | Phase 2 re-entry request protocol | Critical |
| P6 | Independent-validation role separation | High |
| P7 | Theory Discovery Index schema and Q1 gate | High |
| P8 | Negative-evidence search record | High |
| P9 | Coverage metric definitions for C1/Q59/Q60 | High |
| P10 | Terminology collision decision record | High |
| P11 | Formalization-edge schema for RA-14 | Medium |
| P12 | Realization-layer evidence protocol | Medium |
| P13 | State-to-generated-work-queue mechanism | Medium |
| P14 | Canonicalization readiness decision record | Medium |

# Suggested machine-readable contracts

## Reconstruction Package

```yaml
package_id: RRP-0007
package_version: "1.2.0"
status: frozen_for_intake
architecture_version: "1.2"
phase1_protocol_version: "..."
corpus_snapshot: CORPUS-2026-09-23
lineage_snapshot: LIN-2026-09-23
terminology_snapshot: TERM-2026-09-23
scope:
  files:
    - F0001
    - F0010
  theory_threads:
    - TT-0003
records:
  - record_id: CLM-0032
    record_type: claim
known_gaps:
  - GAP-0018
excluded_material:
  - EXC-0004
epistemic_profiles:
  - EP-0032
```

## Re-entry request

```yaml
request_id: RE-0021
requesting_context: phase2
target_context: phase1
reason: missing_theory_context
target:
  term: "partial order"
  theory_thread: TT-0003
scope:
  search:
    - corpus
    - generation_lineage
    - superseded_artifacts
required_outputs:
  - definitions
  - source_locations
  - competing_interpretations
  - missing_assumptions
priority: high
status: submitted
```

## Theory element

```yaml
element_id: EL-0048
kind: proposition
statement: "..."
origin: corpus_synthesized
source_refs:
  - CLM-0032
  - CLM-0039
lineage_refs:
  - LIN-0004
epistemic_profile:
  source_supported: false
  reconstruction_valid: true
  theory_consistent: true
  independently_corroborated: false
scope:
  regime: R-02
status: candidate
validation:
  required:
    - counterexample_search
    - formal_consistency
```

# Recommended changes to the architecture text

I would not change the frozen version unless you discover a real contradiction. But if you later open v1.3, I would consider these precise changes.

## Change 1: clarify phase lifecycle

Replace:

```text
closes when the window is read and validated
```

with:

```text
A Phase-1 work window closes when its assigned reconstruction obligations
and gates are complete. Phase 1 remains globally open while coverage,
correction, or targeted re-entry obligations remain.
```

## Change 2: refine ACL-1

Replace:

```text
Document kind, artifact type and folder say nothing about theory-bearing content
```

with:

```text
Document kind, artifact type, folder, and chronology may support routing and
prioritization, but may never establish theory irrelevance.
```

## Change 3: clarify the shared kernel

Replace:

```text
Only two things are shared: identifiers and provenance links
```

with:

```text
Only identity, version, provenance, scope, and envelope metadata are shared.
Context-specific meanings are translated at the boundary.
```

## Change 4: clarify RA-15

Add:

```text
The four epistemic statuses are orthogonal dimensions, not a single maturity
ladder and not a substitute for support strength, uncertainty, scope, conflict,
or lifecycle state.
```

## Change 5: clarify RA-14

Add:

```text
The provenance chain contains distinct relation types. Artifact transformation,
epistemic assessment, workflow progression, and governance authorization must
not be represented as one undifferentiated status transition.
```

## Change 6: clarify validation independence

Add:

```text
A theory construction unit may not be the sole validator of the same theory
element. Validation execution must use an independent role, context, prompt,
or evidence path.
```

# Final verdict

Your architecture is **conceptually mature and internally coherent**. The most successful design decisions are:

- two bounded contexts rather than two loose phases;
- a Published Language and Anti-Corruption Layer;
- content-based Theory Discovery Index;
- explicit correction requests instead of historical writes;
- terminology collision control;
- state-derived work selection;
- explicit provenance traversal;
- four non-collapsed epistemic dimensions;
- governance preflight before execution;
- canonicalization outside both research contexts.

The architecture’s main remaining weakness is not conceptual. It is operational: several critical ideas still need schemas, state transitions, gate definitions, and independent execution roles.

I would therefore classify it as:

```text
Architecture status: conceptually frozen
Protocol status: partially specified
Control status: partly executable
Corpus compliance: low and explicitly open
Theory status: provisional
Canonicalization: not reachable yet
```

The next correct action is not another architecture redesign. It is:

```text
1. Freeze v1.2 as the governing architecture.
2. Create the Published Language schema.
3. Create the generation-lineage and provenance schemas.
4. Implement RA-15 as an epistemic vector.
5. Define Q1, Q54, Q55, Q59, and Q60 operationally.
6. Implement Phase 1 correction overlays and Phase 2 re-entry requests.
7. Run a conformance audit of both protocols.
8. Continue corpus processing only through the resulting gates.
```

This preserves the architectural work you have already done while converting the remaining ambiguity into explicit, testable protocol artifacts.