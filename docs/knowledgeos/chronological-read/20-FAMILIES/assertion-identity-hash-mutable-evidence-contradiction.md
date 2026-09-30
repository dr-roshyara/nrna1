# assertion-identity-hash-mutable-evidence-contradiction

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** C-01, id=H(P,e,c,t,Π) · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0042, scope OBJECT): Assertion identity id=H(P,e,c,t,Π) is derived from evidence e, while e.state is simultaneously declared mutable. Executed: withdrawing an evidence item changes the assertion's computed id (before: 86a0330e2b65; after: a9d84e7a43d8), so every ℛ edge pointing at the old id dangles, violating StructuralValid's own no-dangling-edges clause via what the theory itself treats as a legal operation (C-01/G6, `12-EXECUTION-RESULTS` §E).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1737 §"| `c` Context | 🔴 **never typed** — scoping is asserted, the type is not given |"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1737. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type: true.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1737 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1737 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S1737]** types=[LIMITATION, DISTINCTION] scope=OBJECT — "Component audit of Assertion=(id,P,e,c,t,Π): two components have no type anywhere in the corpus — Context (c), the most-used untyped symbol in the theory, appearing in every major signature (Assertion, Assessment, Auth, E_q); and the evidence item's own identity (its `ref` field points at the referent, not at the evidence item itself)." (anchor: "| `c` Context | 🔴 **never typed** — scoping is asserted, the type is not given |")
- **[S1737]** types=[EXPERIMENTAL-RESULT, CONTRADICTION] scope=OBJECT — "Executed internal contradiction: the field table declares id=H(P,e,c,t,Π), derived from evidence e, while e.state is simultaneously declared mutable; withdrawing an evidence item re-keys the assertion's id (86a0330e2b65 → a9d84e7a43d8) and every ℛ edge into the old id dangles, a state StructuralValid itself rejects — introduced by adding mutable `state` to e after id was fixed to hash e (C-01)." (anchor: "id before withdrawal : 86a0330e2b65
  id after  withdrawal : a9d84e7a43d8
  SAME? False")
- **[S1737]** types=[RESTATEMENT] scope=THEORY-LEVEL — "Verdict: Assertion is NOT FORMALLY CLOSED — one internal contradiction (id over mutable e.state), two untyped components (Context, evidence-item identity), one contested and unresolved component (P), and uncertainty absent by construction; what survives strongly is Π intrinsic (the t=0 argument, independently re-affirmed by Step 265), bitemporal t, content-addressed identity as a concept, and the five-equality hierarchy history⊊structural⊊semantic." (anchor: "> **NOT FORMALLY CLOSED.**")

## Notes for P3
- Own observation: the CONTESTED flag is evidenced (see Lifecycle section); P3 should read the underlying contradiction/retraction rows before treating this label as settled either way.
- Own observation: completeness is thin — only semantics, experiments is PRESENT; most dimensions are NOT-EVIDENCED-IN-CAPTURE, consistent with a thin or narrowly-scoped source base rather than a claim that the object lacks these properties.
- Own observation: ungrouped in P2a — no co-occurrence or notation signal tied it to another label; may be a genuinely isolated object, or simply under-linked by the mechanical pass.
- Own observation: no rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) row was found for this label — its purpose/motivation, if any, is carried only in DEFINITION/FORMALIZATION-typed rows.
