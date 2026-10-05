# Phase 1 Characterization — Evidence Report (Gate 1)

**Date:** 2026-10-05 · **Type:** characterization evidence (read / test / document only) · **Authority:** none — evidence and recommendation; nothing fixed
**Gate 1: ACCEPTED / CLOSED (PO, 2026-10-05)** — the evidence is accepted; A5 and A6 are confirmed findings; no anonymity correction is authorised; acceptance is **not** an approval of Model A or Model B (Phase 2.0 decides). Interim anonymity rule binding (roadmap §2a).
**Roadmap:** `docs/plans/20261005-0647-voting-integrity-anonymity-and-authorization-roadmap-plan.md` (Phase 1, groups A–K) · **Situation report:** `docs/publicdigit/reviews/2026-10-05-voting-authorization-and-security-situation.md` (SR)
**Code base:** `c1a132065` / `6ca3d199d` (HEAD at the start of Phase 1); **no production file changed in Phase 1**
**Method:** 28 characterization tests in `tests/Feature/Characterization/Phase1/` driving real casts through `slug.vote.store` (`VoteController::store`) — **synthetic data in the testing database only** (roadmap P7); no correlation experiment against any real or development data.

> **Notation.** **FACT** = established by a passing characterization test or read in the code, with citation. **REPORTED** = from an earlier code search, not re-tested here. **INFERENCE** = derived, not observed. **RECOMMENDATION** = opinion for the PO. A test named `known_gap_*` asserts today's defective behaviour; the authorised fix later flips it into the target assertion.

---

## A. Test inventory

All 28 tests pass (112 assertions; the only notice is the environment's PDO deprecation). Files: `CastFixtures.php` (trait), `CastFixtureSmokeTest`, `VotingIntegrityCharacterizationTest`, `BallotAnonymityCharacterizationTest`, `ViewMyVoteReceiptCharacterizationTest`, `CodeRegenerationCharacterizationTest`, `FinalDenialCharacterizationTest`.

| Group | Test | Current behaviour (FACT) | Target behaviour | Result |
|---|---|---|---|---|
| fixture | `CastFixtureSmokeTest::one_synthetic_voter_casts_one_real_vote` | one real cast succeeds **only if `app.max_use_clientIP` is set** (test-only config) | — | pass |
| C | `…Integrity::c_a_cast_sets_the_code_as_participation_record…` | `codes.has_voted` true; `voter_slugs.has_voted` true, `status = voted` | same | pass |
| D | `…Integrity::d_known_gap_…membership_has_voted` | `election_memberships.has_voted` false, `voted_at` null after a real vote | correct projection without correlatable time | pass (gap) |
| A | `…Integrity::a_a_second_sequential_cast…` | second cast writes no vote | same | pass |
| A | `…Integrity::a_a_held_slug_lock_refuses…` | a held slug lock refuses the cast, nothing written | DB guarantee | pass |
| A | `…Integrity::a_one_voter_slug_per_voter_and_election…` | `voter_slugs unique(election_id,user_id)` → lock effectively per voter | — | pass |
| A | `…Integrity::a_structural_…no_database_row_lock` | no `lockForUpdate`/`sharedLock`/`FOR UPDATE` in Vote/CodeController | compare-and-set | pass (gap) |
| B2 | `…Integrity::b2_a_failure_while_writing_the_vote_rolls_back…` | failure in `save_vote` → no vote, `has_voted` false | same | pass |
| H | `…Integrity::h_committed_votes_equal_consumed_codes…` | per election `count(votes) = count(codes has_voted)` (2 = 2 with a failed cast) | same | pass |
| K1 | `…Anonymity::k1_known_gap_…recomputing_vote_hash…` | each of 3 voters → exactly one ballot, Δ = 0 s; complete mapping | non-derivable | pass (gap) |
| K6 | `…Anonymity::k6_known_gap_…completion_time…` | `code_to_save_vote_used_at` ↔ `cast_at` and `codes.updated_at` ↔ `votes.created_at` join 1:1 | no correlatable time | pass (gap) |
| K7/J | `…Anonymity::k7_known_gap_vote_ids…` | id order = cast order; creation time decoded from id | random ballot ids | pass (gap) |
| K8 | `…Anonymity::k8_known_gap_…logs…` | vote id + IP; vote id + slug id; `private_key` logged; user id in same request | separated logs | pass (gap) |
| K9 | `…Anonymity::k9_known_gap_…slug_step…stores_the_vote_id` | voter's step-5 `step_data` holds the vote id | no ballot identity in step tracking | pass (gap) |
| K9 | `…Anonymity::k9_the_vote_row_itself_holds_no_voter…` | `votes` has no voter/code/membership/slug column or value | same | pass |
| K5 | `…ViewMyVote::k5_known_gap_the_email…contains_the_vote_id` | e-mail = `{private_key}_{vote_id}` | per ADR | pass (gap) |
| K3 | `…ViewMyVote::k3_the_receipt_is_stored_as_the_full_secret…` | `receipt_codes.receipt_code` = full secret; no user column; `receipt_hash = sha256(pk‖vote.id‖app.key)` | per ADR | pass |
| K2 | `…ViewMyVote::k2_known_gap_…retrieves_the_ballot_contents` | returns `vote_selections` | per ADR | pass (gap) |
| K2 | `…ViewMyVote::k2_known_gap_any_authenticated_user…` | another user with the code sees the ballot | per ADR | pass (gap) |
| K4 | `…ViewMyVote::k4_the_receipt_list_source_holds_every_full_secret…` | every listed receipt is a full secret | per ADR | pass |
| F1 | `…CodeRegeneration::f1_an_already_voted_voter…` | no new code value, `can_vote_now` false, no second vote | same | pass |
| F2 | `…CodeRegeneration::f2_known_gap_regeneration…keeps_the_code_step_verified` | new code value, `can_vote_now` stays true | decision needed | pass (gap) |
| F2 | `…CodeRegeneration::f2_known_gap_after_regeneration_a_wrong_code_passes…` | wrong code passes code entry | decision needed | pass (gap) |
| F2 | `…CodeRegeneration::f2_mitigation_…final_vote_still_requires_the_new_code` | old code refused, new code accepted at the final vote | same | pass |
| G1 | `…FinalDenial::g1_a_wrong_code_is_refused…` | error, no vote, no `has_voted`, lock released, no receipt | same | pass |
| G2 | `…FinalDenial::g2_known_gap_…security_denial…uncaught_error` | HTTP 500 (`TypeError`), no vote, no `has_voted`; lock not involved | denial with reason | pass (gap) |
| I | `…FinalDenial::i_known_gap_the_pre_check_result_is_never_read…` | `$pre_check` assigned once, never read | consumed or removed | pass (gap) |
| I | `…FinalDenial::i_a_failing_pre_check_condition…missing_view_exception` | pre-check denies only by crashing on `components.error_message` | denial with reason | pass (gap) |

## B. Evidence report

### FACT (tested or read)
1. **One vote per voter today rests on a cache lock + re-read.** No DB row lock; `voter_slugs unique(election_id,user_id)` makes the lock effectively per voter (my earlier hypothesis "lock per slug, not per voter" is **refuted**); the vote is inserted before `has_voted` is set; failures roll back; the aggregate invariant holds.
2. **`election_memberships.has_voted` is never written** (D9); the Voters page and newsletter audiences read it.
3. **A5 confirmed** — `vote_hash` recomputed from voter-side data + `app.vote_salt` identifies each voter's ballot, Δ = 0 s.
4. **New: A6 — a stored voter → ballot link**: step 5 of the voter's own slug stores the vote id (`VoteController.php:1772`).
5. **A2 confirmed with a corrected column**: `codes` has no `vote_completed_at` (the update key is silently dropped); the correlating voter-side times are `code_to_save_vote_used_at` and `updated_at`.
6. **A4 confirmed**: vote ids are time-ordered and encode the creation time (10-µs resolution).
7. **A1 confirmed and extended**: vote id + IP, vote id + slug id, and the receipt `private_key` are logged; the request also logs the user id.
8. **Receipts / view-my-vote**: the e-mail carries the vote id; the receipt row is the full secret; the code retrieves the exact ballot for any authenticated holder; the committee list exposes every full secret.
9. **D6**: regeneration keeps `can_vote_now`, so code entry accepts any value; the final vote still requires the current code.
10. **D3**: the security-denial branch ends in HTTP 500 (`TypeError`); no vote is written; the trust gate runs before the lock is taken (my earlier note that the lock stays held on this path is **refuted**).
11. **D1 / new D10**: `vote_post_check`'s result is ignored; its denials crash on a missing view; with `MAX_USE_IP_ADDRESS` unset **no real vote can be cast**.

### REPORTED (not re-tested here)
- The same `?int` mismatch exists on the lifecycle and membership denial branches of `store()` (`:1563`, `:1588`); middleware refuses those cases first.
- `Code.can_vote_now` is also used as a per-code timer and a global end-of-voting switch (SR §4).
- IP/device binding and `voter_verification_mode` are not enforced (SR §5–§6).

### INFERENCE
- The cache-lock guarantee fails open if the cache is not shared between servers or is flushed; a true parallel race was not reproducible in a single-process test.
- With `MAIL_MAILER=log`, the vote-saving e-mail body (voter address + vote id) is written to the application log.
- Mail transport logs / queues and log archives retain the correlations of K5 and K8.

### RECOMMENDATION
- ~~Treat PBDIGIT-223 (A5) and PBDIGIT-224 (A6) as the first Phase 2 items~~ → **superseded by the PO re-sequencing (2026-10-05, roadmap §2a):** A5/A6 and all anonymity items stay **unchanged until the Phase 2.0 ADR** (accepted interim risk); voter admission and voting-security work (Phases 3–6) may proceed first under the interim rule "no new persistent voter ↔ ballot links"; Phase 7 (IP binding) needs the anonymity-boundary review (A1 log separation first). When Phase 2.1 starts, PBDIGIT-223 and 224 remain the first candidates.
- Confirm with ops **now** that `MAX_USE_IP_ADDRESS` is set in every environment (PBDIGIT-233) — otherwise real voting is impossible.
- Inform the production owner of A5/A6 while the fix is pending.

## C. Confirmed defect list (backlog items created; none implemented)

| ID | Defect | Severity | Proposed phase | ADR |
|---|---|---|---|---|
| **PBDIGIT-223** | `vote_hash` computable from voter-side data + config (A5) | Critical | 2.1 | yes (2.0) |
| **PBDIGIT-224** | voter's slug step stores the vote id (A6, stored link) | Critical | 2 | yes (2.0) |
| PBDIGIT-225 | cast logs correlate voter and ballot; receipt secret logged (A1) | High | 2.2 | yes (2.0) |
| PBDIGIT-226 | voter-side timestamps join to the ballot (A2) | High | 2.2 | yes (2.0) |
| PBDIGIT-227 | vote ids encode creation time and order (A4) | Medium–High | 2.2 | yes (2.0) |
| PBDIGIT-228 | receipts / view-my-vote expose the ballot to any code holder (K2–K5) | High | 2 | yes (2.0: purpose A/B/C) |
| PBDIGIT-229 | `election_memberships.has_voted` never written (D9) | Medium | 2.3 | yes (2.0) |
| PBDIGIT-230 | no DB guarantee against double voting (D4) | High | 4 | yes (3) |
| PBDIGIT-231 | regenerated code keeps the code step verified (D6) | Medium | PO decision (`can_vote_now` frozen) | process decision |
| PBDIGIT-232 | security-denial branch crashes with `TypeError` (D3) | High | 4 | yes (3) |
| PBDIGIT-233 | `vote_post_check` ignored; missing view; unset IP limit blocks every vote (D1, D10) | High | 4 (+ ops now) | yes (3) |

## D. Anonymity findings (synthetic data only)

| Question | Answer |
|---|---|
| Is A5 reproducibly computable? | **Yes** — for every synthetic voter, exactly one ballot. |
| Voter-side inputs | `codes.id`, `codes.code_to_open_voting_form` (plain text, kept after voting), `codes.code_to_save_vote_used_at` (completion second), `election.id`. |
| Ballot-side value derived | `votes.vote_hash` (unique column). |
| Configuration required? | **Yes** — `app.vote_salt` (empty string if unset). |
| Timestamp required? | **Yes**, at one-second resolution; the stored voter-side second equalled the hashed second (Δ = 0) in every case; a ±1 s window covers a second boundary. |
| Historical records affected? | **Yes (INFERENCE from the formula and schema)** — every existing vote whose code row and salt still exist, including in backups. |
| Additional paths | **A6** stored link (step 5 `step_data`); **A2** timestamps (`code_to_save_vote_used_at`, `updated_at` ↔ `cast_at`, `created_at`); **A4** ids; **A1** logs (vote id + IP, vote id + slug, logged `private_key`); **K5** e-mail with vote id; **K4** committee list of full secrets. |

### D.1 A5 — capability, intention and decision status (PO discussion, 2026-10-05)

| | |
|---|---|
| **FACT** | `vote_hash` can be recomputed from voter-associated data (`codes.id`, code value, completion second, `election.id`) and configuration (`app.vote_salt`) and identifies the corresponding anonymous ballot (K1). |
| **Who holds the capability** | anyone with read access to `codes` + `votes` and the application configuration (DB administrators, operators, backup holders). **No feature reads `vote_hash`** (only `VerifyArchitecture` checks the column) — the capability is latent and ungoverned. |
| **Business intention** | PO-stated: administrator-assisted **dispute resolution** ("if a voter disputes the recorded result, an administrator can verify that voter's ballot") — a legitimate **requirement candidate**. |
| **Constitutional status** | conflicts with **strong non-derivability**; compatible only with an explicitly declared *conditionally anonymous* model (Model A), which also creates a coercion surface. |
| **Architectural status** | **DECISION PENDING** — mechanism kept unchanged in Phase 1. |
| **Decision owner** | **Phase 2.0 Anonymity ADR** (Q1 strong secrecy vs administrators? · Q2 administrator-assisted dispute resolution? · Q3 dispute resolution without identifying the ballot, e.g. voter-held evidence?). |

The same capability question applies to A6 (stored step-5 vote id, PBDIGIT-224) and to the committee receipt list (PBDIGIT-228): each gives a holder a voter → ballot or ballot-opening capability, whatever the original intent.

## E. View My Vote / receipt classification

| Flow | Classification | Evidence |
|---|---|---|
| Vote-saving e-mail (`SendVoteSavingCode`, `{private_key}_{vote_id}`) | **Correlation bridge** (voter mailbox / mail transport ↔ ballot id) | K5 |
| Receipt row (`receipt_codes.receipt_code`) | Participation-proof data **held as the full secret** in clear | K3 |
| View my vote (`vote.submit_code_to_view_vote`) | **Ballot retrieval**, not bound to the voter (coercion / vote selling) | K2 ×2 |
| Committee receipt list / CSV (`VotingReceiptController`) | **Ballot retrieval for every ballot** by a list holder | K4 + source |
| `receipt_hash` | participation proof (verifiable from receipt + `app.key`) | K3 |

## Gate 1 items 8–10

**8. Recommended phase per defect** — see table C.

**9. Production files that would eventually need changing (not changed):** `app/Http/Controllers/VoteController.php` (`store`, `save_vote`, `markUserAsVoted`, `vote_post_check`, `trackSovereigntyDivergence`, `submit_code_to_view_vote`, `prepare_unified_vote_display`, logging) · `app/Services/VoterStepTrackingService.php` usage (step-5 payload) · `app/Models/BaseVote.php` / `app/Models/Vote.php` (identifier strategy) · `app/Models/Code.php` (code value retention, timestamps) · `app/Http/Controllers/VotingReceiptController.php` · `app/Models/ReceiptCode.php` and its table · `app/Notifications/SendVoteSavingCode.php` + `resources/views/mail/send_vote_saving_code` · `app/Http/Controllers/CodeController.php` (regeneration, if PBDIGIT-231 is authorised) · `app/Models/ElectionMembership.php` / `app/Services/NewsletterService.php` / `resources/js/Pages/Elections/Voters/Index.vue` (D9) · `config/app.php` (`max_use_clientIP` default) · a new `components.error_message` view or removal of its use · migrations (identifier / receipt / historical remediation — per ADR).

**10. Decisions requiring PO approval (Phase 2/3):**
1. Phase 2.0 anonymity ADR — answers to the Anonymity Gate (8 questions); purpose of view-my-vote (A participation receipt · B ballot verification · C ballot retrieval); identifier strategy for ballots; timestamp policy; log separation; **historical-data remediation** (existing votes, step rows, logs, backups, mail).
2. Order inside Phase 2: PBDIGIT-223 + 224 first (critical, stored/computable links).
3. Ops action now: `MAX_USE_IP_ADDRESS` in every environment (PBDIGIT-233) and notifying the production owner about A5/A6.
4. PBDIGIT-231 (`can_vote_now` regeneration): assign to a phase or keep frozen.
5. Phase 3 authorization ADR scope including PBDIGIT-230/232/233.

**Traceability:** roadmap `20261005-0647` · SR rev 6 · PBDIGIT-213 · PBDIGIT-222 · PBDIGIT-223–233.
