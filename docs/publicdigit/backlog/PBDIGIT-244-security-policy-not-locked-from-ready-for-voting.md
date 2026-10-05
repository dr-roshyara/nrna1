# PBDIGIT-244 — The election's security policy can still be changed from ReadyForVoting (I9)

**Type:** Defect — **constitutional enforcement gap** · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** I1–I13 completion matrix `docs/publicdigit/reviews/2026-10-05-voting-constitution-i1-i13-completion-matrix.md` (I9), committed `ad0cd01db`
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** `git grep` over `docs/publicdigit/backlog/` for a security-policy lock / I9 found no owner. `PBDIGIT-213` C2 locks the **admission** policy only. `PBDIGIT-240` (one security authority, I3) and `PBDIGIT-234` (`users.voting_ip`) are related but do not cover the lifecycle lock.

| | |
|---|---|
| **Status** | 🟢 **RESOLVED for the currently defined four-field boundary (2026-10-05)** — RED `58b0ec769` · GREEN (PO-authorised): application-layer refusal in `ElectionSettingsController::update()` (whole PATCH refused, `SecurityPolicyLocked`) + `Election` model guard on `SECURITY_POLICY_FIELDS`. Evidence: `SecurityPolicyLifecycleLockTest` 26/26; 97-suite regression vs `5cbba6f15`: one status change (`VotingSecurityVerdictAdapterTest` whitelist known-defect fixture), repaired fixture-only, suite back to baseline. **Q3 open** — completeness of the policy surface is not claimed. Guide `developer_guide/election/settings/01_security_policy_lifecycle_lock.md` |
| **PO decisions (GREEN)** | refuse the whole PATCH · `ip_whitelist` `null` = `[]` · fixture adaptations via the query builder (RED suite baseline; `VotingSecurityVerdictAdapterTest` line 232) · guide under `election/settings/`, not voter admission |
| **RED evidence [D] (synthetic data)** | `tests/Feature/Election/SecurityPolicyLifecycleLockTest.php`, 18 cases = 3 components (`ip_restriction` = `ip_restriction_enabled` + `ip_restriction_max_per_ip`; `ip_whitelist`; `verification_mode` = `voter_verification_mode`) × 6 states. Draft · SetupAdministration · SetupNomination: 9/9 pass (change stored). ReadyForVoting · VotingActive · Suspended: **9/9 RED** — the Chief's settings PATCH is accepted and the new value is stored in every case (e.g. `voter_verification_mode` none → ip_only). Refusal shape (error key / message) left to GREEN |
| **Invariant (ADR_20261005_0950 §5, I9)** | "The security policy is immutable from ReadyForVoting (like the admission policy) — the policy boundary itself is Q3." |
| **Fact [S] (source reading at `ad0cd01db`, no test yet)** | `ElectionSettingsController::update()` checks the `manageSettings` policy (active Chief / Deputy) and an optimistic lock only. `UpdateElectionSettingsRequest` accepts `ip_restriction_enabled`, `ip_restriction_max_per_ip`, `ip_whitelist`, `voter_verification_mode` with no lifecycle check. `ElectionLifecycle::isLocked()` is passed to the page only. Not yet proven dynamically |
| **Proposed RED (for authorisation)** | per security-policy component: change accepted before ReadyForVoting; change refused (and the stored value unchanged) from ReadyForVoting on — modelled on the admission-policy lock tests |
| **Open decision** | **Q3** — which settings form the security policy. The PO review names IP restriction, whitelist and verification mode; Q3 decides whether others belong (e.g. `max_votes_per_ip`, `network_binding_strategy`) |
| **Out of scope** | I3 consolidation (PBDIGIT-240) · the `users.voting_ip` column (PBDIGIT-234) · admission policy (already locked, PBDIGIT-213) · UI changes beyond what the lock requires |

**Traceability:** completion matrix §I9 · ADR_20261005_0950 §5 I9, §9 Q3 · PO review 2026-10-05 (I9 promoted to a tracked constitutional-enforcement item; order: I9 before PBDIGIT-240).
