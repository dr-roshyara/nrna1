# IR-OTD-01-C: confirmation review of the Organisation tactical design (§49–§51)

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-10 |
| **Status** | **COMMISSION, READY TO HAND OVER**, preferably to **the same independent reviewer** as IR-OTD-01 (not Claude) |
| **Purpose** | **One question only:** did every IR-OTD-01 finding and every later Principal decision (D-118 … D-122) **actually reach the normative text** of §49–§51 (rules, invariants, algorithm, scenarios), not just a summary table, **without introducing a new contradiction?** |
| **Not in scope** | Re-reviewing the architecture; new findings outside the corrections, **except contradictions the corrections introduced** |

## 1. Package (SHA-256, 2026-11-10)

| File | SHA-256 |
|---|---|
| `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` (subject) | `76c9147dd98156b516e77dd6c6238628c475138f20622d084d508aa0f3380050` |
| `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` (decision record; D-114 … D-122 in §11.43 … §11.47) | `1405e12ff518f061767bae6494dc951855285eea77d0f9a5e8706d7b348dfb65` |
| `docs/publicdigit/architecture/reviews/2026-11-10-ir-otd-01-independent-review.md` (the review being confirmed) | see `MANIFEST.sha256` |

## 2. Decisions taken after IR-OTD-01 (read them in the decision record)

| Decision | Content (summary; the record governs) |
|---|---|
| D-118 | One geographic member belonging per member per date **by default**; the rules may allow more |
| D-119 | A higher body prevails on the same scope whatever the order; exceptions only by the overridden rule's authority or higher; **allocations target only direct children**; a higher body overrides **within** the lower scope. **The reviewer's M-1 proposal (allocate past a scope) was not adopted** |
| D-120 | The root exists before the first admission and cannot close while membership coverage exists |
| D-121 | "Awaiting placement" only where no rule or act places the member |
| D-122 | D-118 confirmed against the reviewer's "no default" recommendation; a new root above the old one moves no member without a decision; the closure notice is `PlacementReviewNeeded` |

## 3. Checklist: confirm each item **in the normative text**

| # | Finding or decision | Must now read (in substance) | Where |
|---|---|---|---|
| 1 | H-1, D-118, D-122 | Default one geographic member belonging; a second refused unless the rules allow | §50.6 **B-3**; §51.14 |
| 2 | H-2, D-119 | Lower body cannot undo a higher one; exception authority | §51.4 **A-3**; §51.3 step 4; §51.14 |
| 3 | H-3 | A-1 over canonical identities at τ; review signal; interim "keeps the destination of the recorded reference" | §51.4 **A-1**; §51.6 **scenario 12**; §51.14 |
| 4 | H-4, D-120 | Root required; cannot close while coverage exists | §49.4 **S-9**; §50.15 **B-7**; §51.14 |
| 5 | H-5 | Re-admission: rule-derived re-applies; explicit placements and exceptions do not revive | §50.11 **scenario 10**; §51.11 R-6 |
| 6 | H-6 | Placement is a floor; only an exception fixes the unit; S-6 withdrawn | §51.3 **step 4**; §49.4 **S-6** |
| 7 | M-1, D-119 | Allocations target direct children only | §51.4 **A-3** |
| 8 | M-2 | Cross-tree continuity: one change set over both trees, one transaction; scheme moves or lapses | §50.1 **R-1** |
| 9 | M-3 | One Organisation knowledge boundary over structure, belonging, allocation schemes | §49.8 **(0)**; §50.8 |
| 10 | M-4 | Designated administrative scheme; re-parenting raises AllocationReviewNeeded | §51.4 A-1; §51.14 |
| 11 | M-5, D-121, D-122 | "Awaiting placement" only if unplaced; `PlacementReviewNeeded` otherwise | §51.1; §51.4 **C-2**; §51.11 R-3 (marked superseded) |
| 12 | M-6 | Closure impact is a report, not a write-time check | §51.6 **scenario 19**; §51.14 |
| 13 | M-7 | "Allocation rule" versus "placement"; meaning of D-114's "allocation in the creating decision" | §51.14 |
| 14 | M-8 | B-5 a record-time assertion | §50.6 **B-5** |
| 15 | L-1 … L-9 | Citation, "at d as known at τ", D-115 as WD, tree named in step 1, root and new top unit (D-122), B-7 rests on S-9, labels, B-1 residence wording, scenario 23 as historical data | §51.3 step 1; §50.6 **B-1**; §51.6 scenario 23; §51.14 |

## 4. Report

Title: **"IR-OTD-01-C — Confirmation Review"**:
1. **Per checklist item:** CONFIRMED · PARTIAL · MISSING, with the line you found.
2. **Any contradiction the corrections introduced** (between corrected and uncorrected passages, or with D-114 … D-122).
3. **Verdict:** CONFIRMED (ready for the Principal's acceptance decision) · NOT CONFIRMED (list what remains).
4. Reviewer identity, prior involvement, integrity method.

**Rules:** confirm only; decide nothing for the Principal; modify nothing; claim no acceptance. **Stop after the report.**
