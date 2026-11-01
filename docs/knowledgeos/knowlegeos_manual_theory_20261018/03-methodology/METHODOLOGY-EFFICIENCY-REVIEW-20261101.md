# Methodology efficiency review: raising reliable theoretical progress per unit of effort (ADVISORY)

| Field | Value |
|---|---|
| Status | **ADVISORY. Decides nothing.** Rulebook v1.4 and `REVIEWER-INSTRUCTIONS.md` remain the binding rules. Nothing here is in force, no rule is changed, no research direction is set |
| Asked by | the Principal, 2026-11-01 (task brief: "How can we increase the rate of reliable theoretical progress per unit of effort without weakening research quality, evidence traceability, independent review, or governance?") |
| Prepared by | an independent AI review session (Claude Code harness; the runtime reports the model as `deepseek-chat`). Not the Professor, not the Governance Engineer, not the Rule Reviewer |
| Date | 2026-11-01 |
| Repository state | lane head `8e1ef363f` (2026-10-31); working tree clean in the lane except `tmp/corpus-reading/START-TIMES.tsv` (modified, uncommitted, not read for this review) |
| Supersedes | the previous advisory review of 2026-10-31, delivered in conversation only and **not stored as a repository file**. Its findings are restated by ID in §4 and reassessed there. That review is not deleted; it has no file to delete |
| Written against | Rulebook v1.4 (APPROVED 2026-10-24), `REVIEWER-INSTRUCTIONS.md` (updated 2026-10-24), `CONSOLIDATION-BRIEF-TEMPLATE.md` (in force from S0200 D6) |

**Labels used in this document** (kept apart throughout, as the lane's own practice requires):

| Label | Meaning |
|---|---|
| **[R]** | **binding rule** — text in Rulebook v1.4 or `REVIEWER-INSTRUCTIONS.md` (quoted with line reference) |
| **[V]** | **verified fact** — inspected in the file named, or produced by a command recorded at the end |
| **[D]** | **recorded decision or Principal direction** — text in the decision record or a mandate |
| **[Ded]** | **derived** from stated premises, with the premises named |
| **[I]** | **interpretation** — my reading of verified facts |
| **[H]** | **hypothesis requiring validation** — not tested |
| **[P]** | **proposal** — advisory; requires the authority named |
| **[U]** | **unknown or unmeasured** — the evidence does not decide it |

---

## 1. Executive summary

### What is working

**[V]** The research lane is a working machine, and its integrity machinery is genuinely strong:

- 21,056 lines of book in 183 files; 265 concepts in the register (57 VALIDATED, 112 PROVISIONAL, 96 INVALID); 111 review folders; 2,831 tracked files and 533 commits in the lane.
- Every claim is line-anchored to a source; every review is blind-first and handed back before the author's work is seen; every finding is dispositioned with a "resolved when"; every consolidation is reviewed by two reviewers on different models and then integrated; every second consolidation carries a planted-gap test.
- **[V]** The last consolidation (S0253–S0277) reported 59 findings, 54 after merging, of which the integration review **confirmed 44, modified 10, rejected 0**, with 6 high and 0 critical, in 66 minutes of reviewer time. That is a low false-positive rate and a real review, not a rubber stamp.
- The lane already complies with the harder epistemic rules most research programmes never write down: no rate without a random sample (v1.4 §4, L91); agreement within one lineage counts as one voice (Ch. 8 §8.31); "a ✓ is a result of same-process review, not of independent confirmation" (`THEORY-MAP.md` L5).

### What is slowing progress

Three separable things — they are not one problem and they do not have one fix.

1. **The theory has no scheduled production step.** **[V]** The book's own outline says it: *"Missing: the derivation from primitives to laws as one chain. … minimality of the primitives was never tested"* (`BOOK-OUTLINE.md` L44). **[V]** The Professor's own blockers analysis of 2026-10-31 ranks five open questions by how many sections cite them — **36, 31, 29, 28, 26** — and the first is *"the kernel's attribute set and class vocabulary"*, with *"two attribute systems standing side by side"* (`review/BLOCKERS-S0278-S0302.md`, table rows 1–5). **[V]** `BOOK-OUTLINE.md` L179 names the requirement set *"the load-bearing undefined object"*: six claims defer to it. The pipeline produces sections, verdicts and reviews; nothing in it is scheduled to produce the compression those five questions need.

2. **The measurement stops at activity.** **[V]** The lane measures sources read, findings per window, review harness time, planted-gap counts and concept statuses. It does not measure where the Professor's time goes — its own proposal says so: *"the Professor's reading log records bookkeeping times only … so where the time goes is unknown"* (`PROPOSAL-FASTER-READING-TEST-CYCLE-20261031.md` §1 [I] 1). **[V]** The C58 window file confirms it: *"Bookkeeping times: each close was logged when the next source was opened; they understate reading"* (`C58-S0274-S0277/STEP8-AND-CHECKS.md` L11). **[V]** On 2026-10-30 the log records 25 full sources and 10,606 lines between 14:00 and 15:16 UTC; on 2026-10-31, 50 full sources and 22,153 lines between 00:36 and 15:56 UTC. Those timestamps are close events, not work intervals. **Therefore the bottleneck cannot currently be identified** — reading, drafting, settlement or review could each be it.

3. **The calibration instrument cannot decide anything at its current size.** **[V]** Planted-gap detection is 4/8, 3/5, 2/4, 4/4 across the four tests with published counts; Wilson 95 % intervals are [0.22, 0.79], [0.23, 0.88], [0.15, 0.85] and [0.51, 1.00] — **all four overlap, so the series shows no detectable change**, and the plant classes differ between tests, so the denominators are not comparable either. **[V]** Detection is also not the same as repair: at S0150 two of the three found items were treated only in part; at S0100 three of four found items were treated correctly and one partially (`PLANTED-GAP-RESULT.md`, "Treatment" tables).

### What the evidence supports

**[I]** The method is optimised for **not being wrong** — traceability, no-overclaiming, admissible correction — and under-optimised for **becoming smaller and more decided**. That is a good failure to have, and it is fixable without weakening any safeguard, because the strongest available intervention is **already authorised**: Rulebook §1 puts *"a critical foundational problem"* above coverage (L45), and §12b already provides theory turns. The lane is not doing what its own priority rule says; it is continuing index-order reading past five questions it has itself ranked as blocking 150 section-citations in total.

### What should happen next

Three bounded steps, in this order, none of which changes a rule:

1. **Measure the time split for three windows and one consolidation** (S8 of the existing proposal, run first, as an observation, not as part of the reading test). Without it, every throughput proposal is a guess.
2. **Run one theory-construction turn on the requirement set (T9-01)**, using existing artifacts — the BLOCKERS ranking, the register, `BOOK-OUTLINE.md` §1, and `tmp/tools/ce_model.py`. The rule already permits it; it is the highest-leverage work available.
3. **Defer the faster-reading bundle test** until step 1 reports. The test's own success threshold (1.5 × baseline, about 40 sources/day) lies inside the variance already observed under unchanged rules (25 and 50 sources on consecutive days), so it cannot attribute a result to the change.

---

## 2. Evidence and limitations

### 2.1 Inspected, in full

| Source | What it establishes here | Strength |
|---|---|---|
| `03-methodology/RULEBOOK-v1.4.md` (174 lines) | the binding rules: §1 priority (L45), §3 window (L81), §4 no-rate rule (L91), §6 review/settlement (L106–120), §7 limits (L122–126), §10 measures (L132–138), §12 consolidations (L142–151), §12b theory turns (L155–161), §12c whole-book (L163–166), §12d dependency graph (L168–170) | **primary rule text** |
| `03-methodology/REVIEWER-INSTRUCTIONS.md` (161 lines) | reviewer role, four modes, blind inventory (L34–48), completeness (L50–67), validity (L69–93), toolbox (L95–103), three verdicts (L118–125), severity (L145–148), running measure (L150) | **primary rule text** |
| `03-methodology/CONSOLIDATION-BRIEF-TEMPLATE.md` (184 lines) | fixed brief structure; G1–G7 checks; the S0200 evidence that Ch. 8 (~4,100 lines) was read in full by neither reviewer (§1.2 L28) | primary process text |
| `03-methodology/PROPOSAL-FASTER-READING-TEST-CYCLE-20261031.md` (117 lines) | the baseline table (§1), the explicit non-attribution caveat (§2 L43), criteria S1–S8 (§4), rollback (§5) | primary proposal; **not in force** |
| `03-methodology/KERNEL-SCOPE-REVIEW-20261031.md` (117 lines) | the engineering-vs-organisational scope conflict, unresolved; Models 1/2/3; the recommendation for Option A | primary review |
| `03-methodology/STATUS-RESEARCH-TRACK-20261029.md` | the one-sentence verdict on the theory; the deliverable table | snapshot, dated |
| `03-methodology/GOVERNANCE-ENGINEER-STATE.md` | role/session state; the S0226–S0252 test scored 4/4 | state file |
| `review/BLOCKERS-S0278-S0302.md` | the five blocking questions and their citation counts; its own stated limits | **primary, most recent** |
| `review/CONSOLIDATION-S0131-S0150/PLANTED-GAP-RESULT.md` | 3/5; treatment verdicts; the commit-subject leak caveat | primary |
| `review/CONSOLIDATION-S0182-S0200/PLANTED-GAP-RESULT.md` | 2/4; reviewer A aware of the test; the misdiagnosis | primary |
| `review/CONSOLIDATION-S0226-S0252/PLANTED-GAP-RESULT.md` | 4/4; the quote-check blind spot for "(L…)" citations | primary |
| `review/WHOLE-BOOK-S0100/PLANTED-GAP-RESULT.md` | 4/8; the class-level conclusions (absence is not found by reading) | primary |
| `review/CONSOLIDATION-S0036-S0065/PLANTED-GAP-RESULT.md` | the test was partly compromised (originals reachable) | primary |
| `review/CONSOLIDATION-S0253-S0277/SETTLEMENT.md` | 59/54/44 confirmed/10 modified/0 rejected; 3,970.4 s | primary |
| `06-theory/RESEARCH-PURPOSE.md` (greps, not a full read) | the five deliverables (L8), the concept test (L9), the minimality definition (L46), the load-bearing caveat (L41) | primary goal text |
| `06-theory/manuscript/BOOK-OUTLINE.md` (greps) | the missing derivation chain (L44); the load-bearing undefined object (L179) | primary |
| `06-theory/THEORY-MAP.md` L5 | "validated" is same-family review survival, not independent confirmation | primary admission |
| `06-theory/CONCEPT-REGISTER.tsv` | 265 rows; 57/112/96; first-source and max-source distributions (§3.3) | primary data |
| `tmp/corpus-reading/READ-LOG-CHRONO-20261018.tsv` | per-day sources, lines, timestamps; 489 entries | primary data |
| `tmp/tools/close_source.py --position` | position S0302; 293 read in order; 80 scans; 13 branch-only; consolidation gate 25/25 DUE | **executed** |
| `tmp/tools/ce_model.py` | executed at head; **61 lines containing PASS, 0 containing FAIL**; no file-writing call found by grep | **executed** |
| `03-methodology/METHOD-VERSIONS.md` L1–60 + structure | 83 table rows; the stale "Limitation" (L52–54) | primary |

### 2.2 Not inspected, and what that costs

| Not inspected | Why | Effect on the conclusions |
|---|---|---|
| The corpus sources themselves (S0001–S0302) | access rules (v1.4 §7 L124); the corpus is the Professor's, not this review's | I can say nothing about whether the corpus actually contains what the book extracts; every claim below is about the **process**, not the content |
| `02-FILES.jsonl` (5.6 MB, 2,734 sources) | not on the allowed list; v1.4 §7 L124 forbids opening files outside it | corpus size (~2,734) is **[reported]** from `STATUS-RESEARCH-TRACK-20261029.md`, not verified by me |
| `00-access/ACCESS-RECORD.md` and `00-access/consolidations/` | no grant for this review; these hold test keys and exposure records | I did not verify any hash, sealing time or blindness claim independently. All blindness statements in §4 are quoted from the result files |
| The full 100 KB decision record (76 addenda) | read at structure level only | individual Principal decisions may be mis-cited; I cite only those quoted in secondary documents |
| AIP-14, the charter, the development constitution | outside the lane; the KERNEL-SCOPE-REVIEW itself marks these "via Ch. 0" | the scope conclusion rests on the Governance Engineer's reading, not mine |
| `ce_model.py` internals (2,684 lines) | executed, not audited | I verified that it runs and passes; I did not verify that its 61 assertions test what their labels say |
| Hashes | not recomputed | every sha256 in this document is quoted, never verified |

### 2.3 The single largest limitation

**[I]** I am reading the **method's own records about itself**. The lane is unusually honest in those records — it documents its own leaks, its own stale claims, and its own failed predictions — but a review of a self-reporting system inherits whatever that system failed to notice. The planted-gap tests exist precisely because this is true, and they show that 40–50 % of planted defects can go unnoticed. **My findings should be treated as a candidate set, not a verdict.**

---

## 3. Current workflow and bottleneck analysis

### 3.1 The workflow as v1.4 actually specifies it

```
index order, allowed list only
  └─ WINDOW: next 5 sources (+ at most 3 added by logged search)          [R §3 L81]
       └─ reviewer: blind inventory (10–15 lines/source) → hand back      [R RI L34–48]
       └─ then completeness (COVERED/MISSING/…) → validity attacks        [R RI L50–93]
       └─ settlement: every finding dispositioned + "resolved when"       [R §6 L113–120]
            + R1 register homes, R2 undefined terms, R3 inbound citations,
              quote check                                                  [R §2.8 L69–73]
  └─ every 25 sources: CONSOLIDATION                                      [R §12 L145]
       └─ 2 reviewers on different models + integration review
       └─ concept verdicts + rotated chapter in full + coverage
  └─ every second consolidation: PLANTED-GAP TEST (3–4 plants, frozen copy) [R §12 L151]
  └─ THEORY TURNS when a question is ready (≤8 sources, 2 reviewers)       [R §12b L155–161]
  └─ WHOLE-BOOK REVIEW at milestones                                       [R §12c L163–166]
```

**[I]** The loop is **verification-first and additive**: each source can add claims, distinctions and sections; each review removes errors; nothing in the loop *reduces* the theory. The only reduction mechanisms are (a) a settlement that narrows or withdraws a claim, and (b) a theory turn that derives a result which makes others redundant. (b) has happened (turns 1–8), but it is triggered by a question becoming "ready", not scheduled.

### 3.2 What is measured, and what is not

| Stage | Measured today? | Where |
|---|---|---|
| Sources read, lines, depth | **yes** | read log; `close_source.py --position` |
| Blind inventory produced | yes (saved file) | review folders |
| Findings per window, by severity | **yes** | settlements (`[R §10 L136]`) |
| Reviewer harness time | **yes** | settlements (e.g. 3,970.4 s) |
| Professor's time by stage (reading / drafting / settlement / rework) | **no** — stated as unknown in the proposal itself | — |
| Time from review receipt to settlement | **no** | — |
| R3 lines per accepted correction | no (the lists are produced; not counted) | — |
| Defects discovered later in work that had passed review | **required** (`RI` L150) and recorded per settlement, **but never aggregated** | settlements, e.g. C29, TURN8, C58 |
| Questions resolved per consolidation | **no** | — |
| Deliverable status change | per consolidation, in prose | `BOOK-OUTLINE.md` |

**[V]** The "running measure" is a rule (REVIEWER-INSTRUCTIONS L150) and it is honoured inside individual settlements — C7 records "1 defect found in work that had passed a check"; TURN8 records "none"; C58 records one. **[I]** No file aggregates it, so the one number that would measure **review false-negative rate on natural (non-planted) defects** does not exist.

### 3.3 The novelty question — and why the obvious reading is wrong

**[V]** Using the register's own `sources` column (the minimum S-ID per concept), and joining it to the read log, novelty per 25 sources collapses after S0150:

| Bin | Sources read | Of which novel |
|---|---|---|
| S0001–S0150 | 145 | 90 (0.62) |
| S0151–S0302 | 144 | 4 (0.03) |

**[V] But the composition changes at the same time.** Classifying by path:

| Source kind | Read | Novel | Rate |
|---|---|---|---|
| `docs/knowledgeos/reviews/` | 126 | 53 | **0.42** |
| `docs/knowledgeos/architecture/` | 34 | 8 | 0.24 |
| `docs/knowledgeos/brainstorming/` | 76 | 1 | **0.01** |

**[V]** Brainstorming sources run S0098–S0297, and they dominate the S0151+ bins (e.g. 18 of 19 sources in S0151–S0175). **[V]** Within kind, the decline is still visible for reviews — 0.56 and 0.55 in S0001–S0100 and S0101–S0200, then **0.06** in S0201–S0302 — so it is not purely composition. **[V]** The register's `max` source distribution is also uninformative about extension: 66 concepts list no source later than S0025, and only 6 concepts are extended by a source in S0151–S0302, while 40 cite later out-of-order topic reads (S0701+). The register has no "last confirmed by" field.

**[I] Three readings fit the same data and I cannot separate them:**

1. **Corpus redundancy** — the brainstorming documents restate architecture conclusions already registered, so nothing new is minted.
2. **Attribution artefact** — concepts are attributed to their *supporting* sources, not the session where the idea first appeared, so brainstorming yield is structurally undercounted.
3. **Method blind spot** — the book's extraction is tuned to rules and distinctions in architecture documents and does not convert brainstorming material into candidate concepts.

**[I] What the evidence does *not* support:** that the remaining corpus can be skipped, and that the method has saturated. Both would be over-readings. The right conclusion is narrower: **novelty per source is strongly a function of source kind, and the current plan reads the low-yield kind at full depth because index order says so.** That is a fact about the corpus's shape, not a licence to stop covering it.

**[H]** A stratified measurement — novelty and *extension* rate by kind, over a random sample of the unread 89 % — would decide which of the three readings holds. It is not run.

### 3.4 Bottleneck hypotheses, with the evidence for and against each

| # | Hypothesis | For | Against | Status |
|---|---|---|---|---|
| **B1** | Reading is the bottleneck | 50–70 sources/day is the stated aim | 25 sources and 10,606 lines were closed in 76 minutes on 2026-10-30; review is 8–13 min/window | **[U] probably false**, but unmeasured |
| **B2** | Drafting and settlement are the bottleneck | the proposal's own inference; the 2026-10-31 day closed 50 sources, the 2026-10-30 day 25 | no time records exist | **[H]** |
| **B3** | The theory lacks a production stage | `BOOK-OUTLINE` L44; BLOCKERS five questions; STATUS one-sentence verdict | theory turns 1–8 did produce derived results | **[V] partially true**: turns produce results, but no turn is scheduled to produce the *chain* or the minimality decision |
| **B4** | Review/consolidation ceremonies cost calendar time | 111 review folders; 66 minutes for the last consolidation review; 76 decision addenda | review time is a small fraction of a day | **[U]** |

**[I]** The ordering matters: if B1 is false — and the reading-log timestamps suggest it is — then the entire faster-reading proposal addresses a non-binding constraint. The cheapest possible decision is to measure first.

---

## 4. Reassessment of previous findings

Each row answers the nine questions in the brief in compressed form: what the problem is, the evidence, its status, whether v1.4 addresses it, its cause, the smallest intervention, the risk, how to test it, and who decides. The longer treatment is in §5.

| ID | Original claim | Evidence (verified now) | Assessment | Addressed by v1.4? | Cause | Disposition |
|---|---|---|---|---|---|---|
| **F1** | The loop has no generative/compressive operator | `BOOK-OUTLINE` L44 ("derives nothing from primitives; minimality never tested"); BLOCKERS five questions with 36/31/29/28/26 citing sections; STATUS verdict | **confirmed, narrowed** | **Partly.** §12b provides theory turns; §1 L45 ranks a critical foundational problem first. What is absent is a turn whose *deliverable is the chain/minimality*, not a turn as such | prioritisation, not rule absence | run the theory turn under §12b; no rule change |
| **F2** | Minimality and existence are ill-posed as specified | `BOOK-OUTLINE` L179 ("the load-bearing undefined object" = required capabilities, T9-01); `RESEARCH-PURPOSE` L46; §9.1 C5 (mechanism- vs primitive-minimality; needs interface A) | **confirmed** | Partly — the book states the problem, does not resolve it | unresolved research question | derive `Req` first; state `A`; restate minimality relative to `(A, Req)` |
| **F3** | Calibration is underpowered | 4/8, 3/5, 2/4, 4/4; Wilson CIs all overlap; class mix changes; S0036 test partly compromised; S0100 commit-subject leak | **confirmed** | **Yes** — §4 L91 already forbids rate claims without a random sample; the result files say "counts only" | test design (denominator and class mix) | do **not** build a 40–60 bank now; run a minimal paired test only if a decision turns on it (§8) |
| **F4** | "Findings per window ≈11" does not establish a capacity ceiling | proposal §1 [V] (2–5 sources, 7–13 findings) and its own concession at §2 L43 that the bundle cannot attribute causation | **confirmed as an inference, not a measurement** | Yes — the proposal states the confound itself | study design | pre-register a regression of findings on sources, if the window test is ever run |
| **F5** | The corpus is front-loaded; reading it all in index order is a misallocation | §3.3 above: novelty 0.62 → 0.03, **but** composition confounded (brainstorming 0.01 vs reviews 0.42); register has no extension field | **partly supported; the strong form is rejected** | No | measurement artefact + corpus shape | do **not** recommend skipping; measure by kind; add extension tracking |
| **F6** | The unread corpus is a held-out set being spent without a prediction register | §12b L159 already requires "predictions recorded before index order reaches them" for branch consults; no scoring aggregate exists | **partly addressed; narrowed** | **Yes, for branch consults only** | the practice is not generalised to all theory turns | extend the existing practice; no new mechanism |
| **F7** | The register is a lexicon, not a ubiquitous language | BLOCKERS #1: "two attribute systems stand side by side — Ch. 1's labels; Ch. 3's six dimensions — with three values in §3.1 and four in §1.3"; 96 INVALID of 265 | **confirmed** | No | the register is a discovery log by design; no kernel-language artifact exists | propose a kernel-language artifact, projected from the register (§5 I-3) |
| **F8** | Strategic design precedes the primitive set; re-derive the context map | Ch. 10 turns 1–5 settled; Architect mandate rev 4; no new evidence of invalidity | **rejected** | Yes — `BOOK-OUTLINE`/Ch. 10 record the stability level S0 | my earlier recommendation was not evidence-based | **withdrawn.** Do not reopen; no evidence justifies it |
| **F9** | Two different three-valued logics used without composition rules | Checked §2.8 (strong Kleene over dimensions) and §9.13 (strong Kleene over acts; §9.13.3); §8.36 L84 explicitly separates the four-valued *content* lens from the three-valued *authority* value | **rejected** | Yes, at §8.36 | my earlier finding was based on labels, not text | **withdrawn.** One residual question remains untested: what happens when applicability is U *and* standing is U — I did not find a rule for the composition, and did not search exhaustively |
| **F10** | Governance growth; a stale premise inside the methodology | **Verified defect:** `METHOD-VERSIONS` L52–54 says the workspace "is not under version control: git reports it as untracked", while `git ls-files` returns **2,831** tracked files and 533 commits — and the file was committed on 2026-10-19 and last on 2026-10-24. **Volume:** 83 register rows, 76 decision addenda, 111 review folders — real, but "overhead is a measurable problem" is **[U]** | **stale claim confirmed; "overhead is a problem" unverified** | No (the defect is in the methodology's own file) | a premise not re-checked after it changed | correct by an appended entry (append-only register); propose a milestone premise re-check (§5 I-7) |
| **F11** | The domain decision determines `Req`, and `Req` determines minimality | `KERNEL-SCOPE-REVIEW` §2 L28: Ch. 0 = engineering; `RESEARCH-PURPOSE` L14 = organisational; **unresolved as a document conflict**. The Principal direction now recorded: engineering is the current domain | **confirmed as a logical dependency; the document conflict is a recorded, unresolved governance item** | Partly — the Principal's direction is recorded; the documents still differ | governance, not method | state the domain as an explicit assumption in the theory turn; do not treat the conflict as resolved |

**Score: 5 confirmed (F1, F2, F3, F7, F11), 1 partly supported with the strong form rejected (F5), 2 narrowed (F6, F10), 2 rejected and withdrawn (F8, F9), 1 confirmed as inference only (F4).**

---

## 5. Proposed improvements

Each is the **smallest** intervention that addresses a demonstrated problem. None is in force. "Authority" names who could approve it; "methodology change?" states whether a rule text would have to move.

### I-1 — Measure the time split before changing anything

| Field | Content |
|---|---|
| **Business problem** | Throughput proposals are being designed against an unmeasured bottleneck |
| **Proposed change** | For three consecutive windows and one consolidation, record active working time in four fixed categories: reading, drafting, settlement (including reviews/e-mail/tool runs), rework (corrections to work already settled). Active time, not elapsed; tool waits recorded separately |
| **Expected benefit** | Decides whether B1, B2 or B4 is the binding constraint; makes every later throughput claim interpretable; costs minutes per window |
| **Supporting evidence** | `PROPOSAL…` §1 [I] 1 and criterion S8 (L68); `C58/STEP8-AND-CHECKS.md` L11 |
| **Effort** | ~3 windows × a few minutes of bookkeeping; no new document (a columns block in each settlement) |
| **Risk** | Self-reported timing is approximate; categories can be gamed or drift. Mitigation: fixed definitions, recorded once per window, and the existing `[R]` §10 measures block extended |
| **Dependencies** | none |
| **Acceptance criteria** | one consolidated table with four category totals for ≥3 windows and ≥1 consolidation; the category with the largest share is named |
| **Authority** | Professor (records it); **Principal** if it is to be a standing measure under §10 |
| **Methodology change?** | No if run as a pilot; yes (a §10 measure addition) if made permanent |

### I-2 — Run the theory-construction turn on the requirement set

| Field | Content |
|---|---|
| **Business problem** | The two top deliverables (primitives; laws as one chain) have no production step; `Req` is "the load-bearing undefined object" |
| **Proposed change** | One §12b theory turn whose deliverable is the requirement set `Req` (T9-01) plus the interface/granularity assumption `A`, expressed in the existing §9.1 formal vocabulary; then restate minimality relative to `(A, Req)` and enumerate the frontier on the existing toy model. Full structure in §6 |
| **Expected benefit** | Unblocks deliverable 5 (existence/minimality) and de-risks deliverables 1–2; every one of the five BLOCKERS questions depends on the attribute/requirement vocabulary |
| **Supporting evidence** | `BOOK-OUTLINE` L44, L179; BLOCKERS rows 1–5; `RESEARCH-PURPOSE` L41 (existence is relative to required capabilities) |
| **Effort** | one theory turn (~1–2 days at the observed pace); uses existing tools and sections |
| **Risk** | (a) `Req` may be under-determined by the corpus, producing a Principal question instead of a result — that is a legitimate outcome and should be marked, not forced; (b) it interrupts index-order reading, which §3 treats as the coverage line — mitigated because §1 L45 ranks a critical foundational problem above coverage, and the reading position does not move |
| **Dependencies** | domain direction (engineering) held as an explicit assumption; BLOCKERS ranking |
| **Acceptance criteria** | a written `Req` with each capability traced to a corpus source and passing the three boundary tests; a written `A`; a minimality statement that names what it is relative to; the model run recorded with its output |
| **Authority** | **Professor** may start it under §12b; **Principal** may direct the sequence |
| **Methodology change?** | **No** — it is a theory turn under the existing rule |

### I-3 — Give the register an extension field

| Field | Content |
|---|---|
| **Business problem** | The register cannot distinguish "no new concept" from "concept extended but not re-registered", so progress and novelty cannot be measured |
| **Proposed change** | Add one column, `last_confirmed_source` (the highest S-ID at which the concept was checked against a source), populated on each check; alternatively record it in `checked_on` as `date/S-ID` |
| **Expected benefit** | Makes extension rate measurable; separates corpus redundancy from method saturation (the §3.3 ambiguity) |
| **Supporting evidence** | §3.3 analysis: 66 concepts have no source later than S0025; only 124 of 255 are multi-source; `checked_on` is a date, not a source |
| **Effort** | one schema change; back-fill from the existing `sources` column; minutes per concept for new checks |
| **Risk** | back-fill is approximate and could manufacture false precision — mark back-filled values as inferred from `sources` |
| **Dependencies** | none |
| **Acceptance criteria** | the column exists for all new checks; a one-page note states what it does and does not prove |
| **Authority** | Professor (owns the register) |
| **Methodology change?** | No (register schema is the Professor's; §4 L96 governs homes, not columns) |

### I-4 — Score the predictions the rule already requires

| Field | Content |
|---|---|
| **Business problem** | Predictions are recorded for branch consults but never scored, so there is no out-of-sample accuracy measure anywhere in the programme |
| **Proposed change** | For each theory turn (not only branch consults), record 3–5 falsifiable claims about sources not yet reached, in a fixed form; on arrival, score each **hit / miss / ambiguous / not yet testable**, with a one-line reason. Definitions in §8 |
| **Expected benefit** | A genuine progress metric that cannot be achieved by writing more prose; catches hindsight in a sequential, self-authored corpus |
| **Supporting evidence** | §12b L159; no scored prediction exists in any settlement or result file I inspected |
| **Effort** | minutes per turn |
| **Risk** | (a) predictions can be made trivially safe — require specificity (a named source range, a stated claim, a falsifier); (b) source lineage can make a "hit" a copy, not a prediction — the lineage rule (§8.31) applies |
| **Dependencies** | I-2 must run for there to be a theory worth predicting from |
| **Acceptance criteria** | ≥10 predictions recorded and scored, with the lineage of each scored source noted |
| **Authority** | Professor (practice); Principal if made permanent |
| **Methodology change?** | No as practice; yes if written into §12b |

### I-5 — Aggregate review quality from reports that already exist

| Field | Content |
|---|---|
| **Business problem** | Detection, false positives and late-discovered defects are recorded per settlement and never aggregated; the only quality numbers available are from 3–4 planted items |
| **Proposed change** | A single running table built from the existing 111 review folders and settlements: findings by severity; confirmed / modified / rejected at integration; defects discovered later in work that had passed review; sections read in full vs not; per window and per consolidation |
| **Expected benefit** | Turns 94 settlements of existing evidence into a defensible quality series; costs no new reviews; directly answers "is review getting weaker?" |
| **Supporting evidence** | `RI` L150 requires the running measure; C7, C29, TURN8, C58 record it; no aggregate found |
| **Effort** | 1–2 days of reading existing files; no research time |
| **Risk** | the corpus of settlements is heterogeneous (formats changed at v1.3/v1.4) — record the era |
| **Dependencies** | none |
| **Acceptance criteria** | one table, one row per window/consolidation, with the columns above and gaps marked "not recorded" rather than zero |
| **Authority** | Governance Engineer (its duty is to evaluate) |
| **Methodology change?** | No |

### I-6 — A minimal paired planted test, only if a decision turns on it

| Field | Content |
|---|---|
| **Business problem** | The four published tests cannot discriminate: CIs overlap and class mix changes |
| **Proposed change** | 8–12 plants, balanced across **two** classes only (the two that are missed repeatedly: an ordinary word used as a defined term; a removed claim read as a pointer error), run blind on a frozen copy, scored by class; the same plant set reused at the next test so that change is measurable |
| **Expected benefit** | For the first time, a comparison with a fixed denominator; per-class detection with an interval |
| **Supporting evidence** | S0100 §3 ("undefined terms … missed in every test"); S0200 Q1/Q2; S0252 Q4 (quote blind spot) |
| **Effort** | about one consolidation's preparation time |
| **Risk** | reusing plants leaks them over time — seal the key and rotate half the set each use; 8–12 is still small |
| **Dependencies** | I-5 (so the natural-defect baseline exists) |
| **Acceptance criteria** | per-class counts with CIs; a stated decision rule fixed **before** the test |
| **Authority** | Principal approves the test; Governance Engineer prepares; Rule Reviewer scores |
| **Methodology change?** | No — it runs inside the existing §12 planted-gap slot |

### I-7 — Re-check methodology premises at milestones; correct the stale one by appending

| Field | Content |
|---|---|
| **Business problem** | `METHOD-VERSIONS` L52–54 asserts the workspace is untracked; it has been tracked since 2026-10-19 (2,831 files, 533 commits). The methodology carries a false premise about itself |
| **Proposed change** | (a) Append a new register entry recording that the limitation is satisfied and the paragraph is stale — **entries are appended, never rewritten** (`METHOD-VERSIONS` L3), so this is the only admissible correction; (b) at each consolidation, check every premise a governance document asserts about repository state |
| **Expected benefit** | Removes a false premise; closes the same defect class the method hunts in the book (§8.31 stale citations) inside the methodology itself |
| **Supporting evidence** | `git ls-files` = 2,831; `git log --oneline -- .` = 533; first lane commit `8f0422ffa` 2026-10-19 |
| **Effort** | minutes (a); an hour per milestone (b) |
| **Risk** | scope creep into governance editing — constrain to "premises about repository state", not judgment or policy |
| **Dependencies** | Principal's approval of the appended entry |
| **Acceptance criteria** | entry appended (not editing the original); one premise list checked at the next consolidation |
| **Authority** | **Principal** (the register's decider is the Principal: `METHOD-VERSIONS` L3) |
| **Methodology change?** | No rule change; a record correction |

---

## 6. Theory-building and formalization proposal

This is the structure for **I-2**, the one theory-construction turn. It uses only existing artifacts; it creates no parallel framework.

### 6.1 Why this question, and not another

**[V]** The BLOCKERS file already ranks the candidates by how many sections cite them. **[I] The argument for taking #1 first is not its rank alone but its position in the dependency order:** `Req` is what minimality is relative to (`RESEARCH-PURPOSE` L41: "existence is relative to them: with invariant preservation alone, the empty set of mechanisms is minimal and the existence question is trivially 'yes'"). A minimality result derived before `Req` is fixed is a result about nothing. And `BOOK-OUTLINE` L179 says six claims already defer to it.

### 6.2 The bounded template

| Field | Content for this turn | Existing artifact to use (no new framework) |
|---|---|---|
| **Research question** | What is the kernel's required capability set `Req`, and at what interface granularity `A` is a minimal kernel well-posed? | BLOCKERS #1; `RESEARCH-PURPOSE` L41; §9.1 (T9-03) |
| **Candidate vocabulary** | the kernel/application terms only, each with its CON-ID and status | `CONCEPT-REGISTER.tsv` (filtered to kernel marks); BLOCKERS #1's two attribute systems |
| **Domain and interface assumptions** | domain = engineering knowledge **[D]** (Principal direction), held as an explicit assumption, not claimed resolved; `A` = the mechanism granularity and observable interface, stated separately | `KERNEL-SCOPE-REVIEW` §7; §9.1 C5 |
| **Requirements** | `Req` as reachability of required capabilities; each capability stated, sourced, and passed through the three boundary questions | v1.4 §12b (deliverable follows the question); REVIEWER-INSTRUCTIONS L85 |
| **Candidate primitives** | the nine candidates already listed, with their current status | `BOOK-OUTLINE` §1 (L9–L20) |
| **Rules, axioms, laws** | the existing laws of Ch. 1–2, restated as a numbered axiom set with homes | Ch. 1–2 sections; `THEORY-MAP` |
| **Derivations** | the missing chain, each step with a justification cell | `proof_step_check.py` (supports the table, never replaces it — `RI` L89) |
| **Counterexamples and boundary cases** | trivial cases required by §4 L94 (empty mechanism set, refuse-all, unchanged state), plus a countermodel per axiom | `ce_model.py`; §9.1 C4 |
| **Independence / necessity tests** | for each axiom: a model in which it alone fails while the rest hold | `RI` L86; `ce_model.py` checks 1–61 |
| **Minimality analysis** | the minimality **frontier** relative to `(A, Req)`, not a uniqueness claim | §9.1; the toy model table (C4) |
| **Unresolved questions** | anything `Req` cannot decide from the corpus, routed as a Principal question in one sentence with options | BLOCKERS format |
| **Falsifiable predictions** | 3–5 claims about sources not yet read | v1.4 §12b L159 |
| **Evidence that would change the conclusion** | for each capability in `Req`: the corpus case that would remove it; for `A`: the granularity at which the frontier collapses | — |

### 6.3 What can be claimed at each strength

| Kind | What it can support | What it cannot | Where it lives |
|---|---|---|---|
| **Mathematical proof** | a general result within stated premises | that the premises match KnowledgeOS | prose + proof-step table |
| **Bounded model check** | that no counterexample exists **within the modelled bound** | generalisation beyond the bound | `ce_model.py` output, kept as a run file |
| **Empirical (corpus) evidence** | a claim about these sources | a rate, without a random sample (v1.4 §4 L91) | section + line anchors |
| **Conjecture (Hyp)** | a research direction | any status above PROVISIONAL | register, marked |

**[Ded] A minimality claim needs three things and currently has one.** Premises: (i) the family of admissible mechanism sets is a set of subsets of a fixed pool; (ii) `Req` is fixed; (iii) `A` is fixed. Under (i)–(iii):

- **(a) Minimal kernels form an antichain.** By definition: if `M₁ ⊂ M₂` and both satisfy `Kernel`, `M₂` is not minimal. This holds for any predicate, so it carries no information about KnowledgeOS. **[Ded], trivial.**
- **(b) A single minimal kernel is found greedily** — start from any satisfying set and delete mechanisms while `Kernel` still holds — in at most `|pool|` predicate evaluations. **[Ded]**, and this is what the existing model already does.
- **(c) Uniqueness is not witnessed by (b).** One witness cannot rule out a second minimal kernel. Absent a structure theorem (for example a matroid-like exchange property, or a proof that the predicate is upward-closed so that the greedy result is the least fixed point), uniqueness requires coverage of the remaining subsets — worst case exponential in the pool. **[Ded]** under the stated premises; **[I]** the exponential statement is a worst-case bound, not a claim about this book's pool.
- **(d) Under the guard reading, `Req` is not monotone**, so (b)–(c) do not apply and even the single-check existence result of §9.1 C3 fails. **[V]** the book says this itself.

**[P]** Therefore the deliverable should be **the frontier plus a selection criterion** (for example: minimum cardinality, then the coarsest `A`), and "the unique minimal kernel" should be reported as **not established** unless a structure theorem is produced. This converts an open-ended question into one with a decidable form.

### 6.4 On tools: what would justify one, and what would not

**[I]** The precise question a general model finder (Alloy/Z3) would answer and `ce_model.py` cannot is: *"Is there a finite model of the axiom set with property P, over a signature I did not hand-code?"* `ce_model.py` answers only over its own encoded semantics, which is why §9.1's minimality result is carefully scoped to a toy model. **[P]** Do **not** add a tool now. The condition that would justify one is stated: when `A` and `Req` are fixed and the axioms are written as a parameterised signature rather than as code. Until then, a new solver would answer a question the theory does not yet pose.

---

## 7. Measurement and validation plan

Minimal plan; every value not yet measured is marked **[U]**. No composite score is proposed — each block answers a different question and combining them would hide exactly what the programme needs to see.

### 7.1 Research throughput

| Measure | Source | Frequency | Baseline |
|---|---|---|---|
| Sources read in index order per day | read log | daily | **[V]** 25 (2026-10-30), 50 (2026-10-31) |
| Lines read per day | read log (`lines` column) | daily | **[V]** 10,606 / 22,153 |
| Mean source size | read log | daily | **[V]** 424 / 443 lines |
| Windows settled per day | settlements | daily | **[V]** 7 and 9 (proposal §1) |
| Active hours by stage | **new** (I-1) | per window | **[U]** |

**[P]** Use **lines per active hour**, not sources per day, as the throughput unit. Sources per day is confounded by source size (observed daily means range 122–1,308 lines).

### 7.2 Theory progress

| Measure | Source | Frequency | Baseline |
|---|---|---|---|
| Deliverable status (1–5) | `BOOK-OUTLINE` | per consolidation | **[V]** 0 of 5 met; 5 "open/provisional" |
| Open questions resolved / opened | BLOCKERS + chapter TODOs | per consolidation | **[V]** 168 of 186 TODO rows unresolved |
| Status changes of concepts (V↔P↔I) | register | per consolidation | **[V]** 57/112/96 |
| Axioms/laws with a completed derivation | sections | per theory turn | **[U]** |
| Forks closed / opened | `THEORY-MAP` §7 | per consolidation | **[U]** |
| Predictions scored (hit/miss/ambiguous) | **new** (I-4) | per theory turn | **[U]** |
| Minimality frontier size | **new** (§6) | after I-2 | **[U]** |

### 7.3 Review quality

| Measure | Source | Frequency | Baseline |
|---|---|---|---|
| Findings by severity | settlements | per window | **[V]** ~11/window; 0 critical |
| Confirmed / modified / rejected at integration | integration reports | per consolidation | **[V]** 44/10/0 at S0253–S0277 |
| Defects discovered later in work that passed review | settlements (required by `RI` L150) | per settlement, **aggregated quarterly** | **[V]** recorded; **[U]** never aggregated |
| Planted-gap detection by class | result files | per second consolidation | **[V]** 4/8, 3/5, 2/4, 4/4 |
| Section coverage (read in full / not) | consolidation reports | per consolidation | **[V]** Ch. 8 gap at S0200 |

### 7.4 Rework and operational overhead

| Measure | Source | Frequency | Baseline |
|---|---|---|---|
| Corrections that touch an already-settled section | settlements + git | per settlement | **[U]** |
| Self-caused regressions | settlements | per settlement | **[V]** recorded individually (e.g. C58); never aggregated |
| R3 lines per accepted correction | settlements | per settlement | **[U]** |
| Governance effort (decision addenda, rule entries, records) | decision record, `METHOD-VERSIONS` | per 25 sources | **[V]** 76 addenda; 83 register rows; **[I]** effort in hours **[U]** |

---

## 8. Controlled experiments

**Only one is recommended now.** The others are conditional, and the third is recommended **against** at this time.

### E1 — Time-split observation (recommended now)

| Item | Content |
|---|---|
| Type | observational, not a controlled experiment |
| Variables | none changed; four time categories recorded for 3 windows and 1 consolidation |
| Controls | the same category definitions across all records; active vs elapsed kept apart |
| Duration / stopping | 3 windows + 1 consolidation, or 5 working days |
| Success | a table exists and the largest category is identified; no pass/fail |
| Rollback | none needed (no rule or workflow change) |
| Decision authority | Professor (records); Principal (if made permanent) |
| Why it first | It decides whether the faster-reading experiment is aimed at the right constraint |

### E2 — Paired plant test (conditional on a review-quality decision)

| Item | Content |
|---|---|
| Type | controlled, paired within reviewer, blind |
| Variables | plant class (2 levels); everything else fixed |
| Controls | frozen copy; sealed key; neutral commit subjects; blind file lists; the same reviewer models; the same 8–12 plants reused across the two runs |
| Duration / stopping | one consolidation cycle; stop if a critical finding escapes or a key leaks |
| Success | per-class detection recorded with an interval; the pre-declared decision rule is applied |
| Rollback | revert to the existing 3–4-plant test |
| Decision authority | Principal approves; Rule Reviewer scores |

### E3 — Faster-reading bundle test (recommended **against** now)

| Item | Content |
|---|---|
| The proposal | C1 window 6–8; C2 consolidation every 50; C3 unchanged; C4 split above ~4,000 lines (`PROPOSAL…` §2) |
| Why defer | **[V]** S1 (L62) sets success at ≥1.5 × baseline, "about 40 a day or more". Under **unchanged** v1.4 rules the log shows 50 sources on 2026-10-31 and 25 on 2026-10-30. The threshold is inside the current day-to-day variance, so a result cannot be attributed to the bundle without controlling session length, source size and consolidation days. **[V]** the proposal concedes the attribution problem for C1–C4 as a bundle (§2 L43) |
| What would make it interpretable | (i) E1 shows reading is the binding constraint; (ii) success redefined as **lines per active hour**; (iii) one variable changed at a time (C1 only), or a stepped design; (iv) the findings–sources regression pre-registered (§4 F4) |
| Rollback | already specified in the proposal §5; adequate |
| Decision authority | **Principal** |

**[I]** Deferring is not rejecting. The proposal is well-built; it is being aimed at a target that has not been measured. If E1 shows reading dominates, the test becomes worth running with the two amendments above.

---

## 9. Rulebook version recommendation

**Recommendation: keep v1.4 unchanged. Do not prepare v1.5 at this time.**

| Question | Assessment |
|---|---|
| Does the evidence warrant a rule change? | **No.** The highest-value action (I-2) is already authorised by §1 L45 and §12b. The second (I-1) needs at most a §10 measure addition. Neither requires a new version |
| Is there a demonstrated rule defect? | **No rule defect found.** One **record** defect (`METHOD-VERSIONS` L52–54) — a false premise about repository state, correctable by an appended entry |
| Does the reading test need an annex? | Only if it proceeds, and it should not proceed until E1 reports. The proposal's own mechanism (a dated test-cycle annex, not a v1.5) is the right shape if it does |
| Risk of changing now | **[V]** the lane's own history shows rule churn: 83 `METHOD-VERSIONS` rows and 76 decision addenda inside two weeks. **[I]** Every rule change re-opens settled practice and consumes attention that the theory does not get back |
| Risk of not changing | The five BLOCKERS questions continue to accumulate sections; nothing else |

**What would change this recommendation:** if E1 shows that settlement, not reading, dominates, and if settlement is dominated by rule-driven checks, then a **bounded clarification** (not a new version) to §2 step 8 or §6 would be justified — with the evidence attached. Not before.

---

## 10. Prioritized roadmap

Ranking combines expected value, evidence strength, effort, risk, dependency and urgency.

| Rank | Action | Owner | Effort | Evidence strength | Risk | Depends on | Authority class |
|---|---|---|---|---|---|---|---|
| 1 | **I-2** theory-construction turn on `Req` + `A` | Professor | 1–2 days | **high** (book's own admissions) | low–medium | domain assumption | **existing authority** (§1, §12b) |
| 2 | **I-1** time-split measurement | Professor | minutes × 3 windows | **high** (stated unknown) | low | none | existing (pilot); Principal for permanent |
| 3 | **I-5** aggregate review quality from existing reports | Governance Engineer | 1–2 days | high (data exists) | low | none | existing |
| 4 | **I-3** register extension field | Professor | small | medium | low | none | existing |
| 5 | **I-4** score predictions | Professor | minutes/turn | medium | low | I-2 | existing as practice |
| 6 | **I-7** correct the stale limitation; milestone premise check | Governance Engineer → Principal | minutes | **high** | low | Principal | **Principal decision** |
| 7 | **I-6** paired plant test | Governance Engineer | 1 cycle | medium | medium | I-5; a decision that needs it | **Principal decision** |
| — | **E3** faster-reading bundle | — | 100 sources | **weak** (confounded) | medium | E1 | **deferred** |
| — | Alloy/Z3 or a new contradiction checker | — | — | none | — | `A` and `Req` fixed | **deferred** |
| — | Re-opening strategic design (F8) | — | — | **none** | high | — | **rejected** |
| — | Rulebook v1.5 | — | — | none | medium | evidence not present | **rejected for now** |

**Actions possible under existing authority:** I-1 (as a pilot), I-2, I-3, I-4, I-5.
**Requiring a Principal decision:** I-6, I-7, making I-1 permanent, E2/E3, and any change to the rule text.
**Deferred or rejected:** E3 (deferred), Alloy/Z3 (deferred), strategic design redo (rejected), v1.5 (rejected for now), a 40–60-item plant bank (deferred — not the smallest informative experiment).

---

## 11. Principal decisions required

Presented one at a time, in the recommended sequence. **None is presumed taken.**

### Decision 1 — Which question does the next theory turn answer?

- **Question:** Should the next §12b theory turn be the requirement set and interface (`Req`, `A`, T9-01), or continue index-order reading to the next consolidation?
- **Why it matters:** `Req` gates deliverable 5 and titles the largest BLOCKERS item. Reading further adds coverage but cannot decide it.
- **Options:** (a) `Req` + `A` now; (b) a different BLOCKERS question (identity over versions; root act); (c) continue reading.
- **My recommendation:** (a).
- **Consequences:** deciding now delays coverage by 1–2 days and starts the only work that can produce the missing derivation chain. Deferring leaves the five questions accumulating — they already account for 150 section-citations.

### Decision 2 — Is the time split recorded, and for how long?

- **Question:** Approve recording active time in four categories for 3 windows and 1 consolidation, as a pilot.
- **Why it matters:** every throughput proposal, including the one already drafted, rests on an unmeasured premise.
- **Options:** (a) pilot, 3 windows; (b) pilot plus make it a §10 standing measure; (c) no.
- **My recommendation:** (a) now; revisit (b) when the pilot reports.
- **Consequences:** minutes of bookkeeping versus the risk of investing 100 sources in a test aimed at the wrong constraint.

### Decision 3 — Correct the stale methodology premise?

- **Question:** Approve appending a `METHOD-VERSIONS` entry recording that the "untracked workspace" limitation is stale (2,831 tracked files; 533 commits), and add a milestone premise check.
- **Why it matters:** the methodology currently carries a false statement about itself — the defect class it penalises in the book.
- **Options:** (a) append the entry; (b) append plus milestone checks; (c) leave it.
- **My recommendation:** (b), scoped strictly to premises about repository state.
- **Consequences:** a five-minute correction and an hour per milestone; leaving it keeps a known-false premise in the authority chain.

### Decision 4 — The faster-reading test: proceed, amend, or defer?

- **Question:** The proposal asks for approval of C1–C4 starting after the S0278–S0302 consolidation. Should it proceed as written, be amended, or wait?
- **Why it matters:** 100 sources of work and two consolidations hang on whether the measured gain would be attributable.
- **Options:** (a) proceed as written; (b) amend success to lines per active hour and run after E1; (c) defer until E1 reports; (d) reject.
- **My recommendation:** (c), with (b) as the form in which it later runs.
- **Consequences:** deferring delays a possibly real gain by one experiment; proceeding risks publishing a throughput claim that the data cannot support — in a programme whose central rule is "no claim stronger than its evidence".

---

## 12. Conclusion

**[I]** The methodology does not need to be faster at reading; it needs to be faster at **deciding**. Its integrity machinery is the best part of it and should not be touched. Its weakness is that the two deliverables everything else depends on — the requirement set and the derivation chain — are the only parts of the work with no scheduled production step, while 150 section-citations wait on five questions the Professor has already ranked.

**The next practical step is two things in parallel, both already permitted:** record where the time actually goes for three windows (I-1), and run one theory-construction turn on `Req` and `A` (I-2). If the turn produces a requirement set, the minimality question becomes decidable for the first time; if it produces a well-formed Principal question instead, that is also progress, and it is the kind the method is built to make safely.

Everything else in this document — the register column, the prediction scoring, the aggregation, the plant test, the reading test — should wait behind those two.

---

---

**Inspection record** (evidence for §2.2; not a section of the report — the report has the twelve sections required).

**Files opened in full:** `RULEBOOK-v1.4.md`, `REVIEWER-INSTRUCTIONS.md`, `CONSOLIDATION-BRIEF-TEMPLATE.md`, `PROPOSAL-FASTER-READING-TEST-CYCLE-20261031.md`, `KERNEL-SCOPE-REVIEW-20261031.md`, `STATUS-RESEARCH-TRACK-20261029.md`, `GOVERNANCE-ENGINEER-STATE.md`, `review/BLOCKERS-S0278-S0302.md`, the five planted-gap result files (`WHOLE-BOOK-S0100`, `CONSOLIDATION-S0036-S0065`, `CONSOLIDATION-S0131-S0150`, `CONSOLIDATION-S0182-S0200`, `CONSOLIDATION-S0226-S0252`).

**Files opened in part:** `METHOD-VERSIONS.md` (L1–60 and entry markers; not all 83 rows), `06-theory/RESEARCH-PURPOSE.md` (greps), `06-theory/manuscript/BOOK-OUTLINE.md` (greps), `06-theory/THEORY-MAP.md` (L1–5 and greps), `CONSOLIDATION-S0253-S0277/SETTLEMENT.md` (measures block and greps), `C58-S0274-S0277/STEP8-AND-CHECKS.md`, `02.08` and `09.13` (greps), `08.36` (one line).

**Commands run** (all read-only; no file written by them):

```
git ls-files . | wc -l                     → 2831
git log --oneline -- . | wc -l             → 533
git log --reverse --format=... -- .        → first lane commit 8f0422ffa 2026-10-19
awk/awk-scripts over CONCEPT-REGISTER.tsv  → 265 rows; 57 V / 112 P / 96 I
awk over READ-LOG-CHRONO-20261018.tsv      → per-day counts, lines, first/last timestamps
python3 -I (heredoc, register + read log)  → novelty by bin and by source kind; max-source distribution
python3 -I tmp/tools/close_source.py --position → S0302; 293 in order; gate 25/25 DUE
python3 -I tmp/tools/ce_model.py           → 61 lines with PASS, 0 with FAIL
python3 -I (heredoc, Wilson intervals)     → CI table quoted in §1
grep -c over METHOD-VERSIONS.md            → 83 table rows
grep -c over PRINCIPAL-DECISION-RECORD…    → 76 addenda
```

**Not run:** `helly_check.py`, `kernel_type_model.py`, `corpus_quote_check.py`, `register_home_check.py`, `undefined_terms.py`, `inbound_citations.py`, `dependency_graph.py`; no hash recomputation; no corpus source opened; no file under `00-access/` opened.

**Verification that this task's own constraints were met:** one new file created (`03-methodology/METHODOLOGY-EFFICIENCY-REVIEW-20261101.md`); no authoritative file modified; no rule applied; no research direction set. The lane's working-tree modification `tmp/corpus-reading/START-TIMES.tsv` predates this session and was not touched.
