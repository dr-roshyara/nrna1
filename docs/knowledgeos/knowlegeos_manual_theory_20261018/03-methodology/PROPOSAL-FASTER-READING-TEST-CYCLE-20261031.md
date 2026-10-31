# Proposal: a faster reading method, for one controlled test cycle (PROPOSED)

| Field | Value |
|---|---|
| Status | **PROPOSED.** Nothing in it is in force. Rulebook v1.4, the window rule, the consolidation schedule and the reading tool stay as they are until the Principal approves this proposal explicitly |
| Asked by | the Principal, 2026-10-31 ("Prepare a formal proposal for accelerating the Professor's corpus-reading process") |
| Prepared by | the Governance Engineer (session `57ddce41…`), 2026-10-31; mandate §8.9 (a rule change proposed on evidence) |
| Evidence base | the settlements of windows C46–C61 (measures tables); the reading tool's position (`close_source.py --position`, 2026-10-31: S0299, 22 of 25 since S0277); the planted-gap results S0100, S0150, S0200, S0252; Rulebook v1.4 §3, §6, §10, §12 |

**Labels.** **[V]** verified from the records · **[I]** interpretation · **[P]** proposal.

## 1. Baseline (the present method)

**[V] Windows C46–C61 (16 windows, S0226–S0287):**

| Measure | Value |
|---|---|
| Sources per window | 2–5 (mean about 3.8, counting the index range; non-allowed sources skipped) |
| Findings per window | 7–13 (mean about 11.1); high findings 2–7 (mean about 4.0); critical 0 |
| Findings per source | about 2.9; high about 1.1 |
| Reviewer harness time per window | step 1 about 94–148 s; steps 2–3 about 350–645 s; together about 8–13 minutes |
| Windows settled per day | 7 on 2026-10-30 (C46–C52, 27 sources in range), 9 on 2026-10-31 (C53–C61; with a consolidation) |
| Sources per working day, consolidations included | **about 25–30** |

**[I] What the baseline shows:**
1. **The review is not the time bottleneck:** about 10 minutes of reviewer time per window, against a day that settles 7–9 windows. The Professor's drafting and settlement take most of the day.
2. **Findings per window are nearly constant (about 11) whatever the window size (2–5 sources).** Either the windows have similar numbers of errors, or a review finds about as many as it has attention for. The second reading is the risk of larger windows: **more sources per review could mean the same number of findings and more errors missed.** The test must be able to tell these apart (§4).
3. **Planted-gap results improved after the S0200 rules:** S0100 4 of 8; S0150 3 of 5; S0200 2 of 4; **S0252 4 of 4** (blind).

**[V] The present rules (Rulebook v1.4):**
- §3: "A **window** is the next **5 sources** in index order, plus **at most 3 added sources**, each found by logged search … Ten in index order only for a coherent series of short documents, with the review split into two commissions."
- §12: "**A consolidation every 25 sources read in index order**, **enforced by the reading tool**"; "**A planted-gap test at every second consolidation**".

## 2. The changes to test

| # | Change | Present | Proposed for the test cycle |
|---|---|---|---|
| C1 | Window size | 5 in index order + at most 3 added by search (in practice 2–5) | **6–8 in index order**, plus at most 2 added by search; fewer only at a consolidation gate or the end of a series |
| C2 | Consolidation interval | every 25 sources | **every 50 sources** |
| C3 | Planted-gap test | every second consolidation (every 50 sources) | **unchanged rule** (every second consolidation), so **every 100 sources** — during the test cycle the second consolidation carries a test |
| C4 | Window review | one reviewer per window | one reviewer per window up to **about 4,000 source lines**; above that, **two commissions** splitting the sources (the rule's existing split for ten) |

**Not changed:** the core loop per source (§2), reading depth (§3: full, skim only for purely operational sources), the blind step-1 inventory, the quote check, R1–R3, the two-model consolidation review and its integration, the planted-gap procedure (mandate §8.3, §8.3a), settlement rules (§6.4), the allowed list and access rules (§7).

## 3. Quality safeguards (required during the test)

1. **Every source in a window is in the reviewer's blind step-1 inventory**, and the review report has a **per-source coverage table** (read in full / in part / not), so a larger window cannot leave a source unreviewed unnoticed.
2. **Every quotation the window cites is checked against its source by hand** where the quote-check tool cannot read the citation form (the S0252 finding B-12: the tool reads no "(L…)" citations).
3. **Attribution:** the reviewer checks, for every source, who is speaking (HPA, adviser, assistant, record), the class of error found at C51, C52 and C58.
4. **Removed or changed claims:** each settlement's change-log row names every claim restated, withdrawn or narrowed; the consolidation checks them against the window inventories (D3 in force).
5. **The consolidation's coverage plan** gives every new section a named full reader; with 50 sources the window sections roughly double, so the plan **adds reviewers rather than lengthening their reading** (template §1.2 step 2).
6. **No change to the review's independence**: the reviewer still sees no other reviewer's report.

## 4. Success criteria (fixed before the test starts)

*Step 0, before the first test window: the Governance Engineer fixes the baseline values below from the records and commits them with this proposal's approval.*

| # | Criterion | Measure | Success |
|---|---|---|---|
| S1 | Throughput | sources read in order and settled, per working day, consolidations included (git timestamps of settlements) | **at least 1.5 × baseline** (about 40 a day or more). 50–70 a day is **not** assumed; it is what the test measures |
| S2 | Serious findings in windows | high + critical findings **per source** in window reviews | not below **50 %** of baseline (about 0.55 a source) *unless* S3 and S4 also hold (then fewer findings means fewer errors, not shallower review) |
| S3 | What window reviews missed | findings the consolidation reviewers make against the **new window sections** (rotated chapters excluded), per 25 sources | **not more than 1.5 ×** the baseline of the last two consolidations (S0226–S0252, S0253–S0277) |
| S4 | Planted-error detection | the planted-gap test at the cycle's second consolidation, blind score | **at least 3 of 4** found, with correct stage-1 treatment for each found plant |
| S5 | Review workload | reviewer harness time per window; per source | per source **not below 60 %** of baseline (a sign of thinner review), and no window review over **30 minutes** without a split |
| S6 | Coverage | every source in every window in the blind inventory and the per-source coverage table | **100 %** |
| S7 | The Professor's own errors | self-caused regressions reported in settlements (e.g. C58: a follow-on edit undoing a settled result) | **not more than** in the 16 baseline windows, scaled to sources |

## 5. Rollback conditions (the test stops at once, and v1.4 is restored)

1. **A critical finding** in any window or consolidation that the window review should have caught (a source in that window).
2. **S4 below 3 of 4**, or any plant found but treated wrongly in a way that would have reached the working book.
3. **S6 below 100 %** twice (a source left unreviewed).
4. **S3 above 2 ×** baseline at the first consolidation of the cycle (the test does not wait for the second).
5. **Three self-caused regressions** by the Professor in the cycle.
6. **The Principal stops it**, at any time.

On rollback: v1.4's window and consolidation rules apply from the next window; the windows already read are **not** re-read, but the next consolidation re-checks them with the present rules; the result is reported as in §7.

## 6. Rulebook and other documents affected (for the test cycle only)

| Document | Change |
|---|---|
| `RULEBOOK-v1.4.md` §3 | the window: "the next **6–8 sources** in index order, plus at most 2 added …", for the test cycle; the split rule for long windows (C4) |
| `RULEBOOK-v1.4.md` §12 | "a consolidation every **50** sources" for the test cycle; the planted-gap rule unchanged |
| `RULEBOOK-v1.4.md` §10 | measures per window gain: source lines; the per-source coverage table; findings per source |
| `tmp/tools/close_source.py` | the gate count 25 → 50 (the Professor's tool; a configuration change, made by the Professor on approval) |
| `REVIEWER-INSTRUCTIONS.md` | window review: the per-source coverage table; the attribution check per source |
| `CONSOLIDATION-BRIEF-TEMPLATE.md` | §2.5 budget and §2.6 coverage plan for 50 sources (more reviewers) |
| `BOOK-OUTLINE.md` rotation table | consolidation names (S03xx) for the cycle |
| `MANDATE-GOVERNANCE-ENGINEER-PROPOSED.md` | none (§8.3 already says "every second consolidation") |

**How the change is made:** as a dated **test-cycle annex** to Rulebook v1.4 (not a v1.5), so that ending the test restores v1.4 by removing the annex. A permanent change, if the result supports it, would be a separate proposal.

## 7. The test cycle and its report

- **When:** after the consolidation now due (S0278–S0302; the reading tool shows 22 of 25 at S0299). **That consolidation is the second after S0252, so it carries a planted-gap test under the present rules** (the Governance Engineer prepares it). The test cycle starts with the first window after it settles.
- **Length:** **100 sources read in order** (about S0303–S0405, allowing for non-allowed sources), with two consolidations at 50 and 100; the second carries the planted-gap test.
- **Report (by the Governance Engineer, after the second consolidation is scored):** each criterion S1–S7 with its measured value and baseline; any rollback; costs (reviewer and Professor harness time); a recommendation to **retain**, **modify** or **reject** the faster method, each as a decision for the Principal.

## 8. Decisions for the Principal

1. **Approve the test cycle as proposed** (C1–C4, safeguards §3, criteria §4, rollback §5, the annex of §6), to start after consolidation S0278–S0302 settles; or change it; or reject it.
2. After the cycle: retain, modify or reject, on the report of §7.

**Approval words, if approved as written:** "approve the faster-reading test cycle as proposed".
