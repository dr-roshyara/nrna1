# Phase 5J — Mathematical Compatibility Testing (per the authorization's §15)

Testing whether the D1/D3 and D2/D4 Assertion characterizations can be related through any of the
required mapping types.

| Mapping type | Test | Result |
|---|---|---|
| **Bijection** | A 1-1, onto correspondence between the two field sets | **Not evidenced** — the sets differ in size (6 vs. 7) and content (`Evidence,Context,Provenance` vs. `Observation,id`); no natural pairing is stated |
| **Injective mapping** | A 1-1 (not necessarily onto) map from one set into the other | **Not evidenced** — would require, e.g., mapping `Evidence↦?` in the D2/D4 set; no candidate target is named anywhere |
| **Surjective mapping** | Onto, not necessarily 1-1 | **Not evidenced**, same reason |
| **Embedding** | One set's structure preserved as a sub-structure of the other | **Partially plausible** for `Proposition,Entity` (present in both) but **not evidenced** for the remaining fields |
| **Projection** | A many-to-one map, e.g. the K-1→K-2 maps already studied (Phase 5H/5I) | **This is the one mapping type actually evidenced in the corpus** — but it maps *K-1's 8 primitives* onto *each* Assertion characterization separately (as $\pi_1$/$\pi_2$, Phase 5I `09`), not one Assertion characterization onto the other |
| **Quotient** | One set as an equivalence-class reduction of the other | **Not evidenced** |
| **Structure-preserving transformation** | Any stated function preserving relevant structure | **Not evidenced** between D1/D3 and D2/D4 directly |
| **Lossless encoding** | A reversible transformation | **Not evidenced** |

## The one honest hypothesis available, labeled correctly

A plausible informal correspondence — `Evidence↔e` (Phase 5H `05`), `Context↔c`, `Time↔t`,
`Provenance↔Π` (contested, `09`) — could be *constructed* by this reconstruction. **Per the
authorization's explicit instruction, this is labeled `RECONSTRUCTION / HYPOTHESIS`, not demonstrated
equivalence** — it is not stated anywhere in the corpus, and this phase does not present it as
established merely because the field names are suggestively similar.

## Verdict

**No mathematical compatibility relation is demonstrated between the two competing Assertion
characterizations.** The only genuinely evidenced mapping in this whole investigation remains the
K-1→K-2 projection itself (in its two competing forms, $\pi_1$/$\pi_2$), not a mapping *between* the
two K-2-internal characterizations.
