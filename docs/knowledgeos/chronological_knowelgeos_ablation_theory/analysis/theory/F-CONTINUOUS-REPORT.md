# F-Series continuous research report (append-only; one compact block per task)

> Research record, not canonical. Each block: TASK · QUESTION · FROZEN HYPOTHESES · EVIDENCE · SUBAGENT RESULT · REVIEW RESULT · DISAGREEMENTS · RECONCILIATION · MODEL IMPACT · NEXT. All subagents are Claude, so independence is SECONDARY. Full records in `LOOP-*/`; governance in `F-GOVERNANCE-LOG.md`.

## 1am-2 / 1am-2b (F-LOG-0146)
- **Q:** what determines RAISE: authority, target, their interaction, or other?
- **H:** M_E / M_A / M_T / M_AT / M_C.
- **Ev:** S1–S6 + S7 (R-39).
- **A:** M_E and M_T falsified (R2).
- **Review:** the same (R2); in R1 the reviewer undid A's only deciding pair.
- **Disagreements:** E25 legality vs pending (source interpretation) · units (formal reasoning).
- **Reconciliation / impact:** M_E ✗ and M_T ✗ (conditional); the M_A / M_AT survivors were untested; H-X post hoc.
- **Next:** 1am-2c.

## 1am-2c (F-LOG-0147)
- **Q:** the actor of E02?
- **A / Review:** UNK / UNK.
- **Impact:** undetermined; the line was closed; a designed observation was recorded.

## 1ak-3a (F-LOG-0148)
- **Q:** is the parked → adopted Execution Contract an evidence witness?
- **A / Review:** NOT A WITNESS (kind, target).
- **Impact:** evidence stays WEAK; a facet observation was recorded post hoc.

## 1ak-3b (F-LOG-0149)
- **Q:** unit of evidence; H-X.
- **A:** U none. **Review:** U = [E1].
- **Disagreement:** substantive (kept, not averaged).
- **Impact:** U unresolved; `e` overloaded (slices / occurrences / contexts) → H-E2.

## 1ak-3c (F-LOG-0150)
- **Q:** evidence as a vector.
- **Result:** falsified in all variants, but the strict variant hinges on E01 = 1.
- **Impact:** read of F-LOG-0146 corrected; G_RAISE = e ≥ θ ∨ exception survives.

## 1ak-3d (F-LOG-0151)
- **Q:** E01's decision-time evidence.
- **A / Review:** 1 (inference strength).
- **Impact:** G_RAISE ✗; survivors M_A / M_T / M_K / M_AT (+X), confounded.

## 1am-2d (F-LOG-0152)
- **Q:** can the confound be broken?
- **A / Review:** not broken.
- **Impact:** observationally equivalent → the RAISE branch **STOPPED**; H-K-choice (raise-kind as a choice) is an open hypothesis; designed observations were recorded.
- **Next:** 1am-3.

## 1am-3 (F-LOG-0153)
- **Q:** does role conformance or object kind separate the R-86 ADOPT (performed) from the R-91 ADOPT (held)?
- **H:** M_C / M_K / M_KC / M_U.
- **Ev:** R-71, R-81, R-86, R-91.
- **A:** conformance STRICT, kind NONE. **Review:** the same.
- **Disagreements:** D1 (coding: P's conformance partly stated) · D9 (source interpretation: finer-grain kind).
- **Reconciliation / impact:** conformance WEAK (1 witness, fragile); M_C over M_K at the coarse grain. The sealed expectation failed.
- **Next:** 1am-3b (typed-header kind check).

## 1am-3b (F-LOG-0154)
- **Q:** is kind equal at the typed-header grain?
- **Verifier:** Y differs; the separation is tied to Event D; the "Y = act" alternative stands.
- **Defect:** R-86 was omitted from the bundle (disclosed; the issuer-vs-actor comparison is void).
- **Impact:** the witness is strict only at the coarse grain → conformance WEAK, grain-conditional; H-KC unsupported. **Kind grain is itself a model-integrity question.**
- **Next:** a same-Y conformance pair.

## 1am-3d (F-LOG-0155)
- **Q:** a second conformance cluster at a constant header type (Acceptance)?
- **Ev:** R-66, R-67, R-71, R-93.
- **A / Review:** no witness.
- **Disagreement:** formal reasoning, "does not separate" → "untestable".
- **Impact:** conformance WEAK, grain-conditional; non-acceptance is stated via state → the conformance branch **STOPPED**; a designed observation is recorded.
- **Next:** formal minimization.

## 1an (F-LOG-0156): formal minimization, r1 → r3
- **Q:** the smallest per-operation guards and predictive state.
- **Models:** M0 / M1 / M2 / M4 / MF / MO; variants BASE / REV / REV-TYPE.
- **Worker:** a deterministic script.
- **Reviews:** r2 found consistency-by-ignorance (substantive) → r3 separating semantics; r3 recomputation matches.
- **Disagreements:** 30 classified; defects disclosed (r1 labels, r2 D1/D3, r3 D-N2, SUPERSEDE scope).
- **Result:**
  - guard union {a,c,e,h,k,s,t} (model-relative);
  - r, x redundant (model-conditional);
  - operation UNTESTABLE;
  - bisimulation 250 → 80;
  - **only START{s} generalizes across clusters.**
- **Impact:** formal minimality ≠ a generalizing theory.
- **Next:** 1an-r4, a frozen generalization instrument (LOCO).

## 1an-r4 (F-LOG-0157)
- **Q:** do the guards generalize (recurrence, LOCO, disjoint support)?
- **Worker:** a frozen script (every sealed expectation matched).
- **Review:** recomputation matches; the criterion is not faithful; LOCO tests the refit, not the guard.
- **Reconciliation / impact:**
  - START **UNCLEAR** (s: formal recurrence, 2 disjoint; LOCO uninformative);
  - RAISE, ADOPT, SUPERSEDE **DO NOT GENERALIZE** (RAISE at chance, below the majority baseline);
  - 4 operations **INSUFFICIENT**;
  - operation **UNDETERMINED** (MCR withdrawn).
  - **No guard is yet shown to generalize by prediction.**
- **Next:** 1an-r5, the {s}-only diagnostic.

## 1an-r5 (F-LOG-0158)
- **Q:** does START {s} predict held-out clusters?
- **Worker:** 8/8, 0 wrong ("predictive").
- **Review:** recomputation matches, but:
  - the baseline was structurally capped (my flaw);
  - the guard is near-definitional (untrained 10/10);
  - knife-edge under the circularity filter;
  - 2 episodes.
- **Impact:** START-s is **coding-consistent, not a generalization finding**; no guard generalizes by prediction; the branch **STOPPED**.
- **New hypothesis H-AS:** analytic (definitional) vs synthetic guards. Prediction tests are meaningless for analytic ones; premise-independence tests are needed instead.
- **Next:** H-AS classification.

## 1ag run B2 (F-LOG-0159)
- **Q:** does a fresh Claude coder reproduce the coding; is the main bias confirmed; why V1/V4?
- **Ev:** Gate 1 + reserve bundles (41 rows); three-way scoring with the unchanged scorers.
- **Review:** fidelity OK.
- **Findings:**
  - B1≈B2 (correlated priors);
  - the main bias is bidirectional (MAIN right on V3; the blind coders share errors);
  - the coverage gap is enumeration;
  - V1/V4 are manual-limited;
  - VIOLATED untested.
- **Impact:** the instrument is stable within the family but under-specified.
- **Next:** a human decision on manual r3; a non-Claude coder is still needed.

## 1ao (F-LOG-0160)
- **Q:** analytic vs synthetic guards.
- **A / Review:** agree 10/11 (REGISTER/k disputed, kept).
- **Result:**
  - ANALYTIC: START/s, START/t, ADOPT/c (premise independent in only 2/11 START events);
  - SYNTHETIC: ADOPT/a, AUTH-IMPL/a, OPEN-WORK/k, RAISE/e, ASSIGN-ID/h (all contrasts weak);
  - UNKNOWN: RAISE/t, SUPERSEDE/k.
- **New:** a hidden status variable in AUTHORIZE-IMPL.
- **Impact:** the theory is constitutive definitions with weak premises, plus weakly evidenced synthetic rules.
- **Next:** 1ap.

## 1ap (F-LOG-0161)
- **Q:** issuer vs status for ruling force.
- **A / Review:** agree.
- **Result:** M_issuer ✗ (non-circular), M_adopter ✗, M_status UNDETERMINED (consistent; one value circular).
- **Impact:** authority is not a direct guard; the status chain is attributed but single-episode.

## 1aq (F-LOG-0162)
- **Q:** START premise independence.
- **A / Review:** 0 INDEPENDENT; **10/11 events are permission statements, not acts**.
- **Impact:** target-indexed state is downgraded (a property of permission statements); the corpus is mainly deontic.
- **Next:** 1ar, the act-attestation audit.

## 1ar (F-LOG-0163)
- **Q:** which legality events are attested acts?
- **Result:**
  - the norm-making operations are attested; START and the register-meta operations are permission statements;
  - on attested acts only **authority** keeps 2 strict pairs;
  - kind and state → NOT DEMONSTRATED; e and c WEAK.
- **Impact:** the empirical core is {authority}; H-DEONTIC is supported; the schema must separate norm statements from act observations (a human decision).

## Manual r3 (F-LOG-0164)
- **Q:** does r3 improve inter-coder agreement?
- **Design:** C1 / C2 under r3 vs B1 / B2 under the old manual.
- **Result:** trade-off (V1/V4 up, V2/V3 down); the V2 drop comes from G7 wording; V3 from G6 plus a gap; ops = 1.0 is shared priors.
- **Impact:** the instrument is improvable, not converged; the upstream schema issue (norm vs act) dominates.

## 1as / 1at / 1au (F-LOG-0165..0167)
- **1as:** the R-90 assignment contrast is POSSIBLE, 1 cluster; h is not demonstrated.
- **1at:** RAISE evidence is confounded; **the sample omitted not-promoted items at 3 slices** → e NOT DEMONSTRATED.
- **1au:** schema-r2 package PASS_WITH_LIMITATIONS; narrow necessity; dual nature; a minimal alternative; a pilot option. → **STOP for the human.**
- **Empirical core:** authority only (2 pairs, both on the norm/act boundary).

## 1av pilot (F-LOG-0168)
- **Q:** can the norm/act distinction be coded reliably?
- **Result:** 1.000 agreement, but degenerate and cue-driven (the event ids leaked interpretations: a design defect).
- **Established:** the two-level form is codable; source_act is needed.
- **Not established:** reliability; B's sufficiency.
- **Next:** 1av-b, the de-cued pilot.

## 1av-b de-cued pilot (F-LOG-0169)
- **Q:** does norm/act coding survive cue removal?
- **Result:** P3~P4 is still 1.000 (degenerate); **11/36 events are individuated only by description**; kind's pairs differ in zero fields; e's pair is description-individuated; authority must be re-verified.
- **Impact:** span-level individuation is required under A or B; the measurement problem is two layers deep.
