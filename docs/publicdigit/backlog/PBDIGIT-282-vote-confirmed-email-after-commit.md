# PBDIGIT-282: Send the "vote confirmed" email only after the vote is saved

**Type:** Defect (voter trust) · **Epic:** none (voting) · **Created:** 2026-10-13 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from `PBDIGIT-274` (decision O-15 = (a): accept for 274, plus a follow-up story; to-do item D3). **Index row:** added 2026-10-13.

| | |
|---|---|
| **Status** | `OPEN` · not authorised |
| **Classification** | **reproduced by a test** in `PBDIGIT-274` S9a |

## Finding
- **The email goes out before the commit.** `SendVoteSavingCode` is a plain `Notification`: it imports `ShouldQueue` but doesn't implement it. `VoteController::store` sends it synchronously **inside** the cast transaction, before `DB::commit()`.
- **The mail claims too much:** it says "Vote Successfully Recorded!", "Vote Status: CONFIRMED" and carries the receipt code.
- **Reproduced:** `tests/Isolated/Pbdigit274/Database/CastSuccessContractTest.php::test_confirmation_email_vs_commit_failure` forces the commit to fail (a deferred constraint trigger on `votes`). Nothing is committed and the voter is told to try again, but **the confirmation email was sent** (`Notification::sent(...)` = 1). Evidence: `docs/publicdigit/election/evidence/2026-10-13-pbdigit274-s9a-cast-contract/`.

## Proposed change
- Send the notification only after `DB::commit()`: move the send after the commit, or make the notification `ShouldQueue` with `afterCommit`.
- **Turn the characterisation into a contract test:** a failed commit sends no email, and a successful cast sends exactly one.
- **Keep the S9a rule:** a failure in post-commit work (now including the email) must not tell the voter to try again for a committed vote (L-17).
- **Related, decide together:** `PBDIGIT-279`. A cast that commits without a receipt row still emails a receipt code.

## Related
- `PBDIGIT-274` (L-18, plan section 2.2 O-15; guide `developer_guide/election/voter_slug/12_cast_success_contract.md`).
- `PBDIGIT-279`.
