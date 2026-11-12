# IR-OTD-01-C3: third (narrow) confirmation review of the Organisation tactical design (§49–§51)

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-10 |
| **Status** | **COMMISSION, READY TO HAND OVER** |
| **Reviewer** | **A fresh reviewer: not Claude and not the DeepSeek model that wrote IR-OTD-01, -C and -C2** (it disclosed a self-confirmation limit in -C2). A human architect or another model family |
| **Purpose** | **Narrow:** confirm that the two residuals of IR-OTD-01-C2 and the Principal's decision **D-136** are implemented in the **normative text**, consistently with D-114 … D-122 and D-136, **and that this round introduced no new contradiction** |
| **Not in scope** | A full re-review. Other items were confirmed in IR-OTD-01-C2 (15 of 17); report them only if this round's edits broke them |

## 1. Package (SHA-256, 2026-11-10)

| File | SHA-256 |
|---|---|
| `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` (**subject**) | `fa68a5897bc3c5cfe3fdeed57b826ded072a226b171ca71c7157d7c025a17dbe` |
| `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` (decision record) | `91536d017c9a5f2b4293341d08a54ee33ce3796c0e6e7a5daadea82d52f4a874` |
| `docs/publicdigit/architecture/reviews/2026-11-10-ir-otd-01c2-confirmation-review.md` (the previous confirmation) | see `MANIFEST.sha256` |

**Normative text:**
- the invariant tables (§49.4, §50.6, §51.4);
- §50.15;
- the descent algorithm (§51.3);
- the lifecycle tables (§50.5, §51.5);
- the scenarios.

**§51.11–§51.14 and the "Update" paragraphs are history.** A correction found only there counts as **MISSING**.

## 2. Decisions (read the record, §11.43–§11.47 and **§11.49**)

- **D-119** (§11.46): a higher body prevails on the same scope whatever the order; exceptions only by the overridden rule's authority or higher; allocations target direct children; **the written rules may provide otherwise**.
- **D-136** (§11.49, new): **a higher body's allocation governs every area it covers, including contained areas, whatever the order.** A lower body cannot narrow or carve out part of it by a more specific allocation **unless the written rules expressly give it that competence** (an identifiable provision). **Where competence is unknown or not established, the lower allocation does not take effect: refused and reported.** **Among allocations of equal authority, the most specific area wins.**
- D-116 (exceptions), D-114, D-117, D-118, D-120 … D-122 as before.
- **Numbering note:** **D-123 … D-135** (§11.48) are **other decisions** (election entitlement, people register, fees) by another session. D-136 was first appended as a second "D-123" and then renumbered. **Check that no reference in the subject points to the wrong D-123.**

## 3. Checklist

| # | Item | Must be in the normative text (approximate line) |
|---|---|---|
| 1 | C2 item 2 / D-136 | **A-7** row in §51.4 (≈1419): authority first, whatever the order; contained areas; the written-rules competence exception with an identifiable provision recorded as basis; **unknown competence refused and reported**; specificity only among equal (or expressly authorised) allocations; no authority inferred from names, levels or containment |
| 2 | C2 item 2 / D-136 | **Adopt allocation** row in §51.5 (≈1434): refuses a lower body's allocation for the same or a contained area while a higher body's stands, with the same exception |
| 3 | C2 item 17 / L-1 | A-7 cites the containment slot `(CONTAINMENT, unit, scheme)`, not G-4; designated administrative scheme; **valid at d, as known at τ**. **No dangling A-7 reference** remains (A-1 refers to a real row) |
| 4 | C2 precision notes | §51.3 **step 4** (≈1405): (a) "a later decision for that member" by an authority **at least equal to the one that granted the exception**; (b) equal or higher **than the granting authority**, expressly addressing it; a later general rule does not end it unless it says so; **D-119's written-rules clause** |
| 5 | Scenarios | §51.6 **25–31** (≈1476–1482): removal refused; lower placement does not end an exception; same area refused; contained area refused; authorised by written rules takes effect; rules not on file refused; higher body later prevails from its date |
| 6 | Status hygiene | D-115 **not** in the "Binding" list (≈1367); §51.9 **Q-2** marked decided (≈1511) |
| 7 | No new contradiction | Between A-7 and A-1, A-2, A-3, step 2 (descent), C-2, the closure rows, and D-114 … D-122, D-136 |

## 4. Report

Title: **"IR-OTD-01-C3 — Narrow Confirmation Review"**:
1. **Per item:** CONFIRMED · PARTIAL · MISSING, with lines.
2. **Any new contradiction.**
3. **Verdict:** CONFIRMED (ready for the Principal's acceptance decision) · NOT CONFIRMED (what remains).
4. Reviewer identity (human, or model and version), prior involvement, integrity method.

**Rules:**
- Confirm only.
- **Decide nothing for the Principal.**
- Modify nothing; claim no acceptance.
- Mark anything missing from the bundle UNAVAILABLE.
- **Stop after the report.**
