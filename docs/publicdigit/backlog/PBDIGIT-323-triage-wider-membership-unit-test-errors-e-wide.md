# PBDIGIT-323: Triage the wider Membership unit-test errors (E-WIDE)

**Type:** Technical debt (test suite) · **Epic:** none · **Created:** 2026-11-02 · **Raised by:** C-1 G3 acceptance (PO, 2026-11-02, dispositions record §1 "G3 reconsidered", residual risk 5). Gap **G-5** ("E-WIDE errors with no owner") now has this item as its tracking owner.

| | |
|---|---|
| **Status** | `OPEN` · **not authorised.** A read-only triage run needs the PO's authorisation |
| **Classification** | **reported**, not re-verified at HEAD |
| **Scope** | `tests/Unit/Contexts/Membership/Domain/` and `tests/Unit/Contexts/Membership/Application/` **outside** the `Organisational/` subdirectories (Committee, Fee, Member, Membership) |

## Finding (as reported)

From the independent review `docs/publicdigit/reviews/2026-10-10-membership-c1-correction-independent-review.md:48, :55`:
- Running the whole Membership unit directories gives **27 errors** in Domain (265 tests) and **29** in Application (81 tests).
- The files concerned are untouched by C-1.
- The suspected causes are "TenantUserId format, handler constructor arity".
- The documented C-1 counts, E-RUN-V2 and E-RUN-W0 cover **only** the `Organisational/` subdirectories.

## Why it matters

- These tests cover legacy and other Membership code (Committee, Fee, Member). Production still runs on that code.
- A red wider suite hides new regressions.
- The Committee tests are also relevant to DR-07a, the committee code that is to move to Governance.

## Proposed triage (needs authorisation)

1. A database-free re-run at a named commit in a clean worktree, if these tests are database-free. **Confirm first:** if any test needs a database, stop (ARB-SEC-01).
2. Classify each error: a test defect (stale fixture or format), a code defect, or obsolete code.
3. Propose one owner and one action per class.

**No fix is part of the triage.**

## Not in scope

- Any code or test change.
- The `Organisational/` kernel suites (green; E-RUN-W0).

## Related

- dispositions record §1 (G-5; residual risk 5);
- PBDIGIT-268 (C-1);
- PBDIGIT-269 (mutation runner);
- DR-07a (committee ownership).
