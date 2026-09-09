# MD-064 §02 — Q2: Strict Signature Comparison

| Property | M0043 `Sat(K_t,r)` | M0125 `Sat` |
|---|---|---|
| **Arguments** | `(K,EC_t)` or `(K_t,r)` — two, explicit (`[DEF-19]`/`[DEF-20]`/`[DEF-21]`) | **None given** — never written as an applied function |
| **Domain** | `𝕂 × Req(EC_t)` (or `𝕂 × EC_t`) | **Not stated** |
| **Codomain** | `\{0,1\}` (Boolean, implicit in `¬Sat(...)` usage) | **Not stated** |
| **Purpose/context** | Explicitly purpose-relative, via `EC_t=EC(S_t,G_t,Q_t,C_t)` | **Not connected to any `EC_t`-shaped object anywhere in M0125** |
| **Evaluated object** | A knowledge state `K_t` (or `K`) | **Not stated** — the surrounding table (`§3.1`) evaluates propositions/claims in its other rows (`Zero`, `Contr`-adjacent material) |
| **Requirement object** | `r∈Req(EC_t)`, later typed by M0047 as `(id,type,scope,content,standard,priority,validity)` | **Absent** — no requirement object appears near either `Sat` occurrence |
| **Role in `Δ_t`** | Direct — `Δ_t=\{r∈Req(EC_t):¬Sat(K_t,r)\}`, the frozen definition itself | **None** — `Δ_t`/`Δ` never appears in M0125's own text near `Sat` |
| **Definition/provenance** | `[DEF-19]`–`[DEF-21]`, M0043, a formally tagged definition | **Undefined locally** — used as if already known, no tag, no formula |

## Mismatch classification

| Mismatch | Classification |
|---|---|
| Missing arguments in M0125 | **UNRESOLVED** — could mean "the same predicate, referenced informally without restating its signature" or "a different, informally-named predicate"; the text does not disambiguate |
| Missing codomain/domain in M0125 | **UNRESOLVED**, same reason |
| No `EC_t`/`Req`/`Δ_t` co-occurrence | **UNRESOLVED, leaning toward distinctness** — if M0125 intended the same `Sat(K_t,r)`, at least one of these four closely-linked M0043 symbols would plausibly appear somewhere near it; none does, anywhere in the file |
| Table context (`§3.1`) evaluates propositions in its neighboring rows | **UNRESOLVED, leaning toward distinctness** — the immediate textual neighborhood favors a Contr/Zero-family evaluated object over a `K_t`/`r` pair, though the same table also includes one clearly `K_t`-native row (`"K insufficiency \| E_t→K_t"`), so the table itself is not evidence for one reading exclusively |

**No mismatch is repaired.** Every one is left exactly as strong as the evidence supports —
`UNRESOLVED` in every case, with a stated lean where the text gives one, and no lean where it does
not.
