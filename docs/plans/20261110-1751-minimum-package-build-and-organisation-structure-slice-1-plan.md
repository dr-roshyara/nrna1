# Minimum package build plan, and slice 1: Organisation structure

| | |
|---|---|
| **Status** | **PROPOSED, awaiting Principal approval (EP-01).** Nothing is implemented until approved |
| **Date** | 2026-11-10 |
| **Story** | **PBDIGIT-342** (to be created on approval): "Organisation structure: units, levels and structural change sets" |
| **Authority for the design** | D-146, D-147 (scope); **D-156** (Organisation §49–§51 accepted), D-157 (Governance thin slice), D-158 (Newsletter and Forum); act `docs/publicdigit/reviews/2026-11-10-minimum-package-tactical-designs-acceptance-act.md` |
| **Design text** | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` §49 (structure), §50 (belonging), §51.3 (descent) |

## 1. Objective

- **Build the minimum package** (Membership, Newsletter, Forum; release 1) in small, separately approved slices.
- **Slice 1:** the foundation everything else needs: **an organisation's own units over time**, changed only by recorded decisions, answerable "as at a date, as known at a time".

## 2. Background (readiness review, EP-03, derived from the repository)

| Question | Answer | Source |
|---|---|---|
| **Business need** | Forums are per unit (D-152); newsletter audiences are per unit; committees attach to units; members are placed in units (D-147). **Nothing in the package works without units** | D-146, D-147, D-152 |
| **Owning context** | **Organisation** owns structure and unit belonging | D-106 (accepted), D-142 |
| **Other contexts affected** | Governance decides structural changes (Organisation records them, D-29); Membership coverage is read only for S-9 (closing the root) | D-29, CR-01 C2 |
| **Published-language interaction** | `StructureAsAt` (R-2, D-29) out; decision basis in (D-154 AuthorityCheck) | D-29, D-154 |
| **Canonical discovery** | **No Organisation context exists in code.** `app/Contexts/Membership/Domain/Organisational/` is Membership's ratified kernel (membership facts), **not units**. Legacy `committees` (with `geo_unit_id`), `committee_structures` and `users.residence_geo_unit_id` mix units, organs and address placement: **evidence only, not reused** (D-144 freeze; S-8, D-79) | repository survey 2026-11-10 |
| **Reuse** | `App\Contexts\Shared\Domain` (`DomainEvent`, `TenantId`, `ActorId`); **not** Membership's value objects (context isolation) | — |
| **Tests** | PHPUnit 11; suites Unit / Feature / Architecture (`tests/Architecture/*DomainPurityTest.php` pattern); `RefreshDatabase` on the **testing** database only | `phpunit.xml`; CLAUDE.md |

## 3. Build order for the minimum package (each slice approved separately)

| Slice | Content | Depends on |
|---|---|---|
| **1 (this plan)** | **Organisation structure:** tree, levels, units over time, structural change sets, `StructureAsAt` | — |
| 2 | **Organisation belonging:** placement by act, top-unit default (D-114), reduced descent (§51.3 without area rules), `MembersOfScopeAsAt`, `BelongingWithAncestorsAsAt` | 1; Membership C2 |
| 3 | **Governance thin slice core:** rules on file, Organ, Position (incl. editor and moderator), Resolution with evidence, **AuthorityCheck** (replaces slice 1–2 test doubles) | 1 |
| 4 | **Onboarding and registrar** (D-148): founding documents, recording committees and sittings; workspace screens | 3; Identity claim code |
| 5 | **Newsletter** (Communication) | 2, 3; counsel (D-151) |
| 6 | **Forum** (Deliberation) | 2, 3 |

**Parallel, other session:** Identity participant access (claim code), needed before members use the member area.

## 4. Slice 1 scope

### In

1. **New bounded context folder `app/Contexts/Organisation/`**, split into `Domain` (pure PHP), `Application` and `Infrastructure`.
2. **Domain, aggregate `Tree`** (the consistency boundary, §49.7):
   - levels with label history;
   - units with periods, a parent over time, successors;
   - tree version.
   - **Invariants S-0, S-1, S-2, S-3, S-4, S-7, S-9** checked **for every date from the effective date onwards**. S-5: one main tree only in release 1; S-8 holds by construction.
3. **Structural change set:**
   - **operations:** establish a unit, change its parent, end it (dissolved, divided, merged or absorbed, with a declared successor), add or retire a level;
   - **applied whole or not at all** (D-30);
   - **idempotent** on (decision, change-set number);
   - **inapplicable → nothing written, the refusal recorded and reported.**
4. **Non-structural edits** (display name) do not take the tree version.
5. **Basis on every record (S-7):** a `DecisionBasis` value (decision reference and `AuthorityCheck` answer) or a record correction referring to one.
6. **Ports (interfaces in Application), test doubles in slice 1:**
   - **`AuthorityCheckPort`** → `decide-structural-change(scope)` (D-154). Real implementation in slice 3.
   - **`MembershipCoveragePort`** → C2 `CoverageKnownAt`, used only when a change set would end the root (S-9). An adapter over the existing Membership query is used if available; otherwise the port is refused-safe, so **ending the root is refused**.
7. **Knowledge time:**
   - every write records its record time;
   - **`StructureAsAt(organisation, d, τ)`** answers from records known at τ;
   - corrections are new records, never edits (§49.8).
8. **Persistence:**
   - **new append-only tables**, organisation-scoped (`organisation_id`, tenant isolation as existing models);
   - optimistic **tree version**;
   - migrations **run only in the testing database by the tests**.
9. **Domain event `UnitEnded`** (consumed later by Governance D-145 and Deliberation). Published through the existing event mechanism; **no consumers in slice 1**.
10. **Developer guide** `developer_guide/organisation/` (`00_index.md`, `01_structure_slice.md`), per the Definition of Done.

### Out

- **Belonging and placement** (slice 2).
- **Wings and second trees** (S-5, later).
- **Coverage references to Reference Geography** (D-147).
- **Area rules** (§51; gated on an independent review, D-156).
- **Any UI or HTTP endpoint**, which comes with slice 4.
- **Any change to legacy `committees` / `committee_structures` / Geography code.**
- **Any data migration from legacy tables.**

## 5. Design decisions for the slice

| # | Decision | Reason |
|---|---|---|
| DD-1 | **Tree is the only aggregate** for structure; units are entities inside it | S-0 … S-3 span units (§49.7 [AC]) |
| DD-2 | **Append-only records** plus a derived "as at" view; **no update or delete of structural rows** | §49.8; history and τ queries |
| DD-3 | **Organisation does not judge competence**: it stores the `AuthorityCheck` answer as basis and refuses without `Authorised` | D-12, D-29, D-154 |
| DD-4 | **Own value objects** (`UnitId`, `LevelId`, `EffectiveDate`, `KnowledgeTime`, `DateInterval`) in Organisation's domain; no import from Membership | Context isolation (Rule 1) |
| DD-5 | **Ports refuse safely**: an unavailable port means the change set is refused, never assumed | D-73 (complete or refused) |
| DD-6 | **No address, residence or Geography field anywhere in the context** | S-8, D-79, D-147 |

## 6. Tests first (RED before GREEN)

**Unit (pure domain), `tests/Unit/Contexts/Organisation/Structure/`:**
1. Establishing the root and child units; S-0 refuses a second root on a date.
2. S-1 refuses a unit with two parents on a date; S-2 refuses a cycle; S-3 refuses a child at a higher level (and accepts a recorded exception).
3. S-4: a level in use cannot be retired.
4. **Later-dated changes:** a change set valid on its effective date but breaking an invariant on a later recorded date is inapplicable (§49.4 "every date from the effective date").
5. Change set atomicity: one bad operation → nothing applied.
6. Idempotency: the same (decision, number) twice → applied once.
7. Ending a unit with a successor; ending the root refused unless coverage is empty (S-9 via port double); port unavailable → refused.
8. S-7: no basis or a non-`Authorised` answer → refused.
9. `StructureAsAt(d, τ)`: before / after the effective date; before / after the record time; a correction visible only from its record time.

**Architecture, `tests/Architecture/OrganisationDomainPurityTest.php`:**
- the domain imports no `Illuminate\*`;
- it imports nothing from other contexts' domains;
- there are no address or Geography types.

**Feature / integration, `tests/Feature/Contexts/Organisation/`:**
- the Eloquent repository round-trip;
- a tree-version conflict → retry sees the other change;
- **tenant isolation with two organisations** (CLAUDE.md Rule 9);
- append-only: no update or delete issued on structural tables.

**Commands:**
- `php artisan test --filter=Organisation`;
- `php artisan test --testsuite=Architecture`.

**Never `migrate:fresh` / `migrate:refresh` on the development database.**

## 7. Task checklist

- [ ] Principal approves this plan (EP-01)
- [ ] Create `docs/publicdigit/backlog/PBDIGIT-342-organisation-structure-units.md`
- [ ] RED: unit tests 1–9 and the architecture test
- [ ] GREEN: domain (Tree, units, levels, change set, invariants, value objects)
- [ ] RED → GREEN: application command `ApplyStructuralChangeSet` (DTO), ports and doubles, query `StructureAsAt`
- [ ] RED → GREEN: persistence (migrations for testing, Eloquent repository, tree version, tenant isolation)
- [ ] VERIFY: full Organisation tests, Architecture suite, existing suites unchanged
- [ ] Developer guide `developer_guide/organisation/00_index.md`, `01_structure_slice.md`
- [ ] Session log, CONTEXT-publicdigit, this plan's progress
- [ ] One commit `(PBDIGIT-342)`; completion review (EP-02) presented to the Principal

## 8. Progress

- 2026-11-10: plan written; awaiting approval.

## 9. Risks

| Risk | Mitigation |
|---|---|
| Two "unit" models coexist (legacy `committees` with geo units, new Organisation units) | **Legacy untouched and frozen (D-144)**; no reads from it; classification is O-16, later |
| The "every later date" invariant check is costly on large trees | Trees are small and change rarely (§49.7 [AC]); a test with a realistic size (≈ 2 000 units) |
| The Membership C2 adapter is not callable from Organisation yet | Port refuses safely → ending the root is refused in slice 1; nothing else needs C2 |
| Scope creep into belonging or UI | Explicit "Out" list; anything found becomes a backlog item |
| Existing election flows depend on legacy committee code | Not touched in slice 1 |

## 10. Open questions

- **None for the Principal.** All design questions are decided (D-114 … D-158).
- **[TU]** Exact table names and the record-time source (database clock), settled in RED/GREEN and recorded in the developer guide.

## 11. Next actions

1. **Principal:** approve, amend or reject this plan.
2. **On approval:** create PBDIGIT-342 and start RED.

## Traceability

D-146, D-147, D-156 … D-158; model document §49 (S-0 … S-9, §49.7, §49.8), §50.7; D-12, D-29, D-30, D-73, D-79, D-106, D-142, D-144, D-154; previous plan `20261108-0303-organisational-baseline-to-tactical-design-roadmap-plan.md`.
