# KOS-SNF P5 — Semantic Competition Refinement · PLAN (EP-01, awaiting approval)

> **Status:** ✅ **APPROVED WITH AMENDMENTS (HPA, 2026-08-22) — EXECUTED, COMPLETE.** Steps 6–16 done; results in `docs/knowledgeos/brainstorming/KOS-SNF-RESEARCH-P5-COMPETITION-001.md`. Amendments applied: **Q-1** OQ-4 remains unauthorized · **Q-2** honest reduction accepted · **Q-3** `DOMAIN_CONDITIONAL` deferred · **Q-4** templates + independently declared hand-written gold · **D-3 strengthened** to the four-way distinction (EXPRESSED / EXPRESSED+NEGATED / NOT_EXPRESSED / UNKNOWN).
> **Commission:** HPA, 2026-08-22 — *"Claude Code Commission — P5 Semantic Competition Refinement"* (23 sections + 16-step execution order).
> **Mission (verbatim-in-substance):** determine, through a controlled reproducible experiment, **how the candidate semantic mechanisms behave** with respect to meaning preservation · convergence · false convergence/collapse · distinction preservation · transformation stability · appropriate abstention · calibration · disagreement · false consensus. **Not** to redesign KnowledgeOS, select an SNF winner, or introduce SNF into the kernel.

---

## 1 · Objective

Make the **research** stronger — not the architecture larger. Concretely: repair the pilot's identified defects, then run a stratified 1,000-case competition with a per-policy SNF-E arbitration sweep, and report a measurement **vector** plus an honest account of what remains unknown.

**The prohibited conclusion, stated up front:** *"SNF-C = 93%, therefore SNF-C is the KnowledgeOS semantic engine."* The deliverable is a Pareto/failure-mode map, not a winner.

## 2 · Governance state and the authorization delta

| Item | State entering P5 |
|---|---|
| Reference Architecture v1.1 | **r4 · PROPOSED · NON-AUTHORITATIVE** — **not to be modified by this work** |
| Constitution v1.0 | **FROZEN** |
| Expression↔Meaning Port Contract | authored; **LA Review 01 PASS**; F-1…F-5 amendment candidates **HPA-gated** |
| Post-Research Architectural Review | **v1.1 NO CHANGE**, HPA-accepted |
| Prior stopping point (c34d05e9) | *no further SNF formula refinement · do not modify v1.1 · do not implement the SNF competition* |
| **This commission** | **lifts the research half of that stopping point** — it explicitly directs d_SNF refinement, the SNF-E sweep, and the 1,000-case competition. The **architecture half stands unchanged** |
| OQ-4 | ⚠️ **see Q-1 below** — my reading is that this commission authorizes the **toy-world simulation competition (KOS-SNF)** and leaves **OQ-4 (the real-corpus KOS-SCB experiment) still unauthorized**. Confirm or correct |
| SNF-A…E, SNF-N | **research mechanisms**, never kernel components |

**The invariant of this phase, preserved in code and prose:** `candidate → measurement → evidence → Port Contract/Governance → KnowledgeOS`, never reversed. `Interpretation Candidate ≠ Identity` · `high confidence ≠ Identity` · `mechanism agreement ≠ Identity` · `similarity ≠ Identity` · `canonical representation ≠ Identity`.

## 3 · What the pilot established (read, not reconstructed)

Apparatus **VALID**, research question **deliberately open**. Corpus 100 cases (5 × 20: equivalent · distinct · transformation · adversarial · ambiguous), gold `dd03c0ba…`, results `1f639703…`, metrics `7170e2cd…`, seed `20260822`, byte-reproducible across subprocess runs.

| Mechanism | C/80 | NC | T | A | H | Brier | ECE |
|---|---|---|---|---|---|---|---|
| SNF-A | 80 | 0 | 1.00 | 0.00 | 0.06 | 0.0225 | 0.150 |
| SNF-B | 80 | 0 | 1.00 | 0.00 | 0.00 | 0.0400 | 0.200 |
| SNF-C | 80 | 0 | 1.00 | 0.00 | 0.06 | 0.0400 | 0.200 |
| SNF-D | 57 | 0 | 0.75 | 0.26 | 0.00 | 0.0025 | 0.050 |
| SNF-E | 80 | 0 | 1.00 | 0.00 | 0.06 | 0.0520 | 0.224 |
| SNF-N (null) | 3 | 77 | 0.05 | 0.00 | 0.00 | 0.2500 | 0.463 |

False consensus: strict 2/5 (0.60) · pairwise 176/551 (0.319). Abstention: **only** SNF-D abstains (precision 1.00, recall 0.60).

## 4 · Research defects to repair (the reason not to simply scale 100 → 1,000)

| # | Defect | Evidence | Consequence if unrepaired |
|---|---|---|---|
| **D-1** | **Ceiling effect.** A/B/C/E score 80/80 because the corpus sits inside their competence | pilot §16.2 | 10× more of the same corpus measures nothing new |
| **D-2** | **Presence insensitivity in d_SNF.** An optional role's *presence* can be treated as near-negligible, concentrating false-consensus risk | pilot §15, §18 "next question" | equivalence claims that ignore a real semantic difference |
| **D-3** | **`UNKNOWN` vs `NOT_EXPRESSED` under-separated** (Tier-2 weight 0.30) — epistemic absence collapsing into semantic absence | `distance.py` `W_ABSENT_UNKNOWN` | violates the distinction the commission calls *especially important* (§8) |
| **D-4** | **Single arbitration rule.** SNF-E ran only `abstain_conflict`, τ=0.30 | `mechanisms.SNFE` | no evidence about whether disagreement carries information |
| **D-5** | **Selective-calibration confound.** SNF-D's Brier/ECE advantage may be an abstention artifact, not calibration skill | pilot §16.4 | a false conclusion that abstention improves calibration |
| **D-6** | **Abstention has no cost model.** Abstention is measured but never penalized | pilot §18 | *"prudence"* indistinguishable from *"inability"* (commission §15) |
| **D-7** | **Hard-coded corpus sizes** (`N_PAIRS=80`, `N_TOTAL=100`) | `metrics.py` | silently wrong metrics at n=1000 |
| **D-8** | **No test suite.** A sanity gate and a repro checker exist; unit tests do not | `scripts/snf-research/` | commission §18 requires TDD; refactoring d_SNF without tests is unsafe |
| **D-9** | **No confidence intervals.** Single deterministic run, no interval reporting | pilot §16.5 | point estimates read as precision they do not have |

## 5 · Scope — and one honest reduction

**In scope:** repair D-1…D-9 · a stratified **1,000-case** corpus (`KOS-SNF-1000`, versioned schema, declared relations) · presence-sensitive `d_SNF` **v0.2** · SNF-E arbitration sweep over `UNANIMOUS · MAJORITY · QUORUM · VETO · ABSTAIN_ON_CONFLICT · LEAST_DIVERGENT · DOMAIN_CONDITIONAL` · full measurement vector `M=(C,NC,FC,T,A,H,Cal,R,K)` + pairwise/strict false consensus, abstention precision/recall, Brier, ECE, risk–coverage · Pareto and failure-mode analysis · reproducibility verification · the nine commissioned reports (§22 A–I).

**⚠️ Out of scope — stated, not silently dropped.** The commission's category list (§12) includes **translation · legal semantics · narrative semantics · abstract semantics · technical ontology · institutional and procedural meaning · metaphor/pragmatics**. The apparatus is a **toy world**: one verb lexicon, deterministic surface parsing, hand-written gold (pilot threat #1: *nothing generalizes to real language*). Manufacturing those families inside a toy world would produce **toy artifacts that look like evidence about language and are not** — the exact failure this programme is built to avoid. I will therefore:

1. build the 1,000 cases across the families the toy world can represent **honestly** — structural, semantic-role, epistemic-status, transformation, and adversarial families (§6);
2. record the excluded families in the Research Architecture Note and the final report as **requiring a real corpus** (they belong to OQ-4 / KOS-SCB, not here);
3. **not** claim any result about translation, metaphor, pragmatics, or institutional meaning.

If you want those families attempted in the toy world anyway, say so and I will build them with a declared fidelity warning on every affected metric.

## 6 · Corpus design — `KOS-SNF-1000`

Stratified, ~1,000 cases, **deliberately harder than the pilot** (D-1). Every family declares its relation so the evaluator never infers one.

| Family | Relation | ~n | Purpose / defect addressed |
|---|---|---|---|
| Semantic equivalents (paraphrase, lexical variation) | EQUIVALENT | 120 | baseline convergence |
| Active/passive · nominalization · reordering | PRESERVING | 120 | transformation stability |
| Argument inversion | DISTINCT | 90 | decisive separation |
| Negation vs predicate change | DISTINCT | 90 | D-3 boundary |
| Modality · temporal · quantifier variation | DISTINCT | 120 | Tier-2 discrimination |
| **Optional-role presence/absence** | DISTINCT | 110 | **D-2** — the presence trap |
| **`UNKNOWN` vs `NOT_EXPRESSED`** | DISTINCT | 110 | **D-3** — epistemic ≠ semantic absence |
| Role omission / partial interpretation | AMBIGUOUS | 100 | abstention quality |
| Genuine ambiguity (two licensed readings) | AMBIGUOUS | 90 | D-6 cost model |
| Adversarial false-consensus traps | FALSE_CONSENSUS_TRAP | 100 | agreement ≠ truth |
| Out-of-world / unregistered content | ABSTAIN_EXPECTED | 50 | *inability* vs *prudence* |

Balance target: **no mechanism's competence covers a majority of families** — the corpus must be able to make each mechanism fail somewhere.

## 7 · d_SNF v0.2 — refinement, honestly labelled

- Presence-sensitive optional-role term (**D-2**) and a widened `UNKNOWN`/`NOT_EXPRESSED` separation (**D-3**), both as **declared research hyper-parameters**, versioned, with the old weights retained as `v0.1` for A/B comparison.
- Extend the sanity gate from 8 to ~16 pairs, including the new presence and epistemic-absence pairs; **the gate must pass before any mechanism runs** (unchanged discipline).
- **Terminology, in code and prose:** `d_SNF` remains a **pre-metric** — non-negative, symmetric, `d(x,x)=0`; the **triangle inequality is not claimed**. No metric-space property is asserted without proof (commission §22C). Thresholds (`τ`) stay **research hyper-parameters, never architectural thresholds**.

## 8 · Mechanisms and the no-gold boundary (unchanged, re-verified)

`interpret(Expression, Context) → CandidateSet`, black box. A mechanism never sees gold, evaluator, score, other mechanisms' answers, or any authority decision — except SNF-E, whose member dependency is part of its **declared** ensemble design. SNF-C keeps its genuine Pāṇinian role structure (kartṛ · karman · karaṇa · sampradāna · adhikaraṇa · apādāna) and the experiment must be **able to show its limits**, not only its strengths. SNF-E stays a **veto/agreement ensemble** — no `wA*A + wB*B + …` composite authority, ever.

## 9 · Measurement

Vector `M=(C,NC,FC,T,A,H,Cal,R,K)` + strict/pairwise false consensus · abstention precision/recall · Brier · ECE · confidence distributions · risk–coverage. **Bayesian discipline:** `P(θ|E,C)=0.99` means *the mechanism estimates θ as highly probable* — it does **not** mean `Identity(E)=θ`. **D-5** addressed by **coverage-matched calibration**: compare only at equal coverage, so an abstention artifact cannot masquerade as calibration skill. **D-6** addressed by a declared abstention cost model separating *correct resolution · correct abstention · blind abstention · false acceptance* (false acceptance = the most dangerous error, a **constraint**, not a score). **D-9**: bootstrap intervals over cases where statistically appropriate. A composite score may be *explored*; it may **not** become a decision rule.

## 10 · DDD boundary of the research context

Research-domain concepts, explicitly **not** KnowledgeOS domain concepts: `Expression · Context · InterpretationCandidate · CandidateSet · SemanticRelation · Transformation · Uncertainty · Abstention · Evidence · Measurement · Mechanism · Experiment`. Small cohesive models (`Experiment` owns config/lifecycle · `Case` one controlled test · `CandidateSet` mechanism output · `Measurement` observed evidence · `ConsensusObservation` agreement/disagreement · `Transformation` an input relation) — **no** giant `SemanticExperiment` god-object. Domain services carry semantic rules; infrastructure carries corpus loading, JSON, persistence, subprocess, timing, seeding, reporting. **No SNF terminology enters KnowledgeOS core language** because it is experimentally convenient.

## 11 · Task checklist (execution order per commission §Execution Mode)

- [x] 1–5 **DONE (this plan)** — artifacts read, research context reconstructed, apparatus reviewed, defects D-1…D-9 identified
- [x] 6 Test suite first (**D-8**): distance · abstention · transformation · ensemble · metric-scaling tests — RED before any change
- [x] 7 `d_SNF` v0.2 (**D-2, D-3**) + extended sanity gate; v0.1 retained for comparison
- [x] 8 `KOS-SNF-1000` corpus (**D-1**), versioned schema, declared relations, verifier
- [x] 9 Derive corpus sizes from data (**D-7**); SNF-E arbitration policies implemented as swept policies (**D-4**)
- [x] 10 Run the 1,000-case competition (deterministic, seeded)
- [x] 11 Measure all mechanisms (vector, not scalar)
- [x] 12 Pareto frontier + failure-mode analysis
- [x] 13 Abstention (**D-6**) and false-consensus analysis; coverage-matched calibration (**D-5**); intervals (**D-9**)
- [x] 14 Reports A–I (§22), including *what was established* vs *what remains unknown*
- [x] 15 Verify reproducibility (byte-identical across subprocess runs; record seeds, versions, hashes)
- [x] 16 **STOP** — no architecture change; any implication recorded as OBSERVATION → EVIDENCE → ARCHITECTURAL HYPOTHESIS → HPA DECISION REQUIRED

## 12 · Stop conditions (commission §21)

Halt and report — do not solve — if the work appears to require: modifying v1.1 · modifying the Constitution · changing KnowledgeAggregate authority · introducing SNF into the kernel · changing the Port Contract · making a mechanism an identity authority · promoting experimental metrics into domain invariants.

## 13 · Risks

| Risk | Mitigation |
|---|---|
| **Toy-world results read as language results** | every artifact and every report carries the toy-world limitation; excluded families named (§5) |
| Ceiling persists at n=1,000 | corpus balance target (§6); if A/B/C/E still saturate, that is itself reported as a finding, not hidden |
| d_SNF tuning becomes gold-fitting | weights declared before the run; v0.1 kept; sanity gate frozen before mechanisms execute |
| Arbitration sweep becomes winner-hunting | policies measured independently; no policy declared correct; Pareto reporting only |
| Scope creep into implementation | §12 stop conditions; architecture untouched |
| Composite score creeping into a decision rule | prohibited by design; explored only as an observable |

## 14 · Open questions for the HPA

| # | Question | Why it matters |
|---|---|---|
| **Q-1** | Does this commission authorize **OQ-4**, or does OQ-4 (real-corpus KOS-SCB) remain separate and unauthorized? My reading: **P5 = toy-world simulation only; OQ-4 stays closed.** | the record currently says *v0.4 / OQ-4 unauthorized*; I will not silently reinterpret an authorization |
| **Q-2** | Accept the **honest reduction** in §5 (exclude translation · legal · narrative · abstract · technical-ontology · metaphor/pragmatics from a toy world), or build them anyway with a declared fidelity warning? | it changes the corpus and what may be claimed |
| **Q-3** | `DOMAIN_CONDITIONAL` arbitration — the toy world has no domain taxonomy. Sweep it as a **stub over case families**, or defer the policy? | avoids inventing a domain model to satisfy a policy name |
| **Q-4** | Corpus authorship: **hand-designed** (pilot's method, ~1,000 cases is a large manual set) or **generated from declared family templates** with hand-written gold per template? My recommendation: **templates + hand-written gold**, which keeps determinism and auditability at n=1,000 | affects reproducibility claims and effort |

## 15 · Outcome

**Executed and complete.** 51 tests (RED before GREEN) · `d_SNF v0.2` with the four-way non-collapse gate (14/14) · `KOS-SNF-1000` (1,100 cases, digest `e9fb867903fb`) · six-policy arbitration sweep · full measurement vector with bootstrap CIs · coverage-matched calibration · Pareto per policy · reproducibility verified (sweep digest `9ce9d670cb47`, identical across runs).

**Headline results:** the Phase-1 ceiling was a corpus artifact (R 1.00 → 0.69) · `d_SNF v0.1` collapsed two distinctions and was crediting producers as CORRECT on 45/90 adversarial traps · three of six named arbitration rules were behavioural aliases · 0.587 of mechanism agreements are agreements on a wrong reading · SNF-D's Phase-1 calibration advantage was a selection effect and reverses under matched coverage · **no producer represents the UNKNOWN vs NOT_EXPRESSED distinction at all** (110/110 failures).

**Five architectural hypotheses (AH-1…AH-5) recorded and stopped at "HPA DECISION REQUIRED".** No architecture change, no winner, no promotion, OQ-4 still unauthorized.

---

### Traceability

- **Commission:** HPA, 2026-08-22 — P5 Semantic Competition Refinement (23 sections; execution order 1–16).
- **Read (repository as source of truth):** Reference Architecture v1.1 r4 · first/second HPA reviews + closure/acceptance records · Post-Research Architectural Review + its HPA acceptance · Expression↔Meaning Port Contract + LA Review 01 · `KOS-SNF-RESEARCH-REFINEMENT-001.md` · `KOS-SNF-pilot-100.json` / `-results.json` / `-metrics.json` · apparatus `scripts/snf-research/{ir,distance,lexicon,mechanisms,evaluator,metrics,corpus_builder,run-pilot,_verify_repro}.py` (2,958 lines) · SNF measurement-framework mathematical review (`docs/knowledgeos/brainstorming/20260822-154923-…`, main tree `6709f276`).
- **Process:** EP-01 (plan → **approval** → implement) · EP-03 readiness (steps 1–5 executed) · TDD per commission §18 · ES-004.2 plan naming.
- **Unchanged by this plan:** Reference Architecture v1.1 **r4** · Constitution **FROZEN** · Port Contract (F-1…F-5 still HPA-gated) · register **25+4** · research closure · OQ-2 · OQ-3 · OQ-5 · **OQ-4 pending Q-1**.
