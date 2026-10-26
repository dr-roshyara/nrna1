# PBDIGIT-308: Login and voter-management feature tests fail on stale fixtures

**Type:** Test maintenance · **Epic:** none · **Created:** 2026-10-26 · **Raised by:** found while verifying PBDIGIT-307.

| | |
|---|---|
| **Status** | `OPEN` · causes analysed 2026-10-26 (read-only); fix not authorised |
| **Classification** | **observed:** failures pre-exist PBDIGIT-307. Same results with and without its change (compared by stashing the change) |
| **Scope** | test code; two product issues split off as PBDIGIT-309 and PBDIGIT-310 |

## Why it matters
Login, invitation and voter-management regressions cannot be detected while these suites are red. PBDIGIT-307 needed a with/without comparison to show it broke nothing.

## Analysis (2026-10-26, branch `election-flow-updated`)

### Related suites: 27 failures, 3 causes (all confirmed)
| Group | Tests | Cause | Fix |
|---|---|---|---|
| **A** duplicate role row | 18 | `UserFactory::configure()` creates the user's `user_organisation_roles` row automatically (since `34994a8d6`, 2026-04-20). The tests (last changed 2026-04-06) add it again with `$this->org->users()->attach()` → unique `(user_id, organisation_id)` violation. Sites: `ElectionVoterManagementTest.php:58,74`, `Middleware/EnsureElectionVoterTest.php:45`, `SingleElectionLoginFlowTest` | test only: update the factory-made row (or `firstOrCreate`) instead of `attach()` |
| **B** election without organisation | 8 | `SingleElectionLoginFlowTest.php:39` creates elections without `organisation_id`. The tenant auto-fill looks up slug `platform`, but the factories create `publicdigit` → NULL → NOT NULL violation | depends on **PBDIGIT-309** (canonical slug); meanwhile give the test an organisation |
| **C** election without slug | 1 | `Auth/VoterInvitationSetPasswordTest.php:68` deliberately creates `slug = null`. The schema forbids it, so the scenario cannot occur | delete the test |

### `tests/Feature/Auth`: 93 failures
| Count | Error | Meaning |
|---|---|---|
| 21 | column `users.is_voter` does not exist | removed column |
| 13 | invalid uuid `"1"` | integer IDs from before the UUID schema |
| 12 | `user_organisation_roles.id` NULL | raw `DB::table()->insert` without the UUID `id` |
| 12 | route `dashboard.emergency…` not defined | removed feature |
| 5 | column `assigned_at` does not exist | old schema |
| 4 | duplicate role row | group A, and the registration listener (**PBDIGIT-310**) |
| 3 | transaction aborted | follow-on error from a failed insert (PBDIGIT-310) |
| ~13 | missing Fortify interface, `markDownForMaintenance`, `TenantContext::getCurrentOrganisation()`, table `election_commission_members`, roles not seeded, assertion failures | code these tests call no longer exists, or not yet classified |

Most files were last changed together on 2026-04-06, which looks like a bulk move rather than maintenance.

**Possibly current behaviour, check separately:**
- `HasActiveElectionTest` (changed 2026-08-08; 3 failures, e.g. "a running election must be found…").
- `RegistrationMultiTenancyTest` (500 on registration; likely PBDIGIT-310).

## Next
1. Fix groups A and C (test only).
2. Group B after PBDIGIT-309 is decided.
3. Auth suite file by file: rewrite or delete tests for removed features; the PO decides on deletions.
4. Re-run and record the counts.
