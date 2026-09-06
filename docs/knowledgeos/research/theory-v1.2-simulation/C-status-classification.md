# C — Status Classification (v1.2 §12)

Every claim this lane produced, in v1.2's own vocabulary. This artifact exists because v1.2 made the
vocabulary constitutional, and because the previous lane had no way to grade its own output.

| Claim | Status | Basis |
|---|---|---|
| `Sat` is three-valued and class-indexed | **ESTABLISHED** (by the theory) | v1.2 companion document |
| Kleene conjunction over composite requirements | **ESTABLISHED** (by the theory) | §10 of that document |
| `Gap` is a partition, not a set, once `Sat` is three-valued | **DERIVED** | immediate consequence |
| **"`Zero ⟺ Δ=∅`" names three different predicates** | **DERIVED** | E1 |
| **The three readings disagree on a determined, corroborated state** | **ESTABLISHED** (computed) | E1, `strict=false / weak=true / Kleene=U` |
| `Zero` is unevaluable while governance, temporal and operation semantics are open | **DERIVED** | E1 — those three classes supply every `U` in the best case |
| `Zero` is not a state | **REFUTED** (as a state) | E5 — computed from `(K,Req)`, stored in no `K` |
| **`Zero` is coarser than the missingness taxonomy** | **REFUTED** (as a missingness representation) | E5 — all four unknown kinds map to `U` |
| `Zero` is a derived view | **DERIVED** | E5 — recomputable from `(K,Req)` alone |
| `Zero`-as-boundary requires three-valued `Sat` | **DERIVED** | E5 — two-valued `Sat` collapses absence into negation |
| `Zero` may be only a *name* for `GapPartition` | **HYPOTHESIS** | E5 — no computed outcome depends on the name |
| The five relations are pairwise non-substitutable | **BOUNDED** — true in 13 of 20 directions | E2 |
| The other 7 directions form a refinement order | **DERIVED** | E2 |
| That order is λ-relative | **ESTABLISHED** (computed) | E2 — fails for `λ ∋ provenance`, `λ ∋ weight` |
| CE-1 factivity persists under v1.2 | **ESTABLISHED** (computed) | E3 — witness reproduced |
| v1.2 weakens the premise of CE-1 rather than repairing it | **INTERPRETATION** | v1.2 §9 downgrades `E_t ≠ K_t` |
| CE-3 revision-without-retraction persists | **ESTABLISHED** (computed) | E3 |
| v1.2 §VIII names the revision verbs without defining them | **TECHNICALLY OPEN** | reading of v1.2 |
| Three-valued `Sat` improves the *diagnosis* of CE-3 | **DERIVED** | E3 — 1 violated + 6 undetermined vs "a gap" |
| Corroboration results depend on the source component of an OPEN concept | **ESTABLISHED** (computed) | E4 |
| Context can be dropped with no effect | **BOUNDED** — only for unambiguous tokens | E4; AD-9 of the v1.1 run shows ambiguity matters |
| Which `Zero` reading the theory adopts | **NORMATIVE** | not an experimental question |
| Whether `⪰` on epistemic status exists | **TECHNICALLY OPEN** | the theory does not define it; `Sat_status` assumes one |
| Whether the 8 requirement classes are exhaustive | **UNKNOWN** | not tested |
| Anything about real knowledge | **DEFERRED** | synthetic world |

## Note on the vocabulary itself

Applying v1.2's status vocabulary was the cheapest and highest-yield change in this run. It forced
a distinction the v1.1 lane had to make in prose: between *computed* results (E1, E2, E3, E4),
*consequences* of a definition (`Gap` as partition), *readings* of the theory (that v1.2 weakened
CE-1's premise), and *decisions nobody has made* (which `Zero`).

`[INF]` The one gap in the vocabulary: it has no term for **"refuted as one reading, surviving as
another"**, which is exactly `Zero`'s situation in E5. `REFUTED` is currently all-or-nothing.
