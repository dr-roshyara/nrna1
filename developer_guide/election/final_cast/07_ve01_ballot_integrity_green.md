# 07 — Ballot integrity at the final cast: only a ValidatedBallot is persisted (VE-01, PBDIGIT-237)

**Story:** PBDIGIT-237 · **RED:** `FinalCastBallotInvariantTest` (see guide 05) · **GREEN:** `33bef9f7d` · **Decision:** `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` revision 3 (I1 amended, I11–I13, S2), applied `22c3e5f40`

## Purpose

Before VE-01, `VoteController::store()` committed whatever ballot was in the voter's session: selections from another election, another post, another region, duplicates, over the maximum, non-approved candidacies and malformed entries all reached `votes` and `results` and changed the tally. Referential integrity is not ballot integrity. VE-01 makes the session ballot **untrusted transport** and lets only a ballot proven valid against the voter's authoritative ballot reach persistence.

## Where it fits

```
Domain       app/Contexts/Elections/Domain/Ballot/           pure PHP — SubmittedBallot, AuthoritativeBallot, BallotValidator, ValidatedBallot, BallotRejection
Application  app/Contexts/Elections/Application/Ports/       AuthoritativeBallotQuery (port)
Infra        app/Contexts/Elections/Infrastructure/Queries/  AssembledAuthoritativeBallotQuery (adapter over BallotAssemblyService)
HTTP         app/Http/Controllers/VoteController.php         store() integration · saveValidatedVote()
```

## The final cast after VE-01 (real election)

```
… forCast(CastAttempt) ──not Allowed──► failCastClosed · refuseCast(reason)
  BallotValidator.validate(SubmittedBallot, AuthoritativeBallot)
       ──BallotRejection──► failCastClosed · back()->withErrors(['vote' => …])     ← NEW: no CAS, no vote, no results
  … code check · CAS on codes.has_voted
  saveValidatedVote(ValidatedBallot)                                                ← NEW: real path only
COMMIT
```

Authorization (`forCast()`) and ballot validation are separate concepts that both run **before** the participation CAS, from the same CastContext (voter + election) — I13. Demo elections skip both and keep `save_vote($vote_data, …)` unchanged (S2).

## Key files

| File | Role |
|---|---|
| `Domain/Ballot/SubmittedBallot.php` | `fromTransport($national, $regional)` normalises the session lists. Malformed (any list not an array, an entry not an array, `post_id` not a non-empty string, `no_vote` not bool/0/1, `candidates` not an array, a candidate without a non-empty `candidacy_id`) → a malformed ballot (D3). Display fields are ignored. A **missing** list is treated as empty (PO 2026-10-05; transport contract `nullable\|array`) |
| `Domain/Ballot/AuthoritativeBallot.php`, `AuthoritativePost.php` | The voter's ballot: posts keyed by id, each with name, maximum and approved candidacies; `abstentionAllowed` = the election's no-vote option (N4) |
| `Domain/Ballot/BallotValidator.php` | `validate(SubmittedBallot, AuthoritativeBallot): ValidatedBallot\|BallotRejection` — pure, no I/O |
| `Domain/Ballot/BallotRejectionReason.php` | 8 reasons: `malformed`, `post_not_on_ballot`, `duplicate_post`, `candidacy_not_on_post`, `duplicate_candidacy`, `over_maximum`, `abstention_with_candidates`, `abstention_not_allowed` |
| `Domain/Ballot/ValidatedBallot.php`, `ValidatedAnswer.php` | Answered posts only (Selected or Abstained), in submission order; `abstainedPostIds()` |
| `Application/Ports/AuthoritativeBallotQuery.php` | `ballotFor(voterId, electionId)` — source of the authoritative ballot only; owns no validity rule |
| `Infrastructure/Queries/AssembledAuthoritativeBallotQuery.php` | National + regional posts from `BallotAssemblyService` (the same assembly as the ballot page and preview); region = `users.region` (interim — D6 / PBDIGIT-236 open) |
| `app/Services/BallotAssemblyService.php` | D5: both candidacy loads filter `status = Candidacy::STATUS_APPROVED` — ballot page, preview and final cast agree |

## How validation decides (first failure wins)

1. malformed → `malformed`
2. post not on the voter's ballot (other election, other region, unknown) → `post_not_on_ballot`
3. post answered twice → `duplicate_post`
4. abstention: with candidates → `abstention_with_candidates` (N1); not allowed by the election → `abstention_not_allowed` (N4); otherwise an **Abstained** answer
5. empty candidate list without abstention → **not answered**, nothing stored (N2); an omitted post is also not answered (N3)
6. each candidacy: not an approved candidacy of that post → `candidacy_not_on_post` (D1, D5); seen before → `duplicate_candidacy`
7. more candidacies than `required_number` → `over_maximum` (D2: the number is a maximum)
8. otherwise a **Selected** answer

## Persistence: `saveValidatedVote()`

```php
private function saveValidatedVote(ValidatedBallot $ballot, $hashed_voting_key, Election $election, $auth_user, $private_key = null): void
```

It builds the stored payload **only** from validated content — `post_id`, name, nepali name and `required_number` from the authoritative post, `no_vote` from the answer, `candidacy_id` + `candidacy_name` from the validated ids — and hands it to the existing writer `save_vote()` (vote row, `vote_hash`, `receipt_hash`, `candidate_NN` columns, results via the model event). It re-judges nothing.

All answers go into the writer's first list and the second list is empty. This loses nothing: `save_vote()` merges both lists into one sequence of `candidate_NN` columns, each carrying its own `post_id`, and result rows are created per post. The stored vote has no national/regional section (proven by the two characterization tests below).

## Refusal behaviour

An invalid ballot: `voting_audit` warning with `user_id`, `election_id`, `reason` only (never ballot content — anonymity rules of guide 06 apply) → `failCastClosed()` (rollback, own lock released) → redirect back with a `vote` error. No CAS, no vote, `has_voted` stays false, no result rows, never HTTP 500.

## Testing

| Suite | What it proves |
|---|---|
| `tests/Feature/Voting/FinalCastBallotInvariantTest.php` | 20 contract cases (rules 1–7, D2, D5, N1–N5) + 2 regional persistence characterizations (national + regional → both result rows; regional only → its result row) — 22/22 |
| `tests/Feature/Voting/BallotIntegrityCharacterizationTest.php` | T1 characterization; 8 cases now pass (t1 b, c, d, e, h, i, j, l) — kept unchanged (PO) |
| Phase 1 B2, Phase 4 R4-4 | Rollback after a write failure. Their fault injection now uses a throwing double — `CastFixtures::failTheVoteWriteAfterItsInsert()` (a `Vote::created` listener) — because VE-01 removed the former trigger (a session ballot missing the regional list crashed `save_vote()`) |

Regression over 97 suites vs `22c3e5f40`: 0 pass → fail, 24 fail → pass, 2 new passing, 334 failures unchanged (status-level comparison).

## Pitfalls

- Do not call `save_vote()` with session data on the real path — persistence accepts only a `ValidatedBallot` (I12).
- Do not add validity rules to `saveValidatedVote()`, the adapter or the port; rules live in `BallotValidator`.
- Do not filter candidacies in a second place — D5 lives in `BallotAssemblyService` only.
- Fault-injection tests that relied on a malformed session crashing the writer no longer work; use the throwing double.

## Still open

- A ballot with **nothing answered and nothing abstained** is accepted as a vote with no result rows (as at baseline) — decision → `PBDIGIT-242`.
- D6 regional authority (`users.region` is mutable) → `PBDIGIT-236`.
- Soft-deleted posts / candidacies: `BallotAssemblyService` uses `withoutGlobalScopes()` (note only, not assessed).

**Traceability:** ADR_20261005_0950 rev 3 (I1, I11–I13, S2) · decisions D1–D3, D5, N1–N5 (story PBDIGIT-237) · design `docs/publicdigit/reviews/2026-10-05-ve01-ballot-integrity-design-and-adr-amendment-proposal.md` · RED/design guide `05_ve01_ballot_integrity_red_and_design.md` · GREEN `33bef9f7d`.
