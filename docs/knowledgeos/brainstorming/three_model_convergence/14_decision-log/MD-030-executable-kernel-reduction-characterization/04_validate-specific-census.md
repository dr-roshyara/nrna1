# Validate-Specific Census

Complete `grep -rn "Validate\|VERDICT\|Verdict\|A_WARRANT\|warrant" --include="*.py" .` result
(9 files searched, all `.py` source; `results/*.json` searched separately below) — **every**
occurrence, no omissions:

| File:line | Occurrence | Classification |
|---|---|---|
| `kr/atoms.py:35` | `A_WARRANT = "warrant-assessment"` | atom definition — matches admitted `04-operator-contracts.md` |
| `kr/atoms.py:43` | listed in `PRODUCTIVE_ATOMS` | vocabulary membership only |
| `kr/operators.py:33` | `Op("Validate", {A_WARRANT}, "STRONG", ...)` | atom-set declaration, no I/O type |
| `kr/capabilities.py:27` | comment: "warrant kind DEDUCTIVE" (re: `C7`, inference — not `C10`) | **not about Validate**; a note on a different capability |
| `kr/capabilities.py:30` | `cap("C10", "validate a claim/model", [VERDICT])` | capability definition — matches admitted `03-capability-model.md` |
| `kr/capabilities.py:40–42` | `C16`/`C17` invariant capabilities require `[VERDICT]`; note "assumptions enter warrant assessment" | `Verdict` is also load-bearing for two *other* capabilities (uncertainty, assumptions) beyond `C10` itself — new information, not previously surfaced in MD-024–029 |
| `kr/carriers.py:38` | `VERDICT = "Verdict"` | carrier-kind definition |
| `kr/carriers.py:65–66` | the two derivation rules (see `03`) | **the central finding** |
| `kr/carriers.py:76` | `VERDICT` listed in `DISCRIMINABLE` | `Verdict` artifacts can themselves be discriminated (compared) by the `Discriminate` operator — a downstream-use fact, not an input-to-Validate fact |
| `kr/variants.py:72–81` | `V6` — alternative 3-input Verdict rule | qualifies the `carriers.py` rule as one of ≥2 tested (see `03`) |
| `run_all.py:98` | smuggling probes `("DetectGap","Validate")`, `("Challenge","Validate")` | ablation/anti-circularity test, not a specification |

## `results/*.json` — searched for `"Validate"` / `"Verdict"` as JSON string values

- `minimal_kernels.json` (full read, `00_index.md`/`03` already quoted it): `"Validate"` appears in
  **both** of the 2 minimal covering operator subsets found — i.e. every minimal kernel this
  experiment's search located over the C1–C25 capability model includes `Validate`. Recorded as a
  computed result of this specific model, not re-interpreted further here (see `05`).
- `baseline.json` (structural read, `00`/`03` already quoted the C0 vs. C0+ score difference): `C10`
  appears in the `"lost"` list for plain `C0` (score 21/25) and is **absent** from `C0_plus`'s
  `"lost"` list (score 25/25) — i.e. `C10` (`Validate`'s own capability) is computationally
  unreachable under the 13-operator baseline and becomes reachable only once `Qualify` is added.
  This traces directly to the derivation chain `Qualify → Evidence → (Claim,Evidence) → Verdict`
  found in `carriers.py`.
- The remaining 9 `results/*.json` files (`ablation_atoms`, `ablation_loo`, `ablation_pairwise`,
  `audit_extended_search`, `information_causal`, `paired_analysis`, `randomized`, `smuggling`,
  `smuggling_probes`, `variant_summary`, `variants`) were not exhaustively parsed key-by-key for
  this census beyond what `05` reports; none was found, on inspection, to add a *further* concrete
  Validate-input claim beyond what `carriers.py`/`variants.py`/`baseline.json`/`minimal_kernels.json`
  already establish. This is stated as an **observation bounded by the depth of inspection actually
  performed** (see `05`'s per-file notes), not as an exhaustive proof of absence for every byte of
  every file.

## What this census does and does not establish

**Establishes** (machine-observable, direct evidence, this study's own read): the executable lane
contains one specific, computationally exercised candidate rule for `Validate`'s input carriers
(`{Claim,Evidence}` or `{Hypothesis,Evidence}` → `Verdict`), and that rule is treated internally as
one baseline choice among tested alternatives, and its downstream reachability is conditional on a
different operator (`Qualify`) whose own status this same research lane calls an "irreducible gap."

**Does not establish**: that this rule is what B's own source documentation (the admitted
`04-operator-contracts.md`/`03-capability-model.md`, or the still-unadmitted
`06-composition-rules.md`) actually states as authoritative. The crosswalk in `06` addresses this
directly; no claim of source-authority is made here.
