# METHOD — Gate 2.5 reference implementation (SPEC-G25-r1)

| | |
|---|---|
| Implementer | Claude (Opus 5.5) · class **SELF / SECONDARY_REVIEW** — not independent |
| Spec | `SPEC-G25-r1.md` sha256 `5423ebb9416503d556c911f3868f822579f606ec5f531ee93477fc901ffde9d4` |
| Code | `model_g25.py` sha256 `0e5a27d60760d1dc804fa0f255f04583bec477d8d0787e892f2ccc53f80dcc81` (Python 3, stdlib, deterministic) |
| Results | `results_ref.json` sha256 `1771c79a092cf025a5e5a1e108686b6666cb751c4eac6685b0045533fa424895` · SEALED, not interpreted |

## Method
- State = (p, s, e, g, u, au, ad, rv), enumerated fully per instance (bars = up-sets under A0, all subsets otherwise).
- **Maximal step relation:** for each state and kind, all target states are enumerated component-wise (components fixed by an axiom are held; all others range freely) and filtered by the full step predicate `ok()` of the axiom set. Every step satisfying Σ is therefore present, so this is the maximal relation R_max.
- **Exactness:** each universal property ("no trajectory / every reachable event …") is monotone decreasing in the step relation, so it holds for every Σ-relation iff it holds for R_max; it is decided by exhaustive BFS over R_max. Existential items are evaluated on R_max, as the spec says.
- Ordered patterns (TOCTOU, T1–T6) are decided by BFS over the product (state × pattern phase); this is exhaustive and returns a shortest witness.
- Ablation: every single axiom (base and added) is removed and all of §3 is recomputed. Minimal sets: all 2^|added| subsets (base kept) are enumerated and the inclusion-minimal ones are kept.

## Declared readings
- R1: each pattern element is a distinct step, strictly after the previous one.
- R2: intermediate PromEvents are allowed except where the spec forbids them (T6: none before the EVID step, including the AuthEvent step itself).
- R3: TOCTOU's "state with ¬EF" may be the target of the AuthEvent or any later state before the PromEvent.
- R4: t_a / t_p = the source states of the pattern's AuthEvent / final PromEvent (per spec §3, "evaluate events at their source state").
- R5: a universal FAILS witness is the shortest fresh-to-event trajectory among all failing items.
- R6: REVAL-a forbids EVID only (EVIDREF allowed), as literally stated.
- R7: P-GUARD, D1 and D2 start from all matching states of the full state space (P-GUARD: fresh only), as stated.
- R8: antichain2 is not in r1's instance list and is not computed.

## Disclosure
- Before the full run, one **smoke test** printed the MT1-P1/chain3 property classes and the T4 t_p state to the author's console, in order to validate the code. The code was then changed only in its pattern search (readings R1–R3, R5), not in any axiom or property definition. The printed values were not compared against the predictions and are not used. They are superseded by the sealed run.
