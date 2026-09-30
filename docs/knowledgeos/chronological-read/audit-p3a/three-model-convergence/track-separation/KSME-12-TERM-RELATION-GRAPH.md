---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-11-TERM-DISCOVERY-REGISTRY, KSME-12-TERM-DISCOVERY-REGISTRY]
derived_from: [all KSME-11 and KSME-12 fork reports]
cross_track_dependency: none
---

# KSME-12 — Term Relation Graph

Source-supported relationships only; every edge cites its source. This extends (does not replace) the
relationship list already recorded informally in `KSME-11-TERM-DISCOVERY-REGISTRY.md`.

## Observation-layer cluster

- `Sañjaya-layer --formalizes--> W --Ω--> O` (`20260826-151244`, `-160322`)
- `H-K16 --restates--> Sañjaya-layer` (`R5-RESEARCH-TO-ARCHITECTURE-REPORT`)
- `I-O --derives-from--> H-K16` (`REFINED-STEP-287-INVARIANTS`)
- `K_t's "Observation" primitive --is-equivalent-to--> O (the codomain of Ω)` (`30-THE-EIGHT-PRIMITIVES`)
- `K_t's "Observation" primitive --is-distinct-from--> observation function o:E→Y` (this pass's central finding)
- `G-102 --independently-confirms--> the Observation/𝒪 notation collision` (file 31, predates this investigation's own discovery of the same issue)
- `Ω --has-no-body--> [same shape as Qualify/G1]` (structural resemblance noted, NOT merged per `§6b`'s rule)

## Congruence/quotient cluster

- `258.9 --defines--> congruence` `--is-restated-by--> 258.30 (commuting diagram)`
- `= (bare equality) --is-falsified-by--> step-288/06's executed counterexample --as-a-congruence`
- `≅_I, ≅_P, ∼_H, ∼_F --are-candidate-instances-of--> ≡_K/∼_B, none adequate` (`step-288/04 §12-14`)
- `∼_F --is-decidable-but--> ∼_F --is-insufficient-for--> behavioral-equivalence` (`258.11`)
- `q:K→K/≡ --scores--> 0 of 8 properties` (`step-288/04 §13`)

## Contradiction cluster

- `A+¬A --is-a--> state (one of 4 epistemic states)` (Step 32 §32.33)
- `Conflict(p) --is-a--> predicate-on-state` (Step 60 §60.5)
- `Merge --produces--> Conflict(p) [PASS-verified]` (Step 60 §60.27)
- `"Merge≠Resolve" --governs--> Merge/Conflict(p)/Resolve relationship` (Step 60 §60.22)
- `Contr(e,c) --is-proposed-as--> transition-precondition` (`step-292/04`)
- `Contr(e,c) --borrows-from--> KR-CONTR-EVAL (independent, executed lane)` (`theory-08`)
- `Contr(e,c) [blanket form] --is-refuted-by--> Merge/Conflict(p)'s PASS-verified test` (this pass's finding)
- `Operational/Epistemic split --potentially-reconciles--> Contr(operational) vs. Conflict(p)(epistemic)` [DERIVED, not source-stated]
- `DECISION-02 --blocks--> δ-construction AND composability` (`theory-08 §5`, new dependency)
- `theory-08's contradiction/boundary entanglement --relates-to--> Qualify/G1's boundary problem` [flagged, not resolved]

## Semantic-boundary cluster

- `Qualify/G1 --is-formalized-by--> D285-6`
- `Φ/G-109 --is-formalized-by--> file 38`
- `§6b's rule --forbids-merging--> Qualify/G1 and Φ/G-109` (file 38, explicit)
- `§6b's rule --was-first-applied-to--> Terminus vs. Acknowledgment (two Qualify readings)` (`REFINED-STEP-286`)
- `AP-1 (authority principle) --is-borrowed-by-analogy-for--> Qualify's reframing` (`09-GAP-UPDATE-FROM-CAVELL`) — `--is-distinct-from--> a genuine third boundary instance` (this pass's finding: analogy only, not a peer gap)
- `Φ/G-109 --is-never-referenced-again-after--> file 38 / 00-INDEX.md` (confirmed permanent, 41-hour+ gap, zero forward hits)

## Kernel-ambiguity cluster (new this pass)

- `G-108 --proposes--> Kernel_engineering ≠ Kernel_epistemic` (file 37, `[HP]`)
- `MD-006 --independently-corroborates--> G-108's split` [`SOURCE-CLAIMED-VIA-CITATION` only — `three_model_convergence` never opened directly]
- `Kernel_epistemic --is-the-object--> KSME-04 through KSME-12 have been reconstructing` (this investigation's own scope, confirmed by this pass, not previously made explicit)

## Cross-cutting methodology relationships

- `Mandatory Chronological Continuity Rule --enabled-discovery-of--> the 6-day Sañjaya thread, the 41-hour Φ gap's permanence, the Step-277→278-280 forward chain` (multiple KSME-11/12 findings)
- `Mandatory Comprehensive Term Discovery Rule --enabled-discovery-of--> ≅_I/≅_P/∼_H/∼_F, DECISION-02, G-108` (this pass's own new findings, none of which were seed terms)
