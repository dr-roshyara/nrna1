# PBDIGIT-318: Membership–Election boundary remediation (ratified "no shared persistence")

**Type:** Architecture remediation · **Epic:** none · **Created:** 2026-11-02 · **Raised by:** strategic DDD discovery (Membership–Election separation).

| | |
|---|---|
| **Status** | `OPEN` · plan **PROPOSED**; no slice authorised (S1 is the first candidate) |
| **Classification** | **observed in code** (static, HEAD `ce84016a6`) |
| **Scope** | V1 Membership writes Election data; V2 Election reads Membership / Organisation tables; V3 cross-context foreign key |

## Rule

"There is no shared persistence and no Membership → entitlement shortcut" (`docs/publicdigit/reviews/2026-10-08-membership-final-ratification-verdict.md:46`, ratified).

## Plan and decisions

- Plan: `docs/plans/20261102-0339-membership-election-boundary-remediation-plan.md` (slices S1–S4).
- Decision record: `docs/publicdigit/reviews/2026-11-02-membership-election-separation-decision-record.md`.

## Not authorised

Any code, test or schema change, until the PO approves a slice.
