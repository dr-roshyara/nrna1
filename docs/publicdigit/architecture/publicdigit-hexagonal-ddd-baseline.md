# PublicDigit Hexagonal DDD baseline (architecture supplement)

**Status:** PROPOSED, revision 2, for Principal review. **Date:** 2026-11-13. **Author:** Session A (authority: generated until accepted).
**Revision 2:** corrections from the Principal DDD review of revision 1 (`f62d9faa7`), points 2.1 to 2.7. Revision 1 overstated four things, corrected here:
- that `Shared` is a shared kernel;
- that the outbox is planned for Reference Geography;
- that there were 8 identifier VOs (there are 9);
- that A-4 should ban all Ports.

**Measured at:** branch `election-flow-updated`. The measurement is the test `tests/Architecture/ArchitectureRatchetTest.php`, not a one-off grep, so it can be re-run.

**This is a supplement, not a second standard (ES-005.4).** It builds on, and never restates:
- the layer rules **"Backend Architecture: Laravel with Discipline"** in `.claude/CLAUDE.md`, Rules 1 to 10;
- the ratified strategic baseline `publicdigit-strategic-ddd-baseline-rev3.md` (contexts, ownership, contracts K-1 to K-7);
- D-222 (one connection per organisation) and D-225 (Identity references).

**It adds only:**
- data ownership across central and organisation-owned contexts;
- cross-context rules;
- the delivery rule;
- **the enforcement map;**
- **the measured inventory;**
- **the ratchet.**

## 1. In business terms

Every PublicDigit area (Geography, Membership, Forum, Newsletter, …) keeps its business rules in one place that doesn't depend on Laravel, the database or the screens. Changes to an official record go through one controlled path. One area never reaches into another area's internals: it asks through an agreed interface.

**The point for a solo developer:** **tests, not memory, keep these boundaries.** Today, a new shortcut anywhere in `app/` fails the build, including in old files that already have known problems. The known problems are listed one by one, each with a reason and a fix. The list can only shrink.

**What this does not mean:** that PublicDigit conforms. It means the non-conformance is now **measured and frozen**: 198 known violations (§7), none of them in Reference Geography or the ratified Membership ledger.

## 2. Layers and dependency direction

```
          Delivery: controllers, Inertia endpoints, routes, console commands, jobs, listeners
          │  calls use cases; may read through QUERY ports (CLAUDE.md Rule 5)
          ▼
          Application: use cases · ports · acting person · "this must be atomic"
          │ uses                                ▲ implements the ports
          ▼                                     │
          Domain: aggregates, value objects,    Infrastructure: PostgreSQL adapters, transactions,
          domain services, domain events;       Identity adapter, outbox, mail and queue adapters
          pure PHP                              (depends on Application ports and Domain types;
                                                 never the other way round)

   Across contexts:  consumer Application ──port──▶ adapter ──▶ provider's published contract
                     never: consumer ──▶ provider's Infrastructure, tables, or internal Domain types
```

## 3. Data ownership: two kinds of context, one set of principles

| | Central contexts (Identity, Platform, Reference Geography) | Organisation-owned contexts (Membership, Governance, Deliberation, Communication, Finance, Election, …) |
|---|---|---|
| Database | the central (default) connection | **the organisation's own database**, through the per-organisation connection (D-222). Never a shared, re-pointed `tenant` name; never a shared database filtered by `organisation_id` |
| Reference code | Reference Geography: `refgeo_*` tables | Membership's ratified ledger: `org_membership_*` tables |
| People | Identity owns persons and accounts; other contexts get **opaque per-context references** (D-225) | the organisation's own person records and affiliations (D-101) |
| Shared facts | Reference Geography is read **through its read models (G-5, not yet built)**; **never copied into organisation databases** | consumed through contracts: belonging (K-4), authority (K-5), participation rule (K-7) |

**Measured breach of this rule:**
- **legacy Geography still copies geography into the shared `tenant` connection** (`GeographyMirrorService`, `GeographyLookupService`). That breaches both D-222 and "never copy Reference Geography";
- Membership has 2 further `tenant` uses.

All four are enforced by A-5 (§7).

## 4. What may cross a context boundary (correction 2.3)

| Kind | May a consumer depend on it? | Example in the code |
|---|---|---|
| **Identifier** (a value type naming the provider's thing) | **yes, if listed as published language** | `Membership\Domain\Member\MemberId` (proposed) |
| **Published contract DTO** (a read-only fact shape the provider promises) | yes, if listed | none named yet; to be defined with R-2 |
| **Query contract** (a provider-owned read interface) | yes, through an adapter in the consumer's Infrastructure | Reference Geography's `PublishedAtlasSource` (internal today; published at G-5) |
| **Consumer-owned port** (the consumer defines what it needs; the provider's adapter implements it) | **yes: the preferred direction** | `Membership\Domain\Services\GeographyLookupInterface`, implemented by Geography; Reference Geography's `ActingPerson`, implemented over Identity |
| **Provider's Application port used directly** | only when that port is listed as published | `Platform\Application\Services\ContextInstaller` (unclassified) |
| **Infrastructure adapter, Eloquent model, table** | **never** | 5 breaches (`CommitteeModel` ×3, `MemberContextModel`, …) |
| **Internal domain entity, aggregate, state enum** | **never** | committee organ types, see §5 |

**The published-language list** will be an explicit allow-list in the ratchet once this revision is accepted. Until then, every cross-context import is recorded individually in the baseline, legitimate or not, so none can be added silently. **`Shared` is provisionally exempt** from A-3 (see §6).

## 5. Governance → Membership: the 50 imports classified (correction 2.3, priority 2)

Revision 1 said "47 Domain imports". The ratchet counts **50 imports (47 Domain, 3 Infrastructure) of 10 distinct classes:**

| Classification | Imports | Classes |
|---|---|---|
| 1. Legitimate published language | 3 | `Member\MemberId` |
| 2. Internal domain dependency, **owned by Governance but stored in Membership** | 44 | `Committee\ValueObjects\CommitteeId` (20), `ValueObjects\CommitteeId` (2), `Committee\ValueObjects\MemberId` (7), `CommitteeFacts` (5), `TermPeriod` (1), `ConstitutionalLegitimacy`, `StructuralOperationalState`, `TemporalGovernanceState` (3 each) |
| 3. Persistence leakage | 3 | `Infrastructure\Models\CommitteeModel` |
| 4. Unclear ownership | 0 | none |

**What this means.** Governance isn't reaching into Membership's business. **Governance's own concept, the committee as an organ, was built inside Membership's folder** (`Membership/Domain/Committee`, 188 files, May 2026). That predates the ratified strategic baseline rev3, which assigns organs, positions and terms of office to **Governance**. The coupling is a misplaced aggregate, not a missing interface.

**Smallest correct response (R-2). No move now:**
1. **New organ and committee code goes into Governance.** The ratchet blocks any *new* Governance import of these types.
2. **Governance consumes membership facts** (in force, suspended) **only through a Membership query contract**, for example `MembershipStanding`, defined with PBDIGIT-345 or the lifecycle work. It never uses Membership's internal types.
3. **The 3 `CommitteeModel` imports** are replaced by a Governance query port when touched.
4. **Relocating the 188 files** is its own ticket, under a **Membership change request**. The code predates the ratified 2026-10-08 architecture, but it lives in Membership's folder, so moving it is a change there.

**Two `MemberId` classes exist** (`Member\MemberId` and `Committee\ValueObjects\MemberId`). That's a duplicate identity concept. Record it and resolve it with step 4.

**Why this matters for the Forum and Newsletter:**
- Deliberation (forum access) and Communication (audiences, D-213) will need *belonging*, *membership standing* and *authority*.
- **They must consume Governance and Membership contracts, never committee internals.** Steps 1 and 2 give them something stable to consume.

## 6. `Shared`: classified, not ratified (correction 2.2)

| Contents | Classification | Users |
|---|---|---|
| `Infrastructure/Outbox` (9), `Infrastructure/Inbox` (6), `Infrastructure/Messaging` (3) | **generic technical infrastructure** (outbox, inbox, dispatch) | Membership, Adjudication, Contestation, Election |
| `Application/Inbox` (6), `Application/Messaging/EventProvenance` | **generic technical application contracts** (handler interface, outcomes, provenance) | Adjudication, Contestation, Election |
| `Domain/DomainEvent`, `Domain/Events/IntegrationEvent`, `Domain/TenantAggregateRoot` | **generic technical base types** | Membership |
| `Domain/ValueObjects/TenantId`, `ActorId` | **generic value types** (identifiers) | Membership, Governance, Platform |
| genuine shared business concepts | **none found** | |
| context-specific concepts misplaced in Shared | **none found** | |

**Options:**
- **(a) Shared technical library, recommended.** The rule is "no business concepts, ever". The consequence: contexts may depend on it freely, and a business concept appearing in `Shared` is a defect. Cheap to enforce (a follow-up A-6).
- **(b) A shared domain kernel.** This would mean joint ownership of business concepts by several contexts. **Not warranted: there are none,** and it would invite shared models that blur ownership.
- **(c) Dissolve into the contexts.** This duplicates the outbox and inbox four times, for no gain.

**Recommendation: (a).** It's an architecture ruling within the ratified ownership, so no product decision is needed. The Principal reviewer may confirm it.

## 7. Enforcement map, and the inventory (corrections 2.6 and 2.7)

**The ratchet** is `tests/Architecture/ArchitectureRatchetTest.php`, with its scanner in `tests/Architecture/Support/ArchitectureRatchet.php`.

**What it scans:**
- every `.php` file in `app/` and `routes/`, with comments stripped;
- each violation is one entry: `rule | file | exact dependency`, with an occurrence count.

**The baseline** is `tests/Architecture/baselines/architecture-ratchet.json`. Each entry carries:
- the count;
- a **classification;**
- a **reason;**
- a **remediation;**
- whether a decision or change request is needed.

**The generated inventory, one row per violation:** `publicdigit-architecture-deviation-inventory.md`.

| Rule | What it forbids | Today (violations / files) | Status |
|---|---|---|---|
| A-1 Domain purity | Laravel, Eloquent, `App\Models`, facade calls in any `Domain` | **21 / 15** | **enforced** (ratchet) |
| A-2 Application layer | facades (imported, or called as a global alias), Eloquent, `App\Models` in any `Application` | **67 / 27** | **enforced** |
| A-3 Cross-context | any import of another context's Domain, Application or Infrastructure (`Shared` exempt) | **88 / 58** | **enforced** (all recorded; allow-list pending) |
| A-4 Delivery boundary | in `app/Http`, `app/Console`, `app/Jobs`, `app/Listeners`, `routes`, `Contexts/*/Http`: another context's Infrastructure, **write-side ports** (`…Repository`, `…Store`, `…Ledger`), **context tables** (`refgeo_*`, `org_membership_*`), **raw SQL writes** (`DB::insert/update/delete/statement/unprepared`) | **18 / 14** | **enforced** |
| A-5 Shared `tenant` connection | `connection('tenant')`, `$connection = 'tenant'` anywhere | **4 / 4** | **enforced** |
| No bypass of one write path | Reference Geography | — | **enforced** (`ReferenceGeographyNoBypassTest`) |
| Domain purity, ratified Membership ledger | | 0 | **enforced** (`MembershipSemanticKernelPurityTest`, slice 1a/1b) |
| **Trusted acting person** | | — | **enforced in Reference Geography** (reflection test). **A gap in Membership:** see §9 |
| Eloquent writes in controllers (`$model->save()`) | | — | **documented only.** Not reliably detectable statically without false alarms |
| Published-language allow-list | | — | **proposed** (§4) |

**Classified totals** (from the baseline file):
- genuine: 55;
- genuine, legacy Geography or Membership-to-Geography: 46;
- minor: 13;
- misplaced ownership (committee): 49;
- persistence leakage: 5;
- placement: 6;
- acceptable exception: 6;
- published language, approved or proposed: 7;
- consumer-owned port: 2;
- unclear: 3;
- artefacts: 6 (5 dangling imports of the non-existent `TenantAuth` and `Subscription` contexts, 1 unused import).

**Resolved counts:**
- **Membership Domain:** "11 files" in revision 1 = 9 identifier VOs using `Str` + the Eloquent `Member` model + `Member.legacy.php.backup`. The backup isn't executable: the ratchet doesn't scan it, and the inventory records it separately.
- **"14 Membership Application files" became 15:** adds `CreateMemberRequest`, which calls `Log::` as a global alias without an import, which the first grep missed.
- **"9 delivery files" became 14,** now that console commands, `routes/console.php` and context controllers are included. Six are acceptable exceptions (the outbox and inbox drivers, backfills, a projection rebuild).

**A-4 corrects my own first proposal:** read-only **query ports in controllers are allowed.** They are the hexagonal way to read, and they match CLAUDE.md Rule 5. **Write-side ports are not.**

## 8. Transactions (correction 2.1)

**The use case decides what must be atomic. Infrastructure decides how.** The Application expresses the requirement through a port; it never calls `DB::transaction`. Business orchestration doesn't move into Infrastructure.

| Pattern | The Application says | Infrastructure does | Example |
|---|---|---|---|
| **Unit of work under a lock** | "run this orchestration atomically": `$ledger->withLock(fn (LockedLedger $l) => …)`; `$store->writeUnderLock(fn (StewardRoster $r) => …)`. The closure holds the use case's own logic | opens the transaction, takes `FOR UPDATE`, commits or rolls back | Reference Geography `PublishRevision`, `StewardRosterService` |
| **Optimistic append** | load → authorise → decide → `append(expected version)`; on conflict, discard and retry (at most 3) | makes one `append` atomic and version-checked | Membership ledger `DecideAndAppend` |

**The legacy code doesn't follow this:**
- 10 Membership use cases call `DB::transaction`;
- one controller owns the transaction itself (the comment in `ApproveMembershipApplication`).

Remediation R-3: when a use case is touched, take whichever pattern fits.

## 9. The acting person (correction 2.4)

- **The rule:** a caller never chooses who the acting person is. It is resolved **from the authenticated session, by a trusted resolver**, and checked by the owning context's authority rules.
- **How each context gets it** (no direct dependency on Identity internals):
  - **Through its own consumer-owned port** (Reference Geography: `Application\Ports\ActingPerson`). Only an Infrastructure adapter (`IdentityActingPerson`) touches Identity's published application API. That's 2 approved imports (§7, A-3), guarded by `IdentityG0ArchitectureTest`.
  - **Or by delivery filling a command field from the same trusted resolver,** never from request input.
- **The ratified Membership ledger takes the second form:**
  - `AdmitMember::$actor` is a command field, checked by `AuthorityCheckPort`;
  - **no delivery path calls it yet.**
  - **Gap:** when PBDIGIT-345 wires its controllers, they must fill `actor` from the trusted resolver, **with a delivery test proving that a request value is ignored.**
  - The ratified design isn't changed.

## 10. Messaging and the outbox (correction 2.5)

| | Status |
|---|---|
| Outbox (`outbox_events`, 2026-05), inbox (`inbox_events`, 2026-07), provenance, `ProcessOutboxEvents` and `RedriveInboxEvents` commands | **implemented** (Shared technical infrastructure) |
| Writers | **Membership** (5 files) |
| Consumers (inbox handlers, hydrators) | Adjudication, Contestation, Election |
| Reference Geography | **does not use the outbox.** The event `RevisionPublished` is **planned for G-5, not implemented.** |
| A messaging framework (a broker, …) | **none, and none proposed** (X-4) |

**The convention:**
- cross-context calls are synchronous **query contracts;**
- facts other contexts react to are published through **the existing outbox,** in the writing transaction;
- consumers are **idempotent inbox handlers.**

## 11. Overlapping folders (point 6)

- **`Election` (100 files) and `Elections` (82 files): two modules of one bounded context** (rev3 has a single Election context, deferred by D-146). They don't import each other.
  - `Election` is the operating core: lifecycle, determinations, evidence windows (EM-ARCH-001, Aug 2026).
  - `Elections` is the electorate and ballot side: voter assignment, admission, ballots (Oct 2026).
  - **A naming issue, not an ownership overlap. Recommendation: don't rename.** Reconcile when Election's tactical design resumes after D-146.
- **`Committee` (4 files, a read model) and `Membership/Domain/Committee` (188 files): an ownership finding.** Both hold a Governance concept (organs, rev3). See §5, R-2.

## 12. Remediation priorities (none started; each in its own authorised slice)

1. **R-1:** Membership's `tenant` connection (2 files) → per-organisation connection **in PBDIGIT-345**, with no workaround. Legacy Geography's 2 files go with R-6.
2. **R-2:** Governance–Membership.
   - **Next for Membership:** define the **`MembershipStanding` query contract;** new organ code goes to Governance.
   - **Separately:** the committee relocation (a Membership change request). **This precedes the Forum and Newsletter tactical designs.**
3. **R-3:** a transaction port in the 10 legacy use cases, when touched.
4. **R-4:** the Eloquent `Member` model out of Domain (Membership change request); delete the `.backup` file.
5. **R-5:** 9 identifier VOs from `Str` to pure-PHP UUIDs, when touched.
6. **R-6:** retire legacy Geography (Domain models, facades, the tenant mirror) through G-5 and the D-147 integration decision.
7. **R-7:** the delivery deviations, when each file is touched. The quick one: two controllers type-hint the concrete `EloquentVotingEntitlementQuery` instead of its existing port.

## 13. Decisions

- **For the Principal (product, constitution): none.**
- **For the Principal reviewer (architecture):**
  - confirm `Shared` as a technical library (option a);
  - confirm `Member\MemberId` as the first published-language entry;
  - confirm the A-4 refinement (query ports allowed).
- **Needing a Membership change request later:** R-2's relocation and R-4.

**Traceability:**
- CLAUDE.md Rules 1 to 10;
- strategic baseline rev3;
- D-101, D-146, D-147, D-213, D-222, D-225;
- Reference Geography G-4 (accepted with conditions, 2026-11-13);
- Membership slice 1 (accepted);
- evidence: `docs/publicdigit/architecture/evidence/2026-11-13-architecture-ratchet/`.
