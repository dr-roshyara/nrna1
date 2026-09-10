# MD-082 — Acceptance/Sufficiency Semantics: Birth-and-Evolution, and a Correction to the "Never Wired" Finding

## Purpose

User accepted MD-081 as a good, bounded step but identified a real methodological error: MD-081's own
"`EC.Rules` remains a genuine gap" language conflated *computational completeness* with *semantic
existence*, and its treatment of `Policy_Det` as a data point rather than a thread to trace forward and
backward in time stopped short of what the governing method requires. This phase corrects that:
`Policy_Det` is investigated as a birth-and-evolution question, not a construction candidate, and every
classification below is split three ways — object identity, semantic responsibility, and computational
completeness — never collapsed.

## Central finding, disclosed first — a correction to five prior phases

While tracing `Policy_Det`'s own surrounding sections, this phase found that **`Det(K,p,EC,Γ)`
(Definition 6.2, §6.27, Part VI) and `Δ_p`/`Zero_p`/`Zero(K,EC,Γ)` (§6.28, §6.73–74, same Part) are
defined using the exact three-argument symbol `Sat(K,r,Γ)`** — the same symbol `[Def 6.18]` (§6.18,
~280 lines / 9 sections earlier in the *same file*) equates to `Det_r(EvalReq(K,r,EC,Γ),EC)`. This
means **the 3-argument `Sat(K,r,Γ)` is not "never wired to `Δ`/`Zero`," as MD-076 first claimed and
MD-077/078/079/081 each repeated without re-checking** — it is wired, within Part VI itself, all the way
from `Sat(K,r,Γ)` through `χ_EC` to `Det(K,p,EC,Γ)` to `Δ_p` to `Zero_p`/`Zero(K,EC,Γ)`, via one
continuous, same-symbol, same-file chain. This is recorded forward; MD-076–081's own text is not
edited.

**What this correction does and does not establish**: the wiring is at the *definitional/symbol* level
— identical symbol, same file, no rival referent anywhere else in the corpus except Part V's equally
uncommitted gloss — `RECONSTRUCTED`, not `SOURCE-STATED` with an explicit cross-reference (§6.27 never
writes "per Definition 6.18"). It does **not** establish that `Det_r`'s own body was ever actually
computed for any case — Theorem 6.1's own proof (§6.29) treats "satisfied according to the
contract-specific satisfaction criterion" as a generic, already-available fact, never invoking `Det_r`
or `EvalReq` by name, and remains valid regardless of how `Sat` is determined. **Semantic connection
present; computational completeness still absent** — precisely the distinction this phase's own mission
requires be kept separate.

## Method

New work: direct primary-source tracing of `Policy_Det`'s own surrounding sections (§6.27–6.29,
§6.41–6.44, §6.73–74, all previously read only in isolated fragments by MD-078/079/081); a targeted
phrase search for `Policy_Det`'s own exact case-conditions ("sufficient independent support," "sufficient
challenge") across the corpus. Everything else builds on MD-078–081's own already-verified evidence.

## Artifact map

- `00_index.md` — this file.
- `01_policy-det-birth-and-evolution.md` — `Policy_Det` traced from birth (confirmed: born and dies in
  §6.43, no earlier or later occurrence anywhere) plus the two further, closely-adjacent status-set
  variants found in the same ~70-line span (§6.42's implicit `{Established,Rejected,Conflicted,
  Undetermined}`, distinct from `Policy_Det`'s own `{Established,Rejected,Conflicted,Unknown}`, distinct
  again from Part V's `𝕊_sat`).
- `02_the-part-vi-internal-wiring-correction.md` — the central finding, in full, with exact quotes and
  line numbers, and the precise epistemic status of the correction.
- `03_three-way-classification-acceptance-chain.md` — object-identity / semantic-responsibility /
  computational-completeness applied, separately, to `EC.Rules`, `Policy_Det`, `Admissible`, the
  "qualification rule," and `Sat(K,r,Γ)` itself.
- `04_responsibility-evolution-ledger-and-closure.md` — the requested ledger, terminal classification
  (three dimensions, not collapsed), backlog assessment, verification, closure.

## What this phase does NOT do

Does not construct `Det_r`/`EvalReq`/threshold semantics. Does not complete `Policy_Det`. Does not adopt
`Standing`/`Sat_c`/`Eval_c`/the verification lane's own `Policy`/`Apply`. Does not invent any mapping.
Does not canonicalize. Does not perform F3↔F4 bridging. Does not make a governance choice. Does not
modify any frozen artifact (MD-024–081). Does not access `theory-extraction/`.
