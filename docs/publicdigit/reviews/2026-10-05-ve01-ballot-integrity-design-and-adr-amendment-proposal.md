# VE-01 — Ballot Integrity: design proposal and ADR amendment proposal

**Date:** 2026-10-05 · **Story:** `PBDIGIT-237` · **Status:** 🟡 **APPROVED AS DESIGN DIRECTION (PO, 2026-10-05)** — CastContext approved; amended I1 and I11–I13 approved in principle; **ADR revision 3 signed and applied 2026-10-05** (record `2026-10-05-adr-revision-3-po-arb-decision-record.md`; demo scope S2: real elections only). Design only: no production code, no migration, no GREEN. The frozen ADR text is **not** modified by this document.
**Amends (proposed):** `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` — invariant I1 and the concept list in §3.0
**Evidence:** `docs/publicdigit/reviews/2026-10-05-voting-engine-verification-gate-report.md` (T1, VE-01 RED contract) · `tests/Feature/Voting/BallotIntegrityCharacterizationTest.php` · `tests/Feature/Voting/FinalCastBallotInvariantTest.php`
**Prepared by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Engineering proposes; the PO / ARB decides.

---

## 1. Problem (proven)

The final cast commits whatever ballot it finds in the voter's session. Selections outside the voter's ballot (other election, other post, other region, duplicates, over the maximum, non-electable candidacies, malformed entries) are committed, can change the published tally, and malformed ballots can consume participation with a void vote. **Referential integrity ≠ ballot integrity**: foreign keys stop only non-existent ids.

The missing element is a **domain boundary**: the engine has an authoritative voting entitlement, but no authoritative representation of the ballot an election voter is allowed to cast.

## 2. Decisions this design rests on (PO, 2026-10-05)

| ID | Decision |
|---|---|
| D1 | Only selections from the voter's authoritative assembled ballot; each candidacy at most once. The client never defines what can be voted for. |
| D2 | `posts.required_number` is the per-post maximum (0 … maximum). |
| D3 | Malformed ballot → fail closed. |
| D4 | Existing data: read-only forensic assessment first; no modification. **Pending.** |
| D5 | Only `approved` candidacies are electable — corrected centrally in `BallotAssemblyService` (ballot page, preview and final cast share one definition). |
| N1 | Abstention together with candidates for the same post → invalid. |
| N2 | Empty candidate list without abstention → **Not answered** (not an abstention). |
| N3 | Omitted post → **Not answered**. |
| N4 | The final cast enforces whether the election allows abstention. |
| N5 | Abstention for a post outside the voter's ballot → invalid. |
| **D6** | **OPEN** — which region snapshot decides the regional ballot. Separate architecture item **PBDIGIT-236**. VE-01 uses today's `users.region` as the existing source **for characterization only**; it does not constitutionalize it. |

**Frozen for VE-01:** D1, D2, D3, D5, N1–N5.

## 3. Domain vocabulary

- **ElectionVoter** — domain concept; persisted today as `ElectionMembership (role = voter)` (unique per user and election). No new model.
- **CastContext** — the one coherent context of a final cast: Organisation · User · Election · ElectionVoter (`ElectionMembership`, role voter). Authorization **and** ballot assembly derive from the **same** context (same election, same voter); no second election or voter may enter ballot validation.
- **AuthoritativeBallot** (current source) — the posts and electable candidacies the election voter may vote on, assembled from the CastContext by `BallotAssemblyService` (with D5 applied): national posts of the election + regional posts of the voter's region; per post its `required_number` (maximum) and approved candidacies; plus the election's abstention permission. **Until D6 is decided this is the *current ballot assembly authority*, not the final constitutional ballot authority** — its regional part rests on the mutable, user-level `users.region`, which VE-01 uses for characterization only and does not constitutionalize.
- **SubmittedBallot** — the voter's proposed answer, normalized from the session: per post `post_id` and either a list of `candidacy_id`s or an explicit abstention. Everything else the client sent (`post_name`, `required_number`, display names, any region or election) is ignored. **Untrusted input.**
- **Per-post answer** — exactly one of **Selected** (1 … maximum candidacies), **Abstained** (explicit, only if the election allows it), **Not answered** (omitted or empty list).
- **ValidatedBallot** — a SubmittedBallot proven to be a subset of the AuthoritativeBallot under D1–D3, D5, N1–N5. The only input `save_vote()` accepts.
- **BallotValidator** — pure, framework-free comparison `SubmittedBallot × AuthoritativeBallot → ValidatedBallot | BallotRejection(reason)`.

## 4. Constitutional architectural invariant (proposed)

> **SESSION DATA IS UNTRUSTED INPUT.** The voting session may transport ballot state but never establishes ballot validity. Only the server-side authoritative ballot model may establish whether a submitted selection is valid. The final cast MUST re-evaluate ballot validity, whatever the transport (session, cache, database, frontend state, API payload).

## 5. Target structure

```
                FINAL CAST (VoteController::store — orchestration stays here for VE-01)
                    │
      ┌─────────────┴──────────────┐
      ▼                            ▼
Authorization (frozen)       Ballot integrity (new, beside it)
VotingAuthorization::forCast   AuthoritativeBallot ← port ← adapter(BallotAssemblyService)
  → CastDecision               SubmittedBallot ← session (untrusted)
                               BallotValidator → ValidatedBallot | BallotRejection
      └─────────────┬──────────────┘
                    ▼
     both pass  →  CAS has_voted false→true  →  save_vote(ValidatedBallot)  →  COMMIT
     either fails → reasoned refusal (E1 fail-closed exit) — no CAS, no vote, no rows
```

- **Beside `forCast()`, not inside:** `forCast()` answers *may this attempt proceed?*; ballot integrity answers *is the content of the act valid?* `forCast()`, `CastAttempt` and the 14-reason vocabulary (E2-D3) stay unchanged.
- **Order — the invariant:** authorization **and** ballot validation must **both** complete successfully **before** the CAS; their relative order is not constitutional. Proposed sequence: `forCast()` first (cheap, fresh), then ballot validation, then CAS. The RED contract asserts the CAS is not executed for an invalid ballot.
- **One context:** both decisions read the same CastContext (election from the voter slug / request, the voter's membership, the voter's user record); the ballot validator never receives an election or region from the session.
- **`save_vote()` as a trust boundary:** accepts only a `ValidatedBallot`; stored candidate columns, abstentions and `no_vote_posts` are derived from validated content only — client display strings no longer reach the ballot table. No duplicate validation: the guarantee is the type.
- **Refusal:** deterministic `vote` error through the existing reasoned-refusal path (no generic failure, no 500).
- **No `FinalVoteApplicationService` in VE-01** (separate, later improvement).

## 6. ADR amendment proposal (requires an explicit PO / ARB revision act)

| ADR element | Current (frozen) | Proposed |
|---|---|---|
| §3.0 concepts | authorization · process validation · atomic participation consumption | + **ballot integrity**: *is the content of the cast act a subset of the voter's authoritative ballot?* — evaluated beside the cast decision, before consumption |
| I1 | `CastDecision = Allowed` ∧ CAS changed exactly one row ∧ insert ∧ commit | **`CastDecision = Allowed` ∧ `ValidatedBallot` exists ∧ CAS changed exactly one row ∧ insert of exactly the validated content ∧ commit** |
| New I11 | — | Session (or any transport) data is untrusted; ballot validity is established only against the authoritative ballot, at the final cast. |
| New I12 | — | `save_vote()` persists only a `ValidatedBallot`. |
| New I13 | — | Authorization and ballot validation both complete before the participation CAS, and both derive from one CastContext. |
| I10 | unchanged | (ballot integrity reads voter-side context and writes nothing ballot-side beyond the validated content) |

Phase 2.0, A5 / A6, IP binding, demo behaviour (Q4) and Q5 = A are unaffected.

## 7. Production files that would change at GREEN (not now)

| File | Change |
|---|---|
| new `app/Contexts/Elections/Domain/Ballot/{SubmittedBallot, AuthoritativeBallot, ValidatedBallot, BallotValidator, BallotRejection}` (framework-free) | the model and the pure comparison |
| new port `app/Contexts/Elections/Application/Ports/AuthoritativeBallotQuery` + adapter wrapping `BallotAssemblyService` | no duplicated assembly rules |
| `app/Services/BallotAssemblyService.php` | D5: approved candidacies only (also changes the ballot page and preview) |
| `app/Http/Controllers/VoteController.php` `store()` | build Submitted + Authoritative, validate after `forCast()`, refuse before the CAS |
| `app/Http/Controllers/VoteController.php` `save_vote()` | accept a `ValidatedBallot` only |
| tests | RED contract → GREEN; T1 characterization re-classified |

No migration (D6 open).

## 8. Open items

- **D6 — regional snapshot** (separate architecture item **PBDIGIT-236**). Today the regional ballot is f(`users.region`) — mutable, user-level, not election-specific; nothing snapshots it at ballot issue. Rebuilding at cast from the current `users.region` means the ballot the voter saw and the ballot the server accepts could differ if the region changes mid-session. Options: (a) region at cast (current behaviour), (b) snapshot at ballot issue / code verification, (c) election-specific region on the membership. (b) / (c) require new data → separate decision; VE-01 uses the existing source without adopting it as the rule.
- **D4** — read-only forensic assessment of stored votes / results (plan in §9, not executed).
- UI wording "exactly N" vs D2 (maximum) — later.

## 9. D4 — read-only forensic assessment plan (prepared, NOT executed)

> **Superseded by the schema-verified query set** `docs/publicdigit/reviews/2026-10-05-d4-forensic-schema-verification-and-query-set.md` (D4.0, approved by the PO; SQL validated on the empty test database). The shapes below were written from memory and are kept only as the record of the draft.

**Purpose:** establish whether VE-01 is only prospective or has already affected stored election data. **No correction, deletion, migration or mutation.** Requires explicit PO authorization naming the environment (production / staging / development) before any query runs.

**Anonymity boundary (binding):** every check is ballot-side or aggregate — no query links a vote, result or ballot to a user, membership, code or slug. Per-vote region checks are **impossible by design** (they would need a voter↔ballot link) and are replaced by an aggregate comparison.

| # | Check | Query shape (read-only, aggregate output) |
|---|---|---|
| F1 | result post not of the vote's election | `results r JOIN posts p ON p.id = r.post_id WHERE p.election_id <> r.election_id` → count per election |
| F2 | candidacy not of the result's post | `results r JOIN candidacies c ON c.id = r.candidacy_id WHERE c.post_id <> r.post_id` → count per election |
| F3 | same candidacy twice in one vote | `results GROUP BY vote_id, candidacy_id HAVING COUNT(*) > 1` (candidacy not null) → count of affected votes per election |
| F4 | over-selection per post | `results GROUP BY vote_id, post_id HAVING COUNT(candidacy_id) > MAX(posts.required_number)` → count per election |
| F5 | non-approved candidacy (current status) | `results r JOIN candidacies c … WHERE c.status <> 'approved'` → count per election and status (status may have changed since the cast — reported as such) |
| F6 | void votes | `votes v WHERE NOT EXISTS (results r WHERE r.vote_id = v.id)` → count per election |
| F7 | abstention + candidates on one post | `results GROUP BY vote_id, post_id HAVING bool_or(candidacy_id IS NULL) AND bool_or(candidacy_id IS NOT NULL)` → count per election |
| F8 | regional over-participation (aggregate only) | per regional post P: `COUNT(DISTINCT r.vote_id)` for P **vs** count of consumed codes of users whose **current** `region = P.state_name` in that election — an excess proves out-of-region votes exist without identifying any; limited by region changes since the cast |
| F9 | reference: totals | votes, results, consumed codes per election (sanity, I6 aggregate) |

Output: one table of counts per election (and per check), no row-level data, no identifiers beyond election id / name. Executed as `SELECT` only, in a read-only session / transaction that is rolled back.

## 10. RED contract status

`FinalCastBallotInvariantTest` — **4 GREEN, 16 RED** (51 assertions): GREEN = valid ballot, fewer than maximum (D2), explicit abstention when allowed, omitted post = not answered (N3); RED = rules 1–7, D5 pending / rejected, N1, N2, N4, N5. Every invalid case additionally asserts a reasoned `vote` refusal (not `vote_error`) and that the CAS is not executed. The name-based structural test was removed (the behavioural CAS assertion proves the ordering).
