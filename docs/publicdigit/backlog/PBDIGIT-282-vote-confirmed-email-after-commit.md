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

## Additional impact: locks are held during the email (added 2026-10-14; "BL-EMAIL" from PBDIGIT-286)
The Principal approved a backlog item for this on 2026-10-14 (PBDIGIT-286 decision record §3.2, BL-EMAIL). It has the **same root cause and the same fix as this item**, so it is recorded here instead of as a second item (consume / extend, never duplicate).
- **What is held:** because the email is sent synchronously inside the cast transaction (`VoteController.php:1925`, before `DB::commit()` at `:1941`), the transaction's locks are held while the mail is sent:
  - the per-network PostgreSQL advisory lock of System A (`IpVoteLimit::castRefusal`, PBDIGIT-288);
  - the participation row update;
  - the per-voter cache lock.
- **The effect:** a slow or failing mail server delays every other vote from the **same network**. They wait up to `voting.ip_lock_timeout_ms` (5000 ms), then get the "network busy" refusal.
- **It would get worse if PBDIGIT-286 ADR-C is approved.** ADR-C is currently **PROPOSED, not approved**; it takes the network lock earlier, before the security assessment.
- **The fix proposed above** (send after commit, or `ShouldQueue` with `afterCommit`) also removes the lock hold.
- **Additional acceptance (proposed; no test written or run, implementation not authorised):**
  - the network lock is released before any mail is sent;
  - a slow mail transport doesn't delay a second vote from the same network (an isolated test with a delaying transport fake, to be written test-first when this item is authorised).

## Related
- `PBDIGIT-274` (L-18, plan section 2.2 O-15; guide `developer_guide/election/voter_slug/12_cast_success_contract.md`).
- `PBDIGIT-279`.
- `PBDIGIT-286` (BL-EMAIL; ADR-C `docs/publicdigit/adr/ADR_20261014_1138_Constitutional_Limit_Cast_Concurrency.md` §3.3) · `PBDIGIT-288` (network lock).
