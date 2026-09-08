# Phase 5F — Mathematical Equivalence Test

## Formal representations (components actually evidenced only; `NOT EVIDENCED` elsewhere)

$$K_1 = (E, S, T, O, P, R, \Pi, A) \quad \text{(seq 0630 §49.30, raw-source confirmed)}$$

Operations $\mathcal{O}_1$: **NOT EVIDENCED** (D285-7's own explicit finding: never enumerated against
these 8 primitives). Invariants $\mathcal{I}_1$: named (`I-1…I-12`) but content **NOT EVIDENCED** this
phase. Constraints $C_1$: **NOT EVIDENCED**.

$$K_2 = (\mathcal{A}, \mathcal{R}) \quad \text{(D285-1 §1, D285-6 §1/§3)}$$

Operations $\mathcal{O}_2$: $\mathcal{O}_{sem}$, 19 candidates, 5 families (Step 272A) — **explicitly
not minimal**. Invariants $\mathcal{I}_2$: `StructuralValid` + 3 others, derived/unratified.

## The projection map (already constructed by the corpus's own D285-6; not invented by this phase)

$$\pi_K : K_1 \rightarrow K_2$$

Defined by: $Proposition \mapsto P$ (inside `Assertion`) · $Entity \mapsto E$ (inside `P`, inside
`Assertion`) · $Relation \mapsto \mathcal{R}$ · $State \mapsto$ the carrier · $Observation \mapsto e$,
*after* `Qualify` · $\{Event, Policy, Action\} \mapsto$ **dropped, declared external**.

**This map is DEFINABLE and NOT COMPUTABLE** — `Qualify: Observation × Policy → Evidence` has no body
in the corpus (D285-6 §4a; "one undefined hit, Step 170"). This is named `G1`, "irreducible," by the
corpus's own research.

## The three-level equality specification (D285-1/D285-6's own executed result, raw-source confirmed)

| Equality type | Holds? | Grounds |
|---|---|---|
| **Structural** | 🔴 **FALSE** | $\pi$-image $= \{Entity, Observation, Proposition, Relation, State\}$; target $=\{Assertion, Relation\}$. `Assertion` is not itself a ratified primitive |
| **Semantic** | ✅ **TRUE** | Only after unpacking `Assertion` → `{Proposition, Entity, Observation}` + `{id,c,t,Π}`, modulo the declared drop of `{Event,Policy,Action}` |
| **Observational** | 🔴 **FALSE** | Answerable in `(𝒜,ℛ)`: `member`, `contradicts`, `supersede`, `lineage`. **Not answerable**: `replay`, `policy-eval`, `authorize` |

**No inverse map $g: K_2 \rightarrow K_1$ is constructed or claimed anywhere in the corpus** — the
projection is one-directional and explicitly lossy (three primitives and three query classes lost "by
design," per D285-6 §5). A structure-preserving inverse would be required for demonstrated identity or
full formal equivalence in the strict sense (per the authorization's own §6); its absence is the
reason this phase does not upgrade the K-1↔K-2 relationship beyond partial correspondence (`09`).

## The K-1 ↔ Phase-5C's-K-1-B relationship (Reading 1, no projection needed)

No projection map is required or constructed for this pair, because — per `02`'s reconstruction — both
labels denote the identical formal object `𝒦=(E,S,T,O,P,R,Π,A)`, cited without transformation within
one authored package (seq 1006, seq 1008). The relevant mathematical question here is not "does a
mapping exist" but "is this genuinely one citation trail, or two independent constructions that merely
resemble each other" — resolved in `09` using provenance evidence (shared package, shared day, shared
label, no claimed distinction between the two mentions), not a constructed isomorphism.

## Minimality, addressed directly (per the authorization's own framing)

**K-2 is explicitly NOT concluded to be minimal** — twice, independently: (a) Step 272A §27 itself
states the 19-operation `𝒪_sem` set is "not minimal"; (b) D285-7 states "`(𝒜,ℛ)` must NOT be concluded
to be the minimal operational kernel" because `𝒪` was never enumerated against K-1's own 8 primitives.
**Neither K-1 nor K-2 carries a formally tested minimality claim** — consistent with every prior
phase's own finding that no Kernel candidate anywhere in this reconstruction reaches a fully tested
minimality proof.
