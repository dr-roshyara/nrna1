# 06 — Log anonymity: no receipt secret, no voter↔ballot pair in one entry (PBDIGIT-241)

**Story:** PBDIGIT-241 · **RED:** `19f0d3356` · **GREEN:** `826a8f70d` · **Found by:** review `docs/publicdigit/reviews/2026-10-05-real-election-code-and-vote-controller-review.md` (F-V6, F-V7)

## Purpose

The constitutional invariant is *participation knowledge without ballot knowledge*. The receipt a voter gets is `{32-hex secret}_{vote id}`; with it, anyone can open the ballot (Phase 1 K2). `VoteController` wrote that secret — and pairs of user id and ballot identifier — into the logs; the logged values created a **direct / deterministic voter↔ballot linkage capability for anyone with appropriate access to those logs**. This is a **constitutional anonymity defect**, not ordinary logging hygiene.

> PBDIGIT-241 closes two concrete logging defects (F-V6, F-V7). It does **not** establish the complete anonymity invariant — see *Still open*.

## What changed (log contexts only — every event is kept)

| Site | Removed | Why |
|---|---|---|
| `store()` — receipt generation | the `Log::info('private_key', …)` entry | it logged the full receipt secret (under the misleading key `user_id`) |
| `store()` — "Receipt code stored for verification" | `receipt_code_prefix` | it was the first 20 of the 32 secret characters, next to `vote_id` |
| `submit_code_to_view_vote()` — "Vote verification successful" | `vote_id` | the entry also held the viewer's `user_id`; the viewer need not be the voter (K2) |
| `confirmCorrect()` — "Vote verified as correct" | `receipt_code` | it is `secret_voteId`, next to `user_id` |

Where an entry needs to say *who* used a feature, keep `user_id` and drop the **ballot-side** identifier (vote id, receipt, or a `receipt_codes` row id — that is ballot-side too).

## The rule for new logging

This rule governs **logging added or changed from now on**. It does not claim that existing log statements elsewhere, or historical log files, already comply (see *Still open*).

- Never log the receipt, the receipt secret, or any part of it.
- Never put a voter identifier (`user_id`, `voter_slug_id`, …) and a ballot identifier (`vote_id`, receipt) in the **same** entry.
- ADR_20261005_0950 I8 already forbids an authorization / security outcome next to a ballot-side identifier.

## Testing

```bash
php artisan test tests/Feature/Voting/VoterBallotLogLinkageTest.php
```

L1 final cast — the secret (full or any 16-character prefix) in no entry · L2 `confirmCorrect()` · L3 `submit_code_to_view_vote()` — no secret, no user-id + vote-id pair · L4 final cast — no single entry pairing voter and vote. Entries are searched as message + JSON-encoded context (`CastFixtures::captureLogs()` / `logsMentioning()`). Phase 1 K8 now asserts the secret is **not** logged.

## Status

| Item | State |
|---|---|
| F-V6 receipt secret in logs | CLOSED |
| F-V7 direct voter↔ballot log pair | CLOSED |
| A8 audit `receipt_hash` linkage | OPEN — decision |
| A1 IP↔ballot entry · vote id + `voter_slug_id` entry | OPEN |
| A6 step-5 vote id · receipt stored in clear · cross-entry correlation | OPEN |
| Historical log clean-up | OPEN — operations |

## Still open (not fixed by this story)

- **A8 — decision open:** the audit records written after the cast carry `receipt_hash = sha256(vote_id . user_id . election_id)`; with the `votes` table it can be recomputed for every vote id — a computable voter↔ballot link. Remove, or keep as a governed dispute-resolution capability?
- A1 (vote id + client IP in one entry) and the vote id + `voter_slug_id` entry — pinned by Phase 1 K8 as known gaps.
- A6 (step-5 record stores the vote id), cross-entry correlation within one request, the receipt stored in clear in `receipt_codes`.
- Logs written before this change still contain the removed values — retention / clean-up is an operations decision.

**Traceability:** PBDIGIT-241 · review F-V6, F-V7 · Phase 1 K2, K3, K8 · ADR_20261005_0950 I8.
