# MD-079 §02 — Composition Attempt (Steps 1–8)

## Steps 1–6: strongest candidate selection

Per §01's own matrices, "strongest" is read as: *the formulation most directly load-bearing for T21's
own `Sat(K,r,Γ)=Det_r(EvalReq(K,r,EC,Γ),EC)`*, i.e. the anchor rows, since the composition question is
specifically about *that* equation, not about the family in the abstract.

1. **`Sat`**: `Sat_{PartVI}` — `Sat(K,r,Γ)=Det_r(EvalReq(K,r,EC,Γ),EC)`, the only formulation in the
   corpus attempting a genuine (not fiat) computation rule.
2. **`Req`**: Part II's own `Req(EC)={r_1,...,r_n}` — Terminal Classification A, uncontroversial.
3. **`EC`**: `EC_B` — T21 Part I/II's own `⟨Req,Rules,Scope,ER,TR,AR⟩`, the formulation `Sat_{PartVI}`
   itself is stated over.
4. **`r`**: `r_B` — T21 Part II Def 2.18's own abstract `r∈Req`, the formulation contextually closest
   to `Sat_{PartVI}`'s own usage (same rewrite, same "type system" installment).
5. **`standard`/acceptance machinery**: none exists as a T21-native candidate — `EC_B`'s own `Rules`
   field is confirmed, exhaustively, to receive no internal structure anywhere in the 21 parts
   (MD-078 §01). This is itself Step 5's own result, not a failure of Step 5's own method.
6. **`Eval`/`Determination`**: `Det_{PartIII}`/`Det_{Part21}` — the proven, twice-independently-derived
   `Det(K,p,EC,Γ)⟺∀r∈Req_p(EC,Γ):Sat(K,r)=Satisfied`.

## Step 7: attempted composition, without inventing mappings

**Layer 1 — proposition-level determination, given `Sat` values.** `Det_{PartIII}` composes cleanly
with `Req` (Step 2) and `r_B` (Step 4): `∀r∈Req_p(EC,Γ): Sat(K,r)=Satisfied` is well-typed under `r_B`'s
own abstract sort, requires no invented mapping, and is a genuinely complete, proven result (MD-078's
own Classification A). **This layer composes successfully.**

**Layer 2 — the base case, `Sat(K,r)`'s (or `Sat(K,r,Γ)`'s) own value.** Attempting to compose
`Sat_{PartVI}`'s own formula requires, in order:

- `EvalReq(K,r,EC,Γ)` to actually run against a concrete `K`, `r_B`-typed `r`, `EC_B`-typed `EC`, and
  some `Γ`. `EvalReq` is defined only in prose ("should identify whether the evidence and reasoning
  satisfy the semantic conditions imposed by the requirement") — running it requires knowing, for a
  concrete `r_B` instance, *which* semantic conditions it imposes. `r_B` itself supplies none (Def 2.18
  is deliberately abstract, listing eleven possible concern-*topics*, never instantiated for any
  specific `r`).
- **Failure point 1**: there is no way to run `EvalReq` against `r_B` without first supplying, for the
  specific `r` in question, a concrete semantic condition — and the one place such a condition was
  meant to live, `EC_B.Rules`, has no internal structure anywhere in the corpus (Step 5's own result).
  Composition cannot proceed past this point without inventing what `EC_B.Rules` contains.
- `Det_r(EvalReq(...),EC)`'s own body: even granting `EvalReq` somehow produces a `𝒱`-valued evaluation
  vector, `Det_r`'s own body is disclosed, by the source itself, as "may be defined," contract-specific,
  never supplied for any actual `EC_B` instance.
- **Failure point 2**: even with an evaluation vector in hand, there is no corpus-native rule anywhere
  turning it into a `𝕊_sat` value — this is `Det_r`'s own already-established gap (MD-070/076/077/078,
  unchanged).

**Result: composition fails at exactly two points, both already implicated in MD-070's original
finding, but now precisely located relative to the *specific* T21-native objects (`r_B`, `EC_B.Rules`)
selected as the strongest candidates — not a vague "Det_r is undefined," but "Det_r cannot be run
because its own required inputs (a concrete semantic condition from `EC_B.Rules`, and its own
contract-specific body) are each independently, and for independently-disclosed reasons, absent."**

## Step 8: why composition fails, stated precisely

Composition does not fail because the corpus lacks relevant material (MD-078 showed the opposite — the
corpus is rich). It fails because the **two specific slots T21's own equation requires to be filled by
something *other* than `Sat`/`Det_r`/`EvalReq` themselves — `EC_B.Rules`'s own internal content, and
`Det_r`'s own contract-specific body — are each disclosed by the source as deliberately open design
parameters, never populated for any concrete case anywhere in the corpus.** This is not an oversight
discoverable by more reading; it is what "may be defined" and "the exact structure will be refined
later" (a promise never honored in 20 further parts) actually mean, taken at face value. No amount of
composing *other*, better-developed objects (`Req`, `Det_{PartIII}`, `Zero`) closes these two slots,
because neither slot's own content is stated to be derivable from any of those other objects — it is
stated to be *externally supplied*, by design.
