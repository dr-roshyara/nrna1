# MD-036 — Controlled V0/V6 Semantic and Formal Adjudication

## Authorization

User authorization, 2026-09-08, following the same "write your disagreement first" pattern. No
blocking disagreement — a sound, disciplined deepening of MD-033's/MD-034's own work, correctly
scoped to admissible evidence only.

## Housekeeping note (forward-pointing correction, not a retroactive edit)

Before this study began, a second backlog numbering collision was found: the Lane-T session
independently filed its own, unrelated `EKS-13` at the same time MD-034's own ticket held that
number. Renumbered to `EKS-14` (content unchanged); added a brief, business-language corroboration
note to `EKS-07` (the multi-process-coordination ticket this now twice confirms), per `EKS-09`'s own
established precedent for recording corroborating incidents without duplicating a ticket. **The
already-committed MD-034/MD-035 decision-log entries, which reference the now-superseded `EKS-13`
number, are left exactly as written** — per this session's own standing discipline, frozen phase
records are not retroactively edited; this note is the forward-pointing correction.

## Central question

Is `V6` merely a representational/implementation variant of `V0`, or does it introduce materially
different `Validate` semantics? Does `V6` supply any of the `Validate` specification elements
MD-033 found missing (preconditions, postconditions, failure semantics)?

## Evidence discipline

All four admissible files (`03`, `04`, `06`, `12`) were read in full, directly, in prior studies of
this same session (`03`/`04`: MD-033; `06`: MD-031; `12`: MD-034) — reused here as already-completed
cold reads, not re-executed, consistent with MD-034's own reuse of MD-031's `06` read. **No
executable artifact was consulted** — `kr/carriers.py` and `kr/variants.py` were not re-opened for
this study; MD-030's own prior characterization of them is cited only as already-established
provenance context (per the authorization's own explicit instruction), never as semantic evidence
for this study's own conclusions.

## Headline finding

The reachability difference between `V0` and `V6` (`12`'s own table: "Is a `Verdict` reachable
without `Challenge`? `V0`: yes. `V6`: no.") is **directly source-stated**, not something this study
had to re-derive. But the general *matching mechanism* that would let this fact be independently
re-verified from `06`'s own derivation-table definition alone (does an input set need to exactly
equal, or merely include, the required carriers?) is **not fully specified** in the admissible
narrative text — `06` states the required-input tuples but never spells out the matching rule
itself. This matters directly for `07`'s classification, and is treated with care throughout.

The "`fit ⇒ validation`" claim is **not a formal theorem** — the source itself frames it as an
analogy to an unrelated, illustrative statistical example (synthetic OLS confounding), applied to
the kernel by assertion, not by formal derivation from `Validate`'s own definitions. The underlying
*reachability* fact it illustrates, however, is directly source-stated and not in question.

## Non-goals (restated, binding)

Does not select V0 or V6. Does not reject either. Does not admit any executable artifact. Does not
run a composition test. Does not implement V6. Does not create a canonical `Validate` contract. Does
not open Stage 07.

## Artifact map

| File | Content |
|---|---|
| `00_index.md` | this file |
| `01_authorization-and-scope.md` | scope, disagreement check, evidence discipline |
| `02_v0-reconstruction.md` | the baseline object, per-field closed/partial/not-specified |
| `03_v6-reconstruction.md` | the V6 object, same discipline, "candidate" vs. "adopted" distinction preserved |
| `04_fit-validation-failure-audit.md` | adversarial examination of the `fit ⇒ validation` claim's epistemic status |
| `05_semantic-equivalence-adjudication.md` | the seven-level ladder applied |
| `06_mathematical-comparison.md` | domain/codomain/rule/partiality comparison, with the matching-rule ambiguity flagged |
| `07_specification-gap-analysis.md` | does V6 close any of MD-033's own named gaps |
| `08_ddd-audit.md` | domain-concept vs. label classification, extended to the V0/V6 difference itself |
| `09_adversarial-hypotheses.md` | H1–H5 tested |
| `10_claim-and-provenance-audit.md` | re-examines MD-031/033/034/035's own relevant claims |
| `11_final-verdict.md` | the required A–E decision state |
| `12_verification-and-completion.md` | verification suite + required closing statement |
