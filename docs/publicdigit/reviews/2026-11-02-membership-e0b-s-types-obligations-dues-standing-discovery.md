# Membership E0b-S discovery: types, obligations, dues and Standing

**Date:** 2026-11-02 · **Author:** Claude (DDD architect; authority: generated)
**Status:** **DISCOVERY REPORT. It decides, accepts and authorises nothing.** Proposals are marked PROPOSED; the decisions it prepares (E-1…E-6) are **not taken**.
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` convention.
**Baseline:** branch `election-flow-updated`, HEAD `e738be2e8`. Read-only inspection (`git`, `grep`, `sed`); no tests run; no database or production access.
**Scope:** inside the ratified Membership boundary only (foundation audit §10, `2026-11-02-foundation-boundary-and-ubiquitous-language-audit.md`).

**What E0b-S is.** In earlier documents "E0b" meant *MembershipType → obligations → dues → standing*. That track was renamed **E0b-S** ("standing") to avoid a clash with E0b, the architecture finalisation (`2026-10-08-e0b-membership-architecture-finalization.md:6`). E0b-S was declared research-ready (`:68`), and Standing was left out of scope (`:351`). DS-5 routes "standing" to E0b-S, and DS-1 routes the fee policy for trimmed periods to Finance / E0b-S (`2026-10-08-membership-decision-register-final.md:86, :90`).

**Labels:**

| Label | Meaning |
|---|---|
| RATIFIED | ratification record of 2026-10-08 |
| DECIDED | recorded PO decision |
| OPEN | not decided |
| PROPOSED | this report's suggestion |
| CONFIRMED | read in code or schema at the baseline |
| INFERRED | concluded, not executed |
| CONFLICTING | the sources disagree |

---

## A. What the authoritative records already say

| Statement | Status | Source |
|---|---|---|
| A **membership type** is the class held during a *segment* of a period (`CurrentType`); a ChangeType fact starts a new segment | RATIFIED (concept). Mid-period change is **BD-TYPE**, OPEN (recommended: yes) | canonical `2026-10-08-membership-architecture-ratified.md` §2, §4; decision register `2026-10-08-membership-final-decision-register.md:46` |
| **Standing** is dues compliance, **derived** (never stored) from fee facts; Membership publishes it | RATIFIED (placement); content OPEN (DS-5 → E0b-S) | canonical §4 (`:109`); readiness `2026-10-08-membership-architecture-ratification-readiness.md:328` |
| **Lapse ≠ non-payment ≠ ending.** Lapse is a derived term gap. Unpaid is a fee fact, from which "standing not compliant" is derived. An ending happens **only** through an explicit End fact | RATIFIED (canonical INV-3; readiness §23 "resolved"); DECIDED (D-3: non-payment is a *ground* for exclusion, not an ending act; term expiry is derived) | readiness `:207-215`; `2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md:292` |
| A restriction derived from arrears is **not** `Suspended`. It is a separate, derived restriction; consumers receive bans and standing restrictions separately | RATIFIED derivation | e0b `:206-222`; readiness `:192-203` |
| **Finance** owns settlement, refunds, credits and accounting. "Membership may provide membership-related business facts, but does not become the accounting context" | RATIFIED | act `2026-10-08-membership-architecture-ratification-act.md` §11 |
| Finance's "fees reference periods"; Membership publishes `PeriodTrimmed` / `TrimRevived` | RATIFIED | canonical §8 |
| Fees for the trimmed portion of a period (**DS-1**) | OPEN: Finance / E0b-S | decision register (final) `:86` |
| A type flag "grants voting rights" may be kept as a classification only (**BD-GVR**) | OPEN (recommended: classification only); bounded by **INV-11** (RATIFIED: no voting entitlement from Membership) | decision register `:47`; canonical §5 |

## B. The central conflict: who owns the dues obligation?

There are three different facts. The sources disagree only about the first.

| Fact | Readiness §21 (pre-ratification, `:333`) | Act note N-5 (non-binding) and roadmap BR-16 (a reading) |
|---|---|---|
| **Obligation:** "member X owes €Y for period/segment Z, due on D" | **Membership** ("the Fee aggregate references PeriodId") | **Finance** ("fees are Finance's") |
| **Payment / settlement:** "€Y received and settled" | Finance (accounting projection) | **Finance** |
| **Standing:** "are X's obligations met at time t?" | Membership (derived) | Membership (derived from Finance evidence) |

**What governs.** Ratified act §11 does **not** list fees or dues among Finance's possessions; it lists settlement, refunds, credits and accounting. N-5 is explicitly non-binding ("for EP-01 to honour, not decisions"). BR-16 (roadmap `20261016_2219_…_completion_plan.md`) is a reading of the sources.

**Result: CONFLICTING. A PO decision is required (E-1).** Every other E0b-S rule depends on it.

## C. What the legacy code does today (CONFIRMED unless marked)

**Type:** `membership_types`; model `app/Models/MembershipType.php`. It carries:
- `fee_amount` / `fee_currency`: dues;
- `duration_months`: the term. `MembershipRenewalController.php:59` adds it to the expiry; `null` means lifetime (`isLifetime()`);
- `requires_approval`: the admission process;
- `form_schema`: the application form;
- `grants_voting_rights`: the voting flag;
- `is_active`, `sort_order`.

**One legacy "type" mixes five concerns:** class, dues, term, admission process, voting.

**Obligation:** `membership_fees` (`2026_04_03_155707_create_membership_fees_table.php`):
- one row per member and type: `amount`, `fee_amount_at_time` (NOT NULL), `period_label` (free text, e.g. "2025"), `due_date`, status `pending / paid / waived / overdue`;
- payment fields on the same row (later also `payment_amount`, `transaction_reference`).

It references `members`, not a period. **Obligation and payment are one row.**

**Two standing columns on `members`:**

| Column | Values in code | Writers |
|---|---|---|
| `fees_status` | the original enum `paid / unpaid / partial / exempt` (`2026_04_05_000001`). **On PostgreSQL, `2026_04_19_000001_convert_all_enums_to_varchar_for_postgresql.php:43` replaced it with a CHECK constraint allowing only `exempt / pending / paid / overdue`** | `RecalculateMemberFeeStatus` (writes `unpaid`, `partial`, `paid`, `exempt`); `MarkOverdueMembers` (writes `overdue`); `OrganisationMemberInvitationController.php:216` (writes `unpaid`) |
| `fee_state` | `unpaid / pending / paid / overdue / waived` (`FeeState` enum) | `MemberFeeStateListener` (DDD `FeePaid`) |

Consequences:
- The writers and the constraint disagree. See §F item 2 and PBDIGIT-322.
- `NewsletterService.php:184` and `Member::getVotingRightsAttribute` read `unpaid` / `partial`, values the PostgreSQL constraint forbids.
- The two-column problem was first recorded on 2026-10-08 (`2026-10-08-e0a2-membership-development-report.md:218`).

**History rewritten by a data migration:** `2026_04_15_140049_backfill_active_members_fees_status_to_exempt.php` converted active members with `fees_status='unpaid'` to `exempt`, so the voter query would include them (its docblock, `:11-14`). Dues compliance was set by a data fix, not by a fee fact. This matters for migration (BR-18: never invent truth).

**Voting derived from type and dues:**
- `Member::getVotingRightsAttribute` (`Member.php:104-133`) returns full / voice_only / none from status × `grants_voting_rights` × `fees_status`.
- The Election queries filter `fees_status ∈ {paid, exempt}` and `grants_voting_rights` (`EloquentVoterEligibilityQueryService.php:110-116`; `VoterEligibilityService.php:78-84`).
- **Both conflict with INV-11.** They are part of the baselined violations V1b and V2 (PBDIGIT-318).

**Scheduled jobs** (`routes/console.php:49, :51`, daily):
- `MarkOverdueMembers` overwrites statuses in place (conflicts with INV-6).
- `GenerateAnnualMembershipFees`:
  - reads `membershipType->annual_fee`, which does not exist (the column is `fee_amount`), so every amount would be 0;
  - does not set the NOT NULL `fee_amount_at_time`, so the insert is expected to fail;
  - `description` is not fillable and is silently dropped;
  - runs only in production (`isProduction()`).
  
  These consequences are **INFERRED**: not executed.

**New Membership kernel:** no type, obligation or Standing concept yet (CONFIRMED: absent in `app/Contexts/Membership/Domain/Organisational/`).

## D. Business model for E0b-S (PROPOSED; decides nothing)

Consistent with the ratified rules. Where it depends on E-1, both branches are shown.

1. **The membership type is a classification of a period segment.** In business terms it carries:
   - (a) the **class**;
   - (b) a **term rule** (length, or lifetime), used by Renew;
   - (c) a **dues rule** (amount, interval, exemptions);
   - (d) the admission process (approval required; application form, D-2);
   - (e) an optional **voting classification label** that Election may read. It never grants anything (INV-11; BD-GVR).
2. **An obligation arises from the type's dues rule for a stretch of membership time** (period × type segment × billing interval). It references the **period**, not a legacy member row (canonical §8).
   - *If E-1 = Membership:* Membership records the obligation fact and Finance books it.
   - *If E-1 = Finance:* Finance raises it from Membership's period, type and term facts.
3. **Payment, waiver, refund and credit facts are Finance's** (act §11). Membership consumes them. This is the target direction; the legacy code is reversed (`MembershipPaymentService`).
4. **Standing(p, o, t)** is a two-valued derived predicate over the obligations due by t and their settlement facts. The rules are E-2.
5. **Arrears consequences stay separate:**
   - a standing restriction (derived, by statute rule);
   - optionally an exclusion, decided by an authorised body and recorded as End(exclusion) (D-3);
   - never an automatic ending, never `Suspended`.
6. **Type change mid-period** (if BD-TYPE = yes) starts a new segment. Dues proration is part of the dues rule (E-3).
7. **Trimmed periods (DS-1):** Membership publishes `PeriodTrimmed` / `TrimRevived`; what happens to charged fees is Finance's policy.
8. **Published:** `Standing` and `CurrentType` (with its classification label), with (MembershipId, asOf). **Election decides** whether its voter list requires either.

## E. Decisions prepared for the PO (dependency order; none taken)

### E-1. Who owns the dues obligation?

> **Status update (2026-11-02): DECIDED by the PO, option (a)**, as worded below. Record: `2026-11-02-membership-e0b-s-decision-record.md` §1. The analysis below is unchanged.

**Options:**
- **(a) Membership** (readiness §21).
  - Pro: obligations follow periods and types directly; Standing needs only Finance's payment facts.
  - Con: Membership is closer to accounting.
- **(b) Finance** (N-5, BR-16).
  - Pro: all money facts in one context.
  - Con: Finance must interpret Membership's terms and types; Standing depends on two Finance fact streams.

**Recommendation: (a), limited to the obligation fact.** The amount, interval and exemption are membership terms, and act §11 explicitly lets Membership "provide membership-related business facts". Settlement, refunds, credits and accounting stay with Finance.

**Proposed wording:**
> "Membership owns the dues obligation: the fact that a member owes an amount for a stretch of membership time, derived from the type's dues rule and referencing the period. Finance owns payment, settlement, refunds, credits and accounting, and publishes payment facts that Membership consumes. This reading does not amend act §11."

**Not authorised:** any code change; any change to the existing fee flow.

### E-2. What "in good standing" means

An organisation rule with platform defaults.

Recommended defaults:
- waived and exempt count as met;
- partial does not;
- a configurable grace period;
- judged over all obligations due so far.

**Proposed wording:**
> "Standing(t) is met when every dues obligation due on or before t is paid, waived or exempted, allowing the organisation's grace period. Partial payment does not meet an obligation."

### E-3. Type change during a period (BD-TYPE), and dues

> "Yes. A type change starts a new type segment from its effective date. Dues follow the new type from that date; proration is part of the organisation's dues rule."

### E-4. The voting classification label (BD-GVR)

> "A type may carry a voting classification label. It is published as a classification only. Election alone decides whether its voter list requires it. Membership never derives voting rights."

The existing `voting_rights` derivation remains a recorded deviation, to be removed under PBDIGIT-318 S2/S3.

### E-5. Lifetime and dues-free types

> "A type without a term (lifetime) never lapses. A type without dues creates no obligations, so Standing is trivially met."

### E-6. Fees for trimmed periods (DS-1)

**Finance's policy.** It is listed here only so it is not lost. No decision is needed from Membership.

## F. Implementation gaps (not fixed)

| # | Finding | Status | Handling |
|---|---|---|---|
| 1 | Two standing columns with different value sets; eligibility reads only `fees_status` | CONFIRMED | migration (W9); target = derived Standing |
| 2 | `fees_status` writers (`unpaid`, `partial`) and readers disagree with the PostgreSQL CHECK (`exempt, pending, paid, overdue`). **Correction:** an earlier conversation draft said `MarkOverdueMembers` wrote a disallowed `overdue`. On PostgreSQL `overdue` is allowed; the disallowed values are `unpaid` and `partial` | CONFIRMED statically (code + migrations). Whether the constraint exists in production depends on whether `2026_04_19_000001` ran there: **not verified** | **PBDIGIT-322** |
| 3 | `GenerateAnnualMembershipFees`: non-existent `annual_fee`; NOT NULL `fee_amount_at_time` not set; runs daily in production | INFERRED (not executed; production logs not checked) | **PBDIGIT-322** |
| 4 | Obligation and payment in one row, keyed to the member, not the period | CONFIRMED | E-1, then roadmap W8 |
| 5 | Voting rights derived from type × fees | CONFIRMED; baselined (V1b, V2) | PBDIGIT-318 S2/S3; E-4 |
| 6 | Statuses overwritten in place by jobs and by a data backfill (`2026_04_15_140049`) | CONFIRMED | migration (BR-18) |
| 7 | No type, obligation or Standing in the new kernel | CONFIRMED | roadmap W7 / W8 after E-1…E-5 |

## G. Readiness and next step

- **Ready for PO decisions.** The E0b-S business model is complete once E-1…E-5 are answered (E-6 is Finance's).
- No ratified rule changes. Every proposal stays inside INV-11, act §11 and D-3.
- **Next action:** the PO decides **E-1**. Then E-2…E-5 can be decided in one pass and recorded as an E0b-S decision record that cites this report.

## Traceability

- Ratified: canonical §2, §4, §5, §8; act §11.
- Decided: D-1…D-5.
- Registers: `2026-10-08-membership-decision-register-final.md` (DS-1, DS-5); `2026-10-08-membership-final-decision-register.md` (BD-TYPE, BD-GVR).
- Pre-ratification: readiness §11, §21, §23; e0b finalisation `:6, :68, :206-222, :351`.
- Roadmap (ADOPTED): BR-16, stages W7–W9.
- Backlog: PBDIGIT-318 (V1b, V2), PBDIGIT-322.
