# 01 — Ubiquitous Language Verification Register (initial pass)

**Stage V2 · first-class verification track (1600 §10).** Columns: Defined? · One meaning? · Stable? · Category (M math / E epistemic / D domain / G governance / A architectural) · Verdict. Verdicts: **STABLE** (one meaning, held) · **SPLIT** (several related meanings, distinction *stated* somewhere in-corpus) · **COLLIDING** (one word, unreconciled meanings) · **UNDEFINED** (used, never defined) · **BANNED-BUT-USED**. All entries source-traceable via the registers; no reconciliation performed.

| Term | Defined? | One meaning? | Category | Verdict + evidence |
|---|---|---|---|---|
| Knowledge | partially | **no — 7 senses** (flagged in-corpus, 201 §31) | E/D | **COLLIDING**; 201 §32 proposes the hierarchy (Information/Observation/Evidence/Proposition/Assessment/KnowledgeArtifact) — proposal only |
| Knowledge State K_t | many defs | no — 7 tuples | M | **COLLIDING** (TV-F-016: no canon); abstract-interface reading PROPOSED |
| System State S_t | several | no — 8 forms | M | **COLLIDING** (C-002) |
| Evidence | yes (several tuples) | no (tuple variants; residence [UNRESOLVED]) | M/E | **SPLIT+OPEN** — concept stable, structure/residence not |
| Observation | yes (several) | mostly | E/M | SPLIT (Source vs Semantic — distinction stated, Option 3); carrier 𝒪_t missing from state tuples (C-048) |
| Assertion / Assessment | both defined | **two half-corpora** | E/D | **COLLIDING** (C-009 schism — the highest-risk UL defect; Step-201's own one-word-one-concept rule violated corpus-wide) |
| Claim / Proposition | yes (Q14 P=(E,D,V); 201; 031) | roughly | M/E | SPLIT (structure variants); tolerable |
| Determination | yes (2 places) | **yes via bridge** | E/G | **STABLE-BY-BRIDGE** (v0.1 def = Supported→Accepted; 189 §8 gate — TV-F-019's corpus-licensed identification, INFERRED) |
| Truth | mostly negative defs | yes ("≠" doctrine consistent) | E | STABLE (as the thing everything is ≠ to) |
| Belief | boxed, then used-as-probability, then banned | no | E | **BANNED-BUT-USED** (C-031) |
| Confidence | anti-pattern doctrine + schema fields | no | E/A | **BANNED-BUT-USED** (K1/C-051) |
| Probability | model-relative doctrine consistent | yes at ratified level | M | STABLE (as guarded, model-relative tool; no space ever constructed) |
| Uncertainty | 5 taxonomies | **5 different constructs** | E/M | **SPLIT** (TV-F-015 reclassification: constructs differ; cross-references unqualified) |
| Unknown | multiple | no — 4+ operationalizations | E | **COLLIDING** (C-035); the laws (≠False/≠0.5/≠Null) are STABLE |
| Underdetermined | yes (031 §21) | yes | E/M | **STABLE** — and now theorem-backed (P-02) |
| Conflict | yes (predicates) | **2 concepts, 1 token** | E/G | **COLLIDING** (C-034: preserved epistemic state vs blocking/escalation state) |
| Zero | three layers | no | M/E/G | **SPLIT** (Z-KOS-001 principle [L1] ≠ Zero(K,EC) operator [L2] ≠ Zero-findings-as-data [residence CONTRADICTED, TV-F-002]); FA-4 already binds the naming — the third layer is the open one |
| Lord / Sārathi / Knower | defined (algebras; roles) | mostly | D/G | SPLIT (Lord naming collision is ruled historical [FA-4]; Knower = only 3-regime-invariant concept — most STABLE term in the corpus) |
| Kernel | 5 senses | no | M/D/A/G | **COLLIDING**, disambiguated only by this programme (K0 §1) |
| State | ubiquitous | no (domain/epistemic/governance/decision/execution/outcome — 203's federation IS the split statement) | M/D | SPLIT (stated); federation vs product conflict remains (C-027) |
| Phase | **no** | — | ? | **UNDEFINED** — the folder is *named* phase_measure_theory; "phase" never receives a definition anywhere in the extracted corpus; flag for plan 09 §4 |
| Event / Command / Operation / Transition | yes (Q15-rev; 204/205) | yes where late files govern | M/D | STABLE in late files (Command≠Event≠Transition; Operation≠Event≠Transition); early files predate the split |
| Decision / Action / Outcome | yes (201 §12–14) | yes | D/G | STABLE (Decision≠Action≠Outcome chain consistently held) |
| Policy | 2 signatures | no | G/M | **COLLIDING** (C-013) at signature level; concept stable |
| Authority | yes (relations; A6) | yes conceptually; order axioms missing | G/M | STABLE concept / UNDEFINED structure (⪰_C axioms) |
| Validation / Verification | distinguished (089 §13–14) | yes in late files | E/G | STABLE (late); early usage loose |
| Provenance / Lineage | yes; **explicitly distinguished** (162 I-18) | 3 provenance readings across registries (P1-check PAIR 3) | E/G | SPLIT with a residual COLLISION (claim→evidence vs origin-record vs revision-reason) |
| Context | multiple structures (7-field vs 6-field; "not metadata") | no | M/D | SPLIT (structure drift C-012; role stable) |
| Measure / Measurement / Metric / Score | partially | no | M | **SPLIT+UNDEFINED**: the corpus itself distinguishes measurement-theory ≠ measure-theory ≠ metric ≠ score in places, but `d` was called a metric then renamed, `Score` rejected then used in projections; measure theory absent entirely (deferred day 1) |
| Supported / Accepted / Committed / Contested / Rejected / Unresolved | yes (008) | yes within 008; compressed by R-3 | E | STABLE-per-layer; the chain↔Ω_A projection relation is the open mapping (TV-F-019) |
| Information | rarely defined | no | E | UNDEFINED (appears in the 201 hierarchy proposal only) |
| Entity / Identity | typed in several systems | concept yes; criteria no | M/D | SPLIT — identity *criteria* are the G-CRITICAL gap (TV-F-010) |

## Terminology map (Domain term → Formal object → Type → DDD → Architecture) — status
Deliverable per term; today only these rows are completable end-to-end: **Underdetermined** (Ω-fiber result → outcome value → epistemic status → assurance outcome) · **Determination** (via bridge) · **Decision/Action/Outcome** · **ladder statuses**. All other rows blocked by the COLLIDING/UNDEFINED verdicts above — which is precisely the V2-gates-V3 dependency the master plan asserts.

## Next actions (V2 full pass)
1. Resolve-or-record each COLLIDING term via the anti-reconciliation procedure (per-term provenance → supersession test → classification) — **candidates for canonical meaning exist for**: Assessment/Assertion (Step-201 freeze vs Q-series usage — a governance vocabulary decision), Conflict (two concepts → two terms proposal), Unknown (𝔼-family vs null-semantics family), Kernel (K0 §1 table ready).
2. UNDEFINED terms: demand definitions or mark permanently out-of-theory ("Phase", "Information").
3. Feed stable meanings into plan 02's full definition pass.
