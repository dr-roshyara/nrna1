# KOS-SNF Research Simulation — Phase 1 Refinement Report

**Title:** KOS-SNF Research Simulation Refinement / Phase 1 — corrected experimental apparatus, 100-case pilot measurement, and interpretation boundary
**Author:** Senior Research Engineer / Mathematical Semantic Systems Engineer (commissioned by the Human Principal Architect)
**Date:** 2026-08-22
**Status:** PILOT COMPLETE — apparatus valid; **no winner selected, no architecture change**
**Version:** KOS-SNF-RESEARCH-REFINEMENT-001
**Companion artifacts:** `KOS-SNF-pilot-100.json` · `KOS-SNF-pilot-results.json` · `KOS-SNF-pilot-metrics.json` · runner `scripts/snf-research/run-pilot.py`

---

## 1. Purpose and Commission

The Human Principal Architect (HPA) commissioned *Phase 1: repair the experimental apparatus before interpreting the measurement*. Two prior defects were identified in the black-box SNF simulation:

1. **Benchmark contamination** — the evaluator leaked gold/expected/transformation information into the mechanisms, so "convergence" could not be trusted.
2. **Inexpressive evaluator** — the evaluator could not distinguish the signals that matter (inversion, negation, ambiguity, abstention), producing the degenerate C=0/T=0 signal.

The mandate was explicit and is the spine of this report:

> **DO NOT start by modifying the KnowledgeOS architecture** — no v1.1, no Port Contract, no Governance, no Constitution, no identity authority, no workflow architecture. SNF stays subordinate: `SNF → candidate interpretation → measurement → evidence → Port Contract/Governance`.

The commission further separated **four things that must never be collapsed**: surface normalization, semantic candidate generation, semantic equivalence evaluation, and identity authority. And it required the strongest possible epistemic hygiene: ten scientific rules, a no-gold black-box contract, a metric sanity gate before any mechanism runs, a 100-case pilot before any larger competition, and a **STOP after the pilot** with no winner selection.

**Section 18 is the PILOT COMPLETE block** — the deliverable of this document.

---

## 2. Research Baseline (prior material read, not rewritten)

The following existing research was read and is **preserved** — this report does not rewrite it:

| Prior artifact | Contribution | Preserved in |
|---|---|---|
| `20260822-125831-sanskrit-inspired-semantic-language-artha-research-extraction.md` | Artha/Sanskrit semantic language; SNF/Semantic-Invariance mechanism candidates | brainstorming/00_INDEX.md row 2 |
| `20260822-125015-vedic-sanskrit-grammar-v2-word-order-semantic-normal-form.md` | word-order → semantic normal form; SNF draft | row 7 |
| `20260822-124229-sanskrit-grammar-v1-dhatu-transformation-lens.md` | Sanskrit grammar v1; transformation lens | row 21 |
| `20260822-0241-knowledgeos-semantic-kernel-matilal-word-and-world.md` | semantic kernel; word-and-world distinction (Vāṇī-adjacent) | — |
| `20260816-204714-track2-eks-semantic-discovery.md` | EKS engineering semantics | — |
| HPA architectural exchange (2026-08-22) | multi-lens research stack; SNF = candidate mechanism, not architecture | this report §3 |

**Baseline defect diagnosis.** The prior simulation's C=0/T=0 signal was *not* a statement about SNF mechanisms — it was a statement about the apparatus: a contaminated evaluator and an inexpressive distance function cannot measure anything. The correct scientific response (as commissioned) was to rebuild the apparatus, gate it, and only then read the measurement. This report records the rebuilt apparatus and its first measurement.

---

## 3. Research Question and the Multi-Lens Frame

**The strongest architectural statement is a boundary requirement, not a mechanism:**

> KnowledgeOS requires a **semantic interpretation boundary** capable of (a) preserving meaning across transformations, (b) preserving distinctions, (c) representing uncertainty, and (d) abstaining when semantic determination is unjustified.

The research question is therefore: **which candidate mechanism (if any) satisfies that boundary, under what conditions, and with what evidence?** SNF-A/B/C/D/E are **competing experimental mechanisms measured against the boundary** — never architecture. SNF-C is specifically the computational formalization of the **Sanskrit/Pāṇinian lens** (kartṛ/karman/karaṇa/sampradāna/adhikaraṇa/apādāna), not the whole architecture.

The pilot is read through the full research-stack lens — each lens names a measurable observable:

| Lens | Constraint | Pilot observable |
|---|---|---|
| Sanskrit/Pāṇinian | preserve semantic roles across surface transformation | role-faithfulness on transformation cases (T) |
| Vāṇī | expression ≠ meaning | four-part separation (§5) |
| Navya-Nyāya | don't leave semantic relations underspecified | d_SNF role-status pairs (§6) |
| Tarka | consider competing interpretations | candidate sets + divergence analysis (SNF-E) |
| Zero | if evidence insufficient, abstain | abstention quality (§11) |
| Gödel | a formal system cannot certify itself from within | no self-certification; consensus ≠ truth (§12) |
| Escher | invariance under representation change | d_SNF ≈ 0 across active/passive/paraphrase |
| Tripuṭī | keep knower/act/known distinct | mechanism (knower) / representation (act) / gold (known) separation |
| EKS/PKS | what engineering actually needs | corpus grounded in business expressions |

---

## 4. KOS-SNF-IR v0.1 — the common semantic representation

A research-only intermediate representation, covering: predicates/events, arguments with semantic roles, negation, modality, temporal, quantification, uncertainty, abstention, and provenance.

```text
IR = {
  predicate:     APPROVE
  arguments:     [ {role: AGENT,     entity: committee, status: EXPRESSED},
                   {role: PATIENT,   entity: order,     status: EXPRESSED},
                   {role: INSTRUMENT,entity: manager,   status: AMBIGUOUS} ]
  negation:      false
  modality:      ASSERTED
  temporal:      NONE
  quantification: NONE
  uncertainty:   AMBIGUOUS
  confidence:    0.60
  provenance:    "SNF-C"        # which mechanism produced it — NEVER authority
}
```

**Role statuses** (the epistemic core): `EXPRESSED` (surface licenses + fills), `UNKNOWN` (may exist, undeterminable), `NOT_EXPRESSED` (surface does not express — absence, not ignorance), `AMBIGUOUS` (surface licenses but filler underdetermined), `NOT_LICENSED` (frame does not license). **Unknown ≠ absent ≠ present** is a first-class distinction.

**Identity authority is explicitly absent from KOS-SNF-IR.** It is a candidate-meaning carrier, not an identity claim. `provenance` is bookkeeping for the experiment, never an authority marker. (§13 makes this precise.)

---

## 5. The Four-Part Separation (never collapsed)

| Part | What it is | Where it lives | Explicitly NOT |
|---|---|---|---|
| **A. Surface normalization** | tokens → canonical surface (lowercase, morphology, determiners) | `lexicon.tokenize` / mechanisms | semantic equivalence from surface similarity |
| **B. Semantic candidate generation** | surface → candidate IR(s) + confidence + uncertainty + abstention | mechanisms (A/B/C/D/E/N) | a claim of identity |
| **C. Semantic equivalence evaluation** | distance between candidate IRs | `d_snf` (evaluation layer) | a claim of identity |
| **D. Identity authority** | whether a representation IS the knowledge | **KnowledgeOS Kernel / Constitution** — OUT OF SCOPE of this pilot | any mechanism, score, or consensus |

SNF mechanisms produce **B**. The distance function provides **C**. Neither **B** nor **C** may silently become **D**. The pilot measures B and C; it deliberately never touches D.

---

## 6. d_SNF formalization and the sanity gate

`d_snf(x, y) ∈ [0,1]` — a mechanism-independent, two-tier pre-metric over KOS-SNF-IR.

**Tier 1 (decisive / categorical — a difference is a boundary, not a degree):**

| Dimension | Weight | Rationale |
|---|---|---|
| predicate differs | 0.65 | different event type |
| negation differs | 0.65 | *approved* vs *did not approve* must be strongly separated |
| EXPRESSED-role entity differs | 0.65/slot | different participant |
| argument inversion | +0.20 (on top of the two swaps) | `cat chased dog` ≠ `dog chased cat` |

**Tier 2 (graded / epistemic — a difference is a degree):**

| Status pair (one slot) | Weight |
|---|---|
| EXPRESSED vs NOT_EXPRESSED | 0.40 |
| EXPRESSED vs UNKNOWN | 0.50 |
| NOT_EXPRESSED vs UNKNOWN | 0.30 |
| UNKNOWN vs UNKNOWN | 0.15 |
| one slot AMBIGUOUS, other not | 0.20 |
| role in one IR, absent in other | 0.30 |
| modality differs | 0.10 |
| one IR globally AMBIGUOUS, other RESOLVED | 0.20 |

Role slots are blended `0.65·max + 0.35·mean` so a single decisive slot is never diluted by averaging. Total clamped to [0,1]. **Honest limitation:** d_SNF is a *pre-metric* — non-negative, symmetric, d(x,x)=0, decisive dimensions strongly separated — but the **triangle inequality is not guaranteed** across category boundaries (nonlinear max/mean blend). Pilot thresholds do not rely on it.

**Sanity gate (must pass before any mechanism runs — it did, 2026-08-22):**

| Pair | Label | d_SNF | Bound | Result |
|---|---|---|---|---|
| A | identical | 0.000 | ≤ 0.001 | **PASS** |
| B | paraphrase (same gold) | 0.000 | < 0.30 | **PASS** |
| C | active/passive (same gold) | 0.000 | < 0.30 | **PASS** |
| D | argument inversion | 0.850 | ≥ 0.60 | **PASS** |
| E | negation | 0.650 | ≥ 0.60 | **PASS** |
| F | different predicate | 0.650 | ≥ 0.60 | **PASS** |
| G | ambiguous vs clear | 0.430 | 0.30..0.85 | **PASS** |
| H | incomplete vs complete | 0.330 | 0.15..0.85 | **PASS** |

---

## 7. Mechanisms as true black boxes

Every mechanism implements the identical contract:

```text
interpret(expression, context) -> CandidateSet
candidates: [{representation, confidence, uncertainty}]
abstained: bool · abstention_reason · evidence
```

Mechanisms receive **only `(expression, context)`** — never gold, expected answers, distractors, transformation provenance, or evaluation data. Gold lives only in the evaluation layer.

| Mechanism | Strategy | Notes |
|---|---|---|
| **SNF-A** | symbolic normalization | canonical ordering, morphology, passive reversal; claims only normalization |
| **SNF-B** | directed-graph canonicalization | `cat→chase→dog` ≠ `dog→chase→cat`; passive reversal normalizes edges |
| **SNF-C** | Pāṇinian kāraka roles (kartṛ/karman/karaṇa/sampradāna/adhikaraṇa/apādāna) | explicit UNKNOWN/absent/AMBIGUOUS; never infers unexpressed roles; **the Sanskrit lens formalized** |
| **SNF-D** | SAIT/NSID closed-world registry | resolve registered identifiers, **abstain on unknown** (never invent); known↔unknown distinguished |
| **SNF-E** | constitutional veto ensemble | runs A/B/C/D, pairwise divergence analysis, agreement clustering; **NOT a weighted average** (wA·A+wB·B+wC·C is forbidden); arbitration rule is an experimental variable |
| **SNF-N** | null baseline | most-common-candidate prior, never abstains; exists to answer "is abstention/convergence informative?" |

SNF-E's arbitration rule is configuration (`unanimity|majority|pairwise|abstain_conflict|veto`), default `abstain_conflict`. The pilot measures behavior under it; it endorses no rule.

---

## 8. No-Gold Boundary and Experimental Hygiene

Enforced structurally, not by convention:

1. `run-pilot.py` calls `interpret(expression, context)` only; the corpus's `gold` dict is never in scope of a mechanism call.
2. Gold enters the pipeline exclusively through `evaluator.py` after mechanisms have already produced candidates.
3. Gold IRs are compared by the mechanism-independent `d_snf`, so no mechanism's internals are privileged.
4. The runner is deterministic and seeded (seed `20260822`); outputs are byte-identical across runs (verified — §16).

**Apparatus fixes made before interpreting the measurement** (per the commission's instruction to fix the apparatus, not the scores):

| Fix | Defect found | Correction |
|---|---|---|
| SNF-D registry resolution | compared surface tokens (`"reports"`) against the canonical registry → abstained on every plural, contradicting its own closed-world contract | compare canonical identifiers (`NOUNS[t]`) against `REGISTERED_ENTITIES` |
| warranted-abstention accounting | used raw `.split()` → punctuation-suffixed tokens never matched the registry → 11 abstentions mis-classified as false | use the shared `tokenize()` |
| consensus gold verdict | inferred "gold says same" from `d_snf(gold1,gold2) ≤ tau` → misread presence traps (gold declared DISTINCT) as same | use the corpus's **declared relation** as the gold verdict |

These were instrument repairs, not score adjustments: the mechanism contract, the corpus gold, and the distance function were unchanged.

---

## 9. Corpus design (100 hand-designed cases)

`KOS-SNF-pilot-100.json` · corpus sha256 prefix `dd03c0baeaea`. Gold is hand-written by the researcher and lives only in the corpus. **No mechanism sees it.**

| Category | n | Relation | Purpose |
|---|---|---|---|
| equivalent | 20 | same gold | convergence target (C) |
| distinct | 20 | different gold | non-convergence target (NC) |
| ambiguous | 20 | underdetermined | abstention/uncertainty target |
| transformation | 20 | same gold (structural) | invariance stability target (T) |
| adversarial | 20 | different gold, high surface overlap | false-consensus traps |

Adversarial traps include: inversion (identical words, swapped roles), negation insertion (one-word surface difference), patient/predicate/agent swap, PP-presence flip. Ambiguous cases cover instrument-vs-companion (`with X`), beneficiary-vs-purpose (`for X`), locative attachment (`in X`), source attachment (`from X`). Frame roles absent from the surface are `NOT_EXPRESSED`; ambiguous gold marks the underdetermined role `AMBIGUOUS` with global uncertainty `AMBIGUOUS`.

---

## 10. Measurement vector M

Per mechanism: **C** correct convergence (pair cases where both candidates match gold), **NC** wrong, **T** transformation stability, **R** selective recall (C/(C+NC)), **A** abstention rate, **H** hedging rate (answered cases declared AMBIGUOUS), **Cal** calibration (Brier + ECE). Across mechanisms: **FC** false consensus, **K** inter-mechanism agreement (Fleiss' κ). Research hyper-parameters (not architectural thresholds): `tau_eval=0.40`, `tau_amb=0.55`, `tau_conv=0.40`.

---

## 11. Abstention quality and selective prediction (Zero lens)

Taxonomy: **A_appropriate** (abstained when abstention warranted), **A_false** (abstained when it should have answered), **A_missed** (answered when it should have abstained). Warranted = gold ambiguous, or the expression names something outside the mechanism's own world (closed-world knowledge, not gold).

| Mechanism | A_appropriate | A_false | A_missed | Precision | Recall |
|---|---|---|---|---|---|
| SNF-A | 0 | 0 | 20 | — | 0.00 |
| SNF-B | 0 | 0 | 20 | — | 0.00 |
| SNF-C | 0 | 0 | 20 | — | 0.00 |
| **SNF-D** | **26** | **0** | 17 | **1.00** | 0.60 |
| SNF-E | 0 | 0 | 20 | — | 0.00 |
| SNF-N | 0 | 0 | 20 | — | 0.00 |

**Finding.** Only SNF-D exercises the right to abstain, and it does so **perfectly** (precision 1.0 — every abstention was warranted; recall 0.60 — it still over-answers 17 of the 20 ambiguous cases, its closed-world assertiveness). A/B/C/E **hedge instead of abstain** (marking AMBIGUOUS with a lower-confidence candidate); SNF-N never abstains by design. The Zero-lens discipline — *withholding a determination when evidence is insufficient* — is carried in this pilot only by the closed-world mechanism; every open-world mechanism prefers to hedge or assert.

Risk-coverage (selective accuracy vs confidence threshold) confirms the shape: A/B/C/E retain 100% coverage at confidence ≥ 0.8 with 100% accuracy; SNF-D retains 100% coverage at ≥ 0.95; SNF-N cannot exceed 0.0375 accuracy at any threshold — abstention and confidence carry real information.

---

## 12. False consensus experiment (consensus ≠ truth; Gödel lens)

**Strict consensus** (all resolved mechanisms say the same thing): 5 events, 3 true / 2 false, **consensus-correctness 0.60**. Both false events are ambiguous cases where every mechanism including the null baseline over-committed the same way.

**Pairwise consensus** (finer-grained — a 2-mechanism false agreement counts even when others dissent):

| Category | agreeing pairs | false pairs | false rate |
|---|---|---|---|
| equivalent | 190 | 0 | 0.00 |
| transformation | 185 | 0 | 0.00 |
| distinct | 16 | 16 | **1.00** |
| ambiguous | 144 | 144 | **1.00** |
| adversarial | 16 | 16 | **1.00** |
| **all** | **551** | **176** | **0.319** |

**Findings.**
1. **False agreement is perfectly structured**: 0% where meaning is same, 100% where meaning differs or is underdetermined. Agreement is *informative but not infallible* — exactly the Gödel-lens prediction (a set of mechanisms cannot certify its own consensus).
2. **The presence traps fire on all real mechanisms.** C035/036/091/092 (`submitted the proposal` vs `... to the council`) produce false agreement across SNF-A/B/C/D/E: the toy mechanisms treat optional-role presence as within-tau (d≈0.31 < 0.40), while gold declares them distinct. Presence-vs-absence is under-weighted by the toy world.
3. **The with-animate traps fire only on the role-blind mechanisms.** `with the manager` (C041) → SNF-B and SNF-D falsely agree (INSTRUMENT asserted); SNF-A/C hedge and SNF-E partial-hedges, so the trap is caught by the role-sensitive mechanisms. The *accountant* variant (C042) fools everyone including SNF-N, because the animate-ambiguity list is incomplete — an honest limit of the toy lexicon.
4. **The adversarial category's headline rate is 0** under the strict definition — the deliberately engineered traps (inversion, negation, patient swap) do **not** fool the role-preserving mechanisms, which are individually reliable on well-determined meaning. The false-consensus risk in this world lives in *underdetermined surface and optional-role presence*, not in the dramatic traps.

---

## 13. Calibration and the Bayesian score revisited (epistemic uncertainty ≠ identity authority)

**Per-mechanism calibration** (over answered pair-case candidates):

| Mechanism | Brier | ECE |
|---|---|---|
| SNF-A | 0.0225 | 0.150 |
| SNF-B | 0.0400 | 0.200 |
| SNF-C | 0.0400 | 0.200 |
| **SNF-D** | **0.0025** | **0.050** |
| SNF-E | 0.0520 | 0.224 |
| SNF-N | 0.2500 | 0.463 |

**Findings.**
1. **SNF-D is the best-calibrated** — but for a revealing reason: it abstains on the hard cases (selective reporting), so its confidence is calibrated on the easy tail. Good calibration + abstention can be correlated; the pilot cannot separate the two with one run.
2. **All mechanisms over-assert** (ECE ≫ 0): confidence runs high even on error cases. SNF-E, the ensemble, is **worse-calibrated than its members** (Brier 0.052 vs A's 0.0225) — the arbitration rule's promotion confidence (0.6–0.95) is not a calibrated probability. This is a genuine, non-obvious ensemble result.
3. **The Bayesian score is preserved as epistemic uncertainty, not identity.** We keep `P(θ|E,C)` — a mechanism's posterior over its candidate given the expression and its own strategy — as a calibrated uncertainty estimate (Brier/ECE/risk-coverage above). But we **enforce** `P(θ|E,C) ≠ Identity(E,θ)`: no posterior, no consensus rate, no calibration score confers the authority to declare *this is what E means*. Identity authority is out of scope (§5, part D) and belongs to the KnowledgeOS Kernel/Constitution. The pilot's calibration numbers say how *uncertain* mechanisms are; they say nothing about *truth*.
4. **Inter-mechanism agreement is low-to-moderate** (Fleiss' κ = 0.259) — the mechanisms genuinely disagree where the surface is underdetermined, which is precisely why abstention and divergence carry signal.

---

## 14. The null baseline (is abstention/convergence informative?)

SNF-N — the most-common-candidate prior that never abstains — is the control:

| | SNF-N | best mechanism (per dimension) |
|---|---|---|
| C (pair convergence) | 3/80 | 80/80 |
| R (selective recall) | 0.038 | 1.000 |
| Brier / ECE | 0.250 / 0.463 | 0.0025 / 0.050 (D) |
| abstention | never | D abstains 26×, all appropriate |

**Finding.** Without abstention and without divergence analysis, convergence collapses to 3/80 and calibration is garbage. The information in this experiment lives precisely in the observables the prior lacks — abstention (Zero), hedging (uncertainty), and divergence (Tarka). The corrected apparatus therefore *does* measure something the degenerate C=0/T=0 simulation could not.

---

## 15. Results summary

Per-mechanism measurement vector (see §10 definitions):

| Mechanism | C | NC | T | R | A | H | Brier | ECE |
|---|---|---|---|---|---|---|---|---|
| SNF-A | 80 | 0 | 1.00 | 1.00 | 0.00 | 0.06 | 0.0225 | 0.150 |
| SNF-B | 80 | 0 | 1.00 | 1.00 | 0.00 | 0.00 | 0.0400 | 0.200 |
| SNF-C | 80 | 0 | 1.00 | 1.00 | 0.00 | 0.06 | 0.0400 | 0.200 |
| SNF-D | 57 | 0 | 0.75 | 1.00 | 0.26 | 0.00 | 0.0025 | 0.050 |
| SNF-E | 80 | 0 | 1.00 | 1.00 | 0.00 | 0.06 | 0.0520 | 0.224 |
| SNF-N | 3 | 77 | 0.05 | 0.04 | 0.00 | 0.00 | 0.2500 | 0.463 |

Ambiguity handling (20 cases): A 6/20 (6 hedged), B 0/20 (20 overcommitted), C 6/20 (6 hedged), D 3/20 (3 abstained, 17 overcommitted), E 6/20 (6 hedged), N 0/20 (18 wrong).

**Read together:** in this toy world the role-based mechanisms are individually near-perfect on well-determined meaning (A/B/C/E: 80/80; D: 57/80 with abstention only on genuinely unknown content), the only abstaining mechanism is perfectly precise, and the false-consensus risk is concentrated where the surface is underdetermined or an optional role's presence is treated as negligible. **These are statements about the toy world and the toy mechanisms, not about KnowledgeOS.**

---

## 16. Threats to validity and known limits

1. **Toy world, toy mechanisms.** One verb lexicon, hand-written gold, deterministic surface parsing. Nothing generalizes to real language or real KnowledgeOS.
2. **Near-ceiling on pairs.** A/B/C/E score 80/80 because the corpus is exactly within their competence. The discriminative information is in abstention, hedging, calibration, and false consensus — not in C/NC.
3. **Pre-metric.** d_SNF does not guarantee the triangle inequality across category boundaries; thresholds are research hyper-parameters, not architectural thresholds.
4. **Selective calibration confound.** SNF-D's superior Brier/ECE may be an abstention artifact (single run; not separable in this pilot).
5. **Single run, one arbitration rule.** SNF-E behavior is reported under `abstain_conflict` only; other rules are an explicit next step.
6. **The consensus "correctness" is not authority.** A 60% strict / 68% pairwise true-agreement rate says nothing about what E *means* — only that mechanisms sometimes agree. Gödel boundary is honored by construction.
7. **Reproducibility verified** — two subprocess runs produced byte-identical `KOS-SNF-pilot-results.json` (`1f639703…`) and `KOS-SNF-pilot-metrics.json` (`7170e2cd…`); seed fixed (`20260822`).

---

## 17. Adherence to the ten scientific rules

| # | Rule | Status |
|---|---|---|
| 1 | No gold leakage | ✅ mechanisms receive only `(expression, context)`; gold enters only via evaluator |
| 2 | No identity authority | ✅ KOS-SNF-IR declares identity authority absent; §13 enforces P(θ\|E,C) ≠ Identity(E,θ) |
| 3 | No composite-score shortcut for SNF-E | ✅ SNF-E is a veto/agreement ensemble; weighted-average code does not exist |
| 4 | Abstention is an observable | ✅ A/A_appropriate/A_false/A_missed/precision/recall measured (§11) |
| 5 | Consensus is not truth | ✅ false-consensus experiment measures exactly this (§12) |
| 6 | Confidence is not correctness | ✅ Brier/ECE/risk-coverage measure the gap (§13) |
| 7 | No architecture decision from pilot results | ✅ STOP per §18; no mechanism selected; no Port Contract/Governance/Constitution change |
| 8 | Reproducibility | ✅ deterministic, seeded, byte-identical across runs, versioned artifacts |
| 9 | Research separated from production | ✅ all artifacts under `scripts/snf-research/` + `docs/knowledgeos/brainstorming/`; no production code touched |
| 10 | Stop if the experiment is invalid | ✅ three apparatus defects were fixed (§8) *before* interpretation; no result was read from the broken apparatus |

---

## 18. PILOT COMPLETE

```text
KOS-SNF RESEARCH SIMULATION — PHASE 1 PILOT
────────────────────────────────────────────────────────────
EVIDENCE
  • apparatus: gate passes (8/8), no-gold boundary enforced, byte-reproducible
  • corpus: 100 cases (20×5), hand-written gold, sha dd03c0baeaea
  • mechanisms: A/B/C/D/E/N, identical black-box contract, expression-only input
  • measurement: per-mechanism (C,NC,T,R,A,H,Cal) + cross-mechanism (FC,K)
  • false consensus: strict 2/5 false (rate 0.60); pairwise 176/551 (rate 0.319)
  • abstention: SNF-D only, precision 1.00, recall 0.60; others hedge, never abstain
  • calibration: D best (Brier 0.0025, ECE 0.050); E worse than its members (0.052)
  • null baseline: C=3/80, R=0.038, Brier 0.25 — abstention/convergence carry signal
  • three apparatus defects fixed before interpretation (registry, warranted-
    abstention accounting, gold-verdict inference) — no result read from broken parts

CONCLUSION: VALID (apparatus) — REQUIRES REFINEMENT (corpus + mechanisms)
  The corrected experimental apparatus is VALID for the pilot's scope: it measures
  what the degraded simulation could not. The research question (which mechanism
  satisfies the semantic-interpretation boundary) is NOT answered by this pilot
  and is deliberately left open.

  NO winner selected. NO Port Contract / Governance / Constitution / identity-
  authority change. NO SNF formula finalized. NO architecture decision made.
  SNF remains a candidate mechanism set behind the boundary requirement.

NEXT RESEARCH QUESTION
  "Is the role-based mechanisms' near-ceiling on well-determined pairs robust to
  (a) a wider, noisier corpus and (b) optional-role presence weighted as a real
  semantic difference — and does SNF-D's abstention-based calibration advantage
  survive when abstention is modeled as a decision with a cost?"
  Suggested next slice (NOT authorized here): 1,000-case competition across
  categories, per-rule SNF-E arbitration sweep, and presence-sensitivity
  variant of d_SNF.
────────────────────────────────────────────────────────────
```

---

### Artifacts

| Artifact | Location | sha256 (prefix) |
|---|---|---|
| this report | `docs/knowledgeos/brainstorming/KOS-SNF-RESEARCH-REFINEMENT-001.md` | — |
| corpus | `docs/knowledgeos/brainstorming/KOS-SNF-pilot-100.json` | `dd03c0baeaea` |
| results | `docs/knowledgeos/brainstorming/KOS-SNF-pilot-results.json` | `1f639703d21a` |
| metrics | `docs/knowledgeos/brainstorming/KOS-SNF-pilot-metrics.json` | `7170e2cd6aec` |
| runner (reproducible) | `scripts/snf-research/run-pilot.py` | — |
| IR model | `scripts/snf-research/ir.py` · `distance.py` · `lexicon.py` · `mechanisms.py` · `evaluator.py` · `metrics.py` · `corpus_builder.py` | — |

### Traceability

- Commission: *KOS-SNF Research Simulation Refinement / Phase 1* (HPA, 2026-08-22).
- Boundary: SNF stays subordinate to `candidate → measurement → evidence → Port Contract/Governance`; migration and architecture frozen.
- Supersedes/extends: the prior contaminated simulation's C=0/T=0 record (diagnosed, not trusted).
- **Strongest statement made equals the strongest evidence held** (§16 limits apply).
