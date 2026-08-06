# Membership Capability Review

**Capability:** Membership Management (`PBDIGIT-EPIC-02`) · **Method:** the 9-phase capability review — **application #3 of 3** (Election #1, Organisation #2)
**Date:** 2026-08-06 · **Baseline:** `election-review` @ `41880d49` · **Mode:** Reviewer — **zero files modified**
**Placement:** derived — `--scope=product-specific --domain=publicdigit` → `docs/publicdigit`

> **Method note:** this is the third and final application before the frozen method becomes eligible as a promotion candidate. The five parked refinement candidates were **deliberately not used** — this review tests the method as it stands.

---

# Phase 0 — Customer journey (goals and expectations · no rules, no events, no code)

```
"Our association has members. The platform must know who they are and what they may do."
        ↓
I define our committees            → I expect to describe OUR structure, with terms
        ↓
I load our member list             → I expect a spreadsheet to become members
        ↓
People apply to join               → I expect to review each one and decide
        ↓
I approve someone                  → I expect them to become a real member immediately
        ↓
I put people in committees          → I expect roles to mean something afterwards
        ↓
Someone stops paying / leaves       → I expect the platform to reflect that
        ↓
We hold an election                 → I expect only eligible members to vote,
                                       and to be able to EXPLAIN each decision
```

**The expectation carrying the most weight:** *"only eligible members vote, and I can explain each decision."* Everything else in this capability exists to make that answerable.

---

# Phase 1 — Business lifecycle (business events)

```
LIFECYCLE A — Membership
  Application Submitted → Approved/Rejected → Member Registered → Approved
        → Activated → (Suspended ⇄ Restored) → Terminated / Archived
                                            ↘ Reapplied

LIFECYCLE B — Committee
  Committee Created → Established → Parent Attached → Term Updated
        → Structure Defined → Structure Activated → Lifecycle Changed

LIFECYCLE C — Fee
  Fee Paid → Fee Overdue → Fee Waived

LIFECYCLE D — Eligibility (derived, not stored)
  Membership state + fee state + committee roles → Eligibility Snapshot → Evidence
```

**Four lifecycles — the richest of the three capabilities reviewed.**

---

# Phase 2 — Business events · FACTS

**34 event classes exist** under `app/Contexts/Membership/**/Events/`. Unlike the other two capabilities, this context uses a genuine **record-and-release** pattern.

| Fact | Evidence |
|---|---|
| Aggregates **record** events internally | `Member::register()` → `$member->recordThat(new MemberRegistered(...))` (`Domain/Member/Member.php:64`) · `Application.php:92` · `Fee.php:92` · `CommitteeConstitution.php:49` |
| A buffer exists for recorded events | `Domain/Committee/DomainEventsBuffer.php:12` — `record(object $event)` |
| Application use-cases **release** them to an event bus | `ApproveApplication.php:31` · `RejectApplication.php:31` · `AssignMemberToCommittee.php:42` (`$this->eventBus->dispatch(...)`) · `RemoveMemberFromCommittee.php:44` · `CreateCommitteeHandler.php:34` |
| Some context events **are listened to** — registered at runtime in `boot()`, not in `$listen` | `EventServiceProvider::boot()`: `Event::listen(FeePaid::class, MemberFeeStateListener)` · `Event::listen(MemberAssignedToCommittee::class, CommitteeMemberProjectionListener)` · `MemberRemovedFromCommittee` likewise |
| Legacy events are listened to via `$listen` | `MembershipApplicationApproved`, `MembershipApplicationRejected`, `MembershipFeePaid`, `MembershipRenewed` → `InvalidateMembershipDashboardCache`, `RecalculateMemberFeeStatus`, `CreateIncomeForMembershipFee` |
| Those legacy events live in a **different namespace** from the context events | `use App\Events\Membership\MembershipApplicationApproved;` · `use App\Events\MembershipFeePaid;` vs `use App\Contexts\Membership\Domain\Fee\Events\FeePaid;` |

## ✅ Pattern 1 — **does NOT recur here**

The recurring pattern from Election and Organisation was *"event dispatched, zero listeners, consequence hard-coded in the dispatching controller."* **Membership does the opposite:** aggregates record, use-cases release, listeners consume, and consequences live in listeners (`MemberFeeStateListener`, `CommitteeMemberProjectionListener`, `RecalculateMemberFeeStatus`).

**This is the strongest architecture of the three capabilities reviewed** — and it *refutes* any temptation to call the pattern universal (see §Cross-capability, which is now the honest reason the pattern must not be promoted).

**Residual finding:** two parallel event vocabularies coexist — legacy `App\Events\Membership\*` (in `$listen`) and context `App\Contexts\Membership\Domain\*` (in `boot()`). Two registration mechanisms, two namespaces, same business meaning.

---

# Phase 3 — Route map

| Business step | Route | Controller |
|---|---|---|
| Apply for membership | `GET`/`POST /organisations/{org}/membership/apply` | `MembershipApplicationController` (`routes/organisations.php:85,86`) · `PublicMembershipApplicationController` |
| Accept an invitation | `GET /invitations/{token}` | `OrganisationMemberInvitationController` (`:63`) |
| Membership types | `GET`/`POST /membership-types` | `Membership/MembershipTypeController` (`:111-113`) |
| Membership dashboard | — | `Membership/MembershipDashboardController` |
| Import members | `GET /import` · `/import/template` | `ParticipantImportController` (`:149-150`) · `Import/OrganisationUserImportController` · `app/Imports/OrganisationUserImport.php` |
| List / create committees | `GET /committees` · `/committees/create` · `POST /committees` | `Committee/CommitteeManagementController` (`routes/committee/committeeRoutes.php:42,48,50`) |
| Committee dashboard | `GET /committees/{committee}/dashboard` | `:44` |
| Edit committee | `GET /committees/{c}/edit` · `PATCH /committees/{c}` | `:52,54` |
| Code/slug uniqueness checks | `GET /api/committees/check-code/{code}` · `check-slug` | `:34,38` |
| **Assign / remove committee member** | `POST /committees/{c}/members` · `DELETE /committees/{c}/members/{…}` | `:58,61` · `Api/MemberCommitteesController` |
| Committee membership application | — | `Membership/CommitteeMembershipApplicationController` |
| Officer roles | `POST /roles/assign-officer` · `remove-officer` | `OrganisationRoleController` (`organisations.php:144,145`) |

---

# Phase 4 — Implementation map · FACTS

**This capability has the layered structure the certified core has:** `Contexts/Membership/{Domain,Application,Infrastructure}` with aggregates, use-cases, ports, repositories, projections and an outbox hydrator (`FeePaidHydrator`).

| Business step | Path |
|---|---|
| Apply | controller → `Application` aggregate (`Domain/Application/Application.php:92` records `ApplicationApproved`) |
| Approve application | `Application/Application/UseCases/ApproveApplication.php:31` (pulls events) · `ApproveMembershipApplication.php:77-79` (application + member + fee together) |
| Register member | `Domain/Member/Member.php:64` records `MemberRegistered` |
| Approve member | `Domain/Models/Member.php:321` records `MemberApproved` |
| Pay fee | `Domain/Fee/Fee.php:92` records `FeePaid` → `FeePaidHydrator:31` (outbox) → `CreateIncomeFromFeePaidProjection` (Finance) |
| Create committee | `Application/Committee/UseCases/CreateCommittee/CreateCommitteeHandler.php:34` · `Domain/Committee/CommitteeConstitution.php:49` |
| Assign to committee | `Application/Committee/AssignMemberToCommittee.php:42` → `CommitteeMemberProjectionListener` |
| Eligibility | `app/Services/VoterEligibilityService.php` · `app/Domain/Voting/Service/EligibilityEvaluator.php` · `app/Domain/Voting/ValueObject/EligibilitySnapshot.php` · `Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php` · middleware `VoteEligibility` · `app/Domain/Election/Security/Simplified/ParticipationEligibilityEvidence.php` |

**Notable:** `Domain/Committee/CommitteeConstitution.php` — this capability has a **constitution**, like the Election lifecycle. Committee read/write separation is a recorded decision (`docs/adr/ADR-0001-committee-read-write-separation.md`).

---

# Phase 5 — DDD analysis · INTERPRETATION

1. **This is the best-modelled non-certified capability.** Aggregates with recorded events, use-cases that release them, a constitution, ports, projections, an ADR. Compare Organisation's 21-attribute data model with no transitions.
2. **But the model is duplicated in at least four places** (§6 D-1…D-4). Two `Member` aggregates, two `MemberRegistered` events, two `MemberStatus` types with **different state sets**, two `TenantId` value objects. In DDD terms the ubiquitous language has forked inside a single bounded context.
3. **Pattern 2 recurs — in a new form.** Election and Organisation had *declared states nothing can reach*. Here the states are reachable, but **which set of states is authoritative is undecidable from the code**: `Domain/ValueObjects/MemberStatus` declares 8 (`draft, pending, approved, rejected, active, suspended, inactive, archived`); `Domain/Member/MemberStatus` declares 4 (`active, inactive, suspended, archived`) and *rejects the other four as invalid*. Both have consumers.
4. **Eligibility has no single authority** — five implementations across three contexts plus a middleware (§4). For the expectation *"I can explain each decision"*, `ParticipationEligibilityEvidence` is the right idea; that it lives in `Domain/Election/Security/Simplified/` rather than Membership suggests ownership was never settled.
5. **Two event-registration mechanisms** (`$listen` vs `boot()`) split by namespace, not by intent (§2).

---

# Phase 6 — Code quality (only where it touches this capability)

| # | Observation | Evidence |
|---|---|---|
| **D-1** | **Two `Member` aggregates.** `Domain/Member/Member.php` (5.6 KB, **27** references from Application/Infrastructure) and `Domain/Models/Member.php` (20 KB, **9** references). Both record `MemberRegistered`; both are live | file sizes + reference counts |
| **D-2** | **Two `MemberRegistered` event classes** in different namespaces | `Domain/Events/MemberRegistered.php` · `Domain/Member/Events/MemberRegistered.php` |
| **D-3** | **Two `MemberStatus` types with different state sets** — 8 constants vs 4, and the 4-value class **throws** on the other four. **2 consumers each** | `Domain/ValueObjects/MemberStatus.php` vs `Domain/Member/MemberStatus.php:9-19` |
| **D-4** | **Two `TenantId` value objects** — `Contexts/Membership/Domain/ValueObjects/TenantId` vs `Contexts/Shared/Domain/ValueObjects/TenantId`. This is not theoretical: it is the cause of a currently failing test (`Member::register(): Argument #1 must be of type Shared\…\TenantId, Membership\…\TenantId given`) | test output, `tests/Unit/Contexts/Membership/Domain/Member/MemberTest.php:75,88` |
| **D-5** | **~21 Membership unit tests fail on `main`** (pre-existing, debt `L-7`): `AssignMemberToCommitteeHandler` constructor arity (6 expected, 5 passed) ×6 · `ApplyForCommitteeMembershipHandler` argument-type mismatch ×9 · `FakeEventBus::dispatchAll()` undefined ×4 · `TenantId` mismatch ×2 | verified by running them on `main` |
| **D-6** | `AD-006` — `FeeTestFactory` TenantId mismatch, open, owner Membership | `docs/implementation/backlog/BACKLOG.md` debt table |
| **D-7** | Two event-registration mechanisms for one context | §2 |

---

# Phase 7 — Runtime verification

**Not performed** — no browser, no database in this environment.

**Additionally unverifiable by test today:** the ~21 failing tests (D-5) mean *"the tests pass"* cannot be claimed for this capability even in principle until they are repaired. **This is the only capability of the three where the automated-test signal is itself broken.**

**What runtime verification must answer:**
1. Which `Member` aggregate does a real approval flow use (D-1)?
2. Which `MemberStatus` set governs a real member (D-3)?
3. Does approving an application actually produce an active member the election can see?
4. Can an eligibility decision be *explained* end-to-end (the Phase-0 expectation)?

---

# Phase 8 — Business Outcome

```
Business Outcome

Today     A customer can define committees with terms, import members, receive and
          decide applications, assign committee roles, and record fees — and the
          machinery announces each step properly through domain events and
          listeners. But the capability describes a MEMBER in four different ways
          at once (two aggregates, two status vocabularies with different states,
          two identity value objects), and ~21 of its own unit tests fail before
          any change is made — so nobody can say which description is authoritative.

Expected  One definition of a member, one set of member states, one identity type,
          and a passing test suite that proves an approved applicant becomes a
          member the election can see.
```

**In one sentence:** the capability is well built and **says the same thing twice** — the risk is not absence, it is ambiguity.

---

# Business Rule Matrix

| # | Business rule | Designed | Implemented | Verified | Automated test | Evidence |
|---|---|---|---|---|---|---|
| **M1** | Committees can be created with type, code, term and parent | ✅ constitution + events | ✅ | ⬜ | ⚠️ **suite broken** (D-5) | `CommitteeConstitution:49` · `committeeRoutes:48,50,52,54` |
| **M2** | Committee code/slug are unique | ✅ | ✅ | ⬜ | *Evidence not found* | `committeeRoutes:34,38` |
| **M3** | A member list can be bulk-imported | ✅ | ✅ | ⬜ | *Evidence not found* | `OrganisationUserImport.php` · `:149-150` |
| **M4** | People can apply, and applications are approved/rejected | ✅ | ✅ | ⬜ | ⚠️ suite broken | `Application.php:92` · `ApproveApplication.php:31` · `:85,86` |
| **M5** | An approved applicant becomes a member with a lifecycle | ✅ | ⚠️ **implemented twice** (D-1/D-3) | ⬜ | ⚠️ **failing** (D-4/D-5) | `Member/Member.php:64` · `Models/Member.php:321,291` |
| **M6** | Members are assigned to committees with roles | ✅ | ✅ | ⬜ | ⚠️ **failing** (6 tests, D-5) | `AssignMemberToCommittee:42` · `committeeRoutes:58,61` |
| **M7** | Fees are recorded and affect member state | ✅ | ✅ — reaches Finance via outbox | ⬜ | ⚠️ `AD-006` open | `Fee.php:92` · `FeePaidHydrator:31` · `MemberFeeStateListener` |
| **M8** | Only eligible members may vote | ✅ | ⚠️ **five implementations, no named authority** | ⬜ | *Evidence not found* for an authority-level test | §4 eligibility row |
| **M9** | An eligibility decision can be explained | ✅ | ✅ `ParticipationEligibilityEvidence` + `EligibilitySnapshot` | ⬜ | *Evidence not found* | `Domain/Election/Security/Simplified/…` |

**Verified column: empty on every row.** Additionally, **the Automated-test column is degraded rather than absent** — this capability has tests, and ~21 of them fail before any change.

---

# Findings Table

| # | Finding | Type | Priority | Needs business decision | Needs code | Verified |
|---|---|---|---|---|---|---|
| **MB-1** | **A member is defined twice** — two `Member` aggregates, both live (27 vs 9 consumers) | **Architecture** | **High** | No | Yes | No |
| **MB-2** | **Member states are defined twice with different sets** — 8 values vs 4, and the 4-value type rejects the others as invalid | **Architecture** | **High** | **Maybe** — which states are real business states? | Yes | No |
| **MB-3** | **Two `TenantId` value objects**, already breaking tests | **Technical** | **High** | No | Yes | No |
| **MB-4** | **~21 unit tests fail before any change** — the capability's own quality signal is broken | **Technical** | **High** | No | Yes | No |
| **MB-5** | **Eligibility has five implementations and no named authority** — and eligibility is what the election depends on | **Product** | **High** | **Yes** — who owns eligibility? | Yes | No |
| **MB-6** | Two `MemberRegistered` event classes | **Architecture** | Medium | No | Yes | No |
| **MB-7** | Two event-registration mechanisms (`$listen` vs `boot()`) split by namespace | **Technical** | Medium | No | Yes | No |
| **MB-8** | Legacy and context event vocabularies coexist for the same business meaning | **Architecture** | Medium | No | Yes | No |
| **MB-9** | `AD-006` FeeTestFactory TenantId mismatch (open, owner Membership) | **Technical** | Low | No | Yes | No |

**Product: MB-5 only** — and it is the one that touches the customer's central expectation.
**Architecture: MB-1, MB-2, MB-6, MB-8** · **Technical: MB-3, MB-4, MB-7, MB-9.**

**Note the shape difference from the other two capabilities:** Organisation's findings were mostly *Product* (a feature that cannot run); Membership's are mostly *Architecture/Technical* (features that run, described inconsistently). Same method, different diagnosis — which is the point.

---

# Cross-capability patterns after three applications

| Pattern | Election | Organisation | Membership | Count | Verdict |
|---|---|---|---|---|---|
| Event dispatched with **no listeners**, consequence hard-coded in the controller | ✅ | ✅ | ❌ **refuted** — records, releases, listens properly | 2 / 3 | **NOT universal.** A legacy-code trait, not a repository trait |
| **A declared state that cannot be reached / is not authoritative** | ✅ `begin_setup` no route | ✅ governance `active`/`suspended` never written | ✅ **new form** — two `MemberStatus` sets, neither authoritative | **3 / 3** | **Recurs in every capability reviewed** |
| **The same concept defined twice** | ✅ two `ResultsPublished`; four context stores | ✅ two route-binding styles | ✅ 2× Member · 2× MemberRegistered · 2× MemberStatus · 2× TenantId | **3 / 3** | **Recurs in every capability reviewed** |

**Two patterns now have 3/3 evidence; one has been refuted at 2/3.**

Per `ES-006.1`, three independent observations is *repeated observation*, not a standard — and the **refutation is the most valuable row in this table**, because it shows the method discriminates instead of confirming whatever it looked for first. **Nothing is promoted.** The two 3/3 patterns are candidates for a future retrospective, not conclusions.

---

# Method assessment — application #3 of 3

**The method produced high-value findings without any of the five parked refinements.**

| Question | Answer |
|---|---|
| Did Phase 0 change the analysis? | **Yes** — *"I can explain each eligibility decision"* is what made MB-5 a **Product** finding rather than an architecture nit |
| Did the Business Rule Matrix earn its place? | **Yes** — it exposed a **degraded** test column (tests exist and fail), a state the four-column version could not express |
| Did the Product/Technical split earn its place? | **Yes** — it showed this capability's diagnosis differs in *kind* from Organisation's |
| Did the method discriminate? | **Yes** — it **refuted** its own leading hypothesis (pattern 1) |
| Were the parked refinements missed? | **C-2 (Capability Readiness) was mildly missed**; the other four were not needed. Recorded as evidence for the post-freeze decision, not acted on |

**The freeze held: no phase, template or artifact was added during this review.**

---

## Explicitly not done

no code changed · no refactoring · no ADR · no architecture proposed · no runtime verification · no backlog items created · no pattern promoted · **no method refinement applied**.

---

**Traceability:** `app/Contexts/Membership/Domain/Member/Member.php:64` · `Domain/Models/Member.php:291,321` · `Domain/Member/MemberStatus.php:9-19` · `Domain/ValueObjects/MemberStatus.php` · `Domain/ValueObjects/TenantId.php` vs `app/Contexts/Shared/Domain/ValueObjects/TenantId.php` · `Domain/Events/MemberRegistered.php` vs `Domain/Member/Events/MemberRegistered.php` · `Domain/Application/Application.php:92` · `Domain/Fee/Fee.php:92` · `Domain/Committee/{CommitteeConstitution.php:49, DomainEventsBuffer.php:12}` · `Application/Application/UseCases/{ApproveApplication.php:31, RejectApplication.php:31, ApproveMembershipApplication.php:77-79}` · `Application/Committee/{AssignMemberToCommittee.php:42, RemoveMemberFromCommittee.php:44, UseCases/CreateCommittee/CreateCommitteeHandler.php:34}` · `Infrastructure/Outbox/FeePaidHydrator.php:31` · `app/Providers/EventServiceProvider.php` (`$listen` + `boot()`) · `app/Services/VoterEligibilityService.php` · `app/Domain/Voting/{Service/EligibilityEvaluator.php, ValueObject/EligibilitySnapshot.php}` · `app/Domain/Election/Security/Simplified/ParticipationEligibilityEvidence.php` · `routes/organisations.php:63,85,86,111-113,144,145,149,150` · `routes/committee/committeeRoutes.php:34,38,42,44,48,50,52,54,58,61` · `docs/adr/ADR-0001-committee-read-write-separation.md` · `tests/Unit/Contexts/Membership/…` · `docs/implementation/backlog/BACKLOG.md` (`AD-006`, `L-7`)
