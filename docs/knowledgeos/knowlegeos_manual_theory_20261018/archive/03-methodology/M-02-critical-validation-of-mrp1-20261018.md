# M-02 — Critical validation of MRP-1 before approval

| Field | Value |
|---|---|
| Date | 2026-10-18 |
| Author | Session B (Professor) |
| Commission | Principal, "Critical Validation of MRP-1 Before Approval" (2026-10-18), with the Principal's acceptance of the reviewer's five risks |
| Validates | MRP-1 as defined in `M-01-methodology-assessment-20261018.md` §5. **M-01 is unchanged**; this document proposes amendments (**MRP-1.1**) and does not apply them |
| State of `04-corpus/` | MRP-1 templates and `tools/quote_anchor_check.py` exist and are **empty of corpus data**. The tool passed 7 synthetic cases in the scratchpad (exact, whitespace-tolerant, mismatch, range error, hash mismatch, missing source, empty template). No corpus file was used |
| Constraints kept | No corpus content read · no sealed or hold-out material · the out-of-frame read (M-01 U-3) not investigated · no reserved decision taken · nothing written outside this workspace |
| Labels | **[EV]** evidence · **[I]** interpretation · **[H]** hypothesis · **[?]** open |

---

## 0. Verdict

**MRP-1 needs specific, limited corrections.** It controls *traceability* (where a statement comes from) well. It does not yet control *fidelity* (whether the statement was understood), *discovery* (whether concepts expressed in unexpected ways are found), or *completion* (whether the theory, and not just each file, is covered).

Six essential amendments close these gaps (§6). None replaces an existing artifact; two of them simplify MRP-1.

---

## 1. The goal, stated precisely

The commission asks for two things, and they must not be merged:

| Layer | Question | Product | Evidence standard |
|---|---|---|---|
| **R — Reconstruction** | What did the authors hold, when, and why? | the reconstructed original theory, with its history | primary-source anchors only |
| **T — Derived theory** | What of it is coherent and holds up, and what is the most robust theory these ideas support? | a robust theory derived from the corpus as brainstorming material | every element traces to R, plus an explicit validity check (logic, mathematics, counterexample) |

**[I]** In the earlier lanes the two layers were mixed. Manuscript v1 is T-layer mathematics that was easy to read as R-layer content.

MRP-1 as written serves R only. MRP-1.1 adds the **validity axis** (amendment A-4) so that T can be built on R without being mistaken for it. This is the model-integrity rule applied to the method: *what the authors held* and *whether it is true* are orthogonal dimensions, not two values of one status.

---

## 2. Failure-mode analysis

Scale used below: **PREVENTS** · **DETECTS** · **PARTIAL** · **UNRESOLVED**.

### A. Missing a concept — MRP-1: **PARTIAL**

**Why MRP-1 can miss it:**
- The extraction template has headed sections for definitions, assumptions and so on.
- A concept introduced *through* an example, a diagram, a formula, a criticism of an earlier idea, or a term outside my vocabulary has no section that forces me to notice it.
- A fully filled template therefore proves only that I wrote something under each heading.

**Amendment A-1: line accounting.**
- Every line range of a file is classified: EXTRACTED (with item ids) or NOT-EXTRACTED with a reason (FRONTMATTER · BOILERPLATE · REPETITION-OF:`<anchor>` · NAVIGATION · OFF-TOPIC with a one-line description).
- Unaccounted ranges make the file incomplete.
- This does not make omissions impossible, but it turns a silent omission into a **visible decision** that a reviewer can challenge line by line.

**Amendment A-2: extraction sections for indirect meaning.**
- definitions by example or contrast;
- rejected or criticised ideas (and what is rejected);
- diagrams (transcribed; image-only diagrams flagged UNCERTAINTY:TEXT);
- implicit assumptions (labelled [I] with the lines that require them);
- relations stated only mathematically;
- an "unclassified but salient" catch-all.

**Detection of residual misses:**
- independent double extraction with capture–recapture (D);
- an ML locator for unmatched terms (§5, optional).

**Status after amendment:** DETECTS. The residual risk is a concept that both I and the reviewer fail to notice; capture–recapture gives only a lower bound on it.

### B. Confirmation bias — MRP-1: **PARTIAL**

**Sources of bias:**
- my earlier reading of manuscript v1, Session A's families and four corpus files;
- the term list (K, Zero, Gap) of the Zero/Gap pilot;
- reading founding documents with the later theory in mind.

The LEADS quarantine stops *citation* of secondary material. It does not stop it shaping *attention*.

**Amendment A-3: open coding before vocabulary, and scored leads.**
1. **Pre-registration.** Before corpus reading starts, every expectation I hold is written into LEADS (`recorded_before_reading = yes`). After each batch, each lead is scored CONFIRMED · REFUTED · CHANGED · NOT-YET-TESTED.
   - If almost every lead is confirmed, that is itself a warning sign and is reported as such.
2. **Open coding first.** Each file is extracted in order, from its own words, *before* any term search or lead is consulted for that file.
3. **Provenance of noticing.** CONCEPTS gains `discovered_how` = OPEN-CODING · TERM-SEARCH · LEAD · REVIEWER. A theory whose concepts were mostly found by TERM-SEARCH or LEAD was steered by my expectations.
4. **Chronological reading within a stage** (§3), so later documents cannot frame earlier ones.
5. **Mandatory disconfirmation search** for every claim promoted beyond STATED (C).

**Status after amendment:** DETECTS. Bias in *attention* cannot be prevented by one reader. It is measured through `discovered_how`, lead scores and the reviewer's independent capture.

### C. False stability — MRP-1: **UNRESOLVED as written**

MRP-1's STABLE rule ("at least two dated sources, no later retraction") is a statement about **persistence**. Two documents can repeat one earlier text or one earlier mistake, and agreement says nothing about truth or completeness.

**Amendment A-4: two orthogonal axes and lineage-aware evidence.**

*Axis 1, source standing (R-layer).* What did the authors hold?

| Status | Required evidence |
|---|---|
| **STATED** | ≥1 primary verbatim anchor |
| **STABLE** | All of the following:<br>(i) ≥2 primary anchors from **different lineages**. Sources linked by RESTATES, or one copying or citing the other, count as one lineage; lineage is recorded in CHRONOLOGY (`derived_from`).<br>(ii) No later RETRACTS or CHANGES.<br>(iii) No GENUINE contradiction open.<br>(iv) A **documented contrary search**, recording the terms (including ledger synonyms), the frame searched, hits, which hits were read, and the strata not covered |
| **CONTESTED** | An open GENUINE contradiction, or an authors' revision whose final position is unclear |
| **RECONSTRUCTED** | Not stated as such anywhere; my synthesis, with every premise anchored and the inference written out |
| **UNSUPPORTED** | Only secondary support. Stays in LEADS |

*Axis 2, validity (T-layer).* Does it hold?

| Status | Meaning |
|---|---|
| NOT-ASSESSED | not yet examined |
| CONSISTENT | no contradiction with the other accepted elements (logic check) |
| VERIFIED | proved, or checked by computation, under stated assumptions |
| REFUTED | counterexample or contradiction found |
| UNDETERMINED | examined, no result |

A claim is reported as a **pair**, e.g. *STABLE / REFUTED*: the authors held it consistently, and it is false. That pair is a legitimate and important finding.

**Limits of the contrary search, stated explicitly:**
- Term search misses paraphrases, negations phrased differently, and concepts renamed later.
- Its recall is unknown and is **estimated in the validation exercise** by planting contrary statements in unexpected wording (§5).
- STABLE is therefore always "stable *within the searched and read evidence*", with that evidence named.

**Status after amendment:** DETECTS for repetition (lineage); PARTIAL for completeness (bounded by search recall, which is measured).

### D. Extraction errors (semantic fidelity) — MRP-1: **UNRESOLVED as written**

The quote-anchor check proves only that the text exists where I said it does. Misunderstanding passes it.

**Amendment A-5: an independent fidelity loop.**
1. **Paraphrase-and-test, by me, for every definition.** I write the definition in my own words plus one case it includes and one it excludes. Each case is checked against the source's own examples where they exist. A mismatch is an UNCERTAINTY:TERM.
2. **Independent double extraction on a sample of files.** A reviewer extracts the same files blind to my record. Disagreements go to one new small record, `ledgers/REVIEW.tsv`: item · my reading · reviewer's reading · classification (OMISSION · MISREADING · LABEL-ERROR · SCOPE · BOTH-DEFENSIBLE) · adjudication · decided-by.
   - Adjudication of a dispute about meaning is not mine alone. BOTH-DEFENSIBLE stays visible as an UNCERTAINTY.
3. **Capture–recapture.** From the two independent extractions of the same files:
   - n₁ items found by me, n₂ by the reviewer, m by both.
   - Chapman's estimator gives the total N̂ = (n₁+1)(n₂+1)/(m+1) − 1, and missed-by-both ≈ N̂ − (n₁ + n₂ − m).
   - Easy concepts are caught by both, which violates the equal-catchability assumption. The estimate is therefore **a lower bound on misses**, and is reported as one.
4. **Optional: formal encoding of core definitions** in typed first-order logic, checked with an SMT solver (e.g. Z3). It exposes ambiguity (several models where the text intends one) and inconsistency (no model). It tests T-layer validity and sharpens R-layer reading. It is never a substitute for the source.

**Governance constraint, and the decision it needs.**
- G-LOG-01xx §5 forbids passing corpus text to sub-agents, and *"the reviewer must receive only the material separately approved for that review."*
- Independent review of **corpus** files therefore requires a **Principal decision**: who reviews (a human, or a separately authorized session), on which files, under which access record. **This is a reserved decision; I do not make it.**
- **Background** files are not restricted, so review on them is possible now (§5, V-2).

**Status after amendment:** DETECTS on the reviewed sample; for unreviewed files, risk is *estimated* from the sample, not eliminated.

### E. False completion — MRP-1: **UNRESOLVED as written**

Every file and batch can pass while the theory is incomplete: unread strata, an unfinished concept history, an unclosed dependency.

**Amendment A-6: theory-level completion and one status record, `04-corpus/THEORY-STATUS.md`.**

The reconstruction (R-layer) may be called **COMPLETE-WITHIN-SCOPE** only when **all** of the following hold. Otherwise it is **PROVISIONAL**, and the record says which conditions fail.

| # | Condition | How it is shown |
|---|---|---|
| TC-1 | Every file in the authorized frame has a queue status (DONE · DEFERRED-with-reason · DUPLICATE-OF · NOT-REACHED) | QUEUE reconciliation |
| TC-2 | T0 (the founding canon) is entirely DONE | QUEUE |
| TC-3 | Coverage is reported per stratum (folder family × month) as n of N, with unread strata listed by name | coverage table |
| TC-4 | **Saturation:** the last *k* batches in each read stratum produced no new *foundational* concept. A concept is foundational if another concept or claim depends on it in DEPENDENCIES. *k* is set from the observed discovery curve and stated with it | discovery curve |
| TC-5 | **Probe of the unread:** a stratified random sample of NOT-REACHED files, read in full, finds no new foundational concept. With *n* probe files and zero finds, the 95% upper bound on the share of unread files carrying a new foundational concept is 1 − 0.05^(1/n): about 22% at n = 12 and 10% at n = 30. **The Principal chooses the tolerated share; n follows from it.** If the probe finds something, completion fails and the stratum is read | probe record |
| TC-6 | Every foundational concept has a complete history (BIRTH plus every later change event) and dependency closure (every dependency is itself in the ledger) | CHRONOLOGY / DEPENDENCIES queries |
| TC-7 | Every mathematical statement has a status. None is left TRANSCRIBED only | MATH |
| TC-8 | Every contradiction is classified; GENUINE and UNDETERMINED ones are listed in the theory text, not resolved silently | CONTRADICTIONS |
| TC-9 | Every sentence of the reconstructed theory cites claim ids, and every cited claim is ≥ STATED with anchors that pass the anchor check | CLAIMS + tool |
| TC-10 | Independent review (A-5) has been done on a sample that includes T0, and its estimated misses are reported | REVIEW |

The **T-layer** additionally needs a validity status for each element (A-4). The programme's own ROBUST verdict also requires a prediction confirmed on unseen material (G-LOG-0145). I am barred from writing hold-out predictions (G-LOG-0141 item 5, G-LOG-0144). The reconstruction can only *propose* candidate predictions for another role to register.

**Status after amendment:** DETECTS. Completion becomes a stated, checkable condition with its exclusions visible.

---

## 3. Reading strategy

| Stage in MRP-1 | Strength | Risk |
|---|---|---|
| Founding documents first | earliest meaning, small set | which documents are "founding" is my selection: bias B |
| Term-guided discovery | fast access to known concepts | finds only my vocabulary: misses A and B |
| Chronological sweep by folder family | covers families | folder order is not time order; families can frame each other |
| Hubs and syntheses last | avoids early framing | hubs are where the authors themselves consolidated; reading them last is correct for interpretation but delays discovery |

**Recommended sequence (MRP-1.1):**

1. **Stage 0 — Queue from metadata only.** All frame files with registry date, git first-commit date, size and near-duplicate group (hash/shingle). No content read beyond what the authorized filter permits.
2. **Stage 1 — Founding canon in date order, open coding only.** S0001–S0046 and S0011, earliest first, no term list. *Founding* is defined by date (the earliest dated stratum), not by my judgement of importance.
3. **Stage 2 — Chronological sweep across all families together, month by month.** This is the historical sequence. Author syntheses and hubs are read **in their own chronological place**, as primary evidence of what the authors thought *then*. They are never used to interpret earlier files: an earlier file is extracted before any later file that discusses it.
4. **Term search is used for two things only:**
   - contrary-evidence searches (C);
   - locating the *later* occurrences of a concept already found by open coding, to complete its history (TC-6).

   It never decides what is read first.
5. **Probe sampling** (TC-5) for strata not reached.
6. **The dependency map is built from extractions, never from reading order.** Chronology answers *when*; DEPENDENCIES answers *what rests on what*. The theory is presented in dependency order, and its history is reported in date order.

**Honest scale statement [I].**
- Reading all 2,734 frame files fully, one at a time, is a large undertaking. MRP-1.1 does not promise it.
- It promises a reconstruction whose **coverage is explicit** (TC-3) and whose unread remainder is **statistically probed** (TC-5).
- Near-duplicates are read as diffs, which reduces the load without summarising.

---

## 4. Artifact validation

| Artifact | Purpose | Prevents | Cannot prevent | Complete when | Relations |
|---|---|---|---|---|---|
| READ-LOG + READ-AND-EXTRACTED (existing) | provenance of every read | unlogged reading; silent file change (hash) | poor reading | row per read; hash matches | QUEUE status DONE ⇔ ledger READ+EXTRACTED (reconciled) |
| QUEUE | what will be read, why, in what order | invisible selection; unprovable coverage | a wrong tier assignment | every frame file has a status (TC-1) | feeds batches; coverage table |
| E-record + **line accounting** (A-1, A-2) | the per-file extraction | silent omission (becomes visible); unanchored statements | misreading (needs A-5) | §5.2 of M-01 plus all lines accounted | source of every ledger row |
| quote_anchor_check | mechanical quote verification | misquotation; wrong location; stale hash | misinterpretation | all PASS / PASS-WS | runs on E-records |
| CONCEPTS (+ `discovered_how`) | every definition occurrence, verbatim | scattered or overwritten definitions | missing a concept never noticed | each definition in an E-record has a row | ids used by every other ledger |
| CHRONOLOGY (+ `derived_from`) | dated change events; lineage | history from undated order; double-counting copies | wrong dates in the source | each concept's events from BIRTH | STABLE lineage test; TC-6 |
| DEPENDENCIES | theoretical structure | confusing history with logic | an unstated dependency | closure for foundational concepts | theory order; TC-4 "foundational" |
| CONTRADICTIONS | conflicts between sources, classified | smoothing conflicts away | misclassification (needs review) | all classified | CLAIMS CONTESTED |
| UNCERTAINTY | dating, scope, term and text doubts | hidden doubt | — | each linked to what would resolve it | **Rule:** a contradiction of undetermined class lives only in CONTRADICTIONS |
| MATH | source mathematics and its checking | equation ≠ verified result; my mathematics merged into the source's | the authors' intent behind a formula | every statement has a status | validity axis of CLAIMS |
| CLAIMS (two axes, A-4) | the reconstructed and derived statements | citation of secondary sources; persistence mistaken for truth | completeness beyond the searched evidence | TC-9 | THEORY-STATUS |
| LEADS (pre-registered, scored) | quarantine and bias measurement | secondary material as evidence | influence on attention (measured only) | every lead scored | B |
| **REVIEW** (new, A-5) | independent disagreements | unchallenged interpretation | errors shared by both readers | reviewed sample adjudicated | capture–recapture; TC-10 |
| BATCH report | progress and checks | batch-level slippage | theory-level gaps | M-01 §5.2 | THEORY-STATUS |
| **THEORY-STATUS** (new, A-6) | theory-level completion | false completion | — | TC-1…TC-10 evaluated | reads all ledgers |

**Duplication removed:**
- `CONCEPTS.supersedes` is dropped. Supersession is a CHRONOLOGY event; holding it in two places would let them diverge.
- The undetermined-contradiction overlap is resolved by the rule above.

**Nothing else is added.** REVIEW and THEORY-STATUS are the only new records; each closes a failure mode (D, E) that no existing artifact covers.

---

## 5. Validation exercise (design only — not run)

### What is tested and how

**V-1 — Seeded synthetic mini-corpus.** Needs no corpus access; needs Principal approval and an independent author.
- **Material:** 10–12 short dated documents written by **someone other than me** (the Principal, or a session that has not read the corpus), with a **sealed answer key**.
- **Planted items (about 20):**
  - explicit definitions;
  - a definition given only by example;
  - a concept in vocabulary unlike any term I know;
  - a NARROWS revision and a RETRACTS;
  - a GENUINE contradiction, plus a SCOPE pseudo-contradiction and a TERMINOLOGY pseudo-contradiction;
  - an implicit assumption;
  - a formula with a deliberate error;
  - a relation shown only in a diagram;
  - a contrary statement worded to evade term search;
  - **a bias trap:** a "later summary" document that misstates an earlier concept.
- **Procedure:** I run MRP-1.1 blind; a scorer compares my output with the key.
- **Measures:**
  - extraction accuracy;
  - traceability (anchor check);
  - revision and contradiction detection and classification;
  - capture of the unexpected concept;
  - evidence vs interpretation labelling;
  - whether the bias trap propagated;
  - recall of the contrary search.

**V-2 — Real-text inter-rater test on background.** Possible now, because background files are not restricted.
- **Material:** the 7 RQ-002 files (825 lines). They are theory-like: definitions, invariants and revisions.
- **Procedure:** an independent reviewer sub-agent extracts blind with the E-template; I extract under MRP-1.1.
- **Outputs:** REVIEW disagreements with classification; a capture–recapture lower bound on misses.
- **Limit:** I have read these files before, so V-2 tests **fidelity**, not **discovery**.

**V-3 — Pilot on 5–10 T0 corpus files.** Only after the gate passes and the Principal has decided the reviewer question (§2 D). Independent review of the pilot by the approved reviewer.

### Decision rules, with rationale instead of arbitrary thresholds

With about 20 planted items, a perfect score has a 95% Wilson lower bound of about 84% recall; with 12, about 76%. **A rate from so few items cannot certify the method**, so the rules turn on *whether failures are caught by the controls*.

**Success** (all must hold):
- every planted foundational item is captured, **or** each miss was flagged by a control (line accounting, REVIEW, unmatched-term locator) and traced to a protocol gap that is then fixed;
- all quotes PASS the anchor check;
- the bias trap did not change the extraction of the earlier document;
- every pseudo-contradiction is classified as SCOPE or TERMINOLOGY, not GENUINE;
- no label violation (interpretation recorded as evidence) is found by the scorer or reviewer.

**Failure** (any one):
- a planted foundational item is missed **and no control flagged it**. That is the exact failure mode A-1 to A-5 exist to catch;
- the bias trap propagates;
- contradictions are misclassified systematically, in the same direction more than once;
- an interpretation is recorded as source evidence.

**Inconclusive:**
- the key's author is not independent of me;
- the reviewer saw my extraction;
- planted items are too few to exercise every failure mode at least once;
- the synthetic texts are judged by the Principal to be unlike the corpus in style. Then V-3 decides.

---

## 6. Recommendation: MRP-1.1, specific and limited corrections

**Essential:**

| # | Amendment | Failure mode | Cost |
|---|---|---|---|
| A-1 | Line accounting in every E-record | A, D, E | medium |
| A-2 | Extraction sections for indirect meaning (examples, contrasts, rejections, diagrams, implicit assumptions, maths-only relations, catch-all) | A | low |
| A-3 | Open coding before any term search; pre-registered and scored leads; `discovered_how` | B | low |
| A-4 | Two orthogonal claim axes (source standing × validity); lineage-aware STABLE; documented contrary search with its limits | C, and the R/T goal | low |
| A-5 | Independent double extraction on a sample; REVIEW record; capture–recapture lower bound; paraphrase-and-test | D | high; needs a Principal decision for corpus files |
| A-6 | Theory-level completion conditions TC-1…TC-10; THEORY-STATUS; probe sampling of the unread | E | medium |
| — | Reading sequence of §3; validation V-1/V-2 before scale, V-3 after the gate | A, B, process | — |
| — | Simplification: drop `CONCEPTS.supersedes`; the UNCERTAINTY/CONTRADICTIONS rule | duplication | none |

**Optional, ML and computer logic.** These help locate and check, and never produce evidence. On corpus files they run only on authorized files, locally, and no corpus text leaves the workspace.

| # | Technique | Use | Guard |
|---|---|---|---|
| O-1 | Keyphrase extraction (TF-IDF or embedding-based) over each read file, compared with CONCEPTS | lists **unmatched candidate terms** for my review: an omission detector for A | output is a review list, never a concept |
| O-2 | MinHash / shingling near-duplicate detection | DUPLICATE-OF / VARIANT-OF groups; reading as diffs | a variant is still read for its diff |
| O-3 | Embedding or topic clustering of the frame | strata for coverage and for probe sampling (TC-3, TC-5) | cluster labels are never concepts |
| O-4 | Discovery-curve model (new foundational concepts per batch; species-richness estimators such as Chao1 on concept frequencies) | evidence for saturation (TC-4) and an estimate of undiscovered concepts | reported as an estimate with assumptions |
| O-5 | Typed first-order encoding of core definitions, checked with an SMT solver | ambiguity and inconsistency checks; the validity axis | T-layer only |

---

## 7. Updated architecture and to-dos

**Research pipeline as bounded contexts** (each owns its ledger; dependency inward to provenance):

```
Provenance (READ-LOG, ledger, hashes)
   ↑
Reading (QUEUE, batches) → Extraction (E-records, line accounting, anchor check)
                               ↓
          Concepts ← Chronology (when) · Dependencies (what rests on what)
                               ↓
          Assessment: Claims (standing × validity) · Math · Contradictions · Uncertainty
                               ↓
          Review (independent) · Coverage & Completion (THEORY-STATUS)
                               ↓
          R-layer theory  →  T-layer robust theory (validity-checked)
          LEADS: quarantined; read by no context except bias scoring
```

**To-dos.** Owner P = Principal decision; B = Session B.

| # | Item | Owner | Blocked by |
|---|---|---|---|
| 1 | Decide whether MRP-1.1 is adopted, in whole or in part | P | — |
| 2 | Record the safety gate as passed (G-LOG-01xx §3) | P | D1 review, signed fixture test |
| 3 | Written scope extension to this workspace | P | — |
| 4 | Assessment of earlier filter runs, incl. M-01 U-3 and U-5 | P / governance | 2 |
| 5 | Decide who may independently review corpus extractions, and under which access record | P | — |
| 6 | Appoint an independent author for the V-1 seeded corpus and its sealed key | P | 1 |
| 7 | Update `04-corpus/` templates to MRP-1.1 (no corpus data) | B | 1 |
| 8 | Pre-register all my leads in LEADS (before any corpus read) | B | 7 |
| 9 | Run V-2 on RQ-002 with an independent reviewer sub-agent | B | 1 |
| 10 | Run V-1; report success / failure / inconclusive | B + scorer | 6, 7 |
| 11 | Build QUEUE from metadata (Stage 0) | B | 2, 3, 4 |
| 12 | Run V-3 pilot on T0 | B + reviewer | 5, 10, 11 |
| 13 | Scale: Stages 1–2, probes, THEORY-STATUS | B | 12 |

---

## 8. Plain-language summary for the Principal

**What the method can assure, once amended:**
- Every statement in the reconstructed theory points to an exact place in an original document, and the quotation is checked by machine.
- Every page that was read is accounted for line by line, so a skipped passage is a recorded decision you can challenge, not a silent gap.
- What the authors believed is kept separate from whether it is true. A finding like "they held this consistently, and it is wrong" can be stated.
- Conflicts, revisions and doubts are kept in the open, not smoothed into a neat story.
- Completion is a list of checkable conditions. Until they hold, the theory is labelled provisional, with the unread parts named.

**What it cannot assure:**
- That I understood every passage correctly. Only independent review can test that, and only on the reviewed sample.
- That nothing was missed. It can estimate a lower bound on misses and probe unread material statistically, but cannot prove zero.
- That my earlier reading has no influence on what I notice. It can measure that influence, not remove it.

**What is still needed before reconstruction begins:**
1. Your decision on these amendments.
2. The governance steps already identified: the safety gate recorded as passed, written authorization for this workspace, and the check of the earlier filter runs.
3. A decision on who may independently review corpus extractions.
4. A successful small test: first on a planted mini-corpus written by someone else (and on the RQ-002 background files, which can be done now), then on a handful of founding corpus documents once access is authorized.

Only if those tests show that the checks catch the errors they are designed to catch should the method be applied at scale.
