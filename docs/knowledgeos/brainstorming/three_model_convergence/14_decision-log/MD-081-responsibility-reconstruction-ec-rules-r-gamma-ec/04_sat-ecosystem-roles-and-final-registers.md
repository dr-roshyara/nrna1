# MD-081 §04 — `Sat` Ecosystem: Roles, Responsibility Disposition Register, Terminal Classification

## Role classification: evaluator / aggregator / acceptance-mechanism / consumer

Reorganizes already-verified facts (MD-078/079/080) around a new question: for each object in the
`Sat`-adjacent family, is its job to *evaluate* (produce a fresh judgment from evidence), *aggregate*
(combine already-known per-requirement judgments into a higher-level verdict), serve as the
*acceptance mechanism* (the policy that decides what counts as sufficient), or merely *consume* an
already-computed satisfaction value?

| Object | Role | Basis |
|---|---|---|
| `Eval` (T21 Part VI §6.15) | **Evaluator** (intended) | `K×E×P×EC×Γ→𝒱`; zero invocations anywhere — an evaluator role never actually exercised |
| `EvalReq` (T21 Part VI §6.17) | **Evaluator**, specialized to a requirement | prose-only, no codomain stated (codomain forced to `𝒱` by composition, `RECONSTRUCTED`) |
| `Det_r` (T21 Part VI §6.18) | **Acceptance mechanism** | `𝒱×EC→𝕊_sat`, "may be defined," contract-specific — this is the object actually meant to be the policy/threshold, not `EC.Rules` itself; `EC.Rules` is better read as `Det_r`'s own missing *input*, not a competing acceptance mechanism |
| `Standing(p)` (`M0127`, 2026-09-02) | **Evaluator**, complete and tested | `(S⁺,S⁻,R,P,Ctx,Cond)`, 12/14 scenarios preserved — the one evaluator in this family that actually works |
| `Sat_c`/`Eval_c` (`kos12`, ~09-02) | **Evaluator** (`Eval_c`) feeding an **acceptance mechanism** (`Sat_c:=value∘Eval_c`) | `Eval_c` explicitly self-described as "the primary object"; `Sat_c` a disclosed "candidate projection" — the same evaluator/acceptance-mechanism split `T21` itself attempted, executed successfully |
| `kos/inquiry.py`'s `Sat(K,r,E)` | **Evaluator + acceptance mechanism, fused** | kind-dispatched, 5 branches — a single function performing both jobs at once, the simplest working design in the whole family |
| `Sat(K,r)`/`Sat(K,r,Γ)` (birth/T5/Parts III/V/VI/T22) | **Consumer**, in every concrete instance | every actual use (`Δ_t`'s own definition, `Det(K,p,EC,Γ)`'s own aggregation) treats `Sat`'s value as already available, whether by fiat or by computation — `Sat` itself is the *interface*, not the worker, in the theory's own dominant usage pattern |
| `Det(K,p,EC,Γ)` (Part III Def 3.5, Part 21 Def 21.4) | **Aggregator** | `⟺∀r∈Req_p(EC,Γ):Sat(K,r)=Satisfied` — combines per-requirement `Sat` values, never itself evaluates evidence |
| `Δ_t`, `Zero` | **Aggregator/consumer** | set-builder and closure predicate over already-known `Sat` values |
| `Decision` (Part III Thm 3.2, Part 16 §16.4) | **Downstream consumer**, outside the `Sat` chain entirely | consumes `Determination`'s own output plus policy/authority — never touches `Sat` directly |

**Structural finding**: the theory's own repeated failure mode is not "no acceptance mechanism was ever
designed" — `Det_r`, `Sat_c`, and `kos/inquiry.py`'s own `Sat` are all genuine acceptance-mechanism
attempts. The failure is that **the evaluator feeding the acceptance mechanism was never successfully,
demonstrably wired up for `T21`'s own specific pair** (`EvalReq`→`Det_r`) — every *other* pairing in
this table (`Standing(p)` alone; `Eval_c`→`Sat_c`; `kos/inquiry.py`'s fused function) is complete and
working. This reframes `T21`'s own gap precisely: not "the theory lacks an acceptance mechanism," but
"`T21`'s own specific evaluator-to-acceptance-mechanism wiring is the one instance, among several
attempted, that was never completed."

## Responsibility Disposition Register (consolidated, per the mission's own §12)

| Responsibility | Origin | Historical carriers | Successor | Status | Evidence |
|---|---|---|---|---|---|
| per-instance evaluation → verdict | `T5`'s implicit `Sat` role; formalized as `Eval`/`EvalReq`/`Det_r` (T21) | `Standing(p)` (`M0127`, complete, tested); `Eval_c`/`Sat_c` (`kos12`, complete, tested); `kos/inquiry.py`'s `Sat` (complete, tested) | none demonstrated for `T21`'s own specific pair | **REDISCOVERED** (multiple independent, non-cumulative attempts) — not `ABANDONED` (the job itself was never given up, just never handed off) and not `GENUINE GAP` (the job is dischargeable) | MD-080 §02, this phase §04 |
| contract-level acceptance/policy rule (`EC.Rules`/`standard`) | `[00-51]`'s own `standard` field (`T5` lineage) | none formally developed anywhere — `Warrant`, `Assessment`, `Verdict` all ruled out (MD-080); broader vocabulary sweep this phase (§01) | none found | **UNRESOLVED**, pending this phase's own §01 search result | MD-080 §02, this phase §01 |
| proposition-level aggregation given `Sat` | `T5`'s implicit shape; formalized `Det(K,p,EC,Γ)` (T21 Part III) | independently re-derived within `T21` itself (Part 21 Def 21.4); `kos/inquiry.py`'s `Gap`/`Zero`/`Adequate` | `Det(K,p,EC,Γ)` itself, stable | **COMPLETED**, robustly | MD-078 §04 |
| `Determination⇏Decision` separation | `T21` Part III Thm 3.2 | ≥3 further independent T21-internal proofs (Parts 13/16/18/21) | n/a — never needed a successor | **COMPLETED**, most robustly of any item in this register | MD-078 §04 |
| `r`'s own requirement-sense structure | `T5`'s `[00-51]` (7-tuple) | T21 Part II Def 2.18 (abstracted); Part 13 (refined with `g(r)`); `kos/inquiry.py`'s `Requirement` dataclass | `SAME`/`REFINED`/`RELATED RESPONSIBILITY` throughout — no break | **REFINED**, coherently, once confirmed homonyms are excluded | this phase §02 |
| `Γ`'s own general-purpose context role | T21 Part II Def 2.15 (7-tuple) | Part VIII (narrower, identity-scoped); Part X/21 (narrower, inference-scoped) | three domain-specific subdivisions, none reconciled with the general anchor or with `EvalReq`'s own bare usage | **SUBDIVIDED**, but the specific usage this investigation concerns (`EvalReq`) remains unconnected to any subdivision | this phase §02 |
| `EC.Rules`'s own content specifically | T21 Part I/II | never developed anywhere | none | **GENUINE GAP** — see §03's own closing finding: intentionally deferred, never relocated | this phase §03 |

## Terminal classification, per component (final, this phase)

Unchanged from MD-078/080 for `Req`, aggregation, and separation (**A**); `EC`/`EC_t` (**C**); `r`
object-identity (**E**, though responsibility-relation is now clean/coherent, §02); `Det_r`/`EvalReq`
object-level (**D**) / responsibility-level (**B**, `REDISCOVERED` not `ABANDONED`, per this phase's
own sharper vocabulary). Refined this phase: **`Γ`'s own object-identity classification stands at `E`,
but its responsibility-relation classification is better described as `SUBDIVIDED` than as
unstructured competition** — a materially more informative finding than "unresolved" alone.
`EC.Rules`/`standard`/`AcceptanceCondition` — **`D` confirmed and sharpened by §01's own search**: not
"nothing found" but "multiple genuine attempts found (`Policy_Det`, `Admissible`, the "qualification
rule," "the evaluation rule `R`"), each independently and adversarially confirmed non-computable or
scoped to a neighboring responsibility (the verification lane's own executed `Policy`/`Apply`, an
action-authorization mechanism, not an epistemic-satisfaction one)." The richest, most precisely
bounded `D` classification in the entire investigation.

## Backlog assessment

**No new ticket filed.** Every finding this phase either deepens the evidence behind an already-tracked
decision (`EKS-48`'s own construction-authorization choice; `EKS-55`'s own recommendation to start from
tested material) or reuses already-established, independently-adversarially-confirmed results (the
verification lane's own `NG-1`/`Admissible`, `EG-2`/qualification-rule findings) rather than surfacing
a genuinely new, distinct, load-bearing problem. Per the mission's own explicit conservatism ("do not
create a ticket simply because an interesting historical finding exists"), this phase declines to file
one.

## Final report

**What was reconstructed**: the `EC.Rules`/`standard` responsibility's own full attempt-history across
every designated lane (§01); the responsibility-relation dimension for `r`/`Γ`, layered onto the
already-established object-identity matrices (§02); a complete, chronological `EC₀→EC₉` evolution
ledger (§03); a role classification (evaluator/aggregator/acceptance-mechanism/consumer) for the whole
`Sat`-adjacent ecosystem, plus the consolidated Responsibility Disposition Register (§04).

**What was already defined elsewhere**: the per-instance evaluation responsibility (`Standing(p)`,
`Sat_c`/`Eval_c`, `kos/inquiry.py`'s `Sat` — MD-080, reused); the proposition-level aggregation and
`Determination⇏Decision` separation (T21-internal, multiply independent — MD-078, reused); a
same-document illustrative template for `EC.Rules`'s own content (`Policy_Det`, new this phase, still
incomplete); a complete, executed policy-gate mechanism for a *neighboring* responsibility
(action-authorization — new this phase, different bounded context).

**What responsibilities moved or were abandoned**: `Γ`'s own general-purpose job is better described as
`SUBDIVIDED` across three narrower, domain-specific later contexts than as pure unstructured
competition (new finding, §02). `EC.Rules`'s own responsibility was never relocated — it remained,
throughout the corpus's full chronology, exactly what it was disclosed to be: deliberately deferred
(§03's own closing finding).

**What remains unresolved**: `r`'s and `Γ`'s own object identity (unchanged, `E`); `EC`/`EC_t`'s own
competing formulations (unchanged, `C`); `EC.Rules`'s own actual content (unchanged, `D`, now most
richly evidenced); `EvalReq`'s own `Γ`-argument connection to any of `Γ`'s three subdivisions (new,
named precisely this phase).

**Object-level vs. responsibility-level classification**: maintained separately throughout §§01–04, per
the mission's own explicit requirement; the two dimensions coincide for `EC.Rules` (`D`/`D`) and for
`r`'s confirmed homonyms (`UNRELATED_HOMONYM`/`NO DEMONSTRATED RELATION`), and diverge informatively for
`Γ` (`E` object-identity / `SUBDIVIDED` responsibility) and for `Det_r`/`EvalReq` (`D` object-level /
`B` responsibility-level, from MD-080).

**Terminal classification, A–E, per component** (not forced to one verdict): `A` — `Req`'s shape,
`Det(K,p,EC,Γ)` aggregation, `Δ`/`Zero` given `Sat`, `Determination⇏Decision` separation. `C` —
`EC`/`EC_t`. `D` — `EC.Rules`/`standard`/`AcceptanceCondition` (object and responsibility both), and
`Det_r`/`EvalReq` at the object level specifically. `E` — `r`, `Γ` (object identity). `B` — `Det_r`/
`EvalReq` at the responsibility level (MD-080, reaffirmed).

**Smallest genuinely remaining gap**: unchanged in kind from MD-079/080, now most precisely stated —
whether `Policy_Det`'s own illustrative template (Part VI §6.43) can be completed with an actual
"sufficient independent support"/"sufficient challenge" threshold semantics, without inventing a
mapping to any of `Γ`'s three competing subdivisions or to a source outside the corpus. No source
supplies this; it is not a research question further reading can resolve.

**Whether a new backlog ticket was necessary**: no — see Backlog assessment above.

## Verification

- No construction, mapping invention, or canonicalization performed anywhere in this phase.
- `Standing(p)` not adopted; `Sat_c`/`Eval_c` not adopted; no governance choice made between
  construction alternatives.
- No frozen artifact (MD-024–080) modified; MD-080 not reopened or rewritten.
- `resume.py`/`resume_mathematical.py`: run below, both must report `CONSISTENT`.
- `theory-extraction/` never accessed.

## MD-081 status: EXECUTED. HARD STOP.

`SAT-OPERATIONAL-CLOSURE-v1` remains unauthorized. No F3↔F4 bridging, no implementation. Next action,
named, not authorized: unchanged in kind from MD-080 — a human governance decision among the three
named options, now informed by the most complete evidentiary picture this reconstruction has produced
for this object family.
