# Phase 5H — Semantic Relations and Equivalence (kept explicitly distinct, per the authorization's §13)

| Relation | Operational definition | Holds for K-1↔K-2? | Evidence |
|---|---|---|---|
| **Structural equality** ($S_1=S_2$) | Literal set/tuple identity | **FALSE** | `t285_equality.py` §1: "`pi image ≠ target` → FALSE. `Assertion` is not a ratified primitive at all" |
| **Isomorphism** ($S_1 \cong S_2$) | A bijective, structure-preserving map with an inverse | **NOT TESTED, and not evidenced** — no inverse map $K_2 \to K_1$ exists anywhere in the corpus (confirmed again this phase; `e_equality.py`/`t285_equality.py` never construct one) | — |
| **Projection** ($\pi: S_1 \to S_2$) | A (possibly partial, possibly non-injective) structure-mapping function | **HOLDS**, precisely characterized in `08` | `t285_reconcile.py`, D285-6 |
| **Information-preserving mapping** | An explicit criterion stated and tested | **NOT ESTABLISHED** — the corpus's own code (`t285_equality.py` §3) explicitly tests and REFUTES observational equality, the closest available proxy for "information preserved as far as querying is concerned" | `t285_equality.py` |
| **Observational equivalence** | Same answers to the same defined query set | **FALSE**, with the query set explicitly defined in code (`e_equality.py`'s `QUERIES`; `t285_equality.py`'s `QUERIES` dict with `member/contradicts/supersede/lineage` = answerable, `replay/policy-eval/authorize` = not) | `t285_equality.py` §3 |
| **Semantic preservation** ("=_semantic" in the corpus's own vocabulary) | The corpus's own defined sense: equal *after* unpacking `Assertion` and *modulo* the declared drop | **HOLDS, under the corpus's own definition** — but see the caveat below | `t285_equality.py` §2 |

## The corpus's own self-correction, now fully documented

`t285_equality.py` — an executable script — **explicitly corrects** an earlier overclaim: *"WRONG (as
D285-6 originally wrote it): `(A,R) = π_K(K_t)`. CORRECT: `(A,R) =_semantic π_K(K_t)` after unpacking
Assertion... and explicitly NOT `=_structural`... NOT `=_observational`."* **This is the corpus's own
research doing exactly the discipline Phase 5G's own `06` independently arrived at from the outside**
— the corpus itself, at the code level, already distinguishes structural/semantic/observational
equality and refuses to assert more than "=_semantic" holds.

## Does this change Phase 5G's own "downgrade" of "semantic equality"?

**No, but it reframes it.** Phase 5G downgraded "semantic equality TRUE" to "partial correspondence,
unverified" because the corpus's *prose* (D285-6) stated the result without a rigorous definition.
**This phase now finds the corpus's own *code* supplies exactly such a definition** (`=_semantic`,
precisely: equal after a stated unpacking, modulo a stated drop) — **but `07`'s own finding shows the
unpacking itself is not settled even within the code** (`t285_reconcile.py` vs. `t285_equality.py`
disagree). **Net verdict, combining both findings**: the corpus's own methodology for defining
"=_semantic" is sound and disciplined; its own *application* of that methodology is internally
inconsistent about which fields the equality is even being tested over. **"Partial correspondence,
unverified subset" (Phase 5G) is therefore restated more precisely as: "partial correspondence under a
well-defined but internally contested equivalence relation."**
