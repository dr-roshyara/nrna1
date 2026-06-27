# Package Structure & Naming Conventions v1.0 (FROZEN)

**Status:** 🧊 FROZEN · L3 playbook · the canonical layout + names for every greenfield-Core context. Reviewed/frozen **before** scaling implementation (chief-architect gate). Change only via ADR-T + review.
**Date:** 2026-06-27 · Consistent with the existing `app/Contexts/Governance` convention (concept-grouped Domain).

## Canonical package structure (per bounded context)
```
app/Contexts/<Context>/
  Domain/
    <Aggregate>/            # aggregate root + tightly-bound VOs + state enum + Exception/
      <Aggregate>.php       #   e.g. Challenge.php (final)
      <Vo>.php              #   e.g. ChallengeId.php, SubmittedContent.php (final readonly)
      <Aggregate>State.php  #   enum
      Exception/            #   e.g. IllegalChallengeTransition.php
    Events/                 # readonly domain events OWNED by this context (Catalog v1.0)
    Policies/               # domain policies (Invariant/Decision/...) — 50-06
    Repository/             # repository INTERFACES (ports) — pure PHP
    Service/                # domain services (pure, stateless)
    DomainEvent.php         # context-level marker interface
  Application/
    Command/                # command DTOs (VerbNoun)
    Handler/                # command handlers (one per command)
    Service/                # application services / coordinators (e.g. AdjudicationService)
    DTO/  Port/             # data transfer objects; outbound ports
  Infrastructure/
    Repositories/           # Eloquent repository IMPLEMENTATIONS (EloquentXRepository)
    Persistence/            # Eloquent models, mappers
    Projection/             # read-model projectors
    EventPublisher/         # transactional-outbox publisher
```
**Rule:** repository **interface** in `Domain/Repository/`; **implementation** in `Infrastructure/Repositories/` (CLAUDE.md Rule 1, ADR-T6). Reads that aren't aggregate loads go through read models, not repositories (CQRS-light).

## Naming conventions (FROZEN)
| Element | Convention | Example |
|---------|-----------|---------|
| Aggregate root | `Noun` | `Challenge`, `Determination`, `Vote` |
| Identifier VO | `NounId` | `ChallengeId`, `DeterminationId` |
| State enum | `NounState` | `ChallengeState` |
| Value object | `Noun` (final readonly) | `RaiserStandingRef`, `SubmittedContent` |
| Command | `VerbNoun` (imperative) | `RaiseChallenge`, `IssueDetermination`, `CastVote` |
| Command handler | `CommandNameHandler` | `RaiseChallengeHandler` |
| Domain event | `PastTense` (Catalog v1.0 only) | `ChallengeRaised`, `DeterminationIssued`, `VoteAccepted` |
| Policy | `NounPolicy` / `NounDecision` / `NounInvariant` | `ChallengeStandingPolicy`, `LegitimacyDecision`, `DeterminationFinalityInvariant` |
| Specification | `NounSpecification` | `AdmissibleChallengeSpecification` |
| Repository (interface) | `AggregateRepository` | `ChallengeRepository` |
| Repository (impl) | `EloquentAggregateRepository` | `EloquentChallengeRepository` |
| Factory | `AggregateFactory` | `ChallengeFactory` (only if construction is non-trivial) |
| Application service | `NounService` / `NounCoordinator` | `AdjudicationService` |
| Exception | descriptive, `final`, extends `DomainException` | `IllegalChallengeTransition` |

## Namespace conventions
```
app/Contexts/<Context>/Domain/*          → App\Contexts\<Context>\Domain\*
app/Contexts/<Context>/Application/*      → App\Contexts\<Context>\Application\*
app/Contexts/<Context>/Infrastructure/*   → App\Contexts\<Context>\Infrastructure\*
```
PSR-4 root `App\` → `app/` (composer.json). One class per file; namespace mirrors directory exactly (no drift).

## Dependency rules (Deptrac-translatable)
```
Allowed:
  Domain         → Domain only
  Application    → Domain
  Infrastructure → Application, Domain
Forbidden:
  Domain → Infrastructure | Laravel/Illuminate | Eloquent | Carbon | HTTP
  Application → Eloquent | Facades | HTTP
  any cross-context Domain → another context's Domain (collaborate via events only, TP-1)
```
These map 1:1 to `deptrac.yaml` layers/ruleset (ADR-T7) and the `GreenfieldCoreArchitectureTest`.

## Shared Kernel (ownership of cross-cutting code)
A **minimal** shared kernel under `app/Contexts/Shared/` holds concepts that are genuinely universal and stable:
| Belongs in Shared | Stays in a context |
|-------------------|--------------------|
| Clock interface + UTC impl · UUID generation · Hashing · `DomainEvent` base contract · Result/Either type · Money · TenantContext · time abstractions | aggregate-specific VOs, policies, events, repositories |
**Rule:** add to Shared only when a **second** context genuinely needs it (avoid premature shared coupling — architecture-pragmatism). A concept used by one context stays in that context.

## Migration rules (legacy → greenfield Core)
- **Existing code MUST NOT be copied.** Behavior is **reimplemented** from the frozen architecture.
- Legacy naming does **not** justify new naming — follow the conventions above.
- **Greenfield code is authoritative**; the strangler runs **aggregate-by-aggregate** (Evidence → Appointment → Voting last).
- No legacy Eloquent model is imported into a new Domain/Application layer.

## Evolution rules (governance for structural change)
| Change | Required approval |
|--------|-------------------|
| Minor addition (new class in an existing package) | none (just conform) |
| New package category (new folder type) | **ADR-T** |
| Moving package/aggregate ownership | **Architecture Review** |
| Changing a naming convention | **Constitution Review** |

## Conformance
- The `Challenge` files (committed) already follow this structure and naming → reference exemplar.
- `tests/Architecture/GreenfieldCoreArchitectureTest` enforces: event ownership (PastTense in `Domain/Events`, owned by context), events readonly, domain framework-purity, no Domain→Infrastructure import.

---
*Package Structure & Naming Conventions v1.0 — FROZEN. Concept-grouped Domain (consistent with Governance context); repository interface in Domain/Repository, impl in Infrastructure/Repositories; frozen naming table (Aggregate/Id/State/VO/Command/Handler/Event/Policy/Specification/Repository/Factory/Service/Exception). Challenge = reference exemplar.*
