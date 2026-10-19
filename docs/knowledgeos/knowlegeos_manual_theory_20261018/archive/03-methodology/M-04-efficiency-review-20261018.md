# M-04 per-file extraction and efficiency review — 2026-10-18

| Field | Value |
|---|---|
| Subject | batch 3 (the latest completed batch): `…0841` V3 · `…0905` CF · `…0922` AB · `…1023` IM · `…1029` IA, all authorized background files |
| Evidence used | idea register · manuscript · `REVIEW-batch-03.md` · `00-access/READ-LOG.tsv` · this session's transcript (tool-call **timestamps and descriptions only**) |
| Not used | the restricted corpus (not read, searched or listed) |
| Method status | M-04 v1.0 is unchanged. Everything in §5 is **PROPOSED** and separate from it. A-1 v2 is still pending |
| Independence | **This is a self-review by the producer of batch 3.** Its findings need the targeted independent review listed in §4 |
| Changes made | none to the manuscript or register. This document only |

## 1. Executive conclusion

**The per-file work is not what takes the time.** The logs show batch 3's five files took about **19 minutes** of logged elapsed time. What takes the calendar time is mainly the **methodology, validation and governance cycles between batches** (evidenced below). Inside the batch, a sizeable share of the effort is **rework** that a few cheap mechanical controls would prevent (evidenced). Speed is not the most important finding, though. **Batch 3 still contains defects that its own look-backs missed**, two of them foundational. A faster process must not be bought by dropping the checks that would find them.

| Cause | Status | Evidence |
|---|---|---|
| Most elapsed time since manual reconstruction began (12:03Z) went to methodology, validation, audit and governance prompts, not to file processing | **evidenced** (logs) | pilot 1 + pilot 2 + batch 3 together ≈ 37 min of the ≈ 218 min between 12:03Z and 15:41Z (§2.2) |
| About a quarter of batch-3 tool calls were rework or re-verification | **evidenced** (logs) | 12 of 84 calls were fixes or corrections; 9 were verification of references and consistency (§2.1) |
| References were written from memory and repaired afterwards | **evidenced** | 3 line anchors, 1 section reference, 2 timing statements, 1 cross-reference corrected within the batch |
| Glossary entries were written per file and then rewritten when later files changed the theory | **evidenced** | "current authority", "enforcement consequence" and "may-read" were rewritten within the same batch |
| Interest-driven extraction left whole sections untraced until the look-back | **evidenced** | 9 sections of V3 had no register row before Lookback A |
| A context-window reset (compaction) split file 1 and forced state reconstruction | **evidenced** (one occurrence) | compaction at 15:18:09Z, in the middle of file 1 |
| Human waiting time between prompts | **unknown** | the logs record when entries were written, not human activity |
| Long chapters (Ch. 1: 926 lines) slow each integration | **hypothesis** | 12 locate/read-manuscript calls in the batch; no comparison baseline |

## 2. Workflow and timing

### 2.1 Batch 3, per file

**Source of times:** transcript entry timestamps (UTC). An interval covers model generation and tool execution. **Active human time is not recorded and is reported as unknown.** "Elapsed" runs from the first to the last tool call attributed to the file.

| File | Lines | Elapsed (logged) | Tool calls | Source reads | Manuscript edits | Bash (register, log, checks, inserts) | Output tokens | Register rows |
|---|---|---|---|---|---|---|---|---|
| ① V3 | 655 | ≈ 6 min (15:14:12–15:20:08), **split by a compaction** | 27 | 3 | 10 | 16 | ≈ 28,000 | 34 (10 from Lookback A) |
| ② CF | 293 | ≈ 3.4 min | 19 | 2 | 7 | 12 | ≈ 19,000 | 16 |
| ③ AB | 240 | ≈ 3.3 min | 14 | 2 | 4 | 10 | ≈ 18,300 | 19 |
| ④ IM | 393 | ≈ 2.3 min | 8 | 3 | 0 (inserted by script) | 8 | ≈ 13,600 | 18 |
| ⑤ IA | 266 | ≈ 2.7 min | 9 | 2 | 0 (inserted by script) | 9 | ≈ 15,400 | 15 |
| Batch review | — | ≈ 1.7 min | 7 | — | — | 6 | ≈ 7,900 | — |
| **Total** | 1,847 | **≈ 19 min** | 84 | 12 | 21 | 61 | ≈ 102,000 | 102 |

**By activity** (classified from each tool call's recorded description; approximate, since one call can serve two purposes):

| Activity | Calls | Comment |
|---|---|---|
| writing the manuscript | 28 | Edit/Write 22, script inserts 6 |
| locating or re-reading manuscript passages | 12 | needed to place text in long chapters |
| **rework: fixes and corrections** | **12** | anchors, overclaims, timing, glossary, table repair |
| reading sources | 10 | |
| **verification** of references and consistency | **9** | the mechanical checks that found the rework |
| register, log and change log | 7 | |
| batch review, glossary, other | 6 | |

Reading, extraction and deduction happen inside the model's generation and **cannot be separated in the logs**. They are reported as unknown, not estimated.

### 2.2 The day, by kind of work (manual reconstruction, 12:03Z–15:41Z)

| Kind of work | Logged span (approx.) | Files advanced |
|---|---|---|
| reading the authorized basis (developer guides, knowledge-transfer set) | 12:03–12:54 | basis notes, not theory |
| methodology assessments M-01–M-03 and templates | 13:12–13:39 | 0 |
| V-2 validation run and adjudication | 13:40–14:11 | 0 (7 files re-extracted for validation) |
| pilot 1 and pilot 2 | 14:31–14:49 | 10 |
| methodology M-04, review and audit disposition | 14:53–15:14 | 0 |
| **batch 3** | **15:14–15:33** | **5** |
| A-1 corrective review | 15:33–15:39 | 0 |

**About 37 of roughly 218 minutes (≈ 17%) were spent processing files.** The remainder includes intervals with no tool activity, which are human time or waiting. **The logs cannot separate those, so the remainder is not all machine work.**

## 3. Per-file reconciliation (source × register × book)

**Method.**
1. A mechanical line-coverage check: every non-blank source line, compared against the line ranges in the register rows for that file.
2. A reading of every uncovered run of lines.
3. Each batch-3 manuscript pointer checked against the chapter text (the Lookback-A rows were checked by string search).

**Limitation:** a covered line is not proof that every proposition on it was captured. This is a stronger check than the batch review ran, not a full independent re-extraction.

| File | Non-blank lines | Not covered by any row | What the uncovered lines contain | Omissions found |
|---|---|---|---|---|
| ① V3 | 393 | 61 (16%) | headers and governance tables · the tier anchor (already in Ch. 0 §0.2, cited from an earlier read) · OQ-1 withdrawal (covered by B3-F1-10/-17) · §15 headings · closing | **1, foundational candidate:** §10 L297–306, the **three-class lifecycle** (pre-authority → authoritative → terminal) and the per-type state chains (Decision, Rule, Evidence …). **Absent from the register and from all chapters.** It bears directly on Ch. 1 §1.2 (standing vs lifecycle) and §1.14.1. **Ordinary:** OQ-1′ wording (L448–452); the document's own standing header (used in Ch. 1 §1.14's source note but not registered) |
| ② CF | 157 | 24 (15%) | header · §10 change table · §11 answer | **Ordinary:** the realization chain "architecture → contracts → invariants → fitness functions → workflow gates → human authority" (L273–283). Its content is in Ch. 1 §1.14.5 (staging), but the row is missing |
| ③ AB | 131 | 25 (19%) | header · table headers · §11 change table (a summary) · footer | **none** (only summaries of registered items) |
| ④ IM | 263 | 92 (35%) | header · the 14-field record (L19–42) · atomicity vocabulary (L61–74) · worksheets 5.3–5.4 (L229–267) · OQ-23 · bottom line | **Ordinary, 3:** (a) "the scan table is a projection of the records" (L21), a P-8 instance; (b) the atomicity values, used in my §1.16.6 argument but never registered; (c) OQ-23. **Location defect:** §1.16.6 cites IM L229–248, but the row (B3-F4-02) lists other lines |
| ⑤ IA | 140 | 20 (14%) | header · OQ table header · bottom line (a summary) | **none** |

**The report's "nine sections added, five integrated", resolved.** Lookback A found **9** uncovered V3 sections and added **10** rows (B3-F1-25…34; §16 produced two). The report's own list named only 7 sections, and "five integrated" was a miscount. Every row's pointer was checked against the chapter text:

| Section of V3 | Row | Disposition | Checked |
|---|---|---|---|
| §3 naming | F1-25 | **integrated**, Ch. 0 §0.2 | text present |
| §7 context map | F1-26 | **integrated**, Ch. 1 §1.14.4 (projection is a sink) | present |
| §11 C4 views | F1-27 | **integrated**, §1.14.4 (P-6 instance). Its sub-item, the Level-4 stack contradiction: **evidence only**, reason "implementation choice" | present |
| §12 persistence | (none new) | **already covered**: F1-09 and F1-17 → Ch. 1 §1.14.4 | present |
| §14.2 OQ register | F1-28 | **evidence only**. *Gap:* the row notes that OQ-9 bears on Ch. 0 §0.9, but this was **not followed up** (follow-up field empty) | — |
| §15.1 observed gap | F1-29 | **integrated**, Ch. 2 §2.1 (verified in YAML) | present |
| §15.4 boundary | F1-30 | **already covered** (repeat), pointer Ch. 1 §1.4 / P-3 | — |
| §16 conceptual target | F1-31, F1-32 | **integrated**, §1.14.4 and §1.15.2 | present |
| §19–20 change table, closing | F1-34, F1-33 | change table: **repeat**. Closing maxims: **integrated**, Ch. 2 §2.8.8. One maxim ("confidence rose …") **evidence only** | present |

**Result: 7 rows integrated, 3 not integrated (1 evidence-only, 2 repeats with pointers), and 1 section already covered.** `REVIEW-batch-03.md` §4 should be corrected accordingly (§7 below).

**Register-wide disposition defects (batch 3):**
- the `status` column uses **16 different values** (e.g. "accepted" 80×, "accepted (as standing info)", "out of theory scope"); none is one of the six dispositions of approved step 6a. Whether "accepted" means integrated, already covered or evidence must be read from the `manuscript` column;
- **4** open or provisional rows have no follow-up (F1-18, F2-10, F3-10, F3-17).

## 4. Correctness, epistemic discipline and independent review

**Distinguishability.**
- **52 of 102** batch-3 rows carry compound labels ("SOURCE FACT + DEDUCTION"). In the manuscript the marks (Src, Ind, Int, Ded, Hyp) are mostly applied per paragraph, so the **book** is more discriminating than the **register**.
- Hypotheses are marked Hyp (referent time, may-read, E8).
- "Independent" convergences are qualified as repetition, not replication.

**Defects found in this review (not found by the batch look-backs):**

| # | Location | Defect | Severity |
|---|---|---|---|
| 1 | Ch. 1 §1.17.2 | "A legitimate rejection or adoption needs a covering grant **and** assembled knowledge" contradicts the book's own authority axiom (§1.6: validity = act × scope × time). An authorized but poorly informed act is **valid but ill-founded**. I-2 requires recorded reasons, not sufficient knowledge. The paragraph conflates **validity** with **well-foundedness** | **foundational** (touches the axiom) |
| 2 | V3 §10 (L297–306) | three-class lifecycle omitted (§3) | **foundational candidate** |
| 3 | Ch. 1 §1.14.1 correction | the counterexample ("work delivered under a consumed grant must keep its authority") rests on a **normative premise** (Int) that is not stated. The data show only that CONSUMED exists | ordinary (premise not stated); the corrected formula itself is the item for review |
| 4 | Ch. 1 §1.16.7 | "prevention is impossible in principle without a single write path" is too strong. Several write paths coordinated through one shared lock or registry are one **logical** serialization point. The glossary entry repeats the strong form | ordinary |
| 5 | Ch. 1 §1.16.3 | "reproducibility is a precondition of verification" holds for verification **by re-execution**; a proof can be verified by checking the proof object. Needs scoping | ordinary |
| 6 | Ch. 1 §1.14.5 ("superseded blocker") | treats V3 REV 1's withdrawal of OQ-1 as settled. Its standing is an **ARB ruling reported inside an AI-generated proposal, original record not located**. The text should be conditional. This is close to resolving by "later document" | ordinary (standing) |
| 7 | Ch. 2 §2.7 | the remark on deterministic decoding is general machine-learning knowledge, unverified here; it should be marked Int | ordinary |

**Contradictions in batch 3** (6 rows: F1-11, F1-18, F2-03, F2-06, F3-07, F4-03): none was resolved by date or count alone **except #6 above**, which leans on the later document. B3-F2-03 (label reuse) is correctly kept unresolved.

**Items needing targeted independent review** (a fresh reviewer, background material only):
1. Ch. 1 §1.14.1, the two-time correction of current authority (**foundational**; changes text the earlier audit reviewed).
2. Ch. 1 §1.14.5, the enforcement bound and the permit/oblige argument.
3. Ch. 1 §1.17.2 **after** correcting defect 1.
4. The integration of the V3 three-class lifecycle **after** it is written (defect 2).
5. The hypotheses, to be reviewed **as hypotheses** (not as theory): referent time (§1.16.6), may-read with confidentiality class and E8 (§1.17.5, Ch. 2 §2.8.8).

None of items 1–5 may be cited as established theory until reviewed.

## 5. Proposed efficiency improvements — PROPOSED, separate from M-04 v1.0

| # | Current problem (evidence) | Change | Quality safeguard kept | Expected benefit | Risk or limitation | M-04 change needed? |
|---|---|---|---|---|---|---|
| E-1 | Sections went untraced until the look-back; one foundational omission survived it (§3) | **Mechanical line-coverage check** before Lookback A: list source lines not covered by any register row | Lookback A still reads every uncovered run; coverage ≠ completeness | finds gaps in seconds (this review's check ran in one call) | covered lines can still hide missed propositions | **No**; tool support for step 6a |
| E-2 | 7 reference and fact repairs in one batch | **Cite only from verified output:** anchors and section numbers are written from a grep or read in the same step | the existing reference check at batch review | removes most anchor rework | small per-citation cost | **No** (step 4) |
| E-3 | Per-file whole-book searches; 3 glossary entries rewritten within one batch | **Move whole-book coherence to the five-file review:** glossary consolidation, stale-copy search, cross-reference script, bare-term checks. **Per file, keep:** completeness (6a), correctness of new text (premises, one counterexample attempt per new formula), and a list of definitions touched | **exception:** if a file changes the *meaning* of an existing glossary term, the whole-book search runs immediately | fewer cascades; one consolidated glossary pass | inconsistencies can persist for up to five files | **Yes.** Approved step 7 asks per file whether "the book still makes sense" across chapters. Needs Principal approval |
| E-4 | 16 status values, none of them step 6a's six dispositions; 52 of 102 compound labels | Use step 6a's **six dispositions verbatim** in the register; one epistemic mark per row (as A-1(a) proposes) | unchanged dispositions | review reads dispositions directly | old rows need a one-off normalisation | dispositions: **no** (compliance with 6a); single mark: part of **A-1 (pending)** |
| E-5 | Extraction followed interest, so Lookback A *discovered* rather than *confirmed* (10 rows added for V3) | **Linear pass:** extract section by section in source order before writing | Lookback A unchanged | fewer late additions and re-reads | none known | **No** (step 2) |
| E-6 | Compaction split file 1 (one occurrence) | **Close each file before a context reset is likely:** finish register, log and look-backs per file; write a short state note | — | no state reconstruction mid-file | benefit is a hypothesis (one occurrence) | **No** |
| E-7 | ≈ 17% of logged elapsed time was file processing | **Bundle method and governance questions** into the five-file review instead of between files | methodology reviews still happen, at fixed points | more calendar time on the theory | method defects found later | **No** change to M-04; a Principal process choice |
| E-8 | Independent review is either whole chapters (audit) or nothing | **Targeted independent review** of foundational corrections and new formulas per batch (the list in §4) | full audits remain at their checkpoints | earlier detection of foundational errors at lower cost | reviewer independence and access rules unchanged | **No** (an audit within approved scope) |

**Not proposed:** dropping per-file look-backs, the three-way completeness check, or verification against code and records. These found real errors in batch 3 (two logic errors in my own text) and are the main quality safeguards.

## 6. Stopping rule (proposed definition of "file complete")

**A file is COMPLETE when all of these hold:**
1. every non-blank source line is covered by a register row **or** marked non-propositional (header, footer, summary of registered items) with a reason (E-1);
2. every row has one of step 6a's six dispositions, and every pointer resolves to text that exists;
3. every new Ded or formula paragraph names its premises and records one counterexample attempt;
4. every anchor written for the file has been checked against the source;
5. every row left open or unresolved has a stated reason and a follow-up.

**Mark the file CORRECT** (do not close it) when any of these holds:
- a condition above fails;
- a foundational defect is found in text that the file's integration depends on;
- a new formula fails its counterexample attempt and has not yet been rewritten.

**An issue may stay OPEN without blocking closure** when:
- it is a genuine question that the available evidence or authority cannot decide (outcome *unresolved*, with both positions visible in the book);
- its reason and follow-up are recorded;
- it does not invalidate other text written from the same file.

**Measures, not thresholds.** Per file, record:
- non-blank lines and register rows;
- the uncovered-line share before and after Lookback A;
- the number of reference repairs and rework calls;
- later: omissions, overstatements and foundational defects found by review or audit.

**Baseline needed before any threshold is set:** these measures for pilot 1, pilot 2 and batch 3 (partly recoverable from the logs and this review), plus the targeted independent review of batch 3. That review supplies the "found later" figures, which are the only direct measure of what the per-file checks miss.

## 7. Minimal acceptance checklist for the next authorized batch

Applies under M-04 v1.0. It uses only what the approved method already requires, plus E-1, E-2, E-4 (dispositions only) and E-5, which need no method change.

- [ ] Batch-3 corrections done first: defects 1–7 of §4; V3 §10 integrated; the four missing rows registered (CF L273–283, IM L21, IM atomicity values, OQ-23); IM row location fixed; 4 follow-ups filled; `REVIEW-batch-03.md` §4 count corrected (7 integrated, 3 not, 1 already covered)
- [ ] Targeted independent review of §4 items 1–5 commissioned (background material only)
- [ ] For each file: coverage check run and its result recorded; uncovered runs read and dispositioned
- [ ] Register rows use step 6a's six dispositions; open rows have follow-ups
- [ ] Every new formula: premises and counterexample attempt recorded
- [ ] Anchors cited only from verified output; reference check passes at batch review
- [ ] Batch review: glossary consolidated; cross-chapter reference check; CONTINUE / CORRECT / PAUSE stated with the §6 measures

## 8. Recommendation: **CORRECT**

**Why not CONTINUE:**
- batch 3 contains one **foundational** error in my own text (§1.17.2 conflicts with the axiom) and one **foundational-candidate omission** (V3's three-class lifecycle);
- the batch report misstates its own look-back result.

None of these was caught by the per-file look-backs, so starting the next batch on top of them would spread the errors.

**Why not PAUSE:**
- the defects are specific, located and correctable within the approved method;
- no governance or access boundary is involved;
- the method's safeguards are working, but they are being applied inconsistently. They do not need replacing.

**Limitations:**
- this is a **self-review**;
- the line-coverage check is mechanical, and coverage is not completeness;
- the timing evidence is entry timestamps, not active time;
- human waiting time is unknown;
- the efficiency benefits in §5 are expected, not measured.

**Next step:** wait for the Principal's instruction. The batch-3 corrections in §7, the targeted review, and E-3 (which changes approved step 7) each need the Principal's go-ahead.
