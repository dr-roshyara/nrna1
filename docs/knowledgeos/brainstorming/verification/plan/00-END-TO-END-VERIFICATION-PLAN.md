# 00 — END-TO-END THEORY VERIFICATION PLAN (dependency-aware)

**VERIFY SESSION · 2026-08-29 · per directives 1556 §5 and 1600 §3.** Governing question: *after reading the corpus, what theory can actually be justified?* Method change in force: RECONSTRUCTION → FORMAL VERIFICATION → COMPUTABILITY → EMPIRICAL → DDD/ARCHITECTURE (no further contradiction-hunting for its own sake). Track discipline: A (verify) → B (reconstruct) → C (document).

## 0. Why this order (dependency argument)

Vocabulary gates definitions (a colliding term cannot be defined once); definitions gate derivations (no theorem with undefined symbols — quality standard); derivations gate computability (you can only compute what is defined); computability+statistics gate measurement (estimands before estimators); all of these gate state/transition canonicalization (TV-F-002/009/016 dispositions consume verified inputs); invarially the invariant system consumes the fresh-ID catalogue; DDD consumes stable language + invariants (162's own method); architecture consumes DDD; the book comparison consumes the reconstructed theory; the final document consumes everything. Every stage below names its prerequisites explicitly.

## 1. Stage table

Verdict vocabulary per stage: **PASS** (criteria met, evidence cited) / **FAIL** (counterexample or refutation) / **OPEN** (blocked, with named blocker). Multi-dimensional verdicts (math/comp/DDD independent) per 1600 §16/§23.

| Stage | What is verified | Sources | Prereqs | Tests | Failure modes | Status after Waves 1–3 |
|---|---|---|---|---|---|---|
| **V1 Corpus genealogy** | which docs are foundational/derivation/critique/revision/experiment/summary; source authority | V0 map; A3W 3-layer structure; kernel cascade | — | provenance tracing; supersession evidence | later-summary-as-authority | **LARGELY DONE** (V0 + 3-layer finding + TV-F-016 supersession analysis); residual: per-document genealogy table for phase_measure_theory (OPEN, low priority) |
| **V2 Ubiquitous language** | one term = one stable meaning; term↔formal-object correspondence | whole corpus via registers | V1 | collision/synonym/stability tests (plan 01) | silent reconciliation | **INITIAL REGISTER DELIVERED** (plan 01) — 12 verdicts COLLIDING, 6 SPLIT, 4 UNDEFINED |
| **V3 Definitions** | 15-question test per foundational definition | A3/A3W/A3X + sources | V2 (per-term) | well-formedness, domain/codomain, example/counter-instance | 10 defect classes (D-register) | **INITIAL REGISTER DELIVERED** (plan 02); full pass OPEN pending V2 canonical meanings |
| **V4 Foundational objects** | the actual primitive set via removal test | K0 + corpus kernels | V3 partial | removal test per primitive | kernel-sense conflation | **DONE as candidate** (K0, proof-backed; 5 kernel senses disambiguated); *establishment* blocked by missing middle |
| **V5 Derivations** | every major derivation reconstructed stepwise | A4/A5 + corpus chains | V3 | line-by-line reconstruction; counterexample search | plausible→proven | **CORE DONE** (17 proven-class, 7 refuted); remaining chains listed in plan 03 |
| **V6 Well-definedness & existence** | state space non-empty; equivalences are equivalences; functions exist | TV-F-007/010; 025-series | V3/V5 | model construction; axiom checks | vacuity; partial-as-total | **KEY RESULTS DONE** (M₀/M₁; liveness-free; ~-must-be-equivalence; ≺-must-be-DAG); instances OPEN (oracles) |
| **V7 Computability** | Input→Algorithm→Output per object; 5-class separation | A7 | V5/V6 | construct the computation or mark class | "computable" as slogan | **CLASSIFIED** (A7); constructions OPEN where N/guards/oracles missing (plan 04) |
| **V8 Statistics** | probability spaces, likelihoods, dependence, calibration | A6 + TV-F-014 | V3 | 13-question audit per formula (1549 §11) | familiar-formula fallacy | **FLOOR DONE** (Dempster refuted-as-applicable; likelihood pockets classified; calibration = none); pocket constructions OPEN (plan 05) |
| **V9 Measurement** | construct/estimand/scale/aggregation per metric | AM + TV-F-017 | V8 | scale-type + admissible-transformation tests | numeric≠measurable | **AUDIT DONE** (1 clean, 1 conditional); upgrades OPEN (plan 06) |
| **V10 Empirical** | executed vs conceptual; what PASS establishes | A9 + plan 07 | — | execution-evidence test | conceptual-PASS inflation | **REGISTER DELIVERED** (plan 07): 4 executed, 136+ conceptual |
| **V11 Invariants** | fresh-ID catalogue; joint satisfiability at intended semantics | TV-F-007/008 + 8 registries | V2/V3 | model exhibition per layer | ID collisions; vacuity | **UNDER-INTERPRETATION DONE**; consolidation + intended-semantics OPEN (plan 08) — first Track-B-adjacent task |
| **V12 State/phase/transition** | canonical state+transition or explicit no-canon verdict | TV-F-002/009/016/019 | V3/V11 + 4 governance dispositions | adjudication procedure; typed δ/τ mapping proof | frozen-label authority | **ADJUDICATED: NO CANON ESTABLISHED**; unification PROPOSED; "Phase" term itself UNDEFINED (plan 09 §4) |
| **V13 Kernel finalization** | verified kernel from V4+V5+V11+V12 | K0 | those stages | removal test re-run on verified base | premature declaration | OPEN (gated) |
| **V14 DDD** | contexts/aggregates derived from verified theory | A8; 162 method; 189/205 | V2/V11/V12 | derivation-direction test; classification vocabulary | Conway inversion | OPEN (plan 10) |
| **V15 Architecture** | traceability Theory→DDD→Component→Mechanism, 6-way classification | A8 seed | V14 | per-mechanism trace | architecture-as-proof | OPEN (plan 11) |
| **V16 Book comparison** | 8-verdict fidelity register | A10 stub + book | V13 (reconstructed theory) | claim-by-claim comparison | book-as-authority | DEFERRED BY DESIGN |
| **V17 Final theory** | Parts A–S from survivors | everything | V13–V16 + dispositions | five audits + epistemic audit | documentation pressure | FAR GATE |

## 2. Priority (load-bearing first)

Immediate executable next work (no governance needed): **(i)** V11 step-1 fresh-ID invariant consolidation; **(ii)** plan-03 residual derivation chains (049 reduction; 197 conservation classification; composition law under typing); **(iii)** plan-01/02 full passes for the top-20 terms/definitions. Governance-gated: the 8 dispositions (CHECKPOINT-WAVE3 §9) gate V12 canon, V13, V14.

## 3. Gap severity (1600 §19, adopted)

G-CRITICAL: identity instances (~,≺) · Zero-residence disposition · genesis/precedence devices — theory-defining. G-MAJOR: η reframing adoption · transition typing repairs · 𝒮_gap closure · adjudication operator. G-MODERATE: taxonomy tagging · SNF upgrades · UL splits. G-MINOR: notation table · codomain token drifts. G-ARCHITECTURAL: federation-vs-product · aggregate lists. G-COMPUTATIONAL: N construction · budget policies. G-EMPIRICAL: calibration · EXP-01 successor · EG-05 suites.

## 4. Standing rules carried into every stage

Four-way source discipline · no silent repair · no forced unification (1600 §21) · historical theory vs proposed reconstruction kept distinct (1556 §25) · multi-dimensional verdicts never collapsed · PASS/RATIFIED/TESTED = process evidence only.
