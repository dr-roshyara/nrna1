---
source:
  original_name: "Yes, **the idea behind Spring Boot Depen.md"
  original_path: "docs/knowledgeos/brainstorming/Yes, **the idea behind Spring Boot Depen.md"
  detected_timestamp: "2026-08-19 22:09:24"
  timestamp_source: filesystem-mtime
  timestamp_confidence: medium
classification:
  theme: "05-patterns-technology"
  type: architecture-proposal
  note: "adopt dependency-inversion principle, NOT Spring's technical implementation"
status:
  authoritative: false
  proposed: false
---

Yes, **the idea behind Spring Boot Dependency Injection (DI) can be very valuable in KnowledgeOS**, but we should not copy Spring's technical implementation directly. We should extract the **architectural principle** and apply it with DDD + Hexagonal Architecture.

The principle is:

> **Objects should receive their dependencies from an external composition mechanism instead of creating them internally.**

For KnowledgeOS, this is extremely relevant because KnowledgeOS will have many replaceable capabilities:

* AI providers
* vector databases
* graph databases
* repositories
* evidence storage
* policy engines
* authorization systems
* connectors

---

# 1. Where DI fits in the KnowledgeOS architecture

Recall the DDD architecture:

```
                 KnowledgeOS

        Core Domain

 Knowledge Product
 Governance
 Evidence


        Supporting

 Execution
 Semantic
 Delivery
 Intelligence
 Platform
```

Dependency Injection belongs **outside the domain core**.

The rule:

```
Domain Core
     |
     | depends on interfaces
     |
     v

Ports

     |
     |
Adapters receive injected implementations
```

---

# 2. Example: Evidence Storage

Without DI (bad):

```java
class EvidenceService {

    private EvidenceRepository repository;

    public EvidenceService() {
        this.repository = new PostgreSQLRepository();
    }
}
```

Problem:

The domain now knows:

```
PostgreSQL exists
```

You cannot easily change:

```
PostgreSQL
    |
    v
Neo4j
    |
    v
S3
    |
    v
Blockchain ledger
```

---

With DI:

```java
class EvidenceService {

    private final EvidenceRepository repository;


    EvidenceService(EvidenceRepository repository) {
        this.repository = repository;
    }
}
```

The domain only knows:

```
EvidenceRepository
```

not:

```
PostgreSQL
```

---

# 3. KnowledgeOS equivalent of Spring Beans

Spring has:

```
@Bean
@Component
@Service
```

KnowledgeOS could have:

## Capability Providers

Example:

```yaml
knowledgeos:
  providers:

    evidence:
      provider: git-backed-evidence-store

    semantic:
      provider: neo4j

    ai:
      provider: openai

    policy:
      provider: opa
```

The runtime creates the system.

---

# 4. KnowledgeOS Composition Root

This fits perfectly with your existing AI Engineering Platform idea.

You already have:

```
Composition Root
Session Manager
Knowledge Manager
Workflow Engine
Verification Engine
```

The Composition Root is exactly where DI belongs.

Architecture:

```
                 KnowledgeOS Runtime


              Composition Root

                     |
        --------------------------------

        |              |              |

 Evidence       Semantic        Intelligence
 Provider       Provider        Provider


        |
        |
        v

      Domain Ports
```

---

# 5. Example: Knowledge Governance

The Governance Context defines a port:

```java
interface AuthorityResolver {

    Authority resolve(KnowledgeProduct product);

}
```

The domain uses:

```java
class GovernanceService {


    private final AuthorityResolver resolver;


    GovernanceService(
        AuthorityResolver resolver
    ){
        this.resolver = resolver;
    }


}
```

Now different organizations can provide:

Implementation A:

```java
ArchitectureBoardAuthorityResolver
```

Implementation B:

```java
DAOAuthorityResolver
```

Implementation C:

```java
RegulatoryAuthorityResolver
```

The domain does not change.

---

# 6. AI Provider Injection

This is where DI becomes very powerful.

KnowledgeOS should not depend on:

```
OpenAI
Claude
Gemini
Local Llama
```

Instead:

Port:

```java
interface ReasoningEngine {

    Answer reason(Context context);

}
```

Adapters:

```
OpenAIReasoningAdapter

ClaudeReasoningAdapter

LocalLLMAdapter
```

Configuration:

```yaml
ai:
  reasoning-engine: claude
```

Runtime injects:

```
ClaudeReasoningAdapter
```

---

# 7. Evidence Qualification Pipeline with DI

Your new Evidence Boundary idea becomes:

```
Execution Context

        |
        v

Evidence Qualification Boundary

        |
        v

Evidence Context
```

The qualification process can be injected.

Example:

Port:

```java
interface EvidenceQualifier {

    QualificationResult evaluate(
        EvidenceCandidate candidate
    );

}
```

Implementations:

```
SecurityEvidenceQualifier

ArchitectureEvidenceQualifier

ComplianceEvidenceQualifier

AIGeneratedEvidenceQualifier
```

The runtime composes them.

---

# 8. But avoid Spring's biggest mistake

Spring allows this:

```java
@Autowired
Everything everything;
```

This creates hidden dependencies.

KnowledgeOS should avoid that.

DDD rule:

Dependencies must be explicit.

Good:

```java
KnowledgeProductService(
    KnowledgeRepository repository,
    EvidenceVerifier verifier,
    PolicyEvaluator policy
)
```

Bad:

```java
KnowledgeProductService(ApplicationContext context)
```

The domain should not know the container.

---

# 9. KnowledgeOS Dependency Direction

The final rule:

```
             Infrastructure


                 |
                 |
                 v


             Adapters


                 |
                 |
                 v


              Ports


                 |
                 |
                 v


           Domain Core


```

Never:

```
Domain
 |
 v
Spring
 |
 v
Database
```

---

# 10. How it fits with your previous architecture

Updated view:

```
                         AI Agents
                             |
                             v

                  Knowledge Delivery Context


                             |
                             v


                 KnowledgeOS Runtime

                 +----------------+
                 | Composition    |
                 | Root / DI     |
                 +----------------+

                    |
      --------------------------------

      |              |              |

 Evidence       Semantic       AI
 Providers      Providers      Providers


                    |
                    v


          ----------------------
          |                    |
          v                    v

 Knowledge Governance    Knowledge Product

 Context                 Context

          |
          |
          v

 Knowledge Evidence

```

---

# My recommendation

Add a formal architecture decision:

## ADR-KOS-003: Adopt Dependency Injection and Composition Root Pattern

### Decision

KnowledgeOS will use Dependency Injection principles to:

* isolate domain logic from infrastructure
* allow replaceable providers
* support multiple AI/model/storage implementations
* keep bounded contexts independent

### Rules

1. Domain depends only on ports.
2. Adapters implement ports.
3. Composition Root assembles runtime.
4. Infrastructure dependencies never leak into domain.
5. Configuration selects implementations.

---

This is actually a very natural extension of your existing architecture:

* DDD gives the boundaries.
* Hexagonal Architecture gives the ports.
* Spring-style DI gives the composition mechanism.
* KnowledgeOS Runtime becomes the enterprise composition root.

So yes: **Spring Boot DI is not just applicable; it is one of the missing mechanisms needed to make KnowledgeOS a true platform architecture.**
###
I agree with the **architectural direction**, with a few DDD governance refinements before this becomes an ADR.

The important distinction:

> **Spring DI is not the architecture. Dependency inversion + explicit composition is the architecture. Spring is only one implementation mechanism.**

For KnowledgeOS this fits very naturally, especially after the recent durability/evidence boundary decisions.

However, I would **not immediately create ADR-KOS-003** yet. I would first register this as a **future architecture exploration / candidate decision**, similar to EKS-05, because several concepts touch existing boundaries.

---

# My Architecture Review

## ✅ What is correct

The strongest point is:

```
Domain Core
      |
      v
     Ports
      |
      v
   Adapters
```

This aligns with:

* DDD bounded contexts
* Hexagonal Architecture
* Dependency Inversion Principle
* KnowledgeOS governance separation

The key rule is:

> The domain owns the interface. Infrastructure owns the implementation.

That is exactly what prevents:

```
Knowledge Governance
        |
        v
 PostgreSQLRepository
```

or:

```
Knowledge Evidence
        |
        v
 OpenAI API
```

from leaking into the core.

---

# Important correction: DI belongs in the Composition Root, not "the Runtime"

The proposal says:

> KnowledgeOS Runtime becomes the enterprise composition root.

I would refine this.

The architecture should be:

```
                 KnowledgeOS Platform


                 Composition Root
                       |
                       |
        --------------------------------
        |              |               |
        v              v               v

 Governance       Evidence       Intelligence

 Context          Context        Context


        |
        |
        v

       Ports


        |
        |
        v

      Adapters
```

The runtime **executes**.

The composition root **creates the dependency graph**.

This is an important DDD distinction.

---

# The biggest value for KnowledgeOS

The durability architecture introduced:

```
Execution Context
        |
        v
Evidence Boundary
        |
        v
Governance Evidence
```

DI completes the other side:

```
How do we replace capabilities without changing the domain?
```

Together:

## Evidence boundary

answers:

> What becomes trusted knowledge?

## Dependency boundary

answers:

> How is capability supplied?

---

# Example: Authority Resolver

This is the best example.

Current idea:

```
Knowledge Governance Context

AuthorityResolver
```

Excellent.

But the implementation should not be:

```
GovernanceService

    creates

ArchitectureBoardResolver()
```

because then:

```
Governance Context
        |
        v
Architecture Board implementation
```

becomes coupled.

Instead:

```
Governance Context

interface AuthorityResolver

        ^
        |
        |
Architecture Adapter

ArchitectureBoardResolver
```

The domain says:

> "I need authority resolution."

It does not say:

> "I need an Architecture Board."

---

# Very important: DI and Governance Authority

There is one danger.

A normal software DI container answers:

> Which implementation should I call?

KnowledgeOS also needs:

> Which authority is allowed to decide?

These are different.

Example:

```
Dependency Injection:

AuthorityResolver = DefaultResolver


Governance:

Who owns authority?
ARB
```

The container cannot decide governance ownership.

Therefore:

```
DI ≠ Authority Resolution
```

DI assembles capability.

Governance establishes legitimacy.

---

# Recommended KnowledgeOS Pattern

I would define three layers.

## Layer 1 — Domain Ports

Owned by bounded contexts.

Example:

```
KnowledgeEvidenceContext

port:

EvidenceStore

EvidenceVerifier

EvidenceClassifier
```

---

## Layer 2 — Capability Adapters

Technical implementations.

Example:

```
GitEvidenceStore

S3EvidenceStore

Neo4jSemanticStore

OpenAIReasoningAdapter
```

---

## Layer 3 — Platform Composition Root

Creates the system.

Example:

```
KnowledgeOSCompositionRoot


bind:

EvidenceStore
        ->
GitEvidenceStore


ReasoningEngine
        ->
ClaudeAdapter


PolicyEngine
        ->
OPAAdapter
```

---

# I would add one missing concept

The proposal has:

```
Capability Providers
```

I would introduce:

## Capability Binding

Because "provider" sounds like infrastructure.

Example:

```yaml
knowledgeos:

  bindings:

    evidence-store:
      implementation: git-evidence-store

    reasoning-engine:
      implementation: claude

    policy-engine:
      implementation: opa
```

Why?

Because the important architectural act is not:

> provider exists

It is:

> capability is bound to implementation.

---

# DDD Context Ownership

I would not put DI under Platform Administration.

I would model:

```
KnowledgeOS Supporting Capability

Platform Composition Context
```

Responsibilities:

* dependency graph
* configuration
* environment binding
* adapter selection

Non-responsibilities:

* business rules
* governance decisions
* evidence qualification

---

# Proposed future architecture entry

I would register:

```
EKS-06 — KnowledgeOS Capability Composition Architecture
Status: Future Architecture Exploration
```

Question:

> How should KnowledgeOS compose replaceable capabilities while preserving bounded context independence and governance authority boundaries?

---

## Exploration questions

### Q1

Where does the composition root belong?

Candidates:

* Platform Administration Context
* Dedicated Platform Composition Context
* Deployment layer

---

### Q2

What is the relationship between:

```
Capability Binding
```

and:

```
Governance Authority
```

---

### Q3

Which dependencies are allowed into Core Contexts?

Example:

Allowed:

```
EvidenceContext
     |
     v
EvidenceStore Port
```

Forbidden:

```
EvidenceContext
     |
     v
Spring Container
```

---

### Q4

How are capability changes governed?

Example:

Changing:

```
Claude
   |
   v
OpenAI
```

is technical.

Changing:

```
Evidence Authority Model
```

is governance.

They must not use the same approval path.

---

# My final assessment

The proposal is architecturally strong.

It is the natural next layer after:

```
DDD Boundaries
        +
Hexagonal Architecture
        +
Evidence Governance
        +
Authority Resolution
```

The final KnowledgeOS architecture stack becomes:

```
                 KnowledgeOS


        Governance Authority Layer

                  |
                  |

        Knowledge Evidence Layer

                  |
                  |

        Domain Contexts

                  |
                  |

             Ports

                  |
                  |

        Capability Adapters

                  |
                  |

        Composition Root

                  |
                  |

        Infrastructure
```

The one correction I would insist on:

> **Dependency Injection assembles capabilities. It does not create authority.**

With that invariant, this becomes a very strong candidate for the future KnowledgeOS v3 architecture. I would register it as an exploration item first, not freeze it as ADR-KOS-003 yet.

##
# Research verdict

The Spring Boot DI principle is **architecturally sound and strongly validated** for KnowledgeOS, but it should be adopted as a **composition-root pattern with explicit constructor-style injection**, not as a Spring-style container with field injection and hidden dependencies. The surrounding research on runtime AI governance, policy enforcement, and modular monoliths strongly supports the direction you described. [arxiv](https://arxiv.org/abs/2603.16586)

## 1. Core principle: validated

Your core statement is correct:

> Objects should receive their dependencies from an external composition mechanism instead of creating them internally.

This is the essence of dependency inversion and dependency injection. It is widely recommended in DDD and hexagonal architecture literature, and it is a standard pattern for isolating domain logic from infrastructure. [smithery](https://smithery.ai/skills/neversight/clean-ddd-hexagonal)

The principle is especially relevant for KnowledgeOS because:

- AI providers, vector/graph databases, policy engines, and connectors are **replaceable infrastructure**, not domain invariants.
- Different organizations will need different implementations for evidence storage, authority resolution, and AI reasoning.
- The domain must remain testable without requiring a full runtime stack.

That matches the hexagonal rule: **dependencies point inward; adapters implement ports; wiring happens at a single composition root.** [smithery](https://smithery.ai/skills/neversight/clean-ddd-hexagonal)

## 2. Where DI belongs: validated

Your placement is correct:

> Dependency Injection belongs outside the domain core.

DDD and hexagonal architecture guidance consistently says:

- Domain depends only on **ports** (interfaces/traits/protocols).
- Adapters implement those ports.
- A **composition root** wires concrete adapters to ports at startup. [elpic.medium](https://elpic.medium.com/hexagonal-architecture-in-python-wiring-adapters-dependency-injection-and-the-application-layer-1f2f83910deb)

Your example:

```text
Domain Core
    |
    | depends on interfaces
    v
Ports
    |
    v
Adapters receive injected implementations
```

is exactly the standard pattern.

## 3. KnowledgeOS providers = capability adapters

Your idea of **capability providers** is a good KnowledgeOS-specific framing:

```yaml
knowledgeos:
  providers:
    evidence: git-backed-evidence-store
    semantic: neo4j
    ai: claude
    policy: opa
```

This is consistent with:

- modular monolith wiring, where modules declare capabilities and the application module composes them, [stackpractices](https://stackpractices.com/guides/complete-guide-modular-monolith/)
- runtime governance for AI agents, where policy engines, identity, and tool-call authorization are externalized and configured per deployment, [opensource.microsoft](https://opensource.microsoft.com/blog/2026/04/02/introducing-the-agent-governance-toolkit-open-source-runtime-security-for-ai-agents/)
- hybrid RAG+KG architectures, where retrieval, graph, and policy components are composed as separate adapters. [arunbaby](https://www.arunbaby.com/ai-agents/0051-knowledge-graphs-for-agents/)

You should treat these as **named capability contracts**, not as generic “beans.”

## 4. Composition root: strongly validated

Your composition-root concept is correct and well supported:

> The Composition Root is exactly where DI belongs.

Research and practice emphasize:

- a single place where layers are composed, [dev](https://dev.to/remojansen/from-monolith-to-microservices-without-changing-one-line-of-code-thanks-to-the-power-of-inversion-57l6)
- the application module as the composition root, [wingedsheep](https://wingedsheep.com/building-a-modular-monolith/)
- wiring all dependencies in one place, typically `main` or a dedicated module, [smithery](https://smithery.ai/skills/davincible/rust-architecture-patterns)

For KnowledgeOS, the **KnowledgeOS Runtime** should be that root. It should:

- instantiate adapters,
- bind them to ports,
- configure policy engines,
- establish identity and authorization boundaries,
- expose delivery interfaces.

This is also where runtime governance belongs: intercepting agent actions, enforcing policy, and logging decisions. [arxiv](https://arxiv.org/abs/2603.16586)

## 5. Evidence and governance examples: validated

Your examples are aligned with best practice:

- `EvidenceService` depends on `EvidenceRepository`, not on `PostgreSQLRepository`.
- `GovernanceService` depends on `AuthorityResolver`, not on a specific implementation.
- AI reasoning depends on a `ReasoningEngine` port, not on a specific provider.

This matches:

- constructor injection recommendations, [onenoughtone](https://www.onenoughtone.com/learning-path/system-design-lld/learning/hexagonal-architecture/4)
- anti-pattern warnings against service locators and container injection, [docs.spryker](https://docs.spryker.com/docs/dg/dev/architecture/dependency-injection/best-practices.html)
- DDD guidance that domain objects should not know the container. [smithery](https://smithery.ai/skills/neversight/clean-ddd-hexagonal)

Your evidence qualification pipeline is also consistent with runtime governance patterns:

```text
Port: EvidenceQualifier
Implementations: Security, Architecture, Compliance, AI-generated qualifiers
```

This is analogous to policy engines that evaluate each agent action against multiple rules before execution. [opensource.microsoft](https://opensource.microsoft.com/blog/2026/04/02/introducing-the-agent-governance-toolkit-open-source-runtime-security-for-ai-agents/)

## 6. Anti-patterns to avoid: validated

Your warning against Spring’s worst habits is correct:

> Avoid `@Autowired Everything everything;`

Research and practice consistently warn against:

- field injection, which hides dependencies and makes testing harder, [dev](https://dev.to/olivia_craft/claudemd-for-spring-boot-13-rules-that-make-ai-write-production-ready-java-web-applications-59j7)
- service locator patterns and injecting the entire container, [docs.spryker](https://docs.spryker.com/docs/dg/dev/architecture/dependency-injection/best-practices.html)
- constructor over-injection and excessive parameters, [antigravity](https://antigravity.codes/agent-skills/backend/spring-boot-dependency-injection)
- circular dependencies and implicit wiring. [docs.spryker](https://docs.spryker.com/docs/dg/dev/architecture/dependency-injection/best-practices.html)

Your proposed rule:

> Dependencies must be explicit.

is exactly the recommended practice.

## 7. Dependency direction: validated

Your diagram:

```text
Infrastructure
    |
    v
Adapters
    |
    v
Ports
    |
    v
Domain Core
```

matches the standard hexagonal rule:

- dependencies point inward,
- outer layers depend on inner layers, never the reverse,
- domain defines interfaces; adapters implement them. [smithery](https://smithery.ai/skills/neversight/clean-ddd-hexagonal)

This is also consistent with modular monolith guidance, where modules cannot import each other’s internals and only the composition root knows about implementations. [stackpractices](https://stackpractices.com/guides/complete-guide-modular-monolith/)

## 8. Integration with runtime AI governance

Your DI approach is not just a technical convenience. It is the **enabling mechanism for runtime governance**.

Recent research on AI agent governance emphasizes:

- policy enforcement at the moment an agent acts, not just before or after, [arxiv](https://arxiv.org/abs/2603.16586)
- external policy engines that intercept tool calls and can allow, deny, modify, or escalate actions, [opensource.microsoft](https://opensource.microsoft.com/blog/2026/04/02/introducing-the-agent-governance-toolkit-open-source-runtime-security-for-ai-agents/)
- identity, audit, and chain-of-custody as first-class runtime concerns. [opensource.microsoft](https://opensource.microsoft.com/blog/2026/04/02/introducing-the-agent-governance-toolkit-open-source-runtime-security-for-ai-agents/)

Your DI model supports this by:

- injecting policy engines as capabilities,
- allowing different authorization and evidence-qualification implementations,
- enabling the runtime to intercept and govern every agent action.

This is stronger than a static “governance document.” It is **governance-in-the-loop**. [ijaibdcms](https://ijaibdcms.org/index.php/ijaibdcms/article/view/547)

## 9. Knowledge Graph and ontology governance

Your approach also aligns with knowledge-graph governance patterns:

- hybrid RAG+KG architectures use KGs for deterministic constraints (ownership, dependencies, policy) and RAG for explanations, [arunbaby](https://www.arunbaby.com/ai-agents/0051-knowledge-graphs-for-agents/)
- ontologies and schemas should be defined before extraction, with provenance for every triple, [arunbaby](https://www.arunbaby.com/ai-agents/0051-knowledge-graphs-for-agents/)
- agents should query KGs through typed tool contracts, not raw query languages. [arunbaby](https://www.arunbaby.com/ai-agents/0051-knowledge-graphs-for-agents/)

Your DI model supports this by:

- treating the semantic context as a capability with a port,
- allowing different graph/ontology implementations,
- keeping the domain independent of specific graph technologies.

## 10. ADR recommendation: refined

Your proposed ADR is directionally correct. I would refine it slightly to emphasize composition root and explicit injection:

> **ADR-KOS-003: Adopt Composition Root and Explicit Dependency Injection**
>
> **Decision**
> KnowledgeOS will use a composition-root pattern with explicit constructor-style dependency injection to:
> - isolate domain logic from infrastructure,
> - allow replaceable capability providers,
> - support multiple AI/model/storage implementations,
> - keep bounded contexts independent,
> - enable runtime governance and policy enforcement.
>
> **Rules**
> - Domain depends only on ports (interfaces/protocols).
> - Adapters implement ports and depend on infrastructure.
> - All wiring occurs in a single composition root (KnowledgeOS Runtime).
> - Infrastructure dependencies never leak into the domain.
> - Configuration selects implementations; dependencies are explicit and required.
> - Field injection, service locators, and container injection are prohibited.

This is consistent with both DDD/hexagonal guidance and runtime AI governance patterns. [arxiv](https://arxiv.org/abs/2603.16586)

## Final assessment

| Area | Assessment |
|---|---|
| Core DI principle | ✅ Strongly validated |
| Placement outside domain | ✅ Correct |
| Composition root | ✅ Strongly validated |
| Capability providers | ✅ Good KnowledgeOS framing |
| Evidence/governance examples | ✅ Aligned with best practice |
| Anti-pattern warnings | ✅ Correct |
| Dependency direction | ✅ Correct |
| Integration with runtime governance | ✅ Strongly supported |
| Knowledge graph/ontology governance | ✅ Consistent |
| ADR proposal | ✅ Recommended with minor refinement |

The research confirms that your DI approach is not only applicable but **necessary** for a trustworthy, governed KnowledgeOS. It enables replaceable capabilities, clean domain isolation, and runtime policy enforcement—exactly the properties that distinguish a governed engineering knowledge platform from a documentation system. [arxiv](https://arxiv.org/abs/2603.16586)