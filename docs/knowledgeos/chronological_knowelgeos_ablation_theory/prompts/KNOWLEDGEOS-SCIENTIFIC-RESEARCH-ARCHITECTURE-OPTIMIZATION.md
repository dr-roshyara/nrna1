# KNOWLEDGEOS — SCIENTIFIC RESEARCH ARCHITECTURE OPTIMIZATION

| | |
|---|---|
| **Kind** | architecture / design synthesis. ⚠ **PROPOSED — authority: generated. Not authoritative without human review.** |
| **Commission** | human, 2026-09-25, "KNOWLEDGEOS — SCIENTIFIC RESEARCH ARCHITECTURE OPTIMIZATION" |
| **Relation to the frozen architecture** | **subordinate.** ARCH (`…/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md`) says: *"The Phase-1 and Phase-2 protocols reference this document. They must not duplicate or redefine it"* (L8); *"A protocol that contradicts it is defective"* (L486). This document **adds no bounded context, no epistemic level and no governance authority.** It maps the commissioned scientific layers onto the existing architecture, the Phase-1 Master Protocol (**MP**) and the Phase-2 protocol (**S2**), and marks every addition PROPOSED. An addition that would change ARCH needs a §9 proposal and an L0 act (ARCH standing itself: GI-1 open). An addition to MP or S2 needs an L0 protocol act (L0-DEC-22/26 precedent) |
| **Search before constructing** | ARCH ACL-3 (L170), S2 §5C.4 / Q53. S2 was read at L386–424, L982–1231, L1269–1288, L1410–1434, L1560–1604, L1686–1704, L1736–1767, L1793–1957, L2015–2140, L2316–2463. MP was read in full earlier this session. **Most of the commissioned architecture already exists in S2 and MP (§0 below).** |
| **Corpus** | ⛔ **no corpus file read.** The "existing KnowledgeOS synthetic benchmark research" lives **inside the corpus** (§12). It was **not read**: that would be a corpus read (L0-DEC-30) and would use corpus content as design input |
| **Tags** | **EXISTING** (established in ARCH / MP / S2 / GOV, with a citation) · **PROPOSED** (synthesis in this document) · **TO BE VALIDATED** (a claim needing an experiment before use) · [O] open |

Paths below are under `docs/knowledgeos/knowledgeos_theory_chronological_extraction/`.

---

## 0. What already exists (ACL-3 result)

The commissioned layers are largely **EXISTING**. Re-creating them would be the duplication ACL-3 forbids.

| Commissioned element | EXISTING home | Citation |
|---|---|---|
| epistemic ladder evidence → … → canonical | S2 §1: **L0** historical evidence · **L1** reconstruction · **L2** hypothesis · **L3** derivation · **L4** validation · **L5** canonical (human act only) | S2 L391–396 |
| formalization stages | S2 §9: **F0** observed pattern · **F1** candidate formal structure · **F2** formal proposition (every term defined) · **F3** validated proposition | S2 L1742–1745 |
| maturity | S2 §11: **M0**…**M5**; M2 needs a *genuinely independent* reader; repetition never raises maturity | S2 L1903–1957 |
| origin of a statement | S2 §5C.2 ORIGIN axis **[C] [S] [E] [T]**, orthogonal to STRENGTH | S2 L1560–1572 |
| machine output has no authority | S2 I-14 and the six provenance classes (`SOURCE_ASSERTION` · `DETERMINISTIC_DERIVATION` · `MACHINE_PROPOSAL` · `HUMAN_ASSERTION` · `VALIDATED_FINDING` · `GOVERNANCE_ACT`); the ML port returns type `Proposal` | S2 L1151–1175 |
| ML in Phase 1 | MP §27: ML may assist candidate discovery, similarity, clustering, anomaly detection; it may **not** establish relationship, continuity, validity, identity or gap resolution. Workflow: ML candidate → complete-file reading → evidence → verification | MP L3400–3431 |
| candidate vs verified relationships | MP §8 candidate graph (sources include ML and embeddings) → verified graph | MP L1650–1705 |
| typed test agenda | S2 §13: **T1** corpus search · **T2** mathematical · **T3** statistical · **T4** logical · **T5** DDD · **T6** computational · **T7 synthetic benchmark** · **T8** external validation | S2 L2019–2028 |
| pre-registered falsifier | S2 §13.0 `Experiment.falsification_condition`, stated before execution; Q35 | S2 L2030–2050 |
| discovery / validation separation | S2 §13.0a: the four-stage experimental sequence (TEST → RAW RESULT → INTERPRETATION → IMPACT); *"Never formulate the hypothesis after seeing the result"* | S2 L2051–2069 |
| statistical discipline | S2 §13B: population · frame · unit · dependence · generalization · limitation for every count; Q49 | S2 L2111–2124 |
| hindsight / confirmation control | MP §29 (no rewriting of historical meaning by later knowledge); S2 §4.3b re-READ with `expected_finding` declared first; Q34 | MP L3463–3501; S2 L1269–1288 |
| anti-overfitting | S2 §5.3: the seed is a hypothesis *about* the corpus, not a template | S2 L1419–1434 |
| competing formulations | S2 §6.2 closed outcome set; I-6 (rivals coexist); `THEORY_NON_DETERMINATION_TEST` | S2 L1686–1696, L1202–1214 |
| three faults | S2 §10.3c THEORY / PROTOCOL / INSTRUMENT (+ RESEARCH_DISCOVERY); *"an instrument failure is NEVER evidence about the theory"* | S2 L1873–1884 |
| laboratory architecture | S2 §3B hexagonal: the domain depends on nothing; ports `CandidateGenerator` · `SimilarityScorer` · `TestRunner`; adapters LLM · ML · math/stats engines; *"do not over-engineer"* | S2 L982–1017, L1176–1192 |
| gates for experiments and statistics | `gates.yaml` KOS-G-045 no-silent-promotion (catalogue status `active`, **not activated**) · KOS-G-046 statistical-claims-stated-fully (`active`, not activated) · **KOS-G-047 falsifier-preregistered: `not_yet_defined`** | `governance/gates.yaml` L406–460; `governance-state.yaml` (5 gates activated: 001, 002, 003, 010, 022) |

**[D] What is genuinely missing** (the rest of this document proposes it):
1. a formal **hold-out architecture** (partially present as S2 M3→M4 *"corroboration from corpus outside the seeding window"*);
2. a **hypothesis-freeze record** that fixes the data split, metric and model version (S2 fixes only the falsifier);
3. an **active-learning** protocol for relationship verification;
4. an explicit **multi-clock temporal model**;
5. a **computer-logic pathway** (S2 names "a theorem prover" as a future adapter only);
6. **multiplicity and repeated-look control** (open as F-Series HDR-3; KOS-G-047 undefined);
7. **synthetic-world design rules** answering S2's own OQ-11.

---

## 1. Layer mapping — no new ladder

The commission's L0–L6 would **reuse labels already owned by S2 with different meanings**:
- commission "L2 candidate structure" vs S2 L2 "hypothesis";
- commission "L3 hypothesis" vs S2 L3 "derivation";
- commission "L4 formal model" vs S2 L4 "validation";
- commission "L5 validation" vs S2 L5 "canonical".

That is a term collision under ARCH **RA-9** (L184–201) and S2 **Q56**. S2 has already refused to add levels once (§13A: *"NOT a seventh level"*, L2072).

**PROPOSED:** the commissioned layers are **names for positions on the existing orthogonal axes**, written **SA-0 … SA-6** to avoid the collision. They are never stored as a level.

| Commissioned layer | SA-name | EXISTING representation (combination of axes) |
|---|---|---|
| L0 historical evidence | SA-0 | S2 **L0**; MP Evidence Object `E-####` (§6A); provenance class `SOURCE_ASSERTION` |
| L1 reconstruction | SA-1 | S2 **L1**; MP §9 File Reconstruction Record, §19A Theory Object (`CANDIDATE` / `RECONSTRUCTED`) |
| L2 candidate structure | SA-2 | **not a level:** MP §8 candidate edge · TDI entry (P1-Q1) · §19C candidate theory object · S2 **F0/F1** · provenance class `MACHINE_PROPOSAL` or `HUMAN_ASSERTION`. S2 places ML proposals at **L2** (L1170) [EXISTING] |
| L3 hypothesis | SA-3 | S2 **L2** + typed `falsifiable_as` (§5.2) + ORIGIN [S]/[E] |
| L4 formal model | SA-4 | the **formalization axis**: S2 **F2** (every term defined). Orthogonal to level (S2 §9) |
| L5 validation | SA-5 | S2 **L4** + **F3** + maturity **M3/M4**; the test result `SURVIVED` / `REFUTED` / `INCONCLUSIVE` |
| L6 canonicalization | SA-6 | S2 **L5** / ARCH Layer 5, **human act only** (RA-4 L295; S2 I-4, I-5b) |

**Invariant (EXISTING, S2 L396, L1137):** no process produces SA-6. SA-5 never implies SA-6.

---

## 2. Target flow and the preserved distinctions

```text
corpus ─▶ SA-0 evidence ─▶ SA-1 reconstruction ─▶ SA-2 candidates ─▶ SA-3 hypotheses
      (Phase 1, MP)                       (Phase 1 index / Phase 2 synthesis)
   ─▶ SA-4 formal models ─▶ tests (T1–T8) ─▶ blind / hold-out validation ─▶ SA-5 surviving candidates
      (Phase 2, S2: 2A recovery · 2B construction · 2C attack)
   ─▶ SA-6 canonicalization   ⛔ governance act, outside both contexts
```

**Eight distinctions, each carried by an EXISTING field:**

| Distinction | Carrier |
|---|---|
| historical fact | provenance class `SOURCE_ASSERTION` |
| reconstruction | `DETERMINISTIC_DERIVATION` / MP interpretation fields (§6A L1531–1536) |
| machine-generated proposal | `MACHINE_PROPOSAL` (no authority, I-14) |
| human interpretation | `HUMAN_ASSERTION`; `[E]` |
| mathematical derivation | S2 L3 + F-stage |
| empirical result | Experiment stage 2 RAW RESULT (§13.0a); `[T]` if it changes a formulation |
| validation | `VALIDATED_FINDING`; S2 L4 |
| governance decision | `GOVERNANCE_ACT` (unavailable to Phase 2); an L0 record |

---

## 3. First principle — framework neutrality (EXISTING, with two PROPOSED guards)

**EXISTING:**
- S2 §3B.3 *"theory-neutral core"*;
- `TheoryObject` carries **no type field** (S2 L1047–1068);
- Q33 *"representation derived from CONSTRUCT, never from a pre-specified kernel"*;
- Q20 *"structure SEARCH before staging; S-1..S-5 enter as input, never as the search space"*.

**PROPOSED guards:**
- **G-FN-1 — plurality at registration.** A formal model (SA-4) of a hypothesis is registered together with **at least one rival formalism, or an explicit `NO_RIVAL_FOUND` with the search recorded.** The rival may be a lattice vs a partial order vs a probability model, etc. This operationalises S2 I-6 (rivals coexist) at the formalization level.
- **G-FN-2 — no fit-as-evidence.** *"Framework X can represent the data"* is recorded as **expressiveness**, never as support (cf. §19 FM-5). Any sufficiently rich framework fits anything.

---

## 4. ML discovery architecture (PROPOSED; every method = a `CandidateGenerator` / `SimilarityScorer` adapter behind an S2 port)

**Rule (EXISTING: MP §27; S2 I-14):** ML output is a **`Proposal` / candidate**, never evidence and never a theory statement.

**Phase column:** P1 = Phase-1 discovery aid under MP §27 · P2 = Phase-2 synthesis aid.

| Method | Input | Output | Epistemic status | Leakage risk | Human role | Phase |
|---|---|---|---|---|---|---|
| lexical similarity (TF-IDF / BM25 / n-gram) | extracted unit text (post-read) | ranked candidate pairs | `MACHINE_PROPOSAL`; MP §8 candidate edge | low; but *same word ≠ same concept* (MP §19A) | verify by complete-file reading | P1 |
| semantic embeddings (sentence encoders) | units, definitions | vectors; nearest neighbours | `MACHINE_PROPOSAL` | **high:** a pretrained model carries external priors and possibly knowledge of later text. Record the model id and version; **never embed later files when constructing a file's own record** | verify; record the model version | P1 / P2 |
| temporal semantic drift (per-term embedding shift across `historical_sequence_date` bins) | the same term's definitions over time | a drift score per term | an **exploratory signal** only | **high** if bins are built on registry order instead of MP §3 dates | a human classifies per MP §17 (`SEMANTIC_DRIFT` vs `REFINEMENT` vs `COMPETING_DEFINITION`…) | P1 signal → P1 record |
| clustering (HDBSCAN / agglomerative, with a stated linkage) | vectors | clusters | a candidate grouping; **never an ontology** (FM-4) | medium: cluster count and parameters are researcher degrees of freedom → fix them before inspection (§14) | label candidate `TH-` thread seeds; MP §19B identity test | P1 / P2 |
| graph construction | verified and candidate edges | typed graph (read model) | derived view (MP §41 Tier 3) | low | none (deterministic) | P1 |
| graph embeddings / community detection (e.g. node2vec, Leiden) | the verified-edge graph | communities | a candidate structure (SA-2) | **high** if candidate edges feed it (a self-fulfilling loop) → **verified edges only** | interpret; propose a hypothesis | P2 |
| entity resolution (pairwise classifier + blocking) | D- / T- records | SAME-candidate pairs with a score | `MACHINE_PROPOSAL`; MP §19A six-criteria identity test decides | **high:** label leakage from the active-learning set (§5) | adjudicate | P1 |
| active learning | candidate pairs + human labels | a query order; a classifier | an **instrument**, not evidence | **high** (§5) | the labeler | P1 |
| anomaly detection | file-level features (Phase-0 index, MP §8A) | outlier flags | a signal | low | inspect | P1 |
| contradiction detection (NLI model / rule patterns) | claim pairs | candidate `C-` contradiction | `MACHINE_PROPOSAL` | medium: NLI models conflate scope difference with contradiction (MP §22 lists seven resolution types) | classify per MP §22 | P1 |

---

## 5. Active-learning relationship classification (PROPOSED; ⛔ no reference set exists today)

**Label vocabulary — mapping required.** The commissioned labels (SAME, HOMONYM, CONTINUATION, REFINEMENT, EXTENSION, SPECIALIZATION, DERIVED-FROM, UNWITNESSED) are the **S-Series v3.5 P3 vocabulary** (prompt3 A11). MP owns different vocabularies:
- **§7** file relationships (L1628–1644: CONTINUATION, DERIVATION, EXTENSION, REFINEMENT, CORRECTION, CONTRADICTION, REPLACEMENT, SUPERSESSION, VALIDATION, APPLICATION, BRANCH, MERGE, NEW-RESEARCH-PATH, INDEPENDENT, UNCERTAIN);
- **§17** definition relationships (L2648–2661: SAME_DEFINITION, REFINEMENT, EXPANSION, NARROWING, BROADENING, CORRECTION, SEMANTIC_DRIFT, INDEPENDENT_REDERIVATION, COMPETING_DEFINITION, UNCERTAIN).

**PROPOSED:** the classifier targets **the MP vocabulary of the record type being classified.** A v3.5 label enters only via a recorded mapping table (RA-9 / Q56), e.g. `HOMONYM ↔ COMPETING_DEFINITION`. The mapping itself is [O] and needs human review. `UNWITNESSED` maps to MP `UNCERTAIN` / `NONE_FOUND` (MP §25) — never to `INDEPENDENT`.

**Procedure:**
1. **Seed labels.** A stratified sample of candidate pairs (strata: record type × candidate source × `historical_sequence_date` bin), labelled by ≥ 2 humans independently. Record inter-rater agreement (Cohen's κ / Krippendorff's α) — this is an **instrument-reliability measurement**. The seed is split up front into **train / calibration / blind-validation**, with the blind part **sealed** (hash recorded; unopened until §5.6).
2. **Uncertainty sampling.** Query the pair maximising predictive entropy `H(y|x) = −Σ_c p(c|x) log p(c|x)`. Alternatively use BALD (expected information gain `I(y;θ|x,D) = H[y|x,D] − E_θ[H[y|x,θ]]`) for an ensemble or Bayesian model. With **a diversity constraint** (e.g. a core-set, or one query per cluster per round), so that querying does not collapse onto one region.
3. **Human review.** The labeler sees the two complete passages (MP §2), not the model score. The score is hidden to avoid anchoring. The label is recorded as a `HUMAN_ASSERTION`.
4. **Model update.** Retrain on train + queried labels only. **Queried labels never enter the blind set.**
5. **Stopping.** Stop when both hold, fixed in advance:
   - (a) the maximum pool entropy falls below a threshold τ, **or** a label budget B is spent;
   - (b) the calibration-set expected calibration error stabilises (Δ < ε over k rounds).
   
   τ, B, ε and k are registered before step 2.
6. **Blind validation.** Open the sealed set once. Report per-class precision / recall with confidence intervals, and a confusion matrix. **This validates the classifier (an instrument), not any relationship (a theory claim).**

**Owner of the reference set: UNRESOLVED** — the same kind of question as F-Series H-17 / E-4. This document assigns none.

---

## 6. Temporal model (PROPOSED; builds on MP §3 and §0E)

**Six clocks, never conflated:**

| Clock | Meaning | Source |
|---|---|---|
| `t_hist` | the file's historical position: `historical_sequence_date` from `date_events[]` (`applies_to: THIS_FILE` only) | EXISTING, MP L1141–1158 |
| `t_reg` | canonical registry row | EXISTING, MP L1081–1101 |
| `t_read` | ingestion / read order (receipt time) | EXISTING concept (MP §36A; RCA §5 receipts, proposed) |
| `t_disc` | when a candidate, hypothesis or relationship was first recorded (`iteration_id`, UTC) | EXISTING in part (S2 Q32) |
| `t_train` | the data cut-off of any trained model or embedding used | **PROPOSED** |
| `t_val` | when a frozen hypothesis was tested, and on which split | **PROPOSED** |

**Hindsight rule (PROPOSED, reconciled with MP):**
- The **historical meaning** of file F (its FRR content fields) may use only F itself plus evidence with `t_hist ≤ t_hist(F)`. This operationalises MP §29 L3479 *"Do not rewrite an earlier file's historical meaning based on later knowledge."*
- **Cross-file relationships** that involve later files are **permitted** (MP §0E.1–0E.3 targeted forward investigation), but are recorded as **separate relationship records** carrying both endpoints' `t_hist`, never folded into F's content.
- Whether any stricter policy applies to the F-Series lane is **F-Series H-4 — not decided here.**

**Drift without identity claims (PROPOSED):**
- `drift(term, b1, b2) = 1 − cos(μ_b1, μ_b2)` over mean embeddings per `t_hist` bin, with a permutation null (shuffle bin labels) to express **how unusual** the shift is.
- A high drift score is an **exploratory signal** routed to MP §17 classification. It is never recorded as `SEMANTIC_DRIFT` without a human classification from complete-file reading (MP §27: ML may not establish semantic identity).

---

## 7. Evidence graph (PROPOSED as a **derived read model**, not a new authority)

```text
Evidence(E-) ─▶ Observation ─▶ CandidateObject ─▶ CandidateRelationship ─▶ Hypothesis ─▶ FormalModel
             ─▶ Prediction ─▶ Experiment(EXP-) ─▶ Result ─▶ Validation
```

- **Status:** a **Tier-3 derived view** (MP §41 L4392–4399: *"Generated from Tiers 1–2, never hand-authored"*) over EXISTING records:
  - MP `E-`, T-, G-, edges, `DI-`, `CT-`;
  - S2 seed items, `EXP-`, `THEORY-EVOLUTION`.
- It **creates no record type with authority.**
- **Edge attributes** (every edge): `provenance_ref` · `t_disc` (and `t_hist` of the endpoints) · `method` · `actor` · `asserted_by` (S2's six classes) · `model_id` + version where a model is involved · `confidence` (typed per MP §6A: evidence / relationship / identity / continuity / gap — never one scalar) · `level` (S2 L0–L5) · `origin` (Phase 2) · `f_stage` where formal.
- **Graph invariant (PROPOSED, mirrors S2 I-14):** no path from a `MACHINE_PROPOSAL` node to a `VALIDATED_FINDING` node may skip a `HUMAN_ASSERTION` or an executed `Experiment`.
- **Namespaces:** "Observation", "Prediction" and "Result" have **no MP/S2 ID namespace.** Minting one requires the collision check (MP §4A L1270–1290; GIA-6 / NR-1) — [O].

---

## 8. Mathematical formalization layer (EXISTING staging + PROPOSED record)

**EXISTING:**
- S2 §9 F0–F3; the F1→F2 gate *"every defining condition closed"* (Q14);
- §13A.1 *"a corpus phrase naming a structure is a POINTER, not the structure"*;
- §9.2 *"formalize only where the corpus already reasons formally"*.

**PROPOSED `FormalModel` record** (one per formalization of one hypothesis; append-only; revisions via S2 `THEORY-EVOLUTION`):

```yaml
formal_model:
  hypothesis_ref:           # the SA-3 item
  framework:                # graph | order | lattice | algebra | category | measure space | probability model | state machine | other
  rivals: []                # G-FN-1: other formal_model ids, or NO_RIVAL_FOUND + search record
  definitions: []           # every term; open terms cap f_stage at F1 (S2 Q14)
  axioms: []
  assumptions: []           # each with provenance: corpus [C] / expert [E]
  derived_propositions: []  # each with a proof status (§9 below)
  domain_of_validity:       # the regime (MP §20) — never silently universal
  counterexample_search: {method, bound, result}   # T2
  consistency_status:       # §9 vocabulary
  expressiveness_note:      # G-FN-2: what fitting the data does NOT show
  f_stage:                  # F1 | F2 | F3
```

---

## 9. Computer-logic layer (PROPOSED; engines are driven adapters behind S2 `TestRunner`)

**Pathway:** hypothesis (F2) → encoding → solver → result → Experiment stage 2 (RAW RESULT) → stage 3 (INTERPRETATION).

| Question | Technique | Result vocabulary |
|---|---|---|
| Are the axioms mutually consistent? | SAT / SMT (finite or decidable fragment); a finite model finder (e.g. Mace4-style) | `SAT` (a model exists) · `UNSAT` · `UNKNOWN` (timeout / undecidable fragment) |
| Does P follow from T? | SMT (`T ∧ ¬P` UNSAT ⇒ derivable in the encoding); an interactive theorem prover for full proofs | `DERIVABLE` · `NOT_DERIVABLE_IN_ENCODING` · `UNKNOWN` |
| Is there a counterexample to P? | model finding for `T ∧ ¬P` | `COUNTEREXAMPLE(model)` · `NONE_WITHIN_BOUND(bound)` |
| What is the minimal inconsistent subset? | MUS / unsat-core extraction | a core + minimality check |
| Does a state machine satisfy an invariant? | model checking (explicit or symbolic) | `HOLDS` · `VIOLATED(trace)` · `UNKNOWN(bound)` |

**Five notions, never conflated (PROPOSED terminology):**

| Notion | Meaning |
|---|---|
| satisfiability | ∃ a model of T |
| consistency | T ⊬ ⊥ (for first-order logic, equivalent to satisfiability by completeness; **not** for every logic in use) |
| derivability | T ⊢ P (syntactic) |
| validity | T ⊨ P (semantic) |
| empirical confirmation | a prediction from T survived a T1/T3/T6/T8 test on data |

⛔ The first four never imply the fifth (FM-6). `NONE_WITHIN_BOUND` is not `VALID`: it is a bounded search, the logic analogue of MP §15/§25 `NONE_FOUND` vs `INDEPENDENT`.

**Encoding fidelity:** each encoding is a `[E]` artifact with its own review. An encoding error is an **INSTRUMENT** fault (S2 §10.3c), never theory evidence.

---

## 10. Statistical layer (EXISTING discipline + PROPOSED controls)

**EXISTING:**
- S2 §13B six-part statement for every count;
- T3 settles estimands, independence, identifiability;
- KOS-G-046 statistical-claims-stated-fully (catalogue);
- S2 §11.1 repetition ≠ evidence.

**PROPOSED, per confirmatory test (registered in the §14 freeze record):**

| Element | Requirement |
|---|---|
| H0 / H1 | explicit, directional where claimed |
| estimand + effect size | e.g. a difference in proportions, Cliff's δ, or the AUC gain of a hypothesis-derived predictor over a baseline; reported with **uncertainty** (bootstrap or exact CI; or a posterior with the stated prior) |
| test | chosen before data access; permutation / exact tests preferred for small, dependent corpus counts |
| unit + dependence | S2 §13B; clustered by author / programme / day where needed (the RA-15 D tests; S2 §11.0) |
| multiplicity | families declared in advance; Holm (FWER) for confirmatory families, Benjamini–Hochberg (FDR) for screening families |
| repeated looks | **one confirmatory look per hypothesis per split.** A second look = a new registered hypothesis `-R1` on a fresh split. Alternatively alpha-spending, if pre-declared |
| out-of-sample | the confirmatory split is disjoint from the formation data (§13) |
| sensitivity / robustness | pre-listed perturbations: alternative dependence clustering, alternative drift bins, a leave-one-programme-out variant |

**Defences:**

| Threat | Defence |
|---|---|
| overfitting | disjoint splits; model complexity fixed at freeze |
| p-hacking / researcher degrees of freedom | the freeze record (§14) lists the test, metric, split, covariates and exclusions **before access** |
| selection bias | the population and sampling frame are stated (S2 §13B); the corpus is a **3,081-file canonical list vs 9,163 md files** (STATE) — the generalization limit is stated |
| repeated-look contamination | one-look rule; a hold-out access log |
| training / test leakage | `t_train < t_val`-split cut; hashed split manifests; embeddings fitted on formation data only |

---

## 11. Synthetic benchmark integration

**EXISTING constraint (S2 T7, OQ-11, §13B):**
- T7 *"cannot settle … anything, if built from the theory's own concepts"*;
- *"state its falsification condition first; if none can be stated, it is not a test."*

**The existing benchmark research is corpus material — not read [F].** `git ls-files` shows files named `…benchmark…` under `docs/knowledgeos/brainstorming/knowledgeos_theory_research/corpus/knowledge_os_with_lina_puran/`, for example:
- `20260918-102945_step-545_executing-synthetic-dependency-benchmark.md`;
- `…_step-546_quantitative-ml-dependency-benchmark.md`;
- `…_step-550_formal-benchmark-semantics-oracle-separation.md`;
- `…_step-542_common-mode-failure-benchmark-false-robustness-detection.md`;
- `20260920-162500_lg-06b_adversarial-unseen-topology-structural-equivalence-benchmark.md`;
- `20260919-130414_r586_adversarial-completeness-benchmark.py`.

**[D]:**
- Those are **historical evidence (SA-0)**. Under this architecture they enter only through Phase-1 reconstruction.
- Their benchmark *designs* become **candidate methods**, not the benchmark itself.
- Reading them now is a corpus read (L0-DEC-30) and would make the corpus's own theory concepts the benchmark generator — exactly S2 OQ-11's failure.

**PROPOSED benchmark design** (validates the **method**, never the theory):

| Element | Design rule |
|---|---|
| latent structure generator | drawn from a **family not derived from KnowledgeOS seed concepts**: random DAGs / partial orders / lattices / block models / Markov chains, with known parameters. Includes **competing latent structures** (two generators that produce similar surface statistics) |
| world renderer | produces synthetic "files" with known ground truth: which concepts, relationships, definitions, drifts, contradictions and dates |
| corruption | controlled noise: synonym substitution, homonym injection, date corruption (cited-date vs authorship-date confusion, mirroring MP §3's P3A error), missing derivation steps, distractor structures, duplicate / near-duplicate files |
| hold-out worlds | a set of generator seeds and **generator families** never touched during method development (§13 synthetic hold-out) |
| falsification condition (OQ-11) | stated per method before running, e.g. *"the relationship classifier fails if FDR > q or recall < r on held-out families"* |
| metrics | precision · recall · F1 (per relationship type; macro and micro) · FDR · **structural recovery** (e.g. graph edit distance, adjusted Rand index for clusters, order-embedding accuracy for orders) · calibration (ECE, reliability curves) · robustness (metric degradation vs corruption level) |

**Boundary:** benchmark success ⇒ *"method M recovers structures of family G under noise N"*. It never ⇒ *"KnowledgeOS theory T is true"* (FM-8).

---

## 12. Hold-out architecture (PROPOSED)

| Hold-out | Construction | What it validates | What it does **not** validate |
|---|---|---|---|
| **Temporal** | a `t_hist` cut c: hypotheses are registered using only reconstruction records with `t_hist ≤ c`; tested on records with `t_hist > c`. Consistent with S2 M3→M4 *"corroboration from corpus outside the seeding window"* | **predictive generalization forward in history**: does a hypothesis formed early anticipate later material? | whether it is *true*; later files may simply repeat earlier ones (dependence: collapse duplicates and same-programme files per RA-15 D / S2 §11.0) |
| **Structural** | whole relationship families or concept threads (`TH-`) withheld from model construction and from hypothesis formation | whether a discovered structure or method **transfers** to unseen concept families rather than memorising the seen ones | temporal validity |
| **Synthetic** | generator families and seeds unseen during method development (§11) | **method validity** under known ground truth | anything about the KnowledgeOS theory |

**Access control (PROPOSED):** every hold-out has a sealed manifest (IDs + hash). Opening it is an event with actor and time. A hold-out opened once is **spent** for the hypotheses registered against it.

**Tension recorded [O]:** MP requires every file to be read completely (§2) and in registry order (§3). A temporal hold-out can therefore hold out only from **hypothesis formation and model training**, never from Phase-1 reading. Hypothesis formation happens in Phase 2 on recorded material, so this is feasible. Whether Phase-2 formation may be scheduled ahead of Phase-1 coverage is already allowed by ARCH RA-13 (GI-1 caveat).

---

## 13. Discovery vs validation firewall (PROPOSED extension of S2 §13.0 / §13.0a)

```text
DISCOVERY / FREE EXPLORATION   (Phase 1 index; Phase 2 2A/2B; any ML; any look at formation data)
        ↓
HYPOTHESIS REGISTRATION         typed falsifiable_as (S2 §5.2) + origin + provenance
        ↓
FREEZE                          freeze record (below), hashed
        ↓
VALIDATION                      Phase 2C; one confirmatory look per split
```

**PROPOSED freeze record** (extends the S2 `Experiment`; S2 already fixes `falsification_condition` before execution):

```yaml
freeze:
  hypothesis_id:            # namespace [O]: "HYP-" needs the collision check (MP §4A, GIA-6)
  revision:                 # R0, R1, …  a changed hypothesis is a NEW revision, never an overwrite
  falsifier:                # typed (S2 §5.2)
  estimand_and_metric:
  test_and_threshold:
  data_split: {manifest_hash, holdout_type}
  model_versions: []        # embeddings, classifiers, solvers — with t_train
  multiplicity_family:
  exclusions_and_covariates:
  registered_at:            # t_disc; must precede the holdout access event
  frozen_hash:
```

- After freeze, the split, metric, falsifier and model version are fixed.
- Observations made during validation can only produce **a new revision** (`<id>-R1`) tested on **a fresh split**.
- This is the checkable form of S2 *"never formulate the hypothesis after seeing the result."*

**Gate relation:** KOS-G-047 falsifier-preregistered is catalogued `not_yet_defined`. Defining or activating it is a **governance** act (L0-DEC-15; `governance-state.yaml`). This document defines no gate.

---

## 14. DDD architecture — within the two existing bounded contexts

**EXISTING constraint:**
- ARCH: **one architecture, two bounded contexts** (L7), Phase 1 and Phase 2.
- ARCH §8B **rejected a separate Validation context**: *"a validation context would need its own ubiquitous language and invariants, and it has neither"* (L355–361). Validation is **2C**.
- MP §0D's five "areas of ownership" (L725–737) are not DDD bounded contexts (ambiguity A5, recorded earlier).

**PROPOSED:** the eight commissioned "contexts" are **modules (subdomains) inside the existing two contexts, plus the control plane.** None is a new bounded context. Aggregates are only those the governing documents establish; nothing is created "because it sounds useful" (S2 §3B.7).

| Commissioned context | Placement | Aggregate candidates (EXISTING only) | Invariants (EXISTING) | Commands | Events | Read models | ACL |
|---|---|---|---|---|---|---|---|
| Evidence | Phase 1 (1A) | `File`, `Evidence` (MP §41 L4250–4258) | RA-1 corpus immutable; RA-2 append-only; MP §2 complete file | AdmitSource · RecordEvidence | SourceAdmitted · EvidenceRecorded | manifest, receipts | corpus → Phase 1 (admission; RCA proposed) |
| Reconstruction | Phase 1 (1A) | `TheoryObject` (owns D/A/P), `Gap`, `ResearchObligation` (MP L4250–4258); `TheoryThread` is **not** an aggregate root (MP L4260) | MP §5A append-only; §28 no silent repair; §29 | ReconstructFile · ReviseRecord (§5A) | FileReconstructed · RecordRevised | graphs G_F / G_S / G_D / G_G (Tier 3) | — |
| Discovery | Phase 1 (1B) index + Phase-1 candidate graph | none new; TDI entries, candidate edges, `CT-` are records (MP P1-Q1, §8, §19C) | ACL-1 (L168); MP §27 | ProposeCandidate (ML port) · IndexFile | CandidateProposed · FileIndexed | candidate graph; TDI | ML adapters → `Proposal` type (S2 L1170) |
| Hypothesis | Phase 2 (2A/2B) | the S2 theory-neutral core (L1042–1053): Claim / Proposition / TheoryObject (Phase-2 overlay) | I-6 rivals coexist; I-14; Q51/Q52 origin | RegisterHypothesis · Freeze (PROPOSED) | HypothesisRegistered · HypothesisFrozen | seed, candidate theory document | Phase 1 → Phase 2 (RRP; ACL-1…4) |
| Formalization | Phase 2 (2B) | none new; `FormalModel` is a **record**, PROPOSED (§8) | Q14 (F1 cap) | Formalize | FormalModelRecorded | — | solver adapters (TestRunner port) |
| Experiment | Phase 2 (2C) | `Experiment` (S2 §13.0 record) | Q35; §13.0a stage order | RunExperiment | ExperimentExecuted | agenda | engines behind ports |
| Validation | **Phase 2 (2C), not a separate context** (ARCH §8B) | — | S2 §10.4 verification may not promote; I-15 | Validate (per S2 §10.2 modes) | Survived · Refuted · Inconclusive | maturity report | independent-reader channel |
| Governance | **control plane**, outside both contexts (GOV; ARCH RA-16, GI-1 caveat) | — (owned by governance, not by this architecture) | L0-DEC-15; KOS-G-060 | — (human acts) | AuthorityAct (S2 I-5b) | L0 record | Phase 2 cannot emit `GOVERNANCE_ACT` (S2 L1156) |

**Three architectures, never merged:**
- the historical architecture discovered in the corpus (MP §0C HA-###);
- the researcher's proposed KnowledgeOS model (Phase 2, `[E]`);
- this **scientific research** architecture, which is infrastructure and method, never a KnowledgeOS-theory claim (S2 §3B.1: *"KnowledgeOS theory is an aggregate WITHIN that domain … It is not the domain"*).

---

## 15. Efficiency analysis (conceptual; no fabricated numbers)

| Class | Work | Basis |
|---|---|---|
| **Automated** | hashing, manifests, receipts; provenance-chain completeness; counts with the §13B six-part template; lexical / semantic similarity; clustering; graph construction (Tier 3); statistical computation of pre-registered tests; SAT/SMT/model checking of registered encodings; benchmark generation, execution and scoring; hold-out access logging; the mechanical Q-gate checks | MP §41 Tier 3 "never hand-authored"; S2 I-14 permits `DETERMINISTIC_DERIVATION` without a human |
| **Human-reviewed** | identity ambiguity (MP §19A); relationship labels (active learning); semantic interpretation; hypothesis registration and freeze; framework choice, rivals and encodings (`[E]`); falsifier definition; drift classification; contradiction resolution type | MP §27; S2 §10.2 INDEPENDENT; RCA §9 (proposed) *"what automation must not decide"* |
| **Human-only governance** | authority, release, adoption, rejection, canonical status (SA-6), gate activation | ARCH RA-4; L0-DEC-15/27; S2 I-4/I-5b |

**Where the workload reduction comes from (conceptual):**
1. **From pairwise search to ranked verification.** MP already forbids an O(N²) sweep (L995) and uses progressive search (§15). ML ranking replaces unranked candidate search with a queue, so human effort goes to the pairs most likely to matter.
2. **From random to informative labelling.** Uncertainty or BALD sampling needs fewer labels than random sampling *for a target accuracy* (a standard active-learning result; **TO BE VALIDATED on this corpus** via the §11 benchmark).
3. **From narrative to checkable claims.** Freeze records and solver runs turn arguments into re-runnable artifacts. Review then targets the encodings, not the whole reasoning.
4. **Cost moved, not removed.** Labelling, encoding review and independent verification are new human costs. The net effect is **unmeasured** and must be measured (TO BE VALIDATED).

---

## 16. Architecture comparison (trade-offs; no overall winner)

**Options:**
- **A** — protocol only (MP + S2 as they stand);
- **B** — A + ML discovery (§4–§6);
- **C** — B + formal logic (§9);
- **D** — C + mathematics records + statistics + synthetic benchmark + hold-outs (§8, §10–§13).

| Dimension | A | B | C | D |
|---|---|---|---|---|
| historical fidelity | high (complete reading, provenance) | same, **if** the §6 hindsight rule and MP §27 hold; lower if ML proposals are mistaken for evidence | same as B | same as B |
| leakage resistance | medium (S2 re-READ control, falsifier-first) | **lower** (embeddings, active learning add leakage paths) unless §5 splits and §6 clocks exist | same as B | **highest** (freeze + sealed hold-outs + clocks) |
| discovery power | limited by human reading bandwidth | higher recall of candidate pairs (TO BE VALIDATED) | same as B | same as B, with **measured** recall |
| falsifiability | present (typed falsifiers; T1–T8) but mostly **unexecuted**; no hold-out | unchanged | stronger for logical claims (consistency, derivability, counterexamples) | strongest: logical + statistical + out-of-sample |
| scalability to 3,081 files | low | higher | higher | higher, with more infrastructure |
| reproducibility | medium | lower unless model versions are pinned | higher for logic results | highest (manifests, seeds, frozen splits) |
| human workload | reading-dominated | reading + verification + labelling | + encoding review | + registration, freeze discipline |
| mathematical rigor | staged (F0–F3), little executed | unchanged | higher (machine-checked) | higher (+ statistical rigor) |
| implementation complexity | lowest | medium | medium-high | highest; S2 §3B.7 warns against over-engineering |

**Minimum architecture per research objective [D]:**

| Objective | Minimum |
|---|---|
| faithful reconstruction | **A** |
| scale discovery across the corpus | **B**, and its recall is unknown without D's benchmark |
| check that a formal theory is well-formed and consistent | **A + §9 only** (logic does not need ML) |
| a genuinely falsifiable theory | §17 |
| validated discovery method | **B + §11 benchmark + §12 synthetic hold-out** |

---

## 17. The central question

> **What is the smallest architecture capable of producing a genuinely falsifiable KnowledgeOS theory rather than merely a sophisticated reconstruction?**

**Answer [D].** The minimum is **A** (MP + S2 as existing) **plus three mechanisms**:
1. the **freeze record** (§13);
2. **one hold-out channel disjoint from hypothesis formation** — the temporal hold-out (§12);
3. an **executed** test path for the hypothesis's typed falsifier, with **genuinely independent verification** (S2 M2, §11.0).

**Why each is necessary:**
- **Without (1),** a hypothesis can be adjusted to the result, so it cannot fail (S2 §13.0a; FM-10).
- **Without (2),** every test uses data that shaped the hypothesis, so survival is not evidence beyond fit (§10; FM-1).
- **Without (3),** falsifiability stays nominal. S2 itself records that **no agenda test has been executed** (S2 L1952; Q41 *"failed once"*), and that SELF verification is *"demonstrably insufficient"* (L1797).

**Why ML, logic and the benchmark are not in the minimum:**
- **ML** increases discovery power, not falsifiability.
- **Formal logic** is required **only if the hypothesis is formal** (F2). Its falsifier type is then `MATHEMATICAL_COUNTEREXAMPLE` / `LOGICAL_COUNTEREXAMPLE`, and T2/T4 is its test path.
- **The synthetic benchmark** validates **methods**. It becomes required **as soon as an ML method is used for discovery**: otherwise that method's miss rate is unknown, and absence-of-candidate cannot be interpreted.

**Also required by the existing record, not by this design:** at least one hypothesis must reach **F2** (S2 §9.1 records none — *"Nothing in the window reaches F2"*, L1759). A proposition with open terms cannot be refuted.

---

## 18. Failure modes and controls

| # | Failure mode | Preventive control | Home |
|---|---|---|---|
| FM-1 | hindsight contamination | §6 hindsight rule; S2 §4.3b re-READ with `expected_finding`; temporal hold-out | MP §29; S2 Q34; PROPOSED §6, §12 |
| FM-2 | semantic drift mistaken for identity change | a drift score is a signal only; MP §17 classification by a human | MP §17, §27; PROPOSED §6 |
| FM-3 | ML similarity mistaken for truth | the `Proposal` return type; the graph invariant (§7) | S2 I-14; MP §27 |
| FM-4 | clustering mistaken for ontology | clusters = candidate groupings; MP §19A six-criteria identity test | MP §19A, §19C |
| FM-5 | mathematical elegance mistaken for validity | F1 cap on open terms (Q14); G-FN-2 expressiveness note; rivals (G-FN-1) | S2 §9; PROPOSED §3 |
| FM-6 | proof mistaken for empirical truth | the five-notion vocabulary (§9); derivability ≠ confirmation | PROPOSED §9; S2 §13.0a stage separation |
| FM-7 | implementation success mistaken for theory validity | S2 §13.0a asymmetries; realization layers (§13A); Q50 | S2 |
| FM-8 | benchmark success mistaken for theory validity | method ≠ theory boundary; OQ-11 falsifier-first; a non-seed generator family | S2 T7, OQ-11; PROPOSED §11 |
| FM-9 | statistical significance mistaken for importance | effect sizes + CIs mandatory; §13B population statement | S2 §13B; PROPOSED §10 |
| FM-10 | repeated experimentation leaking information | the one-look rule; `-R1` revisions on fresh splits; hold-out access log | PROPOSED §10, §13 |
| FM-11 | governance decisions contaminating historical reconstruction | Phase-1 records never cite governance acts as evidence; a GOVERNANCE_ACT is unavailable to Phase 2; MP `source_self_declared_status` keeps the corpus's own governance claims **as claims** | S2 L1156; MP L1981–1995 |
| FM-12 | active-learning labels leaking into validation | sealed blind split before querying | PROPOSED §5 |
| FM-13 | pretrained-model priors acting as hidden external theory | model id / version recorded; embeddings treated as `EXTERNAL` input (S2 I-13 pattern) | PROPOSED §4 |
| FM-14 | repetition counted as corroboration | dependence clustering; RA-15 D tests | S2 §11.1 / Q22; ARCH L418–424 (GI-1 caveat) |
| FM-15 | S-Series vocabulary silently replacing MP vocabulary | the mapping table (§5) | ARCH RA-9; S2 Q56 |

---

## 19. Blueprint — status of every component

| Component | Status |
|---|---|
| two bounded contexts; the Phase-1/2 handoff (RRP, ACL-1…4) | **EXISTING** (ARCH; GI-1 caveat) |
| Phase-1 records, gates, P1-Q1 TDI, ML policy §27, candidate→verified pipeline, §3 dates | **EXISTING** (MP) |
| S2 ladder L0–L5, F0–F3, M0–M5, ORIGIN, provenance classes, I-1…I-16, T1–T8, Experiment record, experimental sequence, §13B, Q1–Q61 | **EXISTING** (S2) |
| control plane, Research Release Check, human-only activation | **EXISTING** (GOV; L0-DEC-15/27) |
| SA-0…SA-6 naming (a mapping only) | **PROPOSED** |
| G-FN-1 rivals at registration; G-FN-2 expressiveness | **PROPOSED** |
| ML adapters (§4); drift measure (§6) | **PROPOSED**; methods **TO BE VALIDATED** (§11) |
| active-learning protocol; the label-vocabulary mapping (§5) | **PROPOSED**; mapping [O]; reference-set owner **UNRESOLVED** |
| six-clock temporal model; hindsight rule (§6) | **PROPOSED** |
| evidence graph as a Tier-3 read model (§7) | **PROPOSED**; new namespaces [O] |
| `FormalModel` record (§8); logic pathway and vocabulary (§9) | **PROPOSED** |
| statistical controls (§10); freeze record (§13) | **PROPOSED**; they require an S2 amendment (L0 act) |
| synthetic benchmark design (§11); hold-outs (§12) | **PROPOSED**; efficacy **TO BE VALIDATED** |
| workload reduction (§15) | **TO BE VALIDATED** |
| anything "canonical" | **none** — only a governance act (RA-4) |

---

## 20. Roadmap (not implemented; dependencies listed)

**Standing precondition for every stage [F]:** any execution needs a release through the existing **Research Release Check** (L0-DEC-27). The current release (L0-DEC-30) covers only Critical Attack Pass 01, with no corpus reading. Stages that read no corpus (benchmark construction, logic tooling) still need an L0-released **scope**.

| Stage | Content | Depends on |
|---|---|---|
| **1 — before any scientific discovery** | faithful Phase-1 records (MP) for a window; the six-clock fields; hold-out manifests sealed **before** hypothesis formation; the label-vocabulary mapping reviewed; freeze-record and `FormalModel` schemas adopted as S2 amendments | L0 protocol act for S2 changes; the governing-lane blockers (1b / C-5 / C-7 for chronological progression; L0-DEC-30 for any read) |
| **2 — automation** | hashing, provenance checks, Tier-3 graphs, §13B count templates, hold-out access logging | Stage 1 schemas; the ownership of execution-assurance machinery is a governance question (F-Series H-14a-type; **not decided here**) |
| **3 — ML acceleration** | §4 adapters behind S2 ports; active learning (§5) | Stage 2; a **human-labelled seed** (owner UNRESOLVED); §11 benchmark results for each method before its outputs are trusted as candidates |
| **4 — formal mathematics / logic** | `FormalModel` records; the §9 solver pathway on hypotheses at **F2** | at least one hypothesis at F2 (none today, S2 L1759); encoding review (`[E]`) |
| **5 — statistical validation** | pre-registered tests with multiplicity control | Stage 1 freeze record; KOS-G-047 defined and activated **only by governance** (L0-DEC-15) |
| **6 — independent hold-out validation** | open the temporal / structural hold-outs once per registered hypothesis; independent reader (S2 M2) | Stages 1, 5; availability of a genuinely independent reader (S2 §11.0: an *"experimental resource decision"*) |
| **7 — human canonicalization** | SA-6 | ⛔ governance only (RA-4; S2 I-4/I-5b). Nothing in stages 1–6 produces it |

---

## 21. Governance boundary

This architecture proposal does **not** decide:
- H-1;
- H-14a;
- RC-H-05;
- GIA-9;
- 1b acceptance;
- E-1…E-5;
- the L0 attestation channel.

It **does not authorize corpus execution**. It **does not modify the Master Protocol**, S2 or ARCH. It **does not implement F-Series.** It defines, activates or replaces no gate, and it creates no bounded context and no control plane.

Every S2/MP addition above is a **candidate protocol change** requiring an L0 act, and any ARCH-level change requires ARCH §9 (standing: GI-1). The ownership of any machinery named here is **UNRESOLVED** and belongs to the governance lane.

---

> **DESIGN COMPLETE — IMPLEMENTATION AND CORPUS EXECUTION NOT AUTHORIZED BY THIS DOCUMENT.**
