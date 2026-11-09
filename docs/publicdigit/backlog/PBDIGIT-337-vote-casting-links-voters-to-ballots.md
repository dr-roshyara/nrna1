# PBDIGIT-337: The current vote-casting code links voters to their ballots (VO-1)

**Type:** Defect in live code + architecture gap · **Epic:** none · **Created:** 2026-11-09 · **Raised by:** architecture Session B (secret-ballot discovery); the Principal approved recording it ("Yes, write them and commit, no push", 2026-11-09). Recorded by Claude.

| | |
|---|---|
| **Status** | `PROPOSED` · **not authorised for implementation** · no code changed |
| **Classification** | observed in code (static, branch `election-flow-updated`, HEAD `bad1202d0`) |
| **Invariant broken** | VO-1 "A vote must not be linkable to the voter who cast it" (`docs/architecture/discovery/Round29_Invariant_Catalog.md:251-266`); target assurance Level A (D-45) |
| **Does not duplicate** | F1 and F2 are already recorded as known gaps in `tests/Feature/Characterization/Phase1/BallotAnonymityCharacterizationTest.php:145-176`. F3 is partly covered by the untracked `tests/Isolated/Pbdigit274/Database/VoteAnonymityLinkageTest.php`. F4 to F14 are not recorded elsewhere |
| **Analysis** | model document `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` §22; discovery record §11.2 |

## Business consequence

Anyone who can read the database can match every voter to their ballot, using F5 or F11 alone. No election held on this code can claim to have been secret. Whether real elections have been held on it is unknown and is an operational question for the Principal.

## Findings (FACT)

| # | Location | What |
|---|---|---|
| F1 | `app/Http/Controllers/VoteController.php` ~1874 → `app/Services/VoterStepTrackingService.php:69` | `vote_id` stored in the voter's step data; the voter slug holds `user_id` |
| F2 | `VoteController.php` ~1876 | App log entry with `voter_slug_id` and `vote_id` |
| F3 | `VoteController.php` ~1950 | `receipt_hash = sha256(vote_id . user_id . election_id)` in the voter's audit log |
| F4 | `VoteController.php` `save_vote` ~2925 | `vote_hash` from `code.id`, election, `code_to_open_voting_form`, timestamp and `vote_salt`; recomputable from the code row (which holds `user_id`) |
| F5 | `save_vote` and `markUserAsVoted` (~2095) | Vote `cast_at` and code `code_to_save_vote_used_at` / `vote_completed_at` are both `now()` |
| F6 | ~1925 `SendVoteSavingCode` | Email to the voter containing `private_key_vote_id` |
| F7 | ~2290 verification | Anyone holding the emailed code sees the ballot content (receipt proves how someone voted) |
| F8 | ~1902 `ReceiptCode` | Full receipt string stored unencrypted |
| F9 | `app/Models/BaseVote.php:7,32` | `HasUuids`: time-ordered vote IDs |
| F10 | ~1823 | Cast IP stored on the voter's code row (`client_ip`) |
| F11 | ~1823 and vote insert | Code update and vote insert in one transaction: same PostgreSQL `xmin` |
| F12 | `app/Services/ElectionAuditService.php:86-97, 138` | Per-voter audit file with name, IP, timestamp, receipt hash |
| F13 | `app/Notifications/SendVoteSavingCode.php` (`ShouldQueue`) | Emailed code persisted in job records with a persistent queue (production queue unknown) |
| F14 | logs and web-server access logs | Identified request and cast request recorded side by side |

Not yet checked: caches, exports, admin tools, backups.

## Scope when authorised (strategic first)

1. Follow the accepted design (model document §22): separate Voting storage; blind-signature credentials; "right issued" instead of "has voted"; no identity on the cast path; M3 sealed submission; no ballot-revealing receipts.
2. Classify every past election held on this code as "no secrecy assurance" (assurance level is fixed per election and never upgraded).
3. Independent security review before Level A is claimed.
