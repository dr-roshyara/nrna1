# PBDIGIT-274 S9a: cast success / failure contract (G7), 2026-10-13

**Authority:** Human PO ("start S9"; decisions on the findings, 2026-10-13: **F-B "Repair in 274"**, **F-A "Accept + follow-up story"**).

**Scope:**
- `pbdigit274_isolated_test` only: the committing test base and identity check, with a second connection `t274_c2` (a separate backend pid) on the same database.
- No password and no voting link appear in this folder; the test prints `[slug]` instead.

## Mechanism (section 9.1): executed for the first time, behaved as designed
- **Trigger creation:** plpgsql triggers raising `t274 injected` are created on C1 outside any transaction and are visible to the in-process request.
- **Verification:** C2 reads committed state only.
- **Cleanup:** on C2, then a check that no `t274_%` trigger or function is left. Every test ends with no leftovers.
- **After each request:** the transaction level is back at 0, and the `voter_slug_transition:{id}` lock is free.
- **Audit stub:** `ElectionAuditService` is replaced by a stub, so no audit files are written; L-17a makes it throw.

## Runs
| # | File | Result |
|---|---|---|
| 01 | `01-cast-contract.out` | **before the repair:** exit 1, **3 of 6 fail** (findings below) |
| 02 | `02-after-repair.out` | after the F-B repair, with the receipt test rewritten as a characterisation per the F-A decision: **6 / 51, exit 0** |
| 03 | `03-isolated-all.out` | the whole isolated PBDIGIT-274 suite: **80 / 404, exit 0** |

## Per-case evidence

Every case ended with HTTP 302, transaction level 0 and the lock free. "Committed" is read on C2 as votes / codes.has_voted / slug has_voted / receipt_codes.

| Case | Before (01): voter is told | After (02): voter is told | Committed (both runs) | Verdict |
|---|---|---|---|---|
| baseline | success | success | 1 / true / true / 1 | contract holds |
| G7-a: step-5 record fails | "Please try again." | same | 0 / false / false / 0 | fails closed; the hypothesis held (no savepoint, so the transaction aborts) |
| G7-b: receipt code fails | success | success | 1 / true / true / **0** | **F-A**, accepted; pinned by `test_receipt_code_failure_is_absorbed_the_vote_counts_without_a_receipt`; follow-up **PBDIGIT-279** |
| L-17a: post-commit audit fails (level 0) | **"Please try again."** ✘ | **success** ✔ | 1 / true / true / 1 | **F-B repaired** |
| L-17b: post-commit `current_step` fails (level 0) | **"Please try again."** ✘ | **success** ✔ | 1 / true / true / 1 | **F-B repaired** |
| L-18: commit fails (deferred trigger on `votes`) | "Please try again." | same | 0 / false / false / 0 | characterised: **the "vote confirmed" email was sent (1)**, the accepted residual of O-15 (a) |

## Findings and decisions
- **F-A (receipt lost, success reported):**
  - **Mechanism:** `ReceiptCode::firstOrCreate` (`VoteController.php:1886`) runs in a savepoint (Laravel `createOrFirst`, `Builder.php:607-612`). The failed insert is rolled back to that savepoint, the `catch (\Exception)` swallows the error, and the cast commits without a receipt.
  - **L-16 holds as worded.** The plan's hypothesis that this failure aborts the transaction is **refuted**.
  - **Decision:** accepted for 274. The test name changed from `test_failure_in_receipt_code_never_reports_success` to a characterisation. Follow-up: **PBDIGIT-279**.
- **F-B (false failure after commit, L-17 violated):**
  - **Mechanism:** a throw in the post-commit audit log or `current_step` save reached the outer `catch (\Throwable)`, and the voter was told "Please try again" for a vote that already counts.
  - **Repair (`VoteController::store`):** the post-commit block (audit log, voter action log, `current_step` save) is wrapped in its own `try { … } catch (\Throwable)`. The error is logged with `election_id` and the exception class only, and the voter still gets the success page.
  - **RED:** run 01. **GREEN:** run 02.
- **Not changed here:** the raw slug in the `current_step` log line (T2) is S9c's work.

Checksums: `SHA256SUMS`.
