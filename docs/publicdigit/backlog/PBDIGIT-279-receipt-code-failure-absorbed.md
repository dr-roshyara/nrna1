# PBDIGIT-279: A failed receipt-code write is absorbed; the vote counts without a receipt

**Type:** Defect (voting integrity / voter trust) · **Epic:** none (voting) · **Created:** 2026-10-13 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from `PBDIGIT-274` S9a (finding F-A). It is out of scope there by Human PO decision of 2026-10-13: "Accept + follow-up story". **Index row:** added 2026-10-13.

| | |
|---|---|
| **Status** | `OPEN` · not authorised |

## Finding (executed evidence, not hypothesis)
- **What happens:** in `VoteController::store`, `ReceiptCode::firstOrCreate` (`app/Http/Controllers/VoteController.php:1886`) runs inside a savepoint (Laravel `createOrFirst`, `vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:607-612`).
  - When the insert fails, the savepoint is rolled back, `catch (\Exception)` at `:1897` logs and continues, and the cast commits.
  - Result: the vote, `codes.has_voted` and the slug's `has_voted` are committed, the voter sees **"Your vote has been successfully submitted."**, and **no `receipt_codes` row exists**.
  - The emailed code (`private_key_vote_id`) is that receipt, so it cannot be re-verified later.
- **Evidence:** `tests/Isolated/Pbdigit274/Database/CastSuccessContractTest.php::test_receipt_code_failure_is_absorbed_the_vote_counts_without_a_receipt`, a characterisation that pins today's behaviour; run output in `docs/publicdigit/election/evidence/2026-10-13-pbdigit274-s9a-cast-contract/`.
- **The planned hypothesis** in PBDIGIT-274 (that a swallowed error aborts the PostgreSQL transaction) was **refuted** for this write. It holds for the step-5 record, which has no savepoint.

## Decision needed (business)
- (a) A receipt failure fails the whole cast: rollback, and "please try again".
- (b) The cast stands and the receipt is retried or repaired after commit.
- (c) The cast stands, and the voter is told that no receipt is available.

The choice affects voter trust and receipt verification. When this item is implemented, the characterisation test changes from "pin" to the decided behaviour (RED → GREEN).

## Related
- `PBDIGIT-274` (plan section 9, G7).
- The O-15 follow-up: the "vote confirmed" email is sent before the commit.
