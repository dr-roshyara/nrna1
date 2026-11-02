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

## Triage authorised (PO, 2026-11-02)

**Independent security triage is authorised.** Steps:
1. establish facts;
2. assess exposure;
3. contain if needed;
4. remediate (a separately authorised slice);
5. verify;
6. record closure evidence.

**Known statically [F]:**
- The lines use the default log channel (`Log::info`). By default that is `stack` → `single` → **`storage/logs/laravel.log`** (`config/logging.php:20,40,46`).
- **Unknown:** whether the environment overrides `LOG_CHANNEL`; whether the lines ran in production; who can or could read the logs.

**First step, for the PO / Ops (not Claude):** read-only check of log location, retention and access in each environment.
- Claude does not read logs or `.env`.
- No secret values may be copied into any record.

## Traceability
- `docs/publicdigit/reviews/2026-11-02-inv-org-scope-1-stage1-static-investigation.md` (S-1).
