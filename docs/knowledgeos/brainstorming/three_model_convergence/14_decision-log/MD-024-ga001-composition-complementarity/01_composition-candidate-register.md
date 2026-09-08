# Composition Candidate Register

## Aggregate/object side (A/C1/C2) — candidates carrying enough content to attempt composition against

| Candidate | Identity | Boundary | State | Invariants | Commands | Events | Lifecycle | Ownership |
|---|---|---|---|---|---|---|---|---|
| A's Kernel Candidate v0.2/v0.3 (8-field) | none stated | the 8 named fields | none stated | none stated | none stated | none stated | none stated | none stated |
| C1's P-3 (six-part aggregate) | implied, one instance per claim | Identity+EvidenceRefs+Justification+EpistemicState+Confidence+... | the 5–6 named fields | **one stated and tested**: "one confidence value per claim," shown to break under shared evidence (seq 0157) | none stated | none stated | none stated | none stated |
| C1's P-5 "K-1" | none stated | `KnowledgeAggregate`+`ConflictRecord`+`VerificationPort` | 3 named parts | none stated | `VerificationPort` implies a verification operation, unspecified | none stated | none stated | none stated |
| C2's `𝒞=(D,P,T,C,I,E,R,H,Θ)` | none stated per-instance | the 9 named components | the 9 components themselves | Shani's invariant (§M, "no regime may silently redefine a core semantic concept") — a meta-level guard, not a per-instance data invariant | none stated | `Θ` (transitions) named as a component but not specified as an operation | none stated | none stated |

**None of the four aggregate candidates specifies commands, events, or ownership** — consistent with
Stage 06's own GA-005 finding (representation/operational apparatus is a Model-B/C2-specific construct,
largely absent from A/C1's own aggregate work).

## Operator/process side (B) — candidates carrying enough content to attempt composition against

| Candidate | Identity | Input | Output | Preconditions | Postconditions | Side effects | Composition rules |
|---|---|---|---|---|---|---|---|
| C0's 13 operators (Observe/Interpret/Represent/Relate/Discriminate/Hypothesize/Infer/DetectGap/Challenge/Validate/Revise/Determine/Select) | operator names only | **not specified anywhere in Model B's own 151-file evidence base** (confirmed by raw-source check, `00`) | not specified | not specified | not specified | not specified (ablation-tested for removability, not for effect) | none stated within B's own evidence — the KR-SIM-tagged `ASSERT`/`LINK`/`REVISE`/`RETRACT`/`ISOLATE` apparatus is NOT B's own content |
| `Qualify` (the 14th operator, discovered missing) | name and role ("qualification is required and was silently smuggled") | not specified | not specified | not specified | not specified | not specified | none stated |
| The extended-kernel `RespondToEvidence`/`Maintain·ApplyEpistemicStandards` | names only | not specified | not specified | not specified | not specified | not specified | none stated (`PROPOSED → UNTESTED`, P-3 in B's own table) |

**Every operator candidate in Model B's own legitimate evidence base lacks a stated input/output type.**
The one place B's evidence comes closest to operational content is *what each operator's own ablation
test discovered about it* — its removability, and (for `Determine`/`Validate`) its role in the
Epistemic Standards apparatus (`S^epi(E,C,Q)→A`, B's own §C) — used below where genuinely available.

## What this means for the composition candidates selected in `02`

No pair can be tested at the full rigor §5/§6 of the authorization describes (a genuine
`input → operation → state transition/output → invariant preservation` chain), because the operator
side of every pair lacks a stated input type. Where any operational content is available (an operator's
known role from its own ablation-test discussion, or `S^epi`'s own stated signature), this study uses
it and reports `PARTIALLY TESTABLE`; where none is available, this study reports `NOT FORMALLY
SPECIFIED ENOUGH TO TEST` and does not invent a type to proceed further.
