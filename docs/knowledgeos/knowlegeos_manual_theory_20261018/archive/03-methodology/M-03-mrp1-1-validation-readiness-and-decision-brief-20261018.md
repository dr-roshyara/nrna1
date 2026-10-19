# M-03 — MRP-1.1 validation readiness and decision brief

| Field | Value |
|---|---|
| Date | 2026-10-18 |
| Author | Session B (Professor) |
| Commission | Principal, "Prepare the MRP-1.1 Validation Decision and Execution Plan" (2026-10-18) |
| Basis | M-01 and M-02 (both **unchanged**) · `P3B-GOVERNANCE-LOG.md` (sha256 `e9ef2d02…7b76b`, 2,352 lines, re-checked 2026-10-18 13:17 UTC: **unchanged since the M-01 check**, last entry G-LOG-0145) |
| Status of MRP-1.1 | **PROPOSED.** Not adopted, not validated, not tested |
| Actions in this brief | analysis only. No corpus access, no sub-agent, no template or tool change, no governance record touched |
| Labels | **[EV]** evidence · **[I]** interpretation · **[H]** hypothesis · **[?]** open · **R-layer** what the corpus asserts and how that changed · **T-layer** a separately evaluated derived theory |

---

## 1. Executive recommendation

1. **Do not adopt MRP-1.1 as the reconstruction method yet.** Adopt it, if at all, **as a candidate protocol to be validated**.
2. **Approve V-1 and V-2 now.** Neither requires corpus access, and they test different things:
   - V-1 tests *discovery* against a known answer;
   - V-2 tests *interpretive fidelity* on real text.
3. **Before V-1/V-2, accept six corrections to M-02's statistical and logical claims (§4).** Two are genuine flaws:
   - TC-4 and TC-5 depend on "foundational", which is defined through dependencies that a newly found concept cannot yet have, so the definition is circular at discovery time;
   - the label COMPLETE-WITHIN-SCOPE overstates what probes can show.
4. **No corpus pilot (V-3) until** all four governance prerequisites are recorded (§9). None is recorded today.

| Recommendation | Evidence | Rationale | Cost / trade-off | Residual uncertainty |
|---|---|---|---|---|
| Treat MRP-1.1 as a candidate | M-02 controls are untested; 7 synthetic tool tests only cover the anchor tool [EV] | Sophistication is not proof | Delays scale-up by two small tests | Validation on two small tests cannot certify general reliability |
| Run V-1 and V-2 first | Both need no corpus access (§5) | Cheapest evidence about the method itself | V-1 needs an independent author and scorer; V-2 needs a reviewer and an adjudicator | Synthetic and background text may differ from the corpus |
| Apply the §4 corrections | Logical analysis in §4 | Otherwise completion criteria are circular or overstated | Small wording and definition changes | Some limits are inherent and stay (§3) |

---

## 2. What M-02 establishes, and what remains unproven

| M-02 contribution | Established? |
|---|---|
| A clear **failure-mode analysis** A–E and the controls aimed at each | Yes, as **design** [EV: M-02 §2] |
| **R/T separation** with two orthogonal claim axes | Yes, as **design**. Not yet enforced in any file; the templates still carry MRP-1 (single status) |
| **Quote-anchor tool** | Implemented; 7 synthetic cases behave as specified [EV]. One defect found (§4.9) |
| Line accounting, open coding, lineage, REVIEW, probes, TC-1…TC-10 | **Proposals only.** Untested |
| That MRP-1.1 will recover the theory faithfully | **Unproven.** Cannot be proven in advance; it can only be tested and bounded |

---

## 3. Residual failure modes (possible even if every control passes)

| # | Failure mode | Why the controls cannot exclude it |
|---|---|---|
| RF-1 | **Shared misreading.** Both I and the reviewer misunderstand a passage the same way | Double extraction detects *disagreement*, not shared error. A same-model reviewer makes this more likely |
| RF-2 | **Line accounted, meaning missed.** A line extracted for concept X also carries concept Y | Line accounting tracks *lines*, not *meanings* |
| RF-3 | **Rare concept in unread files.** An important idea that appears in one unread file | Probes bound the *share of files*, not the existence of a rare item (§4.6) |
| RF-4 | **Undetectable common lineage.** AI sessions sharing a prompt or context produce "independent" documents that are not independent | Lineage is detected only through explicit citation, verbatim overlap or session identity |
| RF-5 | **Granularity drift.** What counts as "one concept" or "one claim" varies between files or readers | All counts (capture–recapture, saturation) depend on the unit of count |
| RF-6 | **Wrong dates.** The source's own dates are wrong, or git dates reflect copying, not authorship | Precedence rules trust confirmed explicit dates; confirmation can itself be wrong |
| RF-7 | **Encoding is interpretation.** Formalizing a definition for a logic check fixes one reading | Logic checks validate the *encoding* (T-layer), not the authors' intent |
| RF-8 | **Prior exposure.** I read four corpus files, Session A families and manuscript v1 earlier | Exposure can be measured (lead scoring), not removed |
| RF-9 | **Validation unrepresentative.** V-1 is synthetic and V-2 is background text | Passing them does not show the method works on the corpus; V-3 is the first real evidence |

**[I]** These residual risks are the reason every output must carry its coverage, review sample and open items, and the reason the final theory stays provisional wherever they apply.

---

## 4. Statistical and logical scrutiny of M-02

### 4.1 Line accounting
- **Assumptions:**
  - reason codes are applied honestly;
  - one classification per range is meaningful.
- **Limitations:**
  - RF-2;
  - NOT-EXTRACTED reasons are self-assigned;
  - OFF-TOPIC can hide a concept I failed to recognise.
- **What it achieves:** an *auditable* omission record, not completeness.
- **Smallest correction:** in reviewed files, the reviewer **audits a sample of NOT-EXTRACTED ranges**, and that audit is the only evidence of the control's effectiveness.

### 4.2 Double extraction and capture–recapture

**Assumptions of Chapman's estimator:**
- a closed population of items;
- independence between the two extractions;
- equal catchability of items;
- **correct matching** of items between extractions.

**Corrections to M-02:**
- **"Lower bound" holds only under positive dependence** (easy items found by both). With a *negatively* dependent reviewer, for example one with different blind spots, N̂ can *overestimate*. A same-model reviewer makes positive dependence likely; a human reviewer may not. **The direction of bias must be stated per reviewer, not assumed.**
- **Matching is a judgement.** False matches inflate *m* and bias N̂ down; missed matches bias it up. The **matching rule must be fixed before comparison**: same item type, overlapping anchors, and the adjudicator judging it the same proposition. The adjudicator must not be me.
- **Small *m* gives very wide uncertainty;** report an interval or "not estimable", never a point alone.
- **The unit of count must be defined in advance** (RF-5): one item = one definition, assumption, claim, mathematical statement, revision or rejection, at one anchor.

**What it achieves:** an indication of how much both readers miss, under stated assumptions. **It does not show that all missed concepts were found.**

### 4.3 Source standing vs validity

- **Correction to the R-layer wording.** The corpus is largely AI-session brainstorming, so "what the authors *believed*" is ill-defined. The R-layer should record **what each document asserts and with what self-declared standing** (draft, proposal, adopted, superseded).
- **Validity is relative to the T-layer's assumptions.**
  - CONSISTENT means "no contradiction found with the chosen set".
  - First-order consistency is undecidable in general, so solver checks cover finite or decidable encodings only (RF-7).
- **Rule:** a validity status always names its assumption set and check method.

### 4.4 Lineage and STABLE

- **Assumption:** independence is detectable.
- **Limitation:** RF-4. In a corpus produced from shared contexts, almost everything may be one undetectable lineage.
- **Correction:** STABLE is redefined honestly as **persistence**. The claim is asserted in ≥2 dated documents with no detected lineage link, not retracted, and no open GENUINE contradiction. It must **not** be described as independent confirmation. "No detected lineage" lists the detection methods used.

### 4.5 Contrary-evidence search

- **Assumption:** contrary statements use vocabulary related to the claim.
- **Limitations:** paraphrase, implicit contradiction, and later renaming; recall unknown.
- **V-1 can estimate recall only for planted synthetic statements;** that estimate does not transfer to the corpus.
- **Rule:** every STABLE claim states "stable within the searched and read evidence", naming both. An optional ML locator (keyphrase or embedding similarity) can widen the search but remains a locator.

### 4.6 Discovery-curve saturation (TC-4) — **flaw found**

- **Circularity.** M-02 counts only new *foundational* concepts, where foundational means "another concept or claim depends on it in DEPENDENCIES". A newly discovered concept has **no recorded dependents yet**, so it can never count as foundational at the time it is found. As written, the saturation test would pass trivially.
- **Smallest correction:** TC-4 counts **all new concepts** per batch.
  - Foundational status is re-evaluated **retrospectively** after each batch.
  - Any concept that later becomes foundational re-opens the saturation window for the stratum where it was found.
- **Further limitations:**
  - saturation depends on reading order;
  - it says nothing about unread strata;
  - *k* has no general justification and must be stated with the observed curve.

  Species-richness estimators (e.g. Chao1) assume a random sample, which a chronological sweep is not. They are descriptive only.

### 4.7 Probing unread files (TC-5) — **overstatement found**

The bound 1 − 0.05^(1/n) assumes:
- **simple random sampling** within the stratum;
- **perfect detection** by the probe reader;
- a well-defined "new foundational concept" (see 4.6).

With detection sensitivity *s* < 1, the bound on true prevalence becomes about (1 − 0.05^(1/n)) / *s*:

| Probe size *n* | 95% bound on prevalence, *s* = 1.0 | *s* = 0.8 | *s* = 0.5 |
|---|---|---|---|
| 12 | 22% | 28% | 44% |
| 30 | 10% | 12% | 19% |

**Rare concepts are essentially invisible to probes.** A concept carried by one file among 1,500 unread files is found by a 30-file probe with probability 2%. **Zero finds bound how common such files are; they do not show none exist.**

- **Correction:** report probe results as the prevalence bound, with *s* taken from V-1/V-2 where possible, and add the rare-concept statement verbatim.
- With several strata, either report per stratum or split α across strata (Bonferroni).

### 4.8 The completion label — **overstatement found**

**COMPLETE-WITHIN-SCOPE, applied to the whole frame, claims more than TC-5 can support.**

**Smallest correction:** keep the label, but **define its scope as the set of files actually read**. TC-1…TC-3 and TC-6…TC-10 apply to that set. The frame-level statement is always separate and always of the form:

> *"Unread strata X, Y: probed with n files, zero new concepts found; under random sampling and detection sensitivity s, the share of unread files carrying a new concept is below p at 95%. Rare concepts are not excluded."*

**The reconstruction of the frame as a whole is never labelled complete.** It is PROVISIONAL with a bounded statement.

### 4.9 Tool and template corrections — documented, **not applied**

| # | Item | Problem | Proposed fix |
|---|---|---|---|
| TF-1 | `quote_anchor_check.py` uses `str.splitlines()` | Python also splits on U+2028, U+2029, U+0085, `\x0b`, `\x0c`, `\x1c`–`\x1e`. Line numbers would then diverge from `wc -l` and editor numbering for files containing them; the S5 r7 guide shows such characters occur in this programme's data | split on `\n` only (and strip a trailing `\r`); add a test with U+2028 |
| TF-2 | the tool reads the **first** `source_path:` line anywhere in the record | a quoted `source_path:` inside the body could be picked up if the header is missing | parse only the fenced YAML header |
| TF-3 | templates encode MRP-1 (single claim status, `CONCEPTS.supersedes`) | will be wrong if MRP-1.1 is adopted | change only after D-1 |

---

## 5. Validation plans

### V-1 — Independent synthetic mini-corpus

**Purpose:** test discovery, classification and bias resistance against a known answer.

**Roles, each a different person or session:**
- **Author** writes the documents and the sealed key. The author must not have read the KnowledgeOS corpus, so that no corpus content leaks into the material. A session with no corpus access under G-LOG-0144-like conditions, or a human, qualifies.
- **Professor** extracts.
- **Scorer** compares extraction and key and must not be me. The Principal, or a third session, qualifies.

**Material requirements:**
- **Domain:** analogous in shape to KnowledgeOS (definitions, revisions, formal statements), but **not** KnowledgeOS itself.
- **Size:** 10–12 dated documents carrying about 20 planted items. Each failure mode A–E must be exercised **at least twice**, so a single lucky hit cannot pass a mode.
- **Each failure-mode category must be represented:**
  - explicit definitions;
  - definition by example only;
  - an unfamiliar-vocabulary concept;
  - NARROWS, CHANGES and RETRACTS events;
  - a GENUINE contradiction, plus SCOPE and TERMINOLOGY pseudo-contradictions;
  - an implicit assumption;
  - a formula with a deliberate error;
  - a diagram-only relation;
  - an evasively worded contrary statement;
  - a misleading later summary;
  - **some genuine ambiguity, marked as such in the key**.

**Sealing (commit–reveal):**
1. The author records the key's sha256 in the governance log; the key itself is kept outside the repository.
2. After my extraction is frozen, its sha256 is recorded the same way.
3. Only then is the key revealed to the scorer.

**Scoring protocol, fixed before the run.** Each key item gets exactly one outcome:

| Outcome | Meaning |
|---|---|
| **CAPTURED** | correctly extracted and correctly classified |
| **CAUGHT-BY-CONTROL** | initially missed or misread, but flagged by a control (line accounting, REVIEW, unmatched-term list, anchor check) |
| **MISSED** | not extracted and not flagged |
| **MISREAD** | extracted with the wrong meaning or class, not flagged |
| **AMBIGUOUS-IN-MATERIAL** | the key itself marks the item as ambiguous; any defensible reading counts |
| **NOT-SCORABLE** | the scorer cannot decide; reported, not counted |

**Results:**
- **Success:** no MISSED or MISREAD foundational item; the bias trap did not propagate; anchor check PASS throughout; pseudo-contradictions not classed GENUINE; no interpretation recorded as evidence.
- **Failure:** any foundational MISSED or MISREAD that **no control flagged**, or bias-trap propagation, or interpretation recorded as evidence. *Most checks passing does not offset one of these*, because each is exactly the failure the controls exist to catch.
- **Inconclusive:**
  - the independence of author, scorer or key is compromised;
  - a failure mode was exercised fewer than twice;
  - NOT-SCORABLE items exceed what the scorer can resolve;
  - the Principal judges the material unrepresentative.

**Using the results.** Detection rates are reported as counts with Beta(d+1, s−d+1) intervals, where *d* is the number detected and *s* the number seeded. With ~20 items these intervals are wide by design (a perfect score still has a 95% Wilson lower bound of about 84%), so the outcome cannot certify general reliability. The interval is the estimate of *s* used in §4.7.

### V-2 — Background inter-review (RQ-002, 7 files, 825 lines)

**Purpose:** test interpretive fidelity on real, unrestricted text.

**Independence despite my prior reading:**
- **The reviewer receives only:** the 7 file paths, the E-template, the unit-of-count definition and instructions. It receives **no** basis note, no M-document, no LEADS.
- **Reviewer access is audited afterwards** from its transcript: every tool call must touch only the 7 files or its own output. This is the same capability-closure idea as the S5 r7 W8 check.
- **Both extractions are frozen by hash before comparison.** I extract first and do not see the reviewer's output until both are frozen.
- **A third party adjudicates disagreements,** using the pre-fixed matching rule (§4.2).
- **Caveat:** I have read RQ-002 before (B-08), so V-2 tests **fidelity, not discovery**.

**Who reviews:** a sub-agent (same model family; positive dependence likely; capture–recapture then a lower bound) or a human (stronger independence; slower). **Principal decision D-5.**

**Recording:** REVIEW rows (item · my reading · reviewer's reading · class OMISSION / MISREADING / LABEL-ERROR / SCOPE / BOTH-DEFENSIBLE · adjudication · decided-by); capture–recapture with its interval and the stated dependence direction.

**Results:**
- **Success:** every disagreement adjudicated; no LABEL-ERROR on my side; no MISREADING of a definition or invariant; estimated misses reported with an interval.
- **Failure:** any adjudicated MISREADING of a definition, or a LABEL-ERROR (interpretation recorded as evidence).
- **Inconclusive:** the reviewer's access audit shows reads outside the 7 files; or the matching rule cannot be applied.

**Corpus content:** none. G-LOG-01xx §5 restricts corpus material only.

### V-3 — Corpus pilot (5–10 files)

**Prerequisites:** all of §9, plus V-1 and V-2 not FAILED (or their failures fixed and re-tested), plus D-1 adoption.

**Scope:**
- the earliest-dated T0 files by explicit date rule, not by my choice;
- each file's **prior exposure** recorded: the earlier lane read 4 corpus files.

**Review:** by the reviewer and access record of D-10; at least half of the pilot files double-extracted, all T0 overlap included.

**Acceptance:**
- every file meets the M-01 §5.2 criteria and line accounting;
- anchor check PASS;
- REVIEW fully adjudicated;
- no reviewer-found foundational omission or misreading that no control flagged;
- R/T labels intact.

**Stop conditions (immediate stop, report, no further reading):**
- the filter reports a sealed or hold-out match, or GOVERNANCE_INOPERATIVE;
- a file outside the permitted list is opened (U-3 analogue);
- the count-only check indicates earlier material was affected (G-LOG-01xx §6);
- a reviewer access audit fails;
- a LABEL-ERROR of interpretation-as-evidence.

**Report:** a BATCH report, plus REVIEW summary, capture–recapture, lead scores, the discovery curve so far, and an explicit list of what the pilot does **not** show (RF-1…RF-9).

---

## 6. Criteria summary

| | Success | Failure | Inconclusive | Stop |
|---|---|---|---|---|
| V-1 | §5 V-1 | any unflagged foundational miss or misread; bias propagation; interpretation as evidence | independence compromised; under-exercised modes; unrepresentative | key unsealed before extraction is frozen |
| V-2 | §5 V-2 | adjudicated misreading of a definition or invariant; label error | reviewer access outside scope; matching inapplicable | the reviewer receives my notes |
| V-3 | §5 V-3 | as V-1/V-2, on corpus | review incomplete | §5 V-3 stop list |
| Scale-up | V-3 accepted and D-13 taken | — | — | any stop condition |

---

## 7. Dependency-ordered execution plan

```
D-1 (candidate adoption) ─┬─► §4 corrections + TF-1..3 applied (needs D-12)
                          ├─► D-2/D-3/D-4 ─► V-1 author writes material + sealed key ─► Professor extracts (frozen) ─► reveal ─► score
                          └─► D-5/D-6 ─────► V-2: Professor extracts (frozen) ∥ reviewer extracts (frozen) ─► audit ─► adjudicate
                                                     │
D-7 gate passed ─► D-8 workspace scope ─► D-9 filter-run assessment (U-3, U-5) ─► D-10 corpus reviewer
                                                     │
                     V-1, V-2 not FAILED ────────────┴─► V-3 pilot ─► review ─► D-13 scale decision
```

V-1 and V-2 can run in parallel. The governance chain D-7 → D-10 runs independently of them; V-3 needs both branches.

---

## 8. Principal decision register (none decided here)

| ID | Decision | Options | Consequence of each |
|---|---|---|---|
| D-1 | Status of MRP-1.1 | (a) adopt as candidate for validation · (b) adopt in part (name parts) · (c) reject | (a) enables V-1/V-2 and the template changes · (b) the plan is narrowed · (c) MRP-1 stays; failure modes C–E stay unresolved |
| D-2 | V-1 author | human · a session with no corpus exposure · other | independence of the material |
| D-3 | V-1 sealing | commit–reveal via governance log · other | blindness of the test |
| D-4 | V-1 scorer | Principal · third session · other | independence of scoring |
| D-5 | V-2 reviewer | sub-agent (faster, likely positively dependent) · human (more independent, slower) | strength of the fidelity evidence; direction of capture–recapture bias |
| D-6 | V-2/V-3 adjudicator | Principal · third session | who settles disputes about meaning |
| D-7 | Safety gate (G-LOG-01xx §3) | record passed · not yet | required for any corpus action |
| D-8 | Scope extension to `knowlegeos_manual_theory_20261018/` | grant in writing · keep S-Series workspace only | required; otherwise corpus work must move to the authorized workspace |
| D-9 | Assessment of earlier filter runs incl. U-3 and U-5 | via the authorized process | may require stopping or re-basing earlier reads |
| D-10 | Who may review corpus extractions, under which access record | human · separately authorized session | required for V-3 |
| D-11 | Probe tolerance *p* and confidence for TC-5 | e.g. 10% / 95% (n ≈ 30 per stratum at s = 1) · other | sets probe size and reading cost |
| D-12 | Permission to apply TF-1…TF-3 and template changes | yes · no | tooling correctness before V-1 |
| D-13 | Scale-up after V-3 | proceed · revise · stop | — |
| D-14 | Session-log and CONTEXT synchronization (§10) | permit a write to `.claude/sessions/` · keep the workspace-only scope | project-state traceability |

---

## 9. Preconditions checklist for corpus access (verified 2026-10-18 13:17 UTC)

| # | Precondition | Status | Evidence |
|---|---|---|---|
| P-1 | Independent D1 review PASS (or conditions met) | **not recorded** | no G-LOG entry |
| P-2 | Fake-fixture test signed PASS by a separate human tester, non-root | **not recorded** | no G-LOG entry; the test sheet exists as a document only |
| P-3 | Principal record that the safety gate has passed | **not recorded** | G-LOG-01xx §3; last entry G-LOG-0145 |
| P-4 | Scope and logging confirmed | **not recorded** | — |
| P-5 | Written authorization covering this workspace | **not recorded** | G-LOG-01xx §1 names the S-Series workspace only |
| P-6 | Assessment of earlier filter runs, incl. U-3, U-5 | **not recorded** | G-LOG-01xx §6 |
| P-7 | Corpus reviewer and access record decided | **not decided** | D-10 |
| P-8 | V-1 and V-2 not FAILED; D-1 taken | **not run** | — |

**All corpus actions are blocked**, including:
- reads, searches and counts;
- filter runs;
- queue construction from registry content.

---

## 10. What I may do next, and what is blocked

**Without further approval** (non-corpus, inside this workspace, no reserved decision):
- answer questions on M-01, M-02 and M-03;
- read non-corpus, non-restricted documents for analysis;
- draft (not run) supporting specifications on request: the V-1 author brief (requirements only, never items or key), the V-2 reviewer instruction, the unit-of-count and matching-rule text.

**Blocked until the named decision:**

| Action | Needs |
|---|---|
| Template changes to MRP-1.1; TF-1…TF-3 fixes | D-1, D-12 |
| Pre-registering my leads in LEADS | D-1 (harmless, but it is MRP-1.1 implementation) |
| V-1 | D-1, D-2, D-3, D-4 |
| V-2, including any sub-agent | D-1, D-5, D-6 |
| Any corpus action, V-3 | P-1…P-8 |

**Project-state synchronization issue, reported separately.** The repository's CLAUDE.md requires a session log and CONTEXT update at the end of each session. The Principal limited my writes to this workspace, so `.claude/sessions/2026-10-18.md` and CONTEXT have **not** been updated for today's work (B-07…B-13, M-01…M-03, `04-corpus/`). I have not broadened my scope to fix this (D-14).

---

## 11. Plain-language summary for the Principal

- **Ready to test now, without the corpus:** a planted test written by someone else (V-1), and a reviewer comparison on the RQ-002 background files (V-2). Both need your decisions on who writes, who reviews and who judges.
- **What counts as failure even if most checks pass:** an important item that is missed or misread and that **no check flagged**; a misleading later summary changing how an earlier document is read; or my interpretation recorded as if it were the source.
- **What the method can honestly claim after a successful pilot:**
  - the pilot files were read completely, quoted accurately and reviewed;
  - the reviewed sample shows no unflagged misreading;
  - the estimated misses, coverage and open items are stated.
- **What remains unknown even after successful validation:**
  - errors that both readers make together;
  - rare ideas in unread files;
  - hidden common origins between documents;
  - whether the method behaves the same across the whole corpus.

  The final theory therefore stays provisional wherever these apply, and the whole frame is never called "complete".
- **I found and corrected two flaws in my own M-02:**
  - the saturation test could pass trivially, because a newly found concept can never yet count as foundational;
  - the "complete" label promised more than random probing can show.

  I also found two small defects in the quotation tool. I have documented them and not fixed them, pending your permission.
- **Next action I recommend to you:**
  - **D-1:** adopt MRP-1.1 as a candidate for validation;
  - **D-2 to D-6:** name the test author, scorer, reviewer and adjudicator.

  The governance steps for corpus access (D-7 to D-10) can proceed in parallel.
