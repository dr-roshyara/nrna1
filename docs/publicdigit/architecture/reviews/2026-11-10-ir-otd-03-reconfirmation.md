# IR-OTD-03 — Re-confirmation Review

| | |
|---|---|
| **Subject** | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md`, §49–§51, **corrected revision** — SHA-256 `3df9944d74967f45d68362b7088beee7c818799eaff7297b117fdf7f3e1b0c0a` |
| **Re-runs** | IR-OTD-02 — Fresh Independent Confirmation (`6530eeef…`; verdict NOT CONFIRMED; C-1, C-2, C-3) |
| **Revision reviewed there** | `1292cfce…`, unchanged in the frozen bundle; this re-run is against the **live corrected document** `3df9944d…`, not the frozen copy |
| **Decision record** | `a7fcb16023ac26358d1c8d3d5b54b48baa9231dd8910b9c8d7bb18858ffa69f3` — unchanged |
| **Decisions in scope** | D-114 … D-122, **D-136**, **D-140**; D-106 §6 as quoted in the IR-OTD-02 commission |
| **Reviewer** | `deepseek-chat`, DeepSeek family, Claude Code harness — **not Claude**; author of IR-OTD-01, -C, -C2, -C3 and -02. The independence requirement is still unmet (see §6) |
| **Integrity** | Live document `3df9944d…`; decision record `a7fcb160…`; prior report `6530eeef…`; the frozen bundle still verifies its own `MANIFEST` at `1292cfce…`. I modified nothing during this review |
| **Status** | Re-confirmation only. No acceptance; nothing decided for the Principal |

**What this re-run is.** The Principal applied C-1, C-2 and C-3 to the live document after IR-OTD-02. I re-ran the same 20-item checklist against the corrected revision, re-checked the three corrections against their evidence, re-ran the §51.15 15-case test, and looked for regressions in the rest of the normative text. I treated the same text as normative: §49.4, §50.6, §51.4, §49.7, §49.8, §50.15, §51.3, §50.5, §51.5, and the scenarios; §51.11–§51.14 and every "Update" paragraph remain history.

---

## 1. The three corrections, checked

**C-1 — the Higher-body row and D-140. CONFIRMED as applied.** §51.5 line 1442 now reads: "**Y's allocation has no effect where they conflict; if it was adopted before the higher allocation, it applies again when the higher ends, with `PlacementReviewNeeded` (D-140). An allocation refused at adoption never applies.**" That matches A-7 (1422), the Remove row (1440), scenario 31 (1486), D-140 (decision record 956) and §51.15 cases 2, 8 and 10 (1651, 1657, 1659). The "ends" contradiction is gone.

**C-2 — B-8 in the normative invariant table. CONFIRMED as applied.** B-8 is now a §50.6 row at line 1217 with the R-5 text ("at most one placement-relevant recorded association per member per date; others are profile information; a second is refused unless it replaces the first from a date"); §50.7 (1226) includes B-8 in the per-person boundary; the note at 1221 states that B-7 is in §50.15. Scenario 23's normative reference (1478) now has a normative definition. No contradiction with B-3 (1213): B-3 limits belongings, B-8 limits the recorded association the rules use.

**C-3 — A-8's confirmation authority. CONFIRMED as applied (option (b)).** A-8 (1423) now defines the scheme's **competent authority** — the body or bodies of highest authority with an effective allocation in the scheme, or the written-rules designee — and only that authority moves `confirmedAtPosition`; a lower body's act does not move it while a higher allocation stands (D-119, D-136), except for a written-rules designee; equal-highest bodies need concurrence or the designee. The §51.5 Geography row (1443) points to A-8, and scenario 33 (1488) exercises the closed exploit: Y's unrelated adoption at a newer position is valid but does not move the frame, so the higher allocation still covers W. The trade-off stated in the report — a lower body's new allocation at a newer map position may be read at the confirmed position and match nobody until the competent authority confirms — is a consequence, not a contradiction. The equal-highest concurrence is a new governance mechanism but is consistent with D-119/D-136 and A-7.

---

## 2. Checklist: verdict per item

| # | Item | Verdict | Where I found it |
|---|---|---|---|
| 1 | B-3: one geographic member belonging by default; second refused unless the rules allow (D-118, D-122) | **CONFIRMED** | §50.6 **B-3**, 1213 |
| 2 | Authority (D-119) in §51.5 Adopt / Change / Remove / Higher-body rows, whatever the order; step 4; scenarios 25–26 | **CONTRADICTORY** | Adopt 1438, Change 1439, Remove 1440, Higher-body 1442, step 4 1408, scenarios 25–26 at 1480–1481. The D-140 part is now correct; the row still states D-119's prevalence **without D-119's and D-136's written-rules exception** — **R-1** |
| 3 | A-3: direct child, "unless the written rules provide otherwise"; A-2 consistent | **CONFIRMED** | A-3 1424; A-2 1421 |
| 4 | D-136: A-7 authority first, written-rules competence exception, unknown refused; A-1 defers; Adopt row; scenarios 27–31 | **CONFIRMED** | A-7 1422; A-1 1420; Adopt 1438; scenarios 27–31 at 1482–1486 |
| 5 | A-7: containment slot `(CONTAINMENT, unit, scheme)` (not G-4); designated administrative scheme; valid at d, as known at the confirmed position | **CONFIRMED** | A-7 1422 |
| 6 | A-1 over canonical identities; AllocationReviewNeeded on a merge; interim destination (scenario 12) | **CONFIRMED** | A-1 1420; scenario 12 1467 |
| 7 | S-9 (D-120) with dissolution carve-out and C2 read; §49.7 step 4 checks S-0 … S-5, S-7, S-9; B-7 rests on S-9 | **CONFIRMED** | S-9 935; §49.7 step 4 992; B-7 1339 |
| 8 | B-7 default "root as at admission", step 1 the same, scenarios §50.15 27–28 | **CONFIRMED** | B-7 1339/1342; step 1 1405; scenarios 27–28 at 1359–1360 |
| 9 | Re-admission: rule-derived re-applies; explicit placements and exceptions do not revive | **CONFIRMED** | §50.11 scenario 10, 1277 |
| 10 | Step 4: placement a floor, only an exception fixes; S-6 withdrawn; §49.10 scenario 13 without S-6 | **CONFIRMED** | Step 4 1408; S-6 936; scenario 13 1071 |
| 11 | Cross-tree continuity: one change set, one transaction; scheme moves or lapses | **CONFIRMED** | §50.1 **R-1**, 1125 |
| 12 | One Organisation knowledge boundary; refusal beyond `closedThrough` | **CONFIRMED** | §49.8 (0) 1018; §50.8 1246; refusal 1022 |
| 13 | Re-parenting that could alter a rule-derived result raises AllocationReviewNeeded | **CONFIRMED** | §49.6 980; §51.5 1443. Low note: A-1 (1420) still states the trigger more broadly than A-8 — see §4.2 |
| 14 | "Awaiting placement" only if unplaced; `PlacementReviewNeeded` otherwise | **CONFIRMED** | §51.1 1381; C-2 1429; closure 1445; fallback 1196; scenarios 5 and 15 at 1272 and 1470 |
| 15 | Closure impact is a report, not a write-time check | **CONFIRMED** | Closure 1445; scenario 19 1474 |
| 16 | Vocabulary: "allocation rule", "placement", "change-set placement" consistent | **CONFIRMED** | §50.3 1157; §50.4 1174; step 4 1408. Low note: "allocation act" at 1408 is a D-79 quotation |
| 17 | B-5 record-time assertion; B-1 residence wording; D-115 not binding; Q-1/Q-2 decided; scenario 23 historical; §51.2 note | **CONFIRMED** | B-5 1215; B-1 1211; D-115 1370; Q-1 1508; Q-2 1517; scenario 23 1478; §51.2 1394. B-8 is now normative at 1217 |
| 18 | D-140: A-7, the Remove row and scenario 31 (resumption with `PlacementReviewNeeded`; refused never applies) | **CONFIRMED** | A-7 1422; Remove 1440; scenario 31 1486 |
| 19 | A-8: no later Geography read; confirmation an organisational act; A-1, A-7, step 2, §49.6, §51.5, scenarios 12, 13, 32, 33 | **CONFIRMED** | A-8 1423; A-1 1420; A-7 1422; step 2 1406; §49.6 980; §51.5 1443; scenarios 12 1467, 13 1468, 32 1487, 33 1488 |
| 20 | §51.15: 15 cases; do the invariants, lifecycle and algorithm agree in each? | **CONTRADICTORY** | Cases 2, 6, 8, 10 at 1651–1659. Case 6 ("written-rules exception") marks "=" using the **Adopt** row; the **Higher-body** row, part of the same lifecycle table, gives the opposite answer — **R-1**. The other 14 cases agree |

**Tally: 18 CONFIRMED, 2 CONTRADICTORY, 0 PARTIAL, 0 MISSING among the 20 items.** B-8 is no longer MISSING. The two CONTRADICTORY items have one shared cause, R-1.

---

## 3. Residual R-1 — the Higher-body row still omits the D-119/D-136 written-rules exception

**Where.** §51.5 **Higher body overrides**, line 1442:

> Its decision on S's scope prevails from its date (D-115) and continues to prevail over any later decision of a lower body on the same scope (D-119); **Y's allocation has no effect where they conflict; if it was adopted before the higher allocation, it applies again when the higher ends, with `PlacementReviewNeeded` (D-140). An allocation refused at adoption never applies.**

**What the decisions say.** D-119 (decision record 904) ends: "**The organisation's written rules may provide otherwise.**" D-136 (938): a lower body cannot narrow or carve out "**unless the organisation's written rules expressly give the lower body that competence** (an identifiable provision)." A-7 (1422) and the Adopt row (1438) both carry that exception. Scenario 29 (1484) decides exactly the higher-then-lower case: "Same as 28, but the written rules expressly let province committees allocate wards within districts — **Takes effect**, the provision recorded as its basis; W members → D3 (specificity among authorised allocations)." §51.15 case 6 (1655) agrees: "Written-rules exception — A-7 with a recorded provision — **Adopt: accepted**, provision as basis — Lower's destination — = (29)".

**Why the row is still wrong.** The row states the higher's decision "continues to prevail over any later decision of a lower body on the same scope" and "Y's allocation has no effect where they conflict" without qualification. In scenario 29 the lower body's later decision is expressly authorised and **does** take effect; the higher's decision does **not** prevail for W. The row and A-7/Adopt/scenario 29 therefore give different answers for the same case. C-1 fixed the D-140 resumption but did not restore the exception; this is the same class of defect the IR-OTD-02 item 2 finding identified, and it survived that correction. It is a material contradiction in the lifecycle table, not a missing explanation.

**Impact.** An implementer applying the Higher-body row to the written-rules case would hold the higher allocation and give the lower none — refusing or ignoring an allocation that A-7, the Adopt row, scenario 29 and D-136 say takes effect. The design's own §51.15 case 6 marks the sources as agreeing, but only because its lifecycle column selects the Adopt row; the lifecycle table as a whole does not agree.

**What removes it** (text only; **no new Principal decision** — D-119 and D-136 already decide the exception): add the exception to the row, for example:

> Its decision on S's scope prevails from its date (D-115) and continues to prevail over any later decision of a lower body on the same scope (D-119), **except where the organisation's written rules provide otherwise — in particular, an identifiable provision giving the lower body competence over a contained area (D-119, D-136; A-7)**. **Unless so authorised, Y's allocation has no effect where they conflict; if it was adopted before the higher allocation and is not so authorised, it applies again when the higher ends, with `PlacementReviewNeeded` (D-140). An allocation refused at adoption never applies.**

After that edit, item 2, item 20 and scenario 29 agree; no other normative sentence needs to change for R-1.

---

## 4. Other observations carried forward from IR-OTD-02

**4.1 The §51.15 test.** Fourteen cases agree on their face. Case 6 does not, for the reason in §3: the invariant and algorithm give the lower's destination under the written-rules exception, the Adopt row agrees, and the Higher-body row disagrees. With R-1 corrected, case 6 is clean; I found no other case failure.

**4.2 Low-severity notes, unchanged and not verdict-changing.**
- **A-1 (1420)** still says "at most one destination per area per date" without "effective"; with D-140 a suspended lower allocation coexists with the higher. A-7 resolves which is effective, but the invariant's wording could make an implementer reject or delete the suspended allocation.
- **The Change row (1439)** says the old allocation ends at `d`; it does not mention D-140's resumption when a higher allocation is **superseded** by a new one that does not cover the lower's area. The Remove row and A-7 state it; the Change row is silent.
- **The Binding list (1365–1369)** does not name D-116 … D-122, D-136 or D-140, although §51 applies them. D-115 is correctly under "Not binding" (1370).
- **Step 4's "an allocation act" (1408)** is a D-79 quotation; "a placement act" would match the vocabulary note.
- **A-1 (1420)** raises AllocationReviewNeeded for any re-parenting of an allocated area; A-8 (1423) and the §51.5 row (1443) qualify it with "could alter a rule-derived result". The broader signal is safe but inconsistent.
- **D-106 §6 is still not in the package as a document**; the IR-OTD-02 commission's quotation is the only source (UNAVAILABLE). A-8's premise rests on that quotation.

None of these changes the verdict; R-1 does.

---

## 5. Verdict

**NOT CONFIRMED.**

C-1, C-2 and C-3 are correctly applied and confirmed at the lines given. B-8 now has its normative home; the Higher-body row now states D-140's resumption; A-8 now names the scheme's competent authority and scenario 33 tests the closed exploit. Eighteen of the twenty checklist items are confirmed, and the §51.15 exercise is clean in 14 of 15 cases.

One material residual remains, and it is **R-1**: the Higher-body row (1442) states D-119's prevalence without the written-rules exception that D-119 itself, D-136, A-7, the Adopt row and scenario 29 all carry. It is a one-clause text correction; no new Principal decision is needed. Until it is made, the lifecycle table gives two answers for the written-rules case and §51.15 case 6's "=" does not hold for the lifecycle table as a whole.

I decide nothing for the Principal and claim no acceptance. A narrow re-confirmation after the R-1 edit — item 2, item 20 and scenario 29 — would be sufficient to close this review.

---

## 6. Reviewer identity, prior involvement, integrity — and the independence limit

**Who I am.** I am a **DeepSeek-family** model, designation **`deepseek-chat`**, in the **Claude Code** command-line harness (an Anthropic product). I am **not** a Claude model.

**Prior involvement.** I wrote IR-OTD-01, IR-OTD-01-C, IR-OTD-01-C2, IR-OTD-01-C3 and IR-OTD-02 — every prior report on §49–§51 in this package. I also reviewed the Organisation people register (IR-OPR-01) earlier the same day; that is a different design and is not evidence here.

**The independence limit, stated plainly.** The commission for IR-OTD-02 required a genuinely fresh reviewer: not Claude, not the DeepSeek model that wrote the four earlier reports, preferably outside Claude Code. I am that excluded model, in that excluded harness. This re-run is therefore a **sixth self-review**, and the independence requirement remains unmet. The Principal instructed the re-run. I mitigated the risk by reading the normative text and D-119/D-136/D-140 afresh, by re-testing all 15 cases rather than accepting the author's table, and by looking for defects in the corrections I had just made — which is how R-1 was found: the IR-OTD-02 C-1 correction fixed "ends" but did not restore the D-136 exception, and the earlier C2/C3 passes had checked the Adopt row and the Higher-body row separately without cross-checking them. That is some evidence of a genuine re-read, but it does not substitute for an independent reviewer. A human architect or a GPT/Gemini/Mistral reviewer outside Claude Code remains the right reviewer before acceptance; that is an observation about this review's weight, not a decision for the Principal.

**Integrity check, performed by me.**
1. The live subject hashes to `3df9944d74967f45d68362b7088beee7c818799eaff7297b117fdf7f3e1b0c0a`; the decision record to `a7fcb160…`; the IR-OTD-02 report to `6530eeef…` — all as expected, and the decision record is unchanged from IR-OTD-02.
2. The frozen `ir-otd-02-review-handover` bundle is untouched and still verifies at its reviewed subject `1292cfce…`; this re-run reviewed the **live corrected document**, not the frozen copy, and I did not alter the bundle or its manifest.
3. I modified nothing during this review. The document's corrections were made by the Principal's instruction before the re-run; this report records their state as I found it.
4. I ran no code from the bundle and read no organisation's written rules.

**Labels.** *Confirmed* — the required content is in the normative text at the line given. *Partial* — part present, part missing or incomplete. *Missing* — absent from the normative text; none now. *Contradictory* — a normative sentence says something a decision or another normative rule denies. *Not assessable* — the source needed is not in the package; none. Anything not in the package is **UNAVAILABLE**; the only such item relied on is D-106 §6, quoted in the IR-OTD-02 commission.

**What this review does not establish.** No new architecture review beyond the 20 items and R-1; no verification against code or runtime; no reading of any organisation's written rules; no acceptance, ratification or authorisation; no decision for the Principal. The author's §51.11 and §51.15 are not treated as independent evidence.

**End of report.**
