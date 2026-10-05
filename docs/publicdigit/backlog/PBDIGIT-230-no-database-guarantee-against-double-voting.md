# PBDIGIT-230 — No database-level guarantee against double voting (D4)

**Type:** Defect — voting integrity · **Severity:** High · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Phase 1 characterization · evidence report `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md`
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🔴 **OPEN — not authorised.** Phase 4 (after the Phase 3 authorization ADR). |
| **Defect** | The vote path takes no database row lock and no compare-and-set: protection = `Cache::lock("voter_slug_transition:{slug}", 10)` + a plain re-read of `codes.has_voted`; the vote is inserted **before** `has_voted` is set (`markUserAsVoted` runs after `save_vote`). |
| **Evidence (FACT, synthetic data)** | `VotingIntegrityCharacterizationTest`: sequential re-submission writes no second vote (`test_a_a_second_sequential…`); a held slug lock refuses a concurrent cast (`test_a_a_held_slug_lock…`); `voter_slugs` `unique(election_id, user_id)` makes the lock effectively per voter (`test_a_one_voter_slug…`); no `lockForUpdate`/`sharedLock`/`FOR UPDATE` in `VoteController`/`CodeController` (`test_a_structural…`); a failure while writing rolls everything back (`test_b2…`); aggregate invariant holds (`test_h…`). |
| **INFERENCE** | the guarantee depends on the cache store being shared and not flushed (a non-shared or flushed cache fails open); a true parallel race was not reproducible in a single-process test. |
| **Affected boundary** | final cast transaction |
| **Constitutional impact** | one voter, one vote rests on an application cache, not the database. |
| **Proposed phase** | Phase 4 — compare-and-set on `codes.has_voted` **before** the vote insert, in one transaction (PO-frozen order) · ADR: Phase 3. |
