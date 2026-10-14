# PBDIGIT-289: An opened election without a voting end time falls back to SetupNomination, and its security settings become editable again

**Type:** Defect (election lifecycle / security policy) · **Epic:** none (election lifecycle) · **Created:** 2026-10-14 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the PBDIGIT-286 principal review (read-only inspection of `ElectionClockService::isVotingOpen`). **Index row:** added 2026-10-14.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the Principal 2026-10-14 |
| **Classification** | **verified in source** (read-only). Not reproduced by a test. Whether any such election exists in a database is **not checked** |
| **Scope** | **outside PBDIGIT-286.** PBDIGIT-286's release rule blocks activation while any opened election has an empty end time (ADR-B §3.6b); this item fixes the lifecycle itself |

## Finding (verified in source)
- **`ElectionClockService::isVotingOpen()` returns `false`** when `voting_starts_at` **or** `voting_ends_at` is empty (`app/Services/ElectionClockService.php:47-55`).
- **For an election with `voting_locked = true` and an empty `voting_ends_at`**, the state derivation (`app/Application/Election/Services/ElectionLifecycleEngineImpl.php`) works out as follows:
  - **Counting** needs a non-empty end time in the past (`:85`), so it **doesn't apply**;
  - **VotingActive** needs `isVotingOpen()` (`:110`, `:178-180`), so it **doesn't apply**;
  - **ReadyForVoting** needs both times (`:118-122`), so the state **falls through to SetupNomination**;
  - `hasVotingEnded()` also treats an empty end as "not ended" (`:183-186`).
- **SetupNomination counts as "not locked"** (`isLocked()`, `:295-304`). So `Election::SECURITY_POLICY_FIELDS` (System A's override, whitelist, IP binding, verification mode; `Election.php:63-69`) become **editable again** through the settings page and the model guard (`Election.php:2329`).
- **The application can't repair it:** voting dates are editable only while `voting_locked = false` (`canUpdatePhaseDates('voting')`, `Election.php:979`).
- **No vote is possible:** `canVote` is true only in VotingActive (`ElectionLifecycleEngineImpl.php:257-259`).

## How it can arise (corrected 2026-10-14 during the backlog review)
- **Explicit opening** (`open_voting` through `transitionTo`) is guarded. The precondition `voting_window_defined` requires a non-empty, future `voting_ends_at` (`ConstitutionalTransitionGuard.php:226`; required for `open_voting` in `ElectionConstitution.php:106`). The guard runs only for transitions that aren't system-triggered (`Election::transitionTo`). [V]
- **The scheduled job** `ProcessElectionAutoTransitions::processNominationToVotingTransition` (`app/Console/Commands/ProcessElectionAutoTransitions.php:80-118`) calls `Election::enforceVotingLock()` **without checking `voting_starts_at` or `voting_ends_at`** and without the transition guard. Its only checks are: `allow_auto_transition`, nomination completed plus the grace period, no pending candidates, and at least one approved candidate. [V]
  - **So the malformed state is reachable through the application:** an election with auto-transition enabled whose nomination completes without a voting end time.
  - **Not verified:** whether such an election can reach that point, i.e. whether completing nomination is possible without voting dates [U].
- **Closing voting** sets an end time (`applySideEffectsForCloseVoting`). [V]
- **Other origins:** direct database edits, imports or test fixtures [I].
- **Not reproduced by a test, and no database checked.**

## Impact
- **Security-policy settings can be changed on an election whose voting was opened.** The voting record is then followed by different security settings, which undermines the audit of what applied during voting.
- **The displayed lifecycle state is misleading:** it shows SetupNomination after voting was opened.
- **System B is not affected:** the PBDIGIT-286 freeze keys on `voting_locked`, not on the derived state (ADR-A).

## Proposed direction (for this item's own analysis; nothing decided)
1. The derivation never returns a pre-voting state once `voting_locked = true`. For example, an opened election with an empty end time maps to an explicit inconsistent or locked state, or to Counting.
2. **Alternatively:** `isLocked()`, or the security-policy lock, also considers `voting_locked = true`, independently of the derived state.
3. **A repair path** for a missing end time, under committee or operations control and audited.
4. **The scheduled job** (`processNominationToVotingTransition`) applies the same `voting_window_defined` precondition as the explicit path before locking voting.
5. **Test first:** an opened election with an empty end time is still "locked", and its security-policy fields can't be changed; the scheduled job doesn't lock voting without a defined voting window.

## Related
- `PBDIGIT-286`: decision record `docs/publicdigit/reviews/2026-10-14-pbdigit286-principal-decision-record.md` §5 item 1 and §8; ADR-B `docs/publicdigit/adr/ADR_20261014_1138_Constitutional_Policy_Frozen_Record.md` §3.6b.
- `PBDIGIT-244` (security-policy lock, `SECURITY_POLICY_FIELDS`).
