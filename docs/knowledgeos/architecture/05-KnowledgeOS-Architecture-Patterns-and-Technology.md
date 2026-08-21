# KnowledgeOS Architecture Patterns & Technology

> ⛔ **PROPOSED · NON-AUTHORITATIVE · REQUIRES ARCHITECTURE/GOVERNANCE REVIEW.**
>
> Part of the **Architecture Review Set** (see [00 — Index](00-KnowledgeOS-Architecture-Review-Index.md)).
> Consolidates the corpus's **architecture-pattern and technology** claims — patterns
> the corpus treats as established or proposes, and the technology layer kept
> **separate** from the domain/pattern layer (item 6). Every claim is level-tagged
> (`[DOMAIN]` / `[PATTERN]` / `[TECH]` / `[PRODUCT]`) and status-tagged
> (`ESTABLISHED` / `PROPOSED` / `REJECTED` / `OPEN`).

## 1 · Sources

| Source (renamed corpus) | Type | Content |
|---|---|---|
| `brainstorming/20260820-123902-kos-design-patterns.md` | analysis | Pattern catalogue serving the DDD boundaries; Hexagonal as already adopted; what the domain is NOT |
| `brainstorming/20260818-213516-lcom4-multi-language-binding.md` | analysis | LCOM4 (cohesion) metric explored with Python; multi-language binding concerns |
| `brainstorming/20260819-220924-spring-di-principle-for-kos.md` | architecture-proposal | Applying the Spring Boot DI / auto-configuration idea to KnowledgeOS: composition root, injection, framework decoupling |

**Cross-references:** `docs/knowledgeos/architecture/04-contract-neutrality-fact-model.puml` ·
`docs/knowledge_tranfer/20260816_1023_invariant_aggregate_matrix.md` — ⚠️ **T3 PKS
material**, comparison only · Review-Set [01](01-KnowledgeOS-Domain-and-Context-Architecture.md) §2.7.

## 2 · Architecture patterns — established vs proposed

### 2.1 Hexagonal / Ports & Adapters
- `[PATTERN][ESTABLISHED]` **Hexagonal Architecture is stated as already adopted**:
  "We already have Hexagonal Architecture"; adapters allow swapping PostgreSQL →
  Git/S3/Event Store **without changing the domain** — `…123902-kos-design-patterns`.
- `[PATTERN][ESTABLISHED]` The existing platform's structure is consistent with this
  claim: adapters for the toolset exist as platform components (Git, storage,
  model/provider adapters) — `…121929-business-translator`,
  `03-component-knowledgeos.puml`.
- `[PATTERN][PROPOSED]` Hexagonal is "compatible but not a defining consequence of
  DDD"; a bounded context need not be a microservice — `…205757-ddd-correction-verification-verdict`
  (see [01](01-KnowledgeOS-Domain-and-Context-Architecture.md) §2.7).
- `[PATTERN][PROPOSED]` The kernel/OS model reuses the pattern: kernel primitives
  (ports) with Git/Jira/SAP/AI-Model/Cloud adapters (drivers) — `_misc/…224159-linux-analogy`
  (see [02](02-KnowledgeOS-Kernel-and-Platform-Architecture.md) §2.3).

### 2.2 Dependency injection / composition root (the Spring idea)
- `[PATTERN][PROPOSED]` The Spring Boot DI/auto-configuration idea applied to
  KnowledgeOS: a **composition root** that wires capabilities by convention, with
  dependency injection separating capability definitions from their resolution —
  `…220924-spring-di-principle-for-kos`.
- `[PATTERN][PROPOSED]` Claim: the DI principle (inject dependencies, never
  construct/static-resolve them) is framework-independent — adopting the *principle*
  does not mean adopting Spring — `…220924`.
- `[TECH][PROPOSED]` Applying the **principle** vs adopting **Spring/Kotlin-JVM** is
  explicitly separated in the corpus: the JVM/Spring runtime appears only in the
  `_misc/` kernel recommendation (`_misc/…224159`), never as an adopted KnowledgeOS
  technology — ⚠️ see the preservation note in §4.

### 2.3 Cohesion metric (LCOM4) — what the corpus explores
- `[PATTERN][PROPOSED]` **LCOM4 (Lack of Cohesion of Methods) is explored as a
  mechanical cohesion metric** for the multi-language KnowledgeOS binding — a way to
  detect cohesive responsibility groups inside a module/binding — `…213516-lcom4-multi-language-binding`.
- `[PATTERN][PROPOSED]` Multi-language binding concern: KnowledgeOS pieces (kernel,
  runtime, intelligence, studio) may live in different languages; the **binding**
  between them must stay cohesive and measurable — `…213516`.
- `[TECH][PROPOSED]` The metric exploration uses Python for the analysis tooling —
  an **analysis-side** choice, not a platform-language decision — `…213516`.

### 2.4 What the domain is NOT (pattern boundary, ESTABLISHED as corpus position)
- `[PRODUCT][ESTABLISHED]` KnowledgeOS is **NOT** a "Knowledge Management Platform"
  (Confluence/SharePoint class) — `…204431` (see [01](01-KnowledgeOS-Domain-and-Context-Architecture.md) §2.1).
- `[PATTERN][ESTABLISHED]` The domain is **not generic CRUD over knowledge tables**:
  it governs creation, evolution, and authorized delivery of trusted Knowledge
  Products — `…123902`, `…205757`.

## 3 · Technology layer — kept separate from the pattern layer

> The four claim levels (item 6) require **technology** to be tagged distinctly from
> **architecture patterns** and **domain decisions**. This section collects every
> technology-level claim and marks its status.

- `[TECH][PROPOSED]` Rust for the kernel; Kotlin/Java + Spring Boot enterprise
  runtime; Python intelligence; TypeScript studio/UI — `_misc/…224159-linux-analogy`
  (a **recommendation in a brainstorm file**, not a decision).
- `[TECH][PROPOSED]` Gradle/Cargo for build/binding; microservices decomposition;
  plugin architecture — appear as **options in the discussion**, never adopted —
  `_misc/…224159`, `…213516`, `…123902`.
- `[TECH][PROPOSED]` Event sourcing and a Kafka-class broker — see
  [04](04-KnowledgeOS-Event-and-Integration-Architecture.md) §3.3 — options only.
- `[TECH][ESTABLISHED]` The existing platform is **PHP/Laravel with a TypeScript/Vue
  front-end** (PublicDigit stack), running the `.claude/platform/` components —
  stated as current fact — `…121929-business-translator`, project CLAUDE.md.
- ⚠️ **Preservation note:** Rust · Spring Boot · Gradle/Cargo · microservices ·
  plugin architecture · Event Sourcing · Kafka are **recommendations/hypotheses in the
  corpus, never adopted KnowledgeOS technology**. This document reproduces them as
  level-tagged evidence only.

## 4 · Contradictions & tensions (surfaced, not resolved)

| # | Tension | Where it appears |
|---|---|---|
| T1 | **Modular monolith vs microservices**: the pattern catalogue works within the existing monolithic platform (composition root, modules); the kernel/OS discussion floats microservice decomposition — compatible evolution or a fork is undecided | `…123902` vs `_misc/…224159` |
| T2 | **Rust kernel vs enterprise runtime**: the `_misc/` recommendation (Rust kernel, Spring runtime) conflicts with the PHP/Laravel current-state platform — a greenfield rewrite vs incremental evolution of the existing platform | `_misc/…224159` vs current state (§3) |
| T3 | **Hexagonal "already adopted" vs formal**: the corpus asserts Hexagonal as adopted, but no formal ADR records the adoption — the pattern is lived but under-recorded | `…123902` vs ADR/Decision record |
| T4 | **DI principle vs framework import**: adopt the DI/composition-root *principle* without adopting Spring — the corpus wants the first, but the kernel file pairs the principle with the Spring runtime | `…220924` vs `_misc/…224159` |
| T5 | **Pattern catalogue vs T3 invariant matrix**: KnowledgeOS pattern claims (T1) must not be conflated with the T3 PKS invariant-aggregate matrix (`…1023`) — different systems | `…123902` (T1) vs `knowledge_tranfer/20260816_1023_invariant_aggregate_matrix.md` (T3) |

## 5 · Open questions

- `[OPEN]` Whether Hexagonal/Ports & Adapters deserves a formal ADR (its current
  "adopted" status is asserted, not recorded as a decision) — `…123902`.
- `[OPEN]` How LCOM4-style cohesion measurement would bind multi-language components
  in practice — the corpus explores the metric, not the enforcement — `…213516`.
- `[OPEN]` Where the dependency-injection/composition-root boundary sits for
  AI-agent capabilities (agent-provided vs injected) — `…220924`.

## 6 · What is NOT decided here

No technology is adopted: Rust, Spring, Python, TypeScript, Gradle/Cargo,
microservices, plugin architecture, Event Sourcing, and Kafka all remain
**level-tagged hypotheses**. The existing PHP/Laravel + TypeScript platform remains
the current state. Patterns are classified (established-as-lived vs proposed), never
re-decided.

## Traceability

Corpus sources in §1 (renamed, provenance in `brainstorming/00_INDEX.md`) ·
cross-refs `04-contract-neutrality-fact-model.puml` ·
`docs/knowledge_tranfer/20260816_1023_invariant_aggregate_matrix.md` (T3,
comparison only) · Review-Set
[00](00-KnowledgeOS-Architecture-Review-Index.md) ·
[01](01-KnowledgeOS-Domain-and-Context-Architecture.md) ·
[02](02-KnowledgeOS-Kernel-and-Platform-Architecture.md) ·
[04](04-KnowledgeOS-Event-and-Integration-Architecture.md) ·
[07](07-KnowledgeOS-Target-Architecture-Review.md) · plan D-6/D-7 ·
corpus sort commit `9a57a7cb`.
