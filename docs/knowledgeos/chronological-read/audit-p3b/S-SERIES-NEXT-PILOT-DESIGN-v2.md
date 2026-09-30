# S-Series Next Pilot Design v2: architecture audit and hardened design (design only; NOT executed)

**Scope:** S-Series only. It supersedes the pilot design section (§E–§F) of `S-SERIES-NEXT-ARCHITECTURE-BRIEF.md` (G-LOG-0058, left unchanged as v1).

**Nothing is executed:**
- no production batch; OB0018 not executed; H-19 SEALED; S5c PROHIBITED;
- v3.5, v1.7, the population, hubs, K = 64 and A'1 are unchanged;
- Gate C and OB0004 records are unchanged;
- no corpus content was read for this document.

**Tags:** OBSERVED / INFERRED / HYPOTHESIS / UNRESOLVED; **ARBITRARY** marks a threshold with no empirical or conventional basis.

---

# PART I — AUDIT

## I.1 S/F-ISOLATION-CHECK

| Item | Finding |
|---|---|
| Searched | every S-Series artifact of this line (load brief, root-cause report, pilot report, post-pilot brief, research-purpose gate, Gate C pre-registration and report, next-architecture brief, G-LOG-0050…0058, `pilot-s5-decomp/**`, `scripts/p3b_s5_pilot.py`, pilot contract, session log, CONTEXT block) for `F-SERIES`, `F-Series`, `F3082` |
| Found | (a) one sentence, "no F-Series material inspected or used", in the load brief's scope line; (b) case-insensitive false matches in **corpus text** ("end-**of-series** seam", S1524) inside pilot records |
| S-Series | all of the design reasoning and evidence cited here |
| F-Series | none present in any S-Series artifact. F-Series references in the wider session context (e.g. `F-SERIES-v1.2`, `F3082`) were **not** used in any S-Series document |
| Action | nothing to remove. **Status: CLEAN.** This design uses no cross-programme lessons |

## I.2 The four completeness states

| State | Exact definition | Observable evidence | Verifier | Failure condition | Measurable independently? | Can be confused with |
|---|---|---|---|---|---|---|
| **READ-COMPLETE** | every page 1..N of every required file consumed | page ledger: pages logged, hashes recomputed, spans tile the content | READ-COVERAGE gate (exists) | a missing or forged page | yes, mechanically | **DELIVERED** (OBSERVED: S2276 page 2 was logged but only previewed). The ledger proves delivery, not consumption |
| **RECONSTRUCTION-COMPLETE** | every layer-A obligation of the label filled under the production rules | object record; verifier PASS | production verifier + §21 audit | verifier FAIL; audit PROTOCOL-VIOLATION | form: yes (verifier). **Correctness only via audit** | "verifier PASS" alone (form ≠ correctness) |
| **RESEARCH-EXTRACTION-COMPLETE** | every material finding a read file supports is registered, or explicitly declined | **no ground truth.** Only estimable **relative to independent extraction(s)** | proposed: segment inventory + independent re-inventory + capture–recapture (§II.12, §II.15) | an independent extractor finds a material, file-supported finding that is not registered | **estimable, not provable**. Shared blind spots are undetectable (§I.6) | READ-COMPLETE. Gate C suggests they differ (§I.3) |
| **THEORY-COMPLETE** | the theory is reconstructed and synthesized | not produced in P3b (v3.5 P7) | none in P3b | — | no | — |

## I.3 Is READ-COMPLETE ≠ RESEARCH-EXTRACTION-COMPLETE an empirical finding?

- **OBSERVED.** In Gate C the auditor classed 29 research-first findings as Strategy-A omissions from files A had read.
- **Critical qualifications (INFERRED):**
  1. **A's register was not a single-reader output.** It came from **decomposed units → file records → synthesis**, so the omissions may be **synthesis/decomposition loss** (four such losses are documented in G-LOG-0053), not single-reader extraction loss.
  2. The auditor did **not** see A's object records; some "omissions" may sit there.
  3. There is one auditor (same vendor), a stage-2 blinding weakness, and n = 2 labels.
- **Conclusion:** a **pilot-specific observation**. It shows that the pilot pipeline's registers omitted material from read files. It does **not** establish how complete a single exhaustive reader's extraction is (UNRESOLVED).
- The state distinction is **conceptually sound**: reading is mechanically provable, extraction is not. Its magnitude is unmeasured.

## I.4 Causal audit of the failure taxonomy

| Loss | Formal definition | Observable signature | Measured by | Can masquerade as / be masked by |
|---|---|---|---|---|
| **Candidate generation (CGL)** | a material reference finding *f* whose evidence is **present in B's R1 input** (mechanically: its anchor, S-id or key tokens occur in the package), yet no B candidate corresponds to it | *f* matched to no B candidate, and its R1 presence confirmed | a mechanical presence check plus auditor confirmation | **representation loss** (the package exists but the agent could not take it all in: the step-verify package was 508 KB, which is itself a capacity question); **classification loss** (seen but judged non-material); **matching loss** (a B candidate exists under different wording). Gate C's 7/12 attribution was a single auditor's judgment after unblinding, **not** a mechanical check (INFERRED: an upper bound on true CGL) |
| **Trigger loss (TL)** | a material **research** finding whose supporting evidence lies only in files B never triggered | *f* unmatched; its evidence files are outside B's frozen triggers | the trigger registry against the finding's evidence files | **reconstruction coverage.** Gate C's 34/38 are **P1-gap records**, content by construction outside R1, much of it reconstruction material (births, the programme's self-description). B had **no reconstruction obligation**. So the figure mixes true research-trigger loss with **absent reconstruction scope** (INFERRED) |
| **Extraction/register loss (XL)** | a material finding supported by a file the arm **read completely** that the arm neither registered nor explicitly declined | an independent inventory of the same file contains it; the arm's register does not | an independent re-inventory of the same files | **decomposition/synthesis loss** (A); **matching loss**; **materiality disagreement**. Valid as a basis for *estimating* RESEARCH-EXTRACTION-COMPLETE only **relative to** an independent extractor |
| **Reconstruction loss (RL)** | a layer-A obligation not established | verifier / audit | verifier + audit | should **not** be charged to an arm not tasked with reconstruction (see RCov, §II.15) |
| **Verification limitations** | lenient grading, off-scale verdicts, blinding leaks, correlated auditors | audit meta-checks | the checker + audit | inflate or deflate every metric |

## I.5 Audit of research-first v2 channels

| | Channel 1 (analyst R1) | Channel 2 (gap/null) | Channel 3 (reconstruction triggers) | Channel 4 (file-local extraction) |
|---|---|---|---|---|
| **Input** | the R1 package (pre-S5 layers) | **only these package fields:** P1 rows `types`, `type_signature`; reconciliation object `completeness_absences`, birth-candidate fields; P3a pair verdicts; stage-1 `terms`, raw/ledger hit counts; `files_touching` vs row sources; P2 lifecycle labels in family_md | **only:** 02-FILES best dates and date bases; stage2_files; row sources; reconciliation-object birth candidates; row `types` and `scope` | the pages of a file the arm read (paged reader) |
| **Output** | candidates + triggers | gap candidates with field pointers | trigger records | a segment inventory (§II.12) |
| **Deterministic** | no (LLM) | **yes** (script) | **yes** (script) | no (LLM); segment coverage is mechanically checkable |
| **Hindsight** | none if the package is pre-S5 | none | none, **but it inherits metadata errors** (e.g. example dates taken as file dates; OBSERVED S1022) | none (reads only frozen-triggered files) |
| **Information not available at the stage** | none | none | none | none |
| **Duplicates** | partly C2 | partly C1 | **overlaps the floor's obligations** | partly C1 (candidates from reading) |
| **Tests the claimed failure mode?** | baseline | CGL: yes. Risk: a candidate **flood** (null type signatures in 93/106 labels) with low information | **not research-trigger loss.** It tests **reconstruction coverage** by a discovery layer, a different question. Must be reported under RCov, **not** DR/TL | XL: yes, only if validated against an independent extractor (§I.6) |

## I.6 Aggressive audit of Channel 4 (the v1 checklist)

**The v1 design** was a class checklist with PRESENT/ABSENT per class and "other substantive" as the last class.

1. **Complete enough for "exhaustive"?** No. Missing classes: atomic claims or propositions, examples vs claims, hypotheses vs conclusions, quantitative results and data, experiments and tests, open questions, notation and terminology definitions, lineage or citations to other steps, scope statements, governance decisions.
2. **Is "other substantive" an escape hatch?** Yes. It makes exhaustiveness unfalsifiable.
3. **Can ABSENT be marked wrongly?** Yes. A class-level ABSENT is a single judgment and cannot be checked without re-reading.
4. **Who verifies?** v1 named no independent verifier of PRESENT/ABSENT.
5. **Is "substantive" discretionary?** Yes.
6–14. **Not specified:** nested concepts, repeated definitions, implicit relations, examples, hypotheses, formulas and notation, contradictions and corrections, and multi-file claims.
15. **Does the same checklist for the independent extractor make errors correlated?** Yes. A shared taxonomy produces shared blind spots, so completeness is overestimated.

**Verdict:** the v1 Channel 4 cannot support "exhaustive extraction". **It is redesigned as a segment-level proposition inventory (§II.12)**:
- mechanically checkable **segment coverage** replaces class-level PRESENT/ABSENT;
- "completeness" is replaced by a **measured extraction recall** against an independent, differently-instructed extractor.

## I.7 Audit of the reference arm A0

- **A0 is a reference process and a reconstruction baseline, not ground truth.** It approximates the corpus's findings with its own extraction loss. Gate C suggests that loss is non-trivial for pipeline-produced registers.
- **U = A0 ∪ E is legitimate only as an *observed union*.** Recall relative to U is **relative recall**.
- **If A0 and E both miss a finding, U cannot detect it.** That is a stated limitation.
- **Mitigation (HYPOTHESIS, not a cure):** capture–recapture across **independent** sources (A0, E, B2) estimates the unseen count. With two sources, Lincoln–Petersen: N̂ = n₁n₂/m; with three, log-linear models. This is **only under source independence**, which is violated by same-vendor models and shared taxonomy. The estimate is therefore a **lower bound** on the true total and reported as such.

## I.8 Audit of Reference E (v1: 30% of files read by A0 or B2)

- **Selection depends on the arms:** E's files are chosen from what the arms read, which is circular for XL(B2).
- **Sampling unit:** file-level sampling, while findings are the unit of interest.
- **Justification:** 30% and "at least 2 per label" are **ARBITRARY**. Stratification is unspecified.
- **Timing:** the sample was not fixed before the arms ran.
- **Independence:** E used the same checklist as the arms (correlated errors).
- **Redesigned (§II.6):**
  - E's file sample is **drawn before any arm runs**, from each label's mandatory set, stratified by file role;
  - E works **open-ended** (no class checklist) and, where possible, on a different model;
  - XL is computed at **finding level within sampled files**, clustered by file and label.

## I.9 Metric audit

| Metric | Issue | Revision |
|---|---|---|
| **DR** = (FB + 0.5·PM)/\|U\| | the 0.5 weight is **ARBITRARY**; heavy dependence on matching; splitting and merging of findings | report **DR_strict** = FB/\|U\| and **DR_lenient** = (FB+PM)/\|U\| as an interval; match on **deduplicated finding clusters**; **two independent matchers** on a sample, with **Cohen's κ** reported |
| **CGL** | "present in R1" was not mechanical | the denominator is the reference findings whose presence in R1 passes a **mechanical anchor/S-id/token check**, confirmed by the auditor; reported per channel (C1, C2) |
| **TL** | conflates reconstruction obligations with research discovery | **TL_research** only (material research findings, excluding layer-A obligations); reconstruction coverage is separate (RCov) |
| **XL** | E sample biased and circular | pre-drawn, stratified file sample; open-ended independent extractor; finding-level XL with a **cluster bootstrap** interval (files within labels) |
| **EQ** | "B2 weaker in ≤ 1/3" is **ARBITRARY** | descriptive distribution (A0-stronger / equal / B2-stronger) with counts; no threshold |
| **RC** | — | descriptive only |
| **HS** | v1 did not catch cross-arm leakage or indirect leakage | **hard** constraint, extended (§II.9) |
| **RCov** | B2 is not designed to produce layer A, so the metric is low by construction and biases a floor-vs-discovery decision | re-scoped as a **descriptive architectural-coverage metric**; it has **no role** in judging research discovery |

## I.10 Neutrality

- **v1 framed the pilot around supporting a two-layer architecture.** Its RCov rule ("< 0.80 ⇒ floor necessary") was **true by construction**.
- **v2 asks a neutral estimation question (§II.1).** Its decision rules allow **supported / not supported / mixed / inconclusive** outcomes.

## I.11 The four-label pilot

- **Four labels support an engineering and design pilot only.** They support no inference about the population.
- **The selection was not fully clean.** It used metadata only, **but** the pool thresholds (150–600 KB, at least 6 files, at least 3 row sources) and the strata were chosen by the designer. The seeded picks were **computed and published before any pre-registration**, which is an investigator degree of freedom.
  - **Fix:** re-draw with a seed **derived from the pre-registration commit hash**. The seed is then unknown until the rules are frozen.
- **The strata are unbalanced.** Template terms: 10 of 106 labels; early-file: 32; null type signatures: 93 (nearly universal, so weakly discriminating).
- **Interactions between failure modes cannot be estimated with 4 labels.** They can only be described.

## I.12 Thresholds

| v1 threshold | Pre-existing? | From Gate C? | Conventional? | Classification | Implicit preference |
|---|---|---|---|---|---|
| DR ≥ 0.80 | Gate C's pre-registered H1 bar | carried over, not derived | no | **ARBITRARY** | neutral |
| CGL ≤ 0.10 | no | no | no | **ARBITRARY** | favours B2 if lenient |
| TL ≤ 0.15 | no | no | no | **ARBITRARY** | — |
| XL(B2) ≤ XL(A0) | no | motivated by the 29 omissions | a relative comparison | **ARBITRARY**, but principled | — |
| EQ ≤ 1/3 | no | no | no | **ARBITRARY** | — |
| HS = 0 | yes (Gate C) | yes | an integrity constraint | **justified** (hard) | none |
| RCov < 0.80 ⇒ floor | no | no | no | **biased by construction** | favours the floor |

## I.13 Unit of analysis and statistics

- **Unit:** the deduplicated **finding cluster** nested in files, nested in labels.
- **No pooled significance test.** No test treats findings as independent (that would be pseudo-replication).
- **Reported:** per-label results plus cluster-bootstrap intervals, labelled **descriptive**.
- **Legitimate claims:** feasibility; failure-mode characterization; effect directions on these 4 labels.
- **Not legitimate:** corpus recall, superiority, scalability, model independence, production readiness.

## I.14 OB0018 separation

- **This pilot answers:** how discovery, extraction and evidence quality differ between the two processes on 4 small labels.
- **OB0018 answers:** whether the exhaustive floor can be executed on heavy labels (decomposition fidelity).
- **Left open by this pilot:** the floor's scalability.
- **Left open by OB0018:** the choice of discovery strategy.
- Neither is used as evidence for the other.

---

# PART II — HARDENED PILOT DESIGN v2

## II.1 Research question

Under controlled conditions, how do a reconstruction-first reference process (A0) and an improved research-first process (B2) differ in:
- discovery coverage;
- extraction completeness;
- evidence quality;
- reconstruction coverage;
- reading cost;
- protocol integrity?

## II.2 Hypotheses

The pilot is primarily an **estimation** exercise. The decision rules are in §II.16.
- **H0-D:** B2's relative discovery recall does not reach A0's level on material research findings.
- **H1-D:** it does.
- **H0-X:** B2's extraction loss exceeds A0's on commonly read files.
- **H1-X:** it does not.

## II.3 Scope

Non-production, namespace `PX0105`. 4 labels. No H-19, no S5c, no production state.

## II.4 Population

The S5 population (frozen) excluding hubs, tier Z and OB0004.

**Eligibility (pre-registered):**
- mandatory text set of 150–600 KB (the size bounds are **ARBITRARY**, justified only by single-context feasibility for A0);
- at least 6 required files;
- at least 3 row sources;
- no binary required file.

## II.5 Selection rule

- **Strata** (from package metadata only): (S1) early stage-2 file dated before all row sources, **and** OMQ-14 content; (S2) template-form stage-1 terms.
- **Draw:** 2 labels per stratum, without replacement, by sorted label order and `random.Random(seed)`. The seed is the integer of the first 8 hex digits of the **pre-registration commit hash**, so it is unknowable before the rules are frozen.
- **The v1 picks are void.** They were computed before pre-registration.

## II.6 Arms

- **B2 (research-first v2), run FIRST,** before A0 exists, to prevent leakage:
  - C2 and C3 scripts;
  - one R1 agent (C1) on blind packages;
  - a trigger freeze (hash committed);
  - R2/R3 agents that read triggered files and write the segment inventory (§II.12) with strict grading.
- **A0 (reconstruction-first reference), run SECOND:** per label, one single-context agent in the production procedure (revision-3 semantics), writing a layer-A object, a register, and the segment inventory for every file read.
- **E (independent extraction):**
  - **file sample fixed at pre-registration:** per label, stratified by role (stage-2 / row source / OMQ-14 / earliest-dated), max(4, ⌈30%⌉) files (**ARBITRARY size**);
  - reads its files whole and extracts **open-ended**, with no class checklist;
  - runs on a **different model** from the arms.

## II.7 Controls

Pre-registration before any run; trigger freeze; page ledger; strict verdict scale enforced by the checker; B2-before-A0 ordering; separate scratch directories; canary tokens (§II.9).

## II.8 Blinding

- **Materiality (stage 1):** a merged, shuffled list with statements only.
- **Matching (stage 2):** **structure-equalized** lists with an identical schema (label, statement, verbatim quotes with S-id and anchor). Verdicts and arm fields are withheld until matching is committed.
- **Post-unblinding corrections** are sensitivity only.

## II.9 Information-access rules and HS (hard)

HS = 0 is required. HS counts:
- (a) reads before the freeze;
- (b) untriggered reads;
- (c) post-freeze trigger or candidate changes (hash);
- (d) **cross-arm leakage**, detected by **canary tokens**: unique non-corpus strings embedded in A0 and E outputs (and their prompts). Their appearance in B2 output means leakage. Because of the B2-first ordering, A0 and E outputs do not exist while B2 runs;
- (e) indirect leakage: any B2 agent access to `PX0105-A*`, `-E*` or pilot and Gate C directories, from the agent's self-report and the orchestrator's prompt audit.

## II.10 Candidate-generation rules

- **C1:** analyst R1 on the package.
- **C2:** a deterministic script over the enumerated fields (§I.5). Each gap candidate carries its field pointer. **C2 candidates are capped per label** (e.g. one per anomaly type; **ARBITRARY**) so that nearly universal nulls do not flood the triggers.

## II.11 Trigger rules

- **Research triggers:** from C1 and C2.
- **C3 reconstruction triggers:** the 3 earliest-dated files; stage-2 files dated before all row sources; birth-candidate files; the earliest self-description file.
  - Kept, but **flagged**. Findings reached **only** via C3 are reported under RCov, **not** DR.
- **Budget:** ≤ 800 KB per R2 agent, with a deterministic split.

## II.12 Extraction contract (replaces v1 Channel 4)

1. **Segmentation (deterministic):** each read file is split into segments at Markdown headings. Where there are no headings, a segment is a reader page. The segment ids are computed by script and given to the extractor.
2. **Segment inventory:** for **every** segment the extractor emits **either** a list of propositions **or** `NO-SUBSTANTIVE-PROPOSITION` with a reason.
3. **Proposition record:**
   - `proposition_type` from a closed set: DEFINITION, NOTATION, CLAIM, RULE, FORMULA, THEOREM/RESULT, ALGORITHM, EXAMPLE, HYPOTHESIS, OPEN-QUESTION, CORRECTION, CONTRADICTION, RELATION (with target), LINEAGE (a cited step or file), GOVERNANCE, METHOD, SCOPE;
   - plus verbatim quote, anchor, and a `status` of ASSERTED / EXAMPLE / HYPOTHETICAL / RETRACTED;
   - formulas and notation are quoted verbatim;
   - a repeated definition is linked to its first occurrence;
   - nesting is expressed by `parent_proposition`;
   - an **implicit relation** is allowed only as RELATION with `explicit: false` and the text span that implies it.
4. **Multi-file claims** are out of the file-local inventory; they belong to synthesis and are measured separately.
5. **Register derivation:** every proposition is either promoted to a register candidate or declined with a closed reason (RESTATEMENT, EXAMPLE-ONLY, NON-MATERIAL per §5 rubric, OUT-OF-LABEL-SCOPE).
6. **Mechanical checks:**
   - **segment coverage** = inventoried segments / segments = 1;
   - every quote is verbatim-verified against the file (quote checker);
   - no off-scale types or statuses.

## II.13 Matching contract

- The auditor first **deduplicates within each arm** into finding clusters (clusters recorded).
- It then matches clusters across arms: FOUND-BOTH / PARTIAL / A0-only / B2-only / UNSUPPORTED / UNRESOLVED.
- **Reliability:** a second, independent matcher re-matches a stratified 30% sample (**ARBITRARY size**), and κ is reported.
- If κ < 0.6 (a conventional "moderate/substantial" boundary, **ARBITRARY**), DR is reported as **unreliable**.

## II.14 Independent audit contract

- **Auditor:** a different model from the arms, a different vendor if the human confirms one. It runs stage 1 (materiality, blind), stage 2 (matching, blind, structure-equalized) and stage 3 (after unsealing: loss attribution with the **mechanical R1-presence check** for CGL; files-read analysis for TL/XL).
- **Also audits:** A0's layer-A object (§21-style sample) and B2's R3 grading (off-scale verdicts are already refused).

## II.15 Metrics

**Definitions:**
- **U** = deduplicated material clusters from A0 ∪ B2 ∪ E.
- **U_R** = U minus pure layer-A obligations (the research findings).

| Metric | Formula | Nature |
|---|---|---|
| DR_strict / DR_lenient | FB/\|U_R\| ; (FB+PM)/\|U_R\|, relative to **observed** U_R | estimate with a cluster-bootstrap interval |
| CGL (per channel) | \|{f ∈ U_R : R1-present (mechanical ∧ confirmed), no B2 cluster}\| / \|{f ∈ U_R : R1-present}\| | estimate |
| TL_research | \|{f ∈ U_R : evidence only in files outside B2's triggers}\| / \|U_R\| | estimate |
| XL(arm) | on E's pre-drawn files that the arm read: \|E clusters not matched by the arm's register or declines\| / \|E clusters\| | estimate with a cluster bootstrap |
| Capture–recapture N̂ | log-linear estimate of total material clusters from the A0, B2 and E overlaps | **lower bound** (dependence) |
| EQ | counts A0-stronger / equal / B2-stronger | descriptive |
| RC | page bytes per VERIFIED material cluster, per arm | descriptive |
| RCov (architectural) | share of A0's layer-A obligations that B2 establishes with page-proven evidence (C3-reached included) | **descriptive; no decision role for discovery** |
| Segment coverage | inventoried / total segments, per arm | must be 1.0 (integrity) |
| HS | §II.9 | **= 0, hard** |
| κ (matching) | agreement of the two matchers | reliability gate for DR |

## II.16 Decision rules (pre-registered; thresholds ARBITRARY unless noted)

- **Integrity gate (not arbitrary):** HS = 0 **and** segment coverage = 1.0 **and** κ ≥ 0.6. If the gate fails, the result is **INCONCLUSIVE** (report only).
- **Two-layer hypothesis supported (these 4 labels):**
  - DR_strict interval lower bound ≥ 0.5 **and** DR_lenient point estimate ≥ 0.8 (ARBITRARY);
  - CGL(C1+C2) ≤ 0.15 (ARBITRARY);
  - XL(B2) ≤ XL(A0) + 0.10 (ARBITRARY non-inferiority margin);
  - TL_research ≤ 0.20 (ARBITRARY).
- **Not supported:** DR_lenient < 0.5, **or** XL(B2) > XL(A0) + 0.25, **or** CGL > 0.35.
- **Otherwise mixed:** reported per loss type.
- RCov and EQ are reported and never decisive.

## II.17 Statistical interpretation limits

Four labels. Descriptive only. No population inference, no significance tests, no model-independence claim. All intervals are conditional on these labels and this model pairing.

## II.18 Failure classification (for every unmatched cluster)

Candidate generation (C1 or C2) · representation (the package was too large to take in) · classification (seen but judged non-material) · trigger · extraction · synthesis · matching · reference-questionable · unresolved.

## II.19 Stop conditions

- **Stop immediately and report** on any HS violation, any hold-out refusal, or segment coverage < 1.0 that cannot be repaired within the run.
- No production step follows automatically.

## II.20 Provenance

- **Every output carries:** run id, contract/prompt sha, model id, the frozen-artifact hashes (packages, C2/C3 outputs, triggers, E sample, segment maps).
- **Every proposition** carries S-id, segment id, anchor and quote.

## II.21 Reproducibility

- **Deterministic:** the scripts for packages, C2, C3, segmentation, the E sample and the seed.
- **Stochastic:** the LLM stages. Their prompts and inputs are frozen; outputs are committed with hashes.
- A re-run is not expected to reproduce LLM outputs exactly, and variance is acknowledged.

## II.22 Relationship to OB0018

Independent (§I.14). Running this pilot neither requires nor implies OB0018, and vice versa.

## II.23 What the experiment cannot establish

- corpus-wide recall or extraction completeness;
- superiority of any architecture beyond 4 labels;
- model independence;
- findings missed by all of A0, B2 and E (the capture–recapture estimate is only a lower bound);
- the floor's scalability (OB0018);
- THEORY-COMPLETE.

---

# PART III — MINIMUM NEXT EXPERIMENT

**The v2 pilot's validity depends on two untested instruments:** (i) the segment inventory as an extraction instrument, and (ii) matching reliability. Running the 4-label pilot first would risk an **INCONCLUSIVE** result from instrument failure.

**Smallest design-validation experiment (V1; proposed, not executed):**
- **Files:** 6 files already read in the pilot (e.g. 3 constitution files and 3 step-verify files from `PX0004-U01/U02`). There are no new corpus labels, and **no comparison of strategies**, so it is not a reuse of Gate C evidence.
- **Arms:** two independent open-ended extractors (different models) and one segment-inventory extractor, on the same 6 files.
- **Measure:**
  - segment-coverage mechanics;
  - quote verification;
  - inter-extractor overlap and capture–recapture feasibility;
  - two-matcher κ;
  - time and bytes.
- **Decision it enables:** whether the §II.12 instrument and the §II.13 matching are reliable enough to use (e.g. κ ≥ 0.6; segment coverage mechanically attainable; the inventory captures at least what the open-ended extractors agree on).
- **Cost:** about 4 agents.

**Status of the designs:**
- **v1 (G-LOG-0058):** NEEDS DESIGN REVISION (done here).
- **v2 pilot:** NEEDS ADDITIONAL EVIDENCE (instrument validation V1) → then READY FOR PREREGISTRATION.
