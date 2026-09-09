# MD-074 — SAT-END-TO-END-CLOSURE-TEST-v1: Independent Verification Run

## Authorization and mission

User's direct instruction (2026-09-09, in-session): follow the prompts in
`docs/knowledgeos/brainstorming/what_is_knowlegeos_theory/20260909-2305_sat-evolution-and-end-to-end-closure-test.md`.
That file embeds mission **`SAT-END-TO-END-CLOSURE-TEST-v1`**: select ONE corpus-grounded
requirement `r`; gather only corpus-grounded `K`/`EC`/`Γ`; attempt the `Eval → EvalReq → Det_r →
Sat` chain using corpus-native definitions only; record every intermediate value with exact source;
**stop at the first point where execution requires semantics the corpus does not establish**; and
return a `FINAL VERDICT` of COMPUTED / PARTIALLY COMPUTED / BLOCKED. Do not invent `EvalReq` or
`Det_r` semantics, `Accept_r`, `Σ/V7`, adapters, or thresholds. A failure is a valid research
result.

## Why a separate entry exists (consumption, not duplication — ES-005.4)

`MD-073` already ran a mission materially equivalent to this one (authorized via sibling file
`...-2247`, option b) and returned **BLOCKED at the first `EvalReq` invocation**. This phase is an
**independent, primary-source verification run** of the same experiment — the corpus's own norm for
trusting a result (cf. MD-070 reviewing GAP-004 independently; G-18 closed "by Main against primary
source, not dependent on either worker"; EKS-46 on silent qualification-drop in transmission).
It consumes MD-073/EKS-44/EKS-47 as baseline rather than re-deriving them, executes the 2305
mission's own OUTPUT format (which MD-073's artifact did not literally produce), and records where
this run agrees with and sharpens MD-073. It changes no prior artifact.

## What this phase is

A **CONSTRUCTED** activity (this reconstruction's own experiment), not a corpus-native artifact. It
does not change what any historical `TheoryState(T_n)` records. It makes no claim about the
correctness of the `Sat` definition (GAP-004 already CLOSED WITH QUALIFICATION by MD-070; not
reopened).

## Case selected

`R1` — `r_1 = PaymentConfirmed(S)` ∈ `Req_release(S)`, the corpus's best-evidenced real
requirement (named contract, named requirement, named evidence object with provenance, named
supporting proposition). Deliberately the **same case** MD-073 ran, so two independent runs can be
compared directly.

## Method

1. Read the primary sources directly — the worked example [WE] (Decision Contract §21A.3,
   Evidence Object 1 §21A.5, Rule Definition §21A.10, Determination §21A.17), the Part VI calculus
   [PVI] (§6.15 `Eval`, §6.16–6.17 `EvalReq`, §6.18 `Det_r`/`Sat`, §6.27 Determination), and Part II
   [PII] (Definition 2.20 `EC`, §2.30 `Δ`, Definition 2.21 `Zero`) — not via MD-073's summary.
2. Attempted to instantiate every argument each stage requires, using only corpus-supplied values.
3. Reproduced the load-bearing corpus-wide greps (`EvalReq(`, `Det_r(`, `Eval(`, `Γ` definitions,
   `EC` constructions) to confirm absence of any concrete invocation anywhere.
4. Recorded the trace in the mission's OUTPUT format (per-step classification) and classified the
   case per the mission's three-way schema.

## Verdict, stated up front

**BLOCKED** — independently confirmed, at the same first break MD-073 found: `EvalReq(K,r_1,EC,Γ)`
cannot be invoked because no `EC` instance and no `Γ` definition/instance exist anywhere. The
independent run adds four sharpening observations (Eval never invoked; `EvalReq` has no type
signature; the worked example's only executed computation is the `ρ_release` derivation, bypassing
this chain; the corpus's `Δ`/`Zero` and all `Sat` stipulations are wired to the 2-arg `Sat(K,r)`,
so the decisive 3-arg `Sat(K,r,Γ)` has no downstream consumer). Full trace:
`01_execution-trace-and-verdict.md`.

## Artifacts

- `01_execution-trace-and-verdict.md` — the mission's OUTPUT: full case record, per-stage trace,
  six-way step classifications, corpus-wide checks, FINAL VERDICT.

## Cross-references

- Mission file: `docs/knowledgeos/brainstorming/what_is_knowlegeos_theory/20260909-2305_sat-evolution-and-end-to-end-closure-test.md`
- Prior run, verified here: MD-073 (`../MD-073-sat-single-case-computation-attempt/`)
- Baseline, not reopened: MD-070 (`../MD-070-gap-004-adversarial-review/`), GAP-004, `EKS-44`
- Backlog: `EKS-47` (Γ undefined; EC never instantiated) — independently corroborated by this run
- Distinct, unaffected: `Sat_c`/`Eval_c` lineage; the G-01 lineage/provenance question

## MD-074 STATUS: EXECUTED. HARD STOP — no further phase automatically opened; MD-057–073 preserved
unchanged; GAP-004 not reopened. Smallest next research input, named, not authorized: a decision on
whether to construct new theory (a `Γ` definition, an `EC`-construction rule, a general `EvalReq`
procedure, a `Det_r` body) — per EKS-47's recommended dependency order, each disclosed as new theory
construction, not corpus recovery.
