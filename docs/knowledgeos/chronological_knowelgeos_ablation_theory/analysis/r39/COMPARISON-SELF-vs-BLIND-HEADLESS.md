# R-39 — SELF vs BLIND-HEADLESS comparison (pre-registered classes, F-LOG-0053) — reproducibility check, not independent replication

| | |
|---|---|
| SELF | `analysis/r39/R-39-EVIDENCE-REPORT.md` (the author reader; not blind) |
| BLIND-HEADLESS | `analysis/r39/BLIND-HEADLESS/answers.json` sha256 `69cb472b…a226e` · Claude Opus 5.5, headless session in a non-git directory; self-check CLEAN · **SECONDARY_REVIEW** (same family) |
| Provenance | the same bundle (`2bcc5b81…`) and objects (`5bb384af…`, `45780fd5…`); both read completely → **no PROVENANCE_DISAGREEMENT** |

| Q | Topic | Class | Note (both observations preserved) |
|---|---|---|---|
| 1 | what happened | SEMANTIC_AGREEMENT | DA-approved early promotion as an explicit exception; one context vs > 1 required; "promotion rule intact" (L25) |
| 2 | ordinary rule | SEMANTIC_AGREEMENT | both note that the ES-006.1 text is absent and the rule is attributed to "ES-006.1 + EPIC-004 self-governance" |
| 3 | evidence | SEMANTIC_AGREEMENT + blind-only ambiguity | blind adds: "generalized by construction" might argue the evidence is not domain-specific (reading b) |
| 4 | authority | SEMANTIC_AGREEMENT + blind-only ambiguity | blind adds: the DA/ARB relationship is undefined; which act granted the exception is unclear |
| 5 | bar changed? | EXACT_AGREEMENT | no (L25) |
| 6 | set aside for one item? | SEMANTIC_AGREEMENT | yes, "exception"; blind notes one module = seven principles |
| 7 | promoted with the bar unsatisfied? | SEMANTIC_AGREEMENT | yes; blind notes this is inferred by juxtaposition, not stated in so many words |
| 8 | temporal scope | SEMANTIC_AGREEMENT | unspecified for the exception; forward-only for the rule |
| 9 | validation deferred? | SEMANTIC_AGREEMENT | blind: "Expected validation", not the word "deferred"; conditional vs expectation unclear |
| 10 | future promotions | EXACT_AGREEMENT | the multi-context bar applies to FUTURE methodology promotions |
| 11 | operation kinds | SEMANTIC_AGREEMENT | governance acts plus pre-existing evidence; **no new evidential event at R-39**; blind adds WORK (placement, hook) and the ARB ratification |
| **12** | **A6** | **SUBSTANTIVE_DISAGREEMENT** | SELF: **NOT APPLICABLE (different mechanism)**. Blind: **cannot be determined**. Reading A (literal: bar unchanged) = no contradiction; reading B (the exception modelled as a per-item bar change) = contradiction. Both agree the source states the bar unchanged |
| 13 | Promote representable? | SEMANTIC_AGREEMENT | both: **no**; blind lists three possible re-mappings (per-item u; e satisfies u via the "generalized" argument; "promotion" ≠ Promote) and adopts none |
| **14** | **D1 / D3** | **SUBSTANTIVE_DISAGREEMENT (D3)** | SELF: D3's requirement "met" (evidence plus governance present). Blind: **cannot be determined**: D3 quantifies over trajectories reaching a *Promote state*; if the event ends with e ∉ u it is not formally Promote, and the step order is not given. D1: both treat it as not directly contradicted |
| 15 | fact vs interpretation | SEMANTIC_AGREEMENT | Q1–11 fact; Q12–14 interpretation |

## Result (no averaging; no resolution)

- **Historical reconstruction: reproduced.** 13 of 15 answers agree (2 exact, 11 semantic).
  - An explicit one-item governance exception.
  - Evidence from one context below a stated multi-context rule.
  - The rule is stated intact and forward-applying.
  - Validation is expected.
  - No new evidential event.
- **Formal interpretation: two substantive disagreements, preserved.**
  1. **A6:** SELF says not applicable; blind says cannot be determined without a modelling choice (literal: no contradiction; per-item bar modelling: contradiction).
  2. **D3:** SELF's "requirement met" is **not reproduced**. The blind reader's objection is formally precise: D3's premise is reaching Promote, which the event does not satisfy under e ∉ u. **SELF's §G.1 statement "D3's requirement is met" is therefore flagged as over-stated.**
- **Agreed by both:** the event is **not representable** by Promote ⟺ e ∈ u ∧ g = 1.
- **Relation to the T-A blind readers** (pointer row only): both classified the pointer as AMBIGUOUS with a live A6 counterexample reading. The full-source blind reading keeps that ambiguity **at the modelling level**, even though the historical fact (the rule stays intact) is clear.

## Consequence for the next gate (a recommendation, not a decision)

The disagreements are **modelling questions, not source questions**: how do u, e and Promote map onto "rule", "exception" and "adopted"? That is exactly what Gate 2 (the M0/M1/M2 comparison) must decide formally, with the mapping fixed in advance. It does not call for more corpus reading. Independent (non-Claude) confirmation is still open.
