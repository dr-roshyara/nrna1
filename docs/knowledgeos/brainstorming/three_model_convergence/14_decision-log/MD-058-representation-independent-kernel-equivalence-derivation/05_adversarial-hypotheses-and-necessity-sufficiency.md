# MD-058 §05 — Adversarial Hypotheses (H1–H10) and Necessity/Sufficiency

## H1–H10, none forced to a winner

| # | Hypothesis | Verdict | Basis |
|---|---|---|---|
| H1 | The relation secretly depends on representation. | **PARTIALLY CONFIRMED** | The relation's *form* (`Obs_{Q,𝒪}`-equality) is representation-independent by derivation (`02`); its *instantiation* is not, because `𝒪` is not yet closed representation-neutrally (`R1a`, `03`). |
| H2 | The relation secretly depends on capability decomposition. | **REFUTED for the definition; CONFIRMED as a live risk for computing it** | `Obs` is defined over external query/observation pairs, not internal names — but *computing* `Obs` for a non-F3 candidate currently has no path that avoids inventing a decomposition first, which is exactly why 5/6 candidates are UNAVAILABLE (`04`). |
| H3 | Observational equivalence is too weak to represent kernel identity. | **CONFIRMED** | This is precisely MD-057 Cluster 2's own `N-1A` finding: `≈` is corpus-designed weaker than the intended `≡` (R5). |
| H4 | Behavioural equivalence is too strong. | **PARTIALLY CONFIRMED, for F3 specifically** | The `03` counterexample shows an under-bounded `Beh`/atom-reachability construction can *over-distinguish* representations that should be considered equivalent, unless the atom set itself is representation-neutral — the opposite failure mode from "too strong" in the usual sense, but the same underlying defect (unbounded internal exposure). |
| H5 | Trace equivalence depends on arbitrary observation boundaries. | **CONFIRMED IN PRINCIPLE, UNTESTED IN PRACTICE** | Same shape of dependency as `Obs` (H1), but no `Tr_K` instance exists anywhere to test empirically (`02`). |
| H6 | Satisfaction equivalence collapses into the unresolved requirement definition. | **CONFIRMED** | `C_KOS`/`⊨` are themselves `NOT SPECIFIED` (R8), and the underlying requirement predicates (`Contr`, `⪰`, `δ`, `Qualify`) are themselves multiply-defined/contested per MD-057's own multiplicity register — `Sat`-equivalence would relocate, not resolve, that contest. |
| H7 | Different semantic relations satisfy the same requirements. | **CONFIRMED — the central derived finding** | `Obs_{Q,𝒪}`-equality satisfies R1/R2/R4, but so would any strictly finer relation (e.g. a hypothetical representation-neutral `Beh`-equality) — R as currently stated bounds a *family* of relations from below, it does not pin down one. This is itself the **NECESSARY CONSEQUENCE** that a further design/governance choice (how much stronger than the derived minimum `≡_sem` should be) is mathematically unavoidable. |
| H8 | `MinKer` has multiple incomparable minimal elements. | **NOT TESTED — insufficient instantiated population (1/6)** | Flagged as `HYPOTHESIS` only (`04` §9.8); the operation-registry commission's six-registries finding is cited as a structurally suggestive, not transferable, analogy. |
| H9 | No actual F1–F6 candidate can be instantiated under the derived semantics. | **CONFIRMED for 5 of 6** (all but F3) | `04`. |
| H10 | The mathematical derivation still requires an ungrounded governance/design choice. | **CONFIRMED, at minimum three points** | (a) fixing `Q,𝒪` representation-neutrally (R10/R1a) is a governance act per the corpus's own `N-4`; (b) how much stronger than the derived minimum `≡_sem` should be (H7) is an open design choice; (c) tie-breaking for `MinKer` under possible non-uniqueness (`04` §9.9) is open. |

## Necessity/sufficiency classification (§13)

| Condition | Classification | Basis |
|---|---|---|
| Observational equality (fixed `Q,𝒪`) | **SUFFICIENT for `≈_{Q,𝒪}`** (trivial, by definition); **NECESSARY but NOT SHOWN SUFFICIENT for the corpus-intended `≡`** | R5/R6 — `≡` is intended stronger; matching all admissible observations is necessary but not proven to pin down true identity |
| Behavioural equality (`Beh_𝔠`) | **SUFFICIENT for `Obs`-equality GIVEN a representation-neutral atom set** (`03` P1); **NOT SHOWN NECESSARY** | a candidate could match all observations without matching full internal behaviour if `𝒪` never exposes the differing behaviour |
| Invariant preservation / congruence (R3) | **NECESSARY** (`258.9`, direct); **NOT SHOWN SUFFICIENT** | multiple relations of different strength could each be congruences (H7) — congruence alone does not select one |
| Satisfaction equivalence (R8) | **UNRESOLVED** | cannot classify while `C_KOS`/`⊨` are themselves unspecified (H6) |
| Capability preservation / no laundering (R7) | **NECESSARY** (a conservation principle — its violation would make any equivalence claim untrustworthy); **NOT SUFFICIENT** | many non-equivalent candidates could each individually preserve capability without being equivalent to one another |
| Minimality | **NEITHER necessary nor sufficient for equivalence** | minimality is a property of *representatives of already-formed equivalence classes* (`04` §9.4) — logically downstream of equivalence, not a condition for it |

No condition is called "canonical" merely because it works on the one available example (F3) — the
sufficiency claims above are explicitly conditioned (`GIVEN a representation-neutral atom set`), per
the authorizing prompt's own §13 instruction.
