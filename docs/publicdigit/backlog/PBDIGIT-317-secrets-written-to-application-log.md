# PBDIGIT-317: The application key and voters' private keys are written to the application log

**Type:** Bug (security) · **Epic:** none · **Created:** 2026-11-02 · **Raised by:** found during the INV-ORG-SCOPE-1 Stage 1 static investigation (`docs/publicdigit/reviews/2026-11-02-inv-org-scope-1-stage1-static-investigation.md` §5, S-1).

| | |
|---|---|
| **Status** | `OPEN` · **priority: high** · no fix authorised |
| **Classification** | **observed in code** (static). Whether the lines run in production, and who can read the logs, is **not verified** |
| **Scope** | `app/Http/Controllers/Demo/DemoVoteController.php` hash computation and code parsing |

## Finding (static, verified in source at HEAD `0c782012c`)

| Where | What is logged (`Log::info`) |
|---|---|
| `app/Http/Controllers/Demo/DemoVoteController.php:4875-4884` | `private_key`, `vote_id`, **`app_key` (`config('app.key')`)**, and `concatenated` = private key ‖ vote id ‖ app key |
| `app/Http/Controllers/Demo/DemoVoteController.php:4835-4839` | `private_key` together with `vote_id`. These are the two parts of a complete demo verification code (`{private_key}_{vote_id}`). |

**`APP_KEY` is the application's master secret.** In Laravel it protects encrypted cookies, sessions and `encrypt()` values. Here it is also an input to receipt hashes. Anyone who can read the log can therefore potentially:
- forge or decrypt encrypted values;
- recompute receipt hashes;
- open demo votes.

**Not affected:** the real-election path (`app/Http/Controllers/VoteController.php:3583` is a return value, not a log statement).

## Not verified
- Whether these lines are reached in production (the demo voting flow is live).
- Log retention, and who has or had read access to the logs.
- Whether `APP_KEY` has been exposed through them.

## Decision needed (PO + Security)
1. Who may read existing logs, and should the affected log files be purged?
2. Must `APP_KEY` be rotated? Rotation invalidates sessions and encrypted values, and changes receipt-hash verification. That needs its own plan.
3. Authorise a minimal slice that removes the secret values from these log statements.

## Traceability
- `docs/publicdigit/reviews/2026-11-02-inv-org-scope-1-stage1-static-investigation.md` (S-1).
