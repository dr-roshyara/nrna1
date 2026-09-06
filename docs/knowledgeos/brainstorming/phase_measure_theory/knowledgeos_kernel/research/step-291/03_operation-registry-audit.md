# 03 — Operation Registry Audit (mandate §5)

**Only operations the corpus names are listed. No operation is invented (mandate §21).**
Legend: ✅ present · 🟡 partial · 🔴 absent · **PROSE** = mentioned, no registry entry

| Operation | Exists | Typed | Semantic definition | Observable | Identity | Governance status | Missing specification |
|---|---|---|---|---|---|---|---|
| **Revise** | ✅ `259.8` | 🟡 `K→K` implied | 🔴 | 🟡 | 🔴 | unratified | body; `≡` for postconditions; `261.19` says **identity required** |
| **Transform** | ✅ `259.8` | 🟡 `K→K` | 🔴 | 🟡 | 🔴 | unratified | body |
| **Supersede** | ✅ `259.8`·`258.27` | 🟡 `K→K` | 🔴 | 🟡 | 🔴 | unratified | body; **identity required** (`261.19`) |
| **Merge** | ✅ `259.8`·`25J.43` | ✅ `Merge_K(K_1,K_2,Ω,C,T,M)` | 🟡 **laws only** — non-destructive ✅, idempotent *modulo* `≡`, commutativity *"not imposed"*, associativity *"a design goal"* | 🟡 | 🔴 | unratified | `≡`; and the laws are **untested** without `δ` |
| **Split** | ✅ `259.8`·`25S.39` | 🔴 | 🔴 | 🔴 | 🔴 | unratified | everything; `38.40` *"split is epistemically expensive"* |
| **Withdraw** | ✅ `259.8` | 🔴 | 🟡 **its EFFECT is executed** — re-keys `id`, dangles `ℛ` | ✅ | ⚠️ **breaks assertion identity** | unratified | the `id` repair (`N-14`) |
| **Promote** | ✅ `259.8` | 🔴 | 🔴 | 🔴 | 🔴 | unratified | everything |
| **Assess** | ✅ `259.7`·`259.8` | ✅ **`Assess : K × X → Assessment`** | 🟡 **a determination test**: `K_1 ≡ K_2 ⇒ Assess(K_1,x)=Assess(K_2,x)` | ✅ | 🔴 | unratified | body; ⚠️ **NOT `K→K` — kind 2** |
| **Authorize** | ✅ `259.7`·`259.8` | ✅ **`Authorize : Actor × Action × Policy → Decision`** | 🔴 | ✅ | 🔴 | ⚠️ **`authorities.yaml` is an enum, not an evaluator** (`G-66`) | the evaluator; ⚠️ **NOT `K→K` — kind 3** |
| **Reject** | ✅ `259.7` | 🔴 | 🔴 | 🔴 | 🔴 | unratified | everything; rejection semantics vs `I-12`/Article 8 |
| **Validate** | ✅ `259.7`·`261.19` | 🔴 | 🔴 | 🟡 | 🔴 | unratified | ⚠️ `261.19`: **an ASSESSMENT relation, not an equality** |
| **Reintroduce** | ✅ `259.7` | 🔴 | 🔴 | 🔴 | 🔴 | unratified | everything |
| **Replay** | ✅ `259.7`·`261.14`·`25I.32` | 🟡 `Replay(Events_{0..t}, ModelVersion) → K_t` | 🟡 | ✅ | 🔴 | unratified | `Rules`, `ModelVersion`; `261.19`: **needs provenance + historical equality** |
| **Determine** | ✅ `157.22`·`165.8` | 🟡 aggregate + type | 🔴 | 🔴 | 🔴 | unratified | body |
| **Qualify** | ✅ | ✅ `Observation × Policy ⇀ Evidence` | 🔴 **G1** | — | — | unratified | **the body — irreducible** |
| **Resolve** | ✅ `012 §46` | ✅ `ℛ × C ⇀ 𝒳` | 🔴 | — | — | unratified | body |
| **Normalize** | ✅ `012 §46` | ✅ `ℛ × Ω ⇀ 𝒫` | 🔴 | — | — | unratified | body; ⚠️ **`38.53`: itself an epistemic operation** |
| **Compare** | ✅ `012 §46` | ✅ `𝒫 × 𝒫 × C × Ω → 𝒬` | 🔴 | — | — | unratified | body |
| **Deduplicate** | ✅ `261.17/19` | 🔴 | 🔴 | 🔴 | 🔴 | ⚠️ **NORMATIVE/domain-dependent** | which of **4** criteria |
| **Remove** | ✅ `261.19` | 🔴 | 🔴 | 🔴 | 🔴 | unratified | **identity required for targeting** |
| `Retract` · `Publish` · `Apply` · `Verify` · `Policy evaluation` | **PROSE** | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | ⚠️ **named in the Step-288 mandate; NO corpus registry entry** |
| **`Closure(Identity)`** | ✅ `38.42` | 🟡 `AffectedKnowledge(x)` | 🔴 | 🔴 | — | unratified | body |

## Counts

| | |
|---|---:|
| operations with a **corpus registry entry** | **22** |
| **typed** (an explicit signature) | **8** |
| with a **semantic definition (a body)** | **0** |
| with **operation identity** | **0** |
| **governance-ratified** | **0** |
| named only in **prose**, no entry | **5** |

$$\boxed{\textbf{22 named · 8 typed · ZERO with a body · ZERO with identity · ZERO ratified.}}$$

⚠️ **And 4 of the 8 typed ones are NOT `K → K`** — `Assess`, `Authorize`, `Compare`, `Resolve`/`Normalize`.
**`259.7`'s warning holds: *"we must not manufacture a false universal algebra."***

## STATUS
**CORPUS** 22 entries, 8 typed · **MEASURED** 0 bodies, 0 identities, 0 ratified · **DERIVED** 4 of 8
typed operations fall outside `𝒯` · **UNKNOWN** whether the 5 prose-only operations belong in `𝒪` ·
**NORMATIVE** all membership · **G1** `Qualify` *(and the same shape at `Resolve`/`Normalize`/`Compare`)*
