# IR-OTD-01-C2: second confirmation review of the Organisation tactical design (§49–§51)

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-10, on the Principal DDD Architect's advice |
| **Status** | **COMMISSION, READY TO HAND OVER** |
| **Reviewer** | **A fresh reviewer: not Claude, and preferably not the DeepSeek model** that wrote IR-OTD-01 and IR-OTD-01-C (to reduce self-confirmation risk). A human architect or another model family |
| **Purpose** | **Confirm that every finding of IR-OTD-01 and IR-OTD-01-C, and every Principal decision D-118 … D-122, is implemented in the normative text of §49–§51** (rules, invariants, the descent algorithm, lifecycle tables, scenarios), consistently, **with no remaining or newly introduced contradiction** |
| **Not in scope** | A new architecture review. Report other defects **only** if they are contradictions inside §49–§51 or with D-114 … D-122 |

## 1. Package (SHA-256, 2026-11-10)

| File | SHA-256 |
|---|---|
| `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` (**subject**) | `d60af0a2fd66a4d913d9368c18c82e37a9a4208a2db4706819917b67e1288132` |
| `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` (decision record; D-114 … D-122 in §11.43 … §11.47) | `1405e12ff518f061767bae6494dc951855285eea77d0f9a5e8706d7b348dfb65` |
| `docs/publicdigit/architecture/reviews/2026-11-10-ir-otd-01-independent-review.md` (first review) | see `MANIFEST.sha256` |
| `docs/publicdigit/architecture/reviews/2026-11-10-ir-otd-01c-confirmation-review.md` (first confirmation, NOT CONFIRMED) | see `MANIFEST.sha256` |

**How to read the subject:**
- §49–§51 revise §9–§12, §38 and §45 of the same file; where they differ, §49–§51 govern.
- **Inside §51, §51.11–§51.14 and the "Update" paragraphs are history and summaries. The normative text is:**
  - the invariant tables (§49.4, §50.6, §51.4);
  - §50.15;
  - the descent algorithm (§51.3);
  - the lifecycle tables (§50.5, §51.5);
  - the scenarios (§49.10, §50.11, §50.15, §51.6).
- **A correction that appears only in a summary or update counts as MISSING.**

## 2. Decisions to implement exactly (read the record, not this summary)

| Decision | Content |
|---|---|
| D-114 | Every effective member has a geographic unit on every date; the root by default |
| D-116 | An exception prevails while it stands; ends by an act, a later decision for that member, a later decision of an equal or higher authority expressly addressing it, or closure of its destination; an ordinary placement is a floor |
| D-117 | Allocation rules apply to all qualifying members from their effective date |
| D-118 | One geographic member belonging by default; the rules may allow more |
| D-119 | A higher body prevails on the same scope whatever the order; exceptions only by the overridden rule's authority or higher; allocations target direct children; override within the lower scope; **the written rules may provide otherwise** |
| D-120 | The root exists before the first admission; cannot close while coverage exists; dissolution is a separate act |
| D-121 | "Awaiting placement" only where no rule or act places the member |
| D-122 | D-118 kept; a new root moves no member without a decision; notice `PlacementReviewNeeded` |
| D-115 | **Working direction only (not binding)** |

**Deliberate choices:**
- **IR-OTD-01's M-1 proposal was not adopted** (D-119 governs).
- **Its H-1 recommendation (no default) was rejected** (D-118, D-122).
- **D-116 read with D-119:** a later decision ends an exception only if made by an equal or higher authority. **Check whether this reading is faithful to both.**

## 3. Checklist

| # | Item | Must be in the normative text |
|---|---|---|
| 1 | D-118 | B-3 default one; second refused unless the rules allow |
| 2 | D-119 authority, both directions | §51.5 "Adopt", "Change", "Remove allocation" and "Higher body overrides" refuse lower-body acts whatever the order; §51.3 step 4 exception grant **and** termination by equal or higher authority only; scenarios §51.6 25–26 |
| 3 | D-119 written-rules clause | A-3 "unless the organisation's written rules provide otherwise" |
| 4 | D-119 direct child | A-2 and A-3 consistent ("direct child") |
| 5 | H-3 | A-1 over canonical identities at τ; AllocationReviewNeeded on a merge of allocated areas; interim "keeps the destination of the recorded reference" (scenario 12) |
| 6 | D-120 | S-9 with the dissolution carve-out and the C2 read; **§49.7 step 4 checks S-0 … S-5, S-7, S-9 (not S-6)**; B-7 rests on S-9 |
| 7 | D-122 / C-1 | B-7 default "root as at admission"; §51.3 step 1 the same; S-9 "no member moved without a decision"; scenarios §50.15 27–28 |
| 8 | H-5 | Re-admission: rule-derived re-applies; explicit placements and exceptions do not revive (§50.11 scenario 10) |
| 9 | H-6 | §51.3 step 4: placement is a floor, only an exception fixes; S-6 withdrawn; §49.10 scenario 13 no longer cites S-6 |
| 10 | M-2 | Cross-tree continuity: one change set over both trees, one transaction; scheme moves or lapses (§50.1 R-1) |
| 11 | M-3 | One Organisation knowledge boundary (§49.8 (0), §50.8) |
| 12 | M-4, item 10 | Designated scheme; re-parenting that could alter a rule-derived result raises AllocationReviewNeeded in §49.6 **and** §51.5 |
| 13 | D-121, D-122 | "Awaiting placement" only if unplaced; `PlacementReviewNeeded` otherwise: §51.1, C-2, §51.5 closure row, §50.5 fallback row, scenarios §50.11 5 and §51.6 15 |
| 14 | M-6 | Closure impact is a report, not a write-time check (§51.5 closure row; scenario 19) |
| 15 | M-7 | "Allocation rule" (area rule) versus "placement" (individual act) and "change-set placement" used consistently in §50 and §51 |
| 16 | M-8 | B-5 a record-time assertion |
| 17 | L-1 … L-9 | Containment-slot citation (not G-4); "valid at d, as known at τ" in step 2; D-115 not binding in the §51 status block; tree named in step 1; B-1 residence wording; scenario 23 as historical data; §51.9 Q-1 marked decided |

## 4. Report

Title: **"IR-OTD-01-C2 — Second Confirmation Review"**:
1. **Per item:** CONFIRMED · PARTIAL · MISSING, with the line(s).
2. **Remaining or new contradictions** within §49–§51 or with D-114 … D-122.
3. **Verdict:** CONFIRMED (ready for the Principal's acceptance decision) · NOT CONFIRMED (what remains).
4. Reviewer identity (human, or model and version), prior involvement with PublicDigit, integrity method.

**Rules:**
- Confirm only.
- **Decide nothing for the Principal.**
- Modify nothing; claim no acceptance.
- Mark anything missing from the bundle UNAVAILABLE.
- **Stop after the report.**
