# Review of erratum F-1 (R4 r3 → r3.1; independent subagent, read-only; Claude family)

**Verdict: PASS.**

## Findings
- **Diff:** 4 lines (the title, the Revision row, the Lem 3.7 conclusion, and ledger row 618). The proof is byte-identical.
- **Fidelity:** every part of the new conclusion follows from the proof:
  - h** ∈ H-D⁺′ (D-chg and D-st vacuous; the D-st⁺ clause vacuous because Cap = ∅; Ref unchanged);
  - h** ∈ H-B′ (no B clause mentions Cap);
  - h** ≈_{σ,f,Cap} h;
  - O′(h**) = O′(h) (by SC2);
  - all four image equalities. These follow without needing H-D⁺′ ⊆ H-D′.
- **Dependents:** K4b, the R-d row of the reading table, Rem 9.7, the proof of Prop 9.6, the D-st row and the scope note do not use the withdrawn ≈_{σ,f} clause. The erratum removes an internal inconsistency in r3.
- **No conclusion of R4 changes.**

## Optional items (not applied)
- (a) Make "h** ≈_{σ,f,Cap} h" explicit in the proof. Not applied: it would edit the proof text, and the step is immediate.
- (b) Align the ledger's SC1 gloss with the formal SC1. Not applied: the wording predates F-1 and is outside its scope.

## Status
**F-1 CLOSED.** R4 is sealed at r3.1. Same-family analyst and reviewers.
