# Specification Sufficiency Matrix (required master matrix)

| Structure | Property | Direct evidence? | Partial? | Source-defined? | Conflicts? | Formal enough? | Composition relevance | Status |
|---|---|---|---|---|---|---|---|---|
| B's C0 operators | I/O contracts | No (admissible frame) / **Yes (`research/kernel-reduction/`)** | — | Yes, richly, outside frame | No | Yes, outside frame | High — this is exactly what MD-024 needed | `E1-OUT-OF-SCOPE` |
| `S^epi` | `E`/`C`/`Q`/`A` typing | Partial (admissible: signature only) / **Yes for `C` (external)** | Yes | Yes, outside frame for `C` | No | Partial | High — directly closes MD-024's own named gap | `E2` (in-frame) / `E1-OUT-OF-SCOPE` (`C` specifically) |
| C1 `ConflictRecord` | definition, invariants | No | No | No | No | No | Unknown — cannot assess without a definition | `E3`/`E4` |
| C2 `Θ` | type, domain, codomain | No | No | No | No | No | Unknown | `E3`/`E4` |
| A `Context` | usable definition | Yes, but 4 competing | N/A (internally conflicted) | Yes, 4 ways | **Yes — 4-way internal conflict, CT-1** | No — unresolved among 4 | Cannot supply `S^epi`'s `C` without picking one of 4 | `E2`, internally conflicted |

## Summary

**Composition test now possible for the B-operator/`S^epi` side only if `docs/knowledgeos/research/`
is brought into scope by a separate governance decision.** The `ConflictRecord`/`Θ` side remains
genuinely unspecified corpus-wide, within every frame this study checked. The `Context` side has an
unusual status: richly evidenced, but internally self-conflicting, which is itself a form of
insufficiency distinct from absence.
