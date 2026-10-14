# PBDIGIT-296: Tests call classes, methods and routes that the code no longer has

**Type:** Test-suite health · **Epic:** none · **Created:** 2026-10-14 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the PBDIGIT-283 R6 positive runs (the first full default-suite runs on `nrna_test` since the 2026-10-13 incident). **Index row:** added 2026-10-14.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the Principal 2026-10-14 |
| **Classification** | **observed in test output**; per-case root causes **not analysed** |
| **Scope** | unrelated to PBDIGIT-283; nothing fixed |

## Finding (from the logs)
Counts are across the Unit, Feature and Architecture runs: `docs/publicdigit/election/evidence/2026-10-14-pbdigit283-r6-positive/` (`stage-2-unit.txt`, `stage-3-feature-rerun.txt`, `stage-3-architecture.txt`).
- **Missing classes or interfaces (86):**
  - `App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId` (57);
  - `Laravel\Fortify\Contracts\LoginResponse` (10);
  - `App\Models\DemoCandidate` (6);
  - `App\Domain\Election\Security\OverlayInfluenceContext` (6), `OverlaySignal` (5);
  - `Database\Factories\ResultFactory` (1).
- **Undefined methods (161):**
  - `HasMany::attach()` (103): `attach` is called on a has-many relation;
  - `Election::canTransitionTo()` (9), `ElectionMembership::bulkAssignVoters()` (4);
  - test helpers `assertNotEqual`/`assertIn` (8), `FakeEventBus::dispatchAll()` (4).
- **Constructor and type changes:** `TypeError` (111), `ArgumentCountError` (53). Examples: `CommitteeLevel::create`, `Fee::create`, `Member::register`, `VoterSlugService::__construct`, `MemberImportService`.
- **Null access (69):** `Attempt to read property … on null`.
- **HTTP expectations (95):** expected `422`/`201` but received `302` (40), expected `200` but received `404` (11), expected `302` but received `500` (5).
- **Assertion failures (172),** spread across modules.
- **Deliberately pending tests:** e.g. `EM-IMPL-002 GREEN-5 pending` (6). Expected RED, not drift.

## Proposed direction (nothing decided)
1. **Group by module** (Membership, Election, Voting, Auth/Fortify).
2. **Per group, decide** whether to update the test (code moved on deliberately), restore code (regression), or retire the test (obsolete).
3. **The 302-instead-of-422/201 cases** need a check of the request path (Inertia redirects vs. JSON), not blanket updates.
4. **Track deliberate RED tests explicitly,** so they're not mistaken for drift.

## Acceptance criteria
1. Each listed class or method is classified (moved, removed intentionally, regression), with the decision recorded.
2. The affected tests pass, or are retired with a reason; deliberate RED tests are listed.
3. No production behaviour changes without its own story.
