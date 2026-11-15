# 26: Suspend and Restore (lifecycle S2-2), PBDIGIT-354

**Decisions:** D-163 (Suspend / Restore in release 1) · accepted design D-177 §2, §4, §5 rows 8–16, 15b, 18–19, 22–23; §16 C-1, C-2; §17 H-1; §18 F-04 · D-205 (3) (nothing future-dated) · D-236 (`suspended` is the published standing; no separate contract) · blueprint G-3.
**Plan:** `docs/plans/20261115-0510-release-1-build-slice-s2-membership-lifecycle-plan.md`, step S2-2.
**Evidence:** `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-2/`.

## Business purpose

- **A suspension** restricts a member's rights for a time, without ending the membership. The person **stays a member**, and the term keeps running.
  - It has an end date, or none: then it runs until restored.
  - **Only a membership in force can be suspended.** A lapsed membership exercises no rights, so there is nothing to restrict.
  - **If the term lapses during a suspension, the restriction continues.**
- **A restoration** ends **one** suspension early, at a date strictly inside it. If the term lapsed meanwhile, the organisation **renews first, then restores**.
- **The forum and newsletter (later slices) use this:** posting and receiving need "in force and not suspended" (D-161). They compute that themselves from the published intervals (D-177 §2).
- **No reason, ground or consequence** is recorded or enforced: the accepted texts define none.

## Where it fits

| Layer | Class | Role |
|---|---|---|
| Domain | `Suspend` | period, `effectiveAt`, `until` (finite or ∞) |
| | `Restore` | the Suspend it ends, `effectiveAt`. **Not** a `ReversalFact`: it ends a restriction, it does not cancel the Suspend |
| | `Member::decideSuspend`, `Member::decideRestore` | decisions and guards |
| | `Member::openPeriodAt` | "the period containing t", shared with Renew |
| | `Resolution::restrictions`, `ResolvedPeriod::restrictions()` / `isSuspended()`, `ResolvedMember::suspendedIntervals()` / `isSuspended()` | the resolved restriction |
| Application | `SuspendMembership` / `SuspendMembershipHandler` | use case; authority `MembershipAct::Suspend` |
| | `RestoreMembership` / `RestoreMembershipHandler` | use case; authority `MembershipAct::Restore` |
| | `Ports/StorableValidTime` | the dates must fit the database's range |
| Infrastructure | `PostgresMemberRepository` | `suspend` and `restore` rows |
| | `InterimRoleAuthority` | **unbound** (G-3); denies Renew, Suspend and Restore with evidence |
| | migrations `2026_11_15_350000_…` (central) and `OrganisationMigrations/2026_11_15_300600_…` | the two kinds (parity) |

## The rules (D-177 §4)

| Act | Guards, in order | Refusals |
|---|---|---|
| **Suspend** | unique fact id → non-empty restriction → a period has started → not ended → **in force at `effectiveAt`** → not after its decision date | `DuplicateFactId`, `EmptyRestriction`, `NoPeriodAt`, `PeriodEnded`, **`NotInForce`**, `EffectiveDateAfterDecisionDate` |
| **Restore** | unique fact id → the target exists → it is a **live** Suspend → its period is **in force** at `effectiveAt` → **no live Restore on it yet** → strictly inside the restriction (`s.from < at < s.until`) → not after its decision date | `DuplicateFactId`, `TargetNotFound`, `NotSuspended`, `NotInForce`, **`AlreadyRestored`**, **`OutsideRestriction`**, `EffectiveDateAfterDecisionDate` |

**Decisions made in the code (documented so they are reviewable):**
- **A Restore at or before its suspension's start is `OutsideRestriction`.** There is nothing yet to end; removing the suspension altogether is an Annul or a Void.
- **A Restore after the suspension's period has ended is `NotInForce`.** D-177 §4 gives Restore no other refusal for that case, and an ended period is not in force.
- **Correcting a Suspend's or Restore's date** (D-177 row 14) is refused (`CorrectionOfSuspendOrRestoreNotSupported`) **until the correction step S2-5.**

## Resolution

```
restrictions(q) = ⋃ over the active Suspends s of q:
                    [s.from, s.until)                   no active Restore on s
                    [s.from, min(s.until, r.from))      r = s's active Restore
Suspended(q, t) = t ∈ restrictions(q) ∩ lifetime(q)     no in-force condition (F-04)
```

- **Annul or Void of a Suspend:** it is no longer active, so it restricts nothing, and its Restore is orphaned (row 18).
- **Annul of a Restore:** the suspension runs again from its start (row 19), and a new Restore is accepted.
- **History:** `knownAt(τ)` sees only facts recorded by τ. Before the Restore was recorded, the suspension is known as running on (row 22).
- **Never changed:** coverage, the term, `inForce` and `isMember` (row 23, S2). A restriction never carries into a later period after an End and a new admission.

## How to use it

```php
// suspend from $day; null `until` = until restored
(new SuspendMembershipHandler($repository, $authority))->handle(new SuspendMembership(
    tenantId: $organisationId, memberId: 'M1', factId: 'S1',
    effectiveAt: $day, decisionDate: $day, until: null,
    actor: $actorRef, decidedAt: null, idempotencyKey: $key1,
));

// end that suspension later
(new RestoreMembershipHandler($repository, $authority))->handle(new RestoreMembership(
    tenantId: $organisationId, memberId: 'M1', factId: 'R1', suspensionFactId: 'S1',
    effectiveAt: $laterDay, decisionDate: $laterDay,
    actor: $actorRef, decidedAt: null, idempotencyKey: $key2,
));
```

**Authority:** each handler asks `AuthorityCheckPort` on every attempt, for `suspend` / `restore`, and records the evidence on the fact. **No production binding exists until S3:** the container cannot build either handler. `MembershipThroughRoutingTest::test_s2_2_…` asserts this.

## Storage and publication

| Kind | `effective_at` | `target_fact_id` | `restriction_until_kind` / `restriction_until` |
|---|---|---|---|
| `suspend` | start | the period (its Admit) | `until` + date, or `unlimited` + NULL |
| `restore` | restoration date | **the Suspend** | NULL |

- **CHECKs:** the `kind` CHECK (`renew`, `suspend`, `restore` share one branch); `org_membership_facts_restriction_check` (the columns are set exactly on suspend rows; kind and date agree; not empty; with an explicit `IS NOT NULL`).
- **C1:** no new code. The repository writes `MembershipCoverageResolved` in the append transaction whenever `suspended` changed; `coverage` and `inForce` are unchanged. A suspension wholly inside another one publishes nothing.
- **C2** (`CoverageKnownAt`) carries `suspended` from `ResolvedMember::suspendedIntervals()`, now real.

## Testing

| Suite | What |
|---|---|
| `tests/Unit/Contexts/Membership/Domain/Organisational/SuspendRestoreTest.php` (27) | the decision table, every refusal, history, interactions with Renew and End |
| `tests/Unit/Contexts/Membership/Application/Organisational/SuspendRestoreHandlersTest.php` (9) | the use cases, authority, idempotency, isolation by organisation, storable dates |
| `tests/Unit/…/Infrastructure/Organisational/InterimRoleAuthorityTest.php` (+2) | the unbound interim adapter denies the new acts and answers every act kind |
| `tests/Feature/Contexts/Membership/Organisational/PostgresSuspendRestoreTest.php` (16) | round trip, stored rows, C1, `knownAt` after reload, 11 malformed rows refused |
| `tests/Feature/Contexts/Membership/Organisational/PostgresCoverageKnownAtTest.php::test_s2_2_…` | **C2** answers `suspended` as known at each τ, through the real closer (added after review) |
| `tests/Feature/Contexts/Platform/MembershipThroughRoutingTest.php::test_s2_2_…` | in the organisation's own database through routing; B untouched; fail-closed wiring |

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational/SuspendRestoreTest.php tests/Unit/Contexts/Membership/Application/Organisational/SuspendRestoreHandlersTest.php
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite MembershipPostgres --filter PostgresSuspendRestoreTest
```

## Pitfalls

- **A new `MembershipAct` must be answered by `InterimRoleAuthority`.** Its `match` was not total after S2-1 (Renew would have crashed it). It is total now, and `test_every_act_kind_has_an_answer` keeps it so.
- **No other context's name in Membership's adapters.** `MembershipSlice1bPersistenceBoundaryTest` refuses words such as "governance" there; write "the authority owner".
- **Assert that a command was recorded before reading the log** in a test. A mutant once turned such a test's failure into an error.
- **The parity, ledger-isolation and storable-date rules of guide 25 apply** to every new fact kind.

## Open

- **S2-5:** correcting the dates of Suspend and Restore (row 14), and Q-2 (correcting a Renew's `until`).
- **S2-6:** suspensions pushed from a Governance resolution.
- **Statutes that differ from the ratified defaults** (for example, "renewal while suspended") are recorded with the decision; no platform rule (D-177 §14).

**Traceability:** PBDIGIT-354 S2-2 · D-163 · D-177 · D-205 · D-216 (G-3) · D-233 · D-236.
