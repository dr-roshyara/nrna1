---
source:
  original_name: "perpleixity_research_on_roles.md"
  original_path: "docs/knowledgeos/brainstorming/perpleixity_research_on_roles.md"
  detected_timestamp: "2026-08-19 09:24:49"
  timestamp_source: filesystem-mtime
  timestamp_confidence: medium
classification:
  theme: "03-evidence-assurance-governance"
  type: analysis
  note: "external research (Perplexity) validating the six-role decision framework against NIST/ISO/W3C/DDD"
status:
  authoritative: false
  proposed: false
---

# Executive Summary

External evidence supports the **decision framework** behind your existing platform more strongly than it supports any particular ownership assignment.

The strongest findings are:

1. Mature governance systems separate **responsibility, authority, execution, verification, and evidence** rather than treating them as one concept. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/ai/nist.ai.100-1.pdf)
2. Separation of duties is usually implemented through a combination of organizational assignment, access control, workflow constraints, independent assessment, and audit evidence—not by role labels alone. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/SpecialPublications/NIST.SP.800-53r5.pdf)
3. Independent verification means freedom from actual or perceived conflicts of interest. A platform can mechanically enforce some separation conditions, but it cannot prove complete independence from identity metadata alone. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/specialpublications/nist.sp.800-53r4.pdf)
4. Knowledge distribution is not equivalent to knowledge publication. Modern agent systems require explicit runtime context selection, authorization, provenance, and delivery verification. MCP’s security model illustrates both the value and incompleteness of protocol-level authorization: authorization is optional in implementations, and the protocol does not itself guarantee a complete task-level identity boundary. [media.defense](https://media.defense.gov/2026/Jun/02/2003943289/-1/-1/0/CSI_MCP_SECURITY.PDF)
5. Policy enforcement is distinct from policy definition and policy evaluation. Mature controls distinguish observe, assess, block, escalate, and audit.
6. Communication is commonly treated as a cross-cutting capability or delivery concern unless it has its own domain language, invariants, lifecycle, and independent reason to change.
7. DDD strongly supports your capability-first sequence. A business capability belongs to the problem space; a bounded context belongs to the solution space and bounds the applicability of a model. [pubs.opengroup](https://pubs.opengroup.org/architecture/o-aa-standard/DDD-strategic-patterns.html)
8. Provenance standards explicitly separate entities, activities, and agents. This supports your distinction between knowledge, execution, role, and evidence identity. [w3](https://www.w3.org/TR/prov-o/)
9. The most important challenge is that some of your capabilities may be **control patterns** rather than bounded contexts. C-5 and C-14, in particular, may cut across multiple contexts even if they are coherent capabilities.
10. The PO/ARB should decide ownership only after defining the capability’s invariant, authority, evidence, and enforcement boundary.

The research does not support changing the adopted six-role operating model. It does support keeping role, capability, bounded context, agent, service, and authority as distinct concepts.

# Research Method

This review prioritizes:

- standards and official frameworks;
- original research and formal models;
- established DDD sources;
- recognized professional bodies;
- current protocol and AI-security specifications.

The evidence base combines foundational and recent sources:

| Source type | Examples | Use |
|---|---|---|
| AI governance | NIST AI RMF and Generative AI Profile | Roles, oversight, lifecycle governance. |
| Cybersecurity controls | NIST SP 800-53 and SP 800-53A | Separation of duties, independent assessment, access enforcement. |
| Secure software | NIST SP 800-218 SSDF | Role assignment, development assurance, release integrity. |
| Software quality | ISO/IEC 25010:2023 | Verification, evaluation, and quality responsibility. |
| Provenance | W3C PROV-DM / PROV-O | Entity, activity, agent, and derivation identity. |
| Agent security | MCP authorization/security specifications and OWASP LLM guidance | Runtime access, tool authorization, prompt-injection boundaries. |
| DDD | Open Agile Architecture, Evans/Vernon materials | Capability, subdomain, bounded context, ubiquitous language. |
| Assurance | AICPA Trust Services Criteria | Segregation, monitoring, control activities, assurance. |

The findings below distinguish:
- **normative evidence**: what a standard or framework requires or recommends;
- **descriptive evidence**: how mature systems are commonly structured;
- **analytical interpretation**: implications for your open decisions.

# Six-Role Operating Models

## External pattern

Mature governance frameworks generally model responsibilities as a combination of:

```text
activities
→ responsibilities
→ authorities
→ controls
→ evidence
→ assurance
```

They do not normally assume that each role corresponds to:
- one software service;
- one bounded context;
- one agent;
- one team;
- or one business capability.

NIST’s AI RMF explicitly requires governance structures, role definitions, monitoring, and oversight across the AI lifecycle. Its Generative AI Profile emphasizes differentiated human and AI responsibilities and oversight functions. ISO/IEC 42001 similarly requires top management to assign and communicate responsibilities and authorities for relevant roles, including responsibility for conformity and performance reporting. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/ai/nist.ai.100-1.pdf)

This supports your separation:

```text
Operating role ≠ capability ≠ bounded context ≠ agent ≠ service
```

## Comparable role structures

### AI governance

Typical responsibilities include:

- leadership and risk ownership;
- system development;
- data and model management;
- validation and testing;
- monitoring;
- incident response;
- internal audit;
- human oversight.

NIST separates governance from mapping, measurement, and management, while also requiring role clarity across the lifecycle. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/ai/nist.ai.100-1.pdf)

### Secure software development

NIST SSDF organizes software security work around practices such as:
- preparing the organization;
- protecting software;
- producing well-secured software;
- responding to vulnerabilities.

It explicitly includes role and responsibility assignment rather than equating all activities with a single platform component. [csrc.nist](https://csrc.nist.gov/pubs/sp/800/218/final)

### Regulated assurance

AICPA Trust Services Criteria distinguish:
- control environment;
- risk assessment;
- control activities;
- logical access;
- monitoring;
- change management;
- assurance reporting.

This is a lifecycle/control model, not a one-role/one-service model. [aicpa-cima](https://www.aicpa-cima.com/resources/download/2017-trust-services-criteria-with-revised-points-of-focus-2022)

## Evidence supporting your six-role separation

The external pattern supports using roles as an **operating model** before assigning domain boundaries because:

- one activity can involve multiple roles;
- one role can participate in multiple capabilities;
- authority can differ from responsibility;
- independent verification requires deliberate separation;
- a role can be performed by a human, service, team, or external party;
- implementation and verification may be organizationally distinct but technically coordinated.

The evidence does not establish your exact six-role taxonomy as a standard model. It supports the structural principle behind it.

## Benefits of role-first modeling

Role-first modeling can:

- make workflow responsibilities explicit;
- establish separation-of-duties expectations;
- expose missing responsibilities;
- provide a stable operational vocabulary;
- avoid prematurely encoding organizational labels into software boundaries;
- clarify which actions need authority, evidence, or independent review.

## Risks

Role-first modeling becomes problematic when:

- each role is made into a service without capability evidence;
- each role receives its own database;
- role names become aggregate names;
- role assignment is mistaken for authorization;
- one role is assumed to own every capability it performs;
- agent personas are treated as domain boundaries;
- organizational changes force unnecessary architectural changes.

## Research finding

Your six-role model is **unusual in its explicit separation**, but not inconsistent with mature practice. Its defensibility depends on documenting it as:

```text
an operating and workflow model
```

rather than:

```text
a decomposition of the domain or platform.
```

# Separation of Duties / C-5

## External definition

NIST SP 800-53 defines separation of duties as identifying duties requiring separation and defining access authorizations to support that separation. It describes the purpose as reducing the risk of abuse of authorized privileges without collusion. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/SpecialPublications/NIST.SP.800-53r5.pdf)

It also gives examples involving separation between:
- development;
- system support;
- configuration management;
- quality assurance;
- testing;
- access control administration;
- audit.

NIST’s independent-assessor control requires assessors or assessment teams to be impartial and free from perceived or actual conflicts of interest involving system development, operation, management, or assessment effectiveness. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/specialpublications/nist.sp.800-53r4.pdf)

## What independence means operationally

Independence is not one property. External practice treats it as a combination of:

| Dimension | Question |
|---|---|
| Role independence | Is the verifier assigned a distinct responsibility? |
| Authority independence | Can the verifier reach a conclusion without producer approval? |
| Access independence | Can the producer alter the verification evidence or result? |
| Organizational independence | Is the verifier outside the producer’s reporting/control chain? |
| Financial independence | Does the verifier have incentives tied to acceptance? |
| Technical independence | Does the verifier use an independent environment or mechanism? |
| Temporal independence | Was verification performed after implementation and before acceptance? |
| Cognitive independence | Is the verifier free from confirmation pressure or prior commitment? |

A platform can evidence some of these dimensions. It cannot prove all of them automatically.

## Evidence levels

| Level | Description | Strength |
|---|---|---|
| Self-declaration | Producer states that verification is independent. | Weak. |
| Recorded assignment | Different workflow actors are recorded. | Better, but claims identity. |
| Access-enforced separation | Producer cannot approve or alter verification state. | Stronger. |
| Artifact separation | Verification uses immutable producer output and independent test results. | Strong. |
| Organizational separation | Verifier is structurally outside producer authority. | Strong for conflict reduction. |
| Independent assessment | External or impartial assessor evaluates controls/results. | Highest for assurance, but costly. |

NIST’s controls support the conclusion that role declarations alone are insufficient; access authorization and independent assessment are important mechanisms. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/SpecialPublications/NIST.SP.800-53r5.pdf)

## Can platforms prove producer ≠ verifier?

They can prove a limited proposition:

```text
The recorded producer identity differs from the recorded verifier identity.
```

They may also prove:

```text
The verifier lacked permission to modify the producer’s artifact.
The verification result was generated in a separate environment.
The verification action occurred after the artifact was fixed.
The evidence was immutable after publication.
```

They cannot prove, solely from system metadata:

```text
The individuals had no collaboration.
The verifier had no conflict of interest.
The verifier was cognitively unbiased.
The organizational relationship was independent.
The identities were not shared or compromised.
```

Therefore, C-5 should be understood as an **assurance capability with multiple evidence grades**, not a binary “independent/not independent” switch.

## Common architecture patterns

### Separation matrix

```text
producer × verifier × approver
```

The system rejects or flags prohibited combinations.

### Independent verification pipeline

```text
implementation artifact
  → immutable handoff
  → independent test/evaluation
  → verification result
  → acceptance authority
```

### Protected evidence store

The producer can create evidence but cannot mutate the verification result after submission.

### Two-person approval

High-risk transitions require two distinct authenticated actors.

### External assessment

An independent team or external party evaluates the platform or control.

## What remains unknown

- What independence level does each workflow action require?
- Is identity difference sufficient for low-risk work?
- Which actions require organization-level separation?
- Which evidence must be cryptographically bound?
- Does the verifier need independent infrastructure?
- Who adjudicates disputed independence?
- What is the acceptable fallback when independent personnel are unavailable?

## Evidence relevant to C-5

External evidence supports:
- explicit separation requirements;
- access-enforced separation;
- independent assessment;
- evidence of actual assignment and authorization.

It challenges:
- self-declaration as sufficient;
- a single automated “independence check” as proof;
- role-name difference as evidence of independence.

# Knowledge Distribution / C-10

## Definition

Knowledge distribution is the capability of making authorized, relevant, current, and provenance-preserving knowledge available to the process or agent that must use it.

It is broader than:
- documentation;
- storage;
- search;
- retrieval;
- context assembly;
- or agent memory.

A useful lifecycle is:

```text
knowledge created
  → governed
  → published
  → selected
  → retrieved
  → assembled
  → delivered
  → acknowledged / applied
  → monitored
```

## External architectural pattern

Modern agent systems increasingly distinguish a governed context layer from ordinary RAG. The relevant concerns include:
- source governance;
- permission-aware retrieval;
- context assembly;
- provenance;
- policy;
- evaluation;
- runtime delivery.

MCP demonstrates that a protocol can authorize access to protected resources, but authorization is optional at the implementation level and transport authorization does not automatically establish full task-level or organizational separation. [media.defense](https://media.defense.gov/2026/Jun/02/2003943289/-1/-1/0/CSI_MCP_SECURITY.PDF)

OWASP guidance emphasizes least privilege, privilege control, separation of external content from instructions, and trust boundaries between the model, external sources, and tools. [owasp](https://owasp.org/www-project-top-10-for-large-language-model-applications/assets/PDF/OWASP-Top-10-for-LLMs-v2025.pdf)

## Distinctions to preserve

| Concept | Meaning |
|---|---|
| Knowledge creation | Producing a candidate claim, decision, rule, or procedure. |
| Knowledge publication | Making an approved artifact available to consumers. |
| Knowledge selection | Choosing content based on task, scope, authority, and policy. |
| Knowledge retrieval | Finding candidate evidence or knowledge items. |
| Context assembly | Constructing the actual input provided to an agent/session. |
| Session bootstrap | Establishing initial instructions, constraints, and context. |
| Runtime verification | Checking that delivered context is valid, current, authorized, and complete. |
| Enforcement | Blocking action when required knowledge or policy is absent/violated. |

The common failure is:

```text
approved decision exists
but execution context does not contain it
```

This is a **distribution failure**, not merely a retrieval failure.

## Common patterns

### Policy/context injection

Approved rules are injected into the agent context or tool environment at session initialization.

### Governed context package

A versioned package contains:

```text
instructions
constraints
decisions
applicable rules
evidence references
provenance
expiry
policy version
```

### Runtime retrieval contract

Each request includes:
- actor;
- task;
- scope;
- authority;
- freshness;
- permissions;
- knowledge types.

### Context receipt

The system records:
- which knowledge was delivered;
- what was excluded;
- why it was selected;
- which version was used;
- whether the agent acknowledged or applied it.

### Reconciliation loop

When governed knowledge changes:

```text
knowledge changed
  → affected sessions identified
  → context invalidated
  → bootstrap / refresh requested
  → delivery confirmed
```

## Ownership patterns found externally

Commonly, the relevant responsibility is split among:

- knowledge or content governance;
- policy management;
- retrieval/context infrastructure;
- agent runtime;
- security/authorization;
- platform operations.

This directly challenges the assumption that a “Knowledge Engineer” necessarily owns C-10. External practice treats distribution as a cross-layer capability that may include several stewardship responsibilities.

## Risks

- publication without delivery;
- delivery without authorization;
- retrieval without applicability;
- stale context;
- hidden precedence conflicts;
- context truncation;
- tool calls bypassing policy context;
- prompt injection through untrusted sources;
- inability to prove what the agent received.

## What remains unknown

- Must delivery be guaranteed before execution?
- Is “received” sufficient, or must the agent demonstrate use?
- Which knowledge is mandatory versus advisory?
- What happens when required knowledge is unavailable?
- Who decides applicability?
- How are conflicting instructions resolved?
- What is the session’s context version?
- Does context invalidation halt active sessions?

## Evidence relevant to C-10

External evidence supports treating distribution as more than storage or search. It challenges:
- the assumption that RAG automatically delivers authoritative knowledge;
- the assumption that publication implies execution-time use;
- the assumption that one role necessarily owns the entire capability.

# Policy Enforcement / C-14

## Definition

Policy enforcement is the capability of causing a policy decision to affect behavior through a control point.

It differs from:

```text
policy definition
policy evaluation
policy monitoring
policy reporting
policy enforcement
```

## Distinction table

| Capability | Question |
|---|---|
| Policy definition | What behavior is required, prohibited, or permitted? |
| Policy evaluation | Does a subject/action satisfy the policy? |
| Observation | What happened? |
| Monitoring | Is behavior drifting or violating policy? |
| Enforcement | What happens because of the policy decision? |
| Admission control | Is an operation allowed to start or enter a state? |
| Runtime blocking | Is an active operation stopped or denied? |
| Escalation | Who must decide when the system cannot resolve the issue? |
| Audit | Can the decision and effect be reconstructed? |

## Mature policy architecture

A common model is:

```text
Policy authoring
  → policy repository
  → policy evaluation point
  → policy enforcement point
  → protected resource/action
  → audit and monitoring
```

Policy enforcement is often implemented with:
- admission controllers;
- access-control enforcement points;
- deployment gates;
- CI/CD gates;
- runtime authorization;
- configuration validation;
- database constraints;
- network policy;
- human approval gates.

The key distinction is that an enforcement capability must have a real **effect path**. A script that prints a warning is monitoring or advisory control, not enforcement.

## Observe/warn/block/halt/escalate

| Mode | Effect |
|---|---|
| Observe | Record condition. |
| Warn | Inform actor, continue. |
| Block | Prevent operation. |
| Halt | Stop active process/session. |
| Escalate | Transfer decision to authority. |
| Quarantine | Isolate result or artifact. |
| Compensate | Allow action but trigger corrective process. |

A system may support all modes, but each must be explicitly defined.

## Ownership patterns

Common patterns include:

- policy authors separate from enforcement infrastructure;
- security policy evaluated by a policy engine;
- runtime systems acting as enforcement points;
- governance bodies approving policies;
- audit or assurance functions evaluating control effectiveness;
- product teams consuming policies without owning the policy engine.

NIST SP 800-53 demonstrates this separation: duties are defined by organizational policy, access authorization supports enforcement, and independent assessors evaluate control effectiveness. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/SpecialPublications/NIST.SP.800-53r5.pdf)

## When C-14 is a distinct capability

Evidence for a distinct capability increases when policy enforcement has:

- its own decision model;
- multiple enforcement points;
- explicit deny/allow/escalate semantics;
- policy versioning;
- conflict resolution;
- decision provenance;
- override/exception handling;
- independent audit;
- failure-mode requirements;
- lifecycle and ownership distinct from policy authorship.

It may remain a stewardship concern when:
- enforcement is local to one domain;
- the rule has one enforcement point;
- no shared policy language exists;
- the behavior is a local aggregate invariant;
- there is no independent enforcement lifecycle.

## Risks

- policy author also controls enforcement;
- “advisory” checks described as enforcement;
- enforcement without fail-safe semantics;
- policy decisions without versioned evidence;
- bypassable gates;
- inconsistent enforcement across channels;
- unauthorized override;
- inability to distinguish policy violation from infrastructure failure.

## What remains unknown

- Is C-14 responsible for policy decision, effectuation, or both?
- Is enforcement synchronous or asynchronous?
- Which actions are hard-blocking?
- Who may override?
- What is the default on policy-engine failure?
- Is enforcement expected to work outside the platform?
- How is enforcement tested independently?
- Are enforcement outcomes part of the domain state or audit state?

## Evidence relevant to C-14

External evidence supports C-14 as a potentially coherent capability, but only if it includes the full path from policy decision to behavioral effect. It challenges treating every validation script or gate as an independent policy-enforcement capability.

# Communication / C-19

## External DDD test

DDD does not define a bounded context by noun category or organizational role. A bounded context is a boundary within which a particular model and language apply. [pubs.opengroup](https://pubs.opengroup.org/architecture/o-aa-standard/DDD-strategic-patterns.html)

Communication becomes a bounded context when it has:
- its own domain language;
- stable business invariants;
- independent lifecycle;
- independent ownership;
- distinct policies;
- meaningful relationships with other contexts;
- a reason to evolve separately.

## Common treatments

Communication is commonly modeled as:

### Cross-cutting concern

Used when communication is embedded in many workflows:
- notifications;
- status updates;
- audit messages;
- user-facing explanations.

### Delivery capability

Used when the domain creates messages and another capability renders, routes, and delivers them.

### Shared platform service

Used for generic:
- email;
- chat;
- webhook;
- template rendering;
- localization;
- channel adapters.

### Bounded context

Appropriate when communication itself is the business domain, such as:
- case management;
- regulated correspondence;
- publication workflow;
- customer communication consent;
- records of official notice;
- collaborative negotiation.

## Communication language and invariants

A real Communication context may own concepts such as:

```text
Message
Audience
Channel
Template
Consent
Delivery
Acknowledgement
Escalation
Publication
Retention
```

It may enforce:

```text
A regulated notice must use an approved template.
A message must not be sent without consent.
A delivery must be auditable.
A communication must be retained for a defined period.
```

If C-19 instead means “compose outputs for different audiences,” it may be a delivery capability rather than a bounded context.

## Risks

- treating all presentation as a domain;
- creating a Communication context because a Communication Engineer role exists;
- conflating message composition with authority;
- allowing communication formatting to change domain meaning;
- embedding channel-specific concerns into core knowledge;
- treating generated text as authoritative without provenance.

## What remains unknown

- Is communication merely output rendering?
- Does it own audience/consent/channel policy?
- Are communications legally or operationally binding?
- Does it own delivery state?
- Does it need independent retention and audit?
- Is composition deterministic, AI-assisted, or governed?
- Does it transform meaning or only express it?

## Evidence relevant to C-19

External DDD principles challenge creating a Communication bounded context solely because a communication role exists. A context becomes more defensible if communications have their own authority, lifecycle, invariants, and state.

# Operating Roles vs Governance Authority / OQ-5

## Core distinction

External standards consistently distinguish:

```text
responsibility
authority
accountability
execution
consultation
assurance
```

NIST AI RMF requires clear organizational roles and responsibilities, but also places ultimate risk decisions with leadership or authorized governance structures. ISO/IEC 42001 requires assigning both responsibility and authority, not merely naming roles. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/ai/nist.ai.100-1.pdf)

Therefore:

```text
Architecture Engineer
```

may be responsible for producing architecture work without having authority to:
- approve enterprise standards;
- accept residual risk;
- authorize production;
- certify conformance.

Similarly:

```text
Implementation Engineer
```

may execute work without authority to:
- define policy;
- approve exceptions;
- verify their own work independently;
- declare organizational conformance.

## Useful authority model

```text
Operating role
  performs activity

Decision right
  may make a specified decision

Authority grant
  permits decision for a defined scope/time

Governance body
  holds collective or delegated authority

Accountability
  answers for the outcome
```

These are related but not equivalent.

## Mature pattern

A RACI-like model is common, but it is insufficient by itself. It should be supplemented with:

```text
role
→ activity
→ decision right
→ scope
→ prerequisites
→ evidence
→ escalation path
→ revocation
```

A role can consume authority without becoming the authority model. This supports your OQ-5 framing.

## Evidence relevant to OQ-5

The external evidence strongly supports keeping:
- workflow roles;
- operational roles;
- organizational authority;
- approval rights;
- governance bodies

as distinct layers.

# Conformance Authority / SB-1

## External pattern

Mature assurance separates:

```text
specification
→ implementation
→ verification
→ acceptance
→ certification
```

NIST requires independent assessors or assessment teams to be impartial and free of conflicts involving development, operation, management, or determination of control effectiveness. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/specialpublications/nist.sp.800-53r4.pdf)

ISO/IEC 25010 positions quality evaluation as something that can involve developers, acquirers, QA staff, and independent evaluators, reinforcing that product creation and evaluation are distinct activities. [iso](https://www.iso.org/standard/78176.html)

## Why self-certification is problematic

If the same platform:
- defines the conformance rules;
- implements the controls;
- evaluates its own effectiveness;
- and declares conformance,

then the result is vulnerable to:
- confirmation bias;
- conflict of interest;
- incomplete test coverage;
- control bypass;
- circular evidence;
- inability to challenge the system’s own assumptions.

This does not make internal assurance useless. It means internal assurance and independent certification have different claims.

## Assurance levels

| Form | Claim |
|---|---|
| Internal check | The team ran its own prescribed checks. |
| Internal assurance | A designated assurance function assessed the evidence. |
| Independent verification | A party outside the implementation responsibility assessed it. |
| External certification | An authorized external body assessed conformance against a defined standard. |
| Governance acceptance | An authority accepted the residual risk or evidence. |

These are not interchangeable.

## Patterns preventing self-conformance

- independent verification module or team;
- separate evidence store;
- protected test results;
- immutable release artifacts;
- external audit;
- governance approval distinct from implementation;
- independent acceptance gate;
- authority separation;
- cryptographically bound evidence.

## What remains unknown

- Is KnowledgeOS a system under assessment, an assessment tool, or both?
- Who defines conformance?
- Who owns the acceptance decision?
- Can the platform issue evidence about itself?
- What external party, if any, must validate the platform?
- Are internal checks sufficient for the intended claim?

# Evidence Identity / SB-2

## External provenance model

W3C PROV defines provenance using three core concepts:

```text
Entity
Activity
Agent
```

An entity is an identifiable physical, digital, conceptual, or other thing. An activity processes, transforms, moves, consumes, or generates entities. An agent bears responsibility for an activity or entity. [w3](https://www.w3.org/TR/prov-o/)

This directly supports separating:

```text
semantic evidence identity
from
physical location
from
execution activity
from
responsible agent
```

## Semantic identity versus path

A file path is a locator, not a durable identity.

A mature evidence model should distinguish:

```text
Evidence ID
  stable logical identity

Evidence version
  immutable version of the evidence

Location
  current or historical retrieval address

Content hash
  integrity fingerprint

Source system
  repository, database, tool, or external system

Activity
  extraction, transformation, verification, publication

Agent
  human, team, process, or service responsible
```

W3C PROV treats entities as identifiable and represents different states as different entities, supporting immutable versioned evidence rather than mutable path-based identity. [w3](https://www.w3.org/TR/prov-dm/)

## Migration and restructuring

A durable evidence record should survive:

- file move;
- repository split;
- branch migration;
- rename;
- export/import;
- storage migration;
- format conversion.

Use:

```text
evidence_id
source_system
source_locator
source_version
content_hash
derived_from
generated_by
attributed_to
```

The old location remains historical provenance. The new location becomes a locator for the same or a derived entity, depending on whether content changed.

## Risks of path coupling

- broken citations;
- false loss of evidence;
- inability to distinguish replacement from relocation;
- invalidated audits after repository restructuring;
- ambiguous provenance after copies;
- inability to reproduce historical context.

## What remains unknown

- Is evidence immutable or mutable?
- Does a new file path represent the same entity or a new version?
- Who can invalidate evidence?
- Are hashes enough for semantic equivalence?
- How long must historical locators remain resolvable?
- Is evidence content itself sensitive?

# DDD Capability vs Bounded Context

## Foundational distinction

The Open Agile Architecture material gives a particularly clear separation:

- **business capabilities** belong to the problem space;
- **subdomains** delimit domain applicability;
- **bounded contexts** delimit the applicability of domain models in the solution space. [pubs.opengroup](https://pubs.opengroup.org/architecture/o-aa-standard/DDD-strategic-patterns.html)

A capability describes what the organization can do. A bounded context describes where a model, language, and consistency boundary apply.

This directly supports your sequence:

```text
capability
→ ownership
→ bounded context / stewardship
→ role
→ agent
→ service / technology
```

## Capability

A capability is an ability the organization possesses to achieve a goal.

Examples:

```text
assess conformance
distribute governed knowledge
enforce policy
compose communication
```

Capabilities are technology-independent and organization-independent in their basic form.

## Bounded context

A bounded context exists when a particular domain model has:
- bounded meaning;
- a ubiquitous language;
- explicit invariants;
- ownership;
- integration relationships;
- a reason to evolve independently.

The Open Agile Architecture standard explicitly notes that bounded contexts are not modules and that they bound the applicability of a model. Fowler likewise emphasizes that different contexts can use different models for concepts that share a name. [pubs.opengroup](https://pubs.opengroup.org/architecture/o-aa-standard/DDD-strategic-patterns.html)

## Stewardship

Stewardship is appropriate when a capability needs:
- an accountable owner;
- standards;
- policy;
- monitoring;
- coordination;

but does not yet have:
- an independent model;
- transactional invariants;
- lifecycle state;
- independent data ownership;
- separate integration language.

A capability may therefore be stewarded across contexts without becoming one context.

## When a capability becomes a bounded context

Evidence increases when the capability has:

1. its own ubiquitous language;
2. stable concepts and invariants;
3. independent lifecycle;
4. independently governed state;
5. distinct decision rights;
6. multiple consumers;
7. independent change pressure;
8. cross-context relationships;
9. an explicit data/provenance model;
10. a clear failure and escalation model.

## Anti-pattern: role-to-context mapping

```text
Governance Engineer
  → Governance bounded context

Communication Engineer
  → Communication bounded context

Knowledge Engineer
  → Knowledge bounded context
```

This may be valid eventually, but it is not evidence by itself.

The role could be:
- a workflow responsibility;
- a stewardship assignment;
- a cross-context concern;
- an operating persona;
- or an authority role.

## Anti-pattern: service-to-capability mapping

```text
PolicyService
  → Policy Enforcement capability
```

A service may implement one piece of a capability. Conversely, one capability may require:
- policy definition;
- evaluation;
- enforcement;
- audit;
- escalation;
- evidence.

Technology topology is not capability evidence.

# Cross-Cutting Findings

## 1. Responsibility is not ownership

A person or role may perform an activity without owning:
- the domain model;
- the capability;
- the authority;
- the data;
- the enforcement mechanism.

This is central to OQ-5 and the six-role model.

## 2. Authority must be explicit

External standards are more precise than generic RACI models. They distinguish assignment from authority. The architecture should ask:

```text
Who performs?
Who decides?
Who approves?
Who may override?
Who is accountable?
Who independently verifies?
```

## 3. Independence is graded

Independence is not a Boolean property. It ranges from:
- declared;
- recorded;
- access-constrained;
- technically isolated;
- organizationally separate;
- externally assessed.

C-5 should preserve that gradation.

## 4. Distribution is a control path

Knowledge distribution should be treated as a path:

```text
approved knowledge
  → selected knowledge
  → delivered context
  → execution
  → observed use
```

A repository or RAG index is not evidence that a session received or used a decision.

## 5. Enforcement requires a behavioral effect

A validation report is not enforcement. Enforcement requires:
- a decision point;
- a protected operation;
- a defined effect;
- a failure mode;
- an override model;
- auditability.

## 6. Provenance requires multiple identities

W3C PROV supports the model:

```text
semantic entity
+ activity
+ agent
+ derivation
+ location
```

A file path alone is inadequate for governed evidence. [w3](https://www.w3.org/TR/prov-o/)

## 7. Controls may be cross-cutting

C-5 and C-14 may operate across several contexts. That does not make them architecturally weak. It may mean they are:
- cross-context capabilities;
- shared policies;
- control-plane functions;
- or governance mechanisms.

The question is whether they need one model and one authority, not whether they touch many modules.

## 8. Read-model and control-plane distinctions matter

Knowledge distribution, audit, and policy enforcement may each have:
- authoritative domain state;
- decision services;
- delivery projections;
- enforcement points;
- audit projections.

Avoid assigning one noun to the whole architecture.

# Challenges to Current Thinking

## Challenge 1: capability-first may still overstate ownership

The sequence is sound, but a capability does not automatically have one owner. Some capabilities are inherently distributed.

For example:

```text
Knowledge Distribution
```

may span:
- governance;
- retrieval;
- context assembly;
- identity;
- session runtime;
- audit.

The open question may be **stewardship model**, not simply ownership.

## Challenge 2: “independent verification” may be too binary

External standards define impartiality and conflict avoidance, not perfect independence. A system that reports “independent = true” based only on different actor IDs would overclaim.

C-5 should distinguish:
- actor distinction;
- authorization separation;
- evidence isolation;
- organizational independence;
- assessment impartiality.

## Challenge 3: policy enforcement may be a control pattern

C-14 may not need a single bounded context if enforcement is implemented at:
- command boundaries;
- admission points;
- runtime tool calls;
- deployment gates;
- repository controls;
- workflow transitions.

A coherent capability can still be implemented by several enforcement points.

## Challenge 4: knowledge publication may be insufficient

The current design should avoid assuming:

```text
published = delivered = applied
```

These are different states requiring different evidence.

## Challenge 5: communication may be presentation, not domain

The Communication Engineer role does not establish a Communication context. Communication becomes a bounded context only when messages, audiences, consent, delivery, retention, or official publication have independent domain meaning.

## Challenge 6: platform conformance is not self-proving

If the platform defines, executes, measures, and certifies its own conformance, its assurance claim is structurally limited. Internal evidence can be valuable, but the claim must be labeled accordingly.

## Challenge 7: provenance identity must not be implementation-shaped

Physical paths, session names, role labels, and filenames are not durable semantic identities. Provenance should model entities, activities, agents, versions, and derivations.

# Evidence Relevant to Each Open Decision

## C-5 — Separation Attestation

### External definition

Capability to establish and communicate whether producer and verifier responsibilities are sufficiently separated for a given assurance claim.

### Common treatment

- separation-of-duties policy;
- access-control enforcement;
- independent assessment;
- immutable evidence;
- approval workflows;
- audit reporting.

### Ownership patterns observed externally

- governance/control function;
- security or compliance function;
- independent assurance function;
- quality organization;
- external assessor.

### Bounded-context patterns

A separate context is more plausible if C-5 owns:
- attestation records;
- independence criteria;
- evidence grades;
- exception handling;
- dispute lifecycle;
- assurance levels.

It may remain cross-cutting if it only evaluates workflow relationships at various points.

### Stewardship patterns

- governance stewardship defines independence policy;
- workflow stewardship records assignments;
- security stewardship enforces access;
- assurance stewardship evaluates evidence.

### Risks

- self-declaration treated as proof;
- actor ID mismatch treated as independence;
- automated checks overclaiming organizational independence;
- verifier unable to alter implementation evidence but still subject to approval pressure.

### Evidence relevant to decision

NIST supports separation documentation, access authorization, and independent assessors. AICPA criteria support segregation of incompatible duties and alternative controls where full separation is impractical. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/SpecialPublications/NIST.SP.800-53r5.pdf)

### Unknowns

- assurance level per action;
- required evidence grade;
- whether attestation blocks or reports;
- who adjudicates exceptions;
- whether external assessment is required.

## C-10 — Knowledge Distribution

### External definition

The controlled delivery of applicable, authorized, current, and traceable knowledge to an execution context.

### Common treatment

- governed context layer;
- knowledge registry;
- policy injection;
- retrieval/context assembly;
- agent bootstrap;
- runtime authorization;
- context receipts;
- invalidation/reconciliation.

### Ownership patterns observed externally

- knowledge governance;
- policy management;
- platform/runtime;
- security/identity;
- agent orchestration;
- retrieval infrastructure.

### Bounded-context patterns

A separate context is more plausible if C-10 owns:
- context package lifecycle;
- applicability and precedence;
- delivery guarantees;
- acknowledgment;
- invalidation;
- distribution audit;
- session context versions.

### Stewardship patterns

- knowledge stewardship governs content;
- runtime stewardship delivers content;
- security stewardship authorizes access;
- session stewardship records receipt.

### Risks

- knowledge published but never delivered;
- authorized content retrieved but inapplicable;
- stale or conflicting context;
- prompt injection through untrusted material;
- no proof of what the session received.

### Evidence relevant to decision

MCP’s authorization specification requires servers that implement authorization to validate access tokens and reject tokens not intended for them, but authorization remains optional for implementations. This demonstrates the distinction between protocol access and complete governed knowledge distribution. [modelcontextprotocol](https://modelcontextprotocol.io/specification/2025-06-18/basic/authorization)

### Unknowns

- what constitutes successful delivery;
- mandatory knowledge classes;
- session invalidation semantics;
- whether agents must acknowledge or prove use;
- precedence when context sources conflict.

## C-14 — Policy Enforcement

### External definition

The capability to cause a policy decision to control, constrain, halt, or escalate a protected action.

### Common treatment

- policy administration;
- policy decision point;
- policy enforcement point;
- admission control;
- runtime guardrail;
- audit and monitoring.

### Ownership patterns observed externally

- security/control plane;
- governance policy function;
- platform runtime;
- deployment infrastructure;
- application domain.

### Bounded-context patterns

A separate context is more plausible if C-14 owns:
- policy lifecycle;
- decision semantics;
- enforcement modes;
- override/exception;
- enforcement evidence;
- cross-channel consistency.

### Stewardship patterns

A governance steward may own policy meaning while individual contexts own local enforcement.

### Risks

- warnings mislabeled as enforcement;
- policy authors control enforcement outcomes;
- enforcement bypass through alternate paths;
- unknown policy result treated as allow;
- no override or fail-safe model.

### Evidence relevant to decision

NIST separates duties, access authorization, assessment, and monitoring rather than collapsing them into one control. OWASP emphasizes least privilege and trust boundaries around LLM extensions and backend systems. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/SpecialPublications/NIST.SP.800-53r5.pdf)

### Unknowns

- enforcement strength;
- synchronous versus asynchronous;
- failure default;
- override authority;
- coverage across interfaces.

## C-19 — Communication Composition

### External definition

Potentially either:
- a delivery capability that transforms governed content for an audience;
- or a communication domain that owns messages, audiences, channels, consent, delivery, and retention.

### Common treatment

- shared notification service;
- presentation/delivery layer;
- publication workflow;
- regulated correspondence context;
- message/audience lifecycle.

### Ownership patterns observed externally

- product/application;
- communications function;
- platform delivery;
- records/legal/compliance;
- customer-support or case-management domain.

### Bounded-context patterns

More plausible when communication has:
- independent message lifecycle;
- audience and consent rules;
- channel policy;
- retention;
- delivery state;
- formal acknowledgment;
- legal/operational effect.

### Stewardship patterns

Composition may be stewarded by a communication function while meaning remains owned by the originating domain.

### Risks

- communication changes domain meaning;
- generated wording becomes treated as authority;
- formatting logic hides policy;
- one communication service becomes a catch-all context.

### Unknowns

- whether composition changes meaning;
- whether messages are binding;
- whether communication owns delivery;
- whether composition has independent invariants.

# Research Gaps

The strongest remaining gaps are not generic literature gaps. They are **decision-specific evidence gaps**.

## C-5 gaps

- Define assurance levels for each governed action.
- Specify acceptable evidence for each level.
- Determine whether different identities are authenticated, bound, or merely declared.
- Test whether the platform can prevent producer modification of verification evidence.
- Define organizational independence outside the platform.

## C-10 gaps

- Define delivery success.
- Determine whether a session must acknowledge mandatory knowledge.
- Measure how often approved decisions are absent from execution context.
- Define context version and invalidation semantics.
- Test whether permission and applicability are enforced before context assembly.

## C-14 gaps

- Inventory current controls and classify each as observe, warn, block, halt, escalate, or quarantine.
- Identify protected operations and bypass paths.
- Measure enforcement coverage.
- Define behavior when policy evaluation is unavailable or ambiguous.
- Separate policy authorship, evaluation, enforcement, and audit responsibilities.

## C-19 gaps

- Identify communication-specific invariants.
- Determine whether messages are domain facts, projections, or operational notifications.
- Establish whether composition changes semantic meaning.
- Identify independent lifecycle and retention requirements.
- Determine whether communication has authority over content or only expression.

# Synthesis for PO/ARB

The external evidence supports the following decision framework:

```text
Capability identified
  → domain meaning defined
  → invariants identified
  → authority identified
  → evidence identified
  → enforcement effect identified
  → stewardship/ownership options compared
  → bounded-context threshold evaluated
```

For each capability, PO/ARB should ask:

1. What business outcome does the capability produce?
2. What decision or state does it own?
3. What must always be true?
4. What evidence proves that it occurred?
5. Which authority may approve, override, or revoke it?
6. Does it have its own language and lifecycle?
7. Does it need its own authoritative state?
8. Can it remain a cross-context policy or stewardship responsibility?
9. What happens when it cannot decide?
10. What is observable, what is advisory, and what is enforceable?

## Overall external verdict

| Capability | External pattern suggests |
|---|---|
| C-5 Separation Attestation | Often cross-cutting control/assurance capability; separate context only if it owns attestation lifecycle and assurance semantics. |
| C-10 Knowledge Distribution | Cross-layer governed delivery capability; may justify a context if it owns context-package lifecycle, delivery guarantees, and invalidation. |
| C-14 Policy Enforcement | Distinct capability when it owns the policy-to-effect path; otherwise may be distributed across enforcement points. |
| C-19 Communication Composition | Usually delivery/cross-cutting unless messages, audiences, consent, delivery, and retention form an independent domain. |

These are descriptions of external patterns, not ownership recommendations for your architecture.

# Source Bibliography

## Standards and official frameworks

1. **NIST AI Risk Management Framework 1.0.** NIST AI 100-1. Defines Govern, Map, Measure, and Manage functions and requires roles, responsibilities, oversight, and risk accountability. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/ai/nist.ai.100-1.pdf)
2. **NIST AI RMF Generative AI Profile.** NIST AI 600-1. Adds lifecycle-specific role differentiation, oversight, and monitoring for generative AI. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/ai/NIST.AI.600-1.pdf)
3. **NIST SP 800-53 Rev. 5.** Security and Privacy Controls. AC-5 defines separation of duties; CA-2(1) addresses independent assessors. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/SpecialPublications/NIST.SP.800-53r5.pdf)
4. **NIST SP 800-53A Rev. 5.** Assessment procedures. Defines independent assessors as impartial and free from actual or perceived conflicts. [nvlpubs.nist](https://nvlpubs.nist.gov/nistpubs/SpecialPublications/NIST.SP.800-53Ar5.pdf)
5. **NIST SP 800-218 SSDF v1.1.** Secure Software Development Framework. Defines secure-development practices and role/responsibility considerations. [csrc.nist](https://csrc.nist.gov/pubs/sp/800/218/final)
6. **ISO/IEC 42001.** AI management systems. Requires assignment and communication of relevant responsibilities and authorities, including conformity and performance reporting. [standards.iteh](https://standards.iteh.ai/catalog/standards/cen/adc675e8-4669-4965-b4c1-c8f724832217/en-iso-iec-42001-2026)
7. **ISO/IEC 25010:2023.** Software and systems quality model. Provides a framework for specification, evaluation, QA, and independent evaluation. [iso](https://www.iso.org/standard/78176.html)
8. **AICPA Trust Services Criteria.** Provides criteria for security, availability, processing integrity, confidentiality, privacy, segregation of duties, control activities, and monitoring. [aicpa-cima](https://www.aicpa-cima.com/resources/download/2017-trust-services-criteria-with-revised-points-of-focus-2022)
9. **W3C PROV-DM.** Provenance Data Model. Defines identifiable entities, activities, agents, and provenance relationships. [w3](https://www.w3.org/TR/prov-dm/)
10. **W3C PROV-O.** Provenance Ontology. Provides a formal ontology for entities, activities, agents, attribution, generation, and derivation. [w3](https://www.w3.org/TR/prov-o/)
11. **Model Context Protocol Authorization Specification.** Defines OAuth-based authorization for protected MCP servers, token audience validation, and access-control responsibilities, while making authorization optional for implementations. [modelcontextprotocol](https://modelcontextprotocol.io/specification/2025-06-18/basic/authorization)
12. **OWASP Top 10 for Large Language Model Applications.** Covers prompt injection, privilege control, least privilege, trust boundaries, and secure extension access. [owasp](https://owasp.org/www-project-top-10-for-large-language-model-applications/assets/PDF/OWASP-Top-10-for-LLMs-v2025.pdf)

## DDD and architecture sources

13. **Open Agile Architecture — DDD Strategic Patterns.** The Open Group. Distinguishes business capabilities in the problem space from bounded contexts and models in the solution space. [pubs.opengroup](https://pubs.opengroup.org/architecture/o-aa-standard/DDD-strategic-patterns.html)
14. **Martin Fowler, “BoundedContext.”** Explains bounded contexts as separate models with explicit relationships and recognizes that shared concepts may have different meanings across contexts. [martinfowler](https://martinfowler.com/bliki/BoundedContext.html)
15. **Vaughn Vernon, Implementing Domain-Driven Design.** Defines bounded contexts as boundaries within which a domain model and ubiquitous language apply. [ptgmedia.pearsoncmg](https://ptgmedia.pearsoncmg.com/images/9780321834577/samplepages/0321834577.pdf)
16. **Eric Evans, Domain-Driven Design.** Foundational source for bounded contexts, ubiquitous language, strategic design, and model boundaries.

## Analytical conclusion

The external research validates the architectural discipline you are applying:

```text
role ≠ capability
capability ≠ bounded context
responsibility ≠ authority
publication ≠ distribution
verification ≠ self-declaration
policy definition ≠ enforcement
file path ≠ evidence identity
audit record ≠ provenance proof
agent persona ≠ domain boundary
```

The principal challenge is not to abandon the capability-first model. It is to avoid forcing every capability into a single bounded context when external practice often treats assurance, distribution, enforcement, and communication as **cross-context control or delivery capabilities**.

The most useful next PO/ARB input is therefore not “which role owns this?” but:

> What authoritative decision, invariant, evidence record, and enforcement effect does this capability produce—and does that justify an independent domain model?