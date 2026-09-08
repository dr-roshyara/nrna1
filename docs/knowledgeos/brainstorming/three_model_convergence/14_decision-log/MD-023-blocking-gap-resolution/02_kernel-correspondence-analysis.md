# Kernel Correspondence Analysis

## §1. Selection criteria, pre-registered before any pair was chosen

Per the authorization's binding constraint, the deep-test subset is chosen for diagnostic value, not
convenience:

1. Highest formal-specification quality (prefer Level 3–4 candidates from `01`'s matrix).
2. Strongest existing correspondence evidence (any prior phase's own adjudication that came closest to
   a positive classification).
3. Greatest potential to resolve GA-001 (candidates central enough that a result changes the overall
   picture, not a peripheral pairing).
4. Greatest potential to falsify the aggregate-vs-operator-set distinction (deliberately including a
   pair that could break the categorical finding, not only pairs that confirm it).
5. Meaningful coverage across A/B/C1/C2.

## §2. The 4 selected pairs

| # | Pair | Why selected |
|---|---|---|
| 1 | B's C0 (13-operator, tested) ↔ C1's P-3 (six-part aggregate, tested) | The **only two candidates in the entire reconstruction that were both actually falsified by an executed test** — criterion 1 and 4: both fully specified enough for a real comparison, and this is the single most direct available test of whether "tested aggregate" and "tested operator-set" share any structural commonality in *why* they failed |
| 2 | B's C0 ↔ C1's P-5 "K-1" (`KnowledgeAggregate+ConflictRecord+VerificationPort`) | Criterion 2/3: the two candidates most repeatedly cited across this whole reconstruction's own history (P-5 is the object at the center of GA-006's naming-collision finding); the most "canonical-feeling" representative of each category |
| 3 | A's Kernel Candidate v0.2/v0.3 (8-field, A's most fully-specified candidate) ↔ C1's P-5 "K-1" | Criterion 5: tests whether two *same-form* (aggregate-style) candidates from *different* models (A, C1) correspond more closely than cross-categorical pairs — isolates whether the real divide is representation-style or source-population |
| 4 | C2's sole Kernel definition ↔ C1's P-5 "K-1" | Criterion 5 (coverage) + C1's own OQ-7 (`04_model-c_kernel-ddd/03`): C2's own source file explicitly asks whether its Kernel definition should be reconciled against "C1 kernel candidates" — the closest thing to a corpus-internal invitation to test this specific pair |

**Not selected, and why**: exhaustive pairing (e.g. every C1 candidate against every A candidate) —
would produce ~20+ additional pairs, nearly all involving Level 1–2 candidates with no operational
content to test, yielding `NOT FORMALLY SPECIFIED ENOUGH TO TEST` by inspection alone, adding volume
without diagnostic value. B's own 4 cardinality-8 minimal kernels are not individually testable here —
their specific operator memberships are `NOT IN REGISTER` (`01`), so no pair involving them individually
can proceed past `NOT FORMALLY SPECIFIED ENOUGH TO TEST` regardless of partner.

## §3. Pair 1 — B's C0 ↔ C1's P-3

**Domain**: C0 = a set of 13 named operators over an unspecified domain, tested by exclusion (removing
each operator and checking whether the remaining set can still perform the tested tasks). P-3 = a
6-field aggregate (Identity+EvidenceRefs+Justification+EpistemicState+Confidence+...), tested via a
many-to-many evidence-sharing stress test (seq 0157) checking whether the aggregate's own invariant
holds when one piece of evidence is shared across multiple claims.

**Codomain / mapping attempted**: is there a structure-preserving map from P-3's 6 fields to a subset
of C0's 13 operators, or vice versa? **No map exists to attempt** — the two are not commensurable
objects. C0's operators are *functions* (`Interpret: X → Y`-shaped); P-3's fields are *data slots*
(`Confidence: [0,1]`-shaped). A field is not a function; mapping one to the other would require an
intermediate step (e.g. "which operator produces or consumes this field?") that neither source
specifies.

**Result**: **NOT FORMALLY SPECIFIED ENOUGH TO TEST** as a direct map. However, a weaker, genuinely
informative comparison is possible: *what kind of failure did each test discover?* C0's test (M0035)
discovered a **missing operator** (`Qualify` was absent from the original 13, found via exclusion).
P-3's test (seq 0157) discovered an **invalid invariant** (the aggregate's implicit "one confidence
value per claim" assumption breaks under shared evidence). **These are different failure classes**: an
incompleteness failure (something is missing) vs. a soundness failure (something present is wrong).
This is itself a finding: **the two most rigorously tested Kernel candidates in this whole
reconstruction failed for structurally different reasons, consistent with — not contradicting — the
finding that operator-sets and aggregates are different kinds of object** (an incompleteness failure is
a category error for a data aggregate; a soundness failure is a category error for a pure operator set).

## §4. Pair 2 — B's C0 ↔ C1's P-5 ("K-1")

**Domain/codomain**: C0 (13 operators) vs. P-5 (3 named components: `KnowledgeAggregate`,
`ConflictRecord`, `VerificationPort`).

**Mapping attempted**: does any subset of C0's operators correspond to P-5's 3 components' own
behavior? `VerificationPort` (a DDD "port" — an interface for an external capability) is the only P-5
component with any operational flavor; it could plausibly correspond to something like C0's `Validate`
operator. But P-5's own source (seq 0165/0167) never specifies what `VerificationPort` actually
verifies, against what criterion, or how — there is no behavioral specification to compare against
`Validate`'s own (also informally specified) role.

**Result**: **NOT FORMALLY SPECIFIED ENOUGH TO TEST.** Neither side supplies enough operational detail
for even a partial mapping attempt beyond noting one superficially plausible naming affinity
(`VerificationPort` ~ `Validate`), which this study explicitly declines to promote past that — naming
affinity alone is exactly the trap this whole reconstruction's methodology exists to avoid.

## §5. Pair 3 — A's Kernel Candidate v0.2/v0.3 ↔ C1's P-5 ("K-1")

**Domain/codomain**: A's 8-field tuple (Question/Hypothesis/Evidence/Claim/Argument/Inference/
Assessment/Provenance) vs. P-5's 3-field tuple (`KnowledgeAggregate`/`ConflictRecord`/
`VerificationPort`).

**Mapping attempted**: both are aggregate-style, both name domain objects rather than operations — the
right question is whether A's 8 fields can be partitioned to correspond to P-5's 3. A's `Evidence` and
`Claim` fields plausibly compose into something like a `KnowledgeAggregate` (a claim bundled with its
supporting evidence); A's `Argument`/`Inference` fields have no clear P-5 counterpart at all;
`ConflictRecord` has no counterpart among A's 8 fields (A's own tuple has no explicit conflict-tracking
slot); `VerificationPort` again lacks enough behavioral specification on either side.

**Result**: **PARTIALLY TESTABLE.** Unlike Pairs 1–2, both sides here supply *named, comparably-grained*
components, so a genuine (if incomplete) structural comparison is possible: at most 2 of A's 8 fields
plausibly compose into 1 of P-5's 3; 1 of P-5's 3 (`ConflictRecord`) has no counterpart anywhere in A's
8; the remaining fields on both sides are unmatched. **No structure-preserving map exists** — this is
not "insufficient specification," it is a genuine partial-coverage result: **same representational
style (both aggregate/tuple), still no demonstrated correspondence.** This is the single most
informative negative result in this whole study: it shows that *sharing a representational category*
(both being aggregate-style) is not, by itself, sufficient for correspondence — confirming the
authorization's own warning that the aggregate/operator divide should not be assumed to be the *only*
axis of non-convergence.

## §6. Pair 4 — C2's Kernel definition ↔ C1's P-5 ("K-1")

**Domain/codomain**: C2's 9-component `𝒞=(D,P,T,C,I,E,R,H,Θ)` vs. P-5's 3 components.

**Mapping attempted**: C2's own prose Kernel definition ("owns identity, lifecycle and provenance of...
participants, content references, information histories, contexts, epistemic states, knowledge
attributions and transitions") maps reasonably cleanly onto its own 9-component `𝒞` (participants↔`D`,
content references↔`P`, information histories↔`H`, contexts↔`C`, epistemic states↔`E`,
transitions↔`Θ`) — i.e. C2's Kernel definition and C2's own state structure are *the same object under
two descriptions*, a genuinely internal, self-consistent correspondence (not scored as a cross-model
result, since both sides are C2's own content). Against P-5's 3 fields: `KnowledgeAggregate` could
plausibly correspond to a bundling of C2's `D`+`P`+`E`; `ConflictRecord` has no counterpart in `𝒞` at
all (C2's own structure has no explicit contradiction/conflict-tracking component — worth flagging as a
new observation, see below); `VerificationPort` again lacks operational specification on the C1 side to
compare against C2's own `E`-related epistemic-state apparatus.

**Result**: **PARTIALLY TESTABLE**, same conclusion shape as Pair 3 — no structure-preserving map
found, but for a *specific, locatable* reason (P-5's `ConflictRecord` has no counterpart in either A's
or C2's own structures) rather than blanket under-specification. **New observation, not previously
recorded**: `ConflictRecord`/contradiction-tracking is present in C1's own P-5 candidate and in Model
B's own evidence (the contradiction-representation research line, M0114–127) but **absent from both
A's and C2's own most-specified Kernel candidates** — a genuine, evidence-based asymmetry worth
carrying forward (see `08`).

## §7. Synthesis across all 4 pairs

No pair reached `TESTED — NO MAP FOUND` in the strong sense (a fully-specified attempt that
definitively fails while preserving comparability). Two pairs (1, 2) are `NOT FORMALLY SPECIFIED ENOUGH
TO TEST`. Two pairs (3, 4) are `PARTIALLY TESTABLE`, and both partial tests **found no correspondence**
even between same-representational-style candidates — the strongest available evidence that the
aggregate-vs-operator divide, while real (Pairs 1/2's own failure-class asymmetry corroborates it), is
**not the only source of non-convergence**: even within the aggregate/tuple category, no two
independently-produced candidates (A vs. C1, C1 vs. C2) share a demonstrated structure. This directly
answers the authorization's own §5.3 question ("is the difference genuinely ontological or merely
representational?") with a precise, evidence-grounded answer: **both** — there is a genuine
representational-category divide (operator-set vs. aggregate, corroborated by Pairs 1/2's failure-mode
asymmetry) **and**, independently, no convergence exists even within the aggregate category itself
(Pairs 3/4).
