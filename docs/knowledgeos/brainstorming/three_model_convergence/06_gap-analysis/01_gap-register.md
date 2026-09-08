# Gap Register — GA-001 through GA-053

Epistemic-status vocabulary used throughout (per the authorization's §3): `NOT SEARCHED` /
`NOT EVIDENCED` / `NOT PRESENT IN INSPECTED SOURCE` / `NOT PRESENT IN MODEL REGISTER` /
`MODEL-SPECIFIC ABSENCE` / `CROSS-MODEL NON-CONVERGENCE` / `INTERNAL CONTRADICTION` /
`UNDER-SPECIFIED` / `UNTESTED` / `UNRESOLVED` / `GOVERNANCE-BLOCKED` / `FORMALIZATION-BLOCKED` /
`REQUIRES NEW RESEARCH`. Blocking category vocabulary (per §13): `BLOCKING` / `NON-BLOCKING` /
`RESEARCH OPPORTUNITY` / `GOVERNANCE-BLOCKED` / `EVIDENCE-BLOCKED` / `FORMALIZATION-BLOCKED` /
`ARCHITECTURE-BLOCKED`. Every row cites its source directly; none is asserted without a pointer.

## Cross-model gaps (full treatment: `02_cross-model-gaps.md`)

| ID | Model(s) | Domain | Statement | Evidence | Status | Blocking |
|---|---|---|---|---|---|---|
| GA-001 | A/B/C1/C2 | Kernel | No two of the four independently-produced Kernel-candidate families are shown structure-preserving-equivalent, in whole or specific pairs | `05_cross-model/02` Row 1; `.../MD-021-phase-6.../02` Rows 1–3 | `UNRESOLVED` (specific level) / `CROSS-MODEL NON-CONVERGENCE` (categorical level: aggregate-style A/C1/C2 vs. operator-style B) | BLOCKING for unified formalization; NON-BLOCKING for within-model work |
| GA-002 | B/C1 | State | The C1↔B `K_t`/`Δ_t` notation convergence (Phase 6) has no component-level mapping attempted | `.../phase-6/02` Row 4 | `UNDER-SPECIFIED` | RESEARCH OPPORTUNITY |
| GA-003 | A/B/C1 | State | The "proliferation-without-convergence" pattern for epistemic-state tuples recurs 3× independently (A's Knowledge Vector, B's K_t, C1's own K_t) — still hypothesis-level only | `05_cross-model/04`; `.../phase-6/04` | `REQUIRES NEW RESEARCH` (to test, not merely observe, the hypothesis) | RESEARCH OPPORTUNITY |
| GA-004 | A/B vs. C1/C2 | Knowledge-definition | A and B's own registers show no dedicated "define knowledge" content; C1/C2 do, repeatedly | `.../phase-6/02` Row 8 | `MODEL-SPECIFIC ABSENCE` | BLOCKING only if formalization requires one cross-model-uniform "knowledge" definition; otherwise NON-BLOCKING |
| GA-005 | A/C1 vs. B/C2 | Representation | No formal representation/projection apparatus found in A or C1's own registers; B has a tested one, C2 an untested one | `.../phase-6/01`, Row 7 | `MODEL-SPECIFIC ABSENCE` | NON-BLOCKING |
| GA-006 | C1 vs. Phase 5A–5N (external) | Kernel-naming | "K-1" denotes at least two unrelated structures across this corpus's own history (C1's DDD aggregate, seq 0165/0167; the frozen Phase-5 8-primitive tuple, seq 0630); A's own per-file synthesis (seq 0219) appears to conflate them without raw-source basis | `.../phase-6/03`, `.../phase-6/04` | `INTERNAL CONTRADICTION` (naming, not content) | GOVERNANCE-BLOCKED — resolving it requires reopening the frozen 5A–5N track, out of scope for this stage |

## Model-A-internal gaps (full treatment: `03_internal-model-gaps.md` §A)

| ID | Domain | Statement | Evidence | Status | Blocking |
|---|---|---|---|---|---|
| GA-007 | Context | 4 non-identical treatments of "Context," never reconciled | `02_model-a_gita/03` CT-1 | `INTERNAL CONTRADICTION` | NON-BLOCKING |
| GA-008 | Invariant naming | `EKI-08` assigned to 2 different invariants (identifier collision only) | `02_model-a_gita/03` CT-2 | `INTERNAL CONTRADICTION` (cosmetic) | NON-BLOCKING |
| GA-009 | Provenance | 0808's 3 retracted formulas originate outside A's own 84-file evidence population | `02_model-a_gita/03` CT-3 | `NOT PRESENT IN INSPECTED SOURCE` | NON-BLOCKING |
| GA-010 | Kernel | 10 `alternative_minimal_kernel`-flagged files never cross-tested against the KR-SIM series' own falsification apparatus | `02_model-a_gita/03` CT-4 | `UNTESTED` | RESEARCH OPPORTUNITY (moderate priority for any A-sourced Kernel formalization) |
| GA-011 | Kernel | 4-way Kernel-structure family, `unresolved_equivalence` | `02_model-a_gita/02` §F, `03` UE-1 | `UNRESOLVED` | Component of GA-001 |
| GA-012 | State | 9+-variant Knowledge Vector family, `unresolved_equivalence` | `02_model-a_gita/02` §G, `03` UE-2 | `UNRESOLVED` | Component of GA-002/GA-003 |
| GA-013 | Knowledge-levels | Gyāna/Vigyāna vs. Object/Meta-Knowledge, unreconciled | `02_model-a_gita/03` UE-3 | `UNRESOLVED` | NON-BLOCKING |
| GA-014 | Governance/bridge | Trustee lens vs. Evidence/Provenance/Governance, `status: candidate` | `02_model-a_gita/03` UE-4 | `UNRESOLVED` | NON-BLOCKING |
| GA-015 | Epistemic axis | Para/Apara Vidyā as a possible third orthogonal axis, `status: candidate` | `02_model-a_gita/03` UE-5 | `UNRESOLVED` | NON-BLOCKING |
| GA-016 | Foundations | The Mithyā (reality/appearance) challenge to the whole enterprise, unresolved within A's own evidence | `02_model-a_gita/03` OQ-1 | `UNRESOLVED` — "the single deepest unresolved philosophical question this evidence base carries" (A's own characterization) | FORMALIZATION-BLOCKED only if a future formalization stage must commit to a reality/appearance stance; otherwise NON-BLOCKING background |
| GA-017 | Methodology | 0421's methodological lapse (bypassed non-collapse discipline) | `02_model-a_gita/03` OQ-2 | `UNRESOLVED` | NON-BLOCKING |
| GA-018 | Governance role | CON-02, "Lord"'s characterization (`Lord_R2` vs. `Lord_R5`) | `02_model-a_gita/03` OQ-3 | `UNRESOLVED` | NON-BLOCKING |
| GA-019 | Decision | Decision Readiness vs. Decision Sufficiency, merge-or-parallel undecided | `02_model-a_gita/03` OQ-4 | `UNRESOLVED` | NON-BLOCKING |
| GA-020 | Lenses | 10 proposed Chinese lenses, admission status never decided | `02_model-a_gita/03` OQ-5 | `UNRESOLVED`, likely permanent | NON-BLOCKING |
| GA-021 | Methodology | Meta-principle category (§B) and Chapter-4 capstone rule (§M) never explicitly cross-referenced by the corpus itself | `02_model-a_gita/03` OQ-6 | `UNRESOLVED` (observational) | NON-BLOCKING |

## Model-B-internal gaps (full treatment: `03_internal-model-gaps.md` §B)

| ID | Domain | Statement | Evidence | Status | Blocking |
|---|---|---|---|---|---|
| GA-022 | Kernel | A second, textually distinct "14-vs-12" cardinality tension (M0102), unreconciled with the already-resolved 13-vs-8 result | `03_model-b_mathematical/03` CT-1 | `INTERNAL CONTRADICTION` | RESEARCH OPPORTUNITY (moderate priority if B's kernel work is chosen as a formalization base) |
| GA-023 | Provenance/presentation | M0043's own "what v1.0 now proves" self-description is not corrected in the file itself after M0045's same-day audit | `03_model-b_mathematical/03` CT-2 | `INTERNAL CONTRADICTION` (resolved in substance, presentation gap remains) | NON-BLOCKING |
| GA-024 | Zero | "Zero more fundamental than Sat" claim, rejected one file later | `03_model-b_mathematical/03` CT-3 | Resolved — see `06_non-gaps...` | NON-GAP |
| GA-025 | Structure-First | "General theory" self-description downgraded; `Zero=closure(B_π)` rejected | `03_model-b_mathematical/03` CT-4 | Resolved in substance — see `06_non-gaps...`; the "which closure meaning" residue is GA-026 | Mostly NON-GAP |
| GA-026 | Closure | 7 non-equivalent candidate meanings of "closure," none selected | `03_model-b_mathematical/03` OQ-5 | `UNDER-SPECIFIED` | FORMALIZATION-BLOCKED for any work requiring a definite closure semantics |
| GA-027 | Corpus integrity | 2 timestamp/logical-order anomalies (M0105 vs. M0103/104; M0110/111 vs. M0109) | `03_model-b_mathematical/03` CT-5 | `NOT PRESENT IN INSPECTED SOURCE` (provenance anomaly, content not in tension) | NON-BLOCKING |
| GA-028 | State | 9+-variant K_t family, `unresolved_equivalence` | `03_model-b_mathematical/02` §G, `03` UE-1 | `UNRESOLVED` | Component of GA-002/GA-003 |
| GA-029 | State | M0125/M0126's 11-component K_t + 20 unreconciled invariants | `03_model-b_mathematical/03` UE-2 | `UNDER-SPECIFIED` | NON-BLOCKING |
| GA-030 | Composition | "Majority"/"intraframe-only" survivors, relationship to each other unaddressed | `03_model-b_mathematical/03` UE-3 | `UNRESOLVED` | NON-BLOCKING |
| GA-031 | Kernel | Kernel extension (epistemic-standards operators) never ablation-tested | `03_model-b_mathematical/03` OQ-1, `02` §P-3 | `UNTESTED` | RESEARCH OPPORTUNITY |
| GA-032 | Kernel | `KR-LINGA-YONI-2026-09-02` execution result missing from this evidence base | `03_model-b_mathematical/03` OQ-2, `02` §P-14 | `NOT PRESENT IN INSPECTED SOURCE` | EVIDENCE-BLOCKED |
| GA-033 | History/Audit | ClosureEvent irreversibility — kernel property or History/Audit property? | `03_model-b_mathematical/03` OQ-3, `02` §P-6 | `UNRESOLVED` | RESEARCH OPPORTUNITY (touches formalization of History/Audit boundary) |
| GA-034 | Composition | Full `KR-COMP-SEP-2026-09` writeup not located; only M0129's summary exists | `03_model-b_mathematical/03` OQ-6 | `NOT PRESENT IN INSPECTED SOURCE` | EVIDENCE-BLOCKED |
| GA-035 | Provenance | Part XIV vs. Part XIII continuation status of the theory rewrite series, unconfirmed within this evidence base's own digest pass | `03_model-b_mathematical/03` OQ-7 | `NOT SEARCHED` (this specific pass didn't reach the relevant files) | EVIDENCE-BLOCKED |
| GA-036 | Versioning | M0132's citation of prior artifacts vs. its own "v1.0" label, unusual but not clearly contradictory | `03_model-b_mathematical/03` OQ-8 | `UNRESOLVED` (minor) | NON-BLOCKING |
| GA-037 | Invariants | 20 core invariants (M0125/126) vs. axiomatic-capstone consistency, unaddressed | `03_model-b_mathematical/03` OQ-9 | `UNDER-SPECIFIED` | NON-BLOCKING |
| GA-038 | State | No corpus-internal, non-arbitrary way to select one canonical `K_t` structure from the 9+-variant family | `03_model-b_mathematical/03` OQ-10 | `UNRESOLVED` — the corpus's own most explicit statement of the state-selection problem | FORMALIZATION-BLOCKED — this is the single most consequential open item for any Stage-07 work targeting `K_t` |

## Model-C1/C2-internal gaps (full treatment: `03_internal-model-gaps.md` §C)

| ID | Domain | Statement | Evidence | Status | Blocking |
|---|---|---|---|---|---|
| GA-039 | Kernel | 8-way DDD Kernel-definition family — the largest such family found anywhere in this reconstruction | `04_model-c_kernel-ddd/02` §L, `03` UE-1 | `UNRESOLVED` | Component of GA-001 |
| GA-040 | State | 6-formulation `K_t`/`Δ_t` family within `phase_measure_theory/` alone | `04_model-c_kernel-ddd/02` §F | `UNRESOLVED` | Component of GA-002/GA-003 |
| GA-041 | Provenance | Main-corpus and math-lane epistemology-extraction series never cross-referenced by the corpus itself | `04_model-c_kernel-ddd/03` UE-3 | `NOT PRESENT IN INSPECTED SOURCE` | NON-BLOCKING |
| GA-042 | Kernel | K1-K8 never formally tested against its own proposed Kernel Boundary & Capability Mapping matrix | `04_model-c_kernel-ddd/03` OQ-1 | `UNTESTED` | RESEARCH OPPORTUNITY |
| GA-043 | Kernel | Six-vs-eight Pillar/Capacity count never reconciled | `04_model-c_kernel-ddd/03` OQ-2 | `UNRESOLVED` | NON-BLOCKING |
| GA-044 | Governance | `ADR-KOS-KERNEL-001` PROPOSED, never formally ACCEPTED | `04_model-c_kernel-ddd/03` OQ-3, `02` §N P-4 | `GOVERNANCE-BLOCKED` | GOVERNANCE-BLOCKED |
| GA-045 | Methodology | McGinn adversarial-lens-vs-literal-architecture tension, self-flagged and unresolved | `04_model-c_kernel-ddd/02` §E, `03` CT-2 | `INTERNAL CONTRADICTION` | RESEARCH OPPORTUNITY — directly relevant to GA-001's own categorical finding (a recurring tool-vs-architecture confusion) |
| GA-046 | Kernel | `S_Kernel=(D,E,S,T,U)` vs. Knowledge Ātma Kernel `𝒦_core`, never reconciled | `04_model-c_kernel-ddd/03` OQ-5 | `UNRESOLVED` | NON-BLOCKING |
| GA-047 | Provenance | `kernel/` vs. `phase_measure_theory/` true chronological/content relationship, never established | `04_model-c_kernel-ddd/03` CT-4/OQ-6 | `UNRESOLVED`, corpus-acknowledged | RESEARCH OPPORTUNITY — directly relevant to GA-002's own C1↔B convergence finding |
| GA-048 | Kernel | Seq 2330's own Kernel definition never reconciled against seq 2322/2323's C1 candidates | `04_model-c_kernel-ddd/02` §M, `03` OQ-7 | `EVIDENCE-BLOCKED` — 2322/2323 are `meta_research`/`cross_model`-tagged, not C1/C2 evidence | EVIDENCE-BLOCKED |
| GA-049 | Transitions | Reiter negative-test result's underlying blockers (`Poss≠Qualify`, `δ` as successor-state) never resolved | `04_model-c_kernel-ddd/02` §I, `03` OQ-8 | `UNRESOLVED` | FORMALIZATION-BLOCKED for any Golog/RGolog-style formalization |
| GA-050 | Classification | The C1/C2 classification boundary may not track a clean content split (MD-007's falsifier, triggered by the fuller population) | `04_model-c_kernel-ddd/03` (C1↔C2 relationship section) | `GOVERNANCE-BLOCKED` — explicitly deferred to a separately-authorized classification-review phase by Phase 4's own authorization | GOVERNANCE-BLOCKED |

## Formal/empirical/reconstruction-wide gaps (full treatment: `04_formal-and-empirical-gaps.md`)

| ID | Domain | Statement | Evidence | Status | Blocking |
|---|---|---|---|---|---|
| GA-051 | All models | No formally verified (independently proof-checked) result exists anywhere across A/B/C1/C2 — every "verified"/"proven" claim rests on self-audit or ablation-testing | `04` (this stage's own synthesis) | `UNDER-SPECIFIED` (reconstruction-wide) | FORMALIZATION-BLOCKED for any claim requiring Level-5 formality |
| GA-052 | B/C1 | The K_t/Δ_t notation convergence (GA-002) must not be treated as statistical replication — it is a citation-absence finding on 2 data points, not a tested hypothesis | `.../phase-6/00`, `04` | `UNDER-SPECIFIED`, explicit anti-overclaim guard | Governs how GA-002/GA-003 may be cited downstream |
| GA-053 | C2 | n=1 population — which conclusions are/are not possible | `04_model-c_kernel-ddd/00` | `EVIDENCE-BLOCKED` for any population-level claim; direct single-file claims remain legitimate | See `04_formal-and-empirical-gaps.md` for the explicit possible/impossible split |
