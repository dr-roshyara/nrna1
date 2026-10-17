# Reconstructing a Theory from a Development Corpus
## The S-Series KnowledgeOS Investigation: Integrated Manuscript, Stage 1 (Registered Report, pre-data)

| | |
|---|---|
| **Version** | v0.4, 2026-10-17. v0.4 adds Ch. 15.7–15.8 (the corpus's own formal theory, as recorded in Session A's families, and its unification with §15.3, including realizable sufficiency) and applies the status-report corrections (contents, S-10 wording, Prop. 15.4 qualifier, consequence-3 correction). v0.3, 2026-10-17. v0.3 adds **Part III, Ch. 15**: the corpus-derived candidate theory (research hypotheses) under G-LOG-0141. v0.2, 2026-10-17: v0.1 was the first integrated draft. v0.2 integrates the reviewed findings of Sessions A and B: the scope of the confirmatory family, the confirmatory parameter gap, validity of the null generators, H2 measurement structure, linkage-judgement theory, and the pre-data process-theory candidate. See README §6 |
| **Author** | Session B (Professor / Principal Theory Author) |
| **Kind** | Stage-1 Registered Report. It states the research question, design, measurement, analysis plan, verdict framework and limitations **before any S5 evidence exists**. The results chapters are structured but empty. |
| **Status** | DRAFT, **not reviewed**. One review and one re-review are planned (Ch. 11.5); they need the Principal's authorization. |
| **Current outcome status** | **No programme outcome has been reached.** The proposed criteria (S-VC18 §7.5) describe the present state as `OPEN — INSUFFICIENT EVIDENCE` (codes `STAGE`, `CRITERIA`, `SCORING-UNAVAILABLE`). Adopting that vocabulary is V-1, which is undecided. |
| **Authority** | This manuscript **decides nothing**. Human acts exist only in the governance log (S-GL). Frozen artifacts are described here, never amended. Where this text and a source differ, **the source governs**, and the difference is a defect in this text. |
| **Evidence base** | Only the authorized governing and methodological documents listed in `SOURCE-REGISTER.md`. **No corpus content, S5 data, ledger, batch input, witness archive or hold-out material was read** (O1, G-LOG-0125; OP §22). |

---

## Reading conventions

**Citation keys.** `S-XXX` refers to a row of `SOURCE-REGISTER.md`. `GL-nnnn` means governance-log entry G-LOG-nnnn. Line references are written `S-XXX l.n`. A line reference locates a passage for the reader; it is not an identity anchor (S-MP R11).

**Claim-status tags.** Every substantive statement carries exactly one tag. A tag reports the status the *source* gives the claim. Repetition in this manuscript never raises it (S-MP R9).

| Tag | Meaning |
|---|---|
| **[DESIGN-FROZEN]** | Established design, frozen by a recorded human act |
| **[DESIGN-ADOPTED]** | Established design, adopted by a recorded human act but not frozen |
| **[FACT]** | Observed fact, directly evidenced in a cited record |
| **[DERIVED]** | Derived result: follows mechanically or deductively from cited facts. The derivation is shown or cited |
| **[PROPOSAL]** | Proposed and **not adopted** |
| **[DRAFT]** | A draft artifact, not reviewed to closure or not adopted |
| **[HYPOTHESIS]** | A hypothesis or conjecture under test, or to be tested |
| **[PLANNED]** | Planned test or stage: defined, **not performed** |
| **[OPEN]** | Open question, or a decision reserved for the Principal |
| **[CONFLICT]** | Sources disagree. Recorded, not resolved (see `SOURCE-REGISTER.md`, X-n) |
| **[PENDING]** | A result slot that will be filled only by authorized evidence |
| **[EDITORIAL]** | The manuscript's own exposition or synthesis; claims nothing about the corpus |

No statement here is a **final conclusion**. That tag is reserved for Chapter 12, after the governed process has produced one.

---

## Contents

1. Purpose and research questions
2. Conceptual foundations and terminology
3. Research architecture and scientific framework
4. Evidence model and provenance
5. Pre-registration and measurement design
6. Statistical and analytical methodology
7. Verdict criteria and scientific evaluation
8. Mathematical formalization
9. Falsification and robustness
10. Evidence-based reconstruction and theory recovery
11. Independent review and reproducibility
12. Scientific findings and final assessment
13. Integrated theory and implications
14. References, appendices and traceability
15. *(Part III)* The KnowledgeOS kernel: a candidate theory from the corpus (research hypotheses under G-LOG-0141)

---

# 1. Purpose and research questions

## 1.1 The object of study

The S-Series studies a **historical development corpus**: the files in which the KnowledgeOS ideas were written, revised, contradicted and abandoned over time.

- **[FACT]** The Phase-1 inclusion registry `02-FILES.jsonl` holds 2,779 records:
  - 2,767 are CONTENT files and 12 are FIREWALL-LIMITED;
  - by provenance, 2,026 are PRIMARY, 739 SECONDARY-SYNTHESIS and 14 PROVENANCE-UNRESOLVED;
  - the records carry 27,906 contribution rows (S-P3B §3.2, l.224–229).
  - "A file is in the Phase-1 corpus if and only if it has a record in that file" (S-P3B l.214).
- **[FACT]** Earlier phases (P1 capture, P2 normalization, P3a pair reconciliation) produced **2,497 candidate object labels**. A label is "a handle, not an identity claim" (S-P3B l.97–99, l.283; GL-0003).
- **[DESIGN-FROZEN]** The corpus has a fixed epistemic standing: it is **research material, not truth**.
  - "Nothing in the material is truth" (S-OP §1).
  - The researcher's rule treats it as brainstorming material (S-TODO l.22).
  - The project's own earlier syntheses are "DERIVED, never primary evidence, never auto-promoted" (S-MP R8).

## 1.2 Purpose

- **[DESIGN-FROZEN]** Master Protocol v3.5 states the purpose: to "construct the KnowledgeOS theory from the complete historical corpus" (S-MP l.6).
  - Its governing order is "RECONSTRUCT FIRST → RECONCILE LATER → CANONICALIZE LAST → SYNTHESIZE WITH PROVENANCE" (S-MP l.10).
  - Its terminal label is "RECONSTRUCTED, PENDING GOVERNANCE REVIEW … Never FINAL" (S-MP l.376–377).
- **[DESIGN-ADOPTED]** The operating protocol sharpens the purpose into a question with two admissible answers. The task is "to help determine, from the corpus and only after proper measurement and validation, whether a robust, defensible theory can be reconstructed. The legitimate outcomes are **ROBUST THEORY FOUND** or **NO COHERENT THEORY FOUND**. Assume neither in advance." (S-OP l.12)
- **[DESIGN-FROZEN]** The P3b annex adds a dual objective: "reconstruct what the KnowledgeOS theory actually says and how it develops, **and** to discover what it contains that is not yet known" (S-P3B l.91–92; GL-0055). It ranks the priorities as follows (S-P3B l.38–46):
  1. theory verification;
  2. falsification;
  3. independent recurrence and hold-out prediction;
  4. discrimination against controls;
  5. historical reconstruction, which is "the supporting layer that prevents hindsight".

## 1.3 Research questions

**[FACT]** The governance log refers to "the research question" but states it in no single sentence (S-GL l.63, l.1115). The questions below are this manuscript's reconstruction from the governing texts.

**[EDITORIAL]** Each question is traced to its source. **[OPEN]** The Principal is asked to confirm or amend the wording of RQ-0 (Ch. 14, Appendix E).

> **RQ-0 (programme question).** Under the frozen method, does the discovery population of this corpus support at least one coherent, falsifiable theory candidate that survives the pre-registered attack? Or does an adequate, completed search show that no such candidate exists?
> *Sources:* S-OP l.12, l.290. The two-sided form follows the outcome pair of S-OP l.12. "Discovery population" follows S-P3B §3.6 and S-ADD §1. The candidate/attack wording follows S-P3B §13.9–13.10 and the proposed P-1 (S-VC18 l.47), which is **[PROPOSAL]**.

The programme decomposes RQ-0 into four questions, which it keeps apart (S-B1 l.9).

| ID | Question | Kind | Governing source |
|---|---|---|---|
| **RQ-1** | Observation: can each label of the S5 frame be reconstructed under the frozen contract and accepted through VERIFIED → AUDITED → ACCEPTED? | measurement feasibility | S-OP §21 (AM-2); S-P3B §19–§25 |
| **RQ-2** | Reproducibility and correctness: how often does an independent same-protocol re-analysis disagree (θ_D), how often does blind human adjudication disagree (θ_A), and do pre-registered structural statistics survive a second reading (R2)? | measurement quality | S-B1 §1–§2 |
| **RQ-3** | Structure: do the pre-registered regularities H1, H2 and H4 (confirmatory) and H3 and H5 (exploratory) hold beyond Model 0? | pre-registered hypothesis tests | S-PRV2; GL-0118…0137 |
| **RQ-4** | Theory: do CORPUS-scale theory candidates arise that reach a computed evidence level (S-P3B §13.9b), withstand disconfirmation (§13.10) and, where they make empirical generalizations, predict the sealed hold-out (S-ADD §7)? | theory recovery and corroboration | S-P3B §9E, §13; S-ADD |

**[DERIVED]** RQ-1 and RQ-2 condition RQ-3. RQ-3 and RQ-4 condition any answer to RQ-0. No question can be answered before its predecessors have evidence.

**Two kinds of theory, kept apart [DERIVED; first stated by Session A, checked here; same-model].**
- **RQ-3 concerns a process theory:** how ideas in the corpus developed.
  - The confirmatory hypotheses H1, H2 and H4 speak about change classes, the direction of contradictions, and the order of birth kinds in a label's history.
  - So even full confirmation of all three could support at most a **process theory** of the corpus's development (Ch. 8.16).
- **RQ-4 concerns a content theory:** a theory of what KnowledgeOS *is*, meaning its concepts, invariants and structures.
  - This can come only from CORPUS-scale candidates recovered in S5b (Kind K, P3b §9E, §13).
  - Under the proposed V-2 (ii), such a candidate also needs a scored out-of-sample prediction (S5c).
- **Reporting rule.** Any final report must say which kind of theory its outcome concerns.

## 1.4 Possible outcomes and the present evidence status

- **[DESIGN-ADOPTED]** Two outcomes are named as legitimate (S-OP l.12, §27). The governing documents give **no criteria** for either (GL-0140 finding 1).
- **[PROPOSAL]** S-VC18 proposes criteria for both, plus a third outcome, `OPEN — INSUFFICIENT EVIDENCE`. That outcome holds "whenever neither §5 nor §6 holds. It is the default before evaluation" (S-VC18 l.259).
- **[CONFLICT X-11]** The third outcome is "defined in no governing document" (GL-0140). Whether to adopt it is V-1 **[OPEN]**.
- **[FACT]** The present evidence status:
  - **0 of 1,975** S5-frame labels are measured, and 0 of 396 batches (S-TODO l.5, l.8, l.47).
  - A label counts as measured only when "VERIFIED → §21 AUDITED → H-06 ACCEPTED is complete" (S-OP l.215).
  - No governance entry records an accepted S5 batch (S-GL to GL-0140; verified by Session B).
- **[DERIVED]** Under the proposed criteria, the only reachable outcome today is OPEN (S-VC18 §7.5; Proposition 8.5). Independently of any criteria, no evidence exists that could support or refute a theory.

**[EDITORIAL]** This manuscript therefore claims nothing about the content of a KnowledgeOS theory. It neither asserts nor denies that one exists.

## 1.5 Intended contribution

- **[FACT]** The working state names the ultimate milestone as "one coherent, evidence-linked KnowledgeOS Theory document" (S-TODO l.50).
- **[PROPOSAL]** A product architecture would be derived only after a ROBUST verdict and a canonicalization act. "If the verdict is NCTF or OPEN, this programme yields no product architecture" (S-C4 §7).
- **[OPEN]** The Principal has not yet stated the scientific contribution the theory is meant to make: its audience, its claimed generality, and its relation to existing knowledge-management theory. The working agreement reserves this to the Principal. Until it is stated, the manuscript's scope is the narrow one of §1.6.

## 1.6 Scope

- **[PROPOSAL]** Any verdict is "a statement **about this corpus's discovery population under this method** … never a statement that a theory is true of the knowledge-management domain, of other projects, or of the world" (S-VC18 P-1, l.47).
- **[DESIGN-FROZEN]** The census is not the discovery population: "'Census' never means 'the discovery population'" (S-P3B §3.6 rule 1).
  - While the hold-out is sealed, every record carries `population_basis: DISCOVERY-POPULATION (HS-3d32dd44d162)` (S-ADD §1–§2).

---

# 2. Conceptual foundations and terminology

## 2.1 Rule for terms

- **[DESIGN-FROZEN]** Terms are taken from the governing documents and keep their governing meaning. A term defined in a proposal is marked as such.
- **[EDITORIAL]** The manuscript coins no new theoretical term. ARCH RA-9 states this discipline: "No new theoretical term may reuse an existing corpus term with a different meaning" (S-ARCH l.186). ARCH's governing authority over the S-lane is undecided **[CONFLICT X-2]**, so the rule is adopted here as an editorial discipline, not cited as binding.

## 2.2 Glossary

| Term | Definition (quoted or closely paraphrased) | Source | Status |
|---|---|---|---|
| Registry | `02-FILES.jsonl`, "the authoritative Phase-1 inclusion registry" | S-P3B l.214 | DESIGN-FROZEN |
| `source_id` | S%04d, "the permanent citation and re-read key". Identity = source_id + commit + sha256; "Path is navigation" | S-MP l.47, l.70 | DESIGN-FROZEN |
| Census | "every `CONTENT` record in `02-FILES.jsonl`" (2,767) | S-P3B §3.6; S-ADD l.34 | DESIGN-FROZEN |
| Hold-out H / HF | H is the sealed set of hold-out labels; HF is "every `CONTENT` source linked to a label in H" | S-ADD l.28–36 | DESIGN-FROZEN (approved GL-0016) |
| Discovery population | "the census minus HF (files) and the labels minus H" while sealed | S-ADD l.30–33 | DESIGN-FROZEN |
| Object label | "a P2 candidate object handle; a handle, not an identity claim". Never merged, renamed or split by P3b | S-P3B l.283, l.298 | DESIGN-FROZEN |
| Tier U / X / Z | U touches no affected pair; X touches ≥ 1 affected pair (held until H-02); Z is pairless (semantic_status withheld) | S-P3B §9.3 | DESIGN-FROZEN |
| S5-eligible | Tier U or Z labels (2,020) | S-ADD l.100; GL-0006 | FACT |
| S5 frame | the 1,975 discovery Tier-U/Z labels in `P3B-SAMPLE-PLAN.jsonl` | GL-0018 l.417 | FACT |
| Hub | a frame label whose predicted stage-2 load exceeds 4,081 dispositions; 66 labels, frozen in `P3B-S5-HUBS.jsonl` | S-P3B l.9–12; GL-0033/0034 | DESIGN-FROZEN |
| Measured | a label whose batch reached BATCH-PASS and was ACCEPTED: VERIFIED → AUDITED → H-06 ACCEPTED. "Failed attempts and pilot or development evidence are never measured data" | S-OP §21 (AM-2, GL-0109) | DESIGN-ADOPTED |
| Acceptance (H-06) | a human entry after all gates pass and audit dispositions complete. For research records it "is **never** acceptance of their truth" | S-P3B §25 | DESIGN-FROZEN |
| Output layers A / B / C | A historical reconstruction; B research observation; C research hypothesis or proposal. "Never collapsed" | S-P3B §1B | DESIGN-FROZEN |
| Epistemic class | exactly one per claim: SOURCE, INFERENCE, RESEARCH-OBSERVATION, RESEARCH-SUGGESTION, HYPOTHESIS, THEORY-CANDIDATE, EXTERNAL-THEORY-COMPARISON, DOMAIN-INTERPRETATION, VERIFIED, GOVERNANCE | S-P3B §11.1 | DESIGN-FROZEN |
| Status vector (B4) | independent fields (evidence, identity, form, lifecycle, validation, governance, origin), "never one enum" | S-MP R15, B4 | DESIGN-FROZEN |
| Research register | append-only `P3B-RESEARCH-REGISTER.jsonl`; record kinds are closed (OBSERVATION, GAP, SCHEMA-LIMITATION, METHODOLOGICAL-DEFICIENCY; SUGGESTION-RESEARCH, SUGGESTION-METHOD, HYPOTHESIS, STRUCTURE-CANDIDATE) | S-P3B §13.2, §24.1 | DESIGN-FROZEN |
| Scale | OBJECT (S5), CROSS-OBJECT (S5a/S6a), CORPUS (S5b) | S-P3B §9D | DESIGN-FROZEN |
| Theory candidate | "not a record kind": a HYPOTHESIS or STRUCTURE-CANDIDATE at `scale: CORPUS` with `epistemic_class: THEORY-CANDIDATE`. "Remain hypotheses" | S-P3B §13.2, §1 | DESIGN-FROZEN |
| Lifecycle | OBSERVED → ANALYSED → HYPOTHESIS-STATED → TEST-DEFINED → TESTED → STATUS | S-P3B §13.9 | DESIGN-FROZEN |
| STATUS | SUPPORTED-IN-CORPUS · REFUTED-IN-CORPUS · UNDETERMINED-FROM-CORPUS · ROUTED-TO-GOVERNANCE ("never a truth value") | S-P3B §13.9a | DESIGN-FROZEN |
| Evidence level | PATTERN-OBSERVED → RECURRENT → DISCRIMINATING → PREDICTIVE → THEORY-SUPPORTED; computed and cumulative; "No level … means 'verified' or canonical" | S-P3B §13.9b | DESIGN-FROZEN |
| `claim_type` | closed: EMPIRICAL-GENERALIZATION, HISTORICAL, DEFINITIONAL, STRUCTURAL, EXPLANATORY; frozen in the test plan | S-P3B §13.9b | DESIGN-FROZEN |
| Independent occurrence | a participant set with a PRIMARY source per role, no shared or duplicate source, no shared member label, not SELF-DERIVED, not DESCRIPTIVE and not a control | S-P3B §9E.3 | DESIGN-FROZEN |
| DESCRIPTIVE / DISCOVERY | a finding whose structure is its generator's defining property is DESCRIPTIVE and "never counts as discovery or recurrence" | S-P3B §9E.1 | DESIGN-FROZEN |
| `temporal_scope` | UNORDERED · WITHIN-SLICE [t] · ACROSS-TIME; computed mechanically (A.10); MTIME excluded | S-P3B §14.4c | DESIGN-FROZEN |
| Model 0 | per hypothesis, the null "preserv[ing] exactly the method + frequency information" | S-B1 §3 | DESIGN-FROZEN |
| θ_D / θ_A / θ_E | reconstruction disagreement with an independent same-protocol re-analysis; disagreement with blind human adjudication; error relative to a declared reference standard R\* (only if justified by a human act; otherwise NOT-ESTABLISHED) | S-B1 §1 | DESIGN-FROZEN |
| R1 / R2 (reproducibility) | R1: record reproducibility (θ_D per decision field). R2: structural invariance of each pre-registered statistic across two reconstructions (R2-INVARIANT / R2-FRAGILE) | S-B1 §2 | DESIGN-FROZEN |
| Confirmatory / exploratory family | {H1, H2, H4} at Holm α = 0.05; {H3, H5} at BH q = 0.05 | S-PRV2 l.68–71; GL-0128…0136 | DESIGN-FROZEN |
| O1 (D-ETR-1) | "Theory recovery follows S5a/S5b (P3b §9D/§9E) and G-LOG-0028 after S5b; no early corpus reads." | GL-0125 (S-GL l.2010) | DESIGN-ADOPTED |
| Recovery charter | "a separate, versioned charter **after S5b**", defining any formal methodology for theory synthesis | GL-0028 l.596 | PLANNED |
| S5a / S5b / S5c | cross-object pass; corpus pass; hold-out unseal and scoring | S-P3B §8, §19.1 | DESIGN-FROZEN (S5c PROHIBITED, GL-0045) |
| Kind P / Kind K claim | Kind P: a confirmatory prereg_v2 hypothesis. Kind K: a P3b CORPUS candidate record with a persisted test plan (K-EG if EMPIRICAL-GENERALIZATION) | S-VC18 l.64–67 | PROPOSAL |
| Coherent core; E_close | a satisfiable, link-connected claim subset of size ≥ k_min; E_close is the completion of the recovery pass under a pre-declared stopping rule | S-VC18 §3, l.74–88 | PROPOSAL |
| RA-15 statuses | A SOURCE-SUPPORTED · B RECONSTRUCTION-VALID · C THEORY-CONSISTENT · D INDEPENDENTLY CORROBORATED; "never collapsed" | S-ARCH l.392–426 | FROZEN as F-lane architecture; S-lane authority **CONFLICT X-2** |

## 2.3 Relations between the principal concepts

**[EDITORIAL]** The concepts form three orthogonal axes. Keeping them apart is the main conceptual discipline of the programme.

1. **The population axis.** Registry ⊇ census ⊇ discovery population. Labels ⊇ S5-eligible ⊇ S5 frame. The hold-out lies outside the discovery population until the one unseal.
2. **The epistemic axis.** Source claim ≠ our assessment ≠ agent observation (S-MP R6). Layers A, B and C are never collapsed (S-P3B §1B). The evidence level is computed, never asserted (§13.9b). Acceptance is not truth (§25).
3. **The governance axis.** A proposal is not an adoption, and an adoption is not a freeze. "No authorization approves an artefact that does not yet exist" (S-OP §22). Layer 5, canonicalization, is reachable only by a governance act (S-MP GATE; S-ARCH §3, used as a lens).

**[DERIVED]** One consequence: a statement may carry at most the weakest status its chain supports. A SUPPORTED-IN-CORPUS theory candidate built on labels that are not measured is impossible, because the register records arise only in S5 passes, which consume accepted batch outputs (S-P3B §19.1).

## 2.4 Term collisions inside the record

**[FACT]** Several identifiers are reused with different meanings across the S-Series records. The manuscript therefore always qualifies them.

| Identifier | Meanings found | Manuscript form |
|---|---|---|
| O1 | (i) the human decision closing the V1.2.4 E0 line (GL-0077); (ii) D-ETR-1 = O1, the theory-recovery ordering (GL-0125) | "O1 (GL-0125)" |
| R1 / R2 | (i) reproducibility levels (S-B1 §2); (ii) ROBUST conditions R1–R6 (S-VC18 §5); (iii) v3.5 rules R0–R20; (iv) option R1 vs R3 for untestable hypotheses (GL-0128) | "R1 (B1)", "R1 (VC)", "R1 (v3.5)", "rule R3 (GL-0128)" |
| G1…G5 | (i) measurement gates (GL-0110…); (ii) TODO lane-G items; (iii) S-C4 lane G | "gate G3" |
| H1…H5 | (i) the pre-registered hypotheses; (ii) S-C4 lane-H items | "hypothesis H1" |
| C-4 | (i) the verdict-criteria open item C-4 (S-VC18 §22.3); (ii) the S-C4 document; (iii) conditions C1–C4 of Lemma A (S-NAC4) | "item N-a/C-4" |
| F-01 | (i) conformance finding F-01 (GL-0068); (ii) R7 audit finding F-01 (GL-0091) | "F-01 (GL-0068)" |
| "Master Protocol" | (i) v3.5 (S-lane); (ii) the F-lane `knowledge_os_protocoll.md` (GL-0068 F-02) | "Master Protocol v3.5" |
| P1 | (i) phase P1 capture; (ii) TODO item P1 (the working model); (iii) the prereg decision block P1-1…P1-6 | qualified in context |

---

# 3. Research architecture and scientific framework

## 3.1 Governing hierarchy

- **[FACT]** "The S-lane is governed by Master Protocol v3.5 plus the P3b v1.7 annex (G-LOG-0034); no architecture is declared above it" (GL-0069, S-GL l.1186).
  - P3b is "an operating annex under v3.5, not a successor master protocol" (S-P3B §6, D-01).
  - It inherits "R0–R20 in full" and the P4–P7 definitions and GATE unchanged (S-P3B l.417).
- **[DESIGN-ADOPTED]** The operating protocol governs "**how** sessions work. It grants no authorization." The frozen design, contracts and freezes prevail over it (S-OP l.6).
- **[CONFLICT X-2]** Research Architecture v1.2 (S-ARCH) is frozen and self-describes as "GOVERNING ARCHITECTURE".
  - The governance record classifies it as an F-lane artifact, "not declared governing for the S-lane" (GL-0068 F-01).
  - Decision A (keep / adopt / controlled adoption / defer) is undecided (S-TODO l.353, l.474).
  - Later research documents nevertheless rely on its invariants (S-C4 l.9; S-TT1; S-VC18).
- **[OPEN]** Whether ARCH governs the S-lane is a decision reserved for the Principal. Until it is taken, this manuscript uses ARCH as an **analytical lens**: it explains, it binds nothing.

## 3.2 Stage model

**[DESIGN-FROZEN]** The S-lane is executed as the P3b stage sequence (S-P3B §8, l.457–474).

```
S0 PREFLIGHT → S1 INPUT FREEZE → S2 TIERING → S2b IDENTITY RESOLUTION → S3 MECHANICAL PREP
  → [H-19 addendum approved] → S3b HOLD-OUT AND SAMPLE PLAN → S4 PILOT-R2
  → S5 BATCHES (Tier U, Z) → S5a CROSS-OBJECT PASS → S5b CORPUS PASS
  → S5c HOLD-OUT UNSEAL AND SCORING (per addendum)
  → [S6 TIER-X DISPOSITION (per H-02) → S6a cross-object re-pass] → S7 CLOSE
  → v3.5 A11 TERMINAL → P4 (v1.2 membership) → P5 (validation) → P6 (governance) → P7 (synthesis) → GATE
```

The status of each stage:

| Stage | Status | Record |
|---|---|---|
| S0–S3 | completed (PASS) | GL-0003, 0005, 0006, 0013–0015 **[FACT]** |
| H-19 addendum | approved, variant S | GL-0016 **[FACT]** |
| S3b | hold-out SEALED (HS-3d32dd44d162); sample plan over 1,975 labels | GL-0018 **[FACT]** |
| S4 | pilot executed and closed | GL-0019…0030 **[FACT]** |
| S5 | **not authorized** (gates G3–G5 open); 0 of 396 batches | S-TODO l.8 **[FACT]** |
| S5a, S5b | not started; protocol only ("UNBUILT", S-C4 §3.2) | **[PLANNED]** |
| S5c | **PROHIBITED** (seal breach, P3B-ESC-0001) | GL-0045 **[FACT]** |
| P4–P7, GATE | not started | **[PLANNED]** |

## 3.3 The provenance chain as the backbone of the framework

**[DESIGN-ADOPTED]** The operating protocol maps the S-Series onto one chain, in which every arrow is a gate (S-OP §20, l.209):

> source evidence → S5 per-label reconstruction (VERIFIED → §21 AUDITED → H-06 ACCEPTED) → S5a/S5b → P3b terminal (§23) → P4 → P5 → P6 → P7 ('RECONSTRUCTED, PENDING GOVERNANCE REVIEW', never FINAL) → attack/validation (incl. H-19 by governance) → governance GATE → canonical.

The chain has two forbidden shortcuts (S-OP l.210–212):
- agent synthesis must never flow back to become source evidence;
- a system-created artefact must never silently become corpus evidence.

The working state adds two immutable bans (S-TODO l.30–31):
- never SOURCE → ML → THEORY;
- never SOURCE → similarity → CANONICAL.

**[EDITORIAL]** ARCH describes the same discipline as two bounded contexts:
- historical reconstruction ("What did the corpus actually develop?");
- theory recovery, construction and validation ("What theory can be constructed from it?").

They are joined by a controlled handoff that "is an ACL, not a pipe". ARCH notes that "of the five substantive errors made so far, four occurred at the translation from evidence to theory" (S-ARCH l.52–75, l.131). This is the lens, not the rule; see §3.1.

## 3.4 Measurement machinery

- **[DESIGN-FROZEN]** Per-label reconstruction in S5 is executed by the R7 engine.
  - R7 was accepted at GL-0092 and production-activated at GL-0102. Its contract is currently v2.8.1, sha `7b68cbc9…` (GL-0108).
  - A batch passes when its universe, witness, evidence and reconstruction checks all pass (BATCH-PASS := U ∧ W ∧ E ∧ R, strong Kleene; S-C4 §3.3, as reported).
- **[DESIGN-ADOPTED]** Execution evidence follows **Model D**: "the harness transcript is the authoritative execution witness within the declared trust boundary". Agent-authored read logs, provenance and evidence are untrusted (GL-0085 HD-2).

## 3.5 Gates

**[FACT]** The gate states as of GL-0140:

| Gate | Meaning | State |
|---|---|---|
| G1 | canary repair package v4.1, fork H | approved (GL-0110) |
| G1.5 | adjudication table v2 | approved (GL-0111) |
| G2 | rehearsal dispatch | run-1 approved, FAILED (GL-0112, 0113); run-2 approved, FAILED (GL-0115, 0116); **run-3 approved, execution DEFERRED** (GL-0117) |
| G3 | v2.9 approval and production rebind | open; items listed in GL-0117 |
| G4 | retire and counted canary | open |
| G5 | full S5 | open |

## 3.6 Dependencies between stages and lanes

**[FACT, as an analysis]** The current critical path, as analysed by the Researcher (S-C4 §1.2; S-TODO l.52, l.148–232):

```
Lane M (measurement):  quiet period + PREFLIGHT-OK → run-3 (non-counting) → G3 → rebind
                       → counted canary → S5 authorization (needs Lane V adopted) → S5 (396 batches)
Lane V (verdict):      N-a/C-4 → §22.3 items → V-12/V-13 → V-1 → V-2…V-11 → studies → adoption act
                       (must be complete before ANY S5 data; does not block run-3)
Lane R (after data):   S5a/S5b → P3b terminal → recovery charter → recovery pass (E_close)
                       → hypothesis tests → formalization + Model 0 → attack → H-19 ruling → verdict
Lane T (optional):     T-0/T-1 (theory track; O1 stays in force)
```

**[DERIVED]** Lane V must close before Lane M delivers S5 data. Pre-registration is meaningful only if its rules are fixed before the outcome can be seen (S-OP §17; S-VC18 §11.10: "all must be taken before any S5 data are seen").

---

# 4. Evidence model and provenance

## 4.1 Identity and citation

- **[DESIGN-FROZEN]** "Identity = source_id + commit + sha256 … Anchors are headings or verbatim quotes, never line numbers" (S-MP R11).
- **[DESIGN-FROZEN]** Every claim carries exactly one epistemic class (S-P3B §11.1).
  - Only SOURCE (with a verbatim quote) and INFERENCE (with basis INFERRED and the step written out) may set a P3b status.
  - Research classes live only in the register.

## 4.2 The three separations

1. **Claim vs assessment vs observation.** "Source claim ≠ our assessment ≠ agent observation. Never silently correct the source; flag it." (S-MP R6) **[DESIGN-FROZEN]**
2. **Layer A vs layers B/C.** "Layer-B and layer-C content never enters a layer-A field" (gate G-12). "Layer A never depends on the register." (S-P3B §1B) **[DESIGN-FROZEN]**
3. **Historical time vs research time.** "A research-time statement is never backdated to the S-id it cites" (S-P3B §1C). **[DESIGN-FROZEN]**

## 4.3 What does not count as evidence

**[DESIGN-FROZEN]**
- **Duplicates and repetition.** "Duplicate ≠ independent evidence; repetition is not confirmation" (S-MP R9). Recurrence uses independent occurrences only (S-P3B §9E.3).
- **Mechanical similarity alone.** "Same symbol · same name · same short code · similar wording · temporal proximity · same document · embedding or lexical similarity · … a mechanical clue alone" (S-P3B §11.3). In KSME-22D, "6 of 15 cases the mechanical clue was a false cognate".
- **Own artifacts.** "Own artifacts are DERIVED, never primary evidence" (S-MP R8). A self-citation guard excludes them at P0 (S-MP l.136–138).
- **Absence of contradiction.** "Absence of contradiction is never confirmation. 'Not found' is never 'false'. External theory never sets a corpus STATUS. Recurrence alone never supports." (S-P3B §13.9a)
- **External correspondence.** Comparison with external theory is "never historical evidence" (S-P3B §13.12).
- **Restricted sources.** SECONDARY-SYNTHESIS and PROVENANCE-UNRESOLVED sources cannot alone establish a birth, a FOUND or a CONTESTED status (S-P3B §14.2). FIREWALL-LIMITED files are "never read, never quoted, never inferred" (S-P3B §3.4).

## 4.4 Negative evidence must be earned

**[DESIGN-FROZEN]** A negative claim needs a search of declared scope. "A search bounded to the current group or batch that finds nothing yields UNWITNESSED, labelled NEGATIVE-BOUNDED, never INDEPENDENT. Only a search stated as corpus-wide and empty yields INDEPENDENT, labelled NEGATIVE-CENSUS." (S-MP l.293–300) P3b implements this as a two-stage absence search (§11.4):
- **Stage 1** is a script over contributions and every CONTENT file.
- **Stage 2** is an AI reading of every hit file in whole.
- A disagreement between census and reading resolves "ESCALATED, never FOUND".

## 4.5 Append-only history and change

**[DESIGN-FROZEN]**
- "No existing record is edited or deleted." Reinterpretation creates a new record plus a correction record (`P3B-COR-####`) (S-P3B §12.3).
- "A record's state never advances by editing it" (S-P3B §8).
- Later evidence "never rewrites the earlier record" (S-MP R10).
- The governance log itself is append-only (S-GL l.3–4).
- **[FACT]** It has no hash chain yet (S-C4 l.405, "writable log, no hash chain").

## 4.6 Acceptance and verification requirements

**[DESIGN-FROZEN]** The per-batch flow is "verifier (§20) → audit sample (§21) → human acceptance (§25)" (S-P3B l.1880).
- "No partial acceptance of a failed batch."
- Register audits check "**form, not truth**". Under-discovery is reported but is not a failure condition (S-P3B §20–§21).
- Acceptance is a human act, and "for research records … **never** acceptance of their truth" (§25).

## 4.7 Seal and firewall

**[DESIGN-FROZEN]** The hold-out lists "are for scripts only … never pass them to an agent" (S-ADD §5). "Reading an HF file while sealed, or a hold-out record reaching an agent input, is a **seal breach**" (S-ADD §6).
- **[FACT]** A breach was ruled at GL-0045 (P3B-ESC-0001): H-19 stays SEALED, "no unseal, no S5c scoring, no use of H-19 predictions … S5c requires a separate human decision". It "does not block S5 production".

## 4.8 This manuscript's own provenance discipline

**[EDITORIAL]** The manuscript follows the same rules.
- Every substantive statement cites a source in the register, which carries the SHA-256 Session B computed.
- No statement is copied from a summary when the source was accessible. Extraction assistants were used, and the decisive quotes were re-verified against the files (Ch. 14.3).
- The manuscript is itself DERIVED. It must never be cited as evidence about the corpus (S-MP R8; S-OP §20).

---

# 5. Pre-registration and measurement design

## 5.1 Two freezes and one re-registration

**[FACT]** The scientific design was frozen in two acts, deliberately "never merged" (S-B1 §8), and the analysis instrument was later re-registered.

| Act | Content | Record |
|---|---|---|
| **Freeze 1**: scientific design | B1 v2 text (sha `d6b23886…`); instrument `prereg_v1.py` (sha `eb6291ab…`); estimands θ_D, θ_A and the θ_E conditions; R1 and R2; H1–H5 with Model 0, artifact models and competing models; Holm for confirmatory and BH for exploratory tests, α = q = 0.05; B = 9,999; failed / NOT-ASSESSABLE handling; decision fields. "Nothing learned later … may change any of these." | GL-0094; S-B1 |
| **Freeze 2**: frame, sample, seed | 128-bit seed `128527203382009690619999539329395210354` (OS CSPRNG); audit sample of **231** labels (HUB 33/66 · EMPTY 7/7 · MULTIROW 4/4 · DECOMPOSED 87/173 · SINGLE 100/1,725); sample sha `e3f3ed76…` | GL-0103 |
| **prereg_v2**: re-registered analysis instrument | implements GL-0118…0136; confirmatory family {H1, H2, H4}; exploratory {H3, H5}; B = 999; H2 reworded to the forward-share claim; prereg_v1 preserved but "not used for S5 analysis" | GL-0137; S-PRV2 sha `827ce461…` |

- **[CONFLICT X-8]** Freeze 1's immutability clause and the prereg_v2 changes need an explicit reconciliation record. The changes were made by human acts before any S5 data, and the operating protocol allows "a governed, **disclosed** re-registration" (S-OP §17). But Session B found no entry that names the departure from Freeze 1's clause as such. This is **Request RQ-1** to the Researcher. Session A confirms that no reconciling entry exists (handoff §3, REPORTED; consistent with Session B's search).
- **[FACT] The confirmatory execution parameters are unresolved** (verified by both sessions):

  | Parameter | What the record says |
  |---|---|
  | B for H1, H2, H4 | Freeze 1 fixes B = 9,999 (GL-0094; S-B1 l.68). B = 999 was decided **for H5 only** (GL-0122, 0124). The H2 NR-P threshold "≥ 58 dated relations" is stated "at B = 999" (GL-0135 #1). The frozen code's default B = 999 is a command-line default, not a decision |
  | Analysis seed | Freeze 1 requires "a seed drawn at the freeze from the OS CSPRNG". No analysis seed is recorded anywhere. GL-0094's "Not authorized … seed" sits beside "Freeze 2, … sampling"; whether it means the analysis seed is a reading. GL-0103's seed is the audit-sample seed |
  | Single run | no rule exists |

- **[OPEN] Two coherent readings of the record** (Addendum §1; reserved for the Principal, with RQ-1):
  - (i) Freeze 1 governs B = 9,999. GL-0135 decided the NR-P *rule* (level α/3; power ≥ 0.8), which the code evaluates at whatever B is used. "58" is then an illustration computed at 999;
  - (ii) the later record intended 999 throughout. That needs an explicit act superseding Freeze 1 l.68.
- **[DERIVED] What the guarantee needs from the procedure:** Ch. 8.13, Proposition 8.8.
- **[DESIGN-FROZEN]** From GL-0137 on: "any change to prereg_v2 or its tests needs a new version, independent review and a new human act, before any S5 data."

## 5.2 The frame and its units

**[FACT]** The frame is built as follows:
- Tiering gave X 477, Z 1,120 and U 900 (GL-0006). The S5-eligible set is U ∪ Z = 2,020 labels.
- The hold-out (variant S) holds 26 components, 45 labels and 33 files (GL-0016; S-ADD §3).
- The S5 frame is the 1,975 discovery Tier-U/Z labels (GL-0018). 1,975 = 2,020 − 45 is Session B's arithmetic; the log does not state the subtraction **[DERIVED]**.
- The frame is executed in **396 batches**: 382 regular and 14 hub batches (GL-0043, l.808, l.860).

**[DESIGN-FROZEN]** The object label is the unit of roll-up, batching, verification and acceptance. The source file is the unit of provenance and of stage-2 absence reading (S-P3B §10).

## 5.3 Per-label measurement procedure

**[DESIGN-FROZEN]** For each label, in a fixed order (S-P3B §9.8):
1. Read the label's evidence **in historical order** and build the timeline.
2. Determine semantic_status from its pairs only.
3. Determine type_status.
4. Determine mathematical_status.
5. Establish births.
6. Assign the layer.
7. Resolve absences through the two-stage search. Hubs skip stage 2 and their hit-bearing dimensions resolve ESCALATED (LOAD).
8. Record dependency edges, "only with a source row that states the dependency".
9. Record what says this and what would make it wrong.
10. Record research, which must "never alter 1–8 because of a register record".
11. Run the self-check.

## 5.4 Measurement-quality design

**[DESIGN-FROZEN]** Three estimands are reported separately and "never combined into one number" (S-B1 §1):
- **θ_D**, the reconstruction disagreement rate, from about 231 blind same-protocol re-analyses. It is reported as an HT point estimate with an exact v2.5-S upper bound as the primary figure.
- **θ_A**, disagreement with blind human adjudication, on the human subsample: the MULTIROW census plus ≥ 10 SINGLE labels. Double adjudication covers 20 of the 231 (GL-0103).
- **θ_E**, error relative to a reference standard R\*. It may be reported only if a written justification is accepted by a human act. Otherwise θ_E = NOT-ESTABLISHED and "the word 'error' is not used".

"**The hierarchy is kept: reproducibility (θ_D) ≠ agreement (θ_A) ≠ truth.**" (S-B1 l.31)

- **[FACT]** θ_D has no threshold and no failure response (GL-0140 finding 4). The adequacy rule for θ_D is V-5 **[OPEN]**.

## 5.5 Measurement history to date

All execution so far has produced **development evidence only**, never measured data (S-OP §21). **[FACT]**

| Event | Result | Record |
|---|---|---|
| S4 pilot | executed and closed; development evidence | GL-0020…0030 |
| OB0004-R2 / R2.2 / R2.3 | failed (contract defects; audit protocol violations; capacity limit) | GL-0047, 0049, 0051 |
| V1.2.1, V1.2.4 E0 | RUN-INVALID; instruments UNVALIDATED | GL-0071, 0076, 0077 |
| OB0018 pilot | UNDETERMINED | GL-0080 |
| S5 canary (OB0012 + OB0114) | authorized (GL-0107). The restart failed **11 of 11** runs, with 0 labels measured; "the machinery held". The cause was adjudicated as an instrument/contract defect. **The execution results are recorded only in S-TODO and in GL-0110's adjudication, not as a separate log entry** | GL-0107, 0108, 0110; S-TODO l.140–144 |
| Rehearsal run-1 | FAILED at step 9 (R3′) | GL-0113 |
| Rehearsal run-2 | FAILED before dispatch: an environment permission denial, plus an F5 quiet-period breach by a concurrent session's commit | GL-0116 |
| Rehearsal run-3 | approved at G2; **execution DEFERRED** | GL-0117 |

## 5.6 Unresolved prerequisites before measured data can exist

1. **Run-3 launch conditions (B1).**
   - The human stops "**every** Claude session, IDE, git client or loop on PROD and its worktrees". The preflight must print `PREFLIGHT-OK` (GL-0117).
   - "This approval does not override a failed preflight" (GL-0117 l.1906).
   - **[FACT]** No PREFLIGHT-OK is recorded. Run-3 is non-counting: "never counted S5 evidence" (S-TODO l.173).
2. **Gate G3**: the G-01 mention question, 48 PENDING annex clauses, reader-string registration, the R1 prompt block, adoption of the r5 environment, and separating checkouts from PROD's `.git` (GL-0117).
3. **G4/G5**: counted canary, then full S5. Full S5 also needs the verdict criteria adopted (S-C4 lane M6).
4. **Verdict criteria** before any S5 data (Ch. 7).

**[EDITORIAL]** This manuscript lane must not breach (1). The Professor session will stop entirely when the Principal schedules the quiet period (README §4).

---

# 6. Statistical and analytical methodology

## 6.1 Discipline

**[DESIGN-ADOPTED]** The operating protocol sets the statistical discipline (S-OP §17).
- "**Keep apart:** reliability, validity, measurement error, reconstruction disagreement, adjudication disagreement and theory evidence."
- "**Never interpret reliability as validity.** Never promote pilot evidence to confirmatory evidence."
- Never change hypotheses, estimands, α, q, the multiplicity method, the sample or the stopping rules "after seeing results, unless the change is a governed, **disclosed** re-registration".

## 6.2 Confirmatory family {H1, H2, H4}

**[DESIGN-FROZEN]** (S-PRV2; GL-0118…0137)
- **Error control.** Holm over a **fixed** family of three at α = 0.05, familywise (GL-0118). Under rule R3 (GL-0128), "an UNTESTABLE hypothesis enters Holm with p = 1 and reads OPEN; untestability is decided from that hypothesis's own data only".
- **Decision.** REJ iff the Holm-adjusted p ≤ α. Otherwise the result reads OPEN, apart from H2's powered non-rejection (below).
- **H1**, serial dependence of change classes.
  - Model 0: within-object permutation of positions ≥ 2 (S-B1 §3).
  - Under rule D0 (GL-0132), a rejection licenses only "serial dependence … present beyond Model 0; source/agent unresolved". A non-rejection reads OPEN, never "agent artefact".
  - **[DESIGN-FROZEN, declared limitation]** "H1 can reject under pure agent habit" (GL-0132).
- **H2**, the forward share of dated contradictions.
  - Claim: "dated contradictions run forward in A.10 time more often than chance (forward share > 1/2 beyond Model 0 …)".
  - The claim carries the declaration "**PARTLY METHOD-CONSTRAINED**: the contract orders contradicted_by by timeline predecessor, so this need not be a property of the domain" (S-PRV2 l.81–83).
  - UNTESTABLE if fewer than 5 dated relations.
  - Its outcome vocabulary is REJ / NR-P / NR-U / UNT (GL-0133, 0135). NR-P ("no forward share as large as 0.7") requires power ≥ 0.8 at π = 0.7 and level α/3, fixed in advance. That holds iff m ≥ 58 dated relations at B = 999 (S-PRT l.96–99). Post-hoc power is inadmissible (GL-0133).
  - Acyclicity is reported descriptively only.
- **H4**, the temporal order of birth kinds: lexical < conceptual < formal < operational < governance, one-sided. Ties contribute 0 and are reported.
- **Resampling.** B Monte-Carlo draws per test. **B and the analysis seed are unresolved for H1/H2/H4** (Ch. 5.1).
  - The code defaults (B = 999, seed 20260927; S-PRV2 l.309, l.361–362) are **not approved parameters**.
  - Each analysis uses a separately named random stream (`"{seed}:H1"` etc.). Changing exploratory data "cannot shift any confirmatory T, p, p_holm or outcome" (a tested property, S-PRT).
- **[REPORTED; COMPUTED by Session A, not recomputed here] The H2 NR-P power depends on B and is not monotone in m at B = 9,999.**
  - At B = 999, every m ≥ 58 reaches power 0.8.
  - At B = 9,999, m = 55 and 58 pass, m = 56, 57 and 59 fail, and every m ≥ 60 passes.
  - Neither B is uniformly more powerful (handoff §2 D2.2–D2.3, Annex 2).
  - The mechanism is consistent with theory: at large B the test approaches the exact binomial test, whose power is saw-toothed in m.
  - The rule "power ≥ 0.8 at the observed m" stays a coherent pre-registered rule under non-monotonicity, provided m is ancillary for π. That premise is **[OPEN]** (Addendum §2.2).

## 6.3 Exploratory family {H3, H5}

**[DESIGN-FROZEN]**
- **Error control.** Benjamini–Hochberg at q = 0.05 over the fixed family. A missing p (untestable, data defect or error) enters as p = 1 (L1 = B, GL-0136). Exploratory results are "never cited as confirmation" (S-B1 §5).
- **H3.** Upper-tailed on 2- and 3-cycles of the label dependency graph; UNTESTABLE below 10 internal edges.
  - It "can only give evidence against 'the dependency graph is a DAG'".
  - **[DESIGN-FROZEN, declared limitation]** "exact validity of the H3 null chain is unproven" (GL-0129 m2).
  - **[DERIVED; refined]** This understates the issue (Ch. 8.12):
    - H3's and H5's nulls use a "star" MCMC scheme that is not valid in general (counterexample);
    - their false-positive rates are **unknown**;
    - H3's null is uniform at best on the swap-reachable component, not on the full degree class;
    - H3 does not filter self-loops.
- **H5.** The either-or claim (P1-1 = 1a ∨ 1b): excess exact single-premise implications, or excess pairs with Loevinger H ≥ h₀ = 0.8.
  - Parameters: e₀ = 10, s₀ = 10, a curveball null, the explicit list of D = 12 dimensions, non-hub rows only.
  - A rejection "never identifies which disjunct and is never evidence of a coherent theory".
  - **[DESIGN-FROZEN, declared limitation]** Power and size at D = 12 are not studied; the design study simulated D = 10 (GL-0122, 0124).

## 6.4 Three-way comparison and structural invariance

**[DESIGN-FROZEN]** A candidate regularity advances only if it beats all three of (S-B1 §4):
1. Model 0 at the confirmatory level;
2. every listed reconstruction-artifact model, by stratified permutation;
3. the competing model, by a pre-declared likelihood-ratio or BIC criterion.

Separately, R2 requires structural invariance across two readings. "A fragile candidate cannot rise above EXPLORATORY" (S-B1 §2).

## 6.5 Control comparisons in the cross-object and corpus passes

**[DESIGN-FROZEN]** DISCOVERY claims are compared with matched, blinded controls from the same eligible population (S-P3B §9E.2).
- The test is a one-sided Fisher exact or a permutation test.
- Multiplicity is controlled by Holm or BH. Untested cells enter with p = 1.
- Below a minimum data size m, x_min, the result is UNDERPOWERED.
- "UNDIFFERENTIATED and UNDERPOWERED are never evidence that a structure is absent."
- "More frequent than controls is evidence of discrimination, not proof of a theory."
- **[OPEN]** The parameter values (r, k, m, x_min, the multiplicity choice) are human decision H-17. The P3b recommendation (OMQ-20: r = 2; k = 2, 3; BH q = 0.10; m ≥ 20, x_min ≥ 3) is a recommendation. Session B did not verify whether H-17 has been decided.

## 6.6 Hold-out scoring (planned; prohibited at present)

**[DESIGN-FROZEN; PLANNED; S5c PROHIBITED]** (S-ADD §7)
- **Predictions.** Each prediction sits in the TEST-DEFINED plan of a CORPUS theory candidate with `claim_type = EMPIRICAL-GENERALIZATION`, persisted in an earlier run.
- **Unit and outcome.** The unit is the component. A component shows O iff more than half of its P-labels with a determined O satisfy it.
- **Baseline.** A Jeffreys Beta(k+½, n−k+½) posterior predictive per stratum (n_s ≥ 10, analog never dropped), 100,000 Monte-Carlo draws, seed 20260924.
- **Minima.** ≥ 5 P-components with a baseline.
- **Error control.** Holm at α = 0.05 over "every prediction persisted before the unseal".
- **Outcomes, in precedence order.** NOT-SCOREABLE → UNDERPOWERED → PREDICTION-CONFIRMED → PREDICTION-FAILED.
- "**One unseal, no changes after.**"
- **[DESIGN-FROZEN, declared limitation]** "Predictions test generalization to the corpus periphery, not its core". The test is "weak by construction" (S-ADD §8).

## 6.7 Uncertainty and missing evidence

**[DESIGN-FROZEN]** Missing evidence is never silently converted into a finding:
- Untestable hypotheses enter error control at p = 1 and read OPEN.
- Labels of unaccepted batches are NOT-ASSESSABLE and are worst-cased in the primary bound (EP-01 §7, as reported in GL-0109 and S-B1 §7).
- Underpowered comparisons are not absences.
- A failed prediction is "never read as REFUTED-IN-CORPUS" (S-ADD l.269–270).

## 6.8 Declared statistical limitations (collected)

**[FACT]** Each limitation below is declared by its source.

| # | Limitation | Source |
|---|---|---|
| L-S1 | H1 may reject under agent habit (D0) | GL-0132 |
| L-S2 | H2 is partly method-constrained; its power assumes independent orientations | S-PRV2 l.81–88 |
| L-S3 | exactness of the H3 null chain unproven | GL-0129 |
| L-S4 | H5 not studied at D = 12; its specificity untested | GL-0122, 0124 |
| L-S5 | the inner statistics and nulls of H1/H3/H4/H5 live in frozen prereg_v1 code, not re-inspected by Session B | S-PRV2 import guard |
| L-S6 | reliability (inter-rater agreement) is not validity | S-B1 §1(c) |
| L-S7 | the hold-out tests the periphery and is weak by construction; researchers had prior exposure | S-ADD §8; P3b L-11 |
| L-S8 | θ_D has no adequacy threshold (V-5 open) | GL-0140 |
| L-S9 | B, the analysis seed and the single-run rule are unresolved for the confirmatory tests, so precondition A3 of the guarantee is unmet as a recorded rule | Ch. 5.1; Ch. 8.13 |
| L-S10 | H4's null shuffles dates across **all** dated birth keys, including keys outside the five counted kinds. A1 for H4 is exchangeability over that wider set | S-PRV2 l.281–293 (VERIFIED) |
| L-S11 | on the subset of H2's relations created by the predecessor rule, "forward" measures alignment of list adjacency with file dates, i.e. **compliance with the historical-ordering instruction**, not contradiction direction (Prop. 8.9, Cor. 8.9a; verified for the v2.9 draft contract only) | Ch. 8.14 |
| L-S12 | H2's null assumes independent orientations; relations within an object share one listing, so a rejection may reflect dependence rather than π > ½, and the NR-P power figure assumes independence | Ch. 8.14 |
| L-S13 | H3/H5 false-positive rates unknown (star scheme; synthetic evidence invalid) | Ch. 8.12 |

---

# 7. Verdict criteria and scientific evaluation

## 7.1 Governing status

**[FACT]**
- The two named outcomes have "No criteria anywhere" in the governing documents (GL-0140 finding 1; S-VC18 l.23–25).
- G-LOG-0028 defers any synthesis methodology to a post-S5b charter.
- GL-0140 authorized **drafting and review only** of a verdict-criteria proposal: "**This entry does not approve the criteria.**"

**Everything in §7.2–§7.6 is [PROPOSAL]** (S-VC18 v1.8, sha `469fddca…`, banner "PROPOSAL — NOT APPROVED FOR EXECUTION").

## 7.2 Structure of the proposal

**Six layers, never merged** (S-VC18 l.51–58):
- historical (L-H);
- consistency (L-C);
- in-corpus empirical (L-E);
- reproducibility (L-R);
- out-of-sample (L-P);
- final judgment (L-J).

**Claims.** A claim is either Kind P (a confirmatory prereg_v2 hypothesis) or Kind K (a P3b CORPUS candidate with a persisted test plan; K-EG if it is an empirical generalization). Exploratory results and single CROSS-OBJECT findings "are **neither** kind and are never core".

**Admission conditions CA-1…CA-9** (S-VC18 §4). Each claim in a core must meet every applicable condition:

| | Condition | Kind P instrument | Kind K instrument |
|---|---|---|---|
| CA-1 | provenance from measured data | H-06-accepted inputs | P3b record basis |
| CA-2 | pre-registration | prereg_v2 frozen | §13.10a plan, not post-hoc narrowed |
| CA-3 | formal, falsifiable, non-vacuous | frozen statistic and Model 0 | §13.11 formal object |
| CA-4 | in-corpus support | Holm REJ, licensed reading only | SUPPORTED-IN-CORPUS |
| CA-5 | beyond Model 0, artifacts and competitors | B1 §4 three-way comparison | ≥ DISCRIMINATING plus §13.9a(3) |
| CA-6 | reproducibility | R1 within V-5 **and** R2-INVARIANT | θ_D within V-5 plus the V-7 instrument |
| CA-7 | no unresolved in-scope counterexample | — | no REFUTED-IN-CORPUS |
| CA-8 | source, not agent | H1 under D0 fails | the same test |
| CA-9 | out-of-sample | n/a | K-EG: PREDICTIVE |

**ROBUST THEORY FOUND (§5).** "Some registered theory candidate has a **coherent core** in which every claim meets every admission condition applicable to its kind…", subject to conditions R1–R6:
- registration before E_close;
- a coherent core of size ≥ k_min;
- CA-1…CA-8 for every claim;
- the V-2 out-of-sample requirement;
- independent reproduction;
- human adoption.

**NO COHERENT THEORY FOUND (§6).** "An **adequate, completed** search … produced no P-5-registered candidate with a coherent core … and every P-5-registered candidate was disposed of …, and this is independently reproduced. It reports what this search found. It refutes no unexamined theory." Its conditions are N1–N6: completion, adequacy, disposal, nothing pending, no qualifying core, reproduction.

**OPEN — INSUFFICIENT EVIDENCE (§7).** OPEN is the default. It is always written with reason codes: STAGE, COVERAGE, UNCHECKED, SCOPE, POWER, INSTRUMENT, UNDISCRIMINATED, UNCORROBORATED, SCORING-UNAVAILABLE, POST-HOC-ASSEMBLED, UNORDERED, CRITERIA, REPRODUCTION-PENDING, HUMAN-REJECTED.

**Exclusivity.** The three outcomes are mutually exclusive "by construction" (S-VC18 l.224), as **[DERIVED]** in a proposal. That claim is listed as proof obligation PO-6 (Ch. 8.10).

## 7.3 Permitted and prohibited statements

**[PROPOSAL]** The proposal fixes in advance what may be said.

- **A ROBUST verdict may read only:** "Within the discovery population of this corpus, under method [version], the claims C₁…C_k form a coherent, falsifiable theory candidate that survived the pre-registered attack."
  - It must carry mandatory caveats, for example: no out-of-sample test was possible for Kind-P-only cores; the hold-out limits; prior exposure.
  - It must never claim truth of the domain, canonical status or independent corroboration unless V-4 grants it (S-VC18 l.158–168).
- **An NCTF verdict must never read** "the corpus contains no theory", "KnowledgeOS is false" or "the authors had no theory" (S-VC18 l.237–242).

**[EDITORIAL]** Chapter 12 will apply these wordings if, and only if, they are adopted.

## 7.4 Reachability now

- **[PROPOSAL]** "**Yes, and it is the only reachable outcome now:** `OPEN — INSUFFICIENT EVIDENCE` with codes `STAGE`, `CRITERIA` and `SCORING-UNAVAILABLE`." (S-VC18 §7.5, l.296; verified by Session B)
- **[DERIVED]** Proposition 8.5 gives the derivation. Its premises are the proposal and the facts of Ch. 5.

## 7.5 Open decisions (pointers; the authoritative list is S-TODO and the proposal)

**[OPEN]** All thirteen V-items are open. "Thirteen human decisions are needed, and **all must be taken before any S5 data are seen**" (S-VC18 §11.10).

| Item | Question (short) | Proposal's recommendation |
|---|---|---|
| V-1 | adopt the three outcomes incl. OPEN | adopt |
| V-2 | out-of-sample requirement for ROBUST: (i) Kind-P-only admissible / (ii) ≥ 1 K-EG claim with CA-9 / (iii) qualified | (ii); "Never (iii)" |
| V-3 | k_min | 2 |
| V-4 | RA-15 status D for within-corpus sources | "not D" |
| V-5 | θ_D adequacy rule | a pre-data synthetic attenuation study |
| V-6 | relation to the charter | additions only before S5 data |
| V-7 | Kind K coverage, CA-5 analogue, CA-6 instrument | (a), with limits |
| V-8 | R2 precision | a study |
| V-9 | generation-sensitivity rate | a study |
| V-10 | is PREDICTION-FAILED a disposal ground? | no, unless studied |
| V-11 | powered disconfirmation for H2/H4 (needs a new pre-registration version) | a study, if needed |
| V-12 | the extent of "S5 data" | none (the human's own term) |
| V-13 | when the generation procedure is fixed: (α) before S5 data, plausibly departing from O1; (β) after S5b | "No recommendation on the governance question" |

## 7.6 The N-a/C-4 item and adoption readiness

- **[FACT]** The review record concludes that v1.8 is "PASS-WITH-MINOR-FIX … **not ready for adoption** … until the Principal decides N-a/C-4 and names the carried items as final pre-data limitations" (S-VCR l.750).
- **[PROPOSAL]** The question (S-VC18 §22.4). A linkage ruling that failed its audit, or one re-audited until it passed, can still decide coherence or incoherence. The Principal must choose between:
  - (a) a binding audit rule, a new mechanism needing its own authorization;
  - (b) explicit acceptance as a disclosed limitation.

  Item N-a is the only open item that "can push toward a false ROBUST or NCTF" **together with C-4** (S-VC18 §22.3).
- **[FACT]** Both decision packages on this item were reviewed NEEDS-REVISION:
  - S-NAC4 (round 12b), finding N1, "introduced by the v2 revision";
  - S-NAC4P (round 13b), findings R2-1 and R2-2.
  
  Drafting was stopped each time. The record names the smallest blocker: the G0/G1 generation premise "is itself a pre-data decision on part of the generation procedure", which "plausibly departs from O1" (S-VCR l.781–811).
- **[OPEN]** The next human decision is recorded as **Q-a**: a binding second judgement, or a declared limitation (S-TODO l.5).
- **[FACT]** The review record observes that in consecutive rounds the drafter's own additions introduced a new material defect (S-VCR l.768). The sentence says "fourth" but lists five instances; the count is unreconciled.
- **[FACT]** All reviewers were "the same model as the drafter", and "the drafter wrote their prompts" (S-VCR l.619, l.793).
- **[EDITORIAL]** The Professor's lane records this as a methodological fact about the instrument's maturity. It does not attempt a repair (Ch. 11).

**Competing positions on Q-a, both preserved [OPEN; derivations in Ch. 8.15].**

| | Option (a): binding second judgement | Option (b): one judgement, disclosed |
|---|---|---|
| Safety (false definite verdicts) | **never worse than (b), on every realization**, under (M) fixed manifest (G0), (F) finality, (S) sound checkers and (R) unchanged first rulings (Theorem 8.11). Strictly better unless the judges' errors are perfectly correlated | unchanged by disclosure (Prop. 8.14) |
| Size of the gain | unquantified: it depends on the error correlation φ and on item difficulty (Props. 8.12, 8.13); possibly small for two sessions of one model | — |
| Cost | at equal accuracy, blocked errors = lost correct leaves; hits NCTF hardest (Prop. 8.12); double the judging effort | none added |
| Specification | needs v1.9 and review; the decisive premise G0/G1 collides with V-13/O1 | still needs one fixed reading of the conflict passage (l.69 vs l.270/313) |
| Researcher's recommendation | — | **(b)**, on specification risk (S-NAC4P; Session A packet D3) |
| Package v2's recommendation | **(a)** (S-NAC4 §5) | — |
| Professor | both conditionally defensible. The choice turns on G0/G1, on whether genuinely different judges are available, and on the Principal's loss ratio (a false definite verdict vs a missed definite verdict) | |

Neither option protects candidate-theory assembly (package v2 counterexample).

# 8. Mathematical formalization

## 8.1 Standing of this chapter

**[EDITORIAL]** This chapter formalizes **the method**, not a theory of KnowledgeOS:
- the populations;
- the measurement state;
- the estimands;
- the tests;
- the evidence ladder;
- the proposed verdict function.

Every definition below transcribes an authorized source into symbols. Where the transcription involves a choice, the choice is marked.

Each proposition states its premises and is labelled with its standing:
- **[DERIVED]** with a proof given here (Session B's own derivation from cited premises; same-model, and not independently checked);
- **[DERIVED in source]** (the derivation is the source's own);
- **[PROOF OBLIGATION]** (not yet discharged).

Following S-OP §14, "**No decorative mathematics**": a symbol is introduced only where a proposition uses it.

## 8.2 Populations

Let 𝓡 be the set of registry records, with |𝓡| = 2,779. Let C ⊆ 𝓡 be the CONTENT records (the census), with |C| = 2,767 (S-P3B §3.2).

Let Λ be the set of P2 object labels, with |Λ| = 2,497. Let τ : Λ → {U, X, Z} be the frozen tiering (S-P3B §9.3; GL-0006). Then:
- E := τ⁻¹({U, Z}) is the set of S5-eligible labels, with |E| = 2,020;
- Λ_H ⊆ E is the set of hold-out labels, with |Λ_H| = 45;
- HF ⊆ C is the set of hold-out files, with |HF| = 33 (S-ADD §1, §3);
- the **discovery population** is the pair (C ∖ HF, Λ ∖ Λ_H);
- the **S5 frame** is Λ_S := E ∖ Λ_H.

> **Fact 8.1** [FACT, GL-0018] |Λ_S| = 1,975.
> **Remark** [DERIVED] Λ_H ⊆ E holds by construction (the hold-out was drawn from the 2,020 S5-eligible labels, S-ADD l.100–122), so |Λ_S| = 2,020 − 45 = 1,975, consistent with Fact 8.1.

**Strata.** Freeze 2 partitions the frame as Λ_S = HUB ⊔ EMPTY ⊔ MULTIROW ⊔ DECOMPOSED ⊔ SINGLE, with sizes (66, 7, 4, 173, 1,725). The audit sample S ⊆ Λ_S has stratum sizes (33, 7, 4, 87, 100), so |S| = 231 (GL-0103).
- **[DERIVED]** 66 + 7 + 4 + 173 + 1,725 = 1,975, and 33 + 7 + 4 + 87 + 100 = 231.
- **[CONFLICT X-5]** A second partition appears in S-TODO l.302. It is not used here.

## 8.3 Measurement state

**[DESIGN-FROZEN]** Each label ℓ ∈ Λ_S has a state in the machine (S-P3B §8):

NOT-STARTED → PREPARED → ASSIGNED → PROPOSED → VERIFIED → AUDITED → ACCEPTED, with side states FAILED, PROVISIONAL-HELD and BLOCKED.

Define the measurement indicator μ(ℓ) := 1 if ℓ belongs to an attempt that reached BATCH-PASS and was accepted under H-06, and μ(ℓ) := 0 otherwise (S-OP §21). Let M := μ⁻¹(1) be the set of measured labels.

> **Fact 8.2** [FACT, S-TODO l.8, l.47; no accepted batch in S-GL] M = ∅.

## 8.4 Measurement-quality estimands

**[DESIGN-FROZEN, S-B1 §1]** Let δ_D(ℓ) ∈ {0, 1} indicate that the S5 object of ℓ differs substantively, in a decision field, from an independent same-protocol re-analysis. Then

  θ_D := (1/|Λ_S|) Σ_{ℓ∈Λ_S} δ_D(ℓ),

estimated from S by a Horvitz–Thompson estimator with an exact upper bound (v2.5-S). θ_A is defined in the same way with δ_A (disagreement with blind human adjudication) on its subsample.

**[EDITORIAL]** Two points are not verified by Session B:
- that the population of θ_D is Λ_S rather than a sub-population;
- the estimator and bound themselves.

Both are specified in EP-01, which Session B did not consult (see proof obligation PO-3).

## 8.5 Monte-Carlo tests

**[DESIGN-FROZEN]** For a hypothesis with observed statistic T and B draws T₁\*, …, T_B\* from its Model-0 generator, the instrument reports

  p := (1 + K)/(B + 1),  K := #{b : T_b\* ≥ T}

(S-PRV2 l.118 and the H5 tail definition, l.190–200; the inner v1 functions were not inspected, L-S5).

> **Lemma 8.1** [DERIVED; standard result] If, under H₀, the B + 1 values (T, T₁\*, …, T_B\*) are exchangeable, then P(p ≤ u) ≤ u for every u ∈ [0, 1].
>
> *Proof.* Exchangeability makes the rank of T among the B + 1 values uniformly distributed, with ties broken against rejection, because K counts every T_b\* ≥ T. Hence P(K ≤ k) ≤ (k + 1)/(B + 1) for every integer k ≥ 0. The event p ≤ u is the event K ≤ u(B + 1) − 1, so P(p ≤ u) ≤ ⌊u(B + 1)⌋/(B + 1) ≤ u. ∎

**[PROOF OBLIGATION PO-1]** Lemma 8.1 transfers to the instrument only if each Model-0 generator produces draws exchangeable with the observed statistic under H₀.
- **H1 and H4** use within-object permutation or exchange (S-B1 §3). These are exact when the permutation is drawn uniformly.
- **H3** uses degree-preserving edge swaps. **H5** uses a curveball chain. Both are Markov chains, so exchangeability requires an argument about the chain, for example exact uniform sampling or an exchangeable construction from the observed state.
- For H3 the record states this "is unproven" (GL-0129 m2).

## 8.6 The H2 test and its power

**[DESIGN-FROZEN]** Let the dated, untied contradiction relations be indexed i = 1, …, m. Let X_i = 1 if relation i runs forward (target earlier than source in A.10 time). The estimand is the forward share π; the hypotheses are H₀: π = ½ (Model 0: random orientation) and H₁: π > ½. The statistic is T = Σ X_i.

Write q_t := P(Bin(m, ½) ≥ t). At level ℓ with B draws, the test rejects iff K ≤ k_max, where k_max := ⌊ℓ(B + 1) − 1⌋. The instrument computes the power as

  β(m; π, ℓ, B) = Σ_{t=0}^{m} Bin(t; m, π) · P[Bin(B, q_t) ≤ k_max]   (S-PRV2 l.116–131).

> **Proposition 8.2** [DERIVED] If, under the alternative, X₁, …, X_m are i.i.d. Bernoulli(π), and the Model-0 draws are i.i.d. Bin(m, ½) independent of the data, then β(m; π, ℓ, B) is the exact rejection probability of the Monte-Carlo test.
>
> *Proof.* Condition on T = t. Each null draw satisfies T_b\* ≥ t independently with probability q_t, so K | (T = t) ~ Bin(B, q_t). The test rejects iff (1 + K)/(B + 1) ≤ ℓ, which holds iff K ≤ ℓ(B + 1) − 1, which for integer K holds iff K ≤ k_max. Summing over the law of T, which is Bin(m, π), gives β. ∎

- **[DERIVED]** At ℓ = α/3 = 0.05/3 and B = 999: k_max = ⌊1000 · 0.05/3 − 1⌋ = ⌊15.67⌋ = 15.
- **[FACT, tested]** The instrument's tests record β(57) < 0.8 ≤ β(58) at π = 0.7 (S-PRT l.96–99). This is the basis of "NR-P needs m ≥ 58" (GL-0133). **Session B did not recompute it.**
- **[DESIGN-FROZEN, declared limitation]** The independence premise of Proposition 8.2 is the stated assumption of NR-P: "assumes independent orientations, as Model 0 does" (S-PRV2 l.87–88). Whether it holds for the corpus is not testable inside the frame (L-S2).

## 8.7 Familywise error under rule R3

Let F = {H1, H2, H4}, with |F| = m = 3. For i ∈ F, let D_i be hypothesis i's own data. Let p_i be its p-value when i is testable on D_i. Define

  p̃_i := p_i if i is testable on D_i, and p̃_i := 1 otherwise   (rule R3, GL-0128).

Holm's procedure rejects H_(1), …, H_(k−1), where p̃_(1) ≤ … ≤ p̃_(m) and k is the smallest index with p̃_(k) > α/(m − k + 1).

> **Proposition 8.3** [DERIVED] Suppose that for each true null i, p_i is valid on its own (P(p_i ≤ u) ≤ u), and that testability of i is a function of D_i alone. Then Holm over (p̃_i) at level α controls the familywise error rate at α under arbitrary dependence among the hypotheses.
>
> *Proof.*
> 1. *Validity of p̃_i.* For a true null i and any u < 1, P(p̃_i ≤ u) = P(p_i ≤ u, i testable) ≤ P(p_i ≤ u) ≤ u. For u = 1 the bound is trivial. So each p̃_i is valid.
> 2. *Bonferroni step.* Let I₀ be the set of true nulls, with |I₀| = m₀ ≥ 1. Suppose some true null is rejected, and let j be the first one rejected, at step s. At most m − m₀ false nulls precede j, so s ≤ m − m₀ + 1. Rejection at step s requires p̃_j ≤ α/(m − s + 1) ≤ α/m₀.
> 3. *Bound.* Therefore P(some true null rejected) ≤ P(∃ i ∈ I₀ : p̃_i ≤ α/m₀) ≤ Σ_{i∈I₀} α/m₀ = α. ∎

- **[DERIVED] Corollary.** An untestable hypothesis is never rejected, because 1 > α/(m − k + 1) for every k. Keeping the family at m = 3 weakly raises every Holm-adjusted p relative to dropping the untestable member. That is, R3 is conservative relative to rule R1. This agrees with the tested example: p = (0.001, untestable, 0.02) gives adjusted (0.003, 1, 0.04), against (0.002, 0.02) under R1 (S-PRT l.72–86).
- **[EDITORIAL]** Proposition 8.3 rests on the two premises recorded as conditions of R3 in GL-0129 m2. Its validity for the actual instrument inherits PO-1.

## 8.8 Research register and the evidence ladder

**[DESIGN-FROZEN, S-P3B §13.9b]** For a CORPUS theory candidate c, define requirement predicates:
- Rec(c): ≥ N_support independent occurrences (N_support = 2 recommended by OMQ-19; H-16 decides);
- Dif(c): the derived control comparison is DIFFERENTIATED;
- Pred(c): ≥ 1 hold-out prediction of c scored PREDICTION-CONFIRMED and none PREDICTION-FAILED;
- Sup(c): STATUS(c) = SUPPORTED-IN-CORPUS.

The evidence level is the largest k ∈ {0, 1, 2, 3, 4} such that the first k predicates of the chain Rec, Dif, Pred, Sup all hold. The levels are 0 = PATTERN-OBSERVED, 1 = RECURRENT, 2 = DISCRIMINATING, 3 = PREDICTIVE and 4 = THEORY-SUPPORTED. If claim_type(c) ≠ EMPIRICAL-GENERALIZATION, then ev(c) ≤ 2.

> **Proposition 8.4** [DERIVED] While stage S5c has not been executed, ev(c) ≤ 2 for every candidate c. In particular, no candidate can be PREDICTIVE or THEORY-SUPPORTED.
>
> *Proof.*
> 1. Pred(c) requires a scored prediction.
> 2. Prediction scores are produced only at S5c: `P3B-PREDICTION-SCORES.jsonl` is "written once at S5c by script" (S-P3B §24.1), after the unseal, which is a human act (S-ADD §5, §7).
> 3. S5c is prohibited (GL-0045), so no scored prediction exists, and Pred(c) is false for every c.
> 4. The levels are cumulative, so ev(c) ≤ 2. ∎

- **[EDITORIAL]** This is the formal content of GL-0140 finding (2): "THEORY-SUPPORTED needs PREDICTIVE …, which is unattainable while S5c is prohibited."

## 8.9 The proposed verdict function

**[PROPOSAL; formalization from S-VC18 §3–§7 and S-C4 §5]**

Let 𝕆 = {ROBUST, NCTF, OPEN}. Evidence leaves (rulings, checks, test outcomes) take values in the three-valued set {⊥, ?, ⊤}. Its information order has ? below both ⊥ and ⊤. The verdict is a function V from leaf assignments to 𝕆, with OPEN as the default.
- S-C4 proposes an admissibility condition: "a rule is admissible only if the outcome remains a strong-Kleene-monotone function of evidence leaves" (S-C4 l.311). That is, refining a leaf from ? to ⊤ or ⊥ may move the outcome away from OPEN, and coarsening a leaf may move it only toward OPEN.
- S-NAC4 proves a version of this property (Lemma A) under conditions C1–C4. The round-12 review found that it "holds under C1–C4" (S-VCR). **[DERIVED in source; same-model review; PO-7]**

> **Proposition 8.5** [DERIVED, conditional on S-VC18] Assume the definitions of S-VC18 and the facts of Ch. 5. Then the only outcome the proposed rule can return today is OPEN.
>
> *Proof.*
> 1. *ROBUST is unreachable.* ROBUST requires a coherent core in which every claim satisfies CA-1, provenance from measured data (S-VC18 R3).
>    - A Kind P claim satisfies CA-1 only through inputs from H-06-accepted labels. By Fact 8.2 there are none.
>    - A Kind K claim is a P3b CORPUS register record. Such records are outputs of S5b (S-P3B §19.1), and S5b has not been executed (Ch. 3.2).
>    - So no claim satisfies CA-1, no core exists, and ROBUST fails.
> 2. *NCTF is unreachable.* NCTF requires N1, completion of S5a/S5b and the recovery pass. N1 fails.
> 3. *OPEN follows.* By §7.1 of the proposal, OPEN holds whenever neither ROBUST nor NCTF holds.
> 4. *Reason codes.* STAGE applies because S5, S5a/S5b, the charter and S5c are unexecuted or unauthorized. CRITERIA applies because V-1…V-13 are undecided. SCORING-UNAVAILABLE applies because S5c is prohibited. ∎

- **[EDITORIAL]** This agrees with S-VC18 §7.5. Proposition 8.5 depends on a proposal.
- **[FACT]** The governing statement is weaker: no outcome has been reached, and the governing documents define no criteria for reaching one.

## 8.10 Proof obligations

| ID | Obligation | Current standing | Source |
|---|---|---|---|
| PO-1 | Model-0 draws are exchangeable with the observed statistic, for H1, H3, H4 and H5 | **H1/H2/H4 discharged under A1–A4** (Prop. 8.6; reproduced by Session A; same-model). **H3/H5 not exact in general** (Prop. 8.7); their size is unknown | Ch. 8.12; PO-01 note |
| PO-2 | the H2 orientation-independence assumption behind NR-P | declared assumption. Its violation is plausible by structure (shared listing within an object, Ch. 8.14); not testable in-frame | S-PRV2 l.87–88 |
| PO-3 | validity of the θ_D/θ_A estimators and the exact v2.5-S bound | specified in EP-01; **not consulted** | S-B1 §1; GL-0093, 0106 |
| PO-4 | FWER control under R3 | **discharged here** (Prop. 8.3), given PO-1 | GL-0128, 0129 |
| PO-5 | confirmatory results are invariant to exploratory data (stream separation) | tested property, not proved | S-PRT |
| PO-6 | mutual exclusivity of ROBUST, NCTF and OPEN | "DERIVED, by construction" in the proposal; not re-derived here | S-VC18 l.224 |
| PO-7 | information-monotonicity (Lemma A) under C1–C4 | derived in source; same-model review only | S-NAC4; S-VCR round 12 |
| PO-8 | soundness of the coherence checker (SAT witness / UNSAT certificate) | condition C2 of Lemma A; no checker exists ("Verdict engine UNBUILT", S-C4 §3.2) | S-VC18 §3 |
| PO-9 | NCTF adequacy: generation sensitivity on planted candidates at a pre-fixed rate | V-9 study, not commissioned | S-VC18 N2(d) |
| PO-10 | calibration of the H-19 baseline (beta-binomial posterior predictive, Holm over all persisted predictions) | specified; untested in the S-lane record seen by Session B | S-ADD §7 |
| PO-11 | m (the number of dated, untied H2 relations) is ancillary for π, so that "power ≥ 0.8 at the observed m" is a valid conditional statement | assumption; unverified | Ch. 8.14 |
| PO-12 | Lemma 8.10 and Theorem 8.11 (linkage-judgement monotonicity and dominance) | derived by Session B; equivalent in substance to Session A's derivation A3 once (R) is added; same-model only | Ch. 8.15 |
| PO-13 | H3 null unaffected by swap reducibility for all n | exhaustive for n ≤ 4 only; the triangle-reversal route refuted | Ch. 8.12 |

## 8.11 The form of a theory candidate (structure only)

**[DESIGN-FROZEN; no content]** A candidate theory is not written here. When the governed process produces one, it will be represented by the frozen record schema (S-P3B §13.6, §13.10a, §13.11), which comprises:
- statement;
- `claim_type`;
- formal object, with `relation_source` (CORPUS-STATED [S-ids] or RESEARCHER-CONSTRUCTED);
- property statuses (STATED-BY-SOURCE … VIOLATED; "A universal property is never 'observed'");
- the weakest structure that fits, with `competing_structures[]`;
- `competing_hypotheses[]`;
- `falsification_condition`;
- the disconfirmation record A–D;
- `temporal_scope`;
- `population_basis`;
- `test_plan_sha256`.

**[PENDING]** The content of every such object.

## 8.12 Validity of the frozen null generators

*Sources:*
- `PO-01-NULL-MODEL-VALIDITY-NOTE.md` (Session B);
- Session A's review (`review/20261017_1407_…`, REPORTED);
- frozen `prereg_v1.py` l.33–124 and `prereg_v2.py` (VERIFIED).

> **Proposition 8.6 (exactness of the confirmatory nulls)** [DERIVED; reproduced by Session A; same-model]
>
> *Statement.* Let G be a finite group acting on the data, and let H₀ be the hypothesis that the law of the data x₀ is G-invariant. If g₁, …, g_B are i.i.d. uniform on G and independent of x₀, then (T(x₀), T(g₁x₀), …, T(g_Bx₀)) is exchangeable, and the reported p-value is valid (Lemma 8.1).
>
> *Application to the three confirmatory hypotheses:*
> - **H1:** G = the product of the symmetric groups on positions ≥ 2 of each sequence.
> - **H4:** G = the product of the symmetric groups on the dated birth keys of each object. This covers **all** dated keys (L-S10).
> - **H2:** G = {0,1}^m acting by flips. H₀ is i.i.d. fair orientations.
>
> *Assumptions:*
> - A1: null invariance as just stated;
> - A2: ideal randomness;
> - A3: everything fixed before the data, one run reported (Ch. 8.13);
> - A4: the result concerns the extracted data as extracted.
>
> *Proof.* PO-01 note, Proposition 1 (an independent uniform element g₀, then the reindexing h_b = g_b g₀⁻¹). ∎

> **Proposition 8.7 (the "star" scheme is not valid in general)** [DERIVED; counterexample recomputed exactly by Session A]
>
> *Statement.* Running each of the B MCMC null draws independently from x₀ does not, in general, yield P(p ≤ u) ≤ u.
>
> *Counterexample.* A symmetric three-state lazy chain with n = 1 step and B = 99 has size **0.123 at nominal 0.01** and **0.332 at nominal 0.05**.
>
> *Consequence.* H3 (directed double-edge swaps from x₀, 10·|E| steps) and H5 (curveball from x₀, 5·|R| steps) are **not** shown valid. Their actual sizes are **unknown**.
>
> - The earlier synthetic "conservative" evidence is **withdrawn**: its ±1.96·SE intervals are invalid near 0; Session A's exact Clopper–Pearson bounds include sizes above nominal; and the H5 simulation used the wrong statistic.
> - Besag–Clifford's parallel scheme would be exact. That would require a new pre-registration version (GL-0137).

> **Fact 8.3 (H3 reducibility)** [COMPUTED, exhaustive, n ≤ 4; reproduced by Session A]
>
> - Under the frozen swap move, 1 of 63 degree classes (n = 3) and 16 of 2,656 (n = 4) are disconnected.
> - In every one of them, the components have identical T_h3 distributions.
> - For general n the claim is **[P]**.
> - The proof route "reversing a directed triangle preserves T_h3" is **refuted**: arcs 0→1, 1→2, 2→0, 1→3, 3→0 give T = 2; reversing the triangle gives T = 1, with equal degrees.
> - What H3 actually tests is uniformity on the swap-reachable component.

## 8.13 What the guarantee requires of the study procedure

> **Proposition 8.8 (sufficient pre-data commitments for A3)** [DERIVED; Addendum §2.3]
>
> *Statement.* Propositions 8.3 and 8.6 hold for an actual confirmatory analysis if the following are recorded **before any confirmatory output is inspected**:
> 1. the instrument sha (prereg_v2 `827ce461…`);
> 2. the shas of its extraction dependencies (`p3b_s5_r7_reconstruction`);
> 3. B;
> 4. the analysis seed;
> 5. a hash-frozen manifest of the input objects and metadata;
> 6. the rule "the first run on that manifest is the confirmatory run; every run is reported".
>
> *Argument.* With items 1–5 fixed, the instrument is a deterministic function of the committed inputs: named streams, frozen code. Item 6 removes selection among runs. Without item 5, selection could act through the inputs; without items 1–2, through the code.

**Remarks:**
- **B and the seed.** Validity holds for **any** B ≥ 1 and **any** seed committed before the data and independent of it. A published constant seed is as valid as a fresh CSPRNG draw. The CSPRNG draw adds **auditability** (proof that the seed was not selected), not validity. **[E]**
- **Departure from the frozen text.** Using a constant would depart from the frozen text (S-B1 l.68), which is a procedural matter for RQ-1.
- **Current status.** Items 3, 4 and 6 are unresolved (Ch. 5.1). Item 5 cannot exist before S5 data. **The confirmatory guarantee is therefore not yet in force for any execution.** **[FACT]**

## 8.14 The measurement structure of H2

> **Proposition 8.9 (predecessor relations carry no direction information)** [DERIVED from VERIFIED code and contract facts; scope: the v2.9 draft]
>
> *Setting.* Let R_C be the set of `contradicted_by` entries forced by the contract rule FD-1′a: "a point whose class is CONTRADICTS **must** appear in `contradicted_by`, targeting its predecessor". The verifier implements this as `pred = tl[i − 1]`.
>
> *Statement.* For every r ∈ R_C whose endpoints are both A.10-dated and unequal, H2's indicator X_r = 1[d(pred(s)) < d(s)] equals 1 **iff list adjacency and the A.10 file order agree on that pair**. Hence, restricted to R_C, the forward share carries no information about the temporal direction of contradictions beyond this alignment.
>
> *Supporting facts:*
> - The contract itself states that "'Predecessor' … carries **no** historical precedence" (contract l.714).
> - No R7 module compares list order with dated positions (search of all non-test modules on the S-Series branch).
>
> *Proof.* pred(s) is adjacent to s in the list by definition. X_r compares the same two points by their A.10 dates. ∎

> **Corollary 8.9a (under ordering compliance, R_C is forward by construction)** [DERIVED; first derived by Session A (Kind-P note r2, Q3-A); premises checked by Session B]
>
> *Premises (VERIFIED):*
> - The frozen protocol instructs the agent to read and list "IN HISTORICAL ORDER (§14.2 date rules; BULK blocks unordered)" (P3b l.571). §14.3 defines the timeline as "an ordered list, in historical order (§14.2)".
> - §14.2 positions a file by its `best_historical_date`, confirmed as applying to the file.
> - H2's orientation uses `file_position` → `dated_position`, which reads the same `best_historical_date`, restricted to an EXPLICIT basis outside BULK blocks (reconstruction l.43–65).
>
> *Statement.* For r ∈ R_C with both ends A.10-dated and unequal, list order and A.10 order agree **iff** the agent complied with the ordering instruction on that adjacent pair. Under full compliance, X_r = 1 for every such r.
>
> *Consequence.* The R_C part of H2's statistic measures **ordering compliance**, not how contradictions arise in the domain. If R_C relations dominate, an H2 rejection is close to guaranteed and uninformative about the domain.
>
> *Proof.* Proposition 8.9, plus: compliance means the adjacent pair is listed in increasing `best_historical_date`, which is the key `dated_position` compares. Ties are excluded from H2. ∎

**Scope and limits:**
- The contract inspected (`7eba0385…`) is the **v2.9 draft** proposed for G3. **The production contract v2.8.1 (`7b68cbc9…`) has not been inspected.**
- **Not a conflict after all (correction of A-15):**
  - Contract l.714's "carries **no** historical precedence" concerns how the *verifier* derives precedence ("COMPUTED from A.10 dated positions only (never array, list, … order)", reconstruction module docstring).
  - It does not deny that the list is meant to be historical.
  - The protocol instructs historical listing; the verifier deliberately does not rely on it.
- Non-predecessor entries ("No converse … supported by its own CONTRADICTION fact", contract l.715) are outside R_C. Their orientation is the agent's judgement of which source contradicts which, so it may carry domain content. A semantic habit of naming the earlier source as the contradicted one would reintroduce an artefact (Session A, Q3-B).
- **The share of R_C among H2's relations is unknown before S5 data, so H2 is not shown to be invalid.** **[U]**
- The frozen caveat "PARTLY METHOD-CONSTRAINED" **understates the R_C part**. Changing H2's licensed reading would need a re-registration (reserved).

**Dependence (a separate issue).**
- H2's test is valid for its stated null of i.i.d. fair orientations (Prop. 8.6).
- Relations of one object share one listing, so orientations may be dependent. Then a rejection need not mean π > ½: under positive dependence the rejection rate of "π = ½" exceeds α, approximately.
- The NR-P power figure also assumes independence.
- Validity, interpretation and power are three distinct questions. **[DERIVED; needs independent review]**

## 8.15 The theory of linkage judgements (N-a/C-4)

*Sources:* `THEORETICAL-ASSESSMENT-2026-10-17.md` §2 and its Addendum §3 (Session B); Session A's derivations A and B (handoff §2 D3). Everything here is same-model, unreviewed.

**Setting** (v1.8 §3, VERIFIED):
- Each pair π of claims has a leaf ℓ(π) ∈ {⊤, ⊥, ?}.
- Coherence (c) uses ⊤-edges, so an unruled pair counts as no edge.
- Incoherence (c′) uses edges with ℓ ≠ ⊥, so an unruled pair counts as an edge.

**The two regimes:**
- **(b)** ℓ = r₁, a single ruling.
- **(a)** ℓ = r₁ if r₂ = r₁, and ? otherwise. Disagreement is final. ℓ_a is a *coarsening* of ℓ_b.

> **Lemma 8.10 (monotonicity)** [DERIVED; equivalent in substance to Session A's derivation A3 and to package v2 Lemma A]
>
> *Statement.* With the manifest fixed (M), sound checkers (S) and finality (F), coarsening any leaves maps the outcome to itself or to OPEN.
>
> *Proof sketch.* Coarsening removes ⊤-edges, so it cannot create coherence. It removes ⊥-edges from the incoherence test, so it cannot create a (c′) showing. A withdrawn J3 removal keeps a non-falsifiable claim in C\*(T), where it cannot enter a core and can only add subsets that must be disposed of.

> **Theorem 8.11 (pathwise weak dominance of (a))** [DERIVED]
>
> *Statement.* Under (M), (S) and (F), and (R) (the first rulings are the same in both regimes): if (a) yields a false definite verdict on some realization, then (b) yields the same verdict on that realization. This holds for **any** joint error law, including an adversarial r₂. It extends through the generation-sensitivity condition N2 (d): under (M) and (R), recovery under (a) ≤ recovery under (b), so N2 (d) passing under (a) implies it passes under (b).

> **Proposition 8.12 (gain and price)** [E, algebra]
>
> *Statement.* Per deciding pair, with error indicators of rates ε₁, ε₂ and phi correlation φ:
> - P(false determinate leaf) is ε₁ under (b), and P(both wrong) = ε₁ε₂ + φ·√(ε₁(1−ε₁)ε₂(1−ε₂)) under (a).
> - With ε₁ = ε₂ = ε: blocked errors = lost correct leaves = ε(1−ε)(1−φ).
> - NCTF by disconnection relies on many ⊥-leaves, so its losses compound across them.

> **Proposition 8.13 (item difficulty)** [E]
>
> *Statement.* For judges who are conditionally independent given the pair, P(both wrong) = E[ε_π²] ≥ (E ε_π)². So the "ε²" figure is a lower bound, even for genuinely independent judges.

> **Proposition 8.14 (disclosure)** [E]
>
> *Statement.* Under (b), disclosure does not change the probability of a false definite verdict.
> - With V-2 (i), k_min = 2 and core {H2, H4}, a single J1 ruling decides the outcome.
> - (b) also requires a fixed reading of the conflict passage (v1.8 l.69 vs l.270/313; Session A, derivation B3, confirmed).

**What the theory settles and what it leaves open:**
- **Settled:** (a) is never less safe than (b) under (M), (F), (S) and (R).
- **Not settled:**
  - the G1 case, where generation reads the leaves and Theorem 8.11 does not apply;
  - the size of φ;
  - whether the gain outweighs the lost definite verdicts. That is a loss ratio, which is the Principal's to set.
- **Neither option** protects candidate assembly.

## 8.16 The pre-data process-theory candidate T_P (HYPOTHETICAL)

*Source:* Session A, `audit-p3b/20261017_KIND-P-CANDIDATE-THEORY-STRUCTURE.md` (uncommitted). It was first read at r1 (sha `eed17218…`); this section is based on **r2** (sha `7d3f2de1…`), which appends Session A's investigation of six questions. Assessed and corrected here. This is the only theory content that can be stated before any S5 data. **It is a candidate structure, not a finding, and it is not registered.**

**Why it matters.** Under the proposed P-5, any candidate built from pre-registered hypotheses must be registered **before any S5 data** (S-VC18 l.78). No such candidate has yet been formulated.

**Definition (working).** T_P, "staged maturation with forward revision".
- *Empirical object:* the development histories of discovery-population labels.
- *Claims:*
  - **R₁** (from H1): serial dependence of change classes **in the listing order** of a label's timeline;
  - **R₂** (from H2): contradiction relations tend to run forward on the A.10 file-date axis (forward share > ½ beyond Model 0);
  - **R₄** (from H4): birth kinds tend to occur in the order lex < con < for < ope < gov on the A.10 axis (concordance beyond Model 0).
- *Assumptions:* A.10 dates order files; the agent classifications are valid (CA-8, C1 model).

**Assessment of the candidate:**

| # | Statement | Class | Note |
|---|---|---|---|
| 1 | The three claims do not share one axis: R₁ is on listing order (which, per contract l.714, carries no historical precedence); R₂ and R₄ are on A.10 dates | **[E]** (from verified code and contract facts; v2.9 scope) | Session A's point, confirmed and sharpened |
| 2 | Joint satisfiability is trivial, so coherence condition (a) cannot fail; incoherence can come only from (c′) or (b′) | **[E]** | confirmed |
| 3 | Weakest adequate structure: **three tendency statements sharing the label object**, plus whatever links J1 rules | **[DERIVED]** | **Correction:** Session A's "labelled transition system over (label, stage) with rank-increasing transitions" adds structure the hypotheses do not assert. H4 concerns *first births per kind*, H1 concerns *change classes between successive points*, which are different event types. Merging them violates P3b §13.11's weakest-structure rule |
| 4 | T_P is "confirmable but not refutable". Session A r2 keeps two derivations: Q1-A (CA-3 is met as written) and Q1-B (in Popper's sense T_P is not falsifiable, which stretches the term) | **[A], reformulated; both derivations preserved** | The claims are falsifiable in principle: reversed orders or backward contradictions are possible observations. What is missing is a **refutation rule** in the frozen procedure (non-rejection reads OPEN; NR-P cannot dispose of H2's > ½; no V-11). **Correction:** this does **not** fail CA-3, whose instrument for Kind P is "frozen statistic and Model 0; not UNT/VAC" (S-VC18 §4), and that is met. The consequence is v1.8 §11.3 (d): a registered T_P blocks NCTF unless disposed by (c′), (ii) or vacuously, or unless V-11 supplies a criterion |
| 5 | Core: H1 excluded (CA-8 under D0; no C1 model can change a frozen reading, Session A Q2-A). With k_min = 2 (V-3 open) the core is {H2, H4}, and it needs the C1 model for both (Session A Q4: required by the CA-8 text for H4's birth kinds) | **[A]** (v1.8 as written) | **Correction:** Session A r1's "if Q2 holds the core rests on H4 alone" overstates. Cor. 8.9a weakens H2 only through its R_C share, which is unknown |
| 6 | T_P is a **process** theory, never a **content** theory of KnowledgeOS | **[E]** (by the content of H1, H2, H4) | confirmed; carried into Ch. 1.3 and Ch. 13 |
| 7 | J1 for T_P: do H2 and H4 share an element of one formal object? Both are on the A.10 axis; H1 shares only the label object. Session A r2 keeps Q5-A (linked: shared assumption and domain) and Q5-B (not linked in substance: a shared clock is not a theoretical connection) | **[OPEN]**, a pre-data J1 ruling (item N-b) | human judgement |
| 7a | **Representation dependence of J1** (Session A's "drafting lever", confirmed). J1 links two claims iff they "share an element" of T's formal object. Whether they share one depends on which assumptions the drafter lists, so the J1 outcome is **not invariant under equivalent formulations** of the same formal object. A drafter who knows the consequences can set the outcome in either direction | **[E]** (from the J1 text, S-VC18 l.69) | a defect class of the proposed J1 rule, not only of T_P. **Mitigations (proposals, reserved):** draft the formal object independently of the J1 ruler and blind to consequences (Session A R-3); or define J1 over a canonical form of the formal object |
| 8 | Registering T_P creates a ROBUST route under V-2 (i) but blocks NCTF (absent V-11); not registering it removes the Kind-P route to ROBUST | **[A]** | **a reserved Principal choice** |
| 9 | **Case table** (Session A r2 Q6, checked against v1.8 §3, §5.2, §6.2 and §11.3; k_min = 2; S5c prohibited) | **[A]** | See below |

**Case table for item 9:**
- **Not registered:** ROBUST is impossible under either V-2 option (K-EG cores need CA-9).
- **Registered, J1 = linked:** ROBUST is possible only under V-2 (i). NCTF is blocked permanently unless V-11 is supplied.
- **Registered, J1 = not linked:** T_P is disposed by (c′).
- **Registered, J1 missing or conflicting:** OPEN.

**Implication.** Under V-2 (ii), registering T_P can only block NCTF. **The registration decision should follow V-2.** I agree with Session A's R-1.

---

# 9. Falsification and robustness

## 9.1 Principle

**[DESIGN-FROZEN]** "Every serious hypothesis must survive a search for contradiction, a competing explanation and a counterexample" (S-P3B §31). "At every stage, first ask: **what could prove our current interpretation wrong?**" (S-OP §27)

## 9.2 Methods (all [PLANNED]; none has been executed on S5 evidence)

| Method | Rule | Source |
|---|---|---|
| Four disconfirmation searches | A support, B contradiction, C discrimination, D counterexample. Each records population, method, completeness, termination bound and result; "An unbounded search is not a valid operation"; an empty B without a search record is "confirmation-only and fails G-09" | S-P3B §13.10 |
| Negative-verdict bar | INDEPENDENT or NEGATIVE-CENSUS only after a corpus-wide empty search; otherwise NEGATIVE-BOUNDED | S-MP l.293–300 |
| Logical attack | search for ∃x: Observed(x) ∧ ¬H(x) by enumeration, property-based testing, SAT/SMT and graph methods. "No counterexample found is not a proof unless the search was exhaustive over the relevant finite domain." | S-OP §15 |
| Model 0 and competitors | "Never test in isolation. Always define an explicit Model 0 and strong, non-strawman competitors" | S-OP §16; S-B1 §3–§4 |
| Controls | matched, blinded, pre-registered outcome; DISCOVERY claims only | S-P3B §9E.2 |
| Independence | recurrence counts independent occurrences only | S-P3B §9E.3 |
| Structural invariance | R2-INVARIANT or R2-FRAGILE across two reconstructions | S-B1 §2 |
| Anti-projection | "Would this status be the same if only sources up to the cited early source existed?" | S-P3B §14.4 |
| Pre-registration of tests | the test plan is hashed at TEST-DEFINED and persisted in an earlier run; post-hoc narrowing creates a new POST-HOC-NARROWED record | S-P3B §13.10a |
| Out-of-sample prediction | the H-19 hold-out, after an unseal that is currently prohibited | S-ADD §7 |
| Failure discipline | preserve the evidence; classify; do not retry at once; find the smallest valid correction; independent review; retry only when authorized. "never turn a failed experiment into a success by changing the rules afterwards" | S-OP §24 |

## 9.3 Boundaries on machine learning

**[DESIGN-ADOPTED]** "Before adjudicated S5 data exists: no ML for theory claims. ML never decides: truth, provenance, epistemic status, acceptance, significance, verdicts or weights." (S-OP §18; S-B1 §7)

## 9.4 Results

**[PENDING]** No falsification, robustness or counterexample result exists. Each will be reported in Chapter 12 with its search record.

---

# 10. Evidence-based reconstruction and theory recovery

## 10.1 The planned route

**[DESIGN-ADOPTED]** Theory recovery is ordered by O1: "Theory recovery follows S5a/S5b (P3b §9D/§9E) and G-LOG-0028 after S5b; no early corpus reads" (GL-0125). The route is:
1. **S5** per-label reconstruction (OBJECT scale).
2. **S5a**, the cross-object pass. Mechanical comparison candidates come from registered generators with matched controls, followed by a blinded review (S-P3B §9E steps 1–2).
3. **S5b**, the corpus pass. Recurrence is assessed across cross-object findings; "THEORY-CANDIDATE records where evidenced" are written. "A recurring pattern is not a theory candidate merely because it appears many times." (S-P3B §9E step 3)
4. **P3b terminal** → v3.5 A11 TERMINAL → P4 → P5 → P6 → P7 synthesis → GATE ("Never FINAL").
5. **Recovery charter.** "Any formal methodology for theory synthesis shall be defined in a separate, versioned charter **after S5b**, informed by the empirical results of S5a and S5b" (GL-0028 l.596). **[PLANNED]**

**[DESIGN, non-normative direction]** G-LOG-0028 sets the direction: "Understand first → identify invariants and recurring structures → formulate the theory independently in new language → preserve traceability from every theoretical claim back to its evidential basis."
- It "does not define the theory, select theoretical primitives, establish mathematical definitions, prescribe a synthesis algorithm, or authorize canonicalization."
- **[CONFLICT X-12]** The note calls itself non-normative but is incorporated by O1.

## 10.2 The optional early-read track

**[DRAFT]** S-TT1 proposes option **O3c**: "bounded early reads by a track separate from P3b, with no controls".
- Its ceiling is set by its review: "At best the track yields the authors' proposals, faithfully extracted and checked for internal coherence. It cannot reach RA-15 status D or SUPPORTED-IN-CORPUS" (S-TTR l.25).
- **[OPEN]** T-0 (adoption, as a carve-out or a replacement) and T-1 (route: (a) act / (b) P3b amendment / (c) keep O1) are pending (GL-0139).
- O1 remains in force until a separate act.

## 10.3 The timing tension

**[FACT]** The verdict proposal requires its thirteen decisions before any S5 data (S-VC18 §11.10). Its V-13 asks whether the candidate-generation procedure is fixed (α) before S5 data, which "plausibly departs from O1", or (β) after S5b (S-VC18 §12).

**[DERIVED]** These two requirements can be satisfied together only in one of two ways:
- the Principal rules V-13 in a way compatible with O1;
- the Principal amends O1 by a separate act.

The round-13 review identified the same collision as the smallest blocker for N-a/C-4 (S-VCR, R2-2). **[OPEN]**

## 10.4 Commitment of this manuscript

**[EDITORIAL]** Until S5b and the recovery charter exist, the manuscript writes **no substantive theory content**. It neither infers nor guesses at what the corpus might contain. Corpus-dependent sections will be filled from authorized, accepted, reviewed research packages only (README §3).

---

# 11. Independent review and reproducibility

## 11.1 Review practice in the programme

**[DESIGN-ADOPTED]** "Every substantive task gets an independent reviewer that tries to falsify it" (S-OP §7).
- Reviews use seven lenses: research, mathematics, statistics, formal logic, DDD, software engineering and governance.
- The verdict vocabulary is PASS / PASS-WITH-MINOR-FIX / NEEDS-REVISION / REJECT.
- "A material finding is repaired before the cycle advances."
- "Claims under review are hypotheses to audit, not established truth" (S-OP §8).

## 11.2 Three grades of review (manuscript classification)

**[EDITORIAL]** The record supports a distinction that this manuscript applies throughout:

| Grade | Definition | Examples in the record |
|---|---|---|
| **Consistency check** | the author's own or a same-session re-reading | Session B's checks in this manuscript |
| **Operational independence** | a fresh context with no write access, where the drafter wrote the prompts; the same model, or a different model | the verdict-criteria rounds 1–13, same model (S-VCR l.3, l.619, l.793); the prereg_v2 Reviews 1–4 (S-PRR); the R7 audit, "a fresh general-purpose agent **on a different model (Sonnet 5)**". The record's own limitation for the R7 audit: "it is dispatched by the implementation session (the prompt is the only channel), so it is not organizationally independent; model and context are" (GL-0090) |
| **Genuine independence** | a human expert, or a reviewer of a different model family, with prompts not written by the drafter | none found in the record for the S-Series design artifacts |

**[FACT]** None of the Stage-1 design artifacts has received a review of the third grade: the verdict criteria, prereg_v2 and the theory track. The Researcher recommends one such review for the Stage-1 report (S-ASM §5, suggestion 2). **[OPEN: TODO item P3]**

## 11.3 Reproducibility requirements

**[DESIGN-FROZEN]**
- **Identity.** Every artifact is identified by sha256, and frozen artifacts by their recorded hashes. A freeze needs an integrity check on the exact file (S-P3B §26 item 6).
- **Determinism.** Seeds are recorded (Freeze 2; S-ADD §7), and the analysis streams are named (S-PRV2).
- **Run separation.** A test plan is persisted in an earlier run than its test (S-P3B §13.10a).
- **Single writer.** "All shared and production mutation is serialized" (S-OP §6, AM-1).
- **Reproduction as a verdict condition [PROPOSAL].** R5 and N6 require independent reproduction. The code REPRODUCTION-PENDING applies until it is complete (S-VC18).

## 11.4 Resolving review findings

**[DESIGN-FROZEN]** A change is a new file citing the file it supersedes, plus a governance entry (S-P3B §26). "If execution invalidates this annex, stop, explain why, present the revision, and wait for approval." A finding about a frozen artifact goes back to the Researcher as a question; it is never patched in this manuscript.

## 11.5 Review plan for this manuscript

**[PLANNED; requires the Principal's authorization]**
- One review and one re-review of this Stage-1 report, by a reviewer of the third grade (§11.2).
- After the re-review, either the report is accepted with its remaining findings declared, or a change of approach is recommended. There will be no further drafting loop (working agreement; S-ASM §5 suggestion 6).
- **What the reviewer is asked to check** (from S-ASM §5, reviewer list):
  - status fidelity;
  - no evidence-free theory;
  - traceability against the actual files;
  - no new decision records;
  - terms;
  - faithful description of frozen material;
  - disclosure of the declared limitations in the body text.

---

# 12. Scientific findings and final assessment

**[PENDING]** This chapter is a frame. It is filled only from accepted, reviewed evidence. Its structure follows the separable report strata of S-OP §28: "what the corpus said → what we reconstructed → what we measured → what we inferred → what survived falsification → what remains uncertain."

## 12.1 Measurement (RQ-1, RQ-2)

| Quantity | Value | Record |
|---|---|---|
| Labels measured, \|M\| / 1,975 | **0** (current fact) | S-TODO l.47 |
| θ_D (HT estimate; exact upper bound) | PENDING | — |
| θ_A | PENDING | — |
| θ_E | NOT-ESTABLISHED unless a justification is accepted | S-B1 §1 |
| R1, R2 per hypothesis | PENDING | — |

## 12.2 Pre-registered hypotheses (RQ-3)

| H | Family | T | m / n | p | p (Holm or BH) | Outcome | Licensed reading |
|---|---|---|---|---|---|---|---|
| H1 | confirmatory | PENDING | | | | | |
| H2 | confirmatory | PENDING | | | | | |
| H4 | confirmatory | PENDING | | | | | |
| H3 | exploratory | PENDING | | | | | |
| H5 | exploratory | PENDING | | | | | |

## 12.3 Theory candidates (RQ-4)

| Candidate | claim_type | ev level | STATUS | A–D searches | Controls | R2 | Prediction | Codes |
|---|---|---|---|---|---|---|---|---|
| none exist | | | | | | | | |

## 12.4 Programme outcome (RQ-0)

**[PENDING]** The outcome slot. It is written only in the adopted wording (Ch. 7.3) with its reason codes, after independent reproduction and the human J4 act. The outcome is not preselected. **At present no outcome has been reached** (Ch. 1.4).

---

# 13. Integrated theory and implications

## 13.1 Conditions for writing this chapter

**[DESIGN-FROZEN]** This chapter is written only after all of the following:
1. A verdict computed under adopted criteria.
2. Independent reproduction.
3. The Principal's adoption of the outcome.
4. For any canonical statement, the governance GATE. "Never FINAL" (S-MP GATE); canonical adoption belongs to v3.5 P5/P6 "through recorded human acts" (S-P3B §1D).

## 13.2 What each outcome would permit

| Outcome | This chapter would contain | Source |
|---|---|---|
| ROBUST | the coherent core C₁…C_k in the permitted wording, with its scope (the discovery population), assumptions, caveats and the residual uncertainty, followed by a P7 synthesis labelled "RECONSTRUCTED, PENDING GOVERNANCE REVIEW" | S-VC18 §5 [PROPOSAL]; S-MP A13 |
| NCTF | the search record, the candidates and the grounds for their disposal; what the search could and could not detect; no claim about unexamined theories | S-VC18 §6 [PROPOSAL] |
| OPEN | the reason codes, and what evidence would resolve each | S-VC18 §7 [PROPOSAL] |

## 13.3 Implementation specifications

**[DESIGN-FROZEN as a lens / PROPOSAL]** Implementation specifications are a later stage. Under v3.5, canonicalization follows only the GATE. ARCH (as a lens) places "validated → canonical → specification → implementation" in Layer 5, "unreachable from both contexts" without a governance act (S-ARCH §3). S-C4 §7 proposes that a product architecture be derived only after ROBUST plus canonicalization.

## 13.4 Scope of any eventual theory

**[DERIVED; Ch. 1.3, 8.16]**
- A verdict built only on the pre-registered hypotheses (Kind P, e.g. T_P) can describe at most **how the corpus developed** (a process theory).
- A theory of **what KnowledgeOS is** (its concepts, invariants and structures) can come only from Kind K candidates recovered in S5b, and under V-2 (ii) only with scored out-of-sample predictions.
- This chapter must state which kind of theory any adopted outcome concerns.

**[PENDING]** Everything else in this chapter.

---

# 14. References, appendices and traceability

## 14.1 References

All sources are listed, with path, branch, SHA-256, commit and governance status, in **`SOURCE-REGISTER.md`**. That file is part of this manuscript.

## 14.2 Governing records not duplicated here

**[EDITORIAL]** The manuscript **does not copy** the following. It refers to them:
- the governance log (S-GL), for decisions;
- the S-Series TODO (S-TODO), for open decision codes and working state;
- the verdict-criteria review record (S-VCR) and §22.3 of S-VC18, for the open procedural items.

## 14.3 Verification performed by Session B (2026-10-17)

- SHA-256 of every register row computed and compared with the governance-log record; all match.
- Directly re-read against the files:
  - S-OP l.12 (the outcomes), l.215 (measured);
  - S-P3B §13.9b (l.1381–1399);
  - S-VC18 §7.5 (l.296);
  - GL-0034 (P3b freeze), GL-0016 (ADD approval), GL-0117 (run-3 deferral, l.1906);
  - GL-0125 (O1, l.2010), GL-0137 (prereg_v2 freeze), GL-0138–0140;
  - S-TODO l.5, l.8, l.47 (0 of 1,975);
  - S-B1 in full.
- Other content was taken from line-referenced extracts made by read-only assistants under the same access rule. **Before the external review, the Researcher or the reviewer should spot-check quotations against the files**, not against this text.
- Not run: the prereg_v2 test suite (41 tests as recorded), and any computation over data.

## Appendix A: Source conflicts

See `SOURCE-REGISTER.md`, X-1…X-12. Items recorded by the manuscript but not queued to the Researcher:
- **A-13**: the confirmatory B, the analysis seed and the single-run rule. Unresolved; **verified by both sessions** (Ch. 5.1, 8.13). Reserved for the Principal with RQ-1.
- **A-15**: P3b §9.8 ("IN HISTORICAL ORDER") vs contract l.714 ("predecessor … carries no historical precedence"). **Resolved as not a conflict** (Ch. 8.14): l.714 concerns the verifier's precedence derivation.
- **A-16**: Q2-related facts were verified for the v2.9 draft contract (`7eba0385…`) only; the production contract v2.8.1 is uninspected.
- **A-17**: lane status. Session A's records say the professor lane was "set aside"; the Principal has since commissioned Session B directly. Neither is in the governance log.
- **A-14**: whether H-17 (the control-comparison parameters) and H-16 (N_support) have been decided. Session B did not find them in the entries it read.

## Appendix B: Proof obligations

See Ch. 8.10, PO-1…PO-10.

## Appendix C: Requests to the Researcher

The numbered queue is kept in `README.md` §5, with at most three open at a time.

## Appendix D: Decisions reserved for the Principal that affect this manuscript (pointers only)

**[OPEN]** The authoritative list is S-TODO (codes) and the decision text in S-GL. This appendix only points.
- **Q-a** (N-a/C-4): binding second judgement, or declared limitation. Affects Ch. 7.6.
- **V-1…V-13**: the verdict criteria. Affects Ch. 7 and Ch. 12.
- **Decision A**: whether ARCH v1.2 governs the S-lane. Affects Ch. 2–3 and every RA citation.
- **T-0/T-1**: the theory track. Affects Ch. 10.2.
- **P1 / P3**: the working model; independent review of this report. Affects Ch. 11.5.
- **B1**: the run-3 launch conditions. Affects the activity of this lane (README §4).

## Appendix E: Questions from the Professor to the Principal

**[OPEN]** These are drafted for the Principal's use. None is a decision request in the governance sense.
1. **RQ-0 wording.** Does RQ-0 (Ch. 1.3) state the research question the Principal intends? If not, what should change?
2. **Intended contribution.** What scientific contribution should the theory make: its audience, the generality claimed, and its relation to existing theory (Ch. 1.5)?
3. **Independent reviewer.** Who will provide the third-grade review of this report (Ch. 11.5)?

---

# PART III. CORPUS-DERIVED CANDIDATE THEORY (research hypotheses)

# 15. The KnowledgeOS kernel: a candidate theory from the brainstorming corpus

| | |
|---|---|
| **Authority** | G-LOG-0141 (the Principal's act, recorded by the Principal in the PROD governance log, l.2220 ff.): read-only reading of `docs/knowledgeos/brainstorming/` by Session B, with a script-side hold-out filter, every read logged |
| **Hold-out** | `corpus-access/discovery_filter.py` over the three priority folders: **1,492 allowed, 18 withheld (sealed)**; 1,471 of the allowed files are in the P3b census. Only allowed files were read |
| **Reads for this chapter** | `corpus-access/READ-LOG.tsv`. Read and cited: step 285 canonical-state reconciliation (`24609ec8…`); the 2026-09-01 mathematical-theory research prompt (`c3b9dabc…`); kernel research files 30 (`b358915c…`) and 38 (`57fa8751…`). `00-INDEX.md` was logged in error and **not read** (a correction line is in the log) |
| **Standing** | **P3b layers B/C only: research observations, interpretations and hypotheses.** Nothing here is SUPPORTED-IN-CORPUS, confirmatory or verdict evidence, and nothing here substitutes for S5 (v3.5 R19). This Part does **not** feed Ch. 12–13. Session B is corpus-exposed for its lifetime (G-LOG-0141 item 5) |
| **Lanes** | S-Series only. F-Series material is deliberately **not** used here; the two lanes are combined at the end (Principal, 2026-10-17) |
| **Source class** | The four documents read are **SECONDARY-SYNTHESIS** (research programmes and syntheses by the corpus's authors and assistants, 2026-08-31 to 2026-09-01), not primary sources. Where they call something "ratified", that is a corpus claim ([CLAIM]), not an S-Series governance act |

**Labels used in this Part:**
- **[SRC]**: what a cited corpus document says;
- **[CLAIM]**: a status the corpus asserts about itself;
- **[DERIVED-B]**: Session B's own derivation (same model family; unreviewed);
- **[CONJ]**: a conjecture;
- **[OPEN]**.

## 15.1 The research problem the corpus poses

**[SRC]** The corpus organizes the kernel as a dependency chain (step 285 §2):

  K (canonical state) → 𝓘 (mandatory invariants) → 𝓞 (operation universe) → δ (transition semantics) → kernel,

with the rule "no canonical K ⇒ no canonical 𝓘 ⇒ no canonical 𝓞 ⇒ no canonical δ".

**[SRC]** The open question is "which K is canonical". Six models are posed:
- **A:** complete knowledge state K_t = (𝒜, ℛ, Σ, …);
- **B:** assertion–relation state K = (𝒜, ℛ), with all else external;
- **C:** a domain state, with (𝒜, ℛ) as a projection;
- **D:** an epistemic projection of a broader system state;
- **E:** layered, S_t = (K_t, E_t, H_t, G_t, Q_t);
- **F:** separate products.

The corpus requires that "No model shall be selected by intuition". Its objective is to "find the **smallest** semantically coherent state representation that contains everything the KnowledgeOS kernel must know, and nothing that merely happens to surround that knowledge" (§23).

**[SRC]** Step 285 §14 names the target predicate, Sufficient(K, 𝓞, 𝓘): the state must suffice to
- evaluate every mandatory invariant;
- determine operation legality;
- execute canonical transformations;
- distinguish required identities;
- support equality decisions;
- support deterministic replay.

It adds: "The exact formal predicate must be derived later." §15 states an information test: a legality decision L(o, K, X) must reduce to L(o, K) unless X is declared external.

## 15.2 What the corpus has settled (as it reports it)

| # | Statement | Class | Source |
|---|---|---|---|
| S-1 | K is expressed in **eight primitives**: Entity, State, Event, Observation, Proposition, Relation, Policy, Action. These are "the VOCABULARY IN WHICH state is expressed, not its components, not its fields". K "be defined **extensionally through behavioural sufficiency**, not compositionally through an arbitrary tuple" (citing 258.35) | [CLAIM] "ratified"; 258.35 **not read** | 30 §1 |
| S-2 | Separation of categories: K ≠ operation ≠ δ ≠ history H ≠ evidence E ≠ governance act ≠ inquiry; K′ = δ(K, o) | [SRC], a design hypothesis | 285 §16, §23 |
| S-3 | **Operation identity is not extensional in its effect:** δ(K, o₁) = δ(K, o₂) ⇏ o₁ = o₂ (provenance, authority, lineage may differ) | [SRC], "a major research question" | 285 §6 |
| S-4 | **Evidence is not state:** observations Y_t ∼ P(Y_t ∣ K_t) | [SRC] | 285 §7 |
| S-5 | Discrimination ("Buddhi") is an **operator** over K, not a primitive; the knower ("Kṣetrajña") is **outside** K; "Guṇa" is a **mode**, and state ≠ mode | [SRC]/[CLAIM] | 30 §2 |
| S-6 | The layered pipeline X → Y_t → E_t → 𝓕_t → Π_t → K_t (world, observation, evidence, information structure, probabilistic representation, epistemic state). Constraint **C21: 𝓕_t ≠ Π_t ≠ K_t** | [SRC], "not adopted"; "K_t is still not formally solved" | 38 §2, §7 |
| S-7 | **Two negative results**, reported as convergent across the corpus, Dretske and Kallenberg: "probability IS knowledge" – No; "one scalar probability defines knowledge" – No | [SRC]; the external sources were not read by Session B | 38 §1 |
| S-8 | **Two undefined transformations:** Qualify : Y_t → E_t (named, typed, no body) and Φ : Π_t → K_t (named, untyped, no body). They "must not be merged" | [SRC], OPEN in the corpus | 38 §3 |
| S-9 | The action loop: K_t → Zero → Determination → Decision → Authorization → Action → Y_{t+1} | [SRC], "re-raised, not re-instated" | 38 §2(c) |
| S-10 | Knowledge evolution is **non-monotone** (correction, retraction, supersession), so K_{t+1} ⋡ K_t in general (marked SUPPORTED/DERIVED in its own ledger). On "purification" the source says: "Do not combine these into one scalar **unless mathematically justified**" and "A **partial order may** be more appropriate than a total order" (l.698, l.700). *(Corrected 2026-10-17: v0.3 had dropped the conditional.)* The corpus's Gap theory supplies a candidate order (§15.7) | [SRC] | 2026-09-01 prompt §14–15 |

**[DERIVED-B] Assessment of S-1 to S-10.**
- S-1's demand for an *extensional, behavioural* definition and S-7's rejection of any single scalar are mutually consistent.
- So are S-2/S-3 (operation ≠ effect) and S-4 (evidence ≠ state).
- None of the ten conflicts with another. That is a necessary condition for a coherent theory, not a sufficient one.
- The corpus's own open items are where the missing mathematics sits: the sufficiency predicate (15.1), Qualify, Φ, and Determination.

## 15.3 A formal core: the canonical state as the minimal sufficient state [DERIVED-B]

The corpus asks for K to be defined "extensionally through behavioural sufficiency" and for "the smallest" sufficient representation. Both requirements have a precise classical answer: the **Myhill–Nerode** construction of minimal realizations. Stating it fixes what "canonical K" can mean.

**Setting.**
- Σ is the set of inputs the kernel can receive (operation requests and admitted observations).
- Histories are finite words h ∈ Σ\*.
- 𝔇 is the set of **required decisions**, each a function d : Σ\* → V_d on histories. 𝔇 contains:
  - legality L_o for every o ∈ 𝓞;
  - every mandatory invariant evaluation in 𝓘;
  - required identity and equality decisions;
  - required outputs.

> **Definition 15.1 (behavioural equivalence).** h ∼ h′ iff for every continuation w ∈ Σ\* and every d ∈ 𝔇, d(hw) = d(h′w).

> **Proposition 15.2.** ∼ is an equivalence relation and a right congruence: h ∼ h′ ⇒ ha ∼ h′a for every a ∈ Σ.
>
> *Proof.* Reflexivity, symmetry and transitivity are inherited from equality. For a ∈ Σ and any w, d(haw) = d(h(aw)) = d(h′(aw)) = d(h′aw), because aw is itself a continuation. ∎

> **Definition 15.3 (canonical state and transition).** K(h) := [h]_∼. Set δ([h], a) := [ha] (well defined by Prop. 15.2) and K₀ := [ε].
>
> Then K_t = fold(δ, H_t, K₀) holds **exactly**. This is step 285 §10's replay model, now with a proof that δ is well defined.

> **Proposition 15.4 (minimality and universality).** Let (S, s₀, δ_S) be any deterministic state representation, with state s(h) = fold(δ_S, h, s₀), that is **sufficient**: every d ∈ 𝔇 factors through it, d(h) = f_d(s(h)). Then s(h) = s(h′) ⇒ h ∼ h′. Hence the map s(h) ↦ [h] is well defined and onto the reachable classes, and K is the **coarsest** sufficient state. Any two minimal sufficient representations are isomorphic.
>
> *Proof.* If s(h) = s(h′), then by determinism s(hw) = s(h′w) for every w, so d(hw) = f_d(s(hw)) = f_d(s(h′w)) = d(h′w). Hence h ∼ h′. Isomorphism of minimal representations is the standard Myhill–Nerode argument. ∎

**Consequences for the corpus's open questions (each [DERIVED-B]):**
1. **The six state models become one test.** Any concrete tuple (models A, B or E) is a *representation*. It is sufficient iff its induced equivalence refines ∼, and canonical iff it equals ∼.
   - Model B, (𝒜, ℛ), is the canonical K **iff** assertions and relations alone separate every pair of histories that some required decision separates. Otherwise model C or D holds, with (𝒜, ℛ) a projection.
   - "Which K is canonical" is thus a **decidable comparison relative to 𝔇**, not a matter of taste. This is the meaning of the corpus's §13 classification test (X ⊆ K, X = π_X(K), X ⊥ K).
2. **Membership criterion for constructs.** A construct X (evidence, provenance, a governance record, the inquiry set Q, history) belongs in K **iff** omitting it would merge two histories that some d ∈ 𝔇 distinguishes, on some continuation. Otherwise it is history or external context. This is exactly the corpus's information test L(o, K, X) = L(o, K) (285 §15), generalized to all of 𝔇 and to future continuations.
3. **A dependency the corpus states in the wrong order.** By Def. 15.1, K is *determined by* (Σ, 𝔇), that is, by the operation universe and the required decisions, which include the invariants. So K cannot logically precede 𝓘 and 𝓞, as the chain "no canonical K ⇒ no canonical 𝓘 ⇒ no canonical 𝓞" presents it.
   - The correct statement is K = F(𝓞, 𝔇), with 𝓘 ⊆ 𝔇: the state question is well posed only once the alphabet and the required decisions are fixed. **Fixing 𝔇 is the actual primitive choice.**
   - This may explain why "which K?" stayed blocked. The corpus has files named "cycle reconstruction" and "bootstrap boundary" (step 289); whether they already reach this is **[OPEN]** (not read).
4. **Operation identity (S-3).** If o₁ and o₂ have the same effect on every class, no state-based decision can tell them apart. If provenance or authority must distinguish them, then some d ∈ 𝔇 depends on *how* a state was reached, and the relevant part of history enters K.
   - Hence: **K ≠ H holds exactly when no required decision depends on the path beyond its class.** This sharpens S-2 into a checkable condition.
5. **Non-monotonicity and "purification" (S-10).** ∼ carries no order. Any notion of "better knowledge" is an *additional* structure on classes, and nothing forces it to be total. This agrees with S-7 (no scalar) and S-10 (partial order at most).

**Limits:**
- The construction assumes **determinism** of the kernel's response to inputs. Section 15.4 treats the stochastic side, observations and belief.
- It says nothing about *which* decisions 𝔇 must contain. That remains the corpus's normative question, reserved, in corpus terms, to its governance.

## 15.4 The stochastic side: Qualify, Φ and Determination [DERIVED-B / CONJ]

Treat the world state as latent and observations as Y_t ∼ P(Y_t ∣ X_t) (S-4). The classical stochastic analogue of Prop. 15.4 is that the **belief state** (the posterior given the observation history) is a sufficient statistic for decision-making in a partially observed system. The **predictive-state representations** of machine learning generalize the Nerode construction to probabilistic systems.

Read against the corpus's layered pipeline (S-6):
- **Π_t** corresponds to the belief state. That is "the probabilistic representation", **not** knowledge (S-7).
- **Φ : Π_t → K_t** must turn a belief into *commitments* (the accepted propositions an agent acts on). In decision theory that step requires a **declared loss or acceptance rule** ℓ: Φ_ℓ(Π) = the commitments that minimize expected ℓ, or that pass a stated acceptance threshold.
  - This explains *why* the corpus finds Φ irreducible and non-trivial (S-8): without ℓ it is undefined.
  - It also gives the second negative result (S-7) a precise reason: any scalar summary smuggles in an undeclared ℓ. The corpus says this itself: conditional expectation is optimal "under a particular loss" (38 §1). **[DERIVED-B]**
- **Determination** in the action loop (S-9) sits between Zero and Decision, and also maps an epistemic state to commitments. **[CONJ]** Φ and Determination are the same type of map (belief → commitment under a declared rule), applied at different scopes: Φ to knowledge, Determination to action. If so, the corpus's two undefined objects would share one specification schema. The corpus explicitly refuses to merge Φ with Qualify (S-8); it does not discuss Φ versus Determination.
- **Qualify : Y_t → E_t** is plausibly a **measurement model**: an admissibility predicate together with the likelihood that ties an observation to the latent world (which observations count as evidence, and about what). **[CONJ]**

## 15.5 Ledger of this chapter

| Claim | Class | What would weaken it | Next step |
|---|---|---|---|
| The corpus's ten statements S-1…S-10 are mutually consistent | [DERIVED-B] (check of the cited texts) | a primary source contradicting one of them | read the primaries behind S-1 (258.35) and S-6 |
| Canonical K = the Nerode quotient of histories by required-decision equivalence (Def. 15.1–Prop. 15.4) | **established by derivation** as a standard theorem applied here, under determinism; **same-model, unreviewed**; refined by §15.8 to realizable sufficiency | a kernel requirement that is not a function of the history (non-determinism; external oracle) | 15.4 handles stochastic inputs; external oracles must be declared in 𝔇's inputs |
| K is determined by (𝓞, 𝔇). Step 285's K-first ordering is inverted relative to this, **but the corpus's own Gap theory already makes the epistemic contract EC primary** (§15.7), so this is an *internal tension in the corpus*, not a corpus-wide error | [DERIVED-B]; corrected 2026-10-17 | the step-289 "cycle reconstruction" or "bootstrap boundary" files may already resolve it | **read step-289/01–03** |
| K ≠ H iff no required decision depends on the path beyond its class | [DERIVED-B] | — | apply to provenance (S-3) once 𝔇 is listed |
| Φ needs a declared loss or acceptance rule | [DERIVED-B] | a corpus definition of Φ without one | read files 35–37 (missingness, probability space, convergence protocol) |
| Φ and Determination share one type | [CONJ] | different input or output types in the corpus | read the Determination specification (cited as "157/165") |
| Qualify is a measurement model | [CONJ] | the corpus's G1 typing | read the G1 sources |

## 15.6 Next reading (S-Series lane, through the filter)

In order of information value:
1. step-289/01–03 (dependency graph, cycle reconstruction, bootstrap boundary), which tests §15.3 point 3;
2. REFINED-STEP-287 (invariants) and REFINED-STEP-288 (identity and equality), to see what candidates for 𝔇 exist;
3. research files 31 (state vocabulary and operator contract), 34 (dimension model), 35 (missingness carrier) and 36–37 (probability space; convergence protocol), for §15.4;
4. the primary text behind 258.35 ("behavioural sufficiency").

Each read is logged; each new statement enters §15.2 or §15.5 with its class.

## 15.7 The corpus's own systematic theory, as Session A's families record it

*Authority:* G-LOG-0141 extension (2026-10-17), which covers all discovery-population files of `02-FILES.jsonl` and `chronological-read/20-FAMILIES/`.

*Filter* (`corpus-access/corpus_filter.py`):
- label-list hash verified;
- corpus: 2,734 allowed, 33 sealed excluded;
- families: 2,457 allowed, 48 withheld (conservative label rule);
- the 30 family names displayed before authorization: 0 withheld, so **none is a hold-out label**.

*Profile:* `corpus-access/FAMILIES-PROFILE.tsv`:
- 814 theory-level families;
- lifecycles: 1,359 DORMANT, 837 ACTIVE, 255 CONTESTED.

*Families read* (header, births, completeness, rationale; logged):
- `abk-1-annotated-bilattice-kernel-candidate`;
- `knowledgeos-theory-v1-0-definitions-axioms-theorems`;
- `kos-theory-v2-rewrite-parts-i-xvii-2026-09`;
- `information-sufficiency-compression-model`;
- `oq4-adequacy-realization-witness-2026-09`.

*Standing:* a family is Session A's P1/P2 reconstruction, "a handle, not an identity claim". Its rows quote the corpus by source ID. The statements below are therefore **[SRC via family]**. The underlying source files were not re-read.

**Note on order.** Families report source IDs (S####). Source-ID order is **ingestion order, not chronology** (v3.5 R1). The version labels v1.0, v1.1, v1.2 and v2 are the corpus's own.

| # | Corpus construct | What the family records | Source IDs |
|---|---|---|---|
| T-1 | **Theory v1.0**: definitions, axioms, theorems | 33 DEF, 7 AX, about 11 THM. **AX-1** category non-collapse ("O≠K, Evidence≠Knowledge, Claim≠Truth, Probability≠Knowledge, Proposal≠Decision, Representation≠Identity"). **AX-2** knower-frame ownership. **AX-3** extraction–determination separation. **AX-4** non-monotonicity. **AX-5** provenance preservation. **AX-6** proposal–decision separation. **AX-7** a structure-adding principle. Knowledge is factive: Knows(s, p, c, t) ⇒ True(p, c, t). Self-verdict: **"THEORY CONDITIONALLY VERIFIED"**, not complete | S2373, S2375–S2378 |
| T-2 | **Gap and Zero** | Δ_t = Gap(K_t, EC_t) = {r ∈ Req(EC_t) : ¬Sat(K_t, r)}. **Zero ⇔ Δ_t = ∅ ⇔ K_t ⊨ EC_t.** Zero is the bottom of the lattice (𝒫(R_t), ⊆). A **contract-relative order** K_{t+1} ≥_EC K_t ⇔ Δ_{t+1} ⊆ Δ_t (THM-G2). **Contract invariance** (THM-G4): gaps compare only under compatible contracts. "Gap increase does not imply knowledge decrease". A ten-class gap taxonomy | companion "gap theory v1.0", via family T-1 |
| T-3 | **v1.1 derivation** | repairs a factivity inconsistency: KnowledgeAttribution(K_t) ⊆ EpistemicState(K_t), and EpistemicState → Determination → KnowledgeAttribution. **Behavioural semantic equivalence:** Obs_{EC,Q,C}(R₁) = Obs_{EC,Q,C}(R₂) for all (Q, C, EC). Identity independent of state equality, via a persistent identifier. Five open problems: truth semantics, semantic-equivalence obligations, transition algebra, the attribution function Γ, kernel minimality | via family T-1 (group G0563) |
| T-4 | **v2 rewrite**, Parts I–XVII | "Reality ≠ Observation ≠ Information ≠ Evidence ≠ Knowledge ≠ Decision ≠ Action". A transition-system formalism. The core operations **𝒪_core = {ASSERT, LINK, REVISE, RETRACT, ISOLATE}**. Contract-relative Zero. Governance: **FalseZero ⇔ ZeroClaim ∧ Δ ≠ ∅**, so Zero claims are "auditable assertions rather than ordinary status fields" | S2763–S2780 |
| T-5 | **Information sufficiency** (step 88) | InformationValue = f(Information, Question), with no question-independent scalar. Decision-sufficient ("semantic") losslessness is distinct from byte-level losslessness. The information bottleneck: max I(Z; D) s.t. I(Z; X) ≤ C. K_sufficient(Q) | S0987 |
| T-6 | **Representation adequacy** and **Executable Adequacy** | R_req(Q, Γ) ⊆ D. **Adequacy(R, Q, Γ) ⇔ R_req(Q, Γ) ⊆ Preserved(R, Γ)** (recorded CLOSED). EA(K, O, Q, Γ): representation and operations together answer Q, preserve every required distinction and produce an auditable transition. Self-correction: "6/6 tests passed" ⇏ "Adequacy = 1.0"; the correct claim is Adequacy_tested(ABK1, R_test) = 1 | S2569–S2581 |
| T-7 | **Adequacy ≠ Realization** witness, an executed experiment | at a level where Ĥ(Q∣R) = 0 (Q is determined by R), realization still fails, by two pre-declared routes: a decoder weaker than the information, and a k-anonymity constraint blocking exactly the singleton fibres (16,939 = 16,939). Status: "Theory v1.2 FROZEN, kernel NOT SELECTED, nothing adjudicated" | S2871–S2880 |
| T-8 | **ABK-1**, a representation candidate | the state of each proposition is ⟨ν, J, τ, μ⟩: a Belnap–Dunn FDE value, a justification DAG, a temporal window, modal and scope metadata. It carries a knowledge order ≤_k and a truth order ≤_t. "Representation: CLOSED as a validated candidate; kernel: NOT CLOSED" | S2569–S2581 |

## 15.8 Unification: the corpus's constructs as one structure [DERIVED-B]

The Nerode construction of §15.3 is **not a new idea relative to the corpus**. It is a common formal form for T-2, T-3, T-5 and T-6, which the corpus states separately.

| Corpus construct | Its form in §15.3 |
|---|---|
| Required questions Q and requirement set Req(EC) (T-2, T-6) | the required decisions 𝔇. **The epistemic contract EC fixes 𝔇.** So "fixing 𝔇 is the primitive choice" (§15.3 consequence 3) is the corpus's own move in the Gap theory |
| A required distinction (h, h′) ∈ R_req: some required question separates the two | a pair with h ≁ h′ |
| Adequacy(R, Q, Γ) ⇔ R_req ⊆ Preserved(R) (T-6) | **R's induced equivalence refines ∼** (Prop. 15.4's sufficiency) |
| Behavioural equivalence Obs_{EC,Q,C}(R₁) = Obs_{EC,Q,C}(R₂) (T-3) | equality of the decision behaviour, i.e. of the induced quotients |
| K_sufficient(Q), decision-sufficient losslessness (T-5) | sufficiency of a representation for 𝔇 |
| Zero ⇔ Δ = ∅ (T-2) | every required decision is satisfied at the current class. Zero is a property of K **relative to EC** |
| The order ≥_EC by gap inclusion (T-2) | a preorder on classes, induced by EC. It is partial, contract-relative and non-scalar, consistent with S-7 and S-10 |

> **Refinement 15.5 (realizable sufficiency)** [DERIVED-B; motivated by T-7]
>
> Prop. 15.4 requires only that each decision d *factors* through the state: d = f_d ∘ s for *some* function f_d. That is **information-theoretic sufficiency**, Ĥ(Q∣R) = 0. T-7 shows, by an executed witness, that this is not enough for a kernel. A representation can determine Q while the permitted decoders cannot compute it (a weak decoder), or are forbidden to (k-anonymity).
>
> So fix a class 𝔉 of **permitted decoders**, constrained by computability, cost and policy (privacy, authority). Call a representation **𝔉-sufficient** iff each f_d ∈ 𝔉.
>
> - The coarsest 𝔉-sufficient state is in general **finer** than the Nerode quotient, and may fail to exist uniquely when 𝔉 is not closed under composition.
> - This is the formal content of T-6's Executable Adequacy, EA = Adequacy + realization under constraints.
> - It is also the content of v1.0's theorem "operator-count minimality is representation-relative" (T-1).
> - **[OPEN]** Which closure properties of 𝔉 make a unique minimal 𝔉-sufficient state exist. This is a precise formulation of T-3's open problem "kernel minimality".

**Correction to §15.3 consequence 3.**
- Step 285 presents K before 𝓘 and 𝓞. The Gap theory (T-2) makes the contract EC, and with it the required decisions, primary.
- So the corpus contains **both orderings**. §15.3's result supports the EC-first one.
- Which is later, and whether the Gap theory superseded step 285, cannot be read from source-ID order. **[OPEN]**

**Coherence check across T-1 to T-8** [DERIVED-B; same-model]:
- AX-1 (non-collapse) agrees with S-2 and S-7.
- AX-4 (non-monotonicity) agrees with S-10.
- T-2's order is partial and contract-relative, consistent with S-7.
- T-6's self-correction and T-7's witness discipline the claims of T-8.
- T-4's 𝒪_core is a candidate operation universe, i.e. the alphabet Σ of §15.3. Its minimality is open (T-3, R5).

No contradiction among T-1 to T-8 was found. One **symbol collision** is recorded in the corpus itself: "K" carries seven independently coined definitions (family T-4, group G0759; 09-ORCHESTRATOR-FLAGS B0069). §15.3's K is an eighth; it is the quotient, and is stated as such.

**Next reading** (refined by §15.7–15.8):
1. the family `knowledgeos-kernel-concept` (385 rows; the largest theory family);
2. `kr-kernel-minimality-2026-09` and `kernel-reduction-experiment-KR-2026-09-01-executed-results`, for 𝔉 and minimality;
3. `knowledgeos-theory-v1-1-formal-derivation` and `knowledgeos-gap-theory-v1-0`, to verify T-2/T-3 at the family level;
4. the primary source files S2373, S2763 and S2871, to verify T-1, T-4 and T-7 directly.

*End of manuscript v0.4. Revision record: `README.md` §6.*
