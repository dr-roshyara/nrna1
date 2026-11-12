# IR-OTD-02: fresh independent confirmation of the Organisation tactical design (§49–§51)

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-10 |
| **Status** | **COMMISSION, READY TO HAND OVER** |
| **Reviewer** | **Genuinely fresh:** not Claude; **not the DeepSeek model** that wrote IR-OTD-01, -C, -C2 and -C3; **preferably not run inside Claude Code** (a human architect, or e.g. GPT, Gemini or Mistral in their own interface) |
| **Why** | Four reviews by one model found real defects, each corrected; but the confirmations were largely the same reviewer checking its own findings (disclosed). **No fresh reviewer has read §49–§51** |
| **Purpose** | **Confirm, with fresh eyes, that §49–§51 now implement every finding of IR-OTD-01, -C, -C2 and -C3 and every Principal decision D-114 … D-122, D-136 and D-140, in the normative text, with no contradiction.** Report any **material** contradiction or gap you see in §49–§51, even outside the checklist |
| **Not in scope** | Redesigning the model; other bounded contexts; the people register; implementation |

## 1. Package (SHA-256, 2026-11-10)

| File | SHA-256 |
|---|---|
| `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` (**subject**: §49–§51; base §9–§12, §38, §45) | `1292cfcee038364b0ffea0eda5e5cedffb7690f5bd9f12a4898387c7d0b6fe95` |
| `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` (decision record) | `a7fcb16023ac26358d1c8d3d5b54b48baa9231dd8910b9c8d7bb18858ffa69f3` |
| The four earlier reports (IR-OTD-01, -C, -C2, -C3) | see `MANIFEST.sha256` |

**Reading the subject:**
- **Normative text:**
  - invariant tables §49.4, §50.6, §51.4;
  - §49.7, §49.8;
  - §50.15;
  - the descent algorithm §51.3;
  - lifecycle tables §50.5, §51.5;
  - scenarios §49.10, §50.11, §50.15, §51.6.
- **History, not evidence:** §51.11–§51.14 and every "Update" paragraph. **A correction found only there counts as MISSING.**
- **§49–§51 revise §9–§12, §38 and §45** of the same file; where they differ, §49–§51 govern.

## 2. Decisions (read them in the record)

| Decision | Record | Content in short |
|---|---|---|
| D-114 | §11.43 | Every effective member has a geographic unit on every date; the root by default |
| D-115 | §11.44 | **Working direction only:** a province committee is the default allocation authority unless the highest committee decides otherwise |
| D-116, D-117 | §11.45 | An exception prevails while it stands; a placement is a floor. Allocation rules apply to all qualifying members from their date |
| D-118 … D-121 | §11.46 | One geographic member belonging by default. A higher body prevails whatever the order; exceptions only by the overridden rule's authority or higher; direct-child allocations; written rules may provide otherwise. Root required while coverage exists. "Awaiting placement" only if unplaced |
| D-122 | §11.47 | D-118 kept; a new root moves no member without a decision; `PlacementReviewNeeded` |
| **D-136** | **§11.49** | **Nested areas:** authority before specificity; a lower body's contained-area rule only under an identifiable written-rules provision; unknown competence refused and reported |
| **D-140** | **§11.51** | **An overridden lower allocation is not ended; it resumes when the higher one ends, with `PlacementReviewNeeded`**; a refused allocation never applies |
| *(accepted baseline)* | D-106 §6 | **"A change in published Geography never changes belonging by itself"**, implemented by **A-8** (allocation schemes read Geography at their confirmed position) |

**Context:**
- D-123 … D-135 (§11.48) and D-137 … D-139 (§11.50) are **other sessions' decisions** (D-140 in §11.51 is in scope) (Election, people register, fees). **They are not part of this review**, except that §51.2 refers to D-125/D-126 (electoral divisions).
- **Deliberate choices, not defects:**
  - IR-OTD-01's **M-1** proposal was not adopted (D-119);
  - its **H-1** recommendation of no default was rejected (D-118, D-122).

## 3. Checklist (20 items)

| # | Must be in the normative text |
|---|---|
| 1 | B-3: one geographic member belonging by default; second refused unless the rules allow (D-118, D-122) |
| 2 | Authority (D-119) in §51.5 Adopt / Change / Remove / Higher-body rows, whatever the order; §51.3 step 4 exception grant and termination by equal or higher (than the **granting**) authority, a later general rule does not end it, written-rules clause; scenarios §51.6 25–26 |
| 3 | A-3: direct child, "unless the written rules provide otherwise"; A-2 consistent ("direct child") |
| 4 | **D-136:** A-7 authority first, written-rules competence exception, unknown refused; **A-1 defers to A-7**; Adopt row; scenarios §51.6 27–31, including scenario 31's "no effect while the higher stands, applies again if it ends; a refused allocation never applies" |
| 5 | A-7: containment slot `(CONTAINMENT, unit, scheme)` (not G-4); designated administrative scheme; valid at d, as known at τ |
| 6 | A-1 over canonical identities at τ; AllocationReviewNeeded on a merge of allocated areas; interim destination (scenario 12) |
| 7 | S-9 (D-120) with dissolution carve-out and C2 read; **§49.7 step 4 checks S-0 … S-5, S-7, S-9**; B-7 rests on S-9 |
| 8 | B-7 default "root as at admission", §51.3 step 1 the same, scenarios §50.15 27–28 (D-122) |
| 9 | Re-admission: rule-derived re-applies; explicit placements and exceptions do not revive (§50.11 scenario 10) |
| 10 | §51.3 step 4: placement a floor, only an exception fixes; S-6 withdrawn; §49.10 scenario 13 without S-6 |
| 11 | Cross-tree continuity: one change set, one transaction; scheme moves or lapses (§50.1 R-1) |
| 12 | One Organisation knowledge boundary (§49.8 (0), §50.8); refusal beyond `closedThrough` |
| 13 | Re-parenting that could alter a rule-derived result raises AllocationReviewNeeded (§49.6 and §51.5) |
| 14 | "Awaiting placement" only if unplaced; `PlacementReviewNeeded` otherwise (§51.1, C-2, §51.5 closure row, §50.5 fallback row, scenarios §50.11 5, §51.6 15) |
| 15 | Closure impact is a report, not a write-time check (closure row; scenario 19) |
| 16 | Vocabulary: "allocation rule", "placement", "change-set placement" used consistently in §50–§51 |
| 17 | B-5 a record-time assertion; B-1 residence wording; D-115 not binding; §51.9 Q-1 and Q-2 marked decided; scenario 23 historical; §51.2 electoral-divisions note ("no address and no organisation area alone") |
| 18 | **D-140**: A-7, the Remove row and scenario 31 (resumption with `PlacementReviewNeeded`; refused allocations never apply) |
| 19 | **A-8**: no Geography read at a later position than the scheme's confirmed one (A-1, A-7, step 2, §49.6, §51.5 Geography row, scenarios 12, 13, 32); a confirmation is an organisational act |
| 20 | **§51.15**: test the 15 cases yourself; do the invariants, lifecycle and algorithm agree in each? |

## 4. Report

Title: **"IR-OTD-02 — Fresh Independent Confirmation"**:
1. **Per item:** CONFIRMED · PARTIAL · MISSING · **CONTRADICTORY** · **NOT ASSESSABLE**, with the exact section and line supporting each confirmation.
2. **Contradictions or material gaps** in §49–§51 (also outside the checklist), each with evidence, impact, and whether a Principal decision is needed.
3. **Verdict:** CONFIRMED (ready for the Principal's acceptance decision) · NOT CONFIRMED (what remains).
4. Reviewer identity (human, or model and version, and the interface used), prior involvement with PublicDigit, integrity method.

**Rules:**
- **Decide nothing for the Principal.**
- Modify nothing; claim no acceptance.
- Mark anything not in the package UNAVAILABLE.
- **Stop after the report.**
