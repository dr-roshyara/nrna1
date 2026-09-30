# Independent Corpus-to-Theory Coverage Audit — F0028 · F0035 · F0036 · F0040

| | |
|---|---|
| **Kind** | Independent, observational coverage audit (source → Phase 1 → Phase 2) |
| **Status** | ⚠️ **authority: generated** — audit evidence, not a governance decision |
| **Date** | 2026-09-23 |
| **Evaluated state** | research tree at **HEAD `ca8b96d59`** ("F0036-F0040 — v0.9"), taken as a `git archive` snapshot into the session scratchpad. The corpus files come from the working tree; they are unchanged since 2026-08-04 (`git log`) |
| **Canonical file list** | `docs/knowledgeos/list_of_files_to_read.log` (3,081 rows; one commit, `7698c99b4`, 2026-09-22; not modified since) |
| **Modified** | nothing in the research tree, the governance tree, the corpus or the gates. This report is the only artifact written |
| **Out of scope** | whether the theory is correct · architecture quality · promotion · new gates |

**Auditor independence (stated, not assumed):**
- This session did not construct the theory.
- The four corpus files were read **in full before any research artifact was opened**.
- **Limits:**
  - earlier in the same session I read the research protocols and the registry *schemas*, though not the theory document and not these files;
  - I am the same model family as the research session, so shared blind spots are possible.

---

## Executive conclusion

> ## **INCONCLUSIVE on the general question, because of a more basic defect.** Three of the four audited files **were never read**. The research registry assigns their IDs to *different physical files*.

**The substantive result is an identifier-integrity failure the question did not anticipate:**
- In the research `FILE-REGISTRY.jsonl`, **9 of the 10 rows F0031–F0040 point to a different file** from the canonical list. F0039 is the only match.
- From F0031 onward, the research gave the next sequential ID to each file in the order it chose to read them. The protocol forbids exactly this (P1P §4, l.1123–1132): *"never renumber, never reassign, never regenerate."*
- Consequences:
  - the canonical **F0032, F0035, F0036 and F0040 have not been read at all**;
  - four files from outside the first-40 window have been read under in-window IDs: canonical F0042, F0045, F2837 and F2838;
  - every `F0031`–`F0040` citation in the theory, changelog and state refers to a different file than a reader of the canonical list would open.
- I found **no record of the deviation** in any research artifact.
- **All five activated gates pass, and the runner reports `STATUS: CLEAR`.**

For the one file that could be traced, **F0028, the result is PARTIALLY SUBSTANTIVE**:
- 7 of 18 independently identified substantive units reached Phase 1;
- 4 materially shaped the theory;
- 1 unit's meaning changed;
- 10 units disappeared **with no recorded exclusion**.

**One traceable file cannot support a verdict on the pipeline as a whole.** That is why the executive verdict is INCONCLUSIVE and not SUBSTANTIVE or SUPERFICIAL.

---

## 0. The identifier finding (precondition for everything below)

| ID | Canonical file (list) | File the research registry holds under this ID | Where the research's file sits in the list |
|---|---|---|---|
| F0028 | Recommendation_Lifecycle_Discovery | **same** ✅ | — |
| F0031 | Phase_B_Evidence_Reconciliation | Mission_Discovery | F0038 |
| F0032 | Operational_Validation_Report | Conceptual_Foundation | **F2837** |
| F0033 | Operational_Knowledge_Principles | Ontology_Architecture_Classification | F0037 |
| F0034 | Operational_Evidence_Register | Phase_B_Evidence_Reconciliation | F0031 |
| **F0035** | **Ontology_Discovery** | Operational_Evidence_Register | F0034 |
| **F0036** | **Ontology_Cross_Product_Validation** | Vision_Mission_Clarification | **F0042** |
| F0037 | Ontology_Architecture_Classification | Epistemic_Control_Systems_Comparison | **F2838** |
| F0038 | Mission_Discovery | Operational_Knowledge_Principles | F0033 |
| F0039 | Meta_Model_Discovery | **same** ✅ | — |
| **F0040** | **README** | Semantic_Architecture_Reconciliation | **F0045** |

**Evidence (V):**
- Computed by comparing every `FILE-REGISTRY.jsonl` `path` with column 2 of the list. F0001–F0030 all match.
- Neither `Ontology_Cross_Product_Validation` nor `docs/knowledgeos/README` appears in any research artifact.
- `Ontology_Discovery` appears only in `KNOWLEDGEOS-RESEARCH-STATE.md` l.23, as **"Next unread"**.
- The state also claims "Processed 40 / 3,081", which is true as a count and false as the first 40.

**Consequence for this audit:**
- The audit prompt fixes the four files by the canonical list, so F0035, F0036 and F0040 are audited as **the files the list names**. Those files are T0 (never read).
- Content the research attributes to "F0035/F0036/F0040" belongs to other corpus files, and **cannot count toward these IDs**.

---

## 1. F0028 — `KnowledgeOS_Recommendation_Lifecycle_Discovery.md` (81 lines)

### 1.1 What the file actually contains

It is an EP-02 **living evidence instrument** that observes a running recommendation engine. Run 1 (baseline) is followed on the same day by Run 2 and an addendum.
- It **grades its own vocabulary** by how far each term has been exercised.
- It keeps a discovery log, RD-1 to RD-7.
- It **explicitly refuses** a DDD classification and any impact claim for lack of evidence.
- It binds future runs to standing constraints.

**Banner/body check:** the header (l.8) and traceability line (l.79) still describe Run 1 ("ZERO developer decisions", "RD-1..3"). The body records Run 2, a real decision, and RD-4 to RD-7. **The header understates the body. It is not a withdrawal.** The body is the later state.

### 1.2 Independent inventory and trace map

Outcome codes:
- **T0** not represented
- **T2** represented in Phase-1 evidence only
- **T3** traced into Phase-2 artifacts
- **T4** materially integrated into theory
- **TX** deliberately excluded with a reason

| # | Corpus unit (source line) | Type | Imp. | Phase 1 | Phase 2 | Outcome | Faithful? |
|---|---|---|---|---|---|---|---|
| C01 | Evidence base: 1 run · 10 issued · 0 decisions (Run 1) (l.8) | negative evidence | M | T-0028 `statement` (Run 2 figures) | theory §9A table | T3 | Partial: Run-1 state absent |
| C02 | LIVING instrument; Run 1 baseline → Run 2 same day, 00:01/06:30/15:11 (l.6, 46–56) | historical state | M | `FILE-REGISTRY` F0028 `date_events` (COMMISSION + REVISION) | — | T2 | ✅ chronology kept. ⚠️ header/body divergence not recorded (0 `INTRA-FILE-REVISIONS` rows) |
| C03 | Observed behaviours: dedup identity held · honest silence (R1–R4) · false positives surfaced by design (l.14–20) | evidence | M | — | — | **T0** | — |
| C04 | UL grading scheme OBSERVED / vocabulary-UNEXERCISED / HYPOTHESIZED / EMERGING, "terms enter only when engineering performs them" (l.24–31) | method / epistemic rule | **H** | — | — | **T0** | — |
| C05 | RD-1: issued recommendation immutable; responses in separate records. Confidence **"HIGH for the implementation; domain meaning unproven"** (l.37) | discovery + qualification | H | T-0028 `statement` | theory §9A: *"Issuance, decision and rationale are separated in practice… The analyst/instrument/authority structure… is here held in practice"* | T4 | ⚠️ **Partial: the qualification "domain meaning unproven" is lost**, and record separation is re-read as role separation (see 1.3) |
| C06 | RD-2: identity = (rule, subject); re-issuance after subject change untested (l.38) | discovery + open question | M | — | — | **T0** | — |
| C07 | RD-3: rules produce structurally honest silence (l.39) | discovery | M | — | — | **T0** | — |
| C08 | RD-4: identity may need three layers, Definition / Instance / Revision; **"think, never implement, until a real question requires it"** (l.40) | hypothesis + constraint | H | — | — | **T0** | — |
| C09 | RD-5: age computable from existing timestamps; **validated at 6.5 h, "zero new fields"** (l.41, 52) | measurement | M | T-0028 (6.5 h) | theory §9A table | T3 | Partial: the "no new fields" point is lost |
| C10 | RD-6: actor provenance gap, who-executed vs who-authorized; status OBSERVED, n=1, "observe" (l.42) | open question | **H** | `GAPS` G-0012 (WAITING, reason given) · T-0028 `findings` | `SI-0017` (L2) · theory §5, §9A · relation "corroborates I-14" | **T4** | ✅ faithful; epistemic status preserved ("corroboration, not independent confirmation") |
| C11 | RD-7: decided twice; **"do not patch the guard before the semantics are decided"**; exactly-once vs last-wins is **a domain decision**, and re-decision ≡ RD-4's revision question (l.43, 56) | anomaly + constraint | **H** | `GAPS` G-0013 (WAITING) · T-0028 `findings` | theory §9A | **T4** | Partial: the "do not patch" instruction is kept; **the exactly-once/last-wins question and the RD-4 link are lost** |
| C12 | Learning event 3/5: recommendation ✓ decision ✓ rationale ✓ · commit ✗ outcome ✗ (l.54) | qualification | H | T-0028 `learning_event_completeness` | theory §9A | **T4** | ✅ |
| C13 | DDD classification **refused: "NONE YET"**, with the discriminator (lifecycle, confidence, versioning emerging → domain; transformation → service) (l.58–64) | decision (negative) | **H** | — | — | **T0** | — |
| C14 | Impact measure: "engineering changed, never recommendations issued"; **"10 issued is an activity count and counts for nothing"** (l.66–70) | principle | **H** | — | — | **T0** | ⚠️ see 1.3, meaning drift |
| C15 | Standing constraints: no ML · no ranking · no confidence scoring · no adaptive behaviour (l.75) | constraint | H | — | — | **T0** | — |
| C16 | Decision may carry its own invariants (idempotency, supersession), which "weighs toward Recommendation-being-more-than-transformation" (l.56) | hypothesis | M | — | — | **T0** | — |
| C17 | Status as evidence instrument: "Architecture emerges from future runs or not at all" (l.81) | epistemic status | M | — | — | **T0** | — |
| C18 | decisions.jsonl = **4 lines, 1 unique recommendation**; the naive count skews to "decided=2 · accepted=2" (l.43, 56) | evidence + negative evidence | M | T-0028 relation premise: **"decisions.jsonl holds 1 decision"** | theory §9A: "1 decided" | T3 | ⚠️ **No: meaning changed** (see 1.3) |

**No unit is TX.** Nothing was recorded as deliberately excluded.

### 1.3 Information loss and meaning change

1. **Loss of qualification (C05).**
   - The source rates the record separation HIGH *for the implementation* and states the domain meaning is **unproven**.
   - The theory upgrades it to *"the analyst/instrument/authority structure… is here **held in practice**"*.
   - The source's own RD-6, in the same file, reports that **the decider was the AI at the user's direction**. That is evidence *against* a clean authority separation in this instance.
   - The theory records RD-6 as corroborating I-14. **It does not record RD-6 as a tension with its "held in practice" claim.** A counter-reading present in the source is not represented.
2. **Meaning change (C18).**
   - T-0028's relation premise says *"decisions.jsonl holds 1 decision"*.
   - The source says the file holds 4 lines, including two identical ACCEPTED records for one recommendation, and warns that naive counts skew.
   - "1 decided" is true of *unique recommendations*. "Holds 1 decision" is false of the ledger.
   - T-0028's own `findings.RD-7` contradicts its premise.
3. **Drift against a source principle (C14).**
   - The theory's §9A leads with **"10 recommendations issued"** as evidence that the mechanism runs.
   - The source says 10 issued *"counts for nothing"*, because it is an activity count.
   - The theory does qualify with "n=1" and "not yet demonstrated", so this is **drift**, not reversal. But the source's own counting rule is absent.
4. **Silent loss of the file's method (C04, C13, C15, C17).**
   - The file's most transferable content is lost: its self-grading vocabulary, its explicit refusal to classify, and its standing constraints.
   - These are epistemic-discipline statements, the same kind the research protocols themselves prize.
   - None is represented, and none is excluded with a reason.
5. **Loss of open questions (C06, C08, C11b, C16).**
   - The identity/revision question (RD-2, RD-4), the exactly-once vs last-wins decision, and the invariants-of-Decision hypothesis are all open questions the source poses.
   - None reached `GAPS`, `OPEN-QUESTIONS` or the theory. Only RD-6 and RD-7 became gaps.

**Epistemic status:** Partial. It is preserved for RD-6, RD-7 and the 3/5 learning event, and upgraded for RD-1.

**Chronology:** Partial. The registry's `date_events` capture both runs. The header/body divergence is not recorded as a revision.

**Phase-1 record quality:**
- the registry has `read_status: "READ"` (not `READ_COMPLETE`) and `track: "UNKNOWN"`;
- no dossier exists;
- no `INTRA-FILE-REVISIONS`, `DERIVATION-INSTANCES`, `CONTRADICTIONS` or `VERIFIED-EDGES` row cites F0028.
- The whole Phase-1 representation is one theory object (T-0028) plus two gaps.

### 1.4 Coverage (18 units)

| Measure | Value | Class |
|---|---|---|
| Content coverage (units in Phase 1) | 7 / 18 (C01, C02, C05, C09, C10, C11, C12; C18 in distorted form) | **MEDIUM-LOW** |
| Theory-trace coverage (units with a valid Phase-2 trace) | 6 / 18, one of them distorted | **LOW-MEDIUM** |
| Theory-integration coverage (units materially shaping theory) | 4 / 18 (C05 with lost qualification, C10, C11 partial, C12) | **LOW** |
| High-importance units integrated | 4 / 10 | — |

⚠️ These counts are over *my* inventory. A different auditor would segment the file differently. The **pattern** is more robust than the ratio:
- **What survived:** the units that connect to constructs the theory already held: I-14, the analyst/instrument/authority structure, and the R-6 loop.
- **What vanished:** the units that do not connect: identity layers, honest silence, the vocabulary grading scheme, the classification refusal and the constraints.
- Whether this is theory-driven selection cannot be settled from one file (see §5).

**Final classification, F0028: PARTIALLY SUBSTANTIVE.** It is genuinely extracted, since RD-6 and RD-7 are faithfully traced from source to gap to seed to theory. But the extraction is selective: 10 of 18 units are lost without a recorded exclusion, and there is one meaning change.

---

## 2. F0035 — `KnowledgeOS_Ontology_Discovery.md` (153 lines)

### 2.1 What the file contains (independent extraction, abbreviated)

- **Status:** "CANDIDATE — NOT ADOPTED", generated. It is an elevation of the existing Relationship Ontology from concept level to archetype level.
- **Rules it states:**
  - every relation must name the engineering decision it enables;
  - a UL decision belongs to governance (MM-1), not to the modeler.
- **Challenges settled:** CONTAINER demoted CONFIRMED → CANDIDATE by its own n=1 rule, with the settling test defined. PURPOSE flagged as hypothesis U-ONT-2 (n=0; waits on D-5).
- **Relationship matrix:** nine archetypes × own / reference / realize / constrain / generate.
- **Discovery:** **DOMAIN is the "classification archetype".** It owns nothing and scopes meaning, while DP-n owns (orthodox Evans).
- **"Exactly one archetype executes: CAPABILITY."** Executability of an artifact is a representation property.
- **Hierarchy hypothesis refuted:** 1 edge supported, 1 refuted, 4 unevidenced. It is replaced by **four partial orders**: authority, production, realization, containment. The single stack is named "level mixing", fourth instance.
- **§5 decision/scar table**, including "the MVK FAIL was a containment failure in disguise".
- **Parsimony rule:** already exists; generalizing it is routed to D-8 as a governance act.
- **Unknowns U-ONT-1…5,** including "empty cells are absence-of-evidence" and "who owns the ontology: nobody".
- **Closing:** "submitted to the Decision Authority… input, never a competitor".
- **Banner/body check:** no withdrawal. Status is consistently CANDIDATE throughout.

### 2.2 Trace

| Question | Answer |
|---|---|
| Was the file read? | **No.** It is absent from `FILE-REGISTRY.jsonl` under every ID. `KNOWLEDGEOS-RESEARCH-STATE.md` l.23 lists it as **"Next unread"**. The research's "F0035" is `KnowledgeOS_Operational_Evidence_Register.md` (canonical F0034) |
| Phase 1 | none attributable |
| Phase 2 | none attributable |
| Indirect presence | "the four partial orders" appears **quoted inside the index entry of research-F0023**, a later file citing this result. Archetype/CONTAINER vocabulary appears via research-F0039 (Meta_Model_Discovery) |
| Unique content absent from all research artifacts (searched) | DOMAIN as classification archetype · "exactly one archetype executes" · the hierarchy refutation (1/1/4) · U-ONT-1…5 · the decision-per-relation admission rule |
| Faithful? / Epistemic status / Chronology / Incorporated? | not applicable, **T0** |
| Index/reference without incorporation? | not even indexed |

**Final classification, F0035: T0 — NOT REPRESENTED (never read).**

---

## 3. F0036 — `KnowledgeOS_Ontology_Cross_Product_Validation.md` (199 lines)

### 3.1 What the file contains (independent extraction, abbreviated)

- **Method:** falsification by projection into PublicDigit, a hypothetical Hospital system and a hypothetical ERP system, with the ontology **held fixed**. Success means surviving unchanged, not being proven correct.
- **Four pre-validation corrections,** including I-11 restated as *"Knowledge does not EXECUTE… constrains and informs generation"*, Runtime Adapter placed outside the core, and specialization named as missing.
- **Result:** 8 invariant · 2 specialize · 1 disappears · 1 relationship fails. **10 of 11 invariants survive.**
- **Failures:**
  - **F-2:** "one PKS per product" fails, since the model cannot tell a product from a deployment;
  - **F-3:** no place for external authority: *"can express who decides, not who must be obeyed"*.
- **Records for the ARB:** specializations S-1…S-4; missing relationships M-1…M-4 (specializes, constrains, instantiates, attests).
- **Diagnosis:** *"the ontology describes composition, not transformation"*.
- **Finding:** "PublicDigit **masked** two of three failures", which is used as the evidence-based argument for cross-product validation.
- **Open questions** CV-1…CV-5 go to the ARB.
- **⚠️ Banner/body check (§11 of the prompt): the hazard is present.**
  - §4 F-1 carries **"⚠️ FALSIFICATION WITHDRAWN 2026-08-02"**: I-4 is underspecified, not false, and the problem was a lifecycle gap misdiagnosed as a falsification.
  - But **§8 Verdict (l.159 "Falsified: I-4"), the Closing (l.188), the Traceability line (l.197 "I-4 FALSIFIED") and CV-1 (l.173 "states the falsified form") were not updated.**
  - The file therefore contradicts itself. A reader of the body's verdict would carry forward a falsification that the file itself withdrew.

### 3.2 Trace

| Question | Answer |
|---|---|
| Was the file read? | **No.** It is absent from every research artifact under every ID. The research's "F0036" is `KnowledgeOS_Vision_Mission_Clarification.md` (canonical **F0042**), and the theory cites "F0036" under that meaning |
| Phase 1 / Phase 2 | none attributable |
| Indirect presence | the I-4 withdrawal is recorded from **F0014** (`INTRA-FILE-REVISIONS` IFR-0015). The corrected I-11 wording from **F0027** (T-0026). Product/deployment from **F0014/F0027** (T-0022, T-0024/26). Runtime Adapter via research-F0038/F0040 (T-0042) |
| Unique content absent from all research artifacts (searched) | F-3 external authority ("who must be obeyed") · S-1…S-4 · M-1…M-4 transformational relations · the composition-vs-transformation diagnosis · "PublicDigit masked" · CV-1…CV-5 · **this file's internal withdrawal/verdict contradiction** |
| Index/reference without incorporation? | not even indexed |

**Final classification, F0036: T0 — NOT REPRESENTED (never read).** Some of its conclusions exist in the theory with provenance to *other* files. This is the file where the banner hazard the audit was told to test **actually occurs, and it is unrecorded.**

---

## 4. F0040 — `docs/knowledgeos/README.md` (19 lines)

### 4.1 What the file contains

It is the documentation-root README for the KnowledgeOS domain. It holds:
- the scope (product-specific, domain knowledgeos) and owner (the KnowledgeOS domain);
- a statement that the internal layout needs no ADR;
- pointers to policy (ADR 2026-08-01), configuration and resolver;
- the principle **"Placement is derived, never chosen."**

Theory content is minimal. This is a plausible first legitimate `candidate_theory_bearing: false`, which would be the first test of the index's `false` branch. Banner/body: none.

### 4.2 Trace

| Question | Answer |
|---|---|
| Was the file read? | **No.** It is absent from every research artifact. The research's "F0040" is `KnowledgeOS_Semantic_Architecture_Reconciliation.md` (canonical F0045) |
| Phase 1 / Phase 2 | none |
| Loss | minor in theory terms. But losing it removes the one file in the window likely to exercise P1-Q1's `false` branch. The state notes that "all `true`; the discriminator has never discriminated", yet the file most likely to discriminate was skipped |

**Final classification, F0040: T0 — NOT REPRESENTED (never read).**

---

## 5. Cross-file conclusion

1. **Strongest evidence of genuine incorporation:** F0028's RD-6 and RD-7. They are traceable from source line to `GAPS` (with reasons and a WAITING disposition), to seed item `SI-0017`, to theory §9A. Epistemic status is carried faithfully ("corroboration, not independent confirmation"), and the corpus's "do not patch" instruction is inherited explicitly.
2. **Strongest evidence of superficial or defective processing:** the identifier failure (§0). It is not superficiality in the audited sense, but it is more basic: the provenance of every F0031–F0040 citation is wrong relative to the canonical list, and three audited files were never read.
3. **Important information that disappeared:**
   - F0028's method (vocabulary grading, the classification refusal, the constraints, "activity counts count for nothing") and its open questions (identity layers, exactly-once vs last-wins);
   - the entire unique content of F0035 and F0036, including F0036's external-authority failure and its self-contradicting withdrawal.
4. **Important information that survived:** the corrections that F0036 shares with earlier files (I-4 withdrawal, I-11 wording, product/deployment), attributed to those earlier files.
5. **Driven by corpus evidence, or by the researcher's interpretation?**
   - **Evidence from this sample:** in F0028, what survived is what connects to constructs the theory already held, and what vanished does not connect to them.
   - **That is consistent with interpretation-driven selection, but n=1 cannot distinguish it** from "the file's other units are genuinely less theory-bearing".
   - The distinguishing test is a larger sample with independent inventories. I **do not** conclude that the theory is interpretation-driven.

---

## 6. Gate adequacy

Gate IDs below are those of the research session's `governance/gates.yaml` and `governance-state.yaml` (activated: KOS-G-001 artifacts-parse · 002 no-duplicate-ids · 003 no-dangling-references · 010 theory-discovery-index-coverage · 022 theory-document-completeness).

⚠️ These IDs collide with the IDs in `docs/knowledgeos/governance/2026-09-23-KOS-RESEARCH-GATE-CATALOG-proposal.md`, where for example KOS-G-001 means "corpus immutability". Only the research session's meaning is used here.

Runner result on the evaluated snapshot: all five activated gates `PASS`, **`STATUS: CLEAR`**, exit 0.

| Problem found | G-001 | G-002 | G-003 | G-010 | G-022 | Why |
|---|---|---|---|---|---|---|
| **File ID assigned to the wrong physical file (9/10 in F0031–F0040)** | NO | NO | NO | NO | NO | no gate reads the canonical list. Worse, G-003 *passes because* the wrong IDs are used consistently, so "F0036" resolves |
| Canonical file inside the processed window never read (F0032, F0035, F0036, F0040) | NO | NO | NO | NO | NO | G-010 checks registry ⇔ index, not canonical window ⇔ registry |
| File registered, substantive content not extracted (F0028: 10/18 units) | NO | NO | NO | NO | NO | G-010 checks that an index entry exists, not its content |
| Qualification lost / status upgraded (F0028 C05) | NO | NO | NO | NO | NO | semantic |
| Meaning changed (F0028 C18) | NO | NO | NO | NO | NO | semantic, and the contradiction sits inside one record (T-0028 premise vs findings) |
| Missing source-passage trace | NO | NO | NO | NO | PARTIALLY | G-022 traces seed item → theory appendix only, never source passage → seed |
| Unrecorded intra-file revision (F0028 header/body; F0036 withdrawal vs verdict) | NO | NO | NO | NO | NO | — |
| Duplicate ID | NO | PARTIALLY | NO | NO | NO | G-002 covers theory objects and relation rows, not `FILE-REGISTRY` `file_id` |
| Dangling relation | NO | NO | YES (T-/G-/F- only) | NO | NO | — |
| Missing index entry | NO | NO | NO | YES | NO | — |

**Why:** the five activated gates check the **internal consistency** of the research artifacts with each other. None checks **correspondence with the corpus**: not the canonical list, and not the source text. Every problem this audit found lies in that correspondence, so a fully green run is compatible with all of them. This is stated as observed fact; no new gate is proposed.

---

## 7. Confidence

| | |
|---|---|
| **Establishes** | (a) the ID-assignment deviation for F0031–F0040, **deterministically**, by full comparison of 40 registry rows with the canonical list; (b) that canonical F0032, F0035, F0036 and F0040 are unread; (c) for F0028, a unit-by-unit trace with exact source lines and artifact records; (d) that the activated gates cannot detect (a) through (c) |
| **Does not establish** | whether the theory is right; whether the files the research *did* read under F0031–F0040 were processed substantively (not audited: out of the commissioned scope, and those IDs belong to other files); whether F0028's selective pattern generalizes |
| **Would require a larger sample** | the corpus-vs-interpretation question (§5.5) needs independent inventories of several correctly identified files, ideally including the research's F0031–F0040 files audited under their canonical IDs |
| **Reproducibility** | every fact above can be re-derived from commit `ca8b96d59` and `list_of_files_to_read.log` with read-only commands (`git archive`, path comparison, `grep`, the research runner run on a copy) |

---

*Traceability:*
- Corpus files read in full (working tree, unchanged since 2026-08-04): `KnowledgeOS_Recommendation_Lifecycle_Discovery.md`, `KnowledgeOS_Ontology_Discovery.md`, `KnowledgeOS_Ontology_Cross_Product_Validation.md`, `docs/knowledgeos/README.md`.
- Research artifacts at `ca8b96d59`: `FILE-REGISTRY.jsonl`, `THEORY-DISCOVERY-INDEX.jsonl`, `THEORY-OBJECTS.jsonl` (T-0028 and others), `GAPS.jsonl` (G-0012, G-0013), `INTRA-FILE-REVISIONS.jsonl` (IFR-0015), `phase2_extraction/{THEORY-SEED.md (SI-0017), CANDIDATE-KNOWLEDGEOS-THEORY.md (§5, §9A, appendix), CORPUS-THEORY-RECOVERY.md (§8.8), THEORY-OBJECT-RELATIONS.jsonl}`, `KNOWLEDGEOS-RESEARCH-STATE.md`, `governance/{gates.yaml, governance-state.yaml, gate-runner.py}`.
- Protocol reference: P1P §4, l.1123–1132 (file-ID immutability).
- Nothing was modified.
