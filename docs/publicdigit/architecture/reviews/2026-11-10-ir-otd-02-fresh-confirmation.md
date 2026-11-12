# IR-OTD-02 — Fresh Independent Confirmation

| | |
|---|---|
| **Subject** | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md`, §49–§51 (third revision) — SHA-256 `1292cfcee038364b0ffea0eda5e5cedffb7690f5bd9f12a4898387c7d0b6fe95` |
| **Decision record** | `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` — SHA-256 `a7fcb16023ac26358d1c8d3d5b54b48baa9231dd8910b9c8d7bb18858ffa69f3` |
| **Decisions in scope** | D-114 … D-122 (§11.43–§11.47), **D-136** (§11.49), **D-140** (§11.51); accepted baseline **D-106 §6** as quoted in the commission. D-123–D-135 and D-137–D-139 are other sessions' topics and are not relied on |
| **Earlier reports** | IR-OTD-01, -C, -C2, -C3 — hashes verified in `MANIFEST.sha256` |
| **Reviewer** | `deepseek-chat`, DeepSeek family, Claude Code harness — **not a Claude model, but the model that wrote the four earlier reviews. The commission's fresh-reviewer requirement is NOT met; see §6** |
| **Integrity** | Handover `SHA256SUMS` 4/4 OK; bundle `MANIFEST.sha256` 7/7 OK; upload-files `MANIFEST.sha256` 8/8 OK; subject and decision record hash to the required values. I modified nothing |
| **Status** | Confirmation only. No acceptance; nothing decided for the Principal |

**What I checked, and by what rule.** I read the commission in full and treated as normative only the invariant tables (§49.4, §50.6, §51.4), §49.7, §49.8, §50.15, the descent algorithm (§51.3), the lifecycle tables (§50.5, §51.5) and the scenarios. I treated §51.11–§51.14 and every "Update" paragraph as history, so a correction found only there counts as MISSING. I read D-119, D-136 and D-140 verbatim in the decision record, and D-106 §6 as quoted in the commission. I worked the 20-item checklist, then ran the commission's 15-case test myself against the invariants, the lifecycle and the algorithm.

---

## 1. Checklist: verdict per item

| # | Item | Verdict | Where I found it |
|---|---|---|---|
| 1 | B-3: one geographic member belonging by default; second refused unless the rules allow (D-118, D-122) | **CONFIRMED** | §50.6 **B-3**, line 1213 |
| 2 | Authority (D-119) in §51.5 Adopt / Change / Remove / Higher-body rows, whatever the order; step 4 exception grant and termination by equal or higher (than the granting) authority, later general rule, written-rules clause; scenarios 25–26 | **CONTRADICTORY** | Adopt 1435, Change 1436, Remove 1437, Higher-body 1439; step 4 at 1405; scenarios 25–26 at 1477–1478. The Higher-body row's last clause contradicts D-140 — **C-1** |
| 3 | A-3: direct child, "unless the written rules provide otherwise"; A-2 consistent ("direct child") | **CONFIRMED** | §51.4 **A-3**, line 1421; **A-2**, line 1418 |
| 4 | D-136: A-7 authority first, written-rules competence exception, unknown refused; A-1 defers to A-7; Adopt row; scenarios 27–31, including scenario 31's "no effect while the higher stands, applies again if it ends; a refused allocation never applies" | **CONFIRMED** | A-7 1419; A-1 1417 ("A-7 governs which matching allocation wins: authority first"); Adopt 1435; scenarios 27–31 at 1479–1483 |
| 5 | A-7: containment slot `(CONTAINMENT, unit, scheme)` (not G-4); designated administrative scheme; valid at d, as known at τ | **CONFIRMED** | A-7 1419. The knowledge coordinate is now "the scheme's confirmed Geography position (A-8)", which is a recorded fact at τ; that is the intended A-8 form, not a regression |
| 6 | A-1 over canonical identities at τ; AllocationReviewNeeded on a merge of allocated areas; interim destination (scenario 12) | **CONFIRMED** | A-1 1417 (canonical identity at the scheme's confirmed position, A-8); scenario 12 1464 (no change until confirmation; merge of two allocated areas raises AllocationReviewNeeded) |
| 7 | S-9 (D-120) with dissolution carve-out and C2 read; §49.7 step 4 checks S-0 … S-5, S-7, S-9; B-7 rests on S-9 | **CONFIRMED** | S-9 935; §49.7 step 4 992; B-7 1336 |
| 8 | B-7 default "root as at admission", step 1 the same, scenarios §50.15 27–28 (D-122) | **CONFIRMED** | B-7 1339; step 1 1402; scenarios 27–28 at 1356–1357 |
| 9 | Re-admission: rule-derived re-applies; explicit placements and exceptions do not revive | **CONFIRMED** | §50.11 **scenario 10**, line 1274 |
| 10 | Step 4: placement a floor, only an exception fixes; S-6 withdrawn; §49.10 scenario 13 without S-6 | **CONFIRMED** | Step 4 1405; S-6 936; scenario 13 1071 |
| 11 | Cross-tree continuity: one change set, one transaction; scheme moves or lapses | **CONFIRMED** | §50.1 **R-1**, line 1125 |
| 12 | One Organisation knowledge boundary (§49.8 (0), §50.8); refusal beyond `closedThrough` | **CONFIRMED** | §49.8 (0) 1018; §50.8 1243; refusal 1022 |
| 13 | Re-parenting that could alter a rule-derived result raises AllocationReviewNeeded (§49.6 and §51.5) | **CONFIRMED** | §49.6 row 980; §51.5 Geography row 1440 |
| 14 | "Awaiting placement" only if unplaced; `PlacementReviewNeeded` otherwise (§51.1, C-2, closure row, §50.5 fallback, scenarios §50.11 5, §51.6 15) | **CONFIRMED** | §51.1 1378; C-2 1426; closure 1442; fallback 1196; scenarios 5 and 15 at 1269 and 1467 |
| 15 | Closure impact is a report, not a write-time check (closure row; scenario 19) | **CONFIRMED** | Closure row 1442; scenario 19 1471 |
| 16 | Vocabulary: "allocation rule", "placement", "change-set placement" used consistently in §50–§51 | **CONFIRMED** | §50.3 1165; §50.4 1178; step 4 1405 (the one "allocation act" is a D-79 quotation under the umbrella "placement" — low note) |
| 17 | B-5 a record-time assertion; B-1 residence wording; D-115 not binding; Q-1 and Q-2 marked decided; scenario 23 historical; §51.2 electoral-divisions note | **CONFIRMED** | B-5 1215; B-1 1211; D-115 1367; Q-1 1504, Q-2 1513; scenario 23 1475; §51.2 1391 |
| 18 | D-140: A-7, the Remove row and scenario 31 (resumption with `PlacementReviewNeeded`; refused allocations never apply) | **CONFIRMED** | A-7 1419; Remove 1437; scenario 31 1483. The three required places are correct; the Higher-body row is outside this item and is C-1 under item 2 |
| 19 | A-8: no Geography read at a later position than the scheme's confirmed one (A-1, A-7, step 2, §49.6, §51.5 Geography row, scenarios 12, 13, 32); a confirmation is an organisational act | **PARTIAL** | A-1 1417; A-7 1419; step 2 1403; §49.6 980; §51.5 1440; scenarios 12 1464, 13 1465, 32 1484. The mechanism and the "organisational act" are present, but A-8 does not say **who** may move `confirmedAtPosition` — **C-3** |
| 20 | §51.15: test the 15 cases; do the invariants, lifecycle and algorithm agree in each? | **CONTRADICTORY** | Cases 2, 8 and 10 (1643, 1649, 1651) do not agree: the invariants and algorithm give "lower without effect / resumes", the lifecycle's Higher-body row (1439) says "Y's allocation ends where they conflict". The other 12 agree |

**Tally: 16 CONFIRMED, 1 PARTIAL, 2 CONTRADICTORY, 0 NOT ASSESSABLE; no MISSING among the 20 items. One material gap outside the checklist is MISSING from the normative text: B-8 (§2, C-2).**

---

## 2. Material findings

### C-1 — the Higher-body row still says the lower allocation "ends" (contradicts D-140)

**Where.** §51.5 **Higher body overrides**, line 1439:

> Its decision on S's scope prevails from its date (D-115) and continues to prevail over any later decision of a lower body on the same scope (D-119); **Y's allocation ends where they conflict**.

**What D-140 says.** Decision record, line 956:

> A lower body's allocation that a higher body's allocation overrides (D-119, D-136) is **not ended**, only without effect while the higher allocation stands. **When the higher allocation ends** (removed, superseded, or lapsed because its destination closed), **the lower allocation applies again from that date**, and every member whose unit changes as a result receives a **`PlacementReviewNeeded`** notice.

A-7 (1419) says the same ("not ended: it has no effect while the higher stands and applies again when the higher ends"), the Remove row (1437) says the overridden lower allocation "applies again from d", and scenario 31 (1483) says it "is not ended but has no effect … applies again". The Higher-body row uses the verb D-140 turns on, and uses it the other way.

**Why it is material.** The lifecycle table is normative. An implementer reading the Higher-body row would record an end date for the lower allocation; a later removal or lapse of the higher allocation would then not bring it back, and D-140's resumption would not happen. "Ends where they conflict" can be read as "ceases to have effect in the conflict zone", but D-140 distinguishes exactly those two readings; the row must use the "no effect" wording. The author's own §51.15 cases 2, 8 and 10 rely on the row for the opposite answer.

**What removes it** (text only; no new Principal decision): replace the last clause with "**Y's allocation has no effect where they conflict; if it was adopted before the higher allocation, it applies again when the higher ends, with `PlacementReviewNeeded` (D-140). An allocation refused at adoption never applies.**" That matches A-7, the Remove row and scenario 31.

### C-2 — B-8 is defined only in history (MISSING from the normative invariant table)

**Where.** §50.6 (1207–1218) contains B-1 … B-6; B-7 is in §50.15 (1336). **B-8 does not appear in either.** It is defined only in §51.11 **R-5** (line 1547):

> **B-8: at most one placement-relevant recorded association per member per date** (the one the organisation's rules use). Others are profile information (D-101). **A second is refused unless it replaces the first from a date.**

It is cited in the normative scenario 23 (1475: "only historical or imported data: B-8 refuses a second") and in the aggregate-boundaries list (1554, history); L-9 (1614) also refers to it.

**Why it is material.** The commission's rule is explicit: a correction found only in §51.11–§51.14 or an "Update" paragraph counts as MISSING. A normative scenario tells the reader that "B-8 refuses a second", but the invariant table does not define B-8; there is nothing to enforce or test. The one-association rule is part of the design's handling of ambiguous placement (IR-OTD-01 L-9), not an optional note.

**What removes it** (text only; no new Principal decision): add B-8 to §50.6 (or to §50.15 beside B-7) with the R-5 text, and add it to the §50.7 aggregate statement. The rule is already settled; it needs its normative home.

### C-3 — A-8 does not say who may move the scheme's confirmed Geography position (material gap; design/Principal choice)

**Where.** §51.4 **A-8**, line 1420:

> Each allocation scheme records the Geography position at which the organisation last confirmed it (`confirmedAtPosition`, **set when an allocation is adopted or changed**, or by an explicit **ConfirmScheme** act of the allocation authority).

**The failure case.** Scope Y. The highest committee adopts D4 → D2; the scheme's confirmed position is `p0`. Geography publishes at `p1` that ward W, inside D4 at `p0`, is re-parented to D5. Province Y's committee then adopts an **unrelated** allocation, D6 → D3, at `p1`. D6 is not covered by the higher allocation, so the Adopt row (1435) does not refuse it. Under A-8, that valid adoption sets the scheme's `confirmedAtPosition` to `p1`. Descent for a W member now reads containment at `p1`: W is inside D5, not D4, so the higher committee's D4 → D2 no longer covers W, and the W member falls to their floor — although nobody made a decision about W. The lower body has, in effect, narrowed the higher body's allocation without the written-rules competence D-136 requires, and the member's unit changed because of the map rather than an act about that member.

**Why it is material.** A-8 implements D-106 §6 ("a change in published Geography never changes belonging by itself") only formally if any allocation adoption can move the scheme's position: the map change alone does not move W, but an unrelated lower-body act does, and the baseline's intent is that belonging changes only by a decision about it. D-119's "a lower body's later act cannot undo" the higher body's decision is also engaged in effect. The text says "the allocation authority" in the singular, but when a higher and a lower body both act in one scope it does not say which one may confirm the scheme, or whether a lower body's confirmation can change a higher body's coverage.

**This is a gap, not a proven textual contradiction.** It needs a design choice; I decide nothing. The options the text leaves open are:
- **(a) per-allocation position:** each allocation remembers its own confirmed Geography position, so a lower body's adoption moves only that allocation's reading;
- **(b) authority-limited confirmation:** the scheme's position moves only by an act of the authority competent for the scheme (for example, the highest body with an allocation in the scheme, or the body the written rules designate);
- **(c) protection of higher allocations:** a lower body's adoption or confirmation cannot alter a higher body's allocation without that body's act;
- or another rule the Principal prefers. Whether this needs a **Principal decision** depends on which option the Principal wants; the author can implement (a) as a text clarification if the Principal accepts it. Until one is stated, D-136's "no narrowing" can be bypassed by an unrelated adoption plus a map change.

---

## 3. The §51.15 15-case test, run independently

I checked each case against the invariant table, the lifecycle table and the descent algorithm. **Twelve agree: 1, 3, 4, 5, 6, 7, 9, 11, 12, 13, 14, 15.** **Three do not: 2, 8 and 10.** In each of those, the invariants (A-7, D-140) and the algorithm say the lower allocation is **without effect while the higher stands and resumes when the higher ends**, while the lifecycle's Higher-body row (1439) says it **"ends where they conflict"**. This is C-1, not a separate defect. The author's table marks these cases "="; the lifecycle row is the reason that mark is wrong.

Two remarks on the table, not findings: case 12 says "no effect until ConfirmScheme", while A-8 also lets an allocation adoption or change set the position — the table is narrower than A-8; and case 5's lifecycle column is "—", which is correct because no special lifecycle event is needed for equal-authority nested allocations.

---

## 4. Lower-severity observations (not verdict-changing)

- **A-1's "at most one destination per area per date" (1417)** does not say **effective**. With D-140, a suspended lower allocation for the same area coexists with the higher allocation; A-7 resolves which is effective, but the invariant's wording could make an implementer reject or delete the suspended allocation. "At most one **effective** destination" removes the ambiguity.
- **The Change allocation row (1436)** says the old higher allocation ends at `d`; it does not say that a lower allocation the old one overrode resumes if the new higher allocation does not cover its area. D-140's "superseded" covers it, and A-7 states it, but the row is silent. A short clause would align it with the Remove row.
- **The Binding list (1362–1366)** does not name D-116 … D-122, D-136 or D-140, although §51 applies them. D-115 is correctly under "Not binding" (1367). Add the rulings for status hygiene.
- **Step 4's "an allocation act" (1405)** is a quotation of D-79's individual sense; "a placement act" would match the vocabulary note. The umbrella term "placement" and the D-79 citation make the meaning clear, so this is cosmetic.
- **A-1 (1417) raises AllocationReviewNeeded for any re-parenting of an allocated area**, while A-8 (1420) and the §51.5 row (1440) qualify it with "could alter a rule-derived result". The broader signal is safe but inconsistent; align the trigger.
- **D-106 §6 is not in the package as a document** — the baseline acceptance act is not included; I used the commission's quotation (line 42). If the quotation is inaccurate, A-8's premise needs re-checking. Otherwise no source I needed is missing.

---

## 5. Verdict

**NOT CONFIRMED.**

The design now implements D-114 … D-122, D-136 and D-140 in the normative text in almost all the places the checklist requires: authority first at A-7, the written-rules competence exception, the containment slot, A-1's deference to A-7, the Adopt/Remove/step 4 authority checks, scenarios 25–31, A-3/A-2, S-9, B-7, re-admission, cross-tree continuity, the knowledge boundary, the closure report, the vocabulary alignment and the D-121 notices. Items 1, 3–18 are confirmed at the lines given. The §51.15 exercise is correct in 12 of 15 cases.

Three things remain, and they are a closed list:

1. **C-1 (text correction, no new decision):** the Higher-body row (1439) must say the lower allocation **has no effect** where it conflicts and **applies again when the higher ends**, with `PlacementReviewNeeded` — not that it "ends". This is the D-140 correction that did not reach the lifecycle row.
2. **C-2 (text correction, no new decision):** B-8 must be added to the normative invariant table (§50.6 or §50.15); today it exists only in §51.11 R-5 and is cited by scenario 23.
3. **C-3 (design clarification; may need a Principal decision):** A-8 must say **who** may move a scheme's `confirmedAtPosition` and whether a lower body's unrelated adoption can change a higher body's allocation coverage. Options (a)–(c) are in §2; I decide none of them.

The lower-severity points in §4 can be handled in the same editing pass. With C-1 and C-2 corrected and C-3 resolved, I would expect the next confirmation to be clean. I express no acceptance and make no decision for the Principal.

---

## 6. Reviewer identity, prior involvement, integrity — and the independence limit

**Who I am.** I am a **DeepSeek-family** model, designation **`deepseek-chat`**, running in the **Claude Code** command-line harness (an Anthropic product). I am **not** a Claude model.

**Prior involvement.** I wrote **IR-OTD-01** (the independent review), **IR-OTD-01-C** (first confirmation), **IR-OTD-01-C2** (second confirmation) and **IR-OTD-01-C3** (narrow confirmation) — the four reports in this package. I also reviewed the Organisation people register (IR-OPR-01) earlier the same day; that is a different design and I have not used it as evidence here.

**The independence limit, stated plainly.** The commission requires a **genuinely fresh** reviewer: not Claude, **not the DeepSeek model that wrote the four earlier reports**, preferably outside Claude Code. I am exactly the excluded model, in exactly the excluded harness. **This is therefore a fifth self-review, not the fresh confirmation the package was built for; the independence requirement is not met.** I proceeded because the Principal instructed me to perform the review. I mitigated the risk by reading the normative text and D-119/D-136/D-140/D-106 §6 afresh, by treating §51.11–§51.14 as history and not evidence, by testing all 15 cases rather than accepting §51.15, and by re-checking the items this revision did not intend to change. The mitigation is visible in the result — I found a contradiction in a row I had quoted in C2 without flagging (the Higher-body row, which D-140 has since made wrong), and a missing invariant (B-8) — but it does not remove the limit. A human architect or a GPT/Gemini/Mistral reviewer outside Claude Code remains the right reviewer for IR-OTD-02 before acceptance; that is an observation about this review's weight, not a decision for the Principal.

**Integrity check, performed by me.**
1. In `~/ir-otd-02-review-handover` I ran `sha256sum -c SHA256SUMS`: **4/4 OK**.
2. In `ir-otd-02-review-bundle` I ran `sha256sum -c MANIFEST.sha256`: **7/7 OK**.
3. In `upload-files` I ran `sha256sum -c MANIFEST.sha256`: **8/8 OK**.
4. The subject's computed SHA-256 is `1292cfcee038364b0ffea0eda5e5cedffb7690f5bd9f12a4898387c7d0b6fe95` and the decision record's is `a7fcb16023ac26358d1c8d3d5b54b48baa9231dd8910b9c8d7bb18858ffa69f3`, exactly the required values. **No stop.**
5. The four earlier reports hash to the values in the manifest; the C3 report is the version I delivered.
6. I modified no supplied document, ran no code from the bundle, and changed neither the subject nor the decision record. After writing this report I re-hashed the subject; it is unchanged.

**Labels.** *Confirmed* — the required content is in the normative text at the line given. *Partial* — part is present, part is missing or incomplete at the line given. *Missing* — absent from the normative text. *Contradictory* — a normative sentence says something a decision or another normative rule denies. *Not assessable* — the source needed is not in the package; I found none. Anything not in the package is **UNAVAILABLE**; the only such item I relied on is D-106 §6, which the commission quotes.

**What this review does not establish.**
- No re-review of the architecture beyond the 20 items and the material gaps above.
- No verification against code or runtime.
- No reading of any organisation's written rules; A-7's competence exception is confirmed as a rule, not tested against a real text.
- No acceptance, ratification or authorisation; no decision for the Principal.
- The author's self-review (§51.11) and §51.15 are not treated as independent evidence.

**End of report.**
