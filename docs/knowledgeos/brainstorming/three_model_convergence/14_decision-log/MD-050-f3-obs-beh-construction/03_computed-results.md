# MD-050 §3 — Computed Results

## Atom-pool cross-check (the robust argument)

`atom_pool(C0)` = union of all 13 C0 operators' atoms = `{A_WORLD_CONTACT, A_MEANING, A_ENCODING,
A_LINKING, A_DIFF_DECISION, A_GENERATION, A_ENTAILMENT, A_NORM_COMPARISON, A_NEGATION, A_WARRANT,
A_MUTATION, A_CLOSURE, A_ACTION_PREFERENCE}` — **13 of the 14 productive atoms; the sole absentee is
`A_QUALIFICATION`.** Directly verified: no C0 operator's declared atom set contains
`A_QUALIFICATION` (checked against every one of the 13 entries in `01_...md`'s table).

**Consequence, provable without fixpoint iteration**: `DERIVATION_RULES` rule `({OBSERVATION,POLICY},
{A_QUALIFICATION}) → EVIDENCE` can never fire in C0 — its required atom is absent from C0's entire
pool. And `VERDICT` requires `EVIDENCE` as an input kind in both rules that produce it (`{CLAIM,
EVIDENCE}` and `{HYPOTHESIS,EVIDENCE}`, each `+{A_WARRANT}`) — since `EVIDENCE` can never be produced,
`VERDICT` can never be produced either, regardless of how many rounds the fixpoint runs.

**`Beh_𝔠(C0) = Reach(C0) = all 23 carrier kinds EXCEPT `{EVIDENCE, VERDICT}` = 21 kinds.**

## `C0_PLUS` (C0 + Qualify)

`atom_pool(C0_PLUS)` = all 14 productive atoms (C0's 13 plus `Qualify`'s own `A_QUALIFICATION`) — no
atom is missing. Round-by-round trace (starting from the 9 ambient carriers, applying operators in
their declared order) confirms: `EVIDENCE` becomes reachable once `Qualify` runs (rule requires
`{OBSERVATION,POLICY}`, both available from round 1); `VERDICT` becomes reachable the following round
once `EVIDENCE` and `CLAIM`/`HYPOTHESIS` coexist. Every other kind was already shown reachable in C0.

**`Beh_𝔠(C0_PLUS) = Reach(C0_PLUS) = all 23 carrier kinds (complete).`**

## Proof 1: `C0 ≺_cap C0_PLUS` (strict, computed)

`Beh_𝔠(C0) = 21 kinds ⊊ Beh_𝔠(C0_PLUS) = 23 kinds`. By MinKer's own definitions (§2): `C0 ⪯_cap
C0_PLUS` holds (subset); `C0_PLUS ⪯_cap C0` fails (23 ⊄ 21). **Therefore `C0 ≺_cap C0_PLUS`, strictly
— a computed result, not a citation of the corpus's own prior claim that "Qualify repairs a missing
capability."**

## Proof 2: `Qualify` is provably irreducible (unique atom holder)

Directly from the atom table: `A_QUALIFICATION` is held by exactly one operator among all 14 —
`Qualify` itself. Removing `Qualify` from any operator set containing it therefore removes
`A_QUALIFICATION` from the pool entirely, which (by the same argument as above) makes `EVIDENCE` and
`VERDICT` permanently unreachable. **`Qualify`'s removal strictly shrinks `Beh_𝔠` in every case — this
is the formal content of "Qualify is irreducible," now proven rather than cited.**

## Proof 3: `DetectGap` is provably redundant, given `{Determine, Discriminate}` present

`DetectGap`'s own atoms: `{A_NORM_COMPARISON, A_DIFF_DECISION}`. `A_NORM_COMPARISON` is also held by
`Determine` (`{A_NORM_COMPARISON, A_CLOSURE}`); `A_DIFF_DECISION` is also held by `Discriminate`
(`{A_DIFF_DECISION}`). **`DetectGap`'s atom set is a subset of `atoms(Determine) ∪
atoms(Discriminate)`.** Consequence: `Reach(C0_PLUS \ {DetectGap})` — with `DetectGap` removed but
`Determine` and `Discriminate` retained — still reaches `NORM_DELTA` (via `Determine`'s
`A_NORM_COMPARISON`, rule `{K_STATE,IDEAL_STATE}+{A_NORM_COMPARISON}→NORM_DELTA`) and then `GAP` (via
`Discriminate`'s `A_DIFF_DECISION`, rule `{NORM_DELTA}+{A_DIFF_DECISION}→GAP`), by the identical
mechanism `DetectGap` itself would have used. **`Beh_𝔠(C0_PLUS \ {DetectGap}) = Beh_𝔠(C0_PLUS)` — the
two operator sets are `≡_cap`, a computed semantic equivalence, not a citation.**

## Connection to the corpus's own "13 → 12/13-irreducible, two minimal kernels of equal cardinality"
narrative (MD-048)

`C0_PLUS \ {DetectGap}` has exactly **13 operators** (14 minus 1) and the identical `Beh_𝔠` as the
full 14-operator `C0_PLUS`. This gives a **computed, concrete instance** of exactly the pattern the
breakthrough documents (MD-048) and the MinKer chain (MD-044/045) both narrate but never demonstrate
with a worked example: a 14-operator set and a 13-operator set (differing by exactly the redundant
`DetectGap`) sharing the identical semantic behavior — "different operator packaging, same Kernel
capability system," now shown concretely for F3's own operator vocabulary.
