# MD-065 §01 — Q1: F3 Semantic Type Reconstruction

Source: `docs/knowledgeos/research/kernel-reduction/*.md` (narrative, admitted MD-028-DQ-1) and MD-050's
own already-verified construction (reused by reference, not re-derived).

| Type | Definition | Status |
|---|---|---|
| **Atom** | An element of F3's own carrier/atom table (`03-capability-model.md`), the unit `Reach` closes over | **EVIDENCED** |
| **Observation** | An operator (`Observe`, one of C0's 13/14 named operators) applied to produce/consume atoms | **EVIDENCED** (named, typed as one operator among the set) |
| **Behaviour** | `Beh_𝔠(K):=Reach(Ops(K))` — MD-050's own research construction, hand-traced against F3's own derivation-rule table | **DERIVED** (a research construction, not itself corpus-native, but grounded entirely in corpus-native atom/carrier/rule data — MD-050's own classification, reused here unchanged) |
| **Reachable state** | The atom-closure `Reach(S)` computes from a starting atom set `S` under the operator set's own derivation rules | **EVIDENCED** (`Reach` itself is source-defined in `06-composition-rules.md`, per MD-051's own correction to MD-050) |
| **Operation** | One of C0's 13 named operators (`Observe,Relate,Infer,Qualify,Validate,Revise,Determine,Discriminate,...`) or C0_plus's 14th (`Qualify`, held back from base C0) | **EVIDENCED** |

**No `Requirement`, `Sat`, `EC_t`, or `Δ_t`-shaped object appears anywhere in this source base** —
confirmed by a direct, fresh grep this phase (`00`).
