# Formal Composition Tests

For each pair: domain, codomain, state space, admissible transformation, and (where the specification
permits) closure/invariant-preservation/well-definedness testing.

## Pair 1 — `Validate` (B) ↔ C1's P-3

**Domain**: unspecified (`01`). **Codomain**: unspecified. **State space**: P-3's own 6-field tuple
(Identity+EvidenceRefs+Justification+EpistemicState+Confidence+...), with one known transition
(confidence update under new evidence). **Admissible transformation attempted**: does `Validate`,
applied to a P-3 instance, produce a well-defined post-state that preserves P-3's own stated invariant?

**Result**: `Validate`'s own effect is never specified beyond its name and its survival under ablation
(B's own evidence never states what `Validate` checks or what it does when a check fails). **Closure**:
untestable — no output type stated. **Invariant preservation**: untestable for the same reason.
**Well-definedness**: fails by default — not every permitted input has a defined result, because no
result is defined for any input. **Classification: `PARTIALLY TESTABLE`** — the state space (P-3's own
6 fields, the one known invariant, and the specific failure mode seq 0157 discovered) is genuinely
available; the operator side supplies only a name and a survival record, not a transformation.

## Pair 2 — `S^epi(E,C,Q)→A` (B) ↔ A's Kernel Candidate v0.2/v0.3

**Domain**: B states this explicitly — `S^epi` takes `(E,C,Q)` (Evidence, Context, Query/Question) and
produces `A` (an Assessment, `⟨attitude,strength,warrant,status⟩`). **Codomain**: the 4-field
`Assessment` tuple, B's own most fully-specified output type in this whole evidence base.
**Composition attempted**: does A's 8-field tuple (Question/Hypothesis/Evidence/Claim/Argument/
Inference/Assessment/Provenance) supply inputs `S^epi` could consume? A's own tuple literally contains
fields named `Evidence` and `Question` (matching `S^epi`'s own `E` and `Q` inputs) plus a field
literally named `Assessment` (matching `S^epi`'s own output type name).

**This is the single strongest lexical/structural alignment found in this whole study** — not merely
shared vocabulary but a plausible input-output fit: `S^epi(A.Evidence, ?, A.Question) → A.Assessment`,
missing only a value for `C` (Context), which A's own tuple does not name as a separate field.
**Closure**: partially testable — if `C` could be supplied from elsewhere in A's own architecture
(e.g. the Sañjaya/Arjuna "state" layer, §I of A's register), the composition's output type (`A`) would
land inside A's own tuple's `Assessment` slot, a closed composition. **This study does not attempt to
supply `C`** — doing so would require deciding what "context" means for A's own tuple, which A's own
evidence does not state, and would cross into the reconstruction §9 forbids. **Invariant preservation**:
untestable — A's tuple states no invariants to check against. **Classification: `PARTIALLY TESTABLE`**,
the strongest of the 4 pairs, with the specific missing piece (`C`, the context argument) named exactly.

## Pair 3 — B's operator set ↔ C2's `𝒞`, via `Θ` (transitions)

**Domain/codomain**: C2's own `Θ` component is named as "transitions" but its own source (seq 2330)
does not specify `Θ`'s own type signature beyond the name. **Composition attempted**: could B's 13(+1)
operators populate `Θ`? Plausible in principle (an operator set is exactly the kind of thing that could
populate a "transitions" slot), but **neither side supplies enough specification to test this beyond
plausibility**. **Classification: `NOT FORMALLY SPECIFIED ENOUGH TO TEST`** — both sides are
under-specified here, not merely one.

## Pair 4 (adversarial) — B's contradiction-representation line (§J) ↔ C1's `ConflictRecord`

**Domain**: B's own tested apparatus is fully specified — flat representations (M3/M4, scalar/4th-value/
delegation/exclusive-construction) tested and rejected; structured evaluation (Standing + Boundary, a
4-component Reason/Provenance/Context/Condition structure) tested and **established as required**
(M0127, Kernel Verdict K2: "new semantic representation required, outside the kernel"). **Codomain**:
the Standing+Boundary structure itself is B's own output type. **Composition attempted**: does C1's
`ConflictRecord` field (a single, unstructured slot in P-5's own 3-field tuple) correspond to, or could
it be replaced by, B's own tested 4-component Standing+Boundary structure?

**Result**: **`TESTED — NO MAP FOUND`, the first genuine full test in this study.** C1's own
`ConflictRecord` is a single named field with no internal structure specified anywhere in its source
(seq 0165/0167); B's own Standing+Boundary is a specified, tested, 4-component structure. A "map"
would require deciding that `ConflictRecord`'s single field decomposes into B's 4 components — not
something either source states, and not attempted here (would be reconstruction). **What can be stated
without reconstruction**: B's own evidence directly, independently confirms C1's own implicit
assumption that a single flat conflict-tracking field is *inadequate* — this is exactly what M0127's
own "Kernel Verdict K2" result establishes (flat representations score 7/12, fail identically on the
same distinctions; structured evaluation scores 12/12). **This is a genuine, evidence-grounded finding,
not a map, but a corroboration of adequacy requirements**: whatever eventually replaces C1's own
single-field `ConflictRecord`, if the eventual system is required to preserve the same distinctions
B's own tested criterion checks for, it cannot remain a single flat field — B's own tested result
already rules that out, independent of C1's own evidence.
