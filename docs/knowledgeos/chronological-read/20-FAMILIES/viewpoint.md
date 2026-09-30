# viewpoint

**Scope(s):** THEORY-LEVEL · **Row count:** 6 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Semantic/Structural/Behavioral/Governance/Operational viewpoint · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G0032: links `viewpoint` with `governance-viewpoint-hypothesis` — explicit agent-stated uncertainty: 'governance-viewpoint-hypothesis' POSSIBLY relates to 'viewpoint' (batch B0001). Note: The specific hypothesis that GOVERNANCE constitutes a fifth architectural viewpoint alongside semantic/structural/behavioral/operational; kept distinct from the general 'viewpoint' label since it is itself an unresolved candidate addition to that set.
- G1089: links `viewpoint` with `bounded-context` — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0001, scope THEORY-LEVEL): A proposed four-or-five-way projection of the platform architecture (semantic/structural/behavioral/governance/operational), tested for whether viewpoints constitute bounded contexts (found: no, they are projections of one platform).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0001] §"The ownership table — headers read, not inferred"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0001] §"The ownership table — headers read, not inferred"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0006. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S0001 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0001 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0006 |
| assumptions | PRESENT | S0001 |
| semantics | PRESENT | S0001 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0006 |

## Rationale
- [S0001] (ANALYSIS/FORMALIZATION) A table maps each of five viewpoints (Semantic, Structural, Behavioral, Governance-hypothesis, Operational) to its carrying asset(s), its owner read verbatim from the asset's header (e.g. 'Owner: Decision Authority', 'owner: nab.raj.sharma'), and its ladder status (executable/CANDIDATE, DRAFT, operating).
- [S0001] (ANALYSIS) Running the reviewer's own test ('ownership signals bounded contexts') against the ownership table returns a verdict against viewpoints-as-contexts: every governed platform-side viewpoint asset resolves to the same owner, the Decision Authority (semantic-governed -> the space owner; structural -> DA twice; behavioral -> DA twice; governance -> DA; operational -> DP-bound capabilities under DA's catalog).

## Assumption register
| Statement | Stated | Source | Anchor |
|---|---|---|---|
| ownership is one of the strongest signals for bounded contexts in DDD | EXPLICIT | S0001 | Commission line |

## All rows (source_id order)
- `[S0001]` types=[ANALYSIS/FORMALIZATION] scope=CROSS-OBJECT — "A table maps each of five viewpoints (Semantic, Structural, Behavioral, Governance-hypothesis, Operational) to its carrying asset(s), its owner read verbatim from the asset's header (e.g. 'Owner: Decision Authority', 'owner: nab.raj.sharma'), and its ladder status (executable/CANDIDATE, DRAFT, operating)." (anchor: "The ownership table — headers read, not inferred")
- `[S0001]` types=[ANALYSIS] scope=THEORY-LEVEL — "Running the reviewer's own test ('ownership signals bounded contexts') against the ownership table returns a verdict against viewpoints-as-contexts: every governed platform-side viewpoint asset resolves to the same owner, the Decision Authority (semantic-governed -> the space owner; structural -> DA twice; behavioral -> DA twice; governance -> DA; operational -> DP-bound capabilities under DA's catalog)." (anchor: "Finding 1 — ownership does NOT cut along viewpoint lines")
- `[S0001]` types=[CORRECTION/DISTINCTION] scope=THEORY-LEVEL — "The original one-owner-implies-one-context inference is downgraded: a single Chief Architect may legitimately own three genuine bounded contexts, so shared ownership is evidence against separate bounded contexts but insufficient by itself to establish a single one. The weaker claim still carries the conclusion jointly with the language test (no viewpoint has its own ubiquitous language) and the ARB's 'complementary siblings' line: viewpoints are not bounded contexts and must never be teamed, owned, or governed separately." (anchor: "REV 2 (review 2026-08-03): the inference was TOO STRONG — ownership is evidence, not proof")
- `[S0001]` types=[RESTATEMENT] scope=THEORY-LEVEL — "Closing summary: asked precisely, every governed viewpoint asset is carried by a named artifact and all platform-side ones are owned by the same authority, so ownership refutes viewpoints-as-contexts and re-points at the space map as the platform's true ownership structure. Held open: the structural contest, the fifth-viewpoint framing, and the Knowledge-Architecture-Platform reading (n=1)." (anchor: "Closing — every governed viewpoint asset is carried by a named artifact, and all platform-side ones are owned by the SAME authority")
- `[S0006]` types=[HYPOTHESIS/CORRECTION] scope=THEORY-LEVEL — "The reviewer's hypothesis that Semantic(Ontology)->Structural(Meta-Model)->Behavioral(Decision Model)->Operational(Capability/Runtime) form a hierarchy of viewpoints (not importance) is tested. Complementary viewpoints already exist in canon (ARB, 2026-07-11): 'the Reference Architecture and this Decision Model are complementary siblings, not a hierarchy... Neither depends on the other; both depend on the Standards.' Correction 1: the ARB's recorded form is siblings-without-arrows, not a downward flow; viewpoints are projections of one platform and projections do not flow into each other. Correction 2: the mapping is not 1:1 — Behavioral = Decision Model (engineer decisions) plus the EEP (work), one viewpoint two artifacts; Structural is contested between Reference Architecture and the emerged Meta-Model, routed to D-8/U-MM-5. What survives: four concerns, all evidenced as distinct — meaning (ontology), element types (meta-model), decision resolution (model+EEP), enactment (capabilities, runtime, evidence)." (anchor: "The four-viewpoint hypothesis — tested, PARTIALLY SUPPORTED with two corrections")
- `[S0006]` types=[OPEN-QUESTION] scope=METHODOLOGICAL — "U-EDV-3: whether the viewpoint names (semantic/structural/behavioral/operational) enter the ubiquitous language, waiting on governance as a D-8 rider." (anchor: "U-EDV-3 | Whether the viewpoint names (semantic/structural/behavioral/operational) enter the ubiquitous language")

## Notes for P3
- No unusual internal tension observed across this label's 6 captured row(s); evidentiary base is proportionate to row count.
