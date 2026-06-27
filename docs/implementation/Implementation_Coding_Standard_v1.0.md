# Implementation Coding Standard v1.0 (FROZEN)

**Status:** 🧊 FROZEN · L3 playbook · coding-level DDD conventions (the *how-to-write-it* handbook). Complements the Implementation Architecture Constitution (governance) and Package Structure & Naming (layout). Change only via ADR-T + review.
**Date:** 2026-06-27 · Applies to `app/Contexts/*`. Enforced by PHPUnit arch tests + PHPStan max + Deptrac.

## Aggregates
- `final` class; **private constructor**; create via named static factory (`raise()`, `cast()`) that returns a **valid** object.
- Expose **behavior**, not state — no public getters that leak mutable internals; no public setters.
- Enforce invariants **inside** the aggregate; forbidden transitions **throw + no mutation** (illegal-transition policy).
- Record events internally; release via `pullEvents(): list<DomainEvent>` (pull-and-clear).
- **No** framework, Eloquent, repository, facade, `Carbon`, or `now()` inside. Time is a **parameter** (clock authority).
- Hold other aggregates only **by id/ref** (TP-1); never instantiate another aggregate (TP-2).

## Value objects
- `final readonly`; validate in constructor (throw `InvalidArgumentException` on invalid).
- Private constructor + `fromString()`/named factory; provide `toString()`/`equals()` as needed.
- Immutable: "change" returns a new instance. No identity unless it's an `Id` VO.

## Domain events
- `final readonly`, implement the context `DomainEvent` marker; **PastTense** name from the Catalog only.
- Carry domain fields + `DateTimeImmutable $occurredAt`; **no transport envelope** (added at outbox), **no behavior/logic methods**, **no voter↔vote linkage** (Q7).

## Policies (50-06)
- One responsibility; classified Invariant/Decision/Authorization/Validation/Calculation.
- **Calculation policies are pure** (no I/O, no clock) → replay-safe. Authorization lives at the application boundary, not inside the aggregate.

## Repositories
- Interface (`Domain/Repository`) speaks the domain language; accepts/returns **whole aggregates**, never child rows.
- Implementation (`Infrastructure/Repositories`) maps to Eloquent; **never** leaks Eloquent models into Domain/Application.
- One repository per aggregate root; **never** persist another aggregate or a projection.

## Application services / handlers
- Constructor injection only (no facades/`app()`); depend on **interfaces** (ports), not Eloquent.
- One command → one handler; accept a **DTO/command**, never a raw array.
- Coordinators (e.g. `AdjudicationService`) orchestrate **request-not-create**: load aggregates via repos, invoke behavior, persist within **one aggregate's** transaction + outbox.

## Domain services
- Pure, stateless, no framework; used only when logic doesn't belong to a single aggregate.

## Exceptions (CLAUDE.md Rule 8)
- Domain → `DomainException` subclasses (user-visible message). Application → `ApplicationException`. Infrastructure → `RuntimeException` (logged, 500). `final`, descriptive names.

## Constructors / immutability / serialization
- No logic in event/VO constructors beyond validation. Prefer immutability everywhere in Domain.
- Serialization for events/persistence lives in **Infrastructure mappers** — never `JsonSerializable` on domain objects, never ORM attributes in Domain.

## Dependency injection
- Domain **never** performs DI. Application receives dependencies via **constructor**. Infrastructure composes object graphs (providers).
- **Forbidden:** service locator, `app()`, `resolve()`, static container access, facades in Domain/Application.

## Transaction ownership
- **Application services own transactions.** Aggregates never start/commit a transaction. Repositories never own transaction boundaries. Infrastructure executes the mechanics. One transaction = one aggregate + its outbox row (ADR-T1/T3).

## Concurrency
- `AggregateVersion` is **mandatory**; lost updates must **fail** (`ConcurrencyConflict`) — no silent overwrite. Retry policy lives in **Application** (retry on fresh state). Domain stays deterministic (no clock/random).

## Mapping
| Boundary | Where |
|----------|-------|
| HTTP ↔ DTO | Application |
| DTO ↔ Domain | Application |
| Domain ↔ Persistence | Infrastructure (mapper) |
| Domain ↔ JSON (event payload) | Infrastructure |
Mapping logic never lives in an aggregate or VO.

## PHP language conventions
`declare(strict_types=1)` in every file · typed properties only · return types mandatory · `readonly` where possible · `match` over `switch` · `enum` over class constants · never `mixed` · avoid nullable unless the domain requires it.

## Architecture-drift prevention & document precedence
When this document conflicts with a higher-level one, **the higher-level wins**. **Never "fix" architecture through code — raise an ADR-T.**
```
Precedence (highest first):
1 Project/Strategic Constitution (immutable principles)
2 Architecture Release 1.0 (+ BDR v1.1)
3 ADR-T decisions
4 Implementation Architecture Constitution v1.0
5 Implementation Coding Standard v1.0 (this)
6 Package Structure & Naming Conventions v1.0
7 Greenfield Core Playbook
```

## AI implementation contract
**AI may:** implement, refactor, improve tests, propose ADR-T candidates (with evidence).
**AI must NOT, without ADR approval:** invent events or aggregates · change invariants · rename ubiquitous language · modify any frozen architecture decision · introduce voter↔vote linkage. When unsure, **ask**; verify against current code before asserting.

## Tests (per the Constitution TDD cycle)
- Domain tests extend `PHPUnit\Framework\TestCase` (no Laravel, no DB). Inject fixed `DateTimeImmutable`.
- Cover: happy path, every forbidden transition (assert state unchanged), VO validation, event emission.
- Then: architecture fitness tests, PHPStan max, Infection mutation, review gates → merge.

---
*Implementation Coding Standard v1.0 — FROZEN. Coding-level rules for aggregates (final, private ctor, factory, behavior-not-state, throw+no-mutation, pull-events, time-injected), VOs (final readonly, validate-in-ctor), events (readonly, PastTense, no logic, no linkage), policies (pure calculation), repositories (interface in Domain, impl in Infra, whole-aggregates), application services (DI, request-not-create), exceptions by layer, immutability/serialization-in-infra, domain tests no-DB. Complements the Constitution + Package Structure.*
