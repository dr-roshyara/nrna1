# PBDIGIT-284: The vote hash can link a vote to its voter (anonymity)

**Type:** Security / anonymity defect · **Epic:** none (voting) · **Created:** 2026-10-13 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the IP voting strategy review (finding A). **Index row:** added 2026-10-13.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · **priority: highest. Fix before any production election** |
| **Classification** | the formula is **verified in code**; the practical linkage is **inferred** (not yet demonstrated by a test) |

## Finding
- `VoteController::save_vote` (`app/Http/Controllers/VoteController.php:2905-2915`) sets
  `vote_hash = sha256(code.id . election.id . code.code_to_open_voting_form . castAt.timestamp . config('app.vote_salt', ''))`.
  - The fallback branch (`:2917-2923`) uses the private key, which is not stored.
  - `VoteStorageTrait.php:48` uses the same salt.
- **`app.vote_salt` is not defined in any config file** (`grep vote_salt config/` finds nothing), so the salt is the empty string. An environment variable cannot set it either: nothing maps one onto it.
- **The other inputs are all stored:**
  - the `codes` row holds `id`, `election_id`, `code_to_open_voting_form`, `user_id` and `client_ip`;
  - the cast second is close to `votes.cast_at` and to the code's `vote_completed_at` / `code_to_save_vote_used_at`.
- **Consequence:** anyone with read access to the database can recompute candidate hashes per code row and match `votes.vote_hash`, which links **vote ↔ code ↔ user ↔ IP**. That breaks the platform's core rule: no voter–vote linkage (CLAUDE.md, ADR-T11).
- **Related:** cast-time logs put `user_id`, IP and a timestamp together (`VoteController.php:1502-1508`, `:1604-1610`; `voting_audit` log, 90 days). That allows timing correlation even without the hash.

## Acceptance
- [ ] **RED first:** an isolated-database test reproduces the linkage. From the `codes` rows and `votes.cast_at` alone, it identifies which vote belongs to which voter.
- [ ] **`vote_hash` no longer derives from voter-linked data:**
  - no `code.id` and no code value;
  - or a secret per-election key (HMAC) that is not stored with the data.
  - A missing salt or key **fails closed**, never empty.
- [ ] **Existing hashes:** a decision on whether stored `vote_hash` values are rotated or invalidated (they remain linkable).
- [ ] **Cast-time logs:** reviewed for user / IP / time co-location (ADR-T11).
- [ ] **No change** to counting, receipts or verification semantics, unless decided.

## Related
`PBDIGIT-276` item 3 (ADR-T11 check of log contents) · `PBDIGIT-288` (IP data minimisation).

## Progress (2026-10-13): RED proven, decisions taken, then PARKED by the Human PO
- **RED test:** `tests/Isolated/Pbdigit274/Database/VoteAnonymityLinkageTest.php`, kept **uncommitted** while parked, because it fails by design until the fix.
  - **vote_hash: 3 of 3 votes** linked to their voter, from `codes` rows plus `votes.cast_at` alone.
  - **A second linkage found:** the `vote_confirmed` audit entries carry `sha256(vote_id . user_id . election_id)` next to `user_id` (`VoteController.php` ≈1931, `log()` and `logVoterAction()`). **6 audit entries** link a user to a vote.
  - Evidence: `docs/publicdigit/election/evidence/2026-10-13-pbdigit284-vote-anonymity/01-red.out`.
- **Decisions (Human PO, 2026-10-13):**
  1. `vote_hash` becomes a **random** 256-bit value (nothing verifies it; receipts use the keyed `receipt_hash`).
  2. **Remove** the vote-derived hash from the `vote_confirmed` audit entries (keep the event).
  3. **Existing data:** fix forward, plus a one-off migration (re-randomise existing `vote_hash`, redact `receipt_hash` from audit files), **run only with a separate approval**.
  4. **Demo gets the same fix** (`VoteStorageTrait`).
- **Parked:** "a known loophole; we concentrate now on implementing everything related to IP address." Resume with GREEN on the decisions above.
