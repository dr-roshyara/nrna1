# Hypothesis discrimination 04 — L205, the H6 family, H-PLANES, and the lattice across all cases

| | |
|---|---|
| Status | research record; not canonical; authority none |
| Case | `.claude/sessions/2026-08-04.md` L205–L215 (`6309c2e3…`); spec frozen before the read `d0daa60c6` (`6276589a…`, force UNKNOWN); observation `L205-CASE/OBSERVATION.json` (`44f918e0…`); result `RESULT.json` (`399f6a9d…`) |
| Log | F-LOG-0116 |

## 1. L205 source facts (SOURCE-FACT; INTERPRETATION marked)
| Act | Source | Route · operation · effect |
|---|---|---|
| A1 L208 | "The era-closing directive executed: META-PRINCIPLE FREEZE: no new meta-principles/heuristics/watches minted from here … **Counting and evidence intake continue; discovery of meta-things stops.**" Issuer not named; no evidence basis | directive (INTERPRETATION: RULING-like) · FREEZE · normative (restricts future standing changes) |
| A2 L209 | the protected sentence "preserved verbatim" + six outcome questions | — · PROTECT-TEXT · normative (evaluation criterion) |
| A3 L210 | "Candidate schema reclassified as a KNOWLEDGE LIFECYCLE (not watch tooling) — **identity upgrade, same gate**" | — · RECLASSIFY · identity; gate unchanged |
| A4 L211 | "architectural evolution REVERSIBLE (**precision earns; lapse removes**)" | — · declares reversibility · persistence |
| A5 L212–213 | the learning loop "**designed, not exercised**"; "The review era is complete. The engineering era owns the answers." | — · phase transition |

## 2. Effect on H4 — untested (third attempt)
The section concerns meta-principles but neither cites nor applies an R-39-form bar, so Applicability(I-R39) is not source-supported → force stays **UNKNOWN** (not promoted to IN; no H4 test manufactured). r2: no elimination under any norm reading. **Finding:** force is the least observable attribute in session-log sources; three reads (L216, L493, L205) produced no IN-force act.

## 3. Effect on the H6 family (H6-R route · H6-K kind · H6-O operation · H6-E effect)
- A1: route (directive), kind (meta-rule), operation (FREEZE), effect (normative) all sit on the "direct normativity" side; no evidence recorded → consistent with all four; **confounded again**.
- A3 separates **kind from gate**: identity changes, gate stated unchanged. Under H6-K, a kind change *may* change the gate; here it does not. **Weak evidence against the strict form of H6-K** (reading-dependent: whether "watch tooling" and "knowledge lifecycle" would be differently gated is not stated).
- **R-39 re-read under the family (MODEL-DERIVED, development data):** R-39 is the one known case where **route and operation come apart** — a register RULING that raises a methodology principle's standing — and it is the only ruling that records an exception. H6-O predicts the bar/exception for RAISE-STANDING; H6-R predicts no need for it. Under permitted-set semantics nothing is eliminated (H6-R permits an unneeded exception), but the earlier "tension" is explained only by H6-O.

## 4. Effect on H-PLANES — supported (three acts, two sources); dimensions not settled
- L493: execution OPEN while governance CLOSED (SOURCE).
- L205-A1: the **standing plane** frozen while the **evidence plane** explicitly continues ("counting and evidence intake continue").
- L205-A3: the **identity/kind coordinate** changes while the **gate** is unchanged.
- L205-A4: standing is **non-persistent** — removed by lapse.
- Every one is an act that changes some coordinates with others stated unchanged: **transitions factor**. But the named dimensions differ between sources (L493: execution / governance / findings; L205: standing / evidence / identity), so *which* dimensions exist is open. Do not unify them.

## 5. The hypothesis lattice, evaluated over all cases read (MODEL-DERIVED)
| Dimension | Class | Status | Basis |
|---|---|---|---|
| B state | B1 one linear lifecycle | **ELIMINATED** | L493: one item CLOSED and OPEN at once in named planes (a scalar over a product set is only a relabelling of B2/B3; the substantive claim refuted is a single linear lifecycle with non-factoring transitions) |
| | B2 independent axes / B3 planes | alive; **factoring observed** (4 acts) | L493, L205-A1/A3/A4 |
| | B4 event-sourced (path-dependent) | untested | needs two histories to one state |
| C evidence control | C5 authority alone decides | **ELIMINATED** | L493 minimal pair: same authority, same day, one act without evidence, one refused for evidence |
| | C1 universal evidence-before-P | alive (open world) | P1 refutes only under the VAL / QUAL∧VAL readings |
| | C2 route (H6-R) | alive, confounded | — |
| | C3 kind (H6-K) | alive, **weakly disfavoured** | L205-A3 "same gate" |
| | C4 operation (H6-O) | alive, **weakly favoured** | R-39 (development) |
| A distinguisher | A5 authority | eliminated as sole distinguisher | as C5 |
| | A6 force | untestable so far | no IN-force act observed |

## 6. Remaining observational equivalences
- H6-R ≡ H6-E on every case read; H6-R/H6-O differ only on R-39 (non-eliminating).
- H1–H5 as in note 02; H4 untested; under the QUAL reading H1 ≡ H2 for low-rung cases.
- B2 vs B3 (axes vs authority-owned planes) not separated.

## 7. Strongest next falsifier
A case where **route and operation come apart**, with the evidence basis recorded:
- a RULING that raises standing **without** an exception or bar → refutes H6-O, supports H6-R;
- a PROMOTION that creates a norm with no bar → refutes H6-R.
**Observability lesson (3rd occurrence):** session-log summaries rarely record evidence bases or force. The rulings register (rows carry authority, date and often basis) is the better-observable source for route-vs-operation splits.

## 8. Recommendation for formal modelling (next phase; not started)
Smallest model expressing the observed distinctions: **M = (O, K, R, F, A, S, E, δ)** with
- S = product of coordinates {standing, execution, identity, evidence} (declared, revisable);
- each operation o ∈ O carries a **frame**: the coordinates it may change (FREEZE → standing-capability; RECLASSIFY → identity; RAISE-STANDING → standing; evidence intake → evidence);
- δ guarded by route/operation-specific evidence predicates (the H6 family as alternative guards).
Checks (explicit-state BFS, deterministic): frame preservation against the 9 coded acts; reachability of Promoted ∧ ¬Validated (P1 witnesses it); reversibility (L205-A4 lapse); two exit routes — counter-evidence (L216) vs lapse (L205) — and whether they conflict; path dependence (B4); minimal distinguishing traces among the guard alternatives. **Pre-register M before building; the 9 coded acts are development data; any claim needs a new case.**
