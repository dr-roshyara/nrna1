# DDD Audit — Domain Concept vs. Carrier/Type Label

For each item, the admissible evidence's own words are checked before any classification is applied.

| Concept | What the admissible evidence actually calls it | Classification |
|---|---|---|
| `Claim` | "artifact," realized carrier for capability C7 (`03` line 46); producible only via `entailment`, "warrant kind DEDUCTIVE" (`03` line 46; `04`'s derivation table via `06`) | **Carrier/type label** — no source text treats it as an entity with identity or lifecycle; it is a typed output of a derivation step |
| `Evidence` | "artifact," carrier for the added capability C25 (`03` line 64: "admit an observation as evidence under a policy") | **Carrier/type label**, with an explicit *upstream dependency on `Policy`* (`06` line 36) — the closest thing to domain-service flavor in the whole population, but still not itself given identity or behavior beyond being a derived kind |
| `Hypothesis` | "artifact," carrier for C6 (`03` line 45) | **Carrier/type label** |
| `Verdict` | "artifact," carrier for C10 (`03` line 49); also required, as an *invariant* carrier, by C16/C17 ("represent uncertainty"/"represent assumptions," `03` lines 55–56) | **Carrier/type label** — its dual role (both a produced artifact and a required invariant-carrier for two other capabilities) is itself source-stated, not inferred |
| `warrant-assessment` | an **atom** — "the content [an operator] is permitted to introduce" (`04` lines 3–5) — explicitly not a carrier, not a capability, a third, distinct category | **Neither an entity nor a value object — a *power label*, the source's own third category alongside carriers and capabilities** |
| `Validate` | an **operator** — "is its declared set of semantic atoms" (`04` line 5) | **Not evidenced as an entity, aggregate, or domain service** — the admissible sources describe it purely as a name bound to one atom; the operator/atom/carrier three-way distinction is itself the source's own explicit anti-circularity design (`04` lines 3–11), not something this study infers |
| `Capability` (e.g. `C10`) | a fourth category — "stated independently of operator names" (`03` line 32), each realized by a carrier or (for a "power"-kind capability) an atom | **A requirement specification, not a domain object** — `03`'s own table (line 38, "Kind" column: `artifact`/`power`/`invariant`) already draws exactly this distinction natively |

## Aggregate / command / bounded-context reading

**Not established, and this study does not infer one.** The admissible population's own three/four-
category taxonomy (operator / atom / carrier / capability) is explicitly designed, by its own stated
purpose (the "anti-circularity device," `04` lines 3–16), to prevent any operator from being treated
as a name-addressed unit with private state or ownership — the opposite of what a DDD aggregate root
or command handler would require. This matches, and is not contradicted by, MD-029's and MD-031's own
prior findings on this exact point.

## Explicit caution honored

No carrier is promoted to an entity, value object, domain event, policy, or domain service merely
because it is capitalized or because it plays a structurally interesting role (e.g., `Evidence`'s own
`Policy` dependency is reported as a fact about the derivation graph, not elevated into a claimed
domain-service relationship — the source itself never uses DDD vocabulary at all).
