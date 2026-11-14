# 24: The ledger's C2 ports in production (M-1 … M-3), PBDIGIT-345 task 10

**Decision:** D-233. **Design:** routing tactical design §3.5, §3.7 L-3. **Platform side:** Platform guide 10.

## Business purpose

**C2 (`CoverageKnownAt`, "who was a member, and when, as known at τ") now answers from the right organisation's own register in production,** and refuses otherwise:

| Situation | Answer |
|---|---|
| asked about the organisation this request or job is routed to, while the registry routes it | **complete**, from that organisation's own database |
| asked about another organisation, or no routed operation at all | **`CROSS_ORGANISATION`** (C2's first check, unchanged) |
| the routed organisation stopped being registry-routable during the operation (e.g. `migration-failed`) | **`SOURCE_NOT_LEDGER`** (D-3) |
| boundary not closed up to τ | `BOUNDARY_NOT_CLOSED` (unchanged) |
| registry or database unreachable | **an error** (503 for a request, a retry for a job): never "not a member" |

## The bindings (`MembershipServiceProvider::register`)

| Port (Membership Application) | Production adapter (Membership Infrastructure) | Uses Platform's published |
|---|---|---|
| `WorkingOrganisationPort` (**M-1**) | `RoutedWorkingOrganisation` | `RoutedOrganisation::current()` |
| `MembershipSourceOfTruthPort` (**M-2**) | `RegistryRoutedLedgerSource` (**its only true path**, T-14 part 2) | `RoutedOrganisation::isRegistryRouted()` |
| `LedgerBoundaryPort` (**M-3**) | `RoutedLedgerBoundary`: the existing `LedgerClockCloserBoundary` and `LedgerClockCloser` on the routed connection, per call | `RoutedOrganisationConnection` |
| `MemberRepositoryPort` | `RoutedMemberRepository`: the existing `PostgresMemberRepository` on the routed connection, per call | `RoutedOrganisationConnection` |
| `LedgerClockReader` | `PostgresLedgerClockReader` | — |

**What doesn't change:**
- Membership's Domain and Application import nothing of Platform; only these Infrastructure adapters use the published types (`MembershipPlatformBoundaryTest`).
- `FailClosedMembershipSourceOfTruth` stays as the documented fail-closed default.
- Facts and their C1 event are written in the organisation's database in one transaction, and relayed per organisation (Platform guide 09).

**Recorded legacy deviations** (`InstallMembershipModule`, `MemberImportController` → Platform module-installation classes) predate this task. They stay visible in the ratchet baseline and are the only other Membership → Platform references the boundary test tolerates.

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Infrastructure/Organisational tests/Architecture/MembershipPlatformBoundaryTest.php
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres --filter MembershipThroughRoutingTest
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite MembershipPostgres   # T-12 part 1: the 135 slice-1 tests, unchanged
```

**`MembershipThroughRoutingTest`:**
- **T-7:** a real E-2 job; cross-organisation; `SOURCE_NOT_LEDGER` during the operation.
- **The consistency case:** a flip after M-2 and before M-3.
- **The registry outage:** an error, not a refusal.
- **T-12 part 2:** repository, closer and C2 on a real organisation database.
- **Production wiring.**

**Traceability:** PBDIGIT-345 task 10 · D-233 · M-1 … M-3 · T-7, T-12, T-14 part 2.
