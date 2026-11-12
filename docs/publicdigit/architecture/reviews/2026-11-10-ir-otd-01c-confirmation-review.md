# IR-OTD-01-C — Confirmation Review

| | |
|---|---|
| **Subject** | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md`, §49–§51 as corrected — SHA-256 `76c9147dd98156b516e77dd6c6238628c475138f20622d084d508aa0f3380050` |
| **Review being confirmed** | `docs/publicdigit/architecture/reviews/2026-11-10-ir-otd-01-independent-review.md` — SHA-256 `0db4623fc54c4fbfdaf92bab71b96a5d6b50aa298a3f72b8a3af2e96ec8801a8` (the report I delivered as IR-OTD-01) |
| **Decisions** | D-118 … D-122, decision record §11.46–§11.47 (lines 903–912) |
| **Reviewer** | `deepseek-chat`, DeepSeek family, running in the Claude Code harness — **not a Claude model** |
| **Integrity** | Handover `SHA256SUMS` 4/4 OK; bundle `MANIFEST.sha256` 4/4 OK; subject hash matches the required value. I modified nothing |
| **Status** | Confirmation only. No new architecture review; no acceptance; nothing decided for the Principal |

**What I checked.** For each of the commission's 15 items I read the normative text itself — invariants, the descent algorithm, the lifecycle table, the scenarios and the status block — not the §51.14 summary. I also read D-118 … D-122 verbatim in the decision record. I did not treat the author's own review (§51.11) as evidence.

**The nine corrections the Principal identified as still confined to §51.14 are all now in the text.** I verified each at the line given below: B-1 (1211), B-3 (1213), B-5 (1215), S-9 (935), B-7's dependence on S-9 (1336), the one Organisation knowledge boundary (§49.8 line 1018; §50.8 line 1243), the cross-tree move (R-1, line 1125), and descent step 1 (line 1398). The PARTIAL items below are **not** those nine; they are different, specific residuals.

---

## 1. Checklist: verdict per item

| # | Item | Verdict | Where I found it |
|---|---|---|---|
| 1 | H-1, D-118, D-122 — one geographic member belonging by default; second refused unless the rules allow | **CONFIRMED** | §50.6 **B-3**, line 1213; §51.14 H-1, line 1582 |
| 2 | H-2, D-119 — lower body cannot undo a higher one; exception authority | **PARTIAL** | §51.4 **A-3**, line 1415; §51.3 step 4, line 1401; §51.14 H-2, line 1584 — see 2 below |
| 3 | H-3 — A-1 over canonical identities at τ; review signal; interim destination | **CONFIRMED** | §51.4 **A-1**, line 1413; §51.6 **scenario 12**, line 1458; §51.14 H-3, line 1583 |
| 4 | H-4, D-120 — root required; cannot close while coverage exists | **PARTIAL** | §49.4 **S-9**, line 935; §50.15 **B-7**, line 1336; §51.14 H-4, line 1585 — see 4 below |
| 5 | H-5 — re-admission: rule-derived re-applies; explicit does not revive | **CONFIRMED** | §50.11 **scenario 10**, line 1274; §51.11 **R-6**, line 1534 |
| 6 | H-6 — placement is a floor; only an exception fixes; S-6 withdrawn | **CONFIRMED** | §51.3 **step 4**, line 1401; §49.4 **S-6**, line 936 |
| 7 | M-1, D-119 — allocations target direct children only | **PARTIAL** | §51.4 **A-3**, line 1415 — see 7 below |
| 8 | M-2 — cross-tree continuity: one change set, one transaction; scheme moves or lapses | **CONFIRMED** | §50.1 **R-1**, line 1125 |
| 9 | M-3 — one Organisation knowledge boundary | **CONFIRMED** | §49.8 **(0)**, line 1018; §50.8, line 1243 |
| 10 | M-4 — designated administrative scheme; re-parenting raises AllocationReviewNeeded | **PARTIAL** | §51.4 **A-1**, line 1413; §51.14 M-4, line 1588 — see 10 below |
| 11 | M-5, D-121, D-122 — "awaiting placement" only if unplaced; PlacementReviewNeeded otherwise | **CONFIRMED** | §51.1, line 1376; §51.4 **C-2**, line 1420; R-3 marked superseded, line 1531 |
| 12 | M-6 — closure impact is a report, not a write-time check | **CONFIRMED** | §51.6 **scenario 19**, line 1465; §51.14 M-6, line 1589 |
| 13 | M-7 — "allocation rule" versus "placement"; D-114's "allocation in the creating decision" | **CONFIRMED** | §51.14 M-7, line 1590 |
| 14 | M-8 — B-5 a record-time assertion | **CONFIRMED** | §50.6 **B-5**, line 1215 |
| 15 | L-1 … L-9 | **PARTIAL** | L-4/L-5/L-6/L-7/L-8/L-9 confirmed; L-1, L-2, L-3 — see 15 below |

**Tally: 10 CONFIRMED, 5 PARTIAL, 0 MISSING.**

---

## 2. The five PARTIAL items

**Item 2 — D-119's order-independence is not in the lifecycle.** A-3 (line 1415) correctly says an allocation targets only a direct child and that a higher body overrides "only by deciding within that scope"; step 4 (line 1401) correctly limits an exception to "the authority of the overridden rule or higher". But D-119's first clause — **"a higher body's decision prevails over a lower body's on the same scope, whatever their order; a lower body's later act cannot undo it"** (decision record, line 904) — appears **only** in §51.14 H-2 (line 1584: "a change, end or exception made by a lower body than the decision it would affect is refused"). The lifecycle table still lets a province committee record "Change allocation" (line 1430) and "Remove allocation" (line 1431) with no authority check against a higher body's standing decision; the "Highest committee overrides" row (line 1433) covers only the case where the highest committee acts **after** the province. The rule the corrections table says the "Lifecycle checks" is not in the lifecycle.

**Item 4 — S-9 is stated but not wired into the change-set check.** S-9 (line 935) and B-7's dependence on it (line 1336) are present, and §51.14 H-4 (line 1585) states the inapplicability rule. But §49.7 step 4 (line 992) still reads **"Check S-0 … S-7 for every date from the effective date onwards"**: it **includes the withdrawn S-6** and **excludes the new S-9**. As written, a change set closing the root while membership coverage exists would pass the algorithm's check. Two further consequences are unstated: S-9's condition ("while any membership coverage exists") requires a Membership read (C2 at τ, or the projection) inside a check §49.7 describes as Tree-only, and D-120's carve-out — "Dissolving the whole organisation is a separate act, not a unit closure" (line 905) — is not in S-9, so S-9 read alone appears to block dissolution.

**Item 7 — A-3 implements D-119's direct-child rule but not its final sentence.** A-3 (line 1415) correctly states the direct-child default; this is D-119, **not** my M-1 proposal, and I confirm the design did not adopt M-1. But D-119 ends: **"The organisation's written rules may provide otherwise"** (decision record, line 904). A-3 states the restriction absolutely; the phrase appears nowhere in the design (I searched the whole subject). An organisation whose written rules permitted a deeper allocation would be refused by A-3 as written, contrary to the decision.

**Item 10 — the re-parenting signal is only in the summary.** A-1 (line 1413) names the designated administrative scheme and A-7, and raises `AllocationReviewNeeded` for a reconciliation that merges two allocated areas. But the M-4 correction — **a containment change that alters a rule-derived result raises AllocationReviewNeeded** — appears only in §51.14 M-4 (line 1588). The normative text still says the opposite split: §51.5 (line 1434) triggers `AllocationReviewNeeded` for "a division or end of the area" only, and §49.6 (line 980) sends a **re-parented** reference to `CoverageReviewNeeded`, not to the allocation review.

**Item 15 — three of the nine low corrections did not reach the text.**
- **L-1:** R-1 (line 1529) still says "Containment is single-parent per scheme **(Geography G-4)**", while §51.14 L-1 (line 1592) says the opposite ("not G-4"). The wrong citation is in the normative self-review, not only in a table.
- **L-2:** step 2 (line 1399) still reads "per Geography's containment **at d**" without "as known at τ"; the combined rule is in A-1 (line 1413, for the scheme) and in §51.14 L-2 (line 1593). The substance is recoverable, but the algorithm's own sentence still has one axis.
- **L-3:** the §51 status block (lines 1360–1362) still lists **"D-115 (WD)" under "Binding:"**, while §51.14 L-3 (line 1594) says "D-115 is a **working direction (WD), not binding**". This is a direct contradiction about the status of the authority the whole section uses.
- L-4 (step 1, line 1398), L-5 (step 1; S-9; §51.14 L-5, line 1596), L-6 (line 1336), L-7 (line 1598), L-8 (B-1, line 1211) and L-9 (scenario 23, line 1469) are **CONFIRMED**.

---

## 3. Contradictions the corrections introduced

**C-1 (material) — B-7 against D-122 and step 1 when a new root is inserted.** B-7's enforcement says: "If no recorded geographic belonging is effective on a date, **the effective belonging is the root unit**" (line 1339). D-122(2) says members whose effective unit is the old root **without a recorded placement stay with the old root** (now a child) unless the restructuring decision moves them (decision record, line 912); step 1 (line 1398) implements this ("the root **as at their admission**, carried forward by unit identity"); S-9 (line 935) says "inserting a new root above it moves no member without a decision". A member with no recorded placement has no effective recorded belonging, so B-7 gives the **new** root while D-122/step 1/S-9 give the **old** root. The invariant sentence needs to read "the root unit **as at the member's admission** (D-122)".

**C-2 — §49.7 step 4 against S-6 and S-9.** The check range "S-0 … S-7" (line 992) includes the withdrawn S-6 (line 936) and excludes the new S-9 (line 935). This is the enforcement counterpart of item 4.

**C-3 — the status block against L-3.** "Binding: … D-115 (WD)" (line 1362) versus "D-115 is a working direction (WD), not binding" (line 1594).

**C-4 — R-1 against L-1.** "Containment is single-parent per scheme (Geography G-4)" (line 1529) versus "not G-4" (line 1592).

**C-5 — §49.10 scenario 13 still cites S-6.** "Wing unit moved into the main tree — End and new unit, by decision **(S-6)**" (line 1071), although S-6 is withdrawn (line 936). The outcome is still valid as R-1(b) succession; only the citation is stale.

**C-6 — §51.5's closure row against D-121/C-2.** The row says the decision "may place U's explicit members; **otherwise those members are flagged 'awaiting placement'**" (line 1436). C-2 (line 1420) says rule-derived members get `PlacementReviewNeeded`, and only members whom **no rule or act** places are flagged "awaiting placement". The row is incomplete rather than plainly wrong ("those members" can be read as the explicit members), but a reader applying it to all remaining members would contradict D-121.

**C-7 — A-2's "descendant" against A-3's "direct child" (wording, not logic).** A-2 (line 1414) requires the destination to be "an active **descendant** of the scope unit"; A-3 (line 1415) requires "only a **direct child**". Logically they compose (a direct child is a descendant), but A-2 read as the destination rule permits a grandchild. The two sentences should use the same term.

**C-8 — §51.9 Q-1 is stale (not introduced by this round).** Q-1 still presents the choice as open and recommends "(a) the individual act prevails while it stands" (lines 1490–1497), while step 4 (line 1401) and D-116 distinguish a placement (floor) from an exception (fixed). §51.13's update (line 1562) records that Q-1 was decided, but §51.9 itself is not marked. Low.

**Not a contradiction, but noted:** §50.5 (line 1192) and §50.7 (line 1233) still use "allocation" in the D-79 individual sense, although §51.14 M-7 assigns that sense to "placement"; the glossary resolves the meaning, but the older sentences were not aligned.

---

## 4. Verdict

**NOT CONFIRMED.**

No item is MISSING and the architecture is not in question. Of the 15 items, 10 are confirmed in the normative text and 5 are partial. What remains is a closed list of text corrections, each small:

1. **Item 2 / D-119:** state in §51.5 (or A-3) that a higher body's decision prevails **whatever the order** and that a lower body's change, end or exception is refused — currently only §51.14 H-2.
2. **Item 4 / D-120:** change §49.7 step 4 to check **S-0 … S-5, S-7 and S-9** (not S-6); state the Membership-coverage read S-9 needs; add D-120's dissolution carve-out to S-9.
3. **Item 7 / D-119:** add "unless the organisation's written rules provide otherwise" to A-3 (and step 4).
4. **C-1 / D-122:** align B-7's default with step 1 — "the root unit **as at the member's admission**".
5. **Item 10 / M-4:** put the re-parenting → `AllocationReviewNeeded` rule in §51.5/A-1, and reconcile §49.6's `CoverageReviewNeeded`.
6. **Item 15:** replace R-1's "G-4" with the containment slot; state "at d, as known at τ" in step 2; move D-115 out of "Binding" in the status block.
7. **Minor:** align A-2's "descendant" with A-3's "direct child"; reword §51.5's closure row to match D-121; mark §51.9 Q-1 as decided.

With those corrections the design would implement D-118 … D-122 and the IR-OTD-01 findings without the defects above. I express no view beyond this list, and I make no decision for the Principal.

---

## 5. Reviewer identity, prior involvement, integrity method — and the limits of this review

**Who I am.** I am a **DeepSeek-family** model, designation **`deepseek-chat`**, running in the **Claude Code** command-line harness (an Anthropic product). I am **not** a Claude model. The harness is Anthropic's; the analysis here is mine.

**Prior involvement.** I performed IR-OTD-01, the review being confirmed. I am therefore the "same reviewer" the commission prefers, and I disclose the corresponding risk: I am checking whether **my own** findings were transposed, which invites self-confirmation. I mitigated it by reading the normative text and the decision record afresh, by not treating §51.14 as evidence until I had found the rule in the text, and by reporting the items that are **not** confirmed. I have no other prior involvement with §49–§51; I reviewed the Organisation people register (IR-OPR-01) earlier the same day, a different design, and I have not used it as evidence here.

**Integrity check, performed by me.**
1. In `~/ir-otd-01c-review-handover` I ran `sha256sum -c SHA256SUMS`: 4/4 OK.
2. In the bundle I ran `sha256sum -c MANIFEST.sha256`: **4/4 OK**.
3. The subject's computed SHA-256 is `76c9147dd98156b516e77dd6c6238628c475138f20622d084d508aa0f3380050`, exactly the required value. **No stop.**
4. The bundle's copy of the IR-OTD-01 report hashes to `0db4623f…`, the report I delivered — the review being confirmed is that report, not a later version.
5. I modified no supplied document and ran no code from the bundle.

**Labels.** *Confirmed* — the rule is in the normative text at the line given. *Partial* — part of the finding or decision is present, part is missing or contradicted at the line given. *Missing* — absent from the normative text. I found none. Anything not in the bundle is **UNAVAILABLE**; I did not need any.

**What this review does not establish.**
- No re-review of the architecture; no findings outside the corrections, except the contradictions above.
- No verification against code or runtime.
- No reading of any organisation's written rules.
- No acceptance, ratification or authorisation; no decision for the Principal.
- The author's self-review (§51.11) is not treated as independent evidence.

**End of report.**
