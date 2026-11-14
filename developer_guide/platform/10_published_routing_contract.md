# 10: Platform's published routing contract (open host), PBDIGIT-345 task 10

**Decision:** D-233 (Platform as an open host; registry re-check; C2's order kept; two T-12 suites).
**Design:** routing tactical design §3.5, §3.7 L-3.
**Proposal and guarantee:** `docs/publicdigit/architecture/reviews/2026-11-14-platform-membership-routing-contract-proposal.md` §8.
**Evidence:** `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task10/`.

## Business purpose

Every organisation-owned context needs the same three answers about the operation it is serving:
- **which organisation** is it;
- **is it properly registered and routable right now;**
- **where is that organisation's own database.**

Platform answers them **once, for everyone**. Membership first; Governance, Communication, Deliberation and Finance later. Platform depends on no business context.

## The contract (`published-language.json`)

| Type | Level | Use |
|---|---|---|
| `Platform\Application\Published\RoutedOrganisation` | Application, Laravel-free | `current(): ?TenantId` (the routed organisation, or null); `isRegistryRouted(TenantId): bool` (the current organisation **and** routable now; registry re-asked on every call; an unreachable registry **throws**) |
| `Platform\Infrastructure\Published\RoutedOrganisationConnection` | **Infrastructure** (an allowlisted exception, D-233) | `connectionFor(TenantId): Connection`: the **current** organisation's own connection only |
| `Platform\Infrastructure\Published\NotTheRoutedOrganisation` | Infrastructure | thrown for any other organisation, or outside a routed operation |

**One adapter implements both:** `Platform\Infrastructure\ContainerRoutedOrganisation`.
- It reads the operation's `OrganisationContext`, which is bound only by the E-1 middleware (per request) and the E-2 job middleware (per attempt).
- It asks `ResolutionService` for the registry.
- It is wired in `PlatformServiceProvider`.

**Rules (architecture-tested):**
- other contexts use **only** these three types;
- the two Infrastructure types are used **only** from those contexts' **Infrastructure**;
- **never `OrganisationContext`, the factory or the registry directly** (`MembershipPlatformBoundaryTest` for Membership; the ratchet's A-3 for everyone).

## Consistency guarantee (proposal §8.2)

1. **Isolation never depends on timing:**
   - the locator is immutable;
   - the database is bound to its `organisation_identity` row;
   - `connectionFor` gives the current organisation's own connection only.
2. **Eligibility is checked when the operation is routed, and again by `isRegistryRouted` at each use.**
   - A registry change committed afterwards takes effect at the **next** check.
   - **No cross-database atomicity** (C-3), so no lock is introduced.
3. **A registry or database that cannot be reached raises an error:** never "yes", never "no".

## Using it from another context's Infrastructure

```php
final class SomethingAdapter
{
    public function __construct(private RoutedOrganisation $routed, private RoutedOrganisationConnection $connections) {}

    public function read(TenantId $org): array
    {
        if (!$this->routed->isRegistryRouted($org)) { /* refuse in your context's terms */ }
        $db = $this->connections->connectionFor($org);   // the organisation's own database
        // …
    }
}
```

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Platform/Infrastructure/ContainerRoutedOrganisationTest.php tests/Architecture/MembershipPlatformBoundaryTest.php
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres --filter MembershipThroughRoutingTest
```

**Traceability:** PBDIGIT-345 task 10 · D-233 · §3.5, §3.7 · T-7, T-12, T-14 part 2.
