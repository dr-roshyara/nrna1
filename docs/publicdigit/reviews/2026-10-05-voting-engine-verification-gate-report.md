# Voting Engine Integrity Verification — TDD-first characterization gate (INTERIM: stopped at T1)

**Date:** 2026-10-05 · **Mode:** characterization only — no production, configuration, route, migration, schema or existing-test change; no fix
**Recorded by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Engineering supplies evidence; decisions are the PO's / ARB's.
**Evidence labels:** [D] dynamically characterized · [S] source directly inspected · [I] inference.

---

## STOP GATE

**Reason:** absolute stop condition 6 — a critical ballot-integrity defect is proven (T1).
**Evidence:** `tests/Feature/Voting/BallotIntegrityCharacterizationTest.php` — 8 of 11 RED through the real chain (ballot submit → final cast → results → published tally) [D]; §4–§5.
**Decision required:** PO / ARB decision on the ballot-validity rule (§13) before T2 continues.
**No implementation performed.**

---

## 1. Executive verdict

**PROVEN CRITICAL DEFECT** (ballot integrity, VE-01). T2–T9 not executed (gate).

## 2. Repository baseline (T0)

| Item | Value |
|---|---|
| HEAD | `2937695db` docs(voting): Phase 5 Round 1 evidence and R1-F6a decision preparation |
| Phase 4 E4 commit | `b1daf26a2` feat(voting): atomic participation consumption before final cast (PBDIGIT-230) — 2 files: `VoteController.php` (+35), `AtomicParticipationConsumptionTest.php` (+30) |
| Last commit touching `VoteController.php` | `b1daf26a2` |
| `git diff --stat b1daf26a2 HEAD -- app routes config database bootstrap` | empty |
| Working tree | only pre-existing user files (docs, brainstorming) + this gate's new test and report |

**SHA discrepancy explained:** `27d1e3a54` (docs: close Phase 4, charter Phase 5) was HEAD during the Round 1 re-audit; `2937695db` is the later documentation-only commit (Round 1 evidence + R1-F6a decision brief). Both follow `b1daf26a2`; neither touches application code. **Baseline established.**

## 3. Test inventory (this gate)

`tests/Feature/Voting/BallotIntegrityCharacterizationTest.php` (new, uncommitted) — 11 tests, real HTTP chain, synthetic data, real test database. Fixture: election E (voting active) with national posts p1, p2 (required 1), regional posts pr1 (Bayern, the voter's region) and pr2 (Hessen), election B with post pb; one approved candidacy per post (two on p1). Each voter's code has its own `client_ip` so the Trust → Legitimacy IP-density rule does not interfere when one test casts several ballots (fixture isolation, documented in the test).

## 4. TDD RED / GREEN matrix (T1)

**Invariant (derived from the server's own ballot assembly, `BallotAssemblyService`):** GIVEN an entitled voter casting in election E, WHEN the submitted ballot selects anything outside the voter's assembled ballot (national posts of E + regional posts of the voter's region, each with its own candidacies) or is malformed, THEN no vote, no participation consumed, no result rows, published tally unchanged.

| ID | Invariant | Test | Result | Observed [D] | Classification |
|---|---|---|---|---|---|
| T1-A | valid ballot cast and counted exactly | `test_t1_a_a_valid_ballot_is_cast_and_counted_exactly_as_selected` | **GREEN** | 1 vote, consumed, tally +1 for each selected candidacy only | **PROVEN SAFE** |
| T1-B | candidacy of another election rejected | `test_t1_b_…` | **RED** | vote committed, consumed; result row (E, p1, cb) | **PROVEN DEFECT** |
| T1-C | candidacy of another post rejected | `test_t1_c_…` | **RED** | vote committed, consumed; row (E, p1, c2) | **PROVEN DEFECT** |
| T1-D | regional post of another region rejected | `test_t1_d_…` | **RED** | vote committed, consumed; row (E, pr2, cr2) | **PROVEN DEFECT** |
| T1-E | same candidacy twice rejected | `test_t1_e_…` | **RED** | vote committed, consumed; two rows (E, p1, c1a) | **PROVEN DEFECT** |
| T1-F | nonexistent candidacy rejected | `test_t1_f_…` | **GREEN** | no vote, not consumed, no rows, no 500 | **PROVEN SAFE** — protected by the `results.candidacy_id` foreign key (insert fails → rollback), not by validation |
| T1-G | nonexistent post rejected | `test_t1_g_…` | **GREEN** | as T1-F (FK `results.post_id`) | **PROVEN SAFE** (same caveat) |
| T1-H | post of another election rejected | `test_t1_h_…` | **RED** | vote committed, consumed; row (E, pb, cb) | **PROVEN DEFECT** |
| T1-I | malformed structures fail closed | `test_t1_i_…` | **RED** (2 of 5) | *candidate without id* and *candidate entry scalar*: **vote committed, participation consumed, 0 result rows** (a void ballot that uses up the voter's participation); *candidates scalar*, *selection without post*, *nested malformed*: refused, nothing written | **PROVEN DEFECT** |
| T1-J | over-selection beyond the post's maximum rejected | `test_t1_j_…` | **RED** | vote committed, consumed; rows (E, p1, c1a), (E, p1, c1b) for a post with maximum 1 | **PROVEN DEFECT** (rule decided D2, §15) |
| T1-J2 | fewer selections than the post's maximum accepted | `test_t1_j2_…` | **GREEN** | vote cast | **PROVEN SAFE** (D2) |
| T1-L | an invalid ballot cannot change the published tally | `test_t1_l_…` | **RED** | tally change per variant: **T1-D cr2 +1**, **T1-E c1a +2**, **T1-J c1a +1, c1b +1**; T1-B, T1-C, T1-H: stored but not shown in E's tally | **PROVEN DEFECT** |

Command: `php artisan test --env=testing tests/Feature/Voting/BallotIntegrityCharacterizationTest.php` → 3 passed (GREEN), 8 failed (RED), 32 assertions.

## 5. Ballot integrity findings

### 5.1 Chain (T1-K) [D][S]

```
POST slug.vote.submit (first_submission)
  validate_candidate_selections — counts only (VoteController.php:1217–1301) [S]
  ballot written to the session as submitted (:620) [S]
→ POST slug.vote.store (final cast)
  forCast(): ballotPresent only — no content check [S]
  CAS has_voted false→true → save_vote(): candidate JSON stored verbatim (:2786–2928) [S]
  Vote results: one results row per submitted candidacy_id with the vote's election_id and the
    submitted post_id (Vote.php:74–106) [S]; FKs only require existence (results migration :22–43) [S]
→ COMMIT
→ result.index: results of election E grouped by (post_id, candidacy_id), shown per post of E for that
    post's own candidacies (ResultController.php:18–100) [S]
```

The integrity validator `validateVoteIntegrity` exists but is reachable only from the unrouted `second_submission` [S].

### 5.2 What reaches the published result [D]

| Variant | Stored | Changes E's published tally? | Note |
|---|---|---|---|
| Other-region regional post (D) | yes | **yes (+1)** | out-of-region voters can vote on regional posts |
| Duplicate candidacy (E) | yes (2 rows) | **yes (+2 from one voter)** | one voter can give one candidate several votes |
| Over-selection (J) | yes | **yes (+1 each)** | depends on the rule (§5.3) |
| Other-election candidacy under own post (B) | yes | no (filtered by post's own candidacies) | stored garbage; other read paths not characterized [S: `statisticalVerification` / `verifyResults` count by `post_id` only] |
| Other post's candidacy (C) | yes | no | as B |
| Other-election post (H) | yes (row with election E, post pb) | no for E; election B's tally filters on `results.election_id` | as B |
| Malformed (I, 2 variants) | vote, no rows | no | **participation consumed by a void ballot** |

### 5.3 Selection-count rule (T1-J) — business rule not authoritative

- UI: "Select exactly N candidate(s)" / "Max N" per post, from `posts.required_number` (`CreateVotingPage.vue:553–592`) [S].
- Server: an **election-level** constraint (`selection_constraint_type`, default `maximum` with no maximum → any count) (`Election.php:845–860`) [S]; the per-post `required_number` sent by the client is used only when no election is given (`VoteController.php:1228`) — never in the real path [S].
- **Decision required:** is `posts.required_number` a server-enforced maximum (or exact count) per post?

### 5.4 Classification

**VE-01: PROVEN DEFECT** — an entitled voter can, by submitting a crafted ballot through the normal endpoints, commit a vote for candidates outside their ballot (other region's regional post), give one candidate multiple votes, and change the published result; malformed ballots can consume participation with a void vote. Affected path: `first_submission` → `store` → `save_vote` → results. Votes committed: **yes**. Participation consumed: **yes**. Result/tally changed: **yes** (D, E; J pending rule).

## 6–11. Not executed (gate)

T2 code verification state machine, T3 final-cast code validity, T4 direct-POST bypass, T5 anonymity, T6 credential logging, T7 demo / real boundary, T8 entry-point matrix — pending approval after T1. (Source-level and earlier dynamic evidence for these exists in `2026-10-05-complete-voting-engine-business-audit.md` and the Round 1 report; not re-characterized here.)

## 12. Regression results

Not run in this interim (no production code changed; the new test file is self-contained). T9 runs at the end of the gate.

## 13. Open business decisions (from T1)

1. **Ballot validity rule:** must the server accept only selections from the voter's assembled ballot (election, post, region), each candidacy at most once? (Recommended: yes — the server already defines the ballot.)
2. **Selection count:** is `posts.required_number` a server-enforced per-post maximum or exact count? Is abstention per post (`no_vote`) governed by the election setting only?
3. **Malformed ballots:** must any malformed selection reject the whole ballot (fail closed) rather than store a void vote?
4. **Existing data:** do stored ballots in any live election need an integrity review (results rows whose post / candidacy are outside the voter's ballot)?

## 14. Recommended next TDD gate

After the PO / ARB decides §13: RED contracts for the decided ballot-validity rule at the **final-cast boundary** (the session ballot is server state written from client input; the authoritative check belongs before the CAS) → STOP → GREEN on separate approval. Then resume this gate at T2.

---

## 15. PO decisions on T1 (2026-10-05)

| ID | Decision |
|---|---|
| **D1** | **Yes** — the server accepts only selections from the voter's assembled ballot (election, post, region); each candidacy at most once. The client is never authoritative. |
| **D2** | **`posts.required_number` is the per-post maximum** (0 … required_number selections allowed). UI wording ("exactly N") not changed now. |
| **D3** | **Yes** — any malformed ballot fails closed: no vote, no code consumption, no result rows, no tally change. |
| **D4** | **Pending** — read-only forensic assessment of existing votes / results first; **no data modification authorised**. |

Consequence: **referential integrity ≠ ballot integrity** — the foreign keys stop only non-existent ids (T1-F / T1-G); existing ids from the wrong election, post or region pass them.

## 16. VE-01 RED contract at the final-cast boundary (authorised: RED only)

`tests/Feature/Voting/FinalCastBallotInvariantTest.php` (new, uncommitted). The ballot is placed in the voter's session directly — bypassing the ballot-submit step — because the session is storage, not authority; the final cast must re-establish the invariant. Each invalid case asserts: no 500 · vote count unchanged · `codes.has_voted` unchanged · result rows unchanged · **the participation compare-and-set is not executed** (validation before CAS, observed in the query log). A structural check requires a ballot-content validation step in `store()` before the CAS.

Command: `php artisan test --env=testing tests/Feature/Voting/FinalCastBallotInvariantTest.php` → **2 GREEN, 11 RED** (35 assertions) [D].

| Contract | Result | Observed |
|---|---|---|
| valid ballot from the session is cast | GREEN | vote, consumed, CAS ran |
| D2 fewer than the maximum is cast | GREEN | vote |
| rule 2 post of another election | **RED** | vote committed |
| rule 3 candidacy of another election | **RED** | vote committed |
| rule 4 candidacy of another post | **RED** | vote committed |
| rule 5 regional post outside the voter's region | **RED** | vote committed |
| rule 6 candidacy twice | **RED** | vote committed |
| rule 7 over the post maximum | **RED** | vote committed |
| rule 1 candidate entry without candidacy id | **RED** | vote committed (void ballot) |
| rule 1 scalar candidate entry | **RED** | vote committed (void ballot) |
| rule 1 selection without post | **RED** | no vote, but **the CAS ran** and was rolled back by the database error — refusal by failure, not by validation |
| rule 1 non-existent candidacy | **RED** | as above (foreign key) |
| structural: ballot validation before the CAS in `store()` | **RED** | no such step |

Note for review: the structural test recognises the validation step by name (pattern such as `validateBallot` / `BallotInvariant`); it constrains naming. The behavioural "CAS not executed" assertion already proves the ordering — the structural test can be dropped or re-expressed at GREEN design time if preferred.

**Not started (PO):** T2–T9 until VE-01 is resolved; GREEN design only after inspecting `BallotAssemblyService`, `validate_candidate_selections()`, `validateVoteIntegrity()`, `save_vote()` and the results model together.

## 17. Further PO decisions and amended RED contract (2026-10-05)

- **Decided:** D5 approved candidacies only (central fix in `BallotAssemblyService`); N1 abstention + candidates invalid; N2 empty list without abstention = Not answered; N3 omitted post = Not answered; N4 final cast enforces the election's abstention permission; N5 abstention outside the ballot invalid. **Open:** D6 (regional snapshot), D4 (forensics).
- **Architecture direction (PO):** ballot integrity **beside** `forCast()` (authorization boundary preserved); `ValidatedBallot` as the trust boundary into `save_vote()`; orchestration stays in `store()` for VE-01; ADR I1 amendment required. Proposal: `docs/publicdigit/reviews/2026-10-05-ve01-ballot-integrity-design-and-adr-amendment-proposal.md`.
- **Contract amended:** name-based structural test removed; invalid cases now also assert a reasoned `vote` refusal (not `vote_error`); new contracts D5 (pending, rejected), N1, N2, N3, N4, N5, explicit allowed abstention.
- **Result [D]:** `FinalCastBallotInvariantTest` **4 GREEN, 16 RED** (51 assertions). N2 is RED because an empty list is stored today as an abstention row.
- GREEN not approved.

## 18. Gate board after the principal-architect reviews (2026-10-05)

- **Story:** VE-01 is tracked as **`PBDIGIT-237`** (ballot integrity at the final cast); D6 as **`PBDIGIT-236`** (regional ballot authority) — separate items.
- **Design (PO):** proposal approved as design direction; CastContext approved; I11 / I12 / I13 and the amended I1 approved in principle — formal ADR revision act pending. D1–D3, D5, N1–N5 frozen. D6 open.
- **D4:** D4.0 schema verification and query set **approved**; SQL validated on the empty test database (13/13 planned, 16/16 executed, read-only proven) — `docs/publicdigit/reviews/2026-10-05-d4-forensic-schema-verification-and-query-set.md`. **D4.1 (execution against election data) not authorized.**
- **Not authorized / on hold:** VE-01 GREEN · T2–T9 · historical data repair.
