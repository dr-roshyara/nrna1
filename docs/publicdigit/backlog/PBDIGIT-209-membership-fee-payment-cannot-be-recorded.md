# PBDIGIT-209 — A membership fee payment cannot be recorded: the service writes columns the table does not have

**Type:** Defect (production, data persistence) · **Epic:** `PBDIGIT-EPIC-02` Membership Management · **Created:** 2026-10-01
**Found by:** the backlog-completion review (`docs/publicdigit/reviews/2026-10-01-backlog-completion-review.md` §3): an exception-origin analysis of the full `Unit` + `Feature` run
**Raised by:** `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**ID note:** a draft with this number (merge/release to `main`) was withdrawn **before any commit** on the PO's instruction, and merge/release is now a later concern. No committed record ever used `PBDIGIT-209`, so the number is used here.

| | |
|---|---|
| **Status** | 🔴 **OPEN — defect, runtime-confirmed. Not authorised for implementation.** |
| **Problem** | `App\Services\MembershipPaymentService::recordPayment()` inserts `currency` and `payment_reference` into `membership_payments` (`MembershipPaymentService.php:52-63`, model `$fillable` `MembershipPayment.php:16-28`). **The table has neither column.** It has `transaction_reference` and no currency (`app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_05_03_000001_create_membership_payments_table.php`). Every insert fails: `SQLSTATE[42703]: column "currency" of relation "membership_payments" does not exist`. |
| **Business consequence** | **An organisation cannot record that a member paid a fee.** The routed action `POST /organisations/{organisation}/…/fees/{fee}/pay` (`routes/organisations.php:207` → `MembershipFeeController::pay`, `:100-135`, which calls the service at `:126`) fails. So the fee stays unpaid, and the member's fee status, which feeds standing (`FeePaid` → the fee-paid projection), never updates. Payment-dependent membership rules then act on wrong data. |

## Evidence (re-run 2026-10-01, `php artisan test --env=testing`, test database)

| Test | Result | Cause |
|---|---|---|
| `tests/Feature/Membership/MembershipFeeControllerPayTest.php` (the routed controller) | **5 / 5 fail** | `column "currency" … does not exist` |
| `tests/Feature/Finance/MembershipPaymentServiceTest.php` (the service) | **9 / 9 fail** | same |
| `tests/Feature/Membership/FeePaymentTest.php` | 8 / 8 fail **before reaching the service** | its own fixture (`user_organisation_roles.id` null): test drift, owned by `PBDIGIT-167` |

**Chronology:** the service and model were written on 2026-04-17 (`fdcb1e486`). The table was (re)created on 2026-05-03 (`2bd506bd4`) with a different column set. **No migration adds `currency`, `payment_reference` or `income_id` to `membership_payments`** (`git grep membership_payments -- database app/Contexts`).

**Also writing a non-existent column:** the `MembershipFeePaid` listener `app/Listeners/CreateIncomeForMembershipFee.php:32` does `$event->payment->update(['income_id' => $income->id])`, and `membership_payments` has no `income_id`. That is also in the model's `$fillable`, and the membership-area classification counts 15 failing tests across 4 files with this one root cause. **The scope's column decision covers `income_id` too.**

**Secondary fault on the same line:** `'recorded_by' => auth()->id() ?? $member->organisation_id` (`:61`). `recorded_by` is a foreign key to `users` (migration `:24`), so an unauthenticated caller would write an organisation id into a user reference.

## Existing-story search

`git grep -i` over every `docs/publicdigit/backlog/PBDIGIT-*.md`, the 76–157 backlog and the completeness audit (159–208) for `membership_payments`, `recordPayment`, `record payment`, `fees/{fee}/pay`, `MembershipPaymentService`, `currency` → **no match**.

## Why the existing stories do not cover it

- **`PBDIGIT-165`** (AD-006) is the `FeeTestFactory` `TenantId` fixture. That is a different file and cause, and it is **test-only**: production passes the correct type.
- **`PBDIGIT-167` / `208`** own the **failing tests** as classify-first work. Their scope says each production defect found is *"raised as its own story"*. **This is that story.**
- **`PBDIGIT-04`…`08`** (EPIC-02) are `IMPLEMENTED — NOT VERIFIED` capability verifications. They contain no defect.

## Scope (when authorised)

1. **Decide the canonical shape (PO, small).** Are amount and currency recorded per payment (add the columns), or is currency fixed per fee (`membership_fees.payment_currency` exists, migration `2026_05_04_000003`) and dropped from the payment? Is the reference column `payment_reference` or `transaction_reference`?
2. **RED:** `MembershipFeeControllerPayTest` (existing), with its assertions unchanged, is the failing test.
3. **GREEN:** align the service, the `CreateIncomeForMembershipFee` listener, the model `$fillable` and, if decided, an **additive** migration (`currency`, the reference column, `income_id`). Fix the `recorded_by` fallback so it never writes a non-user id.

## Out of scope

The fee-paid projection's own tests (`PBDIGIT-165`) · `FeePaymentTest`'s fixture (`PBDIGIT-167`) · the renewal or waiver flows · any payment-provider integration.

## Acceptance criteria

- `MembershipFeeControllerPayTest` and `MembershipPaymentServiceTest` pass with their assertions unchanged.
- **Business-state verification:** after `POST …/fees/{fee}/pay`, a `membership_payments` row exists with the method, reference and amount; the fee is `paid` with `paid_at` set; and `FeePaid` was emitted. **Not only the redirect.**
- `recorded_by` always references a user.

## Dependencies / required authority

The PO decides on the column shape (scope 1). Then PO authorisation, RED-first. No architecture decision is needed: this is an existing Membership capability whose persistence drifted.

## Verification method

`php artisan test --env=testing tests/Feature/Membership/MembershipFeeControllerPayTest.php tests/Feature/Finance/MembershipPaymentServiceTest.php`, then the business-state assertions above and one manual payment through the UI in a test organisation.

**Traceability:**
- **Code:** `MembershipPaymentService.php:52-63` · `MembershipPayment.php:16-28` · migration `2026_05_03_000001` · `MembershipFeeController.php:100-135` · `routes/organisations.php:207`.
- **Commits:** `fdcb1e486`, `2bd506bd4`.
