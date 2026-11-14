# Platform–Membership routing contract (PBDIGIT-345 task 10, Stage A): PROPOSAL

| | |
|---|---|
| **Status** | **ACCEPTED by the Human Arb Chief on 2026-11-14 as D-233** (D-1 A, D-2 re-check, D-3 as read, D-4 two suites), with the consistency condition answered in §8. The PROPOSED text above §8 is kept as written, except the D-3 example corrected in §8.1. Written by Claude (Session A), not independent |
| **Commission** | Principal authorisation 2026-11-14, "PBDIGIT-345 Task 10", Stage A |
| **Consumes (does not change)** | routing tactical design §3.5 (M-1 … M-3), §3.7 (L-1 … L-3), T-7, T-12, T-14; slice 1 C2 (`CoverageKnownAt`); the Hexagonal DDD baseline and the ratchet (A-3; baseline §9); D-160, D-219 (1) |

## 1. Business purpose (plain language)

**Membership must answer "who was a member, and when" only from the right organisation's own register.**
- **Every request and every background job already knows its one organisation** (tasks 6 and 7), and only that organisation's database is open.
- **Task 10 lets Membership use that,** and nothing else. Membership asks, in effect: "which organisation am I working for right now, is it a properly registered ledger organisation, and where is its register?"
- **Platform answers** (routing). **Membership decides** everything about memberships.

If any answer is missing or doubtful, Membership refuses; it never falls back to a guess or to the old central tables.

## 2. What exists today (repository facts)

| Fact | Where |
|---|---|
| C2 (`CoverageKnownAt`) checks, in this order: (1) `WorkingOrganisationPort::current()` must equal the requested organisation, else **`CROSS_ORGANISATION`**; (2) `MembershipSourceOfTruthPort::isLedger(org)`, else **`SOURCE_NOT_LEDGER`**; (3) `LedgerBoundaryPort::closeOrRead(org)`, else `BOUNDARY_NOT_CLOSED` | `app/Contexts/Membership/Application/Organisational/CoverageKnownAt.php` (slice 1, accepted) |
| The three ports, and the ledger `MemberRepositoryPort`, have **no production binding**: only tests implement them. `FailClosedMembershipSourceOfTruth` exists | `MembershipServiceProvider`, `…/Infrastructure/Organisational/` |
| `PostgresMemberRepository` and `LedgerClockCloser` take a `Connection` (and write facts and C1 into it, task 9) | Membership Infrastructure |
| The organisation context is `Platform\Infrastructure\OrganisationContext(TenantId, Connection)`, bound per request (E-1) or per job attempt (E-2) | Platform Infrastructure |
| **Nothing in production calls C2 yet.** Its first consumer is Organisation (slice 3) | grep |
| **A-3 forbids Membership importing Platform Infrastructure** (and vice versa), except published language or a recorded consumer-owned-port adapter | `ArchitectureRatchetTest`, `published-language.json` |

## 3. Proposed contract (recommended: Platform as an OPEN HOST)

**Platform publishes one small contract for every organisation-owned context.** Membership is the first; Governance, Communication, Deliberation and Finance follow. Membership's adapters consume it. Platform imports nothing of Membership.

### 3.1 Published by Platform (listed in `published-language.json`)

```php
namespace App\Contexts\Platform\Application\Published;

/** The organisation THIS operation (request or job attempt) was routed to: Laravel-free. */
interface RoutedOrganisation
{
    /** The current routed organisation, or null outside one. Never from caller input. */
    public function current(): ?TenantId;

    /**
     * True only if $organisation IS the current routed organisation AND the registry routes it now
     * (operational, schema current: ResolutionService). Registry unreachable → throws (fail closed, never "false").
     */
    public function isRegistryRouted(TenantId $organisation): bool;
}
```

```php
namespace App\Contexts\Platform\Infrastructure\Published;

/** For organisation contexts' INFRASTRUCTURE adapters only: the current organisation's own connection. */
interface RoutedOrganisationConnection
{
    /** @throws NotTheRoutedOrganisation if $organisation is not the current routed one (or there is none) */
    public function connectionFor(TenantId $organisation): \Illuminate\Database\Connection;
}
```

**One Platform adapter implements both,** reading the request- or job-scoped `OrganisationContext` and `ResolutionService`. **Platform never hands out a connection for an organisation other than the current one.**

### 3.2 Membership's production bindings (Membership Infrastructure, Membership's provider)

| Port (Membership-owned, unchanged) | Production adapter (new, Membership Infrastructure) | Uses |
|---|---|---|
| **M-1** `WorkingOrganisationPort::current()` | `RoutedWorkingOrganisation` | `RoutedOrganisation::current()` |
| **M-2** `MembershipSourceOfTruthPort::isLedger(org)` | `RegistryRoutedLedgerSource` (true only via `isRegistryRouted`; **its only true path**, T-14 part 2) | `RoutedOrganisation::isRegistryRouted()` |
| **M-3** `LedgerBoundaryPort` | `LedgerClockCloserBoundary` (existing), with a `LedgerClockCloser` on **`connectionFor(org)`**, resolved per call | `RoutedOrganisationConnection` |
| ledger `MemberRepositoryPort` | `PostgresMemberRepository` on `connectionFor(org)`, resolved per call | `RoutedOrganisationConnection` |

**Outside any routed context:**
- M-1 returns `null`, so C2 answers `CROSS_ORGANISATION`;
- M-2 is `false`;
- `connectionFor` throws.

**There is no path to the central or legacy tables.** `FailClosedMembershipSourceOfTruth` stays for tests and as the documented default.

### 3.3 Answers to the commission's ten questions

| # | Question | Answer |
|---|---|---|
| 1 | Who owns each contract | Platform owns `RoutedOrganisation` and `RoutedOrganisationConnection` (published). Membership owns its ports, C2, its adapters and every membership rule |
| 2 | What Membership consumes | the two published interfaces above, in its Infrastructure adapters only. **Membership's Domain and Application see only their own ports** |
| 3 | What crosses the boundary | `TenantId` (Shared, already common) and, at Infrastructure level only, Laravel's `Connection`. **No Platform domain object, no locator, no registry entry** |
| 4 | How the context is established and verified | by the existing E-1 middleware or E-2 job middleware (registry-resolved, request- or attempt-scoped). Membership never establishes one. `isRegistryRouted` re-asks the registry |
| 5 | The connection without importing Platform Infrastructure internals | through the published `RoutedOrganisationConnection` (an interface; the factory and `OrganisationContext` stay internal) |
| 6 | `SOURCE_NOT_LEDGER` | M-2 is false unless the organisation is the current routed one **and** the registry routes it now: e.g. a provisioning process's context, or an organisation that stopped being routable |
| 7 | `CROSS_ORGANISATION` | M-1 returns the routed organisation only; C2 already refuses any other requested organisation (and `null`) |
| 8 | Missing, invalid or unavailable context | no context: refusals above. Registry or database unreachable: **exceptions propagate** (the request answers 503, the job retries). **Never** answered as "not a ledger" or "not a member" (failure row 6) |
| 9 | Trusted actor | **not applicable to M-1 … M-3:** C2 is a query, and the working organisation comes from the routed context, never from the caller. Actor-bearing commands are the Membership lifecycle (later), through Governance's AuthorityCheck |
| 10 | Outbox guarantees | unchanged: `PostgresMemberRepository` on the routed connection writes facts and C1 in one transaction in that database, relayed per organisation (task 9) |

## 4. Alternatives considered

| | Option | For | Against |
|---|---|---|---|
| **A (recommended)** | **Platform publishes the contract (open host);** organisation contexts conform | one contract for every organisation context; Platform depends on no business context; follows how Identity and Geography publish | Platform owns a published interface that must stay stable; Infrastructure-level publication (`Connection`) is unusual |
| B | **Consumer-owned ports:** Membership defines the ports; Platform implements them (as Geography implements Membership's `GeographyLookupInterface`) | Membership defines exactly what it needs | **Platform would import every organisation context** (Membership, Governance, Communication …), and M-3 would push Platform into Membership's Infrastructure. Grows badly |
| C | Membership reads Laravel's container key `OrganisationContext` directly | least code | a direct import of Platform Infrastructure: **forbidden by A-3**. Rejected |

## 5. Decisions requested

| # | Decision | Recommendation |
|---|---|---|
| **D-1** | **Contract style:** A (Platform open host) or B (consumer-owned ports) | **A** |
| **D-2** | **How M-2 knows the context is "registry-resolved"** (L-3). (a) Re-ask the registry on each call: `ResolutionService`, one central read per C2 call, always fresh, **no change to tasks 3–7**. (b) A provenance mark on the context, set only by the E-1/E-2 entries: no extra read, but provisioning code would need a separate context path | **(a)** |
| **D-3** | **Reading of "`SOURCE_NOT_LEDGER` outside a registry context" (plan task 10, T-7).** With C2's accepted order, a call with **no** context at all answers **`CROSS_ORGANISATION`** first (M-1 is null). `SOURCE_NOT_LEDGER` then means: **a context for that organisation exists but is not registry-routed** (e.g. a provisioning process, or an organisation that stopped being routable). Changing C2's order would reopen slice 1's accepted C2; **not proposed** | accept this reading; T-7 tests both cases |
| **D-4** | **T-12's scope.** "The slice 1 suite still passes, now also with the connection supplied by the factory." The 135 slice-1 tests build their repositories on the default test database and insert into the central `organisations` table, which an organisation database does not have (F-13). Running them unchanged on a factory connection is not possible without rewriting them. **Proposal:** (1) the slice 1 suite stays green, unchanged; (2) a **factory-connection suite** runs the slice-1 behaviours that task 10 binds (repository create/append/load, the closer, C2 complete and refusals) on a factory-established organisation database | accept (1) + (2) |

**No business-authority decision is involved:** Platform gets no say in membership, and Membership gets no routing power.

## 6. Tests and evidence (Stage B, after approval)

**T-7, C2 end to end through routing,** on two real organisation databases:
- inside an E-2 job and an E-1 request for A: C2 for A is **complete**;
- C2 naming B while routed to A gives **`CROSS_ORGANISATION`**;
- no context gives `CROSS_ORGANISATION`;
- a context for a **provisioning** organisation, or A after `migration-failed`, gives **`SOURCE_NOT_LEDGER`**;
- the registry unreachable gives an exception, not a refusal.

**T-14 part 2 (architecture):**
- **M-2 has no other true path:** `RegistryRoutedLedgerSource` is the only `MembershipSourceOfTruthPort` implementation that can return true, and its true path goes through `isRegistryRouted`;
- **Membership imports only the published Platform interfaces** (A-3; `published-language.json` gets the two interfaces, nothing else).

**`connectionFor`:** refuses another organisation and refuses outside a context; Platform never returns a connection for B while routed to A.

**T-12:** as D-4.

**Regression and mutation:**
- the whole cluster and database-free sets; the architecture suite (the 4 known failures listed separately);
- mutants of M-1, M-2 and `connectionFor` (equality checks, the registry re-check, the null path), classified as usual.

**Guides:**
- Platform guide 10 (the published contract);
- Membership guide 24 (the production bindings);
- index updates.

**No migration, no new environment variable,** so no administrator instructions are expected.

## 7. Risks

- **Infrastructure-level published interface (`RoutedOrganisationConnection`):** it must stay small. It is the only door to an organisation's database for other contexts' adapters.
- **D-2 (a) adds one central read per C2 call.** C2 is not yet called in production; revisit if it becomes hot.
- **Production stays blocked by PBDIGIT-349 (Identity bindings) and the migration-approval control.** Task 10 does not change that.

## 8. After the decision (2026-11-14): the D-3 correction and the consistency guarantee

### 8.1 Correction to D-3's example

**"A provisioning process's context" (§3.3 row 6, §5 D-3) is WRONG** in the existing design:
- the provisioning preparer (task 5b) uses its organisation context locally and **never binds it** as the operation's context;
- so M-1 returns `null` there, and C2 answers `CROSS_ORGANISATION`.

**The case that the existing Platform design represents, with no invented context:**
1. a request (E-1) or job attempt (E-2) is routed to A while A is operational;
2. during that operation the registry marks A not routable (e.g. `migration-failed`);
3. the next C2 call re-checks the registry (D-2) and answers **`SOURCE_NOT_LEDGER`**.

That is what T-7 tests.

### 8.2 The consistency guarantee (registry check versus connection)

1. **Isolation never depends on timing.**
   - An organisation's locator is immutable once assigned (`LocatorAlreadyAssigned`, task 1);
   - its database is bound to its one `organisation_identity` row (F-13, task 4);
   - `connectionFor` returns only the current routed organisation's own connection.
   - **So a stale eligibility answer can never reach another organisation's database.**
2. **Eligibility is a check before use, at two points:**
   - when the operation is routed (E-1 per request, E-2 per job attempt);
   - at **each C2 call** (M-2, D-2), immediately before the boundary is closed and the ledger read.

   **A registry change committed after that check takes effect at the next check** (the next C2 call, request or attempt), **not inside the C2 call already in flight.**
   - Closing that window entirely would need one transaction across the central and the organisation database. The accepted design rules that out (C-3: no cross-database atomicity), so no lock or new mechanism is introduced.
   - `connectionFor` checks identity (the current routed organisation), not the registry: one registry read per C2 call, as D-2 decided.
3. **A registry or database that cannot be reached raises an error** (503 for a request, a retry for a job). It is never answered as "ledger" or "not a ledger".

**Test (T-7):** A is flipped to `migration-failed` after M-2's check and before M-3 obtains the connection.
- **The in-flight call** completes reading **A's own database only**.
- **The next call** answers `SOURCE_NOT_LEDGER`.
