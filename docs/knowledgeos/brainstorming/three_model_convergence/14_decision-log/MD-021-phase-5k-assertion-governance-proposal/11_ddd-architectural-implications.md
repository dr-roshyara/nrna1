# Phase 5K — DDD Architectural Implications (per option, status: `ARCHITECTURAL IMPLICATION — NOT YET ADOPTED` throughout)

| Dimension | Option A | Option B | Option C | Option D | Option E |
|---|---|---|---|---|---|
| Ubiquitous-language impact | Adopts D1/D3's own conceptual vocabulary; drops Observation-language | Adopts D2/D4's own vocabulary; keeps abbreviated `c/t/Π` | Introduces a new dual-vocabulary (both retained) | Introduces a synthesized vocabulary, some terms newly chosen (drops `Π`) | No change — both vocabularies remain live, context-dependent |
| Aggregate boundary | `Assertion` as currently scoped in D1/D3 | `Assertion` as currently scoped in D2/D4 | `Assertion` grows to include an internal 2-step lifecycle | `Assertion` grows to the union of A+B's own fields | Two (or three) separate aggregate candidates remain, unmerged |
| Value-object candidacy | Assertion remains identifier-less (no `id` in D1/D3) | Assertion is content-addressed (`id`, `e_equality.py`) | Unspecified — inherits whichever `id` convention is chosen at implementation time | Assertion is content-addressed (`id` retained) | Each variant keeps its own identity convention |
| Invariants | None stated; none added | None stated; none added | A new invariant is implied (`Observation` must be qualified before contributing to `Evidence`), **not previously stated anywhere** | Same new invariant as C, since it inherits both fields | No new invariant — each variant's own (absent) invariant set is preserved as-is |
| Context ownership | Implicitly D1/D3's own research track | Implicitly D2/D4's own research track | Neither — a new track | Neither — a new track | Both/all tracks retain their own implicit ownership, unassigned |
| Translation requirement | None (single schema) | None (single schema) | None (single schema, by construction) | None (single schema, by construction) | **Required at any future point of use** — whoever consumes K-2 must choose a variant |
| Anti-corruption concern | Low (single schema) | Low (single schema) | Low (single schema) | Low (single schema) | **Elevated** — exactly the concern this reconstruction's own Phase 5G already named without resolving |
| Shared-kernel risk | N/A (one owner) | N/A (one owner) | N/A (one owner) | N/A (one owner) | **The clearest candidate for a future Shared-Kernel-style joint-ownership arrangement**, if the two variants are ever formally co-maintained |
| Governance ownership | Implicit: whoever selects A becomes accountable for its own gaps | Same, for B | Same, for the new synthesis | Same, for the new synthesis | **Deferred** — no single party becomes accountable for a specific schema; accountability shifts to *whoever eventually consumes* K-2 |

## Explicit status

Every cell above is an **ARCHITECTURAL IMPLICATION — NOT YET ADOPTED**. No bounded context, context
map, shared kernel, anti-corruption layer, or governance ownership assignment is declared by this
phase.
