# PublicDigit Hexagonal DDD baseline (architecture standard)

**Status:** PROPOSED, for Principal review · **Date:** 2026-11-13 · **Author:** Session A (authority: generated until accepted)
**Measured at:** branch `election-flow-updated`, after `f2ea45974` (static scan of `app/Contexts`, `app/Http`, `routes`, `tests/Architecture`).

**Builds on, never restates (ES-005.4):**
- the layer rules **"Backend Architecture: Laravel with Discipline"** in `.claude/CLAUDE.md` (Rules 1 to 10: Domain pure PHP; Application without facades or Eloquent; Infrastructure free to use Laravel; DTOs; repositories for aggregates only);
- the ratified strategic baseline `publicdigit-strategic-ddd-baseline-rev3.md` (contexts, ownership, contracts K-1 to K-7);
- D-222 (one connection per organisation).

**What this document adds:**
- the data-ownership rules across the two kinds of context;
- the cross-context rules;
- **the enforcement map** (what is enforced versus only written);
- **the measured deviations;**
- **a ratchet**, so that new code conforms without a big refactor.

## 1. Purpose, in business terms

**Every PublicDigit context** (Geography, Membership, Forum, Newsletter, …) **keeps its business rules in one place that doesn't depend on Laravel, the database or the screens.** Any change to the official record goes through one controlled use case, and no context reaches into another context's data.

For a solo developer this means two things:
- a rule is found in one place, and is tested without a database;
- tests, not memory, stop shortcuts from creeping back in.

## 2. Layers and dependency direction

```
   Delivery  (HTTP / Inertia controllers, routes, console commands, jobs, listeners)
      │ calls only
      ▼
   Application  (use cases; ports; acting person; transaction boundary)
      │ uses                     ▲ implemented by
      ▼                          │
   Domain  (aggregates, value objects,        Infrastructure  (PostgreSQL adapters, Laravel bindings,
            domain services, events;           Identity adapter, mail and queue adapters)
            pure PHP)                                │ depends on Application ports and Domain types
                                                     └── never the reverse
```

| Layer | Owns | Must not | Rule source |
|---|---|---|---|
| Domain | invariants, business meaning | depend on Laravel, Eloquent, HTTP, the DB, queues, clock or randomness | CLAUDE.md Rule 1 |
| Application | use cases; **actor from the trusted Identity port**; the transaction boundary **through a port**; calls to other contexts **through their published contracts** | use facades or Eloquent; duplicate domain rules | CLAUDE.md Rules 2, 4, 5 |
| Infrastructure | adapters for the ports; Laravel and PostgreSQL | make business decisions | CLAUDE.md Rule 1 |
| Delivery | translating input and output | touch Infrastructure, Ports or tables; decide authority | **new: rule D-1 below** |

## 3. Two kinds of context, one set of principles

| | Central contexts (Identity, Platform, Reference Geography) | Organisation-owned contexts (Membership, Deliberation, Communication, Governance, Finance) |
|---|---|---|
| Database | the central (default) connection | **the organisation's own database**, through D-222's per-organisation connection; never a re-pointed shared name, never a shared database filtered by `organisation_id` |
| Example in code | Reference Geography: `refgeo_*`, `PostgresPublicationLedger` | Membership's ratified ledger: `org_membership_facts`, `PostgresMemberRepository` |
| People | Identity owns persons and accounts; contexts receive **opaque per-context references** (D-225) | the organisation's own person profile and affiliations (D-101) |
| Shared facts | Reference Geography is read **through its published read models (G-5)**, never copied into an organisation database | consumed through contracts: belonging (K-4), authority (K-5), participation rule (K-7) |

## 4. Cross-context rules (new; currently written down only, not enforced)

- **X-1:** a context imports another context's **Infrastructure: never.** Its **Domain types: only those named as the other context's published language** (identifiers, contract DTOs). Anything else goes through an Application-level contract (port plus adapter), as Reference Geography does with Identity (`IdentityActingPerson`, the only Identity import, guarded by `IdentityG0ArchitectureTest`).
- **X-2:** a context reads or writes **only its own tables.**
- **X-3:** `Shared` is a **shared kernel** (value types and support only). **Is it ratified as such? Confirm (open, §8).**
- **X-4: no messaging framework yet.** Cross-context calls are synchronous application contracts. Asynchronous ones use the existing outbox pattern (`RevisionPublished` comes at G-5).

## 5. Transactions, persistence, actor

- **One aggregate per transaction by default.**
  - A transaction that spans aggregates must be justified by an invariant; Geography's publication lock is the worked example.
  - The transaction boundary belongs to the **Infrastructure adapter behind a port** (as in `PostgresStewardRosterStore::writeUnderLock`, `PostgresPublicationLedger::withLock`), **not to `DB::transaction` in the Application layer.**
- **Aggregates are rebuilt only through the domain's own rebuild path.** Never by setting private state from rows (Reference Geography G-4b).
- **Insert-only history and immutability are guarded in the database as well,** where the record is official: triggers (`developer_guide/database/02`).
- **The acting person always comes from the Identity port. No use case accepts a person as its actor** (proven in Reference Geography by reflection).

## 6. Testing standard

| Level | What | Database | Example |
|---|---|---|---|
| 1 Domain | invariants | none | `tests/Unit/Contexts/ReferenceGeography/Domain` |
| 2 Application | use cases with fakes for ports | none | `RevisionServiceTest`, `RosterServicesTest` |
| 3 Adapters | repository and ledger round trips, refusals | isolated PostgreSQL (`scripts/testing/membership-pg.sh`) | `PostgresRevisionRepositoryTest` |
| 4 Concurrency | deterministic lock-held races | isolated PostgreSQL | `PostgresConcurrencyMatrixTest` |
| 5 Architecture | dependency direction, no bypass | none | `ReferenceGeographyNoBypassTest`, `MembershipSemanticKernelPurityTest` |
| 6 Delivery | only where a controller has logic | test DB | — |

**Safeguards are proven by mutation** (a loaded copy, never a source edit), each kill **classified by reason** (Reference Geography G-4g).

## 7. Enforcement map, and what was measured

| Rule | Reference Geography | Membership (ratified slice-1 ledger) | Membership (older areas) | Others |
|---|---|---|---|---|
| Domain pure | **enforced** (`ReferenceGeographyDomainPurityTest`): 0 deviations | **enforced** (`MembershipSemanticKernelPurityTest`, slice 1a/1b boundary tests): 0 deviations | **11 files:** 8 identifier VOs use `Illuminate\Support\Str`; **an Eloquent model in `Domain/Models/Member.php`**; a stray `Domain/Models/Member.legacy.php.backup` | legacy Geography 4; Elections 1 |
| Application without facades or Eloquent | 0 (not test-enforced) | 0 | **14 files:** `DB::transaction` in 10 use cases (Application/UseCases, Committee, Fee); `App\Models\Tenant`, `Organisation`, `UserOrganisationRole` in 3; **`DB::connection('tenant')`** in `TenantUserProvisioningService` | Election 2, Elections 2, Geography 6, Governance 2 |
| One write path, no bypass | **enforced** (`ReferenceGeographyNoBypassTest`) | ledger isolation tests | not enforced | not enforced |
| Cross-context (X-1) | only the Identity adapter (enforced) | — | **Membership → Committee Infrastructure (1); → legacy Geography Domain (14)** | **Governance → Membership Domain 47, Infrastructure 3;** Committee → Membership Domain 4, Infrastructure 1 |
| Delivery never imports Infrastructure (D-1) | none exist | — | — | **9 files in `app/Http` or `routes`** import a context's Infrastructure |
| Per-organisation connection (D-222) | n/a (central) | ratified code uses its connection | **`DB::connection('tenant')`**: a shared, re-pointed name, contrary to D-222 | — |

**Reading the table:** the two reference implementations conform. **The deviations are in older code that predates the current rules.** They are listed so they are known, not so they are rewritten now.

## 8. The minimal architecture-test set (proposed; to build test-first after this review)

**A ratchet, not a refactor:** each test fails on any deviation **not already listed** in its baseline file. The baseline files hold today's measured deviations and may only shrink. **New code must conform; old code is fixed when touched.**

| Test | Rule | Today's baseline |
|---|---|---|
| A-1 `ContextDomainPurityTest` | every `app/Contexts/*/Domain` is free of Laravel, Eloquent, facades, `App\Models`, DB | 16 files (Membership 11, Geography 4, Elections 1) |
| A-2 `ApplicationLayerTest` | no facades or `App\Models` in any `Application` | 26 files |
| A-3 `CrossContextImportTest` | no import of another context's Infrastructure; Domain only from a published-language list | the current couplings (§7) |
| A-4 `DeliveryBoundaryTest` | `app/Http`, `app/Console`, `app/Jobs`, `app/Listeners`, `routes` never import `*\Infrastructure\` or `*\Application\Ports\` | 9 files |
| A-5 `OrganisationConnectionTest` | organisation-owned contexts never name the shared `tenant` connection | 1 file |

**Not proposed:** a generic architecture framework, or fragile naming rules.

## 9. Remediation priorities (each a separate, authorised ticket; none started)

1. **`DB::connection('tenant')` in Membership** contradicts D-222. **Fold it into PBDIGIT-345,** the per-organisation routing that is next for Membership anyway.
2. **Governance → Membership Domain (47 imports):** find which are published language (identifiers) and which are reach-ins. **It decides how Governance reads Membership facts**, so it needs investigating before the Forum and Newsletter designs consume Governance contracts.
3. **`DB::transaction` in 10 Membership use cases:** a transaction port, as the ratified ledger code already does through its repository. Fix them when the use cases are touched.
4. **The Eloquent `Member` model in Membership's Domain** (and the `.backup` file): move it to Infrastructure. **It needs a Membership change request** (frozen architecture), because it may be read elsewhere.
5. **8 identifier VOs using `Str`:** use pure-PHP UUID generation. Trivial, when touched.
6. **The legacy Geography context:** will be replaced by Reference Geography read models (G-5; Organisation integration deferred, D-147). No change now.
7. **9 delivery files importing Infrastructure:** fix when each controller is touched.

## 10. Context-specific, justified exceptions

- **Reference Geography:** publication and roster share one lock (v3.11 §6, §8). A cross-aggregate transaction, justified by the governance invariant.
- **Identity:** the only context others may call **from Infrastructure** for the acting person (D-225 port). The opposite direction is never allowed.
- **Membership ledger:** its own clock and closed-through rules (ratified). **PO-8, timestamp adjustment versus Geography's refusal, is to be resolved in Membership's tactical work.**

## 11. Open points

- **X-3:** is `Shared` ratified as a shared kernel, and what may it contain?
- **Which Membership Domain types are published language** for Governance and Committee (A-3's allow-list)?
- **Elections versus Election, and Committee versus Membership's committee areas:** two pairs of overlapping folders. Their ownership is in the strategic baseline, but the code split isn't reconciled. Record it, don't fix it now.

## 12. Recommendation

1. **Accept this baseline** (it adds no new business rule).
2. **Build A-1 to A-5 test-first, as a ratchet,** in one small slice: the tests fail on a newly introduced deviation, and the baselines hold today's state.
3. **Then G-5.**
4. **Fold remediation 1 into PBDIGIT-345.** Investigate remediation 2 before the Forum and Newsletter tactical designs.

**Traceability:** CLAUDE.md layer rules · strategic baseline rev3 · D-101, D-147, D-222, D-225 · Reference Geography G-4 (accepted with conditions) · Membership slice 1 (accepted).
