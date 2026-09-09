# MD-060 §03 — `Sat` Dependency Reconstruction (Phase C)

## The chain, as stated by the corpus

`K_t → component semantics → Sat(K_t,r) → Δ_t`

## Reconstructed, using only corpus-supported semantics

- **`K_t`**: per `01`, 12 distinct primary formulations exist, none equated. `Sat`'s own *signature*
  is stated **relative to the deliberately abstract V4a** (`K_t∈𝕂`, M0043's `[DEF-19]`–`[DEF-21]`) —
  `Sat: 𝕂 × Req(EC_t) → Bool`. **This is a genuinely important, previously under-stated fact**:
  `Sat`'s own definition does NOT presuppose any specific tuple — it is stated over the most abstract
  member of the family. MD-059's own framing ("`Sat`'s computation requires `K_t`'s own component
  semantics") is correct for M0132's own restatement of `Sat` **in the context of the 11-component
  tuple V7** — but is not a universal constraint on `Sat`'s own signature.

- **A second, independent instance**: M0048 (V5) also proposes its own `Sat(K,r)` (line ~610),
  independent of both M0043's and M0132's own text, over its own 5-component tuple. **Neither source
  cites the other.**

- **Component semantics**: FOUND only for `Σ_t` (V7's own component, `Σ=(A,S,R,V,C)`, fully
  enumerated) — status: **FOUND**. All other named components across all 12 variants: **NOT FOUND**
  (named, not typed). `Req(EC_t)`/`EC_t`: **FOUND as a signature** (`EC_t=EC(S_t,G_t,Q_t,C_t)`,
  M0043), **NOT FOUND as a body** (no source computes `EC_t` for a concrete case).

- **`Sat(K_t,r)`'s own body**: **NOT FOUND anywhere in this census — for ANY variant.** This is the
  single most consequential finding of Phase C, generalizing MD-059's own diagnosis: it is not that
  `Sat` needs the 11-component tuple (V7) specifically resolved — it is that **no one of the 12
  variants, including the two (V4a, V5) that come with their own `Sat`-shaped definitions, has ever
  been given a computable body.**

- **`Δ_t`**: **FOUND, frozen** (`Δ_t=Gap(K_t,EC_t)={r∈Req(EC_t):¬Sat(K_t,r)}`, M0043, ratified M0132)
  — a real, corpus-native, governance-frozen shape, inheriting `Sat`'s own missing body as its sole
  computability blocker.

## Classification per link in the chain

| Link | Status |
|---|---|
| `K_t` (as a type/domain) | **CONFLICTING** — 12 distinct, none reconciled (`02`) |
| `K_t` → component semantics | **PARTIALLY FOUND** — one component (`Σ_t`) typed, all others open |
| component semantics → `Sat(K_t,r)`'s signature | **FOUND** — stated twice, independently (V4a/M0043, V5/M0048), over different `K_t` representations each time |
| `Sat(K_t,r)`'s signature → its body | **NOT FOUND** — the chain's actual break point |
| `Sat(K_t,r)` → `Δ_t` | **FOUND, frozen** — this link itself is solid; it inherits the break from upstream |

## Can a decomposition-independent `Sat` semantics be derived from the corpus without a new modelling
choice?

**No.** `Sat`'s own SIGNATURE is already decomposition-independent in one sense — it is stated over
the abstract `K_t∈𝕂` (V4a), not over any specific tuple. But a signature is not a semantics: **no
corpus source supplies the actual decision procedure**, for any `K_t` representation, abstract or
concrete. Deriving one here would require inventing exactly the missing body — explicitly out of
scope for this phase.
