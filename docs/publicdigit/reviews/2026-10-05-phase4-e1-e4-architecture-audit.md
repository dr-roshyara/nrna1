# Phase 4 (E1–E4) Architecture Audit — single-authority review and closure record

**Date:** 2026-10-05 · **Type:** read-only audit (no file changed during the audit) · **Recorded by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Subject:** final real-election cast, `VoteController::store` (`POST v/{vslug}/vote/verify`) after E1 `7aa33df68` · E2 `aa07a9160` · E3 `8cea53c73` · E4 `b1daf26a2`
**Baseline:** `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` · plan `docs/plans/20261005-1105-phase4-final-cast-hardening-plan.md` · decision record `docs/publicdigit/reviews/2026-10-05-phase4-final-cast-hardening-po-decision-record.md`
**Question:** does the implemented E1–E4 architecture have a coherent single-authority model, with no duplicated or conflicting decision authority?

Engineering supplies the evidence; the closure decision in §6 is the PO's.

---

## 1. Authority map — final real-election cast

| Stage | Authority | Input | Decision | Enforcement | Persistence |
|---|---|---|---|---|---|
| Registration | `ElectionMembership` | import / assign | role voter, status | read by entitlement | `election_memberships` |
| Admission | `VoterAdmissionPolicy` → `AdmissionDecision` (inside entitlement) | policy + identity evidence | ALL-of | only through entitlement | `voter_verifications`, policy |
| Voting entitlement | `VotingEntitlementQuery` (`EloquentVotingEntitlementQuery` + `VotingEntitlementPolicy`) | membership, admission, lifecycle | `decide()` = standing ∧ lifecycle | `forCast()` | — |
| Lifecycle | `ElectionLifecycle::canVote()` | election state | votable? | middleware, `store()`, inside `decide()` | — |
| Security | Trust → Legitimacy chain (`TrustPolicyEvaluator` → `ConstitutionalLegitimacyDecision`) | IP hash, density, session | `LegitimacyOutcome` | `store()` pre-lock; verdict passed to `forCast()` as a fact | security logs |
| Cast decision | `VotingAuthorization::forCast()` | `CastAttempt` facts | `CastDecision` (14 reasons) | `store()` → `refuseCast()` | — (derived) |
| Participation consumption | compare-and-set on `codes.has_voted` | code id | exactly 1 row | `store()` immediately before `save_vote()` | `codes.has_voted` |
| Anonymous ballot | `save_vote()` → `Vote` | session ballot | — | inside the transaction | `votes`, results |
| Atomicity | the cast DB transaction | — | commit / rollback | `failCastClosed()`, `catch (\Throwable)` | — |
| Double-click guard | `Cache::lock` per slug | slug id | acquired / refused | released only by the acquiring path | cache |

## 2. Repeated evaluations vs authorities

**Entitlement (3 reads, one authority):**

| # | Where | Read | Lifecycle | Classification |
|---|---|---|---|---|
| 1 | middleware `ensure.election.voter` → `User::isVoterInElection()` → `standingOf()` | container query, cached (model-event invalidation) | no | cached authorization projection (navigation guard; ADR §3.5) |
| 2 | `store()` → `ensureVoterMembership(useCache: false)`, pre-lock | uncached `standingOf()` | no | defensive revalidation |
| 3 | `forCast()` → `decide()`, after the lock, in the transaction | uncached `decide()` | yes | **authoritative decision** (ADR I4, E2-D2) |

No conflicting authority: the same query and policy everywhere; reads differ only in freshness, and only #3 authorises.

**Lifecycle:** 4 reads (`vote.eligibility`, `voting.active`, `store()`, inside `decide()`), all `ElectionLifecycle::canVote()`.

## 3. Findings

| # | Finding | Severity | Authority | Evidence | Disposition |
|---|---|---|---|---|---|
| F1 | Entitlement read 3× | Info | `VotingEntitlementQuery` | §2 | **Accepted redundancy; #2 candidate for later simplification** (PO) |
| F2 | One security authority on the final cast | Info | Trust → Legitimacy | pre-lock gate; verdict to `forCast()`; `vote_post_check()` off the real path | Accept |
| F3 | Security verdict applied twice (pre-lock + inside `forCast()`) | Info | same chain | in practice `Allowed` when it reaches `forCast()` | Accept (defensive, no second engine) |
| F4 | One "already voted" authority | Info | compare-and-set | `!== 1` refuses; `AlreadyVotedPreCheck`, `markUserAsVoted()` re-write, `voter_slugs.has_voted` decide nothing | Accept |
| F5 | Consumption and insert share the transaction | Info | DB transaction | `save_vote()` has no try/catch or own transaction; R4-4, Phase 1 B2, R1-D | Accept |
| F6 | `vote_post_check()` demo-only on the final cast | Info | — | E3 option A; R3-7 | Accept |
| F7 | Every pre-commit exit fails closed | Info | E1 | all early returns use `failCastClosed()`; membership branch rolls back itself (no lock held); IP-binding branch see F9 | Accept |
| F8 | Anonymity | Info for E1–E4 | `Vote` | consumption writes `has_voted` only; refusal logs have no vote id; R5. Existing A1 / A6 (`vote_id` in step-5 record and logs) unchanged | **Deferred — Phase 2.0** |
| F9 | Dormant IP-binding paths | Medium (debt) | — | `validate.voting.ip` middleware and `validateVotingIpWithResponse()` read the non-existent `users.voting_ip`; the helper would return without rollback / lock release, but runs before the consumption | Debt — PBDIGIT-234 |
| F10 | Real-only guards partition by `=== 'real'` / `!== 'real'` | Low (debt) | — | `elections.type` is `enum('demo','real')`; a future third type would skip `forCast()` and the consumption — not fail-closed by construction | **Phase 5 backlog — future-type hardening** (PO) |
| F11 | `\Throwable` after `DB::commit()` returns the generic error although the vote is committed | Low (existing) | — | audit logging after commit | Debt — receipt / post-commit consistency design |
| F12 | Inner `catch (\Exception)` around step-5 record, receipt, notification | Low (existing) | — | exception swallowed, vote commits possibly without a receipt row; `\Error` still rolls back | Debt — receipt / post-commit consistency design |
| **C1** | **D10 still blocks real voting at step 4** | **High — elevated (PO); outside Phase 4** | config limit inside `vote_post_check()` | `verify()` (step-4 page) still calls `vote_post_check()` for real elections; with `MAX_USE_IP_ADDRESS` unset (`0 >= null`) the page fails before the final cast; also a second IP-density check outside the Trust → Legitimacy chain (ADR I3). PBDIGIT-233 is resolved **for the final cast only** | **First Phase 5 discovery item** (§6) |
| F13 | Legacy `POST /votes` closed only by a dead check | Low (debt) | — | `vote.eligibility` legacy branch: `isEligibleToVote()` reads dropped `users.is_voter` / `can_vote` → always false | Debt — PBDIGIT-218 |

## 4. Classification

- **A. Architecturally sound:** entitlement authority (`decide()` in `forCast()`); one security authority on the final cast; one cast-decision step; the compare-and-set as the one "already voted"; consumption + insert atomic; fail-closed exits with lock ownership; no new voter↔vote link; demo separated.
- **B. Acceptable redundancy:** entitlement 3× (#2 candidate for simplification); lifecycle 4×; security verdict re-applied in `forCast()`; non-authoritative already-voted pre-checks and projections.
- **C. Architectural debt (later):** C1 (step 4); F9 / PBDIGIT-234; F10 (future types); F11 / F12 (receipt / post-commit consistency); F13 / PBDIGIT-218; PBDIGIT-235; R2a architecture test (deferred); F8 A1 / A6 (Phase 2.0).
- **D. Blocking findings:** **none** — no finding contradicts the approved ADR invariants for the final cast; no competing authority on the real-election cast path.

## 5. System-level status (not only the final cast)

```
Phase 4 final cast ................................ CLOSED
Voting journey as a whole
  ├── step 4 D10 / MAX_USE_IP_ADDRESS (C1) ........ open
  ├── PBDIGIT-234 dormant IP binding ............... open
  ├── PBDIGIT-218 legacy POST /votes ............... open
  ├── PBDIGIT-235 soft-deleted code ................ open
  ├── A1 / A6 anonymity ............................ deferred (Phase 2.0)
  └── receipt / post-commit semantics (F11, F12) ... open
```

## 6. PO decision (2026-10-05)

```
PHASE 4 — FINAL CAST HARDENING
Implementation:        CLOSED
Tests:                 CLOSED
Documentation:         CLOSED
Authority audit:       CLOSED
Blocking findings:     NONE
Architectural status:  ACCEPTED — Phase 4 frozen
```

- F1 #2: accepted redundancy, candidate for later simplification — no Phase 4 change.
- C1 elevated: **first explicit Phase 5 discovery question** — *"What is the intended authority and operational contract for the step-4 voting gate (`verify()`), and should the legacy `vote_post_check()` security / IP-density behaviour remain there at all?"* — to be answered **before** touching `MAX_USE_IP_ADDRESS` or `verify()`. Setting the variable is not to be treated as solving C1.
- F10 → Phase 5 backlog (future-type hardening). F11 / F12 → future receipt / post-commit consistency design (no `store()` changes now). F8 stays deferred to Phase 2.0; A5 / A6 not in Phase 5 unless its approved scope says so.
- Phase 5 itself still needs a separate authorisation (and PO answers to Q6, Q7; Q4 for demo).

**Traceability:** ADR_20261005_0950 §3, §5 (I1–I10), §7 · Phase 4 plan and decision record · PBDIGIT-218, 219, 230, 232, 233, 234, 235.
