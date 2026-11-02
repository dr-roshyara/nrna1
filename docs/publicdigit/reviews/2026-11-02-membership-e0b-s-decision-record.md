# Membership E0b-S: decision record (types, obligations, dues, Standing)

**Date:** 2026-11-02 · **Recorded by:** Claude (DDD architect), on the PO's instruction
**Status:** **E-1 DECIDED by the PO (2026-11-02).** E-2…E-5 are prepared and OPEN. E-6 belongs to Finance.
**Evidence:** `docs/publicdigit/reviews/2026-11-02-membership-e0b-s-types-obligations-dues-standing-discovery.md` (the discovery report; its §B sets out the conflict, its §E the options).
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.

---

## 1. E-1: who owns the dues obligation. DECIDED (PO, 2026-11-02)

**PO words:** "E-1 option (a), record it and commit".

**Decision (option (a), as worded in the discovery report §E-1):**

> "Membership owns the dues obligation: the fact that a member owes an amount for a stretch of membership time, derived from the type's dues rule and referencing the period. Finance owns payment, settlement, refunds, credits and accounting, and publishes payment facts that Membership consumes. This reading does not amend act §11."

**Who owns what after this decision:**

| Fact | Owner |
|---|---|
| Dues obligation (what is owed, for which stretch of membership, due when) | **Membership** |
| Payment, settlement, refunds, credits, accounting | **Finance** (ratified act §11, unchanged) |
| Standing (are the obligations met at time t?) | **Membership**, derived from its obligations and Finance's payment facts (canonical §4, unchanged) |
| Whether Standing matters for a vote | **Election** (INV-11, unchanged) |

**What it resolves:** the CONFLICTING readings recorded in the discovery report §B, as follows:
- readiness §21 ("Membership owns the fee facts"): **followed** for the obligation;
- act note N-5 ("fees are Finance's") and roadmap BR-16 ("Finance owns fees"): **read as referring to payment facts**. N-5 is non-binding and is not edited (the act is frozen). BR-16 carries annotation R-8.

**What it does not change:**
- ratified act §11 (Finance: settlement, refunds, credits, accounting);
- canonical §8 ("fees reference periods"; `PeriodTrimmed` / `TrimRevived`);
- INV-11;
- D-3 (non-payment is a ground for exclusion, never an ending act).

**What it does not authorise:**
- any code, schema or data change;
- reversing the legacy fee flow (`MembershipPaymentService` → `MembershipFeePaid` → Finance). That remains the earlier, separate FeePaid-direction question and a later, separately authorised slice;
- any change to `membership_fees` or the two legacy standing columns (PBDIGIT-322; roadmap W8/W9).

**Consequences for later work (recorded, not decided):**
- the obligation references the **period** (and type segment), not the legacy member row (discovery report §D item 2);
- Finance consumes obligation facts in order to book them; the contract is part of the Finance integration (roadmap W8, slice 11);
- DS-1 (fees for trimmed periods) stays Finance's policy. Membership supplies `PeriodTrimmed` / `TrimRevived`.

## 2. Prepared, not decided (E-2…E-5)

The exact proposed wording is in the discovery report §E. Status of each: **OPEN**.

| # | Question | Recommendation (PROPOSED) |
|---|---|---|
| E-2 | What "in good standing" means | met when every obligation due by t is paid, waived or exempted, within the organisation's grace period; partial payment does not meet an obligation |
| E-3 | Type change within a period (BD-TYPE), and dues | yes; a new type segment from its effective date; dues follow the new type; proration per the organisation's dues rule |
| E-4 | The voting label on a type (BD-GVR) | a classification only; Election alone decides whether its voter list requires it |
| E-5 | Lifetime and dues-free types | lifetime never lapses; no dues means no obligations, so Standing is trivially met |
| E-6 | Fees for trimmed periods (DS-1) | Finance's policy; no Membership decision |

## 3. Traceability

- Discovery report: §B (conflict), §D (model), §E (options and wording), §F (gaps).
- Ratified: act §11; canonical §4, §8; INV-11.
- Decided: D-3.
- Roadmap (ADOPTED): BR-16 annotated R-8.
- Backlog: PBDIGIT-322; PBDIGIT-318 (V1b, V2).
